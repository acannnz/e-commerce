<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pharmacy_m extends Public_Model
{
	public $table = 'BILLFarmasi';
	public $primary_key = 'NoBukti';
	
	public function __construct()
	{
		$this->rules = array('insert' => array(
			array(
					'field' => 'registration_number',
					'label' => lang('pharmacy:registration_number_label'),
					'rules' => 'trim|required'
				),
			array(
					'field' => 'registration_date',
					'label' => lang('pharmacy:date_label'),
					'rules' => 'trim|required|exact_length[10]'
				),
			array(
					'field' => 'registration_time',
					'label' => lang('pharmacy:time_label'),
					'rules' => 'trim|min_length[5]|max_length[8]'
				),
			array(
					'field' => 'mr_number',
					'label' => lang('pharmacy:mr_number_label'),
					'rules' => ''
				),
			array(
					'field' => 'patient_type_id',
					'label' => lang('pharmacy:type_label'),
					'rules' => 'integer'
				),
			array(
					'field' => 'personal_name',
					'label' => lang('pharmacy:name_label'),
					'rules' => 'trim|required'
				),
			array(
					'field' => 'personal_gender',
					'label' => lang('pharmacy:gender_label'),
					'rules' => 'trim'
				),
			array(
					'field' => 'personal_birth_date',
					'label' => lang('pharmacy:birth_date_label'),
					'rules' => 'trim'
				),
			array(
					'field' => 'personal_address',
					'label' => lang('pharmacy:address_label'),
					'rules' => 'trim|required'
				),
			array(
					'field' => 'country_id',
					'label' => lang('pharmacy:country_label'),
					'rules' => 'integer'
				),
			array(
					'field' => 'province_id',
					'label' => lang('pharmacy:province_label'),
					'rules' => 'integer'
				),
			array(
					'field' => 'county_id',
					'label' => lang('pharmacy:county_label'),
					'rules' => 'integer'
				),
			array(
					'field' => 'district_id',
					'label' => lang('pharmacy:district_label'),
					'rules' => 'integer'
				),
			array(
					'field' => 'area_id',
					'label' => lang('pharmacy:area_label'),
					'rules' => 'integer'
				),
			array(
					'field' => 'phone_number',
					'label' => lang('pharmacy:phone_label'),
					'rules' => 'trim'
				),
			array(
					'field' => 'mobile_number',
					'label' => lang('pharmacy:mobile_label'),
					'rules' => 'trim'
				),
			array(
					'field' => 'email_address',
					'label' => lang('pharmacy:email_label'),
					'rules' => 'trim|valid_email'
				),
			array(
					'field' => 'schedule_date',
					'label' => lang('pharmacy:for_date_label'),
					'rules' => 'trim'
				),
			array(
					'field' => 'schedule_time',
					'label' => lang('pharmacy:for_time_label'),
					'rules' => 'trim'
				),
			array(
					'field' => 'schedule_queue',
					'label' => lang('pharmacy:queue_label'),
					'rules' => 'integer'
				),
			array(
					'field' => 'state',
					'label' => lang('pharmacy:status_label'),
					'rules' => 'integer'
				),
		));
		
		parent::__construct();
	}
	
	protected function _before_delete( $data )
	{
		return $data;
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
		
		return false;
	}

	public function get_section_destination( $NoReg = NULL )
	{
		if (!$NoReg)
		{
			return false;
		}
		// get result filtered
		$db_select = <<<EOSQL
			a.DokterID,
			a.SectionID,
			a.WaktuID,
			a.NoUrut,
			b.Nama_Supplier,
			e.SpesialisName,
			c.SectionName,
			d.Keterangan
			
EOSQL;

		$query = $this->db
					->select( $db_select )
					->from("SimtrRegistrasiTujuan a")
					->join( "{$this->supplier_m->table} b", "a.DokterID = b.Kode_Supplier", "LEFT OUTER" )
					->join( "{$this->supplier_specialist_m->table} e", "b.SpesialisID = e.SpesialisID", "LEFT OUTER" )
					->join( "{$this->section_m->table} c", "a.SectionID = c.SectionID", "LEFT OUTER" )
					->join( "{$this->time_m->table} d", "a.WaktuID = d.WaktuID", "LEFT OUTER" )
					->where( "a.NoReg", $NoReg )
					->get();
		
		if ( $query->num_rows() > 0 )
		{
			return $query->result();
		}
		
		return false;
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

	public function get_option_supplier()
	{
		$query = $this->db
					->where_in("KodeKategoriVendor", array('V-002','V-009'))
					->get("mSupplier");
		
		if ( $query->num_rows() > 0 )
		{
			return $query->result();
		}
		
		return false;
	}
	
	public function get_result_data( $table, $where = NULL )
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
		
	public function get_options( $table, $where = NULL, $order )
	{

		if( !empty( $where ) && is_array( $where ))
		{
			$this->db->where( $where );
		}

		if( !empty( $order ) && is_array( $order ))
		{
			$this->db->order_by( $order['by'], $order['sort'] );
		}

		$query = $this->db->get( $table );
		
		if ( $query->num_rows() > 0 )
		{
			return $query->result();
		}
		
		return false;
	}

	public function get_pharmacy_data( $where = NULL )
	{

		if( !empty( $where ) && is_array( $where ))
		{
			$this->db->where( $where );
		}

		$query = $this->db
					->select("a.*, b.Nama_Supplier")
					->from("BILLFarmasi a")
					->join("mSupplier b", "a.DokterID = b.Kode_Supplier", "LEFT OUTER")
					->get();
		
		if ( $query->num_rows() > 0 )
		{
			return $query->row();
		}
		
		return false;
	}

	public function get_pharmacy_data_bhp( $where = NULL )
	{

		if( !empty( $where ) && is_array( $where ))
		{
			$this->db->where( $where );
		}

		$query = $this->db
					->select("a.*, b.Nama_Supplier")
					->from("SIMtrPOP a")
					->join("mSupplier b", "a.DokterID = b.Kode_Supplier", "LEFT OUTER")
					->get();
		
		if ( $query->num_rows() > 0 )
		{
			return $query->row();
		}
		
		return false;
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
	
	public function get_farmasi_detail( $NoBukti = NULL )
	{
		
		if ( !$NoBukti )
		{
			return [];
		}

		$query = $this->db
					->select("a.*, b.Kode_Barang, b.Nama_Barang, c.Dosis as Dosis_view")
					->from("BILLFarmasiDetail a")
					->join("mBarang b", "a.Barang_ID = b.Barang_ID", "LEFT OUTER")
					->join("SIMmDosisObat c", "a.Dosis = c.Dosis", "LEFT OUTER")
					->where( "a.NoBukti", @$NoBukti )
					->get()
					;
		
		if ( $query->num_rows() > 0 )
		{
			return $query->result();
		}
		
		return array();
	}

	public function get_bhp_detail( $NoBuktiPOP = NULL )
	{
		
		if ( !$NoBuktiPOP )
		{
			return [];
		}

		$query = $this->db
					->select("a.*, b.Kode_Barang, b.Nama_Barang")
					->from("SIMtrDetailPOP a")
					->join("mBarang b", "a.Barang_Id = b.Barang_ID", "LEFT OUTER")
					->where( "a.NoBuktiPOP", @$NoBuktiPOP )
					->get()
					;
		
		if ( $query->num_rows() > 0 )
		{
			return $query->result();
		}
		
		return array();
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

	public function get_prescription_data( $NoResep = NULL )
	{
		$query = $this->db
					->select("
						a.*, 
						a.JenisKerjasamaID AS KerjasamaID,
						b.NRM, 
						c.NamaPasien,
						a.CompanyID AS KodePerusahaan,
						f.Nama_Customer,
						c.Alamat,
						c.TglLahir,
						b.UmurThn,
						b.UmurBln,
						b.UmurHr,
						b.NoReg,
						d.SectionID,
						e.Nama_Supplier
					")
					->from("SIMtrResep a")
					->join("SIMtrRegistrasi b", "a.NoRegistrasi = b.NoReg", "LEFT OUTER")
					->join("mPasien c", "b.NRM = c.NRM", "LEFT OUTER")
					->join("SIMmSection d", "a.SectionID = d.SectionID", "LEFT OUTER")
					->join("mSupplier e", "a.DokterID = e.Kode_Supplier", "LEFT OUTER")
					->join("mCustomer f", "a.CompanyID = f.Kode_Customer", "LEFT OUTER")
					->where("a.NoResep", $NoResep)
					->get()
					;
		
		if ( $query->num_rows() > 0 )
		{
			return $query->row();
		}
		
		return false;
	}

	public function get_prescription_data_bhp( $NoBuktiPOP = NULL )
	{
		$query = $this->db
					->select("
						a.*, 
						a.KerjasamaID,
						b.NRM, 
						c.NamaPasien,
						a.PerusahaanID AS KodePerusahaan,
						f.Nama_Customer,
						c.Alamat,
						c.TglLahir,
						b.UmurThn,
						b.UmurBln,
						b.UmurHr,
						b.NoReg,
						d.SectionID,
						e.Nama_Supplier
					")
					->from("SIMtrPOP a")
					->join("SIMtrRegistrasi b", "a.NoReg = b.NoReg", "LEFT OUTER")
					->join("mPasien c", "b.NRM = c.NRM", "LEFT OUTER")
					->join("SIMmSection d", "a.SectionID = d.SectionID", "LEFT OUTER")
					->join("mSupplier e", "a.DokterID = e.Kode_Supplier", "LEFT OUTER")
					->join("mCustomer f", "a.PerusahaanID = f.Kode_Customer", "LEFT OUTER")
					->where("a.NoBuktiPOP", $NoBuktiPOP)
					->get()
					;
		
		if ( $query->num_rows() > 0 )
		{
			return $query->row();
		}
		
		return false;
	}

	public function get_prescription_detail( $NoResep = NULL, $item = array() )
	{
		$query = $this->db
					->select("a.*, b.Nama_Barang, b.Kode_Barang","b.BiayaResep")
					->from( "SIMtrResepDetail a" )
					->join("mBarang b", "a.Barang_ID = b.Barang_ID", "LEFT OUTER")
					->where("a.NoResep", $NoResep )
					->get()
					;
		
		if ( $query->num_rows() > 0 )
		{
			$collection = array();
			foreach($query->result() as $row)
			{
				if ( in_array($row->Satuan, array("RESEP", "RACIKAN")) )
				{
					$row->HNA = $row->Harga_Satuan;
					$row->HPP = $row->Harga_Satuan;
					$row->Harga = $row->Harga_Satuan;
					$row->HargaOrig = $row->Harga_Satuan;
					$row->HargaPersediaan =  $row->Harga_Satuan;
				} else   {
					# Params = JenisKerjasamaID, KelasID, KTP, Barang_ID, CustomerKerjasamaID, SectionID, JenisBarangID
					$HargaGrading = $this->db->query("Select * from dbo.GetHargaObatNew_WithStok({$item->JenisKerjasamaID}, 'xx', {$item->KTP}, {$row->Barang_ID}, ". (int) @$item->CustomerKerjasamaID.", '". config_item('section_id') ."', 0)")->row();					
					$row->HNA = $HargaGrading->HPP_Baru;
					$row->HPP = $row->Harga_Satuan;
					$row->Harga = $HargaGrading->Harga_Baru;
					$row->HargaOrig = $HargaGrading->Harga_Baru;
					$row->HargaPersediaan =  $HargaGrading->HPP_Baru;
				}	
				$collection[] = $row;
			}

			return $collection;
		}

		
		return false;
	}

	public function get_prescription_detail_bhp( $NoBuktiPOP = NULL, $item = array() )
	{
		// print_r($item);exit;
		$query = $this->db
					->select("a.*, b.Nama_Barang, b.Kode_Barang","b.BiayaResep")
					->from( "SIMtrDetailPOP a" )
					->join("mBarang b", "a.Barang_Id = b.Barang_ID", "LEFT OUTER")
					->where("a.NoBuktiPOP", $NoBuktiPOP )
					->get()
					;
		
		if ( $query->num_rows() > 0 )
		{
			$collection = array();
			foreach($query->result() as $row)
			{
				if ( in_array($row->Satuan, array("RESEP", "RACIKAN")) )
				{
					$row->HNA = $row->HargaSatuan;
					$row->HPP = $row->HargaSatuan;
					$row->Harga = $row->HargaSatuan;
					$row->HargaOrig = $row->HargaSatuan;
					$row->HargaPersediaan =  $row->HargaSatuan;
				} else   {
					# Params = JenisKerjasamaID, KelasID, KTP, Barang_ID, CustomerKerjasamaID, SectionID, JenisBarangID
					$HargaGrading = $this->db->query("Select * from dbo.GetHargaObatNew_WithStok({$item->KerjasamaID}, 'xx', {$item->KTP}, {$row->Barang_Id}, ". (int) @$item->CustomerKerjasamaID.", '". config_item('section_id') ."', 0)")->row();					
					$row->HNA = $HargaGrading->HPP_Baru;
					$row->HPP = $row->HargaSatuan;
					$row->Harga = $HargaGrading->Harga_Baru;
					$row->HargaOrig = $HargaGrading->Harga_Baru;
					$row->HargaPersediaan =  $HargaGrading->HPP_Baru;
				}	
				$collection[] = $row;
			}

			return $collection;
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
			return $query->row()->max_number;
		}
		
		return false;
	}

	public function get_service_component( $where = NULL )
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
	}
	
}