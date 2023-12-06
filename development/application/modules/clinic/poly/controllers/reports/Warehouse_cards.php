<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Warehouse_cards extends Admin_Controller
{ 
	protected $_translation = 'reports';	
	protected $nameroutes = 'poly/reports';	
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role(['inpatient', 'outpatient']);
		
		$this->page = lang( 'reports:warehouse_card_heading' );
		$this->template->title( $this->page . ' - ' . $this->config->item('company_name') );
	}
	
	public function index()
	{
		if( $this->input->is_ajax_request() )
		{
			echo "<script language=\"javascript\">window.location=\"".base_url("{$this->nameroutes}/warehouse-cards")."\";</script>";
			exit();
		} else
		{
			redirect();
		}
	}
	

	public function dialog( $type )
	{
		$location = $this->session->userdata($type);
		$section = $this->db->where("SectionID", $location['section_id'])->get("SIMmSection")->row();
		$item = (object) array(
				"SectionID" => $section->SectionID,
				"Lokasi_ID" => $section->Lokasi_ID
			);

		$data = array(
				"item" => $item,
				"datepicker" => true,
				"datatables" => true,
				"form" => true,
				"export_url" => base_url("{$this->nameroutes}/warehouse-cards/export/{$type}"),
				"lookup_products" => base_url("{$this->nameroutes}/warehouse-cards/lookup_products")
			);
			
		if( $this->input->is_ajax_request())
		{
			$this->load->view(
				"polies/reports/warehouse_cards/modal/dialog",
				array("form_child" => $this->load->view("polies/reports/warehouse_cards/dialog", $data, true))
			);
		} else
		{
			$this->template
				->set( "heading", $this->page )
				->set_breadcrumb( lang("reports:page") )
				->set_breadcrumb( lang("reports:warehouse_card_heading"), base_url("{$this->nameroutes}/warehouse-cards") )
				->build('polies/reports/warehouse_cards/dialog', (isset($data) ? $data : NULL));
		}
	}
	
	public function export($type)
	{
		if ( $this->input->post() )
		{
			$this->load->helper( "export" );
			$this->load->helper( "report" );
			
			switch ( $this->input->post("export_to") ) :
				case "pdf" :
					$this->export_pdf($type);
				break;	
				case "excel" :
					$this->export_excel($type);
				break;
			endswitch;
		}
	}

	private function export_pdf( $type )
	{
		$location = $this->session->userdata($type);
		
		if ($this->input->post())
		{
			$post_data = (object) $this->input->post("f") ;

			$collection = report_helper::get_warehouse_cards($post_data->date_start, $post_data->date_end, $post_data->Barang_ID, $post_data->Lokasi_ID );	
			$barang = report_helper::get_barang($post_data->Barang_ID, $post_data->Lokasi_ID );	
			$last_data = end($collection);	// Mengambil data kartu terakhir untuk stok akhir
			$section = $this->db->where("SectionID", $location['section_id'])->get("SIMmSection")->row();						
			$data = array(
							"post_data" => $post_data,	
							"collection" => $collection,
							"last_data" => $last_data,
							"barang" => $barang,
							"section" => $section,
						);
						
			//print_r($barang);exit;
			
			$html_content =  $this->load->view( "polies/reports/warehouse_cards/export/pdf", $data, TRUE ); 
			$footer = lang('reports:warehouse_card_label')."&nbsp; : &nbsp;".date("d M Y")."&nbsp;".date("H:i:s");
			
			$file_name = lang('reports:warehouse_card_label');		
			
			//print $html_content;exit(0);
			
			export_helper::generate_pdf( $html_content, $file_name, $footer , $margin_bottom = 5, $header = NULL, $margin_top = 2, $orientation = 'L', $margin_left = 8, $margin_right = 8);
	
			
	
			exit(0);
		}
		
		$this->index();

	}

	private function export_excel( $type )
	{
		$location = $this->session->userdata($type);
		if ($this->input->post())
		{
			$post_data = (object) $this->input->post("f") ;

			$collection = report_helper::get_warehouse_cards($post_data->date_start, $post_data->date_end, $post_data->Barang_ID, $post_data->Lokasi_ID );	
			$barang = report_helper::get_barang($post_data->Barang_ID, $post_data->Lokasi_ID );	
			$last_data = end($collection);	// Mengambil data kartu terakhir untuk stok akhir
			$section = $this->db->where("SectionID", $location['section_id'])->get("SIMmSection")->row();						
			$data = array(
							"post_data" => $post_data,	
							"collection" => $collection,
							"last_data" => $last_data,
							"barang" => $barang,
							"section" => $section,
						);
			//print_r($data);exit;
			
			$html_content =  $this->load->view( "reports/warehouse_cards/export/pdf", $data, TRUE ); 
			$footer = lang('reports:warehouse_card_label')."&nbsp; : &nbsp;".date("d M Y")."&nbsp;".date("H:i:s");
			
			$file_name = lang('reports:warehouse_card_label');		
			
			//print $html_content;exit(0);
			
			export_helper::generate_pdf( $html_content, $file_name, $footer , $margin_bottom = 5, $header = NULL, $margin_top = 2, $orientation = 'L', $margin_left = 8, $margin_right = 8);
	
			
	
			exit(0);
		}
		
		$this->index();

	}

	public function lookup_products( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'polies/reports/warehouse_cards/lookup/products');
		} else
		{
			redirect( base_url( "{$this->nameroutes}/warehouse-cards" ) );
		}
	}	

}

