<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Certificates extends MY_Controller 
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
		$crud_state = $crud->getState();
		
		$crud->unset_jquery(); //As we are using admin lte we need to unset default jQuery
		$crud->set_table($this->db->dbprefix('certificates'));
		$crud->set_subject(get_languageword('certificates'));
		$crud->columns('title', 'description', 'certificate_for', 'status');
		$crud->add_fields(array('title', 'description', 'required', 'certificate_for', 'status', 'created'));
		$crud->edit_fields(array('title', 'description', 'required', 'certificate_for', 'status', 'updated'));
		$crud->required_fields(array('title', 'description', 'required', 'certificate_for', 'status'));

		
		//Field Types
		$crud->field_type('created', 'hidden', date('Y-m-d H:i:s'));
		$crud->field_type('updated', 'hidden', date('Y-m-d H:i:s'));


		$crud->order_by('certificate_id','desc');
		$output = $crud->render();
		
		$this->data['activemenu'] = 'certificates';		
		$this->data['activesubmenu'] = 'certificates';

		if($crud_state == 'read')
			$crud_state ='View';
		
		if($crud_state != 'list')
		{
			if($crud_state == 'add')
			$this->data['activesubmenu'] = 'certificates-add';
			$this->data['pagetitle'] = get_languageword($crud_state).' '.get_languageword('certificates');
			$this->data['maintitle'] = get_languageword('certificates');
			$this->data['maintitle_link'] = URL_CERTIFICATES_INDEX;
		}
		else
		{
			$this->data['activesubmenu'] = 'certificates';
			$this->data['pagetitle'] = get_languageword('certificates');
		}
		
		$this->data['grocery_output'] = $output;
		$this->data['grocery'] = TRUE;
		$this->grocery_output($this->data);
	}
}