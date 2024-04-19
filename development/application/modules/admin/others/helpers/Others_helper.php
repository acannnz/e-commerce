<?php
defined('BASEPATH') OR exit('No direct script access allowed');

final class Others_helper
{		
	private static $user_auth;
	private static $_ci;
	
	public static function init()
	{
		self::$_ci = $_ci = self::ci();
		
		$_ci->load->library('simple_login');		
		self::$user_auth = $_ci->simple_login->get_user();
		
	}
	
	public static function gen_section_number()
	{
		self::init();
		$_ci = self::ci();
		
		$query =  $_ci->db->select("MAX( Right(SectionID, 3) ) as max_number")
						->where([
							"LEFT([SectionID], 3)=" => 'SEC',
							"LEN([SectionID])=" => 6
						])
						->get( "{$_ci->section_model->table}" )
						->row();
						
		$max_number = !empty($query->max_number) ? ++$query->max_number : 1;
		$number = (string) (sprintf("SEC%03d", $max_number));
		return $number;
	}
	
	public static function get_all_dicount_service( $id )
	{
		$_ci = self::ci();
		
		$_ci->load->model('discount_service_model');
		$_ci->load->model('service_model');
		
		$query = $_ci->db->select('a.*, b.JasaID, b.JasaName')
						->from("{$_ci->discount_service_model->table} a")
						->join("{$_ci->service_model->table} b", "a.IDJasa = b.JasaID", 'INNER')
						->where("a.{$_ci->discount_model->index_key}", $id)
						->get();
		
		return $query->result();
	}

	private static function & ci()
	{
		return get_instance();
	}	

}
