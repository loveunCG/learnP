<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Packages extends MY_Controller 
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
		$crud->set_table($this->db->dbprefix('packages'));
		$crud->set_subject('Package');
		$crud->columns('id','package_name','package_for', 'package_cost','package_cost_after_discount','discount','discount_type', 'credits', 'status');
		$crud->unset_fields(array('date_updated'));
		$crud->set_field_upload('image','assets/uploads/package_logos');
		$crud->required_fields(array('package_name', 'package_for', 'description', 'package_cost', 'status', 'actual_cost'));


		$crud->unset_read();
		$crud->callback_column('package_cost_after_discount',array($this,'callback_package_cost'));
		$crud->callback_column('discount',array($this,'callback_discount'));

		$crud->display_as('package_cost', get_languageword('package_cost')." (".get_system_settings('currency_symbol').")");
		$crud->display_as('package_cost_after_discount', get_languageword('package_cost_after_discount')." (".get_system_settings('currency_symbol').")");

		//Rules
		$crud->set_rules('package_for',get_languageword('package_for'),'trim|required');
		$output = $crud->render();
		
		$this->data['activemenu'] = 'packages';		
		$this->data['activesubmenu'] = 'list_packages';	

		if($crud_state == 'read')
			$crud_state ='View';

		if($crud_state != 'list')
		{
			if($crud_state == 'add')
			$this->data['activesubmenu'] = 'add_package';
			$this->data['pagetitle'] = get_languageword($crud_state).' '.get_languageword('package');
			$this->data['maintitle'] = get_languageword('packages');
			$this->data['maintitle_link'] = URL_PACKAGE_INDEX;
		}
		else
		{
			$this->data['activesubmenu'] = 'list_packages';
			$this->data['pagetitle'] = get_languageword('packages');
		}
		$this->data['grocery_output'] = $output;
		$this->data['grocery'] = TRUE;
		$this->grocery_output($this->data);
	}

	function callback_discount($primarykey, $row)
	{
		if(isset($row->discount))
		{
			if($row->discount_type =='Value')
			{
				return get_system_settings('currency_symbol').' '. $row->discount;
			}
			else
			{
				return $row->discount.'%';
			}
		}
	}

	function callback_package_cost($primarykey, $row)
	{
		if(isset($row->discount))
		{ 
			if($row->discount_type == 'Value')
			{
				$final_cost = $row->package_cost - $row->discount;
				return  $final_cost;
			}
			else
			{
				$discount = ($row->discount/100)*$row->package_cost;							
				$final_cost = $row->package_cost - $discount;
				return  $final_cost;	
			}
		}
	}
}