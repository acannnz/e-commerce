<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

final class service_helper
{
	private static $_tbl = "common_services";
	
	public static function get_all_services()
	{
		return (array) self::ci()->db
			->select( "*" )
			->from( self::$_tbl )
			->where(array(
					"deleted_at" => NULL,
				))
			->get()
			->result()
			;
	}
	
	public static function find_all_services()
	{
		return (int) self::ci()->db
			->where(array(
					"deleted_at" => NULL,
				))
			->count_all_results( self::$_tbl )
			;
	}
	
	public static function get_service( $service_id )
	{
		$service_id = (int) $service_id;		
		
		return (object) self::ci()->db
			->select( "*" )
			->from( self::$_tbl )
			->where(array(
					"deleted_at" => NULL, 
					"id" => $service_id,
				))
			->get()
			->row()
			;
	}
	
	public static function find_service( $service_id )
	{
		$service_id = (int) $service_id;		
		
		return (int) self::ci()->db
			->where(array(
					"deleted_at" => NULL, 
					"id" => $service_id,
				))
			->count_all_results( self::$_tbl )
			;
	}
	
	private static function & ci()
	{
		return get_instance();
	}
}
