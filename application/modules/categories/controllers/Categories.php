<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Categories extends MY_Controller 
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
		$crud->set_table($this->db->dbprefix('categories'));
		$crud->where('is_parent',1);
		$crud->set_subject(get_languageword('category'));
		$crud->columns('name','code', 'slug', 'is_popular', 'status');
		$crud->add_fields(array('name', 'slug', 'description', 'code', 'sort_order','is_popular', 'status', 'is_parent'));
		$crud->edit_fields(array('name', 'slug', 'description', 'code', 'sort_order','is_popular', 'status', 'is_parent'));
		$crud->required_fields(array('name', 'slug', 'code', 'sort_order', 'status'));
		$crud->unique_fields('name', 'code');
		$crud->set_field_upload('image','assets/uploads/categories');
		
		//Field Types
		$crud->field_type('is_popular', 'dropdown', array('1' => get_languageword('yes'), '0' => get_languageword('no')));
		$crud->field_type('is_parent', 'hidden', 1); //1-category, 0-course
		
		//Rules
		$crud->set_rules('sort_order',get_languageword('sort_order'),'trim|required|integer');

		$crud->order_by('id','desc');

		$crud->callback_before_insert(array($this,'callback_cat_before_insert'));
		$crud->callback_before_update(array($this,'callback_cat_before_update'));

		$output = $crud->render();
		
		if($crud_state == 'read')
			$crud_state ='View';

		$this->data['activemenu'] = 'categories';
		
		$this->data['activesubmenu'] = 'categories';
		if($crud_state != 'list')
		{
			if($crud_state == 'add')
			$this->data['activesubmenu'] = 'categories-add';
			$this->data['pagetitle'] = get_languageword($crud_state).' '.get_languageword('category');
			$this->data['maintitle'] = get_languageword('categories');
			$this->data['maintitle_link'] = URL_CATEGORIES_INDEX;
		}
		else
		{
			$this->data['activesubmenu'] = 'categories';
			$this->data['pagetitle'] = get_languageword('categories');
		}
		
		$this->data['grocery_output'] = $output;
		$this->data['grocery'] = TRUE;
		$this->grocery_output($this->data);
	}


	function callback_cat_before_insert($post_array) {

		$post_array['slug'] = prepare_slug($post_array['slug'], 'slug', 'categories');

		return $post_array;
	}

	function callback_cat_before_update($post_array, $primary_key) {

		$prev_name = $this->base_model->fetch_value('categories', 'slug', array('id' => $primary_key));

		//If updates the name
		if($prev_name != $post_array['slug']) {
			$post_array['slug'] = prepare_slug($post_array['slug'], 'slug', 'categories');
		}
		return $post_array;
	}


	/** Displays the Index Page**/
	function courses()
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			$this->prepare_flashmessage(get_languageword('Please login to access this area'));
			redirect('auth/login');
		}
				
		$crud = new grocery_CRUD();
		$crud_state = $crud->getState();
		
		$crud->unset_jquery(); //As we are using admin lte we need to unset default jQuery
		$crud->set_table($this->db->dbprefix('categories'));
		$crud->where('is_parent',0);
		$crud->set_subject(get_languageword('course'));
		$crud->columns('name','code', 'slug', 'is_popular', 'status');
		$crud->add_fields(array('categories','name', 'slug', 'description', 'code', 'sort_order', 'image', 'is_popular', 'status', 'is_parent'));
		$crud->edit_fields(array('categories','name', 'slug', 'description', 'code', 'sort_order', 'image', 'is_popular', 'status', 'is_parent'));
		
		$crud->required_fields(array('name', 'slug', 'code', 'sort_order', 'status'));
		$crud->unique_fields('name', 'code');
		$crud->set_field_upload('image','assets/uploads/courses');
		
		//Field Types
		/* This is not working as expected. We need to work on it*/
		$crud->field_type('is_parent', 'hidden', '0'); //1-category, 0-course
		
		$crud->field_type('is_popular', 'dropdown', array('1' => get_languageword('yes'), '0' => get_languageword('no')));
		
		$categories = $this->base_model->fetch_records_from('categories', array('is_parent' => 1, 'status' => 1));
		$categories_arr = array('' => get_languageword('no_categories_available'));
		if(!empty($categories))
		{
			foreach($categories as $cat)
			{
				$categories_arr[$cat->id] = $cat->name;
			}
		}
		$crud->field_type('categories', 'multiselect', $categories_arr);		
		
		//Rules
		$crud->set_rules('sort_order',get_languageword('sort_order'),'trim|required|integer');
		$crud->order_by('id','desc');
		$crud->callback_insert(array($this,'course_insert_callback'));
		$crud->callback_update(array($this,'course_update_callback'));
		$output = $crud->render();
		
		$this->data['activemenu'] = 'categories';		
		$this->data['activesubmenu'] = 'courses';		

		if($crud_state == 'read')
			$crud_state ='View';

		if($crud_state != 'list')
		{
			if($crud_state == 'add')
			$this->data['activesubmenu'] = 'courses-add';
			$this->data['pagetitle'] = get_languageword($crud_state).' '.get_languageword('course');
			$this->data['maintitle'] = get_languageword('courses');
			$this->data['maintitle_link'] = URL_CATEGORIES_INDEX;
		}
		else
		{
			$this->data['activesubmenu'] = 'courses';
			$this->data['pagetitle'] = get_languageword('courses');
		}
		
		$this->data['grocery_output'] = $output;
		$this->data['grocery'] = TRUE;
		$this->grocery_output($this->data);
	}
	
	function course_insert_callback( $post_array )
	{
		$data = array(
			'is_parent' => 0,
			'name' => $post_array['name'],
			'description' => $post_array['description'],
			'code'	=> $post_array['code'],
			'image' => $post_array['image'],
			'is_popular' => $post_array['is_popular'],
			'slug' => prepare_slug($post_array['slug'], 'slug', 'categories'),
			'status' => $post_array['status'],
			'sort_order' => $post_array['sort_order'],
			'created_at' => date('Y-m-d H:i:s'),
			'categories' => implode(',', $post_array['categories']),
		);
		$this->db->insert('categories', $data);
		$insert_id = $this->db->insert_id();
		$this->base_model->delete_record_new($this->db->dbprefix('course_categories'), array('course_id' => $insert_id));
		$categories = $post_array['categories'];
		if(!empty($categories))
		{
			$cats_courses = array();
			foreach($categories as $cat)
			{
				$cats_courses[] = array('course_id' => $insert_id, 'category_id' => $cat);
			}
			if(!empty($cats_courses))
			{
				$this->db->insert_batch('course_categories', $cats_courses);
			}
		}
		return TRUE;
	}
	
	function course_update_callback( $post_array, $primary_key )
	{

		$data = array(
			'is_parent' => 0,
			'name' => $post_array['name'],
			'description' => $post_array['description'],
			'code'	=> $post_array['code'],
			'image' => $post_array['image'],
			'is_popular' => $post_array['is_popular'],
			'status' => $post_array['status'],
			'sort_order' => $post_array['sort_order'],
			'updated_at' => date('Y-m-d H:i:s'),
			'categories' => implode(',', $post_array['categories']),
		);

		$prev_name = $this->base_model->fetch_value('categories', 'slug', array('id' => $primary_key));

		//If updates the name
		if($prev_name != $post_array['slug']) {
			$data['slug'] = prepare_slug($post_array['slug'], 'slug', 'categories');
		}


		$this->db->update('categories',$data,array('id' => $primary_key));
		
		$this->base_model->delete_record_new($this->db->dbprefix('course_categories'), array('course_id' => $primary_key));
		$categories = $post_array['categories'];
		if(!empty($categories))
		{
			$cats_courses = array();
			foreach($categories as $cat)
			{
				$cats_courses[] = array('course_id' => $primary_key, 'category_id' => $cat);
			}
			if(!empty($cats_courses))
			{
				$this->db->insert_batch('course_categories', $cats_courses);
			}
		}
		return TRUE;
	}
}