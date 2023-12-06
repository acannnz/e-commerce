<?php if (!defined('BASEPATH')) exit('No direct script access allowed.');

final class chart_template_helper
{
	private static $_tbl = "chart_templates";
	
	public static function form_injection( $chart_number=NULL )
	{
		$data = array( 
				"chart_number" => $chart_number,
			);
		return self::ci()->load->view( "common/chart/templates/form/injection", $data, TRUE );
	}
	
	public static function get_drug_by_cc( $chief_complaint )
	{
		$chief_complaint = (string) trim($chief_complaint);		
		
		return self::ci()->db
			->where(array(
					"deleted_at" => NULL, 
					"chief_complaint" => $chief_complaint,
				))
			->get( self::$_tbl )
			->row()
			;
	}
	
	public static function find_drug_by_cc( $chief_complaint )
	{
		$chief_complaint = (string) trim($chief_complaint);	
		
		return (int) self::ci()->db
			->where(array(
					"deleted_at" => NULL, 
					"chief_complaint" => $chief_complaint,
				))
			->count_all_results( self::$_tbl )
			;
	}
	
	public static function get_default_template()
	{
		return self::ci()->db
			->where(array(
					"deleted_at" => NULL, 
					"is_default" => 1,
				))
			->get( self::$_tbl )
			->row()
			;
	}
	
	public static function find_default_template()
	{
		return (int) self::ci()->db
			->where(array(
					"deleted_at" => NULL, 
					"is_default" => 1,
				))
			->count_all_results( self::$_tbl )
			;
	}
	
	public static function get_template( $template_id )
	{
		$template_id = (int) $template_id;		
		
		return self::ci()->db
			->where(array(
					"deleted_at" => NULL, 
					"id" => $template_id,
				))
			->get( self::$_tbl )
			->row()
			;
	}
	
	public static function find_template( $template_id )
	{
		$template_id = (int) $template_id;		
		
		return (int) self::ci()->db
			->where(array(
					"deleted_at" => NULL, 
					"id" => $template_id,
				))
			->count_all_results( self::$_tbl )
			;
	}
	
	private static function & ci()
	{
		return get_instance();
	}
}