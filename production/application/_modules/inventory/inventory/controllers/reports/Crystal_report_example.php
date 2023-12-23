<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Crystal_report_example extends Admin_Controller
{
	protected $nameroutes = 'inventory/reports/crystal_report_example';

	public function __construct()
	{
		parent::__construct();

		$this->load->language('reports');
		$this->load->model([
			'section_model',
			'user_model'
		]);

		$this->page = lang('reports:dead_stock');
		$this->template->title($this->page);
	}

	public function index()
	{
		if ($this->input->is_ajax_request()) {
			echo "<script language=\"javascript\">window.location=\"" . base_url("{$this->nameroutes}") . "\";</script>";
			exit();
		} else {
			redirect("{$this->nameroutes}/dialog");
		}
	}

	public function dialog($is_ajax = FALSE)
	{
		$option_section = $this->section_model->get_all(NULL, 0, ['KelompokSection' => 11, 'StatusAktif' => 1]);
		$data = [
			"option_section" => $option_section,
			"nameroutes" => $this->nameroutes,
			"datepicker" => true,
			"form" => true,
		];

		if ($this->input->is_ajax_request() || $is_ajax) {
			$this->load->view(
				"reports/dead_stock_items/modal/dialog",
				array("form_child" => $this->load->view("reports/dead_stock_items/dialog", $data, true))
			);
		} else {
			$this->template
				->set("heading", $this->page)
				->set_breadcrumb(lang('reports:page'))
				->set_breadcrumb(lang('reports:dead_stock'), base_url("{$this->nameroutes}"))
				->build('reports/dead_stock_items/dialog', (isset($data) ? $data : NULL));
		}
	}

	public function export()
	{
		if ($this->input->post()) {
			$this->load->helper([
				"export",
				"report"
			]);
			switch ($this->input->post("export_to")):
				case "pdf":
					$this->export_pdf();
					break;
				case "excel":
					$this->export_excel();
					break;
			endswitch;
		}
	}

	private function export_pdf()
	{
		// if ($this->input->post())
		// {
		// 	$params 	= (object) $this->input->post("f");
		// 	$collection = report_helper::get_dead_stock_items( $params->date, 'DEAD STOCK', $params->section, $params->group );	
		// 	$section 	= $this->section_model->get_one($params->section);
		// 	$user 		= $this->user_model->get_one($this->user_auth->User_ID);

		// 	$data = [
		// 				"title" => $this->page,
		// 				"params" => $params,	
		// 				"collection" => $collection,
		// 				"section" => $section,
		// 				"user" => $user,
		// 			];

		// 	$html_content =  $this->load->view( "reports/dead_stock_items/export/pdf", $data, TRUE ); 
		// 	$footer = "&nbsp;".date("d M Y")."&nbsp;".date("H:i:s");
		// 	$file_name = $this->page;		

		// 	export_helper::generate_pdf( $html_content, $file_name, $footer , $margin_bottom = 5, $header = NULL, $margin_top = 2, $orientation = 'L', $margin_left = 8, $margin_right = 8);
		// 	exit(0);
		// }

		// redirect("{$this->nameroutes}/dialog");
		$params = [
			// ['params' => 1, 'value' => '01-sep-2019'],
			// ['params' => 2, 'value' => '30-sep-2019'],
			// ['params' => 3, 'value' => '00.00.26'],
		];
		// $my_report  = APPPATH . ("../../public/Report/inventory/SIM_Rpt_Laporan_Pasien_Per_Dokter.rpt"); // RPT Orig file
		$my_report  = APPPATH . ("../../public/Report/inventory/test.rpt"); // RPT Orig file

			// ['params' => 1, 'value' => '01-aug-2019'],
			// ['params' => 2, 'value' => '30-sep-2019'],
			// ['params' => 3, 'value' => '00.00.26'],

		export_helper::generate_rpttopdf($my_report, $params);
	}

	private function export_excel()
	{
		// if ($this->input->post())
		// {
		// 	$params = (object) $this->input->post("f");		
		// 	report_helper::export_excel_dead_stock_items( $params->date, 'DEAD STOCK', $params->section, $params->group, $this->page );	
		// 	exit(0);
		// }
		$params = [
			['params' => 1, 'value' => '01-aug-2019'],
			['params' => 2, 'value' => '30-sep-2019'],
			['params' => 3, 'value' => '00.00.26'],
		];
		$xls_name = 'Laporan Uji Coba';
		$my_report  = APPPATH . ("../../public/Report/inventory/SIM_Rpt_Laporan_Pasien_Per_Dokter.rpt"); // RPT Orig file

		export_helper::generate_rpttoxls($my_report, $xls_name, $params);
	}
}
