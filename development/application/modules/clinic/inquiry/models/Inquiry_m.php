<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Inquiry_m extends Public_Model
{
	public $table = 'GD_trAmprahan';
	public $primary_key = 'NoBukti';
	
	public function __construct()
	{
		$this->rules = array('insert' => array(
			array(
					'field' => 'registration_number',
					'label' => lang('inquiry:registration_number_label'),
					'rules' => 'trim|required'
				),
			array(
					'field' => 'registration_date',
					'label' => lang('inquiry:date_label'),
					'rules' => 'trim|required|exact_length[10]'
				),
			array(
					'field' => 'registration_time',
					'label' => lang('inquiry:time_label'),
					'rules' => 'trim|min_length[5]|max_length[8]'
				),
			array(
					'field' => 'mr_number',
					'label' => lang('inquiry:mr_number_label'),
					'rules' => ''
				),
			array(
					'field' => 'patient_type_id',
					'label' => lang('inquiry:type_label'),
					'rules' => 'integer'
				),
			array(
					'field' => 'personal_name',
					'label' => lang('inquiry:name_label'),
					'rules' => 'trim|required'
				),
			array(
					'field' => 'personal_gender',
					'label' => lang('inquiry:gender_label'),
					'rules' => 'trim'
				),
			array(
					'field' => 'personal_birth_date',
					'label' => lang('inquiry:birth_date_label'),
					'rules' => 'trim'
				),
			array(
					'field' => 'personal_address',
					'label' => lang('inquiry:address_label'),
					'rules' => 'trim|required'
				),
			array(
					'field' => 'country_id',
					'label' => lang('inquiry:country_label'),
					'rules' => 'integer'
				),
			array(
					'field' => 'province_id',
					'label' => lang('inquiry:province_label'),
					'rules' => 'integer'
				),
			array(
					'field' => 'county_id',
					'label' => lang('inquiry:county_label'),
					'rules' => 'integer'
				),
			array(
					'field' => 'district_id',
					'label' => lang('inquiry:district_label'),
					'rules' => 'integer'
				),
			array(
					'field' => 'area_id',
					'label' => lang('inquiry:area_label'),
					'rules' => 'integer'
				),
			array(
					'field' => 'phone_number',
					'label' => lang('inquiry:phone_label'),
					'rules' => 'trim'
				),
			array(
					'field' => 'mobile_number',
					'label' => lang('inquiry:mobile_label'),
					'rules' => 'trim'
				),
			array(
					'field' => 'email_address',
					'label' => lang('inquiry:email_label'),
					'rules' => 'trim|valid_email'
				),
			array(
					'field' => 'schedule_date',
					'label' => lang('inquiry:for_date_label'),
					'rules' => 'trim'
				),
			array(
					'field' => 'schedule_time',
					'label' => lang('inquiry:for_time_label'),
					'rules' => 'trim'
				),
			array(
					'field' => 'schedule_queue',
					'label' => lang('inquiry:queue_label'),
					'rules' => 'integer'
				),
			array(
					'field' => 'state',
					'label' => lang('inquiry:status_label'),
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
	
	public function get_option_section_pharmacy ()
	{
		$query = $this->db
					->where_in("TipePelayanan", array("GUDANG", "FARMASI"))
					->where("StatusAktif", 1)
					->order_by("SectionName", "ASC")
					->get("SIMmSection");
		
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
		
	public function get_options( $table, $where = NULL, $order = NULL, $or_where = NULL, $where_in = NULL )
	{

		if( !empty( $where ) && is_array( $where ))
		{
			$this->db->where( $where );
		}

		if( !empty( $or_where ) && is_array( $or_where ))
		{
			$this->db->or_where( $or_where );
		}

		if( !empty( $where_in ) && is_array( $where_in ))
		{
			$this->db->where_in( $where_in );
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

	public function get_inquiry_data( $where = NULL )
	{

		if( !empty( $where ) && is_array( $where ))
		{
			$this->db->where( $where );
		}

		$query = $this->db
					->select("a.*, b.Nama_Supplier,")
					->from("SIMtrRegistrasiTujuan a")
					->join("mSupplier b", "a.DokterID = b.Kode_Supplier", "LEFT OUTER")
					->get();
		
		if ( $query->num_rows() > 0 )
		{
			return $query->row();
		}
		
		return false;
	}

	public function get_inquiry_detail( $NoBukti = NULL )
	{

		$db_from = "GD_trAmprahanDetail a";
		
		$db_select = <<<EOSQL
			a.Barang_ID,
			b.Kode_Barang,
			b.Nama_Barang,
			a.Qty AS QtyAmprah,
			a.Qty AS Qty_Amprah,
			a.Qty,
			a.QtyStok AS Qty_Stok,
			a.Satuan AS Satuan_Stok,
			c.Nama_Satuan AS Kode_Satuan,
			f.Nama_Satuan AS Satuan_Beli,
			d.Nama_Kategori AS Kategori,
			e.Nama_Sub_Kategori AS Sub_Kategori,
			b.Harga_Jual AS Harga,
			b.HRataRata,
			b.Stok_Satuan_ID,
			b.Kategori_id,
			b.SubKategori_id,
			b.Konversi,
			g.Akun_ID_Mutasi AS MutasiAkun_ID
			
			
EOSQL;

		$query = $this->db
					->select( $db_select )
					->from( $db_from )
					->join( "mBarang b", "a.Barang_ID = b.Barang_ID", "LEFT OUTER" )
					->join( "mSatuan c", "b.Stok_Satuan_ID = c.Satuan_ID", "LEFT OUTER" )
					->join( "mKategori d", "b.Kategori_id = d.Kategori_ID", "LEFT OUTER" )
					->join( "mSubKategori e", "b.SubKategori_id = e.SubKategori_ID", "LEFT OUTER" )
					->join( "mSatuan f", "b.Beli_Satuan_id = f.Satuan_ID", "LEFT OUTER" )
					->join( "SIMmKelompokJenisObat g"," b.KelompokJenis = g.KelompokJenis", "LEFT OUTER" )
					->where( array("a.NoBukti" => $NoBukti) )
					->get()
					;
		
		if ( $query->num_rows() > 0 )
		{
			return $query->result();
		}
		
		return false;
	}

	public function get_opname_detail( $No_Bukti )
	{

		$db_select = <<<EOSQL
			a.Barang_ID,
			b.Kode_Barang,
			b.Nama_Barang,
			a.Stock_Akhir,
			a.Qty_Opname,
			(a.Qty_Opname - a.Stock_Akhir) AS Selisih,
			a.Kode_Satuan,
			d.Nama_Kategori AS Kategori,
			b.Harga_Jual,
			b.HRataRata AS Harga_Rata,
			b.Konversi,
			a.JenisBarangID,
			b.KelompokJenis,
			a.Tgl_Expired,
			a.Keterangan
			
EOSQL;

		$query = $this->db
			->select( $db_select )
			->from( "GD_trOpnameDetail a" )
			->join( "mBarang b", "a.Barang_ID = b.Barang_ID", "LEFT OUTER" )
			->join( "mSatuan c", "b.Stok_Satuan_ID = c.Satuan_ID", "LEFT OUTER" )
			->join( "mKategori d", "b.Kategori_id = d.Kategori_ID", "LEFT OUTER" )
			->join( "mSubKategori e", "b.SubKategori_id = e.SubKategori_ID", "LEFT OUTER" )
			->join( "SIMmKelompokJenisObat g", "b.KelompokJenis = g.KelompokJenis", "LEFT OUTER")
			->where( array("a.No_Bukti" => $No_Bukti) )
			->get();
			;
		
		if ( $query->num_rows() > 0 )
		{
			return $query->result();
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

	public function get_tujuan_stok( $where = NULL )
	{

		if( !empty( $where ) && is_array( $where ))
		{
			$this->db->where( $where );
		}

		$query = $this->db
					->select("Qty_Stok")
					->from( "mBarangLokasiNew" )
					->get()
					;
		
		if ( $query->num_rows() > 0 )
		{
			return $query->row()->Qty_Stok;
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

	public function get_option_section_opname()
	{

		$query = $this->db
					->from( "SIMmSection" )
					->where("StatusAktif", 1)
					->where_in("TipePelayanan", array("RJ", "GUDANG", "FARMASI", "PENUNJANG"))
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