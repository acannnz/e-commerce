<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Postings extends Admin_Controller
{
	protected $_translation = 'general_cashier';	
	protected $_model = 'posting_m';
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('general_cashier');
				
		$this->load->helper("general_cashier");
				
		$this->page = "general_cashier_postings";
		$this->template->title( lang("postings:page") . ' - ' . $this->config->item('company_name') );
	}
	
	public function index()
	{
		$data = array(
				'page' => $this->page,
				"beginning_balance_date" => general_cashier_helper::get_beginning_balance_date(),
				"posting_url" => base_url("general-cashier/postings/posting"),
				"form" => TRUE,
				'datatables' => TRUE,
				"navigation_minimized" => TRUE
			);
		
		$this->template
			->set( "heading", lang("postings:page") )
			->set_breadcrumb( lang("general_cashier:page") )
			->set_breadcrumb( lang("postings:page"), base_url("general-cashier/postings") )
			->build('postings/datatable', (isset($data) ? $data : NULL));
	}

	public function cancel()
	{
		$data = array(
				'page' => $this->page,
				"beginning_balance_date" => general_cashier_helper::get_beginning_balance_date(),
				"posting_cancel_url" => base_url("general-cashier/postings/posting_cancel"),
				"form" => TRUE,
				'datatables' => TRUE,
				"navigation_minimized" => TRUE
			);
		
		$this->template
			->set( "heading", lang("postings:cancel_page") )
			->set_breadcrumb( lang("general_cashier:page") )
			->set_breadcrumb( lang("postings:page"), base_url("general-cashier/postings") )
			->set_breadcrumb( lang("postings:cancel_page"), base_url("general-cashier/postings/cancel") )
			->build('postings/datatable_cancel', (isset($data) ? $data : NULL));
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
		
		$db_from = "{$this->get_model()->table} a";
		$db_from_detail = "{$this->get_model()->table_detail} b";
		
		$db_not_bg_where = "( a.NoBg = '' OR a.NoBg IS NULL )";
		$db_with_bg_where= "( a.NoBg != '' OR a.NoBg IS NOT NULL )";
		
		
		$db_where = array();
		$db_where['a.Status_Batal'] = 0;
		$db_where['a.Type_Transaksi !='] = 'ARC';
		$db_where['a.Posted'] = 0;
		if ( $this->input->post("date_start") )
		{
			$db_where['a.Tgl_Transaksi >='] = $this->input->post("date_start"); 
		}

		if ( $this->input->post("date_end"))
		{
			$db_where['a.Tgl_Transaksi <='] = $this->input->post("date_end"); 
		}
		
		if (!empty($this->input->post("search_text")))
		{
			$db_like['a.No_Bukti'] = $this->input->post("search_text"); 
			$db_like['a.Type_Transaksi'] = $this->input->post("search_text"); 
			$db_like['a.Keterangan'] = $this->input->post("search_text"); 
		}
						
		# Not BG 
		$db_select = <<<EOSQL
			ABS(SUM(b.Kredit - b.Debet)) AS Nilai, 
			SUBSTRING( a.No_Bukti, 1, 50 ) AS No_Bukti, 
			a.Tgl_Transaksi,  
			a.Type_Transaksi,
			a.Status_Batal,
			a.Posted,
			c.Currency_Code,
			a.Currency_ID,
			a.Keterangan,
			a.Nilai_Tukar,
			'' as Nama_Proyek,
			'' as Kode_Proyek,
			a.Supplier_ID,
			a.Customer_ID ,
			0 as DivisiID,
			'' as Nama_Divisi
EOSQL;
		
		$this->db
			->select( $db_select )
			->from( $db_from )
			->join( $db_from_detail, "a.No_Bukti = b.No_Bukti", "INNER" )
			->join( "Mst_Currency c", "a.Currency_ID = c.Currency_ID", "INNER" )
			->join( "Mst_Akun d", "b.Akun_ID = d.Akun_ID", "INNER" )
			->group_by(array(
				'a.No_Bukti', 
				'a.Tgl_Transaksi',
				'a.Nilai_Tukar', 
				'a.Type_Transaksi',
				'a.Status_Batal',
				'a.Posted',
				'c.Currency_Code',
				'a.Currency_ID',
				'a.Keterangan',
				'a.SUpplier_ID',
				'a.Customer_ID'
			));					
		if( !empty($db_not_bg_where) ){ $this->db->where( $db_not_bg_where ); }
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }
		
		$_union_not_bg = $this->db->get_compiled_select();


		# With BG
		$db_select = <<<EOSQL
			ABS(SUM(b.Kredit - b.Debet)) AS Nilai, 
			SUBSTRING( a.No_Bukti, 1, 50 ) as No_Bukti, 
			a.Tgl_Transaksi,  
			a.Type_Transaksi ,
			a.Status_Batal,
			a.Posted,
			c.Currency_Code,
			a.Currency_ID,
			a.Keterangan,
			a.Nilai_Tukar,
			'' as Nama_Proyek,
			'' as Kode_Proyek,
			a.Supplier_ID,
			a.Customer_ID ,
			0 as DivisiID,
			'' as Nama_Divisi
EOSQL;

		$this->db
			->select( $db_select )
			->from( $db_from )
			->join( $db_from_detail, "a.No_Bukti = b.No_Bukti", "INNER" )
			->join( "Mst_Currency c", "a.Currency_ID = c.Currency_ID", "INNER" )
			->join( "Mst_Akun d", "b.Akun_ID = d.Akun_ID", "INNER" )
			->group_by(array(
				'a.No_Bukti', 
				'a.Tgl_Transaksi',
				'a.Nilai_Tukar', 
				'a.Type_Transaksi',
				'a.Status_Batal',
				'a.Posted',
				'c.Currency_Code',
				'a.Currency_ID',
				'a.Keterangan',
				'a.SUpplier_ID',
				'a.Customer_ID'
			));		
		if( !empty($db_with_bg_where) ){ $this->db->where( $db_with_bg_where ); }
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }

		$_union_with_bg = $this->db->get_compiled_select();

		// GET UNION POSTED
		$union_posted = $this->db->from("
								(
									{$_union_with_bg} UNION {$_union_not_bg} 
								)
								
								AS UNION_POSTED
							")
							->order_by("UNION_POSTED.No_Bukti", 'ASC')
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
		
		$db_from = "{$this->get_model()->table} a";
		$db_from_detail = "{$this->get_model()->table_detail} b";
		
		$db_not_bg_where = "( a.NoBg = '' OR a.NoBg IS NULL )";
		$db_with_bg_where= "( a.NoBg != '' OR a.NoBg IS NOT NULL )";
		

		$db_where = array();
		$db_where['a.Status_Batal'] = 0;
		$db_where['a.Type_Transaksi !='] = 'ARC';
		$db_where['a.Posted'] = 1;
		if ( $this->input->post("date_start") )
		{
			$db_where['a.Tgl_Transaksi >='] = $this->input->post("date_start"); 
		}

		if ( $this->input->post("date_end"))
		{
			$db_where['a.Tgl_Transaksi <='] = $this->input->post("date_end"); 
		}
		
		if (!empty($this->input->post("search_text")))
		{
			$db_like['a.No_Bukti'] = $this->input->post("search_text"); 
			$db_like['a.Type_Transaksi'] = $this->input->post("search_text"); 
			$db_like['a.Keterangan'] = $this->input->post("search_text"); 
		}
						
		# Not BG 
		$db_select = <<<EOSQL
			ABS(SUM(b.Kredit - b.Debet)) AS Nilai, 
			SUBSTRING( a.No_Bukti, 1, 50 ) as No_Bukti, 
			a.Tgl_Transaksi,  
			a.Type_Transaksi,
			a.Status_Batal,
			a.Posted,
			c.Currency_Code,
			a.Currency_ID,
			a.Keterangan,
			a.Nilai_Tukar,
			'' as Nama_Proyek,
			'' as Kode_Proyek,
			a.Supplier_ID,
			a.Customer_ID ,
			0 as DivisiID,
			'' as Nama_Divisi
EOSQL;
		
		$this->db
			->select( $db_select )
			->from( $db_from )
			->join( $db_from_detail, "a.No_Bukti = b.No_Bukti", "INNER" )
			->join( "Mst_Currency c", "a.Currency_ID = c.Currency_ID", "INNER" )
			->join( "Mst_Akun d", "b.Akun_ID = d.Akun_ID", "INNER" )
			->group_by(array(
				'a.No_Bukti', 
				'a.Tgl_Transaksi',
				'a.Nilai_Tukar', 
				'a.Type_Transaksi',
				'a.Status_Batal',
				'a.Posted',
				'c.Currency_Code',
				'a.Currency_ID',
				'a.Keterangan',
				'a.SUpplier_ID',
				'a.Customer_ID'
			));					
		if( !empty($db_not_bg_where) ){ $this->db->where( $db_not_bg_where ); }
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }
		
		$_union_not_bg = $this->db->get_compiled_select();


		# With BG
		$db_select = <<<EOSQL
			ABS(SUM(b.Kredit - b.Debet)) AS Nilai, 
			SUBSTRING( a.No_Bukti, 1, 50 ) as No_Bukti, 
			a.Tgl_Transaksi,  
			a.Type_Transaksi ,
			a.Status_Batal,
			a.Posted,
			c.Currency_Code,
			a.Currency_ID,
			a.Keterangan,
			a.Nilai_Tukar,
			'' as Nama_Proyek,
			'' as Kode_Proyek,
			a.Supplier_ID,
			a.Customer_ID ,
			0 as DivisiID,
			'' as Nama_Divisi
EOSQL;

		$this->db
			->select( $db_select )
			->from( $db_from )
			->join( $db_from_detail, "a.No_Bukti = b.No_Bukti", "INNER" )
			->join( "Mst_Currency c", "a.Currency_ID = c.Currency_ID", "INNER" )
			->join( "Mst_Akun d", "b.Akun_ID = d.Akun_ID", "INNER" )
			->group_by(array(
				'a.No_Bukti', 
				'a.Tgl_Transaksi',
				'a.Nilai_Tukar', 
				'a.Type_Transaksi',
				'a.Status_Batal',
				'a.Posted',
				'c.Currency_Code',
				'a.Currency_ID',
				'a.Keterangan',
				'a.SUpplier_ID',
				'a.Customer_ID'
			));		
		if( !empty($db_with_bg_where) ){ $this->db->where( $db_with_bg_where ); }
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }
		
		$_union_with_bg = $this->db->get_compiled_select();

		// GET UNION POSTED
		$union_posted = $this->db->from("
								(
									{$_union_with_bg} UNION {$_union_not_bg} 
								)
								
								AS UNION_POSTED
							")
							->order_by("UNION_POSTED.No_Bukti", 'ASC')
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
				
	public function posting()
	{
		if ( $this->input->post() )
		{
			$this->load->helper("Approval");
			$approver = $this->input->post('approver');
			if ( Approval_helper::approve( 'POSTING GC', $approver['username'], $approver['password'] ) === FALSE )
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
			$response = array(
					"status" => "success",
					"message" => "",
					"code" => "200",
				);

			$this->load->helper("Approval");
			$approver = $this->input->post('approver');
			if ( Approval_helper::approve( 'CANCEL POSTING GC', $approver['username'], $approver['password'] ) === FALSE )
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
			
			foreach( $postings as $row ):
			
				if ( general_cashier_helper::check_general_ledger_closing_transaction( $row['No_Bukti'] ) )
				{
					$response["message"] = sprintf( lang('postings:journal_close_book'), $row['No_Bukti'] );
					$response["status"] = "error";
					$response["code"] = "500";
					
					print_r(json_encode( $response, JSON_NUMERIC_CHECK )); 
					exit(0);
				}
				
			endforeach;
			
			$this->load->library( 'form_validation' );
			$this->form_validation->set_rules( $this->get_model()->rules['insert'] );
			$this->form_validation->set_data( $postings );		

			if( !$this->form_validation->run() )
			{
				$response = array(
						"status" => "success",
						"message" => lang('postings:cancel_posting_successfully'),
						"code" => "200",
					);
				
				if ($this->get_model()->posting_cancel( $postings ) === FALSE)
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



