<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Import extends MY_Controller 
{
	function __construct()
	{
		parent::__construct();
		if( ! $this->ion_auth->logged_in() ) {
			$this->prepare_flashmessage(get_languageword('You dont have permission to access this page'), 1);
			redirect('auth/login', 'refresh');
		}
		$group = array('admin', 'tutor', 'student','institute');
		if ( ! $this->ion_auth->in_group( $group ) ) {
			$this->prepare_flashmessage(get_languageword('MSG_NO_ENTRY'),2);
			redirect(getUserType());
		}
		
		$this->data['my_profile'] = getUserRec();
	}
	/**
	 * Generate the index page
	 *
	 * @access	public
	 * @return	string
	 */
	function index( $type, $menu = '' )
	{
		if ( empty( $type ) ) {			
			safe_redirect(get_safe_url(), get_languageword('Please select the type of file you want to import file to import'), 'error');
		}
		$this->data['message'] = $this->session->flashdata('message');

		if( isset( $_POST['submitbutt'] ) ) {
			$this->load->library('form_validation');
			$imported = 0;
			if (empty($_FILES['import_file']['name']))
			{
				$this->form_validation->set_rules('import_file', 'File to import', 'required|callback_checkfiletype');
			}
			else {
				$this->form_validation->set_rules('import_file', 'File to import', 'callback_checkfiletype');
			}
			$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
			if($this->form_validation->run()!=false)
			{
				$type = $this->input->post('file_type');
				$imported = 0;
				$url = get_safe_url();
				if( $type == 'users' && $this->ion_auth->is_admin() ) {
					$url = 'auth/index';
				} elseif( $type == 'categories' && $this->ion_auth->is_admin() ) {
					$url = 'categories/index';
				} elseif( $type == 'courses' && $this->ion_auth->is_admin() ) {
					$url = 'categories/courses';
				} elseif( $type == 'degree' && $this->ion_auth->is_admin() ) {
					$url = 'options/index';
				} elseif( $type == 'locations' && $this->ion_auth->is_admin() ) {
					$url = 'locations/index';
				} elseif( $type == 'packages' && $this->ion_auth->is_admin() ) {
					$url = 'packages/index';
				} elseif( $type == 'certificates' && $this->ion_auth->is_admin() ) {
					$url = 'certificates/index';
				}
				
				$this->load->library('upload');
				foreach ($_FILES as $key => $value)
				{
					if (!empty($value['name']) && $value['error'] != 4)
					{
						$config['upload_path'] =  FCPATH . '/assets/temp/';
						$config['allowed_types'] = '*';
						//$config['file_name']	= $_FILES[$key]['name'];
						$config['file_name']	= date('YmdHis').'_'.$_FILES[$key]['name'];
						$config['overwrite'] = TRUE;
						$this->upload->initialize($config);
						if( ! $this->upload->do_upload($key))
						{							
							safe_redirect( $url, get_languageword('Error to upload file.' . $this->upload->display_errors()), 'error');
						} else {
							$this->load->library('csvreader');
							$result =   $this->csvreader->parse_file( FCPATH . 'assets/temp/'.$config['file_name']);
							
							if( $type == 'users' ) {
								foreach($result as $row)
								{
									$email 		= strtolower($row['email']);
									$password 	= (isset($row['password']) && $row['password'] != '') ? $row['password'] : rand();
									$first_name = ucfirst(strtolower($row['first_name']));
									$last_name = ucfirst(strtolower($row['last_name']));
									$username =  $first_name.' '.$last_name;

									$slug = prepare_slug($username, 'slug', 'users');

									$code_country = explode('_', $post_array['phone_code']);
																	
									if( $this->is_duplicate('users', array( 'email' => $email )) == TRUE ) {
										
									} else {
										$inputdata = array();
										$inputdata['username'] = $username;
										$inputdata['slug'] = $slug;										
										$inputdata['first_name'] = isset($row['first_name']) ? $row['first_name'] : '';
										$inputdata['last_name'] = isset($row['last_name']) ? $row['last_name'] : '';
										$inputdata['gender'] = isset($row['gender']) ? $row['gender'] : '';
										$inputdata['country'] = isset($row['country']) ? $row['country'] : '';
										$inputdata['phone_code'] = isset($row['phone_code']) ? $row['phone_code'] : '';
										$inputdata['phone'] = isset($row['phone']) ? $row['phone'] : '';
										$inputdata['active'] = 1;
										$group = isset($row['group']) ? $row['group'] : '2';
										$groups = array('id' => ( in_array($group, array(1,2,3,4)) ) ? $group : 2 );			
										$this->ion_auth->register($username, $password, $email, $inputdata, $groups);	
										$imported++;
									}
								}
							} elseif( $type == 'categories' ) {
								foreach($result as $row)
								{
									$name 		= (isset($row['name']) && $row['name'] != '') ? $row['name'] : '';
									$code 	= (isset($row['code']) && $row['code'] != '') ? $row['code'] : '';
									$slug = prepare_slug($name, 'slug', 'categories');							
																	
									if( ( $this->is_duplicate('categories', array( 'name' => $name )) == TRUE ) || ( $this->is_duplicate('categories', array( 'code' => $code )) == TRUE ) ) {
										
									} else {
										$inputdata = array();
										$inputdata['name'] = $name;
										$inputdata['slug'] = $slug;										
										$inputdata['code'] = $code;
										$inputdata['description'] = isset($row['description']) ? $row['description'] : '';
										$inputdata['is_parent'] = 1; // 1 - Category, 0 - Course
										$inputdata['status'] = 1;
										$inputdata['sort_order'] = isset($row['sort_order']) ? $row['sort_order'] : '';
										$inputdata['created_at'] = date('Y-m-d H:i:s');
										$this->base_model->insert_operation($inputdata, 'categories');
										$imported++;
									}
								}
							} elseif( $type == 'courses' ) {
								foreach($result as $row)
								{
									$name 		= (isset($row['name']) && $row['name'] != '') ? $row['name'] : '';
									$code 	= (isset($row['code']) && $row['code'] != '') ? $row['code'] : '';
									$slug = prepare_slug($name, 'slug', 'categories');							
																	
									if( ( $this->is_duplicate('categories', array( 'name' => $name )) == TRUE ) || ( $this->is_duplicate('categories', array( 'code' => $code )) == TRUE ) ) {
										
									} else {
										$inputdata = array();
										$inputdata['name'] = $name;
										$inputdata['slug'] = $slug;										
										$inputdata['code'] = $code;
										$inputdata['description'] = isset($row['description']) ? $row['description'] : '';
										$inputdata['is_parent'] = 0; // 1 - Category, 0 - Course
										$inputdata['status'] = 1;
										$inputdata['sort_order'] = isset($row['sort_order']) ? $row['sort_order'] : '';
										$inputdata['is_popular'] = isset($row['is_popular']) ? $row['is_popular'] : '0';
										$inputdata['created_at'] = date('Y-m-d H:i:s');
										$this->base_model->insert_operation($inputdata, 'categories');
										$imported++;
									}
								}
							} elseif( $type == 'degree' ) {
								foreach($result as $row)
								{
									$name 		= (isset($row['name']) && $row['name'] != '') ? $row['name'] : '';
									$slug = prepare_slug($name, 'term_slug', 'terms_data');							
																	
									if( ( $this->is_duplicate('terms_data', array( 'term_title' => $name )) == TRUE ) ) {
										
									} else {
										$inputdata = array();
										$inputdata['term_title'] = $name;
										$inputdata['term_slug'] = $slug;										
										$inputdata['term_status'] = 'Active';
										$inputdata['term_content'] = isset($row['description']) ? $row['description'] : '';
										$inputdata['term_type'] = 'degree';
										$inputdata['term_created'] = date('Y-m-d H:i:s');
										$this->base_model->insert_operation($inputdata, 'terms_data');
										$imported++;
									}
								}
							} elseif( $type == 'locations' ) {
								foreach($result as $row)
								{
									$name 		= (isset($row['location_name']) && $row['location_name'] != '') ? $row['location_name'] : '';
									$slug = prepare_slug($name, 'location_name', 'locations');			
									$code 		= (isset($row['code']) && $row['code'] != '') ? $row['code'] : $slug;
													
																	
									if( ( $this->is_duplicate('locations', array( 'location_name' => $name )) == TRUE ) || ( $this->is_duplicate('locations', array( 'code' => $name )) == TRUE ) ) {
										
									} else {
										$inputdata = array();
										$inputdata['location_name'] = $name;
										$inputdata['parent_location_id'] = isset($row['parent_location_id']) ? $row['parent_location_id'] : '0';
										$inputdata['code'] = $code;
										$inputdata['slug'] = $slug;
										$inputdata['sort_order'] = (isset($row['sort_order']) && $row['sort_order'] != '') ? $row['sort_order'] : '0';
										$inputdata['created_at'] = date('Y-m-d H:i:s');
										$this->base_model->insert_operation($inputdata, 'locations');
										$imported++;
									}
								}
							} elseif( $type == 'packages' ) {
								foreach($result as $row)
								{
									$name 		= (isset($row['package_name']) && $row['package_name'] != '') ? $row['package_name'] : '';
									$slug = prepare_slug($name, 'package_name', 'packages');
													
																	
									if( ( $this->is_duplicate('packages', array( 'package_name' => $name )) == TRUE ) ) {
										
									} else {
										$inputdata = array();
										$inputdata['package_name'] = $name;
										$inputdata['package_for'] = isset($row['package_for']) ? $row['package_for'] : 'All';
										$inputdata['description'] = isset($row['description']) ? $row['description'] : '';
										
										$inputdata['credits'] = isset($row['credits']) ? $row['credits'] : '0';
										$inputdata['discount_type'] = isset($row['discount_type']) ? $row['discount_type'] : 'Percent';
										$inputdata['discount'] = isset($row['discount']) ? $row['discount'] : '0';
										$inputdata['package_cost'] = isset($row['package_cost']) ? $row['package_cost'] : '10';
										$this->base_model->insert_operation($inputdata, 'packages');
										$imported++;
									}
								}
							} elseif( $type == 'certificates' ) {
								foreach($result as $row)
								{
									$name 		= (isset($row['title']) && $row['title'] != '') ? $row['title'] : '';
																	
									if( ( $this->is_duplicate('certificates', array( 'title' => $name )) == TRUE ) ) {
										
									} else {
										$inputdata = array();
										$inputdata['title'] = $name;										
										$inputdata['description'] = isset($row['description']) ? $row['description'] : '';
										$inputdata['required'] = isset($row['required']) ? $row['required'] : 'No';
										$inputdata['allowed_formats'] = isset($row['allowed_formats']) ? $row['allowed_formats'] : 'jpg,gif,png,jpeg';
										$inputdata['certificate_for'] = isset($row['certificate_for']) ? $row['certificate_for'] : '';											
										$inputdata['created'] = date('Y-m-d H:i:s');
										$this->base_model->insert_operation($inputdata, 'certificates');
										$imported++;
									}
								}
							}							
						}
					}
				}				
				safe_redirect( $url, $imported . ' ' . get_languageword('Records import successful'), 'success');
			}
			$this->data['message'] = $this->prepare_message( validation_errors() );
		}
		$this->data['pagetitle'] 	= get_languageword('file import');
		
		$activemenu = '';
		$activesubmenu = '';
		switch( $type ) {
			case 'users':
				$activemenu = 'users';
				$activesubmenu = 'import';
			break;
			case 'categories':
				$activemenu = 'categories';
				$activesubmenu = 'importcategories';
			break;
			case 'courses':
				$activemenu = 'categories';
				$activesubmenu = 'importcourses';
			break;
			case 'degree':
				$activemenu = 'options';
				$activesubmenu = 'importdegree';
			break;
			case 'locations':
				$activemenu = 'locations';
				$activesubmenu = 'importlocations';
			break;
			case 'packages':
				$activemenu = 'packages';
				$activesubmenu = 'importpackages';
			break;
			case 'certificates':
				$activemenu = 'certificates';
				$activesubmenu = 'importcertificates';
			break;
		}
		$this->data['type'] 	= $type;
		$this->data['activemenu'] 	= $activemenu;
		$this->data['activesubmenu'] = $activesubmenu;
		$this->data['content'] 		= 'index';		
		$this->get_safe_template();
	}
	
	function checkfiletype()
	{
		$file_name = $_FILES['import_file']['name'];
		$name = explode('.',$file_name);
		
		if(count($name)>2 || count($name)<= 0) {
           $this->form_validation->set_message('checkfiletype', 'Only CSV files are accepted.');
            return FALSE;
        }		
		$ext = $name[1];		
		$allowed_types = array('csv','CSV');
		
		if (!in_array($ext, $allowed_types)) {			
			$this->form_validation->set_message('checkfiletype', 'Only CSV files are accepted.');
			return FALSE;
		}
		else {
			return TRUE;
		}
	}
}
?>