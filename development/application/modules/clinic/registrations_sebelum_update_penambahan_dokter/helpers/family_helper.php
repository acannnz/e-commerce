<?php
defined('BASEPATH') OR exit('No direct script access allowed');

final class Family_helper
{		
	private static $user_auth;
	private static $_ci;
	
	public static function init()
	{
		self::$_ci = $_ci = get_instance();
		
		$_ci->load->model('family_model');
		$_ci->load->model('personal_model');
		$_ci->load->model('personal_to_family_model');

		$_ci->load->library('simple_login');		
		self::$user_auth = $_ci->simple_login->get_user();
		
	}

	public static function gen_family_number( $date = NULL )
	{
		$_ci = self::ci();
		
		$query =  $_ci->db->select("MAX( Right(NoFamily, 4) ) as max_number")
						->get( "{$_ci->family_model->table}" )
						->row();
						
		$max_number = !empty($query->max_number) ? ++$query->max_number : 1;
		$number = (string) (sprintf(self::_gen_family_number(), $max_number));
		return $number;
	}
	
	private static function _gen_family_number()
	{
		$format = "FF%04d";
		return $format;
	}	
	
	public static function get_all($like = NULL, $where, $to_array = FALSE)
	{
		$_ci = self::ci(); 
		
		if (!is_null($like) && !empty($like)){ $_ci->db->or_like($like); }
		if (!is_null($where) && !empty($where)){ $_ci->db->where($where); }
		
		$query = $_ci->db
			->order_by('NoFamily', 'ASC')
			->from("{$_ci->family_model->table} a")
			->join("{$_ci->personal_model->table} b", "a.PersonalIdKK = b.Id", "INNER")
			->get();		
			
		return (TRUE == $to_array) ? $query->result_array() : $query->result();
	}
	
	public static function get_family_member( $id, $where = NULL, $to_array = FALSE )
	{
		self::init();
		$_ci = self::ci(); 
		
		if( !empty($where) ){ $_ci->db->where( $where ); }
		
		$query = $_ci->db
			->order_by('a.id', 'ASC')
			->from("{$_ci->personal_model->table} a")
			->join("{$_ci->personal_to_family_model->table} b", "a.Id = b.PersonalId", "INNER")
			->where("b.FamilyId", $id)
			->get();		
			
		return (TRUE == $to_array) ? $query->result_array() : $query->result();
		
	}
	
	public static function get_option_family_member( $id )
	{
		self::init();
		$_ci = self::ci(); 
		
		$option_data = [];
		$collection = self::get_family_member( $id );
		foreach($collection as $row):
			$option_data[ $row->PersonalId ] = $row->PersonalName;
		endforeach;
		
		return $option_data;
	}

	public static function get_option_family_member_by_relation( $family_id, $relation )
	{
		self::init();
		$_ci = self::ci(); 
		
		$option_data = [];
		$collection = self::get_family_member( $family_id, ['b.Relation' => $relation] );
		foreach($collection as $row):
			$option_data[ $row->PersonalId ] = $row->PersonalName;
		endforeach;
		
		return $option_data;
	}

	private static function & ci()
	{
		return get_instance();
	}	

}
