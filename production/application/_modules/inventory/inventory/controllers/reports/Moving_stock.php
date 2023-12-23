<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Moving_stock extends Admin_Controller
{ 
	protected $nameroutes = 'inventory/reports/moving_stock';	
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('inventory');

		$this->load->language('reports');
		$this->load->model('section_model');
		
		$this->page = 'Laporan Slow Moving Stok';
		$this->template->title( $this->page );
	}
	
	public function slow()
	{
		$data = [
			'nameroutes' => $this->nameroutes
		];
		$this->template
			->set( "heading", $this->page )
			->set_breadcrumb( 'Laporan' )
			->set_breadcrumb( 'Laporan Slow Moving Stok', base_url("{$this->nameroutes}/slow") )
			->build('reports/moving_stock/slow', $data);
	}

	public function death()
	{
		$data = [
			'nameroutes' => $this->nameroutes
		];

		$this->template
			->set( "heading", $this->page )
			->set_breadcrumb( 'Laporan' )
			->set_breadcrumb( 'Laporan Death Moving Stok', base_url("{$this->nameroutes}/death") )
			->build('reports/moving_stock/death', $data);
	}

	// moving_type
	// 0 = death stock
	// 1 = slow stock
	public function datatable_collection( $moving_type = 0)	
    {
		$start = $this->input->get_post('start', true);
        $length = $this->input->get_post('length', true);
        $order = $this->input->get_post('order', true);
        $columns = $this->input->get_post('columns', true);
        $search = $this->input->get_post('search', true);
        $draw = $this->input->get_post('draw', true);
		
		$section_pharmacy = $this->session->userdata('pharmacy');
		$section = $this->section_model->get_one($section_pharmacy['section_id']);

		$db_from = "Apotek_SlowMovingDeathStok({$section->Lokasi_ID}, {$moving_type}) a";
		$db_where = array();
		$db_like = array();
		
		//prepare defautl flter							

		// preparing default
		if( isset($search['value']) && ! empty($search['value']) )
        {
            $keywords = $this->db->escape_str( $search['value'] );
			
			$db_like[ $this->db->escape_str("a.Kode_Barang") ] = $keywords;
			$db_like[ $this->db->escape_str("a.Nama_Barang") ] = $keywords;
			$db_like[ $this->db->escape_str("a.Satuan") ] = $keywords;
			$db_like[ $this->db->escape_str("a.Qty") ] = $keywords;
			$db_like[ $this->db->escape_str("a.TglTransaksi_Terakhir") ] = $keywords;
        }
		
		// get total records
		$this->db->from( $db_from )	
			;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		$records_total = $this->db->count_all_results();
		
		// get total filtered
		$this->db
			->from( $db_from )
			;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }		
		$records_filtered = $this->db->count_all_results();
		
		// get result filtered
		$db_select = <<<EOSQL
			a.*
EOSQL;

		$this->db
			->select( $db_select )
			->from( $db_from )
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

