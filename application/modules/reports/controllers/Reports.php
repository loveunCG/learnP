<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Reports extends MY_Controller 
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->library(array('ion_auth','form_validation', 'grocery_CRUD'));
		$this->load->helper(array('language'));

		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

	}
	
	
	/**
	 * Fecilitates to See All Student leads
	 * @access	public
	 * @return	string
	 */

	function users_info()
	{
	 	if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			$this->prepare_flashmessage(get_languageword('You dont have permission to access this page'), 1);
			redirect('auth/login', 'refresh');
		}
		
		$this->data['message'] = $this->session->flashdata('message');
		$this->data['profile'] = getUserRec();
		$this->load->library(array('grocery_CRUD'));
		$crud = new grocery_CRUD();
		$crud_state = $crud->getState();
		$crud->unset_jquery();
		$crud->set_table($this->db->dbprefix('users'));
		$crud->order_by('id','desc');
		$crud->set_relation('id','users','{phone_code}{phone}');
		$crud->set_relation('location_id','locations','location_name');
		$crud->set_relation('user_belongs_group','groups','name');
		$crud->set_subject( get_languageword('users_information') );
		
		
		$crud->unset_add();
		$crud->unset_delete();
		$crud->unset_edit();
		$crud->unset_read();

		$crud->columns('username','email','first_name','last_name','gender','dob','id','location_id', 'is_premium','user_belongs_group','active');
				
		$crud->callback_column('is_premium', array($this, 'call_back_check_is_premium'));

		//####### Changing column names #######
		$crud->display_as('dob',get_languageword('date_of_birth'));
		$crud->display_as('location_id',get_languageword('location_name'));
		$crud->display_as('user_belongs_group',get_languageword('user_type'));
		$crud->display_as('id',get_languageword('phone_number'));
		$crud->display_as('active',get_languageword('status'));
		

		$output = $crud->render();

		if($crud_state == 'read')
			$crud_state ='View';
		
		$this->data['activemenu'] 	= "reports";
		$this->data['activesubmenu'] = "users_info";
		$this->data['pagetitle'] = get_languageword($crud_state) .' ' . get_languageword('users_information');
		$this->data['grocery_output'] = $output;
		$this->data['grocery'] = TRUE;
		$this->grocery_output($this->data);
	

	}

	function call_back_check_is_premium($primarykey, $row) {

        return (is_premium($row->id)) ? 'Yes' : 'No';
    }

	function packages()
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			$this->prepare_flashmessage(get_languageword('You dont have permission to access this page'), 1);
			redirect('auth/login', 'refresh');
		}

		$this->data['message'] = $this->session->flashdata('message');
		$this->data['profile'] = getUserRec();
		$this->load->library(array('grocery_CRUD'));
		$crud = new grocery_CRUD();
		$crud_state = $crud->getState();
		$crud->unset_jquery();
		// $crud->set_theme('datatables');

		$this->db->select('package_name, package_cost');
		
		$crud->set_table($this->db->dbprefix('packages'));
		$crud->order_by('id','desc');
		$crud->columns('package_name','package_cost','package_cost_after_discount','package_for','credits','no_of_students_subscribed','no_of_tutors_subscribed','no_of_institutes_subscribed', 'total_subscribers','total_payments');


		//unset actions
		$crud->unset_add();
		$crud->unset_delete();
		$crud->unset_edit();
		$crud->unset_read();

		//callback columns
		$crud->callback_column('package_cost_after_discount',array($this,'callback_package_cost'));
		$crud->callback_column('no_of_students_subscribed',array($this,'call_back_no_of_students_subscribed'));
		$crud->callback_column('no_of_tutors_subscribed',array($this,'call_back_no_of_tutors_subscribed'));
		$crud->callback_column('no_of_institutes_subscribed',array($this,'call_back_no_of_institutes_subscribed'));
		$crud->callback_column('total_subscribers', array($this, 'call_back_total_subscribers'));
		$crud->callback_column('total_payments', array($this, 'call_back_total_payments'));


		$output = $crud->render();

		if($crud_state == 'read')
			$crud_state ='View';
		
		$this->data['activemenu'] 	= "reports";
		$this->data['activesubmenu'] = "packages_info";
		$this->data['pagetitle'] = get_languageword($crud_state) .' '. get_languageword('of_Packages_Subscribe_Details');
		$this->data['grocery_output'] = $output;
		$this->data['grocery'] = TRUE;
		$this->grocery_output($this->data);


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


	function call_back_no_of_students_subscribed($primarykey, $row){

		$package_id = $row->id;	
		$user_type  = 'Student';
		$this->load->model('reports_model');
		$package_subscribe = $this->reports_model->get_package_subscribe_number($package_id, $user_type);
		return $package_subscribe;
	}


	function call_back_no_of_tutors_subscribed($primarykey, $row){

		$package_id = $row->id;	
		$user_type  = 'Tutor';
		$this->load->model('reports_model');
		$package_subscribe = $this->reports_model->get_package_subscribe_number($package_id, $user_type);
		return $package_subscribe;
	}

	function call_back_no_of_institutes_subscribed($primarykey, $row){

		$package_id = $row->id;	
		$user_type  = 'Institute';
		$this->load->model('reports_model');
		$package_subscribe = $this->reports_model->get_package_subscribe_number($package_id, $user_type);
		return $package_subscribe;
	}

	function call_back_total_subscribers($primarykey, $row){
		$package_id = $row->id;
		$this->load->model('reports_model');
		$package_subscribe = $this->reports_model->get_package_subscribe_number($package_id);
		return $package_subscribe;
	}

	function call_back_total_payments($primarykey, $row){
		$package_id  =	$row->id;	
		$subscribers =	$row->total_subscribers;
		$this->load->model('reports_model');
		$package_subscribe_amount = $this->reports_model->get_package_subscribe_total_amount($package_id);
		return  $subscribers * $package_subscribe_amount;

	}
	
	
}