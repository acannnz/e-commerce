<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Data_close_bhp extends Admin_Controller
{
	protected $_translation = 'pharmacy';	
	protected $_model = 'pharmacy_m';
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('pharmacy');

		$this->load->model( "pharmacy_m" );
		$this->load->model( "common/patient_m" );
		$this->load->model( "common/patient_type_m" );
		$this->load->model( "common/supplier_m" );
		$this->load->model( "common/section_m" );
	}
	
	public function index()
	{
		$data = [
			'option_doctor' => $this->pharmacy_m->get_option_supplier(),
			'option_section' => $this->section_model->to_list_data(['TipePelayanan' => 'FARMASI', 'StatusAktif' => 1])
		];
		$this->load->view('pharmacies/datatable/close_bhp', (isset($data) ? $data : NULL));
	}

	public function lookup_collection()
	{
		$this->datatable_collection();
	}

	public function datatable_collection()
    {
        $start = $this->input->get_post('start', true);
        $length = $this->input->get_post('length', true);
        $order = $this->input->get_post('order', true);
        $columns = $this->input->get_post('columns', true);
        $search = $this->input->get_post('search', true);
        $draw = $this->input->get_post('draw', true);
		
		$db_from = "SIMtrPOP a";
		$this->load->model("registration_m");
		$db_where = array();
		$db_or_where = array();
		$db_like = array();
		
		//$db_where['a.Retur'] = 0;
		$location = $this->session->userdata('pharmacy');
		$db_where['a.SectionID'] = "SEC008";
		$db_where['a.Realisasi_Farmasi'] = 1;
        // print_r($location);exit;
		if ($this->input->post("date_from"))
		{
			$db_where['a.Jam >='] = DateTime::createFromFormat('Y-m-d', $this->input->post("date_from"))->setTime(0,0)->modify('+8 hour')->format('Y-m-d H:i:s');
		}

		if ($this->input->post("date_till"))
		{
			$db_where['a.Jam <='] = DateTime::createFromFormat('Y-m-d', $this->input->post("date_till"))->setTime(0,0)->modify('+1 day')->modify('+8 hour')->format('Y-m-d H:i:s');
		}

		if( $this->input->post("NRM") && strpos($this->input->post("NRM"), "_") === FALSE ){
			$db_like['b.NRM'] = $this->input->post("NRM");
		}

		if( $this->input->post("Nama") ){
			$db_like['b.NamaPasien_Reg'] = $this->input->post("Nama");
		}

		if( $this->input->post("DokterID") ){
			$db_where['a.DokterID'] = $this->input->post("DokterID");
		}		

		// preparing default
		if( isset($search['value']) && ! empty($search['value']) )
        {
            $keywords = $this->db->escape_str( $search['value'] );
						
			$db_like[ $this->db->escape_str("a.NoBuktiPOP") ] = $keywords;
			$db_like[ $this->db->escape_str("a.Tanggal") ] = $keywords;
			$db_like[ $this->db->escape_str("a.Jam") ] = $keywords;
			$db_like[ $this->db->escape_str("a.DokterID") ] = $keywords;
			$db_like[ $this->db->escape_str("c.NamaPasien") ] = $keywords;
			$db_like[ $this->db->escape_str("e.Nama_Supplier") ] = $keywords;

        }
		
		// get total records
		$this->db->from( $db_from )
			->join( "{$this->registration_m->table} b", "a.NoReg = b.NoReg", "LEFT OUTER" )
			->join( "{$this->patient_m->table} c", "b.NRM = c.NRM", "LEFT OUTER" )
			->join( "{$this->patient_type_m->table} d", "b.JenisKerjasamaID = d.JenisKerjasamaID", "LEFT OUTER" )
			->join( "{$this->supplier_m->table} e", "a.DokterID = e.Kode_Supplier", "LEFT OUTER" )
			->join( "{$this->section_m->table} f", "a.SectionID = f.SectionID", "LEFT OUTER" )
		;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		$records_total = $this->db->count_all_results();
		
		// get total filtered
		$this->db
			->from( $db_from )
			->join( "{$this->registration_m->table} b", "a.NoReg = b.NoReg", "LEFT OUTER" )
			->join( "{$this->patient_m->table} c", "b.NRM = c.NRM", "LEFT OUTER" )
			->join( "{$this->patient_type_m->table} d", "b.JenisKerjasamaID = d.JenisKerjasamaID", "LEFT OUTER" )
			->join( "{$this->supplier_m->table} e", "a.DokterID = e.Kode_Supplier", "LEFT OUTER" )
			->join( "{$this->section_m->table} f", "a.SectionID = f.SectionID", "LEFT OUTER" )
			;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_or_where) ){ $this->db->group_start()->or_where( $db_or_where )->group_end(); }		
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }		
		$records_filtered = $this->db->count_all_results();
		
		// get result filtered
		$db_select = <<<EOSQL
			a.NoBuktiPOP AS NoBukti,
			a.NoReg,
			c.NRM,
			a.Jam,
			a.Tanggal,
			c.NamaPasien,
			d.JenisKerjasama,
			e.Nama_Supplier,
			f.SectionName,
			a.Realisasi_Farmasi,
            a.Ditagihkan,
			
EOSQL;

		$this->db
			->select( $db_select )
			->from( $db_from )
			->join( "{$this->registration_m->table} b", "a.NoReg = b.NoReg", "LEFT OUTER" )
			->join( "{$this->patient_m->table} c", "b.NRM = c.NRM", "LEFT OUTER" )
			->join( "{$this->patient_type_m->table} d", "b.JenisKerjasamaID = d.JenisKerjasamaID", "LEFT OUTER" )
			->join( "{$this->supplier_m->table} e", "a.DokterID = e.Kode_Supplier", "LEFT OUTER" )
			->join( "{$this->section_m->table} f", "a.SectionID = f.SectionID", "LEFT OUTER" )
			;

		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_or_where) ){ $this->db->group_start()->or_where( $db_or_where )->group_end(); }		
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
		
	public function autocomplete()
	{
		$words = $this->input->get_post('query');
		
		$this->db
			->select( array("id", "code", "pharmacy_title") )
			;
			
		$this->db
			->from( "common_pharmacys" )
			;
		
		$this->db
			->group_start()
				->where(array(
						'deleted_at' => NULL,
						'state' => 1
					))
			->group_end()
			;
		
		$this->db
			->group_start()
			->or_like(array(
					"code" => $words,
					"pharmacy_title" => $words,
					"pharmacy_description" => $words,
				))
			->group_end();
			
		$result = $this->db
			->get()
			->result()
			;
		
		if( $result )
		{
			$collection = array();
			foreach( $result as $item )
			{
				array_push($collection, array(
						"name" => $item->pharmacy_title,
						"id" => $item->id,
					));
			}
		} else
		{
			$collection = array(array(
					"value" => 0,
					"label" => lang( "global:no_match" ),
					"id" => 0,
				));
		}
		
		header( 'Content-Type: application/json; charset=utf-8' );
		echo json_encode($collection);
		exit(0);
	}

}



