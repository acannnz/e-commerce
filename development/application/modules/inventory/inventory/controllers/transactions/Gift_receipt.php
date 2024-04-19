<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Gift_receipt extends ADMIN_Controller
{
	protected $nameroutes;
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('inventory');
		
		$this->data['nameroutes'] = $this->nameroutes = 'inventory/transactions/gift_receipt'; 
		
		$this->load->language('inventory');		
		$this->load->library('inventory');
		$this->load->helper('inventory');
		
		$this->load->model('gift_receipt_model');
		$this->load->model('gift_receipt_detail_model');

		$this->load->model('supplier_model');
		$this->load->model('section_model');

		$this->load->model('item_model');
		$this->load->model('item_category_model');
		$this->load->model('item_location_model');
		$this->load->model('item_unit_model');
		$this->load->model('item_type_model');
		$this->load->model('currency_model');
	}
	
	//load note list view
	public function index()
	{		
		$this->data['dropdown_section'] = $this->section_model->for_dropdown(true, ['TipePelayanan' => 'GUDANG']);


		$this->template
			->title(lang('heading:gift_receipt'),lang('heading:transactions'))
			->set_breadcrumb(lang('heading:gift_receipt_list'), site_url($this->nameroutes))
			->build("transactions/gift_receipt/index", $this->data);
	}

	public function create()
	{		
		$item = (object)[
			"No_Bonus" => inventory_helper::gen_gift_receipt_evidence_number( date('Y-m-d') ),
			"Tgl_Bonus" => date('Y-m-d'),
			"No_DO" => '',
			"PPN" => 10,
			"NilaiPPN" => '',
			"DateUpdate" => date('Y-m-d'),
			"User_ID" => $this->user_auth->User_ID,
			"Supplier_ID" => NULL,
			"Lokasi_ID" => 0,
			"Total_Nilai" => 0,
			"Currency_ID" => 1,
			"Batal" => 0,
			"Keterangan" => '',
		];
		
		if( $this->input->post() ) 
		{
			$post_header = array_merge( (array) $item, $this->input->post("header") );
			$post_header['No_Bonus'] = inventory_helper::gen_gift_receipt_evidence_number( $post_header['Tgl_Bonus'] );
			$post_details = $this->input->post("details");
			$additional = $this->input->post("additional");
			
			$this->load->library('form_validation');
			$this->form_validation->set_rules($this->gift_receipt_model->rules['insert']);
			$this->form_validation->set_data($post_header);
			if( $this->form_validation->run())
			{										
				if ( inventory_helper::check_closing_period( $post_header['Tgl_Bonus'] ))
				{
					$response = array(
								"status" => 'error',
								"message" => lang('message:already_closing_period'),
								"code" => 500
							);
					
					response_json( $response );
				}
				
				if ( empty( $post_details ))
				{
					$response = array(
								"status" => 'error',
								"message" => lang('message:empty_detail_item'),
								"code" => 500
							);
					
					response_json( $response );
				}
				
				$this->db->trans_begin();
				
					$this->gift_receipt_model->create( $post_header );

					$activities_description = sprintf( "Input Penerimaan Bonus. # %s # %s # %s ", $post_header['No_Bonus'], $additional['Supplier_Name'], $additional['SectionName']);
					insert_user_activity( $activities_description, $post_header['No_Bonus'], $this->gift_receipt_model->table);
					
					foreach ($post_details as $row)
					{
						$row['No_Bonus'] = $post_header['No_Bonus'];
						$this->gift_receipt_detail_model->create( $row ); 

						$item_data = $this->item_model->get_one( $row['Barang_ID'] );
						
						$_insert_fifo = [
							'location_id' => $post_header['Lokasi_ID'], 
							'item_id' => $row['Barang_ID'],  
							'item_unit_code' => $row['Kode_Satuan'],  
							'qty' => $row['Qty'],
							'price' => $row['Harga'],
							'conversion' => 1,
							'evidence_number' => $post_header['No_Bonus'],
							'trans_type_id' => 501,
							'in_out_state' => 1,
							'trans_date' => $post_header['Tgl_Bonus'],
							'exp_date' => $post_header['Tgl_Bonus'],
							'item_type_id' => 0,
							'TglED' => $row['TglED'],
							'NoBatch' => $row['NoBatch']
						];					
						inventory_helper::insert_warehouse_fifo_gift( $_insert_fifo );
						
						$activities_description = sprintf( "Input Penerimaan Bonus Item. # %s # %s # %s # Qty : %s # Harga: %s # %s ", $post_header['No_Bonus'], $row['Barang_ID'], $item_data->Nama_Barang, $row['Qty'], $row['Harga'], $additional['SectionName'] );
						insert_user_activity( $activities_description, $post_header['No_Bonus'], $this->gift_receipt_detail_model->table);
					}					
					
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
					//$this->db->trans_rollback();
					$this->db->trans_commit();
					$response = array(
							"status" => 'success',
							"message" => lang('global:created_successfully'),
							"code" => 200
						);
				}			
			} else
			{
				$response["message"] = $this->form_validation->get_all_error_string();
				$response["status"] = "error";
				$response["code"] = "500";
			}

			response_json( $response );
		}
		
		$this->data['item'] = $item;

		$this->data['form_action'] = $form_action = current_url();
		$this->data['dropdown_section'] = $this->section_model->for_dropdown(FALSE);

		$this->data['dropdown_currency'] = $this->currency_model->to_list_data();
		$this->data['lookup_supplier'] = base_url("{$this->nameroutes}/lookup/lookup_supplier");
		$this->data['lookup_item'] = base_url("{$this->nameroutes}/lookup/lookup_item");


		$this->template
			->title(lang('heading:gift_receipt'),lang('heading:transactions'))
			->set_breadcrumb(lang('heading:gift_receipt_list'), site_url($this->nameroutes))
			->set_breadcrumb(lang('heading:gift_receipt'))
			->build("transactions/gift_receipt/form", $this->data);
	}
	
	public function view($id = 0)
	{
		$this->data['item'] = $item = $this->gift_receipt_model->get_one($id);
		
		$this->data['collection'] = $this->gift_receipt_detail_model->get_all_by( NULL, [], $id );
		
		$this->data['supplier'] = $this->supplier_model->get_one( $item->Supplier_ID );
		$this->data['dropdown_section'] = $this->section_model->for_dropdown(true, ['TipePelayanan' => 'GUDANG']);
		$this->data['dropdown_currency'] = $this->currency_model->to_list_data();

		$this->data['cancel_url'] = base_url("{$this->nameroutes}/cancel/{$id}");

		$this->data['is_edit'] = TRUE;		
		
		$this->load->view("transactions/gift_receipt/view", $this->data);
	}
	
	public function cancel($id = 0)
	{
		$this->data['item'] = $item = $this->gift_receipt_model->get_one($id);
		
		if ($item  && (1 == $this->input->post('confirm') ) )
		{ 
			if ( inventory_helper::check_closing_period( date('Y-m-d', strtotime($item->Tgl_Bonus)) ))
			{
				$response = array(
							"status" => 'error',
							"message" => lang('message:already_closing_period'),
							"code" => 500
						);
				
				response_json( $response );
			}
			
			$this->db->trans_begin();
				
				$this->gift_receipt_model->update( ['Batal' => 1 ], $id );

				$activities_description = sprintf( "Batal Penerimaan. # %s", $item->No_Bonus );
				insert_user_activity( $activities_description, $item->No_Bonus, $this->gift_receipt_model->table);
				
				$collection = $this->gift_receipt_detail_model->get_all_by( NULL, TRUE, $id );
				foreach( $collection as $row )
				{					
					$_insert_fifo = [
						'location_id' => $item->Lokasi_ID, 
						'item_id' => $row['Barang_ID'],  
						'item_unit_code' => $row['Kode_Satuan'],  
						'qty' => $row['Qty'], 
						'price' => $row['Harga'],  
						'conversion' => 1,  
						'evidence_number' => $item->No_Bonus. "-R",
						'trans_type_id' => 565,
						'in_out_state' => 0,
						'trans_date' => date('Y-m-d'),
						'exp_date' => date('Y-m-d'),  
						'item_type_id' => 0, 
						'TglED' => $row['TglED'],
						'NoBatch' => $row['NoBatch']
					];					
					inventory_helper::insert_warehouse_fifo_gift( $_insert_fifo );
					
					$activities_description = sprintf( "Batal Penerimaan Bonus Item. # %s # %s # %s # Qty : %s # Harga: %s", $item->No_Bonus, $row['Barang_ID'], $row['Nama_Barang'], $row['Qty'], $row['Harga'] );
					insert_user_activity( $activities_description, $item->No_Bonus, $this->gift_receipt_detail_model->table);
					
				}
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
				response_json(["status" => 'error', 'message' => lang('global:cancel_failed'), 'success' => FALSE]);
			} else
			{
				$this->db->trans_commit();
				response_json(["status" => 'success', 'message' => lang('global:cancel_successfully'), 'success' => TRUE]);
			}
		} 
		
		$this->data['form_action'] = $form_action = current_url();
		$this->load->view('transactions/gift_receipt/modal/cancel', $this->data);
	}
		
	public function lookup_collection ()
	{
		$this->datatable_collection();
	}
	
	public function datatable_collection()
	{
		$start = $this->input->get_post('start', true);
        $length = $this->input->get_post('length', true);
        $order = $this->input->get_post('order', true);
        $columns = $this->input->get_post('columns', true);
        $search = $this->input->get_post('search', true);
        $draw = $this->input->get_post('draw', true);
		
		$db_from = "{$this->gift_receipt_model->table} a";
		$db_where = array();
		$db_like = array();
		
		if( $this->input->post("date_from") ){
			$db_where['a.Tgl_Bonus >='] = $this->input->post("date_from");
		}

		if( $this->input->post("date_till") ){
			$db_where['a.Tgl_Bonus <='] = $this->input->post("date_till");
		}

		if( $this->input->post("location_id") ){
			$db_where['a.Lokasi_ID'] = $this->input->post("location_id");
		}
		
		
		// preparing default
		if( isset($search['value']) && ! empty($search['value']) )
        {
            $keywords = $this->db->escape_str( $search['value'] );
			
			$db_like[ $this->db->escape_str("a.No_Bonus") ] = $keywords;
			$db_like[ $this->db->escape_str("a.Total_Nilai") ] = $keywords;
			$db_like[ $this->db->escape_str("b.Nama_Supplier") ] = $keywords;
        }
		
		// get total records
		$this->db
			->from( $db_from )
			->join("{$this->supplier_model->table} b", "a.Supplier_ID = b.Supplier_ID", "LEFT OUTER")
			;

		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		$records_total = $this->db->count_all_results();
		
		// get total filtered
		$this->db
			->from( $db_from )
			->join("{$this->supplier_model->table} b", "a.Supplier_ID = b.Supplier_ID", "LEFT OUTER")
			;

		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }		
		$records_filtered = $this->db->count_all_results();
		
		// get result filtered
		$db_select = <<<EOSQL
			a.No_Bonus,
			a.No_DO,
			a.Tgl_Bonus,
			a.Total_Nilai,
			a.Batal,
			b.Kode_Supplier,
			b.Nama_Supplier,
EOSQL;

		$this->db
			->select( $db_select )
			->from( $db_from )
			->join("{$this->supplier_model->table} b", "a.Supplier_ID = b.Supplier_ID", "LEFT OUTER")
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

		response_json( $output );
    }
	
	public function lookup( $view, $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( "transactions/gift_receipt/lookup/{$view}");
		}
	}
}

