<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Laboratory_m extends Public_Model
{
	public $table = 'SIMtrRJ';
	public $index_key = 'NoBukti';
	public $rules;
	
	public function __construct()
	{
		$this->rules = [
			'insert' => [
				[
					'field' => 'RegNo',
                	'label' => 'RegNo',
               		'rules' => 'required'
				],
				[
					'field' => 'NRM',
                	'label' => 'NRM',
               		'rules' => 'required'
				],
			],
		];
	}
	
	public function create($data)
	{
		$this->db->insert($this->table, $data);
		return (int) $this->db->insert_id(); 
	}
	
	public function mass_create($collection)
	{
		return $this->db->insert_batch($this->table, $collection);
	}
	
	public function update($data, $key)
	{
		$this->db->where($this->index_key, $key);
		return $this->db->update($this->table, $data);
	}
	
	public function update_by($data, Array $where)
	{
		$this->db->where($where);
		return $this->db->update($this->table, $data);
	}
	
	public function delete($key)
	{
		$this->db->where($this->index_key, $key);
		return $this->db->delete($this->table);
	}
	
	public function delete_by(Array $where)
	{
		$this->db->where($where);
		return $this->db->delete($this->table);
	}
	
	public function get_one($key, $to_array = FALSE)
	{
		$this->db->where($this->index_key, $key);
		$query = $this->db->get($this->table, 1);
		return (TRUE == $to_array) ? $query->row_array() : $query->row();
	}
	
	public function get_by(Array $where, $to_array = FALSE)
	{
		$this->db->where($where);
		$query = $this->db->get($this->table, 1);
		return (TRUE == $to_array) ? $query->row_array() : $query->row();
	}
	
	public function get_all($limit = NULL, $offset = 0, $where = NULL, $to_array = FALSE)
	{
		if (!is_null($where) && !empty($where)){ $this->db->where($where); }
		
		$query = $this->db
			->order_by($this->index_key, 'ASC')
			->get($this->table, $limit, $offset);		
		return (TRUE == $to_array) ? $query->result_array() : $query->result();
	}
	
	public function count_all($where = NULL)
	{
		if (!is_null($where) && !empty($where)){ $this->db->where($where); }
		
		$this->db->where($where);		
		return (int) ($this->db->count_all_results($this->table));
	}
	
	public function get_patient( $NRM )
	{
		// get result filtered
		$db_select = <<<EOSQL
			a.NRM
			,a.NamaPasien
			,a.NoIdentitas
			,a.JenisKelamin
			,a.TglLahir
			,a.TglLahirDiketahui
			,a.UmurSaatInput
			,a.Pekerjaan
			,a.Alamat
			,a.PropinsiID
			,a.KabupatenID
			,a.KecamatanID
			,a.DesaID
			,a.BanjarID
			,a.Phone
			,a.Email
			
			,a.JenisPasien
			,a.JenisKerjasamaID
			,a.AnggotaBaru
			,a.CustomerKerjasamaID
			,a.NoKartu
			,a.Klp
			,a.JabatanDiPerusahaan
			,a.PasienLoyal
			
			,a.TotalKunjunganRawatInap
			,a.TotalKunjunganRawatJalan
			,a.KunjunganRJ_TahunIni
			,a.KunjunganRI_TahunIni
			
			,a.EtnisID
			,a.NationalityID
			,a.PasienVVIP
			,a.PasienKTP
			,a.TglInput
			,a.UserID
			,a.CaraDatangPertama
			,a.DokterID_ReferensiPertama
			,a.SedangDirawat
			,a.KodePos
			
			,a.TglRegKasusKecelakaanBaru
			,a.NoRegKecelakaanBaru
			,a.Aktive_Keanggotaan
			,a.Agama
			,a.NoANggotaE
			,a.NamaAnggotaE
			,a.GenderAnggotaE
			,a.TglTidakAktif
			,a.TipePasienAsal
			,a.NoKartuAsal
			,a.NamaPerusahaanAsal

			,a.PenanggungIsPasien
			,a.PenanggungNRM
			,a.PenanggungNama
			,a.PenanggungAlamat
			,a.PenanggungPhone
			,a.PenanggungKTP
			,a.PenanggungHubungan
			,a.PenanggungPekerjaan
			
			,a.Aktif
			,a.PasienBlackList
			,a.NamaIbuKandung
			,a.NonPBI
			,a.KdKelas
			,a.Prematur
			,a.NamaAlias
			
			,d.Nama_Customer
			,d.Kode_Customer AS CompanyID
			
			
EOSQL;

		$this->db
			->select( $db_select )
			->from( "mPasien a" )
			->join( "SIMmJenisKerjasama b", "a.JenisKerjasamaID = b.JenisKerjasamaID", "LEFT OUTER" )
			->join( "SIMdCustomerKerjasama c", "a.CustomerKerjasamaID = c.CustomerKerjasamaID", "LEFT OUTER" )
			->join( "mCustomer d", "c.CustomerID = d.Customer_ID", "LEFT OUTER" )
			->join( "mPropinsi f", "a.PropinsiID = f.Propinsi_ID", "LEFT OUTER" )
			->join( "mKabupaten g", "a.KabupatenID = g.Kode_Kabupaten", "LEFT OUTER" )
			->join( "mKecamatan h", "a.KecamatanID = h.KecamatanID", "LEFT OUTER" )
			->join( "mDesa i", "a.DesaID = i.DesaID", "LEFT OUTER" )
			->join( "mBanjar j", "a.BanjarID = j.BanjarID", "LEFT OUTER" )
			->join( "mNationality k", "a.NationalityID = k.NationalityID", "LEFT OUTER" )
			;
		
		$query = $this->db
					->where("a.NRM", $NRM)
					->get();

		if ( $query->num_rows() > 0 )
		{
			return $query->row();
		}
		
		return false;
	}

	public function get_customer( $where = NULL)
	{
		if (!$where)
		{
			return false;
		}
		
		$query = $this->db
					->where( $where )
					->get("mCustomer");
		
		if ( $query->num_rows() > 0 )
		{
			return $query->row();
		}
		
		return (object) [];
	}
	
	public function get_option_patient_type ()
	{
		$query = $this->db
					->order_by("JenisKerjasama", "ASC")
					->get("SIMmJenisKerjasama");
		
		if ( $query->num_rows() > 0 )
		{
			return $query->result();
		}
		
		return false;
	}

	public function get_child_data( $table, $where = NULL )
	{

		if( !empty( $where ) && is_array( $where ))
		{
			$this->db->where( $where );
		}

		$query = $this->db->get( $table );
		
		if ( $query->num_rows() > 0 )
		{
			return $query->result();
		}
		
		return false;
	}

	public function get_product_package_detail( $table, $where = NULL )
	{

		if( !empty( $where ) && is_array( $where ))
		{
			$this->db->where( $where );
		}

		$query = $this->db
					->select("a.*, b.Nama_Barang, b.Kode_Barang, c.Nama_Satuan AS Satuan")
					->from($table." a")
					->join("mBarang b", "a.Barang_ID = b.Barang_ID", "LEFT OUTER")
					->join("mSatuan c", "b.Beli_Satuan_ID = c.Satuan_ID", "LEFT OUTER")
					->get();
		
		if ( $query->num_rows() > 0 )
		{
			return $query->result();
		}
		
		return false;
	}

	public function get_bhp_package_detail( $table, $where = NULL )
	{

		if( !empty( $where ) && is_array( $where ))
		{
			$this->db->where( $where );
		}

		$query = $this->db
					->select("a.*, b.Nama_Barang, b.Kode_Barang, c.Nama_Satuan AS Satuan")
					->from($table." a")
					->join("mBarang b", "a.Kode_Barang = b.Kode_Barang", "LEFT OUTER")
					->join("mSatuan c", "b.Beli_Satuan_ID = c.Satuan_ID", "LEFT OUTER")
					->get();
		
		if ( $query->num_rows() > 0 )
		{
			return $query->result();
		}
		
		return false;
	}
		
	public function get_options( $table, $where = NULL, $order = NULL )
	{

		if( !empty( $where ) && is_array( $where ))
		{
			$this->db->where( $where );
		}

		if( !empty( $order ) )
		{
			$this->db->order_by( $order );
		}

		$query = $this->db->get( $table );
		
		if ( $query->num_rows() > 0 )
		{
			return $query->result();
		}
		
		return false;
	}

	public function get_poly_data( $where = NULL )
	{

		if( !empty( $where ) && is_array( $where ))
		{
			$this->db->where( $where );
		}

		$query = $this->db
					->select("a.*, b.Nama_Supplier")
					->from("SIMtrDataRegPasien a")
					->join("mSupplier b", "a.DokterID = b.Kode_Supplier", "LEFT OUTER")
					->get();
		
		if ( $query->num_rows() > 0 )
		{
			return $query->row();
		}
		
		return false;
	}
	
	public function get_row_data( $table, $where = NULL )
	{

		if( !empty( $where ) && is_array( $where ))
		{
			$this->db->where( $where );
		}

		$query = $this->db->get( $table );
		
		if ( $query->num_rows() > 0 )
		{
			return $query->row();
		}
		
		return false;
	}

	public function get_icd( $where = NULL )
	{

		if( !empty( $where ) && is_array( $where ))
		{
			$this->db->where( $where );
		}

		$query = $this->db
					->select("a.*, b.Descriptions")
					->from( "SIMtrRJDiagnosaAwal a" )
					->join("mICD b", "a.KodeICD = b.KodeICD", "LEFT OUTER")
					->get()
					;
		
		if ( $query->num_rows() > 0 )
		{
			return $query->result();
		}
		
		return false;
	}
	
	public function get_service( $NoBukti, $item, $is_edit )
	{
		$query = $this->db->select("a.*, b.JasaName, c.Nama_Supplier, d.User_id, d.Nama_Singkat")
					->from( "SIMtrRJTransaksi a" )
					->join("SIMmListJasa b", "a.JasaID = b.JasaID", "LEFT OUTER")
					->join("mSupplier c", "a.DokterID = c.Kode_Supplier", "LEFT OUTER")
					->join("mUser d", "a.UserID = d.User_ID", "LEFT OUTER")
					->where('a.NoBukti', $NoBukti)
					->get();
					
		if ( $query->num_rows() > 0 )
		{
			return $query->result();
		}
		
		if ( ! $is_edit )
		{			
			$query = $this->db->select('a.JasaID, a.JasaName')
							->from('SIMmListJasa a')
							->join('SIMmListJasaSection b', "a.JasaID = b.JasaID")
							->where(['b.SectionID' => $item->SectionID, 'a.AutoSystemRI' => 1])
							->get();
			$collection = [];				
			foreach( $query->result() as $row)
			{
				$pasienKTP = @$item->PasienKTP;
				if( in_array($item->JenisKerjasamaID, [2, 3, 4]) )
				{
					#GetTarifBiayaNonKerjasama(@JasaID varchar(50),@DokterID varchar(50),@KelasID varchar(50),@Cyto int,@KategoriOperasiID int,@TipePasienID int,@UnitBisnisID varchar(50))
					
					$tariff_jasa = $this->db->query("
									Select *, 0 AS KenaikanProsen 
									FROM GetTarifBiayaNonKerjasama ('{$row->JasaID}' ,'XX', 'XX', 0, 1, {$item->JenisKerjasamaID}, 1) 
									WHERE (KTP={$pasienKTP} AND (Lokasi='RJ' OR Lokasi='XX')) ORDER BY LOKASI ASC
								")
								->row();
				} elseif($item->JenisKerjasamaID == 9) {
					#GetTarifBiayaNonKerjasama(@JasaID varchar(50),@DokterID varchar(50),@KelasID varchar(50),@Cyto int,@KategoriOperasiID int,@NoAnggota varchar(50))
					$tariff_jasa = $this->db->query("
									Select *, 0 AS KenaikanProsen 
									FROM GetTarifBiayaJKN_BUFF ('{$row->JasaID}', 'XX', 'XX', 0, 1, '{$item->NoAnggota}')	
									WHERE (Lokasi='RJ' OR Lokasi='XX') ORDER BY LOKASI ASC
								")
								->row();	
				}
				
				if(empty($tariff_jasa))
				{
					continue;
				}
				
				$doctor = $this->db->select('b.Kode_Supplier, b.Nama_Supplier')							
								->from("{$this->registration_data_model->table} a")
								->join("{$this->supplier_model->table} b", "a.DokterID = b.Kode_Supplier", "INNER")
								->where(['a.NoReg' => $item->NoReg, 'a.SectionID' => $item->SectionID ])
								->get()->row();
								
				$collection[] = [
					'JasaID' => $row->JasaID,
					'JasaName' => $row->JasaName,
					'Qty' => 1,
					'Tarif' => $tariff_jasa->Harga_Baru,
					'DokterID' => $doctor->Kode_Supplier,
					'Nama_Supplier' => $doctor->Nama_Supplier,
					'User_id' => $this->simple_login->get_user()->User_ID,
					'Jam' => date('Y-m-d H:i:s'),
					'HargaOrig' => $tariff_jasa->Harga_Baru,
					"ListHargaID" => $tariff_jasa->ListHargaID,
				];
			}
			
			return $collection;
		}
	}
	
	public function get_service_inpatient( $NoBukti )
	{
		$query = $this->db->select("a.*, b.JasaName, c.Nama_Supplier, d.User_id, d.Nama_Singkat")
					->from("SIMtrRJTransaksi a")
					->join("SIMmListJasa b", "a.JasaID = b.JasaID", "LEFT OUTER")
					->join("mSupplier c", "a.DokterID = c.Kode_Supplier", "LEFT OUTER")
					->join("mUser d", "a.UserID = d.User_ID", "LEFT OUTER")
					->where('a.NoBukti', $NoBukti)
					->get();
					
		return $query->result();
	}
	
	public function get_service_component( $params )
	{

		if( in_array($params['JenisKerjasamaID'], [3, 4, 2] ) ) // Sementara Tidak Untuk Kerjasama & BPJS tarifnya sama dengan Umum
		{
			# GetDetailKomponenTarifNonKerjasama(@ListHargaID int,@UnitBisnisID	varchar(50))
			return
				$this->db->query("
							Select *, KomponenBiayaID AS KomponenID, 0 AS Disc 
							from GetDetailKomponenTarifNonKerjasama(". $params['ListHargaID'] .", '')
						")->result();
			
		}
		if( $params['JenisKerjasamaID'] == 9 ) // Tarif Komponen BPJS 
		{
			# GetDetailKomponenTarifJKN(@ListHargaID int,@UnitBisnisID	varchar(50))
			return
				$this->db->query("
							Select *, KomponenBiayaID AS KomponenID, 0 AS Disc 
							from GetDetailKomponenTarifJKN(". $params['ListHargaID'] .", '')
						")->result();
			
		} 
		 /*elseif( in_array($params['JenisKerjasamaID'], [2, 9] ) )
		{
			# GetDetailKomponenTarifKerjasama(CustomerKerjasamaID int, ListHargaID int, @UnitBisnisID varchar(50))
			return
				$this->db->query("
						Select *, KomponenBiayaID AS KomponenID, 0 AS Disc 
						from 
							dbo.GetDetailKomponenTarifKerjasama(". $params['CustomerKerjasamaID'] .", ". $params['ListHargaID'] .", '')
					")->result();
		
		}*/
						
		return false;
	}

	public function get_service_consumable( $where = NULL, $data )
	{

		if( !empty( $where ) && is_array( $where ))
		{
			$this->db->where( $where );
		}
		
		//$this->db->where("JasaID", $data->JasaID)->get('SIMmListJasaSection')->row();
		
		$query = $this->db
					->select(
						"
							a.JasaID, 
							a.Qty,
							b.Barang_ID,
							b.Nama_Barang,
							b.Harga_Beli,
							a.Satuan, 
							(0 + 0) AS Disc
						"
						)
					->from( "SIMmJasaBHP a" )
					->join("mBarang b", "a.Kode_Barang = b.Kode_Barang", "LEFT OUTER")
					//->join("mBarangLokasiNew c", "b.Barang_ID = c.Barang_ID", "LEFT OUTER")
					->get()
					;
		
		if ( $query->num_rows() > 0 )
		{
			$result = array();
			foreach ( $query->result() as $row )
			{					
				# Params = JenisKerjasamaID, KelasID, KTP, Barang_ID, CustomerKerjasamaID, SectionID, JenisBarangID
				$HargaGrading = $this->db->query("Select * from dbo.GetHargaObatNew_WithStok($data->JenisKerjasamaID, 'xx', $data->KTP, $row->Barang_ID, $data->CustomerKerjasamaID, '$data->SectionID', 0)")->row();					
				$row->HNA = $HargaGrading->HPP_Baru;
				//$row->HPP = $row->Harga_Beli;
				$row->Harga = $HargaGrading->Harga_Baru;
				$row->Jumlah = $HargaGrading->Harga_Baru;
				$row->HargaOrig = $HargaGrading->Harga_Baru;
				$row->HargaPersediaan =  $HargaGrading->HPP_Baru;
				$row->Stok =  $HargaGrading->Stok;
				
				$result[] = $row;
			}
			
			return $result;

		}
		return false;
	}

	public function check_service_component_transaction( $where )
	{

		if( !empty( $where ) && is_array( $where ))
		{
			$this->db->where( $where );
		}

		$count = $this->db
					->count_all_results("SIMtrRJTransaksi")
					;
				
		return (int) $count;
	}
	
	public function get_service_component_transaction( $NoBukti, $JasaID, $Nomor )
	{		
		$query = $this->db->select("
						a.JasaID, 
						a.ListHargaID,							
						a.KomponenID, 
						a.Harga AS HargaBaru, 
						a.Harga AS HargaBPJS, 
						a.Harga AS HargaIKS_Baru, 
						a.HargaOrig AS HargaAwal, 
						a.KelompokAkun,
						a.PostinganKe,						
						c.KomponenName,
						a.Disc
					")
					->from( "SIMtrRJTransaksiDetail a" )
					#->join("SIMdListHargaDetail b", "a.ListHargaID = b.ListHargaID", "LEFT OUTER")
					#->join("SIMmKomponenBiaya c", "b.KomponenBiayaID = c.KomponenBiayaID", "LEFT OUTER")
					->join("SIMmKomponenBiaya c", "a.KomponenID = c.KomponenBiayaID", "LEFT OUTER")
					->where(['a.NoBukti' => $NoBukti, 'a.JasaID' => $JasaID, 'a.Nomor' => $Nomor])
					->get()
					;
		
		if ( $query->num_rows() > 0 )
		{
			return $query->result();
		}
		
		return false;
	}

	public function get_service_consumable_transaction( $NoBukti, $JasaID )
	{		
		$query = $this->db->select("
					a.JasaID, 
					a.Qty,
					b.Barang_ID,
					b.Nama_Barang,
					b.Harga_Beli,
					a.Satuan, 
					(0 + 0) AS Disc
					")
					->from( "SIMtrRJBiayaPOP a" )
					->join("mBarang b", "a.Barang_ID = b.Barang_ID", "LEFT OUTER")
					->where(['a.NoBukti' => $NoBukti, 'a.JasaID' => $JasaID])
					->get();
		
		if ( $query->num_rows() > 0 )
		{
			$result = array();
			foreach ( $query->result() as $row )
			{					
				# Params = JenisKerjasamaID, KelasID, KTP, Barang_ID, CustomerKerjasamaID, SectionID, JenisBarangIDs				
				$result[] = $row;
			}
			
			return $result;

		}
		return false;
	}
	
	public function get_nurse( $where = NULL, $is_edit = FALSE )
	{
		if( !empty( $where ) && is_array( $where ))
		{
			$this->db->where( $where );
		}
				
		if(! $is_edit)
		{
			$query = $this->db->select("Kode_Supplier, Nama_Supplier")
						->from("mSupplier")
						->get();
			
			return $query->result();
		}

		$query = $this->db->select("a.*, b.Kode_Supplier, b.Nama_Supplier")
					->from( "SIMtrRJPerawat a" )
					->join("mSupplier b", "a.PerawatID = b.Kode_Supplier", "LEFT OUTER")
					->get()
					;
		
		if ( $query->num_rows() > 0 )
		{
			return $query->result();
		}
	}
		
	public function get_prescriptions_data( $where = NULL )
	{

		if( !empty( $where ) && is_array( $where ))
		{
			$this->db->where( $where );
		}

		$query = $this->db
					->select("a.*, b.Nama_Supplier")
					->from( "SIMtrResep a" )
					->join("mSupplier b", "a.DokterID = b.Kode_Supplier", "LEFT OUTER")
					->get()
					;
		
		if ( $query->num_rows() > 0 )
		{
			return $query->result();
		}
		
		return false;
	}

	public function get_prescriptions_detail_data( $where = NULL )
	{

		if( !empty( $where ) && is_array( $where ))
		{
			$this->db->where( $where );
		}

		$query = $this->db
					->select("a.*, b.Nama_Barang, b.Kode_Barang")
					->from( "SIMtrResepDetail a" )
					->join("mBarang b", "a.Barang_ID = b.Barang_ID", "LEFT OUTER")
					->get()
					;
		
		if ( $query->num_rows() > 0 )
		{
			return $query->result();
		}
		
		return false;
	}

	public function get_helper( $where = NULL )
	{

		if( !empty( $where ) && is_array( $where ))
		{
			$this->db->where( $where );
		}

		$query = $this->db
					->select("a.*, b.Nama_Supplier, c.SectionName")
					->from( "SIMtrMemoPenunjang a" )
					->join("mSupplier b", "a.DokterID = b.Kode_Supplier", "LEFT OUTER")
					->join("SIMmSection c", "a.SectionTujuanID = c.SectionID", "LEFT OUTER")
					->get()
					;
		
		if ( $query->num_rows() > 0 )
		{
			return $query->result();
		}
		
		return false;
	}


	public function get_consumable_detail_data( $where = NULL )
	{

		if( !empty( $where ) && is_array( $where ))
		{
			$this->db->where( $where );
		}

		$query = $this->db
					->select("a.*, b.Nama_Barang, b.Kode_Barang")
					->from( "BILLFarmasiDetail a" )
					->join("mBarang b", "a.Barang_ID = b.Barang_ID", "LEFT OUTER")
					->get()
					;
		
		if ( $query->num_rows() > 0 )
		{
			return $query->result();
		}
		
		return false;
	}

	public function get_memo_data( $where = NULL )
	{

		if( !empty( $where ) && is_array( $where ))
		{
			$this->db->where( $where );
		}

		$query = $this->db
					->select("a.*, b.SectionName, c.Username")
					->from( "SIMtrMemo a" )
					->join("SIMmSection b", "a.SectionID = b.SectionID", "LEFT OUTER")
					->join("mUser c", "a.User_ID = c.User_ID", "LEFT OUTER")
					->get()
					;
		
		if ( $query->num_rows() > 0 )
		{
			return $query->result();
		}
		
		return false;
	}

	public function get_checkout( $where = NULL )
	{

		if( !empty( $where ) && is_array( $where ))
		{
			$this->db->where( $where );
		}

		$query = $this->db
					->select("a.*, a.NoAntri AS NoUrut, b.Nama_Supplier, c.SectionName, d.Keterangan")
					->from( "SIMtrDataRegPasien a" )
					->join("mSupplier b", "a.DokterID = b.Kode_Supplier", "LEFT OUTER")
					->join("SIMmSection c", "a.SectionID = c.SectionID", "LEFT OUTER")
					->join("SIMmWaktuPraktek d", "a.WaktuID = d.WaktuID", "LEFT OUTER")
					->get()
					;
		
		if ( $query->num_rows() > 0 )
		{
			return $query->result();
		}
		
		return false;
	}
	
	public function get_section_queue( $table, $where = NULL )
	{

		if( !empty( $where ) && is_array( $where ))
		{
			$this->db->where( $where );
		}

		$query = $this->db
					->select("COUNT( b.NoReg ) as queue") 
					->from( $table ." a" )
					->join( "simtrregistrasitujuan b", "a.NoReg = b.NoReg", "LEFT OUTER" )
					->get()
					;
		
		if ( $query->num_rows() > 0 )
		{
			return $query->row()->queue;
		}
		
		return false;
	}
	
	public function get_max_number( $table, $where = NULL, $max_field )
	{

		if( !empty( $where ) && is_array( $where ))
		{
			$this->db->where( $where );
		}

		$query = $this->db
					->select("MAX( $max_field ) as max_number") 
					->from( $table )
					->get()
					;
		
		if ( $query->num_rows() > 0 )
		{
			return (int) $query->row()->max_number;
		}
		
		return 0;
	}

	public function get_last_stock_warehouse_card( $where = NULL )
	{

		if( !empty( $where ) && is_array( $where ))
		{
			$this->db->where( $where );
		}

		$query = $this->db
					->select("Qty_Saldo")
					->order_by("Kartu_ID", "DESC")
					->get("GD_trKartuGudang");
		
		if ( $query->num_rows() > 0 )
		{
			return $query->row()->Qty_Saldo;
		}
		
		return false;
	}
	
	/*public function get_service_component( $where = NULL )
	{

		if( !empty( $where ) && is_array( $where ))
		{
			$this->db->where( $where );
		}

		$query = $this->db
					->select("a.ListHargaID, a.JasaID, b.KomponenID, b.HargaBaru, b.HargaIKS_Baru, b.HargaBPJS, c.KelompokAkun, c.PostinganKe,") 
					->from( "SIMdListHarga a" )
					->join( "SIMdListHargaDetail b", "a.ListHargaID = b.ListHargaID", "LEFT OUTER" )
					->join( "SIMmKomponenBiaya c", "b.KomponenBiayaID = c.KomponenBiayaID", "LEFT OUTER" )
					->get()
					;
		
		if ( $query->num_rows() > 0 )
		{
			return $query->row()->queue;
		}
		
		return false;
	}*/

}