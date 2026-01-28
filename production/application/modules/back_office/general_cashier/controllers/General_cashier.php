<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class General_cashier extends Admin_Controller
{
	protected $_translation = 'general_cashier';	
	protected $_model = 'general_cashier_m';
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('general_cashier');
				
		$this->load->model( "general_cashier_m" );
		
		$this->load->helper("general_cashier");
		
		$this->load->model("general_cashier/general_cashier_detail_m");
		$this->load->model("common/house_m");
		$this->load->model("general_ledger/account_m");
		
		$this->page = "general_cashier";
		$this->template->title( lang("general_cashier:page") . ' - ' . $this->config->item('company_name') );
	}
	
	public function index()
	{
		$data = array(
				'page' => $this->page,
				'house' => $this->house_m->get_house( $this->_house_id ),
				"lookup_suppliers" => base_url("general_cashier/general_cashier/lookup_suppliers"),
				"form" => TRUE,
				'datatables' => TRUE,
				"navigation_minimized" => TRUE
			);
		
		$this->template
			->set( "heading", lang("general_cashier:page") )
			->set_breadcrumb( lang("general_cashier:page"), base_url("general_cashier/general_cashier") )
			->build('general_cashier/general_cashier/datatable', (isset($data) ? $data : NULL));
	}

	public function create(  )
	{
		$item_data = array(
				'id' => 0,
				'house_id' => $this->_house_id,
				'evidence_number' => NULL,
				'account_id' => 0,
				'transaction_date' => date("Y-m-d"),
				'transaction_type' => NULL,
				'from_to' => NULL,
				'description' => NULL,
				'debit' => '0.00',
				'credit' => '0.00',
				'currency_code' => "IDR",
				'state' => 1,
				'created_at' => time(),
				'created_by' => $this->user,
				'updated_at' => time(),
				'updated_by' => $this->user,
				'deleted_at' => NULL,
				'deleted_by' => 0,
			);
			
		$this->load->library( 'my_object', $item_data, 'item' );
		if( $this->input->post() ) 
		{
			
			
			$this->load->library( 'form_validation' );
			
			$response = array(
					"status" => "success",
					"message" => "",
					"code" => "200",
				);

			$header_data = $this->input->post("header_data");
			$header_data['house_id'] = $this->_house_id;

			$credit_data = $this->input->post("credit_data");

			$debit_data = $this->input->post("debit_data");

			if ( empty($debit_data ) || empty($credit_data ))
			{
				$response["message"] = lang('general_cashier:details_cannot_empty');
				$response["status"] = "error";
				$response["code"] = "500";
				
				print_r(json_encode( $response, JSON_NUMERIC_CHECK )); 
				exit(0);
			}
					
			if ( $header_data['credit'] != $header_data['debit'] )
			{
				$response["message"] = lang('general_cashier:value_not_match');
				$response["status"] = "error";
				$response["code"] = "500";

				print_r(json_encode( $response, JSON_NUMERIC_CHECK )); 
				exit(0);

			}
			
			
			// gabungkan data prepare dengan data post header_data
			$general_cashier = array_merge($item_data, $header_data);

			$this->form_validation->set_rules( $this->general_cashier_m->rules['insert'] );
			$this->form_validation->set_data( $general_cashier );

			if( $this->form_validation->run() )
			{				
				$response = $this->general_cashier_m->process_transaction_general_cashier( $general_cashier, $credit_data, $debit_data );
				
			} else
			{

				$response["message"] = $this->form_validation->get_all_error_string();
				$response["status"] = "error";
				$response["code"] = "500";
			}
			
			print_r(json_encode( $response, JSON_NUMERIC_CHECK )); 
			exit(0);

		} else {
		
			if( $this->input->is_ajax_request() )
			{
				$data = array(
						'item' => $this->item,
						"house" => $this->house_m->get_house( $this->_house_id ),
						"is_ajax_request" => TRUE,
						"is_modal" => TRUE,
						"form" => TRUE,
						"datatables" => TRUE,
						"lookup_suppliers" => base_url("general_cashier/general_cashier/lookup_suppliers"),
					);
				
				$this->load->view( 
						'general_cashier/modal/create_edit', 
						array('form_child' => $this->load->view('general_cashier/form', $data, true))
					);
			} else
			{
				$data = array(
						"page" => $this->page."_".strtolower(__FUNCTION__),
						"item" => $this->item,
						"house" => $this->house_m->get_house( $this->_house_id ),
						"form" => TRUE,
						"datatables" => TRUE,
						"lookup_suppliers" => base_url("general_cashier/general_cashier/lookup_suppliers"),
					);
				
				$this->template
					->set( "heading", lang("general_cashier:create_heading") )
					->set_breadcrumb( lang("general_cashier:page"), base_url("general_cashier/general_cashier") )
					->set_breadcrumb( lang("general_cashier:create_heading") )
					->build('general_cashier/general_cashier/form', $data);
			}
		}
	}
		
	public function edit( $id=0 )
	{
		$id = (int) @$id;
		
		$item = $this->db->where("id", $id)->get( "ap_general_cashier" )->row_array();
		
		
		if( ! $item ){ $item = array('id' => 0); }
		$this->load->library( 'my_object', $item, 'item' );

		if( $this->input->post() ) 
		{
			
			
			$this->load->library( 'form_validation' );
			
			$this->item->addData( $this->input->post("f") );
			$this->form_validation->set_rules( $this->get_model()->rules['insert'] );
			$this->form_validation->set_data( $this->item->toArray() );
			
			$response = array(
					"status" => "success",
					"error" => "",
					"code" => "200",
				);
			
			if( $this->form_validation->run() )
			{
				if( $this->db->where("id", $id )->update( "gl_general_cashier", $this->item->toArray()) )
				{
					$this->get_model()->delete_cache( 'common_account.collection' );
					
					make_flashdata(array(
							'response_status' => 'success',
							'message' => lang('global:updated_successfully')
						));
						
					$response["id"] = $id;
						
				} else
				{
					make_flashdata(array(
							'response_status' => 'error',
							'message' => lang('global:updated_failed')
						));
						
					$response["message"] = lang('global:updated_failed');
					$response["status"] = "error";
					$response["code"] = "500";				
				}
			} else
			{
				make_flashdata(array(
						'response_status' => 'error',
						'message' => $this->form_validation->get_all_error_string()
					));

					$response["message"] = $this->form_validation->get_all_error_string();
					$response["status"] = "error";
					$response["code"] = "500";				
			}

			$this->template->build_json( $response );

		} else {
			
			if( $this->input->is_ajax_request() )
			{
				$data = array(
						'item' => $this->item,
						"house" => $this->house_m->get_house( $this->_house_id ),
						"is_ajax_request" => TRUE,
						"is_modal" => TRUE,
						"is_edit" => TRUE,
						"form" => TRUE,
						"datatables" => TRUE,
						"lookup_suppliers" => base_url("general_cashier/general_cashier/lookup_suppliers"),
						"options_type" => $this->type_m->options_type(),
						"supplier" => $this->db->where("id", $this->item->supplier_id)->get("common_suppliers")->row(),
					);
				
				$this->load->view( 
						'general_cashier/modal/create_edit', 
						array('form_child' => $this->load->view('general_cashier/form', $data, true))
					);
			} else
			{
				$data = array(
						"page" => $this->page,
						"item" => $this->item,
						"house" => $this->house_m->get_house( $this->_house_id ),
						"form" => TRUE,
						"datatables" => TRUE,
						"is_edit" => TRUE,
						"lookup_suppliers" => base_url("general_cashier/general_cashier/lookup_suppliers"),
						"options_type" => $this->type_m->options_type(),
						"supplier" => $this->db->where("id", $this->item->supplier_id)->get("common_suppliers")->row(),
					);
				
				$this->template
					->set( "heading", lang("general_cashier:modal_heading") )
					->set_breadcrumb( lang("general_cashier:page"), base_url("general_cashier/general_cashier") )
					->set_breadcrumb( lang("general_cashier:modal_heading") )
					->build('general_cashier/form', $data);
			}
		}

	}
	
	public function cancel( $id=0 )
	{
		$id = (int) @$id;
		
		$item = $this->db->where("id", $id )->get( "ap_general_cashier" )->row_array();
		if( ! $item ){ $item = array('id' => 0); }
		$this->load->library( 'my_object', $item, 'item' );
		
		if( $this->input->post() ) 
		{
						
			
			
			if ( $this->general_cashier_m->check_credit_debit_notes( $this->item->voucher_number ) )
			{
				make_flashdata(array(
						'response_status' => 'error',
						'message' => lang( 'general_cashier:has_credit_debit_notes' )
					));
					
				redirect("general_cashier/general_cashier");
				
			}
			
			if( 0 == @$this->item->id )
			{
				make_flashdata(array(
						'response_status' => 'error',
						'message' => lang( 'global:get_failed' )
					));
					
				redirect("general_cashier/general_cashier");
			
			}
			
			if( $this->item->id == $this->input->post( 'confirm' ) )
			{
				if ( $this->general_cashier_m->cancel_general_cashier( $this->item->evidence_number ))
				{
					make_flashdata(array(
						'response_status' => 'success',
						'message' => lang('global:deleted_successfully')
					));				
						
				} else {
					
					make_flashdata(array(
						'response_status' => 'error',
						'message' => $this->db->_error_message()
					));
					
					redirect("general_cashier/general_cashier/edit/{$this->item->id}");
				
				}
			}
			
			redirect("general_cashier/general_cashier");

		} else {
			
			$this->load->view( 'general_cashier/modal/delete', array('item' => $this->item) );	
		}
		
	}
		
	public function update( $evidence_number = '' )
	{
		if( $evidence_number == '' )
		{
			$evidence_number = $this->input->get_post( 'evidence_number', TRUE );
		}
		
		$data = array(
				"evidence_number" => $evidence_number,
				"form_action" => base_url("general_cashier/general_cashier/update")."?evidence_number={$evidence_number}",
				"count_balance_url" => base_url("general_cashier/general_cashier/count_tariff_total")."?evidence_number={$evidence_number}",
				"balance" => 0,
			);
		
		if( $this->input->is_ajax_request() )
		{
			if( $this->input->post() )
			{
				//if( $tariff_total = (float) $this->input->post( 'tariff_total', TRUE ) )
				//{
					$tariff_total = (float) $this->input->post( 'tariff_total', TRUE );
					
					$this->get_model()->update(
							array('tariff_total' => $tariff_total), 
							array('evidence_number' => $evidence_number)
						);
					
					exit();
				//}
			}
			
			if( $this->input->post() )
			{
				//if( $service_tariff = (float) $this->input->post( 'service_tariff', TRUE ) )
				//{
					$service_tariff = (float) $this->input->post( 'service_tariff', TRUE );
					$this->chart_m->update(
							array('service_tariff' => $service_tariff), 
							array('evidence_number' => $evidence_number)
						);					
					exit();
				//}
			}
		} else
		{
			if( $this->session->has_userdata( "applied.chart" ) )
			{
				$item = $this->session->userdata( "applied.chart" );
			} else
			{
				if( chart_helper::find_chart($evidence_number) )
				{
					$item = chart_helper::get_chart( $evidence_number );
				}
			}
			
			if( isset($item) )
			{
				$data['item'] = $item;
				$data['service_tariff'] = $item->service_tariff;
			}
			
			$this->load->view( "form/services", $data );
		}
	}
	
	public function transaction_debit( $general_cashier, $is_edit = FALSE )
	{
		$data = array(
				"evidence_number" => $general_cashier->evidence_number,
				"general_cashier" => $general_cashier,
				"form_action" => base_url("general_cashier/general_cashier/items")."?evidence_number={$general_cashier->evidence_number}",
				"populate_url" => base_url("general_cashier/general_cashier/detail_collection")."?evidence_number={$general_cashier->evidence_number}",
				"lookup_accounts" => base_url("general_cashier/general_cashier/lookup_accounts/dt_debit_details"), 
				"form_voucher_invoice" => base_url("general_cashier/general_cashier/form_voucher_invoice"), 
				"create_url" => base_url("general_cashier/general_cashier/item_create")."?evidence_number={$general_cashier->evidence_number}",
				"update_url" => base_url("general_cashier/general_cashier/item_update")."?evidence_number={$general_cashier->evidence_number}",
				"delete_url" => base_url("general_cashier/general_cashier/item_delete")."?evidence_number={$general_cashier->evidence_number}",
				"form" => TRUE,
				"datatables" => TRUE,
			);
		
		if ($is_edit === TRUE)
		{
			return	$this->load->view( "general_cashier/general_cashier/tables/tables_edit", $data );		
			
		} else 
		{
			return	$this->load->view( "general_cashier/general_cashier/tables/debit", $data );		
		}
		
	}

	public function transaction_credit( $general_cashier, $is_edit = FALSE )
	{
		$data = array(
				"evidence_number" => $general_cashier->evidence_number,
				"general_cashier" => $general_cashier,
				"form_action" => base_url("general_cashier/general_cashier/items")."?evidence_number={$general_cashier->evidence_number}",
				"populate_url" => base_url("general_cashier/general_cashier/detail_collection")."?evidence_number={$general_cashier->evidence_number}",
				"lookup_accounts" => base_url("general_cashier/general_cashier/lookup_accounts/dt_credit_details"), 
				"form_voucher_invoice" => base_url("general_cashier/general_cashier/form_voucher_invoice"), 
				"create_url" => base_url("general_cashier/general_cashier/item_create")."?evidence_number={$general_cashier->evidence_number}",
				"update_url" => base_url("general_cashier/general_cashier/item_update")."?evidence_number={$general_cashier->evidence_number}",
				"delete_url" => base_url("general_cashier/general_cashier/item_delete")."?evidence_number={$general_cashier->evidence_number}",
				"form" => TRUE,
				"datatables" => TRUE,
			);
		
		if ($is_edit === TRUE)
		{
			return	$this->load->view( "general_cashier/general_cashier/tables/tables_edit", $data );		
			
		} else 
		{
			return	$this->load->view( "general_cashier/general_cashier/tables/credit", $data );		
		}
		
	}
		
	public function item_create( $evidence_number='' )
	{
		if( ! $this->input->is_ajax_request() )
		{
			show_error( "Bad Request", 400 );
		}
		
		if( $evidence_number == '' )
		{
			$evidence_number = $this->input->get_post( 'evidence_number', TRUE );
		}
		
		$response = array(
				"status" => "success",
				"error" => "",
				"code" => "200"
			);
		
		if( $this->input->post() )
		{
			$post_data = $this->input->post( 'f', TRUE );
			$item = (object) $post_data;
			
			
			if( general_cashier_helper::find_general_cashier( $post_data['evidence_number'] ) )
			{
				$data = array(
						'account_id' => @$item->account_id,
						'evidence_number' => @$item->evidence_number,
						'house_id' => @$this->_house_id,
						'value' => 0.00,
						'description' => NULL,
						'normal_pos' => @$item->normal_pos,
						'qty' => 1,
					);
								
				if( ! $this->db->insert( "ap_general_cashier_details", $data) )
				{
					$response["error"] = "Internal Server Error";
					$response["status"] = "error";			
					$response["code"] = "500";
				}	
			} else
			{
				$response["error"] = "Not Found";
				$response["status"] = "error";
				$response["code"] = "404";
			}
		} else
		{
			$response["error"] = "Precondition Failed";
			$response["status"] = "error";
			$response["code"] = "412";
		}
		
		$this->template->build_json( $response );
	}
	
	public function item_update( $evidence_number='' )
	{
		if( ! $this->input->is_ajax_request() )
		{
			show_error( "Bad Request", 400 );
		}
		
		if( $evidence_number == '' )
		{
			$evidence_number = $this->input->post( 'evidence_number');
		}
		
		$response = array(
				"status" => "success",
				"error" => "",
				"code" => "200"
			);
		
		if( $this->input->post() )
		{
			$post_data = $this->input->post( 'f', TRUE );
			$item = (object) $post_data;
			$detail_id = $this->input->post("detail_id");
				
			if( general_cashier_helper::find_general_cashier($item->evidence_number) )
			{
					$data = array(
						'account_id' => @$item->account_id,
						//'evidence_number' => @$item->evidence_number,
						//'house_id' => @$this->_house_id,
						//'value' => @$item->value,
						//'description' => @$item->description,
						'normal_pos' => @$item->normal_pos,
					);
								
				if( ! $this->db->where("id", $detail_id )->update( "ap_general_cashier_details", $data) )
				{
					$response["error"] = "Internal Server Error";
					$response["status"] = "error";			
					$response["code"] = "500";
				}	
			} else
			{
				$response["error"] = "Not Found";
				$response["status"] = "error";
				$response["code"] = "404";
			}
		} else
		{
			$response["error"] = "Precondition Failed";
			$response["status"] = "error";
			$response["code"] = "412";
		}
		
		$this->template->build_json( $response );
	}
	
	public function item_delete( $evidence_number='' )
	{
		if( ! $this->input->is_ajax_request() )
		{
			show_error( "Bad Request", 400 );
		}
		
		if( $evidence_number == '' )
		{
			$evidence_number = $this->input->get_post( 'evidence_number', TRUE );
		}
		
		$response = array(
				"status" => "success",
				"error" => "",
				"code" => "200"
			);
		
		if( $this->input->post() )
		{
			$post_data = $this->input->post( 'f', TRUE );
			$item = (object) $post_data;
			
			if( general_cashier_helper::find_general_cashier($evidence_number) )
			{
				if( ! $this->db->where(array("id" => @$item->id))->delete("ap_general_cashier_details") )
				{
					$response["error"] = "Internal Server Error";
					$response["status"] = "error";			
					$response["code"] = "500";
				}
			} else
			{
				$response["error"] = "Not Found";
				$response["status"] = "error";
				$response["code"] = "404";
			}
		} else
		{
			$response["error"] = "Precondition Failed";
			$response["status"] = "error";
			$response["code"] = "412";
		}
		
		$this->template->build_json( $response );
	}
	
	public function detail_collection( $state = false, $evidence_number = false )
    {
        $start = $this->input->get_post('start', true);
        $length = $this->input->get_post('length', true);
        $order = $this->input->get_post('order', true);
        $columns = $this->input->get_post('columns', true);
        $search = $this->input->get_post('search', true);
        $draw = $this->input->get_post('draw', true);
		
		$db_from = "ap_general_cashier_details a";
		$db_where = array();
		$db_like = array();
		
		// prepare defautl flter
		$db_where['a.deleted_at'] = NULL;
		$db_where['b.house_id'] = $this->_house_id;
		if( $state !== false )
		{
			$db_where['a.state >='] = $state;
		}

		$db_where['a.evidence_number'] = $evidence_number;
		if( $evidence_number == false )
		{
			$db_where['a.evidence_number'] = $this->input->get_post('evidence_number');
		}
		
		// preparing default
		if( isset($search['value']) && ! empty($search['value']) )
        {
            $keywords = $this->db->escape_str( $search['value'] );
						
			$db_like[ $this->db->escape_str("a.debit") ] = $keywords;
			$db_like[ $this->db->escape_str("a.credit") ] = $keywords;
			$db_like[ $this->db->escape_str("a.notes") ] = $keywords;

			$db_like[ $this->db->escape_str("b.account_number") ] = $keywords;
			$db_like[ $this->db->escape_str("b.account_name") ] = $keywords;
						
			/*for($i=0; $i<count($columns); $i++)
            {
                if( isset($columns[$i]['searchable']) && $columns[$i]['searchable'] == 'true')
                {
                	$column_name = $columns[$i]['data'];
					$column_value = $search['value'];
					
					$db_like[$column_name] = $column_value;
				}
            }*/
        }
		
		// get total records
		$this->db->from( $db_from )
			->join( "{$this->account_m->table} b", "a.account_id = b.id", "LEFT OUTER" )
		;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		$records_total = $this->db->count_all_results();
		
		// get total filtered
		$this->db
			->from( $db_from )
			->join( "{$this->account_m->table} b", "a.account_id = b.id", "LEFT OUTER" )
			;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }		
		$records_filtered = $this->db->count_all_results();
		
		// get result filtered
		$db_select = <<<EOSQL
			a.id,
			a.evidence_number,
			a.account_id,
			b.account_number,
			b.account_name,
			b.normal_pos,
			a.value,
			a.value As value_money,
			a.description,
			a.state,
			a.created_at,
			a.updated_at
EOSQL;

		$this->db
			->select( $db_select )
			->from( $db_from )
			->join( "{$this->account_m->table} b", "a.account_id = b.id", "LEFT OUTER" )
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
			$row->created_at = strftime(config_item('date_format'), @$row->created_at);
			$row->updated_at = strftime(config_item('date_format'), @$row->updated_at);
			
            $output['data'][] = $row;
        }
		
		$this->template
			->build_json( $output );
    }


	public function datatable_collection( $evidence_number = false )
    {
        $start = $this->input->get_post('start', true);
        $length = $this->input->get_post('length', true);
        $order = $this->input->get_post('order', true);
        $columns = $this->input->get_post('columns', true);
        $search = $this->input->get_post('search', true);
        $draw = $this->input->get_post('draw', true);
		
		$filter = $this->input->post('f');
		//print_r($this->input->post());exit;
		
		foreach ( $filter as $k => $v )
		{
			if( $k == 'date_start')
			{
				$filter['transaction_date >='] = $filter[ $k ];
				unset( $filter[$k] );
			}

			if( $k == 'date_end')
			{
				$filter['transaction_date <='] = $filter[ $k ];
				unset( $filter[$k] );
			}

			if( $k == 'type_id')
			{
				$filter['a.transaction_type'] = $filter[ $k ];
				unset( $filter[$k] );				
				$k = 'a.transaction_type';
			}
			
			if( $v == "all" )
			{
				unset( $filter[$k] );
			}
		}
				
		$db_from = "gc_general_cashiers a";
		$db_where = $filter;
		$db_like = array();
		
		// prepare defautl flter
		$db_where['a.deleted_at'] = NULL;
		$db_where['a.house_id'] = $this->_house_id;
		
		// preparing default
		if( isset($search['value']) && ! empty($search['value']) )
        {
            $keywords = $this->db->escape_str( $search['value'] );
						
			$db_like[ $this->db->escape_str("a.evidence_number") ] = $keywords;
			$db_like[ $this->db->escape_str("a.transaction_date") ] = $keywords;
			$db_like[ $this->db->escape_str("a.description") ] = $keywords;
			$db_like[ $this->db->escape_str("a.debit") ] = $keywords;
			$db_like[ $this->db->escape_str("a.credit") ] = $keywords;
			

			//$db_like[ $this->db->escape_str("b.supplier_name") ] = $keywords;
						
        }
		
		// get total records
		$this->db->from( $db_from )
		;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		$records_total = $this->db->count_all_results();
		
		// get total filtered
		$this->db
			->from( $db_from )
			;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }		
		$records_filtered = $this->db->count_all_results();
		
		// get result filtered
		$db_select = <<<EOSQL
			a.*
EOSQL;

		$this->db
			->select( $db_select )
			->from( $db_from )
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
			//$row->created_at = strftime(config_item('date_format'), @$row->created_at);
			//$row->updated_at = strftime(config_item('date_format'), @$row->updated_at);
			
            $output['data'][] = $row;
        }
		
		$this->template
			->build_json( $output );
    }
	
	public function gen_evidence_number()
	{
		if ( $this->input->get())
		{
			$response = array(
					"status" => "success",
					"message" => "",
					"code" => "200",
				);

			$transaction_type = $this->input->get("transaction_type");
			
			if ( empty($transaction_type) )
			{
				$response["message"] = "Internal Server Error!";
				$response["status"] = "error";
				$response["code"] = "500";
				
				print_r(json_encode( $response, JSON_NUMERIC_CHECK )); 
				exit(0);
			}
			
			$response['evidence_number'] = general_cashier_helper::gen_evidence_number( $this->_house_id, $transaction_type );

			print_r(json_encode( $response, JSON_NUMERIC_CHECK )); 
			exit(0);
		}		
	}
		
	public function lookup_accounts( $table = NULL, $trId = NULL , $general_cashier_id = NULL, $is_create = NULL ){
	
		$general_cashier = $this->db->where("id", $general_cashier_id)->get( "gc_general_cashiers" )->row();
		$post_action = ($is_create) ? base_url("general_cashier/general_cashier/item_create") : base_url("general_cashier/general_cashier/item_update");
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$data = array(
						"table" => $table,
						"trId" => is_numeric($trId) ? $trId : NULL, 
						"general_cashier" => $general_cashier, 
						"post_action" => $post_action
					);
					
			$this->load->view( 'general_cashier/general_cashier/lookup/accounts', $data );
		} 
	}

	public function lookup_suppliers( $is_ajax_request = FALSE )
	{
	
		if( $this->input->is_ajax_request() || $is_ajax_request !== FALSE )
		{
			$this->load->view( 'general_cashier/general_cashier/lookup/suppliers' );
		} 
	}

	public function form_voucher_invoice( $table, $integration_source, $rowIndex )
	{
	
		if( $this->input->is_ajax_request() )
		{
			$data = array(
					"table" => $table,
					"rowIndex" => $rowIndex,
					"lookup_voucher_invoice" => base_url("general_cashier/lookup_voucher_invoice"),
					"lookup_voucher_invoice_details" => base_url("general_cashier/lookup_voucher_invoice_details"),
				);
			if ( $integration_source == "AP")
			{
				$this->load->view( 'general_cashier/general_cashier/form/voucher', $data );
				
			} elseif( $integration_source == "AR"){
				
				$this->load->view( 'general_cashier/general_cashier/form/invoice', $data );
			}
		} 
	}

	public function lookup_voucher_invoice( $table, $integration_source, $rowIndex = NULL )
	{	

		if( $this->input->is_ajax_request() )
		{
			$data = array(
					"table" => $table,
					"rowIndex" => $rowIndex,
				);
	
			if ( $integration_source == "AP")
			{
				$this->load->view( 'general_cashier/general_cashier/lookup/vouchers', $data );
				
			} elseif( $integration_source == "AR"){
				
				$this->load->view( 'general_cashier/general_cashier/lookup/invoices', $data );
			}
		}
	}
	
	public function lookup_voucher_invoice_details( $table, $integration_source, $rowIndex )
	{
	
		if( $this->input->is_ajax_request() )
		{
			$data = array(
					"table" => $table,
					"rowIndex" => $rowIndex
				);
			
			if ( $integration_source == "AP")
			{
				$this->load->view( 'general_cashier/general_cashier/form/voucher_details', $data );
				
			} elseif( $integration_source == "AR"){
				
				$this->load->view( 'general_cashier/general_cashier/form/invoice_details', $data );
			}
		} 
	}
}



