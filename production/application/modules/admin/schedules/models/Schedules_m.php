<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Schedules_m extends Public_Model
{
	public $table = 'SIMtrDokterJaga';
	public $primary_key = 'DokerID';
	
	public function __construct()
	{
		$this->rules = array('insert' => array(
			array(
					'field' => 'DokterID',
					'label' => lang('registrations:registration_number_label'),
					'rules' => 'trim|required'
				),
			array(
					'field' => 'SectionID',
					'label' => lang('registrations:date_label'),
					'rules' => 'trim|required|exact_length[10]'
				),
			array(
					'field' => 'Tanggal',
					'label' => lang('registrations:time_label'),
					'rules' => 'trim|min_length[5]|max_length[8]'
				),
			array(
					'field' => 'WaktuID',
					'label' => lang('registrations:mr_number_label'),
					'rules' => ''
				),
			array(
					'field' => 'Hari',
					'label' => lang('registrations:type_label'),
					'rules' => 'integer'
				),
			array(
					'field' => 'FromJam',
					'label' => lang('registrations:name_label'),
					'rules' => 'trim|required'
				),
			array(
					'field' => 'ToJam',
					'label' => lang('registrations:gender_label'),
					'rules' => 'trim'
				),
			array(
					'field' => 'JmlAntrian',
					'label' => lang('registrations:birth_date_label'),
					'rules' => 'trim'
				),
			array(
					'field' => 'Realisasi',
					'label' => lang('registrations:address_label'),
					'rules' => 'trim|required'
				),
			array(
					'field' => 'Cancel',
					'label' => lang('registrations:country_label'),
					'rules' => 'integer'
				),
			array(
					'field' => 'DokterPenggantiID',
					'label' => lang('registrations:province_label'),
					'rules' => 'integer'
				),
			array(
					'field' => 'NoAntrianTerakhir',
					'label' => lang('registrations:county_label'),
					'rules' => 'integer'
				),
			array(
					'field' => 'NoRuang',
					'label' => lang('registrations:district_label'),
					'rules' => 'integer'
				),
//			array(
//					'field' => 'area_id',
//					'label' => lang('registrations:area_label'),
//					'rules' => 'integer'
//				),
//			array(
//					'field' => 'phone_number',
//					'label' => lang('registrations:phone_label'),
//					'rules' => 'trim'
//				),
//			array(
//					'field' => 'mobile_number',
//					'label' => lang('registrations:mobile_label'),
//					'rules' => 'trim'
//				),
//			array(
//					'field' => 'email_address',
//					'label' => lang('registrations:email_label'),
//					'rules' => 'trim|valid_email'
//				),
//			array(
//					'field' => 'schedule_date',
//					'label' => lang('registrations:for_date_label'),
//					'rules' => 'trim'
//				),
//			array(
//					'field' => 'schedule_time',
//					'label' => lang('registrations:for_time_label'),
//					'rules' => 'trim'
//				),
//			array(
//					'field' => 'schedule_queue',
//					'label' => lang('registrations:queue_label'),
//					'rules' => 'integer'
//				),
//			array(
//					'field' => 'state',
//					'label' => lang('registrations:status_label'),
//					'rules' => 'integer'
//				),
		));
		
		parent::__construct();
	}
	

	public function get_schedule( $DokterID, $SectionID  )
	{

		$query = $this->db
					->select("a.*, b.Nama_Supplier, c.SpesialisName, d.SectionName")
					->from( $this->table ." a")
					->join( "mSupplier b", "a.DokterID = b.Kode_Supplier", "LEFT OUTER" )
					->join( "SIMmSpesialisasi c", "b.SpesialisID = c.SpesialisID", "LEFT OUTER" )
					->join( "SIMmSection d", "a.SectionID = d.SectionID", "LEFT OUTER" )
					->where(array( "a.DokterID" => $DokterID, "a.SectionID" => $SectionID ))
					->get()
					;
		
		if ( $query->num_rows() > 0 )
		{
			return $query->row();
		}
		
		return false;
	}

	public function get_schedule_detail( $DokterID, $SectionID )
	{

		$query = $this->db
					->select("a.*, b.Keterangan, c.Nama_Supplier, 1 as Ruangan ")
					->from( "SIMtrDokterJagaDetail a")
					->join( "SIMmWaktuPraktek b", "a.WaktuID = b.WaktuID", "LEFT OUTER" )
					->join( "mSupplier c", "a.DokterPenggantiID = c.Kode_Supplier", "LEFT OUTER" )
					->where(array( "a.DokterID" => $DokterID, "a.SectionID" => $SectionID ))
					->get()
					;
		
		if ( $query->num_rows() > 0 )
		{
			return $query->result();
		}
		
		return false;
	}
	
	public function get_options( $table, $where = NULL )
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
	
	public function get_option_times( $where = NULL )
	{

		if( !empty( $where ) && is_array( $where ))
		{
			$this->db->where( $where );
		}

		$query = $this->db
					->order_by("Keterangan", "ASC")
					->get( "SIMmWaktuPraktek" );
		
		if ( $query->num_rows() > 0 )
		{
			return $query->result();
		}
		
		return false;
	}

	public function get_practice_schedule()
	{

		$query = $this->db
					->order_by("Keterangan", "ASC")
					->get( "SIMmWaktuPraktek" );
		
		if ( $query->num_rows() > 0 )
		{
			$result = array();
			foreach ( $query->result() as $row )
			{
				$result[ $row->WaktuID ] = $row;
			}
			
			return $result;
		}
		
		return false;
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
}