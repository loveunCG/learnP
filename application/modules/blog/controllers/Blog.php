<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Blog extends MY_Controller 
{
	function __construct()
	{
		parent::__construct();
		
		$this->load->library(array('session'));
		$this->load->library(array('ion_auth','form_validation'));
	}
	/*** Displays the Index Page**/
	function index()
	{
		$this->data['activemenu'] 	= "blog";		
		$this->data['content'] 		= 'index';
		$this->_render_page('template/site/site-template', $this->data);
	}
	
	function single()
	{
		$this->data['activemenu'] 	= "blog";		
		$this->data['content'] 		= 'single';
		$this->_render_page('template/site/site-template', $this->data);
	}

	function pages($slug = "")
	{
		$slug = str_replace('_', '-', $slug);

		if(empty($slug)) {

			redirect('/');
		}

		$this->load->model('base_model');
		$page_info= $this->base_model->get_page_by_title_content($slug);

		if(empty($page_info))
			redirect('/');

		$this->data['page_info'] 	= $page_info;
		$this->data['pagetitle'] 	= (!empty($page_info)) ? $page_info[0]->name : get_languageword('tutors_system');
		$this->data['meta_description'] = (!empty($page_info)) ? $page_info[0]->meta_description : get_languageword('meta_description');
		$this->data['meta_keywords'] = (!empty($page_info)) ? $page_info[0]->seo_keywords : get_languageword('tutors_system');
		$this->data['content'] 		= 'page_content';

		$this->_render_page('template/site/site-template', $this->data);
	
	}

}
?>