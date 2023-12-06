<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Beginning_balance extends Admin_Controller
{
	protected $_translation = 'receivable';	
	protected $_model = 'beginning_balance_m';
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('receivable');
				
		$this->load->model("common/customer_m");
		$this->load->model("factur_m");
		$this->load->model("type_m");
		
		$this->load->helper("receivable");
				
		$this->page = "beginning_balances";
		$this->template->title( lang("beginning_balances:page") . ' - ' . $this->config->item('company_name') );
	}
	
	public function index()
	{
		//$type_beginning_balance = $this->get_model()->get_receivable_type_beginning_balances( $this->_house_id );
					
		$data = array(
				'page' => $this->page,
				"form" => TRUE,
				'datatables' => TRUE,
				"navigation_minimized" => TRUE,	
				"populate_url" => base_url("receivable/beginning-balance/datatable_collection"),
				//"type_beginning_balance" => @$type_beginning_balance,
				'options_type' => $this->type_m->get_option_type(),
			);
			
		$this->template
			->set( "heading", lang("beginning_balances:page") )
			->set_breadcrumb( lang("beginning_balances:page"), base_url("receivable/beginning-balances") )
			->build('beginning_balances/datatables', (isset($data) ? $data : NULL));
	}
	
	public function details( )
	{
		$type_beginning_balance = $this->get_model()->get_receivable_type_beginning_balances( $this->_house_id );
		
		//print_r($type_beginning_balance);exit;
		
		$data = array(
				"populate_url" => base_url("receivable/beginning-balance/detail_collection"),
				"form" => TRUE,
				"datatables" => TRUE,
				"type_beginning_balance" => $type_beginning_balance,
			);
		
		return	$this->load->view( "beginning_balances/tables/details", $data );		
		
	}

	
	public function create(  )
	{
		
		if( $this->input->post() ) 
		{
			
			
			
			$post = (object) $this->input->post("f");
			
			$this->load->library( 'form_validation' );
			//$this->form_validation->set_rules( $this->get_model()->rules['insert'] );
			//$this->form_validation->set_data( $post );

			if ( $this->form_validation->run() )
			{
				$response["message"] = $this->form_validation->get_all_error_string();
				$response["status"] = "error";
				$response["code"] = "500";
				
				print_r(json_encode($response)); 
				exit;
				
			}

			if( empty($this->get_model()->get_hisscurency_id( $post->Tgl_Saldo )) )
			{
				$response["message"] = lang("beginning_balances:hiss_currency_not_setup");
				$response["status"] = "error";
				$response["code"] = "500";
				
				print_r(json_encode($response)); 
				exit;
			
			}

			if( $this->get_model()->find_identical_posted( $post ))
			{
				$response["message"] = lang("beginning_balances:identical_posted");
				$response["status"] = "error";
				$response["code"] = "500";
				
				print_r(json_encode($response)); 
				exit;
			
			}


			$response = array(
					"message" => lang('global:created_successfully'),
					"status" => "success",
					"code" => "200",
				);
			
			if ( $this->get_model()->create_data( $post ) === FALSE )
			{
				$response["message"] = lang('global:created_failed');
				$response["status"] = "error";
				$response["code"] = "500";
			}
			
			print_r(json_encode($response)); 
			exit;

		} 
		
		if( $this->input->is_ajax_request() )
		{
			$this->load->view( 
					'beginning_balances/modal/create_edit', 
					array('form_child' => $this->load->view('beginning_balances/form', 
						array(
							'is_modal' => TRUE,
							'options_type' => $this->type_m->get_option_type(),
							'options_currency' => receivable_helper::get_option_currency(),
							'options_division' => receivable_helper::get_option_division(),
							'options_project' => receivable_helper::get_option_project(),
							"lookup_customers" => base_url("receivable/beginning-balance/lookup_customers"),
						), 
					true)) 
				);
				
		} else
		{
			$data = array(
					"page" => $this->page,
					'options_type' => $this->type_m->get_option_type(),
					'options_currency' => receivable_helper::get_option_currency(),
					'options_division' => receivable_helper::get_option_division(),
					'options_project' => receivable_helper::get_option_project(),
					"form" => TRUE,
					"datatables" => TRUE,
					"lookup_customers" => base_url("receivable/beginning-balance/lookup_customers"),
				);
			
			$this->template
				->set( "heading", lang("beginning_balances:edit_heading") )
				->set_breadcrumb( lang("receivable:page"), base_url("receivable") )
				->set_breadcrumb( lang("beginning_balances:breadcrumb"), base_url("receivable/beginning-balance") )
				->set_breadcrumb( lang("beginning_balances:edit_heading") )
				->build('beginning_balances/form', $data);
		}		
	}

	public function edit()
	{
		
		if ( $this->input->get() )
		{
			$params = (object) $this->input->get();
			$item = $this->get_model()->get_beginning_balance_row( $params );
			$item->No_Invoice = $this->get_model()->get_invoice_number( $params );
		}
		
		if( $this->input->post() ) 
		{
			
			

			$post = (object) $this->input->post("f");
			
			$this->load->library( 'form_validation' );
			
			//$this->item->addData( $this->input->post("f") );
			//$this->form_validation->set_rules( $this->get_model()->rules['insert'] );
			//$this->form_validation->set_data( (array) $post );
			
			if ( $this->form_validation->run() )
			{
				$response["message"] = $this->form_validation->get_all_error_string();
				$response["status"] = "error";
				$response["code"] = "500";
				
				print_r(json_encode($response)); 
				exit;
				
			}

			// Jika piutang Sudah dibayar
			if ( $this->get_model()->is_paid( $post->No_Invoice ) == TRUE )
			{
				$response["message"] = lang("beginning_balances:paid_receivable");
				$response["status"] = "error";
				$response["code"] = "500";
	
				print_r(json_encode($response)); 
				exit;
			}
			
			// Jika piutang Sudah tutup buku
			if ( $this->get_model()->check_close_book( $post->No_Invoice ) == TRUE)
			{
				$response["message"] = lang("beginning_balances:close_book");
				$response["status"] = "error";
				$response["code"] = "500";
	
				print_r(json_encode($response)); 
				exit;
			}
						
			$response = array(
					"status" => "success",
					"message" => lang('global:updated_successfully'),
					"code" => "200",
				);
			
			
			if ( $this->get_model()->update_data( $post ) === FALSE)
			{
				$response["message"] = lang('global:updated_failed');
				$response["status"] = "error";
				$response["code"] = "500";
			}
			
			print_r(json_encode($response)); 
			exit;

		}
		
		if( $this->input->is_ajax_request() )
		{
			$this->load->view( 
					'beginning_balances/modal/create_edit', 
					array('form_child' => $this->load->view('beginning_balances/form', 
						array(
							'item' => $item, 
							'is_modal' => TRUE,
							'is_edit' => TRUE,
							'options_type' => $this->type_m->get_option_type(),
							'options_currency' => receivable_helper::get_option_currency(),
							'options_division' => receivable_helper::get_option_division(),
							'options_project' => receivable_helper::get_option_project(),
							"lookup_customers" => base_url("receivable/beginning-balance/lookup_customers"),
						), 
					true))
				);
		} else
		{
			$data = array(
					"page" => $this->page,
					"item" => $item,
					'options_type' => $this->type_m->get_option_type(),
					'options_currency' => receivable_helper::get_option_currency(),
					'options_division' => receivable_helper::get_option_division(),
					'options_project' => receivable_helper::get_option_project(),
					"lookup_customers" => base_url("receivable/beginning-balance/lookup_customers"),
					"form" => TRUE,
					"datatables" => TRUE,
				);
			
			$this->template
				->set( "heading", lang("types:edit_heading") )
				->set_breadcrumb( lang("receivable:page"), base_url("receivable") )
				->set_breadcrumb( lang("types:breadcrumb"), base_url("receivable/types") )
				->set_breadcrumb( lang("types:edit_heading") )
				->build('beginning_balances/form', $data);
		}
	}
	
	public function delete()
	{
		if( ! $this->input->is_ajax_request() )
		{
			show_error( "Bad Request", 400 );
		}

		if ( $this->input->get() )
		{
			$params = (object) $this->input->get();
			$item = $this->get_model()->get_beginning_balance_row( $params );
			$item->No_Invoice = $this->get_model()->get_invoice_number( $params );
		}
				
		$response = array(
				"status" => "success",
				"message" => lang('global:deleted_successfully'),
				"code" => "200"
			);
					
		if( $this->input->post() )
		{	
			// Jika piutang Sudah dibayar
			if ( $this->get_model()->check_invoice_already_paid( $item->No_Invoice ) == TRUE )
			{
				$response["message"] = lang("beginning_balances:paid_receivable");
				$response["status"] = "error";
				$response["code"] = "500";
	
				print_r(json_encode($response)); 
				exit;
			}
	
			// Jika piutang Sudah tutup buku
			if ( $this->get_model()->check_close_book( $item->No_Invoice ) == TRUE )
			{
				$response["message"] = lang("beginning_balances:close_book");
				$response["status"] = "error";
				$response["code"] = "500";
	
				print_r(json_encode($response)); 
				exit;
			}

			$post_data = $this->input->post( 'f', TRUE );
			
			if( $item->No_Invoice == $this->input->post( 'confirm' ) )
			{
				if ($this->get_model()->delete_data( $item ) === FALSE)
				{
					$response["message"] = lang('global:deleted_failed');
					$response["status"] = "error";
					$response["code"] = "500";
				}
			}
			
			print_r( json_encode( $response, JSON_NUMERIC_CHECK )); 
			exit;

		}

		if( $this->input->is_ajax_request() )
		{
			$data = array( 
				"item" => $item,
				"delete_url" => current_url() . "?" . $_SERVER['QUERY_STRING']
			);
			$this->load->view( 'beginning_balances/modal/delete', $data );
		}
	}

	
	public function lookup_customers( ){
	
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'beginning_balances/lookup/customers' );
		} 
	}
	
	public function datatable_collection(  )
    {
        $start = $this->input->get_post('start', true);
        $length = $this->input->get_post('length', true);
        $order = $this->input->get_post('order', true);
        $columns = $this->input->get_post('columns', true);
        $search = $this->input->get_post('search', true);
        $draw = $this->input->get_post('draw', true);
		
		$db_from = "{$this->get_model()->table} a";
		$db_where = array();
		$db_like = array();
		
		$db_where["a.JenisPiutang_ID"] = $this->input->post('receivable_type');
		$db_where["a.SaldoAwal"] = 1;
				
		// get total records
		$this->db->from( $db_from )
				->join("mCustomer b", "a.Customer_ID = b.Customer_ID", "LEFT OUTER" )
				->join("mProyek c", "a.Kode_Proyek = c.Kode_Proyek_Real", "LEFT OUTER" )
				->join("Mst_Currency d", "a.Currency_ID = d.Currency_ID", "LEFT OUTER" )
				->join("mDivisi e", "a.DivisiID = e.Divisi_ID", "LEFT OUTER" )
				;
				
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		$records_total = $this->db->count_all_results();
		
		// get total filtered
		$this->db
			->from( $db_from )
				->join("mCustomer b", "a.Customer_ID = b.Customer_ID", "LEFT OUTER" )
				->join("mProyek c", "a.Kode_Proyek = c.Kode_Proyek_Real", "LEFT OUTER" )
				->join("Mst_Currency d", "a.Currency_ID = d.Currency_ID", "LEFT OUTER" )
				->join("mDivisi e", "a.DivisiID = e.Divisi_ID", "LEFT OUTER" )
				;
				
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		$records_filtered = $this->db->count_all_results();
		
		// get result filtered
		$db_select = <<<EOSQL
				b.Kode_Customer, 
				b.Nama_Customer, 
				c.Nama_Proyek, 
				a.Tgl_Saldo, 
				d.Currency_Code, 
				a.Nilai, 
				a.Customer_ID, 
				a.Currency_ID, 
				a.JenisPiutang_ID, 
				a.Kode_Proyek, 
				e.Nama_Divisi, 
				a.DivisiID
EOSQL;

		$this->db
			->select( $db_select )
			->from( $db_from )
			->join("mCustomer b", "a.Customer_ID = b.Customer_ID", "LEFT OUTER" )
			->join("mProyek c", "a.Kode_Proyek = c.Kode_Proyek_Real", "LEFT OUTER" )
			->join("Mst_Currency d", "a.Currency_ID = d.Currency_ID", "LEFT OUTER" )
			->join("mDivisi e", "a.DivisiID = e.Divisi_ID", "LEFT OUTER" )
			;
				
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		
		//$this->db->order_by( "b.customer_name ASC, c.type_name ASC" );

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



