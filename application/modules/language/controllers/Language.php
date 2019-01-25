<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Language extends MY_Controller 
{
	function __construct()
	{
		parent::__construct();
		$this->load->library(array('session'));
		$this->load->library(array('ion_auth','form_validation', 'grocery_CRUD'));
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
		{
			redirect(SITEURL, 'refresh');
		}
		$this->load->model('language_model');
		$this->data['statistics'] = $this->language_model->getLanguageStatistics();
	}
	
	function isAdmin()
	{
		$group = array('admin');
		if (!$this->ion_auth->in_group($group)) {
			$this->prepare_flashmessage(get_languageword('MSG_NO_ENTRY'),2);
			redirect(getUserType());
		}
	}
	/*** Displays the Index Page **/
	function index()
	{		
		$this->isAdmin();
		$this->data['message'] = $this->session->flashdata('message');	
		$this->data['records'] = $this->db->list_fields('languagewords');
		
		$this->data['content'] = 'index';
		$this->data['activemenu'] = 'language';
		$this->data['activesubmenu'] = 'view';
		$this->data['helptext'] = array();
		$this->data['pagetitle'] = get_languageword('view_language');
		$this->_render_page('template/admin/admin-template', $this->data);		
	}
	function checkduplicatelanguage()
	{
		$this->isAdmin();
		$ret = clean_text(strtolower($this->input->post('title')));		
		if (!$this->db->field_exists($ret, $this->db->dbprefix('languagewords'))) {
		  return true;
		} else {
		  $this->form_validation->set_message('checkduplicatelanguage', get_languageword('language_already_exists'));
		  return false;
		}
	}	
	function addlanguage($id = '')
	{
		$this->isAdmin();
		$this->data['id'] = $id;
		$condition = array();
		$condition['term_id'] = $id;
		
		if( $this->input->post( 'submitbutt' ) )
		{
			$this->load->library('form_validation');
			$this->form_validation->set_rules('title',get_languageword('language'),'trim|required|callback_checkduplicatelanguage');
			
			if($this->form_validation->run()!=false)
			{
				$language = clean_text(strtolower($this->input->post('title')));
				$this->load->dbforge();	
				if( $this->input->post('id') == '' )
				{
					$fields = array(
						$language => array(
							'type' => 'LONGTEXT',
							'COLLATE' => 'utf8_general_ci',
						)
					);
					$this->dbforge->add_column(TBL_LANGUAGEWORDS, $fields);
					$this->prepare_flashmessage(get_languageword('MSG_LANGUAGE_ADDED'), 0);					
				}
				else
				{
					$fields = array(
							$id => array(
									'name' => $language,
									'type' => 'LONGTEXT',
							),
					);
					$this->dbforge->modify_column(TBL_LANGUAGEWORDS, $fields);
					$this->prepare_flashmessage(get_languageword('MSG_LANGUAGE_UPDATED'), 0);					
				}	
				//
				$langs = '';
				$languages = $this->db->list_fields('languagewords');
				
				foreach($languages as $l)
				{
					if($l=='lang_id' || $l=='lang_key')
						continue;
					else
						$langs = $langs.$l.',';
				}
				$langs = substr($langs, 0, -1);
				$this->base_model->run_query("UPDATE ".TBL_PREFIX.TBL_SETTINGS_FIELDS." SET field_type_values = '$langs' WHERE type_id=1 AND field_key='Default_Language'" );
				//
				redirect(URL_LANGUAGE_INDEX);				
			}
			else
			{
				$this->data['message'] = prepare_message(validation_errors(),1);
			}
			
		}		
		$this->data['activemenu'] = 'language';
		$this->data['activesubmenu'] = 'add';
		$this->data['pagetitle'] = get_languageword('add_language');
		$this->data['details'] = $this->db->list_fields('languagewords');
		$this->data['content'] = 'addlanguage';
		$this->_render_page('template/admin/admin-template', $this->data);
	}
	function deletelanguage($id)
	{
		$this->isAdmin();
		$this->load->dbforge();
		$this->dbforge->drop_column(TBL_LANGUAGEWORDS, urldecode($id));
		//
		$langs = '';
		$languages = $this->db->list_fields('languagewords');
		
		foreach($languages as $l)
		{
			if($l=='lang_id' || $l=='lang_key')
				continue;
			else
				$langs = $langs.$l.',';
		}
		$langs = substr($langs, 0, -1);
		$this->base_model->run_query("UPDATE ".TBL_PREFIX.TBL_SETTINGS_FIELDS." SET field_type_values = '$langs' WHERE type_id=1 AND field_key='Default_Language'" );
		//
		
		$this->prepare_flashmessage(get_languageword('MSG_LANGUAGE_DELETED'), 0);
		redirect(URL_LANGUAGE_INDEX);
	}
	
	/*** Displays the Phrases Page**/
	function phrases()
	{	
		$this->isAdmin();		
		$crud = new grocery_CRUD();
		$crud->unset_jquery(); //As we are using admin lte we need to unset default jQuery
		$crud->set_table($this->db->dbprefix(TBL_LANGUAGEWORDS));
		$crud->set_subject(get_languageword('phrase'));
		$crud->columns('lang_id','lang_key', 'english');
		$crud->required_fields(array('lang_key', 'english'));
		$crud->unique_fields('lang_key');
		$state = $crud->getState();

		$crud->unset_texteditor('*');

		$output = $crud->render();
		$this->data['activemenu'] = 'language';
		$this->data['activesubmenu'] = 'phrases';
		if($state != 'list')
		{
		$this->data['maintitle_link'] = base_url().'language/phrases';
		}
		$this->data['maintitle'] = get_languageword('language_settings');
		$this->data['pagetitle'] = get_languageword('view_phrases');
		$this->data['grocery_output'] = $output;
		$this->data['grocery'] = TRUE;
		$this->grocery_output($this->data);
	}
		
	/**
	* This function fecilitate to enter language words
	* @param string $language
	* @return void
	*/
	function addlanguagephrases( $language = "" )
	{
		$this->isAdmin();
		if(empty($language)) {
			$this->prepare_flashmessage(getPhase('Please select language'), 1);
			redirect(URL_LANGUAGE_INDEX);
		}

		if($this->input->post()) {
			$words = $this->input->post('word');

			$id = ($this->input->post('id')) ? $this->input->post('id') : $language;
			foreach($words as $key => $val) {
				$key = str_replace('___', ' ', $key);
				if(!empty($key) && !is_numeric($key) && !empty($val))
					$this->base_model->update_operation( array($id => $val), 'languagewords', array('lang_key' => $key) );
			}
			$this->prepare_flashmessage(get_languageword('success_phrases_updated_successfuly'), 0);
			redirect(URL_LANGUAGE_INDEX);
		}
		
		$this->data['activemenu'] = 'language';
		$this->data['activesubmenu'] = 'view';
		$this->data['pagetitle'] = get_languageword('add_phrases');
		$this->data['id'] = $language;
		$this->data['languagewords'] = $this->base_model->fetch_records_from('languagewords', array(), '*', 'lang_key');
		$this->data['content'] = 'addlanguagephrases';								   
		$this->_render_page('template/admin/admin-template', $this->data);
	}
	
	/*** Displays the Tutor Languages Page**/
	function languages()
	{	
		$this->isAdmin();		
		$crud = new grocery_CRUD();
		$crud->unset_jquery(); //As we are using admin lte we need to unset default jQuery
		$crud->set_table($this->db->dbprefix(TBL_LANGUAGES));
		$crud->set_subject(get_languageword('language'));
		$crud->columns('id','name','status');
		$crud->required_fields(array('name', 'status'));
		$crud->unique_fields('name');
		$state = $crud->getState();
		$output = $crud->render();
		$this->data['activemenu'] = 'language';
		$this->data['activesubmenu'] = 'languages';
		if($state != 'list')
		{
		$this->data['maintitle_link'] = base_url().'language/languages';
		}
		$this->data['maintitle'] = get_languageword('language_settings');
		$this->data['pagetitle'] = get_languageword('Tutoring_Languages');
		$this->data['grocery_output'] = $output;
		$this->data['grocery'] = TRUE;
		$this->grocery_output($this->data);
	}
}