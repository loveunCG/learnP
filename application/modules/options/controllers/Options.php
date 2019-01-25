<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Options extends MY_Controller 
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
		$crud->set_table($this->db->dbprefix('terms_data'));
		$crud->where('term_type', 'degree');
		$crud->set_subject(get_languageword('degree'));
		$crud->columns('term_id', 'term_title','term_status');
		$crud->add_fields(array('term_title', 'term_content', 'term_status'));
		$crud->edit_fields(array('term_title', 'term_content', 'term_status'));
		$crud->required_fields(array('term_title', 'term_status'));
		$crud->unique_fields('term_title');
		$crud->callback_insert(array($this,'degree_insert_callback'));
		$output = $crud->render();		
		
		$this->data['activemenu'] = 'options';
		$this->data['activesubmenu'] = 'degree';

		if($crud_state == 'read')
			$crud_state ='View';
		if($crud_state == 'add')
			$this->data['activesubmenu'] = 'add_degree';

		if($crud_state != 'list')
		{
			$this->data['pagetitle'] = get_languageword($crud_state).' degree';
			$this->data['maintitle'] = get_languageword('degrees');
			$this->data['maintitle_link'] = URL_OPTIONS_INDEX;
		}
		else
		{
			$this->data['pagetitle'] = get_languageword('degrees');
		}
		$this->data['grocery_output'] = $output;
		$this->data['grocery'] = TRUE;
		$this->grocery_output($this->data);
	}
	
	function degree_insert_callback( $post_array )
	{
		$data = array(
			'term_title' => $post_array['term_title'],
			'term_content' 			=> $post_array['term_content'],
			'term_slug'	 			=> clean_url($post_array['term_title']),
			'term_status' 				=> $post_array['term_status'],
			'term_created' 				=> date('Y-m-d H:i:s'),
			'term_type' => 'degree',
		);
		return $this->db->insert('terms_data',$data);
	}
}