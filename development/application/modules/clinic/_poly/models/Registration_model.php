<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Registration_model extends Public_Model
{
	public $table = 'SimtrRegistrasi';
	public $index_key = 'NoReg';
	public $rules;
	
	public function __construct()
	{
		$this->rules = [];
		
		parent::__construct();
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
	
	public function to_list_html($first_label = '')
	{
		$option_html = "<option value=\"0\">{$first_label}</option>";		
		if ($items = $this->get_all())
		{
			foreach($items as $item)
			{
				$option_html .= "<option value=\"{$item->Supplier_ID}\">{$item->Kode_Supplier} - {$item->Nama_Supplier}</option>";
			}
		}
		
		return $option_html;
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
					->join( "{$this->supplier_model->table} b", "a.DokterID = b.Kode_Supplier", "LEFT OUTER" )
					->join( "{$this->supplier_specialist_m->table} e", "b.SpesialisID = e.SpesialisID", "LEFT OUTER" )
					->join( "{$this->section_model->table} c", "a.SectionID = c.SectionID", "LEFT OUTER" )
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
			a.NoUrut,
			b.Nama_Supplier,
			e.SpesialisName,
			c.SectionName,
			d.Keterangan
			
EOSQL;

		$query = $this->db
					->select( $db_select )
					->from("SimtrReservasi a")
					->join( "{$this->supplier_model->table} b", "a.UntukDokterID = b.Kode_Supplier", "LEFT OUTER" )
					->join( "{$this->supplier_specialist_m->table} e", "b.SpesialisID = e.SpesialisID", "LEFT OUTER" )
					->join( "{$this->section_model->table} c", "a.UntukSectionID = c.SectionID", "LEFT OUTER" )
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
				
				//$queue = registration_helper::get_queue( $params );
				//$row->NoUrut = $queue > $this->config->item("start_queue") ? $queue : $this->config->item("start_queue");

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