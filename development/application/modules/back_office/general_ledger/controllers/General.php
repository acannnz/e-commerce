<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class General extends Admin_Controller
{
	protected $_translation = 'general_ledger';	
	protected $_model = 'journal_m';
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('general_ledger');
		
		$this->load->helper("general_ledger");
		
		$this->load->model("general_ledger/journal_m");
		$this->load->model("general_ledger/account_m");
		
		$this->page = "general";
		$this->template->title( lang("general:page") . ' - ' . $this->config->item('company_name') );
	}
	
	public function index()
	{
		$data = array(
				'page' => $this->page,
				"form" => TRUE,
				'datatables' => TRUE,
				"navigation_minimized" => TRUE,
				"currencies" => general_ledger_helper::get_option_currency(),
				"currency_default" => general_ledger_helper::get_default_currency_id(),
				"journal_type" => array("Jurnal Umum", "Jurnal Hutang", "Jurnal Piutang", "Jurnal General Cashier", "Jurnal Gudang", "Semua Jurnal"),
			);
					
		$this->template
			->set( "heading", lang("general:page") )
			->set_breadcrumb( lang("general:page"), base_url("general_ledger") )
			->build('general/form', (isset($data) ? $data : NULL));
	}
	
	public function details( )
	{
		
		$data = array(
				"populate_url" => base_url("general-ledger/general/datatable_collection"),
			);
		
		return $this->load->view( "general/table/tables", $data );		
		
	}

	public function lookup( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'general_ledger/lookup/datatable' );
		} else
		{
			$data = array(
					'page' => $this->page,
					'datatables' => TRUE,
					'form' => TRUE,
				);
			
			$this->template
				->set( "heading", "Lookup Box" )
				->set_breadcrumb( lang("common:page"), base_url("common") )
				->set_breadcrumb( "Lookup Box" )
				->build('general_ledger/lookup', (isset($data) ? $data : NULL));
		}
	}

	public function lookup_accounts(  ){
	
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'general/lookup/accounts', array() );
		} 
	}
	
	public function print_journal_transactions()
	{
		$post_data = (object) $this->input->post('f');
		
		$evidence_number = $evidence_number_before = NULL;
		$collection = array();
		
		$transactions = general_ledger_helper::get_general_details( $post_data );
		foreach( $transactions['collection'] as $row ){
			$evidence_number = $row->NoBukti;
			$row->Tanggal = ( $evidence_number_before == $evidence_number ) ? '' : substr( $row->Tanggal, 0, 10 );
			$row->NoBukti =	( $evidence_number_before == $evidence_number ) ? '' : $evidence_number;
			$row->Debit = number_format($row->Debit, 2, ',', '.');
			$row->Kredit = number_format($row->Kredit, 2, ',', '.');
			$collection[] = $row;
	
			$evidence_number_before = $evidence_number;
		}
		
        // Output
        $data = array(
				'collection' => $collection,
				'debit' => number_format( $transactions['summary']->Debit, 2, ',', '.'),
				'credit' => number_format( $transactions['summary']->Kredit, 2, ',', '.'),
				'balance' => number_format( $transactions['summary']->Debit - $transactions['summary']->Kredit, 2, ',', '.'),
				'post_data' => $post_data,
				'file_name' => sprintf( "Journal-%s.pdf", date("d F Y") )
			);		

		//print_r($data);exit(0);
			
		$html_header = "";
		$html_footer = "";
		$html_content = $this->load->view( "general/print/journals", $data, TRUE );
		
		//print $html_content;exit(0);
		
		$this->load->helper( "export" );
		export_helper::print_pdf( $html_content, $file_name );
		
		exit(0);
	}
	
	public function lookup_collection()
	{
		$this->datatable_collection( 1 );
	}
	
	public function datatable_collection( $state=false )
    {
		
		// prepare defautl flter	
		$params  = (object) $this->input->get();						
		$this->db->query("EXEC CekHisCurrency '$params->date_till'");		
	
		/*
			[Tanggal] => 2016-01-20 00:00:00.000
			[NoBukti] => 2016/01/JUM/0077
			[NoAkun] => 101030100601
			[NamaAkun] => Piutang Merchan BCA
			[Debit] => 6633.0000
			[Kredit] => .0000
			[Keterangan] => Add Charge Obat Bebas 160120/POB/000576
			[Proyek] => RSIA PURI BUNDA
			[Divisi] => RSIA PURI BUNDA
			[Kode_Transfer] => 
		*/
		$evidence_number = $evidence_number_before = NULL;

		$collection = array();
		
		$transactions = general_ledger_helper::get_general_details( $params );
		foreach( $transactions['collection'] as $row ){
			$evidence_number = $row->NoBukti;
			$row->Tanggal = ( $evidence_number_before == $evidence_number ) ? '' : substr( $row->Tanggal, 0, 10 );
			$row->NoBukti =	( $evidence_number_before == $evidence_number ) ? '' : $evidence_number;
			$row->Debit = number_format($row->Debit, 2, ',', '.');
			$row->Kredit = number_format($row->Kredit, 2, ',', '.');
			$row->NoBuktiHide = $evidence_number;
			$collection[] = $row;
	
			$evidence_number_before = $evidence_number;
		}
		
        // Output
        $output = array(
				'collection' => $collection,
				'debit' => number_format( $transactions['summary']->Debit, 2, ',', '.'),
				'credit' => number_format( $transactions['summary']->Kredit, 2, ',', '.'),
				'balance' => number_format( $transactions['summary']->Debit - $transactions['summary']->Kredit, 2, ',', '.'),
			);

		$this->template
			->build_json($output);
    }

}