<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Patient_m extends Public_Model
{
	public $table = 'mPasien';
	public $primary_key = 'NRM';
	
	public function __construct()
	{
		$this->rules = array(
				'insert' => array(
						array(
								'field' => 'NRM',
								'label' => lang('patients:mr_number_label'),
								//'rules' => 'trim|required|exact_length[8]|callback_valid_mr_number|is_unique[mPasien.NRM]'
								'rules' => 'required|exact_length[8]|is_unique[mPasien.NRM]'
							),
						array(
								'field' => 'JenisPasien',
								'label' => lang('patients:type_label'),
								'rules' => 'integer|required'
							),
						array(
								'field' => 'NamaPasien',
								'label' => lang('patients:name_label'),
								'rules' => 'trim|required'
							),
						array(
								'field' => 'JenisKelamin',
								'label' => lang('patients:gender_label'),
								'rules' => 'trim'
							),
						array(
								'field' => 'TglLahir',
								'label' => lang('patients:birth_date_label'),
								'rules' => 'trim'
							),
						array(
								'field' => 'Alamat',
								'label' => lang('patients:address_label'),
								'rules' => 'trim|required'
							),
					),
				'modify' => array(
						array(
								'field' => 'JenisPasien',
								'label' => lang('patients:type_label'),
								'rules' => 'required'
							),
						array(
								'field' => 'NamaPasien',
								'label' => lang('patients:name_label'),
								'rules' => 'trim|required'
							),
						array(
								'field' => 'JenisKelamin',
								'label' => lang('patients:gender_label'),
								'rules' => 'trim'
							),
						array(
								'field' => 'TglLahir',
								'label' => lang('patients:birth_date_label'),
								'rules' => 'trim'
							),
						array(
								'field' => 'Alamat',
								'label' => lang('patients:address_label'),
								'rules' => 'trim|required'
							),
					)
			);
		
		parent::__construct();
	}
	
	public function valid_mr_number( $str )
	{
		if( preg_match("/(\d{2})\.(\d{2})\.(\d{2})$/", $str, $matches) )
		{
			return TRUE;
		}
		
		$this->form_validation->set_message('valid_mr_number', lang('patients:invalid_mr_number'));
		return FALSE;
	}
	
	public function exist_mr_number( $str )
	{
		if( patient_helper::find_patient($str) === FALSE )
		{
			return TRUE;
		}
		
		$this->form_validation->set_message('exist_mr_number', lang('patients:exist_mr_number'));
		return FALSE;
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
	
	public function get_row( $NRM )
	{
		return $this->db->where( $this->primary_key, $NRM)->get( $this->table)->row();		
	}

	public function create( $data )
	{
		$this->db->insert( $this->table, $data);
		
		return $this->db->affected_rows();
	}

	public function update( $data, $NRM )
	{
		$this->db->update( $this->table, $data, array( $this->primary_key => $NRM));
		
		return $this->db->affected_rows();
	}

}


