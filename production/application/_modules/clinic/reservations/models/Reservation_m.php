<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reservation_m extends Public_Model
{
	public $table = 'SIMtrReservasi';
	public $primary_key = 'NoReservasi';
	
	public $rules;
	
	public function __construct()
	{
		$this->rules = array('insert' => array(
			array(
					'field' => 'NoReservasi',
					'label' => lang('registrations:registration_number_label'),
					'rules' => ''
				),
			array(
					'field' => 'Tanggal',
					'label' => lang('registrations:date_label'),
					'rules' => ''
				),
			array(
					'field' => 'Jam',
					'label' => lang('registrations:time_label'),
					'rules' => ''
				),
			array(
					'field' => 'PasienBaru',
					'label' => lang('registrations:mr_number_label'),
					'rules' => ''
				),
			array(
					'field' => 'NRM',
					'label' => lang('registrations:type_label'),
					'rules' => ''
				),
			array(
					'field' => 'Nama',
					'label' => lang('registrations:name_label'),
					'rules' => ''
				),
			array(
					'field' => 'Alamat',
					'label' => lang('registrations:gender_label'),
					'rules' => ''
				),
			array(
					'field' => 'Phone',
					'label' => lang('registrations:birth_date_label'),
					'rules' => ''
				),
			array(
					'field' => 'UntukSectionID',
					'label' => lang('registrations:address_label'),
					'rules' => ''
				),
			array(
					'field' => 'UntukDokterID',
					'label' => lang('registrations:country_label'),
					'rules' => ''
				),
			array(
					'field' => 'UntukHari',
					'label' => lang('registrations:province_label'),
					'rules' => ''
				),
			array(
					'field' => 'UntukTanggal',
					'label' => lang('registrations:county_label'),
					'rules' => ''
				),
			//array(
//					'field' => 'UntukJam',
//					'label' => lang('registrations:district_label'),
//					'rules' => ''
//				),
			array(
					'field' => 'NoUrut',
					'label' => lang('registrations:area_label'),
					'rules' => ''
				),
			array(
					'field' => 'User_ID',
					'label' => lang('registrations:phone_label'),
					'rules' => ''
				),
			array(
					'field' => 'Registrasi',
					'label' => lang('registrations:mobile_label'),
					'rules' => ''
				),
			array(
					'field' => 'JmlAntrian',
					'label' => lang('registrations:email_label'),
					'rules' => ''
				),
			array(
					'field' => 'WaktuID',
					'label' => lang('registrations:for_date_label'),
					'rules' => ''
				),
			array(
					'field' => 'JenisKerjasamaID',
					'label' => lang('registrations:for_time_label'),
					'rules' => ''
				),
			array(
					'field' => 'TanggalLahir',
					'label' => lang('registrations:queue_label'),
					'rules' => ''
				),
			array(
					'field' => 'Email',
					'label' => lang('registrations:status_label'),
					'rules' => ''
				),
			array(
					'field' => 'TglPerkiraan_1',
					'label' => lang('registrations:status_label'),
					'rules' => ''
				),
			array(
					'field' => 'TglPerkiraan_2',
					'label' => lang('registrations:status_label'),
					'rules' => ''
				),
			array(
					'field' => 'Tindakan_SC',
					'label' => lang('registrations:status_label'),
					'rules' => ''
				),
			array(
					'field' => 'Tindakan_Normal',
					'label' => lang('registrations:status_label'),
					'rules' => ''
				),
			array(
					'field' => 'KelasID',
					'label' => lang('registrations:status_label'),
					'rules' => ''
				),
			array(
					'field' => 'Deposit',
					'label' => lang('registrations:status_label'),
					'rules' => ''
				),
			array(
					'field' => 'PermintaanKhusus',
					'label' => lang('registrations:status_label'),
					'rules' => ''
				),
			array(
					'field' => 'Batal',
					'label' => lang('registrations:status_label'),
					'rules' => ''
				),
			array(
					'field' => 'Paid',
					'label' => lang('registrations:status_label'),
					'rules' => ''
				),
			array(
					'field' => 'PaidNoBukti',
					'label' => lang('registrations:status_label'),
					'rules' => ''
				),
			array(
					'field' => 'UserIDPaid',
					'label' => lang('registrations:status_label'),
					'rules' => ''
				),
			array(
					'field' => 'TipeReservasi',
					'label' => lang('registrations:status_label'),
					'rules' => ''
				),
			array(
					'field' => 'NamaSuami',
					'label' => lang('registrations:status_label'),
					'rules' => ''
				),
			array(
					'field' => 'KodeCUstomerIKS',
					'label' => lang('registrations:status_label'),
					'rules' => ''
				),
		));
		
		parent::__construct();
	}
	
	protected function _before_delete( $data )
	{
		return $data;
	}
	
	
	public function get_option_patient_type ()
	{
		$query = $this->db->get("SIMmJenisKerjasama");
		
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
					->where_in("PoliKlinik", array("UMUM","SPESIALIS", 'UGD'))
					//->where_in("KelompokSection", array("POLI","UGD","LAB & RAD"))
					->order_by("SectionID")
					->get("SIMmSection");
		
		if ( $query->num_rows() > 0 )
		{
			return $query->result();
		}
		
		return false;
	}
	
	public function get_option_time()
	{
		$query = $this->db
					->order_by("Keterangan")	
					->get("SIMmWaktuPraktek");
		
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
}