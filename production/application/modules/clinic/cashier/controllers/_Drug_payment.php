<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\ImagickEscposImage;

class Drug_payment extends Admin_Controller
{
	protected $_translation = 'drug_payment';
	protected $_model = 'drug_payment_m';

	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('cashier');

		//jika berlaku clerk
		if (config_item('use_clerk') == 'TRUE') {
			if (empty($this->session->userdata('KodeClerk'))) {
				redirect('clerk/start');
			}
		}


		$this->page = lang("nav:drug_payment");
		$this->template->title(lang("drug_payment:page") . ' - ' . $this->config->item('company_name'));

		$this->load->model('drug_payment_m');
		$this->load->model('common/patient_type_m');
		$this->load->model('common/supplier_m');
		$this->load->model('common/section_m');
		$this->load->model('common/customer_m');

		$this->load->helper('drug_payment');
	}

	public function index()
	{
		$data = array(
			'page' => $this->page,
			"form" => TRUE,
			'datatables' => TRUE,
		);

		$this->template
			->set("heading", lang("drug_payment:list_heading"))
			->set_breadcrumb(lang("drug_payment:page"), base_url("cashier/drug-payment"))
			->build('cashier/drug_payment/datatable', (isset($data) ? $data : NULL));
	}

	public function pay($NoFarmasi = NULL)
	{
		if (empty($NoFarmasi)) {
			make_flashdata(array(
				'response_status' => 'error',
				'message' => "URL Tidak Sah!"
			));
			redirect("cashier/drug-payment");
		}

		$cashier = $this->db->where("NoBukti", $NoFarmasi)->get("BILLFarmasi")->row();
		$cashier->JenisKerjasama = @$cashier->KerjasamaID;
		$cashier->TipePasien = $this->db->where("JenisKerjasamaID", $cashier->KerjasamaID)->get("SIMmJenisKerjasama")->row()->JenisKerjasama;
		$cashier_detail = $this->db->where("NoBukti", $NoFarmasi)->get("BILLFarmasiDetail")->result();

		$location = 'SECT0002';
		$item = (object) array(
			'NoBukti' => drug_payment_helper::gen_evidence_number(date('Y-m-d H:i:s')),
			'NoBuktiFarmasi' => $NoFarmasi,
			'Tanggal' => date("Y-m-d"),
			'Jam' => date("Y-m-d H:i:s"),
			'UserID' => $this->user_auth->User_ID,
			'NilaiPembayaran' => 0,
			'NilaiPembayaranIKS' => 0,
			'NilaiPembayaranBPJS' => 0,
			'NilaiPembayaranBebanRS' => 0,
			'Kredit' => 0,
			'NilaiPembayaranCC' => 0,
			'IDBank' => NULL,
			'AddCharge' => 0,
			'SectionID' => $location,
			'TipePembayaran' => 'TUNAI',
			'UnitBisnisID' => 1,
			// 'Shift' => @$this->user_auth->shift_name,
			'Shift' => $this->session->userdata('shift_id'),
			'tipe' => 'OBAT BEBAS',
			'KodeCustomerPenjamin' => $cashier->KodePerusahaan
		);


		if ($cashier->ClosePayment == 1) {
			redirect("cashier/drug-payment/view/{$NoFarmasi}");
		}

		if ($this->input->post()) {

			$validation = TRUE;
			$data_post = $this->input->post("f");
			$item = (object) array_merge((array) $item, $data_post);
			$item->NoBukti = drug_payment_helper::gen_evidence_number($item->Jam);
			$total_pembayaran = $item->NilaiPembayaran + $item->NilaiPembayaranIKS + $item->NilaiPembayaranBPJS + $item->NilaiPembayaranBebanRS + $item->Kredit + $item->NilaiPembayaranCC;
			if ($item->NilaiTransaksi != $total_pembayaran) {
				$validation = FALSE;
				$validation_message = "Nilai Transaksi &amp; Total Pembayaran Tidak Sama";
			}

			$this->load->library('form_validation');
			$this->form_validation->set_rules($this->drug_payment_m->rules['insert']);
			$this->form_validation->set_data((array) $item);
			if (!$this->form_validation->run() && $validation) {
				$this->db->trans_begin();
				$this->db->insert("SIMtrPembayaranObatBebas", $item);
				$this->db->update("BILLFarmasi", array("ClosePayment" => 1), array("NoBukti" => $item->NoBuktiFarmasi));
				$this->db->update("BILLFarmasi", array("Batal" => 0), array("NoBukti" => $item->NoBuktiFarmasi));
				$section = $this->db->where("SectionID", $location)->get("SIMmSection")->row();

				// Insert User Aktivities
				$activities_description = sprintf("%s # %s # %s # %s # %s", "INPUT PEMBYR OBAT BEBAS.", $item->NoBukti, $item->NoBuktiFarmasi, $item->Keterangan, $section->SectionName);
				$this->db->query("EXEC InsertUserActivities '$item->Tanggal','$item->Jam', {$this->user_auth->User_ID} ,'$item->NoBukti','$activities_description','SIMtrPembayaranObatBebas'");

				if ($this->db->trans_status() === FALSE) {
					$this->db->trans_rollback();
					make_flashdata(array(
						"response_status" => 'error',
						"message" => lang('global:created_failed'),
						"code" => 500
					));
				} else {
					//$this->db->trans_rollback();
					$this->db->trans_commit();
					make_flashdata(array(
						"response_status" => 'success',
						"message" => lang('global:created_successfully'),
						"code" => 200
					));

					redirect("cashier/drug-payment/view/$item->NoBuktiFarmasi");
				}
			} else {
				make_flashdata(array(
					"response_status" => 'error',
					"message" => !empty($validation_message) ? $validation_message : $this->form_validation->get_all_error_string(),
					"code" => 500
				));
			}
		}

		$item->grandTotal = $cashier->Total;

		if ($this->input->is_ajax_request()) {
			$data = array(
				"item" => $item,
				"cashier" => $cashier,
				"cashier_detail" => $cashier_detail,
				"lookup_merchan" => base_url("cashier/drug-payment/lookup_merchan"),
				"is_ajax_request" => TRUE,
				"is_modal" => TRUE,
				"is_edit" => FALSE,
			);

			$this->load->view(
				'drug_payment/modal/create_edit',
				array('form_child' => $this->load->view('drug_payment/form', $data, true))
			);
		} else {

			$data = array(
				"page" => $this->page . "_" . strtolower(__FUNCTION__),
				"item" => $item,
				"cashier" => $cashier,
				"cashier_detail" => $cashier_detail,
				"lookup_merchan" => base_url("cashier/drug-payment/lookup_merchan"),
				"form" => TRUE,
				"datatables" => TRUE,
			);

			$this->template
				->set("heading", lang("drug_payment:pay_heading"))
				->set_breadcrumb(lang("drug_payment:breadcrumb"), base_url("cashier/drug_payment"))
				->set_breadcrumb(lang("drug_payment:pay_heading"))
				->build('drug_payment/form', $data);
		}
	}

	public function view($NoFarmasi = 0)
	{
		if ($NoFarmasi == 0) {
			make_flashdata(array(
				'response_status' => 'error',
				'message' => "URL Tidak Sah!"
			));
			redirect("cashier/drug-payment");
		}

		$cashier = $this->db->where("NoBukti", $NoFarmasi)->get("BILLFarmasi")->row();

		$item = $this->db->where(array("NoBuktiFarmasi" => $NoFarmasi, "Batal" => 0))->get("SIMtrPembayaranObatBebas")->row();
		$item->AddChargeValue = $item->NilaiPembayaranCC * $item->AddCharge / 100;
		$item->GrandTotal = $item->NilaiTransaksi + $item->AddChargeValue;

		$cashier->JenisKerjasama = $this->db->where("JenisKerjasamaID", $cashier->KerjasamaID)->get("SIMmJenisKerjasama")->row()->JenisKerjasama;
		$cashier->TipePasien = $cashier->JenisKerjasama;

		$cashier_detail = $this->db->where("NoBukti", $NoFarmasi)->get("BILLFarmasiDetail")->result();
		$merchan = $this->db->where("ID", $item->IDBank)->get("SIMmMerchan")->row();


		if ($this->input->is_ajax_request()) {
			$data = array(
				"item" => $item,
				"cashier" => $cashier,
				"cashier_detail" => $cashier_detail,
				"merchan" => $merchan,
				"cancel_payment_link" => base_url("cashier/drug-payment/cancel/$item->NoBuktiFarmasi"),
				// "print_billing_link" => base_url("cashier/drug-payment/print_billing/$item->NoBuktiFarmasi"),
				"print_billing_link" => base_url("cashier/cashier/print_billing/"),
				"dp_billing_link" => base_url("cashier/drug-payment/dp_billing/$item->NoBuktiFarmasi"),
				"is_ajax_request" => TRUE,
				"is_modal" => TRUE,
				"is_edit" => TRUE,
			);

			$this->load->view(
				'drug_payment/modal/view',
				array('form_child' => $this->load->view('drug_payment/view', $data, true))
			);
		} else {
			$data = array(
				"page" => $this->page . "_" . strtolower(__FUNCTION__),
				"item" => $item,
				"cashier" => $cashier,
				"cashier_detail" => $cashier_detail,
				"merchan" => $merchan,
				"cancel_payment_link" => base_url("cashier/drug-payment/cancel/$item->NoBuktiFarmasi"),
				// "print_billing_link" => base_url("cashier/drug-payment/print_billing/$item->NoBuktiFarmasi"),
				"print_billing_link" => base_url("cashier/cashier/print_billing/"),
				"dp_billing_link" => base_url("cashier/drug-payment/dp_billing/$item->NoBuktiFarmasi"),
				"form" => TRUE,
				"datatables" => TRUE,
				"is_edit" => TRUE,
			);

			$this->template
				->set("heading", lang("drug_payment:view_heading"))
				->set_breadcrumb(lang("drug_payment:breadcrumb"), base_url("cashier/drug_payment"))
				->set_breadcrumb(lang("drug_payment:view_heading"))
				->build('drug_payment/form', $data);
		}
	}

	public function cancel($NoFarmasi)
	{

		$item = $this->db->where(array("NoBuktiFarmasi" => $NoFarmasi, "Batal" => 0))->get("SIMtrPembayaranObatBebas")->row();

		if ($this->input->post()) {
			if (empty($item)) {
				make_flashdata(array(
					'response_status' => 'error',
					'message' => lang('global:get_failed')
				));

				redirect($this->input->post('r_url'));
			}

			if ($item->NoBukti == $this->input->post('confirm') && $item->Audit == 0) {

				$this->db->trans_begin();

				$this->db->update("SIMtrPembayaranObatBebas", array("Batal" => 1), array("NoBukti" => $item->NoBukti));
				$this->db->update("BILLFarmasi", array("ClosePayment" => 0), array("NoBukti" => $item->NoBuktiFarmasi));
				// Batal BILLFarmasi tidak diaktifkan, biar bisa return realisasi obat
				// $this->db->update("BILLFarmasi", array("Batal" => 1), array("NoBukti" => $item->NoBuktiFarmasi));
				$location = 'SEC079';
				$section = $this->db->where("SectionID", $location)->get("SIMmSection")->row();

				// Insert User Aktivities
				$activities_description = sprintf("%s # %s # %s # %s # %s", "Cancel PEMBYR OBAT BEBAS.", $item->NoBukti, $item->NoBuktiFarmasi, $item->Keterangan, $section->SectionName);
				$this->db->query("EXEC InsertUserActivities '$item->Tanggal','$item->Jam', {$this->user_auth->User_ID} ,'$item->NoBukti','$activities_description','SIMtrPembayaranObatBebas'");

				if ($this->db->trans_status() === FALSE) {
					$this->db->trans_rollback();
					make_flashdata(array(
						'response_status' => 'success',
						'message' => lang('global:cancel_failed')
					));
				} else {
					$this->db->trans_commit();
					make_flashdata(array(
						'response_status' => 'success',
						'message' => lang('global:cancel_successfully')
					));

					redirect("cashier/drug-payment/pay/$item->NoBuktiFarmasi");
				}
			} else {
				make_flashdata(array(
					'response_status' => 'error',
					'message' => 'Tidak Data Membatalkan Transaksi, data ini sudah di Verifikasi!'
				));

				redirect("cashier/drug-payment/pay/$item->NoBuktiFarmasi");
			}

			redirect($this->input->post('r_url'));
		}

		$this->load->view('drug_payment/modal/cancel', array('item' => $item));
	}

	// Print Billing
	public function print_billing($NoFarmasi = 0)
	{
		if ($this->input->is_ajax_request() || $NoFarmasi) {

			$response = array(
				"status" => "success",
				"message" => "",
				"code" => 200
			);

			$NoFarmasi = !empty($this->input->post("NoBuktiFarmasi")) ? $this->input->post("NoBuktiFarmasi") : $NoFarmasi;

			if ($NoFarmasi == 0) {
				$response = array(
					"status" => "error",
					"message" => lang('global:get_failed'),
					"code" => 500
				);
				print_r(json_encode($response, JSON_NUMERIC_CHECK));
				exit(0);
			}

			$item = drug_payment_helper::get_billing($NoFarmasi);
			$detail = drug_payment_helper::get_billing_detail($NoFarmasi);

			$collection = array();
			$grand_total =  0;
			foreach ($detail as $row) {
				$row->SubTotal = $row->Harga - ($row->Harga * $row->Disc / 100);
				$grand_total = $grand_total + $row->SubTotal;

				$collection[] = $row;
			}

			$data = array(
				"item" => $item,
				"collection" => $collection,
				"grand_total" => $grand_total,
				"user" => $this->user_auth,
			);

			//print_r($item);exit;

			// PDF Content
			//$html_content = $this->load->view( "drug_payment/print/billing", $data, TRUE );    
			$html_content_ascii = htmlentities($this->load->view("drug_payment/print/billing_table_only", $data, TRUE));
			$file_name = "billing-OB-$NoFarmasi.pdf";
			$footer = '';
			//print_r($user);exit;
			$this->load->helper("export");

			export_helper::print_pdf($html_content, $file_name, NULL /*footer*/, $margin_bottom = 0.3, $header = NULL, $margin_top = 0.3, $orientation = 'P', $margin_left = 0.5, $margin_right = 0.5);
			exit(0);
		}
	}

	// DP = Direct Print
	public function dp_billing($NoFarmasi)
	{
		$item = drug_payment_helper::get_billing($NoFarmasi);
		//print_r($item);exit;
		$detail = drug_payment_helper::get_billing_detail($NoFarmasi);
		$type_payment_used = drug_payment_helper::get_type_payment_used($NoFarmasi);

		$collection = [];
		$sub_total = [];
		$grand_total =  0;
		foreach ($detail as $row) {
			if ($row->Qty <= $row->JmlRetur) continue;

			$row->SubTotal = currency_ceil($row->Qty * $row->Harga - ($row->Qty * $row->Harga * $row->Disc / 100) + $row->HExt + $row->BiayaResep);

			if (empty($sub_total[$row->NamaResepObat])) {
				$sub_total[$row->NamaResepObat] = $row->SubTotal;
			} else {
				$sub_total[$row->NamaResepObat] = $sub_total[$row->NamaResepObat] + $row->SubTotal;
			}

			$grand_total = $grand_total + $row->SubTotal;


			if (empty($collection[$row->NamaResepObat]) && $row->Nama_Barang == $row->NamaResepObat) {
				$collection[$row->NamaResepObat] = $row;
			} elseif (!empty($collection[$row->NamaResepObat]) && $row->Nama_Barang == $row->NamaResepObat) {
				$collection[$row->NamaResepObat]->Qty += $row->Qty;
				$collection[$row->NamaResepObat]->HExt += $row->HExt;
				$collection[$row->NamaResepObat]->BiayaResep += $row->BiayaResep;
				$collection[$row->NamaResepObat]->SubTotal += $row->SubTotal;
			} else {
				$collection[] = $row;
			}
		}
		$date = date("D jS M Y\nh:i:s A");
		$data = array(
			"item" => $item,
			"collection" => $collection,
			"grand_total" => $grand_total,
			"user" => $this->user_auth,
		);

		try {

			$pwlen = 33; // printer width lenght		
			/* Start the printer */
			$connector = new WindowsPrintConnector("Microsoft Print to PDF"); // USB LOCAL
			//$connector = new WindowsPrintConnector("smb://10.10.10.13/PrinterBilling"); //USB LAN
			//$connector = new NetworkPrintConnector("192.168.0.118", 'ESDPRT001');
			$printer = new Printer($connector);
			$logo = EscposImage::load($_SERVER["DOCUMENT_ROOT"] . "/public/resource/print/escpos-php.png", false);

			/* Print top logo */
			$printer->setJustification(Printer::JUSTIFY_CENTER);
			//$printer->bitImage($logo);
			//$printer->bitImageColumnFormat($logo, Printer::IMG_DOUBLE_HEIGHT);

			/* Name of shop */
			$printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
			$printer->text('APOTEK KARYA PRIMA' . "\n");
			$printer->selectPrintMode();
			$printer->text(sprintf("%s, %s \n", $this->config->item("company_address"), $this->config->item("company_city")));
			$printer->text(sprintf("%s, %s \n", lang("drug_payment:phone_label"), ($this->config->item("company_phone") ? $this->config->item("company_phone") : "n/a")));
			$printer->feed();

			$printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
			$printer->text(lang("drug_payment:billing_subtitle") . "\n");
			$printer->selectPrintMode();
			//$printer->feed();

			$printer->setEmphasis(true);
			$printer->text("---------------------------------\n");
			$printer->setJustification(Printer::JUSTIFY_LEFT);
			$printer->text(sprintf("%s : %s \n", lang("drug_payment:no_bill_label"), $item->NoBuktiBill));
			if (!empty($item->NoReg)) {
				$printer->text(sprintf("%s  : %s \n", lang("drug_payment:no_reg_label"), $item->NoReg));
			}
			$printer->text(sprintf("%s   : %s \n", lang("drug_payment:name_label"), $item->Keterangan));
			$printer->text(sprintf("%s : %s \n", lang("drug_payment:doctor_label"), $item->Nama_Supplier));
			$printer->text("---------------------------------\n");

			/* Items */
			foreach ($collection as $row) {
				if ($row->Nama_Barang != $row->NamaResepObat) {
					continue;
				}
				$printer->setJustification(Printer::JUSTIFY_LEFT);
				$printer->text($row->Nama_Barang . "\n");
				$printer->setJustification(Printer::JUSTIFY_RIGHT);
				//$left = sprintf("%sx %s", $row->Qty, number_format($row->Harga, 2, ".", ",") );
				$left = sprintf("%sx %s -%s%%", $row->Qty, number_format($sub_total[$row->NamaResepObat] / $row->Qty, 2, ".", ","), (float)@$row->Disc);
				$right = number_format($sub_total[$row->NamaResepObat], 2, ".", ",");
				$printer->text($this->dp_adjust_line($left, $right));
			}


			//$printer->feed();
			$printer->text("---------------------------------\n");
			/* Tax and total */
			$printer->setJustification(Printer::JUSTIFY_LEFT);
			$gt = lang("drug_payment:grand_total_label");
			$val = number_format($grand_total, 2, ".", ",");
			$printer->text($this->dp_adjust_line($gt, $val));

			foreach ($type_payment_used as $key => $val) {
				if ($val > 0) {
					$val = number_format($val, 2, ".", ",");
					$printer->text($this->dp_adjust_line($key, $val));
				}
			}
			$printer->text("---------------------------------\n");

			$printer->setEmphasis(false);

			/* Footer */
			$printer->feed();
			$printer->setJustification(Printer::JUSTIFY_CENTER);
			$printer->text("Thank You\n{$this->user_auth->Nama_Singkat}\n");
			$printer->feed(2);
			$printer->text($date . "\n");

			/* Cut the receipt and open the cash drawer */
			$printer->cut();
			$printer->pulse();

			$printer->close();
			$response = array(
				"status" => "success",
				"message" => "Data Billing Berhasil Dicetak!",
				"code" => 200
			);
		} catch (Exception $e) {

			$response = array(
				"status" => "error",
				"message" => "Couldn't print to this printer: " . $e->getMessage(),
				"code" => 500
			);
		}

		print_r(json_encode($response, JSON_NUMERIC_CHECK));
		exit(0);
	}

	private function dp_adjust_line($text1, $text2, $sperator = " ")
	{
		$pwlen = 33; // printer max width lenght
		$strlen = strlen($text1 . $text2); // string length
		$space = $pwlen - $strlen;

		return sprintf("%s%s%s\n", $text1, str_repeat($sperator, $space), $text2);
	}

	public function lookup_merchan($is_ajax_request = false)
	{
		if ($this->input->is_ajax_request() || $is_ajax_request !== false) {
			$this->load->view('drug_payment/lookup/merchans', (isset($data) ? $data : NULL));
		}
	}

	public function lookup($is_ajax_request = false)
	{
		if ($this->input->is_ajax_request() || $is_ajax_request !== false) {
			$this->load->view('registrations/lookup/datatable');
		} else {
			$data = array(
				'page' => $this->page,
				'datatables' => TRUE,
				'form' => TRUE,
			);

			$this->template
				->set("heading", "Lookup Box")
				->set_breadcrumb(lang("drug_payment:page"), base_url("drug_payment"))
				->set_breadcrumb("Lookup Box")
				->build('registrations/lookup', (isset($data) ? $data : NULL));
		}
	}

	public function lookup_collection()
	{
		$this->datatable_collection(1);
	}

	public function datatable_collection()
	{
		$start = $this->input->get_post('start', true);
		$length = $this->input->get_post('length', true);
		$order = $this->input->get_post('order', true);
		$columns = $this->input->get_post('columns', true);
		$search = $this->input->get_post('search', true);
		$draw = $this->input->get_post('draw', true);

		$db_from = "{$this->drug_payment_m->table} a";
		$db_where = array();
		$db_like = array();

		$db_where['a.ObatBebas'] = 1;
		$db_where['a.Retur <>'] = 1;
		if ($this->input->get_post("date_from")) {
			$db_where['a.Tanggal >='] = $this->input->get_post("date_from");
		}

		if ($this->input->get_post("date_till")) {
			$db_where['a.Tanggal <='] = $this->input->get_post("date_till");
		}

		// preparing default
		if (isset($search['value']) && !empty($search['value'])) {
			$keywords = $this->db->escape_str($search['value']);

			$db_like[$this->db->escape_str("a.NoBukti")] = $keywords;
			$db_like[$this->db->escape_str("a.Jam")] = $keywords;
			$db_like[$this->db->escape_str("a.Keterangan")] = $keywords;
			$db_like[$this->db->escape_str("b.JenisKerjsama")] = $keywords;
			$db_like[$this->db->escape_str("c.Nama_Supplier")] = $keywords;
			$db_like[$this->db->escape_str("d.SectionName")] = $keywords;
		}

		//get total records
		$this->db->from($db_from);
		if (!empty($db_where)) {
			$this->db->where($db_where);
		}
		$records_total = $this->db->count_all_results();

		// get total filtered
		$this->db
			->from($db_from)
			->join("{$this->patient_type_m->table} b", "a.KerjasamaID = b.JenisKerjasamaID", "LEFT OUTER")
			->join("{$this->supplier_m->table} c", "a.DokterID = c.Kode_Supplier", "LEFT OUTER")
			->join("{$this->section_m->table} d", "a.SectionAsalID = d.SectionID", "LEFT OUTER")
			->join("{$this->customer_m->table} e", "a.KodePerusahaan = e.Kode_Customer", "LEFT OUTER");
		if (!empty($db_where)) {
			$this->db->where($db_where);
		}
		if (!empty($db_like)) {
			$this->db->group_start()->or_like($db_like)->group_end();
		}
		$records_filtered = $this->db->count_all_results();

		// get result filtered
		$db_select = <<<EOSQL
			a.NoBukti,
			a.Jam,			
			a.Keterangan,
			b.JenisKerjasama,
			c.Nama_Supplier,
			d.SectionName,
			e.Nama_Customer,
			a.ClosePayment			
			
EOSQL;

		$this->db
			->select($db_select)
			->from($db_from)
			->join("{$this->patient_type_m->table} b", "a.KerjasamaID = b.JenisKerjasamaID", "LEFT OUTER")
			->join("{$this->supplier_m->table} c", "a.DokterID = c.Kode_Supplier", "LEFT OUTER")
			->join("{$this->section_m->table} d", "a.SectionAsalID = d.SectionID", "LEFT OUTER")
			->join("{$this->customer_m->table} e", "a.KodePerusahaan = e.Kode_Customer", "LEFT OUTER");
		if (!empty($db_where)) {
			$this->db->where($db_where);
		}
		if (!empty($db_like)) {
			$this->db->group_start()->or_like($db_like)->group_end();
		}

		// ordering
		if (isset($order)) {
			$sort_column = $order[0]['column'];
			$sort_dir = $order[0]['dir'];

			if ($columns[$sort_column]['orderable'] == 'true') {
				$this->db
					->order_by($columns[intval($this->db->escape_str($sort_column))]['data'], $this->db->escape_str($sort_dir));
			}
		}

		// paging
		if (isset($start) && $length != '-1') {
			$this->db
				->limit($length, $start);
		}

		// get
		$result = $this->db
			->get()
			->result();

		// Output
		$output = array(
			'draw' => intval($draw),
			'recordsTotal' => $records_total,
			'recordsFiltered' => $records_filtered,
			'data' => array()
		);

		foreach ($result as $row) {
			/*$date = DateTime::createFromFormat("Y-m-d H:i:s.u", $row->TglReg);
			$time = DateTime::createFromFormat("Y-m-d H:i:s.u", $row->JamReg ); 
			
			$row->TglReg = $date->format('Y-m-d');
			$row->JamReg = $time->format('H:i:s');*/

			$output['data'][] = $row;
		}

		$this->template
			->build_json($output);
	}
}
