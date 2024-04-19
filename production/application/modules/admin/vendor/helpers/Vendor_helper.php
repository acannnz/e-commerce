<?php
defined('BASEPATH') OR exit('No direct script access allowed');

final class Vendor_helper
{		
	private static $user_auth;
	private static $_ci;
	
	public static function init()
	{
		self::$_ci = $_ci = self::ci();
		
		$_ci->load->model('category_model');
		$_ci->load->model('sub_specialist_model');

		$_ci->load->library('simple_login');		
		self::$user_auth = $_ci->simple_login->get_user();
		
	}

	public static function gen_category_number()
	{
		self::init();
		$_ci = self::ci();
		
		$query =  $_ci->db->select("MAX( Right(Kode_Kategori, 3) ) as max_number")
						->where("Kode_Kategori !=", "LAIN")
						->get( "{$_ci->category_model->table}" )
						->row();
						
		$max_number = !empty($query->max_number) ? ++$query->max_number : 1;
		$number = (string) (sprintf("V-%03d", $max_number));
		return $number;
	}
	
	// SELECT MAX([SubSpesialisID]) AS MyID FROM [SIMmSubSpesialis] 
	// WHERE LEN([SubSpesialisID])=7 AND LEFT([SubSpesialisID], 3)='SUB'
	public static function gen_sub_specialist_number()
	{
		self::init();
		$_ci = self::ci();
		
		$query =  $_ci->db->select("MAX(SubSpesialisID) as max_number")
						->where([
							"LEFT([SubSpesialisID], 3)=" => 'SUB',
							"LEN([SubSpesialisID])=" => 7
						])
						->get( "{$_ci->sub_specialist_model->table}" )
						->row();
						
		if( !empty($query->max_number) )
		{
			$number = ++$query->max_number;
		} else {
			$number = (sprintf("SUB%04d", 1));
		}

		return $number;
	}
	
	public static function get_drug_commission_patient( $id )
	{
		self::init();
		$_ci = self::ci();
		
		$query =  $_ci->db->select("a.*, b.Nama_Supplier, c.JenisKerjasama")
						->from( "{$_ci->drug_commission_patient_model->table} a" )
						->join( "{$_ci->vendor_model->table} b", "a.DokterID = b.Supplier_ID", "INNER" )
						->join( "{$_ci->patient_type_model->table} c", "a.JenisPasienID = c.JenisKerjasamaID", "INNER" )
						->where("DokterID", $id)
						->get();
						
		return $query->result();
	}

	public static function get_drug_commission_item( $id )
	{
		self::init();
		$_ci = self::ci();
		
		$query =  $_ci->db->select("a.*, b.Nama_Supplier, c.Kode_Barang, c.Nama_Barang")
						->from( "{$_ci->drug_commission_item_model->table} a" )
						->join( "{$_ci->vendor_model->table} b", "a.DokterID = b.Supplier_ID", "INNER" )
						->join( "{$_ci->item_model->table} c", "a.Barang_ID = c.Barang_ID", "INNER" )
						->where("DokterID", $id)
						->get();
						
		return $query->result();
	}
	
	public static function get_drug_commission_tht( $id )
	{
		self::init();
		$_ci = self::ci();
		
		$query =  $_ci->db->select("a.*, b.Nama_Supplier, c.JenisKerjasama")
						->from( "{$_ci->drug_commission_tht_model->table} a" )
						->join( "{$_ci->vendor_model->table} b", "a.DokterID = b.Supplier_ID", "INNER" )
						->join( "{$_ci->patient_type_model->table} c", "a.JenisPasienID = c.JenisKerjasamaID", "INNER" )
						->where("DokterID", $id)
						->get();
						
		return $query->result();
	}
	
	public static function drug_commission_update( $dc, $dc_item, $dc_patient, $dc_tht )
	{
		$_ci = self::ci();		
		
		$_ci->db->trans_begin();
			
			$delete_dc_not_item = [];
			foreach( $dc_item as $row ):
				if( ! $_ci->drug_commission_item_model->count_all(['DokterID' => $dc['DokterID'], 'Barang_ID' => $row['Barang_ID']]))
				{
					$_ci->drug_commission_item_model->create( $row );
				}	
				$delete_dc_not_item[] = $row['Barang_ID'];
			endforeach;

			$delete_dc_not_patient = [];
			foreach( $dc_patient as $row ):
				if( $_ci->drug_commission_patient_model->count_all(['DokterID' => $dc['DokterID'], 'JenisPasienID' => $row['JenisPasienID']]))
				{
					$_ci->drug_commission_patient_model->update_by( ['Komisi' => $row['Komisi']], ['DokterID' => $dc['DokterID'], 'JenisPasienID' => $row['JenisPasienID']] );
				} else {
					$_ci->drug_commission_patient_model->create( $row );
				}
				$delete_dc_not_patient[] = $row['JenisPasienID'];
			endforeach;

			$delete_dc_not_tht = [];
			foreach( $dc_tht as $row ):
				if( $_ci->drug_commission_tht_model->count_all(['DokterID' => $dc['DokterID'], 'JenisPasienID' => $row['JenisPasienID']]))
				{
					$_ci->drug_commission_tht_model->update_by( ['NilaiTHT' => $row['NilaiTHT']], ['DokterID' => $dc['DokterID'], 'JenisPasienID' => $row['JenisPasienID']] );
				} else {
					$_ci->drug_commission_tht_model->create( $row );
				}
				$delete_dc_not_tht[] = $row['JenisPasienID'];
			endforeach;
			
			$_ci->db->where_not_in('Barang_ID', $delete_dc_not_item )
					->where('DokterID', $dc['DokterID'])
					->delete( $_ci->drug_commission_item_model->table );
	
			$_ci->db->where_not_in('JenisPasienID', $delete_dc_not_patient )
					->where('DokterID', $dc['DokterID'])
					->delete( $_ci->drug_commission_patient_model->table );

			$_ci->db->where_not_in('JenisPasienID', $delete_dc_not_tht )
					->where('DokterID', $dc['DokterID'])
					->delete( $_ci->drug_commission_tht_model->table );

		if($_ci->db->trans_status() === FALSE)
		{
			$_ci->db->trans_rollback();
			return ["status" => 'error', "message" => lang('global:created_failed')];
		}
		//$_ci->db->trans_rollback();
		$_ci->db->trans_commit();
		return ["status" => 'success', "message" => lang('global:created_successfully')];
	}

	private static function & ci()
	{
		return get_instance();
	}	

}
