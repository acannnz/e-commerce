<?php
defined('BASEPATH') OR exit('No direct script access allowed');

final class Marketing_helper
{		
	private static $user_auth;
	private static $_ci;
	
	public static function init()
	{
		self::$_ci = $_ci = self::ci();
		
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
	
	public static function get_contract($id)
	{
		self::init();
		$_ci = self::ci();
		
		$db_select = "
			a.*, 
			b.Nama_Customer,
			b.Kode_Customer,
			c.Akun_Name Nama_Akun,
			c.Akun_No  
		";		
		$dbo = $_ci->load->database('BO_1', TRUE);

		$query =  $_ci->db->select( $db_select )
						->from("{$_ci->contract_model->table} a")
						->join("{$_ci->customer_model->table} b", "a.CustomerID = b.Customer_ID", "INNER" )
						->join("{$dbo->database}.dbo.{$_ci->account_model->table} c", "a.AkunPiutang_ID = c.Akun_ID", "LEFT OUTER" )
						->where("CustomerKerjasamaID", $id)
						->get();
						
		return $query->row();	
	}
	
	public static function get_contract_service($id)
	{
		self::init();
		$_ci = self::ci();
		
		$db_select = <<<EOSQL
			a.ListHargaID,
			b.JasaID,
			b.JasaName,
			b.NamaKelas,
			b.SpesialisName,
			b.SubSpesialisName,
			b.NamaDokter,
			a.ListHargaID,
			b.Cyto,
			a.Included,
			a.Ditanggung,
			a.AutoSystem,
			a.TglUpdate,
			b.Lokasi,
			b.PasienKTP,
			a.Harga_Baru,
			a.Harga_Lama,
			b.NamaKelas,
			c.KategoriName
EOSQL;

		$query = $_ci->db
			->select( $db_select )
			->from("{$_ci->contract_service_model->table} a")
			->join("VW_ListHarga b", "a.ListHargaID = b.ListHargaID ", "INNER")
			->join("SIMmKategoriOperasi c", "b.KategoriOperasiID = c.KategoriID ", "LEFT OUTER")
			->where('CustomerKerjasamaID', $id)
			->get()
			;
		
		$collection = [];
		foreach($query->result() as $row):
			$row->components = self::get_contract_service_component($row->ListHargaID);
			$collection[] = $row;
		endforeach;
		
		return $collection;
	}
	
	public static function get_contract_service_component( $id )
	{
		self::init();
		$_ci = self::ci();
		
		$db_select = "
			b.KomponenName, 
			a.HargaLama As Harga_Lama, 
			a.HargaBaru AS Harga_Baru,  
			a.Prosentase, 
			a.NilaiPersen, 
			a.AkunNo,  
			a.ListHargaID,
			b.KomponenBiayaID  
		";		
		$query =  $_ci->db->select( $db_select )
						->from("{$_ci->contract_service_component_model->table} a")
						->join("SIMmKomponenBiaya b", "a.KomponenBiayaID = b.KomponenBiayaID", "INNER" )
						->where("ListHargaID", $id)
						->group_by("
							b.KomponenName, a.HargaLama, a.HargaBaru,  
							a.Prosentase, a.NilaiPersen, a.AkunNo,  
							a.ListHargaID,b.KomponenBiayaID
						")
						->get();
						
		return $query->result();
	}
	
	public static function get_contract_drug( $id )
	{
		self::init();
		$_ci = self::ci();
		
		$db_select = "
			a.Barang_ID,
			b.Kode_Barang,
			b.Nama_Barang,
			b.Satuan_Stok AS Satuan,
			a.Include,
			a.Ditanggung
		";		
		$query =  $_ci->db->select( $db_select )
						->from("{$_ci->contract_drug_model->table} a")
						->join("VW_Barang b", "a.Barang_ID = b.Barang_ID", "INNER" )
						->where("a.CustomerKerjasamaID", $id)
						->get();

		return $query->result();
	}
	
	public static function get_service_component( $id )
	{
		self::init();
		$_ci = self::ci();
		
		$db_select = "
			b.KomponenName, 
			a.HargaLama As Harga_Lama, 
			a.HargaBaru AS Harga_Baru,  
			a.Prosentase, 
			a.NilaiPersen, 
			a.AkunNo,  
			a.ListHargaID,
			b.KomponenBiayaID  
		";		
		$query =  $_ci->db->select( $db_select )
						->from("SIMdListHargaDetail a")
						->join("SIMmKomponenBiaya b", "a.KomponenBiayaID = b.KomponenBiayaID", "INNER" )
						->where("ListHargaID", $id)
						->group_by("
							b.KomponenName, a.HargaLama, a.HargaBaru,  
							a.Prosentase, a.NilaiPersen, a.AkunNo,  
							a.ListHargaID,b.KomponenBiayaID
						")
						->get();
						
		return $query->result();
	}
	
	public static function create_contract($contract, $contract_service, $contract_component, $contract_drug)
	{
		$_ci = self::ci();		
		
		$_ci->db->trans_begin();
			
			settype($contract['MaxHariRawatPerOpname'], 'int');
			settype($contract['MaxHariRawatPerTahun'], 'int');
			settype($contract['MaxRIRupiahPerTahun'], 'int');
			$id = $_ci->contract_model->create($contract);	

			if(!empty($contract_service) && !empty($contract_component)):
				$contract_service = array_map(function($arr) use ($id){
									return $arr + ['CustomerKerjasamaID' => $id, 'TglPerubahanHarga' => date('Y-m-d')];
								}, $contract_service);
				$_ci->contract_service_model->mass_create($contract_service);
				
				$contract_component = array_map(function($arr) use ($id){
									return $arr + ['CustomerKerjasamaID' => $id];
								}, $contract_component);
				$_ci->contract_service_component_model->mass_create($contract_component);
			endif;
			
			if(!empty($contract_drug)):		
				$contract_drug = array_map(function($arr) use ($id){
									return $arr + ['CustomerKerjasamaID' => $id];
								}, $contract_drug);
				$_ci->contract_drug_model->mass_create($contract_drug);
			endif;
		if($_ci->db->trans_status() === FALSE)
		{
			$_ci->db->trans_rollback();
			return ["status" => 'error', "message" => lang('global:created_failed')];
		}
		
		$_ci->db->trans_commit();
		return ["status" => 'success', "message" => lang('global:created_successfully')];
	}
	
	public static function update_contract($id, $contract, $contract_service, $contract_component, $contract_drug)
	{
		$_ci = self::ci();		
		
		$_ci->db->trans_begin();
			
			settype($contract['MaxHariRawatPerOpname'], 'int');
			settype($contract['MaxHariRawatPerTahun'], 'int');
			settype($contract['MaxRIRupiahPerTahun'], 'int');
			$_ci->contract_model->update($contract, $id);	
			
			if(!empty($contract_service) && !empty($contract_component)):
				$_ci->contract_service_component_model->delete($id);
				$_ci->contract_service_model->delete($id);	
						
				$contract_service = array_map(function($arr) use ($id){
									return $arr + ['CustomerKerjasamaID' => $id, 'TglPerubahanHarga' => date('Y-m-d')];
								}, $contract_service);
				$_ci->contract_service_model->mass_create($contract_service);
				
				$contract_component = array_map(function($arr) use ($id){
									return $arr + ['CustomerKerjasamaID' => $id];
								}, $contract_component);
				$_ci->contract_service_component_model->mass_create($contract_component);
			endif;
			
			if(!empty($contract_drug)):
				$_ci->contract_drug_model->delete($id);
				$contract_drug = array_map(function($arr) use ($id){
									return $arr + ['CustomerKerjasamaID' => $id];
								}, $contract_drug);
				$_ci->contract_drug_model->mass_create($contract_drug);
			endif;
			
		if($_ci->db->trans_status() === FALSE)
		{
			$_ci->db->trans_rollback();
			return ["status" => 'error', "message" => lang('global:updated_failed')];
		}
		
		$_ci->db->trans_commit();
		return ["status" => 'success', "message" => lang('global:updated_successfully')];
	}

	private static function & ci()
	{
		return get_instance();
	}	

}
