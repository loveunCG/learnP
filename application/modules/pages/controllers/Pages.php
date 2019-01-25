<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Pages extends MY_Controller 
{
	function __construct()
	{
		parent::__construct();
		$this->load->library(array('session'));
		$this->load->library(array('ion_auth','form_validation'));
		
		$this->load->model('pages_model');
		$this->data['statistics'] = $this->pages_model->getPageStatistics();
		
		$group = array('admin');
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
	function viewpages()
	{		
		$this->isAdmin();
		$this->data['message'] = $this->session->flashdata('message');		
		$this->data['content'] 		= 'view';
		$this->data['activemenu'] 	= 'pages';
		$this->data['activesubmenu']= 'view';
		$this->data['pagetitle'] 	= get_languageword('view_pages');
		$this->data['helptext'] 	= array();
		$this->data['ajaxrequest'] = array(
			'url' => URL_PAGE_AJAX_GET_DATA
			//'disablesorting' => '0,3,4',
		);
		$this->_render_page('template/admin/' . getTemplate(), $this->data);
	}
	/**
	 * Diaplays data
	 * @param	mixed	$type
	 * @return	void
	 */
	function ajax_get_data()
	{
		$this->isAdmin();
		if($this->input->post())
		{
			$data 		= array();
			$no 		= $_POST['start'];
			$columns 	= array('id', 'name');
			$condition  = array(1=>1);
			$order = array();
			//$records 	= $this->base_model->fetch_records_from(TBL_PAGES,'','','id','','','');
			$records = $this->base_model->get_datatables(TBL_PAGES, 'auto', $condition, $columns, $order);
			
			foreach($records as $record)
			{
				$no++;
				$row 	= array();
				
				$row[]  = '<span>' . $record->name . '</span>';
				
				$row[]  = '<span>'. substr($record->description,0,100) . '</span>';
				
				//add html for action
				$row[] = '
				<div class="digiCrud">
					<a href="'.URL_PAGE_EDIT . '/' . $record->id . '">
						<i class="flaticon-pencil124" data-toggle="tooltip" data-placement="top" title="Edit"></i>
					</a>
				</div>';
				$data[] = $row;
			}
			$output = array(
					"draw" => $_POST['draw'],
					"recordsTotal" => $this->base_model->count_all(TBL_PAGES),
					"recordsFiltered" =>$this->base_model->count_filtered(TBL_PAGES, 'auto', $condition, $columns, array('id' => 'asc')),
					"data" => $data,
			);
			echo json_encode($output);	
		}        
	}
	
	function checkduplicate()
	{
		$check = $this->base_model->fetch_records_from(TBL_PAGES,array('name' => $this->input->post('name')));		
		if (count($check) == 0 && $this->input->post('id') == '') {
		  return true;
		} elseif((count($check) >= 1 || count($check) == 0)&& $this->input->post('id') != '') {
			return true;
		}else {
		  $this->form_validation->set_message('checkduplicate', get_languageword('MSG_DUPLICATE'));
		  return false;
		}
	}
	/**
	 * Displays edit form
	 * @param mixed $_POST
	 * @return void
	 */
	function edit($id = '')
	{
		$this->isAdmin();
		$this->data['message'] = $this->session->flashdata('message');
		$this->data['id'] = $id;
		$condition = array();		
		$condition['id'] = $id;
		
		if($this->input->post('submitbutt'))
		{
			
			$this->form_validation->set_rules('name',get_languageword('page_name'),'trim|required|callback_checkduplicate');
			
			$this->form_validation->set_rules('description',get_languageword('description'),'required');
			
			$this->form_validation->set_rules('meta_tag',get_languageword('meta_tag'),'xss_clean');
			
			$this->form_validation->set_rules('meta_description',get_languageword('meta_description'),'xss_clean');
			
			$this->form_validation->set_rules('seo_keywords',get_languageword('seo_keywords'),'xss_clean');
			
			
			if($this->form_validation->run()!=false)
			{
				$inputdata['name'] 			= $this->input->post('name');
				$inputdata['description'] 	= $this->input->post('description');	
				$inputdata['meta_tag'] 		= $this->input->post('meta_tag');
				$inputdata['meta_description']= $this->input->post('meta_description');
				$inputdata['seo_keywords'] = $this->input->post('seo_keywords');
				
				$id = $this->input->post('id');
				$where = array();
				$where['id'] = $id;					
				$this->base_model->update_operation( $inputdata, TBL_PAGES, $where );
				$this->prepare_flashmessage(get_languageword('MSG_RECORD_UPDATED'), 0);					
			
				redirect(URL_PAGES);				
			}
			else
			{
				$this->data['message'] = prepare_message(validation_errors(),1);
			}
			
		}		
		$this->data['pagetitle'] = get_languageword('update_page');
		$this->data['activemenu'] = 'pages';
		$this->data['activesubmenu'] = 'update';
		$this->data['details'] = $this->base_model->fetch_records_from(TBL_PAGES, $condition);
		$this->data['content'] = 'edit';
		$this->_render_page('template/admin/' . getTemplate(), $this->data);
	}
	function index()
	{
		redirect(URL_PAGES);
	}
}