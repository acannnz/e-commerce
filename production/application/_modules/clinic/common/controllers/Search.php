<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Search extends Public_Controller
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function result()
	{
		$data = array();
		$this->load->view( "search/result", $data );
	}
}