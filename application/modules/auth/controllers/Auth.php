<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends MY_Controller {

	private $twitterconnection;
	
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->library(array('ion_auth','form_validation', 'grocery_CRUD'));
		$this->load->helper(array('language'));

		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

		$this->lang->load('auth');		
	}

	// redirect if needed, otherwise display the user list
	function index($param = '', $param2 = '')
	{

		if (!$this->ion_auth->logged_in())
		{
			$this->session->set_flashdata('message', get_languageword('please_login_to_continue'));	
			redirect('auth/login', 'refresh');
		}
		elseif ($this->ion_auth->is_tutor())
		{
			redirect('tutor/index', 'refresh');
		}
		elseif ($this->ion_auth->is_student())
		{
			redirect('student/index', 'refresh');
		}
		elseif ($this->ion_auth->is_institute() && $param != 3)
		{
			//Allow Institutes to add their Tutors
			redirect('institute/index', 'refresh');

		}		
		else
		{

			$this->load->library('grocery_CRUD_extended');
			$crud = new grocery_CRUD_extended();
			$crud_state = $crud->getState();
			$crud->unset_jquery(); //As we are using admin lte we need to unset default jQuery


			$crud->set_table($this->db->dbprefix('users'));
			$crud->where('id!=',1);

			$user_group_arr = array('2' => 'Student', '3' => 'Tutor', '4' => 'Institute');

			$this->data['activemenu'] = 'users';

			if($this->ion_auth->is_institute()) {

				$inst_user_id = $this->ion_auth->get_user_id();
				//Authenticate whether Inst editing/viewing their records only
				if($crud_state == "edit" || $crud_state == "read") {

					$p_key = $this->uri->segment(5);
					$parent_id = $this->base_model->fetch_value('users', 'parent_id', array('id' => $p_key));
					if($parent_id != $inst_user_id) {

						$this->prepare_flashmessage(get_languageword('not_authorized'), 1);
		    			redirect(URL_AUTH_INDEX."/3");
					}

				}

				$crud->where('parent_id', $inst_user_id);
				$user_group_arr = array('3' => 'Tutor');

				$this->data['activemenu'] 	 = 'manage';
				$this->data['activesubmenu'] = $crud_state;

			} else {

				$crud->where('parent_id', 0);
			}

			$user_type_arr = array(2, 3, 4);
			if(in_array($param, $user_type_arr))
			{
				$crud->where('user_belongs_group',$param);
			}
			$crud->set_subject(get_languageword('user'));
			$crud->columns('id','email', 'first_name', 'last_name', 'user_belongs_group', 'admin_approved', 'active');
			$crud->set_field_upload('photo','assets/uploads/profiles');
			$crud->required_fields(array('username', 'password', 'email', 'first_name', 'gender', 'phone_code', 'phone', 'confirm_password', 'user_belongs_group'));
			$crud->unique_fields('email');


			$crud->display_as('admin_approved', get_languageword('is_approved'));
			$crud->display_as('active', get_languageword('status'));

			$crud->add_fields('email','first_name','last_name', 'gender', 'phone_code', 'phone', 'password', 'confirm_password', 'user_belongs_group');

			$crud->edit_fields('first_name','last_name', 'gender', 'active', 'phone_code', 'phone', 'email', 'user_belongs_group');

			$crud->callback_field('phone_code', array($this, 'callback_field_phone_code'));

			$crud->callback_column('active', array($this, 'callback_column_user_status'));
		

			if($this->ion_auth->is_institute()) {
				$crud->callback_field('active', array($this,'_call_back_active_field'));
			}

			//Custom action button for approving Tutor|Institute
			if(($param == 3 || $param == 4) && $this->ion_auth->is_admin()) {
				$crud->callback_column('admin_approved', array($this,'_call_back_set_action'));
			}

			//No Admin approvement required for Students
			if(empty($param) || $param == 2 || (strcasecmp(get_system_settings('need_admin_for_tutor'), 'no') == 0) || $this->ion_auth->is_institute()) {

				$crud->unset_columns('admin_approved');
				$crud->unset_read_fields('admin_approved', 'admin_approved_date');
			}

			$crud->change_field_type('password', 'password');
			$crud->change_field_type('confirm_password', 'password');
			$crud->field_type('user_belongs_group','dropdown', $user_group_arr);

			$crud->set_rules('password', get_languageword('password'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[confirm_password]');


			if($this->ion_auth->is_admin()){
				if($param==2 || $param==3 || $param==4)
					$crud->unset_add();
				if($param == 4){
					$crud->add_action(get_languageword('view_institute_tutors'), URL_ADMIN_IMAGES.'users.png',  URL_ADMIN_VIEW_INST_TUTORS.'/');
				}

				$crud->add_action(get_languageword('view_certificates'), URL_ADMIN_IMAGES.'stamp.png',  URL_ADMIN_VIEW_CERTIFICATES.'/');
			}
			$crud->callback_insert(array($this,'encrypt_password_and_insert_callback'));
			$crud->callback_update(array($this,'encrypt_password_and_update_callback'));


			if($crud_state != 'list')
			{
				if(!empty($param))
				{
					$title = get_languageword('user');
					if($param == 2)
					{
						$title = get_languageword('students');
						$crud->set_subject(get_languageword('student'));
					}
					if($param == 3)
					{
						$title = get_languageword('tutors');
						$crud->set_subject(get_languageword('tutor'));
					}
					if($param == 4)
					{
						$title = get_languageword('institutes');
						$crud->set_subject(get_languageword('institute'));
					}
				}

				if($crud_state=='read')
				{
					$crud_state = 'view';
					$crud->unset_read_fields('password','confirm_password', 'created_on','last_login','slug','activation_code','forgotten_password_code','forgotten_password_time','remember_code','parent_id','subscription_id','salt');
					$crud->set_relation('location_id','locations','location_name');
					$crud->set_relation('user_belongs_group','groups','name');
					$crud->set_relation('active','user_status_texts','text');
					$crud->set_relation('visibility_in_search','yesno_status_texts','text');
					$crud->set_relation('availability_status','yesno_status_texts','text');
					$crud->set_relation('is_profile_update','yesno_status_texts','text');
					$crud->display_as('location_id',get_languageword('location'));
				}
				$this->data['pagetitle'] = get_languageword($crud_state).' '.$title;
				$this->data['maintitle'] = get_languageword('users');
				$this->data['maintitle_link'] = URL_AUTH_INDEX;
			}
			else
			{
				if(!empty($param))
				{
					$title = get_languageword('user');
					if($param == 2)
					{
						$title = get_languageword('students');
						$crud->set_subject(get_languageword('student'));
					}
					if($param == 3)
					{
						$title = get_languageword('tutors');
						$crud->set_subject(get_languageword('tutor'));
					}
					if($param == 4)
					{
						$title = get_languageword('institutes');
						$crud->set_subject(get_languageword('institute'));
					}
					$this->data['pagetitle'] = $title;
				}
				else
				{
				$this->data['pagetitle'] = get_languageword('users');
				}
			}

			if($this->ion_auth->is_institute()) {
				$crud->set_read_fields('username', 'email', 'first_name', 'last_name', 'gender', 'phone', 'is_online', 'last_updated');
				$this->data['pagetitle'] = get_languageword('Manage Tutors');
			}

			$output = $crud->render();
			$this->data['grocery_output'] = $output;
			$this->data['grocery'] = TRUE;
			if($this->ion_auth->is_institute()) {
				$this->data['my_profile'] = getUserRec();
			}
			$this->grocery_output($this->data);
		}
	}


	function _call_back_active_field($val = '', $primary_key = null)
	{
		return form_dropdown('active', array('1' => get_languageword('active'), '0' => get_languageword('inactive')), $val, 'class="chosen-select"');
	}


	function callback_field_phone_code($val = '', $primary_key = null)
	{

		$user_country = $this->base_model->fetch_value('users', 'country', array('id' => $primary_key));

		$countries = $this->base_model->fetch_records_from('country');
		$country_opts = array('' => get_languageword('select_Phone_Code'));
		if(!empty($countries)) {
			foreach ($countries as $key => $value) {
				$country_opts[$value->phonecode.'_'.$value->nicename] = $value->nicename." +".$value->phonecode;
			}
		}

		if(!empty($user_country))
			$val = $val.'_'.$user_country;

		return form_dropdown('phone_code', $country_opts, $val, 'class="chosen-select"');
	}


	function _call_back_set_action($primary_key , $row)
	{
	    $action = "";
	    $admin_approved = $row->admin_approved;

	    if($admin_approved == 'Yes') {

	    	$action = $admin_approved.' <a title="'.get_languageword("cancel_approvement").'" href="'.URL_AUTH_CHANGE_APPROVEMENT_STATUS.'/'.$row->id.'/No"><img alt="'.get_languageword("cancel_approvement").'" src="'.URL_FRONT_IMAGES.'/error.png"></a>';

	    } else if($admin_approved == 'No') {

	    	$action = $admin_approved.' <a title="'.get_languageword("approve").'" href="'.URL_AUTH_CHANGE_APPROVEMENT_STATUS.'/'.$row->id.'/Yes"><img alt="'.get_languageword("approve").'" src="'.URL_FRONT_IMAGES.'/checked.png"></a>';
	    }

	    return $action;
	}


	function callback_column_user_status($primary_key , $row)
	{
	    $txt = "";
	    $status = $row->active;

	    if($status == 1) {

	    	$txt = $status." - ".get_languageword('active');

	    } else if($status == 0) {

	    	$txt = $status." - ".get_languageword('inactive');
	    }

	    return $txt;
	}

	
	function encrypt_password_and_insert_callback( $post_array )
	{

		$email 		= strtolower($post_array['email']);
		$password 	= $post_array['password'];

		$first_name = ucfirst(strtolower($post_array['first_name']));
		$last_name = ucfirst(strtolower($post_array['last_name']));
		$username =  $first_name.' '.$last_name;

		$slug = prepare_slug($username, 'username', 'users');

		$code_country = explode('_', $post_array['phone_code']);

		$additional_data = array(
								'username' 				=> $username,
								'slug' 					=> $slug,
								'first_name' 			=> $first_name,
								'last_name'	 			=> $last_name,
								'phone_code' 			=> $code_country[0],
								'country' 				=> $code_country[1],
								'phone' 				=> $post_array['phone'],
								'gender' 				=> $post_array['gender'],
								'user_belongs_group'	=> $post_array['user_belongs_group'],
								'last_updated' 			=> date('Y-m-d H:i:s'),
							);

		if($this->ion_auth->is_institute()) {

			$additional_data['parent_id'] = $this->ion_auth->get_user_id();
		}

		$groups = array('id' => $post_array['user_belongs_group']);
		$id = $this->ion_auth->register($username, $password, $email, $additional_data, $groups);

		return TRUE;
	}
	
	function encrypt_password_and_update_callback( $post_array, $primary_key )
	{

		$first_name = ucfirst(strtolower($post_array['first_name']));
		$last_name = ucfirst(strtolower($post_array['last_name']));
		$username =  $first_name.' '.$last_name;

		$code_country = explode('_', $post_array['phone_code']);

		$additional_data = array(
								'username' 				=> $username,
								'first_name' 			=> $first_name,
								'last_name'	 			=> $last_name,
								'phone_code' 			=> $code_country[0],
								'country' 				=> $code_country[1],
								'phone' 				=> $post_array['phone'],
								'gender' 				=> $post_array['gender'],
								'active' 				=> $post_array['active'],
								'user_belongs_group' 	=> $post_array['user_belongs_group'],
								'last_updated' 			=> date('Y-m-d H:i:s'),
							);

		$prev_username = $this->base_model->fetch_value('users', 'username', array('id' => $primary_key));

		//If user updates the username
		if($prev_username != $username) {
			$slug = prepare_slug($username, 'username', 'users');
			$additional_data['slug'] = $slug;
		}

		$groups = array($post_array['user_belongs_group']);
		
		if (isset($groups) && !empty($groups)) {
			$this->ion_auth->remove_from_group('', $primary_key);
			foreach($groups as $val) {
				$this->ion_auth->add_to_group($val, $primary_key);
			}
		}				
		$this->ion_auth->update($primary_key, $additional_data);
		return TRUE;
	}
	

	function change_approvement_status($user_id, $status)
	{
		if (!$this->ion_auth->logged_in())
		{
			$this->prepare_flashmessage(get_languageword('please login to access this page'), 1);
			redirect('auth/login', 'refresh');
		}

		if (!$this->ion_auth->is_admin())
		{
			$this->prepare_flashmessage(get_languageword('not_authorized'), 1);
			redirect('auth/login', 'refresh');
		}


		$up_data['admin_approved'] = $status;

		if($status == "Yes") {

			$up_data['admin_approved_date'] = date('Y-m-d H:i:s');
		}

		if($this->base_model->update_operation($up_data, 'users', array('id' => $user_id))) {

			if($status == "Yes")
				$this->base_model->update_operation(array('admin_status' => 'Approved'), 'users_certificates', array('user_id' => $user_id));

			//Email Alert to User - Start
			//Get Approvement Status Changed Email Template
			$email_tpl = $this->base_model->fetch_records_from('email_templates', array('template_status' => 'Active', 'email_template_id' => '12'));

			if(!empty($email_tpl)) {

				$email_tpl = $email_tpl[0];

				$user_rec = getUserRec($user_id);


				if(!empty($email_tpl->from_email)) {

					$from = $email_tpl->from_email;

				} else {

					$from 	= get_system_settings('Portal_Email');
				}

				$to 	= $user_rec->email;

				if(!empty($email_tpl->template_subject)) { 

					$sub = $email_tpl->template_subject;

				} else {

					$sub = get_languageword("Approvement Status Changed");
				}

				if(!empty($email_tpl->template_content)) {

					if($status == "Yes") {

						$approvemnet_status = "approved";
						$extra_txt = "";

					} else {

						$approvemnet_status = "disapproved";
						$extra_txt = "<p>".get_languageword('Please verify and upload required certificates')."</p>";
					}

					$original_vars  = array($user_rec->username, $approvemnet_status, $extra_txt.'<a href="'.URL_AUTH_LOGIN.'">'.get_languageword('Login Here').'</a>');
					$temp_vars		= array('___USER_NAME___', '___APPROVEMENT_STATUS___', '___LOGINLINK___');
					$msg = str_replace($temp_vars, $original_vars, $email_tpl->template_content);

				} else {

					$msg = get_languageword('please')." <a href='".URL_AUTH_LOGIN."'>".get_languageword('Login Here')."</a> ".get_languageword('to view the approvement details');
					$msg .= "<p>".get_languageword('Thank you')."</p>";
				}

				sendEmail($from, $to, $sub, $msg);
			}
			//Email Alert to User - End

			$this->prepare_flashmessage(get_languageword('approvement_status_changed_successfully'), 0);
			redirect(URL_AUTH_INDEX.'/'.getUserTypeId($user_id));
		}

	}


	function profile()
	{
		$id = $this->ion_auth->get_user_id();
		
		if (!$this->ion_auth->logged_in())
		{
			$this->prepare_flashmessage(get_languageword('please login to access this page'), 1);
			redirect('auth/login', 'refresh');
		}

		if (!$this->ion_auth->is_admin()) {
			$this->prepare_flashmessage(get_languageword('You dont have permission to access this page'), 1);
			redirect('auth');
		}

		$user = $this->ion_auth->user($id)->row();
		
		$this->data['message'] = $this->session->flashdata('message');
		
		if (isset($_POST) && !empty($_POST))
		{
			$this->form_validation->set_rules('first_name', get_languageword('first_name'), 'trim|required');
			$this->form_validation->set_rules('last_name', get_languageword('last_name'), 'trim|required');
			$this->form_validation->set_rules('phone', get_languageword('phone'), 'trim|required|numeric|exact_length[10]');
			
			if ($this->form_validation->run() === TRUE)
			{
				$data = array(
					'first_name' => $this->input->post('first_name'),
					'last_name'  => $this->input->post('last_name'),
					'phone' => $this->input->post('phone'),
				);
								
			// check to see if we are updating the user
			   if($this->ion_auth->update($user->id, $data))
			    {
					$this->prepare_flashmessage(get_languageword('account_information_successfully_updated'), 0);
				    if ($this->ion_auth->is_admin())
					{
						redirect('auth/profile', 'refresh');
					}
					else
					{
						redirect('user/index', 'refresh');
					}

			    }
			    else
			    {
					$this->prepare_message(get_languageword($this->ion_auth->errors()), 1);
				    if ($this->ion_auth->is_admin())
					{
						redirect('admin/profile', 'refresh');
					}
					else
					{
						redirect('user/edit_user/'.$id, 'refresh');
					}

			    }

			}
			else
			{
				$this->data['message'] = $this->prepare_message(validation_errors(), 1);
			}
		}

		// pass the user to the view
		$this->data['user'] = $user;

		$this->data['first_name'] = array(
			'name'  => 'first_name',
			'id'    => 'first_name',
			'type'  => 'text',
			'required'=>'true',
			'value' => $this->form_validation->set_value('first_name', $user->first_name),
		);
		$this->data['last_name'] = array(
			'name'  => 'last_name',
			'id'    => 'last_name',
			'type'  => 'text',
			'required'=>'true',
			'value' => $this->form_validation->set_value('last_name', $user->last_name),
		);
		
		$this->data['phone'] = array(
			'name'  => 'phone',
			'id'    => 'phone',
			'type'  => 'text',
			'required'=>'true',
			'value' => $this->form_validation->set_value('phone', $user->phone),
		);
		
		$this->data['activemenu'] = 'profile';
		$this->data['activesubmenu'] = 'my_profile';
		$this->data['pagetitle'] = get_languageword('my_profile');
		$this->data['helptext'] = array();
		$this->data['content'] = 'profile';
        $this->_render_page('template/admin/admin-template', $this->data);
	}
	
	
	/**
	* METHO FOR SIGNUP FOR COMPANY OR USER
	* IF POSTED HIDDEN FEILD USER_TYPE IS 'USER' THEN USER REGISTRATION, 
	* ELSE COMPANY REGISTRATION
	* SUCCESS MESSAGE OR ERROR MESSAGES WILL BE RETURNED
	*/
	function signup()
    { 
		$this->data['message'] = $this->session->flashdata('message');
		if(isset($_POST['brnRegister']))
		{
			$tables 						= $this->config->item('tables','ion_auth');
			$identity_column 				= $this->config->item('identity','ion_auth');
			$this->data['identity_column'] 	= $identity_column;
			//Validate User feilds			
			 $this->form_validation->set_rules('first_name',get_languageword('first_ name'),'required');
			 $this->form_validation->set_rules('last_name',get_languageword('last_name'),'xss_clean');
			 $this->form_validation->set_rules('phone_code',get_languageword('phone_code'),'required|numeric');
			 $this->form_validation->set_rules('mobile_number',get_languageword('mobile_number'),'required|numeric|exact_length[10]');
						
			if($identity_column !== 'email')
			{
				$this->form_validation->set_rules('identity',$this->lang->line('create_user_validation_identity_label'),'required|is_unique['.$tables['users'].'.'.$identity_column.']');
				$this->form_validation->set_rules('identity', $this->lang->line('create_user_validation_email_label'), 'required|valid_email');
			}
			else
			{
				$this->form_validation->set_rules('identity', $this->lang->line('create_user_validation_email_label'), 'required|valid_email|is_unique[' . $tables['users'] . '.email]');
			}
		
			$this->form_validation->set_rules('password',$this->lang->line('create_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
			$this->form_validation->set_rules('password_confirm', 'Confirm password', 'required');
			if ($this->form_validation->run() == true)
			{
				$email    	= strtolower($this->input->post('identity'));
				$identity 	= ($identity_column==='email') ? $email : $this->input->post('identity');
				$password 	= $this->input->post('password');
				$additional_data = array();
				
				//Prepare User related data
				$name = $this->input->post('first_name').' '.$this->input->post('last_name');
				$additional_data = array(
					'username' => $email,
					'first_name' 			=> $this->input->post('first_name'),
					'last_name'  			=> $this->input->post('last_name'),
					'current_language'      => 'english',
					'phone_code'     	=> $this->input->post('phone_code'),
					'mobile_number'     	=> $this->input->post('mobile_number'),
					'admin_read_status'      => '0'
				);
				$group = array(GRP_USER);
								
				/* if ($this->form_validation->run() == true && $this->ion_auth->register($identity, $password, $email, $additional_data,$group)) */
				$id = $this->ion_auth->register($identity, $password, $email, $additional_data,$group);
				
				if ($id)
				{
					$email_template = $this->db->get_where(TBL_PREFIX.TBL_EMAIL_TEMPLATES,array('template_key'=>'registration_template','status'=>'Active'))->result();
					if(count($email_template)>0) 
					{
						$email_template = $email_template[0];			
						$content 	= $email_template->email_template;
						
						$content 	= str_replace("__NAME__", $name,$content);						
						$content 	= str_replace("__LOGO__", base_url().'assets/uploads/'.$this->config->item('site_settings')->logo,$content);
						$content 	= str_replace("__CONTACTUS__", $this->config->item('site_settings')->address,$content);
						
						/* $user = $this->db->get_where(TBL_PREFIX.TBL_USERS,array('email'=>$email))->result();
						if(count($user) > 0)
						{
						$link 		= SITEURL2.DS.'activation'.DS.$user[0]->id.DS.$user[0]->activation_code;
						$content 	= str_replace("__ACTIVATELINK__", $link,$content);
						}
						else
						{ */
						$content 	= str_replace("__ACTIVATELINK__", SITEURL2,$content);	
						//}
						
						$content 		= str_replace("__URL__", SITEURL2,$content);
						$content 		= str_replace("__SITETITLE__", $this->config->item('site_settings')->site_title,$content);
						
						$content 		= str_replace("__COPYRIGHTS__", $this->config->item('site_settings')->rights_reserved_by,$content);
						$content 		= str_replace("__EMAIL__", $email,$content);
						$content 	= str_replace("__PASSWORD__", $this->input->post('password'),$content);				
										
						$from 		= $this->config->item('admin_email', 'ion_auth');
						$to 		= $email;
						$sub 		= $this->config->item('site_title', 'ion_auth') 
						. ' - ' . "Welcome Message";
						//sendEmail($from, $to, $sub, $content); 
					}
					
					/**default package subscription for user if admin provides**/
			if(isset($this->config->item('quote_settings')->would_you_like_to_provide_package_to_user) && ($this->config->item('quote_settings')->would_you_like_to_provide_package_to_user) == 'Yes')
			{
				if(($this->config->item('quote_settings')->package_id != '') && ($this->config->item('quote_settings')->no_of_days_package_provide > 0) )
				{
					$package = $this->base_model->fetch_records_from(TBL_PACKAGES,array('package_id'=>$this->config->item('quote_settings')->package_id));
					
					$days = '';
					$days = $this->config->item('quote_settings')->no_of_days_package_provide;
					if(count($package)>0)
					{
						$user = getUserRec($id);
						
						$package = $package[0];
						
						$sub_data['package_id'] = $package->package_id;
						$sub_data['subscribed_date'] = date('Y-m-d');
						$sub_data['expire_date'] = date(
													'Y-m-d', 
													strtotime("+ ".$days." days")
													);
						$sub_data['no_of_quotes_provided'] = $package->no_of_quotes;
						$sub_data['no_of_quotes_used'] = 0;
						$sub_data['package_name'] = $package->package_name;
						$sub_data['subscription_duration_in_days'] = $this->config->item('quote_settings')->no_of_days_package_provide;
						$sub_data['package_cost'] = $package->package_cost;
						$sub_data['user_id'] = $id;
						$sub_data['user_name'] = $user->first_name.''.$user->last_name;
						$sub_data['user_email'] = $user->email;
						$sub_data['status'] = 'Active';
						$sub_data['subscription_type'] = 'gift';
						
						
						if($this->base_model->insert_operation($sub_data,TBL_SUBSCRIPTIONS))
						{
							$user_data['is_premium'] = 1;
							$user_data['package_id'] = $package->package_id;
							$user_data['no_of_package_quotes'] = $package->no_of_quotes;
							$user_data['no_of_package_quotes_used'] = 0;
							
							$whr['id'] = $id;
							$this->base_model->update_operation($user_data,TBL_USERS,$whr);
						}
					}
				}
			}
			/**default package subscription end**/
					
					$this->prepare_flashmessage(get_languageword('registration_completed_successfully_activation_email_sent'), 0);	
					redirect(URL_AUTH_LOGIN);
				}
				else
				{
					$this->data['message'] = prepare_message($this->ion_auth->errors(), 1);	
				}
			}
			else
			{
				$this->data['message'] = prepare_message(validation_errors(), 1);	
			}
		}
		$this->data['countries'] = $this->base_model->fetch_records_from('country', array('status' => 'Active'), '*', 'nicename');
		$this->data['content']			= 'auth/signup';		
		$this->data['activemenu'] 		= "signup";			
		$this->_render_page('template/site/site-template', $this->data);
    }
	
	// log the user in
	function login()
	{		
		if($this->ion_auth->logged_in())
			redirect(URL_AUTH_INDEX);
		
		
		$this->data['message'] = $this->session->flashdata('message');
		if(isset($_POST['btnLogin']))
		{
			//validate form input
			$this->form_validation->set_rules('identity', get_languageword('email'), 'required');
			$this->form_validation->set_rules('password', get_languageword('Password'), 'required');

			$this->form_validation->set_error_delimiters('<div class="error">', '</div>');

			if ($this->form_validation->run() == true)
			{
				if($this->ion_auth->logged_in())
				redirect(SITEURL2,'refresh');
				// check to see if the user is logging in
				// check for "remember me"
				$remember = (bool) $this->input->post('remember');

				if ($this->ion_auth->login($this->input->post('identity'), $this->input->post('password'), $remember))
				{

					$is_profile_updated = "";
					$user_id = $this->ion_auth->get_user_id();
					$this->base_model->update_operation(array('is_online' => 'yes'), 'users', array('id' => $user_id));


					if(isset($this->config->item('site_settings')->default_language) && $this->config->item('site_settings')->default_language != '')
						$this->session->set_userdata('current_language' , strtolower($this->config->item('site_settings')->default_language));

					$this->prepare_flashmessage(get_languageword('logged_in_successfully'), 0);

					if($this->session->userdata('req_from')) {

						if($this->session->userdata('req_from') == "leads" && ($this->ion_auth->is_tutor() || $this->ion_auth->is_institute()) )
							redirect(URL_HOME_SEARCH_STUDENT_LEADS);
						else if($this->session->userdata('req_from') == "buy_course")
							redirect(URL_HOME_BUY_COURSE.'/'.$this->session->userdata('selling_course_slug'));
					}

					if(!$this->ion_auth->is_admin() && !is_inst_tutor($user_id)) {

						$is_profile_updated = $this->ion_auth->user($user_id)->row()->is_profile_update;
	
					}

					if($this->ion_auth->is_admin()) {
						  redirect(URL_ADMIN_INDEX, 'refresh');
					}
					else if($this->ion_auth->is_tutor()) {

						if(!is_inst_tutor($user_id) && $is_profile_updated != 1) {

							$this->prepare_flashmessage(get_languageword('please_update_your_profile_by_adding_tutoring_courses_and_preferred_teaching_types_to_avail_for_students'), 2);
							redirect(URL_TUTOR_MANAGE_COURSES, 'refresh');
						}

						redirect(URL_TUTOR_INDEX, 'refresh');
					}
					else if($this->ion_auth->is_student()) {

						if($is_profile_updated != 1) {

							$this->prepare_flashmessage(get_languageword('please_update_your_profile_by_adding_preferred_courses_and_preferred_teaching_types_to_get_tutors'), 2);
							redirect(URL_STUDENT_MANAGE_COURSES, 'refresh');
						}

						redirect(URL_STUDENT_INDEX, 'refresh');
					}
					else if($this->ion_auth->is_institute()) {	

						if($is_profile_updated != 1) {

							$this->prepare_flashmessage(get_languageword('please_update_your_profile_by_adding_tutoring_courses_and_preferred_teaching_types_to_avail_for_students'), 2);
							
							redirect(URL_INSTITUTE_OFFERED_COURSES, 'refresh');
						}

						redirect(URL_INSTITUTE_INDEX, 'refresh');
					}					
					else {
						redirect(SITEURL,'refresh');
					}
				}
				else
				{
					$this->prepare_flashmessage($this->ion_auth->errors(), 1);	
					redirect(URL_AUTH_LOGIN);
				}
			}
			else
			{
				$this->data['message'] = (validation_errors()) ? $this->prepare_message(validation_errors(), 1) : $this->session->flashdata('message');
			}
		}
		
		if(isset($_POST['create']))
		{
			
			$tables 						= $this->config->item('tables','ion_auth');
			$identity_column 				= $this->config->item('identity','ion_auth');
			$this->data['identity_column'] 	= $identity_column;
			//Validate User feilds
			$this->form_validation->set_rules('user_belongs_group',get_languageword('Group'),'trim|required');

			if($this->input->post('user_belongs_group') == 4)
				$lbl_name = get_languageword('Institute Name');
			else
				$lbl_name = get_languageword('first_name');

			$this->form_validation->set_rules('first_name',$lbl_name,'required');

			 $this->form_validation->set_rules('last_name',get_languageword('last_name'),'xss_clean');
			 $this->form_validation->set_rules('pin_code',get_languageword('pin_code'),'xss_clean');
			 $this->form_validation->set_rules('phone_code',get_languageword('phone_code'),'required');
			 $this->form_validation->set_rules('phone',get_languageword('phone'),'required');
						
			if($identity_column !== 'email')
			{
				$this->form_validation->set_rules('identity',get_languageword('Email'),'required|is_unique['.$tables['users'].'.'.$identity_column.']|valid_email');
			}
			else
			{
				$this->form_validation->set_rules('identity', get_languageword('Email'), 'required|valid_email|is_unique[' . $tables['users'] . '.email]');
			}

			$this->form_validation->set_rules('password',get_languageword('Password'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
			$this->form_validation->set_rules('password_confirm', 'Confirm password', 'required');

			$this->form_validation->set_error_delimiters('<div class="error">', '</div>');

			if ($this->form_validation->run() == true)
			{
				$email    	= strtolower($this->input->post('identity'));
				$identity 	= ($identity_column==='email') ? $email : $this->input->post('identity');
				$password 	= $this->input->post('password');
				$additional_data = array();

				//Prepare User related data
				$first_name = ucfirst(strtolower($this->input->post('first_name')));
				$last_name = ucfirst(strtolower($this->input->post('last_name')));
				$username =  $first_name.' '.$last_name;

				$slug = prepare_slug($username, 'slug', 'users');

				$user_belongs_group = $this->input->post('user_belongs_group');

				$phone_code = explode('_', $this->input->post('phone_code'))[1];

				$additional_data = array(
					'username' => $username,
					'slug' => $slug,
					'first_name' 	=> $first_name,
					'last_name'  	=> $last_name,
					'pin_code'   	=> $this->input->post('pin_code'),
					'phone_code'   	=> $phone_code,
					'phone'     	=> $this->input->post('phone'),
					'user_belongs_group' => $user_belongs_group,
				);
				$group = array($user_belongs_group);
				
				$id = $this->ion_auth->register($identity, $password, $email, $additional_data,$group);
				
				if ($id)
				{										
					$this->prepare_flashmessage(get_languageword($this->ion_auth->messages()), 0);	
					redirect(URL_AUTH_LOGIN);
				}
				else
				{
					$this->data['message_create'] = prepare_message($this->ion_auth->errors(), 1);	
				}
			}
			else
			{
				$this->data['message_create'] = prepare_message(validation_errors(), 1);	
			}
		}

		$this->data['activemenu'] 				= "login";
		$this->data['groups'] = $this->base_model->fetch_records_from('groups', array('group_status' => 'Active', 'id != ' => 1));

		$countries = $this->base_model->fetch_records_from('country');
		$country_opts = array('' => get_languageword('select_Phone_Code'));
		foreach ($countries as $key => $value) {
			$country_opts[$key.'_'.$value->phonecode] = $value->nicename." +".$value->phonecode;
		}
		$this->data['country_opts'] = $country_opts;
		
		$this->data['content'] = 'auth/login';
		$this->_render_page('template/site/site-template', $this->data);
	}
	
	/**
	 * Changes the status of the record
	 *
	 */
		
	function statustoggle()
	{
		if($this->input->post())
		{
			$id = $this->input->post('id');
			$user_status = $this->input->post('status');
			$redirection = $this->input->post('redirection');
			$filedata = array();
			$message = '';
			if($user_status == 'false')
			{
				$filedata['active'] = '0';
				$message = get_languageword('MSG_USER_DEACTIVATED');
			}
			else
			{
				$filedata['active'] = '1';				
				$message = get_languageword('MSG_USER_ACTIVATED');
			}	

			$this->base_model->update_operation_in( $filedata, TBL_USERS, 'id', explode(',', $id) );

			if($redirection == 'yes') $this->prepare_flashmessage($message, 0);
			echo json_encode(array('status' => 1, 'message' => $message, 'action' => get_languageword('success'), 'url' => URL_AUTH_INDEX));
		} 
		else
		{
			$message = get_languageword('MSG_WRONG_OPERATION');
			echo json_encode(array('status' => 0, 'message' => $message, 'action' => get_languageword('failed')));			
		}
	}
	// log the user out
	function logout()
	{
		$user_id = $this->ion_auth->get_user_id();
		$this->base_model->update_operation(array('is_online' => 'no'), 'users', array('id' => $user_id));

		// log the user out
		$logout = $this->ion_auth->logout();

		// redirect them to the login page
		$this->prepare_flashmessage($this->ion_auth->messages(), 0);
		redirect('auth/login', 'refresh');
	}
	// change password
	function change_password()
	{
		if (!$this->ion_auth->logged_in())
		{
			$this->session->set_flashdata('message', get_languageword('please_login_to_access_this_page'));	
			redirect('auth/login', 'refresh');
		}
		
		$this->data['message'] = $this->session->flashdata('message');

		if(isset($_POST['submitbutt']))
		{
			$this->form_validation->set_rules('old', get_languageword('Current Password'), 'required');
			$this->form_validation->set_rules('new', get_languageword('New Password'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[new_confirm]');
			$this->form_validation->set_rules('new_confirm', get_languageword('Confirm Password'), 'required');
			if ($this->form_validation->run() == true)
			{
				$identity = $this->session->userdata('identity');
				$change = $this->ion_auth->change_password($identity, $this->input->post('old'), $this->input->post('new'));
				//echo $this->db->last_query();die();
				if ($change)
				{
					$this->prepare_flashmessage(get_languageword('password_changed_successfully'), 0);
				}
				else
				{
					$this->prepare_flashmessage($this->ion_auth->errors(), 1);					
				}
				redirect(URL_AUTH_CHANGE_PASSWORD,'refresh');
			}
			else
			{
				$this->data['message'] = $this->prepare_message(validation_errors(), 1);
			}
		}
		$user = $this->ion_auth->user()->row();
		$this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');
		$this->data['old_password'] = array(
			'name' => 'old',
			'id'   => 'old',
			'type' => 'password',
			'class' => 'form-control',
		);
		$this->data['new_password'] = array(
			'name'    => 'new',
			'id'      => 'new',
			'type'    => 'password',
			'pattern' => '^.{'.$this->data['min_password_length'].'}.*$',
			'class' => 'form-control',
		);
		$this->data['new_password_confirm'] = array(
			'name'    => 'new_confirm',
			'id'      => 'new_confirm',
			'type'    => 'password',
			'pattern' => '^.{'.$this->data['min_password_length'].'}.*$',
			'class' => 'form-control',
		);
		$this->data['user_id'] = array(
			'name'  => 'user_id',
			'id'    => 'user_id',
			'type'  => 'hidden',
			'value' => $user->id,
		);
		
		$this->data['my_profile'] = getUserRec();
		$this->data['activemenu'] = 'profile';
		$this->data['activesubmenu'] = 'change_password';
		$this->data['pagetitle'] = get_languageword('change_password');
		$this->data['helptext'] = array();
		$this->data['content'] = 'auth/change_password';
		if($this->ion_auth->is_admin())
			$this->_render_page('template/admin/admin-template', $this->data);
		elseif($this->ion_auth->is_tutor())
		{
			$this->data['activemenu'] = 'account';
			$this->data['activesubmenu'] = 'change_password';
			$this->_render_page('template/site/tutor-template', $this->data);
		}
		elseif($this->ion_auth->is_student())
			$this->_render_page('template/site/student-template', $this->data);

		elseif($this->ion_auth->is_institute())
			$this->_render_page('template/site/institute-template', $this->data);
	}
	// forgot password
	function forgot_password()
	{
		// setting validation rules by checking wheather identity is username or email
		if($this->config->item('identity', 'ion_auth') != 'email' )
		{
		   $this->form_validation->set_rules('identity', $this->lang->line('forgot_password_identity_label'), 'required');
		}
		else
		{
		   $this->form_validation->set_rules('identity', $this->lang->line('forgot_password_validation_email_label'), 'required|valid_email');
		}

		$this->data['type'] = $this->config->item('identity', 'ion_auth');

		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');

		if($this->form_validation->run() == false)
		{
			// setup the input
			$this->data['identity'] = array('name' => 'identity',	'id' => 'email_forget', 'class' => 'form-control');

			if ( $this->config->item('identity', 'ion_auth') != 'email' ){
				$this->data['identity_label'] = $this->lang->line('forgot_password_identity_label');
			}
			else
			{
				$this->data['identity_label'] = $this->lang->line('forgot_password_email_identity_label');
			}

			// set any errors and display the form
			$this->data['message'] = (validation_errors()) ? $this->prepare_message(validation_errors(), 1) : $this->session->flashdata('message');
			$this->data['content'] = 'auth/forgot_password';
			$this->_render_page('template/site/site-template', $this->data);

		}
		else
		{
			$identity_column = $this->config->item('identity','ion_auth');
			$identity = $this->ion_auth->where($identity_column, $this->input->post('identity'))->users()->row();

			if(empty($identity)) {

        		if($this->config->item('identity', 'ion_auth') != 'email')
            	{
            		$this->ion_auth->set_error('forgot_password_identity_not_found');
            	}
            	else
            	{
            	   $this->ion_auth->set_error('forgot_password_email_not_found');
            	}

				$this->prepare_flashmessage($this->ion_auth->errors(), 1);
        		redirect("auth/forgot_password", 'refresh');
    		}

			// run the forgotten password method to email an activation code to the user
			$forgotten = $this->ion_auth->forgotten_password($identity->{$this->config->item('identity', 'ion_auth')});

			if($forgotten)
			{
				// if there were no errors
				$this->prepare_flashmessage($this->ion_auth->messages(), 0);
				redirect(URL_AUTH_LOGIN, 'refresh'); //we should display a confirmation page here instead of the login page
			}
			else
			{
				$this->prepare_flashmessage($this->ion_auth->errors(), 1);
				redirect("auth/forgot_password", 'refresh');
			}
		}
	}
	// reset password - final step for forgotten password
	public function reset_password($code = NULL)
	{
		if (!$code)
		{
			show_404();
		}

		$code = str_replace('_', '-', $code);

		$user = $this->ion_auth->forgotten_password_check($code);
		$this->data['activemenu'] = 'account';
		if ($user)
		{
			// if the code is valid then display the password reset form

			$this->form_validation->set_rules('new_password', $this->lang->line('reset_password_validation_new_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[new_confirm]');
			$this->form_validation->set_rules('new_confirm', $this->lang->line('reset_password_validation_new_password_confirm_label'), 'required');

			$this->form_validation->set_error_delimiters('<div class="error">', '</div>');

			if ($this->form_validation->run() == false)
			{
				// display the form

				// set the flash data error message if there is one
				$this->data['message'] = (validation_errors()) ? $this->prepare_message(validation_errors(), 1) : $this->session->flashdata('message');

				$this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');
				
				$this->data['new_password'] = array(
					'name' => 'new_password',
					'id'   => 'new_password',
					'class'  => 'form-control',
					'type' => 'password',
					'pattern' => '^.{'.$this->data['min_password_length'].'}.*$',
					'placeholder'=> get_languageword('password'),
					'value'	=> $this->form_validation->set_value('new_password')
				);
				$this->data['new_password_confirm'] = array(
					'name'    => 'new_confirm',
					'id'      => 'new_confirm',
					'class'  => 'form-control',
					'type'    => 'password',
					'pattern' => '^.{'.$this->data['min_password_length'].'}.*$',
					'placeholder'=> get_languageword('confirm_password'),
					'value'	=> $this->form_validation->set_value('new_confirm')
				);
				$this->data['user_id'] = array(
					'name'  => 'user_id',
					'id'    => 'user_id',
					'type'  => 'hidden',
					'value' => $user->id,
				);
				$this->data['csrf'] = $this->_get_csrf_nonce();
				$this->data['code'] = $code;

				$this->data['content'] = 'auth/reset_password';
				$this->_render_page('template/site/site-template', $this->data);
			}
			else
			{
				// do we have a valid request?
				//$this->_valid_csrf_nonce() === FALSE ||
				if ($user->id != $this->input->post('user_id')) 
				{

					// something fishy might be up
					$this->ion_auth->clear_forgotten_password_code($code);

					show_error($this->lang->line('error_csrf'));

				}
				else
				{
					// finally change the password
					$identity = $user->{$this->config->item('identity', 'ion_auth')};

					$change = $this->ion_auth->reset_password($identity, $this->input->post('new_password'));

					if ($change)
					{
						// if the password was successfully changed
						$this->prepare_flashmessage($this->ion_auth->messages(), 0);
						redirect(URL_AUTH_LOGIN, 'refresh');
					}
					else
					{
						$this->prepare_flashmessage($this->ion_auth->errors(), 1);
						redirect(URL_RESET_PASSWORD.'/'.$code, 'refresh');
					}
				}
			}
		}
		else
		{
			// if the code is invalid then send them back to the forgot password page
			$this->prepare_flashmessage($this->ion_auth->errors(), 1);
			redirect(URL_FORGOT_PASSWORD, 'refresh');
		}
	}
	// activate the user
	function activate($id, $code)
	{
		if(empty($id) || empty($code))
		{
			$this->prepare_flashmessage(get_languageword('wrong_opeartion'), 1);
			redirect(URL_AUTH_LOGIN, 'refresh');
		}
		
		$data = array(
			    'activation_code' => NULL,
			    'active'          => 1
		);
		if($this->db->update(TBL_USERS, $data, array('id' => $id, 'activation_code' => $code)))
		{
			$this->prepare_flashmessage(get_languageword('your_account_activated_successfully_please_login'), 0);
			redirect(URL_AUTH_LOGIN, 'refresh');
		}
		else
		{
			$this->prepare_flashmessage(get_languageword('account_not_activated_please_contact_administrator'), 1);
			redirect(URL_AUTH_LOGIN, 'refresh');
		}
	}
	// deactivate the user
	function deactivate($id = NULL)
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
		{
			// redirect them to the home page because they must be an administrator to view this
			return show_error('You must be an administrator to view this page.');
		}

		$id = (int) $id;

		$this->load->library('form_validation');
		$this->form_validation->set_rules('confirm', $this->lang->line('deactivate_validation_confirm_label'), 'required');
		$this->form_validation->set_rules('id', $this->lang->line('deactivate_validation_user_id_label'), 'required|alpha_numeric');

		if ($this->form_validation->run() == FALSE)
		{
			// insert csrf check
			$this->data['csrf'] = $this->_get_csrf_nonce();
			$this->data['user'] = $this->ion_auth->user($id)->row();
			$this->data['content'] = 'auth/deactivate_user';
			$this->_render_page('template/front-template', $this->data);
		}
		else
		{
			// do we really want to deactivate?
			if ($this->input->post('confirm') == 'yes')
			{
				// do we have a valid request?
				if ($this->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id'))
				{
					show_error($this->lang->line('error_csrf'));
				}

				// do we have the right userlevel?
				if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin())
				{
					$this->ion_auth->deactivate($id);
				}
			}

			// redirect them back to the auth page
			redirect('auth', 'refresh');
		}
	}
	// create a new user
	function create_user()
    {
	    $this->data['title'] = "Create User";

        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
        {
            redirect('auth', 'refresh');
        }

        $tables = $this->config->item('tables','ion_auth');
        $identity_column = $this->config->item('identity','ion_auth');
        $this->data['identity_column'] = $identity_column;

        // validate form input
        $this->form_validation->set_rules('first_name', $this->lang->line('create_user_validation_fname_label'), 'required');
        $this->form_validation->set_rules('last_name', $this->lang->line('create_user_validation_lname_label'), 'required');

        if($identity_column !== 'email')
        {
            $this->form_validation->set_rules('identity',$this->lang->line('create_user_validation_identity_label'),'required|is_unique['.$tables['users'].'.'.$identity_column.']');
            $this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'required|valid_email');
        }
        else
        {
            $this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'required|valid_email|is_unique[' . $tables['users'] . '.email]');
        }

        $this->form_validation->set_rules('phone', $this->lang->line('create_user_validation_phone_label'), 'numeric|exact_length[10]');


        $this->form_validation->set_rules('password', $this->lang->line('create_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');

        $this->form_validation->set_rules('password_confirm', $this->lang->line('create_user_validation_password_confirm_label'), 'required');

		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

        if ($this->form_validation->run() == true)
        {

            $email    = strtolower($this->input->post('email'));
            $identity = ($identity_column==='email') ? $email : $this->input->post('identity');
            $password = $this->input->post('password');

            $first_name = ucfirst(strtolower($this->input->post('first_name')));
			$last_name = ucfirst(strtolower($this->input->post('last_name')));
			$username =  $first_name.' '.$last_name;

			$slug = prepare_slug($username, 'slug', 'users');

            $additional_data = array(
                'username' => $username,
                'slug' => $slug,
				'first_name' => $first_name,
                'last_name'  => $last_name,
                'mobile_number'=> $this->input->post('phone'),
				'admin_read_status'      => '0'
            );
        $group = array(GRP_USER);
		
		$id = $this->ion_auth->register($identity, $password, $email, $additional_data, $group);
				
        if ($id)
        {
			$email_template = $this->db->get_where(TBL_PREFIX.TBL_EMAIL_TEMPLATES,array('template_key'=>'registration_template','status'=>'Active'))->result();
			if(count($email_template)>0) 
			{
				$email_template = $email_template[0];			
				$content 	= $email_template->email_template;
				
				$content 	= str_replace("__NAME__", $this->input->post('first_name').' '.$this->input->post('last_name'),$content);						
				$content 	= str_replace("__LOGO__", base_url().'assets/uploads/'.$this->config->item('site_settings')->logo,$content);
				$content 	= str_replace("__CONTACTUS__", $this->config->item('site_settings')->address,$content);

				$content 	= str_replace("__ACTIVATELINK__", SITEURL2,$content);	

				
				$content 		= str_replace("__URL__", SITEURL2,$content);
				$content 		= str_replace("__SITETITLE__", $this->config->item('site_settings')->site_title,$content);
				
				$content 		= str_replace("__COPYRIGHTS__", $this->config->item('site_settings')->rights_reserved_by,$content);
				$content 		= str_replace("__EMAIL__", $email,$content);
				$content 	= str_replace("__PASSWORD__", $password,$content);				
								
				$from 		= $this->config->item('admin_email', 'ion_auth');
				$to 		= $email;
				$sub 		= $this->config->item('site_title', 'ion_auth') 
				. ' - ' . "Welcome Message";
				sendEmail($from, $to, $sub, $content); 
			}
		
			/**default package subscription for user if admin provides**/
			if(isset($this->config->item('quote_settings')->would_you_like_to_provide_package_to_user) && ($this->config->item('quote_settings')->would_you_like_to_provide_package_to_user) == 'Yes')
			{
				if(($this->config->item('quote_settings')->package_id != '') && ($this->config->item('quote_settings')->no_of_days_package_provide > 0) )
				{
					$package = $this->base_model->fetch_records_from(TBL_PACKAGES,array('package_id'=>$this->config->item('quote_settings')->package_id));
					
					$days = '';
					$days = $this->config->item('quote_settings')->no_of_days_package_provide;
					if(count($package)>0)
					{
						$user = getUserRec($id);
						
						$package = $package[0];
						
						$sub_data['package_id'] = $package->package_id;
						$sub_data['subscribed_date'] = date('Y-m-d');
						$sub_data['expire_date'] = date(
													'Y-m-d', 
													strtotime("+ ".$days." days")
													);
						$sub_data['no_of_quotes_provided'] = $package->no_of_quotes;
						$sub_data['no_of_quotes_used'] = 0;
						$sub_data['package_name'] = $package->package_name;
						$sub_data['subscription_duration_in_days'] = $this->config->item('quote_settings')->no_of_days_package_provide;
						$sub_data['package_cost'] = $package->package_cost;
						$sub_data['user_id'] = $id;
						$sub_data['user_name'] = $user->first_name.' '.$user->last_name;
						$sub_data['user_email'] = $user->email;
						$sub_data['status'] = 'Active';
						$sub_data['subscription_type'] = 'gift';
						
						
						if($this->base_model->insert_operation($sub_data,TBL_SUBSCRIPTIONS))
						{
							$user_data['is_premium'] = 1;
							$user_data['package_id'] = $package->package_id;
							$user_data['no_of_package_quotes'] = $package->no_of_quotes;
							$user_data['no_of_package_quotes_used'] = 0;
							
							$whr['id'] = $id;
							$this->base_model->update_operation($user_data,TBL_USERS,$whr);
						}
					}
				}
			}
			/**default package subscription end**/
            // check to see if we are creating the user
            // redirect them back to the admin page
            $this->session->set_flashdata('message', $this->ion_auth->messages());
			if ($this->ion_auth->is_admin())
			{
				$this->prepare_flashmessage(get_languageword('MSG_USER_CREATED'), 0);
				redirect('auth/index', 'refresh');
			}
			else
			{
				redirect("auth", 'refresh');
			}
        }
		}
        else
        {
            // display the create user form
            // set the flash data error message if there is one
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
			if($this->data['message'] != '')
			$this->data['message'] = $this->prepare_message($this->data['message'], 1);
			

            $this->data['first_name'] = array(
                'name'  => 'first_name',
                'id'    => 'first_name',
                'type'  => 'text',
				'required'=>'true',
                'value' => $this->form_validation->set_value('first_name'),
            );
            $this->data['last_name'] = array(
                'name'  => 'last_name',
                'id'    => 'last_name',
                'type'  => 'text',
				'required'=>'true',
                'value' => $this->form_validation->set_value('last_name'),
            );
            $this->data['identity'] = array(
                'name'  => 'identity',
                'id'    => 'identity',
                'type'  => 'text',
				'required'=>'true',
                'value' => $this->form_validation->set_value('identity
                '),
            );
            $this->data['email'] = array(
                'name'  => 'email',
                'id'    => 'email',
                'type'  => 'text',
				'required'=>'true',
                'value' => $this->form_validation->set_value('email'),
            );
           /*  $this->data['company'] = array(
                'name'  => 'company',
                'id'    => 'company',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('company'),
            ); */
            $this->data['phone'] = array(
                'name'  => 'phone',
                'id'    => 'phone',
                'type'  => 'text',
				'required'=>'true',
                'value' => $this->form_validation->set_value('phone'),
            );
            $this->data['password'] = array(
                'name'  => 'password',
                'id'    => 'password',
                'type'  => 'password',
				'required'=>'true',
                'value' => $this->form_validation->set_value('password'),
            );
            $this->data['password_confirm'] = array(
                'name'  => 'password_confirm',
                'id'    => 'password_confirm',
                'type'  => 'password',
				'required'=>'true',
                'value' => $this->form_validation->set_value('password_confirm'),
            );

			$this->data['activemenu'] = 'users';
			$this->data['activesubmenu'] = 'add';
			$this->data['pagetitle'] = get_languageword('create_user');
			$this->data['helptext'] = array();
            $this->data['content'] = 'create_user';
            $this->_render_page('template/admin/admin-template', $this->data);
        }
    }

	// edit a user
	function edit_user($id)
	{
		if (!$this->ion_auth->logged_in() || (!$this->ion_auth->is_admin() && !($this->ion_auth->user()->row()->id == $id)))
		{
			redirect('auth', 'refresh');
		}

		$user = $this->ion_auth->user($id)->row();
		$groups=$this->ion_auth->groups()->result_array();
		$currentGroups = $this->ion_auth->get_users_groups($id)->result();

		if (isset($_POST) && !empty($_POST))
		{
			// do we have a valid request?
			/*
			if ($this->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id'))
			{
				show_error($this->lang->line('error_csrf'));
			}
*/
			$this->form_validation->set_rules('first_name', get_languageword('first_name'), 'trim|required');
			$this->form_validation->set_rules('last_name', get_languageword('last_name'), 'trim|required');
			$this->form_validation->set_rules('phone', get_languageword('phone'), 'trim|required|numeric|exact_length[10]');
			//print_r($this->input->post());
			// update the password if it was posted
			if ($this->input->post('password'))
			{
				$this->form_validation->set_rules('password', $this->lang->line('edit_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
				$this->form_validation->set_rules('password_confirm', $this->lang->line('edit_user_validation_password_confirm_label'), 'required');
			}
			//var_dump(validation_errors());
//var_dump($this->form_validation->run());die();
			if ($this->form_validation->run() === TRUE)
			{
				$data = array(
					'first_name' => $this->input->post('first_name'),
					'last_name'  => $this->input->post('last_name'),
					'mobile_number' => $this->input->post('phone'),
				);
				
				// update the password if it was posted
				if ($this->input->post('password'))
				{
					$data['password'] = $this->input->post('password');
				}

				// Only allow updating groups if user is admin
				if ($this->ion_auth->is_admin())
				{
					//Update the groups user belongs to
					$groupData = $this->input->post('groups');

					if (isset($groupData) && !empty($groupData)) {

						$this->ion_auth->remove_from_group('', $id);

						foreach ($groupData as $grp) {
							$this->ion_auth->add_to_group($grp, $id);
						}

					}
				}
				
			// check to see if we are updating the user
			   if($this->ion_auth->update($user->id, $data))
			    {
			    	// redirect them back to the admin page if admin, or to the base url if non admin
				    //$this->session->set_flashdata('message', $this->ion_auth->messages() );
					$this->prepare_flashmessage(get_languageword('account_information_successfully_updated'), 1);
				    if ($this->ion_auth->is_admin())
					{
						redirect('auth/index', 'refresh');
					}
					else
					{
						redirect('user/index', 'refresh');
					}

			    }
			    else
			    {
			    	// redirect them back to the admin page if admin, or to the base url if non admin
				    $this->session->set_flashdata('message', $this->ion_auth->errors() );
				    if ($this->ion_auth->is_admin())
					{
						redirect('admin/edit_user/'.$id, 'refresh');
					}
					else
					{
						redirect('user/edit_user/'.$id, 'refresh');
					}

			    }

			}
		}

		// display the edit user form
		$this->data['csrf'] = $this->_get_csrf_nonce();

		// set the flash data error message if there is one
		$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

		// pass the user to the view
		$this->data['user'] = $user;
		$this->data['groups'] = $groups;
		$this->data['currentGroups'] = $currentGroups;

		$this->data['first_name'] = array(
			'name'  => 'first_name',
			'id'    => 'first_name',
			'type'  => 'text',
			'required'=>'true',
			'value' => $this->form_validation->set_value('first_name', $user->first_name),
		);
		$this->data['last_name'] = array(
			'name'  => 'last_name',
			'id'    => 'last_name',
			'type'  => 'text',
			'required'=>'true',
			'value' => $this->form_validation->set_value('last_name', $user->last_name),
		);
		
		$this->data['phone'] = array(
			'name'  => 'phone',
			'id'    => 'phone',
			'type'  => 'text',
			'required'=>'true',
			'value' => $this->form_validation->set_value('phone', $user->mobile_number),
		);
		$this->data['password'] = array(
			'name' => 'password',
			'id'   => 'password',
			'type' => 'password'
		);
		$this->data['password_confirm'] = array(
			'name' => 'password_confirm',
			'id'   => 'password_confirm',
			'type' => 'password'
		);

		$this->data['activemenu'] = 'users';
		$this->data['activesubmenu'] = 'add';
		$this->data['pagetitle'] = get_languageword('view_users');
		$this->data['helptext'] = array();
		$this->data['content'] = 'edit_user';
        $this->_render_page('template/admin/admin-template', $this->data);
	}

	// create a new group
	function create_group()
	{
		$this->data['title'] = $this->lang->line('create_group_title');

		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
		{
			redirect('auth', 'refresh');
		}

		// validate form input
		$this->form_validation->set_rules('group_name', $this->lang->line('create_group_validation_name_label'), 'required|alpha_dash');

		if ($this->form_validation->run() == TRUE)
		{
			$new_group_id = $this->ion_auth->create_group($this->input->post('group_name'), $this->input->post('description'));
			if($new_group_id)
			{
				// Only allow updating groups if user is admin
				if ($this->ion_auth->is_admin())
				{
					//Update the groups user belongs to
					$groupData = $this->input->post('modules');

					if (isset($groupData) && !empty($groupData)) {
						$groupData = $this->input->post('modules');
						$this->base_model->update_operation(array('modules' => implode(',',$groupData)), TBL_GROUPS, array('id' => $new_group_id));
						
						$this->base_model->delete_record(TBL_GROUPS_MODULES, 'group_id', $new_group_id);
						foreach ($groupData as $key => $grp) {
							$this->base_model->insert_operation(array('module_id' => $grp, 'group_id' => $new_group_id), TBL_GROUPS_MODULES);
						}

					}
				}
				// check to see if we are creating the group
				// redirect them back to the admin page
				$this->prepare_flashmessage($this->ion_auth->messages(), 0);			
				if($this->input->post( 'submitbutt' ) == 'addnew')
				{
					redirect(URL_AUTH_CREATE_GROUP);
				}
				else
				{
					redirect(URL_AUTH_ROLES);
				}
			}
		}
		else
		{
			// display the create group form
			// set the flash data error message if there is one
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

			$this->data['group_name'] = array(
				'name'  => 'group_name',
				'id'    => 'group_name',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('group_name'),
			);
			$this->data['description'] = array(
				'name'  => 'description',
				'id'    => 'description',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('description'),
			);
			
			$this->data['modules'] = $this->base_model->fetch_records_from(TBL_MODULES,'','','name asc');
			$this->data['activemenu'] = 'roles';
			$this->data['activesubmenu'] = 'add';
			$this->data['pagetitle'] = get_languageword('add_roles');
			$this->data['helptext'] = array();
			$this->data['content'] = 'create_group';			
			$this->_render_page('template/admin/admin-template', $this->data);
		}
	}

	// edit a group
	function edit_group($id)
	{
		// bail if no group id given
		if(!$id || empty($id))
		{
			redirect('auth', 'refresh');
		}

		$this->data['title'] = $this->lang->line('edit_group_title');

		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
		{
			redirect('auth', 'refresh');
		}

		$group = $this->ion_auth->group($id)->row();

		// validate form input
		$this->form_validation->set_rules('group_name', $this->lang->line('edit_group_validation_name_label'), 'required|alpha_dash');

		if (isset($_POST) && !empty($_POST))
		{
			if ($this->form_validation->run() === TRUE)
			{
				$group_update = $this->ion_auth->update_group($id, $_POST['group_name'], $_POST['group_description']);

				if($group_update)
				{
					
					// Only allow updating groups if user is admin
					if ($this->ion_auth->is_admin())
					{
						//Update the groups user belongs to
						$groupData = $this->input->post('modules');
						$this->base_model->update_operation(array('modules' => implode(',',$groupData)), TBL_GROUPS, array('id' => $id));
						if (isset($groupData) && !empty($groupData)) {

							$this->base_model->delete_record(TBL_GROUPS_MODULES, 'group_id', $id);

							
							foreach ($groupData as $grp) {
								
								$this->base_model->insert_operation(array('group_id' => $id, 'module_id' => $grp), TBL_GROUPS_MODULES);
							
							}

						}						
					}
					$this->prepare_flashmessage($this->lang->line('edit_group_saved'), 0);
				}
				else
				{
					$this->prepare_flashmessage($this->ion_auth->errors(), 0);
				}
				if($this->input->post( 'submitbutt' ) == 'addnew')
				{
					redirect(URL_AUTH_CREATE_GROUP);
				}
				else
				{
					redirect(URL_AUTH_ROLES);
				}				
			}
		}

		// set the flash data error message if there is one
		$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

		// pass the user to the view
		$this->data['group'] = $group;

		$readonly = $this->config->item('admin_group', 'ion_auth') === $group->name ? 'readonly' : '';

		$this->data['group_name'] = array(
			'name'    => 'group_name',
			'id'      => 'group_name',
			'type'    => 'text',
			'value'   => $this->form_validation->set_value('group_name', $group->name),
			$readonly => $readonly,
		);
		$this->data['group_description'] = array(
			'name'  => 'group_description',
			'id'    => 'group_description',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('group_description', $group->description),
		);

		$this->data['modules'] = $this->base_model->fetch_records_from(TBL_MODULES,'','','name asc');
		$this->data['activemenu'] = 'roles';
		$this->data['activesubmenu'] = 'add';
		$this->data['pagetitle'] = get_languageword('add_roles');
		$this->data['helptext'] = array();
		$this->data['content'] = 'edit_group';			
		$this->_render_page('template/admin/admin-template', $this->data);
	}


	function _get_csrf_nonce()
	{
		$this->load->helper('string');
		$key   = random_string('alnum', 8);
		$value = random_string('alnum', 20);
		$this->session->set_flashdata('csrfkey', $key);
		$this->session->set_flashdata('csrfvalue', $value);

		return array($key => $value);
	}

	function _valid_csrf_nonce()
	{
		if ($this->input->post($this->session->flashdata('csrfkey')) !== FALSE &&
			$this->input->post($this->session->flashdata('csrfkey')) == $this->session->flashdata('csrfvalue'))
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
	
	///Custom Functions
	function roles()
	{
		$this->data['message'] = $this->session->flashdata('message');		
		$this->data['content'] = 'roles';
		$this->data['activemenu'] = 'users';
		$this->data['activesubmenu'] = 'view';
		$this->data['pagetitle'] = get_languageword('view_roles');
		$this->data['helptext'] = array();
		$this->data['ajaxrequest'] = array(
			'url' => URL_AUTH_AJAX_GET_DATA,
			'disablesorting' => '0,3',
		);
		$this->_render_page('template/admin/admin-template', $this->data);
	}
	
	/**
	 * Diaplays data
	 *
	 * @param	mixed	$type
	 * @return	void
	 */
	function ajax_get_data()
	{
		if($this->input->post())
		{
			$data = array();
			$no = $_POST['start'];
			$columns = array('id', 'name', 'group_status');
			$condition = array('1' => '1');
			$records = $this->base_model->get_datatables(TBL_GROUPS, 'auto', $condition, $columns, array('name' => 'asc'));
			
			foreach($records as $record)
			{
				$no++;
				$row = array();				
				$row[] = '<input id="checkbox-'.$record->id.'" class="checkbox-custom checkbox_class" name="ids[]" type="checkbox" value="'.$record->id.'" onclick="javascript:deselectall_check(\'selectall\')">
				<label for="checkbox-'.$record->id.'" class="checkbox-custom-label"> </label>';			
				$row[] = $record->name;
				$row[] = '
			  <div class="digiCrud"><i class="fa fa-tv" data-toggle="tooltip" data-placement="top" title="View"></i></div>
			  ';
				$checked = '';
				if($record->group_status == 'Active')
				$checked = ' checked';
				//add html for action
				$row[] = '<div class="digiCrud">							
					<a data-toggle="modal" data-target="#deletemodal" onclick="delete_record('.$record->id . ',\''.URL_AUTH_DELETE_GROUP.'\')">
						<i class="flaticon-round73" data-toggle="tooltip" data-placement="left" title="Remove"></i>
					</a>
				</div>
				
				<div class="digiCrud">
					<a href="'.URL_AUTH_EDIT_GROUP . '/' . $record->id . '">
						<i class="flaticon-pencil124" data-toggle="tooltip" data-placement="top" title="Edit"></i>
					</a>
				</div>
				
				<div class="digiCrud">
				  <div class="slideThree slideBlue">
					
					<input type="checkbox" value="' . $record->id . '" id="status_' . $record->id . '" name="check_' . $record->id . '" onclick="statustoggle(this, \'' .  URL_AUTH_GROUPSTATUSTOGGLE .'\')"'.$checked . '/>
					<label for="status_' . $record->id . '"></label>
				  </div>
				</div>
				';
				$data[] = $row;
			}
			$output = array(
					"draw" => $_POST['draw'],
					"recordsTotal" => $this->base_model->count_all(TBL_GROUPS, $condition),
					"recordsFiltered" => $this->base_model->count_filtered(TBL_GROUPS, 'auto', $condition, $columns, array('category_name' => 'asc')),
					"data" => $data,
			);
			
			echo json_encode($output);
		}
	}
	
	/**
	* This will delete the record
	* @param	Int $id
	* @return	void
	*/
	function delete_group()
	{
		$id = $this->input->post('id');
		if(!empty($id))
		{
			$ids = explode(',', $id);
			$details = $this->base_model->fetch_records_from_in(TBL_GROUPS, 'id', $ids);
			if(count($details) > 0)
			{
				$this->base_model->delete_record(TBL_GROUPS, 'id',$ids);
				$this->base_model->delete_record(TBL_USERS_GROUPS, 'group_id',$ids);				
				echo json_encode(array('status' => 1, 'message' => get_languageword('MSG_GROUP_DELETED'), 'action' => get_languageword('success')));
			}
			else
			{
				echo json_encode(array('status' => 0, 'message' => get_languageword('MSG_DELETE_FAILED'), 'action' => get_languageword('failed')));
			}
		}
		else
		{
			echo json_encode(array('status' => 0, 'message' => get_languageword('MSG_DELETE_FAILED'), 'action' => get_languageword('failed')));	
		}
	}
	
	function groupstatustoggle()
	{
		if($this->input->post())
		{
			$id = $this->input->post('id');
			$ids = explode(',', $id);
			$category_status = $this->input->post('status');
			$filedata = array();
			$message = '';
			if($category_status == 'false')
			{
				$filedata['group_status'] = 'In-Active';
				$message = get_languageword('MSG_GROUP_DEACTIVATED');
			}
			else
			{
				$filedata['group_status'] = 'Active';				
				$message = get_languageword('MSG_GROUP_ACTIVATED');
			}	
			$this->base_model->update_operation_in( $filedata, TBL_GROUPS, 'id', $ids );
			echo json_encode(array('status' => 1, 'message' => $message, 'action' => get_languageword('success')));
		} 
		else
		{
			$this->prepare_flashmessage(get_languageword('MSG_WRONG_OPERATION'), 1);
			redirect(URL_AUTH_ROLES);
		}
	}
	
	function facebooklogin()
	{
		$this->load->library('facebook'); // Automatically picks appId and secret from config
		
		$user = $this->facebook->getUser();
	
		if ($user) {
			try {
				$facebook_profile_data= $this->facebook->api('/me');				
				$check=$this->base_model->fetch_records_from('users',array('email' => $facebook_profile_data['email']));
				if(count($check) == 0)
				{			
					$email = $facebook_profile_data['email'];
					$additional_data = array(
							'username' => $email,
							'first_name' => trim($facebook_profile_data['first_name']),
							'last_name' =>trim($facebook_profile_data['last_name']),
							'is_social_login' => 'Yes',
							'facebook' =>  $facebook_profile_data['id'],
					);
					$group = array(GRP_USER);
					$identity = $email;
					$password = $this->randomString(8);
					
					$this->ion_auth->register($identity, $password, $email, $additional_data,$group,TRUE);
					
					$details = $this->base_model->fetch_records_from('users', array('email' => $email));
					if(count($details) > 0)
					{												
						$this->base_model->update_operation(array('active' => 1, 'is_social_login' => 'Yes', 'facebook' => $facebook_profile_data['id']), 'users', array('id' => $details[0]->id));
					}						
				}
				else
				{
					$identity = $check[0]->email;
					$password = $this->randomString(8);				
				}
				//echo $identity.'##'.$password;die();
				if ($this->ion_auth->login($identity, $password, FALSE))
				{	
					$this->prepare_flashmessage($this->ion_auth->messages(), 0);
					if($this->ion_auth->is_admin()) {
					redirect(URL_ADMIN_INDEX, 'refresh');					
					}
					else{						 
						redirect(URL_USER_INDEX, 'refresh');					
					}
				}
				else
				{
					$this->prepare_flashmessage(get_languageword('unable_to_login_please_try_again').':Login', 1);	
					redirect(URL_AUTH_LOGIN);
				}
				
			} catch (FacebookApiException $e) {
				$user = null;
			}
		}else {
			// Solves first time login issue. (Issue: #10)
			//$this->facebook->destroySession();
		}
		
		if ($user) {
		
			$data['logout_url'] = URL_AUTH_FACEBOOKLOGOUT; // Logs off application
		  
		
		} else {
			$data['login_url'] = $this->facebook->getLoginUrl(array(
					'redirect_uri' => URL_AUTH_FACEBOOKLOGIN,
					'scope' => array("email") // permissions here
			));
			redirect($data['login_url'] );
		}	
	}
	
	function twitterlogin()
	{
		$this->reset_sessiontwitter();
		//if($this->session->userdata('access_token') && $this->session->userdata('access_token_secret'))
		if(1==2)
		{
			// User is already authenticated. Add your user notification code here.
			
			redirect( URL_USER_INDEX );
		}
		else
		{
			// Making a request for request_token
			$request_token = $this->twitterconnection->getRequestToken( URL_AUTH_TWITTERCALLBACK );
			
			$this->session->set_userdata('request_token', $request_token['oauth_token']);
			$this->session->set_userdata('request_token_secret', $request_token['oauth_token_secret']);
			//var_dump($this->twitterconnection);die();
			if($this->twitterconnection->http_code == 200)
			{
				$url = $this->twitterconnection->getAuthorizeURL($request_token);
				redirect($url);
			}
			else
			{
				$this->prepare_flashmessage(get_languageword('authentication_failed_please_try_again').':twitterlogin', 1);
				// An error occured. Make sure to put your error notification code here.
				redirect( URL_AUTH_LOGIN );
			}
		}
	}
	
	/**
	 * Reset session data
	 * @access	private
	 * @return	void
	 */
	public function reset_sessiontwitter()
	{
		$this->session->unset_userdata('access_token');
		$this->session->unset_userdata('access_token_secret');
		$this->session->unset_userdata('request_token');
		$this->session->unset_userdata('request_token_secret');
		$this->session->unset_userdata('twitter_user_id');
		$this->session->unset_userdata('twitter_screen_name');
		$this->session->set_flashdata('message', 'Logout Success');
		//redirect( TWITTER_LOGIN_URL );
	}

	/**
	 * Callback function, landing page for twitter.
	 * @access	public
	 * @return	void
	 */
	public function twittercallback()
	{
		
		if($this->input->get('oauth_token') && $this->session->userdata('request_token') !== $this->input->get('oauth_token'))
		{
			$this->reset_sessiontwitter();
			$this->prepare_flashmessage(get_languageword('authentication_failed_please_try_again'), 1);
			redirect( URL_AUTH_LOGIN );
		}
		else
		{
			$access_token = $this->twitterconnection->getAccessToken($this->input->get('oauth_verifier'));
		
			if ($this->twitterconnection->http_code == 200)
			{
				$email = $access_token['user_id'].'@twitter.com';
				$check = $this->base_model->fetch_records_from(TBL_USERS, array('twitter_id' => $access_token['user_id'], 'email' => $email));
				
				$this->session->set_userdata('access_token', $access_token['oauth_token']);
				$this->session->set_userdata('access_token_secret', $access_token['oauth_token_secret']);
				$this->session->set_userdata('twitter_user_id', $access_token['user_id']);
				$this->session->set_userdata('twitter_screen_name', $access_token['screen_name']);				
				if(count($check)  == 0)
				{					
					$additional_data = array(
						'username' => $email,
						'first_name' 	=> trim($access_token['screen_name']),
						'last_name'  	=> '',
						'current_language'      => 'english',
						'is_social_login' => 'Yes',
						'twitter_id' => $access_token['user_id'],					
					);
					$group = array(GRP_USER);
					$identity = $email;
					$password = $this->randomString(8);
					$this->ion_auth->register($identity, $password, $email, $additional_data,$group,TRUE);
					
					$details = $this->base_model->fetch_records_from(TBL_USERS, array('twitter_id' => $access_token['user_id'], 'email' => $email));
					if(count($details) > 0)
					{
						$email_template = $this->db->get_where(TBL_PREFIX.TBL_EMAIL_TEMPLATES,array('template_key'=>'registration_template','status'=>'Active'))->result();
						if(count($email_template)>0) 
						{
							$email_template = $email_template[0];			
							$content 	= $email_template->email_template;
							
							$content 	= str_replace("__NAME__", $details[0]->first_name.' '.$details[0]->last_name,$content);						
							$content 	= str_replace("__LOGO__", base_url().'assets/uploads/'.$this->config->item('site_settings')->logo,$content);
							$content 	= str_replace("__CONTACTUS__", $this->config->item('site_settings')->address,$content);
							
							$user = $this->db->get_where(TBL_PREFIX.TBL_USERS,array('email'=>$identity))->result();
							
							$content 	= str_replace("__ACTIVATELINK__", SITEURL2,$content);	
							
							
							$content 		= str_replace("__URL__", SITEURL2,$content);
							$content 		= str_replace("__SITETITLE__", $this->config->item('site_settings')->site_title,$content);
							
							$content 		= str_replace("__COPYRIGHTS__", $this->config->item('site_settings')->rights_reserved_by,$content);
							$content 		= str_replace("__EMAIL__", $identity,$content);
							$content 	= str_replace("__PASSWORD__", $password,$content);				
											
							$from 		= $this->config->item('admin_email', 'ion_auth');
							$to 		= $identity;
							$sub 		= $this->config->item('site_title', 'ion_auth') 
							. ' - ' . "Welcome Message";
							sendEmail($from, $to, $sub, $content); 
						}
						/**default package subscription for user if admin provides**/
						if(isset($this->config->item('quote_settings')->would_you_like_to_provide_package_to_user) && ($this->config->item('quote_settings')->would_you_like_to_provide_package_to_user) == 'Yes')
						{
							if(($this->config->item('quote_settings')->package_id != '') && ($this->config->item('quote_settings')->no_of_days_package_provide > 0) )
							{
								$package = $this->base_model->fetch_records_from(TBL_PACKAGES,array('package_id'=>$this->config->item('quote_settings')->package_id));
								
								$days = '';
								$days = $this->config->item('quote_settings')->no_of_days_package_provide;
								if(count($package)>0)
								{
									$user = getUserRec($details[0]->id);
									
									$package = $package[0];
									
									$sub_data['package_id'] = $package->package_id;
									$sub_data['subscribed_date'] = date('Y-m-d');
									$sub_data['expire_date'] = date(
																'Y-m-d', 
																strtotime("+ ".$days." days")
																);
									$sub_data['no_of_quotes_provided'] = $package->no_of_quotes;
									$sub_data['no_of_quotes_used'] = 0;
									$sub_data['package_name'] = $package->package_name;
									$sub_data['subscription_duration_in_days'] = $this->config->item('quote_settings')->no_of_days_package_provide;
									$sub_data['package_cost'] = $package->package_cost;
									$sub_data['user_id'] = $details[0]->id;
									$sub_data['user_name'] = $user->first_name.' '.$user->last_name;
									$sub_data['user_email'] = $user->email;
									$sub_data['status'] = 'Active';
									$sub_data['subscription_type'] = 'gift';
									
									
									if($this->base_model->insert_operation($sub_data,TBL_SUBSCRIPTIONS))
									{
										$user_data['is_premium'] = 1;
										$user_data['package_id'] = $package->package_id;
										$user_data['no_of_package_quotes'] = $package->no_of_quotes;
										$user_data['no_of_package_quotes_used'] = 0;
										
										$whr['id'] = $details[0]->id;
										$this->base_model->update_operation($user_data,TBL_USERS,$whr);
									}
								}
							}
						}
						/**default package **/
						$this->base_model->update_operation(array('active' => 1), TBL_USERS, array('id' => $details[0]->id));
					}
				}
				else
				{
					$identity = $check[0]->email;
					$password = $this->randomString(8);
				}				
				if ($this->ion_auth->login($identity, $password, FALSE))
				{
					$this->session->unset_userdata('request_token');
					$this->session->unset_userdata('request_token_secret');
					$this->prepare_flashmessage($this->ion_auth->messages(), 0);
					if($this->ion_auth->is_admin()) {
					redirect(URL_ADMIN_INDEX, 'refresh');					
					}
					else{						 
						redirect(URL_USER_INDEX, 'refresh');					
					}
				}
				else
				{
					$this->prepare_flashmessage(get_languageword('unable_to_login_please_try_again').':Login', 1);	
					redirect(URL_AUTH_LOGIN);
				}
			}
			else
			{
				$this->prepare_flashmessage(get_languageword('unable_to_login_please_try_again'), 1);
				redirect( URL_AUTH_LOGIN );
			}
		}
	}
	
	function isAdmin()
	{
		$group = array('admin');
		if (!$this->ion_auth->in_group($group)) {
			$this->prepare_flashmessage(get_languageword('MSG_NO_ENTRY'),2);
			redirect(getUserType());
		}
	}
	/***delete user***/
	function delete_record()
	{
		//remove all user data in all tables-if delete user.
		$this->isAdmin();
		$id = $this->input->post('id');
		$redirection = $this->input->post('redirection');
		if(!empty($id))
		{			
			$ids = explode(',', $id);
			$details = $this->base_model->fetch_records_from_in(TBL_USERS, 'id', $ids);
			if(count($details) > 0)
			{
				$this->base_model->delete_record(TBL_USERS, 'id',$ids);
				$this->base_model->delete_record(TBL_USERS_GROUPS, 'user_id',$ids);
				
				//
				$this->base_model->delete_record(TBL_USERS_SENT_QUOTES, 'user_id',$ids);
				$this->base_model->delete_record(TBL_PAYMENTS, 'payer_id',$ids);
				$this->base_model->delete_record(TBL_SAVED_QUOTES, 'user_id',$ids);
				$this->base_model->delete_record(TBL_SUBMIT_QUOTES, 'user_id',$ids);
				$this->base_model->delete_record(TBL_SUBSCRIPTIONS, 'user_id',$ids);
				//
				
				$this->load->helper("file");
				foreach($details as $recod)
				{
					if($recod->logo_profile_image != '')
					{
						if(file_exists(URL_PUBLIC_UPLOADS . 'profiles' . DS . $recod->logo_profile_image))
						{
							unlink(URL_PUBLIC_UPLOADS . 'profiles' . DS . $recod->logo_profile_image);
						}
						if(file_exists(URL_PUBLIC_UPLOADS . 'profiles/thumbs' . DS . $recod->logo_profile_image))
						{
							unlink(URL_PUBLIC_UPLOADS . 'profiles/thumbs' . DS . $recod->logo_profile_image);
						}
					}
				}
				if($redirection == 'yes')
					$this->prepare_flashmessage(get_languageword('MSG_USER_DELETED'), 0);
				echo json_encode(array('status' => 1, 'message' => get_languageword('MSG_USER_DELETED'), 'action' => get_languageword('success'), 'redirection' => $redirection, 'url' => URL_AUTH_INDEX));
			}
			else
			{
				echo json_encode(array('status' => 0, 'message' => get_languageword('MSG_DELETE_FAILED'), 'action' => get_languageword('failed'), 'redirection' => $redirection));
			}
		}
		else
		{
			echo json_encode(array('status' => 0, 'message' => get_languageword('MSG_DELETE_FAILED'), 'action' => get_languageword('failed'), 'redirection' => $redirection));	
		}
	}
	
function google_openid()
{
    phpinfo();die();
	parse_str($_SERVER['QUERY_STRING'],$_GET);
	
	$this->load->library('LightOpenID', SITEURL2);
	try {
		
		if(!isset($_GET['openid_mode'])) {
		
			$openid = new LightOpenID(SITEURL2);
			
			$openid->identity = 'https://www.google.com/accounts/o8/id';
			//http://code.google.com/p/lightopenid/wiki/GettingMoreInformation
			$openid->required = array(
				'namePerson/first',
				'namePerson/last',
				'contact/email',
				'contact/country/home', //Country
				'pref/language',
				'namePerson/friendly', //Alias/Username
			);
			
			$openid->returnUrl = URL_AUTH_GOOGLE_OPENID;
			header('Location: ' . $openid->authUrl());
		}
		elseif($_GET['openid_mode'] == 'cancel')
			echo 'User has canceled authentication!';
		else {
			$openid = new LightOpenID(base_url());
			if($openid->validate()){
				$data = $openid->getAttributes();
				$email = $data['contact/email'];
				$first = $data['namePerson/first'];
				$check = $this->user_model->get_members(array( 'email_address' => $email ));
			
				if( count( $check ) > 0)
				{
					$this->session->set_userdata('member_id', $check[0]->member_id);
					$this->session->set_userdata('member_type', $check[0]->member_type);
					$this->session->set_userdata('member_name', $check[0]->member_fname . ' ' . $check[0]->member_lname);
					$this->session->set_userdata('logintype', 'google');
					
				}
				else
				{
					$inputdata['member_fname'] = $first;
					$inputdata['member_lname'] = $data['namePerson/last'];
					$inputdata['user_name'] = $email;
					$inputdata['password'] = $this->generateRandpassword();
					$inputdata['email_address'] = $email;
					$inputdata['country'] = $data['contact/country/home'];
					$inputdata['prefered_language_communication'] = $data['pref/language'];
					$inputdata['created_date'] = date('Y-m-d H:i:s');
					//$inputdata['facebook_id'] = $user['id'];
					$insertid = $this->user_model->insert_operation( $inputdata, 'members' );
					$this->session->set_userdata('member_id', $insertid);
					$this->session->set_userdata('member_type', 'user');
					$this->session->set_userdata('logintype', 'google');
					$this->session->set_userdata('member_name', $data['namePerson/first'] . ' ' . $data['namePerson/last']);
				}
				redirect('user/index');
			/*
				echo "Identity : $openid->identity <br>";
				echo "Email : $email <br>";
				echo "First name : $first";
				*/
			}
			else
			{
			redirect('user/login');
			}
		}
		
    }
    catch(ErrorException $e) {
    echo $e->getMessage();
    }
 }
}