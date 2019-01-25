<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Sitetestimonials extends MY_Controller 
{
	function __construct()
	{
		parent::__construct();
		$this->load->library(array('session'));
		$this->load->library(array('ion_auth','form_validation', 'grocery_CRUD'));
		
		$this->load->model('packages_model');
		$this->data['statistics'] = $this->packages_model->getPackageStatistics();
		
		$group = array('admin','user');
		if (!$this->ion_auth->in_group($group)) {
			$this->prepare_flashmessage(get_languageword('MSG_NO_ENTRY'),2);
			redirect(getUserType());
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

	/** Displays the Index Page**/
	function index()
	{		
		$this->isAdmin();
		$crud = new grocery_CRUD();
		$crud_state = $crud->getState();
		
		$crud->unset_jquery(); //As we are using admin lte we need to unset default jQuery
		$crud->set_table($this->db->dbprefix('sitetestimonials'));
		$crud->set_subject('testimonial');
		$crud->columns('id','name', 'position', 'image', 'status');
		
		$crud->add_fields(array('name', 'position', 'comments', 'image', 'status', 'created'));
		$crud->edit_fields(array('name', 'position', 'comments', 'image', 'status', 'updated'));
		//Hidden Fields
		$crud->field_type('created', 'hidden', date('Y-m-d H:i:s'));
		$crud->field_type('updated', 'hidden', date('Y-m-d H:i:s'));		
		//$crud->set_rule('image', 'gif|jpeg|jpg|png');		
		$crud->set_field_upload('image','assets/uploads/testimonials');
		$crud->required_fields(array('name', 'position', 'comments'));
		$crud->callback_before_upload(array($this, '_valid_images'));		

		$output = $crud->render();

		if($crud_state == 'read')
			$crud_state ='View';
		
		$this->data['activemenu'] = 'sitetestimonials';		
		$this->data['activesubmenu'] = 'sitetestimonials';		
		if($crud_state != 'list')
		{
			if($crud_state == 'add')
			$this->data['activesubmenu'] = 'sitetestimonials-'.$crud_state;
			$this->data['pagetitle'] = get_languageword($crud_state).' '.get_languageword('testimonial');
			$this->data['maintitle'] = get_languageword('testimonials');
			$this->data['maintitle_link'] = base_url().'sitetestimonials/index';
		}
		else
		{
			$this->data['pagetitle'] = get_languageword('testimonials');
		}
		$this->data['grocery_output'] = $output;
		$this->data['grocery'] = TRUE;
		$this->grocery_output($this->data);
	}
	
	public function _valid_images($files_to_upload, $field_info)
	{
	  if (!in_array($files_to_upload[$field_info->encrypted_field_name]['type'], array('image/png', 'image/jpg', 'image/jpeg')))
	  {
	   return 'Sorry, we can upload only PNG-images here.';
	  }
	  return true;
	}
}