<?php
defined('BASEPATH') OR exit('No direct script access allowed');

final class Customer_helper
{		
	private static $user_auth;
	private static $_ci;
	
	public static function init()
	{
		self::$_ci = $_ci = self::ci();
		
		$_ci->load->model('category_model');

		$_ci->load->library('simple_login');		
		self::$user_auth = $_ci->simple_login->get_user();
		
	}

	public static function gen_category_number()
	{
		self::init();
		$_ci = self::ci();
		
		$query =  $_ci->db->select("MAX( Right(Kode_Kategori, 3) ) as max_number")
						->get( "{$_ci->category_model->table}" )
						->row();
						
		$max_number = !empty($query->max_number) ? ++$query->max_number : 1;
		$number = (string) (sprintf("CC-%03d", $max_number));
		return $number;
	}	

	private static function & ci()
	{
		return get_instance();
	}	

}
