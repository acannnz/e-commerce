<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bank_cash_deposit extends Admin_Controller
{ 
	protected $_translation = 'bank_cash_deposit';	
	protected $_model = 'bank_cash_deposit_m'; 
	  
	public function __construct()  
	{ 
		parent::__construct();
		$this->simple_login->check_user_role('cashier');
		
		$this->page = lang("nav:bank_cash_deposit");
		$this->template->title( lang("bank_cash_deposit:page") . ' - ' . $this->config->item('company_name') );
		
		$this->load->model('bank_cash_deposit_m');
		$this->load->model('common/section_m');

		$this->load->helper('bank_cash_deposit');
	}
	
	public function index()
	{
		$data = array(
				'page' => $this->page,
				"form" => TRUE,
				'datatables' => TRUE,
			);
		
		$this->template
			->set( "heading", lang("bank_cash_deposit:list_heading") )
			->set_breadcrumb( lang("bank_cash_deposit:page"), base_url("cashier/bank-cash-deposit") )
			->build('cashier/bank_cash_deposit/datatable', (isset($data) ? $data : NULL));
	}
		
	public function create()
	{
		//SECTION KASIR
		$section = $this->db->where('SectionID', 'SEC079')->get('SIMmSection')->row();

		$item = (object) array(
				'NoBukti' => bank_cash_deposit_helper::gen_evidence_number(),
				'Tanggal' => date("Y-m-d H:i:s"),
				'Jam' => date("Y-m-d H:i:s"),
				'Keterangan' => NULL,
				'Nilai' => 0,
				'AkunID' => 0,
				'Batal' => 0,
				'Posting' => 0,
				'UserID' => $this->user_auth->User_ID,
				'Tipe' => 'SKB',
				'TipeTransaksi' => 'KAS',
				'MerchanID' => NULL,
				'AkunMerchanID' => NULL,
				'SectionID' => 'SEC079', //Section KASIR
				'DokterID' => '',
				'Shift' => $this->session->userdata('shift_id'),
				'TglHonor' => date("Y-m-d"),
				'HonorBruto' => NULL,
				'Pajak' => NULL,
				'DIterimaDari' => NULL,
				'NoRegPasien' => '',
				'NilaiTunaiOrig' => 0,
				'SectionID' => $section->SectionID
			);
		
		if( $this->input->post() ) 
		{
			
			$validation = TRUE;
			$data_post = $this->input->post("f");
			$item = (object) array_merge( (array) $item, $data_post);
						
			$this->load->library( 'form_validation' );
			$this->form_validation->set_rules( $this->bank_cash_deposit_m->rules['insert'] );
			$this->form_validation->set_data( (array) $item );
			
			if( $this->form_validation->run() && $validation )
			{				
				$this->db->trans_begin();
					$this->db->insert("SIMtrPenerimaanNonPasien", $item );				
					
					// Insert User Aktivities
					$shift = $this->db->where("IDShift", $item->Shift)->get("SIMmShift")->row();
					$activities_description = sprintf( "%s # %s # %s # %s ", "INPUT SETORAN KAS KE BANK.", $item->NoBukti, $item->Keterangan, $shift->Deskripsi, $this->user_auth->Nama_Singkat );			
					$this->db->query("EXEC InsertUserActivities '$item->Tanggal','$item->Jam', {$this->user_auth->User_ID} ,'$item->NoBukti','$activities_description','SIMtrPenerimaanNonPasien'");
	
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
						"NoBukti" => $item->NoBukti,
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
					"section" => $section,
					"user" => $this->user_auth,
					"lookup_account_cash_bank" => base_url("cashier/bank-cash-deposit/lookup_account_cash_bank"),
					"lookup_merchan" => base_url("cashier/bank-cash-deposit/lookup_merchan"),
					"is_ajax_request" => TRUE,
					"is_modal" => TRUE,
					"is_edit" => FALSE,
				);
			
			$this->load->view( 
					'bank_cash_deposit/modal/create_edit', 
					array('form_child' => $this->load->view('bank_cash_deposit/form', $data, true))
				);
		} else
		{
			
			$data = array(
				"page" => $this->page."_".strtolower(__FUNCTION__),
				"item" => $item,
				"user" => $this->user_auth,
				"lookup_account_cash_bank" => base_url("cashier/bank-cash-deposit/lookup_account_cash_bank"),
				"lookup_merchan" => base_url("cashier/bank-cash-deposit/lookup_merchan"),
				"form" => TRUE,
				"datatables" => TRUE,
				"section" => $section,
			);

			$this->template
				->set( "heading", lang("bank_cash_deposit:pay_heading") )
				->set_breadcrumb( lang("bank_cash_deposit:breadcrumb"), base_url("cashier/bank-cash-deposit") )
				->set_breadcrumb( lang("bank_cash_deposit:pay_heading") )
				->build('bank_cash_deposit/form', $data);
		}
	}
		
	public function edit( $NoBukti = 0 )
	{	
		if ( $NoBukti == 0)	  
		{
			make_flashdata(array(
				'response_status' => 'error',
				'message' => "URL Tidak Sah!"
			));
			redirect("cashier/bank-cash-deposit");
		}
	  	
		$item = bank_cash_deposit_helper::get_bank_cash_deposit( $NoBukti );
		$section = $this->db->where("SectionID", $item->SectionID)->get("SIMmSection")->row();					

		if ( $item->Posting == 1 || $item->Batal == 1 )	  
		{
			redirect("cashier/bank-cash-deposit/view/$NoBukti");
		}

		if( $this->input->post() ) 
		{
			
			$validation = TRUE;
			
			$data_post = $this->input->post("f");
			$item = $this->db->where( "NoBukti", $NoBukti )->get("SIMtrPenerimaanNonPasien")->row();
			$item = (object) array_merge( (array) $item, $data_post);
			
			$this->load->library( 'form_validation' );
			//$this->form_validation->set_rules( $this->reservation_m->rules['insert'] );
			$this->form_validation->set_data( (array) $item );
			
			//print_r($item);exit;		
			if( !$this->form_validation->run() && $validation )
			{				
				$this->db->trans_begin();
					$this->db->update("SIMtrPenerimaanNonPasien", $item, array("NoBukti" => $NoBukti) );

					// Insert User Aktivities
					$shift = $this->db->where("IDShift", $item->Shift)->get("SIMmShift")->row();
					$activities_description = sprintf( "%s # %s # %s # %s ", "EDIT SETORAN KAS KE BANK.", $item->NoBukti, $item->Keterangan, $shift->Deskripsi, $this->user_auth->Nama_Singkat );			
					$this->db->query("EXEC InsertUserActivities '$item->Tanggal','$item->Jam', {$this->user_auth->User_ID} ,'$item->NoBukti','$activities_description','SIMtrPenerimaanNonPasien'");
	
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
						"NoBukti" => $item->NoBukti,
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
					"section" => $section,
					"user" => $this->user_auth,
					"lookup_account_cash_bank" => base_url("cashier/bank-cash-deposit/lookup_account_cash_bank"),
					"lookup_merchan" => base_url("cashier/bank-cash-deposit/lookup_merchan"),
					"print_kwitansi_link" => base_url("cashier/bank-cash-deposit/print_kwitansi/{$item->NoBukti}"),
					"cancel_link" => base_url("cashier/bank-cash-deposit/cancel/$item->NoBukti"),
					"is_ajax_request" => TRUE,
					"is_modal" => TRUE,
					"is_edit" => TRUE,
				);
			
			$this->load->view( 
					'bank_cash_deposit/modal/create_edit', 
					array('form_child' => $this->load->view('bank_cash_deposit/form', $data, true))
				);
		} else
		{
			$data = array(
				"page" => $this->page."_".strtolower(__FUNCTION__),
				"item" => $item,
				"section" => $section,
				"user" => $this->user_auth,
				"lookup_account_cash_bank" => base_url("cashier/bank-cash-deposit/lookup_account_cash_bank"),
				"lookup_merchan" => base_url("cashier/bank-cash-deposit/lookup_merchan"),
				"print_kwitansi_link" => base_url("cashier/bank-cash-deposit/print_kwitansi/{$item->NoBukti}"),
				"cancel_link" => base_url("cashier/bank-cash-deposit/cancel/$item->NoBukti"),
				"form" => TRUE,
				"datatables" => TRUE,
				"is_edit" => TRUE,
			);
			
			$this->template
				->set( "heading", lang("bank_cash_deposit:edit_heading") )
				->set_breadcrumb( lang("bank_cash_deposit:breadcrumb"), base_url("cashier/bank-cash-deposit") )
				->set_breadcrumb( lang("bank_cash_deposit:edit_heading") )
				->build('bank_cash_deposit/form', $data);
		}
	}

	public function view( $NoBukti = NULL )
	{
		if ( $NoBukti == NULL )	  
		{
			make_flashdata(array(
				'response_status' => 'error',
				'message' => "URL Tidak Sah!"
			));
			redirect("cashier/bank-cash-deposit");
		}
	  	
		$item = bank_cash_deposit_helper::get_bank_cash_deposit( $NoBukti );
		$section = $this->db->where("SectionID", $item->SectionID)->get("SIMmSection")->row();					
				
		if( $this->input->is_ajax_request() )
		{
			$data = array(
					"item" => $item,
					"section" => $section,
					"user" => $this->user_auth,
					"print_kwitansi_link" => base_url("cashier/bank-cash-deposit/print_kwitansi/{$item->NoBukti}"),
					"cancel_link" => base_url("cashier/bank-cash-deposit/cancel/$item->NoBukti"),
					"is_ajax_request" => TRUE,
					"is_modal" => TRUE,
					"is_edit" => TRUE,
				);
			
			$this->load->view( 
					'bank_cash_deposit/modal/view', 
					array('form_child' => $this->load->view('bank_cash_deposit/view', $data, true))
				);
		} else
		{
			$data = array(
				"page" => $this->page."_".strtolower(__FUNCTION__),
				"item" => $item,
				"section" => $section,
				"user" => $this->user_auth,
				"print_kwitansi_link" => base_url("cashier/bank-cash-deposit/print_kwitansi/{$item->NoBukti}"),
				"cancel_link" => base_url("cashier/bank-cash-deposit/cancel/$item->NoBukti"),
				"form" => TRUE,
				"datatables" => TRUE,
				"is_edit" => TRUE,
			);

			$this->template
				->set( "heading", lang("bank_cash_deposit:view_heading") )
				->set_breadcrumb( lang("bank_cash_deposit:breadcrumb"), base_url("cashier/bank-cash-deposit") )
				->set_breadcrumb( lang("bank_cash_deposit:view_heading") )
				->build('bank_cash_deposit/view', $data);
		}
	}
		
	public function cancel( $NoBukti = 0 )
	{
		
		$item = $this->db->where( array("NoBukti" => $NoBukti))->get("SIMtrPenerimaanNonPasien")->row();		

		if( $this->input->post() ) 
		{
			
			
			if( empty($item) )
			{
				make_flashdata(array(
						'response_status' => 'error',
						'message' => lang( 'global:get_failed' )
					));
			
				redirect( "cashier/bank-cash-deposit/edit/$item->NoBukti" );
			}

			if( $item->POsted == 1 )
			{
				make_flashdata(array(
						'response_status' => 'error',
						'message' => 'Tidak bisa membatalkan Transaksi : Transaksi ini Sudah di Posting.'
					));
			
				redirect( "cashier/bank-cash-deposit/edit/$item->NoBukti" );
			}
			
			if( $item->NoBukti == $this->input->post( 'confirm' ) )
			{
				
				$this->db->trans_begin();
					$this->db->update("SIMtrPenerimaanNonPasien", array("Batal" => 1), array("NoBukti" => $item->NoBukti) );					
					
					// Insert User Aktivities
					$shift = $this->db->where("IDShift", $item->Shift)->get("SIMmShift")->row();
					$activities_description = sprintf( "%s # %s # %s # %s ", "BATAL SETORAN KAS KE BANK.", $item->NoBukti, $shift->Deskripsi, 'USER', $this->user_auth->Nama_Singkat );			
					$this->db->query("EXEC InsertUserActivities '$item->Tanggal','$item->Jam', {$this->user_auth->User_ID} ,'$item->NoBukti','$activities_description','SIMtrPenerimaanNonPasien'");
	
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
						
					redirect("cashier/bank-cash-deposit/edit/$item->NoBukti" );
				}	
			} 
			redirect( $this->input->post( 'r_url' ) );
		}
		
		$data = array(
			"item" => $item,
			"delete_url" => current_url(),
		);
			
		$this->load->view( 'bank_cash_deposit/modal/cancel', $data );
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

			$item = bank_cash_deposit_helper::get_bank_cash_deposit( $NoBukti );
			$spelled = bank_cash_deposit_helper::money_to_text( number_format($item->Nilai, 0, '', '') ); // terbilang
			$user = $this->user_auth;
			$data = array(
				"item" => $item,
				"spelled" => $spelled,
				"for_payment" => $item->Keterangan,
				"user" => $user,
			);

			// PDF Content
			$html_content = $this->load->view( "bank_cash_deposit/print/kwitansi", $data, TRUE );    
			$file_name = "Kwitansi-Penerimaan-Non-Invoice-$NoBukti.pdf";
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
	
	public function lookup_account_cash_bank( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'bank_cash_deposit/lookup/account_cash_bank', (isset($data) ? $data : NULL) );
		} 
	}

	public function lookup_merchan( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'bank_cash_deposit/lookup/merchans', (isset($data) ? $data : NULL) );
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
				->set_breadcrumb( lang("bank_cash_deposit:page"), base_url("bank_cash_deposit") )
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
		
		$db_from = "{$this->bank_cash_deposit_m->table} a";
		$db_where = array();
		$db_like = array();
		
		// Preparing defaul filter
		$db_where['a.Tipe'] = 'SKB';
		// $db_where['a.SectionID'] = 'SEC079';
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
			$db_like[ $this->db->escape_str("a.Tanggal") ] = $keywords;
			$db_like[ $this->db->escape_str("a.debet") ] = $keywords;
			$db_like[ $this->db->escape_str("a.kredit") ] = $keywords;
			$db_like[ $this->db->escape_str("a.deskripsi") ] = $keywords;
        }
		
		//get total records
		$this->db->from( $db_from )
			->join( "{$this->section_m->table} b", "a.SectionID = b.SectionID", "LEFT OUTER" )
			->join( "Mst_akun c", "a.AkunID = c.akun_ID", "LEFT OUTER" )
			->join( "mUser d", "a.UserID = d.User_ID", "LEFT OUTER" )
			;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		$records_total = $this->db->count_all_results();
		
		// get total filtered
		$this->db
			->from( $db_from )
			->join( "{$this->section_m->table} b", "a.SectionID = b.SectionID", "LEFT OUTER" )
			->join( "Mst_akun c", "a.AkunID = c.akun_ID", "LEFT OUTER" )
			->join( "mUser d", "a.UserID = d.User_ID", "LEFT OUTER" )
			;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }		
		$records_filtered = $this->db->count_all_results();
		
		// get result filtered
		$db_select = <<<EOSQL
			a.NoBukti,
			a.Tanggal,			
			a.Jam,			
			a.Nilai,			
			a.Keterangan,			
			a.Batal,			
			a.Posting,			
			b.SectionName,
			c.akun_Name,
			d.Nama_Singkat,
			
EOSQL;

		$this->db
			->select( $db_select )
			->from( $db_from )
			->join( "{$this->section_m->table} b", "a.SectionID = b.SectionID", "LEFT OUTER" )
			->join( "Mst_akun c", "a.AkunID = c.akun_ID", "LEFT OUTER" )
			->join( "mUser d", "a.UserID = d.User_ID", "LEFT OUTER" )
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
            $output['data'][] = $row;
        }

		$this->template
			->build_json( $output );
    }


}



