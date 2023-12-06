<?php 
if ( ! defined('BASEPATH') ){ exit('No direct script access allowed'); }

class Statistics extends Public_Controller 
{
	protected $_translation = 'common/reports';	
	
	public function __construct()
	{
		parent::__construct();
		
		$this->load->helper( "reservations/reservation" );
		$this->load->helper( "registrations/registration" );
		$this->load->helper( "common/patient" );
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
	
	public function statistics()
	{
		$data = array();
		
		$this->load->view( 'statistics', $data );
	}
	
	public function patients()
	{
		$data = array(
				"total_patients" => 0,
				"total_patients_year" => 0,
				"total_patients_percentage" => 0,
			);
		
		$data["total_patients"] = (int) patient_helper::get_total_patients();
		$data["total_patients_year"] = (int) patient_helper::get_total_patients_year();
		$data["total_patients_percentage"] = @ceil(($data["total_patients_year"]/$data["total_patients"])*100);
		
		$this->load->view( 'statistics/patients', $data );
	}
	
	public function reservations()
	{
		$data = array(
				"total_reservations" => 0,
				"total_reservations_year" => 0,
				"total_reservations_percentage" => 0,
			);
			
		$data["total_reservations"] = (int) reservation_helper::get_total_reservations();
		$data["total_reservations_year"] = (int) reservation_helper::get_total_reservations_year();
		$data["total_reservations_percentage"] = @ceil(($data["total_reservations_year"]/$data["total_reservations"])*100);
		
		$this->load->view( 'statistics/reservations', $data );
	}
	
	public function registrations()
	{
		$data = array(
				"total_registrations" => 0,
				"total_registrations_year" => 0,
				"total_registrations_percentage" => 0,
			);
			
		$data["total_registrations"] = (int) registration_helper::get_total_registrations();
		$data["total_registrations_year"] = (int) registration_helper::get_total_registrations_year();
		$data["total_registrations_percentage"] = @ceil(($data["total_registrations_year"]/$data["total_registrations"])*100);
		
		$this->load->view( 'statistics/registrations', $data );
	}
	
	public function charts()
	{
		$data = array(
				"total_charts" => 0,
			);
		
		$this->load->view( 'statistics/charts', $data );
	}
}






