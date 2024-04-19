<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reports extends Admin_Controller
{ 
	protected $_translation = 'laboratory';	
	protected $nameroutes = 'laboratory/reports';	
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role(['laboratory','inpatient', 'outpatient']);
		
		$this->load->model([
				"laboratory_m",
				"lis_test_sample_model",
				"lis_test_type_model",
				"test_type_detail_m",
				"test_type_m",
				"test_category_m",
				"registration_model",
				"patient_model",
				"supplier_model"
			]);
		
		$this->page = lang( 'reports:stock_opname_heading' );
		$this->template->title( $this->page . ' - ' . $this->config->item('company_name') );
	}
	
	public function index()
	{
		if( $this->input->is_ajax_request() )
		{
			echo "<script language=\"javascript\">window.location=\"".base_url("{$this->nameroutes}/stock-opname")."\";</script>";
			exit();
		} else
		{
			redirect();
		}
	}
	

	public function dialog ( $type )
	{
		$location = $this->session->userdata($type);
		$section = $this->db->where("SectionID", $location['section_id'])->get("SIMmSection")->row();
		$item = (object) array(
				"SectionName" => $section->SectionName
			);

		$data = array(
				"item" => $item,
				"datepicker" => true,
				"form" => true,
				"export_url" => base_url("{$this->nameroutes}/stock-opname/export/{$type}"),
				"lookup_products" => base_url("{$this->nameroutes}/stock-opname/lookup_products")
			);
			
		if( $this->input->is_ajax_request())
		{
			$this->load->view(
				"polies/reports/stock_opname/modal/dialog",
				array("form_child" => $this->load->view("polies/reports/stock_opname/dialog", $data, true))
			);
		} else
		{
			$this->template
				->set( "heading", $this->page )
				->set_breadcrumb( lang("reports:page") )
				->set_breadcrumb( lang("reports:stock_opname_heading"), base_url("{$this->nameroutes}/stock-opname") )
				->build('polies/reports/stock_opname/dialog', (isset($data) ? $data : NULL));
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

	public function examination_test_result($NoBukti = NULL)
	{
		if($NoBukti)
		{
			$this->load->helper([
				"export",
				"laboratory" 
			]);
						
			$data = [
				"item" => $item = laboratory_helper::get_examination_test($NoBukti, TRUE),
				"doctor" => $this->supplier_model->get_by(['Kode_Supplier' => $item->DokterID]),
				"analysis" => $this->supplier_model->get_by(['Kode_Supplier' => $item->AnalisID]),
				"collection" => laboratory_helper::get_examination_test_result_report($NoBukti),
			];
						
			$html_content =  $this->load->view( "reports/export/examination_test_result", $data, TRUE ); 
			$footer = NULL;
			// print_r(json_encode($data));exit;
			$file_name = lang('reports:stock_opname_label');		
			
			export_helper::generate_pdf( $html_content, $file_name, $footer , 5, NULL,  2, 'P', 8,  8);
			exit(0);
		}
		
		show_404();

	}

	private function export_excel( $type )
	{
		$location = $this->session->userdata($type);
		if ($this->input->post())
		{
			$post_data = (object) $this->input->post("f") ;

			$collection = report_helper::get_stock_opname($post_data->date_start, $post_data->date_end, $post_data->Barang_ID, $post_data->Lokasi_ID );	
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
			
			$html_content =  $this->load->view( "polies/reports/stock_opname/export/pdf", $data, TRUE ); 
			$footer = lang('reports:stock_opname_label')."&nbsp; : &nbsp;".date("d M Y")."&nbsp;".date("H:i:s");
			
			$file_name = lang('reports:stock_opname_label');		
			
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
			$this->load->view( 'polies/reports/stock_opname/lookup/products');
		} else
		{
			redirect( base_url( "{$this->nameroutes}/stock-opname" ) );
		}
	}	

}

