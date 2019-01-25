<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Home extends MY_Controller 
{
	function __construct()
	{
		parent::__construct();

		$this->load->model(array('base_model', 'home_model'));
		$this->load->library('Ajax_pagination');
		$this->load->library(array('session'));
		$this->load->library(array('ion_auth','form_validation'));

	}
	/*** Displays the Index Page**/
	function index()
	{

		$show_records_count_in_search_filters = strip_tags($this->config->item('site_settings')->show_records_count_in_search_filters);
		$avail_records_cnt = "";

		//Location Options
		$locations = $this->home_model->get_locations(array('child' => true));
		$location_opts[''] = get_languageword('select_location');
		if(!empty($locations)) {
			foreach ($locations as $key => $value) {
				if($show_records_count_in_search_filters == "Yes") {

					$avail_records_cnt = " (".count($this->home_model->get_tutors(array('location_slug'=>$value->slug))).")";
				}
				$location_opts[$value->slug] = $value->location_name.$avail_records_cnt;
			}
		}
		$this->data['location_opts'] = $location_opts;


		//Course Options
		$courses = $this->home_model->get_courses();
		$course_opts[''] = get_languageword('type_of_course');
		if(!empty($courses)) {
			foreach ($courses as $key => $value) {
				if($show_records_count_in_search_filters == "Yes") {

					$avail_records_cnt = " (".count($this->home_model->get_tutors(array('course_slug'=>$value->slug))).")";
				}
				$course_opts[$value->slug] = $value->name.$avail_records_cnt;
			}
		}
		$this->data['course_opts'] = $course_opts;


		//Recent Added Courses
		$this->data['recent_courses'] = $this->home_model->get_courses(array('order_by' => 'courses.id DESC', 'limit' => 6));


		/* Category-wise Popular Courses - Start */
			$category_limit = 8;
			$course_limit   = 4;
			$this->data['popular_courses'] = $this->home_model->get_popular_courses($category_limit, $course_limit);
		/* Category-wise  Popular Courses - End */

		//Site Testimonials
		$site_testimonials = $this->home_model->get_site_testimonials();
		$this->data['site_testimonials']	= $site_testimonials;
		// Tuotor ratings
		$home_tutor_ratings = $this->home_model->get_home_tutor_ratings();
		$this->data['home_tutor_ratings'] = $home_tutor_ratings;


		//Send App Link Email - Start
		if($this->input->post()) {

			//Form Validations
			$this->form_validation->set_rules('mailid', get_languageword('Email'), 'trim|required|xss_clean|valid_email');

			$this->form_validation->set_error_delimiters('<div class="error">', '</div>');

			if($this->form_validation->run() == TRUE) {

				$to = $this->input->post('mailid');

				//Email Alert to User - Start
				//Get Send App Download Link Email Template
				$email_tpl = $this->base_model->fetch_records_from('email_templates', array('template_status' => 'Active', 'email_template_id' => '18'));


				if(!empty($email_tpl)) {

					$email_tpl = $email_tpl[0];


					if(!empty($email_tpl->from_email)) {

						$from = $email_tpl->from_email;

					} else {

						$from = get_system_settings('Portal_Email');
					}

					if(!empty($email_tpl->template_subject)) {

						$sub = $email_tpl->template_subject;

					} else {

						$sub = get_languageword('Tutor_App_Download_Link');
					}

					if(!empty($email_tpl->template_content)) {

						$msg = $email_tpl->template_content;

					} else {

						$msg = "";
					}

					if(sendEmail($from, $to, $sub, $msg)) {

						$this->prepare_flashmessage(get_languageword('Tutor_App_Download_Link_sent_to_your_email_successfully'), 0);

					} else {

						$this->prepare_flashmessage(get_languageword('App not sent due to some technical issue Please enter valid email Thankyou'), 2);
					}

					redirect('/#footer_sec');
					//Email Alert to User - End

				} else {

					$this->prepare_flashmessage(get_languageword('App not available Please contact Admin for any details Thankyou'), 2);
					redirect(URL_HOME_CONTACT_US);
				}

			} else {

				$this->prepare_flashmessage(validation_errors(), 1);
				redirect('/#footer_sec');
			}

		}
		//Send App Link Email - End



		$this->data['activemenu'] 	= "home";		
		$this->data['content'] 		= 'index';
		$this->_render_page('template/site/site-template', $this->data);
	}


	function contact_us()
	{

		if($this->input->post()) {

			//Form Validations
			$this->form_validation->set_rules('fname',get_languageword('first_name'),'trim|required|xss_clean');
			$this->form_validation->set_rules('lname',get_languageword('last_name'),'trim|required|xss_clean');
			$this->form_validation->set_rules('email',get_languageword('email'),'trim|required|xss_clean|valid_email');
			$this->form_validation->set_rules('sub',get_languageword('subject'),'trim|required|xss_clean');
			$this->form_validation->set_rules('msg',get_languageword('message'),'trim');

			$this->form_validation->set_error_delimiters('<div class="error">', '</div>');

			if($this->form_validation->run() == TRUE) {

				$first_name = $this->input->post('fname');
				$last_name  = $this->input->post('lname');
				$email 		= $this->input->post('email');
				$subjct 	= $this->input->post('sub');
				$msgg 		= $this->input->post('msg');

				//Send conatct query details to Admin Email
				//Email Alert to Admin - Start
				//Get Contact Query Email Template
				$email_tpl = $this->base_model->fetch_records_from('email_templates', array('template_status' => 'Active', 'email_template_id' => '16'));

				$from 	= $email;
				$to 	= get_system_settings('Portal_Email');
				$sub 	= get_languageword("Contact Query Received");
				$msg 	= '<p>
									'.get_languageword('Hello Admin, ').',</p>
								<p>
									'.get_languageword('You got contact query Below are the details').'</p>
								<p>
									<strong>'.get_languageword('first_name').':</strong> '.$first_name.'</p>
								<p>
									<strong>'.get_languageword('last_name').':</strong> '.$last_name.'</p>
								<p>
									<strong>'.get_languageword('email').':</strong> '.$email.'</p>
								<p>
									<strong>'.get_languageword('subject').':</strong> '.$subjct.'</p>
								<p>
									<strong>'.get_languageword('message').':</strong> '.$msgg.'</p>
								<p>
									&nbsp;</p>
								';
				$msg 	.= "<p>".get_languageword('Thank you')."</p>";

				if(!empty($email_tpl)) {

					$email_tpl = $email_tpl[0];


					if(!empty($email_tpl->from_email)) {

						$from = $email_tpl->from_email;

					}

					if(!empty($email_tpl->template_subject)) {

						$sub = $email_tpl->template_subject;

					}

					if(!empty($email_tpl->template_content)) {

						$msg = "";
						$original_vars  = array($first_name, $last_name, $email, $subjct, $msgg,);
						$temp_vars		= array('___FIRST_NAME___', '___LAST_NAME___', '___EMAIL___', '___SUBJECT___', '___MESSAGE___');
						$msg = str_replace($temp_vars, $original_vars, $email_tpl->template_content);

					}

				}

				if(sendEmail($from, $to, $sub, $msg)) {

					$this->prepare_flashmessage(get_languageword('Your contact request sent successfully'), 0);

				} else {

					$this->prepare_flashmessage(get_languageword('Your contact request not sent due to some technical issue Please contact us after some time Thankyou.'), 2);
				}

				redirect(URL_HOME_CONTACT_US);
				//Email Alert to Admin - End

			}

		}


		$this->data['activemenu'] 	= "contact_us";		
		$this->data['content'] 		= 'contact_us';
		$this->data['pagetitle']	= get_languageword('contact_us');
		$this->_render_page('template/site/site-template', $this->data);
	}
	
	function about_us()
	{

		$this->load->model('base_model');
		$about_us = $this->base_model->get_page_about_us();
		$this->data['about_us'] 	= $about_us;
	
		$this->data['activemenu'] 	= "blog";		
		$this->data['content'] 		= 'about_us';
		$this->_render_page('template/site/site-template', $this->data);
	}

	function faqs()
	{
		$faqs = $this->home_model->get_faqs();
		$this->data['faqs']	=  $faqs;

		$this->data['activemenu'] 	= "blog";		
		$this->data['content'] 		= 'faqs';
		$this->_render_page('template/site/site-template', $this->data);
	}


	function terms_and_conditions()
	{

		$this->load->model('base_model');
		$terms_and_conditions = $this->base_model->get_page_terms_and_conditions();
		$this->data['pageTermsAndCondtions'] 	= $terms_and_conditions;

		$privacy_and_policy= $this->base_model->get_page_privacy_and_policy();
		$this->data['privacy_and_policy'] = $privacy_and_policy;
	
		$this->data['activemenu'] 	= "terms_conditions";		
		$this->data['content'] 		= 'terms_conditions';
		$this->_render_page('template/site/site-template', $this->data);
	}



	/*** Displays All Courses **/
	function all_courses($category_slug = '')
	{

		$category_slug = str_replace('_', '-', $category_slug);

		$this->data['categories'] = get_categories();
		$params = array(
							'limit' 		=> LIMIT_COURSE_LIST, 
							'category_slug' => $category_slug
						);
		$this->data['courses'] 	  = $this->home_model->get_courses($params);



		//total rows count
		unset($params['limit']);
        $total_records = count($this->home_model->get_courses($params));


		$active_cat = 'all_courses';
		$heading1   = get_languageword('all_courses').' ('.$total_records.')';

		if(!empty($category_slug)) {

			$active_cat = $category_slug;
			$heading1	= get_languageword('courses_in').' '.$this->home_model->get_categoryname_by_slug($category_slug).' ('.$total_records.')';
		}


		$this->data['total_records'] = $total_records;
		$this->data['active_cat']	 = (!empty($category_slug)) ? $category_slug : "all_courses";
		$this->data['category_slug'] = $category_slug;

		$this->data['activemenu'] 	 = "courses";
		$this->data['heading1'] 	 = $heading1;
		$this->data['content'] 		 = 'all_courses';
		$this->_render_page('template/site/site-template', $this->data);
	}



	function load_more_courses()
	{

		$limit   		= $this->input->post('limit');
		$offset  		= $this->input->post('offset');
		$category_slug = str_replace('_', '-', $this->input->post('category_slug'));

		$params = array(
							'start'			=> $offset, 
							'limit' 		=> $limit, 
							'category_slug' => $category_slug
						);

		$courses  		= $this->home_model->get_courses($params);
		$result 		= $this->load->view('sections/course_section', array('courses' => $courses), true);

		$data['result'] = $result;
		$data['offset'] = $offset + $limit;
		$data['limit']  = $limit;

		echo json_encode($data);

    }



    /* SEARCH TUTOR */
    function search_tutor($course_slug = '', $location_slug = '', $teaching_type_slug = '')
	{

		$course_slug = (!empty($course_slug)) ? array($course_slug) : $this->input->post('course_slug');

		$location_slug = (!empty($location_slug)) ? array($location_slug) : $this->input->post('location_slug');

		$teaching_type_slug = (!empty($teaching_type_slug)) ? array($teaching_type_slug) : $this->input->post('teaching_type_slug');


		if(!empty($course_slug[0]) && $course_slug[0] == "by_location")
			$course_slug = '';
		if(!empty($course_slug[0]) && $course_slug[0] == "by_teaching_type") {
			$teaching_type_slug = $location_slug;
			$course_slug   = '';
			$location_slug = '';
		}


		$course_slug = str_replace('_', '-', $course_slug);
		$location_slug = str_replace('_', '-', $location_slug);
		$teaching_type_slug = str_replace('_', '-', $teaching_type_slug);


		$params = array(
							'limit' 	  	=> LIMIT_PROFILES_LIST, 
							'course_slug' 	=> $course_slug, 
							'location_slug' => $location_slug, 
							'teaching_type_slug' => $teaching_type_slug
						);

		$tutor_list = $this->home_model->get_tutors($params);
		$this->data['tutor_list'] = $tutor_list;
		
			
		//total rows count
		unset($params['limit']);
        $total_records = count($this->home_model->get_tutors($params));


		$this->data['total_records'] = $total_records;
		$this->data['course_slug'] 	 = $course_slug;
		$this->data['location_slug'] = $location_slug;
		$this->data['teaching_type_slug'] = $teaching_type_slug;


		/*** Drop-down Options - Start ***/
		$show_records_count_in_search_filters = strip_tags($this->config->item('site_settings')->show_records_count_in_search_filters);
		$avail_records_cnt = "";
		//Course Options
		$courses = $this->home_model->get_courses();
		$course_opts[''] = get_languageword('select');
		if(!empty($courses)) {
			foreach ($courses as $key => $value) {
				if($show_records_count_in_search_filters == "Yes") {

					$avail_records_cnt = " (".count($this->home_model->get_tutors(array('course_slug'=>$value->slug, 'location_slug'=>$location_slug, 'teaching_type_slug'=>$teaching_type_slug))).")";
				}
				$course_opts[$value->slug] = $value->name.$avail_records_cnt;
			}
		}
		$this->data['course_opts'] = $course_opts;


		//Location Options
		$locations = $this->home_model->get_locations(array('child' => true));
		$location_opts[''] = get_languageword('select');
		if(!empty($locations)) {
			foreach ($locations as $key => $value) {
				if($show_records_count_in_search_filters == "Yes") {

					$avail_records_cnt = " (".count($this->home_model->get_tutors(array('location_slug'=>$value->slug, 'course_slug'=>$course_slug, 'teaching_type_slug'=>$teaching_type_slug))).")";
				}
				$location_opts[$value->slug] = $value->location_name.$avail_records_cnt;
			}
		}
		$this->data['location_opts'] = $location_opts;


		//Teaching type Options
		$teaching_types = $this->home_model->get_teaching_types();
		$teaching_type_opts[''] = get_languageword('select');
		foreach ($teaching_types as $key => $value) {
			if($show_records_count_in_search_filters == "Yes") {

				$avail_records_cnt = " (".count($this->home_model->get_tutors(array('teaching_type_slug'=>$value->slug, 'course_slug'=>$course_slug, 'location_slug'=>$location_slug))).")";
			}
			$teaching_type_opts[$value->slug] = $value->teaching_type.$avail_records_cnt;
		}
		$this->data['teaching_type_opts'] = $teaching_type_opts;
		/*** Drop-down Options - End ***/
		
		$this->data['activemenu'] 	= "search_tutor";		
		$this->data['content'] 		= 'search_tutor';
		
		$this->_render_page('template/site/site-template', $this->data);
	}



	function load_more_tutors()
	{

		$limit   		= $this->input->post('limit');
		$offset  		= $this->input->post('offset');
		$course_slug  	= ($this->input->post('course_slug')) ? explode(',', $this->input->post('course_slug')) : '';
		$location_slug  = ($this->input->post('location_slug')) ? explode(',', $this->input->post('location_slug')) : '';
		$teaching_type_slug  = ($this->input->post('teaching_type_slug')) ? explode(',', $this->input->post('teaching_type_slug')) : '';


		$course_slug = str_replace('_', '-', $course_slug);
		$location_slug = str_replace('_', '-', $location_slug);
		$teaching_type_slug = str_replace('_', '-', $teaching_type_slug);

		$params = array(
							'start'			=> $offset, 
							'limit' 		=> $limit, 
							'course_slug' 	=> $course_slug, 
							'location_slug' => $location_slug, 
							'teaching_type_slug' => $teaching_type_slug
						);

		$tutor_list  	= $this->home_model->get_tutors($params);
		$result 		= $this->load->view('sections/tutor_list_section', array('tutor_list' => $tutor_list), true);

		$data['result'] = $result;
		$data['offset'] = $offset + $limit;
		$data['limit']  = $limit;

		echo json_encode($data);

    }


    /* SEARCH INSTITUTE */
    function search_institute($course_slug = '', $location_slug = '', $teaching_type_slug = '', $inst_slug = '')
	{

		$course_slug = (!empty($course_slug)) ? array($course_slug) : $this->input->post('course_slug');

		$location_slug = (!empty($location_slug)) ? array($location_slug) : $this->input->post('location_slug');

		$teaching_type_slug = (!empty($teaching_type_slug)) ? array($teaching_type_slug) : $this->input->post('teaching_type_slug');

		$inst_slug = (!empty($inst_slug)) ? array($inst_slug) : $this->input->post('inst_slug');


		if(!empty($course_slug[0]) && $course_slug[0] == "by_location")
			$course_slug = '';
		if(!empty($course_slug[0]) && $course_slug[0] == "by_teaching_type") {
			$teaching_type_slug = $location_slug;
			$course_slug   = '';
			$location_slug = '';
		}


		$course_slug = str_replace('_', '-', $course_slug);
		$location_slug = str_replace('_', '-', $location_slug);
		$teaching_type_slug = str_replace('_', '-', $teaching_type_slug);
		$inst_slug = str_replace('_', '-', $inst_slug);


		$params = array(
							'limit' 	  	=> LIMIT_PROFILES_LIST, 
							'course_slug' 	=> $course_slug, 
							'location_slug' => $location_slug, 
							'teaching_type_slug' => $teaching_type_slug, 
							'inst_slug' 	=> $inst_slug
						);

		$this->data['institute_list'] = $this->home_model->get_institutes($params);


		//total rows count
		unset($params['limit']);
        $total_records = count($this->home_model->get_institutes($params));


		$this->data['total_records'] = $total_records;
		$this->data['course_slug'] 	 = $course_slug;
		$this->data['location_slug'] = $location_slug;
		$this->data['teaching_type_slug'] = $teaching_type_slug;
		$this->data['inst_slug'] = $inst_slug;


		/*** Drop-down Options - Start ***/
		$show_records_count_in_search_filters = strip_tags($this->config->item('site_settings')->show_records_count_in_search_filters);
		$avail_records_cnt = "";
		//Course Options
		$courses = $this->home_model->get_courses();
		$course_opts[''] = get_languageword('select');
		if(!empty($courses)) {
			foreach ($courses as $key => $value) {
				if($show_records_count_in_search_filters == "Yes") {

					$avail_records_cnt = " (".count($this->home_model->get_institutes(array('course_slug'=>$value->slug, 'location_slug'=>$location_slug, 'inst_slug'=>$inst_slug))).")";
				}
				$course_opts[$value->slug] = $value->name.$avail_records_cnt;
			}
		}
		$this->data['course_opts'] = $course_opts;


		//Location Options
		$locations = $this->home_model->get_locations(array('child' => true));
		$location_opts[''] = get_languageword('select');
		if(!empty($locations)) {
			foreach ($locations as $key => $value) {
				if($show_records_count_in_search_filters == "Yes") {

					$avail_records_cnt = " (".count($this->home_model->get_institutes(array('location_slug'=>$value->slug, 'course_slug'=>$course_slug, 'inst_slug'=>$inst_slug))).")";
				}
				$location_opts[$value->slug] = $value->location_name.$avail_records_cnt;
			}
		}
		$this->data['location_opts'] = $location_opts;


		//Institute Options
		$insts = $this->home_model->get_institutes();
		$inst_opts[''] = get_languageword('select');
		if(!empty($insts)) {
			foreach ($insts as $key => $value) {
				$inst_opts[$value->slug] = $value->username;
			}
		}
		$this->data['inst_opts'] = $inst_opts;

		/*** Drop-down Options - End ***/


		$this->data['activemenu'] 	= "search_institute";		
		$this->data['content'] 		= 'search_institute';
		$this->_render_page('template/site/site-template', $this->data);
	}



	function load_more_institutes()
	{

		$limit   		= $this->input->post('limit');
		$offset  		= $this->input->post('offset');
		$course_slug  	= ($this->input->post('course_slug')) ? explode(',', $this->input->post('course_slug')) : '';
		$location_slug  = ($this->input->post('location_slug')) ? explode(',', $this->input->post('location_slug')) : '';
		$teaching_type_slug  = ($this->input->post('teaching_type_slug')) ? explode(',', $this->input->post('teaching_type_slug')) : '';


		$course_slug = str_replace('_', '-', $course_slug);
		$location_slug = str_replace('_', '-', $location_slug);
		$teaching_type_slug = str_replace('_', '-', $teaching_type_slug);

		$params = array(
							'start'			=> $offset, 
							'limit' 		=> $limit, 
							'course_slug' 	=> $course_slug, 
							'location_slug' => $location_slug, 
							'teaching_type_slug' => $teaching_type_slug
						);

		$institute_list  = $this->home_model->get_institutes($params);
		$result 		= $this->load->view('sections/institute_list_section', array('institute_list' => $institute_list), true);

		$data['result'] = $result;
		$data['offset'] = $offset + $limit;
		$data['limit']  = $limit;

		echo json_encode($data);

    }



    /* SEARCH STUDENT LEADS */
    function search_student_leads($course_slug = '', $location_slug = '', $teaching_type_slug = '')
	{

		$course_slug = (!empty($course_slug)) ? array($course_slug) : $this->input->post('course_slug');

		$location_slug = (!empty($location_slug)) ? array($location_slug) : $this->input->post('location_slug');

		$teaching_type_slug = (!empty($teaching_type_slug)) ? array($teaching_type_slug) : $this->input->post('teaching_type_slug');


		if(!empty($course_slug[0]) && $course_slug[0] == "by_location")
			$course_slug = '';
		if(!empty($course_slug[0]) && $course_slug[0] == "by_teaching_type") {
			$teaching_type_slug = $location_slug;
			$course_slug   = '';
			$location_slug = '';
		}

		$course_slug = str_replace('_', '-', $course_slug);
		$location_slug = str_replace('_', '-', $location_slug);
		$teaching_type_slug = str_replace('_', '-', $teaching_type_slug);

		$params = array(
							'limit' 	  	=> LIMIT_PROFILES_LIST, 
							'course_slug' 	=> $course_slug, 
							'location_slug' => $location_slug, 
							'teaching_type_slug' => $teaching_type_slug
						);

		$this->data['student_leads_list'] = $this->home_model->get_student_leads($params);


		//total rows count
		unset($params['limit']);
        $total_records = count($this->home_model->get_student_leads($params));


		$this->data['total_records'] = $total_records;
		$this->data['course_slug'] 	 = $course_slug;
		$this->data['location_slug'] = $location_slug;
		$this->data['teaching_type_slug'] = $teaching_type_slug;


		$show_records_count_in_search_filters = strip_tags($this->config->item('site_settings')->show_records_count_in_search_filters);
		$avail_records_cnt = "";

		//Course Options
		$courses = $this->home_model->get_courses();
		$course_opts[''] = get_languageword('select');
		if(!empty($courses)) {
			foreach ($courses as $key => $value) {
				if($show_records_count_in_search_filters == "Yes") {

					$avail_records_cnt = " (".count($this->home_model->get_student_leads(array('course_slug'=>$value->slug, 'location_slug'=>$location_slug, 'teaching_type_slug'=>$teaching_type_slug))).")";
				}
				$course_opts[$value->slug] = $value->name.$avail_records_cnt;
			}
		}
		$this->data['course_opts'] = $course_opts;


		//Location Options
		$locations = $this->home_model->get_locations(array('child' => true));
		$location_opts[''] = get_languageword('select');
		if(!empty($locations)) {
			foreach ($locations as $key => $value) {
				if($show_records_count_in_search_filters == "Yes") {

					$avail_records_cnt = " (".count($this->home_model->get_student_leads(array('location_slug'=>$value->slug, 'course_slug'=>$course_slug, 'teaching_type_slug'=>$teaching_type_slug))).")";
				}
				$location_opts[$value->slug] = $value->location_name.$avail_records_cnt;
			}
		}
		$this->data['location_opts'] = $location_opts;


		//Teaching type Options
		$teaching_types = $this->home_model->get_teaching_types();
		$teaching_type_opts[''] = get_languageword('select');
		foreach ($teaching_types as $key => $value) {
			if($show_records_count_in_search_filters == "Yes") {

				$avail_records_cnt = " (".count($this->home_model->get_student_leads(array('teaching_type_slug'=>$value->slug, 'course_slug'=>$course_slug, 'location_slug'=>$location_slug))).")";
			}
			$teaching_type_opts[$value->slug] = $value->teaching_type.$avail_records_cnt;
		}
		$this->data['teaching_type_opts'] = $teaching_type_opts;


		$this->data['activemenu'] 	= "search_student_leads";		
		$this->data['content'] 		= 'search_student_leads';

		$this->_render_page('template/site/site-template', $this->data);
	}



	function load_more_student_leads()
	{

		$limit   		= $this->input->post('limit');
		$offset  		= $this->input->post('offset');
		$course_slug  	= ($this->input->post('course_slug')) ? explode(',', $this->input->post('course_slug')) : '';
		$location_slug  = ($this->input->post('location_slug')) ? explode(',', $this->input->post('location_slug')) : '';
		$teaching_type_slug  = ($this->input->post('teaching_type_slug')) ? explode(',', $this->input->post('teaching_type_slug')) : '';


		$course_slug = str_replace('_', '-', $course_slug);
		$location_slug = str_replace('_', '-', $location_slug);
		$teaching_type_slug = str_replace('_', '-', $teaching_type_slug);

		$params = array(
							'start'			=> $offset, 
							'limit' 		=> $limit, 
							'course_slug' 	=> $course_slug, 
							'location_slug' => $location_slug, 
							'teaching_type_slug' => $teaching_type_slug
						);

		$student_leads_list  = $this->home_model->get_student_leads($params);
		$result 		= $this->load->view('sections/student_leads_list_section', array('student_leads_list' => $student_leads_list), true);

		$data['result'] = $result;
		$data['offset'] = $offset + $limit;
		$data['limit']  = $limit;

		echo json_encode($data);

    }




    //TUTOR PROFILE
    function tutor_profile($tutor_slug = '')
	{
		$tutor_slug = ($this->input->post('tutor_slug')) ? $this->input->post('tutor_slug') : $tutor_slug;

		if(empty($tutor_slug)) {

			$this->prepare_flashmessage(get_languageword('invalid_request'), 1);
			redirect(URL_HOME_SEARCH_TUTOR);
		}

		$tutor_slug = str_replace('_', '-', $tutor_slug);

		$tutor_details = $this->home_model->get_tutor_profile($tutor_slug);

		if(empty($tutor_details)) {

			$this->prepare_flashmessage(get_languageword('no_details_available'), 2);
			redirect(URL_HOME_SEARCH_TUTOR);
		}

		$this->data['tutor_details'] = $tutor_details;


		//Send Message to Tutor
		if($this->input->post()) {

			if(!$this->ion_auth->logged_in()) {

				$this->prepare_flashmessage(get_languageword('please_login_to_send_message'), 2);
				redirect(URL_AUTH_LOGIN, 'refresh');
			}

			$inputdata['from_user_id'] 	= $this->ion_auth->get_user_id();
			$credits_for_sending_message = $this->config->item('site_settings')->credits_for_sending_message;

			//Check Whether student is premium user or not
			if(!is_premium($inputdata['from_user_id'])) {

				$this->prepare_flashmessage(get_languageword('please_become_premium_member_to_send_message_to_tutor'), 2);
				redirect(URL_STUDENT_LIST_PACKAGES, 'refresh');
			}

			//Check If student has sufficient credits to send message to tutor
			if(!is_eligible_to_make_booking($inputdata['from_user_id'], $credits_for_sending_message)) {

				$this->prepare_flashmessage(get_languageword("you_do_not_have_enough_credits_to_send_message_to_the_tutor_Please_get_required_credits_here"), 2);
				redirect(URL_STUDENT_LIST_PACKAGES, 'refresh');
			}

			//Form Validations
			$this->form_validation->set_rules('name',get_languageword('name'),'trim|required|xss_clean');
			$this->form_validation->set_rules('email',get_languageword('email'),'trim|required|xss_clean|valid_email');
			$this->form_validation->set_rules('phone',get_languageword('phone'),'trim|required|xss_clean');
			$this->form_validation->set_rules('msg',get_languageword('message'),'trim|required');

			$this->form_validation->set_error_delimiters('<div class="error">', '</div>');

			if($this->form_validation->run() == TRUE) {


				$course_name = $this->base_model->fetch_value('categories', 'name', array('slug' => $this->input->post('course_slug1')));

				$inputdata['name'] 			= $this->input->post('name');
				$inputdata['course_slug']	= $course_name;
				$inputdata['email'] 		= $this->input->post('email');
				$inputdata['phone'] 		= $this->input->post('phone');
				$inputdata['message'] 		= $this->input->post('msg');

				$to_user_type   = $this->input->post('to_user_type');
				$inputdata['to_user_id']   = $this->input->post('to_user_id');				

				$inputdata['created_at']	= date('Y-m-d H:i:s');
				$inputdata['updated_at']	= $inputdata['created_at'];

				$ref = $this->base_model->insert_operation($inputdata, 'messages');
				if($ref) {

					//Send message details to Tutor Email
					//Email Alert to Tutor - Start
					//Get Send Message Email Template
					$email_tpl = $this->base_model->fetch_records_from('email_templates', array('template_status' => 'Active', 'email_template_id' => '17'));

					$tutor_rec = getUserRec($inputdata['to_user_id']);

					$from 	= $inputdata['email'];
					$to 	= $tutor_rec->email;
					$sub 	= get_languageword("Message Received From Student");
					$msg 	= '<p>
										'.get_languageword('Hi ').$tutor_rec->username.',</p>
									<p>
										'.get_languageword('You got a message from Student Below are the details').'</p>
									<p>
										<strong>'.get_languageword('name').':</strong> '.$inputdata['name'].'</p>
									<p>
										<strong>'.get_languageword('email').':</strong> '.$inputdata['email'].'</p>
									<p>
										<strong>'.get_languageword('phone').':</strong> '.$inputdata['phone'].'</p>
									<p>
										<strong>'.get_languageword('course_seeking').':</strong> '.$inputdata['course_slug'].'</p>
									<p>
										<strong>'.get_languageword('message').':</strong> '.$inputdata['message'].'</p>
									<p>
										&nbsp;</p>
									';
					$msg 	.= "<p>".get_languageword('Thank you')."</p>";

					if(!empty($email_tpl)) {

						$email_tpl = $email_tpl[0];


						if(!empty($email_tpl->from_email)) {

							$from = $email_tpl->from_email;

						}

						if(!empty($email_tpl->template_subject)) {

							$sub = $email_tpl->template_subject.get_languageword(' Student');

						}

						if(!empty($email_tpl->template_content)) {

							$msg = "";
							$original_vars  = array($tutor_rec->username, get_languageword('Student'), $inputdata['name'], $inputdata['email'], $inputdata['phone'], $inputdata['course_slug'], $inputdata['message']);
							$temp_vars		= array('___TO_NAME___','___USER_TYPE___','___NAME___', '___EMAIL___', '___PHONE___', '___COURSE___', '___MESSAGE___');
							$msg = str_replace($temp_vars, $original_vars, $email_tpl->template_content);

						}

					}

					if(sendEmail($from, $to, $sub, $msg)) {

						//Log Credits transaction data & update user net credits - Start
						$per_credit_value = $this->config->item('site_settings')->per_credit_value;
						$log_data = array(
										'user_id' => $inputdata['from_user_id'],
										'credits' => $credits_for_sending_message,
										'per_credit_value' => $per_credit_value,
										'action'  => 'debited',
										'purpose' => 'For Sending Message To Tutor "'.$tutor_slug.'" ',
										'date_of_action	' => date('Y-m-d H:i:s'),
										'reference_table' => 'messages',
										'reference_id' => $ref,
									);

						log_user_credits_transaction($log_data);

						update_user_credits($inputdata['from_user_id'], $credits_for_sending_message, 'debit');
						//Log Credits transaction data & update user net credits - End


						$this->prepare_flashmessage(get_languageword('Your message sent to Tutor successfully'), 0);

					} else {

						$this->prepare_flashmessage(get_languageword('Your message not sent due to some technical issue Please send message after some time Thankyou'), 2);
					}

					redirect(URL_HOME_TUTOR_PROFILE.'/'.$tutor_slug);
				}
				//Email Alert to Tutor - End

			}

		}


		//Tutor Course Options
		$tutor_courses = $this->home_model->get_tutor_courses($tutor_slug);
		if(!empty($tutor_courses)) {
			$tutor_course_opts[''] = get_languageword('select');
			foreach ($tutor_courses as $key => $value) {
				$tutor_course_opts[$value->slug] = $value->name;
			}
		} else {
			$tutor_course_opts = "";
		}
		$this->data['tutor_course_opts'] = $tutor_course_opts;


		//Tutor Location Options
		$tutor_locations = $this->home_model->get_tutor_locations($tutor_slug);
		if(!empty($tutor_locations)) {
			$tutor_location_opts[''] = get_languageword('select_location');
			foreach ($tutor_locations as $key => $value) {
				$tutor_location_opts[$value->slug] = $value->location_name;
			}
		} else {
			$tutor_location_opts = "";
		}
		$this->data['tutor_location_opts'] = $tutor_location_opts;
		
		//User Meta Data
		$this->data['meta_description'] = $tutor_details[0]->meta_desc;
		$this->data['meta_keywords'] = $tutor_details[0]->seo_keywords;
		//Tutor Teaching types
		$tutor_teaching_types = $this->home_model->get_tutor_teaching_types($tutor_slug);
		$this->data['tutor_teaching_types'] = $tutor_teaching_types;

		//Tutor Reviews
		$tutor_reviews = $this->home_model->get_tutor_reviews($tutor_slug);
		$this->data['tutor_reviews'] = $tutor_reviews;
		
		//Tutor ratings
		$tutor_rating	= $this->home_model->get_tutor_rating($tutor_slug);
		$this->data['tutor_raing'] = $tutor_rating;
		$this->data['activemenu'] 	= "search_tutor";		
		$this->data['content'] 		= 'tutor_profile';
		$this->_render_page('template/site/site-template', $this->data);
	}


	function ajax_get_tutor_course_details()
	{
		$avail_time_slots = array();
		$course_slug = $this->input->post('course_slug');
		$tutor_id = $this->input->post('tutor_id');
		$selected_date = $this->input->post('selected_date');

		if(empty($course_slug) || empty($tutor_id) || empty($selected_date)) {
			echo ''; die();
		}

		$row =  $this->home_model->get_tutor_course_details($course_slug, $tutor_id);

		if(empty($row)) {
			echo NULL; die();
		}

		$tutor_time_slots = explode(',', $row->time_slots);

		$booked_slots = $this->home_model->get_booked_slots($tutor_id, $row->course_id, $selected_date);

		if(!empty($booked_slots)) {

			foreach ($tutor_time_slots as $slot) {
				if(!in_array($slot, $booked_slots))
					$avail_time_slots[] = $slot;
			}

		} else {

			$avail_time_slots = $tutor_time_slots;
		}

		if(!empty($row))
        	echo $row->fee."~".$row->duration_value." ".$row->duration_type."~".$row->content."~".implode(',', $avail_time_slots)."~".$row->days_off;

	}



	//INSTITUTE PROFILE
    function institute_profile($inst_slug = '')
	{
		$inst_slug = ($this->input->post('inst_slug')) ? $this->input->post('inst_slug') : $inst_slug;

		if(empty($inst_slug)) {

			$this->prepare_flashmessage(get_languageword('invalid_request'), 1);
			redirect(URL_HOME_SEARCH_INSTITUTE);
		}

		$inst_slug = str_replace('_', '-', $inst_slug);


		$inst_details = $this->home_model->get_inst_profile($inst_slug);

		if(empty($inst_details)) {

			$this->prepare_flashmessage(get_languageword('no_details_available'), 2);
			redirect(URL_HOME_SEARCH_INSTITUTE);
		}


		//Send Message to Institute
		if($this->input->post()) {

			$inputdata['from_user_id'] 	= $this->ion_auth->get_user_id();
			$credits_for_sending_message = $this->config->item('site_settings')->credits_for_sending_message;

			//Check Whether student is premium user or not
			if(!is_premium($inputdata['from_user_id'])) {

				$this->prepare_flashmessage(get_languageword('please_become_premium_member_to_send_message_to_institute'), 2);
				redirect(URL_STUDENT_LIST_PACKAGES, 'refresh');
			}

			//Check If student has sufficient credits to send message to institute
			if(!is_eligible_to_make_booking($inputdata['from_user_id'], $credits_for_sending_message)) {

				$this->prepare_flashmessage(get_languageword("you_do_not_have_enough_credits_to_send_message_to_the_institute_Please_get_required_credits_here"), 2);
				redirect(URL_STUDENT_LIST_PACKAGES, 'refresh');
			}

			//Form Validations
			$this->form_validation->set_rules('name',get_languageword('name'),'trim|required|xss_clean');
			$this->form_validation->set_rules('email',get_languageword('email'),'trim|required|xss_clean|valid_email');
			$this->form_validation->set_rules('phone',get_languageword('phone'),'trim|required|xss_clean');
			$this->form_validation->set_rules('msg',get_languageword('message'),'trim|required');

			$this->form_validation->set_error_delimiters('<div class="error">', '</div>');

			if($this->form_validation->run() == TRUE) {

				$course_name = $this->base_model->fetch_value('categories', 'name', array('slug' => $this->input->post('course_slug1')));

				$inputdata['name'] 			= $this->input->post('name');
				$inputdata['course_slug']	= $course_name;
				$inputdata['email'] 		= $this->input->post('email');
				$inputdata['phone'] 		= $this->input->post('phone');
				$inputdata['message'] 		= $this->input->post('msg');

				$to_user_type   = $this->input->post('to_user_type');
				$inputdata['to_user_id']   = $this->input->post('to_user_id');				

				$inputdata['created_at']	= date('Y-m-d H:i:s');
				$inputdata['updated_at']	= $inputdata['created_at'];

				$ref = $this->base_model->insert_operation($inputdata, 'messages');
				if($ref) {

					//Email Alert to Institute - Start
					//Get Send Message Email Template
					$email_tpl = $this->base_model->fetch_records_from('email_templates', array('template_status' => 'Active', 'email_template_id' => '17'));

					$inst_rec = getUserRec($inputdata['to_user_id']);

					$from 	= $inputdata['email'];
					$to 	= $inst_rec->email;
					$sub 	= get_languageword("Message Received From Student");
					$msg 	= '<p>
										'.get_languageword('Hi ').$inst_rec->username.',</p>
									<p>
										'.get_languageword('You got a message from Student Below are the details').'</p>
									<p>
										<strong>'.get_languageword('name').':</strong> '.$inputdata['name'].'</p>
									<p>
										<strong>'.get_languageword('email').':</strong> '.$inputdata['email'].'</p>
									<p>
										<strong>'.get_languageword('phone').':</strong> '.$inputdata['phone'].'</p>
									<p>
										<strong>'.get_languageword('course_seeking').':</strong> '.$inputdata['course_slug'].'</p>
									<p>
										<strong>'.get_languageword('message').':</strong> '.$inputdata['message'].'</p>
									<p>
										&nbsp;</p>
									';
					$msg 	.= "<p>".get_languageword('Thank you')."</p>";

					if(!empty($email_tpl)) {

						$email_tpl = $email_tpl[0];


						if(!empty($email_tpl->from_email)) {

							$from = $email_tpl->from_email;

						}

						if(!empty($email_tpl->template_subject)) {

							$sub = $email_tpl->template_subject.get_languageword(' Student');

						}

						if(!empty($email_tpl->template_content)) {

							$msg = "";
							$original_vars  = array($inst_rec->username, get_languageword('Student'), $inputdata['name'], $inputdata['email'], $inputdata['phone'], $inputdata['course_slug'], $inputdata['message']);
							$temp_vars		= array('___TO_NAME___','___USER_TYPE___','___NAME___', '___EMAIL___', '___PHONE___', '___COURSE___', '___MESSAGE___');
							$msg = str_replace($temp_vars, $original_vars, $email_tpl->template_content);

						}

					}

					if(sendEmail($from, $to, $sub, $msg)) {

						//Log Credits transaction data & update user net credits - Start
						$per_credit_value = $this->config->item('site_settings')->per_credit_value;
						$log_data = array(
										'user_id' => $inputdata['from_user_id'],
										'credits' => $credits_for_sending_message,
										'per_credit_value' => $per_credit_value,
										'action'  => 'debited',
										'purpose' => 'For Sending Message To Institute "'.$inst_slug.'" ',
										'date_of_action	' => date('Y-m-d H:i:s'),
										'reference_table' => 'messages',
										'reference_id' => $ref,
									);

						log_user_credits_transaction($log_data);

						update_user_credits($inputdata['from_user_id'], $credits_for_sending_message, 'debit');
						//Log Credits transaction data & update user net credits - End


						$this->prepare_flashmessage(get_languageword('Your message sent to Institute successfully'), 0);

					} else {

						$this->prepare_flashmessage(get_languageword('Your message not sent due to some technical issue Please send message after some time Thankyou'), 2);
					}

					redirect(URL_HOME_INSTITUTE_PROFILE.'/'.$inst_slug);
				}
				//Email Alert to Institute - End

			}

		}


		$this->data['inst_details'] = $inst_details;
		//Inst meta data
		$this->data['meta_description'] = $inst_details[0]->meta_desc;
		$this->data['meta_keywords'] = $inst_details[0]->seo_keywords;
		
		$this->data['activemenu'] 	= "search_institute";		
		$this->data['content'] 		= 'institute_profile';
		$this->_render_page('template/site/site-template', $this->data);
	}



	//STUDENT PROFILE
    function student_profile($student_slug = '', $student_lead_id = '')
	{

		if(!$this->ion_auth->logged_in()) {

			$this->prepare_flashmessage(get_languageword('please_login_to_continue'), 2);
			redirect(URL_AUTH_LOGIN);
		}

		if ($this->ion_auth->is_student() || $this->ion_auth->is_admin()) {
			$this->prepare_flashmessage(get_languageword('You dont have permission to access this page'), 1);
			redirect(URL_AUTH_LOGIN);
		}

		$student_slug = ($this->input->post('student_slug')) ? $this->input->post('student_slug') : $student_slug;

		if(empty($student_slug)) {

			$this->prepare_flashmessage(get_languageword('invalid_request'), 1);
			redirect(URL_HOME_SEARCH_STUDENT_LEADS);
		}

		$student_slug = str_replace('_', '-', $student_slug);

		$student_lead_id = ($this->input->post('lead_id')) ? $this->input->post('lead_id') : $student_lead_id;

		$stduent_details = $this->home_model->get_student_profile($student_slug,$student_lead_id);

		if(empty($stduent_details)) {

			$this->prepare_flashmessage(get_languageword('no_details_available'), 2);
			redirect(URL_HOME_SEARCH_STUDENT_LEADS);
		}


		//Send Message to Student
		if($this->input->post()) {

			$from_user_type = "";

			if($this->ion_auth->is_tutor())
				$from_user_type = 'tutor';
			else if($this->ion_auth->is_institute())
				$from_user_type = 'institute';

			$inputdata['from_user_id'] 	= $this->ion_auth->get_user_id();
			$credits_for_sending_message = $this->config->item('site_settings')->credits_for_sending_message;

			//Check Whether student is premium user or not
			if(!is_premium($inputdata['from_user_id'])) {

				$this->prepare_flashmessage(get_languageword('please_become_premium_member_to_send_message_to_student'), 2);
				if($from_user_type == "tutor")
					redirect(URL_TUTOR_LIST_PACKAGES, 'refresh');
				else if($from_user_type == "institute")
					redirect(URL_TUTOR_LIST_PACKAGES, 'refresh');
				else
					redirect(URL_AUTH_INDEX);
			}

			//Check If student has sufficient credits to send message to institute
			if(!is_eligible_to_make_booking($inputdata['from_user_id'], $credits_for_sending_message)) {

				$this->prepare_flashmessage(get_languageword("you_do_not_have_enough_credits_to_send_message_to_the_student_Please_get_required_credits_here"), 2);
				if($from_user_type == "tutor")
					redirect(URL_TUTOR_LIST_PACKAGES, 'refresh');
				else if($from_user_type == "institute")
					redirect(URL_TUTOR_LIST_PACKAGES, 'refresh');
				else
					redirect(URL_AUTH_INDEX);
			}

			//Form Validations
			$this->form_validation->set_rules('name',get_languageword('name'),'trim|required|xss_clean');
			$this->form_validation->set_rules('email',get_languageword('email'),'trim|required|xss_clean|valid_email');
			$this->form_validation->set_rules('phone',get_languageword('phone'),'trim|required|xss_clean');
			$this->form_validation->set_rules('msg',get_languageword('message'),'trim|required');

			$this->form_validation->set_error_delimiters('<div class="error">', '</div>');

			if($this->form_validation->run() == TRUE) {

				$inputdata['name'] 			= $this->input->post('name');
				$inputdata['course_slug']	= $this->input->post('course_slug1');
				$inputdata['email'] 		= $this->input->post('email');
				$inputdata['phone'] 		= $this->input->post('phone');
				$inputdata['message'] 		= $this->input->post('msg');

				$to_user_type   = $this->input->post('to_user_type');
				$inputdata['to_user_id']   = $this->input->post('to_user_id');				

				$inputdata['created_at']	= date('Y-m-d H:i:s');
				$inputdata['updated_at']	= $inputdata['created_at'];

				$ref = $this->base_model->insert_operation($inputdata, 'messages');
				if($ref) {

					//Email Alert to Student - Start
					//Get Send Message Email Template
					$email_tpl = $this->base_model->fetch_records_from('email_templates', array('template_status' => 'Active', 'email_template_id' => '17'));

					$student_rec = getUserRec($inputdata['to_user_id']);

					$from 	= $inputdata['email'];
					$to 	= $student_rec->email;
					$sub 	= get_languageword("Message Received From ")." ".get_languageword(ucfirst($from_user_type));
					$msg 	= '<p>
										'.get_languageword('Hi ').$student_rec->username.',</p>
									<p>
										'.get_languageword('You got a message from '.ucfirst($from_user_type).' Below are the details').'</p>
									<p>
										<strong>'.get_languageword('name').':</strong> '.$inputdata['name'].'</p>
									<p>
										<strong>'.get_languageword('email').':</strong> '.$inputdata['email'].'</p>
									<p>
										<strong>'.get_languageword('phone').':</strong> '.$inputdata['phone'].'</p>
									<p>
										<strong>'.get_languageword('message').':</strong> '.$inputdata['message'].'</p>
									<p>
										&nbsp;</p>
									';
					$msg 	.= "<p>".get_languageword('Thank you')."</p>";

					if(!empty($email_tpl)) {

						$email_tpl = $email_tpl[0];


						if(!empty($email_tpl->from_email)) {

							$from = $email_tpl->from_email;

						}

						if(!empty($email_tpl->template_subject)) {

							$sub = $email_tpl->template_subject." ".get_languageword(ucfirst($from_user_type));

						}

						if(!empty($email_tpl->template_content)) {

							$msg = "";

							$original_vars  = array($student_rec->username, get_languageword(ucfirst($from_user_type)), $inputdata['name'], $inputdata['email'], $inputdata['phone'], $inputdata['course_slug'], $inputdata['message']);
							$temp_vars		= array('___TO_NAME___','___USER_TYPE___','___NAME___', '___EMAIL___', '___PHONE___', '___COURSE___', '___MESSAGE___');
							$msg = str_replace($temp_vars, $original_vars, $email_tpl->template_content);

						}

					}

					if(sendEmail($from, $to, $sub, $msg)) {

						//Log Credits transaction data & update user net credits - Start
						$per_credit_value = $this->config->item('site_settings')->per_credit_value;
						$log_data = array(
										'user_id' => $inputdata['from_user_id'],
										'credits' => $credits_for_sending_message,
										'per_credit_value' => $per_credit_value,
										'action'  => 'debited',
										'purpose' => 'For Sending Message To Student "'.$student_slug.'" ',
										'date_of_action	' => date('Y-m-d H:i:s'),
										'reference_table' => 'messages',
										'reference_id' => $ref,
									);

						log_user_credits_transaction($log_data);

						update_user_credits($inputdata['from_user_id'], $credits_for_sending_message, 'debit');
						//Log Credits transaction data & update user net credits - End


						$this->prepare_flashmessage(get_languageword('Your message sent to Student successfully'), 0);

					} else {

						$this->prepare_flashmessage(get_languageword('Your message not sent due to some technical issue Please send message after some time Thankyou'), 2);
					}

					redirect(URL_VIEW_STUDENT_PROFILE.'/'.$student_slug.'/'.$student_lead_id);
				}
				//Email Alert to Student - End

			}

		}


		$this->data['stduent_details'] = $stduent_details;
		//Student Meta Data
		$this->data['meta_description'] = $stduent_details[0]->meta_desc;
		$this->data['meta_keywords'] = $stduent_details[0]->seo_keywords;


		$this->data['activemenu'] 	= "search_student_leads";
		$this->data['content'] 		= 'student_profile';
		$this->_render_page('template/site/site-template', $this->data);
	}

	function ajax_get_institute_batches()
	{
		$course_id = $this->input->post('course_id');
		$inst_id = $this->input->post('inst_id');
		$this->load->model('institute/institute_model');
		$batches = $this->institute_model->get_batches_by_course($course_id, $inst_id);

		$batch_opts = '';

		if(!empty($batches)) {

			$batch_opts .= '<option value="">'.get_languageword('select_batch').'</option>';

			foreach ($batches as $key => $value) {
				$batch_opts .= '<option value="'.$value->batch_id.'">'.$value->batch_name.'</option>';
			}

		} else {

			$batch_opts = '<option value="">'.get_languageword('no_batches_available').'</option>';
		}

		echo $batch_opts;
	}
	
	function ajax_get_institute_batches_info()
	{
		$course_id = $this->input->post('course_id');
		$inst_id = $this->input->post('inst_id');
		$batch_id = $this->input->post('batch_id');

		
		$batch_status = "";

		$batche_info = $this->home_model->get_institute_batches_info_by_course($course_id, $inst_id,$batch_id);
		$total_enrolled = $this->home_model->total_enrolled_students_in_batch($batch_id);
		$available_slots = "";

			$html = "";
			
			foreach ($batche_info as  $row) {
					
			$available_slots = $row->batch_max_strength - $total_enrolled;

			$today = date('Y-m-d');
			if($row->batch_start_date >= $today)
				$batch_status = get_languageword('not_yet_started');
			else
				$batch_status = get_languageword('running');

			$html.='<div class="dashboard-panel">
					<h2>Batch Details</h2>
						<div class="table-responsive">
                           	<table class="report-table row-border">
                            	<thead>
		                            <tr>
		                              	<th>'.get_languageword('batch_code').'</th>
			                            <th>'.get_languageword('tutor_name').'</th>
			                            <th>'.get_languageword('course_content').'</th>
			                            <th>'.get_languageword('time_slot').'</th>
			                            <th>'.get_languageword('course_offering_location').'</th>
			                            <th>'.get_languageword('batch_start_date').'</th>
			                            <th>'.get_languageword('batch_end_date').'</th>
			                            <th>'.get_languageword('days_off').'</th>
			                            <th>'.get_languageword('fee').' ('.get_languageword('in_credits').')</th>
			                            <th>'.get_languageword('batch_max_strength').'</th>
			                            <th>'.get_languageword('slots_available').'</th>
			                            <th>'.get_languageword('batch_status').'</th>
			                        </tr>
                            	</thead>
                           		<tbody>
		                            <tr>

		                                <td>'.$row->batch_code.'</td>
		                                <td>'.$row->tutorname.'</td>
		                                <td><div class="message more">'.strip_tags($row->course_content).'</div></td>
		                                <td>'.$row->time_slot.'</td>
		                                <td>'.$row->course_offering_location.'</td>
		                                <td>'.$row->batch_start_date.'</td>
		                                <td>'.$row->batch_end_date.'</td>
		                                 <td>'.$row->days_off.'</td>
		                                <td>'.$row->fee.'</td>
		                                <td>'.$row->batch_max_strength.'</td>
		                                <td>'.$available_slots.'</td>
		                                <td>'.$batch_status.'</td>

		                            </tr>
	                        	</tbody>
                        	</table>
                		</div>
                    </div>';

				}	
		
		echo $html;

		
	}




	/*** Displays All Selling Courses **/
	function buy_courses()
	{

		$params = array(
							'limit' 		=> LIMIT_COURSE_LIST
						);
		$this->data['selling_courses'] 	  = $this->home_model->get_selling_courses($params);


		//total rows count
		unset($params['limit']);
        $total_records = count($this->home_model->get_selling_courses($params));

        $total_records = ($total_records > 1) ? $total_records : 0;

		$heading1   = get_languageword('selling_courses').' ('.$total_records.')';

		$this->data['total_records'] = $total_records;

		$this->data['activemenu'] 	 = "buy_courses";
		$this->data['heading1'] 	 = $heading1;
		$this->data['content'] 		 = 'selling_courses';
		$this->_render_page('template/site/site-template', $this->data);
	}



	function load_more_selling_courses()
	{

		$limit   		= $this->input->post('limit');
		$offset  		= $this->input->post('offset');

		$params = array(
							'start'			=> $offset, 
							'limit' 		=> $limit
						);

		$selling_courses= $this->home_model->get_selling_courses($params);
		$result 		= $this->load->view('sections/selling_course_section', array('selling_courses' => $selling_courses), true);

		$data['result'] = $result;
		$data['offset'] = $offset + $limit;
		$data['limit']  = $limit;

		echo json_encode($data);

    }



	function buy_course($selling_course_slug = "")
	{

		if(empty($selling_course_slug)) {

			$this->prepare_flashmessage(get_languageword('Invalid_Request'), 1);
			redirect(URL_HOME_BUY_COURSES);
		}
		$selling_course_slug = str_replace('_', '-', $selling_course_slug);
		$sc_id = $this->base_model->fetch_value('tutor_selling_courses', 'sc_id', array('slug' => $selling_course_slug));

		if(!($sc_id > 0)) {

			$this->prepare_flashmessage(get_languageword('Invalid_Request'), 1);
			redirect(URL_HOME_BUY_COURSES);
		}

		$record = get_tutor_sellingcourse_info($sc_id);

		if(empty($record)) {

			$this->prepare_flashmessage(get_languageword('No Details Found'), 2);
			redirect(URL_HOME_BUY_COURSES);

		}

		$this->data['record'] = $record;


		if($this->ion_auth->logged_in()) {

			$user_id = $this->ion_auth->get_user_id();
			$this->data['is_purchased'] = $this->base_model->get_query_row("SELECT max_downloads FROM ".TBL_PREFIX."course_purchases WHERE sc_id=".$sc_id." AND user_id=".$user_id." ORDER BY max_downloads DESC LIMIT 1 ");
		}


		//More From this Tutor
		$params = array(
							'limit' 		=> 4,
							'tutor_slug'	=> $record->tutor_id
						);
		$this->data['more_selling_courses'] = $this->home_model->get_selling_courses($params);


		$this->data['activemenu'] 	= "buy_courses";
		$this->data['content'] 		= 'buy_course';
		$this->data['pagetitle'] 	= get_languageword('buy_course');
		$this->_render_page('template/site/site-template', $this->data);
	}



	function checkout($selling_course_slug = "", $payment_gateway = "")
	{

		if(empty($selling_course_slug)) {

			$this->prepare_flashmessage(get_languageword('Invalid_Request'), 1);
			redirect(URL_HOME_BUY_COURSES);
		}

		$selling_course_slug = str_replace('_', '-', $selling_course_slug);

		$sc_id = $this->base_model->fetch_value('tutor_selling_courses', 'sc_id', array('slug' => $selling_course_slug));

		if(!($sc_id > 0)) {

			$this->prepare_flashmessage(get_languageword('Invalid_Request'), 1);
			redirect(URL_HOME_BUY_COURSES);
		}


		if(!$this->ion_auth->logged_in()) {

			$this->session->set_userdata('req_from', 'buy_course');
			$this->session->set_userdata('selling_course_slug', $selling_course_slug);
			$this->prepare_flashmessage(get_languageword('please_login_to_continue'), 2);
			redirect(URL_AUTH_LOGIN);
		}


		$record = get_tutor_sellingcourse_info($sc_id);

		if(empty($record)) {

			$this->prepare_flashmessage(get_languageword('No Details Found'), 2);
			redirect(URL_HOME_BUY_COURSES);

		}


		if(!empty($payment_gateway)) {

			$gateway_details = $this->session->userdata('gateway_details');

			$user_info = $this->base_model->get_user_details( $this->ion_auth->get_user_id() );
			$user_info = $user_info[0];
			$this->data['user_info'] = $user_info;

			$field_values = $this->db->get_where('system_settings_fields',array('type_id' => $payment_gateway))->result();

			$razorpay_key_id 			= 'rzp_test_tjwMzd8bqhZkMr';
			$razorpay_key_secret 		= 'EWI9VQiMH43p6LDCbpsgvvHZ';
			$razorpay_payment_action 	= 'capture';
			$razorpay_mode 				= 'sandbox';

			foreach($field_values as $value) {
				if( $value->field_key == 'razorpay_key_id' ) {
					$razorpay_key_id = $value->field_output_value;
				}
				if( $value->field_key == 'razorpay_key_secret' ) {
					$razorpay_key_secret = $value->field_output_value;
				}
				if( $value->field_key == 'razorpay_payment_action' ) {
					$razorpay_payment_action = $value->field_output_value;
				}
				if( $value->field_key == 'razorpay_mode' ) {
					$razorpay_mode = $value->field_output_value;
				}
			}

			$course_name  = $record->course_name;
			$course_title = $record->course_title;
			$total_amount = $record->course_price;


			$config = array(
							'razorpay_key_id' 			=> $razorpay_key_id,
							'razorpay_key_secret' 		=> $razorpay_key_secret,
							'razorpay_payment_action' 	=> $razorpay_payment_action,
							'razorpay_mode' 			=> $razorpay_mode,
							'total_amount' 				=> $total_amount * 100, //As Razorpay accepts amount in paise

							'product_name' 				=> $course_name,
							'product_desc' 				=> $course_title,

							'firstname' 				=> $user_info->first_name,
							'lastname' 					=> $user_info->last_name,
							'email' 					=> $user_info->email,
							'phone' 					=> $user_info->phone,

							'success_url' 	=> base_url() . 'pay/payment_success',
							'cancel_url' 	=> base_url() . 'pay/payment_cancel',
							'failed_url' 	=> base_url() . 'pay/payment_success',
						);

			$site_logo = get_system_settings('Logo');

			if($site_logo != '' && file_exists(URL_PUBLIC_UPLOADS.'settings/thumbs/'.$site_logo)) {
				$config['image'] = URL_PUBLIC_UPLOADS2.'settings/thumbs/'.$site_logo;
			}

			$this->data['razorpay'] = $config;

			$content 	= 'checkout_razorpay';

			$pagetitle 	= get_languageword('checkout_with_Razorpay');

		} else {

			$gateway_details = $this->base_model->get_payment_gateways('', 'Active');

			$content 	= 'checkout';

			$pagetitle 	= get_languageword('checkout');
		}



		$this->data['record'] = $record;
		$this->data['payment_gateways'] = $gateway_details;


		$this->data['activemenu'] 	= "buy_courses";
		$this->data['content'] 		= $content;
		$this->data['pagetitle'] 	= $pagetitle;
		$this->_render_page('template/site/site-template', $this->data);
	}











}
?>