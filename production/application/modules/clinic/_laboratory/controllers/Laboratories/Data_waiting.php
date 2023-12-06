<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Data_waiting extends Admin_Controller
{
	protected $_translation = 'laboratory';	
	protected $_model = 'laboratory_m';
	protected $nameroutes = 'laboratory/laboratories';
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('laboratory');
		
		$this->page = "laboratory";
		$this->template->title( lang("laboratory:page") . ' - ' . $this->config->item('company_name') );

		$this->load->model( "patient_model" );
		$this->load->model( "patient_type_model" );
		$this->load->model( "supplier_model" );
		
		$this->load->helper( "laboratory" );

	}
	
	public function index()
	{
		$data = [
			'option_doctor' => option_doctor(),
			'option_section' => option_section(['TipePelayanan' => 'PENUNJANG']),
			'medics' => $this->session->userdata('laboratory')
		];
		$this->load->view('datatable/waiting', $data);
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
		
		//$db_from = "{$this->laboratory_m->table} a";
		$db_from = "SIMtrDataRegPasien a";
		$this->load->model("registration_model");
		$db_where = array();
		$db_like = array();
		
		$db_where['a.RJ'] = 1;
		$db_where['a.SudahPeriksa'] = 0;
		$db_where['a.Batal'] = 0;
		
		$location = $this->session->userdata('laboratory');
		$db_where['a.SectionID'] = $location['section_id'];
		
		if ($this->input->post("date_from"))
		{
			$db_where['a.Tanggal >='] = $this->input->post("date_from");
		}

		if ($this->input->post("date_till"))
		{
			$db_where['a.Tanggal <='] = $this->input->post("date_till");
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
		
		if( $this->input->post("SectionID") ){
			$db_where['a.SectionID'] = $this->input->post("SectionID");
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
		$this->db->from( $db_from )
		;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		$records_total = $this->db->count_all_results();
		
		// get total filtered
		$this->db
			->from( $db_from )
			->join( "{$this->registration_model->table} b", "a.NoReg = b.NoReg", "LEFT OUTER" )
			->join( "{$this->patient_model->table} c", "b.NRM = c.NRM", "LEFT OUTER" )
			->join( "{$this->patient_type_model->table} d", "b.JenisKerjasamaID = d.JenisKerjasamaID", "LEFT OUTER" )
			->join( "{$this->supplier_model->table} e", "a.DokterID = e.Kode_Supplier", "LEFT OUTER" )
			;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }		
		$records_filtered = $this->db->count_all_results();
		
		// get result filtered
		$db_select = <<<EOSQL
			a.NoAntri,
			a.NoReg,
			a.SectionID,
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
			->join( "{$this->registration_model->table} b", "a.NoReg = b.NoReg", "LEFT OUTER" )
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
				'recordsFiltered' => $records_filtered,
				'data' => array()
			);
        
        foreach($result as $row)
        {			
            $output['data'][] = $row;
        }

		response_json($output);
    }
}



