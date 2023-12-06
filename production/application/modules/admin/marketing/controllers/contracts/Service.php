<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Service extends ADMIN_Controller
{
	protected $nameroutes = 'marketing/contracts/service';
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('admin');
		
		$this->data['nameroutes'] = $this->nameroutes; 
		
		$this->load->language('marketing');		
		$this->load->helper('marketing');
		$this->load->model('contract_service_model');
		$this->load->model('class_model');
		$this->load->model('cooperation_type_model');
	}
	
	/*
		@params
		(Object) $item -> Data Jasa
	*/
	public function index( $id )
	{
		$this->data['collection_service'] = marketing_helper::get_contract_service( $id );
		$this->data['add_contract_service'] = base_url("{$this->nameroutes}/lookup_data/lookup_service");
		$this->data['update_contract_service'] = base_url("{$this->nameroutes}/form");
		$this->data['add_service_component'] = base_url("{$this->nameroutes}/form_component");
		$this->data['update_service_component'] = base_url("{$this->nameroutes}/form_component");
		
		$this->load->view('contract/contracts/service/table', $this->data);
	}
	
	public function form( $row_index = NULL )
	{
		if( $this->input->is_ajax_request() )
		{
			$this->data['item'] = $item = (object) [
				'KelasID' => 'xx',
				'JenisPasienID' => 3,
				'PasienKTP' => 1,
				'KategoriOperasiID' => 1,
				'DokterID' => 'XX',
				'Harga_Lama' => NULL,
				'Harga_Baru' => NULL,
				'HargaHC_Lama' => NULL,
				'HargaHC_Baru' => NULL,
				'TglHargaBaru' => date('Y-m-d'),
				'SpesialisID' => '99',
				//'SubSpesialisID' => NULL,
				'Cyto' => NULL,
				//'HonorDokter' => NULL,
				//'Incentive' => NULL,
				'Lokasi' => 'RJ',
				'DiscHCUmum' => NULL,
				'SubSpesialis' => NULL,
				//'HargaIKS_Lama' => NULL,
				//'HargaIKS_Baru' => NULL,
				//'MulaiHarga_IKS' => NULL,
				//'Import' => NULL,
				'HargaBPJS' => NULL,
				'HargaBPJS_Lama' => NULL,
				'TglHargaBaruBPJS' => NULL,
				'InsentifKomponen' => NULL,
				'InsentifDetail' => NULL,
				//'TglHargaBaruHC' => NULL,
				//'MappingInHealth' => NULL,
			];
			
			$this->data['row_index'] = $row_index;
			$this->data['doctor'] = (object)['Nama_Supplier' => 'None'];
			$this->data['dropdown_class'] = $this->class_model->dropdown_data(['Active' => 1]);
			$this->data['dropdown_patient_type'] = $this->patient_type_model->dropdown_data(['Kerjasama' => 0]);
			$this->data['dropdown_specialist'] = $this->specialist_model->dropdown_data();
			$this->data['dropdown_operation_category'] = $this->operation_service_category_model->dropdown_data();
			$this->data['dropdown_location'] = $this->service_model->dropdown_static('Lokasi');
			
			$this->data['lookup_doctor'] = base_url("{$this->nameroutes}/lookup_data/doctor");
			
			$this->load->view("contracts/service/form", $this->data);
		}
	}
	
	public function form_component( $row_index = NULL )
	{
		if( $this->input->is_ajax_request() )
		{
			$this->data['item'] = $item = (object) [
				'KomponenBiayaID' => NULL,
				'Qty' => 1,
				'HargaLama' => NULL,
				'HargaBaru' => NULL,
				'HargaHCLama' => NULL,
				'HargaHCBaru' => NULL,
				'PersenInsentifHC' => NULL,
				//'HargaIKS_Lama' => NULL,
				//'HargaIKS_Baru' => NULL,
				'HargaBPJS' => NULL,
				'HargaBPJS_Lama' => NULL,
				'AkunNoLawan' => NULL,
				'AkunNo' => NULL,
				'PersenPajakTitipan' => 0,
				'PersenInsentif' => 0,
				'IncludeInsentif' => 0,
				'Prosentase' => 0,
				'NilaiPersen' => 0,
			];
			
			$this->data['row_index'] = $row_index;			
			$this->data['lookup_component'] = base_url("{$this->nameroutes}/lookup_data/component");
			$this->data['lookup_hpp'] = base_url("{$this->nameroutes}/lookup_data/lookup_hpp_account");
			$this->data['lookup_hpp_againts'] = base_url("{$this->nameroutes}/lookup_data/lookup_hpp_againts_account");
			
			$this->load->view("contracts/service/form_component", $this->data);
		}
	}
	
	public function get_service_component($id = NULL)
	{
		$id = $id ? $id : $this->input->get('ListHargaID');
		if($id)
		{
			$collection = marketing_helper::get_service_component($id);
			response_json([
				'collection' => $collection,
				'status' => 'success',
				'message' => 'success',
			]);
		}
	
		response_json([
			'collection' => [],
			'status' => 'success',
			'message' => 'success',
		]);
	}
	
	public function lookup_data( $view, $is_ajax_request=false )
	{	
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view("contract/contracts/service/lookup/{$view}", $this->data);
		}
	}
	
	public function lookup_collection()
	{
		$start = $this->input->get_post('start', true);
        $length = $this->input->get_post('length', true);
        $order = $this->input->get_post('order', true);
        $columns = $this->input->get_post('columns', true);
        $search = $this->input->get_post('search', true);
        $draw = $this->input->get_post('draw', true);
		
		$db_from = "VW_ListHarga a";
		$db_where = array();
		$db_like = array();
		
		//prepare defautl flter
		$db_where['a.JenisPasienID'] = 3;
		$db_where['a.PasienKTP'] = 1;
		$db_where['a.Aktif'] = 1;

		// preparing default
		if( isset($search['value']) && ! empty($search['value']) )
        {
            $keywords = $this->db->escape_str( $search['value'] );
			
			$db_like[ $this->db->escape_str("a.JasaID") ] = $keywords;
			$db_like[ $this->db->escape_str("a.JasaName") ] = $keywords;
			$db_like[ $this->db->escape_str("a.NamaDokter") ] = $keywords;
        }
		
		// get total records
		$this->db->from( $db_from );
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		$records_total = $this->db->count_all_results();
		
		// get total filtered
		$this->db
			->from( $db_from )
			->join("SIMmKategoriOperasi b", "a.KategoriOperasiID=b.KategoriID ", "LEFT OUTER")
			->join("{$this->cooperation_type_model->table} c", "a.JenisPasienID = c.JenisKerjasamaID ", "LEFT OUTER")
			;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }		
		$records_filtered = $this->db->count_all_results();
				
		// get result filtered
		$db_select = <<<EOSQL
			a.ListHargaID,
			a.JasaID,
			a.JasaName,
			a.NamaKelas,
			a.SpesialisName,
			a.SubSpesialisName,
			a.NamaDokter,
			a.ListHargaID,
			a.Cyto,
			a.Lokasi,
			a.PasienKTP,
			a.Harga_Baru,
			a.Harga_Lama,
			a.NamaKelas,
			a.JenisPasienID,
			b.KategoriName,
			c.JenisKerjasama
EOSQL;

		$this->db
			->select( $db_select )
			->from( $db_from )
			->join("SIMmKategoriOperasi b", "a.KategoriOperasiID = b.KategoriID ", "LEFT OUTER")
			->join("{$this->cooperation_type_model->table} c", "a.JenisPasienID = c.JenisKerjasamaID ", "LEFT OUTER")
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
					->order_by( $columns[intval($this->db->escape_str($sort_column))]['data'], $this->db->escape_str($sort_dir) );
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
		
		response_json( $output );
	}
}

