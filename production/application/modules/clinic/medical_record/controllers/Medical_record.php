<?php 
if ( ! defined('BASEPATH') ){ exit('No direct script access allowed'); }

class Medical_record extends ADMIN_Controller 
{
	protected $nameroutes = 'reports/medical_record';
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('medical_record');
		
	}
	
	public function index()
	{		
		$data = [
			'nameroutes' => $this->nameroutes,
			'form' => TRUE,
			'highcharts' => TRUE,
			'datepicker' => TRUE,
			'is_dashboard' => TRUE,
			'navigation_minimized' => TRUE,
		];
		
		$this->template
			->build( 'medical_record/reports/landing', $data );
	}
}






