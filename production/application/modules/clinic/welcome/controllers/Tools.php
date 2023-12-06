<?php 
if ( ! defined('BASEPATH') ){ exit('No direct script access allowed'); }

class Tools extends Public_Controller 
{
	protected $_translation = 'common/reports';	
	
	public function __construct()
	{
		parent::__construct();
	}
	
	public function index()
	{
		if( $this->input->is_ajax_request() )
		{
			echo "<script language=\"javascript\">window.location=\"".base_url("examinations")."\";</script>";
			exit();
		} else
		{
			redirect( "examinations" );
		}
	}
	
	public function calendar()
	{
		$data = array();
		
		$this->load->view( 'tools/calendar', $data );
	}
	
	public function calculator()
	{
		$data = array();
		
		$this->load->view( 'tools/calculator', $data );
	}
	
	public function notes()
	{
		$data = array();
		
		$this->load->view( 'tools/notes', $data );
	}
}

