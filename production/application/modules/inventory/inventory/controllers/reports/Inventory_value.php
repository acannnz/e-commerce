<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Inventory_value extends Admin_Controller
{ 
	protected $nameroutes = 'inventory/reports/inventory_value';	
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('inventory');

		$this->load->language('reports');
		$this->load->model('section_model');
		
		$this->page = 'Nilai Persediaan Akhir';
		$this->template->title( $this->page );
	}
	
	public function index()
	{
		if( $this->input->is_ajax_request() )
		{
			echo "<script language=\"javascript\">window.location=\"".base_url("{$this->nameroutes}")."\";</script>";
			exit();
		} else
		{
			redirect( "{$this->nameroutes}/dialog" );
		}
	}
	

	public function dialog ( $is_ajax = FALSE)
	{
		$option_section = $this->section_model->get_all(NULL, 0, ['KelompokSection' => 11, 'StatusAktif' => 1]);
		$data = [
			"option_section" => $option_section,
			"nameroutes" => $this->nameroutes,
			"datepicker" => true,
			"form" => true,
		];
			
		if( $this->input->is_ajax_request() || $is_ajax )
		{
			$this->load->view(
				"reports/inventory_value/modal/dialog",
				array("form_child" => $this->load->view("reports/inventory_value/dialog", $data, true))
			);
		} else
		{
			$this->template
				->set( "heading", $this->page )
				->set_breadcrumb( 'Laporan' )
				->set_breadcrumb( 'Nilai Persediaan Akhir', base_url("{$this->nameroutes}") )
				->build('reports/inventory_value/dialog', (isset($data) ? $data : NULL));
		}
	}
	
	public function export()
	{
		if ( $this->input->post() )
		{
			$this->load->helper( "export" );
			$this->load->helper( "report" );
			
			switch ( $this->input->post("export_to") ) :
				case "pdf" :
					$this->export_pdf();
				break;	
				case "excel" :
					$this->export_excel();
				break;
			endswitch;
		}
	}

	private function export_pdf()
	{
		if ($this->input->post())
		{
			$post_data = (object) $this->input->post("f") ;

			$collection = report_helper::get_warehouse_cards($post_data->date_start, $post_data->date_end, $post_data->Barang_ID, $post_data->Lokasi_ID );	
			$barang = report_helper::get_barang($post_data->Barang_ID, $post_data->Lokasi_ID );	
			$last_data = end($collection);	// Mengambil data kartu terakhir untuk stok akhir
			$section = $this->db->where("SectionID", "SECT0002")->get("SIMmSection")->row();						
			$data = array(
							"post_data" => $post_data,	
							"collection" => $collection,
							"last_data" => $last_data,
							"barang" => $barang,
							"section" => $section,
						);
						
			//print_r($barang);exit;
			
			$html_content =  $this->load->view( "reports/warehouse_cards/export/pdf", $data, TRUE ); 
			$footer = lang('reports:warehouse_card_label')."&nbsp; : &nbsp;".date("d M Y")."&nbsp;".date("H:i:s");
			
			$file_name = lang('reports:warehouse_card_label');		
			
			//print $html_content;exit(0);
			
			export_helper::generate_pdf( $html_content, $file_name, $footer , $margin_bottom = 5, $header = NULL, $margin_top = 2, $orientation = 'L', $margin_left = 8, $margin_right = 8);
	
			
	
			exit(0);
		}
		
		redirect("{$this->nameroutes}/dialog");

	}

	private function export_excel()
	{
		if ($this->input->post())
		{
			$date = $this->input->post('f[date]');		
			$location = $this->input->post('f[location]');		
			$group = $this->input->post('f[group]');		
			
			report_helper::export_excel_inventory_value($date, $location, $group);
						
			exit(0);
		}
	}

	public function lookup_products( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'reports/warehouse_cards/lookup/products');
		} else
		{
			redirect( base_url( "{$this->nameroutes}" ) );
		}
	}	

}

