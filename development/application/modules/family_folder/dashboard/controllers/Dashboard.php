<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Dashboard extends ADMIN_Controller
{
	public function __construct()
	{
		parent::__construct();
		
		$this->load->language('folder/family');
	}
	
	public function index()
	{
		$this->template
			->build("dashboard", $this->data);
	}
}