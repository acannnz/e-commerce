<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Zone_m extends Public_Model
{
	public $table = 'common_zones';
	public $primary_key = 'id';
	
	public function __construct()
	{
		$this->rules = array('insert' => array(
				'code' => array(
						'field' => 'code',
						'label' => lang( 'zones:code_label' ),
						'rules' => 'required'
					),
				'parent_id' => array(
						'field' => 'parent_id',
						'label' => lang( 'zones:parent_label' ),
						'rules' => ''
					),
				'zone_type' => array(
						'field' => 'zone_type',
						'label' => lang( 'zones:type_label' ),
						'rules' => 'required'
					),
				'zone_name' => array(
						'field' => 'zone_name',
						'label' => lang( 'zones:name_label' ),
						'rules' => 'required'
					),
				'zone_island' => array(
						'field' => 'zone_island',
						'label' => lang( 'zones:island_label' ),
						'rules' => ''
					),
				'zone_postcode' => array(
						'field' => 'zone_postcode',
						'label' => lang( 'zones:postcode_label' ),
						'rules' => ''
					),
				'zone_description' => array(
						'field' => 'zone_description',
						'label' => lang( 'zones:description_label' ),
						'rules' => ''
					),
				'state' => array(
						'field' => 'state',
						'label' => lang( 'zones:state_label' ),
						'rules' => ''
					),
			));
		
		parent::__construct();
	}
	
	public function options_country()
	{
		$options = $this
			->as_dropdown( "zone_name" )
			->order_by( "zone_name" )
			->get_all(array("zone_type" => "COUNTRY", "state" => 1))
			;
		
		if( is_array($options) ){ return $options; }
		return (array());
	}
	
	public function options_province( $parent_id=0 )
	{
		$options = $this
			->as_dropdown( "zone_name" )
			->order_by( "zone_name" )
			->get_all(array("zone_type" => "PROVINCE", "state" => 1, "parent_id" => $parent_id))
			;
			
		return (is_array($options) ? $options : array());
	}
	
	public function options_county( $parent_id=0 )
	{
		$options = $this
			->as_dropdown( "zone_name" )
			->order_by( "zone_name" )
			->get_all(array("zone_type" => "COUNTY", "state" => 1, "parent_id" => $parent_id))
			;
			
		return (is_array($options) ? $options : array());
	}
	
	public function options_district( $parent_id=0 )
	{
		$options = $this
			->as_dropdown( "zone_name" )
			->order_by( "zone_name" )
			->get_all(array("zone_type" => "DISTRICT", "state" => 1, "parent_id" => $parent_id))
			;
			
		return (is_array($options) ? $options : array());
	}
	
	public function options_area( $parent_id=0 )
	{
		$options = $this
			->as_dropdown( "zone_name" )
			->order_by( "zone_name" )
			->get_all(array("zone_type" => "AREA", "state" => 1, "parent_id" => $parent_id))
			;
			
		return (is_array($options) ? $options : array());
	}
}