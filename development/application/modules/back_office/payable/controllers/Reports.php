<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reports extends Admin_Controller
{
	protected $_translation = 'payable';	
	protected $_model = 'report_m';
	protected $nameroutes;
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('payable');
		
		$this->nameroutes = "payable/reports";
		$this->load->helper("payable");		
		
		$this->load->model("report_m");
		$this->load->model("card_payable_m");
		$this->load->model("type_m");
		$this->load->model("supplier_m");
		
		$this->page = "payable";
		$this->template->title( lang("reports:page") . ' - ' . $this->config->item('company_name') );
	}
	
	public function index()
	{
		$data = array(
				'page' => $this->page,
				'options_type' => $this->type_m->get_option_type(),
				"lookup_suppliers" => base_url("{$this->nameroutes}/lookup_suppliers"),
				"beginning_balance_date" => payable_helper::get_beginning_balance_date(),
				"nameroutes" => $this->nameroutes,
				"form" => TRUE,
				"fileinput" => TRUE,
				"datatables" => TRUE,
			);
		
		$this->template
			->set( "heading", lang("reports:page") )
			->set_breadcrumb( lang("payable:page"), base_url("payable/facturs") )
			->set_breadcrumb( lang("reports:page"), base_url("{$this->nameroutes}") )
			->build('reports/form', (isset($data) ? $data : NULL));
	}

	public function card_payable( )
	{
		if ( $this->input->post())
		{
			$post_data = (object) $this->input->post("f");
			
			$data['collections'] = $this->db->where("Supplier_ID", $post_data->supplier_id )
										->where("Tanggal >=", $post_data->date_start )
										->where("Tanggal <=", $post_data->date_end )
										->from("{$this->card_payable_m->table}")
										->order_by("Tanggal", "ASC")
										->get()
										->result()
										;
	
	
			$data["file_name"] = sprintf( lang('reports:card_payable_filename'), date("d/m/Y", strtotime($post_data->date_start)), date("d/m/Y", strtotime($post_data->date_end)));
			$data["data"] = $post_data;
			//print_r($this->input->post());
			//print_r($data['collections']);
			//exit(0);
				
			$html_header = "";
			$html_footer = "";
			$html_content = $this->load->view( "reports/print/card_payable", $data, TRUE );
			
			//print $html_content;exit(0);
			
			$this->load->helper( "export" );
			export_helper::print_pdf( $html_content, $data['file_name'] );
			
			exit(0);
		}
	}

	public function recap_payable( )
	{
		if ( $this->input->post())
		{
			$post_data = (object) $this->input->post("f");

			$_db_select = "
				a.Tanggal,
				b.Kode_Supplier, 
				b.Nama_Supplier, 
				SUM(a.SaldoAwal) AS SaldoAwal, 
				SUM(a.Debet) AS Debet, 
				SUM(a.Kredit) AS Kredit";
	
			if ( $post_data->type_id == 0 || empty($post_data->type_id) )
			{
				$type = $this->type_m->get_option_type();
				
				if ( !empty( $type ) ): foreach( $type as $k => $v ):
				$data['collections'][$v] = (object) $this->db->select( $_db_select )
											->where("a.Tanggal >=", $post_data->date_start )
											->where("a.Tanggal <=", $post_data->date_end )
											->where("a.TipeHutang", $k)
											->from("{$this->card_payable_m->table} a")
											->join("{$this->supplier_m->table} b", "a.Supplier_ID = b.Supplier_ID", "INNER")
											->group_by(['a.Tanggal','b.Kode_Supplier', 'b.Nama_Supplier'])
											->order_by("a.Tanggal", "ASC")
											->get()->result();
				endforeach; endif;
	
			} else {
				
				$type = $this->type_m->get_row( $post_data->type_id );
				
				$data['collections'][$type['Nama_Type']] = $this->db->select( $_db_select )
											->where("a.Tanggal >=", $post_data->date_start )
											->where("a.Tanggal <=", $post_data->date_end )
											->where("a.TipeHutang", $post_data->type_id )
											->from("{$this->card_payable_m->table} a")
											->join("{$this->supplier_m->table} b", "a.Supplier_ID = b.Supplier_ID", "INNER")
											->group_by(['a.Tanggal','b.Kode_Supplier', 'b.Nama_Supplier'])
											->order_by("a.Tanggal", "ASC")
											->get()->result();		
			}
			
			$data["file_name"] = sprintf(lang('reports:recap_payable_filename'), date("d/m/Y", strtotime($post_data->date_start)), date("d/m/Y", strtotime($post_data->date_end)));
			$data["data"] = $post_data;
				
			$html_header = "";
			$html_footer = "";
			$html_content = $this->load->view( "reports/print/recap_payable", $data, TRUE );
			
			//print $html_content;exit(0);
			
			$this->load->helper( "export" );
			export_helper::print_pdf( $html_content, $data['file_name'] );
			
			exit(0);
		}
	}
	
	public function group_recap_payable( )
	{
		if ( $this->input->post())
		{
			$post_data = (object) $this->input->post("f");

			$_db_select = "
				b.Kode_Supplier, 
				b.Nama_Supplier, 
				SUM(a.SaldoAwal) AS SaldoAwal, 
				SUM(a.Debet) AS Debet, 
				SUM(a.Kredit) AS Kredit";
	
			if ( $post_data->type_id == 0 || empty($post_data->type_id) )
			{
				$type = $this->type_m->get_option_type();
				
				if ( !empty( $type ) ): foreach( $type as $k => $v ):
				$data['collections'][$v] = (object) $this->db->select( $_db_select )
											->where("a.Tanggal >=", $post_data->date_start )
											->where("a.Tanggal <=", $post_data->date_end )
											->where("a.TipeHutang", $k)
											->from("{$this->card_payable_m->table} a")
											->join("{$this->supplier_m->table} b", "a.Supplier_ID = b.Supplier_ID", "INNER")
											->group_by(['b.Kode_Supplier', 'b.Nama_Supplier'])
											->order_by("b.Kode_Supplier", "ASC")
											->get()->result();
				endforeach; endif;
	
			} else {
				
				$type = $this->type_m->get_row( $post_data->type_id );
				
				$data['collections'][$type['Nama_Type']] = $this->db->select( $_db_select )
											->where("a.Tanggal >=", $post_data->date_start )
											->where("a.Tanggal <=", $post_data->date_end )
											->where("a.TipeHutang", $post_data->type_id )
											->from("{$this->card_payable_m->table} a")
											->join("{$this->supplier_m->table} b", "a.Supplier_ID = b.Supplier_ID", "INNER")
											->group_by(['b.Kode_Supplier', 'b.Nama_Supplier'])
											->order_by("b.Kode_Supplier", "ASC")
											->get()->result();		
			}
			
			$data["file_name"] = sprintf(lang('reports:recap_payable_filename'), date("d/m/Y", strtotime($post_data->date_start)), date("d/m/Y", strtotime($post_data->date_end)));
			$data["data"] = $post_data;
				
			$html_header = "";
			$html_footer = "";
			$html_content = $this->load->view( "reports/print/group_recap_payable", $data, TRUE );
			
			//print $html_content;exit(0);
			
			$this->load->helper( "export" );
			export_helper::print_pdf( $html_content, $data['file_name'] );
			
			exit(0);
		}
	}
	
	public function lookup_suppliers( ){
	
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'reports/lookup/suppliers' );
		} 
	}
}



