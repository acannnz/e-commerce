<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Zones extends Admin_Controller
{
	
	public function __construct()
	{
		parent::__construct();
		
		$this->load->model('country_model');
		$this->load->model('province_model');
		$this->load->model('county_model');
		$this->load->model('district_model');
		$this->load->model('village_model');
		$this->load->model('area_model');
		
		$this->page = "common_zones";
		$this->template->title( lang("zones:page") );
	}
	
	public function populate_country()
	{
		$item["populate"] = array();
		if( $result = $this->country_model->to_list_data() )
		{
			foreach( $result as $value => $label )
			{
				array_push( $item["populate"], array("value" => @$value, "label" => @$label) );
			}
		}
		
		response_json( $item );
	}
	
	public function populate_province( $parent_id=0 )
	{
		$item["populate"] = array();
		if( $result = $this->province_model->to_list_data( $parent_id ) )
		{
			foreach( $result as $value => $label )
			{
				array_push( $item["populate"], array("value" => @$value, "label" => @$label) );
			}
		}
		
		response_json( $item );
	}
	
	public function populate_county( $parent_id=0 )
	{
		$item["populate"] = array();
		if( $result = $this->county_model->to_list_data( $parent_id ) )
		{
			foreach( $result as $value => $label )
			{
				array_push( $item["populate"], array("value" => @$value, "label" => @$label) );
			}
		}
		
		response_json( $item );
	}
	
	public function populate_district( $parent_id=0 )
	{
		$item["populate"] = array(); 
		if( $result = $this->district_model->to_list_data( $parent_id ) )
		{
			foreach( $result as $value => $label )
			{
				array_push( $item["populate"], array("value" => @$value, "label" => @$label) );
			}
		}
		
		response_json( $item );
	}

	public function populate_village( $parent_id=0 )
	{
		$item["populate"] = array();
		if( $result = $this->village_model->to_list_data( $parent_id ) )
		{
			foreach( $result as $value => $label )
			{
				array_push( $item["populate"], array("value" => @$value, "label" => @$label) );
			}
		}
		
		response_json( $item );
	}	
	
	public function populate_area( $parent_id=0 )
	{
		$item["populate"] = array();
		if( $result = $this->area_model->to_list_data( $parent_id ) )
		{
			foreach( $result as $value => $label )
			{
				array_push( $item["populate"], array("value" => @$value, "label" => @$label) );
			}
		}
		
		response_json( $item );
	}
}