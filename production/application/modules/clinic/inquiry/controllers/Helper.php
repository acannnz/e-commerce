<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include_once("Inquiry.php")	;

class Helper extends Inquiry
{
	protected $_translation = 'inquiry';	
	protected $_model = 'inquiry_m';
	
	public function __construct()
	{
		parent::__construct();
				
	}
	
	public function request( )
	{
			
		$this->template
			->set( "heading", "Amprah Barang Laboratorium" )
			->set_breadcrumb( "Amprahan", base_url("inquiry/helper/request") )
			->set_breadcrumb( "Amprah Barang Laboratorium" )
			;

		$this->request_( "SEC005" );
	}

	public function request_list( )
	{

		$section = $this->inquiry_m->get_row_data("SIMmSection", array("SectionID" => "SEC005" ));

		$data = array(
				'page' => $this->page,
				'section' => $section,
				"form" => TRUE,
				'datatables' => TRUE,
				'create_link' => base_url("inquiry/helper/request"),
			);
		
		$this->template
			->set( "heading", "List Amprahan Laboratorium" )
			->set_breadcrumb( lang("inquiry:breadcrumb") )
			->set_breadcrumb( "Amprahan Laboratorium", current_url() )
			->build('pharmacies/datatable', (isset($data) ? $data : NULL));
	}
		
	public function stock_opname( )
	{
		$this->template
			->set( "heading", "Stok Opname Laboratorium" )
			->set_breadcrumb( "Stok Opname", current_url() )
			->set_breadcrumb( "Stok Opname Laboratorium" )
			;
			
		$this->stock_opname_( "SEC005" );
	}

	public function stock_opname_view( $No_Bukti )
	{
			$this->template
				->set( "heading", "Stok Opname Laboratorium" )
				->set_breadcrumb( "Stok Opname", current_url() )
				->set_breadcrumb( "Stok Opname Laboratorium" )
				;
				
		$this->stock_opname_view_( "SEC005", $No_Bukti );
	}

	public function stock_opname_list()
	{
		
		$section = $this->inquiry_m->get_row_data("SIMmSection", array("SectionID" => "SEC005" ));
		$option_section_opname = $this->inquiry_m->get_option_section_opname();
		$option_kelompok_jenis = $this->inquiry_m->get_options("SIMmKelompokJenisObat", array("Kelompok" => "OBAT"));

		$data = array(
				'page' => $this->page,
				'section' => $section,
				'option_section_opname' => $option_section_opname,
				'option_kelompok_jenis' => $option_kelompok_jenis,
				"form" => TRUE,
				'datatables' => TRUE,
				"create_link" => base_url("inquiry/helper/stock-opname")
			);
		
		$this->template
			->set( "heading", "List Stok Opname Laboratorium" )
			->set_breadcrumb( "Stok Opname" )
			->set_breadcrumb( "Stok Opname Laboratorium", current_url() )
			->build('pharmacies/datatable_opname', (isset($data) ? $data : NULL));
	}
	
	public function lookup( )
	{
		$this->_lookup( "SEC005" );
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
		
		//$db_from = "{$this->inquiry_m->table} a";
		$db_from = "{$this->inquiry_m->table} a";
		$db_where = array();
		$db_like = array();
		
		$db_where['a.SectionAsal'] = $this->input->post("SectionID");
		
		if ($this->input->post("date_from"))
		{
			$db_where['a.Tanggal >='] = $this->input->post("date_from");
		}

		if ($this->input->post("date_till"))
		{
			$db_where['a.Tanggal <='] = $this->input->post("date_till");
		}
				
		// preparing default
		if( isset($search['value']) && ! empty($search['value']) )
        {
            $keywords = $this->db->escape_str( $search['value'] );
						
			$db_like[ $this->db->escape_str("a.NoBukti") ] = $keywords;

        }
		
		// get total records
		$this->db->from( $db_from )
			->join( "{$this->section_m->table} b", "a.SectionAsal = b.SectionID", "LEFT OUTER" )
			->join( "{$this->section_m->table} c", "a.SectionTujuan = c.SectionID", "LEFT OUTER" )
			->join( "mUser d", "a.UserID = d.User_ID", "LEFT OUTER" )
		;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		$records_total = $this->db->count_all_results();
		
		// get total filtered
		$this->db
			->from( $db_from )
			->join( "{$this->section_m->table} b", "a.SectionAsal = b.SectionID", "LEFT OUTER" )
			->join( "{$this->section_m->table} c", "a.SectionTujuan = c.SectionID", "LEFT OUTER" )
			->join( "mUser d", "a.UserID = d.User_ID", "LEFT OUTER" )
			;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }		
		$records_filtered = $this->db->count_all_results();
		
		// get result filtered
		$db_select = <<<EOSQL
			a.NoBukti,
			a.Tanggal,
			b.SectionName AS SectionNameAsal,
			c.SectionName AS SectionNameTujuan,
			a.Disetujui,
			a.Keterangan,
			d.Nama_Singkat
			
			
EOSQL;

		$this->db
			->select( $db_select )
			->from( $db_from )
			->join( "{$this->section_m->table} b", "a.SectionAsal = b.SectionID", "LEFT OUTER" )
			->join( "{$this->section_m->table} c", "a.SectionTujuan = c.SectionID", "LEFT OUTER" )
			->join( "mUser d", "a.UserID = d.User_ID", "LEFT OUTER" )
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
		
		//print_r($output);exit;
		
		$this->template
			->build_json( $output );
    }
}