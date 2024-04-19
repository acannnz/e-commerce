<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

final class chart_file_helper
{
	public static function prepare_file_from_chart( $chart_number, $file_type )
	{
		$CI = self::ci();
		
		$chart_number = (string) trim($chart_number);
		if( "" === $chart_number )
		{
			return FALSE;
		}
		
		$CI->load->model( "charts/chart_m" );
		if( ! $CI->chart_m->count(array("chart_number" => $chart_number)) )
		{
			return FALSE;
		}
		
		if( ! $_chart = $CI->chart_m->get(array("chart_number" => $chart_number)) )
		{
			return FALSE;
		}
		
		$blank_data = array(
				"chart_id" => @$_chart->id,
				"chart_number" => @$_chart->chart_number,
				"registration_id" => @$_chart->registration_id,
				"registration_number" => @$_chart->registration_number,
				"reservation_id" => @$_chart->reservation_id,
				"reservation_number" => @$_chart->reservation_number,
				"patient_id" => @$_chart->patient_id,
				"mr_number" => @$_chart->mr_number,
				
				'file_type' => $file_type,
				'file_title' => NULL,
				'file_description' => NULL,
				'file_datetime' => NULL,
				'file_path' => NULL,
				'file_name' => NULL,
				'file_ext' => NULL,
				'file_size' => 0,
				'is_image' => 0,
				'image_width' => 0,
				'image_height' => 0,
				'state' => 1,
			);
		
		$CI->load->model( "files/chart_file_m" );
		if( ! $CI->chart_file_m->insert( $blank_data ) )
		{
			return FALSE;
		}
		
		return TRUE;
	}
	
	public static function get_file_by_chart( $chart_number, $file_type=NULL )
	{
		$CI = self::ci();
		
		$chart_number = (string) trim($chart_number);
		$file_type = (string) trim($file_type);
		
		$CI->load->model( "files/chart_file_m" );
		if( "" == $file_type ){ return (object) $CI->chart_file_m->get(array("chart_number" => $chart_number)); }
		return (object) $CI->chart_file_m->get(array("chart_number" => $chart_number, "file_type" => $file_type));
	}
	
	public static function find_file_by_chart( $chart_number, $file_type=NULL )
	{
		$CI = self::ci();
		
		$chart_number = (string) trim($chart_number);
		$file_type = (string) trim($file_type);
		
		$CI->load->model( "files/chart_file_m" );
		if( "" == $file_type ){ return (int) $CI->chart_file_m->count(array("chart_number" => $chart_number)); }
		return (int) $CI->chart_file_m->count(array("chart_number" => $chart_number, "file_type" => $file_type));
	}
	
	public static function get_file( $id )
	{
		$CI = self::ci();
		
		$id = (int) $id;
		
		$CI->load->model( "files/chart_file_m" );
		return (object) $CI->chart_file_m->get(array("id" => $id));
	}
	
	public static function find_file( $id )
	{
		$CI = self::ci();
		
		$id = (int) $id;
		
		$CI->load->model( "files/chart_file_m" );
		return (int) $CI->chart_file_m->count(array("id" => $id));
	}
	
	private static function & ci()
	{
		return get_instance();
	}
}

