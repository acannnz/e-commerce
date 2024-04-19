<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Files extends Admin_Controller
{
	public function __construct()
    {
        parent::__construct();
		
		$this->load->helper( "specialist/specialist" );
    }
	
	public function index()
	{
		redirect( "" );
	}
	
	public function prepare()
	{
		redirect( "" );
	}
}


