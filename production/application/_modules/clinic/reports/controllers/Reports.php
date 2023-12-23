<?php
if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\LabelAlignment;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Response\QrCodeResponse;

class Reports extends ADMIN_Controller
{
	protected $nameroutes = 'reports';

	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('reports');

		$this->load->helper('general_payment');
		$this->load->helper("report");
	}

	public function medical_records()
	{
		$data = [
			'nameroutes' => $this->nameroutes,
			'form' => TRUE,
			'datatables' => TRUE,
			'datatables_export' => TRUE,
			'datepicker' => TRUE,
			'navigation_minimized' => TRUE,
		];

		$this->template
			->build('reports/reports/medical_records/datatable', $data);
	}

	public function medical_records_collection()
	{
		$start = $this->input->get_post('start', true);
		$length = $this->input->get_post('length', true);
		$order = $this->input->get_post('order', true);
		$columns = $this->input->get_post('columns', true);
		$search = $this->input->get_post('search', true);
		$draw = $this->input->get_post('draw', true);
		$rujukan = $this->input->get_post('patien_rujukan', true);
		$patient_age = $this->input->get_post('patient_age', true);

		$db_where = [];
		$db_like = [];

		$db_where['a.Batal'] = 0;
		$db_where['a.Tanggal >='] = $this->input->get_post('date_from', true);
		$db_where['a.Tanggal <='] = $this->input->get_post('date_till', true);

		if (!empty($rujukan)) {
			$db_where['j.PxKeluar_Dirujuk'] = $rujukan;
		}

		if (!empty($patient_age)) {
			$db_where['DATEDIFF(hour, e.TglLahir,GETDATE())/24 <='] = $patient_age * 365;
		}


		// get result filtered
		$db_select = <<<EOSQL
			a.Tanggal, 
			a.RegNo,
			b.JenisKerjasamaID,
			b.JenisKerjasama,
			c.SectionName,
			d.Nama_Supplier AS NamaDokter,
			a.NRM,
			e.NamaPasien,
			e.JenisKelamin,
			e.TglLahir,
			DATEDIFF(hour, e.TglLahir,GETDATE())/8766 AS Umur,
			e.Alamat,
			e.RiwayatAlergi,
			f.Subjective,
			f.Objective,
			f.Assessment,
			f.Plan,
			g.Height,
			g.Weight,
			g.Temperature,
			convert(VARCHAR(10), g.Systolic) +'/'+ convert(VARCHAR(10), g.Diastolic) AS BloodPressure,
			g.HeartRate,
			g.RespiratoryRate,
			g.OxygenSaturation,
			g.Pain,
			i.Nama_Customer AS NamaCustomer,
			j.PxKeluar_Dirujuk,
			j.PxKeluar_DirujukKeterangan
			
EOSQL;
		$this->db
			->select($db_select)
			->from("SIMtrRJ a")
			->join("SIMmJenisKerjasama b", "a.JenisKerjasamaID = b.JenisKerjasamaID", "INNER")
			->join("SIMmSection c", "a.SectionID = c.SectionID", "INNER")
			->join("mSupplier d", "a.DokterID = d.Kode_Supplier", "INNER")
			->join("mPasien e", "a.NRM = e.NRM", "INNER")
			->join("SIMtrEMRSoapNotes f", "a.NoBukti = f.NoPemeriksaan", "INNER")
			->join("SIMtrEMRVitalSigns g", "a.NoBukti = g.NoPemeriksaan AND g.Parent = 1", "INNER")
			->join("SIMDCustomerKerjasama h", "a.CustomerKerjasamaID = h.CustomerKerjasamaID", "LEFT OUTER")
			->join("mCustomer i", "h.CustomerID = i.Customer_ID", "LEFT OUTER")
			->join("SIMtrRegistrasi j", "a.RegNo = j.NoReg", "LEFT OUTER");
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
			'recordsTotal' => count($result),
			'recordsFiltered' => count($result),
			'data' => array()
		);

		foreach ($result as $row) {
			$output['data'][] = $row;
		}

		$this->template
			->build_json($output);
	}

	public function group_by_icd()
	{
		$data = [
			'nameroutes' => $this->nameroutes,
			'form' => TRUE,
			'datatables' => TRUE,
			'datatables_export' => TRUE,
			'datepicker' => TRUE,
			'navigation_minimized' => TRUE,
		];

		$this->template
			->build('reports/reports/group_by_icd/datatable', $data);
	}

	public function group_by_icd_collection()
	{
		$start = $this->input->get_post('start', true);
		$length = $this->input->get_post('length', true);
		$order = $this->input->get_post('order', true);
		$columns = $this->input->get_post('columns', true);
		$search = $this->input->get_post('search', true);
		$draw = $this->input->get_post('draw', true);

		$db_where = [];
		$db_like = [];

		$db_where['c.Batal'] = 0;
		$db_where['c.Tanggal >='] = $this->input->get_post('date_from', true);
		$db_where['c.Tanggal <='] = $this->input->get_post('date_till', true);

		// get result filtered
		$db_select = <<<EOSQL
			b.KodeICD, 
			b.ICDName, 
			COUNT(b.KodeICD) Jumlah
			
EOSQL;
		$this->db
			->select($db_select)
			->from("SIMtrRJDiagnosaAwal a")
			->join("mICD b", "a.KodeICD = b.KodeICD", "INNER")
			->join("SIMtrRJ c", "a.NOBukti = c.NoBukti", "INNER")
			->group_by("b.KodeICD, b.ICDName")
			->order_by("Jumlah", "DESC");
		if (!empty($db_where)) {
			$this->db->where($db_where);
		}
		if (!empty($db_like)) {
			$this->db->group_start()->or_like($db_like)->group_end();
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
			'recordsTotal' => count($result),
			'recordsFiltered' => count($result),
			'data' => array()
		);

		foreach ($result as $row) {
			$output['data'][] = $row;
		}

		$this->template
			->build_json($output);
	}

	public function daily_registration()
	{
		$data = [
			'option_doctor' => option_doctor(),
			'nameroutes' => $this->nameroutes,
			'form' => TRUE,
			'datatables' => TRUE,
			'datatables_export' => TRUE,
			'datepicker' => TRUE,
			'navigation_minimized' => TRUE,
		];

		$this->template
			->title("Laporan Registrasi Harian")
			->build('reports/reports/daily_registration/datatable', $data);
	}

	public function daily_registration_collection()
	{
		$start = $this->input->get_post('start', true);
		$length = $this->input->get_post('length', true);
		$order = $this->input->get_post('order', true);
		$columns = $this->input->get_post('columns', true);
		$search = $this->input->get_post('search', true);
		$draw = $this->input->get_post('draw', true);

		$db_where = [];
		$db_like = [];

		$db_where['a.Batal'] = 0;
		$db_where['a.Tanggal'] = $this->input->get_post('date', true);
		$db_where['a.DokterID'] = $this->input->get_post('doctor', true);

		// get result filtered
		$db_select = <<<EOSQL
			a.NoBukti, 
			a.Tanggal, 
			b.JenisKerjasama,
			c.SectionName,
			d.Nama_Supplier AS NamaDokter,
			a.NRM,
			e.NamaPasien,
			e.JenisKelamin,
			e.TglLahir,
			DATEDIFF(hour, e.TglLahir,GETDATE())/8766 AS Umur,
			f.Assessment,
			f.Plan,
			i.Nama_Customer AS NamaCustomer
			
EOSQL;
		$this->db
			->select($db_select)
			->from("SIMtrRJ a")
			->join("SIMmJenisKerjasama b", "a.JenisKerjasamaID = b.JenisKerjasamaID", "INNER")
			->join("SIMmSection c", "a.SectionID = c.SectionID", "INNER")
			->join("mSupplier d", "a.DokterID = d.Kode_Supplier", "INNER")
			->join("mPasien e", "a.NRM = e.NRM", "INNER")
			->join("SIMtrEMRSoapNotes f", "a.NoBukti = f.NoPemeriksaan", "INNER")
			->join("SIMDCustomerKerjasama h", "a.CustomerKerjasamaID = h.CustomerKerjasamaID", "LEFT OUTER")
			->join("mCustomer i", "h.CustomerID = i.Customer_ID", "LEFT OUTER");
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
			'recordsTotal' => count($result),
			'recordsFiltered' => count($result),
			'data' => array()
		);

		foreach ($result as $row) {
			$row->Tindakan = $this->db
				->select('b.JasaName, a.Tarif')
				->where('a.NoBukti', $row->NoBukti)
				->from('SIMtrRJTransaksi a')
				->join('SIMmListJasa b', 'a.JasaID = b.JasaID', 'INNER')
				->get()->result();

			$output['data'][] = $row;
		}

		$this->template
			->build_json($output);
	}

	public function patient_certificate($NoReg = NULL)
	{
		if(!empty($NoReg))
		{
			$item = general_payment_helper::get_item($NoReg, 0);
		}

		if ($this->input->is_ajax_request()) {
			$data = array(
				'item' => (object)$item,
				'nameroutes' => $this->nameroutes,
				"is_ajax_request" => TRUE,
				"is_modal" => TRUE,
				"lookup_registration" => base_url("reports/reports/lookup_registration"),
				"lookup_supplier" => base_url("cashier/general-payment/lookup_supplier_cashier"),
			);

			$this->load->view(
				'helper/modal/create_edit',
				array('form_child' => $this->load->view('reports/reports/patient_certificate/datatable', $data, true))
			);
		} else {
			$data = array(
				"item" => @$item,
				'nameroutes' => $this->nameroutes,
				"form" => TRUE,
				"datatables" => TRUE,
				"update_process_payment" => base_url('cashier/general-payment/update_process_payment'),
				"lookup_registration" => base_url("reports/reports/lookup_registration"),
				"lookup_supplier" => base_url("cashier/general-payment/lookup_supplier_cashier"),
			);

			$this->template
				->set("heading", lang("reports:pay_heading"))
				->set_breadcrumb("Surat Keterangan Pasien", base_url("reports/patient_certificate"))
				->set_breadcrumb(lang("reports:pay_heading"))
				->build('reports/reports/patient_certificate/datatable', $data);
		}
	}

	public function export_patient_certificate($NoReg)
	{
		$NoReg = str_replace("/", "-", $NoReg);
		$item = general_payment_helper::get_item($NoReg, 0);
		$post = $this->input->post("f");
		// print_r($item);exit;
		$date_start = new DateTime(@$post['date_start']);
		$date_end   = new DateTime(@$post['date_end']);
		$hari = $date_end->diff($date_start)->days;

		$data = [
			"item"	=> $item,
			"post"	=> $post,
			"date_start" => $date_start,
			"date_end" => $date_end,
			"hari"	=> $hari,
		];

		// swab antigen process
		if ($post['report_type'] == "4") {
			
			$tes_result = $post['Antigen'] == "negatif" ? "0" : "1";
			
			// 0 encryted key ## 1 No Bukti ## 2 Nama Lengkap ## 3 No Identitas ## 4 Jenis Kelamin ## 5 Umur ## 6 Alamat ## 7 Waktu Regis ## 8 ID Dokter ## 9 negatif 0 / positif 1
			// $simple_string = "AvHf7xmQ1#210608INVRJ-000156#Putu May Rama Wisesa#9803050484780003#L#99#Perum Taman Mulia Jalan lobster 16#2021-06-15 16:20:22#78#0";
			$Dokter = $this->db->where("Kode_Supplier", $item->DokterRawatID)->get("mSupplier")->row();
			$simple_string = sprintf("%s#%s#%s#%s#%s#%s#%s#%s#%s#%s", 's@tU23EmpaTt', $item->NoBukti, $item->NamaPasien, $item->NoIdentitas, $item->JenisKelamin, $item->UmurThn, $item->Alamat, $item->JamReg, $Dokter->Supplier_ID, $tes_result);
			
			$ciphering = "AES-256-CBC";
			$options = 0;
			$encryption_iv = '1dU@3456tuJuh890';
			$encryption_key = openssl_digest('AvHf7xmQ1', 'MD5', TRUE);
			$encryption = openssl_encrypt($simple_string, $ciphering,$encryption_key, $options, $encryption_iv);
			$encryption = base64_encode($encryption);
			$encryption = str_replace("=", "_", $encryption);

			//Isi dari QRCode Saat discan
			$qrCode = new QrCode();
			$inputFileName = FCPATH.'../../themes/intuitive/assets/img/swab.png';
			// print_r($encryption);exit;			
			if (file_exists($inputFileName))
			{
				unlink($inputFileName);
			}
			// Set Text
			$qrCode->setText("http://sanatasystem.com/cetak/".$encryption);
			$qrCode->setWriterByName('png');
			$qrCode->setMargin(5);
			$qrCode->setEncoding('UTF-8');
			$qrCode->setErrorCorrectionLevel(new ErrorCorrectionLevel(ErrorCorrectionLevel::HIGH));
			$qrCode->setForegroundColor(['r' => 0, 'g' => 0, 'b' => 0, 'a' => 0]);
			$qrCode->setBackgroundColor(['r' => 255, 'g' => 255, 'b' => 255, 'a' => 0]);
			$qrCode->setLogoPath(base_url("resource/images/logos/qrcode_logo.png"));
			$qrCode->setLogoSize(100, 100);
			$qrCode->writeFile($inputFileName);

		}
		
		if ($post['report_type'] == "1") {
			$html_content =  $this->load->view("reports/reports/patient_certificate/print/surat_keterangan_sehat", $data, TRUE);
		} else if ($post['report_type'] == "2") {
			$html_content =  $this->load->view("reports/reports/patient_certificate/print/surat_keterangan_sakit", $data, TRUE);
		} else if ($post['report_type'] == "3") {
			$html_content =  $this->load->view("reports/reports/patient_certificate/print/surat_keterangan_tidak_buta_warna", $data, TRUE);
		} else if ($post['report_type'] == "5") {
			$html_content =  $this->load->view("reports/reports/patient_certificate/print/surat_rujukan", $data, TRUE);
		} else if ($post['Antigen'] == "negatif") {
			$html_content =  $this->load->view("reports/reports/patient_certificate/print/surat_keterangan_swab_negatif", $data, TRUE);
		} else {
			$html_content =  $this->load->view("reports/reports/patient_certificate/print/surat_keterangan_swab_positif", $data, TRUE);
		}
		// print_r($html_content);exit;
		$file_name = "Surat Keterangan Pasien.pdf";
		$this->load->helper("export");
		
		// print_r('aw');exit;
		export_helper::generate_pdf($html_content, $file_name, '', $margin_bottom = 5.0, $header = NULL, $margin_top = 10.0, $orientation = 'P', 10, 10, '', $footer);
		exit(0);
	}

	public function lookup_registration($is_ajax_request = false)
	{
		if ($this->input->is_ajax_request() || $is_ajax_request !== false) {
			$data = array(
				"view_datatable" => $this->lookup_registration_datatable(true)
			);

			$this->load->view('reports/reports/patient_certificate/lookup/registration', $data);
		}
	}

	public function lookup_registration_datatable($is_ajax_request = false)
	{

		if ($this->input->is_ajax_request() || $is_ajax_request !== false) {
			return $this->load->view('reports/reports/patient_certificate/lookup/datatable_registration', array(), TRUE);
		}
	}

	public function lookup_patient()
	{
		if ($this->input->is_ajax_request() || $is_ajax_request !== false) {
			$this->load->view('reports/reports/patient_certificate/lookup/datatable_patient_certificate');
		}
	}

	public function lookup_patient_certificate($is_ajax_request = false)
	{
		if ($this->input->is_ajax_request() || $is_ajax_request !== false) {
			$data = array(
				"option_patient_certificate" => $this->get_model()->get_option_patient_certificate(),
			);

			$this->load->view('reports/lookup/datatable_patient_certificate', $data);
		}
	}

	public function toddler_visiting()
	{
		$data = [
			'nameroutes' => $this->nameroutes,
			'collection_url' => base_url("{$this->nameroutes}/toddler_visiting_collection"),
			'form' => TRUE,
			'datatables' => TRUE,
			'datatables_export' => TRUE,
			'datepicker' => TRUE,
			'navigation_minimized' => TRUE,
		];

		$this->template
			->title("Laporan Kunjungan Balita Umur (0-60 Bulan)")
			->build('reports/reports/toddler_visiting/datatable', $data);
	}

	public function toddler_visiting_collection()
	{
		$start = $this->input->get_post('start', true);
		$length = $this->input->get_post('length', true);
		$order = $this->input->get_post('order', true);
		$columns = $this->input->get_post('columns', true);
		$search = $this->input->get_post('search', true);
		$draw = $this->input->get_post('draw', true);

		$db_where = [];
		$db_like = [];

		$db_where["(a.Umur_Th * 12 + a.Umur_Bln) <="] = 60;
		$db_where['a.Batal'] = 0;
		$priod = DateTime::createFromFormat('Y-m', $this->input->get_post('date_from', true));
		$db_where['a.Tanggal >='] = $priod->format('Y-m-01');
		$db_where['a.Tanggal <='] = $priod->format('Y-m-t');

		// get result filtered
		$db_select = <<<EOSQL
			a.Tanggal, 
			a.RegNo,
			b.JenisKerjasamaID,
			b.JenisKerjasama,
			c.SectionName,
			d.Nama_Supplier AS NamaDokter,
			a.NRM,
			e.NamaPasien,
			e.Phone,
			e.NoIdentitas,
			e.JenisKelamin,
			e.TglLahir,
			DATEDIFF(hour, e.TglLahir,GETDATE())/8766 AS Umur,
			e.Alamat,
			e.RiwayatAlergi,
			e.PenanggungNama,
			e.PenanggungPhone,
			e.PenanggungKTP,
			a.Therapi,
			f.Subjective,
			f.Objective,
			f.Assessment,
			f.Plan,
			g.Height,
			g.Weight,
			g.Temperature,
			convert(VARCHAR(10), g.Systolic) +'/'+ convert(VARCHAR(10), g.Diastolic) AS BloodPressure,
			g.HeartRate,
			g.RespiratoryRate,
			g.OxygenSaturation,
			g.Pain,
			i.Nama_Customer AS NamaCustomer
			
EOSQL;
		$this->db
			->select($db_select)
			->from("SIMtrRJ a")
			->join("SIMmJenisKerjasama b", "a.JenisKerjasamaID = b.JenisKerjasamaID", "INNER")
			->join("SIMmSection c", "a.SectionID = c.SectionID", "INNER")
			->join("mSupplier d", "a.DokterID = d.Kode_Supplier", "INNER")
			->join("mPasien e", "a.NRM = e.NRM", "INNER")
			->join("SIMtrEMRSoapNotes f", "a.NoBukti = f.NoPemeriksaan", "INNER")
			->join("SIMtrEMRVitalSigns g", "a.NoBukti = g.NoPemeriksaan AND g.Parent = 1", "INNER")
			->join("SIMDCustomerKerjasama h", "a.CustomerKerjasamaID = h.CustomerKerjasamaID", "LEFT OUTER")
			->join("mCustomer i", "h.CustomerID = i.Customer_ID", "LEFT OUTER")
			->join("SIMtrRegistrasi j", "a.RegNo = j.NoReg", "INNER");
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
			'recordsTotal' => count($result),
			'recordsFiltered' => count($result),
			'data' => array()
		);

		foreach ($result as $row) {
			$output['data'][] = $row;
		}

		$this->template
			->build_json($output);
	}

	public function kb_visiting()
	{
		$data = [
			'nameroutes' => $this->nameroutes,
			'collection_url' => base_url("{$this->nameroutes}/kb_visiting_collection"),
			'form' => TRUE,
			'datatables' => TRUE,
			'datatables_export' => TRUE,
			'datepicker' => TRUE,
			'navigation_minimized' => TRUE,
		];

		$this->template
			->title("Laporan Kunjungan Peserta KB")
			->build('reports/reports/kb_visiting/datatable', $data);
	}

	public function kb_visiting_collection()
	{
		$start = $this->input->get_post('start', true);
		$length = $this->input->get_post('length', true);
		$order = $this->input->get_post('order', true);
		$columns = $this->input->get_post('columns', true);
		$search = $this->input->get_post('search', true);
		$draw = $this->input->get_post('draw', true);

		$db_where = [];
		$db_like = [];

		$db_where['a.Batal'] = 0;
		$db_where["a.SectionID"] = 'SECT0017';
		$priod = DateTime::createFromFormat('Y-m', $this->input->get_post('date_from', true));
		$db_where['a.Tanggal >='] = $priod->format('Y-m-01');
		$db_where['a.Tanggal <='] = $priod->format('Y-m-t');

		// get result filtered
		$db_select = <<<EOSQL
			a.Tanggal, 
			a.RegNo,
			b.JenisKerjasamaID,
			b.JenisKerjasama,
			c.SectionName,
			d.Nama_Supplier AS NamaDokter,
			a.NRM,
			e.NamaPasien,
			e.Phone,
			e.NoIdentitas,
			e.JenisKelamin,
			e.TglLahir,
			DATEDIFF(hour, e.TglLahir,GETDATE())/8766 AS Umur,
			e.Alamat,
			e.RiwayatAlergi,
			e.PenanggungNama,
			e.PenanggungPhone,
			e.PenanggungKTP,
			a.Therapi,
			f.Subjective,
			f.Objective,
			f.Assessment,
			f.Plan,
			g.Height,
			g.Weight,
			g.Temperature,
			convert(VARCHAR(10), g.Systolic) +'/'+ convert(VARCHAR(10), g.Diastolic) AS BloodPressure,
			g.HeartRate,
			g.RespiratoryRate,
			g.OxygenSaturation,
			g.Pain,
			i.Nama_Customer AS NamaCustomer
			
EOSQL;
		$this->db
			->select($db_select)
			->from("SIMtrRJ a")
			->join("SIMmJenisKerjasama b", "a.JenisKerjasamaID = b.JenisKerjasamaID", "INNER")
			->join("SIMmSection c", "a.SectionID = c.SectionID", "INNER")
			->join("mSupplier d", "a.DokterID = d.Kode_Supplier", "INNER")
			->join("mPasien e", "a.NRM = e.NRM", "INNER")
			->join("SIMtrEMRSoapNotes f", "a.NoBukti = f.NoPemeriksaan", "INNER")
			->join("SIMtrEMRVitalSigns g", "a.NoBukti = g.NoPemeriksaan AND g.Parent = 1", "INNER")
			->join("SIMDCustomerKerjasama h", "a.CustomerKerjasamaID = h.CustomerKerjasamaID", "LEFT OUTER")
			->join("mCustomer i", "h.CustomerID = i.Customer_ID", "LEFT OUTER")
			->join("SIMtrRegistrasi j", "a.RegNo = j.NoReg", "INNER");
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
			'recordsTotal' => count($result),
			'recordsFiltered' => count($result),
			'data' => array()
		);

		foreach ($result as $row) {
			$output['data'][] = $row;
		}

		$this->template
			->build_json($output);
	}

	public function productive_age_by_gender()
	{
		$data = [
			'nameroutes' => $this->nameroutes,
			'collection_url' => base_url("{$this->nameroutes}/productive_age_by_gender_collection"),
			'form' => TRUE,
			'datatables' => TRUE,
			'datatables_export' => TRUE,
			'datepicker' => TRUE,
			'navigation_minimized' => TRUE,
		];

		$this->template
			->title("Laporan Kunjungan Usia Produktif per Jenis Kelamin")
			->build('reports/reports/productive_age_by_gender/datatable', $data);
	}

	public function productive_age_by_gender_collection()
	{
		$start = $this->input->get_post('start', true);
		$length = $this->input->get_post('length', true);
		$order = $this->input->get_post('order', true);
		$columns = $this->input->get_post('columns', true);
		$search = $this->input->get_post('search', true);
		$draw = $this->input->get_post('draw', true);

		$db_where = [];
		$db_like = [];

		$db_where['a.Batal'] = 0;
		$db_where["(a.Umur_Th * 12 + a.Umur_Bln) >="] = 180;
		$db_where["(a.Umur_Th * 12 + a.Umur_Bln) <="] = 780;
		$priod = DateTime::createFromFormat('Y-m', $this->input->get_post('date', true));
		$db_where['a.Tanggal >='] = $priod->format('Y-m-01');
		$db_where['a.Tanggal <='] = $priod->format('Y-m-t');

		// get result filtered
		$db_select = <<<EOSQL
			SUM(
				CASE 
					WHEN a.JenisKelamin = 'M'
					THEN 1
					ELSE 0 
				END
			) AS JumlahPria,
			SUM(
				CASE 
					WHEN a.JenisKelamin = 'F'
					THEN 1
					ELSE 0 
				END
			) AS JumlahWanita,
			SUM(
				CASE 
					WHEN b.PxKeluar_Dirujuk = 1
					THEN 1
					ELSE 0 
				END
			) AS JumlahDirujuk
			
EOSQL;
		$this->db
			->select($db_select)
			->from("SIMtrRJ a")
			->join("SIMtrRegistrasi b", "a.RegNo = b.NoReg", "INNER");

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
			'recordsTotal' => count($result),
			'recordsFiltered' => count($result),
			'data' => array()
		);

		foreach ($result as $row) {
			$output['data'][] = $row;
		}

		$this->template
			->build_json($output);
	}

	public function get_monthly_section_visit()
	{
		if ($this->input->is_ajax_request()) :
			$type = $this->input->get('type');
			$date = $this->input->get('date');

			switch ($type):
				case 'month':
					$response = report_helper::get_monthly_section_visit($date);
					break;
				case 'year':
					$response = report_helper::get_yearly_section_visit($date);
					break;
			endswitch;

			response_json($response);
		endif;
	}

	public function get_monthly_type_visit()
	{
		if ($this->input->is_ajax_request()) :
			$type = $this->input->get('type');
			$date = $this->input->get('date');

			switch ($type):
				case 'month':
					$response = report_helper::get_monthly_type_visit($date);
					break;
				case 'year':
					$response = report_helper::get_yearly_type_visit($date);
					break;
			endswitch;

			response_json($response);
		endif;
	}

	public function patient()
	{
		if ($this->input->post()) {
			report_helper::export_all_patient();
		}

		$data = [
			'nameroutes' => $this->nameroutes,
			'form' => TRUE,
			'datatables' => TRUE,
			'datatables_export' => TRUE,
			'datepicker' => TRUE,
			'navigation_minimized' => TRUE,
			'option_doctor' => option_doctor(),
		];

		$this->template
			->build('reports/reports/patient/datatable', $data);
	}

	public function patient_collection()
	{
		$start = $this->input->get_post('start', true);
		$length = $this->input->get_post('length', true);
		$order = $this->input->get_post('order', true);
		$columns = $this->input->get_post('columns', true);
		$search = $this->input->get_post('search', true);
		$draw = $this->input->get_post('draw', true);

		$db_from = "mPasien a";
		$db_where = array();
		$db_like = array();

		// preparing default
		if (isset($search['value']) && !empty($search['value'])) {
			$keywords = $this->db->escape_str($search['value']);

			$db_like[$this->db->escape_str("a.NRM")] = $keywords;
			$db_like[$this->db->escape_str("a.NoKartu")] = $keywords;
			$db_like[$this->db->escape_str("a.NamaPasien")] = $keywords;
			$db_like[$this->db->escape_str("a.JenisKelamin")] = $keywords;
			$db_like[$this->db->escape_str("a.Agama")] = $keywords;
			$db_like[$this->db->escape_str("a.TglLahir")] = $keywords;
			$db_like[$this->db->escape_str("a.NoIdentitas")] = $keywords;
			$db_like[$this->db->escape_str("a.TempatLahir")] = $keywords;
			$db_like[$this->db->escape_str("a.Alamat")] = $keywords;
			$db_like[$this->db->escape_str("a.Phone")] = $keywords;
			$db_like[$this->db->escape_str("a.Email")] = $keywords;
			$db_like[$this->db->escape_str("a.Pekerjaan")] = $keywords;
			$db_like[$this->db->escape_str("a.PenanggungNama")] = $keywords;
			$db_like[$this->db->escape_str("a.PenanggungAlamat")] = $keywords;
			$db_like[$this->db->escape_str("a.PenanggungKTP")] = $keywords;
			$db_like[$this->db->escape_str("a.PenanggungPhone")] = $keywords;
			$db_like[$this->db->escape_str("a.PenanggungHubungan")] = $keywords;
		}

		// get total records
		$this->db->from($db_from);
		if (!empty($db_where)) {
			$this->db->where($db_where);
		}
		$records_total = $this->db->count_all_results();

		// get total filtered
		$this->db
			->from($db_from);

		if (!empty($db_where)) {
			$this->db->where($db_where);
		}
		if (!empty($db_like)) {
			$this->db->group_start()->or_like($db_like)->group_end();
		}
		$records_filtered = $this->db->count_all_results();


		// get result filtered
		$db_select = <<<EOSQL
			a.*
			
			
EOSQL;

		$this->db
			->select($db_select)
			->from($db_from)
			->join("SIMmJenisKerjasama b", "a.JenisKerjasamaID = b.JenisKerjasamaID", "LEFT OUTER")
			->join("SIMdCustomerKerjasama c", "a.CustomerKerjasamaID = c.CustomerKerjasamaID", "LEFT OUTER")
			->join("mCustomer d", "c.CustomerID = d.Customer_ID", "LEFT OUTER")
			->join("mPropinsi f", "a.PropinsiID = f.Propinsi_ID", "LEFT OUTER")
			->join("mKabupaten g", "a.KabupatenID = g.Kode_Kabupaten", "LEFT OUTER")
			->join("mKecamatan h", "a.KecamatanID = h.KecamatanID", "LEFT OUTER")
			->join("mDesa i", "a.DesaID = i.DesaID", "LEFT OUTER")
			->join("mBanjar j", "a.BanjarID = j.BanjarID", "LEFT OUTER")
			->join("mNationality k", "a.NationalityID = k.NationalityID", "LEFT OUTER");
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
			$output['data'][] = $row;
		}

		$this->template
			->build_json($output);
	}

	public function average_age_patient()
	{
		$data = [
			'nameroutes' => $this->nameroutes,
			'form' => TRUE,
			'datatables' => TRUE,
			'datatables_export' => TRUE,
			'datepicker' => TRUE,
			'navigation_minimized' => TRUE,
		];
		$this->template
				->set( "heading", "Laporan Jumlah Pasien" )
				->set_breadcrumb( "Laporan Jumlah Pasien" )
				->build("reports/reports/average_age_patient/index", $data);
	}

	public function average_age_patient_collection()
	{
		$start = $this->input->get_post('start', true);
		$length = $this->input->get_post('length', true);
		$order = $this->input->get_post('order', true);
		$columns = $this->input->get_post('columns', true);
		$search = $this->input->get_post('search', true);
		$draw = $this->input->get_post('draw', true);
		
		// get result
		$AnakFemale = $this->db
			->select()
			->from("VW_Registrasi a")
			->where(['a.UmurThn <=' => 18,
					'a.Batal' => 0,
					'StatusPeriksa' => 'CO', 
					'JenisKelamin' => 'F',
					'TglReg >=' => $this->input->get_post('date_from', true),
					'TglReg <=' => $this->input->get_post('date_till', true)])
			->get()
			->result();
		
		$AnakMale = $this->db
			->select()
			->from("VW_Registrasi a")
			->where(['a.UmurThn <=' => 18,
					'a.Batal' => 0,
					'StatusPeriksa' => 'CO', 
					'JenisKelamin' => 'M',
					'TglReg >=' => $this->input->get_post('date_from', true),
					'TglReg <=' => $this->input->get_post('date_till', true)])
			->get()
			->result();
		
		$DewasaFemale= $this->db
			->select()
			->from("VW_Registrasi a")
			->where(['a.UmurThn >' => 18, 
					'a.UmurThn <' => 60, 
					'a.Batal' => 0,
					'StatusPeriksa' => 'CO', 
					'JenisKelamin' => 'F',
					'TglReg >=' => $this->input->get_post('date_from', true),
					'TglReg <=' => $this->input->get_post('date_till', true)])
			->get()
			->result();
		
		$DewasaMale = $this->db
			->select()
			->from("VW_Registrasi a")
			->where(['a.UmurThn >' => 18, 
					'a.UmurThn <' => 60, 
					'a.Batal' => 0,
					'StatusPeriksa' => 'CO', 
					'JenisKelamin' => 'M',
					'TglReg >=' => $this->input->get_post('date_from', true),
					'TglReg <=' => $this->input->get_post('date_till', true)])
			->get()
			->result();
		
		$LansiaFemale = $this->db
			->select()
			->from("VW_Registrasi a")
			->where(['a.UmurThn >=' => 60, 
					'a.Batal' => 0,
					'StatusPeriksa' => 'CO', 
					'JenisKelamin' => 'F',
					'TglReg >=' => $this->input->get_post('date_from', true),
					'TglReg <=' => $this->input->get_post('date_till', true)])
			->get()
			->result();

		$LansiaMale = $this->db
			->select()
			->from("VW_Registrasi a")
			->where(['a.UmurThn >=' => 60, 
					'a.Batal' => 0,
					'StatusPeriksa' => 'CO', 
					'JenisKelamin' => 'M',
					'TglReg >=' => $this->input->get_post('date_from', true),
					'TglReg <=' => $this->input->get_post('date_till', true)])
			->get()
			->result();

		// paging
		if (isset($start) && $length != '-1') {
			$this->db
				->limit($length, $start);
		}

		// Output
		$output = array(
			'draw' => intval($draw),
			'recordsTotal' => 6,
			'recordsFiltered' => 6,
			'data' => array()
		);
		$output['data'][] = (object) [
			'No' => 1,
			'Nama' => 'Lansia Laki-Laki',
			'Total' => '',
			'Jumlah' => count($LansiaMale)
		];
		$output['data'][] = (object) [
			'No' => 2,
			'Nama' => 'Lansia Perempuan',
			'Total' => '',
			'Jumlah' => count($LansiaFemale)
		];
		$output['data'][] = (object) [
			'No' => 3,
			'Nama' => 'Dewasa Laki-Laki',
			'Total' => '',
			'Jumlah' => count($DewasaMale)
		];
		$output['data'][] = (object) [
			'No' => 4,
			'Nama' => 'Dewasa Perempuan',
			'Total' => '',
			'Jumlah' => count($DewasaFemale)
		];
		$output['data'][] = (object) [
			'No' => 5,
			'Nama' => 'Anak Laki-Laki',
			'Total' => '',
			'Jumlah' => count($AnakMale)
		];
		$output['data'][] = (object) [
			'No' => 6,
			'Nama' => 'Anak Perempuan',
			'Total' => '',
			'Jumlah' => count($AnakFemale)
		];
		$output['data'][] = (object) [
			'No' => '',
			'Nama' => '',
			'Total' => 'Total Pasien',
			'Jumlah' => count($LansiaFemale) + count($DewasaFemale) + count($AnakFemale) + count($LansiaMale) + count($DewasaMale) + count($AnakMale)
		];

		$this->template
			->build_json($output);
	}

	public function export()
	{
		if ($this->input->post()) {
			// get result
			$AnakFemale = $this->db
			->select()
			->from("VW_Registrasi a")
			->where(['a.UmurThn <=' => 18,
			'a.Batal' => 0,
			'StatusPeriksa' => 'CO', 
			'JenisKelamin' => 'F',
			])				
			->get()
			->result();
			
			$AnakMale = $this->db
			->select()
			->from("VW_Registrasi a")
			->where(['a.UmurThn <=' => 18,
			'a.Batal' => 0,
			'StatusPeriksa' => 'CO', 
			'JenisKelamin' => 'M',
			])				
			->get()
			->result();
			
			$DewasaFemale= $this->db
			->select()
			->from("VW_Registrasi a")
			->where(['a.UmurThn >' => 18, 
			'a.UmurThn <' => 60, 
			'a.Batal' => 0,
			'StatusPeriksa' => 'CO', 
			'JenisKelamin' => 'F',
			])				
			->get()
			->result();
			
			$DewasaMale = $this->db
			->select()
			->from("VW_Registrasi a")
			->where(['a.UmurThn >' => 18, 
			'a.UmurThn <' => 60, 
			'a.Batal' => 0,
			'StatusPeriksa' => 'CO', 
			'JenisKelamin' => 'M',
			])				
			->get()
			->result();
			
			$LansiaFemale = $this->db
			->select()
			->from("VW_Registrasi a")
			->where(['a.UmurThn >=' => 60, 
			'a.Batal' => 0,
			'StatusPeriksa' => 'CO', 
			'JenisKelamin' => 'F',
			])				
			->get()
			->result();

			$LansiaMale = $this->db
				->select()
				->from("VW_Registrasi a")
				->where(['a.UmurThn >=' => 60, 
				'a.Batal' => 0,
						'StatusPeriksa' => 'CO', 
						'JenisKelamin' => 'M',
						])				
						->get()
						->result();
						
						// Output
						
						$output['data'][] = (object) [
							'No' => 1,
							'Nama' => 'Lansia Laki-Laki',
							'Total' => '',
							'Jumlah' => count($LansiaMale)
			];
			$output['data'][] = (object) [
				'No' => 2,
				'Nama' => 'Lansia Perempuan',
				'Total' => '',
				'Jumlah' => count($LansiaFemale)
			];
			$output['data'][] = (object) [
				'No' => 3,
				'Nama' => 'Dewasa Laki-Laki',
				'Total' => '',
				'Jumlah' => count($DewasaMale)
			];
			$output['data'][] = (object) [
				'No' => 4,
				'Nama' => 'Dewasa Perempuan',
				'Total' => '',
				'Jumlah' => count($DewasaFemale)
			];
			$output['data'][] = (object) [
				'No' => 5,
				'Nama' => 'Anak Laki-Laki',
				'Total' => '',
				'Jumlah' => count($AnakMale)
			];
			$output['data'][] = (object) [
				'No' => 6,
				'Nama' => 'Anak Perempuan',
				'Total' => '',
				'Jumlah' => count($AnakFemale)
			];
			// print_r($output);exit;
			report_helper::export_patient_list($output);
			
			exit(0);
		}
			
			show_404();
			
		}
}
