<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Data_checkup extends Admin_Controller
{
	protected $_translation = 'poly';	
	protected $_model = 'poly_m';
	protected $nameroutes = 'poly/inpatients/data_checkup';
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('inpatient');
		
		$this->page = "poly";
		$this->template->title( lang("poly:page") . ' - ' . $this->config->item('company_name') );

		$this->load->model( "patient_model" );
		$this->load->model( "patient_type_model" );
		$this->load->model( "supplier_model" );
		
		$this->load->helper( "poly" );
		
	}
	
	public function index()
	{
		$data = [
			'option_doctor' => poly_helper::option_doctor(),
			'medics' => $this->session->userdata('inpatient')
		];
		
		$this->load->view('inpatient/datatable/checkup', $data);
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
		
		$db_from = "{$this->poly_m->table} a";
		$this->load->model("registration_model");
		$db_where = array();
		$db_like = array();
		
		$db_where['a.RawatInap'] = 0;
		$db_where['a.Batal'] = 0;
		$db_where['a.SectionID'] = $this->section->SectionID;
		
		if ($this->input->post("date_from"))
		{
			$db_where['a.tanggal >='] = $this->input->post("date_from");
		}

		if ($this->input->post("date_till"))
		{
			$db_where['a.tanggal <='] = $this->input->post("date_till");
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
						
			$db_like[ $this->db->escape_str("a.NoReg") ] = $keywords;
			$db_like[ $this->db->escape_str("b.NRM") ] = $keywords;
			$db_like[ $this->db->escape_str("a.Jam") ] = $keywords;
			$db_like[ $this->db->escape_str("c.NamaPasien") ] = $keywords;
			$db_like[ $this->db->escape_str("d.JenisKerjasama") ] = $keywords;

        }
		
		
		// get total records
		$this->db->from( $db_from );
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		$records_total = $this->db->count_all_results();
		
		// get total filtered
		/*$this->db
			->from( $db_from )
			->join( "{$this->registration_model->table} b", "a.RegNo = b.NoReg", "LEFT OUTER" )
			->join( "{$this->patient_model->table} c", "b.NRM = c.NRM", "LEFT OUTER" )
			->join( "{$this->patient_type_model->table} d", "b.JenisKerjasamaID = d.JenisKerjasamaID", "LEFT OUTER" )
			->join( "{$this->supplier_model->table} e", "a.DokterID = e.Kode_Supplier", "LEFT OUTER" )
			;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }		
		$records_filtered = $this->db->count_all_results();*/
		
		// get result filtered
		$db_select = <<<EOSQL
			a.RegNo,
			a.NoBukti,
			b.NRM,
			a.Jam,
			c.NamaPasien,
			c.JenisKelamin,
			d.JenisKerjasama,
			e.Nama_Supplier
			
			
EOSQL;

		$this->db
			->select( $db_select )
			->from( $db_from )
			->join( "{$this->registration_model->table} b", "a.RegNo = b.NoReg", "LEFT OUTER" )
			->join( "{$this->patient_model->table} c", "b.NRM = c.NRM", "LEFT OUTER" )
			->join( "{$this->patient_type_model->table} d", "b.JenisKerjasamaID = d.JenisKerjasamaID", "LEFT OUTER" )
			->join( "{$this->supplier_model->table} e", "a.DokterID = e.Kode_Supplier", "LEFT OUTER" )
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
				'recordsFiltered' => count($result), //,$records_filtered,
				'data' => array()
			);
        
        foreach($result as $row)
        {			
            $output['data'][] = $row;
        }
		
		print_r( json_encode( @$output, JSON_NUMERIC_CHECK ) );
		exit(0);
		
    }

}



