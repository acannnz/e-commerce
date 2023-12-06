<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Aging extends Admin_Controller
{
	protected $_translation = 'receivable';	
	protected $_model = '';
	protected $nameroutes = "receivable/aging";
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('receivable');
				
		$this->load->helper("receivable");
		$this->load->model("type_m");
		$this->load->model("currency_m");
				
		$this->page = "receivable_aging";
		$this->template->title( lang("aging:page") . ' - ' . $this->config->item('company_name') );
	}
	
	public function index()
	{
		$data = array(
				'page' => $this->page,
				"beginning_balance_date" => receivable_helper::get_beginning_balance_date(),
				"lookup_customers" => base_url("{$this->nameroutes}/lookup_customers"),
				"option_type" => $this->type_m->get_option_type(),
				"option_currency" => $this->currency_m->to_list_data(),
				"export_url" => base_url("{$this->nameroutes}/export"),
				"form" => TRUE,
				'datatables' => TRUE,
				"navigation_minimized" => TRUE
			);
		
		$this->template
			->set( "heading", lang("aging:page") )
			->set_breadcrumb( lang("aging:page"), base_url("receivable/aging") )
			->build('aging/datatable', (isset($data) ? $data : NULL));
	}
	
	public function export()
	{
		if( $this->input->post() )
		{
			$data['post_data'] = json_decode( $this->input->post('post_data') );					
			$data["file_name"] = sprintf('%s %s', lang("aging:list_heading"), $data['post_data']->date_start );
			
			$html_header = "";
			$html_footer = "";
			$html_content = $this->load->view( "aging/export/pdf", $data, TRUE );
			
			//print $html_content;exit(0);
			set_time_limit(120);
			$this->load->helper( "export" );
			export_helper::print_pdf( $html_content, $data['file_name'] );
			
			exit(0);
		}
	}
		
	public function datatable_collection( $posting_number = false )
    {
        $start = $this->input->get_post('start', true);
        $length = $this->input->get_post('length', true);
        $order = $this->input->get_post('order', true);
        $columns = $this->input->get_post('columns', true);
        $search = $this->input->get_post('search', true);
        $draw = $this->input->get_post('draw', true);
		
		$this->load->model("customer_m");
		
		$db_where = [];
		if ( $this->input->post("date_start") )
		{
			$date = DateTime::createFromFormat('Y-m-d', $this->input->post("date_start") );
			$date_start = $date->format('Y-m-d'); 
			$date_end = $date->format('Y-m-t'); 
		}
				
		if ( $this->input->post("customer_id") )
		{
			$db_where['b.Customer_ID'] = $this->input->post("customer_id");
		}
		
		$CurrencyRate = receivable_helper::get_rate_currency_by_id( $this->input->post("currency_id"), $date_end );
		
		/*
			select  
			from (
					Select * from 
					dbo.AR_UmurPiutang_AllCustomer_New('2018-01-04',1,'2018-01-31','Customer','')
					dbo.AR_UmurPiutang_AllCustomer_New_PerTipe('2018-01-04',9560,'2018-01-31','Customer','',2)
				) a
				left outer join mCustomer b on a.Kode_Customer = b.Kode_Customer 
			ORDER BY a.Nama_Customer asc

		*/
		
		if( $type_id = $this->input->post("type_id") )
		{
			$db_from = "
				AR_UmurPiutang_AllCustomer_New_PerTipe('{$date_start}',{$CurrencyRate},'{$date_end}','Customer','', {$type_id})
			";		
		} else {
			$db_from = "
				AR_UmurPiutang_AllCustomer_New('{$date_start}',{$CurrencyRate},'{$date_end}','Customer','')
			";
		}
		
		
		$db_select = <<<EOSQL
			a.*, 
			b.Customer_ID
EOSQL;
		
		$this->db
			->select( $db_select )
			->from( "{$db_from} a" )
			->join( "{$this->customer_m->table} b", "a.Kode_Customer = b.Kode_Customer", "LEFT OUTER" )
			;		
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		
		$result = $this->db->get();
			
		// Output
        $output = array(
				'draw' => intval($draw),
				'recordsTotal' => $result->num_rows() - 1,
				'recordsFiltered' => $result->num_rows() - 1,
				'data' => $result->result()
			);
		
		$this->template
			->build_json( $output );
    }

	public function lookup_customers( ){
	
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'aging/lookup/customers' );
		} 
	}
}



