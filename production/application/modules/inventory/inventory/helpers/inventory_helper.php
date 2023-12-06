<?php
defined('BASEPATH') OR exit('No direct script access allowed');

final class Inventory_helper
{
	
	public static function gen_item_code()
	{
		$_ci = self::ci();
		
		return sprintf("%s%s", date('ny'), gen_unique_number(5));
	}
	
	public static function gen_purchase_evidence_number( $date )
	{
		$_ci = self::ci();
		
		$date = DateTime::createFromFormat('Y-m-d', $date );
		
		$date_start = $date->format( "Y-m-01 00:00:00.000" );
		$date_end = $date->format( "Y-m-t 00:00:00.000" );
		$date_y = $date->format( "y" );
		$date_m = $date->format( "m" );
		$date_d = $date->format( "d" );
		
		$query =  $_ci->db->select("MAX( Right(No_Permintaan, 6) ) as max_number")
						->where(array(
							"DATEPART(YEAR, Tgl_Permintaan) =" => $date->format( "Y" ),
						))
						->like("No_Permintaan", "/PRQ/")
						->get( "BL_trPermintaan" )
						->row();
						
		$max_number = (!empty($query->max_number)) ? ++$query->max_number : 1;
		$number = (string) (sprintf(self::_gen_purchase_evidence_number(), $date_y, $date_m, $max_number));
		return $number;
	}
	
	private static function _gen_purchase_evidence_number()
	{
		$format = "%02d/%02d/PRQ/%06d";
		return $format;
	}	
	
	public static function gen_goods_receipt_evidence_number( $date )
	{
		$_ci = self::ci();
		
		$date = DateTime::createFromFormat('Y-m-d', $date );
		
		$date_start = $date->format( "Y-m-01 00:00:00.000" );
		$date_end = $date->format( "Y-m-t 00:00:00.000" );
		$date_y = $date->format( "y" );
		$date_m = $date->format( "m" );
		$date_d = $date->format( "d" );
		
		$query =  $_ci->db->select("MAX( Right(No_Penerimaan, 6) ) as max_number")
						->where(array(
							"DATEPART(YEAR, Tgl_Penerimaan) =" => $date->format( "Y" ),
						))
						->like("No_Penerimaan", "/RC/")
						->get( "BL_trPenerimaan" )
						->row();
						
		$max_number = (!empty($query->max_number)) ? ++$query->max_number : 1;
		$number = (string) (sprintf(self::_gen_goods_receipt_evidence_number(), $date_y, $date_m, $max_number));
		return $number;
	}
	
	private static function _gen_goods_receipt_evidence_number()
	{
		$format = "%02d/%02d/RC/%06d";
		return $format;
	}
	
	public static function gen_inquiry_number( $date, $section_id = NULL )
	{
		$_ci = self::ci();
		
		if ( !empty($section_id) )
		{
			$_ci->load->model('section_model');
			$section = $_ci->section_model->get_one( $section_id );
			$_ci->db->where("SectionAsal", $section_id);
		} else {
			$_ci->db->where("RIGHT(LEFT(LTRIM([NoBukti]),7),3)", "AMP");
		}
		
		$date = DateTime::createFromFormat('Y-m-d', $date);
		$date_start = $date->format( "Y-m-01 00:00:00.000" );
		$date_end = $date->format( "Y-m-t 00:00:00.000" );
		$date_y = $date->format( "y" );
		$date_m = $date->format( "m" );
		
		$query =  $_ci->db->select("MAX( Right(NoBukti, 6) ) as max_number")
						->where(array(
							"DATEPART(YEAR, Tanggal) =" => $date->format( "Y" ),
						))
						->get( "GD_trAmprahan" )
						->row();
						
		$max_number = (!empty($query->max_number)) ? ++$query->max_number : 1;
		$number = (string) (sprintf(self::_gen_format_inquiry_number(), $date_y, $date_m, @$section->KodeNoBukti, $max_number));
		return $number;
	}
	
	private static function _gen_format_inquiry_number()
	{
		$format = "%02d%02dAMP%s-%06d";
		return $format;
	}

	public static function gen_mutation_evidence_number( $date )
	{
		$_ci = self::ci();
		
		$date = DateTime::createFromFormat('Y-m-d', $date);
		$date_start = $date->format( "Y-m-01 00:00:00.000" );
		$date_end = $date->format( "Y-m-t 00:00:00.000" );
		$date_y = $date->format( "y" );
		$date_m = $date->format( "m" );
		$date_d = $date->format( "d" );
		
		$query =  $_ci->db->select("MAX( Right(No_Bukti, 6) ) as max_number")
						->where(array(
							"DATEPART(YEAR, Tgl_Mutasi) =" => $date->format( "Y" ),
						))
						->like("No_Bukti", "-MUT-")
						->get( "GD_trMutasi" )
						->row();
						
		$max_number = (!empty($query->max_number)) ? ++$query->max_number : 1;
		$number = (string) (sprintf(self::_gen_mutation_evidence_number(), $date_y, $date_m, $max_number));
		
		return $number;
	}
	
	private static function _gen_mutation_evidence_number()
	{
		$format = "%02d%02d-MUT-%06d";
		return $format;
	}

	public static function gen_mutation_return_evidence_number( $date )
	{
		$_ci = self::ci();

		$date = DateTime::createFromFormat('Y-m-d', $date);
		$date_start = $date->format( "Y-m-01 00:00:00.000" );
		$date_end = $date->format( "Y-m-t 00:00:00.000" );
		$date_y = $date->format( "y" );
		$date_m = $date->format( "m" );
		$date_d = $date->format( "d" );
		
		$query = $_ci->db->select("MAX( Right(No_Bukti, 6) ) as max_number")
							->where(array(
							"DATEPART(YEAR, Tgl_Mutasi) =" => $date->format( "Y" ),
						))
						->like("No_Bukti", "RTO-")
						->get( "GD_trReturMutasi" )
						->row();
						
		$max_number = (!empty($query->max_number)) ? ++$query->max_number : 1;
		$number = (string) (sprintf(self::_gen_mutation_return_evidence_number(), $date_y, $date_m, $max_number));		
		return $number;
	}
	
	private static function _gen_mutation_return_evidence_number()
	{
		$format = "%02d%02dRTO-%06d";
		return $format;
	}
		
	public static function gen_opname_evidence_number( $date )
	{
		$_ci = self::ci();
		$date = DateTime::createFromFormat('Y-m-d', $date);
		
		$date_start = $date->format( "Y-m-01 00:00:00.000" );
		$date_end = $date->format( "Y-m-t 00:00:00.000" );
		$date_y = $date->format( "y" );
		$date_m = $date->format( "m" );
		$date_d = $date->format( "d" );
				
		$query = $_ci->db->select("MAX( Right(No_Bukti, 6) ) as max_number")
							->where(array(
							"DATEPART(YEAR, Tgl_Opname) =" => $date->format( "Y" ),
						))
						->like("No_Bukti", "-OPN-")
						->get( "GD_trOpname" )
						->row();
						
		$max_number = (!empty($query->max_number)) ? ++$query->max_number : 1;
		$number = (string) (sprintf(self::_gen_opname_evidence_number(), $date_y, $date_m, $max_number));		
		return $number;
	}
	
	private static function _gen_opname_evidence_number()
	{
		$format = "%02d%02d-OPN-%06d";
		return $format;
	}
	
	public static function gen_item_category_code()
	{
		$_ci = self::ci();

		$query =  $_ci->db->select("MAX({$_ci->item_category_model->index_key}) as max_code")
						->where([
							"LEN([{$_ci->item_category_model->index_key}]) =" => 4, 
							"LEFT([{$_ci->item_category_model->index_key}], 1) =" => 'K'
						])
						->get( "{$_ci->item_category_model->table}" )
						->row();
		if(!empty($query->max_code))
		{				
			$code = ++$query->max_code;
		} else {
			$code = sprintf("K%03d", 1);
		}
		return $code;
	}
	
	public static function gen_item_subcategory_code()
	{
		$_ci = self::ci();

		$query =  $_ci->db->select("MAX({$_ci->item_subcategory_model->index_key}) as max_code")
						->where([
							"LEN([{$_ci->item_subcategory_model->index_key}]) =" => 5, 
							"LEFT([{$_ci->item_subcategory_model->index_key}], 1) =" => 'SK'
						])
						->get( "{$_ci->item_subcategory_model->table}" )
						->row();
		if(!empty($query->max_code))
		{				
			$code = ++$query->max_code;
		} else {
			$code = sprintf("SK%03d", 1);
		}
		return $code;
	}

	public static function get_mutation_detail( $mutation_number )
	{
		$query = self::ci()->db->select("
								a.*, 
								b.Kode_Barang,
								b.Nama_Barang,
								b.Konversi,
								c.Kode_Satuan AS Satuan_Beli
							")
							->from("GD_trMutasiDetail a")
							->join("mBarang b", "a.Barang_ID = b.Barang_ID", "LEFT OUTER")
							->join("mSatuan c", "b.Beli_Satuan_Id = c.Satuan_ID", "LEFT OUTER")	
							->where("a.No_Bukti", $mutation_number)
							->get();
							
		return $query->result();
	}

	public static function get_mutation_return( $mutation_return_number )
	{
		$query = self::ci()->db->select("
								a.*, 
								b.Nama_Lokasi SectionAsal, 
								c.Nama_Lokasi SectionTujuan
							")
							->from("GD_trReturMutasi a")
							->join("mLokasi b", "a.Lokasi_Asal = b.Lokasi_ID", "LEFT OUTER")
							->join("mLokasi c", "a.Lokasi_Tujuan = c.Lokasi_ID", "LEFT OUTER")
							->where("a.No_Bukti", $mutation_return_number)
							->get();
							
		return $query->row();
	}

	public static function get_mutation_return_detail( $mutation_return_number )
	{
		$query = self::ci()->db->select("
								a.*, 
								b.Kode_Barang,
								b.Nama_Barang,
								b.Konversi,
								c.Kode_Satuan AS Satuan_Beli,
								d.Kode_Satuan AS Satuan_Stok
							")
							->from("GD_trReturMutasiDetail a")
							->join("mBarang b", "a.Barang_ID = b.Barang_ID", "LEFT OUTER")
							->join("mSatuan c", "b.Beli_Satuan_Id = c.Satuan_ID", "LEFT OUTER")	
							->join("mSatuan d", "b.Stok_Satuan_ID = d.Satuan_ID", "LEFT OUTER")	
							->where("a.No_Bukti", $mutation_return_number)
							->get();
							
		return $query->result();
	}
	
	public static function check_closing_period( $date )
	{
		$date = DateTime::createFromFormat('Y-m-d', $date);
		//$date->modify('-1 day');
		$month = $date->format('m');
		$year = $date->format('Y');
		
		return (boolean)
			self::ci()->db->where(array(
								//"DATEPART(YEAR, Tanggal)=" => $year,
								//"DATEPART(MONTH, Tanggal)=" => $month
								"Tanggal >" => $date->format('Y-m-d') 
							))
							->count_all_results('GD_trPostedBulanan');
	}
						
	public static function insert_warehouse_fifo( Array $args )
	{
		$defaults = [
			'location_id' => 0, 
			'item_id' => 0,  
			'item_unit_code' => 0,  
			'qty' => 0, 
			'price' => 0,  
			'conversion' => 1,  
			'evidence_number' => '',  
			'trans_type_id' => 0,
			'in_out_state' => 1,
			'trans_date' => date('Y-m-d'),  
			'exp_date' => date('Y-m-d'),  
			'item_type_id' => 0, 
		];
		
		$arguments = array_merge( $defaults, $args );
		extract($arguments);
		
		$price = $price / $conversion;
		
		/*EXEC IsiKartuGudangFIFO 
			Lokasi_ID, Barang_Id, 'Kode_Satuan_Stok', dIntQtyTerima, Harga_Beli / Barang_Konversi,
			'Penerimaan_No_Penerimaan', jenisTranskasiID, in_out_state, Penerimaan_Tgl_Penerimaan, Exp_Date, JenisBarangID
			in state = 1
			out state= 0	
		*/
		
		self::ci()->db->query("
				EXEC IsiKartuGudangFIFO 
					{$location_id}, {$item_id}, '{$item_unit_code}', {$qty}, {$price},
					'{$evidence_number}', {$trans_type_id}, {$in_out_state}, '{$trans_date}', '{$exp_date}', {$item_type_id} 
			");
		
	}

	public static function insert_warehouse_fifo_mutation( Array $args )
	{
		
		$defaults = [
			'location_id' => 0, 
			'item_id' => 0,  
			'item_unit_code' => 0,  
			'qty' => 0, 
			'price' => 0,  
			'evidence_number' => '',  
			'trans_type_id' => 520,
			'trans_date' => date('Y-m-d'),  
			'item_type_id' => 0, 
			'to_location_id' => 0,
			'exp_date' => date('Y-m-d'),  
		];
		
		$arguments = array_merge( $defaults, $args );
		extract($arguments);
		
		# [IsiKartuGudangFIFO_Mutasi](@LokasiID int, @BarangID int,@KodeSatuan varchar(10),@Qty float,@Harga money,
		# @NoBukti varchar(50),@JenisTransaksi int,@TglTransaksi varchar(50),@JenisBarang smallint,
		# @LokasiIDTujuan int,@ExpDate varchar(50))
		
		self::ci()->db->query("
				EXEC IsiKartuGudangFIFO_Mutasi 
					{$location_id}, {$item_id}, '{$item_unit_code}', {$qty}, {$price},
					'{$evidence_number}', {$trans_type_id}, '{$trans_date}', {$item_type_id}, {$to_location_id}, '{$exp_date}'
			");
	}

	public static function insert_price_change( Array $args )
	{
		self::ci()->load->library('simple_login');
		$user = self::ci()->simple_login->get_user();
		
		$defaults = [
			'location_id' => 0, 
			'item_id' => 0,  
			'trans_date' => date('Y-m-d'),  
			'price' => 0,  
			'user_id' => $user->User_ID, 
		];
		
		$arguments = array_merge( $defaults, $args );
		extract($arguments);
		
		# EXEC InsertHargaChange IntLokasi_ID, Barang_Id, 'Tgl_Penerimaan', harga, user
		self::ci()->db->query("
				EXEC InsertHargaChange {$location_id}, {$item_id}, '{$trans_date}', {$price}, {$user_id} 
			");
	}
	
	public static function insert_supplier_item( Array $args )
	{
		self::ci()->load->library('simple_login');
		$user = self::ci()->simple_login->get_user();
		
		$defaults = [
			'supplier_id' => 0, 
			'item_id' => 0,  
			'trans_date' => date('Y-m-d'),  
			'price' => 0,  
			'user_id' => $user->User_ID, 
		];
		
		$arguments = array_merge( $defaults, $args );
		extract($arguments);
		
		# EXEC InsertBarangSupplier Supplier_ID, Barang_ID, 'Tgl_Penerimaan', Harga_Beli, User.ID
		self::ci()->db->query("
				EXEC InsertBarangSupplier {$supplier_id}, {$item_id}, '{$trans_date}', {$price}, {$user_id} 
			");
	}
	
	public static function create_item($item, $location, $package)
	{
		$_ci = self::ci();
		$_ci->load->model('item_model');
		$_ci->load->model('item_location_model');
		$_ci->load->model('item_package_model');
		
		
		$_ci->db->trans_begin();
							
			$id = $_ci->item_model->create( $item );	
			
			foreach( $location as $row ):
				$row['Barang_ID'] = $id;
				$row['JenisBarangID'] = 0;
				$_ci->item_location_model->create( $row );
			endforeach;
			
			foreach( $package as $row ):
				$row['Barang_ID'] = $id;
				$_ci->item_package_model->create( $row );
			endforeach;

		if($_ci->db->trans_status() === FALSE)
		{
			$_ci->db->trans_rollback();
			return FALSE;
		}
		
		$_ci->db->trans_commit();
		return TRUE;
	}
	
	public static function update_item($id, $item, $location, $package)
	{
		$_ci = self::ci();
		$_ci->load->model('item_model');
		$_ci->load->model('item_location_model');
		$_ci->load->model('item_package_model');
		
		
		$_ci->db->trans_begin();
							
			$_ci->item_model->update( $item, $id );	
			
			$delete_location = [];
			foreach( $location as $row ):
				if( !empty($row['Barang_ID']) ):
					
					$_ci->item_location_model->update_by( $row, ['Barang_ID' => $row['Barang_ID'], 'Lokasi_ID' => $row['Lokasi_ID'] ] );
				else:
					
					$row['Barang_ID'] = $id;
					$row['JenisBarangID'] = 0;
					$_ci->item_location_model->create( $row );
				endif;

				$delete_location[] = $row['Lokasi_ID'];
			endforeach;
			
			if(!empty($delete_location)):
				$_ci->db->where_not_in('Lokasi_ID', $delete_location)
					->where('Barang_ID', $id)
					->delete($_ci->item_location_model->table);
			endif;
						
			$delete_package = [];
			foreach( $package as $row ):
				if( !empty($row['Barang_ID']) ):
					$_ci->item_package_model->update_by( $row, ['Barang_ID' => $id, 'Barang_ID_Penyusun' => $row['Barang_ID_Penyusun'] ] );
				else: 
					$row['Barang_ID'] = $id;
					$_ci->item_package_model->create( $row );
				endif;
				$delete_package = $row['Barang_ID_Penyusun'];
			endforeach;
			
			if(!empty($delete_package)):
				$_ci->db->where_not_in('Barang_ID_Penyusun', $delete_package)
					->where('Barang_ID', $id)
					->delete($_ci->item_package_model->table);
			endif;

		if($_ci->db->trans_status() === FALSE)
		{
			$_ci->db->trans_rollback();
			return FALSE;
		}
		
		//$_ci->db->trans_rollback();
		$_ci->db->trans_commit();
		return TRUE;
	}
	
	public static function get_item_location( $id )
	{
		$_ci = self::ci();
		$_ci->load->model('item_location_model');
		$_ci->load->model('section_model');
		
		$query = self::ci()->db->select("
								a.*, 
								b.SectionName
							")
							->from("{$_ci->item_location_model->table} a")
							->join("{$_ci->section_model->table} b", "a.Lokasi_ID = b.Lokasi_ID", "INNER")
							->where("a.Barang_ID", $id)
							->get();
							
		return $query->result();
	}
	
	public static function get_item_package( $id )
	{
		$_ci = self::ci();
		$_ci->load->model('item_package_model');
		$_ci->load->model('item_model');
		
		$query = self::ci()->db->select("
								a.*, 
								b.Nama_Barang
							")
							->from("{$_ci->item_package_model->table} a")
							->join("{$_ci->item_model->table} b", "a.Barang_ID_Penyusun = b.Barang_ID", "INNER")
							->where("a.Barang_ID", $id)
							->get();
							
		return $query->result();
	}
		
	private static function & ci()
	{
		return get_instance();
	}	

	public static function gen_gift_receipt_evidence_number( $date )
	{
		$_ci = self::ci();
		
		$date = DateTime::createFromFormat('Y-m-d', $date );
		
		$date_start = $date->format( "Y-m-01 00:00:00.000" );
		$date_end = $date->format( "Y-m-t 00:00:00.000" );
		$date_y = $date->format( "y" );
		$date_m = $date->format( "m" );
		$date_d = $date->format( "d" );
		
		$query =  $_ci->db->select("MAX( Right(No_Bonus, 3) ) as max_number")
						->where(array(
							"DATEPART(YEAR, Tgl_Bonus) =" => $date->format( "Y" ),
							"DATEPART(MONTH, Tgl_Bonus) =" => $date->format( "m" ),
						))
						->get( "BL_trReceiveBonus" )
						->row();
						
		$max_number = (!empty($query->max_number)) ? ++$query->max_number : 1;
		$number = (string) (sprintf(self::_gen_gift_receipt_evidence_number(), $date_y, $date_m, $max_number));
		return $number;
	}
	
	private static function _gen_gift_receipt_evidence_number()
	{
		$format = "%02d%02dBRC-%03d";
		return $format;
	}

	
	public static function insert_warehouse_fifo_gift( Array $args )
	{
		$defaults = [
			'location_id' => 0, 
			'item_id' => 0,  
			'item_unit_code' => 0,  
			'qty' => 0, 
			'price' => 0,  
			'conversion' => 1,  
			'evidence_number' => '',  
			'trans_type_id' => 0,
			'in_out_state' => 1,
			'trans_date' => date('Y-m-d'),  
			'exp_date' => date('Y-m-d'),  
			'item_type_id' => 0, 
			'TglED' => '',
			'NoBatch' => ''
		];
		
		$arguments = array_merge( $defaults, $args );
		extract($arguments);
		
		$price = $price / $conversion;
	
		
		self::ci()->db->query("
				EXEC IsiKartuGudangFIFO_Bonus
					{$location_id}, {$item_id}, '{$item_unit_code}', {$qty}, {$price},
					'{$evidence_number}', {$trans_type_id}, {$in_out_state}, '{$trans_date}', '{$exp_date}', {$item_type_id}
			");
		
	}
	

}
