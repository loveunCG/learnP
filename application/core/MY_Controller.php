<?php ob_start();
class MY_Controller extends CI_Controller
{
	/**
	 * MY Controller
	 *
	 * @author		Digi Samaritan  Team
	 * @link		http://www.digisamaritan.com
	 * @email		digisamaritan@gmail.com
	 * @since		Version 1.0.0
	 */
	protected $data;
	public $perpage;
	public $is_demo;
	
	
	function __construct()
	{
		parent::__construct();		
		$this->data['message'] = '';
		$this->perpage = 3;
		$this->data['showstatistics'] = FALSE;
		
		$this->data['current_module'] = $this->router->fetch_module();
		$this->data['current_controller'] = $this->router->fetch_class();
		$this->data['current_method'] = $this->router->fetch_method();
		$this->data['grocery_output'] = '';


		$this->load->library('ion_auth');
		setlocale(LC_MONETARY, "en_".strtoupper($this->config->item('site_settings')->country_code)); //'en_US'
		date_default_timezone_set($this->config->item('site_settings')->time_zone);


		/*** CRUD Operation Manipulation - Start ***/
		$this->is_demo = 0; //Set it to 0 for actual version, set to 1 for demo version.

		if($this->is_demo) {

			$redirect_path = $this->data['current_controller'].'/'.$this->data['current_method'];

			$allowed_urls = array('auth/login', 'home/search_tutor', 'home/search_institute', 'home/search_student_leads', 'institute/enrolled_students', 'tutor/my_batches');

			$restricted_urls = array('institute/approve_batch_students', 'institute/send_credits_conversion_request', 'tutor/initiate_batch_session', 'tutor/complete_batch_session');

			if($this->input->post()) {

				if(!($this->input->is_ajax_request() || in_array($redirect_path, $allowed_urls))) {

					$this->prepare_flashmessage(get_languageword("CRUD_opeartions_disabled_in_Demo_version"), 2);

					if($this->data['current_method'] == "book_tutor")
						redirect(URL_HOME_SEARCH_TUTOR);
					if($this->data['current_method'] == "enroll_in_institute")
						redirect(URL_HOME_SEARCH_INSTITUTE);

					if($this->uri->segment(2) > 0)
						$redirect_path .= "/".$this->uri->segment(2);
					if($this->uri->segment(3))
						$redirect_path .= "/".$this->uri->segment(3);

					redirect($redirect_path);

				} else if($this->input->is_ajax_request() && (in_array($this->uri->segment(3), array('insert', 'insert_validaiton', 'update', 'update_validation', 'delete')) || in_array($this->uri->segment(4), array('insert', 'insert_validaiton', 'update', 'update_validation', 'delete')) || in_array($this->uri->segment(5), array('insert', 'insert_validaiton', 'update', 'update_validation', 'delete')))) {

					return false;
				}

			} else if($this->uri->segment(3) == "delete" || $this->uri->segment(4) == "delete" || $this->uri->segment(5) == "delete") {

				if($this->input->is_ajax_request())
					return false;
				else {

					$this->prepare_flashmessage(get_languageword("CRUD_opeartions_disabled_in_Demo_version"), 2);
					redirect($redirect_path);
				}

			} else if(in_array($redirect_path, $restricted_urls)) {

				$this->prepare_flashmessage(get_languageword("CRUD_opeartions_disabled_in_Demo_version"), 2);

				if($this->data['current_method'] == "approve_batch_students")
					redirect(URL_INSTITUTE_ENROLLED_STUDENTS);
				if($this->data['current_method'] == "send_credits_conversion_request")
					redirect(URL_INSTITUTE_ENROLLED_STUDENTS);
				if($this->data['current_method'] == "initiate_batch_session")
					redirect(URL_TUTOR_MY_BATCHES);
				if($this->data['current_method'] == "complete_batch_session")
					redirect(URL_TUTOR_MY_BATCHES);

				redirect($redirect_path);
			}
		}
		/*** CRUD Operation Manipulation - End ***/


	}

	function create_thumbnail($sourceimage,$newpath, $width, $height)
	{
		
		$this->load->library('image_lib');
		$this->image_lib->clear();
		
		$config['image_library'] = 'gd2';
		$config['source_image'] = $sourceimage;
		$config['create_thumb'] = TRUE;
		$config['new_image'] = $newpath;
		$config['dynamic_output'] = FALSE;
		$config['maintain_ratio'] = TRUE;
		$config['width'] = $width;
		$config['height'] = $height;
	    $config['thumb_marker'] = '';
	
		$this->image_lib->initialize($config); 
		return $this->image_lib->resize();
	}
	
	/**
	 * Displays the specified view
	 * @param array $data
	 */
	function _render_page($view, $data=null, $returnhtml=false)
	{

		$this->viewdata = (empty($data)) ? $this->data: $data;

		$view_html = $this->load->view($view, $this->viewdata, $returnhtml);

		if ($returnhtml) return $view_html;//This will return html on 3rd argument being true
	}
	
	
	/**
	 * Prepare flash message
	 *
	 */
	function prepare_flashmessage($msg,$type = 2)
	{
		$returnmsg='';
		switch($type){
			case 0: $returnmsg = " <!-- <div class='col-md-12'> -->
										<div class='alert alert-success'>
											<a href='#' class='close' data-dismiss='alert'>&times;</a>
											<strong>".get_languageword('success')."!</strong> ". $msg."
										</div>
									<!-- </div> -->";
				break;
			case 1: $returnmsg = " <!-- <div class='col-md-12'> -->
										<div class='alert alert-danger'>
											<a href='#' class='close' data-dismiss='alert'>&times;</a>
											<strong>".get_languageword('error')."!</strong> ". $msg."
										</div>
									<!-- </div> -->";
				break;
			case 2: $returnmsg = " <!-- <div class='col-md-12'> -->
										<div class='alert alert-info'>
											<a href='#' class='close' data-dismiss='alert'>&times;</a>
											<strong>".get_languageword('info')."!</strong> ". $msg."
										</div>
									<!-- </div> -->";
				break;
			case 3: $returnmsg = " <!-- <div class='col-md-12'> -->
										<div class='alert alert-warning'>
											<a href='#' class='close' data-dismiss='alert'>&times;</a>
											<strong>".get_languageword('warning')."!</strong> ". $msg."
										</div>
									<!-- </div> -->";
				break;
		}
		
		$this->session->set_flashdata("message",$returnmsg);
	}
	
	function prepare_message($msg,$type = 2)
	{
		$returnmsg='';
		switch($type){
			case 0: $returnmsg = " <!-- <div class='col-md-12'> -->
										<div class='alert alert-success'>
											<a href='#' class='close' data-dismiss='alert'>&times;</a>
											<strong>".get_languageword('success')."!</strong> ". $msg."
										</div>
									<!-- </div> -->";
				break;
			case 1: $returnmsg = " <!-- <div class='col-md-12'> -->
										<div class='alert alert-danger'>
											<a href='#' class='close' data-dismiss='alert'>&times;</a>
											<strong>".get_languageword('error')."!</strong> ". $msg."
										</div>
									<!-- </div> -->";
				break;
			case 2: $returnmsg = " <!-- <div class='col-md-12'> -->
										<div class='alert alert-info'>
											<a href='#' class='close' data-dismiss='alert'>&times;</a>
											<strong>".get_languageword('info')."!</strong> ". $msg."
										</div>
									<!-- </div> -->";
				break;
			case 3: $returnmsg = " <!-- <div class='col-md-12'> -->
										<div class='alert alert-warning'>
											<a href='#' class='close' data-dismiss='alert'>&times;</a>
											<strong>".get_languageword('warning')."!</strong> ". $msg."
										</div>
									<!-- </div> -->";
				break;
		}
		
		return $returnmsg;
	}
	
	function set_pagination($url,$offset,$numrows,$perpage,$pagingfunction='')
	{
		$config['base_url'] = SITEURL.$url;  //Setting Pagination parameters
		$config['per_page'] = $perpage;
		$config['offset'] = $offset;
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$config['num_links'] = 4; // numlinks before and after current page
		$config['total_rows'] =  $numrows;
		
		$config['first_link'] = 'First';
		$config['last_link'] = 'Last';
		if(!empty($pagingfunction))
			$config['paging_function'] = $pagingfunction; 
		else	$config['paging_function'] = 'ajax_paging';
		$this->pagination->initialize($config);  
	}
	
	/**
	 * Validate URL
	 *
	 * @access    public
	 * @param    string
	 * @return    string
	 */
	function valid_url($url)
	{
		$pattern = "|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i";
		if (!preg_match($pattern, $url))
		{
			return FALSE;
		}

		return TRUE;
	}
	
	/**
	 * Generates Random String
	 *
	 * @access    public
	 * @param    integer
	 * @return    string
	 */
	function randomString($length = 6){
    $chars =  'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'.
              '0123456789';

    $str = '';
    $max = strlen($chars) - 1;

    for ($i=0; $i < $length; $i++)
      $str .= $chars[rand(0, $max)];

    return $str;
  }
  
  function generateAverageRatings($records, $type)
{
	//print_r($records);
	$this->load->library( 'jquery_stars' );
	$ratings = array();
	foreach($records as $row)
	{
		$stars_array = array();
		for($i = 1; $i <= 5; $i++)
		{
			$text = '';
			switch($i)
			{
				case '1':
					$text = 'Very poor';
				break;
				case '2':
					$text = 'Not that bad';
				break;
				case '3':
					$text = 'Average';
				break;
				case '4':
					$text = 'Good';
				break;
				case '5':
					$text = 'Perfect';
				break;
			}
			if($row->rating == $i)
			$stars_array[] = array('value' => $i, 'text' => $text, 'selected' => TRUE );
			else
			$stars_array[] = array('value' => $i, 'text' => $text );
		}
		$config = array('id'=>'mystar-' .$type . '_' . $row->id ,
				'label'=>'Overall Rating',
				'disabled'=>TRUE,
				'enable_caption'=>FALSE,
				'enable_label'=>FALSE,
				'one_vote'=>TRUE,
				'stars'=>$stars_array,
				);
		$this->jquery_stars->set_place_holder_id('stars-wrapper-'.$type . '_' . $row->id);
		$this->jquery_stars->init_stars($config); //yey! where finished!
		$ratings[$row->id] = $this->jquery_stars->get_stars();
	}
	return $ratings;
}

function generateRatingStar($id, $average = 3)
{
	$stars_array = array();
	$this->load->library( 'jquery_stars' );
	for($i = 1; $i <= 5; $i++)
	{
		$text = '';
		switch($i)
		{
			case '1':
				$text = 'Very poor';
			break;
			case '2':
				$text = 'Not that bad';
			break;
			case '3':
				$text = 'Average';
			break;
			case '4':
				$text = 'Good';
			break;
			case '5':
				$text = 'Perfect';
			break;
		}
		if($average == $i)
		$stars_array[] = array('value' => $i, 'text' => $text, 'selected' => TRUE );
		else
		$stars_array[] = array('value' => $i, 'text' => $text );
	}
	$config = array('id'=>'mystar-' . $id,
			'label'=>'Overall Rating',
			'disabled'=>FALSE,
			'enable_caption'=>FALSE,
			'enable_label'=>FALSE,
			'one_vote'=>TRUE,
			'stars'=>$stars_array,
			);
	$this->jquery_stars->set_place_holder_id('stars-wrapper-'.$id);
	$this->jquery_stars->init_stars($config); //yey! where finished!
	$ratings = $this->jquery_stars->get_stars();
	return $ratings;
}

	public function grocery_output($output = null)
	{
		if($this->ion_auth->is_admin())
		$this->load->view('template/admin/admin-template-grocery',$output);
		elseif($this->ion_auth->is_tutor())
		$this->load->view('template/site/tutor-template-grocery',$output);
		elseif($this->ion_auth->is_student())
		$this->load->view('template/site/student-template-grocery',$output);
		elseif($this->ion_auth->is_institute())
		$this->load->view('template/site/institute-template-grocery',$output);
	}
	
	function safe_redirect($url = '', $message = '', $set_default_msg = TRUE)
	{
		if(!$this->ion_auth->logged_in())
		{
			if($message == '')
			{
				if($set_default_msg)
				$this->prepare_flashmessage(get_languageword('Please login to access this area'));
			}			
			else
			{
			$this->prepare_flashmessage($message);	
			}
			if($url != '')
			{					
				redirect($url);
			}
			else
			{
			redirect('auth/login');
			}
		}
		else if($this->ion_auth->is_tutor())
		{
			if($message == '')
			{
				if($set_default_msg)
				$this->prepare_flashmessage(get_languageword('Welcome back'));
			}
			else
			$this->prepare_flashmessage($message);
			if($url != '')
			{
				redirect($url);
			}
			else
			{
			redirect('tutor/index');
			}
		}
		else if($this->ion_auth->is_student())
		{
			if($message == '')
			{
				if($set_default_msg)
				$this->prepare_flashmessage(get_languageword('Welcome back'));
			}
			else
			{
			$this->prepare_flashmessage($message);
			}
			if($url != '')
			{
				redirect($url);
			}
			else
			{
			redirect('student/index');
			}
		}
		else if($this->ion_auth->is_institute())
		{
			if($message == '')
			{
				if($set_default_msg)
				$this->prepare_flashmessage(get_languageword('Welcome back'));
			}
			else
			$this->prepare_flashmessage($message);
			if($url != '')
			{
				redirect($url);
			}
			else
			{
			redirect('institute/index');
			}
		}
		else if($this->ion_auth->is_admin())
		{
			if($message == '')
			{
				if($set_default_msg)
				$this->prepare_flashmessage(get_languageword('Welcome back'));
			}
			else
			$this->prepare_flashmessage($message);
			if($url != '')
			{
				redirect($url);
			}
			else
			{
			redirect('admin/index');
			}
		}
	}
	
	function check_tutor_access()
	{
		if (!$this->ion_auth->logged_in() || !($this->ion_auth->is_tutor() || (($this->data['current_method'] == "list_selling_courses" || $this->data['current_method'] == "view_selling_course_curriculum") && $this->ion_auth->is_admin()))) {
			$this->prepare_flashmessage(get_languageword('You dont have permission to access this page'), 1);
			redirect('auth/login', 'refresh');
		}
		else if(isset($this->config->item('site_settings')->need_admin_for_tutor) && $this->config->item('site_settings')->need_admin_for_tutor == 'Yes')
		{
			if(!is_uploaded_certificates($this->ion_auth->get_user_id())) {

				$this->prepare_flashmessage(get_languageword('Please upload following certificates to proceed'), 1);
				if($this->data['current_controller'] == 'tutor')
				{
					if($this->data['current_method'] != 'certificates')
						redirect('tutor/certificates', 'refresh');
				}
			}			

		}
	}


	function check_inst_access()
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_institute()) {
			$this->prepare_flashmessage(get_languageword('You dont have permission to access this page'), 1);
			redirect('auth/login', 'refresh');
		}
		else if(isset($this->config->item('site_settings')->need_admin_for_inst) && $this->config->item('site_settings')->need_admin_for_inst == 'Yes')
		{
			if(!is_uploaded_certificates($this->ion_auth->get_user_id())) {

				$this->prepare_flashmessage(get_languageword('Please upload following certificates to proceed'), 1);
				if($this->data['current_controller'] == 'institute')
				{
					if($this->data['current_method'] != 'certificates')
						redirect('institute/certificates', 'refresh');
				}
			}			

		}
	}
	
	function get_safe_template() {
		if ( $this->ion_auth->is_student() ) {
			$this->_render_page('template/site/student-template', $this->data);
		} elseif( $this->ion_auth->is_tutor() ) {
			$this->_render_page('template/site/tutor-template', $this->data);
		} elseif( $this->ion_auth->is_institute() ) {
			$this->_render_page('template/site/institute-template', $this->data);
		} else {
			$this->_render_page('template/admin/admin-template', $this->data);
		}
	}
	
	/* Check Duplicate Value */
	function is_duplicate($table_name = '', $condition = '')
	{
		if(empty($table_name) || empty($condition)) {
			return TRUE;
		}
		$check_duplicate = $this->base_model->fetch_records_from($table_name, $condition);
		if(count($check_duplicate) == 0) {
			return FALSE;
		}
		return TRUE;
	}



}
?>