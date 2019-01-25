<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends MY_Controller {

	function __construct()
	{
		parent::__construct();		
		$this->load->library(array('session'));
	}
	
	public function index()
	{
		$this->load->view('welcome_message');
	}
}
