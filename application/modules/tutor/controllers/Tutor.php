<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Tutor extends MY_Controller 
{
	function __construct()
	{
		parent::__construct();
		
		$this->load->library(array('session'));
		$this->load->library(array('ion_auth','form_validation'));
		$this->load->model('tutor_model');

		$this->check_tutor_access();

		$this->data['my_profile'] = getUserRec();
	}
	/**
	 * Generate the index page
	 *
	 * @access	public
	 * @return	string
	 */
	function index()
	{

		$this->data['message'] = $this->session->flashdata('message');

		$user_id = $this->ion_auth->get_user_id();
		$tutor_dashboard_data = $this->tutor_model->get_tutor_dashboard_data($user_id);
		$this->data['tutor_dashboard_data']	= $tutor_dashboard_data;
		$inst_tutor_dashboard = $this->tutor_model->get_inst_tutor_dashboard($user_id);
		$this->data['inst_tutor_dashboard']	= $inst_tutor_dashboard;
		$profile = getUserRec();
		$this->data['pagetitle'] 	= get_languageword('dashboard');
		$this->data['activemenu'] 	= "dashboard";
		$this->data['activesubmenu'] = "dashboard";
		$this->data['content'] 		= 'index';
		$this->_render_page('template/site/tutor-template', $this->data);
	}
	
	/**
	 * Fecilitates to upload gallery pictures
	 *
	 * @access	public
	 * @return	string
	 */
	function my_gallery()
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_tutor()) {
			$this->prepare_flashmessage(get_languageword('You dont have permission to access this page'), 1);
			redirect('auth/login', 'refresh');
		}
		$this->data['activemenu'] 	= "account";
		$this->data['pagetitle'] = get_languageword('My Gallery');

		
		$this->load->library('Image_crud');
		$image_crud = new image_CRUD();
		$image_crud->unset_jquery(); //As we are using our jQuery bundle we need to unset default jQuery
		
		$image_crud->set_table($this->db->dbprefix('gallery'));
		$image_crud->set_relation_field('user_id');
		$image_crud->set_ordering_field('image_order');
		
		$image_crud->set_title_field('image_title');
		$image_crud->set_primary_key_field('image_id');
		$image_crud->set_url_field('image_name');
		$image_crud->set_image_path('assets/uploads/gallery');
		$output = $image_crud->render();
		$output->grocery = TRUE;
		$this->data['grocery_output'] = $output;
		$this->data['grocery'] = TRUE;
		$this->data['activesubmenu'] = "gallery";
		$this->grocery_output($this->data);
	}



	/**
	 * Facilitates to update personal information
	 *
	 * @access	public
	 * @return	string
	 */
	function personal_info()
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_tutor()) {
			$this->prepare_flashmessage(get_languageword('You dont have permission to access this page'), 1);
			redirect('auth/login', 'refresh');
		}
		
		$this->data['message'] = $this->session->flashdata('message');
		
		$user_id = $this->ion_auth->get_user_id();

		if(isset($_POST['submitbutt']))
		{

			$this->form_validation->set_rules('first_name', get_languageword('first_name'), 'trim|required|xss_clean');
			$this->form_validation->set_rules('dob', get_languageword('date_of_birth'), 'trim|required|xss_clean');
			$this->form_validation->set_rules('paypal_email', get_languageword('paypal_email'), 'trim|required|valid_email|xss_clean');
			$this->form_validation->set_error_delimiters('<div class="error">', '</div>');

			if ($this->form_validation->run() == TRUE)
			{
				$first_name = ucfirst(strtolower($this->input->post('first_name')));
				$last_name = ucfirst(strtolower($this->input->post('last_name')));
				$username =  $first_name.' '.$last_name;

				$prev_username = $this->base_model->fetch_value('users', 'username', array('id' => $user_id));

				//If user updates the username
				if($prev_username != $username) {
					$slug = prepare_slug($username, 'slug', 'users');
					$inputdata['slug'] =  $slug;
				}

				$inputdata['first_name'] = $first_name;
				$inputdata['last_name'] = $last_name;
				$inputdata['username'] = $username;
				$inputdata['gender'] = $this->input->post('gender');
				$inputdata['dob'] = $this->input->post('dob');
				$inputdata['website'] = $this->input->post('website');
				$inputdata['facebook'] = $this->input->post('facebook');
				$inputdata['twitter'] = $this->input->post('twitter');
				$inputdata['linkedin'] = $this->input->post('linkedin');
				$inputdata['paypal_email'] = $this->input->post('paypal_email');
				$inputdata['bank_ac_details'] = $this->input->post('bank_ac_details');
				
				$language_of_teaching = $this->input->post('language_of_teaching');
				if(!empty($language_of_teaching))
				$inputdata['language_of_teaching'] = implode(', ', $language_of_teaching);
			// echo'<pre>';print_r($inputdata);die();
				
				$this->base_model->update_operation($inputdata, 'users', array('id' => $user_id));
				
				$this->prepare_flashmessage(get_languageword('profile updated successfully'), 0);
				redirect('tutor/personal-info');				
			}
			else
			{
				$this->data['message'] = $this->prepare_message(validation_errors(), 1);
			}
		}	
		$this->data['profile'] = getUserRec();
		
		//Preparing Language options
		$lng_opts = $this->db->get_where('languages',array('status' => 'Active'))->result();
		$options = array();
		if(!empty($lng_opts))
		{
			foreach($lng_opts as $row):
				$options[$row->name] = $row->name;
			endforeach;
		}

		$this->data['language_options'] = $options;
		$this->data['activemenu'] 	= "account";
		$this->data['activesubmenu'] = "personal_info";
		$this->data['pagetitle'] = get_languageword('personal_information');
		$this->data['content'] 		= 'personal_info';
		$this->_render_page('template/site/tutor-template', $this->data);
	}
	
	/**
	 * Fecilitates to update profile information includes profile picture
	 *
	 * @access	public
	 * @return	string
	 */
	function profile_information()
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_tutor()) {
			$this->prepare_flashmessage(get_languageword('You dont have permission to access this page'), 1);
			redirect('auth/login', 'refresh');
		}
		
		$this->data['message'] = $this->session->flashdata('message');
		
		if(isset($_POST['submitbutt']))
		{
			$this->form_validation->set_rules('experience_desc', get_languageword('experience_description'), 'trim|required|max_length[500]|xss_clean');
			$this->form_validation->set_rules('profile', get_languageword('profile_description'), 'trim|required|max_length[500]|xss_clean');
			$this->form_validation->set_rules('seo_keywords',get_languageword('seo_keywords'), 'trim|max_length[100]|xss_clean');
			$this->form_validation->set_rules('meta_desc',get_languageword('meta_description'),'trim|max_length[100]|xss_clean');
			$this->form_validation->set_rules('teaching_experience', get_languageword('teaching_experience'), 'trim|required|xss_clean');
			$this->form_validation->set_rules('willing_to_travel', get_languageword('willing_to_travel'), 'trim|required|xss_clean');
			$this->form_validation->set_rules('qualification', get_languageword('qualification'), 'trim|required|xss_clean');
			$this->form_validation->set_rules('profile_page_title', get_languageword('profile_page_title'), 'trim|required|xss_clean');
			if($_FILES['photo']['name'] != '')
			{
				$this->form_validation->set_rules('photo', get_languageword('Profile Image'), 'trim|callback__image_check');
			}
			
			$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
			
			if ($this->form_validation->run() == TRUE)
			{
				$user_id = $this->ion_auth->get_user_id();
				$inputdata['experience_desc'] = $this->input->post('experience_desc');
				$inputdata['profile'] 		  = $this->input->post('profile');
				$inputdata['profile_page_title'] = $this->input->post('profile_page_title');
				$inputdata['qualification'] = $this->input->post('qualification');
				$inputdata['seo_keywords'] = $this->input->post('seo_keywords');
				$inputdata['meta_desc'] = $this->input->post('meta_desc');
				$inputdata['teaching_experience'] = $this->input->post('teaching_experience');
				$inputdata['duration_of_experience'] = $this->input->post('duration_of_experience');
				$inputdata['willing_to_travel'] = $this->input->post('willing_to_travel');
				$inputdata['own_vehicle'] = $this->input->post('own_vehicle');
				
				$image 	= $_FILES['photo']['name'];
				//Upload User Photo
				if (!empty($image)) 
				{					
					$ext = pathinfo($image, PATHINFO_EXTENSION);
					$file_name = $user_id.'.'.$ext;
					$config['upload_path'] 		= 'assets/uploads/profiles/';
					$config['allowed_types'] 	= 'jpg|jpeg|png';
					$config['overwrite'] 		= true;
					$config['file_name']        = $file_name;
					$this->load->library('upload', $config);
					
					if($this->upload->do_upload('photo'))
					{
						$inputdata['photo']		= $file_name;
						$this->create_thumbnail($config['upload_path'].$config['file_name'],'assets/uploads/profiles/thumbs/'.$config['file_name'], 200, 200);		
					}
				}				
				$this->base_model->update_operation($inputdata, 'users', array('id' => $user_id));
				
				$this->prepare_flashmessage(get_languageword('profile updated successfully'), 0);
				redirect('tutor/profile-information');				
			}
			else
			{
				$this->data['message'] = $this->prepare_message(validation_errors(), 1);
			}
		}	
		$this->data['profile'] = getUserRec();
		$degrees = array();
		$records = $this->base_model->fetch_records_from('terms_data', array('term_type' => 'degree', 'term_status' => 'Active'));
		if(!empty($records))
		{
			foreach($records as $record)
			{
				$degrees[$record->term_id] = $record->term_title;
			}
		}
		$this->data['degrees'] = $degrees;
		
		$years = array();
		for($y = 0; $y < 100; $y++)
		{
			$year = date('Y');
			$years[$year-$y] = $year-$y;
		}
		$this->data['years'] = $years;
		$this->data['activemenu'] 	= "account";
		$this->data['activesubmenu'] = "profile_information";
		$this->data['pagetitle'] = get_languageword('profile_information');;
		$this->data['content'] 		= 'profile_information';
		$this->_render_page('template/site/tutor-template', $this->data);
	}
	
	public function _image_check()
	{
		$image = $_FILES['photo']['name'];
		$name = explode('.',$image);
		
		if(count($name)>2 || count($name)<= 0) {
           $this->form_validation->set_message('_image_check', 'Only jpg / jpeg / png images are accepted.');
            return FALSE;
        }
		
		$ext = $name[1];
		
		$allowed_types = array('jpg','jpeg','png');
		
		if (!in_array($ext, $allowed_types))
		{			
			
			$this->form_validation->set_message('_image_check', 'Only jpg / jpeg / png images are accepted.');
			
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	
	/**
	 * Fecilitates to add / update education information
	 *
	 * @access	public
	 * @return	string
	 */
	function experience($param1 = null, $param2 = null)
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_tutor()) {
			$this->prepare_flashmessage(get_languageword('You dont have permission to access this page'), 1);
			redirect('auth/login', 'refresh');
		}
		
		$this->data['message'] = $this->session->flashdata('message');
		
		if($param1 == 'delete' && $param2 != '')
		{
			$this->base_model->delete_record_new('users_experience', array('record_id' => $param2));
			$this->prepare_flashmessage(get_languageword('record deleted successfully'), 0);				
			redirect('tutor/experience');	
		}
		
		if(isset($_POST['submitbutt']))
		{
			$this->form_validation->set_rules('company', get_languageword('company_name'), 'trim|required|xss_clean');
			$this->form_validation->set_rules('description', get_languageword('description'), 'trim|required|xss_clean');
			$this->form_validation->set_rules('role', get_languageword('role'), 'trim|required|xss_clean');
			
			$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
			
			if ($this->form_validation->run() == TRUE)
			{
				$inputdata['company'] = $this->input->post('company');
				$inputdata['role'] = $this->input->post('role');
				$inputdata['description'] = $this->input->post('description');
				if($this->input->post('to_month') == 'Present')
					 $year = ' ';
				else
					$year = $this->input->post('to_year');

				$inputdata['from_date'] = $this->input->post('from_month').' '. $this->input->post('from_year');
				$inputdata['to_date'] = $this->input->post('to_month').' '. $year;
			
				$inputdata['user_id'] = $this->ion_auth->get_user_id();
				$update_rec_id = $this->input->post('update_rec_id');
				if($update_rec_id != '')
				{
				$inputdata['updated_at'] = date ("Y-m-d H:i:s");
				$this->base_model->update_operation($inputdata, 'users_experience', array('record_id' => $update_rec_id));
				$this->prepare_flashmessage(get_languageword('record updated successfully'), 0);
				}
				else
				{
			
				$inputdata['created_at'] = date ("Y-m-d H:i:s");
				$inputdata['updated_at'] = $inputdata['created_at'];
				$this->base_model->insert_operation($inputdata,'users_experience');
				$this->prepare_flashmessage(get_languageword('record added successfully'), 0);
				}				
				redirect('tutor/experience');				
			}
			else
			{
				$this->data['message'] = $this->prepare_message(validation_errors(), 1);
			}
		}	
		$this->data['profile'] = array();
		if($param1 == 'edit' && $param2 != '')
		{
			$profile = $this->base_model->fetch_records_from('users_experience', array('record_id' => $param2));
			if(!empty($profile))
			$this->data['profile'] = $profile[0];
		}
		$this->data['educations'] = $this->base_model->fetch_records_from('users_experience', array('user_id' => $this->ion_auth->get_user_id()));
			
		$years = array();
		for($y = 0; $y < 100; $y++)
		{
			$year = date('Y');
			$years[$year-$y] = $year-$y;
		}
		
		$months= array("Present"=>"Present","January" => "January","February"=> "February","March" => "March","April" => "April","May" => "May","June" => "June","July" => "July", "August"=> "August","September" => "September","October" => "October","November" => "November","December" => "December");
			
		$this->data['param1'] = $param1;
		$this->data['param2'] = $param2;
		$this->data['months'] = $months;
		$this->data['years'] = $years;
		$this->data['activemenu'] 	= "account";
		$this->data['activesubmenu'] = "experience";
		$this->data['pagetitle'] = get_languageword('experience');
		$this->data['content'] 		= 'experience';
		$this->_render_page('template/site/tutor-template', $this->data);
	}
	
	/**
	 * Fecilitates to update contact information
	 *
	 * @access	public
	 * @return	string
	 */
	function update_contact_information()
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_tutor()) {
			$this->prepare_flashmessage(get_languageword('You dont have permission to access this page'), 1);
			redirect('auth/login', 'refresh');
		}
		
		$this->data['message'] = $this->session->flashdata('message');
		if(isset($_POST['submitbutt']))
		{
			$this->form_validation->set_rules('city', get_languageword('City'), 'trim|required|xss_clean');
			$this->form_validation->set_rules('land_mark', get_languageword('land_mark'), 'trim|required|xss_clean');			
			$this->form_validation->set_rules('country', get_languageword('country'), 'trim|required|xss_clean');
			$this->form_validation->set_rules('pin_code', get_languageword('pin_code'), 'trim|required|xss_clean');
			$this->form_validation->set_rules('phone', get_languageword('phone'), 'trim|required|xss_clean');
			
			$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
			
			if ($this->form_validation->run() == TRUE)
			{
				$inputdata['city'] = $this->input->post('city');
				$inputdata['land_mark'] = $this->input->post('land_mark');
				$code_country = explode('_', $this->input->post('country'));

				$inputdata['country'] = $code_country[1];
				$inputdata['phone_code'] = $code_country[0];
				$inputdata['pin_code'] = $this->input->post('pin_code');
				$inputdata['phone'] = $this->input->post('phone');				
				$inputdata['academic_class'] = isset($_POST['academic_class']) ? 'yes' : 'no';
				$inputdata['non_academic_class'] = isset($_POST['non_academic_class']) ? 'yes' : 'no';
				$inputdata['share_phone_number'] = isset($_POST['share_phone_number']) ? 'yes' : 'no';				
				$this->base_model->update_operation($inputdata, 'users', array('id' => $this->ion_auth->get_user_id()));

				$this->prepare_flashmessage(get_languageword('record updated successfully'), 0);								
				redirect('tutor/update-contact-information');				
			}
			else
			{
				$this->data['message'] = $this->prepare_message(validation_errors(), 1);
			}
		}

		$this->data['profile'] = getUserRec();
		$countries = $this->base_model->fetch_records_from('country');
		$countries_opts = array('' => get_languageword('please select country'));
		if(!empty($countries))
		{
			foreach($countries as $country)
			{
				$countries_opts[$country->phonecode.'_'.$country->nicename]  = $country->nicename." +".$country->phonecode;
			}
		}
		$this->data['countries'] 	 = $countries_opts;
		$this->data['activemenu'] 	= "account";
		$this->data['activesubmenu'] = "update_contact_info";
		$this->data['pagetitle'] 	= get_languageword('update_contact_information');
		$this->data['content'] 		= 'update_contact_information';
		$this->_render_page('template/site/tutor-template', $this->data);
	}
	
	/**
	 * Fecilitates to view contact information
	 *
	 * @access	public
	 * @return	string
	 */
	function contact_information()
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_tutor()) {
			$this->prepare_flashmessage(get_languageword('You dont have permission to access this page'), 1);
			redirect('auth/login', 'refresh');
		}
		$this->data['profile'] = getUserRec();
		$this->data['activemenu'] 	= "account";
		$this->data['activesubmenu'] = "update_contact_info";	
		$this->data['content'] 		= 'contact_information';
		$this->data['pagetitle'] = get_languageword('Contact Information');
		$this->_render_page('template/site/tutor-template', $this->data);
	}
	
	/**
	 * Fecilitates to add or update tutor tutoring subjects
	 *
	 * @access	public
	 * @return	string
	 */
	function manage_subjects()
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_tutor()) {
			$this->prepare_flashmessage(get_languageword('You dont have permission to access this page'), 1);
			redirect('auth/login', 'refresh');
		}
		
		$this->data['message'] = $this->session->flashdata('message');
		$tutorSubjectIds 	= $this->tutor_model->get_tutor_subject_ids(
		$this->ion_auth->get_user_id()); //Getting tutor selected subject ids
		
		if ($this->input->post()) {	
		
			if ($this->input->post('tutor_subjects')) {
				if ($this->input->post('tutor_subjects') != $tutorSubjectIds) {
					$tutor_subjects 	= $this->input->post('tutor_subjects');
					if ($this->base_model->delete_record_new('tutor_subjects', array(
						'user_id' 	=> $this->ion_auth->get_user_id()))) {
						$data['user_id'] 		= $this->ion_auth->get_user_id();
						foreach($tutor_subjects as $subject) {
							if (is_numeric($subject)) {
								$data['subject_id'] = $subject;
								$this->base_model->insert_operation($data, 'tutor_subjects');
							}
						}
						$this->prepare_flashmessage(get_languageword('subjects')." " . 
						get_languageword('updated_successfully'), 0);
					}
					else
					{
						$this->prepare_flashmessage(get_languageword('subjects').' '.get_languageword('failed to update'), 1);
					}
				}
				else {
					$this->prepare_flashmessage(get_languageword('You have not done any changes'), 2);
				}
			}
			else {
				$this->prepare_flashmessage(get_languageword('Please select at least on subject'), 1);
			}
			redirect('tutor/manage-subjects', 'refresh');
		}
		
		$this->data['subjects'] 	= $this->tutor_model->get_subjects();
		$this->data['tutorSubjectIds'] 	= $tutorSubjectIds;
		$this->data['pagetitle'] = $this->data['my_profile']->first_name.' '.$this->data['my_profile']->last_name.' '.get_languageword('Subjects');
		
		$this->data['activemenu'] 	= "manage";
		$this->data['activesubmenu'] = "manage_subjects";	
		$this->data['content'] 		= 'manage_subjects';
		$this->_render_page('template/site/tutor-template', $this->data);
	}



	function manage_courses()
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_tutor()) {
			$this->prepare_flashmessage(get_languageword('You dont have permission to access this page'), 1);
			redirect('auth/login', 'refresh');
		}

		$this->data['message'] = $this->session->flashdata('message');

		$user_id = $this->ion_auth->get_user_id();
		$this->load->library(array('grocery_CRUD'));
		$crud = new grocery_CRUD();
		$crud_state = $crud->getState();
		$crud->set_table(TBL_TUTOR_COURSES);
		$crud->set_relation('course_id',TBL_CATEGORIES, 'name');
		$crud->where('tutor_id', $user_id);
		$crud->where('jea134da7.status', 1);
		$crud->set_subject( get_languageword('tutoring_courses') );


		//List Table Columns
		$crud->columns('course_id','course_duration','fee','content','time_slots', 'days_off');

		$crud->callback_column('course_duration',array($this,'_callback_course_duration'));

		//Display Alias Names
		$crud->display_as('course_id',get_languageword('course_name'));
		$crud->display_as('fee',get_languageword('fee').' ('.get_languageword('in_credits').')');
		$crud->display_as('per_credit_value',get_languageword('per_credit_value')." (".get_system_settings('currency_symbol').")");

		//From Validations
		$crud->required_fields(array('course_id','duration_value','duration_type', 'fee', 'content', 'time_slots', 'sort_order'));
		$crud->set_rules('fee',get_languageword('fee'),'integer');

		//Form fields for Add Record
		$crud->add_fields('tutor_id', 'course_id','duration_type','duration_value', 'fee', 'per_credit_value', 'content', 'time_slots', 'days_off', 'sort_order', 'created_at', 'updated_at');

		//Form fields for Edit Record
		$crud->edit_fields('tutor_id', 'course_id','duration_type','duration_value', 'fee', 'per_credit_value', 'content', 'time_slots', 'days_off', 'status', 'sort_order', 'updated_at');

		//Unset Read Fields
		$crud->unset_read_fields('tutor_id');

		//Set Custom Filed Types
		$crud->field_type('days_off', 'multiselect', array('SUN' => 'SUN', 'MON' => 'MON', 'TUE' => 'TUE', 'WED' => 'WED', 'THU' => 'THU', 'FRI' => 'FRI', 'SAT' => 'SAT'));
		$crud->field_type('tutor_id', 'hidden', $user_id);
		$crud->field_type('per_credit_value', 'hidden', get_system_settings('per_credit_value'));
		$crud->field_type('created_at', 'hidden', date('Y-m-d H:i:s'));
		$crud->field_type('updated_at', 'hidden', date('Y-m-d H:i:s'));

		//Modify fields in Form
		$crud->callback_field('course_id',array($this,'call_back_set_course_dropdown'));
		$crud->callback_field('time_slots',array($this,'call_back_set_time_slots_field'));


		//Authenticate whether Tutor editing/viewing his records only
		if($crud_state == "edit" || $crud_state == "read") {

			$p_key = $this->uri->segment(4);
			$tutor_id = $this->base_model->fetch_value('tutor_courses', 'tutor_id', array('id' => $p_key));
			if($tutor_id != $user_id) {

				$this->prepare_flashmessage(get_languageword('not_authorized'), 1);
    			redirect(URL_TUTOR_MANAGE_COURSES);
			}

		}

		if($crud_state == "read") {

			$crud->field_type('created_at', 'visibile');
			$crud->field_type('updated_at', 'visibile');
			$crud->set_relation('status','user_status_texts','text');
		}

		$crud->callback_after_insert(array($this, 'callback_is_profile_updated1'));

		$output = $crud->render();

		
		$this->data['activemenu'] 	= 'manage';	
		$this->data['activesubmenu'] 	= 'courses';	
		$this->data['pagetitle'] = get_languageword('Manage');
		$this->data['grocery_output'] = $output;
		$this->data['grocery'] = TRUE;
		$this->grocery_output($this->data);
	}

	function _callback_course_duration($primary_key, $row)
	{
		return $row->duration_value.' '. $row->duration_type;
	}


	function call_back_set_course_dropdown($val)
	{
		//Course Options
		$this->load->model('home_model');
		$courses = $this->home_model->get_courses();
		$course_opts[''] = get_languageword('select_course');
		foreach ($courses as $key => $value) {
			$course_opts[$value->id] = $value->name;
		}

		$val = !empty($val) ? $val : '';

		return form_dropdown('course_id', $course_opts, $val, 'id="course_id" class="chosen-select" ');
	}


	function call_back_set_time_slots_field($value)
	{
		$value = !empty($value) ? $value : '';
		return '<input type="text" name="time_slots" value="'.$value.'" placeholder="'.get_languageword('example_format').' 6-7,13-14,14-16,20.30-21.30">';
	}



	function callback_is_profile_updated1($post_array,$primary_key)
	{
		$is_profile_updated = $this->ion_auth->user($post_array['tutor_id'])->row()->is_profile_update;

		if($is_profile_updated != 1) {

			$tut_pref_teaching_types = $this->base_model->fetch_records_from('tutor_teaching_types', array('tutor_id' => $post_array['tutor_id'], 'status' => 1));
			if(count($tut_pref_teaching_types) > 0)
				$this->base_model->update_operation(array('is_profile_update' => 1), 'users', array('id' => $post_array['tutor_id']));
		}

		return TRUE;
	}



	/**
	 * Facilitates to add or update tutor locations, where he is tutoring
	 *
	 * @access	public
	 * @return	string
	 */
	function manage_locations()
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_tutor()) {
			$this->prepare_flashmessage(get_languageword('You dont have permission to access this page'), 1);
			redirect('auth/login', 'refresh');
		}
		
		$this->data['message'] = $this->session->flashdata('message');
		$tutorLocationIds 	= $this->tutor_model->get_tutor_location_ids(
		$this->ion_auth->get_user_id()); //Getting locaiton ids
		
		if ($this->input->post()) 
		{		
			if ($this->input->post('tutor_locations')) {
				if ($this->input->post('tutor_locations') != $tutorLocationIds) {
					$tutor_locations 	= $this->input->post('tutor_locations');
					if ($this->base_model->delete_record_new('tutor_locations', array(
						'tutor_id' 	=> $this->ion_auth->get_user_id()
					))) {
						$data['tutor_id'] 	= $this->ion_auth->get_user_id();
						$data['created_at'] = date('Y-m-d H:i:s');
						foreach($tutor_locations as $location) {
							if (is_numeric($location)) {
								$data['location_id'] = $location;
								$this->base_model->insert_operation($data, 'tutor_locations');
							}
						}

						$this->prepare_flashmessage(get_languageword('Locations') . " " . get_languageword('updated successfully'), 0);
					}
					else
					{
						$this->prepare_flashmessage(get_languageword('Locations') . " " . get_languageword('failed to updated'), 1);
					}						
				}
				else {
					$this->prepare_flashmessage(get_languageword('You have not done any changes'), 2);
				}
			}
			else {
				$this->prepare_flashmessage(
				get_languageword('please_select_atleast_one_preferred_location'), 1);
			}
			redirect('tutor/manage-locations');
		}
		
		$this->data['locations'] 				= $this->tutor_model->get_locations();
		$this->data['tutorLocationIds'] 		= $tutorLocationIds;
		
		$this->data['activemenu'] 	= "manage";
		$this->data['activesubmenu'] = "manage_locations";
		$this->data['pagetitle'] = get_languageword('Manage').'-'.get_languageword('Locations');		
		$this->data['content'] 		= 'manage_locations';
		$this->_render_page('template/site/tutor-template', $this->data);
	}
	
	/**
	 * Fecilitates to add or update tutor teaching types
	 *
	 * @access	public
	 * @return	string
	 */
	function manage_teaching_types()
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_tutor()) {
			$this->prepare_flashmessage(get_languageword('You dont have permission to access this page'), 1);
			redirect('auth/login', 'refresh');
		}
		
		$this->data['message'] = $this->session->flashdata('message');
		$tutorSelectedTypeIds 	= $this->tutor_model->get_tutor_selected_teachingtype_ids(
		$this->ion_auth->get_user_id());
		
		if ($this->input->post()) 
		{
			if ($this->input->post('tutor_selected_types')) {
				$user_id = $this->ion_auth->get_user_id();
				if ($this->input->post('tutor_selected_types') != $tutorSelectedTypeIds) {
					$tutor_selected_types 	= $this->input->post('tutor_selected_types');
					if ($this->base_model->delete_record_new('tutor_teaching_types', array('tutor_id' => $user_id))) {
						$data['tutor_id'] 		= $this->ion_auth->get_user_id();
						$data['created_at'] 	= date('Y-m-d H:i:s');
						foreach($tutor_selected_types as $tutor_type) {
							if (is_numeric($tutor_type)) {
								$data['teaching_type_id'] = $tutor_type;
								$this->base_model->insert_operation($data, 'tutor_teaching_types');
							}
						}

						$is_profile_updated = $this->ion_auth->user($user_id)->row()->is_profile_update;

						if($is_profile_updated != 1) {

							$tut_pref_courses = $this->base_model->fetch_records_from('tutor_courses', array('tutor_id' => $user_id, 'status' => 1));

							if(count($tut_pref_courses) > 0) {

								$this->base_model->update_operation(array('is_profile_update' => 1), 'users', array('id' => $user_id));

							}
						}

						$this->prepare_flashmessage(get_languageword('Teaching Types'). " " . get_languageword('updated_successfully'), 0);
					}
					else
					{
						$this->prepare_flashmessage(get_languageword('Teaching Types'). " " . get_languageword('failed to update'), 1);
					}
				}
				else {
					$this->prepare_flashmessage(get_languageword('you_have_not_done_any_changes') , 2);
				}
			}
			else {
				$this->prepare_flashmessage(
				get_languageword('please_select_atleast_one_preferred_teaching_type') , 1);
			}
			redirect('tutor/manage-teaching-types', 'refresh');
		}
		
		$this->data['tutor_types'] 				= $this->tutor_model->get_tutor_teachingtypes();
		$this->data['tutorSelectedTypeIds']	 	= $tutorSelectedTypeIds;
		
		$this->data['activemenu'] 	= "manage";
		$this->data['activesubmenu'] = "manage_teaching_types";	
		$this->data['pagetitle'] = get_languageword('Manage Teaching Types');
		$this->data['content'] 		= 'manage_teaching_types';
		$this->_render_page('template/site/tutor-template', $this->data);
	}
	
	/**
	 * Fecilitates to display packages for tutor.
	 *
	 * @access	public
	 * @param	string (Optional)
	 * @return	string
	 */	
	function list_packages($param1 = '')
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_tutor() || is_inst_tutor($this->ion_auth->get_user_id())) {
			$this->prepare_flashmessage(get_languageword('You dont have permission to access this page'), 1);
			redirect('auth/login', 'refresh');
		}
		
		$this->data['message'] = $this->session->flashdata('message');
				
		$this->data['pagetitle'] = get_languageword('packages');
		$this->data['package_data'] = $this->tutor_model->list_tutor_packages();
		
		$this->data['payment_gateways'] = $this->base_model->get_payment_gateways('', 'Active');

		$this->data['activemenu'] 	= "Packages";
		$this->data['activesubmenu'] = "list_packages";	
		$this->data['pagetitle'] = get_languageword('Packages');
		$this->data['content'] 		= 'list_packages';
		$this->_render_page('template/site/tutor-template', $this->data);
	}
	
	function mysubscriptions()
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_tutor()) {
			$this->prepare_flashmessage(get_languageword('You dont have permission to access this page'), 1);
			redirect('auth/login', 'refresh');
		}
		
		$this->data['message'] = $this->session->flashdata('message');

		$user_id = $this->ion_auth->get_user_id();
		$this->load->library(array('grocery_CRUD'));
		$crud = new grocery_CRUD();
		$crud_state = $crud->getState();
		$crud->set_table($this->db->dbprefix('subscriptions'));
		$crud->where('user_id', $user_id);
		$crud->set_subject( get_languageword('subscriptions') );


		$crud->unset_add();
		$crud->unset_delete();
		$crud->unset_edit();
		$crud->unset_read();

		$crud->columns('subscribe_date','package_name','transaction_no', 'payment_type','credits','amount_paid');
		$crud->callback_column('subscribe_date',array($this,'callback_subscribe_date'));
		$output = $crud->render();
		
		$this->data['pagetitle'] = get_languageword('packages');
		$this->data['activemenu'] 	= "Packages";
		$this->data['activesubmenu'] 	= "mysubscriptions";
		$this->data['content'] 		= 'mysubscriptions';
		$this->data['grocery_output'] = $output;
		$this->data['grocery'] = TRUE;
		$this->grocery_output($this->data);
	}
	
	function callback_subscribe_date($value, $row)
	{
		return date('d/m/Y', strtotime($value));
	}
	
	/**
	 * Fecilitates to set privacy
	 *
	 * @access	public
	 * @return	string
	 */
	function manage_privacy()
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_tutor()) {
			$this->prepare_flashmessage(get_languageword('You dont have permission to access this page'), 1);
			redirect('auth/login', 'refresh');
		}
		
		$this->data['message'] = $this->session->flashdata('message');
		$this->data['profile'] = getUserRec();		
		if(isset($_POST['submitbutt']))
		{
			$inputdata['free_demo'] = $this->input->post('free_demo');
			$inputdata['visibility_in_search'] = $this->input->post('visibility_in_search');
			$inputdata['show_contact'] = $this->input->post('show_contact');
			$inputdata['availability_status'] = $this->input->post('availability_status');
			$this->base_model->update_operation($inputdata, 'users', array('id' => $this->ion_auth->get_user_id()));
			$this->prepare_flashmessage(get_languageword('privacy updated successfully'), 0);								
			redirect('tutor/manage-privacy');			
		}
		
		$this->data['pagetitle'] = get_languageword('Manage Privacy');
		$this->data['activemenu'] 	= "manage";
		$this->data['activesubmenu'] = "manage_privacy";	
		$this->data['content'] 		= 'manage_privacy';
		$this->_render_page('template/site/tutor-template', $this->data);
	}

	/**
	 * Institute Tutor Batches List
	 *
	 * @access	public
	 * @return	string
	 */
	function my_batches($course_id = "")
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_tutor()) {
			$this->prepare_flashmessage(get_languageword('You dont have permission to access this page'), 1);
			redirect('auth/login', 'refresh');
		}

		$user_id = $this->ion_auth->get_user_id(); 
		$inst_id = is_inst_tutor($user_id);

		if(!$inst_id) {

			$this->prepare_flashmessage(get_languageword('You dont have permission to access this page'), 1);
			redirect('auth/login', 'refresh');
		}

		$page_title = get_languageword('my_batches');

		$course_id = ($this->input->post('course_id')) ? $this->input->post('course_id') : $course_id;


		if(!empty($course_id))
		{

			$this->data['message'] = $this->session->flashdata('message');

			$this->load->library(array('grocery_CRUD'));
			$crud = new grocery_CRUD();
			$crud_state = $crud->getState();
			$crud->set_table($this->db->dbprefix('inst_batches'));
			$crud->where('tutor_id', $user_id);
			$crud->where('inst_id', $inst_id);
			$crud->where('course_id', $course_id);
			$crud->set_relation('inst_id','users','username');

			$crud->set_subject( get_languageword('tutor_batches_list') );

			$crud->unset_add();
			$crud->unset_delete();
			$crud->unset_read();
			$crud->unset_edit();

			$crud->columns('inst_id','batch_code','batch_name','course_offering_location', 'batch_start_date', 'batch_end_date', 'time_slot','duration_value','duration_type', 'total_enrolled_students', 'status', 'initiate_session');

			$crud->callback_column('total_enrolled_students',array($this,'_callback_batch_enrolled_students_cnt'));
			$crud->callback_column('status',array($this,'_callback_batch_status'));
			$crud->callback_column('initiate_session',array($this,'callback_batch_action_initiated'));

			$crud->display_as('inst_id',get_languageword('Institute_Name'));
			$crud->display_as('status',get_languageword('batch_status'));

			//custom actions
			$crud->add_action(get_languageword('View Enrolled Students'), URL_FRONT_IMAGES.'magnifier.png',  URL_TUTOR_VIEW_STUDETNS.'/');
			$crud->add_action(get_languageword('Update as Course Completed For Batch'), URL_FRONT_IMAGES.'approve.png', '','',array($this,'callback_batch_action_completed'));

			$output = $crud->render();

			$this->data['course_id'] = $course_id;

			$this->data['grocery_output'] = $output;
			$this->data['grocery'] = TRUE;

		 }

		$this->data['message'] = $this->session->flashdata('message');
		$this->data['pagetitle'] = $page_title;
		$this->data['tutor_courses'] = $this->tutor_model->get_tutor_assigned_course($user_id,$inst_id);
		$this->data['activemenu'] 	= "my_batches";
		$this->grocery_output($this->data);
		
	}


	function _callback_batch_enrolled_students_cnt($primary_key, $row)
	{
		$batch_id = $row->batch_id;
		$this->load->model('institute/institute_model');
		return $this->institute_model->get_batch_enrolled_students_cnt($batch_id);
	}


	function _callback_batch_status($val, $row)
	{
		$batch_id = $row->batch_id;

		$this->load->model('institute/institute_model');
		$batch_status = $this->institute_model->get_batch_status($batch_id);

		return get_languageword($batch_status);

	}

	function callback_batch_action_initiated($val, $row)
	{
		$batch_status = $row->status;

		$today = date('Y-m-d');
		$batch_start_date = str_replace('/', '-', $row->batch_start_date);
		$batch_start_date = date('Y-m-d', strtotime($batch_start_date));

		$batch_end_date = str_replace('/', '-', $row->batch_end_date);
		$batch_end_date = date('Y-m-d', strtotime($batch_end_date));

		if($batch_status == "approved" && (strtotime($batch_start_date) <= strtotime($today)) && (strtotime($today) <= strtotime($batch_end_date))) {

			$cur_time 	= (float)date('H.i');
			$time_slot 	= str_replace(':', '.', $row->time_slot);
			$time 	  	= explode('-', str_replace(' ', '', $time_slot));
			$start_time = date('H:i', strtotime(number_format($time[0],2)));
			$end_time   = date('H:i', strtotime(number_format($time[1],2)));

			$certain_mins_before_start_time = (float)date('H.i', strtotime($start_time.' -'.$this->config->item('site_settings')->enable_initiate_session_option_before_mins.' minutes'));
			$certain_mins_before_end_time 	= (float)date('H.i', strtotime($end_time.' -'.$this->config->item('site_settings')->enable_course_completed_option_before_mins.' minutes'));

			if($cur_time >= $certain_mins_before_start_time && $cur_time <= $certain_mins_before_end_time) {

				$initiate_actn = "<a title='".get_languageword('Initiate Session For Batch Students')."' href='".URL_TUTOR_INITIATE_BATCH_SESSION.'/'.$row->course_id.'/'.$row->batch_id."'><img src='".URL_FRONT_IMAGES.'initiate-session.png'."' alt='".get_languageword('Initiate Session For Batch Students')."'/></a>";				

				return $initiate_actn;

			} else return '-';
		} else return '-';
	}


	function callback_batch_action_completed($val, $row)
	{
		$batch_status = $row->status;

		if($batch_status == "running" || $batch_status == "Running") {

			$today = date('Y-m-d');
			$batch_start_date = str_replace('/', '-', $row->batch_start_date);
			$batch_start_date = date('Y-m-d', strtotime($batch_start_date));

			if(strtotime($today) >= strtotime($batch_start_date)) {

				return URL_TUTOR_COMPLETE_BATCH_SESSION.'/'.$row->course_id.'/'.$row->batch_id;
			}

		}
	}


	
	function initiate_batch_session($course_id = "", $batch_id = "")
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_tutor()) {
			$this->prepare_flashmessage(get_languageword('You dont have permission to access this page'), 1);
			redirect('auth/login', 'refresh');
		}

		$user_id   = $this->ion_auth->get_user_id();
		$inst_id   = is_inst_tutor($user_id);

		if(!$inst_id) {

			$this->prepare_flashmessage(get_languageword('You dont have permission to access this page'), 1);
			redirect('auth/login', 'refresh');
		}

		$course_id = ($this->input->post('course_id')) ? $this->input->post('course_id') : $course_id;
		$batch_id  = ($this->input->post('batch_id')) ? $this->input->post('batch_id') : $batch_id;

		if(empty($course_id) || empty($batch_id)) {

			$this->prepare_flashmessage(get_languageword('No Details Found'), 1);
			redirect(URL_TUTOR_MY_BATCHES);
		}

		$batch_det_recs = $this->base_model->fetch_records_from('inst_enrolled_students', array('batch_id' => $batch_id, 'status =' => 'approved'));

		if(empty($batch_det_recs)) {

			$this->prepare_flashmessage(get_languageword('No Student enrolled in this batch.'), 2);
			redirect(URL_TUTOR_MY_BATCHES.'/'.$course_id);
		}


		$batch_det = $batch_det_recs[0];

		//Check Whether Tutor updating their record only
		if($user_id != $batch_det->tutor_id) {

			$this->prepare_flashmessage(get_languageword('You dont have permission to perform this action'), 1);
			redirect(URL_TUTOR_MY_BATCHES.'/'.$course_id);
		}


		if($this->input->post()) {

			$this->load->model('institute/institute_model');
			$batch_status = $this->institute_model->get_batch_status($batch_id);

			//If batch not already initiated, status as approve and status_desc, 
			//else update status desc only.
			if($batch_status == "approved") {

				$up_data['prev_status'] = 'approved';
				$up_data['status'] 		= 'running';
			}

			$up_data['updated_at'] 		= date('Y-m-d H:i:s');
			$up_data['updated_by'] 		= $user_id;
			$up_data['status_desc'] 	= $this->input->post('status_desc');

			if($batch_status == "approved" || ($batch_status != "pending" && $batch_status != "approved" && ($up_data['status_desc'] != $batch_det->status_desc))) {

				if($this->base_model->update_operation($up_data, 'inst_enrolled_students', array('batch_id' => $batch_id, 'status =' => 'approved'))) {

					//Email Alert to Batch Students - Start

					//Get Batch Session Initiated Alert To Student Email Template
					$email_tpl = $this->base_model->fetch_records_from('email_templates', array('template_status' => 'Active', 'email_template_id' => '14'));

					if(!empty($email_tpl)) {

						$email_tpl = $email_tpl[0];

						$tutor_rec 	 = getUserRec($batch_det->tutor_id);

						foreach($batch_det_recs as $row) {

							$student_rec = getUserRec($row->student_id);

							if(!empty($email_tpl->from_email)) {

								$from = $email_tpl->from_email;

							} else {

								$from 	= $tutor_rec->email;
							}

							$to 	= $student_rec->email;

							if(!empty($email_tpl->template_subject)) {

								$sub = $email_tpl->template_subject;

							} else {

								$sub = get_languageword("Batch Session Initiated");
							}

							if(!empty($email_tpl->template_content)) {

								$original_vars  = array($student_rec->username, $tutor_rec->username, $batch_det->batch_name." - ".$batch_det->batch_code, '<a href="'.URL_AUTH_LOGIN.'">'.get_languageword('Login Here').'</a>');
								$temp_vars		= array('___STUDENT_NAME___', '___TUTOR_NAME___', '___BATCH_NAME___', '___LOGINLINK___');
								$msg = str_replace($temp_vars, $original_vars, $email_tpl->template_content);

							} else {

								$msg = get_languageword('please')." <a href='".URL_AUTH_LOGIN."'>".get_languageword('Login Here')."</a> ".get_languageword('to view the details.');
								$msg .= "<p>".get_languageword('Thank you')."</p>";
							}

							sendEmail($from, $to, $sub, $msg);
						}
					}
					//Email Alert to Batch Students - End

					if($batch_status == "approved")
						$this->prepare_flashmessage(get_languageword('Batch Session Initiated successfully'), 0);
					else
						$this->prepare_flashmessage(get_languageword('Information updated successfully'), 0);

					redirect(URL_TUTOR_MY_BATCHES.'/'.$course_id);

				} else {

					$this->prepare_flashmessage(get_languageword('Batch Session not initiated due to some technical issue'), 2);
					redirect(URL_TUTOR_MY_BATCHES.'/'.$course_id);
				}

			} else {

				$this->prepare_flashmessage(get_languageword('Batch Session already initiated'), 2);
				redirect(URL_TUTOR_MY_BATCHES.'/'.$course_id);
			}

		}

		$this->data['course_id'] 	= $course_id;
		$this->data['status_desc'] 	= $batch_det->status_desc;
		$this->data['batch_id'] 	= $batch_id;
		$this->data['message'] 		= $this->session->flashdata('message');
		$this->data['pagetitle'] 	= get_languageword('Initiate Session for the Batch')." (".$batch_det->batch_name." - ".$batch_det->batch_code.") ";
		$this->data['content']	 	= 'initiate_session_for_batch_students';
		$this->data['activemenu']	= "enrolled_students";
		$this->_render_page('template/site/tutor-template', $this->data);

	}



	function complete_batch_session($course_id = "", $batch_id = "")
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_tutor()) {
			$this->prepare_flashmessage(get_languageword('You dont have permission to access this page'), 1);
			redirect('auth/login', 'refresh');
		}

		$user_id   = $this->ion_auth->get_user_id();
		$inst_id   = is_inst_tutor($user_id);

		if(!$inst_id) {

			$this->prepare_flashmessage(get_languageword('You dont have permission to access this page'), 1);
			redirect('auth/login', 'refresh');
		}

		$course_id = ($this->input->post('course_id')) ? $this->input->post('course_id') : $course_id;
		$batch_id  = ($this->input->post('batch_id')) ? $this->input->post('batch_id') : $batch_id;

		if(empty($course_id) || empty($batch_id)) {

			$this->prepare_flashmessage(get_languageword('No Details Found'), 1);
			redirect(URL_TUTOR_MY_BATCHES);
		}

		$batch_det_recs = $this->base_model->fetch_records_from('inst_enrolled_students', array('batch_id' => $batch_id, 'status =' => 'running'));

		if(empty($batch_det_recs)) {

			$this->prepare_flashmessage(get_languageword('No Student enrolled in this batch'), 2);
			redirect(URL_TUTOR_MY_BATCHES.'/'.$course_id);
		}


		$batch_det = $batch_det_recs[0];

		//Check Whether Tutor updating their record only
		if($user_id != $batch_det->tutor_id) {

			$this->prepare_flashmessage(get_languageword('You dont have permission to perform this action'), 1);
			redirect(URL_TUTOR_MY_BATCHES.'/'.$course_id);
		}


		if($this->input->post()) {

			$this->load->model('institute/institute_model');
			$batch_status = $this->institute_model->get_batch_status($batch_id);

			if($batch_status == "running") {

				$up_data['prev_status'] = 'running';
				$up_data['status'] 		= 'closed';

				$up_data['updated_at'] 		= date('Y-m-d H:i:s');
				$up_data['updated_by'] 		= $user_id;
				$up_data['status_desc'] 	= $this->input->post('status_desc');


				if($this->base_model->update_operation($up_data, 'inst_enrolled_students', array('batch_id' => $batch_id, 'status =' => 'running'))) {

					//Log Credits transaction data & update user net credits - Start
					$this->load->model('institute_model');
					$total_credits_of_batch_closed = $this->institute_model->get_credits_of_batch_closed($batch_id);

					$log_data = array(
									'user_id' => $inst_id,
									'credits' => $total_credits_of_batch_closed,
									'per_credit_value' => $batch_det->per_credit_value,
									'action'  => 'credited',
									'purpose' => 'Credits added for the batch "'.$batch_id.'" ',
									'date_of_action	' => date('Y-m-d H:i:s'),
									'reference_table' => 'inst_enrolled_students',
									'reference_id' => $batch_id,
								);

					log_user_credits_transaction($log_data);

					update_user_credits($inst_id, $total_credits_of_batch_closed, 'credit');
					//Log Credits transaction data & update user net credits - End

					//Email Alert to Batch Students & Institute - Start
					//Get Batch Session Completed Alert To Students Email Template
					$email_tpl = $this->base_model->fetch_records_from('email_templates', array('template_status' => 'Active', 'email_template_id' => '15'));

					if(!empty($email_tpl)) {

						$email_tpl = $email_tpl[0];

						$tutor_rec 	 = getUserRec($batch_det->tutor_id);
						$inst_rec 	 = getUserRec($batch_det->inst_id);

						$cnt = 1;
						foreach($batch_det_recs as $row) {

							$student_rec = getUserRec($row->student_id);

							if(!empty($email_tpl->from_email)) {

								$from = $email_tpl->from_email;

							} else {

								$from 	= $tutor_rec->email;
							}

							$to 	= $student_rec->email;
							if($cnt++ == count($batch_det_recs))
								$to .= $inst_rec->email;

							if(!empty($email_tpl->template_subject)) {

								$sub = $email_tpl->template_subject;

							} else {

								$sub = get_languageword("Course Completed for the Batch");
							}

							if(!empty($email_tpl->template_content)) {

								$original_vars  = array($student_rec->username, $tutor_rec->username, $batch_det->batch_name." - ".$batch_det->batch_code, '<a href="'.URL_AUTH_LOGIN.'">'.get_languageword('Login Here').'</a>');
								$temp_vars		= array('___STUDENT_NAME___', '___TUTOR_NAME___', '___BATCH_NAME___', '___LOGINLINK___');
								$msg = str_replace($temp_vars, $original_vars, $email_tpl->template_content);

							} else {

								$msg = get_languageword('please')." <a href='".URL_AUTH_LOGIN."'>".get_languageword('Login Here')."</a> ".get_languageword('to view the details');
								$msg .= "<p>".get_languageword('Thank you')."</p>";
							}

							sendEmail($from, $to, $sub, $msg);
						}
					}
					//Email Alert to Batch Students - End

					$this->prepare_flashmessage(get_languageword('Course completed for the Batch successfully'), 0);
					redirect(URL_TUTOR_MY_BATCHES.'/'.$course_id);

				} else {

					$this->prepare_flashmessage(get_languageword('Course not completed for the Batch due to some technical issue'), 2);
					redirect(URL_TUTOR_MY_BATCHES.'/'.$course_id);
				}

			} else {

				$this->prepare_flashmessage(get_languageword('Batch Session not completed'), 2);
				redirect(URL_TUTOR_MY_BATCHES.'/'.$course_id);
			}

		}

		$this->data['course_id'] 	= $course_id;
		$this->data['status_desc'] 	= $batch_det->status_desc;
		$this->data['batch_id'] 	= $batch_id;
		$this->data['message'] 		= $this->session->flashdata('message');
		$this->data['pagetitle'] 	= get_languageword('Update as Course Completed for the Batch')." (".$batch_det->batch_name." - ".$batch_det->batch_code.") ";
		$this->data['content']	 	= 'course_completed_for_batch_students';
		$this->data['activemenu']	= "enrolled_students";
		$this->_render_page('template/site/tutor-template', $this->data);

	}



	function view_students($batch_id = "")
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_tutor()) {
			$this->prepare_flashmessage(get_languageword('You dont have permission to access this page'), 1);
			redirect('auth/login', 'refresh');
		}

		if(!empty($batch_id))
		{
			$this->data['message'] = $this->session->flashdata('message');

			$user_id = $this->ion_auth->get_user_id();
			$inst_id = is_inst_tutor($user_id);

			if(!$inst_id) {

				$this->prepare_flashmessage(get_languageword('You dont have permission to access this page'), 1);
				redirect('auth/login', 'refresh');
			}

			$this->load->library(array('grocery_CRUD'));
			$crud = new grocery_CRUD();
			$crud_state = $crud->getState();
			$crud->set_table($this->db->dbprefix('inst_enrolled_students'));
			$crud->where('batch_id', $batch_id);
			$crud->set_relation('student_id','users','username');
			$crud->set_relation('inst_id','users','username');

			$crud->set_subject( get_languageword('enrolled_student') );

			//unset actions
			$crud->unset_add();
			$crud->unset_edit();
			$crud->unset_delete();

			//display columns
			$crud->columns('student_id','batch_code','batch_name','batch_start_date','batch_end_date','duration_value','duration_type','course_offering_location', 'time_slot','status');

			//display names as
			$crud->display_as('student_id',get_languageword('student_name'));
			$crud->display_as('inst_id',get_languageword('Institute_Name'));
			$crud->display_as('enroll_id',get_languageword('course_duration')); 

			if($crud_state == "read") {

				$p_key = $this->uri->segment(5);

				$enroll_det = $this->base_model->fetch_records_from('inst_enrolled_students', array('enroll_id' => $p_key));

				if(!empty($enroll_det)) {

					$enroll_det = $enroll_det[0];

					if($enroll_det->tutor_id != $user_id) {

						$this->prepare_flashmessage(get_languageword('not_authorized'), 1);
		    			redirect(URL_TUTOR_VIEW_STUDETNS.'/'.$batch_id);
					}

				} else {

					$this->prepare_flashmessage(get_languageword('invalid_request'), 1);
		    		redirect(URL_TUTOR_VIEW_STUDETNS.'/'.$batch_id);
				}

				$crud->unset_read_fields('fee', 'per_credit_value', 'admin_commission', 'admin_commission_val');
			}

			$output = $crud->render();


			$this->data['grocery_output'] = $output;
			$this->data['grocery'] = TRUE;

			$this->data['message'] = $this->session->flashdata('message');
			$this->data['pagetitle'] = get_languageword('enrolled_students');
			$this->data['activemenu'] 	= "my_batches";
			$this->grocery_output($this->data);
		}
		else
		{
			redirect( URL_TUTOR_MY_BATCHES);
		}
		
	}


		
	/**
	 * Fecilitates to upload certificates
	 *
	 * @access	public
	 * @return	string
	 */
	
	function certificates()
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_tutor()) {
			$this->prepare_flashmessage(get_languageword('You dont have permission to access this page'), 1);
			redirect('auth/login', 'refresh');
		}
		
		$this->data['message'] = $this->session->flashdata('message');
		$user_id = $this->ion_auth->get_user_id();
		
		if(isset($_POST['submitbutt']))
		{
			//neatPrint($_FILES);
			
			if(count($_FILES['certificate']['name']) > 0)
			{
				foreach ($_FILES['certificate']['name'] as $i => $value)
				{
					if($_FILES['certificate']['name'][$i] != '')
					{
					
					$tmpFilePath = $_FILES['certificate']['tmp_name'][$i];
					$ext = pathinfo($_FILES['certificate']['name'][$i], PATHINFO_EXTENSION);
					$new_name = $user_id.'_'.$i.'.'.$ext;
					$filePath = './assets/uploads/certificates/'.$new_name;
						if(move_uploaded_file($tmpFilePath, $filePath))
						{
							if(in_array(strtolower($ext), array('jpg', 'png', 'gif', 'jpeg')))
							{
							$this->create_thumbnail($filePath,'./assets/uploads/certificates/thumbs/','40','40');
							}
							$user_image['admin_certificate_id'] = $i;
							$user_image['user_id']				= $user_id;
							if(isset($this->config->item('site_settings')->need_admin_for_tutor) && $this->config->item('site_settings')->need_admin_for_tutor == 'yes')
							$user_image['admin_status']			= 'Pending';
							else
							$user_image['admin_status']			= 'Approved';
							$user_image['certificate_type']		= 'admin';
							$user_image['certificate_name']		= $new_name;
							$user_image['file_type']		= $ext;
							
							$existed = $this->base_model->fetch_records_from('users_certificates',
														array('admin_certificate_id'=>$i,
														'user_id'=>$user_id,'certificate_type'=>'admin'));
							if(count($existed)>0)
							{
								$whr['user_certificate_id']			= $existed[0]->user_certificate_id;
								$this->base_model->update_operation($user_image,'users_certificates',$whr);
							}
							else
							{
							$this->base_model->insert_operation($user_image,'users_certificates');	
							}
						}
					}
				}
				
				if(count($_FILES['other']['name']) > 0)
				{
					$n=0;
					if(count($_FILES['other']['name']) > 0)
					{
						$n=0;
						for($i=0; $i<count($_FILES['other']['name']); $i++) 
						{					
							$n++;
							 //Get the temp file path
							$tmpFilePath = $_FILES['other']['tmp_name'][$i];			
							
							 //Make sure we have a filepath
							if($tmpFilePath != "")
							{
								//save the filename
								$shortname = $user_id.'_'.str_replace(' ','_',rand().'_'.$_FILES['other']['name'][$i]);
								$ext = pathinfo($_FILES['other']['name'][$i], PATHINFO_EXTENSION);
								//$filename = 'other_'.$n.'.'.$ext;
								//save the url and the file
								$filePath = './assets/uploads/certificates/'.$shortname;
								//Upload the file into the temp dir
								if(move_uploaded_file($tmpFilePath, $filePath)) 
								{								
									$user_image['user_id']				= $user_id;
									$user_image['admin_certificate_id'] = 0;
									if(isset($this->config->item('site_settings')->need_admin_for_tutor) && $this->config->item('site_settings')->need_admin_for_tutor == 'yes')
									$user_image['admin_status']			= 'Pending';
									else
									$user_image['admin_status']			= 'Approved';
									$user_image['certificate_type']		= 'other';
									$user_image['certificate_name']		= $shortname;
									$user_image['file_type']		= $ext;									
									$this->base_model->insert_operation($user_image,'users_certificates');
								}
							}
						}
					}
				}
			}
			$this->prepare_flashmessage(get_languageword('Certificates uploaded successfully'), 0);
			redirect('tutor/certificates');
		}

		$certificates = $this->base_model->fetch_records_from('certificates', array('certificate_for' => 'tutors', 'status' => 'Active'));
		$this->data['certificates'] 	= $certificates;
		
		$user_uploads = $this->base_model->fetch_records_from('users_certificates', array('user_id' => $user_id));
		$user_uploads_arr = array();
		if(!empty($user_uploads))
		{
			foreach($user_uploads as $up)
			{
				$user_uploads_arr[$up->admin_certificate_id] = $up->certificate_name;
			}
		}
		$this->data['user_uploads_arr'] 	= $user_uploads_arr;
		
		$this->data['activemenu'] 	= "manage";
		$this->data['activesubmenu'] = 'certificates';
		$this->data['content'] 		= 'certificates';
		$this->data['pagetitle']	= get_languageword('manage_certificates');
		$this->_render_page('template/site/tutor-template', $this->data);
	}
		
		
	//Need to implement
	function membership()
	{
		$this->data['activemenu'] 	= "home";		
		$this->data['content'] 		= 'membership';
		$this->_render_page('template/site/tutor-template', $this->data);
	}
	
	function preferences()
	{
		$this->data['activemenu'] 	= "home";		
		$this->data['content'] 		= 'preferences';
		$this->_render_page('template/site/tutor-template', $this->data);
	}
	
	function privacy_settings()
	{
		$this->data['activemenu'] 	= "home";		
		$this->data['content'] 		= 'privacy_settings';
		$this->_render_page('template/site/tutor-template', $this->data);
	}
		


	/**
	 * Fecilitates to update student leads
	 * @access	public
	 * @return	string
	 */
	function user_reviews()
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_tutor()) {
			$this->prepare_flashmessage(get_languageword('You dont have permission to access this page'), 1);
			redirect('auth/login', 'refresh');
		}

		$tutor_id = $this->ion_auth->get_user_id();

		if(is_inst_tutor($tutor_id)) {

			$this->prepare_flashmessage(get_languageword('Invalid Request'), 1);
			redirect(URL_TUTOR_INDEX);
		}

		$this->load->library(array('grocery_CRUD'));
		$crud = new grocery_CRUD();
		$crud_state = $crud->getState();
		$crud->set_table($this->db->dbprefix('tutor_reviews'));
		$crud->where('tutor_id', $tutor_id);
		$crud->set_relation('student_id','users','username');
		$crud->set_relation('course_id','categories','name');
		$crud->set_subject( get_languageword('student_reviews') );

		$crud->unset_add();

		$crud->columns('student_id','course_id','comments','rating', 'created_at', 'updated_at','status');

		//########Edit fields only#######
		$crud->edit_fields('status');

		//####### Changing column names #######
		$crud->display_as('created_at','Posted Date');
		$crud->display_as('updated_at','Last Updated');
		$crud->display_as('course_id','Course Name');
		$crud->display_as('student_id','Student Name');
		$crud->display_as('rating', get_languageword('rating').' ('.get_languageword('out_of').' 5)');

		#### Invisible fileds in reading ####
		if ($crud->getState() == 'read') {
		    $crud->field_type('tutor_id', 'hidden');
		}


		$output = $crud->render();

		$this->data['activemenu'] 	= "reviews";
		$this->data['pagetitle'] = get_languageword('reviews');
		$this->data['grocery_output'] = $output;
		$this->data['grocery'] = TRUE;
		$this->_render_page('template/site/tutor-template-grocery', $this->data);
	}


	function student_enquiries($param = "")
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_tutor()) {
			$this->prepare_flashmessage(get_languageword('You dont have permission to access this page'), 1);
			redirect('auth/login', 'refresh');
		}

		$this->data['message'] = $this->session->flashdata('message');

		$user_id = $this->ion_auth->get_user_id();
		$this->load->library(array('grocery_CRUD'));
		$crud = new grocery_CRUD();
		$crud_state = $crud->getState();
		$crud->set_table(TBL_BOOKINGS);
		$crud->set_relation('student_id',TBL_USERS, 'username');
		$crud->set_relation('updated_by',TBL_USERS, 'username');
		$crud->where(TBL_BOOKINGS.'.tutor_id', $user_id);

		$status_arr = array('pending', 'approved', 'cancelled_before_course_started', 'cancelled_when_course_running', 'cancelled_after_course_completed', 'session_initiated', 'running', 'completed', 'called_for_admin_intervention', 'closed');
		if(in_array($param, $status_arr)) {

			$crud->where(TBL_BOOKINGS.'.status', $param);
		}

		$crud->set_subject( get_languageword('booking_status') );

		//Unset Actions
		$crud->unset_add();
		$crud->unset_delete();

		//List Table Columns
		$crud->columns('student_id', 'course_id', 'course_duration', 'fee', 'admin_commission_val','content', 'start_date', 'time_slot', 'preferred_location', 'status', 'payment_status');

		$crud->callback_column('course_duration',array($this,'_callback_course_duration'));
		$crud->callback_column('course_id',array($this,'_callback_course_id'));

		if($crud_state =="read")
			$crud->set_relation('course_id','categories','name');
		//Display Alias Names
		$crud->display_as('status',get_languageword('booking_status'));
		$crud->display_as('student_id',get_languageword('student_name'));
		$crud->display_as('course_id',get_languageword('course_seeking'));
		$crud->display_as('fee',get_languageword('fee').' ('.get_languageword('in_credits').')');
		$crud->display_as('admin_commission_val',get_languageword('admin_commission_val').' ('.get_languageword('in_credits').')');
		$crud->display_as('admin_commission',get_languageword('admin_commission_percentage').' ('.get_languageword('with_credits').')');
		$crud->display_as('per_credit_value',get_languageword('per_credit_value')." (".get_system_settings('currency_symbol').")");
		$crud->display_as('start_date',get_languageword('preferred_commence_date'));



		if($param == "closed") {

			$crud->callback_column('payment_status', array($this, 'callback_payment_status'));

			$crud->add_action(get_languageword('send_credits_conversion_request'), URL_FRONT_IMAGES.'/money.png', URL_TUTOR_SEND_CREDITS_CONVERSION_REQUEST.'/');

		} else {

			$crud->unset_columns('payment_status');
		}

		//Form fields for Edit Record
		$crud->edit_fields('status', 'status_desc', 'updated_at', 'prev_status');

		//Hidden Fields
		$crud->field_type('updated_at', 'hidden', date('Y-m-d H:i:s'));

		//Unset Fields
		$crud->unset_fields('tutor_id', 'admin_commission_val');


		//Authenticate whether Tutor editing/viewing his records only
		if($crud_state == "edit" || $crud_state == "read") {

			if($param != "" && $param != "add" && $param != "edit" && $param != "read" && $param != "success")
				$p_key = $this->uri->segment(4);
			else
				$p_key = $this->uri->segment(3);

			$booking_det = $this->base_model->fetch_records_from('bookings', array('booking_id' => $p_key));

			if(!empty($booking_det)) {

				$booking_det = $booking_det[0];

				if($booking_det->tutor_id != $user_id) {

					$this->prepare_flashmessage(get_languageword('not_authorized'), 1);
	    			redirect(URL_TUTOR_STUDENT_ENQUIRIES);
				}

				if($crud_state == "edit") {

					$booking_status = $booking_det->status;
					$updated_by = getUserType($booking_det->updated_by);

					$crud->field_type('prev_status', 'hidden', $booking_status);

					$crud->display_as('status', get_languageword('change_status'));

					if(in_array($booking_status, array('pending', 'approved', 'session_initiated', 'running', 'completed'))) {
						$crud->required_fields(array('status'));
					}

					if($booking_status == "pending") {
						$crud->field_type('status', 'dropdown', array('approved' => get_languageword('approve'), 'cancelled_before_course_started' => get_languageword('cancel')));
					}

					if($booking_status == "approved") {

						$status = array('cancelled_before_course_started' => get_languageword('cancel'));

						$today = date('Y-m-d');

						if((strtotime($booking_det->start_date) <= strtotime($today)) && (strtotime($today) <= strtotime($booking_det->end_date))) {

							$cur_time 	= (float)date('H.i');
							$time_slot 	= str_replace(':', '.', $booking_det->time_slot);
							$time 	  	= explode('-', str_replace(' ', '', $time_slot));
							$start_time = date('H:i', strtotime(number_format($time[0],2)));
							$end_time   = date('H:i', strtotime(number_format($time[1],2)));

							$certain_mins_before_start_time = (float)date('H.i', strtotime($start_time.' -'.$this->config->item('site_settings')->enable_initiate_session_option_before_mins.' minutes'));
							$certain_mins_before_end_time 	= (float)date('H.i', strtotime($end_time.' -'.$this->config->item('site_settings')->enable_course_completed_option_before_mins.' minutes'));

							if($cur_time >= $certain_mins_before_start_time && $cur_time <= $certain_mins_before_end_time) {
								$status = array('session_initiated' => get_languageword('initiate_session'), 'cancelled_before_course_started' => get_languageword('cancel'));
							}
						}

						$crud->field_type('status', 'dropdown', $status);

					}

					if($booking_status == "session_initiated") {

						$status = array('cancelled_before_course_started' => get_languageword('cancel'));
						$crud->field_type('status', 'dropdown', $status);
					}

					if($booking_status == "running") {

						$status = array('cancelled_when_course_running' => get_languageword('cancel'));

						$today = date('Y-m-d');

						if(strtotime($today) >= strtotime($booking_det->start_date)) {

							$status = array('completed' => get_languageword('course_completed'), 'cancelled_when_course_running' => get_languageword('cancel'));
						}

						$crud->field_type('status', 'dropdown', $status);

					}

					if($booking_status == "completed") {

						$status = array('called_for_admin_intervention' => get_languageword('claim_for_admin_intervention'));

						$crud->field_type('status', 'dropdown', $status);

					}

					if($booking_status == "called_for_admin_intervention" && $updated_by == "student") {

						if($booking_det->prev_status == "pending")
							$status['approved'] = get_languageword('approve');
						else if($booking_det->prev_status == "approved")
							$status['cancelled_before_course_started'] = get_languageword('cancel');
						else if($booking_det->prev_status == "running") {
							$status['running'] = get_languageword('continue_course');
							$status['cancelled_when_course_running'] = get_languageword('cancel');
						}
						else if($booking_det->prev_status == "cancelled_when_course_running") {
							$status['running'] = get_languageword('continue_course');
						}
						else if($booking_det->prev_status == "completed") {
							$status['running'] = get_languageword('continue_course');
							$status['cancelled_when_course_running'] = get_languageword('cancel');
						}

						$crud->required_fields(array('status'));
						$crud->field_type('status', 'dropdown', $status);

					} else if($booking_status == "called_for_admin_intervention" && ($updated_by == "tutor"  || $updated_by == "admin")) {

						$crud->edit_fields('status_desc', 'updated_at');
					}


					if($booking_status == "cancelled_when_course_running" && $updated_by == "student") {

						$crud->required_fields(array('status'));

						$status = array('called_for_admin_intervention' => get_languageword('claim_for_admin_intervention'));

						$crud->field_type('status', 'dropdown', $status);
					}


					if($booking_status == "cancelled_after_course_completed" && $updated_by == "student") {

						$crud->required_fields(array('status'));

						$status = array('called_for_admin_intervention' => get_languageword('claim_for_admin_intervention'));

						$crud->field_type('status', 'dropdown', $status);
					}

					if($booking_status == "closed" || $booking_status == "cancelled_before_course_started" || ($booking_status == "cancelled_when_course_running" && $updated_by == "tutor") || ($booking_status == "cancelled_after_course_completed" && $updated_by == "tutor")) {

						$crud->edit_fields('status_desc', 'updated_at');

					}

				}

			} else {

				$this->prepare_flashmessage(get_languageword('invalid_request'), 1);
	    		redirect(URL_TUTOR_STUDENT_ENQUIRIES);
			}

		}


		if($crud_state == "read") {

			$crud->field_type('updated_at', 'visibile');
		}

		$crud->callback_column('preferred_location', array($this, 'callback_column_preferred_location'));
		$crud->callback_column('status', array($this, 'callback_column_booking_status'));

		$crud->callback_update(array($this,'callback_send_email'));

		$output = $crud->render();

		$param = get_languageword($param);
		$this->data['pagetitle'] = get_languageword('bookings');
		$this->data['activemenu'] 	= "enquiries";
		$this->data['activesubmenu'] = $param;

		$this->data['grocery_output'] = $output;
		$this->data['grocery'] = TRUE;
		$this->grocery_output($this->data);
	}


	function _callback_course_id($primary_key , $row)
	{

	  $course_name = $this->base_model->fetch_value('categories', 'name', array('id' => $row->course_id));
	   return $course_name;
	}

	function callback_column_booking_status($primary_key , $row)
	{

	    return humanize($row->status);
	}

	function callback_column_preferred_location($primary_key , $row)
	{

	    return humanize($row->preferred_location);
	}

	function callback_payment_status($val, $row)
	{
		$user_id = $this->ion_auth->get_user_id();
		$payment_status = $this->base_model->fetch_records_from('admin_money_transactions', array('booking_id' => $row->booking_id, 'user_id' => $user_id, 'user_type' => 'tutor'));
		if(!empty($payment_status))
			return $payment_status[0]->status_of_payment;
	}


	function callback_send_email($post_array, $primary_key)
	{
		$post_array['updated_by'] = $this->ion_auth->get_user_id();

		if($this->base_model->update_operation($post_array, 'bookings', array('booking_id' => $primary_key))) {

			$booking_det = $this->base_model->fetch_records_from('bookings', array('booking_id' => $primary_key));

			if(!empty($booking_det)) {

				$booking_det = $booking_det[0];

				$student_rec = getUserRec($booking_det->student_id);
				$tutor_rec 	 = getUserRec($booking_det->tutor_id);

				//If Tutor Cancelled booking before session gets started, refund Student's Credits
				if($post_array['status'] == "cancelled_before_course_started") {

					//Log Credits transaction data & update user net credits - Start
					$log_data = array(
									'user_id' => $booking_det->student_id,
									'credits' => $booking_det->fee,
									'per_credit_value' => $booking_det->per_credit_value,
									'action'  => 'credited',
									'purpose' => 'Slot booked with the Tutor "'.$tutor_rec->username.'" has cancelled before course started',
									'date_of_action	' => date('Y-m-d H:i:s'),
									'reference_table' => 'bookings',
									'reference_id' => $primary_key,
								);

					log_user_credits_transaction($log_data);

					update_user_credits($booking_det->student_id, $booking_det->fee, 'credit');
					//Log Credits transaction data & update user net credits - End
				}


				//If Tutor approves the booking send Student's address to Tutor
				//Email Alert to Tutor - Start
				//Get Send Student's Address Email Template
				if($post_array['status'] == "approved" && $booking_det->preferred_location == "home") {

					$email_tpl = $this->base_model->fetch_records_from('email_templates', array('template_status' => 'Active', 'email_template_id' => '6'));

					if(!empty($email_tpl)) {

						$email_tpl = $email_tpl[0];

						if(!empty($email_tpl->from_email)) {

							$from = $email_tpl->from_email;

						} else {

							$from 	= get_system_settings('Portal_Email');
						}

						$to 	= $tutor_rec->email;

						if(!empty($email_tpl->template_subject)) {

							$sub = $email_tpl->template_subject;

						} else {

							$sub = get_languageword("Student Address");
						}

						$student_addr = $student_rec->city.", <br />".$student_rec->land_mark.", <br />".$student_rec->country.", <br/>Phone: ".$student_rec->phone;

						$course_name = $this->base_model->fetch_value('categories', 'name', array('id' => $booking_det->course_id));

						if(!empty($email_tpl->template_content)) {

							$original_vars  = array($tutor_rec->username, $student_rec->username, $course_name, $booking_det->start_date." & ".$booking_det->time_slot, $booking_det->preferred_location, $student_addr);
							$temp_vars		= array('___TUTOR_NAME___', '___STUDENT_NAME___', '___COURSE_NAME___', '___DATE_TIME___', '___LOCATION___', '___STUDENT_ADDRESS___');
							$msg = str_replace($temp_vars, $original_vars, $email_tpl->template_content);

						} else {

							$msg = "<p>
										".get_languageword('hello')." ".$tutor_rec->username.",</p>
									<p>
										".get_languageword('You approved Student')." &quot;".$student_rec->username."&quot; ".get_languageword('booking for the course')." &quot;".$course_name."&quot;</p>
									<p>
										".get_languageword('for the timeslot')." &quot;".$booking_det->start_date." & ".$booking_det->time_slot."&quot; and &quot; ".$booking_det->preferred_location."&quot; ".get_languageword('as preferred location for sessions').".</p>
									<p>
										".get_languageword('Below is the address of the Student')."</p>
									<p>
										".$student_addr."</p>";

							$msg .= "<p>".get_languageword('Thank you')."</p>";
						}

						sendEmail($from, $to, $sub, $msg);
					}
					//Email Alert to Tutor - End
				}


				//If Tutor initiates the session send email alert to Student
				//Email Alert to Student - Start
				//Get SEssion Initiated Email Template
				if($post_array['status'] == "session_initiated") {

					$email_tpl = $this->base_model->fetch_records_from('email_templates', array('template_status' => 'Active', 'email_template_id' => '11'));

					if(!empty($email_tpl)) {

						$email_tpl = $email_tpl[0];

						if(!empty($email_tpl->from_email)) {

							$from = $email_tpl->from_email;

						} else {

							$from 	= get_system_settings('Portal_Email');
						}

						$to 	= $student_rec->email;

						if(!empty($email_tpl->template_subject)) {

							$sub = $email_tpl->template_subject;

						} else {

							$sub = get_languageword("Session Initiated By Tutor");
						}

						$course_name = $this->base_model->fetch_value('categories', 'name', array('id' => $booking_det->course_id));

						if(!empty($email_tpl->template_content)) {

							$original_vars  = array($student_rec->username, $tutor_rec->username, $course_name, $booking_det->start_date." & ".$booking_det->time_slot, $booking_det->preferred_location, '<a href="'.URL_AUTH_LOGIN.'">'.get_languageword('Login Here').'</a>');
							$temp_vars		= array('___STUDENT_NAME___', '___TUTOR_NAME___', '___COURSE_NAME___', '___DATE_TIME___', '___LOCATION___', '___LOGINLINK___');
							$msg = str_replace($temp_vars, $original_vars, $email_tpl->template_content);

						} else {

							$msg = "<p>
										".get_languageword('hello')." ".$student_rec->username.",</p>
									<p>
										".get_languageword('Tutor initiated the session Please start the session by logging in here')."<a href='".URL_AUTH_LOGIN."'>".get_languageword('Login Here')."</a></p>";

							$msg .= "<p>".get_languageword('Thank you')."</p>";
						}

						sendEmail($from, $to, $sub, $msg);
					}
					//Email Alert to Student - End
				}



			}

			return TRUE;

		} else return FALSE;
	}



	function send_credits_conversion_request($booking_id = "")
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_tutor()) {
			$this->prepare_flashmessage(get_languageword('You dont have permission to access this page'), 1);
			redirect('auth/login', 'refresh');
		}

		$booking_id = ($this->input->post('booking_id')) ? $this->input->post('booking_id') : $booking_id;

		if(empty($booking_id)) {

			$this->prepare_flashmessage(get_languageword('Please complete your course to send credit conversion request'), 2);
			redirect(URL_TUTOR_STUDENT_ENQUIRIES);
		}

		$user_id = $this->ion_auth->get_user_id();

		//Check whether booking exists
		$booking_det = $this->base_model->fetch_records_from('bookings', array('booking_id' => $booking_id, 'tutor_id' => $user_id, 'status' => 'closed'));

		if(empty($booking_det)) {

			$this->prepare_flashmessage(get_languageword('Invalid request'), 1);
			redirect(URL_TUTOR_STUDENT_ENQUIRIES);
		}

		//Check whether already sent request
		$payment_status = $this->base_model->fetch_records_from('admin_money_transactions', array('booking_id' => $booking_id, 'user_id' => $user_id, 'user_type' => 'tutor'));

		if(!empty($payment_status)) {

			$this->prepare_flashmessage(get_languageword('Already sent the request And status of the payment is ').$payment_status[0]->status_of_payment, 1);
			redirect(URL_TUTOR_STUDENT_ENQUIRIES.'/closed');
		}


		$booking_det = $booking_det[0];
		$user_rec 	 = getUserRec($user_id);

		$inputdata['user_id'] 						= $user_id;
		$inputdata['booking_id'] 					= $booking_id;
		$inputdata['user_type'] 					= 'tutor';
		$inputdata['user_name'] 					= $user_rec->username;
		$inputdata['user_paypal_email'] 			= $user_rec->paypal_email;
		$inputdata['user_bank_ac_details'] 			= $user_rec->bank_ac_details;
		$inputdata['no_of_credits_to_be_converted'] = $booking_det->fee-$booking_det->admin_commission_val;
		$inputdata['admin_commission_val'] 			= $booking_det->admin_commission_val;
		$inputdata['per_credit_cost'] 				= $booking_det->per_credit_value;
		$inputdata['total_amount'] 					= $inputdata['no_of_credits_to_be_converted'] * $inputdata['per_credit_cost'];
		$inputdata['created_at'] 					= date('Y-m-d H:i:s');
		$inputdata['updated_at'] 					= $inputdata['created_at'];
		$inputdata['updated_by'] 					= $user_id;


		if($this->base_model->insert_operation($inputdata, 'admin_money_transactions')) {

			$this->prepare_flashmessage(get_languageword('Credits to Money conversion request sent successfully'), 0);
			redirect(URL_TUTOR_CREDIT_CONVERSION_REQUESTS);

		} else {

			$this->prepare_flashmessage(get_languageword('Somthing went wrong Your request not sent'), 2);
			redirect(URL_TUTOR_STUDENT_ENQUIRIES);
		}

	}



	function credits_transactions_history()
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_tutor()) {
			$this->prepare_flashmessage(get_languageword('You dont have permission to access this page'), 1);
			redirect('auth/login', 'refresh');
		}

		$tutor_id = $this->ion_auth->get_user_id();
		$this->load->library(array('grocery_CRUD'));
		$crud = new grocery_CRUD();
		$crud_state = $crud->getState();
		$crud->set_table($this->db->dbprefix('user_credit_transactions'));
		$crud->where('user_id', $tutor_id);
		
		$crud->set_subject( get_languageword('user_credit_transactions') );

		$crud->unset_add();
		$crud->unset_delete();
		$crud->unset_edit();


		$crud->columns('credits','action','purpose','date_of_action');

		$crud->unset_read_fields('user_id', 'reference_table', 'reference_id', 'per_credit_value');

		$output = $crud->render();

		$this->data['activemenu'] 	= "user_credit_transactions";
		$this->data['pagetitle'] = get_languageword('user_credit_transactions');
		$this->data['grocery_output'] = $output;
		$this->data['grocery'] = TRUE;
		$this->grocery_output($this->data);
	}


	function credit_conversion_requests($param = "Pending")
	{

		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_tutor()) {
		$this->prepare_flashmessage(get_languageword('You dont have permission to access this page'), 1);
		redirect('auth/login', 'refresh');
		}

		$user_id = $this->ion_auth->get_user_id();

		$this->load->library(array('grocery_CRUD_extended'));
		$crud = new grocery_CRUD_extended();
		$crud_state = $crud->getState();

		$crud->set_table($this->db->dbprefix('admin_money_transactions'));
		$crud->where('user_id',$user_id);
		$crud->where('status_of_payment', $param);

		//unset actions
		$crud->unset_add();
		$crud->unset_edit();
		$crud->unset_delete();


		//display columns    			
		$crud->columns('booking_id', 'user_paypal_email', 'user_bank_ac_details', 'no_of_credits_to_be_converted', 'per_credit_cost', 'total_amount', 'status_of_payment', 'updated_at');

		$crud->set_read_fields('booking_id', 'user_paypal_email', 'user_bank_ac_details', 'total_amount', 'status_of_payment', 'updated_at');

		$currency_symbol = $this->config->item('site_settings')->currency_symbol;
		$crud->display_as('no_of_credits_to_be_converted', get_languageword('credits_acquired'));
		$crud->display_as('per_credit_cost', get_languageword('per_credit_cost')." (".$currency_symbol.")");
		$crud->display_as('total_amount', get_languageword('total_amount')." (".$currency_symbol.")");


		$crud->callback_column('booking_id',array($this,'callback_booking_id'));

		$output = $crud->render();

		$this->data['activemenu'] 	= "credit_conversion_requests";
		$this->data['activesubmenu'] 	= $param;
		$this->data['pagetitle'] = get_languageword('credit_conversion_requests');
		$this->data['grocery_output'] = $output;
		$this->data['grocery'] = TRUE;
		$this->grocery_output($this->data);	

	}


	function callback_booking_id($value, $row)
	{
		return '<a href="'.URL_TUTOR_STUDENT_ENQUIRIES.'/read/'.$row->booking_id.'">'.$row->booking_id.'</a>';
	}





	/**
	 * Facilitates to set selling courses information
	 *
	 * @access	public
	 * @return	string
	 */
	function sell_courses_online($sc_id = "")
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_tutor()) {
			$this->prepare_flashmessage(get_languageword('You dont have permission to access this page'), 1);
			redirect('auth/login', 'refresh');
		}


		$this->data['message'] = $this->session->flashdata('message');

		$tutor_id = $this->ion_auth->get_user_id();


		if(is_inst_tutor($tutor_id)) {

			$this->prepare_flashmessage(get_languageword('Invalid Request'), 1);
			redirect(URL_TUTOR_INDEX);
		}


		if($this->input->post())
		{

			$total_curriculum_titles 	= count(array_filter($this->input->post('lesson_title')));
			$total_curriculum_files 	= (!empty($_FILES['lesson_file']['name'])) ? count(array_filter($_FILES['lesson_file']['name'])) : 0;
			$total_curriculum_urls 		= ($this->input->post('lesson_url')) ? count(array_filter($this->input->post('lesson_url'))) : 0;


			$this->form_validation->set_rules('course_name', get_languageword('Course_Name'), 'trim|required|xss_clean');
			$this->form_validation->set_rules('course_title', get_languageword('Course_Title'), 'trim|required|xss_clean');
			$this->form_validation->set_rules('description', get_languageword('Description'), 'trim|required|xss_clean');
			$this->form_validation->set_rules('skill_level', get_languageword('Skill_Level'), 'trim|xss_clean');

			$this->form_validation->set_rules('lesson_url[]', get_languageword('Curriculum_Source'), 'trim|xss_clean|valid_url|prep_url');

			$this->form_validation->set_rules('course_price', get_languageword('Course_Price'), 'trim|required|numeric|xss_clean');
			$this->form_validation->set_rules('max_downloads', get_languageword('Maximum_number_of_Downloads'), 'trim|required|integer|xss_clean');
			$this->form_validation->set_rules('status', get_languageword('status'), 'trim|required|xss_clean');


			$this->form_validation->set_error_delimiters('<div class="error">', '</div>');


			if ($this->form_validation->run() == TRUE)
			{

				$valid = 1;

				if($total_curriculum_titles == 0 || ($total_curriculum_files == 0 && $total_curriculum_urls == 0)) {

					$valid = 0;

					if($total_curriculum_titles == 0) {

						if($this->input->post('sc_id'))
							$this->form_validation->set_rules('lesson_title[]', get_languageword('Curriculum_Titles'), 'trim|xss_clean');
						else
							$this->form_validation->set_rules('lesson_title[]', get_languageword('Curriculum_Titles'), 'trim|required|xss_clean');
					}
					if($total_curriculum_files == 0 || $total_curriculum_urls == 0) {

						if($this->input->post('sc_id'))
							$this->form_validation->set_rules('lesson_file[]', get_languageword('Curriculum_Source'), 'trim|xss_clean');
						else
							$this->form_validation->set_rules('lesson_file[]', get_languageword('Curriculum_Source'), 'trim|required|xss_clean');
					}

				} else if($total_curriculum_titles > 0 && $total_curriculum_files > 0) {

					//Check for atleast one valid file from uploaded
					$allowed_types = array('mp2', 'mp3', 'mp4', '3gp', 'pdf', 'ppt', 'pptx', 'doc', 'docx', 'rtf', 'rtx', 'txt', 'text', 'webm', 'aac', 'wav', 'wmv', 'flv', 'avi', 'ogg', 'jpg', 'jpeg', 'png', 'gif', 'svg', 'bmp');

					$uploaded_types = array();

					foreach ($_FILES['lesson_file']['name'] as $key => $value) {
						if(!empty($value))
							$uploaded_types[] = pathinfo($value, PATHINFO_EXTENSION);
					}

					if (!(count(array_intersect($allowed_types, $uploaded_types)) > 0)) {

						$valid = 0;
						$this->form_validation->set_rules('lesson_file[]', get_languageword('Curriculum_Source'), 'callback__msg_allowed_formats');
					}

				}


				if($valid == 0) {

					$this->form_validation->run();
					$this->data['message'] = $this->prepare_message(validation_errors(), 1);

				} else {


					$inputdata['tutor_id']		= $tutor_id;
					$inputdata['course_name']	= $this->input->post('course_name');
					$inputdata['course_title']	= $this->input->post('course_title');
					$inputdata['description']	= $this->input->post('description');
					$inputdata['skill_level']	= $this->input->post('skill_level');
					$inputdata['languages']		= implode(',', $this->input->post('languages'));
					$inputdata['course_price']	= $this->input->post('course_price');
					$inputdata['max_downloads']	= $this->input->post('max_downloads');
					$inputdata['status']		= $this->input->post('status');
					$inputdata['admin_approved']= 'No';
					$inputdata['admin_commission_percentage']= $this->config->item('site_settings')->admin_commission_on_course_purchase;

					$course_image 	= $_FILES['course_image']['name'];
					$preview_image 	= $_FILES['preview_image']['name'];
					$preview_file  	= $_FILES['preview_file']['name'];


					$update_rec_id = $this->input->post('sc_id');

					if($update_rec_id > 0) { //Update Operation

						$prev_coursename = $this->base_model->fetch_value('tutor_selling_courses', 'course_name', array('sc_id' => $update_rec_id));

						//If user updates the username
						if($prev_coursename != $inputdata['course_name']) {
							$slug = prepare_slug($inputdata['course_name'], 'course_name', 'tutor_selling_courses');
							$inputdata['slug'] = $slug;
						}


						$inputdata['updated_at'] = date('Y-m-d H:i:s');

						$rec_det = $this->base_model->fetch_records_from('tutor_selling_courses', array('sc_id' => $update_rec_id));
						if(!empty($rec_det)) $rec_det = $rec_det[0];


						if(!empty($course_image)) {

							$course_image_parts = explode('.', $course_image);
							$course_image_name = str_replace('.', '_', str_replace($course_image_parts[count($course_image_parts)-1], '', $course_image));

							$ext = pathinfo($course_image, PATHINFO_EXTENSION);
							$file_name = $course_image_name.date('Ymdhis').rand().'.'.$ext;
							$config['upload_path'] 		= 'assets/uploads/course_curriculum_files/';
							$config['allowed_types'] 	= 'jpg|jpeg|png|svg|bmp';
							$config['overwrite'] 		= true;
							$config['max_size']     	= '10240';//10MB
							$config['file_name']        = $file_name;

							$this->load->library('upload', $config);
							$this->upload->initialize($config);

							if($this->upload->do_upload('course_image'))
							{
								//Unlink Old File
								if(!empty($rec_det->course_image) && file_exists(URL_PUBLIC_UPLOADS.'course_curriculum_files/'.$rec_det->course_image))
									unlink(URL_PUBLIC_UPLOADS.'course_curriculum_files/'.$rec_det->course_image);

								$inputdata['image']	= $file_name;
							} //else {neatPrint($this->upload->display_errors());}
						}


						if(!empty($preview_image)) {

							$preview_image_parts = explode('.', $preview_image);
							$preview_image_name = str_replace('.', '_', str_replace($preview_image_parts[count($preview_image_parts)-1], '', $preview_image));

							$ext = pathinfo($preview_image, PATHINFO_EXTENSION);
							$file_name = $preview_image_name.date('Ymdhis').rand().'.'.$ext;
							$config['upload_path'] 		= 'assets/uploads/course_curriculum_files/';
							$config['allowed_types'] 	= 'jpg|jpeg|png|svg|bmp';
							$config['overwrite'] 		= true;
							$config['max_size']     	= '10240';//10MB
							$config['file_name']        = $file_name;

							$this->load->library('upload', $config);
							$this->upload->initialize($config);

							if($this->upload->do_upload('preview_image'))
							{
								//Unlink Old File
								if(!empty($rec_det->preview_image) && file_exists(URL_PUBLIC_UPLOADS.'course_curriculum_files/'.$rec_det->preview_image))
									unlink(URL_PUBLIC_UPLOADS.'course_curriculum_files/'.$rec_det->preview_image);

								$inputdata['preview_image']	= $file_name;
							}
						}


						if(!empty($preview_file)) {

							$preview_file_parts = explode('.', $preview_file);
							$preview_file_name = str_replace('.', '_', str_replace($preview_file_parts[count($preview_file_parts)-1], '', $preview_file));

							$ext = pathinfo($preview_file, PATHINFO_EXTENSION);
							$file_name = $preview_file_name.date('Ymdhis').rand().'.'.$ext;
							$config['upload_path'] 		= 'assets/uploads/course_curriculum_files/';
							$config['allowed_types'] 	= 'mp2|mp3|mp4|3gp|pdf|webm|aac|wav|wmv|flv|avi|ogg|jpg|jpeg|png|svg|bmp';
							$config['overwrite'] 		= true;
							$config['max_size']     	= '10240';//10MB
							$config['file_name']        = $file_name;

							$this->load->library('upload', $config);
							$this->upload->initialize($config);

							if($this->upload->do_upload('preview_file'))
							{
								//Unlink Old File
								if(!empty($rec_det->preview_file) && file_exists(URL_PUBLIC_UPLOADS.'course_curriculum_files/'.$rec_det->preview_file))
									unlink(URL_PUBLIC_UPLOADS.'course_curriculum_files/'.$rec_det->preview_file);

								$inputdata['preview_file']	= $file_name;
							}
						}


						if($this->base_model->update_operation($inputdata, 'tutor_selling_courses', array('sc_id' => $update_rec_id))) {

							$this->prepare_flashmessage(get_languageword("Your course has been updated successfully"), 0);

							$lesson_files			= $_FILES['lesson_file'];
							$curriculum_data_final 	= array();

							$curriculum_titles 	= $this->input->post('lesson_title');
							$source_type 		= $this->input->post('source_type');
							$curriculum_urls 	= $this->input->post('lesson_url');
							$total_curriculum_recs = count($curriculum_titles);

							// Loop through each file
							$j = 0;
							$k = 0;
							for($i=0; $i<$total_curriculum_recs; $i++) {

							  	if(!empty($curriculum_titles[$i]) && (!empty($lesson_files['size'][$j]) || !empty($curriculum_urls[$k]))) {

							  		$curriculum_data 		= array();

							  		$curriculum_data['sc_id']	= $update_rec_id;
									$curriculum_data['title']	= $curriculum_titles[$i];
									$curriculum_data['source_type']	= $source_type[$i];

							  		if($source_type[$i] == "file") {

							  			$_FILES['lessonfile']['name'] = $lesson_files['name'][$j];
						                $_FILES['lessonfile']['type'] = $lesson_files['type'][$j];
						                $_FILES['lessonfile']['tmp_name'] = $lesson_files['tmp_name'][$j];
						                $_FILES['lessonfile']['error'] = $lesson_files['error'][$j];
						                $_FILES['lessonfile']['size'] = $lesson_files['size'][$j];

								  		$ext = pathinfo($_FILES['lessonfile']['name'], PATHINFO_EXTENSION);
										$file_name = $update_rec_id.'_'.($j+1).'_'.date('Ymdhis').rand().'.'.$ext;
										$config['upload_path'] 		= 'assets/uploads/course_curriculum_files/';
										$config['allowed_types'] 	= 'mp2|mp3|mp4|3gp|pdf|ppt|pptx|doc|docx|rtf|rtx|txt|text|webm|aac|wav|wmv|flv|avi|ogg|jpg|jpeg|png|gif|svg|bmp';
										$config['overwrite'] 		= true;
										$config['max_size']     	= '20480';//20MB
										$config['file_name']        = $file_name;

										$this->load->library('upload', $config);
										$this->upload->initialize($config);

										if($this->upload->do_upload('lessonfile'))
										{

											$curriculum_data['file_name']	= $file_name;
											$curriculum_data['file_ext']	= $ext;
											$curriculum_data['file_size']	= $_FILES['lessonfile']['size'];

											$curriculum_data_final[] = $curriculum_data;
											$j++;
										} else {
											echo $this->upload->display_errors();
										}

									} else {

										$curriculum_data['file_name']	= $curriculum_urls[$k];
										$curriculum_data['file_ext']	= null;
										$curriculum_data['file_size']	= null;

										$curriculum_data_final[] = $curriculum_data;
										$k++;
									}
							  	}
							}


							if(!empty($curriculum_data_final)) {

								$this->db->insert_batch('tutor_selling_courses_curriculum', $curriculum_data_final);

								$this->prepare_flashmessage(get_languageword("Your course has been published successfully"), 0);

							} else {

								$this->base_model->delete_record_new('tutor_selling_courses', array('sc_id' => $update_rec_id));

								$this->prepare_flashmessage(get_languageword("Your course not published due to invalid input data"), 2);
							}

						} else {

							$this->prepare_flashmessage(get_languageword("Your course not published due to invalid input data"), 2);
						}

						redirect(URL_TUTOR_SELL_COURSES_ONLINE);


					} else { //Insert Operation

						$slug = prepare_slug($inputdata['course_name'], 'course_name', 'tutor_selling_courses');

						$inputdata['slug'] 		 = $slug;
						$inputdata['created_at'] = date('Y-m-d H:i:s');
						$inputdata['updated_at'] = $inputdata['created_at'];


						if(!empty($course_image)) {

							$course_image_parts = explode('.', $course_image);
							$course_image_name = str_replace('.', '_', str_replace($course_image_parts[count($course_image_parts)-1], '', $course_image));

							$ext = pathinfo($course_image, PATHINFO_EXTENSION);
							$file_name = $course_image_name.date('Ymdhis').rand().'.'.$ext;
							$config['upload_path'] 		= 'assets/uploads/course_curriculum_files/';
							$config['allowed_types'] 	= 'jpg|jpeg|png|svg|bmp';
							$config['overwrite'] 		= true;
							$config['max_size']     	= '10240';//10MB
							$config['file_name']        = $file_name;

							$this->load->library('upload', $config);
							$this->upload->initialize($config);

							if($this->upload->do_upload('course_image'))
							{
								$inputdata['image']	= $file_name;
							}
						}


						if(!empty($preview_image)) {

							$preview_image_parts = explode('.', $preview_image);
							$preview_image_name = str_replace('.', '_', str_replace($preview_image_parts[count($preview_image_parts)-1], '', $preview_image));

							$ext = pathinfo($preview_image, PATHINFO_EXTENSION);
							$file_name = $preview_image_name.date('Ymdhis').rand().'.'.$ext;
							$config['upload_path'] 		= 'assets/uploads/course_curriculum_files/';
							$config['allowed_types'] 	= 'jpg|jpeg|png|svg|bmp';
							$config['overwrite'] 		= true;
							$config['max_size']     	= '10240';//10MB
							$config['file_name']        = $file_name;

							$this->load->library('upload', $config);
							$this->upload->initialize($config);

							if($this->upload->do_upload('preview_image'))
							{
								$inputdata['preview_image']	= $file_name;
							}
						}


						if(!empty($preview_file)) {

							$preview_file_parts = explode('.', $preview_file);
							$preview_file_name = str_replace('.', '_', str_replace($preview_file_parts[count($preview_file_parts)-1], '', $preview_file));

							$ext = pathinfo($preview_file, PATHINFO_EXTENSION);
							$file_name = $preview_file_name.date('Ymdhis').rand().'.'.$ext;
							$config['upload_path'] 		= 'assets/uploads/course_curriculum_files/';
							$config['allowed_types'] 	= 'mp2|mp3|mp4|3gp|pdf|webm|aac|wav|wmv|flv|avi|ogg|jpg|jpeg|png|svg|bmp';
							$config['overwrite'] 		= true;
							$config['max_size']     	= '10240';//10MB
							$config['file_name']        = $file_name;

							$this->load->library('upload', $config);
							$this->upload->initialize($config);

							if($this->upload->do_upload('preview_file'))
							{
								$inputdata['preview_file']	= $file_name;
							}
						}


						$insert_id = $this->base_model->insert_operation($inputdata, 'tutor_selling_courses');

						if($insert_id > 0) {

							$lesson_files			= $_FILES['lesson_file'];
							$curriculum_data_final 	= array();

							$curriculum_titles 	= $this->input->post('lesson_title');
							$source_type 		= $this->input->post('source_type');
							$curriculum_urls 	= $this->input->post('lesson_url');
							$total_curriculum_recs = count($curriculum_titles);

							// Loop through each file
							$j = 0;
							$k = 0;
							for($i=0; $i<$total_curriculum_recs; $i++) {

							  	if(!empty($curriculum_titles[$i]) && (!empty($lesson_files['name'][$j]) || !empty($curriculum_urls[$k]))) {

							  		$curriculum_data 		= array();

							  		$curriculum_data['sc_id']	= $insert_id;
									$curriculum_data['title']	= $curriculum_titles[$i];
									$curriculum_data['source_type']	= $source_type[$i];

							  		if($source_type[$i] == "file") {

							  			$_FILES['lessonfile']['name'] = $lesson_files['name'][$j];
						                $_FILES['lessonfile']['type'] = $lesson_files['type'][$j];
						                $_FILES['lessonfile']['tmp_name'] = $lesson_files['tmp_name'][$j];
						                $_FILES['lessonfile']['error'] = $lesson_files['error'][$j];
						                $_FILES['lessonfile']['size'] = $lesson_files['size'][$j];

								  		$ext = pathinfo($_FILES['lessonfile']['name'], PATHINFO_EXTENSION);
										$file_name = $insert_id.'_'.($j+1).'_'.date('Ymdhis').rand().'.'.$ext;
										$config['upload_path'] 		= 'assets/uploads/course_curriculum_files/';
										$config['allowed_types'] 	= 'mp2|mp3|mp4|3gp|pdf|ppt|pptx|doc|docx|rtf|rtx|txt|text|webm|aac|wav|wmv|flv|avi|ogg|jpg|jpeg|png|gif|svg|bmp';
										$config['overwrite'] 		= true;
										$config['max_size']     	= '20480';//10MB
										$config['file_name']        = $file_name;

										$this->load->library('upload', $config);
										$this->upload->initialize($config);

										if($this->upload->do_upload('lessonfile'))
										{

											$curriculum_data['file_name']	= $file_name;
											$curriculum_data['file_ext']	= $ext;
											$curriculum_data['file_size']	= $_FILES['lessonfile']['size'];

											$curriculum_data_final[] = $curriculum_data;
											$j++;
										}

									} else {

										$curriculum_data['file_name']	= $curriculum_urls[$k];
										$curriculum_data['file_ext']	= null;
										$curriculum_data['file_size']	= null;

										$curriculum_data_final[] = $curriculum_data;
										$k++;
									}
							  	}
							}


							if(!empty($curriculum_data_final)) {

								$this->db->insert_batch('tutor_selling_courses_curriculum', $curriculum_data_final);

								$this->prepare_flashmessage(get_languageword("Your course has been published successfully"), 0);

							} else {

								$this->base_model->delete_record_new('tutor_selling_courses', array('sc_id' => $insert_id));

								$this->prepare_flashmessage(get_languageword("Your course not published due to invalid input data"), 2);
							}

						} else {

							$this->prepare_flashmessage(get_languageword("Your course not published due to invalid input data"), 2);
						}

						redirect(URL_TUTOR_SELL_COURSES_ONLINE);

					} // Insert Operation - End

				}

			}
			else
			{
				$this->data['message'] = $this->prepare_message(validation_errors(), 1);
			}
		}



		//Edit Operation
		if(!empty($sc_id)) {

			$record = get_tutor_sellingcourse_info($sc_id);

			if(empty($record)) {

				$this->prepare_flashmessage(get_languageword('No Details Found'), 2);
				redirect(URL_TUTOR_LIST_SELLING_COURSES);

			}

			$this->data['record'] = $record;
		}


		//Preparing Language options
		$lng_opts = $this->base_model->fetch_records_from('languages',array('status' => 'Active'));
		$options = array();
		if(!empty($lng_opts))
		{
			foreach($lng_opts as $row):
				$options[$row->name] = $row->name;
			endforeach;
		}

		$this->data['language_options'] = $options;
		$this->data['activemenu'] 		= "sell_courses_online";
		$this->data['activesubmenu'] 	= "publish";
		$this->data['pagetitle'] 		= get_languageword('Sell_Courses_Online');
		$this->data['content'] 			= 'sell_courses_online';
		$this->data['texteditor'] 		= TRUE;
		$this->_render_page('template/site/tutor-template', $this->data);
	}



	function _msg_allowed_formats()
	{
		$this->form_validation->set_message('_msg_allowed_formats', get_languageword('Please upload files only with allowed formats')." ".get_languageword('Allowed File Foramts are')." 'mp2', 'mp3', 'mp4', '3gp', 'pdf', 'ppt', 'pptx', 'doc', 'docx', 'rtf', 'rtx', 'txt', 'text', 'webm', 'aac', 'wav', 'wmv', 'flv', 'avi', 'ogg', 'jpg', 'jpeg', 'png', 'gif', 'svg', 'bmp'.");

		return false;
	}



	function list_selling_courses($operation = "", $sc_id = "")
	{

		if (!$this->ion_auth->logged_in() || !($this->ion_auth->is_tutor() || $this->ion_auth->is_admin())) {
			$this->prepare_flashmessage(get_languageword('You dont have permission to access this page'), 1);
			redirect('auth/login', 'refresh');
		}


		if($this->ion_auth->is_tutor()) {

			$tutor_id = $this->ion_auth->get_user_id();

			if(is_inst_tutor($tutor_id)) {

				$this->prepare_flashmessage(get_languageword('Invalid Request'), 1);
				redirect(URL_TUTOR_INDEX);
			}
		}



		//Delete Operation - Start
		if($operation == "delete" && $sc_id > 0) {

			$query_course_files = "(SELECT preview_file AS file_name FROM ".TBL_PREFIX."tutor_selling_courses WHERE sc_id=".$sc_id.") UNION (SELECT file_name FROM ".TBL_PREFIX."tutor_selling_courses_curriculum WHERE sc_id=".$sc_id.") ";

			$course_curriculum_files = $this->base_model->get_query_result($query_course_files);

			if($this->base_model->delete_record_new('tutor_selling_courses', array('sc_id' => $sc_id))) {

				$this->base_model->delete_record_new('tutor_selling_courses_curriculum', array('sc_id' => $sc_id));

				if(!empty($course_curriculum_files)) {

					foreach ($course_curriculum_files as $key => $value) {

						if(file_exists(URL_PUBLIC_UPLOADS.'course_curriculum_files/'.$value->file_name))
							unlink(URL_PUBLIC_UPLOADS.'course_curriculum_files/'.$value->file_name);
					}
				}

				$this->prepare_flashmessage(get_languageword('Record_Deleted_Successfully'), 0);

			} else {

				$this->prepare_flashmessage(get_languageword('Record_Not_Deleted'), 2);
			}

			redirect(URL_TUTOR_LIST_SELLING_COURSES);
		}
		//Delete Operation - End



		$this->load->library(array('grocery_CRUD'));
		$crud = new grocery_CRUD();
		$crud_state = $crud->getState();
		$crud->set_table($this->db->dbprefix('tutor_selling_courses'));

		if($this->ion_auth->is_admin()) {

			$crud->set_relation('tutor_id', TBL_PREFIX.'users', 'username');
		}

		$crud->set_subject( get_languageword('Published_Courses') );

		if($this->ion_auth->is_admin()) {

			$crud->unset_jquery();

			$crud->unset_add();
			$crud->unset_delete();
			$crud->unset_read();

			$crud->columns('tutor_id', 'course_name', 'course_title', 'course_price', 'admin_commission_percentage', 'max_downloads', 'status', 'admin_approved');

			$crud->edit_fields('admin_approved');

			$crud->display_as('tutor_id', get_languageword('Tutor_Name'));

			$activemenu = 'tutor_selling_courses';
			$title 		= get_languageword('Tutor_Selling_Courses');

		} else {

			$crud->where('tutor_id', $tutor_id);

			$crud->unset_operations();

			$crud->columns('course_name', 'course_title', 'course_price', 'max_downloads', 'status', 'admin_approved');

			$activemenu = 'sell_courses_online';
			$title 		= get_languageword('My_Selling_Courses');
			$this->data['activesubmenu'] 	= "list";
		}

		$crud->display_as('course_title', get_languageword('title'));
		$crud->display_as('course_price', get_languageword('price'));


		$crud->add_action(get_languageword('delete'), URL_FRONT_IMAGES.'close-grocery.png', '', 'delete-icon-grocery', array($this,'callback_delete_selling_course'));

		if($this->ion_auth->is_tutor())
			$crud->add_action(get_languageword('edit'), URL_FRONT_IMAGES.'edit-grocery.png', URL_TUTOR_SELL_COURSES_ONLINE.'/');

		$crud->add_action(get_languageword('View_Curriculum'), URL_FRONT_IMAGES.'magnifier-grocery.png', URL_TUTOR_VIEW_SELLING_COURSE_CURRICULUM.'/');


		$output = $crud->render();

		$this->data['activemenu'] 		= $activemenu;
		$this->data['pagetitle'] 		= $title;
		$this->data['grocery_output'] 	= $output;
		$this->data['grocery'] = TRUE;
		$this->grocery_output($this->data);

	}


	function callback_delete_selling_course($primary_key , $row)
	{
		return URL_TUTOR_LIST_SELLING_COURSES.'/delete/'.$row->sc_id;
	}


	function delete_course_curriculum_record($file_id = "")
	{
		if (!$this->ion_auth->logged_in() || !($this->ion_auth->is_tutor())) {
			$this->prepare_flashmessage(get_languageword('You dont have permission to access this page'), 1);
			redirect('auth/login', 'refresh');
		}


		if($this->ion_auth->is_tutor()) {

			$tutor_id = $this->ion_auth->get_user_id();

			if(is_inst_tutor($tutor_id)) {

				$this->prepare_flashmessage(get_languageword('Invalid Request'), 1);
				redirect(URL_TUTOR_INDEX);
			}
		}


		if(!($file_id > 0)) {

			$this->prepare_flashmessage(get_languageword('No_Details_Found'), 2);
			redirect(URL_TUTOR_LIST_SELLING_COURSES);
		}


		$record = $this->base_model->fetch_records_from('tutor_selling_courses_curriculum', array('file_id' => $file_id));


		if(empty($record)) {

			$this->prepare_flashmessage(get_languageword('No Details Found'), 2);
			redirect(URL_TUTOR_LIST_SELLING_COURSES);

		} else {

			$record = $record[0];

			$sc_id = $record->sc_id;

			if($record->source_type == "file" && $record->file_name != "" && file_exists(URL_PUBLIC_UPLOADS.'course_curriculum_files/'.$record->file_name)) {

				//Unlink file first
				unlink(URL_PUBLIC_UPLOADS.'course_curriculum_files/'.$record->file_name);

			}

			if($this->base_model->delete_record_new('tutor_selling_courses_curriculum', array('file_id' => $file_id))) {

				$this->prepare_flashmessage(get_languageword('Record_Deleted_Successfully'), 0);

			} else {

				$this->prepare_flashmessage(get_languageword('Record_Not_Deleted'), 2);
			}

			redirect(URL_TUTOR_VIEW_SELLING_COURSE_CURRICULUM.'/'.$sc_id);
		}

	}



	function view_selling_course_curriculum($sc_id = "")
	{

		if (!$this->ion_auth->logged_in() || !($this->ion_auth->is_tutor() || $this->ion_auth->is_admin())) {
			$this->prepare_flashmessage(get_languageword('You dont have permission to access this page'), 1);
			redirect('auth/login', 'refresh');
		}


		if($this->ion_auth->is_tutor()) {

			$tutor_id = $this->ion_auth->get_user_id();

			if(is_inst_tutor($tutor_id)) {

				$this->prepare_flashmessage(get_languageword('Invalid Request'), 1);
				redirect(URL_TUTOR_INDEX);
			}
		}


		if(!($sc_id > 0)) {

			$this->prepare_flashmessage(get_languageword('No_Details_Found'), 2);
			redirect(URL_TUTOR_LIST_SELLING_COURSES);
		}


		$record = get_tutor_sellingcourse_info($sc_id);

		if(empty($record)) {

			$this->prepare_flashmessage(get_languageword('No Details Found'), 2);
			redirect(URL_TUTOR_LIST_SELLING_COURSES);

		}


		$this->data['record'] = $record;

		$template = 'template/site/tutor-template';
		$activemenu = 'sell_courses_online';

		if($this->ion_auth->is_admin()) {

			$template = 'template/admin/admin-template';
			$activemenu = 'tutor_selling_courses';
		}

		$this->data['activemenu'] 		= $activemenu;
		$this->data['activesubmenu'] 	= "list";
		$this->data['pagetitle'] 		= get_languageword('Selling_Course_Curriculum');
		$this->data['content'] 			= 'selling_course_curriculum';

		$this->_render_page($template, $this->data);

	}




	function purchased_courses()
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_tutor()) {
			$this->prepare_flashmessage(get_languageword('You dont have permission to access this page'), 1);
			redirect('auth/login', 'refresh');
		}

		$tutor_id = $this->ion_auth->get_user_id();

		$this->load->library(array('grocery_CRUD'));
		$crud = new grocery_CRUD();
		$crud_state = $crud->getState();
		$crud->set_table(TBL_PREFIX.'course_purchases');
		$crud->set_relation('sc_id',TBL_PREFIX.'tutor_selling_courses','course_title');
		$crud->set_relation('user_id',TBL_PREFIX.'users','username');
		$crud->where(TBL_PREFIX.'course_purchases.tutor_id', $tutor_id);
		$crud->where('payment_status', 'Completed');

		$crud->set_subject( get_languageword('Purchased_Courses') );

		$crud->unset_add();
		$crud->unset_delete();
		$crud->unset_edit();
		$crud->unset_read();

		$crud->columns('sc_id','user_id','total_amount','admin_commission_val','max_downloads','total_downloads','paid_date', 'status_of_payment_to_tutor');

		$crud->display_as('sc_id', get_languageword('Course_Title'));
		$crud->display_as('user_id', get_languageword('student_name'));
		$crud->display_as('paid_date', get_languageword('Purchased_On'));
		$crud->display_as('status_of_payment_to_tutor', get_languageword('Payment_from_Admin'));

		$output = $crud->render();

		$this->data['activemenu'] 		= "purchased_courses";
		$this->data['pagetitle'] 		= get_languageword('Purchased_Courses');
		$this->data['grocery_output'] 	= $output;
		$this->data['grocery'] 			= TRUE;
		$this->grocery_output($this->data);

	}






}
?>
