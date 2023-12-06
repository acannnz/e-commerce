<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class General_payment extends Admin_Controller 
{ 
	protected $_translation = 'general_payment';	
	protected $_model = 'general_payment_m';  
	    
	public function __construct() 
	{ 
		parent::__construct();
		$this->simple_login->check_user_role('cashier');
		
		$this->load->model("general_payment_m");
		$this->load->model("cashier_model");
		$this->load->model("cashier_discount_model");
		$this->load->model("cashier_detail_model");
		$this->load->model("cashier_detail_service_group_model");
		
		$this->load->model("registration_model");
		$this->load->model("registration_data_model");
		$this->load->model("patient_model");
		$this->load->model("patient_type_model");
		$this->load->model("section_model");
		$this->load->model("supplier_model");
		
		$this->page = lang("general_payment:page");
		$this->template->title( lang("general_payment:page") . ' - ' . $this->config->item('company_name') );
		
		$this->load->language('general_payment');
		$this->load->helper('general_payment');
	}
	
	public function index()
	{
		$data = array(
				'page' => $this->page,
				"form" => TRUE,
				'datatables' => TRUE,
			);
		
		$this->template
			->set( "heading", lang("general_payment:page") )
			->set_breadcrumb( lang("general_payment:cashier_page") )
			->set_breadcrumb( lang("general_payment:page"), base_url("cashier/general-payment") )
			->build('general_payment/datatable', (isset($data) ? $data : NULL));
	}
		
	public function pay( $NoReg = NULL ) 
	{
		$item = general_payment_helper::get_item( $NoReg, 0 );
		$item->NoBukti = general_payment_helper::gen_evidence_number();


		$datareg = $this->registration_data_model->get_one($NoReg);
		if(!empty($datareg))
		{
			$dokter = $this->supplier_model->get_by(['Kode_Supplier' => $datareg->DokterID]);
			$item->DokterID = $dokter->Kode_Supplier;
			$item->NamaDokter = $dokter->Nama_Supplier;
		}

		if (!empty($item->NoReg))
		{
			
			if ( @$item->StatusBayar == "Sudah Bayar" )
			{
				// make_flashdata(array(
				// 		'response_status' => 'error',
				// 		'message' => "Invalid URL!"
				// ));
				
				redirect("cashier/general-payment");
			}
			
			$item->total_cost = general_payment_helper::get_total_cost( $NoReg, 0 );			
			// Data Detail Jasa yg digroup
			$item->group_detail_cost = general_payment_helper::get_group_detail_cost( $NoReg, 0 );
			$this->db->update("SIMtrRegistrasi", array("ProsesPayment" => 1), array("NoReg" => $NoReg));
		}				
		

		if( $this->input->post() ) 
		{
			$transaction = (object) $this->input->post("DataTransaction");
			$additional = (object) $this->input->post("additional");
			$discount = (array)$this->input->post("discount");				
			$payments = $this->input->post("JenisBayar");
			
			
			$this->load->library( 'form_validation' );
			
			$this->form_validation->set_rules( $this->get_model()->rules['insert'] );
			$this->form_validation->set_data( (array) $transaction );			
			if($this->form_validation->run() )
			{ 
				$message = general_payment_helper::create_general_payment($transaction, $payments, $discount, $additional, $item);
				
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


		
		if( $this->input->is_ajax_request() )
		{
			$data = array(
					'item' => (object)$item,
					// "patient" => @$patient,
					// "cooperation" => @$cooperation,
					"is_ajax_request" => TRUE,
					"is_modal" => TRUE,
					"lookup_registration" => base_url("cashier/general-payment/lookup_registration"),
					"lookup_supplier" => base_url("cashier/general-payment/lookup_supplier_cashier"),
					"lookup_form_credit_card" => base_url("cashier/general-payments/payment/lookup_form_credit_card"),
					"print_cost_breakdown" => base_url("cashier/general-payments/print/cost_breakdown/{$NoReg}"),
				);
			
			$this->load->view( 
					'helper/modal/create_edit', 
					array('form_child' => $this->load->view('helper/form', $data, true))
				);
		} else
		{
			$data = array(
					"page" => $this->page."_".strtolower(__FUNCTION__),
					"item" => @$item,
					"form" => TRUE,
					"datatables" => TRUE,
					"update_process_payment" => base_url('cashier/general-payment/update_process_payment'),
					"lookup_registration" => base_url("cashier/general-payment/lookup_registration"),
					"lookup_supplier" => base_url("cashier/general-payment/lookup_supplier_cashier"),
					"lookup_form_credit_card" => base_url("cashier/general-payments/payment/lookup_form_credit_card"),
					"lookup_form_credit_bon" => base_url("cashier/general-payments/payment/lookup_form_credit_bon"),
					"print_cost_breakdown" => base_url("cashier/general-payments/print/cost_breakdown/{$NoReg}"),
				);
						
			$this->template
				->set( "heading", lang("general_payment:pay_heading") )
				->set_breadcrumb( lang("general_payment:page"), base_url("cashier/general-payment") )
				->set_breadcrumb( 'Proses Pembayaran' )
				->build('general_payment/form', $data);
		}
	}
		
	public function edit( $NoBukti = NULL )
	{		
		if ( empty($NoBukti) )
		{
			make_flashdata(array(
					'response_status' => 'error',
					'message' => "Invalid URL!"
			));
			
			redirect("cashier/general-payment");
		}
		
		$cashier = $this->db->where("NoBukti", $NoBukti)->get("SIMtrKasir")->row();
		
		if ( empty($cashier) )
		{
			make_flashdata(array(
					'response_status' => 'error',
					'message' => lang("global:get_failed")
			));
			
			redirect("cashier/general-payment");
		}

		$item = (object) array_merge( (array) general_payment_helper::get_item( $cashier->NoReg ), (array) $cashier);

		if ($cashier->Audit == 1 || $cashier->Batal == 1)
		{
			if ( $this->input->is_ajax_request() )
			{
				$response = array(
						"NoBukti" => $NoBukti,
						"status" => 'success',
						"message" => "Proses Dibatalkan, Data Sudah Di Audit atau Batal!",
						"code" => 200
					);
				response_json($response);
			}
		}
		
		$item->total_cost = general_payment_helper::get_total_cost( $item->NoReg, 0 );			
		// Data Detail Jasa yg digroup
		$item->group_detail_cost = general_payment_helper::get_group_detail_cost( $item->NoReg, 0 );
		
		$dokter = $this->supplier_model->get_by(['Kode_Supplier' => $item->DokterID]);
		if(!empty($dokter))
		{
			$item->NamaDokter = $dokter->Nama_Supplier;
		}


		if(!empty($item->DokterBonID))
			$item->DokterBonName = $this->db->where("Kode_Customer", $item->DokterBonID)->get("mCustomer")->row()->Nama_Customer;
						
		if( $this->input->post() ) 
		{				
			$transaction = (object) $this->input->post("DataTransaction");
			$discount = (array)$this->input->post("discount");
			$payments = $this->input->post("JenisBayar");
			$additional = (object) $this->input->post("additional");
			
			$state = general_payment_helper::check_invoice_state( $NoBukti);
			$message = array();
			foreach( $state as $row )
			{
				if ( $row->state > 0 || !empty($row->state) )
				{
					$message[] = $row->message;
				}
			}
			
			if ( !empty($message) )
			{
				$response = array(
						"state" => 1,
						"status" => 'error',
						"message" => $message,
						"code" => 500
					);
				response_json($response);
			}
			
			$this->load->library( 'form_validation' );
			$this->form_validation->set_rules( $this->get_model()->rules['insert'] );
			$this->form_validation->set_data( (array) $transaction );			
			if( !$this->form_validation->run() )
			{
				$response = general_payment_helper::update_general_payment($transaction, $payments, $discount, $additional, $item);
			} else
			{
				$response = [
					"status" => 'error',
					"message" => $this->form_validation->get_all_error_string(),
					"code" => 500
				];
			}
			response_json($response);
		}
		
		if( $this->input->is_ajax_request() )
		{
			$data = array(
					"item" => $item,
					"form" => TRUE,
					"datatables" => TRUE,
					"is_edit" => TRUE,
					"lookup_registration" => base_url("cashier/general-payment/lookup_registration"),
					"lookup_supplier" => base_url("cashier/general-payment/lookup_supplier_cashier"),
					"lookup_form_credit_card" => base_url("cashier/general-payments/payment/lookup_form_credit_card"),
					"lookup_cancel" => base_url("cashier/general-payment/cancel/{$NoBukti}"),
					"print_cost_breakdown" => base_url("cashier/general-payments/print/cost_breakdown/{$NoBukti}"),
					"print_invoice" => base_url("cashier/general-payments/print/invoice/{$NoBukti}"),
					"print_kwitansi" => base_url("cashier/general-payments/print/kwitansi/{$NoBukti}"),
				);
			
		} else {
			$data = array(
					"page" => $this->page."_".strtolower(__FUNCTION__),
					"item" => $item,
					"form" => TRUE,
					"datatables" => TRUE,
					"is_edit" => TRUE,
					"lookup_registration" => base_url("cashier/general-payment/lookup_registration"),
					"lookup_supplier" => base_url("cashier/general-payment/lookup_supplier_cashier"),
					"lookup_form_credit_card" => base_url("cashier/general-payments/payment/lookup_form_credit_card"),
					"lookup_form_credit_bon" => base_url("cashier/general-payments/payment/lookup_form_credit_bon"),
					"lookup_cancel" => base_url("cashier/general-payment/cancel/{$NoBukti}"),
					"print_cost_breakdown" => base_url("cashier/general-payments/print/cost_breakdown/{$NoBukti}"),
					"print_invoice" => base_url("cashier/general-payments/print/invoice/{$NoBukti}"),
					"print_kwitansi" => base_url("cashier/general-payments/print/kwitansi/{$NoBukti}"),
					"update_process_payment" => base_url('cashier/general-payment/update_process_payment'),
				);
			
			
			$this->template
				->set( "heading", lang("general_payment:edit_heading") )
				->set_breadcrumb( lang("general_payment:page"), base_url("cashier/general-payment") )
				->set_breadcrumb( lang("general_payment:edit_heading")  )
				->build('general_payment/form', $data);
		}
	}

	public function view( $NoBukti = NULL )
	{		
		if ( empty($NoBukti) )
		{
			make_flashdata(array(
					'response_status' => 'error',
					'message' => "Invalid URL!"
			));
			
			redirect("cashier/general-payment");
		}
		
		$cashier = $this->db->where("NoBukti", $NoBukti)->get("SIMtrKasir")->row();
		
		if ( empty($cashier) )
		{
			make_flashdata(array(
					'response_status' => 'error',
					'message' => lang("global:get_failed")
			));
			
			redirect("cashier/general-payment");
		}

		$item = general_payment_helper::get_item( $cashier->NoReg );
		$item->cashier = $cashier;		
		$item->section = $this->db->where("SectionID", $item->SectionID)->get("SIMmSection")->row();		
		// $item->total_cost = general_payment_helper::get_total_cost( $item->NoReg, $item->Status );
		$item->total_cost = general_payment_helper::get_total_cost( $item->NoReg, 0 );
		$item->group_detail_cost = general_payment_helper::get_group_detail_cost( $item->NoReg, 0 );
		// $item->group_detail_cost = general_payment_helper::get_group_detail_cost( $item->NoReg, $item->Status );
		
		if( $this->input->is_ajax_request() )
		{
			$data = array(
					"item" => $item,
					"form" => TRUE,
					"datatables" => TRUE,
					"is_edit" => TRUE,
					"lookup_registration" => base_url("cashier/general-payment/lookup_registration"),
					"lookup_supplier" => base_url("cashier/general-payment/lookup_supplier_cashier"),
					"lookup_cancel" => base_url("cashier/general-payment/cancel/{$NoBukti}")
				);
			
		} else {
			$data = array(
					"page" => $this->page."_".strtolower(__FUNCTION__),
					"item" => $item,
					"form" => TRUE,
					"datatables" => TRUE,
					"is_edit" => TRUE,
					"lookup_registration" => base_url("cashier/general-payment/lookup_registration"),
					"lookup_supplier" => base_url("cashier/general-payment/lookup_supplier_cashier"),
					"lookup_cancel" => base_url("cashier/general-payment/cancel/{$NoBukti}")
				);
			
			
			$this->template
				->set( "heading", lang("general_payment:view_heading") )
				->set_breadcrumb( lang("general_payment:page"), base_url("cashier/general-payment") )
				->set_breadcrumb( lang("general_payment:view_heading")  )
				->build('general_payment/form_view', $data);
		}
	}
		
	public function cancel( $NoBukti )
	{
		if( $this->input->is_ajax_request() )
		{		
			$cashier = $this->db->where( "NoBukti", $NoBukti)->get("SIMtrKasir")->row();
			$item = general_payment_helper::get_item( $cashier->NoReg );
			$item->cashier = $cashier;

			if( $this->input->post() ){
				
				$data = (object) $this->input->post('f');
				
				$state = general_payment_helper::check_invoice_state( $item->cashier->NoBukti );
				$message = array();
				foreach( $state as $row )
				{
					if ( $row->state > 0 || !empty($row->state) )
					{
						$message[] = $row->message;
					}
				}
				
				if ( !empty($message) )
				{
					$response = array(
							"state" => 1,
							"status" => 'error',
							"message" => $message,
							"code" => 500
						);
						
					print_r( json_encode($response, JSON_NUMERIC_CHECK) );
					exit(0);
				}

				if( general_payment_helper::approval($data->Approve_User, $data->Approve_Pswd ) ) {
					
					$this->db->trans_begin();
						$cashier = array(
							"TglBatal" => date("Y-m-d"),
							"BATAL" => 1,
							"AlasanBatal" => $data->Approve_Reason
						);
						
						$registration = array(
							"PxKeluar_Pulang" => 0,
							"StatusPeriksa" => 'Sudah', 
							"StatusBayar"=>'Belum',
							"ProsesPayment" => 0
						);

						$data_reg = array(
							"PxKeluar_Pulang" => 0,
							"out" => 0 
						);

						$this->db->update( "SIMtrKasir", $cashier , array("NoBukti" => $NoBukti) );
						
						$this->db->delete("SIMtrHonor_RJ", array("NoReg" => $item->NoReg));

						$this->db->update("SIMtrRegistrasi", $registration, array("NoReg" => $item->NoReg));

						$this->db->update("SIMtrDataRegPasien", $data_reg ,array("NoReg" => $item->NoReg));
						
						$this->db
							->set("TotalKunjunganRawatJalan", "TotalKunjunganRawatJalan - 1", FALSE)
							->set("KunjunganRJ_TahunIni", "KunjunganRJ_TahunIni - 1", FALSE)
							->set("SedangDirawat", 1)
							->where("NRM", $item->NRM)
							->update($this->patient_model->table);
						
						$date = date("Y-m-d");
						$time = date("Y-m-d H:i:s");
						$activities_description = sprintf( "%s # %s # %s # %s", "CANCEL KASIR.", $NoBukti, $item->NRM, @$item->NamaPasien );			
						$this->db->query("EXEC InsertUserActivities '{$date}','{$time}', {$this->user_auth->User_ID} ,'{$NoBukti}','{$activities_description}','SIMtrKasir'");
						
					if ($this->db->trans_status() === FALSE)
					{
						$this->db->trans_rollback();
						$response = array(
								"status" => 'error',
								"message" => lang("global:cancel_failed"),
								"code" => 500
							);
					} else {
						$this->db->trans_commit();
						$response = array(
								"NoBukti" => $NoBukti,
								"status" => 'success',
								"message" => lang("global:cancel_successfully"),
								"code" => 200
							);
					}	
					
				} else {
					$response = array(
							"status" => 'error',
							"message" => "Username atau Password Salah",
							"code" => 500
						);
				}

				print_r( json_encode($response, JSON_NUMERIC_CHECK) );
				exit(0);
			}
			
			$this->load->view( 'general_payment/lookup/cancel', array('item' => $item) );
		}
	}
	
	public function update_process_payment()
	{
		if( $this->input->post() )
		{
			if( $post_data = $this->input->post('f') )
			{
				$this->db->update('SIMtrRegistrasi ', ['ProsesPayment' => $post_data['ProsesPayment']], ['NoReg' => $post_data['NoReg']] );
				response_json('success');
			}
		}
	}
	
	public function lookup_supplier_cashier( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'general_payment/lookup/suppliers_cashier', array("type" => "doctor" ));
		} 
	}

	public function lookup_supplier( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'general_payment/lookup/suppliers');
		} 
	}

	public function lookup_registration( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{	
			$data = array( 
				"view_datatable" => $this->lookup_registration_datatable( true ) 
			);
			
			$this->load->view( 'general_payment/lookup/registration', $data);
		} 
	}
	
	public function lookup( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'registrations/lookup/datatable' );
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
				->build('registrations/lookup', (isset($data) ? $data : NULL));
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
		
		$db_from = "{$this->general_payment_m->table} a";
		$db_where = array();
		$db_like = array();
		
		if( $this->input->post("date_from") ){
			$db_where['a.Tanggal >='] = $this->input->post("date_from");
		}

		if( $this->input->post("date_till") ){
			$db_where['a.Tanggal <='] = $this->input->post("date_till");
		}

		if( $this->input->post("NRM") ){
			$db_like['a.NRM'] = $this->input->post("NRM");
		}

		if( $this->input->post("Nama") ){
			$db_like['b.NamaPasien_Reg'] = $this->input->post("Nama");
		}

		if( $this->input->post("Phone") ){
			$db_like['e.Phone'] = $this->input->post("Phone");
		}
		
		if( $this->input->post("Alamat")){
			$db_like['e.alamat'] = $this->input->post("Alamat");
		}

		// prepare default
		$db_where['a.SectionID'] = 'SEC079';	

		if( isset($search['value']) && ! empty($search['value']) )
        {
            $keywords = $this->db->escape_str( $search['value'] );
			
			$db_like[ $this->db->escape_str("a.NoBukti") ] = $keywords;
			$db_like[ $this->db->escape_str("b.NRM") ] = $keywords;
			$db_like[ $this->db->escape_str("b.NoReg") ] = $keywords;
			$db_like[ $this->db->escape_str("b.NamaPasien_Reg") ] = $keywords;
			 
			
        }
		
		// get total records
		$this->db->from( $db_from )
				;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		$records_total = $this->db->count_all_results();
		
		// get total filtered
		$this->db
			->from( $db_from )
			->join( "{$this->registration_model->table} b", "a.NoReg = b.NoReg", "LEFT OUTER" )
			->join( "{$this->section_m->table} c", "a.SectionID = c.SectionID", "LEFT OUTER" )
			->join( "{$this->patient_type_m->table} d", "b.JenisKerjasamaID = d.JenisKerjasamaID","LEFT OUTER")
			->join( "{$this->patient_m->table} e", "b.NRM= e.NRM","LEFT OUTER")
			;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }		
		$records_filtered = $this->db->count_all_results();
		
		// get result filtered
		$db_select = <<<EOSQL
			a.NoBukti,
			a.Tanggal,
			a.Jam,
			a.Shift,
			a.Batal,
			a.Audit,
			a.Closing,
			b.NoReg,
			b.TglReg,
			b.NRM,
			b.NamaPasien_Reg,
			d.JenisKerjasama,
			e.JenisKelamin,
			e.Alamat,
			e.Phone
EOSQL;

		$this->db
			->select( $db_select )
			->from( $db_from )
			->join( "{$this->registration_model->table} b", "a.NoReg = b.NoReg", "LEFT OUTER" )
			->join( "{$this->section_m->table} c", "a.SectionID = c.SectionID", "LEFT OUTER" )
			->join( "{$this->patient_type_m->table} d", "b.JenisKerjasamaID = d.JenisKerjasamaID","LEFT OUTER")
			->join( "{$this->patient_m->table} e", "b.NRM= e.NRM","LEFT OUTER")
			->order_by("a.NoReg");
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
			$date = DateTime::createFromFormat("Y-m-d H:i:s.u", $row->Tanggal);
			$date_reg = DateTime::createFromFormat("Y-m-d H:i:s.u", $row->TglReg);
			$time = DateTime::createFromFormat("Y-m-d H:i:s.u", $row->Jam ); 
			
			$row->Tanggal = $date->format('Y-m-d');
			$row->TglReg = $date_reg->format('Y-m-d');
			$row->Jam = $time->format('Y-m-d H:i:s');
      
            $output['data'][] = $row;
        }
		
		$this->template
			->build_json( $output );
    }

	public function lookup_registration_datatable( $is_ajax_request=false )
	{
		
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			return $this->load->view( 'general_payment/lookup/datatable_registration', array(), TRUE );
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
			
			$db_like[ $this->db->escape_str("a.NoBukti") ] = $keywords;
			$db_like[ $this->db->escape_str("b.NRM") ] = $keywords;
			$db_like[ $this->db->escape_str("b.NoReg") ] = $keywords;
			$db_like[ $this->db->escape_str("b.NamaPasien_Reg") ] = $keywords;
			 
			
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



