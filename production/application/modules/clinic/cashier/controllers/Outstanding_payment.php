<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Outstanding_payment extends Admin_Controller
{ 
	protected $_translation = 'outstanding_payment';	
	protected $_model = 'outstanding_payment_m'; 
	  
	public function __construct()  
	{ 
		parent::__construct();
		$this->simple_login->check_user_role('cashier');
		
		$this->page = lang("nav:outstanding_payment");
		$this->template->title( lang("outstanding_payment:page") . ' - ' . $this->config->item('company_name') );
		
		$this->load->model('outstanding_payment_m');
		$this->load->model('registrations/registration_model');
		$this->load->model('common/patient_m');
		$this->load->model('common/patient_type_m');

		$this->load->helper('outstanding_payment');
	}
	
	public function index()
	{
		$data = array(
				'page' => $this->page,
				"form" => TRUE,
				'datatables' => TRUE,
			);
		
		$this->template
			->set( "heading", lang("outstanding_payment:list_heading") )
			->set_breadcrumb( lang("outstanding_payment:page"), base_url("cashier/outstanding-payment") )
			->build('cashier/outstanding_payment/datatable', (isset($data) ? $data : NULL));
	}
		
	public function pay()
	{
	  
		$item = (object) array(
				'NoBukti' => outstanding_payment_helper::gen_evidence_number(),
				'NoInvoice' => NULL,
				'NoReg' => NULL,
				'Tanggal' => date("Y-m-d H:i:s"),
				'Jam' => date("Y-m-d H:i:s"),
				'UserID' => $this->user_auth->User_ID,
				'NilaiAwal' => 0,
				'NilaiAkumulaiPembayaran' => 0,
				'NilaiPembayaran' => 0,
				'Tunai' => 0,
				'CC' => 0,
				'BRITunai' => 0,
				'NoKartu' => NULL,
				'AddCharge_Persen' => 0,
				'AddCharge' => 0,
				'Lunas' => '0',
				'IDBank' => '',
				'Batal' => 0,
				'SudahAdaPembayaranBaru' => 0,
				'Diskon' => 0,
				'PotongHonor' => 0,
				'DokterID' => '',
				'JenisHonor' => '',
				'TanggalHonor' => date("Y-m-d"),
				'Others' => '',
				'TanggalOthers' => date("Y-m-d"),
				// 'Shift' => @$this->user_auth->shift_name ? $this->user_auth->shift_name : 'PAGI',
				'Shift' => $this->session->userdata('shift_id'),
				'SectionID' => 'SEC079',
			);
						
		if( $this->input->post() ) 
		{
			
			$validation = TRUE;
			$data_post = $this->input->post("f");
			$patient = (object) $this->input->post("p");
			$item = (object) array_merge( (array) $item, $data_post);
			
			if ( $item->NilaiPembayaran  > ( $item->NilaiAwal - $item->NilaiAkumulaiPembayaran) )			
			{					
				$validation = FALSE;
				$validation_message = "Nilai Pembayaran Tidak Boleh lebih besar dari Kewajiban.";
			}

			if ( $item->CC == 1 )			
			{				
				if ( empty($item->IDBank) || $item->AddCharge_Persen == 0 || $item->AddCharge == 0 )	
				{
					$validation = FALSE;
					$validation_message = "Pembayaran dengan Kartu Kredit diharapkan mengisi Semua Field dengan Benar!";
				}
			}
			
			$this->load->library( 'form_validation' );
			//$this->form_validation->set_rules( $this->reservation_m->rules['insert'] );
			$this->form_validation->set_data( (array) $item );
			
			//print_r($item);exit;		
			if( !$this->form_validation->run() && $validation )
			{				
				$this->db->trans_begin();
					$this->db->insert("SIMtrPembayaranOutStanding", $item );				
					$this->db->update("SIMtrPembayaranOutStanding", array("SudahAdaPembayaranBaru" => 1), array("NoBukti <>" => $item->NoBukti, "NoInvoice" => $item->NoInvoice) );					
					$this->db->update("SIMtrKasir", array("Cair" => 1, "TglCair" => date("Y-m-d")), array("NoBukti" => $item->NoInvoice) );					
					
					$remain = $item->NilaiAwal - ($item->NilaiPembayaran + $item->NilaiAkumulaiPembayaran);
					if ( $remain == 0 )
					{
						$this->db->update("SIMtrKasir", array("Lunas" => 1, "TglLunas" => date("Y-m-d")), array("NoBukti" => $item->NoInvoice) );					
					}
					
					//$section = $this->db->where("SectionID", "SEC080")->get("SIMmSection")->row();					
					// Insert User Aktivities
					$activities_description = sprintf( "%s # %s # %s # %s # %s", "INPUT PEMBAYARAN OUTSTANDING.", $item->NoBukti, $item->NoInvoice, $item->NoReg, $patient->NRM, $patient->NamaPasien );			
					$this->db->query("EXEC InsertUserActivities '$item->Tanggal','$item->Jam', {$this->user_auth->User_ID} ,'$item->NoBukti','$activities_description','SIMtrPembayaranOutStanding'");
	
				if ($this->db->trans_status() === FALSE)
				{
					$this->db->trans_rollback();
					$response = array(
						"status" => 'error',
						"message" => lang('global:created_failed'),
						"code" => 500
					);
				}
				else
				{
					$this->db->trans_commit();
					$response = array(
						"NoBukti" => rawurlencode($item->NoBukti),
						"status" => 'success',
						"message" => lang('global:created_successfully'),
						"code" => 200
					);
				}				

			} else
			{
				$response = array(
					"status" => 'error',
					"message" => !empty($validation_message) ? $validation_message : $this->form_validation->get_all_error_string(),
					"code" => 500
				);
			}
			
			print_r( json_encode($response, JSON_NUMERIC_CHECK));
			exit(0);
			
		}
		
		if( $this->input->is_ajax_request() )
		{
			$data = array(
					"item" => $item,
					"lookup_invoice" => base_url("cashier/outstanding-payment/lookup_invoice"),
					"lookup_merchant" => base_url("cashier/outstanding-payment/lookup_merchant"),
					"submit_url" => current_url(),
					"is_ajax_request" => TRUE,
					"is_modal" => TRUE,
					"is_edit" => FALSE,
				);
			
			$this->load->view( 
					'outstanding_payment/modal/create_edit', 
					array('form_child' => $this->load->view('outstanding_payment/form', $data, true))
				);
		} else
		{
			$data = array(
				"page" => $this->page."_".strtolower(__FUNCTION__),
				"item" => $item,
				"lookup_invoice" => base_url("cashier/outstanding-payment/lookup_invoice"),
				"lookup_merchan" => base_url("cashier/outstanding-payment/lookup_merchan"),
				"submit_url" => current_url(),
				"form" => TRUE,
				"datatables" => TRUE,
			);

			$this->template
				->set( "heading", lang("outstanding_payment:pay_heading") )
				->set_breadcrumb( lang("outstanding_payment:breadcrumb"), base_url("cashier/outstanding_payment") )
				->set_breadcrumb( lang("outstanding_payment:pay_heading") )
				->build('outstanding_payment/form', $data);
		}
	}
		
	public function edit( $NoBukti = 0 )
	{
		$NoBukti = $this->input->get("NoBukti") ? rawurldecode($this->input->get("NoBukti")) : $NoBukti;

		//echo $NoBukti;exit;
		
		if ( $NoBukti == 0)	  
		{
			make_flashdata(array(
				'response_status' => 'error',
				'message' => "URL Tidak Sah!"
			));
			redirect("cashier/outstanding-payment");
		}
	  	
		$item = outstanding_payment_helper::get_outstanding( $NoBukti );

		if ( $item->SudahAdaPembayaranBaru == 1 && $item->Audit == 1)	  
		{
			$this->view($NoBukti);
		}

		if( $this->input->post() ) 
		{
			
			$validation = TRUE;

			$data_post = $this->input->post("f");
			$patient = (object) $this->input->post("p");

			$item = $this->db->where( "NoBukti", $NoBukti )->get("SIMtrPembayaranOutStanding")->row();
			$item = (object) array_merge( (array) $item, $data_post);
			
			if ( $item->NilaiPembayaran  > ( $item->NilaiAwal - $item->NilaiAkumulaiPembayaran) )			
			{					
				$validation = FALSE;
				$validation_message = "Nilai Pembayaran Tidak Boleh lebih besar dari Kewajiban.";
			}

			if ( $item->CC == 1 )			
			{				
				if ( empty($item->IDBank) || $item->AddCharge_Persen == 0 || $item->AddCharge == 0 )	
				{
					$validation = FALSE;
					$validation_message = "Pembayaran dengan Kartu Kredit diharapkan mengisi Semua Field dengan Benar!";
				}
			}
			
			$this->load->library( 'form_validation' );
			//$this->form_validation->set_rules( $this->reservation_m->rules['insert'] );
			$this->form_validation->set_data( (array) $item );
			
			//print_r($item);exit;		
			if( !$this->form_validation->run() && $validation )
			{				
				$this->db->trans_begin();
					$this->db->update("SIMtrPembayaranOutStanding", $item, array("NoBukti" => $NoBukti) );

					$remain = $item->NilaiAwal - ($item->NilaiPembayaran + $item->NilaiAkumulaiPembayaran);
					if ( $remain == 0 )
					{
						$this->db->update("SIMtrKasir", array("Lunas" => 1, "TglLunas" => date("Y-m-d")), array("NoBukti" => $item->NoInvoice) );					
					} else {
						$this->db->update("SIMtrKasir", array("Lunas" => 0), array("NoBukti" => $item->NoInvoice) );
					}
					
					//$section = $this->db->where("SectionID", "SEC080")->get("SIMmSection")->row();					
					// Insert User Aktivities
					$activities_description = sprintf( "%s # %s # %s # %s # %s", "EDIT PEMBAYARAN OUTSTANDING.", $item->NoBukti, $item->NoInvoice, $item->NoReg, $patient->NRM, $patient->NamaPasien );			
					$this->db->query("EXEC InsertUserActivities '$item->Tanggal',$this->user_auth->User_ID', $this->user ,'$item->NoBukti','$activities_description','SIMtrPembayaranOutStanding'");
	
				if ($this->db->trans_status() === FALSE)
				{
					$this->db->trans_rollback();
					$response = array(
						"status" => 'error',
						"message" => lang('global:created_failed'),
						"code" => 500
					);
				}
				else
				{
					$this->db->trans_commit();
					$response = array(
						"NoBukti" => rawurlencode($item->NoBukti),
						"status" => 'success',
						"message" => lang('global:created_successfully'),
						"code" => 200
					);
				}				

			} else
			{
				$response = array(
					"status" => 'error',
					"message" => !empty($validation_message) ? $validation_message : $this->form_validation->get_all_error_string(),
					"code" => 500
				);
			}
			
			print_r( json_encode($response, JSON_NUMERIC_CHECK));
			exit(0);
			
		}

		// Menentukan nilai kewajiban / obligation
		$item->Obligation = $item->NilaiAwal - $item->NilaiAkumulaiPembayaran ;
				
		if( $this->input->is_ajax_request() )
		{
			$data = array(
					"item" => $item,
					"lookup_merchan" => base_url("cashier/outstanding-payment/lookup_merchan"),
					"submit_url" => current_url()."?NoBukti=".rawurlencode($item->NoBukti),
					"cancel_payment_link" => base_url("cashier/outstanding-payment/cancel/")."?NoBukti=".rawurlencode($item->NoBukti),
					"print_kwitansi_link" => base_url("cashier/outstanding-payment/print_kwitansi/")."?NoBukti=".rawurlencode($item->NoBukti),
					"is_ajax_request" => TRUE,
					"is_modal" => TRUE,
					"is_edit" => TRUE,
				);
			
			$this->load->view( 
					'outstanding_payment/modal/view', 
					array('form_child' => $this->load->view('outstanding_payment/view', $data, true))
				);
		} else
		{
			$data = array(
				"page" => $this->page."_".strtolower(__FUNCTION__),
				"item" => $item,
				"lookup_merchan" => base_url("cashier/outstanding-payment/lookup_merchan"),
				"cancel_payment_link" => base_url("cashier/outstanding-payment/cancel/")."?NoBukti=".rawurlencode($item->NoBukti),
				"print_kwitansi_link" => base_url("cashier/outstanding-payment/print_kwitansi/")."?NoBukti=".rawurlencode($item->NoBukti),
				"dp_billing_link" => base_url("cashier/outstanding-payment/dp_billing/$item->NoBukti"),
				"submit_url" => current_url()."?NoBukti=".rawurlencode($item->NoBukti),
				"form" => TRUE,
				"datatables" => TRUE,
				"is_edit" => TRUE,
			);
			
			$this->template
				->set( "heading", lang("outstanding_payment:view_heading") )
				->set_breadcrumb( lang("outstanding_payment:breadcrumb"), base_url("cashier/outstanding_payment") )
				->set_breadcrumb( lang("outstanding_payment:view_heading") )
				->build('outstanding_payment/form', $data);
		}
	}

	public function view( $NoBukti = NULL )
	{
		$NoBukti = $NoBukti ? $NoBukti : rawurldecode($this->input->get("NoBukti")) ;
		
		if ( $NoBukti == NULL )	  
		{
			make_flashdata(array(
				'response_status' => 'error',
				'message' => "URL Tidak Sah!"
			));
			redirect("cashier/outstanding-payment");
		}
	  	
		$item = outstanding_payment_helper::get_outstanding( $NoBukti );
				
		if( $this->input->is_ajax_request() )
		{
			$data = array(
					"item" => $item,
					"cancel_payment_link" => base_url("cashier/outstanding-payment/cancel/")."?NoBukti=".rawurlencode($item->NoBukti),
					"print_kwitansi_link" => base_url("cashier/outstanding-payment/print_kwitansi/")."?NoBukti=".rawurlencode($item->NoBukti),
					"dp_billing_link" => base_url("cashier/outstanding-payment/dp_billing/$item->NoBukti"),
					"is_ajax_request" => TRUE,
					"is_modal" => TRUE,
					"is_edit" => TRUE,
				);
			
			$this->load->view( 
					'outstanding_payment/modal/view', 
					array('form_child' => $this->load->view('outstanding_payment/view', $data, true))
				);
		} else
		{
			$data = array(
				"page" => $this->page."_".strtolower(__FUNCTION__),
				"item" => $item,
				"cancel_payment_link" => base_url("cashier/outstanding-payment/cancel/")."?NoBukti=".rawurlencode($item->NoBukti),
				"print_kwitansi_link" => base_url("cashier/outstanding-payment/print_kwitansi/")."?NoBukti=".rawurlencode($item->NoBukti),
				"dp_billing_link" => base_url("cashier/outstanding-payment/dp_billing/$item->NoBukti"),
				"form" => TRUE,
				"datatables" => TRUE,
				"is_edit" => TRUE,
			);

			$this->template
				->set( "heading", lang("outstanding_payment:view_heading") )
				->set_breadcrumb( lang("outstanding_payment:breadcrumb"), base_url("cashier/outstanding_payment") )
				->set_breadcrumb( lang("outstanding_payment:view_heading") )
				->build('outstanding_payment/view', $data);
		}
	}
		
	public function cancel( $NoBukti = 0 )
	{
		
		$NoBukti = $NoBukti ? $NoBukti : rawurldecode($this->input->get("NoBukti")) ;
		$item = $this->db->where( array("NoBukti" => $NoBukti, "Batal" => 0))->get("SIMtrPembayaranOutStanding")->row();		

		if( $this->input->post() ) 
		{
			
			
			if( empty($item) )
			{
				make_flashdata(array(
						'response_status' => 'error',
						'message' => lang( 'global:get_failed' )
					));
			
				redirect( "cashier/outstanding-payment/edit/?NoBukti=".rawurlencode($item->NoBukti) );
			}
			
			if( $item->SudahAdaPembayaranBaru == 1 )
			{
				make_flashdata(array(
						'response_status' => 'error',
						'message' => 'Tidak bisa membatalkan Transaksi : Sudah ada Transaksi dengan Kwitansi Baru.'
					));
			
				redirect( "cashier/outstanding-payment/edit/?NoBukti=".rawurlencode($item->NoBukti) );
			}

			if( $item->Audit == 1 )
			{
				make_flashdata(array(
						'response_status' => 'error',
						'message' => 'Tidak bisa membatalkan Transaksi : Transaksi ini Sudah di Audit.'
					));
			
				redirect( "cashier/outstanding-payment/edit/?NoBukti=".rawurlencode($item->NoBukti) );
			}
			
			if( $item->NoBukti == $this->input->post( 'confirm' ) )
			{
				
				$this->db->trans_begin();
					$this->db->update("SIMtrPembayaranOutStanding", array("Batal" => 1), array("NoBukti" => $item->NoBukti) );					
					$this->db->update("SIMtrKasir", array("Lunas" => 0), array("NoBukti" => $item->NoInvoice) );
					$section = $this->db->where("SectionID", "SEC080")->get("SIMmSection")->row();
					
					// Insert User Aktivities
					$activities_description = sprintf( "%s # %s # %s # %s # %s", "Cancel PEMBAYARAN OUTSTANDING.", $item->NoBukti, $item->NoInvoice, $item->NoReg, $item->NamaPasien );			
					$this->db->query("EXEC InsertUserActivities '$item->Tanggal','$item->Jam', {$this->user_auth->User_ID} ,'$item->NoBukti','$activities_description','SIMtrPembayaranOutStanding'");
	
				if ($this->db->trans_status() === FALSE)
				{
					$this->db->trans_rollback();
					make_flashdata(array(
							'response_status' => 'success',
							'message' => lang('global:cancel_failed')
						));
				}
				else
				{
					$this->db->trans_commit();
					make_flashdata(array(
							'response_status' => 'success',
							'message' => lang('global:cancel_successfully')
						));
						
					redirect("cashier/outstanding-payment/edit/?NoBukti=".rawurlencode($item->NoBukti));
				}	
			} 
			redirect( $this->input->post( 'r_url' ) );
		}
		
		$data = array(
			"item" => $item,
			"delete_url" => current_url()."?NoBukti=".rawurlencode($item->NoBukti),
		);
			
		$this->load->view( 'outstanding_payment/modal/cancel', $data );
	}
	
	// Print Kwitansi
	public function print_kwitansi( $NoBukti = 0 )
	{
		$NoBukti = $this->input->get("NoBukti") ? $this->input->get("NoBukti") : $NoBukti;
		
		if ( $this->input->is_ajax_request() || $NoBukti )		
		{

			$response = array(
				"status" => "success",
				"message" => "",
				"code" => 200
			);
						
			if ( $NoBukti == 0 )
			{
				$response = array(
					"status" => "error",
					"message" => lang( 'global:get_failed' ),
					"code" => 500
				);
				print_r(json_encode( $response, JSON_NUMERIC_CHECK ));
				exit(0);
			}

			$item = outstanding_payment_helper::get_outstanding( $NoBukti );
			$spelled = outstanding_payment_helper::money_to_text( number_format($item->NilaiPembayaran, 0, '', '') ); // terbilang
			$user = $this->user_auth;
			$data = array(
				"item" => $item,
				"spelled" => $spelled,
				"for_payment" => "OutStanding",
				"user" => $user,
			);

			// PDF Content
			$html_content = $this->load->view( "outstanding_payment/print/kwitansi", $data, TRUE );    
			$file_name = "Kwitansi-OutStanding-$NoBukti.pdf";
			$footer = '';
			//print_r($user);exit;
			$this->load->helper("export");
			
			export_helper::generate_pdf( $html_content, $file_name, $footer , $margin_bottom = 5, $header = NULL, $margin_top = 2, $orientation = 'P', $margin_left = 2, $margin_right = 2);
			exit(0);
					
		}
	}
	
	// DP = Direct Print
	public function dp_billing ()
	{
		
		$connector = new FilePrintConnector("/dev/usb/lp0");
					
		try {
			// Enter the share name for your USB printer here
			//$connector = null;
			$connector = new WindowsPrintConnector("Receipt Printer");
			/* Print a "Hello world" receipt" */
			$printer = new Printer($connector);
			$printer -> text("Hello World!\n");
			$printer -> cut();
			
			/* Close printer */
			$printer -> close();
			$response = array(
					"status" => "success",
					"message" => "Data Billing Berhasil Dicetak!",
					"code" => 200
			);

		} catch (Exception $e) {
			echo 
			$response = array(
					"status" => "erorr",
					"message" => "Couldn't print to this printer: " . $e -> getMessage(),
					"code" => 500
			);
		}
		
		print_r(json_encode($response, JSON_NUMERIC_CHECK));
		exit(0);
			
	}
	
	public function lookup_invoice( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'outstanding_payment/lookup/invoices', (isset($data) ? $data : NULL) );
		} 
	}
	
	public function lookup_merchan( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'outstanding_payment/lookup/merchans', (isset($data) ? $data : NULL) );
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
				->set_breadcrumb( lang("outstanding_payment:page"), base_url("outstanding_payment") )
				->set_breadcrumb( "Lookup Box" )
				->build('registrations/lookup', (isset($data) ? $data : NULL));
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
		
		$db_from = "{$this->outstanding_payment_m->table} a";
		$db_where = array();
		$db_like = array();
		
		if ($this->input->get_post("date_from"))
		{
			$db_where['a.Tanggal >='] = $this->input->get_post("date_from");
		}

		if ($this->input->get_post("date_till"))
		{
			$db_where['a.Tanggal <='] = $this->input->get_post("date_till");
		}
				
		// preparing default
		if( isset($search['value']) && ! empty($search['value']) )
        {
            $keywords = $this->db->escape_str( $search['value'] );
						
			$db_like[ $this->db->escape_str("a.NoBukti") ] = $keywords;
			$db_like[ $this->db->escape_str("a.Jam") ] = $keywords;
			$db_like[ $this->db->escape_str("a.NoInvoice") ] = $keywords;
			$db_like[ $this->db->escape_str("a.NoReg") ] = $keywords;
			$db_like[ $this->db->escape_str("c.NamaPasien") ] = $keywords;
			$db_like[ $this->db->escape_str("d.JenisKerjasama") ] = $keywords;
			$db_like[ $this->db->escape_str("e.Nama_Singkat") ] = $keywords;

        }
		
		//get total records
		$this->db->from( $db_from )
			->join( "{$this->registration_model->table} b", "a.NoReg = b.NoReg", "LEFT OUTER" )
			->join( "{$this->patient_m->table} c", "b.NRM = c.NRM", "LEFT OUTER" )
			->join( "{$this->patient_type_m->table} d", "b.JenisKerjasamaID = d.JenisKerjasamaID", "LEFT OUTER" )
			->join( "mUser e", "a.UserID = e.User_ID", "LEFT OUTER" )
			;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		$records_total = $this->db->count_all_results();
		
		// get total filtered
		$this->db
			->from( $db_from )
			->join( "{$this->registration_model->table} b", "a.NoReg = b.NoReg", "LEFT OUTER" )
			->join( "{$this->patient_m->table} c", "b.NRM = c.NRM", "LEFT OUTER" )
			->join( "{$this->patient_type_m->table} d", "b.JenisKerjasamaID = d.JenisKerjasamaID", "LEFT OUTER" )
			->join( "mUser e", "a.UserID = e.User_ID", "LEFT OUTER" )
			;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }		
		$records_filtered = $this->db->count_all_results();
		
		// get result filtered
		$db_select = <<<EOSQL
			a.NoBukti,
			a.Jam,			
			a.NoInvoice,
			a.NoReg,
			c.NamaPasien,
			d.JenisKerjasama,
			a.NilaiPembayaran,
			e.Nama_Singkat		
			
EOSQL;

		$this->db
			->select( $db_select )
			->from( $db_from )
			->join( "{$this->registration_model->table} b", "a.NoReg = b.NoReg", "LEFT OUTER" )
			->join( "{$this->patient_m->table} c", "b.NRM = c.NRM", "LEFT OUTER" )
			->join( "{$this->patient_type_m->table} d", "b.JenisKerjasamaID = d.JenisKerjasamaID", "LEFT OUTER" )
			->join( "mUser e", "a.UserID = e.User_ID", "LEFT OUTER" )
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
			/*$date = DateTime::createFromFormat("Y-m-d H:i:s.u", $row->TglReg);
			$time = DateTime::createFromFormat("Y-m-d H:i:s.u", $row->JamReg ); 
			
			$row->TglReg = $date->format('Y-m-d');
			$row->JamReg = $time->format('H:i:s');*/
			
            $output['data'][] = $row;
        }

		$this->template
			->build_json( $output );
    }

	public function lookup_invoice_datatable( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'outstanding_payment/lookup/invoice_datatable' );
		} 
	}
	
	public function lookup_invoice_collection()
	{
		$this->datatable_invoice_collection( 1 );
	}

	public function datatable_invoice_collection()
    {
        $start = $this->input->get_post('start', true);
        $length = $this->input->get_post('length', true);
        $order = $this->input->get_post('order', true);
        $columns = $this->input->get_post('columns', true);
        $search = $this->input->get_post('search', true);
        $draw = $this->input->get_post('draw', true);
		
		$db_from = "SIMtrKasir a";
		$db_where = array();
		$db_like = array();
		
		// Preparing defaul where
		$db_where['a.OutStanding'] = 1;
		$db_where['a.Batal'] = 0;
		$db_where['a.Lunas'] = 0;
		
		// preparing default
		if( isset($search['value']) && ! empty($search['value']) )
        {
            $keywords = $this->db->escape_str( $search['value'] );
						
			$db_like[ $this->db->escape_str("a.NoBukti") ] = $keywords;
			$db_like[ $this->db->escape_str("a.Tanggal") ] = $keywords;
			$db_like[ $this->db->escape_str("a.NoReg") ] = $keywords;
			$db_like[ $this->db->escape_str("c.NRM") ] = $keywords;
			$db_like[ $this->db->escape_str("c.NamaPasien") ] = $keywords;
			$db_like[ $this->db->escape_str("d.JenisKerjasama") ] = $keywords;
        }
		
		//get total records
		$this->db->from( $db_from )
			->join( "{$this->registration_model->table} b", "a.NoReg = b.NoReg", "LEFT OUTER" )
			->join( "{$this->patient_m->table} c", "b.NRM = c.NRM", "LEFT OUTER" )
			->join( "{$this->patient_type_m->table} d", "b.JenisKerjasamaID = d.JenisKerjasamaID", "LEFT OUTER" )
			;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		$records_total = $this->db->count_all_results();
		
		// get total filtered
		$this->db
			->from( $db_from )
			->join( "{$this->registration_model->table} b", "a.NoReg = b.NoReg", "LEFT OUTER" )
			->join( "{$this->patient_m->table} c", "b.NRM = c.NRM", "LEFT OUTER" )
			->join( "{$this->patient_type_m->table} d", "b.JenisKerjasamaID = d.JenisKerjasamaID", "LEFT OUTER" )
			;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }		
		$records_filtered = $this->db->count_all_results();
		
		// get result filtered
		$db_select = <<<EOSQL
			a.NoBukti,
			a.Tanggal,			
			a.Jam,			
			b.NoReg,
			c.NRM,
			c.NamaPasien,
			d.JenisKerjasama
			
EOSQL;

		$this->db
			->select( $db_select )
			->from( $db_from )
			->join( "{$this->registration_model->table} b", "a.NoReg = b.NoReg", "LEFT OUTER" )
			->join( "{$this->patient_m->table} c", "b.NRM = c.NRM", "LEFT OUTER" )
			->join( "{$this->patient_type_m->table} d", "b.JenisKerjasamaID = d.JenisKerjasamaID", "LEFT OUTER" )
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
			// Get Nilai Outstanding
			$row->NilaiOutStanding = outstanding_payment_helper::outstanding_value( $row->NoBukti );
			
			// Get Nilai Akumulasi Pembayaran
			$row->NilaiAkumulaiPembayaran = outstanding_payment_helper::accumulated_payment( $row->NoBukti );
            $output['data'][] = $row;
        }

		$this->template
			->build_json( $output );
    }


}



