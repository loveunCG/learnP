<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Testimonials extends MY_Controller 
{
	function __construct()
	{
		parent::__construct();
		$this->load->library(array('session'));
		$this->load->library(array('ion_auth','form_validation'));
		
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
	function index($param1 = '', $param2 = '')
	{		
		$this->isAdmin();
		$this->data['message'] = $this->session->flashdata('message');		
		$this->data['content'] 		= 'view';
		$this->data['activemenu'] 	= 'testimonials';
		$this->data['activesubmenu']= 'all';
		$this->data['pagetitle'] 	= get_languageword('view_testimonials');
		$this->data['helptext'] 	= array();
		
		$user_group_id = "";
		if ($param1 != '') {
			if (is_numeric($param1)) {
				$user_group_id 					= $param1;
				if ($user_group_id == 2) 
						$this->data['pagetitle'] = get_languageword('studentz');
				elseif ($user_group_id == 3) 
						$this->data['pagetitle'] = get_languageword('tutorz');
			}
		}
		
		$this->data['ajaxrequest'] = array(
			'url' => URL_TESTIMONIALS_AJAX_GET_DATA,
			'disablesorting' => '0,3',
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
			$data = array();
			$no = $_POST['start'];
			$columns = array('rating_value', 'content');
			$condition = array(1=>1);
			$order = array();
			
			$query = 'SELECT t.*, u.username, u.photo FROM '.$this->db->dbprefix('testimonials').' t
INNER JOIN '.$this->db->dbprefix('users').' u ON u.id = t.user_id
INNER JOIN '.$this->db->dbprefix('users_groups').' ug ON ug.user_id = u.id 
WHERE u.active = 1';			
			$records = $this->base_model->get_datatables($query, 'customnew', $condition, $columns, array('t.testimony_id' => 'desc'));
			
			
			foreach($records as $record)
			{
				$no++;
				$row = array();
				$image = '';
				if(isset($record->image) && $record->image != '' && file_exists('assets/uploads/thumbs/'.$record->image))
				{
					$image = URL_PUBLIC_UPLOADS_THUMBS . $record->image;
				}
				$row[] = '<input id="checkbox-'.$record->testimony_id.'" class="checkbox-custom checkbox_class" name="ids[]" type="checkbox" value="'.$record->testimony_id.'" onclick="javascript:deselectall_check(\'selectall\')">
                        <label for="checkbox-'.$record->testimony_id.'" class="checkbox-custom-label"> </label>';
				if(!empty($image))
					$row[] = '<span><img src="'.$image.'" class="img-responsive"/>' . $record->package_name . '</span>';
				else
					$row[] = '<span>' . $record->package_name . '</span>';
				
				$row[] = '<span>'. $record->package_cost . '</span>';
				
				$checked = '';
				if($record->package_status == 'Active')
				$checked = ' checked';
				//add html for action
				$row[] = '<div class="digiCrud">							
					<a data-toggle="modal" data-target="#deletemodal" onclick="delete_record('.$record->testimony_id . ',\''.URL_TESTIMONIALS_DELETE_RECORD.'\')">
						<i class="flaticon-round73" data-toggle="tooltip" data-placement="left" title="Remove"></i>
					</a>
				</div>
				
				<div class="digiCrud">
					<a href="'.URL_TESTIMONIALS_ADDEDIT . '/' . $record->testimony_id . '">
						<i class="flaticon-pencil124" data-toggle="tooltip" data-placement="top" title="Edit"></i>
					</a>
				</div>
				
				<div class="digiCrud">
				  <div class="slideThree slideBlue">
					
					<input type="checkbox" value="' . $record->testimony_id . '" id="status_' . $record->testimony_id . '" name="check_' . $record->testimony_id . '" onclick="statustoggle(this, \'' .  URL_TESTIMONIALS_STATUSTOGGLE .'\')"'.$checked . '/>
					<label for="status_' . $record->testimony_id . '"></label>
				  </div>
				</div>
				';
				$data[] = $row;
			}
			$output = array(
					"draw" => $_POST['draw'],
					"recordsTotal" => $this->base_model->numrows,
					"recordsFiltered" =>$this->base_model->get_datatables($query, 'customnew', $condition, $columns, array('t.testimony_id' => 'desc')),
					"data" => $data,
			);
			echo json_encode($output);	
		}        
	}
	
	/**
	* This will delete the record
	* @param	Int $id
	* @return	void
	*/
	function delete_record()
	{
		$this->isAdmin();
		$id = $this->input->post('id');
		if(!empty($id))
		{			
			$ids = explode(',', $id);
			$details = $this->base_model->fetch_records_from_in(TBL_TESTIMONIALS, 'testimony_id', $ids);
			if(count($details) > 0)
			{
				$this->base_model->delete_record(TBL_TESTIMONIALS, 'testimony_id',$ids);
				$this->load->helper("file");
				foreach($details as $recod)
				{
					if($recod->image != '')
					{
						if(file_exists(URL_PUBLIC_UPLOADS . 'testimonials' . DS . $recod->image))
						{
							unlink(URL_PUBLIC_UPLOADS . 'testimonials' .  DS . $recod->image);
						}
						if(file_exists(URL_PUBLIC_UPLOADS . 'thumbs' .  DS . $recod->image))
						{
							unlink(URL_PUBLIC_UPLOADS . 'thumbs' . DS . $recod->image);
						}
					}
				}
				echo json_encode(array('status' => 1, 'message' => get_languageword('MSG_TESTIMONIALS_DELETED'), 'action' => get_languageword('success')));
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
	/*** Changes the status of the record**/
	function statustoggle()
	{
		$this->isAdmin();
		if($this->input->post())
		{
			$id 			= $this->input->post('id');
			$status = $this->input->post('status');
			$filedata 		= array();
			$message = '';
			if($status == 'false')
			{
				$filedata['status'] = 'In-Active';
				$message = get_languageword('MSG_TESTIMONIALS_DEACTIVATED');
			}
			else
			{
				$filedata['status'] = 'Active';				
				$message = get_languageword('MSG_TESTIMONIALS_ACTIVATED');
			}	
			$this->base_model->update_operation_in( $filedata, TBL_TESTIMONIALS, 'package_id', explode(',', $id) );
			echo json_encode(array('status' => 1, 'message' => $message, 'action' => get_languageword('success')));
		} 
		else
		{
			$message = get_languageword('MSG_WRONG_OPERATION');
			echo json_encode(array('status' => 0, 'message' => $message, 'action' => get_languageword('failed')));			
		}
	}
}