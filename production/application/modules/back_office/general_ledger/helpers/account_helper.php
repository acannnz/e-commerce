<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

final class account_helper
{
	private static $_tbl = "Mst_Akun";
	private static $_pri = "Akun_ID";
	private static $data =  array();
	
	public static function get_child( $parent_number = NULL, $parent_level )
	{
		$query = self::ci()->db
			->like("Akun_No", $parent_number, "after")
			->where(array(
					"Akun_No !=" => $parent_number,
					"Level_Ke" => $parent_level + 1,
				))
			->get( self::$_tbl )
			;
		
		return ( $query->num_rows() > 0) ? $query->result() : FALSE;
	}
	
	public static function get_last_child( $parent_number = NULL, $parent_level )
	{
		$query = self::ci()->db
			->like("Akun_No", $parent_number, "after")
			->where(array(
					"Akun_No !=" => $parent_number,
					"Level_Ke" => $parent_level + 1,
					"Akun_No !=" => sprintf("%s%d", $parent_number, 99), // yg tidak diakhiri dengan 99
				))
			->order_by("Akun_No", "DESC")
			->get( self::$_tbl )
			;
		
		return ( $query->num_rows() > 0) ? $query->row() : FALSE;
	}
		
	public static function get_parent_tree( $parent_id, $data = array() )
	{
		$row = self::ci()->db
				->select( "id, parent_id" )
				->where(array(
						"deleted_at" => NULL,
						"id" => $parent_id
					))
				->get(self::$_tbl)
				->row();
						
		if ($row->parent_id == 0)
		{
			array_unshift($data, $row->id);  
			
			return $data;
		} 
		else 
		{
			array_unshift($data, $row->id);  
			
			return self::get_parent_tree( $row->parent_id, $data );
		}
	}	

	public static function get_parent_root( $parent_id )
	{
		$row = self::ci()->db
				->select( "id, parent_id" )
				->from( self::$_tbl )
				->where(array(
						"deleted_at" => NULL,
						"id" => $parent_id
					))
				->get()
				->row_object();
		
		if ($row->parent_id == 0)
		{
			return $row->id;
		} 
		else 
		{
			self::get_parent_tree( $row->parent_id );
		}
	}	
	
	public static function get_parent( $parent_id = 0 )
	{
		return self::ci()->db
			->where(array(
					self::$_pri => $parent_id,
				))
			->get( self::$_tbl )
			->row()
			;
	}

	public static function get_parent_id( $account )
	{
		try { 
		$parent_level = $account->Level_Ke - 1;
		if( $parent_level == 0) return "#";
		
		self::ci()->load->model( "general_ledger/account_m" );
		$concepts = self::ci()->account_m->get_concepts();
		$parent_digit = @$concepts[ @$parent_level ]->Jumlah_Digit;
		$parent_number = substr($account->Akun_No, 0, $parent_digit); // get parent Account Number

		return (int) self::ci()->db
			->where(array(
					"Akun_No" => @$parent_number,
				))
			->get( self::$_tbl )
			->row()
			->{self::$_pri};
		} catch (Exception $e) { }	
		
	}
	
	public static function gen_account_number( $parent_id = 0 )
	{
		if( $parent_id == 0 ) return NULL;
		
		$parent = self::get_parent( $parent_id );
		$last_child = self::get_last_child( $parent->Akun_No, $parent->Level_Ke );
		
		return self::_gen_account_number( $parent, $last_child);
	}
	
	private static function _gen_account_number( $parent, $last_child )
	{
		self::ci()->load->model( "general_ledger/account_m" );
		$concepts = self::ci()->account_m->get_concepts();
		$digit = $concepts[ $parent->Level_Ke + 1 ]->Jumlah_Digit - $concepts[ $parent->Level_Ke ]->Jumlah_Digit;
		
		return !empty($last_child)
			? ++$last_child->Akun_No
			: sprintf("%s%0{$digit}d", @$parent->Akun_No, 1)
			;
	}

	private static function & ci()
	{
		return get_instance();
	}
}
