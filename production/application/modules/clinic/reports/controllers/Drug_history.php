<?php
if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Drug_history extends ADMIN_Controller
{
	protected $nameroutes = 'reports/drug_history';

	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('reports');
		$this->load->model([
			"patient_type_model",
			"section_model",
			"patient_model",
			"desa_model",
			"class_model"
		]);
		$this->load->helper('general_payment');

		$this->page = 'Laporan';
		$this->template->title( $this->page . ' - ' . $this->config->item('company_name') );
	}

	public function index()
	{
		if( $this->input->is_ajax_request() )
		{
			echo "<script language=\"javascript\">window.location=\"".base_url("reports/drug_history")."\";</script>";
			exit();
		} else
		{
			redirect( "reports/drug_history/dialog" );
		}
	}

	public function dialog ( $NoReg = NULL, $is_ajax = FALSE )
	{
		if(!empty($NoReg))
		{
			$item = general_payment_helper::get_item($NoReg, 0);
		}   
		// print_r($item);exit;
		
		if( $this->input->is_ajax_request() || $is_ajax )
		{
			$data = array(
				'item' => (object)$item,
				'nameroutes' => $this->nameroutes,
				"is_ajax_request" => TRUE,
				"is_modal" => TRUE,
				"datepicker" => true,
				"lookup_registration" => base_url("reports/drug_history/lookup_registration"),
				"lookup_supplier" => base_url("cashier/general-payment/lookup_supplier_cashier"),
				"url_export" => base_url("reports/drug_history/export/$NoReg"),
			);

			$this->load->view(
				"reports/drug_history/modal/dialog",
				array("form_child" => $this->load->view("reports/drug_history/dialog", $data, true))
			);
		} else
		{
			$data = array(
				"item" => @$item,
				'nameroutes' => $this->nameroutes,
				"datepicker" => true,
				"form" => true,
				"datatables" => TRUE,
				"update_process_payment" => base_url('cashier/general-payment/update_process_payment'),
				"lookup_registration" => base_url("reports/drug_history/lookup_registration"),
				"lookup_supplier" => base_url("cashier/general-payment/lookup_supplier_cashier"),
				"url_export" => base_url("reports/drug_history/export/$NoReg"),
			);

			$this->template
				->set( "heading", $this->page )
				->set_breadcrumb( $this->page, base_url("reports/drug_history") )
				->set_breadcrumb( 'Registrasi Pasien' )
				->build('reports/reports/drug_history/dialog', $data );
		}
	}

	public function export($NoReg)
	{
		$NoReg = str_replace("/", "-", $NoReg);
		$item = general_payment_helper::get_item($NoReg, 0);
		// print_r($NoReg);exit;
		if ( $this->input->post() )
		{
			$this->load->helper( "export" );
			$this->load->helper( "report" );
			
			switch ( $this->input->post("export_to") ) :
				case "pdf" :
					$this->export_pdf($NoReg);
				break;	
				case "excel" :
					$this->export_excel();
				break;
			endswitch;
		}
	}

	private function export_pdf($NoReg)
	{
		
		if ($this->input->post())
		{
			// $NoReg = str_replace("/", "-", $NoReg);
			// $item = general_payment_helper::get_item($NoReg, 0);
			// print_r($item);exit;
			$post_data = (object) $this->input->post("f");
			$collection = report_helper::get_drug_history( $NoReg );	
			// print_r($collection);exit;
			$data = array(
						"post_data" => $post_data,	
						"collection" => $collection,
					);
			// print_r($data);exit;
			$html_content =  $this->load->view( "reports/reports/drug_history/export/pdf", $data, TRUE ); 
			$footer = 'Laporan Registrasi Pasien'."&nbsp; : &nbsp;".date("d M Y")."&nbsp;".date("H:i:s");
			
			$file_name = 'Laporan Rekam Medis Obat Pasien';		
			
			export_helper::generate_pdf( $html_content, $file_name, $footer , $margin_bottom = 5, $header = NULL, $margin_top = 2, $orientation = 'L', $margin_left = 8, $margin_right = 8);
			print_r($html_content);exit;
			
	
			exit(0);
		}
		
		redirect("{$this->nameroutes}/dialog");

	}

	private function export_excel()
	{
		if ($this->input->post())
		{
			$post_data = (object) $this->input->post('f');
			report_helper::export_excel_registration_patient_types($post_data->date_start, $post_data->date_end, $post_data->tipe_pasien, $post_data->section );
			
			
			exit(0);
		}

	}

	public function lookup_registration($is_ajax_request = false)
	{
		if ($this->input->is_ajax_request() || $is_ajax_request !== false) {
			$data = array(
				"view_datatable" => $this->lookup_registration_datatable(true)
			);

			$this->load->view('reports/reports/drug_history/lookup/registration', $data);
		}
	}

	public function lookup_registration_datatable($is_ajax_request = false)
	{

		if ($this->input->is_ajax_request() || $is_ajax_request !== false) {
			return $this->load->view('reports/reports/drug_history/lookup/datatable_registration', array(), TRUE);
		}
	}

	public function lookup_registration_collection()
	{
		$this->datatable_registration_collection( 1 );
	}
	
	public function datatable_registration_collection( $state=false )
    {
        $start = $this->input->get_post('start', true);
        $length = $this->input->get_post('length', true);
        $order = $this->input->get_post('order', true);
        $columns = $this->input->get_post('columns', true);
        $search = $this->input->get_post('search', true);
        $draw = $this->input->get_post('draw', true);

		$this->load->model("registration_model");

		$db_from = "{$this->registration_model->table} a";
		$db_where = array();
		$db_like = array();
		$db_custome_where = NULL;
		
		if( $this->input->post("date_from") ){
			$db_where['a.TglReg >='] = $this->input->post("date_from");
		}

		if( $this->input->post("date_till") ){
			$db_where['a.TglReg <='] = $this->input->post("date_till");
		}

		// prepare default
		$db_where['a.Batal'] = 0;	
		$db_custome_where = " ((a.StatusPeriksa ='CO' OR a.StatusPeriksa='Sudah') AND (a.StatusBayar='Belum' OR a.StatusBayar='Proses')) ";	

		if( isset($search['value']) && ! empty($search['value']) )
        {
            $keywords = $this->db->escape_str( $search['value'] );
			
			$db_like[ $this->db->escape_str("a.NoReg") ] = $keywords;
			$db_like[ $this->db->escape_str("b.NRM") ] = $keywords;
			$db_like[ $this->db->escape_str("a.NamaPasien_Reg") ] = $keywords;
			 
			
        }
		
		// get total records
		$this->db->from( $db_from )
				;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_custome_where) ){ $this->db->where( $db_custome_where ); }
		$records_total = $this->db->count_all_results();
		
		// get total filtered
		$this->db
			->from( $db_from )
			->join( "{$this->patient_model->table} b", "a.NRM = b.NRM", "LEFT OUTER" )
			;
			
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }		
		if( !empty($db_custome_where) ){ $this->db->where( $db_custome_where ); }
		$records_filtered = $this->db->count_all_results();
		
		// get result filtered
		$db_select = <<<EOSQL
			a.NoReg,
			a.TglReg,
			a.JamReg,
			a.NRM,
			b.NamaPasien,
			b.Alamat,
			d.JenisKerjasama,
			c.Nama_Customer,
			a.NoAnggota AS NoKartu,
			a.PxKeluar_Dirujuk,
			a.PxKeluar_PlgPaksa,
			a.PxKeluar_Pulang,
			a.PxMeninggal,
			a.Status
EOSQL;

		$this->db
			->select( $db_select )
			->from( $db_from )
			->join( "{$this->patient_model->table} b", "a.NRM = b.NRM", "LEFT OUTER" )
			->join( "mCustomer c", "a.KodePerusahaan = c.Kode_Customer", "LEFT OUTER" )
			->join( "SIMmJenisKerjasama d", "a.JenisKerjasamaID = d.JenisKerjasamaID", "LEFT OUTER" )
			;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }		
		if( !empty($db_custome_where) ){ $this->db->where( $db_custome_where ); }
		
		// ordering
        if( isset($order) )
        {
            $sort_column = $order[0]['column'];
			$sort_dir = $order[0]['dir'];
			
			if( $columns[$sort_column]['orderable'] == 'true' )
			{
				$this->db
					->order_by( $columns[intval($this->db->escape_str($sort_column))]['data'], $this->db->escape_str($sort_dir) );
			}
        }
		
		// paging
		if( isset($start) && $length != '-1')
        {
            $this->db
				->limit( $length, $start );
        }
		
		// get
		$result = $this->db
					->get()
					->result()
					;
		
        // Output
        $output = array(
				'draw' => intval($draw),
				'recordsTotal' => $records_total,
				'recordsFiltered' => $records_filtered,
				'data' => array()
			);
        
		
		foreach($result as $row)
        {
			$date_reg = DateTime::createFromFormat("Y-m-d H:i:s.u", $row->JamReg);
			
			$row->JamReg = $date_reg->format('Y-m-d H:i:s');
      
            $output['data'][] = $row;
        }
		
		$this->template
			->build_json( $output );
    }	
}
