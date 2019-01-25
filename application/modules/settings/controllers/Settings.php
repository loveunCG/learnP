<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Settings extends MY_Controller 
{
	function __construct()
	{
		parent::__construct();
		$this->load->library(array('session'));
		$this->load->library(array('ion_auth','form_validation', 'grocery_CRUD'));
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
		{
			redirect('auth/login', 'refresh');
		}
		$this->load->model('settings_model');
		$this->data['statistics'] = $this->settings_model->getSettingsStatistics();
		
		$this->data['menues'] = $this->base_model->fetch_records_from(TBL_SETTINGS_TYPES, array('parent_id' => 0));
		
	}
	function isAdmin()
	{
		$group = array('admin');
		if (!$this->ion_auth->in_group($group)) {
			$this->prepare_flashmessage(get_languageword('MSG_NO_ENTRY'),2);
			redirect(getUserType());
		}
	}
	/*** Displays the Index Page**/
	function index()
	{
		$crud = new grocery_CRUD();
		$crud_state = $crud->getState();
		$crud->unset_jquery(); //As we are using admin lte we need to unset default jQuery
		$crud->set_table($this->db->dbprefix(TBL_SETTINGS_TYPES));
		$crud->where('parent_id', 0);
		$crud->where('type_id !=', 9);
		//$crud->where('type_id != ', 9);
		$crud->set_subject( get_languageword('settings') );
		$crud->columns('type_id','type_title');

		$crud->unset_add();
		$crud->unset_delete();
		$crud->unset_edit();
		
		$crud->add_fields(array('type_title', 'type_slug', 'created'));
		$crud->edit_fields(array('type_title', 'type_slug', 'updated'));
		
		//Add Hidden fields
		$crud->field_type('created', 'hidden', date('Y-m-d H:i:s'));
		$crud->field_type('updated', 'hidden', date('Y-m-d H:i:s'));
		
		$crud->unique_fields('type_title', 'type_slug');
		$crud->required_fields(array('type_title', 'type_slug'));
		
		//After development we need to uncomment these operations
		/*
		$crud->unset_delete();
		$crud->unset_add();
		$crud->unset_edit();
		$crud->unset_read();
		*/
		
		//$crud->add_action('view_fields', 'view_fields', 'settings/fieldsvalues','ui-icon-plus'); //TO add custom action link
		
					
		$crud->add_action(get_languageword('Edit'), URL_GROCERY_CRUD_IMAGES.'edit.png', '','',array($this,'subtypes_link'));
		$crud->add_action(get_languageword('View_Fields'), URL_FRONT_IMAGES.'view_fields.png', 'settings/fields');
		
		
		$output = $crud->render();
		
		$this->data['activemenu'] = 'settings';
		$this->data['activesubmenu'] = 'settings';
		$this->data['pagetitle'] = get_languageword('settings');
		$this->data['grocery_output'] = $output;
		$this->data['grocery'] = TRUE;
		$this->grocery_output($this->data);		
	}
	
	function subtypes_link($primary_key , $row)
	{
		$check = $this->base_model->fetch_records_from(TBL_SETTINGS_TYPES, array('parent_id' => $row->type_id));
		if(count($check) == 0)
		$url = URL_SETTINGS_FIELDSVALUES . '/' . $row->type_id;
		else
		$url = URL_SETTINGS_SUBTYPES . '/' . $row->type_id;
		return $url;
	}
	
	/**
	* This will display field values
	* @param	Int $id
	* @return	void
	*/
	function fieldsvalues($id)
	{
		$this->isAdmin();

		if(empty($id) || !($id > 0)) {

			$this->prepare_flashmessage(get_languageword('Invalid_Request'), 1);
			redirect(URL_SETTINGS_INDEX);
		}

		$this->data['message'] = $this->session->flashdata('message');
		$this->data['id'] = $id;
		$condition = array();		
		$condition['type_id'] = $id;
		
		if( $this->input->post( 'submitbutt' ) )
		{
			$this->load->library('form_validation');			
			$field_values = $this->input->post('field');
			
			if(count($_FILES) > 0)
			{
				$filedata = array();
				$this->load->library('upload');
				foreach ($_FILES as $key => $value)
				{
					$file_error = '';
					if (!empty($value['name']) && $value['error'] != 4)
					{
						$config['overwrite'] = TRUE;
						$config['upload_path'] = URL_PUBLIC_UPLOADS . 'settings/';							
						$config['allowed_types'] = 'gif|jpg|jpeg|png';
						$ext = pathinfo($_FILES[$key]['name'], PATHINFO_EXTENSION);						
						$field_parts = explode('_', $key);
						$field_id = $field_parts[1];
						$config['file_name'] =  $key . '.' . $ext;
						//print_r($config);
						$this->upload->initialize($config);
						
						if (!$this->upload->do_upload($key))
						{
							$file_error .= $this->upload->display_errors();
						}
						else
						{								
							$this->create_thumbnail($config['upload_path'] . $config['file_name'], $config['upload_path'] . 'thumbs'.DS, 178, 115);
							$filedata['field_output_value'] = $config['file_name'];
						}									 
					}
					//print_r($_FILES);print_r($filedata);die($file_error);
					if(count($filedata) > 0)
					{
						$where = array('type_id' => $this->input->post('gid'), 'field_id' => $field_id);
						$this->base_model->update_operation( $filedata, TBL_SETTINGS_FIELDS, $where );
					}
				}
			}
			//echo '<pre>';print_r($field_values);die();
			foreach($field_values as $field_id => $val) {
				$inputdata = array(
						'type_id' => $this->input->post('gid'),
						'field_id' => $field_id,
						'field_output_value' => $val,
						'updated' => date('Y-m-d H:i:s'),
					);
				$where = array('type_id' => $this->input->post('gid'), 'field_id' => $field_id);
				$this->base_model->update_operation( $inputdata, TBL_SETTINGS_FIELDS, $where );	
				
				//To set Default language
				if($field_id == 73)
				{
					$this->session->set_userdata('current_language' , strtolower($val));
				}
			}
			
			$this->prepare_flashmessage(get_languageword('MSG_RECORD_UPDATED'), 0);
			if($this->input->post('editing') == 'sub')
			{
				redirect( URL_SETTINGS_SUBTYPES . DS . $this->input->post('activesubmenu') );
			} else {
				redirect( URL_SETTINGS_INDEX );
			}
		}
		$this->data['details'] = $this->base_model->fetch_records_from(TBL_SETTINGS_TYPES, array('type_id' => $id));
		
		$this->data['editing'] = 'main';
		if(count($this->data['details']) > 0)
		{
			$id = 	($this->data['details'][0]->parent_id == 0) ? $id : $this->data['details'][0]->parent_id;
			$this->data['editing'] = ($this->data['details'][0]->parent_id == 0) ? 'main' : 'sub';
		}
		$this->data['activemenu'] = 'settings';				
		$this->data['activesubmenu'] = $id;
		$this->data['pagetitle'] = get_languageword('add_setting_field');
		$this->data['maintitle'] = get_languageword('settings');
		if($this->data['editing'] == 'sub')
		{
		$this->data['maintitle_link'] = URL_SETTINGS_SUBTYPES . '/' . $id;
		}
		else
		{
		$this->data['maintitle_link'] = URL_SETTINGS_INDEX;		
		}
		
		$this->data['fields'] = $this->base_model->fetch_records_from(TBL_SETTINGS_FIELDS, $condition);
		
		$this->data['content'] = 'fieldsvalues';
		$this->_render_page('template/admin/admin-template', $this->data);
	}
	
	
	/**
	 * Displays the Index Page
	 *
	 */
	function subtypes($param)
	{	
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			$this->prepare_flashmessage(get_languageword('Please login to access this area'));
			redirect('auth/login');
		}
		
		if(empty($param))
		{
			$this->prepare_flashmessage(get_languageword('Please select a subject'));
			redirect('locations/index');
		}
		
		$type_name = $this->base_model->fetch_records_from(TBL_SETTINGS_TYPES, array('type_id' => $param));
		if(empty($type_name))
		{
			$this->prepare_flashmessage(get_languageword('invalid location'));
			redirect('settings/index');
		}
		$type_name = $type_name[0]->type_title;
		
		$crud = new grocery_CRUD();
		$crud_state = $crud->getState();
		$crud->unset_jquery(); //As we are using admin lte we need to unset default jQuery
		$crud->set_table($this->db->dbprefix(TBL_SETTINGS_TYPES));
		$crud->where('parent_id', $param);
		$crud->set_subject(get_languageword('Types for ').$type_name);
		$crud->columns('type_id','type_title');
		
		$crud->add_fields(array('type_title', 'type_slug','status', 'is_default', 'created'));
		$crud->edit_fields(array('type_title', 'type_slug', 'status', 'is_default', 'updated'));
		
		//Required fields
		$crud->required_fields(array('type_title', 'type_slug', 'status'));
		//Unique fields
		$crud->unique_fields('type_title', 'type_slug');		
		//Hidden fields
		$crud->field_type('type_id', 'hidden', $param); //Add hidden field
		$crud->field_type('created', 'hidden', date('Y-m-d H:i:s')); //Add hidden field
		$crud->field_type('updated', 'hidden', date('Y-m-d H:i:s')); //Add hidden field
		//Custom action links
		if($param != 14)
		{
		$crud->add_action(get_languageword('Edit_Values'), URL_FRONT_IMAGES.'edit.png', 'settings/fieldsvalues','ui-icon-plus');
		$crud->add_action(get_languageword('View_Fields'), URL_FRONT_IMAGES.'view_fields.png', 'settings/fields','ui-icon-plus');
		}
		else
		{
			$crud->add_action(get_languageword('Make_Default'), URL_FRONT_IMAGES.'star.png', '','',array($this,'makedefault_link'));
			$crud->add_action(get_languageword('Edit_Values'), URL_FRONT_IMAGES.'edit.png', '','', array($this, 'callback_fieldsvalues'));
			$crud->add_action(get_languageword('View_Fields'), URL_FRONT_IMAGES.'view_fields.png', '','', array($this, 'callback_fields'));
		}
		
		$this->data['activemenu'] = 'settings';
		$this->data['activesubmenu'] = 'settings';
		$this->data['pagetitle'] = get_languageword('locations');
		if($crud_state != 'list')
		{
			if($crud_state == 'add')
			$this->data['activesubmenu'] = 'settings';
			$this->data['pagetitle'] = get_languageword($crud_state).' '.get_languageword('settings');
			$this->data['maintitle'] = get_languageword('settings');
			$this->data['maintitle_link'] = base_url().'settings/subtypes/'.$param;
		}
		else
		{
			$this->data['activesubmenu'] = 'settings';
			$this->data['pagetitle'] = get_languageword('settings');
			$this->data['maintitle'] = get_languageword('settings');
			$this->data['maintitle_link'] = base_url().'settings/index';
		}
		
		$output = $crud->render();		
		$this->data['activemenu'] = 'settings';
		$this->data['grocery_output'] = $output;
		$this->data['grocery'] = TRUE;
		$this->grocery_output($this->data);		
	}
	
	function callback_fieldsvalues($primary_key , $row)
	{
		if(in_array($row->type_id, array(36, 39))) //Webmail and Mandril
		{
			return site_url('settings/fieldsvalues').'/'.$row->type_id;
		}
		else
		{
			return false;
		}
	}
	
	function callback_fields($primary_key , $row)
	{
		if(in_array($row->type_id, array(36, 39))) //Webmail and Mandril
		{
			return site_url('settings/fields').'/'.$row->type_id;
		}
		else
		{
			return false;
		}
	}
	
	/**
	* This will display fields
	* @param	Int $param
	* @return	void
	*/
	function fields($param)
	{	
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			$this->prepare_flashmessage(get_languageword('Please login to access this area'));
			redirect('auth/login');
		}
		
		if(empty($param))
		{
			$this->prepare_flashmessage(get_languageword('Please select a type'));
			redirect('locations/index');
		}
		
		$type_name = $this->base_model->fetch_records_from(TBL_SETTINGS_TYPES, array('type_id' => $param));
		if(empty($type_name))
		{
			$this->prepare_flashmessage(get_languageword('invalid location'));
			redirect('settings/index');
		}
		$type_name = $type_name[0]->type_title;
		
		$crud = new grocery_CRUD();
		$crud_state = $crud->getState();
		$crud->unset_jquery(); //As we are using admin lte we need to unset default jQuery
		$crud->set_table($this->db->dbprefix(TBL_SETTINGS_FIELDS));
		$crud->where('type_id', $param);
		$crud->set_subject(get_languageword('Fields for ').$type_name);
		$crud->columns('field_id', 'field_key', 'field_name');
		
		$crud->add_fields(array('type_id', 'field_name','field_key', 'is_required', 'field_order', 'field_type', 'field_type_values', 'field_output_value', 'field_type_slug', 'created'));
		$crud->edit_fields(array('type_id', 'field_name','field_key', 'is_required', 'field_order', 'field_type', 'field_type_values', 'field_output_value', 'field_type_slug', 'updated'));
		
		//Required fields
		$crud->required_fields(array('field_name', 'field_key', 'type_id'));
		//Unique fields
		$crud->unique_fields('field_name', 'field_key');		
		//Hidden fields
		$crud->field_type('type_id', 'hidden', $param); //Add hidden field
		$crud->field_type('created', 'hidden', date('Y-m-d H:i:s')); //Add hidden field
		$crud->field_type('updated', 'hidden', date('Y-m-d H:i:s')); //Add hidden field


		$crud->unset_add();
		$crud->unset_delete();

		//Custom action links
		//$crud->add_action('view_values', 'view_fields', 'settings/fieldsvalues','ui-icon-plus');
		//$crud->add_action('view_fields', 'view_fields', 'settings/fieldsvalues','ui-icon-plus');
		
		$this->data['activemenu'] = 'settings';
		$this->data['activesubmenu'] = 'settings';
		$this->data['pagetitle'] = get_languageword('Fields');
		if($crud_state != 'list')
		{
			if($crud_state == 'add')
			$this->data['activesubmenu'] = 'settings';
			$this->data['pagetitle'] = get_languageword($crud_state).' '.get_languageword('settings');
			$this->data['maintitle'] = get_languageword('settings');
			
			$check = $this->base_model->fetch_records_from(TBL_SETTINGS_TYPES, array('parent_id' => $param));
			if(!empty($check))
			$this->data['maintitle_link'] = base_url().'settings/subtypes/'.$param;
			else
			$this->data['maintitle_link'] = base_url().'settings/index';	
		}
		else
		{
			$this->data['activesubmenu'] = 'settings';
			$this->data['pagetitle'] = get_languageword('settings');
			$this->data['maintitle'] = get_languageword('settings');
			$this->data['maintitle_link'] = base_url().'settings/index';
		}
		
		$output = $crud->render();		
		$this->data['activemenu'] = 'settings';
		$this->data['grocery_output'] = $output;
		$this->data['grocery'] = TRUE;
		$this->grocery_output($this->data);		
	}
	
	
	
	function makedefault_link($primary_key , $row)
	{
		return site_url('settings/makedefault').'/'.$row->type_id;
	}
	
	function makedefault($type_id)
	{
		$details = $this->base_model->fetch_records_from_in(TBL_SETTINGS_TYPES, 'type_id', $type_id);
		if(count($details) > 0)
		{
			$this->db->query('UPDATE '.TBL_PREFIX.TBL_SETTINGS_TYPES.' SET is_default="No" WHERE parent_id = '.$details[0]->parent_id);
			
			$this->db->query('UPDATE '.TBL_PREFIX.TBL_SETTINGS_TYPES.' SET is_default="Yes" WHERE type_id = '.$type_id);
			
			$this->prepare_flashmessage(get_languageword('MSG_RECORD_UPDATED'), 0);
		}
		else
		{
			$this->prepare_flashmessage(get_languageword('MSG_RECORD_UPDATED_FAILED'), 1);
		}
		redirect(URL_SETTINGS_SUBTYPES.DS.$details[0]->parent_id);		
	}
	
	function makeactive($type_id)
	{
		$details = $this->base_model->fetch_records_from_in(TBL_SETTINGS_TYPES, 'type_id', $type_id);
		if(count($details) > 0)
		{			
			$this->db->query('UPDATE '.TBL_PREFIX.TBL_SETTINGS_TYPES.' SET status="Active" WHERE type_id = '.$type_id);
			
			$this->prepare_flashmessage(get_languageword('MSG_RECORD_UPDATED'), 0);
		}
		else
		{
			$this->prepare_flashmessage(get_languageword('MSG_RECORD_UPDATED_FAILED'), 1);
		}
		redirect(URL_SETTINGS_SUBTYPES.DS.$details[0]->parent_id);		
	}
	
	function makeinactive($type_id)
	{
		$details = $this->base_model->fetch_records_from_in(TBL_SETTINGS_TYPES, 'type_id', $type_id);
		if(count($details) > 0)
		{
			$this->db->query('UPDATE '.TBL_PREFIX.TBL_SETTINGS_TYPES.' SET status="In-Active" WHERE type_id = '.$type_id);
			
			$this->prepare_flashmessage(get_languageword('MSG_RECORD_UPDATED'), 0);
		}
		else
		{
			$this->prepare_flashmessage(get_languageword('MSG_RECORD_UPDATED_FAILED'), 1);
		}
		redirect(URL_SETTINGS_SUBTYPES.DS.$details[0]->parent_id);		
	}
		
	/**
	 * Displays add or edit category form
	 *
	 * @param	mixed $_POST
	 * @return	void
	 */
	function typeaddedit($id = '')
	{
		$this->isAdmin();
		$this->data['message'] = $this->session->flashdata('message');
		$this->data['id'] = $id;
		$condition = array();		
		$condition['type_id'] = $id;
		
		if( $this->input->post( 'submitbutt' ) )
		{
			$this->load->library('form_validation');
			$this->form_validation->set_rules('type_title','Title','trim|required|callback_checkduplicatetypetitle');
			$this->form_validation->set_rules('parent_id','Type','trim|required');
						
			if($this->form_validation->run()!=false)
			{
				$inputdata['type_title'] = $this->input->post('type_title');
				$inputdata['parent_id'] = $this->input->post('parent_id');
				$inputdata['is_default'] = $this->input->post('is_default');
				
				if($inputdata['is_default'] == 'Yes')
				{
					$this->base_model->update_operation( array('is_default' => 'No'), TBL_SETTINGS_TYPES, array('parent_id' => $inputdata['parent_id']) );
				}
				
				if( $this->input->post('id') == '' )
				{
					$inputdata['created'] = date('Y-m-d H:i:s');					
					$id = $this->base_model->insert_operation( $inputdata, TBL_SETTINGS_TYPES );
					$this->prepare_flashmessage(get_languageword('MSG_RECORD_INSERTED'), 0);					
				}
				else
				{
					$id = $this->input->post('id');
					$inputdata['updated'] = date('Y-m-d H:i:s');
					$where = array();
					$where['type_id'] = $id;					
					$this->base_model->update_operation( $inputdata, TBL_SETTINGS_TYPES, $where );
					$this->prepare_flashmessage(get_languageword('MSG_RECORD_UPDATED'), 0);					
				}
				
				if($this->input->post( 'submitbutt' ) == 'addnew')
				{
					redirect(URL_SETTINGS_TYPEADDEDIT);
				}
				else
				{
					if($this->input->post('parent_id') == 0)
					redirect(URL_SETTINGS_TYPES);
					else
					redirect(URL_SETTINGS_SUBTYPES . DS . $this->input->post('parent_id'));
				}				
			}
			else
			{
				$this->data['message'] = prepare_message(validation_errors(),1);
			}
			
		}
		
		$this->data['types'] = $this->base_model->fetch_records_from(TBL_SETTINGS_TYPES, array('parent_id' => 0));
		$this->data['activemenu'] = 'settings';
		$this->data['activesubmenu'] = 'addsettingtypes';
		$this->data['details'] = $this->base_model->fetch_records_from(TBL_SETTINGS_TYPES, $condition);
		$this->data['content'] = 'typeaddedit';
		$this->_render_page('template/admin/admin-template', $this->data);
	}
	
	/**
	* This will delete the record
	* @param	Int $id
	* @return	void
	*/
	function typedelete()
	{
		$this->isAdmin();
		$id = $this->input->post('id');
		if(!empty($id))
		{
			$ids = explode(',', $id);
			$details = $this->base_model->fetch_records_from_in(TBL_SETTINGS_TYPES, 'type_id', $ids);
			if(count($details) > 0)
			{
				$this->base_model->delete_record(TBL_SETTINGS_TYPES, 'type_id',$ids);	
				echo json_encode(array('status' => 1, 'message' => get_languageword('MSG_SETTING_DELETED'), 'action' => get_languageword('success')));
			}
			else
			{
				echo json_encode(array('status' => 0, 'message' => get_languageword('MSG_DELETE_FAILED'), 'action' => get_languageword('failed')));
			}
		}
		else
		{
			echo json_encode(array('status' => 0, 'message' => get_languageword('MSG_DELETE_FAILED'), 'action' => get_languageword('failed')));	
		}
	}
	
	/**
	 * Duplicate check for type_title
	 *
	 * @param	String	$type_title
	 * @return	Boolean
	 */	
	function checkduplicatetypefield()
	{
		$this->isAdmin();
		$check = $this->base_model->fetch_records_from(TBL_SETTINGS_FIELDS,array('field_name' => $this->input->post('field_name'), 'type_id' => $this->input->post('type_id')));		
		if (count($check) == 0 && $this->input->post('id') == '') {
		  return true;
		} elseif((count($check) >= 1 || count($check) == 0)&& $this->input->post('id') != '') {
			return true;
		}else {
		  $this->form_validation->set_message('checkduplicatetypefield', get_languageword('MSG_DUPLICATE'));
		  return false;
		}
	}
	
	/**
	 * Displays add or edit category form
	 *
	 * @param	mixed $_POST
	 * @return	void
	 */
	function fieldaddedit($id = '')
	{
		$this->isAdmin();
		$this->data['message'] = $this->session->flashdata('message');
		$this->data['id'] = $id;
		$condition = array();		
		$condition['field_id'] = $id;
		
		if( $this->input->post( 'submitbutt' ) )
		{
			$this->load->library('form_validation');
			$this->form_validation->set_rules('field_name','field_name','trim|required|callback_checkduplicatetypefield');
			$this->form_validation->set_rules('field_output_value','Value','trim|required');
			$this->form_validation->set_rules('type_id','Type','trim|required');
			if($this->input->post('field_type') == 'select')
			$this->form_validation->set_rules('field_type_values','Values','trim|required');
						
			if($this->form_validation->run()!=false)
			{
				$inputdata['field_name'] = $this->input->post('field_name');
				$inputdata['field_output_value'] = $this->input->post('field_output_value');
				$parts = explode('_', $this->input->post('type_id'));
				$inputdata['type_id'] = $parts[0];
				
				$inputdata['field_key'] = clean_text($this->input->post('field_name'));
				$inputdata['is_required'] = $this->input->post('is_required');
							
				$inputdata['field_type'] = $this->input->post('field_type');
				$inputdata['field_type_values'] = $this->input->post('field_type_values');
				if($inputdata['field_type'] == 'default_language')
				{
					$str = '';
					$languages = $this->db->list_fields('languagewords');
					if(count($languages) > 0) 
					{
						foreach($languages as $index => $record)
						{
							if($record == 'lang_id' || $record == 'lang_key') continue;
							$str .= $record.',';
						}
					}
					$inputdata['field_type_values'] = substr($str,0,strlen($str)-1);
					$inputdata['field_type'] = 'select';
				}
				if( $this->input->post('id') == '' )
				{
					$inputdata['created'] = date('Y-m-d H:i:s');					
					$id = $this->base_model->insert_operation( $inputdata, TBL_SETTINGS_FIELDS );
					$this->prepare_flashmessage(get_languageword('MSG_RECORD_INSERTED'), 0);					
				}
				else
				{
					$id = $this->input->post('id');
					$inputdata['updated'] = date('Y-m-d H:i:s');
					$where = array();
					$where['field_id'] = $id;					
					$this->base_model->update_operation( $inputdata, TBL_SETTINGS_FIELDS, $where );
					$this->prepare_flashmessage(get_languageword('MSG_RECORD_UPDATED'), 0);					
				}
				
				if($this->input->post( 'submitbutt' ) == 'addnew')
				{
					redirect( URL_SETTINGS_FIELDADDEDIT );
				}
				else
				{
					if($parts[1] == 0)
					redirect( URL_SETTINGS_FIELDS );
					else
					redirect(URL_SETTINGS_SUBTYPES . '/' . $parts[1]);
				}				
			}
			else
			{
				$this->data['message'] = prepare_message(validation_errors(),1);
			}
			
		}	
		$this->data['types'] = $this->settings_model->get_types();
		$this->data['activemenu'] = 'settings';
		$this->data['activesubmenu'] = 'addsettingfields';
		
		$this->data['details'] = $this->base_model->fetch_records_from(TBL_SETTINGS_FIELDS, $condition);
		$this->data['content'] = 'fieldaddedit';
		$this->_render_page('template/admin/admin-template', $this->data);
	}

}
?>