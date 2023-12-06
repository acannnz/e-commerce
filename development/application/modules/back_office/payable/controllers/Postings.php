<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Postings extends Admin_Controller
{
	protected $_translation = 'payable';	
	protected $_model = 'posting_m';
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('payable');
				
		$this->load->helper("payable");
				
		$this->page = "payable";
		$this->template->title( lang("postings:page") . ' - ' . $this->config->item('company_name') );
	}
	
	public function index()
	{
		$data = array(
				'page' => $this->page,
				"beginning_balance_date" => payable_helper::get_beginning_balance_date(),
				"lookup_suppliers" => base_url("payable/postings/lookup_suppliers"),
				"posting_url" => base_url("payable/postings/posting"),
				"form" => TRUE,
				'datatables' => TRUE,
				"navigation_minimized" => TRUE
			);
		
		$this->template
			->set( "heading", lang("postings:page") )
			->set_breadcrumb( lang("postings:page"), base_url("payable/postings") )
			->build('postings/datatable', (isset($data) ? $data : NULL));
	}

	public function cancel()
	{
		$data = array(
				'page' => $this->page,
				"beginning_balance_date" => payable_helper::get_beginning_balance_date(),
				"lookup_suppliers" => base_url("payable/postings/lookup_suppliers"),
				"posting_cancel_url" => base_url("payable/postings/posting_cancel"),
				"form" => TRUE,
				'datatables' => TRUE,
				"navigation_minimized" => TRUE
			);
		
		$this->template
			->set( "heading", lang("postings:cancel_page") )
			->set_breadcrumb( lang("postings:page"), base_url("payable/postings") )
			->set_breadcrumb( lang("postings:cancel_page"), base_url("payable/postings/cancel") )
			->build('postings/datatable_cancel', (isset($data) ? $data : NULL));
	}
		
	public function view_factur( $id=0 )
	{
		$id = (int) @$id;
		
		$item = $this->db->where("id", $id)->get( "ar_facturs" )->row_array();
		
		if( ! $item ){ $item = array('id' => 0); }
		$this->load->library( 'my_object', $item, 'item' );

		if( $this->input->is_ajax_request() )
		{
			$data = array(
					'item' => $this->item,
					"house" => $this->house_m->get_house( $this->_house_id ),
					"options_type" => $this->type_m->options_type(),
					"supplier" => $this->db->where("id", $this->item->supplier_id)->get("common_suppliers")->row(),
					"is_ajax_request" => TRUE,
					"is_modal" => TRUE,
					"is_edit" => TRUE,
					"form" => TRUE,
					"datatables" => TRUE,
				);
			
			$this->load->view( 
					'postings/modal/create_edit', 
					array('form_child' => $this->load->view('postings/form_factur', $data, true))
				);
		} else
		{
			return false;
		}
	}

	public function view_voucher( $id=0 )
	{
		$id = (int) @$id;
		
		$item = $this->db->where("id", $id)->get( "ar_vouchers" )->row_array();
		$nota_detail = $this->db->where("voucher_number", $item['voucher_number'])->get( "ar_voucher_details" )->row();
		$voucher = $this->db->where("voucher_number", $nota_detail->evidence_number)->get( "ar_vouchers" )->row();
		$voucher_detail = $this->db->where("voucher_number", $nota_detail->evidence_number)->get( "ar_voucher_details" )->row();
		
		if( ! $item ){ $item = array('id' => 0); }
		$this->load->library( 'my_object', $item, 'item' );
				
		if( $this->input->is_ajax_request() )
		{
			$data = array(
				"item" => $this->item,
				"nota_detail" => $nota_detail,
				"voucher" => $voucher,
				"voucher_detail" => $voucher_detail,
				"supplier" => $this->db->where("id", $this->item->supplier_id)->get("common_suppliers")->row(),
				"account" => $this->db->where("id", $this->item->account_id)->get("accounting_accounts")->row(),
				"is_ajax_request" => TRUE,
				"is_modal" => TRUE,
				"is_edit" => TRUE,
				"form" => TRUE,
				"datatables" => TRUE,
				);
	
			$this->load->view( 
					'postings/modal/create_edit', 
					array('form_child' => $this->load->view('postings/form_voucher', $data, true))
				);
		} else
		{
			$data = array(
				"page" => $this->page."_".strtolower(__FUNCTION__),
				"item" => $this->item,
				"nota_detail" => $nota_detail,
				"voucher" => $voucher,
				"voucher_detail" => $voucher_detail,
				"supplier" => $this->db->where("id", $this->item->supplier_id)->get("common_suppliers")->row(),
				"account" => $this->db->where("id", $this->item->account_id)->get("accounting_accounts")->row(),
				"form" => TRUE,
				"datatables" => TRUE,
				"is_edit" => TRUE,
				"navigation_minimized" => TRUE
			);
			
			$this->template
				->set( "heading", lang("credit_debit_notes:view_heading") )
				->set_breadcrumb( lang("payables:page"), base_url("payable/facturs") )
				->set_breadcrumb( lang("credit_debit_notes:page"), base_url("payable/debit_credit_note") )
				->set_breadcrumb( lang("credit_debit_notes:view_heading") )
				->build('postings/form_voucher', $data);		
		}
	}	
		
	public function datatable_collection( $posting_number = false )
    {
        $start = $this->input->get_post('start', true);
        $length = $this->input->get_post('length', true);
        $order = $this->input->get_post('order', true);
        $columns = $this->input->get_post('columns', true);
        $search = $this->input->get_post('search', true);
        $draw = $this->input->get_post('draw', true);
		
		$filter = $this->input->post('f');
		
		$this->load->model("common/supplier_m");
		
		$db_factur = "AP_trFaktur a";
		$db_factur_where = array();
		$db_factur_where['a.Diakui_Hutang'] = 1;
		$db_factur_where['a.Cancel_Faktur'] = 0;
		$db_factur_where['a.Posted'] = 0;

		$db_voucher = "AP_trVoucher a";
		$db_voucher_where = array();
		$db_voucher_where['a.Cancel_Voucher'] = 0;
		$db_voucher_where['a.Posted'] = 0;

		/*$db_cashier = "GC_trGeneralCashier a";
		$db_cashier_where = array();
		$db_cashier_where['a.Type_Transaksi'] = 'APC';
		$db_cashier_where['a.Posted'] = 0;*/
		
		
		$db_where = array();
		if ( $this->input->post("date_start") )
		{
			$db_where['UNION_POSTED.Tgl_Transaksi >='] = $this->input->post("date_start"); 
		}

		if ( $this->input->post("date_end"))
		{
			$db_where['UNION_POSTED.Tgl_Transaksi <='] = $this->input->post("date_end"); 
		}
				
		if ( $this->input->post("Supplier_ID"))
		{
			$db_where['UNION_POSTED.Supplier_ID'] = $this->input->post("Supplier_ID");
		}
		
		
		# Facturs 
		$db_select = <<<EOSQL
			a.Tgl_Faktur AS Tgl_Transaksi, 
			a.No_Faktur AS No_Bukti, 
			a.Nilai_Faktur AS Nilai, 
			d.Currency_Code AS Mata_Uang, 
			a.Keterangan, 
			e.Kode_Supplier, 
			e.Nama_Supplier, 
			e.Supplier_ID, 
			401 AS JTransaksi_ID,
			b.Akun_ID,
			d.Currency_ID,
			a.Nilai_Tukar,
			a.HisCurrencyID,
			0 AS Akun_ID2 ,
			f.Nama_Proyek,
			f.Kode_Proyek_Real,
			a.DivisiID,
			c.Nama_Divisi,
			'SEC078' AS SectionID,
			'factur' AS source
EOSQL;
		
		$this->db
			->select( $db_select )
			->from( $db_factur )
			->join( "AP_mTypeHutang b", "a.JenisHutang_ID = b.TypeHutang_ID", "INNER" )
			->join( "mDivisi c", "a.DivisiID = c.Divisi_ID", "INNER" )
			->join( "Mst_Currency d", "a.Currency_ID = d.Currency_ID", "INNER" )
			->join( "{$this->supplier_m->table} e", "a.Supplier_ID = e.Supplier_ID", "INNER" )
			->join( "mProyek f", "a.Kode_Proyek = f.Kode_Proyek_Real", "INNER" );		
		if( !empty($db_factur_where) ){ $this->db->where( $db_factur_where ); }
		
		$_union_factur = $this->db->get_compiled_select();


		# Vouchers
		$db_select = <<<EOSQL
			a.Tgl_Voucher AS Tgl_Transaksi, 
			a.No_Voucher AS No_Bukti, 
			a.Nilai, 
			d.Currency_Code AS Mata_Uang,
			a.Keterangan, 
			e.Kode_Supplier, 
			e.Nama_Supplier, 
			e.Supplier_ID, 
			a.JTransaksi_ID, 
			b.Akun_ID,
			d.Currency_ID,
			a.Nilai_Tukar,
			a.HisCurrencyID,
			a.Akun_ID AS Akun_ID2 ,
			f.Nama_Proyek,
			f.Kode_Proyek_Real,
			a.DivisiID,
			c.Nama_Divisi ,
			a.SectionID,
			'voucher' AS source
EOSQL;

		$this->db
			->select( $db_select )
			->from( $db_voucher )
			->join( "AP_mTypeHutang b", "a.JenisHutang_ID = b.TypeHutang_ID", "INNER" )
			->join( "mDivisi c", "a.DivisiID = c.Divisi_ID", "INNER" )
			->join( "Mst_Currency d", "a.Currency_ID = d.Currency_ID", "INNER" )
			->join( "{$this->supplier_m->table} e", "a.Supplier_ID = e.Supplier_ID", "INNER" )
			->join( "mProyek f", "a.Kode_Proyek = f.Kode_Proyek_Real", "INNER" )
			->where_in( "a.JTransaksi_ID", array(402, 403, 405, 406, 407), FALSE );
		if( !empty($db_voucher_where) ){ $this->db->where( $db_voucher_where ); }
		
		$_union_voucher = $this->db->get_compiled_select();
		
		
		# General Cashier
		/*$db_select = <<<EOSQL
			a.Tgl_Transaksi, 
			a.No_Bukti,
			sum( b.Debet ) AS Nilai ,  
			d.Currency_Code,
			a.Keterangan,
			e.Kode_Supplier,  
			e.Nama_Supplier,
			e.Supplier_ID,
			901 AS JTransaksi_ID,
			e.Akun_ID,
			d.Currency_ID,  
			a.Nilai_Tukar,
			0 AS Hiscurrency,
			0 AS Akun_2 ,
			f.Nama_Proyek,
			f.Kode_Proyek_Real,
			a.DivisiID,
			c.Nama_Divisi,
			b.SectionID,
			'cashier' AS source
EOSQL;

		$this->db
			->select( $db_select )
			->from( $db_cashier )
			->join( "GC_trGeneralCashierDetail b", "a.No_Bukti = b.No_Bukti", "INNER" )
			->join( "mDivisi c", "a.DivisiID = c.Divisi_ID", "INNER" )
			->join( "Mst_Currency d", "a.Currency_ID = d.Currency_ID", "INNER" )
			->join( "{$this->supplier_m->table} e", "a.Supplier_ID = e.Supplier_ID", "INNER" )
			->join( "mProyek f", "a.Kode_Proyek = f.Kode_Proyek_Real", "INNER" )
			->group_by( array( 'a.Tgl_Transaksi', 'a.No_Bukti',  'd.Currency_Code', 'a.Keterangan',
						'e.Kode_Supplier', 'e.Nama_Supplier', 'e.Supplier_ID', 'e.Akun_ID',
						'd.Currency_ID', 'a.Nilai_Tukar', 'f.Nama_Proyek', 'f.Kode_Proyek_Real',
						'a.DivisiID', 'c.Nama_Divisi', 'b.SectionID' ) );
			
		if( !empty($db_cashier_where) ){ $this->db->where( $db_cashier_where ); }
		
		$_union_cashier = $this->db->get_compiled_select();*/

		// GET UNION POSTED
		$union_posted = $this->db->from("
								(
									{$_union_factur} UNION {$_union_voucher} 
								)
								
								AS UNION_POSTED
							")
							->where( $db_where )	
							->order_by("UNION_POSTED.Tgl_Transaksi, UNION_POSTED.No_Bukti ASC")
							->get();
			
		// Output
        $output = array(
				'draw' => intval($draw),
				'recordsTotal' => $union_posted->num_rows() - 1,
				'recordsFiltered' => $union_posted->num_rows() - 1,
				'data' => $union_posted->result()
			);
		
		$this->template
			->build_json( $output );
    }

	public function datatable_collection_cancel()
    {
        $start = $this->input->get_post('start', true);
        $length = $this->input->get_post('length', true);
        $order = $this->input->get_post('order', true);
        $columns = $this->input->get_post('columns', true);
        $search = $this->input->get_post('search', true);
        $draw = $this->input->get_post('draw', true);
		
		$filter = $this->input->post('f');
		
		$this->load->model("common/supplier_m");
		
		$db_factur = "AP_trFaktur a";
		$db_factur_where = array();
		$db_factur_where['a.Diakui_Hutang'] = 1;
		$db_factur_where['a.Cancel_Faktur'] = 0;
		$db_factur_where['a.Posted'] = 1;

		$db_voucher = "AP_trVoucher a";
		$db_voucher_where = array();
		$db_voucher_where['a.Cancel_Voucher'] = 0;
		$db_voucher_where['a.Posted'] = 1;		
		
		$db_where = array();
		if ( $this->input->post("date_start") )
		{
			$db_where['UNION_POSTED.Tgl_Transaksi >='] = $this->input->post("date_start"); 
		}

		if ( $this->input->post("date_end"))
		{
			$db_where['UNION_POSTED.Tgl_Transaksi <='] = $this->input->post("date_end"); 
		}
				
		if (!empty($this->input->post("Supplier_ID")))
		{
			$db_where['UNION_POSTED.Supplier_ID'] = $this->input->post("Supplier_ID");
		}
		
		
		# Facturs 
		$db_select = <<<EOSQL
			a.Tgl_Faktur AS Tgl_Transaksi, 
			a.No_Faktur AS No_Bukti, 
			a.Nilai_Faktur AS Nilai, 
			d.Currency_Code AS Mata_Uang, 
			a.Keterangan, 
			e.Kode_Supplier, 
			e.Nama_Supplier, 
			e.Supplier_ID, 
			401 AS JTransaksi_ID,
			b.Akun_ID,
			d.Currency_ID,
			a.Nilai_Tukar,
			a.HisCurrencyID,
			0 AS Akun_ID2 ,
			f.Nama_Proyek,
			f.Kode_Proyek_Real,
			a.DivisiID,
			c.Nama_Divisi,
			'SEC078' AS SectionID,
			'factur' AS source
EOSQL;
		
		$this->db
			->select( $db_select )
			->from( $db_factur )
			->join( "AP_mTypeHutang b", "a.JenisHutang_ID = b.TypeHutang_ID", "INNER" )
			->join( "mDivisi c", "a.DivisiID = c.Divisi_ID", "INNER" )
			->join( "Mst_Currency d", "a.Currency_ID = d.Currency_ID", "INNER" )
			->join( "{$this->supplier_m->table} e", "a.Supplier_ID = e.Supplier_ID", "INNER" )
			->join( "mProyek f", "a.Kode_Proyek = f.Kode_Proyek_Real", "INNER" );		
		if( !empty($db_factur_where) ){ $this->db->where( $db_factur_where ); }
		
		$_union_factur = $this->db->get_compiled_select();


		# Vouchers
		$db_select = <<<EOSQL
			a.Tgl_Voucher AS Tgl_Transaksi, 
			a.No_Voucher AS No_Bukti, 
			a.Nilai, 
			d.Currency_Code AS Mata_Uang,
			a.Keterangan, 
			e.Kode_Supplier, 
			e.Nama_Supplier, 
			e.Supplier_ID, 
			a.JTransaksi_ID, 
			b.Akun_ID,
			d.Currency_ID,
			a.Nilai_Tukar,
			a.HisCurrencyID,
			a.Akun_ID AS Akun_ID2 ,
			f.Nama_Proyek,
			f.Kode_Proyek_Real,
			a.DivisiID,
			c.Nama_Divisi ,
			a.SectionID,
			'voucher' AS source
EOSQL;

		$this->db
			->select( $db_select )
			->from( $db_voucher )
			->join( "AP_mTypeHutang b", "a.JenisHutang_ID = b.TypeHutang_ID", "INNER" )
			->join( "mDivisi c", "a.DivisiID = c.Divisi_ID", "INNER" )
			->join( "Mst_Currency d", "a.Currency_ID = d.Currency_ID", "INNER" )
			->join( "{$this->supplier_m->table} e", "a.Supplier_ID = e.Supplier_ID", "INNER" )
			->join( "mProyek f", "a.Kode_Proyek = f.Kode_Proyek_Real", "INNER" )
			->where_in( "a.JTransaksi_ID", array(402, 403, 405, 406, 407), FALSE );
		if( !empty($db_voucher_where) ){ $this->db->where( $db_voucher_where ); }
		
		$_union_voucher = $this->db->get_compiled_select();
		

		// GET UNION POSTED
		$union_posted = $this->db->from("
		
								(
									{$_union_factur} UNION {$_union_voucher} 
								)	
								AS UNION_POSTED
							")	
							->where( $db_where )	
							->order_by("UNION_POSTED.Tgl_Transaksi, UNION_POSTED.No_Bukti ASC")
							->get();
			
		// Output
        $output = array(
				'draw' => intval($draw),
				'recordsTotal' => $union_posted->num_rows() - 1,
				'recordsFiltered' => $union_posted->num_rows() - 1,
				'data' => $union_posted->result()
			);
		
		$this->template	
			->build_json( $output );
    }
			
	public function lookup_accounts( $trId = NULL , $posting_id = NULL, $is_create = NULL ){
	
		$posting = $this->db->where("id", $posting_id)->get( "ar_postings" )->row();
		$post_action = ($is_create) ? base_url("payable/postings/item_create") : base_url("payable/postings/item_update");
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'postings/lookup/accounts', array("trId" => is_numeric($trId) ? $trId : NULL, "posting" => $posting, "post_action" => $post_action) );
		} 
	}

	public function lookup_suppliers( ){
	
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'postings/lookup/suppliers' );
		} 
	}
	
	public function posting()
	{
		if ( $this->input->post() )
		{
			$this->load->helper("Approval");
			$approver = $this->input->post('approver');
			if ( Approval_helper::approve( 'POSTING AR', $approver['username'], $approver['password'] ) === FALSE )
			{
				$response["message"] = lang('auth_incorrect');
				$response["status"] = "error";
				$response["code"] = "500";				
				
				print_r( json_encode( $response, JSON_NUMERIC_CHECK ) );
				exit(0);
			}

			$response = array(
					"status" => "success",
					"message" => "",
					"code" => "200",
				);

			$postings = $this->input->post("postings");
						
			if( empty($postings) )
			{				
				
				$response["message"] = lang('postings:empty_posting_data');
				$response["status"] = "error";
				$response["code"] = "500";
				
				print_r(json_encode( $response, JSON_NUMERIC_CHECK )); 
				exit(0);
			}
			
			$this->load->library( 'form_validation' );
			$this->form_validation->set_rules( $this->get_model()->rules['insert'] );
			$this->form_validation->set_data( $postings );		

			if( !$this->form_validation->run() )
			{
				$response = array(
						"status" => "success",
						"message" => lang('postings:posting_successfully'),
						"code" => "200",
					);
				
				if ($this->get_model()->posting_data( $postings ) === FALSE)
				{
					$response["message"] = lang('global:created_failed');
					$response["status"] = "error";
					$response["code"] = "500";
				}
				
			} else
			{

				$response["message"] = $this->form_validation->get_all_error_string();
				$response["status"] = "error";
				$response["code"] = "500";
			}
		
			print_r(json_encode( $response, JSON_NUMERIC_CHECK )); 
			exit(0);
			
		} else {

			$this->load->view( 'postings/modal/posting' );	
			
		}
	}

	public function posting_cancel()
	{
		if ( $this->input->post() )
		{
			$this->load->model("posting_cancel_m");
			
			$response = array(
					"status" => "success",
					"message" => "",
					"code" => "200",
				);

			$this->load->helper("Approval");
			$approver = $this->input->post('approver');
			if ( Approval_helper::approve( 'CANCEL POSTING AP', $approver['username'], $approver['password'] ) === FALSE )
			{
				$response["message"] = lang('auth_incorrect');
				$response["status"] = "error";
				$response["code"] = "500";				
				
				print_r( json_encode( $response, JSON_NUMERIC_CHECK ) );
				exit(0);
			}
				
			$postings = $this->input->post("postings");
			
			if( empty($postings) )
			{				
				
				$response["message"] = lang('postings:empty_posting_data');
				$response["status"] = "error";
				$response["code"] = "500";
				
				print_r(json_encode( $response, JSON_NUMERIC_CHECK )); 
				exit(0);
			}
			
			// Cek apakah Data yg dibatalkan sudah di tutup buku atau belum pada GL!
			foreach( $postings as $row ):
			
				if ( $this->posting_cancel_m->check_general_ledger_closing( $row['No_Bukti'] ) )
				{
					$response["message"] = sprintf( lang('postings:journal_close_book'), $row['No_Bukti'] );
					$response["status"] = "error";
					$response["code"] = "500";
					
					print_r(json_encode( $response, JSON_NUMERIC_CHECK )); 
					exit(0);
				}
				
			endforeach;
			
			$this->load->library( 'form_validation' );
			$this->form_validation->set_rules( $this->posting_cancel_m->rules['insert'] );
			$this->form_validation->set_data( $postings );		

			if( !$this->form_validation->run() )
			{
				$response = array(
						"status" => "success",
						"message" => lang('postings:posting_cancel_successfully'),
						"code" => "200",
					);
				
				if ($this->posting_cancel_m->posting_cancel_data( $postings ) === FALSE)
				{
					$response["message"] = lang('global:created_failed');
					$response["status"] = "error";
					$response["code"] = "500";
				}
				
			} else
			{

				$response["message"] = $this->form_validation->get_all_error_string();
				$response["status"] = "error";
				$response["code"] = "500";
			}
		
			print_r(json_encode( $response, JSON_NUMERIC_CHECK )); 
			exit(0);
			
		} else {
			
			$this->load->view( 'postings/modal/posting_cancel' );	
		}
		
	}	
}



