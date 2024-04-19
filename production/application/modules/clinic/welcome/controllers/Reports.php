<?php 
if ( ! defined('BASEPATH') ){ exit('No direct script access allowed'); }

class Reports extends Public_Controller 
{
	protected $_translation = 'common/reports';	
	
	public function __construct()
	{
		parent::__construct();
		
		$this->load->helper( "charts/chart" );
		$this->load->helper( "reservations/reservation" );
		$this->load->helper( "registrations/registration" );
		$this->load->helper( "common/zone" );
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
	
	public function charts()
	{
		$data = array(
				"total_tasks" => 0,
				"task_items" => array(),
				"total_charts" => 0,
				"chart_items" => array(),
			);
		
		if( $total_tasks = registration_helper::find_upcoming_tasks() )
		{
			$data['total_tasks'] = $total_tasks;
			$data['task_items'] = registration_helper::get_upcoming_tasks();
		}
		
		if( $total_charts = chart_helper::find_charts_today() )
		{
			$data['total_charts'] = $total_charts;
			$data['chart_items'] = chart_helper::get_charts_today();
		}
		
		$this->load->view( 'reports/charts', $data );
	}
	
	public function lite_tasks()
	{
		$data = array(
				"total_tasks" => 0,
				"task_items" => array(),
			);
		
		if( $total_tasks = registration_helper::find_upcoming_tasks() )
		{
			$data['total_tasks'] = $total_tasks;
			$data['task_items'] = registration_helper::get_upcoming_tasks();
		}
		
		$this->load->view( 'reports/lite/tasks', $data );
	}
	
	public function lite_charts()
	{
		$data = array(
				"total_charts" => 0,
				"chart_items" => array(),
			);
		
		if( $total_charts = chart_helper::find_charts_today() )
		{
			$data['total_charts'] = $total_charts;
			$data['chart_items'] = chart_helper::get_charts_today();
		}
		
		$this->load->view( 'reports/lite/charts', $data );
	}
	
	public function reservations()
	{
		$data = array(
				"items" => array(),
				"items_count" => 0 
			);
		
		if( $items = reservation_helper::get_latest_reservations() )
		{
			$data['items'] = $items;
			$data['items_count'] = count($items);
		}
		
		$this->load->view( 'reports/reservations', $data );
	}
	
	public function registrations()
	{
		$data = array(
				"items" => array(),
				"items_count" => 0 
			);
		
		if( $items = registration_helper::get_latest_registrations() )
		{
			$data['items'] = $items;
			$data['items_count'] = count($items);
		}
		
		$this->load->view( 'reports/registrations', $data );
	}
}

