<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Account extends Admin_Controller
{
	protected $_translation = 'account';	
	protected $_model = 'account_m';
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('cashier');
		
		$this->load->model("account_m");
		
		$this->page = "Rekening";
		$this->template->title( lang("icd:page") . ' - ' . $this->config->item('company_name') );
	}
	
	public function index( $NoBukti = NULL )
	{
		$data = array(
				"form" => TRUE,
				'datatables' => TRUE,
			);

		$this->template
			->breadcum( 'Account', base_url("cashier/account") )
			->view( 'account/datatable', $data );		
	}
	
	public function lookup( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'account/lookup/datatable' );
		}
	}
		
	public function lookup_collection()
	{
		$this->datatable_collection( 1 );
	}
	
	public function datatable_collection( $is_lookup = 0)
    {
        $start = $this->input->get_post('start', true);
        $length = $this->input->get_post('length', true);
        $order = $this->input->get_post('order', true);
        $columns = $this->input->get_post('columns', true);
        $search = $this->input->get_post('search', true);
        $draw = $this->input->get_post('draw', true);
		
		$DBBO = $this->load->database('BO_1', true);
		
		$db_from = "{$DBBO->database}.dbo.{$this->account_m->table} a";
		$db_where = array();
		$db_like = array();

		$db_where['a.Akun_No <>'] = 'XX';
		if ( $is_lookup )
		{
			$db_where['a.Induk'] = 0;
		}
				
		// preparing default
		if( isset($search['value']) && ! empty($search['value']) )
        {
            $keywords = $this->db->escape_str( $search['value'] );
						
			$db_like[ $this->db->escape_str("a.Akun_No") ] = $keywords;
			$db_like[ $this->db->escape_str("a.Akun_Name") ] = $keywords;

        }
		
		//get total records
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
		
		if ( $is_lookup )
		{
			$this->db->order_by("a.Akun_ID");
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
	
	public function lookup_cash_bank( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'account/lookup/datatable_cash_bank' );
		}
	}

	public function lookup_cash_bank_collection()
	{
		$this->datatable_cash_bank_collection( 1 );
	}
	
	public function datatable_cash_bank_collection( $is_lookup = 0)
    {
        $start = $this->input->get_post('start', true);
        $length = $this->input->get_post('length', true);
        $order = $this->input->get_post('order', true);
        $columns = $this->input->get_post('columns', true);
        $search = $this->input->get_post('search', true);
        $draw = $this->input->get_post('draw', true);		

		$DBBO = $this->load->database('BO_1', TRUE);

		$db_from = "{$DBBO->database}.dbo.{$this->account_m->table} a";
		$db_where = array();
		$db_like = array();
		$db_custome_where = NULL;
		
		
		$db_where['a.Akun_No <>'] = 'XX';
		if ( $is_lookup )
		{
			$db_where['a.Induk'] = 0;
		}
		
		$db_custome_where = "(b.Bank = 1 OR b.Cash = 1)";
		$db_custome_where .= " AND a.Akun_ID NOT IN (SELECT Akun_Id  FROM mJenisBayar WHERE IDBayar = 4)";
				
		// preparing default
		if( isset($search['value']) && ! empty($search['value']) )
        {
            $keywords = $this->db->escape_str( $search['value'] );
						
			$db_like[ $this->db->escape_str("a.Akun_No") ] = $keywords;
			$db_like[ $this->db->escape_str("a.Akun_Name") ] = $keywords;

        }
		
		//get total records
		$this->db->from( $db_from )
				->join("{$DBBO->database}.dbo.Mst_GroupAkunDetail b", "a.GroupAkunDetailId = b.GroupAkunDetailId", "LEFT OUTER")
			;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_custome_where) ){ $this->db->where( $db_custome_where ); }
		$records_total = $this->db->count_all_results();
		
		// get total filtered
		$this->db
			->from( $db_from )
			->join("{$DBBO->database}.dbo.Mst_GroupAkunDetail b", "a.GroupAkunDetailId = b.GroupAkunDetailId", "LEFT OUTER")
			;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }		
		if( !empty($db_custome_where) ){ $this->db->where( $db_custome_where ); }
		$records_filtered = $this->db->count_all_results();
		
		// get result filtered
		$db_select = <<<EOSQL
			a.*	
			
EOSQL;

		$this->db
			->select( $db_select )
			->from( $db_from )
			->join("{$DBBO->database}.dbo.Mst_GroupAkunDetail b", "a.GroupAkunDetailId = b.GroupAkunDetailId", "LEFT OUTER")
			;
			
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }		
		if( !empty($db_custome_where) ){ $this->db->where( $db_custome_where ); }
		
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
		
		$this->db->order_by("a.Akun_ID");
		
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