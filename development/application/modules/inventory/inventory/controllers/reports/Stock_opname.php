<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Stock_opname extends Admin_Controller
{ 
	protected $nameroutes = 'inventory/reports/stock-opname';	
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('inventory');

		$this->load->language('reports');
		
		$this->page = lang( 'reports:stock_opname_heading' );
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
		$section = $this->db->where("SectionID", "SECT0002")->get("SIMmSection")->row();
		$item = (object) array(
				"SectionName" => $section->SectionName
			);

		$data = array(
				"item" => $item,
				"nameroutes" => $this->nameroutes,
				"datepicker" => true,
				"form" => true,
				"lookup_products" => base_url("{$this->nameroutes}/lookup_products")
			);
			
		if( $this->input->is_ajax_request() || $is_ajax )
		{
			$this->load->view(
				"reports/stock_opname/modal/dialog",
				array("form_child" => $this->load->view("reports/stock_opname/dialog", $data, true))
			);
		} else
		{
			$this->template
				->set( "heading", $this->page )
				->set_breadcrumb( lang("reports:page") )
				->set_breadcrumb( lang("reports:stock_opname_heading"), base_url("{$this->nameroutes}") )
				->build('reports/stock_opname/dialog', (isset($data) ? $data : NULL));
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

			$collection = report_helper::get_stock_opname($post_data->date_start, $post_data->date_end, $post_data->SectionName );	
			$section = $this->db->where("SectionID", "SECT0002")->get("SIMmSection")->row();						
			$data = array(
							"post_data" => $post_data,	
							"collection" => $collection,
							"section" => $section,
						);
						
			//print_r($collection);exit;
			
			$html_content =  $this->load->view( "reports/stock_opname/export/pdf", $data, TRUE ); 
			$footer = lang('reports:stock_opname_label')."&nbsp; : &nbsp;".date("d M Y")."&nbsp;".date("H:i:s");
			
			$file_name = lang('reports:stock_opname_label');		
			
			//print $html_content;exit(0);
			
			/*if(!empty($collection)) : foreach ($collection as $key => $value) :
				echo $key."<br>";
				if(!empty($value)) : foreach ($value as $row) :
					print_r($row);
					echo "<br>";
				endforeach; endif;
			endforeach; endif;
			
			exit;*/
			
			export_helper::generate_pdf( $html_content, $file_name, $footer , $margin_bottom = 5, $header = NULL, $margin_top = 2, $orientation = 'L', $margin_left = 8, $margin_right = 8);
	
			
	
			exit(0);
		}
		
		redirect("{$this->nameroutes}/dialog");

	}

	private function export_excel()
	{
		if ($this->input->post())
		{
			$post_data = (object) $this->input->post("f") ;

			$collection = report_helper::get_stock_opname($post_data->date_start, $post_data->date_end, $post_data->Barang_ID, $post_data->Lokasi_ID );	
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
			//print_r($data);exit;
			
			$html_content =  $this->load->view( "reports/stock_opname/export/pdf", $data, TRUE ); 
			$footer = lang('reports:patient_reservation_label')."&nbsp; : &nbsp;".date("d M Y")."&nbsp;".date("H:i:s");
			
			$file_name = lang('reports:patient_reservation_label');		
			
			//print $html_content;exit(0);
			
			export_helper::generate_pdf( $html_content, $file_name, $footer , $margin_bottom = 5, $header = NULL, $margin_top = 2, $orientation = 'L', $margin_left = 8, $margin_right = 8);
	
			
	
			exit(0);
		}
		
		redirect("{$this->nameroutes}/dialog");

	}

	public function lookup_products( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'reports/stock_opname/lookup/products');
		} else
		{
			redirect( base_url( "{$this->nameroutes}" ) );
		}
	}	

}

