<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Patients extends Admin_Controller
{
	protected $_translation = 'registrations';	
	protected $_model = 'patient_model';
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('registration');
		
		$this->load->model( "section_group_model" );
		$this->load->model( "patient_model" );
		$this->load->model( "patient_nrm_model" );
		$this->load->helper( "registration" );
		
		$this->page = "common_patients";
		$this->template->title( lang( "patients:page" ) . ' - ' . $this->config->item('company_name') );
	}
	
	public function migrate_patient_nrm()
	{
		response_json( registration_helper::migrate_patient_nrm() );
	}
	
	public function lookup_collection( $state=false )
	{
		$this->datatable_collection( $state );
	}
	
	public function datatable_collection( $state=false )
    {
        $start = $this->input->get_post('start', true);
        $length = $this->input->get_post('length', true);
        $order = $this->input->get_post('order', true);
        $columns = $this->input->get_post('columns', true);
        $search = $this->input->get_post('search', true);
        $draw = $this->input->get_post('draw', true);
		
		$db_from = "{$this->patient_model->table} a";
		$db_where = array();
		$db_like = array();
				
		// preparing default
		if( isset($search['value']) && ! empty($search['value']) )
        {
            $keywords = $this->db->escape_str( $search['value'] );
			
			$db_like[ $this->db->escape_str("a.NRM") ] = $keywords;
			$db_like[ $this->db->escape_str("a.NRMLama") ] = $keywords;
			$db_like[ $this->db->escape_str("a.NamaPasien") ] = $keywords;
			$db_like[ $this->db->escape_str("a.JenisPasien") ] = $keywords;
			$db_like[ $this->db->escape_str("a.Phone") ] = $keywords;
			$db_like[ $this->db->escape_str("a.Alamat") ] = $keywords;
			$db_like[ $this->db->escape_str("a.Pekerjaan") ] = $keywords;
			$db_like[ $this->db->escape_str("a.NoIdentitas") ] = $keywords;
        }
		
		// get total records
		$this->db->from( $db_from );
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		$records_total = $this->db->count_all_results();
		
		// get total filtered
		$this->db
			->from( $db_from )
			->join( "SIMdCustomerKerjasama b", "a.CustomerKerjasamaID = b.CustomerKerjasamaID", "LEFT OUTER" )
			->join( "mCustomer c", "b.CustomerID = c.Customer_ID", "LEFT OUTER" )
			;
			
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }		
		$records_filtered = $this->db->count_all_results();

		
		// get result filtered
		$db_select = <<<EOSQL
			a.NRM
			,a.NRMLama
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
			,a.RiwayatAlergi
			
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
			,a.KodeRegional
			,a.TempatLahir
			
			,c.Nama_Customer
			,c.Kode_Customer AS CompanyID
			
			
EOSQL;

		$this->db
			->select( $db_select )
			->from( $db_from )
			->join( "SIMdCustomerKerjasama b", "a.CustomerKerjasamaID = b.CustomerKerjasamaID", "LEFT OUTER" )
			->join( "mCustomer c", "b.CustomerID = c.Customer_ID", "LEFT OUTER" )
			;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }		
		
		// ordering
        if( isset($order) )
        {
            $sort_column = $order[0]['column'];
			$sort_dir = $order[0]['dir'];
			
			if( $columns[$sort_column]['orderable'] == 'true' )
			{
				$this->db
					->order_by( $columns[intval($this->db->escape_str($sort_column))]['name'], $this->db->escape_str($sort_dir) );
			}
        }
		
		// paging
		if( isset($start) && $length != '-1')
        {
            $this->db
				->limit( $length, $start );
        }
		
		// get
		$result = $this->db
					->get()
					->result()
					;
		
        // Output
        $output = array(
				'draw' => intval($draw),
				'recordsTotal' => $records_total,
				'recordsFiltered' => $records_filtered,
				'data' => array()
			);
        
        foreach($result as $row)
        {			
            $output['data'][] = $row;
        }
		
		$this->template
			->build_json( $output );
    }
}


