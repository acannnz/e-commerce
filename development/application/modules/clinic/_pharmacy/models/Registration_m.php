<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Registration_m extends Public_Model
{
	public $table = 'SimtrRegistrasi';
	public $primary_key = 'id';
	
	public function __construct()
	{
		$this->rules = array('insert' => array(
			array(
					'field' => 'registration_number',
					'label' => lang('registrations:registration_number_label'),
					'rules' => 'trim|required'
				),
			array(
					'field' => 'registration_date',
					'label' => lang('registrations:date_label'),
					'rules' => 'trim|required|exact_length[10]'
				),
			array(
					'field' => 'registration_time',
					'label' => lang('registrations:time_label'),
					'rules' => 'trim|min_length[5]|max_length[8]'
				),
			array(
					'field' => 'mr_number',
					'label' => lang('registrations:mr_number_label'),
					'rules' => ''
				),
			array(
					'field' => 'patient_type_id',
					'label' => lang('registrations:type_label'),
					'rules' => 'integer'
				),
			array(
					'field' => 'personal_name',
					'label' => lang('registrations:name_label'),
					'rules' => 'trim|required'
				),
			array(
					'field' => 'personal_gender',
					'label' => lang('registrations:gender_label'),
					'rules' => 'trim'
				),
			array(
					'field' => 'personal_birth_date',
					'label' => lang('registrations:birth_date_label'),
					'rules' => 'trim'
				),
			array(
					'field' => 'personal_address',
					'label' => lang('registrations:address_label'),
					'rules' => 'trim|required'
				),
			array(
					'field' => 'country_id',
					'label' => lang('registrations:country_label'),
					'rules' => 'integer'
				),
			array(
					'field' => 'province_id',
					'label' => lang('registrations:province_label'),
					'rules' => 'integer'
				),
			array(
					'field' => 'county_id',
					'label' => lang('registrations:county_label'),
					'rules' => 'integer'
				),
			array(
					'field' => 'district_id',
					'label' => lang('registrations:district_label'),
					'rules' => 'integer'
				),
			array(
					'field' => 'area_id',
					'label' => lang('registrations:area_label'),
					'rules' => 'integer'
				),
			array(
					'field' => 'phone_number',
					'label' => lang('registrations:phone_label'),
					'rules' => 'trim'
				),
			array(
					'field' => 'mobile_number',
					'label' => lang('registrations:mobile_label'),
					'rules' => 'trim'
				),
			array(
					'field' => 'email_address',
					'label' => lang('registrations:email_label'),
					'rules' => 'trim|valid_email'
				),
			array(
					'field' => 'schedule_date',
					'label' => lang('registrations:for_date_label'),
					'rules' => 'trim'
				),
			array(
					'field' => 'schedule_time',
					'label' => lang('registrations:for_time_label'),
					'rules' => 'trim'
				),
			array(
					'field' => 'schedule_queue',
					'label' => lang('registrations:queue_label'),
					'rules' => 'integer'
				),
			array(
					'field' => 'state',
					'label' => lang('registrations:status_label'),
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

	public function get_section_destination_from_reservation( $NoReservasi = NULL )
	{
		if (!$NoReservasi)
		{
			return false;
		}
		
		$this->load->helper("registrations/registration");
		// get result filtered
		$db_select = <<<EOSQL
			a.UntukDokterID AS DokterID,
			a.UntukSectionID AS SectionID,
			a.WaktuID,
			b.Nama_Supplier,
			e.SpesialisName,
			c.SectionName,
			d.Keterangan
			
EOSQL;

		$query = $this->db
					->select( $db_select )
					->from("SimtrReservasi a")
					->join( "{$this->supplier_m->table} b", "a.UntukDokterID = b.Kode_Supplier", "LEFT OUTER" )
					->join( "{$this->supplier_specialist_m->table} e", "b.SpesialisID = e.SpesialisID", "LEFT OUTER" )
					->join( "{$this->section_m->table} c", "a.UntukSectionID = c.SectionID", "LEFT OUTER" )
					->join( "{$this->time_m->table} d", "a.WaktuID = d.WaktuID", "LEFT OUTER" )
					->where( "a.NoReservasi", $NoReservasi )
					->get();
		
		if ( $query->num_rows() > 0 )
		{
			$collection = array();
			foreach ($query->result() as $row )
			{
				$params = (object) array(
						"SectionID" => $row->SectionID,
						"DokterID" => $row->DokterID,
						"WaktuID" => $row->WaktuID,
					);
				
				$queue = registration_helper::get_queue( $params );
				$row->NoUrut = $queue > $this->config->item("start_queue") ? $queue : $this->config->item("start_queue");

				$collection[] = $row;
			}
			
			return $collection;
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

	public function get_option_section()
	{
		$query = $this->db
					->where( array("StatusAktif" => 1))
					->where_in("TipePelayanan", array("RJ","PENUNJANG"))
					->where_in("KelompokSection", array("POLI","UGD","LAB & RAD"))
					->order_by("SectionName")
					->get("SIMmSection");
		
		if ( $query->num_rows() > 0 )
		{
			return $query->result();
		}
		
		return false;
	}
	
	public function get_option_nationality()
	{
		$query = $this->db->get("mNationality");
		
		if ( $query->num_rows() > 0 )
		{
			return $query->result();
		}
		
		return false;
	}

	public function get_option_zones( $table, $where = NULL )
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

	public function get_default_zones( $table, $where = NULL )
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
	
	public function get_option_province()
	{
		$query = $this->db->get("mPropinsi");
		
		if ( $query->num_rows() > 0 )
		{
			return $query->result();
		}
		
		return false;
	}

	public function get_option_county( $where )
	{
		if( !empty($where))
		{
			$this->db->where($where);
		}

		$query = $this->db->get("mKabupaten");
		
		if ( $query->num_rows() > 0 )
		{
			return $query->result();
		}
		
		return false;
	}

	public function get_option_district( $where )
	{
		
		if( !empty($where))
		{
			$this->db->where($where);
		}
		
		$query = $this->db->get("mKecamatan");
		
		if ( $query->num_rows() > 0 )
		{
			return $query->result();
		}
		
		return false;
	}

	public function get_option_village( $where )
	{
		
		if( !empty($where))
		{
			$this->db->where($where);
		}
		
		$query = $this->db->get("mDesa");
		
		if ( $query->num_rows() > 0 )
		{
			return $query->result();
		}
		
		return false;
	}

	public function get_option_area( $where )
	{
		
		if( !empty($where))
		{
			$this->db->where($where);
		}
		
		$query = $this->db->get("mBanjar");
		
		if ( $query->num_rows() > 0 )
		{
			return $query->result();
		}
		
		return false;
	}

	public function check_data( $table, $where )
	{
		
		if( !empty($where))
		{
			$this->db->where($where);
		}
		
		$query = $this->db->get( $table );
		
		return $query->num_rows();
		
	}
	
}