<?php
defined('BASEPATH') OR exit('No direct script access allowed');

final class Service_helper
{		
	private static $user_auth;
	private static $_ci;
	
	public static function init()
	{
		self::$_ci = $_ci = self::ci();
		
		$_ci->load->model('service_component_model');
		$_ci->load->model('service_category_model');
		$_ci->load->model('devices_model');
		$_ci->load->model('service_model');

		$_ci->load->library('simple_login');		
		self::$user_auth = $_ci->simple_login->get_user();
		
	}
	
	public static function gen_component_code()
	{
		self::init();
		$_ci = self::ci();
		
		$query =  $_ci->db->select("MAX({$_ci->service_component_model->index_key}) as max_code")
						->get( "{$_ci->service_component_model->table}" )
						->row();
		if(!empty($query->max_code))
		{				
			$code = ++$query->max_code;
		} else {
			$code = sprintf("DT%02d", 1);
		}
		return $code;
	}

	public static function gen_category_code()
	{
		self::init();
		$_ci = self::ci();
		
		$query =  $_ci->db->select("MAX({$_ci->service_category_model->index_key}) as max_code")
						->where([
							"LEN([{$_ci->service_category_model->index_key}]) =" => 5, 
							"LEFT([{$_ci->service_category_model->index_key}], 2) =" => 'KB'
						])
						->get( "{$_ci->service_category_model->table}" )
						->row();
		if(!empty($query->max_code))
		{				
			$code = ++$query->max_code;
		} else {
			$code = sprintf("KB%03d", 1);
		}
		return $code;
	}	
	
	public static function gen_devices_code()
	{
		self::init();
		$_ci = self::ci();
		
		$query =  $_ci->db->select("MAX({$_ci->devices_model->index_key}) as max_code")
						->where([
							"LEN([{$_ci->devices_model->index_key}]) =" => 7, 
							"LEFT([{$_ci->devices_model->index_key}], 3) =" => 'ALT'
						])
						->get( "{$_ci->devices_model->table}" )
						->row();
		if(!empty($query->max_code))
		{				
			$code = ++$query->max_code;
		} else {
			$code = sprintf("ALT%04d", 1);
		}
		return $code;
	}	
	
	public static function gen_class_code()
	{
		self::init();
		$_ci = self::ci();

		$query =  $_ci->db->select("MAX({$_ci->class_model->index_key}) as max_code")
						->where([
							"LEFT([{$_ci->class_model->index_key}], 3) =" => 'KLS'
						])
						->get( "{$_ci->class_model->table}" )
						->row();
		if(!empty($query->max_code))
		{				
			$code = ++$query->max_code;
		} else {
			$code = sprintf("KLS%03d", 1);
		}
		return $code;
	}	
	
	public static function gen_service_code()
	{
		self::init();
		$_ci = self::ci();
		
		$query =  $_ci->db->select("MAX({$_ci->service_model->index_key}) as max_code")
						->where([
							"LEN([{$_ci->service_model->index_key}]) =" => 7, 
							"LEFT([{$_ci->service_model->index_key}], 3) =" => 'JAS'
						])
						->get( "{$_ci->service_model->table}" )
						->row();
		if(!empty($query->max_code))
		{				
			$code = ++$query->max_code;
		} else {
			$code = sprintf("JAS%04d", 1);
		}
		return $code;
	}	
	
	public static function gen_injury_code()
	{
		self::init();
		$_ci = self::ci();
		
		$query =  $_ci->db->select("MAX({$_ci->injury_type_model->index_key}) as max_code")
						->where([
							"LEN([{$_ci->injury_type_model->index_key}]) =" => 5, 
							"LEFT([{$_ci->injury_type_model->index_key}], 2) =" => 'LK'
						])
						->get( "{$_ci->injury_type_model->table}" )
						->row();
		if(!empty($query->max_code))
		{				
			$code = ++$query->max_code;
		} else {
			$code = sprintf("LK.%02d", 1);
		}
		return $code;
	}	
	
	public static function create_service($service, $price, $bhp, $section, $test)
	{
		self::init();
		$_ci = self::ci();
		$_ci->load->model('service_model');
		$_ci->load->model('services/price_model');
		$_ci->load->model('services/price_detail_model');
		$_ci->load->model('services/bhp_model');
		$_ci->load->model('services/service_section_model');
		$_ci->load->model('services/service_test_model');
		
		
		$_ci->db->trans_begin();
							
			$_ci->service_model->create( $service );	
			
			# insert detail harga jasa
			foreach( $price as $row ):
				$row = (object)$row;
				
				$insert_price = [
					'JasaID' => $service->JasaID,
					'KelasID' => $row->KelasID,
                    'JenisPasienID' => $row->JenisPasienID,
                    'PasienKTP' => $row->PasienKTP,
                    'KategoriOperasiID' => $row->KategoriOperasiID,
                    'DokterID' => $row->DokterID,
                    'Harga_Lama' => $row->Harga_Lama,
                    'Harga_Baru' => $row->Harga_Baru,
                    'HargaHC_Lama' => $row->HargaHC_Lama,
                    'HargaHC_Baru' => $row->HargaHC_Baru,
                    'TglHargaBaru' => $row->TglHargaBaru,
                    'SpesialisID' => $row->SpesialisID,
                    'Cyto' => $row->Cyto,
                    'Lokasi' => $row->Lokasi,
                    'DiscHCUmum' => $row->DiscHCUmum,
                    'SubSpesialis' => $row->SubSpesialis,
                    'HargaBPJS' => $row->HargaBPJS,
                    'HargaBPJS_Lama' => $row->HargaBPJS_Lama,
                    'TglHargaBaruBPJS' => $row->TglHargaBaruBPJS,
                    'InsentifKomponen' => $row->InsentifKomponen,
                    'InsentifDetail' => $row->InsentifDetail,
				];

				$insert_price_id = $_ci->price_model->create( $insert_price );
				
				foreach( $row->component_detail as $com ):
					$com = (object)$com;
					$insert_price_detail = [
						'ListHargaID' => $insert_price_id,
						'KomponenBiayaID' => $com->KomponenBiayaID,
						'Qty' => $com->Qty,
						'HargaLama' => $com->HargaLama,
						'HargaBaru' => $com->HargaBaru,
						'HargaAwal' => $com->HargaAwal,
						'HargaAwalLama' => $com->HargaAwalLama,
						'HargaHCLama' => $com->HargaHCLama,
						'HargaHCBaru' => $com->HargaHCBaru,
						'PersenInsentifHC' => $com->PersenInsentifHC,
						'HargaBPJS' => $com->HargaBPJS,
						'HargaBPJS_Lama' => $com->HargaBPJS_Lama,
						'IncludeInsentif' => $com->IncludeInsentif,
						'PersenInsentif' => $com->PersenInsentif,
						'PersenPajakTitipan' => $com->PersenPajakTitipan,
						'AkunNo' => $com->AkunNo,
						'AkunNoLawan' => $com->AkunNoLawan,
					];
					
					$_ci->price_detail_model->create( $insert_price_detail );	
					
				endforeach;
				
			endforeach;
			
			# insert detail bhp
			foreach( $bhp as $row ):
				$row = (object)$row;
				$insert_bhp = [
					'JasaID' => $service->JasaID,
					'Kode_Barang' => $row->Kode_Barang,
					'Satuan' => $row->Satuan,
					'Qty' => $row->Qty,
					'Ditagihkan' => $row->Ditagihkan,
				];
				
				$_ci->bhp_model->create( $insert_bhp );
			endforeach;

			# insert detail section
			foreach( $section as $row ):
				$row = (object)$row;
				$insert_section = [
					'JasaID' => $service->JasaID,
					'SectionID' => $row->SectionID,
				];
				
				$_ci->service_section_model->create( $insert_section );
			endforeach;
			
			# insert detail test lab
			foreach( $test as $row ):
				$row = (object)$row;
				$insert_test = [
					'JasaID' => $service->JasaID,
					'TestID' => $row->TestID,
				];
				
				$_ci->service_test_model->create( $insert_test );
			endforeach;
			
								
		if($_ci->db->trans_status() === FALSE)
		{
			$_ci->db->trans_rollback();
			return $response = array(
					"status" => 'error',
					"message" => lang('global:created_failed'),
					"code" => 500
				);
		}
		
		$_ci->db->trans_commit();
		return $response = array(
				"status" => 'success',
				"message" => lang('global:created_successfully'),
				"code" => 200,
				"id" => $service->JasaID
			);		
	}
	
	public static function update_service($service, $JasaID, $price, $bhp, $section, $test)
	{
		self::init();
		$_ci = self::ci();
		$_ci->load->model('service_model');
		$_ci->load->model('services/price_model');
		$_ci->load->model('services/price_detail_model');
		$_ci->load->model('services/bhp_model');
		$_ci->load->model('services/service_section_model');
		$_ci->load->model('services/service_test_model');
		
		
		$_ci->db->trans_begin();
							
			$_ci->service_model->update( $service, $JasaID );	
			
			# Update detail harga jasa
			$price_in = [];
			foreach( $price as $row ):
				$row = (object)$row;
				
				if( empty($row->ListHargaID)): # Jika ListHargaID Kosong maka insert detail harga baru
					$insert_price = [
						'JasaID' => $JasaID,
						'KelasID' => $row->KelasID,
						'JenisPasienID' => $row->JenisPasienID,
						'PasienKTP' => $row->PasienKTP,
						'KategoriOperasiID' => $row->KategoriOperasiID,
						'DokterID' => $row->DokterID,
						'Harga_Lama' => (float)$row->Harga_Lama,
						'Harga_Baru' => (float)$row->Harga_Baru,
						'HargaHC_Lama' => (float)$row->HargaHC_Lama,
						'HargaHC_Baru' => (float)$row->HargaHC_Baru,
						'TglHargaBaru' => $row->TglHargaBaru,
						'SpesialisID' => $row->SpesialisID,
						'Cyto' => $row->Cyto,
						'Lokasi' => $row->Lokasi,
						'DiscHCUmum' => $row->DiscHCUmum,
						'SubSpesialis' => $row->SubSpesialis,
						'HargaBPJS' => (float)$row->HargaBPJS,
						'HargaBPJS_Lama' => (float)$row->HargaBPJS_Lama,
						'TglHargaBaruBPJS' => $row->TglHargaBaruBPJS,
						'InsentifKomponen' => $row->InsentifKomponen,
						'InsentifDetail' => $row->InsentifDetail,
					];
	
					$price_in[] = $insert_price_id = $_ci->price_model->create( $insert_price );	
					
					foreach( $row->component_detail as $com ):
						$com = (object)$com;
						$insert_price_detail = [
							'ListHargaID' => $insert_price_id,
							'KomponenBiayaID' => $com->KomponenBiayaID,
							'Qty' => $com->Qty,
							'HargaLama' => (float)$com->HargaLama,
							'HargaBaru' => (float)$com->HargaBaru,
							'HargaAwal' => (float)$com->HargaAwal,
							'HargaAwalLama' => (float)$com->HargaAwalLama,
							'HargaHCLama' => (float)$com->HargaHCLama,
							'HargaHCBaru' => (float)$com->HargaHCBaru,
							'PersenInsentifHC' => $com->PersenInsentifHC,
							'HargaBPJS' => (float)$com->HargaBPJS,
							'HargaBPJS_Lama' => (float)$com->HargaBPJS_Lama,
							'IncludeInsentif' => $com->IncludeInsentif,
							'PersenInsentif' => $com->PersenInsentif,
							'PersenPajakTitipan' => $com->PersenPajakTitipan,
							'AkunNo' => $com->AkunNo,
							'AkunNoLawan' => $com->AkunNoLawan,
						];
						
						$_ci->price_detail_model->create( $insert_price_detail );	
						
					endforeach;
				
				else: # Jika ListHargaID Tidak Kosong maka update detail harga baru
				
					$update_price = [
						'KelasID' => $row->KelasID,
						'JenisPasienID' => $row->JenisPasienID,
						'PasienKTP' => $row->PasienKTP,
						'KategoriOperasiID' => $row->KategoriOperasiID,
						'DokterID' => $row->DokterID,
						'Harga_Lama' => (float)$row->Harga_Lama,
						'Harga_Baru' => (float)$row->Harga_Baru,
						'HargaHC_Lama' => (float)$row->HargaHC_Lama,
						'HargaHC_Baru' => (float)$row->HargaHC_Baru,
						'TglHargaBaru' => $row->TglHargaBaru,
						'SpesialisID' => $row->SpesialisID,
						'Cyto' => $row->Cyto,
						'Lokasi' => $row->Lokasi,
						'DiscHCUmum' => $row->DiscHCUmum,
						'SubSpesialis' => $row->SubSpesialis,
						'HargaBPJS' => (float)$row->HargaBPJS,
						'HargaBPJS_Lama' => (float)$row->HargaBPJS_Lama,
						'TglHargaBaruBPJS' => $row->TglHargaBaruBPJS,
						'InsentifKomponen' => $row->InsentifKomponen,
						'InsentifDetail' => $row->InsentifDetail,
					];
					
					$_ci->price_model->update( $update_price, $row->ListHargaID );	
					
					$component_in = [];
					foreach( $row->component_detail as $com ):
						$com = (object)$com;
						
						$get_by = ['ListHargaID' => $row->ListHargaID, 'KomponenBiayaID' => $com->KomponenBiayaID];
						$is_exist = $_ci->price_detail_model->get_by( $get_by );
						
						if( empty( $com->ListHargaID) && empty( $is_exist ) ):
							$insert_price_detail = [
								'ListHargaID' => $row->ListHargaID,
								'KomponenBiayaID' => $com->KomponenBiayaID,
								'Qty' => $com->Qty,
								'HargaLama' => (float)$com->HargaLama,
								'HargaBaru' => (float)$com->HargaBaru,
								'HargaAwal' => (float)$com->HargaAwal,
								'HargaAwalLama' => (float)$com->HargaAwalLama,
								'HargaHCLama' => (float)$com->HargaHCLama,
								'HargaHCBaru' => (float)$com->HargaHCBaru,
								'PersenInsentifHC' => $com->PersenInsentifHC,
								'HargaBPJS' => (float)$com->HargaBPJS,
								'HargaBPJS_Lama' => (float)$com->HargaBPJS_Lama,
								'IncludeInsentif' => $com->IncludeInsentif,
								'PersenInsentif' => $com->PersenInsentif,
								'PersenPajakTitipan' => $com->PersenPajakTitipan,
								'AkunNo' => $com->AkunNo,
								'AkunNoLawan' => $com->AkunNoLawan,
							];
							
							$_ci->price_detail_model->create( $insert_price_detail );	
							$component_in[] = $com->KomponenBiayaID;
						else:
						
							$update_price_detail = [
								'Qty' => $com->Qty,
								'HargaLama' => (float)$com->HargaLama,
								'HargaBaru' => (float)$com->HargaBaru,
								'HargaAwal' => (float)$com->HargaAwal,
								'HargaAwalLama' => (float)$com->HargaAwalLama,
								'HargaHCLama' => (float)$com->HargaHCLama,
								'HargaHCBaru' => (float)$com->HargaHCBaru,
								'PersenInsentifHC' => $com->PersenInsentifHC,
								'HargaBPJS' => (float)$com->HargaBPJS,
								'HargaBPJS_Lama' => (float)$com->HargaBPJS_Lama,
								'IncludeInsentif' => $com->IncludeInsentif,
								'PersenInsentif' => $com->PersenInsentif,
								'PersenPajakTitipan' => $com->PersenPajakTitipan,
								'AkunNo' => $com->AkunNo,
								'AkunNoLawan' => $com->AkunNoLawan,
							];
							
							$update_by = ['ListHargaID' => $row->ListHargaID, 'KomponenBiayaID' => $com->KomponenBiayaID];
							$_ci->price_detail_model->update_by( $update_price_detail, $update_by );	
							$component_in[] = $com->KomponenBiayaID;
						endif;
						
					endforeach;
					
					if( !empty($component_in) ):
						$_ci->db->where('ListHargaID', $row->ListHargaID)
							->where_not_in('KomponenBiayaID', $component_in)
							->delete( $_ci->price_detail_model->table );
					endif;

					// untuk delete detail harga yg tidak ada di array price_in[].
					// karena jika tidak ada didalam post_price, maka data tersebut sebelumnya di hapus oleh client pada datatable browser
					$price_in[] = $row->ListHargaID;	
					
				endif;
				
			endforeach;
			
			if( !empty( $price_in ) ):
				
				$get_price = $_ci->db->where('JasaID', $JasaID)
									->where_not_in('ListHargaID', $price_in )
									->get( $_ci->price_model->table )
									->result();
				// delete detail harga
				if( !empty( $get_price) ):
					foreach($get_price as $row):
						$_ci->price_detail_model->delete_by(['ListHargaID' => $row->ListHargaID]);
						$_ci->price_model->delete($row->ListHargaID);
					endforeach;
				endif;
			endif;
			
			# Update detail bhp
			$bhp_in = [];
			foreach( $bhp as $row ):
				$row = (object)$row;
			
				if( empty($row->JasaID )):
					$insert_bhp = [
						'JasaID' => $JasaID,
						'Kode_Barang' => $row->Kode_Barang,
						'Satuan' => $row->Satuan,
						'Qty' => $row->Qty,
						'Ditagihkan' => $row->Ditagihkan,
					];
					$_ci->bhp_model->create( $insert_bhp );
					
				else:	

					$update_bhp = [
						'Satuan' => $row->Satuan,
						'Qty' => $row->Qty,
						'Ditagihkan' => $row->Ditagihkan,
					];
					$update_by = ['JasaID' => $row->JasaID, 'Kode_Barang' => $row->Kode_Barang];
					$_ci->bhp_model->update_by( $update_bhp, $update_by );				
				endif;
							
				$bhp_in[] = $row->Kode_Barang;
			endforeach;
			
			if( !empty( $bhp_in )):
				$_ci->db->where('JasaID', $JasaID)
						->where_not_in('Kode_Barang', $bhp_in)
						->delete( $_ci->bhp_model->table );
			endif;

			# Update detail section
			$section_in = [];
			foreach( $section as $row ):
				$row = (object)$row;
				
				if( empty($row->JasaID) ):
					$insert_section = [
						'JasaID' => $JasaID,
						'SectionID' => $row->SectionID,
					];
					$_ci->service_section_model->create( $insert_section );
				endif;
				
				$section_in[] = $row->SectionID;
			endforeach;
			
			if( !empty( $section_in )):
				$_ci->db->where('JasaID', $JasaID)
						->where_not_in('SectionID', $section_in)
						->delete( $_ci->service_section_model->table );
			endif;
			
			# Update detail test lab
			$test_in = [];
			foreach( $test as $row ):
				$row = (object)$row;
				
				if( empty($row->JasaID) ):
					$insert_test = [
						'JasaID' => $JasaID,
						'TestID' => $row->TestID,
					];
					$_ci->service_test_model->create( $insert_test );
				endif;
				
				$test_in[] = $row->TestID;
			endforeach;
			
			if( !empty( $test_in )):
				$_ci->db->where('JasaID', $JasaID)
						->where_not_in('TestID', $test_in)
						->delete( $_ci->service_test_model->table );
			endif;
			
								
		if($_ci->db->trans_status() === FALSE)
		{
			$_ci->db->trans_rollback();
			return $response = array(
					"status" => 'error',
					"message" => lang('global:created_failed'),
					"code" => 500
				);
		}
		//$_ci->db->trans_rollback();
		$_ci->db->trans_commit();
		return $response = array(
				"status" => 'success',
				"message" => lang('global:created_successfully'),
				"code" => 200,
				"id" => $JasaID
			);		
	}
	
	public static function get_all_service_price( $JasaID )
	{
		self::init();
		$_ci = self::ci();
		$_ci->load->model('service_model');
		$_ci->load->model('services/price_model');
		$_ci->load->model('services/price_detail_model');
		$_ci->load->model('class_model');
		$_ci->load->model('patient_type_model');
		$_ci->load->model('specialist_model');
		$_ci->load->model('supplier_model');
		$_ci->load->model('operation_service_category_model');
		$_ci->load->model('account_model');
		$_ci->load->model('service_component_model');
		
		$price = $_ci->db->select(' a.*, b.NamaKelas, c.JenisKerjasama, d.SpesialisName, e.Nama_Supplier AS NamaDokter, f.KategoriName AS KategoriOperasiName')
					->from("{$_ci->price_model->table} a")
					->join("{$_ci->class_model->table} b", "a.KelasID = b.KelasID", "INNER")
					->join("{$_ci->patient_type_model->table} c", "a.JenisPasienID = c.JenisKerjasamaID", "INNER")
					->join("{$_ci->specialist_model->table} d", "a.SpesialisID= d.SpesialisID", "INNER")
					->join("{$_ci->supplier_model->table} e", "a.DokterID = e.Kode_Supplier", "INNER")
					->join("{$_ci->operation_service_category_model->table} f", "a.KategoriOperasiID = f.KategoriID", "INNER")
					->where('a.JasaID', $JasaID)
					->get()
					->result();	
					
		$collection = [];
		foreach( $price as $row ):
		
			$component_detail = $_ci->db->select('a.*, b.KomponenName, c.Akun_Name, d.Akun_Name')
									->from("{$_ci->price_detail_model->table} a")
									->join("{$_ci->service_component_model->table} b", "a.KomponenBiayaID = b.KomponenBiayaID", "INNER")
									->join("{$_ci->account_model->table} c", "a.AkunNo = c.Akun_No", "LEFT OUTER")
									->join("{$_ci->account_model->table} d", "a.AkunNoLawan = d.Akun_No", "LEFT OUTER")
									->where('a.ListHargaID', $row->ListHargaID)
									->get()
									->result();	
					
			$row->component_detail = $component_detail;
			
			$collection[] = $row;
		endforeach;
		
		return $collection;
	}
	
	public static function get_all_service_bhp( $JasaID )
	{
		self::init();
		$_ci = self::ci();
		
		$_ci->load->model('services/bhp_model');
		$_ci->load->model('item_model');
				
		$collection = $_ci->db->select(' a.*, b.Nama_Barang')
					->from("{$_ci->bhp_model->table} a")
					->join("{$_ci->item_model->table} b", "a.Kode_Barang = b.Kode_Barang", "INNER")
					->where('a.JasaID', $JasaID)
					->get()
					->result();	
					
		return $collection;
	}
	
	public static function get_all_service_section( $JasaID )
	{
		self::init();
		$_ci = self::ci();
		
		$_ci->load->model('services/service_section_model');
		$_ci->load->model('section_model');
				
		$collection = $_ci->db->select(' a.*, b.SectionName')
					->from("{$_ci->service_section_model->table} a")
					->join("{$_ci->section_model->table} b", "a.SectionID = b.SectionID", "INNER")
					->where('a.JasaID', $JasaID)
					->get()
					->result();	
					
		return $collection;
	}
	
	public static function get_all_service_test( $JasaID )
	{
		self::init();
		$_ci = self::ci();
		
		$_ci->load->model('services/service_test_model');
		$_ci->load->model('test_model');
				
		$collection = $_ci->db->select(' a.*, b.NamaTest')
					->from("{$_ci->service_test_model->table} a")
					->join("{$_ci->test_model->table} b", "a.TestID = b.TestID", "INNER")
					->where('a.JasaID', $JasaID)
					->get()
					->result();	
					
		return $collection;
	}
	
	public static function get_service_group_account( $Akun_No, $database = '' )
	{
		self::init();
		$_ci = self::ci();
		
		$_ci->db_bo = $_ci->load->database($database, TRUE);
		
		$_ci->load->model('account_model');
				
		return 
			$_ci->db_bo->where('Akun_No', $Akun_No)
					->get( $_ci->account_model->table )->row();	
	}

	private static function & ci()
	{
		return get_instance();
	}	

}
