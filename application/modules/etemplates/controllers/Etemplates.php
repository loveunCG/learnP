<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Etemplates extends MY_Controller 
{
	function __construct()
	{
		parent::__construct();
		$this->load->library(array('session'));
		$this->load->library(array('ion_auth','form_validation', 'grocery_CRUD'));
		
		$group = array('admin');
		if (!$this->ion_auth->in_group($group)) {
			$this->prepare_flashmessage(get_languageword('MSG_NO_ENTRY'),2);
			redirect(getUserType());
		}
	}
	
	/** Displays the Index Page**/
	function index()
	{
		$crud = new grocery_CRUD();
		$crud->unset_jquery(); //As we are using admin lte we need to unset default jQuery
		$crud->set_table($this->db->dbprefix('email_templates'));
		$crud->set_subject(get_languageword('template'));
		$crud->columns('email_template_id','template_key');
		$crud->add_fields(array('template_key', 'template_subject', 'template_content', 'from_email', 'from_name', 'template_status'));
		$crud->edit_fields(array('template_key', 'template_subject', 'template_content', 'from_email', 'from_name','template_status'));
		$crud->required_fields(array('template_key', 'template_subject', 'template_content', 'template_status'));
		$crud->set_rules('from_email',get_languageword('from_email'),'valid_email');
		$crud->unique_fields('template_key');

		$crud->unset_delete();

		$output = $crud->render();		
		$this->data['activemenu'] = 'etemplates';
		$this->data['pagetitle']  = get_languageword('Email_Templates');
		$this->data['grocery_output'] = $output;
		$this->data['grocery'] = TRUE;
		$this->grocery_output($this->data);
	}
	
	/** Displays the Index Page**/
	function view_subjects( $param )
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			$this->prepare_flashmessage(get_languageword('Please login to access this area'));
			redirect('auth/login');
		}
		
		if(empty($param))
		{
			$this->prepare_flashmessage(get_languageword('Please select a subject'));
			redirect('subjects/index');
		}
		
		$crud = new grocery_CRUD();
		$crud->set_table($this->db->dbprefix('subjects'));
		$crud->where('subject_parent_id', $param);
		$crud->set_subject('Subjects');
		$crud->columns('id','subject_name');
		$crud->unset_fields(array('subject_title', 'sort_order'));
		$crud->set_field_upload('image','assets/uploads/subject_logos');
		$crud->required_fields(array('subject_name', 'status'));
		$crud->unique_fields('subject_name');
		
		$crud->field_type('subject_parent_id', 'hidden', $param); //Add hidden field
		
		$output = $crud->render();		
		$this->data['activemenu'] = 'subjects';
		$this->data['grocery_output'] = $output;
		$this->data['grocery'] = TRUE;
		$this->grocery_output($this->data);
	}
}