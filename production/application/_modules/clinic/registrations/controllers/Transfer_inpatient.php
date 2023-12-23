<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Transfer_inpatient extends Admin_Controller
{
	protected $_translation = 'registrations';	
	protected $_model = 'registration_model';
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('registration');
		
		$this->page = lang("registrations:transfer_inpatient_page");
		$this->template->title( lang("registrations:transfer_inpatient_page") . ' - ' . $this->config->item('company_name') );

		$this->load->model( "registration_model" );
		$this->load->model( "registration_data_model" );
		$this->load->model( "registration_destination_model" );
		$this->load->model( "patient_model" );
		$this->load->model( "class_model" );
		$this->load->model( "patient_type_model" );
		$this->load->model( "patient_nrm_model" );	
		$this->load->model( "memo_model" );
		$this->load->model( "cooperation_member_model" );
		
		$this->load->model( "common/supplier_m" );
		$this->load->model( "common/supplier_specialist_m" );
		$this->load->model( "section_model" );
		$this->load->model( "section_group_model" );
		$this->load->model( "common/time_m" );
			
		$this->load->helper( "registration" );
				
		$this->load->config('registrations');
	}

	public function index()
	{
		$data = array(
			'page' => $this->page,
			'option_section' => $this->registration_model->get_option_section(),
			'form' => TRUE,
			'datatables' => TRUE,
			"option_doctor" => option_doctor(),
		);
		
		$this->template
			->set( "heading", lang("registrations:transfer_inpatient_page") )
			->set_breadcrumb( lang("registrations:transfer_inpatient_page") )
			->build('transfer_inpatient/datatable', (isset($data) ? $data : NULL));
	}
	
	public function create($NoReg)
	{
		$item = $this->registration_model->get_one($NoReg, TRUE);
		if( empty($item) )
		{
			make_flashdata(array(
					'response_status' => 'error',
					'message' => lang( 'global:get_failed' )
				));
		
			redirect( "registrations/transfer_inpatient" );
		}
		
		$patient = $this->registration_model->get_patient( $item['NRM'] );
		$section_destination = $this->registration_model->get_section_destination( $item['NoReg'], $item['NRM'] );
		$cooperation = $this->registration_model->get_customer( array("Kode_Customer" => $item['KodePerusahaan']) ); // Perusahaan Kerja sama
		$second_insurer = $this->registration_model->get_customer( array("Kode_Customer" => $item['PertanggunganKeduaCompanyID']) ); // Pertanggungan Kedua (IKS)
		
		if( $this->input->post() ) 
		{
			$registration = array_merge($item, $this->input->post('f')); 
			$destinations = $this->input->post("destinations");

			if ( empty($destinations) )
			{
				$message = [
					"status" => "error",
					"message" => "Anda Belum Memilih Section Tujuan!",
					"code" => 200
				];
				response_json( $message );	
			}
			
			if ( $item['StatusBayar'] != "Belum" )
			{
				$message = [
					"status" => "error",
					"message" => "Data Tidak bisa Diubah! Data Ini sudah melakukan pembayaran diKasir!",
					"code" => 200
				];
					
				response_json($message);
			}
			
			$this->load->library( 'form_validation' );			
			$this->form_validation->set_rules( $this->get_model()->rules['update'] );
			$this->form_validation->set_data( $registration );			
			if( $this->form_validation->run() )
			{	
				$message = registration_helper::transfer_inpatient( $registration, $destinations);			

			} else
			{
				$message = [
					"status" => 'error',
					"message" => $this->form_validation->get_all_error_string(),
					"code" => 500
				];
			}
			
			response_json( $message );
		}

		$option_class = $this->class_model->dropdown_data(['Active' => 1]);
		$option_patient_type = $this->registration_model->get_option_patient_type();
		
		if( $this->input->is_ajax_request() )
		{
			$data = array(
					'item' => (object) $item,
					"is_ajax_request" => TRUE,
					"is_modal" => TRUE,
				);
			
			$this->load->view( 
					'registrations/modal/create_edit', 
					array('form_child' => $this->load->view('registrations/form', $data, true))
				);
		} else
		{
			$data = array(
					"page" => $this->page."_".strtolower(__FUNCTION__),
					"item" => (object) $item,
					"patient" => $patient,
					"option_class" => $option_class,
					"option_patient_type" => $option_patient_type,
					"cooperation" => $cooperation,
					"second_insurer" => $second_insurer,
					"option_patient_type" => $option_patient_type,
					"lookup_section" => base_url("registrations/transfer_inpatient/lookup_section"),
					"lookup_doctor" => base_url("registrations/transfer_inpatient/lookup_doctor"),
					"lookup_room" => base_url("registrations/transfer_inpatient/lookup_room"),
					"lookup_cooperation" => base_url("registrations/transfer_inpatient/lookup_cooperation"),
					"lookup_patient_cooperation_card" => base_url("registrations/transfer_inpatient/lookup_patient_cooperation_card"),
					"lookup_second_insurer" => base_url("registrations/transfer_inpatient/lookup_second_insurer"),
					"lookup_patient_second_insurer_card" => base_url("registrations/transfer_inpatient/lookup_patient_second_insurer_card"),
					"form" => TRUE,
					"datatables" => TRUE,
				);
			
			$this->template
				->set( "heading", lang("registrations:transfer_inpatient_create_heading") )
				->set_breadcrumb( lang("registrations:transfer_inpatient_page"), base_url("registrations/transfer_inpatient") )
				->set_breadcrumb( lang("registrations:transfer_inpatient_create_heading") )
				->build('transfer_inpatient/form', $data);
		}
	}
		
	public function edit( $NoReg = NULL )
	{
		$item = $this->registration_model->get_one($NoReg, TRUE);
		if( empty($item) )
		{
			make_flashdata(array(
					'response_status' => 'error',
					'message' => lang( 'global:get_failed' )
				));
		
			redirect( "registrations" );
		}
		
		if ( $item['StatusPeriksa'] != 'Belum' || $item['StatusBayar'] != "Belum"){
			redirect("registrations/view/{$NoReg}");
		}
		
		$patient = $this->registration_model->get_patient( $item['NRM'] );
		$section_destination = $this->registration_model->get_section_destination( $item['NoReg'], $item['NRM'] );
		$cooperation = $this->registration_model->get_customer( array("Kode_Customer" => $item['KodePerusahaan']) ); // Perusahaan Kerja sama
		$second_insurer = $this->registration_model->get_customer( array("Kode_Customer" => $item['PertanggunganKeduaCompanyID']) ); // Pertanggungan Kedua (IKS)
		
		if( $this->input->post() ) 
		{			
		
			$registration = array_merge($item, $this->input->post('f')); 
			$destinations = $this->input->post('destinations'); 
			if ( empty($this->input->post("destinations")) )
			{
				$message = [
					"status" => "error",
					"message" => "Anda Belum Memilih Section Tujuan!",
					"code" => 200
				];
					
				response_json($message);		
			}
			
			if ( $item['StatusPeriksa'] != 'Belum' || $item['StatusBayar'] != "Belum" )
			{
				$message = [
					"status" => "error",
					"message" => "Data Tidak bisa Diubah! Data Ini sudah melakukan pemeriksaan!",
					"code" => 200
				];
					
				response_json($message);
			}
			
			$this->load->library( 'form_validation' );
			$this->form_validation->set_rules( $this->get_model()->rules['update'] );
			$this->form_validation->set_data( $registration );
			if( $this->form_validation->run() )
			{
				$message = registration_helper::update_registration( $registration, $destinations);
			} else
			{
				$message = [
					'response_status' => 'error',
					'message' => $this->form_validation->get_all_error_string()
				];
			}
			
			response_json($message);
		}
		
		$option_patient_type = $this->registration_model->get_option_patient_type();
		$option_nationality = $this->registration_model->get_option_nationality();

		if( $this->input->is_ajax_request() )
		{
			$data = array(
					'item' => $this->item,
					"is_ajax_request" => TRUE,
					"is_modal" => TRUE,
				);
			
			$this->load->view( 
					'registrations/modal/create_edit', 
					array('form_child' => $this->load->view('registrations/form', $data, true))
				);
		} else
		{
			$data = array(
					"page" => $this->page."_".strtolower(__FUNCTION__),
					"item" => (object) $item,
					"patient" => $patient,
					"section_destination" => $section_destination,
					"cooperation" => $cooperation,
					"second_insurer" => $second_insurer,
					"option_patient_type" => $option_patient_type,
					"lookup_patients" => base_url("registrations/lookup_patient"),
					"lookup_section" => base_url("registrations/lookup_section"),
					"lookup_doctor_schedule" => base_url("registrations/lookup_doctor_schedule_from_registration"),
					"lookup_supplier" => base_url("registrations/lookup_supplier"),
					"lookup_cooperation" => base_url("registrations/lookup_cooperation"),
					"lookup_insurer" => base_url("registrations/lookup_insurer"),
					"lookup_patient_cooperation_card" => base_url("registrations/lookup_patient_cooperation_card"),
					"lookup_second_insurer" => base_url("registrations/lookup_second_insurer"),
					"lookup_patient_second_insurer_card" => base_url("registrations/lookup_patient_second_insurer_card"),
					"gen_mrn_link" => base_url("registrations/gen_mr_number"),
					"delete_registration_destination_link" => base_url("registrations/delete_registration_destination"),
					"cancel_link" => base_url("registrations/cancel/$NoReg"),
					"create_link" => base_url("registrations/create"),
					"print_label" => base_url("registrations/print_label/$NoReg"),
					"form" => TRUE,
					"datatables" => TRUE,
					"is_edit" => TRUE,
				);
		
			$this->template
				->set( "heading", lang("registrations:edit_heading") )
				->set_breadcrumb( lang("registrations:breadcrumb"), base_url("registrations") )
				->set_breadcrumb( lang("registrations:edit_heading") )
				->build('registrations/form_edit', $data);
		}
	}
			
	public function lookup_section( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'transfer_inpatient/lookup/sections' );
		} 
	}

	public function lookup_doctor( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'transfer_inpatient/lookup/doctor', array("type" => "doctor") );
		} 
	}
	
	public function lookup_room($is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'transfer_inpatient/lookup/room');
		} 
	}

	// Cooperation == Perusahaan yang diajak kerja sama (BPJS, IKS)
	public function lookup_cooperation( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'transfer_inpatient/lookup/cooperations', array() );
		} 
	}
	
	// Lookup kartu anggota kerja sama patient(BPJS, IKS)
	public function lookup_patient_cooperation_card( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'transfer_inpatient/lookup/patient_cooperation_cards', array() );
		} 
	}
	
	// lookup_second_insurer == Pertanggungan Kedua (IKS)
	public function lookup_second_insurer( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'transfer_inpatient/lookup/second_insurers', array() );
		} 
	}
	
	// Lookup kartu anggota kerja sama kedua patient(IKS)
	public function lookup_patient_second_insurer_card( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'transfer_inpatient/lookup/patient_second_insurer_cards', array() );
		} 
	}

	public function lookup_collection()
	{
		$this->datatable_collection( 1 );
	}

	public function datatable_collection()
    {
        $start = $this->input->get_post('start', true);
        $length = $this->input->get_post('length', true);
        $order = $this->input->get_post('order', true);
        $columns = $this->input->get_post('columns', true);
        $search = $this->input->get_post('search', true);
        $draw = $this->input->get_post('draw', true);
		
		$db_from = "{$this->registration_model->table} a";
		$db_where = [];
		$db_like = [];
		
		$db_where['a.Batal'] = 0;
		$db_where['a.AkanRI'] = 1;
		$db_where['a.Batal'] = 0;
		
		if( $this->input->post("date_from") ){
			$db_where['a.JamReg >='] = DateTime::createFromFormat('Y-m-d', $this->input->post("date_from"))->setTime(0,0)->modify('+8 hour')->format('Y-m-d H:i:s');
		}

		if( $this->input->post("date_till") ){
			$db_where['a.JamReg <='] = DateTime::createFromFormat('Y-m-d', $this->input->post("date_till"))->setTime(0,0)->modify('+1 day')->modify('+8 hour')->format('Y-m-d H:i:s');
		}
		
		if( $this->input->post("NRM") ){
			$db_like['a.NRM'] = $this->input->post("NRM");
		}

		if( $this->input->post("Nama") ){
			$db_like['a.NamaPasien_Reg'] = $this->input->post("Nama");
		}
		
					
		// preparing default
		if( isset($search['value']) && ! empty($search['value']) )
        {
            $keywords = $this->db->escape_str( $search['value'] );
						
			$db_like[ $this->db->escape_str("a.NoReg") ] = $keywords;
			$db_like[ $this->db->escape_str("a.NRM") ] = $keywords;
			$db_like[ $this->db->escape_str("a.TglReg") ] = $keywords;
			$db_like[ $this->db->escape_str("a.JamReg") ] = $keywords;
			$db_like[ $this->db->escape_str("a.NamaPasien_Reg") ] = $keywords;
			$db_like[ $this->db->escape_str("b.Phone") ] = $keywords;
			$db_like[ $this->db->escape_str("c.JenisKerjasama") ] = $keywords;

			$db_like[ $this->db->escape_str("a.NoReservasi") ] = $keywords;
        }
		
		//get total records
		$this->db->from( $db_from )
			;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		$records_total = $this->db->count_all_results();
		
		// get total filtered
		$this->db
			->from( $db_from )
			;

		if ( $this->input->post("SectionID") || $this->input->post("DokterID") ){		
		
			$this->db->join( "SIMtrDataRegPasien r", "a.NoReg = r.NoReg", "INNER" );
			
			if ( $this->input->post("SectionID") ){
				$this->db->join( "{$this->section_model->table} s", "r.SectionID = s.SectionID", "LEFT OUTER" );
				$db_where['r.SectionID'] = $this->input->post("SectionID");
			}
	
			if ( $this->input->post("DokterID") ){
				$this->db->join( "{$this->supplier_m->table} t", "r.DokterID = t.Kode_Supplier", "LEFT OUTER" );
				$db_where['r.DokterID'] = $this->input->post("DokterID");
			}
		}

		$this->db->join( "{$this->patient_model->table} b", "a.NRM = b.NRM", "LEFT OUTER" )
			->join( "{$this->patient_type_model->table} c", "a.JenisKerjasamaID = c.JenisKerjasamaID", "LEFT OUTER" )
			->join( "mUser e", "a.User_ID = e.User_ID", "LEFT OUTER" )
			;

		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }		
		$records_filtered = $this->db->count_all_results();
		
		// get result filtered
		$db_select = <<<EOSQL
			a.NoReg,
			a.NoReservasi,
			a.NRM,
			a.TglReg,
			a.JamReg,
			a.StatusPeriksa,
			a.StatusBayar,
			a.Batal,
			b.NamaPasien,
			b.Phone,
			b.Alamat,
			b.JenisKelamin,
			c.JenisKerjasamaID,
			c.JenisKerjasama,
			e.Nama_Singkat,
						
EOSQL;

		if ( $this->input->post("SectionID") ){
		$db_select .= <<<EOSQL
			s.SectionName,
EOSQL;
		}

		if ( $this->input->post("DokterID") ){
		$db_select .= <<<EOSQL
			t.Nama_Supplier,
EOSQL;
		}

		$this->db
			->select( $db_select )
			->from( $db_from )
			;

		if ( $this->input->post("SectionID") || $this->input->post("DokterID") ){		
		
			$this->db->join( "SIMtrDataRegPasien r", "a.NoReg = r.NoReg", "INNER" );
			
			if ( $this->input->post("SectionID") ){
				$this->db->join( "{$this->section_model->table} s", "r.SectionID = s.SectionID", "LEFT OUTER" );
				$db_where['r.SectionID'] = $this->input->post("SectionID");
			}
	
			if ( $this->input->post("DokterID") ){
				$this->db->join( "{$this->supplier_m->table} t", "r.DokterID = t.Kode_Supplier", "LEFT OUTER" );
				$db_where['r.DokterID'] = $this->input->post("DokterID");
			}
		}
			
		$this->db->join( "{$this->patient_model->table} b", "a.NRM = b.NRM", "LEFT OUTER" )
			->join( "{$this->patient_type_model->table} c", "a.JenisKerjasamaID = c.JenisKerjasamaID", "LEFT OUTER" )
			->join( "mUser e", "a.User_ID = e.User_ID", "LEFT OUTER" )
			;

		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }		
		
		// ordering
        if( isset($order) )
        {
            $sort_column = $order[0]['column'];
			$sort_dir = $order[0]['dir'];
			
			if( $columns[$sort_column]['orderable'] == 'true' )
			{
				$this->db
					->order_by( $columns[intval($this->db->escape_str($sort_column))]['name'], $this->db->escape_str($sort_dir) );
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
			$date = DateTime::createFromFormat("Y-m-d H:i:s.u", $row->TglReg);
			$time = DateTime::createFromFormat("Y-m-d H:i:s.u", $row->JamReg ); 
			
			$row->TglReg = $date->format('Y-m-d');
			$row->JamReg = $time->format('H:i:s');
			
            $output['data'][] = $row;
        }
		
		$this->template
			->build_json( $output );
    }
		
	public function doctor_schedule_collection()
    {
        $start = $this->input->get_post('start', true);
        $length = $this->input->get_post('length', true);
        $order = $this->input->get_post('order', true);
        $columns = $this->input->get_post('columns', true);
        $search = $this->input->get_post('search', true);
        $draw = $this->input->get_post('draw', true);
		
		$db_from = "SIMtrDokterJagaDetail AS a";
		$db_where = array();
		$db_like = array();
		
		$db_where['a.tanggal'] = date("Y-m-d");
				
		// preparing default
		if( isset($search['value']) && ! empty($search['value']) )
        {
            $keywords = $this->db->escape_str( $search['value'] );
						
			$db_like[ $this->db->escape_str("b.Nama_Supplier") ] = $keywords;
			$db_like[ $this->db->escape_str("e.SpesialisName") ] = $keywords;
			$db_like[ $this->db->escape_str("c.SectionName") ] = $keywords;
			$db_like[ $this->db->escape_str("d.Keterangan") ] = $keywords;

        }
		
		// get total records
		$this->db->from( $db_from )
			->join( "{$this->supplier_m->table} b", "a.DokterID = b.Kode_Supplier", "LEFT OUTER" )
			->join( "{$this->supplier_specialist_m->table} e", "b.SpesialisID = e.SpesialisID", "LEFT OUTER" )
			->join( "{$this->section_model->table} c", "a.SectionID = c.SectionID", "LEFT OUTER" )
			->join( "{$this->time_m->table} d", "a.WaktuID = d.WaktuID", "LEFT OUTER" )
		;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		$records_total = $this->db->count_all_results();
		
		// get total filtered
		$this->db
			->from( $db_from )
			->join( "{$this->supplier_m->table} b", "a.DokterID = b.Kode_Supplier", "LEFT OUTER" )
			->join( "{$this->supplier_specialist_m->table} e", "b.SpesialisID = e.SpesialisID", "LEFT OUTER" )
			->join( "{$this->section_model->table} c", "a.SectionID = c.SectionID", "LEFT OUTER" )
			->join( "{$this->time_m->table} d", "a.WaktuID = d.WaktuID", "LEFT OUTER" )
			;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }		
		$records_filtered = $this->db->count_all_results();
		
		// get result filtered
		$db_select = <<<EOSQL
			a.DokterID,
			a.SectionID,
			a.Cancel,
			a.DokterPenggantiID,
			a.NoAntrianTerakhir,
			a.WaktuID,
			b.Nama_Supplier,
			e.SpesialisName,
			c.SectionName,
			d.Keterangan
			
EOSQL;

		$this->db
			->select( $db_select )
			->from( $db_from )
			->join( "{$this->supplier_m->table} b", "a.DokterID = b.Kode_Supplier", "LEFT OUTER" )
			->join( "{$this->supplier_specialist_m->table} e", "b.SpesialisID = e.SpesialisID", "LEFT OUTER" )
			->join( "{$this->section_model->table} c", "a.SectionID = c.SectionID", "LEFT OUTER" )
			->join( "{$this->time_m->table} d", "a.WaktuID = d.WaktuID", "LEFT OUTER" )
			;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
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
			if( $row->Cancel == 1 && !empty($row->DokterPenggantiID) )
			{
				$row->NamaDokterPengganti = "-";
			} else if( $row->Cancel == 1 && empty($row->DokterPenggantiID) ) {
				$row->NamaDokterPengganti = "-";
			} else if( $row->Cancel == 0 ) {
				$row->NamaDokterPengganti = "-";
			}

			$queue_where = (object) array("DokterID" => $row->DokterID, "SectionID" => $row->SectionID, "WaktuID" => $row->WaktuID, "Tanggal" => date("Y-m-d"));
			$queue = registration_helper::get_queue( $queue_where );
			$row->NoUrut = $queue > $this->config->item("start_queue") ? $queue : $this->config->item("start_queue");
			// Update antrian pada Jadwal			
            $output['data'][] = $row;
        }
		
		//print_r($output);exit;
		
		$this->template
			->build_json( $output );
    }
	
	public function get_queue()
	{
		if ( $this->input->is_ajax_request() && $this->input->post() )		
		{
			$response = [
				"status" => "success",
				"message" => "",
				"code" => 200
			];
			
			$params = (object) $this->input->post();
			if ($queue = registration_helper::get_queue($params))
			{
				$response['queue'] = $queue;
			} else {
				$response = [
					"status" => "error",
					"message" => "Failed Get Queue",
					"code" => 500
				];
			}
			
			response_json($response);						
		}
	}
		
	public function print_label( $NoReg= NULL )
	{
		$this->load->helper("report");
		$item = report_helper::get_registration_label( $NoReg );
		
		if ( empty($item) ){
			redirect("registrations");
		}
		
		$date = DateTime::createFromFormat("Y-m-d H:i:s.u", $item->TglReg);		
				
		$counter = explode("-", $item->NoReg);
		$item->NoRegLabel = sprintf("%s-%s", $date->format('y'), $counter[1]);
									
		$data = array(
					"item" => $item,
				);

		$html_content =  $this->load->view( "print/label", $data, TRUE); 
		$file_name = 'Print  Label';		
		$this->load->helper( "export" );

		export_helper::generate_label_pdf( $html_content, $file_name, NULL , $margin_bottom = 1.0, $header = NULL, $margin_top = 0.3, $orientation = 'P', $margin_left = 0.3, $margin_right = 0.3);	
		exit(0);

	}
		
}