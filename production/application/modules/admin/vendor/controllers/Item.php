<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Item extends ADMIN_Controller
{
	protected $nameroutes = 'vendor/item';
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('admin');
		
		$this->data['nameroutes'] = $this->nameroutes; 
		
		$this->load->language('vendor');		
		$this->load->helper('vendor');
		$this->load->model('item_model');
		$this->load->model('item_category_model');
		$this->load->model('item_unit_model');
	}
	
	public function index()
	{
		show_404();
	}
		
	public function lookup_collection ()
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
		
		$db_from = "{$this->item_model->table} a";
		$db_where = $this->input->get_post('_where');
		$db_expression = (array) $this->input->get_post('_expression');
		$db_like = array();
		
		//prepare defautl flter
		$db_where["a.Kelompok"] = "OBAT";
		foreach($db_expression as $key => $val)
		{
			if( @$db_where[ $key ] )
			{
				$db_where["{$key} {$val}"] = $db_where[ $key ];
				unset($db_where[ $key ]);
			}
		}

		// preparing default
		if( isset($search['value']) && ! empty($search['value']) )
        {
            $keywords = $this->db->escape_str( $search['value'] );
			
			$db_like[ $this->db->escape_str("a.Kode_Barang") ] = $keywords;
			$db_like[ $this->db->escape_str("a.Nama_Barang") ] = $keywords;
			$db_like[ $this->db->escape_str("b.Nama_Kategori") ] = $keywords;
			$db_like[ $this->db->escape_str("c.Nama_Satuan") ] = $keywords;
        }
		
		// get total records
		$this->db->from( $db_from );
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		$records_total = $this->db->count_all_results();
		
		// get total filtered
		$this->db
			->from( $db_from )
			->join("{$this->item_category_model->table} b", "a.Kategori_Id = b.Kategori_ID", "INNER")
			->join("{$this->item_unit_model->table} c", "a.Stok_Satuan_ID = c.Satuan_ID", "INNER")
			;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }		
		$records_filtered = $this->db->count_all_results();
		
		// get result filtered
		$db_select = <<<EOSQL
			a.*,
			b.Nama_Kategori,
			c.Nama_Satuan
EOSQL;

		$this->db
			->select( $db_select )
			->from( $db_from )
			->join("{$this->item_category_model->table} b", "a.Kategori_Id = b.Kategori_ID", "INNER")
			->join("{$this->item_unit_model->table} c", "a.Stok_Satuan_ID = c.Satuan_ID", "INNER")
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

