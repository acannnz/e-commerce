<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class View_reservations extends Admin_Controller
{ 
	protected $_translation = 'reservations';	
	//protected $_model = 'reservation_m'; 
	 
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('reservation');
		
		$this->page = "common_reservations";
		$this->template->title( lang("reservations:page") . ' - ' . $this->config->item('company_name') );
		
		$this->load->helper('reservation');
		$this->load->helper('registrations/registration');
		$this->load->model('reservation_m');
		$this->load->model('common/patient_type_m');
		$this->load->model('common/section_m');
		$this->load->model('common/supplier_m');
	}
	
	public function index($ID = NULL)
	{
		$data = array(
				"option_section" => $this->reservation_m->get_option_section(),
				"page" => $this->page,
	 			"form" => TRUE,
				"datatables" => TRUE,
				"option_doctor" => option_doctor(),
				"lookup_reservations" => base_url("view_reservations/lookup_reservations/{$ID}"),
			);
		
		$this->template
			->set( "heading", "View Reservasi" )
			->set_breadcrumb( lang("reservations:breadcrumb") )
			->build('view_reservations/datatable', (isset($data) ? $data : NULL));
	}
	
	public function index_reminder()
	{
		$data = array(
				'page' => $this->page,
				"form" => TRUE,
				'datatables' => TRUE,
			);
		
		$this->template
			->set( "heading", "Reservasi" )
			->set_breadcrumb( "List Reservasi" )
			->build('reservations/datatable_reminder', (isset($data) ? $data : NULL));
	}

	public function lookup_reservations($ID)
	{
		$post_data = (object) $this->input->post("f");
		print_r($post_data);exit;	
		
		if ($this->input->is_ajax_request()) {
			$data = [
				'collection' => reservation_helper::get_reservations(),
				// 'nameroutes' => $this->nameroutes,
			];

			$this->load->view('view_reservations/form/view_reservations', $data);
		}
	}
	
	public function lookup_patient( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			if( $this->input->get_post("is_modal") ){ $data["is_modal"] = TRUE; }
			
			$this->load->view( 'lookup/patients', (isset($data) ? $data : NULL) );
		} 
	}

	public function lookup_doctor( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{	
			$data = array(
				"type" => "doctor"
			);
			$this->load->view( 'lookup/suppliers', (isset($data) ? $data : NULL) );
		} 
	}

	public function lookup_doctor_datatable( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{	
			$data = array(
				"type" => "doctor"
			);
			$this->load->view( 'lookup/supplier_datatables', (isset($data) ? $data : NULL) );
		} 
	}
	
	public function lookup_schedule( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			if( $this->input->get_post("is_modal") ){ $data["is_modal"] = TRUE; }
			
			$this->load->view( 'lookup/schedules', (isset($data) ? $data : NULL) );
		} 
	}


	public function lookup( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'reservations/lookup/datatable' );
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
				->build('reservations/lookup', (isset($data) ? $data : NULL));
		}
	}
	
	public function lookup_collection()
	{
		$this->datatable_collection( 1 );
	}
	
	
	public function datatable_collection( $state=false )
    {
        $start = $this->input->get_post('start', true);
        $length = $this->input->get_post('length', true);
        $order = $this->input->get_post('order', true);
        $columns = $this->input->get_post('columns', true);
        $search = $this->input->get_post('search', true);
        $draw = $this->input->get_post('draw', true);
		
		$db_from = "{$this->reservation_m->table} a";
		$db_where = array();
		$db_or_where = array();
		$db_like = array();
		
		if($this->input->post("reminder") == 1){
			$db_where['a.UntukTanggal'] = date('Y-m-d', strtotime("+3 days"));
		}
		
		/*if( $this->input->post("date_from") ){
			$db_where['a.Tanggal >='] = $this->input->post("date_from");
		}

		if( $this->input->post("date_till") ){
			$db_where['a.Tanggal <='] = $this->input->post("date_till");
		}*/
		
		if( $this->input->post("for_date_from") ){
			$db_where['a.UntukTanggal >='] = $this->input->post("for_date_from");
		}

		if( $this->input->post("for_date_till") ){
			$db_where['a.UntukTanggal <='] = $this->input->post("for_date_till");
		}

		if( $this->input->post("NRM") ){
			$db_like['a.NRM'] = $this->input->post("NRM");
		}

		if( $this->input->post("Nama") ){
			$db_like['a.Nama'] = $this->input->post("Nama");
		}

		if( $this->input->post("Phone") ){
			$db_like['a.Phone'] = $this->input->post("Phone");
		}
		
		if( $this->input->post("SectionID") ){
			$db_where['a.UntukSectionID'] = $this->input->post("SectionID");
		}

		if( $this->input->post("DokterID") ){
			$db_where['a.UntukDokterID'] = $this->input->post("DokterID");
		}

		if( $this->input->post("show_already_registration") ){
			$db_or_where['a.Registrasi'] = $this->input->post("show_already_registration");
		}

		if( $this->input->post("show_cancel") ){
			$db_or_where['a.Batal'] = $this->input->post("show_cancel");
		}
				
		// preparing default
		if( isset($search['value']) && ! empty($search['value']) )
        {
            $keywords = $this->db->escape_str( $search['value'] );
			
			$db_like[ $this->db->escape_str("a.NoReservasi") ] = $keywords;
			
			$db_like[ $this->db->escape_str("a.NRM") ] = $keywords;
			 
        }
		
		// get total records
		$this->db->from( $db_from );
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		$records_total = $this->db->count_all_results();
		
		// get total filtered
		$this->db
			->from( $db_from )
			->join("{$this->section_m->table} b","a.UntukSectionID=b.SectionID","LEFT OUTER")
			->join("{$this->supplier_m->table} c","a.UntukDokterID=c.Kode_Supplier","LEFT OUTER")
			;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_or_where) ){ $this->db->group_start()->or_where( $db_or_where )->group_end(); }		
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }		
		$records_filtered = $this->db->count_all_results();
		
		// get result filtered
		$db_select = <<<EOSQL
			a.NoReservasi,
			a.Tanggal,
			a.Jam,
			a.PasienBaru,
			a.NRM,
			a.Nama,
			a.Alamat,
			a.Phone,
			a.UntukSectionID,
			a.UntukDokterID,
			a.UntukHari,
			a.UntukTanggal,
			a.NoUrut,
			a.Memo,
			b.SectionName,
			c.Nama_Supplier,
			d.Keterangan AS UntukJam,
			d.FromJam,
			a.Registrasi,
			a.Batal
			
EOSQL;

		$this->db
			->select( $db_select )
			->from( $db_from )
			->join("{$this->section_m->table} b","a.UntukSectionID=b.SectionID","LEFT OUTER")
			->join("{$this->supplier_m->table} c","a.UntukDokterID=c.Kode_Supplier","LEFT OUTER")
			->join("SIMmWaktuPraktek d","a.WaktuId=d.WaktuId","LEFT OUTER")
			;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_or_where) ){ $this->db->group_start()->or_where( $db_or_where )->group_end(); }		
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }		
		
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
			$estimasi = DateTime::createFromFormat("Y-m-d H:i:s.u", $row->FromJam);	
			if( $row->NoUrut != 1){
				$add_minute = ($row->NoUrut - 1) * 6;
				$estimasi->modify("+{$add_minute} minutes");
			}
			$row->estimation_time = $estimasi->format('H:i');
      
            $output['data'][] = $row;
        }
		
		$this->template
			->build_json( $output );
    }
	
	public function get_reservation_queue()
	{
		if ( $this->input->is_ajax_request() )
		{
			$params = $this->input->get('f');		
			$response = array(
				"status" => "success",
				"message" => "",
				"code" => 200,
				"NoUrut" => reservation_helper::get_reservation_queue( $params['UntukSectionID'], $params['UntukDokterID'], $params['UntukTanggal'], $params['WaktuID'] )
			);
			response_json($response);
		}
	}
}



