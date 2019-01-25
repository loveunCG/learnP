<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Institute extends MY_Controller 
{
	function __construct()
	{
		parent::__construct();


		$this->load->library(array('session'));
		$this->load->library(array('ion_auth','form_validation'));
		$this->load->model('home_model');

		$this->check_inst_access();
		
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
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_institute()) {
			$this->prepare_flashmessage(get_languageword('You dont have permission to access this page'), 1);
			redirect('auth/login', 'refresh');
		}
		
		$this->data['message'] = $this->session->flashdata('message');
		$user_id = $this->ion_auth->get_user_id();
		
		$this->load->model('institute_model');
		$inst_dashboard_data = $this->institute_model->get_inst_dashboard_data($user_id);
		$this->data['inst_dashboard_data']	= $inst_dashboard_data;	

		$profile = getUserRec();
		$this->data['pagetitle'] 	= get_languageword('dashboard');
		$this->data['activemenu'] 	= "dashboard";
		$this->data['activesubmenu'] = "dashboard";
		$this->data['content'] 		= 'index';
		
		$this->_render_page('template/site/institute-template', $this->data);
	}
	
	/**
	 * Fecilitates to upload gallery pictures
	 *
	 * @access	public
	 * @return	string
	 */
	function my_gallery()
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_institute()) {
			$this->prepare_flashmessage(get_languageword('You dont have permission to access this page'), 1);
			redirect('auth/login', 'refresh');
		}
		$this->data['activemenu'] 	= "account";
		$this->data['activesubmenu'] = "gallery";
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
		$this->_render_page('template/site/institute-template-grocery',$this->data);
	}
	


	/**
	 * Fecilitates to update personal information
	 *
	 * @access	public
	 * @return	string
	 */
	function personal_info()
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_institute()) {
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
				$inputdata['language_of_teaching'] = implode(',', $language_of_teaching);

				
				$this->base_model->update_operation($inputdata, 'users', array('id' => $user_id));
				
				$this->prepare_flashmessage(get_languageword('profile updated successfully'), 0);
				redirect('institute/personal-info');				
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
		$this->data['pagetitle'] = get_languageword('personal_info');
		$this->data['content'] 		= 'personal_info';
		$this->_render_page('template/site/institute-template', $this->data);
	}
	
	/**
	 * Fecilitates to update profile information includes profile picture
	 *
	 * @access	public
	 * @return	string
	 */
	function profile_information()
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_institute()) {
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
				$inputdata['profile'] = $this->input->post('profile');
				$inputdata['seo_keywords'] = $this->input->post('seo_keywords');
				$inputdata['meta_desc'] = $this->input->post('meta_desc');
				$inputdata['profile_page_title'] = $this->input->post('profile_page_title');
				$inputdata['teaching_experience'] = $this->input->post('teaching_experience');
							
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
				redirect('institute/profile-information');				
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
		$this->data['pagetitle'] = get_languageword('profile_information');
		$this->data['content'] 		= 'profile_information';
		$this->_render_page('template/site/institute-template', $this->data);
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
	function education($param1 = null, $param2 = null)
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_institute()) {
			$this->prepare_flashmessage(get_languageword('You dont have permission to access this page'), 1);
			redirect('auth/login', 'refresh');
		}
		
		$this->data['message'] = $this->session->flashdata('message');
		
		if($param1 == 'delete' && $param2 != '')
		{
			$this->base_model->delete_record_new('users_education', array('record_id' => $param2));
			$this->prepare_flashmessage(get_languageword('record deleted successfully'), 0);				
			redirect('tutor/education');	
		}
		
		if(isset($_POST['submitbutt']))
		{
			$this->form_validation->set_rules('degree', get_languageword('degree'), 'trim|required|xss_clean');
			if($this->input->post('degree') == 0)
			{
				$this->form_validation->set_rules('other_title', get_languageword('other_title'), 'trim|required|xss_clean');
			}
			$this->form_validation->set_rules('education_institute', get_languageword('University'), 'trim|required|xss_clean');
			$this->form_validation->set_rules('education_address', get_languageword('Address'), 'trim|required|xss_clean');
			$this->form_validation->set_rules('education_year', get_languageword('Year'), 'trim|required|xss_clean');
			
			$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
			
			if ($this->form_validation->run() == TRUE)
			{
				$inputdata['education_id'] = $this->input->post('degree');
				$inputdata['other_title'] = $this->input->post('other_title');
				$inputdata['education_institute'] = $this->input->post('education_institute');
				$inputdata['education_address'] = $this->input->post('education_address');
				$inputdata['education_year'] = $this->input->post('education_year');$inputdata['user_id'] = $this->ion_auth->get_user_id();
				$update_rec_id = $this->input->post('update_rec_id');
				if($update_rec_id != '')
				{
				$this->base_model->update_operation($inputdata, 'users_education', array('record_id' => $update_rec_id));
				$this->prepare_flashmessage(get_languageword('record updated successfully'), 0);
				}
				else
				{
				$this->base_model->insert_operation($inputdata, 'users_education');
				$this->prepare_flashmessage(get_languageword('record added successfully'), 0);
				}				
				redirect('tutor/education');				
			}
			else
			{
				$this->data['message'] = $this->prepare_message(validation_errors(), 1);
			}
		}	
		$this->data['profile'] = array();
		if($param1 == 'edit' && $param2 != '')
		{
			$profile = $this->base_model->fetch_records_from('users_education', array('record_id' => $param2));
			if(!empty($profile))
			$this->data['profile'] = $profile[0];
		}
		$this->data['educations'] = $this->base_model->fetch_records_from('users_education', array('user_id' => $this->ion_auth->get_user_id()));
		$degrees = array();
		$records = $this->base_model->fetch_records_from('terms_data', array('term_type' => 'degree', 'term_status' => 'Active'));
		if(!empty($records))
		{
			foreach($records as $record)
			{
				$degrees[$record->term_id] = $record->term_title;
			}
		}
		$degrees[0] = get_languageword('other');
		$this->data['degrees'] = $degrees;
		
		$years = array();
		for($y = 0; $y < 100; $y++)
		{
			$year = date('Y');
			$years[$year-$y] = $year-$y;
		}
		$this->data['param1'] = $param1;
		$this->data['param2'] = $param2;
		$this->data['years'] = $years;
		$this->data['activemenu'] 	= "account";
		$this->data['activesubmenu'] = "education";
		$this->data['pagetitle'] = $this->data['my_profile']->first_name.' '.$this->data['my_profile']->last_name.' '.get_languageword('Education');
		$this->data['content'] 		= 'education';
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
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_institute()) {
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
				//neatPrint($_POST);
				//echo $this->db->last_query();die();
				$this->prepare_flashmessage(get_languageword('record updated successfully'), 0);								
				redirect('institute/update-contact-information');				
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
		$this->_render_page('template/site/institute-template', $this->data);
	}
	
	/**
	 * Fecilitates to view contact information
	 *
	 * @access	public
	 * @return	string
	 */
	function contact_information()
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_institute()) {
			$this->prepare_flashmessage(get_languageword('You dont have permission to access this page'), 1);
			redirect('auth/login', 'refresh');
		}
		$this->data['profile'] = getUserRec();
		$this->data['activemenu'] 	= "account";
		$this->data['activesubmenu'] = "update_contact_info";	
		$this->data['content'] 		= 'contact_information';
		$this->data['pagetitle'] = get_languageword('Contact Information');
		$this->_render_page('template/site/institute-template', $this->data);
	}
	


	/**
	 * Fecilitates to view Courses
	 *
	 * @access	public
	 * @return	string
	 */
	function courses()
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_institute()) {
			$this->prepare_flashmessage(get_languageword('You dont have permission to access this page'), 1);
			redirect('auth/login', 'refresh');
		}
		
		$this->data['message'] = $this->session->flashdata('message');
		$this->load->model('institute_model');
		$instituteOfferedCourseIds	= $this->institute_model->get_institute_offered_course_ids($this->ion_auth->get_user_id()); //Getting locaiton ids
		

		if ($this->input->post()) 
		{		
			if ($this->input->post('institute_courses')) {

				$user_id = $this->ion_auth->get_user_id();

				if ($this->input->post('institute_courses') != $instituteOfferedCourseIds) {
					$institute_courses 	= $this->input->post('institute_courses');
					if ($this->base_model->delete_record_new('inst_offered_courses', array('inst_id'=> $user_id))) {
						$data['inst_id'] 	= $this->ion_auth->get_user_id();
						$data['created_at'] = date('Y-m-d H:i:s');
						$data['updated_at'] = $data['created_at'];
						foreach($institute_courses as $courses) {
							if (is_numeric($courses)) {
								$data['course_id'] = $courses;
								$this->base_model->insert_operation($data, 'inst_offered_courses');
							}
						}

						$is_profile_updated = $this->ion_auth->user($user_id)->row()->is_profile_update;

						if($is_profile_updated != 1) {

							$inst_teaching_types = $this->base_model->fetch_records_from('inst_teaching_types', array('inst_id' => $user_id, 'status' => 1));
							if(count($inst_teaching_types) > 0)
								$this->base_model->update_operation(array('is_profile_update' => 1), 'users', array('id' => $user_id));
						}

						$this->prepare_flashmessage(get_languageword('courses') . " " . get_languageword('updated successfully'), 0);
					}
					else
					{
						$this->prepare_flashmessage(get_languageword('courses') . " " . get_languageword('failed to updated'), 1);
					}						
				}
				else {
					$this->prepare_flashmessage(get_languageword('You have not done any changes'), 2);
				}
			}
			else {
				$this->prepare_flashmessage(
				get_languageword('please_select_atleast_one_preferred_course'), 1);
			}
			redirect('institute/courses');
		}

		$this->data['courses'] 				  =   $this->home_model->get_popular_courses('','',false);
		$this->data['instituteOfferedCourseIds'] =   $instituteOfferedCourseIds;
		$this->data['activemenu'] 	= "manage";
		$this->data['activesubmenu'] = "manage_courses";
		$this->data['pagetitle'] = get_languageword('manage_courses');		
		$this->data['content'] 		= 'manage_courses';
		$this->_render_page('template/site/institute-template', $this->data);
	}


	function set_course_dropdown()
	{
		//Course Options
		$this->load->model('home_model');
		$courses = $this->home_model->get_courses();
		$course_opts[''] = get_languageword('select_course');
		foreach ($courses as $key => $value) {
			$course_opts[$value->id] = $value->name;
		}
		return form_dropdown('course_id', $course_opts, '', 'id="course_id" class="chosen-select" ');
	}


	/**
	 * Fecilitates to add or update tutor locations, where he is tutoring
	 *
	 * @access	public
	 * @return	string
	 */
	function locations()
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_institute()) {
			$this->prepare_flashmessage(get_languageword('You dont have permission to access this page'), 1);
			redirect('auth/login', 'refresh');
		}
		
		$this->data['message'] = $this->session->flashdata('message');
		$this->load->model('institute_model');
		$instituteLocationIds 	= $this->institute_model->get_institute_location_ids(
		$this->ion_auth->get_user_id()); //Getting locaiton ids
		
		if ($this->input->post()) 
		{		
			if ($this->input->post('institute_locations')) {
				if ($this->input->post('institute_locations') != $instituteLocationIds) {
					$institute_locations 	= $this->input->post('institute_locations');
					if ($this->base_model->delete_record_new('inst_locations', array('inst_id' 	=> $this->ion_auth->get_user_id()))) 
					{
						$data['inst_id'] 	= $this->ion_auth->get_user_id();
						$data['created_at'] = date('Y-m-d H:i:s');
						$data['updated_at'] = $data['created_at'];
						foreach($institute_locations as $location) {
								if (is_numeric($location)) {
									$data['location_id'] = $location;
									$this->base_model->insert_operation($data, 'inst_locations');
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

			redirect('institute/locations');
		}
		
		$this->data['locations'] 				= $this->institute_model->get_locations();
		$this->data['instituteLocationIds'] 		= $instituteLocationIds;
		
		$this->data['activemenu'] 	= "manage";
		$this->data['activesubmenu'] = "manage_locations";
		$this->data['pagetitle'] = get_languageword('manage_locations');		
		$this->data['content'] 		= 'manage_locations';

		$this->_render_page('template/site/institute-template', $this->data);
	}
	
	/**
	 * Fecilitates to add or update Institute teaching types
	 *
	 * @access	public
	 * @return	string
	 */
	function teaching_types()
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_institute()) {
			$this->prepare_flashmessage(get_languageword('You dont have permission to access this page'), 1);
			redirect('auth/login', 'refresh');
		}
		
		$this->data['message'] = $this->session->flashdata('message');
		$this->load->model('institute_model');
		$instituteSelectedTypeIds 	= $this->institute_model->get_institute_selected_teachingtype_ids(
		$this->ion_auth->get_user_id());
		
		if ($this->input->post()) 
		{
			$user_id = $this->ion_auth->get_user_id();
			if ($this->input->post('institute_selected_types')) {
				if ($this->input->post('institute_selected_types') != $instituteSelectedTypeIds) {
					$institute_selected_types 	= $this->input->post('institute_selected_types');
					if ($this->base_model->delete_record_new('inst_teaching_types', array('inst_id' => $user_id))) {
						$data['inst_id'] 		= $this->ion_auth->get_user_id();
						$data['created_at'] 	= date('Y-m-d H:i:s');
						$data['updated_at'] 	= $data['created_at']; 
						foreach($institute_selected_types as $institute_type) {
							if (is_numeric($institute_type)) {
								$data['teaching_type_id'] = $institute_type;
								$this->base_model->insert_operation($data, 'inst_teaching_types');
							}
						}

						$is_profile_updated = $this->ion_auth->user($user_id)->row()->is_profile_update;

						if($is_profile_updated != 1) {

							$inst_offered_courses = $this->base_model->fetch_records_from('inst_offered_courses', array('inst_id' => $user_id, 'status' => 1));
							if(count($inst_offered_courses) > 0)
								$this->base_model->update_operation(array('is_profile_update' => 1), 'users', array('id' => $user_id));
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
			redirect('institute/teaching-types', 'refresh');
		}
		
		$this->data['institute_types'] 				= $this->institute_model->get_institute_teachingtypes();
		$this->data['insittuteSelectedTypeIds']	 	= $instituteSelectedTypeIds;
		$this->data['activemenu'] 	= "manage";
		$this->data['activesubmenu'] = "manage_teaching_types";	
		$this->data['pagetitle'] = get_languageword('Teaching Types');
		$this->data['content'] 		= 'manage_teaching_types';
		$this->_render_page('template/site/institute-template', $this->data);
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
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_institute()) {
			$this->prepare_flashmessage(get_languageword('You dont have permission to access this page'), 1);
			redirect('auth/login', 'refresh');
		}
		
		$this->data['message'] = $this->session->flashdata('message');
				
		$this->data['pagetitle'] = get_languageword('packages');
		$this->load->model('institute_model');
		$this->data['package_data'] = $this->institute_model->list_institute_packages();
		
		$this->data['payment_gateways'] = $this->base_model->get_payment_gateways('', 'Active');

		$this->data['activemenu'] 	= "Packages";
		$this->data['activesubmenu'] = "list_packages";	
		$this->data['pagetitle'] = get_languageword('Packages');
		$this->data['content'] 		= 'list_packages';
		$this->_render_page('template/site/institute-template', $this->data);
	}
	
	function mysubscriptions()
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_institute()) {
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
		//$crud->set_theme('datatables');

		$crud->unset_add();
		$crud->unset_delete();
		$crud->unset_edit();
		$crud->unset_read();

		$crud->columns('subscribe_date','package_name','transaction_no', 'payment_type','credits','amount_paid');
		$crud->callback_column('subscribe_date',array($this,'callback_subscribe_date'));
		$output = $crud->render();
		
		$this->data['pagetitle'] = get_languageword('My Subscriptions');
		$this->data['activemenu'] 	= "Packages";
		$this->data['activesubmenu'] = "mysubscriptions";
		$this->data['pagetitle'] = get_languageword('my_subscriptions');
		$this->data['content'] 		= 'mysubscriptions';
		$this->data['grocery_output'] = $output;
		$this->data['grocery'] = TRUE;
		$this->_render_page('template/site/institute-template-grocery',$this->data);
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
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_institute()) {
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
			redirect(URL_INSTITUTE_MANAGE_PRIVACY);			
		}
		
		$this->data['pagetitle'] = get_languageword('Manage Privacy');
		$this->data['activemenu'] 	= "manage";
		$this->data['activesubmenu'] = "manage_privacy";	
		$this->data['content'] 		= 'manage_privacy';
		$this->_render_page('template/site/tutor-template', $this->data);
	}
		
	/**
	 * Fecilitates to upload certificates
	 *
	 * @access	public
	 * @return	string
	 */
	function certificates()
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_institute()) {
			$this->prepare_flashmessage(get_languageword('You dont have permission to access this page'), 1);
			redirect('auth/login', 'refresh');
		}
		
		$this->data['message'] = $this->session->flashdata('message');
		$user_id = $this->ion_auth->get_user_id();
		
		if(isset($_POST['submitbutt']))
		{

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
							if(isset($this->config->item('site_settings')->need_admin_for_student) && $this->config->item('site_settings')->need_admin_for_student == 'yes')
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
									if(isset($this->config->item('site_settings')->need_admin_for_student) && $this->config->item('site_settings')->need_admin_for_student == 'yes')
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
			redirect('institute/certificates');
		}
		
		$certificates = $this->base_model->fetch_records_from('certificates', array('certificate_for' => 'institutes', 'status' => 'Active'));		
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
		$this->data['activesubmenu'] = "certificates";
		$this->data['pagetitle']	= get_languageword('manage_certificates');
		$this->data['content'] 		= 'certificates';
		$this->_render_page('template/site/institute-template', $this->data);
	}
	/**
	 * Batches
	 *
	 * @access	public
	 * @return	string
	 */
	function batches()
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_institute()) {
			$this->prepare_flashmessage(get_languageword('You dont have permission to access this page'), 1);
			redirect('auth/login', 'refresh');
		}

		$inst_id = $this->ion_auth->get_user_id();
		$this->load->library(array('grocery_CRUD'));
		$crud = new grocery_CRUD();
		$crud_state = $crud->getState();
		$crud->set_table($this->db->dbprefix('inst_batches'));
		$crud->where('inst_id', $inst_id);
		$crud->set_relation('course_id','categories','name');
		$crud->set_relation('tutor_id','users','username');
		
		$crud->set_subject( get_languageword('institute_batches') );

		

		$crud->columns('course_id','batch_name','batch_code','tutor_id','time_slot','days_off','duration_value','duration_type','fee');

		$crud->add_fields('inst_id', 'course_id','batch_name','batch_code','tutor_id','course_content', 'time_slot', 'course_offering_location', 'batch_start_date', 'batch_end_date', 'batch_max_strength', 'days_off','duration_value','duration_type','fee', 'per_credit_value', 'created_at','updated_at');

		$crud->edit_fields('inst_id', 'course_id','batch_name','batch_code','tutor_id','course_content', 'time_slot', 'course_offering_location', 'batch_start_date', 'batch_end_date', 'batch_max_strength', 'days_off','duration_value','duration_type','fee', 'per_credit_value','updated_at');

		$crud->callback_field('batch_start_date',array($this,'call_back_set_batch_start_date_field'));


		//####### Changing column names #######
		$crud->display_as('course_id','Course Name');
		$crud->display_as('student_id','Student Name');
		$crud->display_as('tutor_id','Tutor Name');



		//Set Custom Filed Types
		$crud->field_type('days_off', 'multiselect', array('SUN' => 'SUN', 'MON' => 'MON', 'TUE' => 'TUE', 'WED' => 'WED', 'THU' => 'THU', 'FRI' => 'FRI', 'SAT' => 'SAT')); 

		//callback fields
		$crud->callback_field('course_id',array($this,'call_back_set_course_dropdown'));
		$crud->callback_field('tutor_id',array($this,'call_back_set_tutor_dropdown'));
		$crud->callback_field('course_offering_location',array($this,'call_back_set_col_dropdown'));

		//hidden files
		$crud->field_type('per_credit_value', 'hidden',get_system_settings('per_credit_value'));
		$crud->field_type('batch_end_date', 'invisible');
		$crud->field_type('created_at', 'hidden',date('Y-m-d H:i:s'));
		$crud->field_type('updated_at', 'hidden',date('Y-m-d H:i:s'));
		$crud->field_type('inst_id', 'hidden',$inst_id);

		//From Validations
		$crud->required_fields(array('course_id','tutor_id','duration_value','batch_name','batch_code','duration_type', 'fee', 'content', 'time_slot', 'course_offering_location', 'batch_start_date', 'batch_max_strength', 'sort_order'));
		$crud->set_rules('fee',get_languageword('fee'),'required|integer');

		//ddd
		$crud->callback_add_field('time_slot',function () {
		return '<input type="text" maxlength="50" placeholder=" 24 hour format, Ex: 13-14" name="time_slot">&nbsp; <i class="fa fa-info-circle" title="'.get_languageword('enter_only_one_timeslot_for_one_batch').'" onclick="window.alert(this.title);"></i><br>'; 
		});


		//Authenticate whether Tutor editing/viewing his records only
		if($crud_state == "edit" || $crud_state == "read") {

			$p_key = $this->uri->segment(4);
			$db_inst_id = $this->base_model->fetch_value('inst_batches', 'inst_id', array('batch_id' => $p_key));
			if($db_inst_id != $inst_id) {

				$this->prepare_flashmessage(get_languageword('not_authorized'), 1);
    			redirect(URL_INSTITUTE_BATCHES);
			}

		}


		$crud->callback_before_insert(array($this,'call_back_insert_data'));
		$crud->callback_before_update(array($this,'call_back_update_data'));


		$output = $crud->render();

		$this->data['activemenu'] 	= "manage";
		$this->data['pagetitle'] = get_languageword('institute_batches');
		$this->data['grocery_output'] = $output;
		$this->data['grocery'] = TRUE;
		$this->grocery_output($this->data);
	}


	function call_back_insert_data($post_arry)
	{
	
		$inst_id = $this->ion_auth->get_user_id();
		
		$post_arry['inst_id'] = $inst_id;

		if($post_arry['duration_type'] == "hours") {

			$time_slot  = str_replace(':', '.', $post_arry['time_slot']);
			$time 	    = explode('-', str_replace(' ', '', $time_slot));

			$start_time = number_format($time[0],2);
			$end_time   = number_format($time[1],2);

			$total_time = $end_time - $start_time;

			if($total_time >= 1) {

				$days = round(($post_arry['duration_value']) / $total_time);

			} else {

				$total_time = (int)(explode('.', number_format($total_time,2))[1]);
				$days = round(($post_arry['duration_value']) / ($total_time/60));
			}

			$batch_end_date = date("Y-m-d", strtotime($post_arry['batch_start_date'].'+'.$days.' days'));

		} else {

			$batch_end_date = date("Y-m-d", strtotime($post_arry['batch_start_date'].'+'.$post_arry['duration_value'].' '.$post_arry['duration_type']));
		}

		$batch_end_date = date("Y-m-d", strtotime($batch_end_date.'-1 days'));

		$post_arry['batch_end_date'] = $batch_end_date;

		$this->load->database();
		$this->load->model('base_model');


		$query = "SELECT * 
					FROM ".$this->db->dbprefix('inst_batches')."
					WHERE inst_id =".$inst_id."
					AND tutor_id =".$post_arry['tutor_id']."
					AND (
					batch_name =  '".$post_arry['batch_name']."'
					OR batch_code =  '".$post_arry['batch_code']."'
					OR (
						time_slot ='".$post_arry['time_slot']."'
						AND (
								(
								'".$post_arry['batch_start_date']."'
								BETWEEN batch_start_date
								AND batch_end_date
								)
								OR (
								'".$post_arry['batch_end_date']."' > batch_start_date
								AND  '".$post_arry['batch_end_date']."' < batch_end_date
								)
							)
						)
					)";

		$duplicate_records = $this->db->query($query);
	
		if($duplicate_records->num_rows() > 0) {

			$message = $this->prepare_flashmessage(get_languageword('the_batch_you_are_trying_to_add_is_already_exists'), 1);
			$this->form_validation->set_message('call_back_insert_data', $message);
            return FALSE;

		} else return $post_arry;

	}


	function call_back_update_data($post_arry, $p_key)
	{

		$inst_id =$this->ion_auth->get_user_id();

		$post_arry['inst_id'] = $inst_id;

		if($post_arry['duration_type'] == "hours") {

			$time_slot  = str_replace(':', '.', $post_arry['time_slot']);
			$time 	    = explode('-', str_replace(' ', '', $time_slot));

			$start_time = number_format($time[0],2);
			$end_time   = number_format($time[1],2);

			$total_time = $end_time - $start_time;

			if($total_time >= 1) {

				$days = round(($post_arry['duration_value']) / $total_time);

			} else {

				$total_time = (int)(explode('.', number_format($total_time,2))[1]);
				$days = round(($post_arry['duration_value']) / ($total_time/60));
			}

			$batch_end_date = date("Y-m-d", strtotime($post_arry['batch_start_date'].'+'.$days.' days'));

		} else {

			$batch_end_date = date("Y-m-d", strtotime($post_arry['batch_start_date'].'+'.$post_arry['duration_value'].' '.$post_arry['duration_type']));
		}

		$batch_end_date = date("Y-m-d", strtotime($batch_end_date.'-1 days'));

		$post_arry['batch_end_date'] = $batch_end_date;


		$this->load->database();
		$this->load->model('base_model');

		$query = "SELECT * 
					FROM ".$this->db->dbprefix('inst_batches')."
					WHERE inst_id =".$inst_id."
					AND batch_id!=".$p_key."
					AND tutor_id =".$post_arry['tutor_id']."
					AND (
					batch_name =  '".$post_arry['batch_name']."'
					OR batch_code =  '".$post_arry['batch_code']."'
					OR (
						time_slot ='".$post_arry['time_slot']."'
						AND (
								(
								'".$post_arry['batch_start_date']."'
								BETWEEN batch_start_date
								AND batch_end_date
								)
								OR (
								'".$post_arry['batch_end_date']."' > batch_start_date
								AND  '".$post_arry['batch_end_date']."' < batch_end_date
								)
							)
						)
					)";

		$duplicate_records = $this->db->query($query);

		if($duplicate_records->num_rows() > 0) {

			$message = $this->prepare_flashmessage(get_languageword('the_batch_you_are_trying_to_add_is_already_exists'), 1);
			$this->form_validation->set_message('call_back_update_data', $message);
            return FALSE;

		} else return $post_arry;

	}
	

	function call_back_set_batch_start_date_field($value)
	{
		$value = (!empty($value)) ? $value : "";
		return '<input type="text" name="batch_start_date" value="'.$value.'" class="calendar" data-mindate="today" >';
	}



	function call_back_set_course_dropdown($val)
	{
		$inst_id = $this->ion_auth->get_user_id();
		//Institute Course Options
		$this->load->model('institute_model');
		$inst_courses_opts = $this->institute_model->get_institute_offered_course($inst_id);

		$val = !empty($val) ? $val : '';

		return form_dropdown('course_id', $inst_courses_opts, $val, 'id="course_id" class="chosen-select" ');
	}


	function call_back_set_tutor_dropdown($val)
	{
		//tutor Options
		$this->load->model('institute_model');
		$tutors = $this->institute_model->get_tutors($inst_id = $this->ion_auth->get_user_id());

		if(!empty($tutors)) {

			$tutor_opts[''] = get_languageword('select_tutors');
			foreach ($tutors as $key => $value) {
				$tutor_opts[$value->id] = $value->username;
			}

		} else {

			$tutor_opts[''] = get_languageword('no_tutors_available');
		}

		$val = !empty($val) ? $val : '';

		return form_dropdown('tutor_id', $tutor_opts, $val, 'id="tutor_id" class="chosen-select" ');
	}


	function call_back_set_col_dropdown($val)
	{
		//tutor Options
		$inst_id = $this->ion_auth->get_user_id();
		$this->load->model('institute_model');
		$inst_locations = $this->institute_model->get_inst_locations($inst_id);

		if(!empty($inst_locations)) {

			$inst_loc_opts = array('' => get_languageword('select_course_offering_location'), 'online' => get_languageword('online'));

			foreach ($inst_locations as $key => $value) {
				$inst_loc_opts[$value->location_name] = $value->location_name;
			}

		} else {

			$inst_loc_opts = array('' => get_languageword('no_institute_locations_available'));
		}

		$val = !empty($val) ? $val : '';

		return form_dropdown('course_offering_location', $inst_loc_opts, $val, 'id="course_offering_location" class="chosen-select" ');
	}
		
	
	

	/**
	 * Fecilitates to update student leads
	 * @access	public
	 * @return	string
	 */
	function user_reviews()
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_institute()) {
			$this->prepare_flashmessage(get_languageword('You dont have permission to access this page'), 1);
			redirect('auth/login', 'refresh');
		}
		
		$tutor_id = $this->ion_auth->get_user_id();
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

		#### Invisible fileds in reading ####
		if ($crud->getState() == 'read') {
		    $crud->field_type('tutor_id', 'hidden');
		}

		$output = $crud->render();

		$this->data['activemenu'] 	= "reviews";
		$this->data['pagetitle'] = get_languageword('student_reviews');
		$this->data['grocery_output'] = $output;
		$this->data['grocery'] = TRUE;
		$this->_render_page('template/site/tutor-template-grocery', $this->data);
	}

	function enrolled_students($course_id = "")
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_institute()) {
			$this->prepare_flashmessage(get_languageword('You dont have permission to access this page'), 1);
			redirect('auth/login', 'refresh');
		}

		$course_id = ($this->input->post('course_id')) ? $this->input->post('course_id') : $course_id;

		if($course_id > 0)
		{

			$this->data['message'] = $this->session->flashdata('message');

			$user_id = $this->ion_auth->get_user_id();
			$this->load->library(array('grocery_CRUD'));
			$crud = new grocery_CRUD();
			$crud_state = $crud->getState();
			$crud->set_table($this->db->dbprefix('inst_batches'));
			$crud->where($this->db->dbprefix('inst_batches').'.inst_id', $user_id);
			$crud->where($this->db->dbprefix('inst_batches').'.course_id', $course_id);
			$crud->set_relation('course_id','categories','name');
			$crud->set_relation('tutor_id','users','username');


			$crud->set_subject( get_languageword('inst_enrolled_students') );


			$crud->unset_add();
			$crud->unset_edit();
			$crud->unset_read();
			$crud->unset_delete();

			$crud->columns('batch_name','batch_code','tutor_id', 'course_offering_location', 'batch_start_date', 'time_slot', 'batch_end_date', 'total_enrolled_students','batch_max_strength','course_duration','fee', 'status');


			//display as
			$crud->display_as('tutor_id',get_languageword('tutor_name'));
			$crud->display_as('student_id',get_languageword('student_name'));
			$crud->display_as('status',get_languageword('batch_status'));

			$crud->callback_column('course_duration',array($this,'_callback_course_duration'));
			$crud->callback_column('total_enrolled_students',array($this,'_callback_batch_enrolled_students_cnt'));
			$crud->callback_column('status',array($this,'_callback_batch_status'));

			$crud->add_action(get_languageword('View Enrolled Students'), URL_FRONT_IMAGES.'magnifier.png',  URL_INSTITUTE_GET_BATCH_LIST.'/');
			$crud->add_action(get_languageword('Approve Batch Students'), URL_FRONT_IMAGES.'approve.png',  URL_INSTITUTE_APPROVE_BATCH_STUDENTS.'/'.$course_id.'/');
			$crud->add_action(get_languageword('send_credits_conversion_request_for_this_batch'), URL_FRONT_IMAGES.'/money.png', URL_INSTITUTE_SEND_CREDITS_CONVERSION_REQUEST.'/'.$course_id.'/');

			$output = $crud->render();

			$this->data['course_id'] = $course_id;

			$this->data['grocery_output'] = $output;
			$this->data['grocery'] = TRUE;
		}

		$this->data['message'] = $this->session->flashdata('message');
		$this->data['pagetitle'] = get_languageword('Batches_list');
		$this->load->model('institute_model');
		$this->data['course_data'] = $this->institute_model->get_institute_offered_course($this->ion_auth->get_user_id());
		
		$this->data['activemenu'] 	= "enrolled_students";
		$this->grocery_output($this->data);

	}

	function _callback_course_duration($primary_key, $row)
	{
		return $row->duration_value.' '. $row->duration_type;
	}

	function _callback_batch_enrolled_students_cnt($primary_key, $row)
	{
		$batch_id = $row->batch_id;
		$this->load->model('institute_model');
		return $this->institute_model->get_batch_enrolled_students_cnt($batch_id);
	}

	function _callback_batch_status($primary_key, $row)
	{
		$batch_id = $row->batch_id;

		$this->load->model('institute_model');
		$batch_status = $this->institute_model->get_batch_status($batch_id);

		return get_languageword($batch_status);

	}



	function approve_batch_students($course_id = "", $batch_id = "")
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_institute()) {
			$this->prepare_flashmessage(get_languageword('You dont have permission to access this page'), 1);
			redirect('auth/login', 'refresh');
		}

		
		$user_id   = $this->ion_auth->get_user_id(); 
		$course_id = ($this->input->post('course_id')) ? $this->input->post('course_id') : $course_id;
		$batch_id  = ($this->input->post('batch_id')) ? $this->input->post('batch_id') : $batch_id;

		if(empty($course_id) || empty($batch_id)) {

			$this->prepare_flashmessage(get_languageword('No Details Found'), 1);
			redirect(URL_INSTITUTE_ENROLLED_STUDENTS);
		}

		$batch_det = $this->base_model->fetch_records_from('inst_enrolled_students', array('batch_id' => $batch_id, 'status =' => 'pending'));

		if(empty($batch_det)) {

			$this->prepare_flashmessage(get_languageword('No Student enrolled in this batch'), 2);
			redirect(URL_INSTITUTE_ENROLLED_STUDENTS.'/'.$course_id);
		}


		$batch_det = $batch_det[0];

		//Check Whether Inst updating their record only
		if($user_id != $batch_det->inst_id) {

			$this->prepare_flashmessage(get_languageword('You dont have permission to perform this action'), 1);
			redirect(URL_INSTITUTE_ENROLLED_STUDENTS.'/'.$course_id);
		}


		if($this->input->post()) {

			$this->load->model('institute_model');
			$batch_status = $this->institute_model->get_batch_status($batch_id);

			//If batch not already approved, status as approve and status_desc, 
			//else update status desc only.
			if($batch_status == "pending") {

				$up_data['prev_status'] = 'pending';
				$up_data['status'] 		= 'approved';
			}

			$up_data['updated_at'] 		= date('Y-m-d H:i:s');
			$up_data['updated_by'] 		= $user_id;
			$up_data['status_desc'] = $this->input->post('status_desc');

			if($batch_status == "pending" || ($batch_status != "pending" && ($up_data['status_desc'] != $batch_det->status_desc))) {

				if($this->base_model->update_operation($up_data, 'inst_enrolled_students', array('batch_id' => $batch_id, 'status =' => 'pending'))) {

					//Email Alert to Batch Tutor - Start

					//Get Batch Approved Alert To Tutor Email Template
					$email_tpl = $this->base_model->fetch_records_from('email_templates', array('template_status' => 'Active', 'email_template_id' => '13'));

					if(!empty($email_tpl)) {

						$email_tpl = $email_tpl[0];

						$inst_rec 	 = getUserRec($batch_det->inst_id);
						$tutor_rec 	 = getUserRec($batch_det->tutor_id);


						if(!empty($email_tpl->from_email)) {

							$from = $email_tpl->from_email;

						} else {

							$from 	= $inst_rec->email;
						}

						$to 	= $tutor_rec->email;

						if(!empty($email_tpl->template_subject)) {

							$sub = $email_tpl->template_subject;

						} else {

							$sub = get_languageword("Batch Approved");
						}

						if(!empty($email_tpl->template_content)) {

							$original_vars  = array($tutor_rec->username, $inst_rec->username, $batch_det->batch_name." - ".$batch_det->batch_code, $this->config->item('site_settings')->enable_initiate_session_option_before_mins.' '.get_languageword('minutes'), '<a href="'.URL_AUTH_LOGIN.'">'.get_languageword('Login Here').'</a>');
							$temp_vars		= array('___TUTOR_NAME___', '___INSTITUTE_NAME___', '___BATCH_NAME___', '___MINS___', '___LOGINLINK___');
							$msg = str_replace($temp_vars, $original_vars, $email_tpl->template_content);

						} else {

							$msg = get_languageword('please')." <a href='".URL_AUTH_LOGIN."'>".get_languageword('Login Here')."</a> ".get_languageword('to view the details');
							$msg .= "<p>".get_languageword('Thank you')."</p>";
						}

						sendEmail($from, $to, $sub, $msg);
					}
					//Email Alert to Batch Tutor - End

					if($batch_status == "pending")
						$this->prepare_flashmessage(get_languageword('Batch approved successfully'), 0);
					else
						$this->prepare_flashmessage(get_languageword('Information updated successfully'), 0);

					redirect(URL_INSTITUTE_ENROLLED_STUDENTS.'/'.$course_id);

				} else {

					$this->prepare_flashmessage(get_languageword('Batch not approved due to some technical issue'), 2);
					redirect(URL_INSTITUTE_ENROLLED_STUDENTS.'/'.$course_id);
				}

			} else {

				$this->prepare_flashmessage(get_languageword('Batch already approved'), 2);
				redirect(URL_INSTITUTE_ENROLLED_STUDENTS.'/'.$course_id);
			}

		}

		$this->data['course_id'] 	= $course_id;
		$this->data['status_desc'] 	= $batch_det->status_desc;
		$this->data['batch_id'] 	= $batch_id;
		$this->data['message'] 		= $this->session->flashdata('message');
		$this->data['pagetitle'] 	= get_languageword('Approve Batch')." (".$batch_det->batch_name." - ".$batch_det->batch_code.") ".get_languageword('Students');
		$this->data['content']	 	= 'approve_batch_students';
		$this->data['activemenu']	= "enrolled_students";
		$this->_render_page('template/site/institute-template', $this->data);

	}



	function get_batches()
	{
		$course_id = $this->input->post('course_id');
		$this->load->model('institute_model');
		$batches = $this->institute_model->get_batches_by_course($course_id);

		$batch_opts = '';

		if(!empty($batches)) {

			$batch_opts .= '<option value="">'.get_languageword('select_batch').'</option>';

			foreach ($batches as $key => $value) {
				$batch_opts .= '<option value="'.$value->batch_id.'">'.$value->batch_name.'</option>';
			}

		} else {

			$batch_opts = '<option value="">'.get_languageword('no_batches_available').'</option>';
		}

		echo $batch_opts;
	}


	//Send Credits Conversion Requests
	function send_credits_conversion_request($course_id = "", $batch_id = "")
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_institute()) {
			$this->prepare_flashmessage(get_languageword('You dont have permission to access this page'), 1);
			redirect('auth/login', 'refresh');
		}

		$course_id = ($this->input->post('course_id')) ? $this->input->post('course_id') : $course_id;
		$batch_id  = ($this->input->post('batch_id')) ? $this->input->post('batch_id') : $batch_id;

		if(empty($course_id) || empty($batch_id)) {

			$this->prepare_flashmessage(get_languageword('Please complete the course for the batch to send credit conversion request'), 2);
			redirect(URL_INSTITUTE_ENROLLED_STUDENTS);
		}

		$user_id = $this->ion_auth->get_user_id();

		//Check whether batch exists
		$batch_det = $this->base_model->fetch_records_from('inst_enrolled_students', array('batch_id' => $batch_id, 'inst_id' => $user_id, 'status' => 'closed'), '', 'updated_at ASC');

		if(empty($batch_det)) {

			$this->prepare_flashmessage(get_languageword('Invalid request Or Batch not yet completed'), 1);
			redirect(URL_INSTITUTE_ENROLLED_STUDENTS.'/'.$course_id);
		}

		$batch_det = $batch_det[0];

		//Check If requesting after 24 hours from the session closed time
		$batch_closed_time_after_24_hrs  = date("Y-m-d H:i:s", strtotime($batch_det->updated_at.'+24 hours'));
		$cur_time						 = date('Y-m-d H:i:s');

		if(!(strtotime($cur_time) >= strtotime($batch_closed_time_after_24_hrs))) {

			$this->prepare_flashmessage(get_languageword('Please send your request after twenty four hours of the Batch closed time Thank you'), 2);
			redirect(URL_INSTITUTE_ENROLLED_STUDENTS.'/'.$course_id);
		}

		//Check whether already sent request
		$payment_status = $this->base_model->fetch_records_from('admin_money_transactions', array('booking_id' => $batch_id, 'user_id' => $user_id, 'user_type' => 'institute'));

		if(!empty($payment_status)) {

			$this->prepare_flashmessage(get_languageword('Already sent the request And status of the payment is ').$payment_status[0]->status_of_payment, 1);
			redirect(URL_INSTITUTE_ENROLLED_STUDENTS.'/'.$course_id);
		}


		
		$user_rec  = getUserRec($user_id);

		$this->load->model('institute_model');
		$credits_of_batch_closed = $this->institute_model->get_credits_of_batch_closed($batch_id);
		$admin_commission_credits_of_batch_closed = $this->institute_model->get_admin_commission_credits_of_batch_closed($batch_id);

		$inputdata['user_id'] 						= $user_id;
		$inputdata['booking_id'] 					= $batch_id;
		$inputdata['user_type'] 					= 'institute';
		$inputdata['user_name'] 					= $user_rec->username;
		$inputdata['user_paypal_email'] 			= $user_rec->paypal_email;
		$inputdata['user_bank_ac_details'] 			= $user_rec->bank_ac_details;
		$inputdata['no_of_credits_to_be_converted'] = $credits_of_batch_closed;
		$inputdata['admin_commission_val'] 			= $admin_commission_credits_of_batch_closed;
		$inputdata['per_credit_cost'] 				= $batch_det->per_credit_value;
		$inputdata['total_amount'] 					= $inputdata['no_of_credits_to_be_converted'] * $inputdata['per_credit_cost'];
		$inputdata['created_at'] 					= date('Y-m-d H:i:s');
		$inputdata['updated_at'] 					= $inputdata['created_at'];
		$inputdata['updated_by'] 					= $user_id;


		if($this->base_model->insert_operation($inputdata, 'admin_money_transactions')) {

			$this->prepare_flashmessage(get_languageword('Credits to Money conversion request sent successfully'), 0);
			redirect(URL_INSTITUTE_MONEY_CONVERSION_REQUEST);

		} else {

			$this->prepare_flashmessage(get_languageword('Somthing went wrong Your request not sent'), 2);
			redirect(URL_INSTITUTE_ENROLLED_STUDENTS.'/'.$course_id);
		}

	}


	//Credists conversion requests List
	function money_conversion_request($param ='Pending')
	{

		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_institute()) {
			$this->prepare_flashmessage(get_languageword('You dont have permission to access this page'), 1);
			redirect('auth/login', 'refresh');
		}

		$user_id= $this->ion_auth->get_user_id();

		$this->load->library(array('grocery_CRUD_extended'));
		$crud = new grocery_CRUD_extended();
		$crud_state = $crud->getState();

		$crud->set_table($this->db->dbprefix('admin_money_transactions'));
		$crud->where('user_id',$user_id);
		$crud->where('status_of_payment',$param);

		//unset actions			
		$crud->unset_add();
		$crud->unset_edit();
		$crud->unset_delete();
		
		
		$crud->columns('booking_id', 'user_paypal_email', 'user_bank_ac_details', 'no_of_credits_to_be_converted', 'per_credit_cost', 'total_amount', 'status_of_payment', 'updated_at');

		$crud->set_read_fields('booking_id', 'user_paypal_email', 'user_bank_ac_details', 'total_amount', 'status_of_payment', 'updated_at');

		$currency_symbol = $this->config->item('site_settings')->currency_symbol;
		$crud->display_as('no_of_credits_to_be_converted', get_languageword('credits_acquired'));
		$crud->display_as('per_credit_cost', get_languageword('per_credit_cost')." (".$currency_symbol.")");
		$crud->display_as('total_amount', get_languageword('total_amount')." (".$currency_symbol.")");


		$crud->callback_column('booking_id',array($this,'callback_batch_id'));
		$crud->display_as('booking_id', get_languageword('Batch Id'));

		$output = $crud->render();

		$this->data['activemenu'] 	= "moneyconversion-institute";
		$this->data['activesubmenu'] 	= $param;
		$this->data['pagetitle'] = get_languageword('money_conversion_requests');
		$this->data['grocery_output'] = $output;
		$this->data['grocery'] = TRUE;
		$this->grocery_output($this->data);	

	}


	function callback_batch_id($value, $row)
	{
		return '<a href="'.URL_INSTITUTE_GET_BATCH_LIST.'/'.$row->booking_id.'">'.$row->booking_id.'</a>';
	}


	function credits_transactions_history()
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_institute()) {
			$this->prepare_flashmessage(get_languageword('You dont have permission to access this page'), 1);
			redirect('auth/login', 'refresh');
		}
		
		$user_id = $this->ion_auth->get_user_id();
		$this->load->library(array('grocery_CRUD'));
		$crud = new grocery_CRUD();
		$crud_state = $crud->getState();
		$crud->set_table($this->db->dbprefix('user_credit_transactions'));
		$crud->where('user_id', $user_id);
		
		$crud->set_subject( get_languageword('user_credit_transactions') );

		$crud->unset_add();
		$crud->unset_delete();
		$crud->unset_edit();
		

		$crud->columns('credits','action','purpose','date_of_action');

		$crud->unset_read_fields('user_id', 'reference_table', 'reference_id', 'per_credit_value');
		
		$output = $crud->render();

		$this->data['activemenu'] 	= "user_credit_transactions";
		$this->data['pagetitle'] = get_languageword('credits_transactions_history');
		$this->data['grocery_output'] = $output;
		$this->data['grocery'] = TRUE;
		$this->grocery_output($this->data);

	}

	

	function get_batch_list($batch_id = "")
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_institute()) {
			$this->prepare_flashmessage(get_languageword('You dont have permission to access this page'), 1);
			redirect('auth/login', 'refresh');
		}


		$user_id = $this->ion_auth->get_user_id();
		$this->load->library(array('grocery_CRUD'));
		$crud = new grocery_CRUD();
		$crud_state = $crud->getState();
		$crud->set_table($this->db->dbprefix('inst_enrolled_students'));
		$crud->where('batch_id', $batch_id);
		$crud->set_relation('student_id','users','username');
		$crud->set_relation('tutor_id','users','username');
		$crud->set_relation('course_id','categories','name');
		$crud->set_subject( get_languageword('student_list') );

		$crud->unset_add();
		$crud->unset_delete();

		$crud->columns('course_id','batch_name','batch_code','student_id','tutor_id', 'time_slot','batch_start_date', 'batch_end_date','course_duration','fee','status');

		$crud->callback_column('course_duration',array($this,'_callback_course_duration'));

		//########Edit fields only#######
		$crud->edit_fields('status', 'status_desc', 'updated_at', 'prev_status', 'updated_by');

		//Hidden Fields
		$crud->field_type('updated_at', 'hidden', date('Y-m-d H:i:s'));
		$crud->field_type('updated_by', 'hidden', $user_id);

		//####### Changing column names #######
		$crud->display_as('course_id',get_languageword('Course Name'));
		$crud->display_as('student_id',get_languageword('Student Name'));
		$crud->display_as('tutor_id',get_languageword('Tutor Name'));


		if($crud_state == "edit") {

			$p_key = $this->uri->segment(5);

			$enroll_det = $this->base_model->fetch_records_from('inst_enrolled_students', array('enroll_id' => $p_key));

			if(!empty($enroll_det)) {

				$enroll_det = $enroll_det[0];

				if($enroll_det->inst_id != $user_id) {

					$this->prepare_flashmessage(get_languageword('not_authorized'), 1);
	    			redirect(URL_INSTITUTE_GET_BATCH_LIST.'/'.$batch_id);
				}

				$booking_status = $enroll_det->status;

				$crud->field_type('prev_status', 'hidden', $booking_status);

				$crud->display_as('status', get_languageword('change_status'));

				if($booking_status == "pending")
					$crud->field_type('status', 'dropdown', array('approved' => get_languageword('approve')));
				else {
					$crud->field_type('prev_status', 'hidden', $enroll_det->prev_status);
					$crud->field_type('status', 'hidden', $booking_status);
				}

			} else {

				$this->prepare_flashmessage(get_languageword('invalid_request'), 1);
	    		redirect(URL_INSTITUTE_GET_BATCH_LIST.'/'.$batch_id);
			}
		}

		if($crud_state == "read") {
			$crud->field_type('updated_at', 'visible');
		}

		$output = $crud->render();

		$this->data['activemenu'] 	= "enrolled_students";
		$this->data['pagetitle'] = get_languageword('enrolled_students_list');
		$this->data['grocery_output'] = $output;
		$this->data['grocery'] = TRUE;
		$this->grocery_output($this->data);
	}
}
?>