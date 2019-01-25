<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Admin extends MY_Controller 
{
	function __construct()
	{
		parent::__construct();
		$this->load->library(array('session'));
		$this->load->library(array('ion_auth','form_validation'));
	}
	
	/*** Displays the Index Page ***/
	function index()
	{	
		if(!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()){
			$this->prepare_flashmessage(get_languageword('You do not have permission to access this page'),1);
			redirect('auth/login','refresh');
		}

		$this->data['activemenu'] 	= "dashboard";
		$this->data['message'] 		= $this->session->flashdata('message');
		$usersCount = $this->base_model->get_usersCount();
		$this->data['usersCount']   = $usersCount; 

		$packageNames = array();
		$packageSubscriptions = array();
		$packagePayments = array();
		$Students = array();
		$Tutors = array();
		$Institutes = array();

		$packages_data = $this->base_model->get_packages_subscriptions();
		$this->data['packages_data'] = $packages_data;
		foreach($packages_data as $row){
			array_push($packageNames, $row->package_name);
			array_push($packageSubscriptions, $row->total_subscriptions);
			array_push($packagePayments, $row->total_payments);
			array_push($Students, $row->Students);
			array_push($Tutors, $row->Tutors);
			array_push($Institutes, $row->Institutes);

		}

		$this->data['packageNames'] = $packageNames;
		$this->data['pagetitle']	= get_languageword('tutor_System');
		$this->data['packageSubscriptions'] = $packageSubscriptions;
		$this->data['packagePayments'] = $packagePayments;
		$this->data['Students'] = $Students;
		$this->data['Tutors'] = $Tutors;
		$this->data['Institutes'] = $Institutes;
		$this->data['content'] 		= 'dashboard';
		$this->_render_page('template/admin/admin-template', $this->data);
	}


	function changepassword()
	{	
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			$this->prepare_flashmessage(get_languageword('You dont have permission to access this page'), 1);
			redirect('auth/login', 'refresh');
		}

		$this->data['message'] 	 = $this->session->flashdata('message');
		if($this->input->post( 'submitbutt' ))
		{
			$this->form_validation->set_rules('current_password',get_languageword('current_password'),'trim|required');
			$this->form_validation->set_rules('new_password',get_languageword('new_password'),'trim|required');
			$this->form_validation->set_rules('retype_password',get_languageword('retype_password'),'trim|required|matches[new_password]');

			$this->form_validation->set_error_delimiters('<div class="error">', '</div>');

			if ($this->form_validation->run() == TRUE)
			{
				$identity = $this->session->userdata('identity');
				$change = $this->ion_auth->change_password($identity, $this->input->post('current_password'), $this->input->post('new_password'));
				if ($change)
				{
					$this->prepare_flashmessage(get_languageword('password_changed_successfully'), 0);
					redirect(URL_ADMIN_CHANGEPASSWORD);
				}
				else
				{
					//$this->data['message'] = prepare_message(validation_errors(),1);
					$this->prepare_flashmessage($this->ion_auth->errors(), 1);
					redirect(URL_ADMIN_CHANGEPASSWORD);
				}
			}

		}	
		$this->data['activemenu']= "dashboard";

		$this->data['pagetitle'] = 'Change Password';
		$this->data['content']   = 'changepassword';
		$this->_render_page('template/admin/admin-template', $this->data);
	}


	function all_leads()
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			$this->prepare_flashmessage(get_languageword('You dont have permission to access this page'), 1);
			redirect('auth/login', 'refresh');
		}
		

		$this->load->library(array('grocery_CRUD'));
		$crud = new grocery_CRUD();
		$crud_state = $crud->getState();
		$crud->unset_jquery();
		$crud->set_table($this->db->dbprefix('student_leads'));
		$crud->set_relation('teaching_type_id','teaching_types','teaching_type');
		$crud->set_relation('location_id','locations','location_name');
		$crud->set_relation('course_id','categories','name');
		$crud->set_relation('user_id','users','username');
		$crud->set_subject( get_languageword('student_leads') );
		
		$crud->unset_add();
		$crud->unset_delete();
		$crud->unset_edit();
		$crud->unset_read();
		 
    			
		$crud->columns('user_id','course_id','teaching_type_id','location_id','title_of_requirement','priority_of_requirement','updated_at','duration_needed', 'no_of_views','status');
		
		//####### Changing column names #######
		$crud->display_as('updated_at',get_languageword('last_updated'));
		$crud->display_as('course_id',get_languageword('course_name'));
		$crud->display_as('teaching_type_id',get_languageword('teaching_type'));
		$crud->display_as('location_id',get_languageword('location_name'));
		$crud->display_as('duration_needed',get_languageword('duration'));
		$crud->display_as('user_id',get_languageword('student_name'));
			

		$crud->callback_column('priority_of_requirement',array($this,'callback_humanize_priority_of_requirement'));

		//#### Invisible fileds in reading ####
		if ($crud->getState() == 'read') {
		    $crud->field_type('user_id', 'invisible');
		    $crud->field_type('priority_of_requirement','invisible');
		}


		$output = $crud->render();
		
		$this->data['activemenu'] 	= "myleads";
		$this->data['activesubmenu'] ="all_Leads";
		$this->data['content'] 		= 'admin_leads';
		$this->data['pagetitle'] = get_languageword('All_Leads');
		$this->data['grocery_output'] = $output;
		$this->data['grocery'] = TRUE;
		$this->grocery_output($this->data);
	}

	function callback_humanize_priority_of_requirement($primarykey, $row)
	{
		return ucfirst(str_replace('_', ' ', $row->priority_of_requirement));
	}

	
	function opened_leads()
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			$this->prepare_flashmessage(get_languageword('You dont have permission to access this page'), 1);
			redirect('auth/login', 'refresh');
		}
		

		$this->load->library(array('grocery_CRUD'));
		$crud = new grocery_CRUD();
		$crud_state = $crud->getState();
		$crud->unset_jquery();
		$crud->set_table($this->db->dbprefix('student_leads'));
		$crud->where('pre_student_leads.status', 'opened');
		$crud->set_relation('teaching_type_id','teaching_types','teaching_type');
		$crud->set_relation('location_id','locations','location_name');
		$crud->set_relation('course_id','categories','name');
		$crud->set_relation('user_id','users','username');
		$crud->set_subject( get_languageword('student_leads') );
	
		// unset actions	
		$crud->unset_add();
		$crud->unset_delete();
		$crud->unset_edit();
		$crud->unset_read();
		 
		 
    	// display columns		
		$crud->columns('course_id','user_id','teaching_type_id','location_id','title_of_requirement','priority_of_requirement','updated_at','duration_needed', 'no_of_views','status');
		
		$crud->callback_column('priority_of_requirement',array($this,'callback_humanize_priority_of_requirement'));
		// Changing column names 
		$crud->display_as('updated_at',get_languageword('last_updated'));
		$crud->display_as('course_id',get_languageword('course_name'));
		$crud->display_as('teaching_type_id',get_languageword('teaching_type'));
		$crud->display_as('location_id',get_languageword('location_name'));
		$crud->display_as('duration_needed',get_languageword('duration'));
		$crud->display_as('user_id',get_languageword('student_name'));
		
		
		// Invisible fileds in reading 
		if ($crud->getState() == 'read') {
		    $crud->field_type('user_id', 'invisible');
		}


		$output = $crud->render();
		
		$this->data['activemenu'] 	= "myleads";
		$this->data['activesubmenu'] = "opened_Leads";
		$this->data['content'] 		= 'admin_leads';
		$this->data['pagetitle'] = get_languageword('Opened Leads');
		$this->data['grocery_output'] = $output;
		$this->data['grocery'] = TRUE;
		// $this->_render_page('template/admin/admin-template-grocery', $this->data);
		$this->grocery_output($this->data);
	}

	function closed_leads()
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			$this->prepare_flashmessage(get_languageword('You dont have permission to access this page'), 1);
			redirect('auth/login', 'refresh');
		}


		$this->load->library(array('grocery_CRUD'));
		$crud = new grocery_CRUD();
		$crud_state = $crud->getState();
		$crud->unset_jquery();
		$crud->set_table($this->db->dbprefix('student_leads'));
		$crud->where('pre_student_leads.status', 'closed');
		$crud->set_relation('teaching_type_id','teaching_types','teaching_type');
		$crud->set_relation('location_id','locations','location_name');
		$crud->set_relation('course_id','categories','name');
		$crud->set_relation('user_id','users','username');
		$crud->set_subject( get_languageword('student_leads') );
		
		$crud->unset_add();
		$crud->unset_delete();
		$crud->unset_edit();
		$crud->unset_read();
		 
    			
		$crud->columns('course_id','user_id','teaching_type_id','location_id','title_of_requirement','priority_of_requirement','updated_at','duration_needed', 'no_of_views','status');
				

		$crud->callback_column('priority_of_requirement',array($this,'callback_humanize_priority_of_requirement'));
		//####### Changing column names #######
		$crud->display_as('updated_at',get_languageword('last_updated'));
		$crud->display_as('course_id',get_languageword('course_name'));
		$crud->display_as('teaching_type_id',get_languageword('teaching_type'));
		$crud->display_as('location_id',get_languageword('location_name'));
		$crud->display_as('duration_needed',get_languageword('duration'));
		$crud->display_as('user_id',get_languageword('student_name'));

		//#### Invisible fileds in reading ####
		if ($crud->getState() == 'read') {
		    $crud->field_type('user_id', 'invisible');
		}


		$output = $crud->render();
		
		$this->data['activemenu'] 	= "myleads";
		$this->data['activesubmenu'] = "closed_Leads";
		$this->data['content'] 		= 'admin_leads';
		$this->data['pagetitle'] = get_languageword('closed Leads');
		$this->data['grocery_output'] = $output;
		$this->data['grocery'] = TRUE;
		$this->grocery_output($this->data);	
	}

	function faqs()
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			$this->prepare_flashmessage(get_languageword('You dont have permission to access this page'), 1);
			redirect('auth/login', 'refresh');
		}
		

		$this->load->library(array('grocery_CRUD'));
		$crud = new grocery_CRUD();
		$crud_state = $crud->getState();
		$crud->unset_jquery();
		$crud->set_table($this->db->dbprefix('faqs'));
		$crud->set_subject( get_languageword('FAQs') );
		
		$crud->required_fields('question','answer');
		
		//display columns    			
		$crud->columns('question','answer','status');
		
		// Changing column names 
		$crud->display_as('updated_at',get_languageword('last_updated'));
		
		$output = $crud->render();
		
		if($crud_state == 'read')
			$crud_state ='View';

		$this->data['activemenu'] 	= "pages";
		$this->data['activesubmenu'] 	= "faqs";
		$this->data['pagetitle'] = get_languageword($crud_state) .' '. get_languageword('faqs');
		$this->data['grocery_output'] = $output;
		$this->data['grocery'] = TRUE;
		$this->grocery_output($this->data);	
	}


	function dynamic_pages()
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			$this->prepare_flashmessage(get_languageword('You dont have permission to access this page'), 1);
			redirect('auth/login', 'refresh');
		}
		

		$this->load->library(array('grocery_CRUD_extended'));
		$crud = new grocery_CRUD_extended();
		$crud_state = $crud->getState();
		$crud->unset_jquery();
		$crud->set_table($this->db->dbprefix('pages'));
		$crud->set_subject( get_languageword('pages') );
		
		$crud->required_fields('name','description','slug');

		$crud->unique_fields('name');

		$crud->unset_delete();

		//display columns    			
		$crud->columns('name','description','slug','meta_tag','meta_description','seo_keywords','status');
		
		$crud->display_as('name', get_languageword('page_title'));

		if($crud_state == 'read')
			$crud_state ='View';


		$crud->callback_before_insert(array($this,'callback_dynapage_before_insert'));
		$crud->callback_before_update(array($this,'callback_dynapage_before_update'));

		$output = $crud->render();
		
		$this->data['activemenu'] 	= "pages";
		$this->data['activesubmenu'] 	= "dynamic_pages";
		$this->data['pagetitle'] = get_languageword($crud_state).' '. get_languageword('dynamic_pages');
		if($crud_state == "list")
			$this->data['pagetitle'] = get_languageword($crud_state).' '. get_languageword('dynamic_pages').' (<small><code>*'.get_languageword('Please_do_not_delete_first_4_rows_as_they_are_deafult_pages_in_the_system').'</code></small>)';
		$this->data['grocery_output'] = $output;
		$this->data['grocery'] = TRUE;
		$this->grocery_output($this->data);	
	}


	function callback_dynapage_before_insert($post_array) {

		$post_array['slug'] = prepare_slug($post_array['slug'], 'slug', 'pages');

		return $post_array;
	}

	function callback_dynapage_before_update($post_array, $primary_key) {

		$prev_name = $this->base_model->fetch_value('pages', 'slug', array('id' => $primary_key));

		//If updates the name
		if($prev_name != $post_array['slug']) {
			$post_array['slug'] = prepare_slug($post_array['slug'], 'slug', 'pages');
		}
		return $post_array;
	}


	function student_bookings()
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			$this->prepare_flashmessage(get_languageword('You dont have permission to access this page'), 1);
			redirect('auth/login', 'refresh');
		}


		$this->load->library(array('grocery_CRUD_extended'));
		$crud = new grocery_CRUD_extended();
		$crud_state = $crud->getState();
		$crud->unset_jquery();
		$crud->set_table($this->db->dbprefix('bookings'));
		$crud->set_relation('student_id','users','{username} - (Ph: +{phone_code} {phone})');
		$crud->set_relation('tutor_id','users','{username} - (Ph: +{phone_code} {phone})');
		$crud->set_relation('course_id','categories','name');
		$crud->set_relation('updated_by',TBL_USERS, 'username');
		$crud->set_subject( get_languageword('student_Bookings') );

		$crud->unset_add();
		$crud->unset_delete();


		//display columns
		$crud->columns('booking_id','student_id','tutor_id','course_id','content','fee','course_duration','start_date','end_date','days_off','preferred_location','admin_commission','admin_commission_val','status');


		$status = array('pending' => get_languageword('pending'), 'approved' => get_languageword('approved'), 'cancelled_before_course_started' => get_languageword('cancelled_before_course_started'), 'cancelled_when_course_running' => get_languageword('cancelled_when_course_running'), 'cancelled_after_course_completed' => get_languageword('cancelled_after_course_completed'), 'session_initiated' => get_languageword('session_initiated'), 'running' => get_languageword('running'), 'completed' => get_languageword('completed'), 'called_for_admin_intervention' => get_languageword('claim_for_admin_intervention'), 'closed' => get_languageword('closed'));

		$crud->field_type('status', 'dropdown', $status);


		$crud->callback_column('course_duration',array($this,'call_back_course_duration'));
		$crud->callback_column('status',array($this,'call_back_status'));

		//Form fields for Edit Record
		$crud->edit_fields('status', 'status_desc', 'updated_at', 'prev_status');

		//Hidden Fields
		$crud->field_type('updated_at', 'hidden', date('Y-m-d H:i:s'));

		$crud->display_as('student_id', get_languageword('student_name').' - '.get_languageword('Phone_Num'));
		$crud->display_as('tutor_id', get_languageword('tutor_name').' - '.get_languageword('Phone_Num'));
		$crud->display_as('course_id', get_languageword('course_Booked'));
		$crud->display_as('start_date', get_languageword('batch_start_date'));
		$crud->display_as('end_date', get_languageword('batch_end_date'));
		$crud->display_as('content', get_languageword('course_content'));
		$crud->display_as('fee',get_languageword('fee').' ('.get_languageword('in_credits').')');
		$crud->display_as('admin_commission',get_languageword('admin_commission_percentage'));


		if($crud_state == "edit") {

			$p_key = $this->uri->segment(4);

			$booking_det = $this->base_model->fetch_records_from('bookings', array('booking_id' => $p_key));

			if(!empty($booking_det)) {

				$booking_det = $booking_det[0];

				$booking_status = $booking_det->status;

				$crud->field_type('prev_status', 'hidden', $booking_status);

				if($booking_status == "called_for_admin_intervention") {

					$crud->edit_fields('status', 'status_desc', 'refund_credits_to_student', 'tranfer_credits_to_tutor', 'updated_at', 'prev_status');

				} 

			} else {

				$this->prepare_flashmessage(get_languageword('invalid_request'), 1);
	    		redirect(URL_ADMIN_STUDENT_BOOKINGS);
			}

		}


		if($crud_state == "read") {

			$crud->field_type('updated_at', 'visibile');
		}

		$crud->callback_update(array($this,'callback_student_bookings_update'));

		$output = $crud->render();

		$this->data['activemenu'] 	= "bookings";
		$this->data['activesubmenu'] 	= "student_bookings";
		$this->data['pagetitle'] = get_languageword('student Bookings');
		$this->data['grocery_output'] = $output;
		$this->data['grocery'] = TRUE;
		$this->grocery_output($this->data);	
	}


	function callback_student_bookings_update($post_array, $primary_key)
	{
		$post_array['updated_by'] = $this->ion_auth->get_user_id();

		if(!empty($post_array['refund_credits_to_student']))
			$no_of_credits_for_student = $post_array['refund_credits_to_student'];
		if(!empty($post_array['tranfer_credits_to_tutor']))
			$no_of_credits_for_tutor   = $post_array['tranfer_credits_to_tutor'];

		$booking_det = $this->base_model->fetch_records_from('bookings', array('booking_id' => $primary_key));

		if(empty($booking_det))
			return FALSE;

		$booking_det = $booking_det[0];

		if($post_array['prev_status'] == "called_for_admin_intervention" && !empty($no_of_credits_for_tutor)) {
			$post_array['fee'] 					= $no_of_credits_for_tutor;
			$post_array['admin_commission']		= $booking_det->admin_commission;
			$post_array['admin_commission_val'] = round($no_of_credits_for_tutor * ($post_array['admin_commission'] / 100));
		}


		unset($post_array['refund_credits_to_student']);
		unset($post_array['tranfer_credits_to_tutor']);

		if($this->base_model->update_operation($post_array, 'bookings', array('booking_id' => $primary_key))) {

			if($post_array['prev_status'] == "called_for_admin_intervention") {

				$student_rec = getUserRec($booking_det->student_id);
				$tutor_rec 	 = getUserRec($booking_det->tutor_id);

				if(!empty($no_of_credits_for_student)) {

					//Log Credits transaction data & update Student net credits - Start
					$log_data = array(
									'user_id' => $booking_det->student_id,
									'credits' => $no_of_credits_for_student,
									'per_credit_value' => $booking_det->per_credit_value,
									'action'  => 'credited',
									'purpose' => 'Credits refunded by Admin for the booking id "'.$primary_key.'" ',
									'date_of_action	' => date('Y-m-d H:i:s'),
									'reference_table' => 'bookings',
									'reference_id' => $primary_key,
								);

					log_user_credits_transaction($log_data);

					update_user_credits($booking_det->student_id, $no_of_credits_for_student, 'credit');
					//Log Credits transaction data & update Student net credits - End
				}

				if(!empty($no_of_credits_for_tutor)) {

					//Log Credits transaction data & update Tutor net credits - Start
					$log_data = array(
									'user_id' => $booking_det->tutor_id,
									'credits' => $no_of_credits_for_tutor,
									'per_credit_value' => $booking_det->per_credit_value,
									'action'  => 'credited',
									'purpose' => 'Credits refunded by Admin for the booking id "'.$primary_key.'" ',
									'date_of_action	' => date('Y-m-d H:i:s'),
									'reference_table' => 'bookings',
									'reference_id' => $primary_key,
								);

					log_user_credits_transaction($log_data);

					update_user_credits($booking_det->tutor_id, $no_of_credits_for_tutor, 'credit');
					//Log Credits transaction data & update Tutor net credits - End
				}

			}

			return TRUE;

		} else return FALSE;
	}


	function call_back_course_duration($primarykey, $row)
	{
		return $row->duration_value .' '. $row->duration_type;
	}

	function call_back_status($primarykey, $row)
	{
		if($row->status == "called_for_admin_intervention")
			return '<font color="red">'.humanize($row->status).'</font>';
		else
			return humanize($row->status);
	}

	function inst_batches()
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			$this->prepare_flashmessage(get_languageword('You dont have permission to access this page'), 1);
			redirect('auth/login', 'refresh');
		}


		$this->load->library(array('grocery_CRUD_extended'));
		$crud = new grocery_CRUD_extended();
		$crud_state = $crud->getState();
		$crud->unset_jquery();
		$crud->set_table($this->db->dbprefix('inst_batches'));
		$crud->set_relation('inst_id','users','username');
		$crud->set_relation('tutor_id','users','username');
		$crud->set_relation('course_id','categories','name');

		$crud->set_subject( get_languageword('inst_batches') );

		$crud->unset_add();
		$crud->unset_delete();
		$crud->unset_edit();
		$crud->unset_read();


		//display columns
		$crud->columns('batch_id','batch_code','batch_name','inst_id','course_id','tutor_id','total_enrolled_students','batch_max_strength','course_content','fee','course_duration','batch_start_date','batch_end_date','time_slot','days_off','course_offering_location');

		$crud->display_as('inst_id', get_languageword('institute_Name'));
		$crud->display_as('tutor_id', get_languageword('assigned_Tutor'));
		$crud->display_as('course_id', get_languageword('course_Name'));

		$crud->callback_column('total_enrolled_students',array($this,'callback_batch_enrolled_students_cnt'));
		$crud->callback_column('course_duration',array($this,'callback_column_course_duration'));
		$crud->callback_column('batch_max_strength',array($this,'call_back_batch_strength_color'));

		$crud->add_action(get_languageword('view_Enrolled_Students'), URL_FRONT_IMAGES.'magnifier.png', URL_ADMIN_INST_BATCH_ENROLLED_STUDENTS.'/');

		$output = $crud->render();

		$this->data['activemenu'] 	= "bookings";
		$this->data['activesubmenu'] 	= "inst_batches";
		$this->data['pagetitle'] = get_languageword('Institute Batches');
		$this->data['grocery_output'] = $output;
		$this->data['grocery'] = TRUE;
		$this->grocery_output($this->data);	
	}


	function callback_batch_enrolled_students_cnt($primary_key, $row)
	{
		$batch_id = $row->batch_id;
		$this->load->model('institute/institute_model');
		$total_enrolled_students = $this->institute_model->get_batch_enrolled_students_cnt($batch_id);
		return '<font color="red">'.$total_enrolled_students.'</font>';
	}

	function callback_column_course_duration($prinmarykey, $row)
	{
		return $row->duration_value .' '. $row->duration_type;
	}

	function call_back_batch_strength_color($primarykey, $row)
	{
		return '<font color="green">'.$row->batch_max_strength.'</font>';
	}


	function inst_batche_enrolled_students($batch_id = "")
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			$this->prepare_flashmessage(get_languageword('You dont have permission to access this page'), 1);
			redirect('auth/login', 'refresh');
		}
		

		$this->load->library(array('grocery_CRUD_extended'));
		$crud = new grocery_CRUD_extended();
		$crud_state = $crud->getState();
		$crud->unset_jquery();
		$crud->set_table($this->db->dbprefix('inst_enrolled_students'));
		$crud->where('batch_id',$batch_id);
		$crud->set_relation('inst_id','users','{username} - (Ph: +{phone_code} {phone})');
		$crud->set_relation('student_id','users','{username} - (Ph: +{phone_code} {phone})');
		$crud->set_relation('tutor_id','users','{username} - (Ph: +{phone_code} {phone})');
		$crud->set_relation('course_id','categories','name');
		
		$crud->set_subject( get_languageword('student_enrollment_details') );
		
		$crud->unset_add();
		$crud->unset_delete();

		//display columns
		$crud->columns('batch_id','course_id','batch_name','batch_code','student_id','tutor_id','inst_id','time_slot','batch_start_date','batch_end_date','course_duration','fee','admin_commission','admin_commission_val','status');

		$crud->display_as('inst_id', get_languageword('institute_name'));
		$crud->display_as('student_id', get_languageword('student_name'));
		$crud->display_as('tutor_id', get_languageword('tutor_name'));
		$crud->display_as('course_id', get_languageword('course_name'));
		$crud->display_as('fee',get_languageword('fee').' ('.get_languageword('in_credits').')');
		$crud->display_as('admin_commission',get_languageword('admin_commission_percentage').' ('.get_languageword('in_credits').')');

		$crud->callback_column('course_duration',array($this,'call_back_course_duration'));
		$crud->callback_column('status',array($this,'call_back_status'));

		//Form fields for Edit Record
		$crud->edit_fields('status', 'status_desc', 'updated_at', 'prev_status');

		$status = array('pending' => get_languageword('pending'), 'approved' => get_languageword('approved'), 'cancelled_before_course_started' => get_languageword('cancelled_before_course_started'), 'session_initiated' => get_languageword('session_initiated'), 'running' => get_languageword('running'), 'completed' => get_languageword('completed'), 'called_for_admin_intervention' => get_languageword('claim_for_admin_intervention'), 'closed' => get_languageword('closed'));

		$crud->field_type('status', 'dropdown', $status);

		//Hidden Fields
		$crud->field_type('updated_at', 'hidden', date('Y-m-d H:i:s'));

		if($crud_state == "edit") {

			$p_key = $this->uri->segment(5);

			$enroll_det = $this->base_model->fetch_records_from('inst_enrolled_students', array('enroll_id' => $p_key));

			if(!empty($enroll_det)) {

				$enroll_det = $enroll_det[0];

				$enroll_status = $enroll_det->status;

				$crud->field_type('prev_status', 'hidden', $enroll_status);

				if($enroll_status == "called_for_admin_intervention") {

					$crud->edit_fields('status', 'status_desc', 'refund_credits_to_student', 'tranfer_credits_to_institute', 'updated_at', 'prev_status');

				} 

			} else {

				$this->prepare_flashmessage(get_languageword('invalid_request'), 1);
	    		redirect(URL_ADMIN_INST_BATCH_ENROLLED_STUDENTS.'/'.$batch_id);
			}

		}


		if($crud_state == "read") {

			$crud->field_type('updated_at', 'visibile');
			$crud->set_relation('updated_by','users', 'username');
		}

		$crud->callback_update(array($this,'callback_student_enrollment_update'));


		$output = $crud->render();
		
		$this->data['activemenu'] 	= "bookings";
		$this->data['activesubmenu'] 	= "inst_enrolled_students";
		$this->data['maintitle_link'] = base_url().'admin/inst-batches/2';
		$this->data['pagetitle'] = get_languageword('enrolled_students');
		$this->data['grocery_output'] = $output;
		$this->data['grocery'] = TRUE;
		$this->grocery_output($this->data);	
	}


	function callback_student_enrollment_update($post_array, $primary_key)
	{
		$post_array['updated_by'] = $this->ion_auth->get_user_id();

		if(!empty($post_array['refund_credits_to_student']))
			$no_of_credits_for_student = $post_array['refund_credits_to_student'];
		if(!empty($post_array['tranfer_credits_to_institute']))
			$no_of_credits_for_inst   = $post_array['tranfer_credits_to_institute'];

		$enroll_det = $this->base_model->fetch_records_from('inst_enrolled_students', array('enroll_id' => $primary_key));

		if(empty($enroll_det))
			return FALSE;

		$enroll_det = $enroll_det[0];

		if($post_array['prev_status'] == "called_for_admin_intervention" && !empty($no_of_credits_for_inst)) {
			$post_array['fee'] 	= ($enroll_det->admin_commission_val)+($no_of_credits_for_inst);
		}


		unset($post_array['refund_credits_to_student']);
		unset($post_array['tranfer_credits_to_institute']);

		if($this->base_model->update_operation($post_array, 'inst_enrolled_students', array('enroll_id' => $primary_key))) {

			if($post_array['prev_status'] == "called_for_admin_intervention") {

				$student_rec = getUserRec($enroll_det->student_id);
				$inst_rec 	 = getUserRec($enroll_det->inst_id);

				if(!empty($no_of_credits_for_student)) {

					//Log Credits transaction data & update Student net credits - Start
					$log_data = array(
									'user_id' => $enroll_det->student_id,
									'credits' => $no_of_credits_for_student,
									'per_credit_value' => $enroll_det->per_credit_value,
									'action'  => 'credited',
									'purpose' => 'Credits refunded by Admin for the enroll id "'.$primary_key.'" ',
									'date_of_action	' => date('Y-m-d H:i:s'),
									'reference_table' => 'inst_enrolled_students',
									'reference_id' => $primary_key,
								);

					log_user_credits_transaction($log_data);

					update_user_credits($enroll_det->student_id, $no_of_credits_for_student, 'credit');
					//Log Credits transaction data & update Student net credits - End
				}

				if(!empty($no_of_credits_for_inst)) {

					//Log Credits transaction data & update Institute net credits - Start
					$credits_to_be_debted = ($enroll_det->fee-$enroll_det->admin_commission_val)-($no_of_credits_for_inst);
					$log_data = array(
									'user_id' => $enroll_det->inst_id,
									'credits' => $credits_to_be_debted,
									'per_credit_value' => $enroll_det->per_credit_value,
									'action'  => 'debited',
									'purpose' => 'Credits debited by Admin for the enroll id "'.$primary_key.'" as Student claimed for Admin intervention',
									'date_of_action	' => date('Y-m-d H:i:s'),
									'reference_table' => 'inst_enrolled_students',
									'reference_id' => $primary_key,
								);

					log_user_credits_transaction($log_data);

					update_user_credits($enroll_det->inst_id, $credits_to_be_debted, 'debit');
					//Log Credits transaction data & update Institute net credits - End
				}

			}

			return TRUE;

		} else return FALSE;
	}


	function view_certificates($id="")
 	{
	 	if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
				$this->prepare_flashmessage(get_languageword('You dont have permission to access this page'), 1);
				redirect('auth/login', 'refresh');
			}


			$username = getUserRec($id)->username;

			$this->load->library(array('grocery_CRUD_extended'));
			$crud = new grocery_CRUD_extended();
			$crud_state = $crud->getState();
			$crud->unset_jquery();
			$crud->set_table($this->db->dbprefix('users_certificates'));
			$crud->where('user_id',$id);

			$crud->set_relation('admin_certificate_id','certificates','title');

		
			$crud->unset_add();
			$crud->unset_delete();
			$crud->unset_edit();
			$crud->unset_read();
			
			
			//display columns    			
			$crud->columns('admin_certificate_id','certificate_name');

			$crud->callback_column('certificate_name',array($this,'showFile'));

			$crud->display_as('admin_certificate_id',get_languageword('certificate_type'));

			$output = $crud->render();

			$this->data['activemenu'] 	= "users";
			$this->data['pagetitle'] = get_languageword('certificates_of').' "'.$username.'"';
			$this->data['grocery_output'] = $output;
			$this->data['grocery'] = TRUE;
			$this->grocery_output($this->data);	
	}

	function showFile($row) {  
	   return "<a href='".URL_PUBLIC_UPLOADS_CERTIFICATES . $row."' target='_blank'>".$row." </a> ";
	}


	//Tutor money conversion requests
	function tutor_money_conversion_requests($param = "Pending")
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			$this->prepare_flashmessage(get_languageword('You dont have permission to access this page'), 1);
			redirect('auth/login', 'refresh');
		}

		$this->load->library(array('grocery_CRUD_extended'));
		$crud = new grocery_CRUD_extended();
		$crud_state = $crud->getState();
		$crud->unset_jquery();
		$crud->set_table($this->db->dbprefix('admin_money_transactions'));
		$crud->where('user_type','tutor');
		if(!empty($param))
			$crud->where('status_of_payment',$param);

		//unset actions	
		$crud->unset_add();
		$crud->unset_delete();


		//display columns
		$crud->columns('user_id','booking_id','user_name','user_paypal_email','no_of_credits_to_be_converted', 'per_credit_cost', 'total_amount','user_bank_ac_details','status_of_payment');

		$crud->required_fields('status_of_payment');

		$currency_symbol = $this->config->item('site_settings')->currency_symbol;
		$crud->display_as('per_credit_cost', get_languageword('per_credit_cost')." (".$currency_symbol.")");
		$crud->display_as('total_amount', get_languageword('total_amount')." (".$currency_symbol.")");

		//edit fields
		$crud->edit_fields('status_of_payment', 'transaction_details', 'updated_at', 'updated_by');

		$pmt_status = array();
		if($param == "Pending")
			$pmt_status = array('Done' => get_languageword('Done'));
		else if($param == "Done")
			$pmt_status = array('Pending' => get_languageword('Pending'));
		$crud->field_type('status_of_payment', 'dropdown', $pmt_status);


		$crud->field_type('updated_at', 'hidden',date('Y-m-d H:i:s'));
		$crud->field_type('updated_by', 'hidden',$this->ion_auth->get_user_id());

		$crud->callback_column('booking_id',array($this,'_call_back_column_booking_id'));
		$crud->callback_column('user_name',array($this,'_call_back_column_user_name'));

		$crud->callback_after_update(array($this, 'callback_log_user_credits'));

		if($crud_state == "read") {

			$crud->field_type('updated_at', 'visibile');
		}

		$output = $crud->render();

		$this->data['activemenu'] 	= "tutor_money_reqs";
		$this->data['activesubmenu'] 	= "tutor_".$param;
		$this->data['pagetitle'] = get_languageword('money_conversion_requests_from_tutor')." - ".get_languageword($param);
		$this->data['grocery_output'] = $output;
		$this->data['grocery'] = TRUE;
		$this->grocery_output($this->data);	

	}


	function _call_back_column_booking_id($primarykey, $row) {  
		return "<a href=".URL_ADMIN_STUDENT_BOOKINGS."/".$crud_state='read'."/".$row->booking_id.">".$row->booking_id."</a>";
	}

	function _call_back_column_user_name($primarykey, $row) {  
		return "<a href=".URL_AUTH_INDEX."/".$crud_state='read'."/".$row->user_id.">".$row->user_name."</a>";
	}


	function callback_log_user_credits($post_array, $primary_key)
	{

		$req_det = $this->base_model->fetch_records_from('admin_money_transactions', array('id' => $primary_key));

		if(!empty($req_det)) {

			if($post_array['status_of_payment'] == "Done") {

				$action  = "debited";
				$action1 = "debit";

			} else if($post_array['status_of_payment'] == "Pending") {

				$action  = "credited";
				$action1 = "credit";
			}

			$req_det = $req_det[0];
			//Log Credits transaction data & update user net credits - Start
			$log_data = array(
							'user_id' => $req_det->user_id,
							'credits' => $req_det->no_of_credits_to_be_converted,
							'per_credit_value' => $req_det->per_credit_cost,
							'action'  => $action,
							'purpose' => 'For Credits to Money Conversion with status "'.$post_array['status_of_payment'].'" ',
							'date_of_action	' => date('Y-m-d H:i:s'),
							'reference_table' => 'admin_money_transactions',
							'reference_id' => $primary_key,
						);

			log_user_credits_transaction($log_data);

			update_user_credits($req_det->user_id, $req_det->no_of_credits_to_be_converted, $action1);
			//Log Credits transaction data & update user net credits - End

			return TRUE;

		} else return FALSE;
	}
	

	//Institute money conversion requests
	function inst_money_conversion_requests($param = "Pending")
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			$this->prepare_flashmessage(get_languageword('You dont have permission to access this page'), 1);
			redirect('auth/login', 'refresh');
		}

		$this->load->library(array('grocery_CRUD_extended'));
		$crud = new grocery_CRUD_extended();
		$crud_state = $crud->getState();
		$crud->unset_jquery();
		$crud->set_table($this->db->dbprefix('admin_money_transactions'));
		$crud->where('user_type','institute');
		if(!empty($param))
			$crud->where('status_of_payment',$param);

		//unset actions			
		$crud->unset_add();
		$crud->unset_delete();


		//display columns    			
		$crud->columns('user_id','booking_id','user_name','user_paypal_email','no_of_credits_to_be_converted', 'per_credit_cost', 'total_amount','user_bank_ac_details','status_of_payment');

		$crud->required_fields('status_of_payment');

		$currency_symbol = $this->config->item('site_settings')->currency_symbol;
		$crud->display_as('per_credit_cost', get_languageword('per_credit_cost')." (".$currency_symbol.")");
		$crud->display_as('total_amount', get_languageword('total_amount')." (".$currency_symbol.")");

		//edit fields
		$crud->edit_fields('status_of_payment', 'transaction_details', 'updated_at', 'updated_by');

		$pmt_status = array();
		if($param == "Pending")
			$pmt_status = array('Done' => get_languageword('Done'));
		else if($param == "Done")
			$pmt_status = array('Pending' => get_languageword('Pending'));
		$crud->field_type('status_of_payment', 'dropdown', $pmt_status);

		$crud->field_type('updated_at', 'hidden',date('Y-m-d H:i:s'));
		$crud->field_type('updated_by', 'hidden',$this->ion_auth->get_user_id());

		$crud->callback_column('booking_id',array($this,'_call_back_column_batch_id'));
		$crud->callback_column('user_name',array($this,'_call_back_column_user_name'));

		$crud->callback_after_update(array($this, 'callback_log_user_credits'));

		if($crud_state == "read") {

			$crud->field_type('updated_at', 'visibile');
		}

		$output = $crud->render();

		$this->data['activemenu'] 	= "inst_money_reqs";
		$this->data['activesubmenu'] 	= "inst_".$param;
		$this->data['pagetitle'] = get_languageword('money_conversion_requests_from_isntitute')." - ".get_languageword($param);
		$this->data['grocery_output'] = $output;
		$this->data['grocery'] = TRUE;
		$this->grocery_output($this->data);	

	}

	function _call_back_column_batch_id($primarykey, $row) {  
		return "<a href='".URL_ADMIN_INST_BATCH_ENROLLED_STUDENTS."/".$row->booking_id."'>".$row->booking_id."</a>";
	}



	//view inst-tutors
	function view_inst_tutors($id="")
 	{
	 	if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
				$this->prepare_flashmessage(get_languageword('You dont have permission to access this page'), 1);
				redirect('auth/login', 'refresh');
			}

			$user_name = getUserRec($id)->username;
			$this->load->library(array('grocery_CRUD_extended'));
			$crud = new grocery_CRUD_extended();
			$crud_state = $crud->getState();
			$crud->unset_jquery();
			$crud->set_table($this->db->dbprefix('users'));
			$crud->where('parent_id',$id);
					
			$crud->unset_add();
			$crud->unset_delete();
			$crud->unset_edit();
			$crud->unset_read();

			//display columns    			
			$crud->columns('email','first_name','last_name','active');

			$crud->display_as('admin_approved', get_languageword('is_approved'));

			$output = $crud->render();

			$this->data['activemenu'] 	= "users";
			$this->data['pagetitle'] = get_languageword('institute_tutors_of').' "'.$user_name.'"';
			$this->data['grocery_output'] = $output;
			$this->data['grocery'] = TRUE;
			$this->grocery_output($this->data);	
	}

	function scroll_news()
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
				$this->prepare_flashmessage(get_languageword('You dont have permission to access this page'), 1);
				redirect('auth/login', 'refresh');
			}

			
			$this->load->library(array('grocery_CRUD_extended'));
			$crud = new grocery_CRUD_extended();
			$crud_state = $crud->getState();
			$crud->unset_jquery();
			$crud->set_table($this->db->dbprefix('scroll_news'));		
			
			//display columns    			
			$crud->columns('title','url','status');
			$crud->callback_add_field('url',array($this,'call_back_url_format'));	
			$crud->required_fields('title','url');
			$output = $crud->render();

			if($crud_state == 'read')
			$crud_state ='View';

			$this->data['activemenu'] 	= "pages";
			$this->data['activesubmenu'] 	= "scroll_news";
			$this->data['pagetitle'] = get_languageword($crud_state) .' '. get_languageword('scroll_news');
			$this->data['grocery_output'] = $output;
			$this->data['grocery'] = TRUE;
			$this->grocery_output($this->data);
	}

	function call_back_url_format()
	{
		return '<input type="text" class="form-control" maxlength="100" name="url" placeholder="http://www.sitename.com">';
	}
	
	function payments( $param = NULL )
	{
		if(!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()){
			$this->prepare_flashmessage(get_languageword('You do not have permission to access this page'),1);
			redirect('auth/login','refresh');
		}
		
		$this->data['message'] = $this->session->flashdata('message');

		$user_id = $this->ion_auth->get_user_id();
		$this->load->library(array('grocery_CRUD'));
		$crud = new grocery_CRUD();
		$crud_state = $crud->getState();
		$crud->set_table($this->db->dbprefix('subscriptions'));
		if ( $param != NULL && $param == 'pending' ) {
			$crud->where('payment_received', 0);
		}
		$crud->set_subject( get_languageword('subscriptions') );
		
		$crud->unset_add();
		$crud->unset_delete();
		$crud->unset_edit();
		$crud->unset_read();
		
		$crud->columns('subscribe_date','package_name','transaction_no', 'payment_type','credits','amount_paid');
		$crud->callback_column('subscribe_date',array($this,'callback_subscribe_date'));
		$crud->callback_column('amount_paid',array($this,'callback_amount_paid'));
		$output = $crud->render();
		
		$this->data['activemenu'] 	= "payments";
		$this->data['activesubmenu'] ="payments";
		$this->data['content'] 		= 'payments';
		$this->data['pagetitle'] = get_languageword('payments');
		$this->data['grocery_output'] = $output;
		$this->data['grocery'] = TRUE;
		$this->grocery_output($this->data);
	}
	
	function callback_subscribe_date($value, $row)
	{
		return date('d/m/Y', strtotime($value));
	}
	function callback_amount_paid( $value, $row ) {
		if ( $row->payment_received == 1 ) {
			$value .= '&nbsp;<img src="'.URL_FRONT_IMAGES . 'checked.png">';
		} else {
			if ( $row->payment_type == 'manual' ) {
				$value .= '&nbsp;<a href="' . site_url( 'admin/manual_payment_status/' . $row->id ) . '"><img src="'.URL_FRONT_IMAGES . 'error.png"></a>';
			} else {
				$value .= '&nbsp;<img src="'.URL_FRONT_IMAGES . 'error.png">';
			}
		}
		return $value;
	}
	
	function manual_payment_status( $payment_id ) {
		$this->data['message'] = $this->session->flashdata('message');
		if ( ! empty( $payment_id ) ) {
			$check = $this->db->query( 'SELECT * FROM `'.$this->db->dbprefix('subscriptions').'` s INNER JOIN `'.$this->db->dbprefix('users').'` u ON s.user_id = u.id AND s.payment_received = 0 WHERE s.id = ' . $payment_id )->result();
			if ( empty( $check ) ) {
				safe_redirect( $this->ion_auth->get_user_id() );
			} else {
				if(isset($_POST['submitbutt']))
				{
					$this->form_validation->set_rules('payment_updated_admin_message', get_languageword('Enter you comments'), 'trim|required|xss_clean');
					$this->form_validation->set_rules('is_received', get_languageword('Payment Received?'), 'trim|required|xss_clean');
					if ( $this->input->post('is_received') == 'yes' ) {
						$this->form_validation->set_rules('transaction_no', get_languageword('Reference No'), 'trim|required|xss_clean');
					}
					$this->form_validation->set_error_delimiters('<div class="error">', '</div>');			
					if ($this->form_validation->run() == TRUE)
					{
						$inputdata = array();						
						if ( $this->input->post('is_received') == 'yes' ) {
							$inputdata['payment_updated_admin'] = 'settled';
							$inputdata['payment_received'] = '1';
							$inputdata['transaction_no'] = $this->input->post('transaction_no');
						} else {
							$inputdata['payment_updated_admin'] = 'yes';
						}
						$inputdata['payment_updated_admin_time'] = date('Y-m-d H:i:s');
						$inputdata['payment_updated_admin_message'] = $this->input->post('payment_updated_admin_message');
						$this->base_model->update_operation($inputdata, 'subscriptions', array('id' => $payment_id));
						
						if( $this->input->post('is_received') == 'yes' ) {
							$user_id =  $check[0]->user_id;
							$package_details 	= $this->db->get_where('packages',array('id' => $check[0]->package_id))->result();
							$subscription_details 	= $package_details[0];
							$user_data['subscription_id'] 		= $payment_id;
							$this->base_model->update_operation($user_data, 'users', array('id' => $user_id));

							// Log Credits transaction data & update user net credits - Start
							$log_data = array(
								'user_id' => $user_id,
								'credits' => $subscription_details->credits,
								'per_credit_value' => get_system_settings('per_credit_value'),
								'action'  => 'credited',
								'purpose' => 'Package "'.$subscription_details->package_name.'" subscription',
								'date_of_action	' => date('Y-m-d H:i:s'),
								'reference_table' => 'subscriptions',
								'reference_id' => $payment_id,
							);
							log_user_credits_transaction($log_data);
							update_user_credits($user_id, $subscription_details->credits, 'credit');
							// Log Credits transaction data & update user net credits - End
						}
						$this->prepare_flashmessage(get_languageword('record updated successfully'), 0);
						redirect('admin/payments');	
					}
					$this->data['message'] = $this->prepare_message(validation_errors(), 1);
				}
				$this->data['activemenu'] 	 = "packages";
				$this->data['activesubmenu'] = "mysubscriptions";
				$this->data['content'] 		 = 'manual_payment_status';
				$this->data['pagetitle'] 	 = get_languageword('manual_payment_status');
				$this->data['profile'] = $check[0];
				$this->_render_page('template/admin/admin-template', $this->data);
			}
		} else {
			$this->safe_redirect( site_url( 'admin/payments' ), 'Wrong operation' );
		}
	}



	function view_purchased_courses()
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			$this->prepare_flashmessage(get_languageword('You dont have permission to access this page'), 1);
			redirect('auth/login', 'refresh');
		}


		$this->load->library(array('grocery_CRUD'));
		$crud = new grocery_CRUD();
		$crud->unset_jquery();
		$crud_state = $crud->getState();
		$crud->set_table(TBL_PREFIX.'course_purchases');
		$crud->set_relation('sc_id',TBL_PREFIX.'tutor_selling_courses','course_title');
		$crud->set_relation('tutor_id',TBL_PREFIX.'users','username');
		$crud->set_relation('user_id',TBL_PREFIX.'users','username');
		$crud->where('payment_status', 'Completed');

		$crud->set_subject( get_languageword('Purchased_Courses') );

		$crud->unset_add();
		$crud->unset_delete();
		$crud->unset_read();

		$crud->columns('sc_id','tutor_id','user_id','total_amount','admin_commission_val','max_downloads','total_downloads','paid_date', 'status_of_payment_to_tutor', 'tutor_payment_details');


		$crud->edit_fields('status_of_payment_to_tutor', 'tutor_payment_details');

		$crud->display_as('sc_id', get_languageword('Course_Title'));
		$crud->display_as('tutor_id', get_languageword('tutor_name'));
		$crud->display_as('user_id', get_languageword('student_name'));
		$crud->display_as('paid_date', get_languageword('Purchased_On'));
		$crud->display_as('status_of_payment_to_tutor', get_languageword('Payment_from_Admin'));


		$crud->add_action(get_languageword('View_Download_History'), URL_FRONT_IMAGES.'magnifier-grocery.png', URL_ADMIN_VIEW_COURSE_DOWNLOAD_HISTORY.'/');

		$output = $crud->render();

		$this->data['activemenu'] 		= "purchased_courses";
		$this->data['pagetitle'] 		= get_languageword('Purchased_Courses');
		$this->data['grocery_output'] 	= $output;
		$this->data['grocery'] 			= TRUE;
		$this->grocery_output($this->data);

	}


	function view_course_download_history($purchase_id = "")
	{

		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			$this->prepare_flashmessage(get_languageword('You dont have permission to access this page'), 1);
			redirect('auth/login', 'refresh');
		}


		if(empty($purchase_id)) {

			$this->prepare_flashmessage(get_languageword('invalid_request'), 1);
			redirect(URL_ADMIN_VIEW_PURCHASED_COURSES);
		}


		$this->load->library(array('grocery_CRUD'));
		$crud = new grocery_CRUD();
		$crud->unset_jquery();
		$crud_state = $crud->getState();
		$crud->set_table(TBL_PREFIX.'course_downloads');
		$crud->set_relation('sc_id',TBL_PREFIX.'tutor_selling_courses','course_title');
		$crud->set_relation('tutor_id',TBL_PREFIX.'users','username');
		$crud->set_relation('user_id',TBL_PREFIX.'users','username');
		$crud->where('purchase_id', $purchase_id);

		$crud->set_subject( get_languageword('Course_Download_History') );

		$crud->unset_add();
		$crud->unset_delete();
		$crud->unset_edit();
		$crud->unset_read();

		$crud->columns('sc_id','tutor_id','user_id','ip_address','browser','browser_version', 'platform', 'downloaded_date');

		$crud->display_as('sc_id', get_languageword('Course_Title'));
		$crud->display_as('tutor_id', get_languageword('tutor_name'));
		$crud->display_as('user_id', get_languageword('student_name'));

		$output = $crud->render();

		$this->data['activemenu'] 		= "purchased_courses";
		$this->data['pagetitle'] 		= get_languageword('Course_Download_History');
		$this->data['grocery_output'] 	= $output;
		$this->data['grocery'] 			= TRUE;
		$this->grocery_output($this->data);

	}








}