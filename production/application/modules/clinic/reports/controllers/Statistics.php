<?php 
if ( ! defined('BASEPATH') ){ exit('No direct script access allowed'); }

class Statistics extends ADMIN_Controller 
{
	protected $nameroutes = 'reports/statistics';
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('reports');
		
		
		$this->load->helper("statistics");
		
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
			'widget_total_patient' => statistics_helper::widget_total_patient(),
			'widget_total_visite' => statistics_helper::widget_total_visite(),
			'widget_total_drug' => statistics_helper::widget_total_drug(),
			'widget_total_receipt' => statistics_helper::widget_total_receipt(),
			'monthly_section_visit' => statistics_helper::get_monthly_section_visit(date('Y-m')),
			'monthly_type_visit' => statistics_helper::get_monthly_type_visit(date('Y-m')),
		];
		
		$this->template
			->build( 'statistics/statistics', $data );
	}
	
	public function get_monthly_section_visit()
	{
		if($this->input->is_ajax_request()):
			$type = $this->input->get('type');
			$date = $this->input->get('date');
			
			switch($type):
				case 'month':
					$response = statistics_helper::get_monthly_section_visit($date);
					break;
				case 'year':
					$response = statistics_helper::get_yearly_section_visit($date);
					break;
			endswitch;			

			response_json($response);
		endif;
	}
	
	public function get_monthly_type_visit()
	{
		if($this->input->is_ajax_request()):
			$type = $this->input->get('type');
			$date = $this->input->get('date');
			
			switch($type):
				case 'month':
					$response = statistics_helper::get_monthly_type_visit($date);
					break;
				case 'year':
					$response = statistics_helper::get_yearly_type_visit($date);
					break;
			endswitch;			

			response_json($response);
		endif;
	}
}






