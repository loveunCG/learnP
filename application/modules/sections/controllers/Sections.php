<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Sections extends MY_Controller 
{
	function __construct()
	{
		parent::__construct();
		$this->load->library(array('session'));
		$this->load->library(array('ion_auth','form_validation'));


		$group = array('admin');
		if (!$this->ion_auth->in_group($group)) {
			$this->prepare_flashmessage(get_languageword('MSG_NO_ENTRY'),2);
			redirect(getUserType());
		}
	}



	function index()
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			$this->prepare_flashmessage(get_languageword('You dont have permission to access this page'), 1);
			redirect('auth/login', 'refresh');
		}


		$this->load->library(array('grocery_CRUD_extended'));
		$crud = new grocery_CRUD_extended();
		$crud_state = $crud->getState();
		$crud->unset_jquery();
		$crud->set_table($this->db->dbprefix('sections'));
		$crud->set_subject( get_languageword('sections') );

		$crud->required_fields('description');
		if($crud_state == "edit" || $crud_state == "update")
			$crud->field_type('name', 'readonly');

		$crud->unique_fields('name');

		$crud->unset_add();
		$crud->unset_delete();

		//display columns    			
		$crud->columns('name','description');

		$crud->display_as('name', get_languageword('section_name'));

		if($crud_state == 'read')
			$crud_state ='View';

		$output = $crud->render();

		$this->data['activemenu'] 	= "sections";
		$this->data['activesubmenu'] = "list_sections";
		if($crud_state != "list")
			$this->data['activesubmenu'] = "sections-add";

		$this->data['pagetitle'] = get_languageword($crud_state).' '. get_languageword('sections');
		if($crud_state == "list")
			$this->data['pagetitle'] = get_languageword($crud_state).' '. get_languageword('sections');
		$this->data['grocery_output'] = $output;
		$this->data['grocery'] = TRUE;
		$this->grocery_output($this->data);	
	}







}