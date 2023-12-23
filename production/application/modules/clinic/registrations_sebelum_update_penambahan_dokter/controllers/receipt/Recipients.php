<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Recipients extends Admin_Controller
{
	protected $_translation = 'product';	
	protected $_model = 'recipients_m';  
	 
	public function __construct()
	{ 
		parent::__construct();
		$this->simple_login->check_user_role('registration');  
		  
		$this->load->language( "inventory" );
		$this->load->helper( "recipient" );
		//$this->load->library( 'my_object', (array()), 'item' );
		
		$this->load->model("inventory/products_m");
		$this->load->model("inventory/recipient_details_m");
		$this->load->model("inventory/inventory_cards_m");
		$this->load->model("inventory/products_m");
		$this->load->model("inventory/procurement_type_m");
		$this->page = 'Penerimaan Barang';//lang("recipient:page");
		$this->template
			->title( $this->page . ' - ' . $this->config->item('company_name') );
	}
	
	public function index()
	{
		$data = array(
				'page' => $this->page,
				"form" => TRUE,
				"typeahead" => TRUE,
				"autocomplete" => TRUE,
				'datatables' => TRUE,
				'navigation_minimized' => TRUE,
			);
		
		$this->template
			->set( "heading", lang("recipient:page") )
			->set_breadcrumb( lang("recipient:breadcrumb"), base_url("") )
			->set_breadcrumb( lang("recipient:breadcrumb") )
			->build('recipients/datatable', (isset($data) ? $data : NULL));
	}
	
	public function create( $supplier_id = "" )
	{
		$item_data = array(
				'id' => 0,
				'code' => recipient_helper::gen_recipient_number(),
				'house_id' => 0,
				'supplier_code' => 0,
				'currency_id' => 0,
				'date' => date("Y-m-d"),
				'description' => null,
				'payment_type' => null,
				'diskon_type' => null,
				'payment_total' => 0,
				'tax' => 0,
				'no_do' => null,
				'posting_gl' => 0,
				'due_date' => null,
				'include_ppn' => 0,
				'reduced_fare' => 0,
				'other_purchase' =>0,
				'tax_value' => 0,
				'recipient_type_id' => null,
				'state' => 1,
				'created_at' => null,
				'created_by' => 0,
				'updated_at' => null,
				'updated_by' => 0,
				'deleted_at' => null,
				'deleted_by' => 0,
			);
		
		$this->load->library( 'my_object', $item_data, 'item' );
		// flash recipient
		if( $supplier_id = $this->input->get_post( 'supplier_id', true ) )
		{
			if( $this->_apply_supplier( $supplier_id) !== FALSE )
			{
				redirect( 'inventory/receipt/recipient/create' );
			}
		}
		
		// bind supplier
		$this->_bind_supplier( $item_data );
		
		// add item data		
		$this->item->addData( $item_data );
		//$this->load->library( 'my_object', $item_data, 'item' );
		
		$dropdown_category = array();
		$dropdown_category = $this->procurement_type_m->get_procurement_type();
		//print_r($dropdown_category);exit;
		
		if( $this->input->post() ) 
		{
			
			
			$this->load->library( 'form_validation' );
			$this->item->addData( $this->input->post("f") );
			$details = explode(",", $this->input->post("details"));
			$data_details = array();
						
			foreach($details as $i)
			{
				parse_str($i, $d);
				$d['recipient_code'] = $this->item->code;
				$d['description'] = $this->input->post("f[supplier_name]");
				$d['tax'] = $this->input->post('f[tax_value]');
				
				array_push($data_details,$d);
			}
			$this->form_validation->set_rules( $this->get_model()->rules['insert'] );
			$this->form_validation->set_data( $this->item->toArray() );
			
			$response = array(
					"status" => "success",
					"error" => "",
					"code" => "200",
				);
			//print_r($this->item->toArray());exit;
			if( $this->form_validation->run() )
			{
				if( $id = $this->get_model()->insert( $this->item->toArray() ) )
				{
					$this->get_model()->delete_cache( 'accounting.collection' );
					//print_r($data_details);exit;
					
					foreach($data_details as $row)
					{
						if ($this->create_details($row) )
						{
							if($this->card_details($row) )
							{
								if(!$this->sell_price($row) )
								{
									$response["message"] = "Failed update harga beli!!";
									$response["status"] = "error";
									$response["code"] = "500";
								}
							}
						}
					}
					$response["id"] = $id;
					
				} else
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
			
			$this->template->build_json( $response );
			
		}else{
		
			if( $this->input->is_ajax_request() )
			{
				$data = array(
						"currency_symbol" => $this->config->item( "default_currency_symbol" ),
						"types" => $types,
						"groups" => $groups,
						"units" => $units,
						"item" => $this->item,
						"is_ajax_request" => TRUE,
						'navigation_minimized' => TRUE,
						"is_modal" => TRUE,
						"is_cancel" => FALSE,
						"datatables" => TRUE,
						"dropdown_category" => $dropdown_category,
						"url_lookup_suppliers" => base_url("inventory/receipt/lookup_suppliers")."?is_modal=yes",
	
					);
				
				$this->load->view( 
						'receipt/modal/create_edit', 
						array('form_child' => $this->load->view('receipt/form', $data, true))
					);
			} else
			{
				$data = array(
						//"page" => $this->page."_".strtolower(__FUNCTION__),
						"page" => "Transaksi Penerimaan",
						"item" => $this->item,
						"form" => TRUE,
						"datatables" => TRUE,
						"typeahead" => TRUE,
						"autocomplete" => TRUE,
						"is_cancel" => FALSE,
						"dropdown_category" => $dropdown_category,
						"currency_symbol" => $this->config->item( "default_currency_symbol" ),
						"url_lookup_suppliers" => base_url("inventory/receipt/lookup_suppliers"),
						'navigation_minimized' => TRUE,					
						//"groups" => $groups,
						//"units" => $units,
					);
				
				$this->template
					->set( "heading", "Transaksi Penerimaan")//lang("product:create_heading") )
					->set_breadcrumb( lang("inventory:breadcrumb"), base_url( "" ) )
					->set_breadcrumb( "recipient", base_url("inventory/receipt"))//lang("product:breadcrumb"), base_url("inventory/receipt") )
					->build('recipients/form', $data);
			}
		}
	}
	
	public function create_details( $data = array() )
	{
		$this->load->library( 'form_validation' );
		
		$this->form_validation->set_rules( $this->recipient_details_m->rules['insert'] );
		$this->form_validation->set_data( $data );
		//print_r($data);exit;
		if( $this->form_validation->run() )
		{
			if( $this->recipient_details_m->insert( $data ) )
			{
				return TRUE;
										
			} else
			{
				return false;
			}
		} else
		{
			return false;
		}
	}
	
	public function card_details( $data = array(), $is_cancel = FALSE )
	{
		//print_r($is_cancel);exit;
		$inventory_card = recipient_helper::gen_card_inventory_number();
		$this->load->library( 'form_validation' );
		
		$this->form_validation->set_rules( $this->inventory_cards_m->rules['insert'] );
		$this->form_validation->set_data( $data );
		//print_r($data);
		//print_r("Work!");
		if( $this->form_validation->run() )
		{
			//if( $this->recipient_details_m->insert( $data ) )
			//{
				if($is_cancel != TRUE){
					$datas = array(
								   "evidence_code"=> @$data['recipient_code'],
								   "product_code" => @$data['product_code'],
								   "unit_code" => @$data['unit_code'],
								   "qty_in"=> @$data['qty_recipient'],
								   "price_in"=> @$data['purchase_price'],
								   "code"=> @$inventory_card,
								   "time"=> date("H:m:s:"),
								   "description" => $data['description'],
								   //"transaction_date" => @$data['created_at']
								   );
				}else{
					$datas = array(
								   "evidence_code"=> @$data['recipient_code'],
								   "product_code" => @$data['product_code'],
								   "unit_code" => @$data['unit_code'],
								   "qty_out"=> @$data['qty_recipient'],
								   "price_out"=> @$data['purchase_price'],
								   "code"=> @$inventory_card,
								   "time"=> date("H:m:s:"),
								   "description" => $data['description'],
								   //"transaction_date" => @$data['created_at']
								   );
				}
				//var_dump($this->inventory_cards_m->insert($datas));
				if( $this->inventory_cards_m->insert($datas))
				{
					return TRUE;						
				} else {
					return false;
				}
		} else
		{
			return false;
		}
	}
	
	public function sell_price( $data = array())
	{
		$this->load->library( 'form_validation' );
		$this->form_validation->set_rules( $this->products_m->rules['update'] );
		$this->form_validation->set_data( $data );
		if( $this->form_validation->run() )
		{
				$tax_value = @$data['tax'];
				
				$purchase_price = @$data['purchase_price'];
				$sell_price = $purchase_price + ($purchase_price * $tax_value/100); //count for sale price
				$all_product = $this->get_model()->get_konversi(@$data['product_code']); //query conversion
				$qty_in = $this->inventory_cards_m->stock_count('qty_in',@$data['product_code']); //count stok in
				$qty_out = $this->inventory_cards_m->stock_count('qty_out',@$data['product_code']); //count stok out
				$stok = $qty_in->qty_in - $qty_out->qty_out;
				
				$konversi = $all_product->sale_conversion;
				$default_price = $all_product->purchase_price;
				
				$average = (($stok * @$data['purchase_price']) + ($default_price*(@$data['qty_recipient']*$konversi)))/($stok + (@$data['qty_recipient']*$konversi));
				
				$product = array(
					 "purchase_price"=> @$data['purchase_price'],
					 "sale_price" => $sell_price,
					 "average_price" => $average,
					 );
				$filters = array(
					"code" => @$data['product_code'],
					);
				
				//var_dump($this->products_m->update($product, $filters));exit;
				if( $this->products_m->update($product, $filters) )
				{
					return TRUE;						
				} else {
					return false;
				}
		} else
		{
			return false;
		}
	}

	
	public function edit( $id=0 )
	{
		//print_r($id);exit;
		$id = (int) @$id;
		$items = $this->get_model()->as_array()->get( $id );
		
		//get supplier name
		$supplier_code = $this->get_model()->get_supplier_name($items['supplier_code']);
		$item = array_merge($items,$supplier_code);
		
		if( ! $item ){ $item = array('id' => 0); }
		$this->load->library( "my_object", $item, "item" );
		//print_r($this->item);exit;
		
		if( $this->input->post() ) 
		{
			
			
			$this->load->library( 'form_validation' );
			
			$this->item->addData( $this->input->post("f") );
			$this->form_validation->set_rules( $this->get_model()->rules['insert'] );
			$this->form_validation->set_data( $this->item->toArray() );
			
			//$response = array(
//					"status" => "success",
//					"error" => "",
//					"code" => "200",
//				);
			
			if( $this->form_validation->run() )
			{
				if( $this->get_model()->update( $this->item->toArray(), @$id ) )
				{
					$this->get_model()->delete_cache( 'common_recipient.collection' );
					
					make_flashdata(array(
							'response_status' => 'success',
							'message' => lang('global:updated_successfully')
						));
						
				} else {
					make_flashdata(array(
							'response_status' => 'error',
							'message' => lang('global:updated_failed')
						));
						
					$response["message"] = lang('global:updated_failed');
					$response["status"] = "error";
					$response["code"] = "500";				
				}
			} else {
				make_flashdata(array(
						'response_status' => 'error',
						'message' => $this->form_validation->get_all_error_string()
					));

					$response["message"] = $this->form_validation->get_all_error_string();
					$response["status"] = "error";
					$response["code"] = "500";				
			}

			//$this->template->build_json( $response );
			//redirect("inventory/receipt/edit/$id");
			//redirect()
		}
			$dropdown_category = array();
			$dropdown_category = $this->procurement_type_m->get_procurement_type();
			
			if( $this->input->is_ajax_request() )
			{
				$data = array(
						"item" => $this->item,
						"is_ajax_request" => TRUE,
						"is_modal" => TRUE,
						"is_cancel" => FALSE,
						"form" => TRUE,
						"dropdown_category" => $dropdown_category,
						"currencies" => $this->get_model()->find_currency_list(),
					);
				
				$this->load->view( 
						'inventory/receipt/modal/create_edit', 
						array('form_child' => $this->load->view('recipients/form', $data, true))
					);
			} else	{
				//print_r("Tes!");exit;
				$data = array(
						"page" => $this->page,
						"item" => $this->item,
						"form" => TRUE,
						"is_edit" => TRUE,
						"is_cancel" => FALSE,
						"datatables" => TRUE,
						"dropdown_category" => $dropdown_category,
						"currencies" => $this->get_model()->find_currency_list(),
						'navigation_minimized' => TRUE,	
					);
				
				$this->template
					->set( "heading", lang("recipients:edit_heading") )
					->set_breadcrumb( lang("inventory:page"), base_url("inventory") )
					->set_breadcrumb( lang("services:breadcrumb"), base_url("inventory/receipt") )
					->set_breadcrumb( lang("recipients:edit_heading") )
					->build('recipients/form', $data);
			}

	}
	
	public function cancel( $id=0 )
	{
		//print_r($id);exit;
		$id = (int) @$id;
		$items = $this->get_model()->as_array()->get( $id );
		$is_cancel = TRUE;
		
		//get supplier name
		$supplier_code = $this->get_model()->get_supplier_name($items['supplier_code']);
		$item = array_merge($items,$supplier_code);
		
		if( ! $item ){ $item = array('id' => 0); }
		$this->load->library( "my_object", $item, "item" );
		
		
		if($is_cancel == TRUE){
			if( $this->input->post() ) 
			{
				//print_r($this->input->post());exit;
				
				
				if( $this->input->post("f[state]") == 1 ){
					$this->input->post("f[state]") == 2;
				}
				
				$insert = $this->input->post("f");
				$this->item->addData( $this->input->post("f") );
				$details = explode(",", $this->input->post("details"));
				$data_details = array();
				
				foreach($details as $i)
				{
					parse_str($i, $d);
					$d['recipient_code'] = $this->item->code;
					$d['description'] = "Cancel Penerimaan ".$this->input->post("f[supplier_name]");
					
					array_push($data_details,$d);
				}
				$this->load->library( 'form_validation' );
				
				$this->form_validation->set_rules( $this->get_model()->rules['insert'] );
				$this->form_validation->set_data( $this->item->toArray() );
				
				//$response = array(
	//					"status" => "success",
	//					"error" => "",
	//					"code" => "200",
	//				);
				
				if( $this->form_validation->run() )
				{
					if( $this->get_model()->update( $this->item->toArray(), @$id ) )
					{
						$this->get_model()->delete_cache( 'common_recipient.collection' );
						//print_r($is_cancel);exit;
						foreach($data_details as $row)
						{
							if ($this->create_details($row) )
							{
								if(!$this->card_details($row, $is_cancel) )
								{
									$response["message"] = "Failed insert into Card Inventory!!";
									$response["status"] = "error";
									$response["code"] = "500";
								}
							}else{
								$response["message"] = lang('global:created_failed');
								$response["status"] = "error";
								$response["code"] = "500";
							}
							//var_dump($this->card_details($row));exit;
							
						}
						
						$response["id"] = $id;
						
						make_flashdata(array(
								'response_status' => 'success',
								'message' => lang('global:updated_successfully')
							));
							
					} else {
						make_flashdata(array(
								'response_status' => 'error',
								'message' => lang('global:updated_failed')
							));
							
						$response["message"] = lang('global:updated_failed');
						$response["status"] = "error";
						$response["code"] = "500";				
					}
				} else {
					make_flashdata(array(
							'response_status' => 'error',
							'message' => $this->form_validation->get_all_error_string()
						));
	
						$response["message"] = $this->form_validation->get_all_error_string();
						$response["status"] = "error";
						$response["code"] = "500";				
				}
	
				//$this->template->build_json( $response );
				//redirect("inventory/receipt/edit/$id");
				//redirect()
			}
		}
			if( $this->input->is_ajax_request() )
			{
				$data = array(
						"item" => $this->item,
						"is_ajax_request" => TRUE,
						"is_modal" => TRUE,
						"form" => TRUE,
						"currencies" => $this->get_model()->find_currency_list(),
						"is_cancel" => TRUE,
					);
				
				$this->load->view( 
						'inventory/recipients/modal/create_edit', 
						array('form_child' => $this->load->view('recipients/form', $data, true))
					);
			} else	{
				//print_r("Tes!");exit;
				$data = array(
						"page" => $this->page,
						"item" => $this->item,
						"form" => TRUE,
						"is_cancel" => TRUE,
						"datatables" => TRUE,
						"currencies" => $this->get_model()->find_currency_list(),
						'navigation_minimized' => TRUE,	
					);
				
				$this->template
					->set( "heading", lang("recipients:edit_heading") )
					->set_breadcrumb( lang("inventory:page"), base_url("inventory") )
					->set_breadcrumb( lang("services:breadcrumb"), base_url("inventory/receipt") )
					->set_breadcrumb( lang("recipients:edit_heading") )
					->build('recipients/form', $data);
			}

	}
	
	public function delete( $id=0 )
	{
		$id = (int) @$id;
		
		$item = $this->get_model()->as_array()->get( $id );
		if( ! $item ){ $item = array('id' => 0); }
		$this->load->library( 'my_object', $item, 'item' );
		
		if( $this->input->post() ) 
		{
			
			
			if( 0 == @$this->item->id )
			{
				make_flashdata(array(
						'response_status' => 'error',
						'message' => lang( 'global:get_failed' )
					));
			
				redirect( $this->input->post( 'r_url' ) );
			}
			
			if( $this->item->id == $this->input->post( 'confirm' ) )
			{
				$this->get_model()->where( $id )->delete();				
				
				$this->get_model()->delete_cache( 'products.collection' );
				
				make_flashdata(array(
						'response_status' => 'success',
						'message' => lang('global:deleted_successfully')
					));
			}
			
			redirect( $this->input->post( 'r_url' ) );
		}
		
		$this->load->view( 'products/modal/delete', array('item' => $this->item) );
	}
	
	public function item_create( $recipient_code='' )
	{
		//print_r("Work!");exit;
		if( ! $this->input->is_ajax_request() )
		{
			show_error( "Bad Request", 400 );
		}
		
		if( $recipient_code == '' )
		{
			$recipient_code = $this->input->get_post( 'recipient_code', TRUE );
		}
		
		$response = array(
				"status" => "success",
				"error" => "",
				"code" => "200"
			);
		
		if( $this->input->post() )
		{
			$details = explode(",", $this->input->post("details"));
			$data_details = array();			
			foreach($details as $i)
			{
				parse_str($i, $d);
				$d['recipient_code'] = $this->input->get( 'recipient_code',TRUE );
				array_push($data_details,$d);
			}
			$item = $data_details;
			//print_r($item);exit;
			if( recipient_helper::find_recipient( $recipient_code ) )
			{
				foreach($item as $row){
					if( recipient_helper::prepare_recipient_detail( @$row) )
					{
						if( !recipient_helper::prepare_card_inventory (@$row)){
							$response["error"] = "Error insert into inventory card";
							$response["status"] = "error";			
							$response["code"] = "510";			
						}
					}else{
						$response["error"] = "Error insert into order details";
						$response["status"] = "error";			
						$response["code"] = "510";			

					}
					
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

	public function item_update( $recipient_code='' )
	{
		if( ! $this->input->is_ajax_request() )
		{
			show_error( "Bad Request", 400 );
		}
		
		if( $recipient_code == '' )
		{
			$recipient_code = $this->input->get_post( 'recipient_code', TRUE );
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
			//print_r($item);exit;
			if( recipient_helper::find_recipient($recipient_code) )
			{
				$data = array(
						'product_code' => @$item->product_code,
						'unit_code' => @$item->unit_code,
						'qty_recipient' => @$item->qty_recipient,
						'purchase_price' => @$item->purchase_price,
						'discount_in_percen' => @$item->discount_in_percen,
						'discount_in_amount' => @$item->discount_in_amount,
						'total_by_product' => @$item->total_by_product
					);
				
				$filter = array(
						"recipient_code" => @$recipient_code, 
						"id" => @$item->id,
					);
				
				if( $this->recipient_details_m->update( $data, $filter ) )
				{
					$response["success"] = "Update success";
					$response["status"] = "success";			
					$response["code"] = "500";

				}else{
					$response["error"] = "Internal Server Error";
					$response["status"] = "error";			
					$response["code"] = "500";
				}
			} else {
				$response["error"] = "Not Found";
				$response["status"] = "error";
				$response["code"] = "404";
			}
			if( recipient_helper::find_inventory_card($recipient_code, @$item->product_code))
			{
				//print_r("Work!");exit;
				$datas = array(
							   "qty_in"=> @$item->qty_recipient,
							   "price_in"=> @$item->purchase_price,
							   );
				
				$filters = array(
								 "product_code" => @$item->product_code,
								 "evidence_code" => @$recipient_code,
								 );
				if(!$this->inventory_cards_m->update($datas, $filters))
				{
					$response["error"] = "Update to Inventory Card Failed!";
					$response["status"] = "error";			
					$response["code"] = "500";
				}
			}else{
					$response["error"] = "Update Inventory card failed";
					$response["status"] = "error";			
					$response["code"] = "500";

			}

		} else
		{
			$response["error"] = "Precondition Failed";
			$response["status"] = "error";
			$response["code"] = "412";
		}
		
		$this->template->build_json( $response );
	}
	
	public function item_delete( $recipient_code='' )
	{
		if( ! $this->input->is_ajax_request() )
		{
			show_error( "Bad Request", 400 );
		}
		
		if( $recipient_code == '' )
		{
			$recipient_code = $this->input->get_post( 'recipient_code', TRUE );
		}
		//print_r($recipient_code);exit;
		
		$response = array(
				"status" => "success",
				"error" => "",
				"code" => "200"
			);
		
		if( $this->input->post() )
		{
			$post_data = $this->input->post( 'f', TRUE );
			$item = (object) $post_data;
			//print_r($item);exit;
			if( recipient_helper::find_recipient($recipient_code) )
			{
				if( !$this->recipients_m->delete_detail(@$item->id) )
				{
					$response["error"] = "Internal Server Error";
					$response["status"] = "error";			
					$response["code"] = "500";
				}
				var_dump(recipient_helper::find_inventory_card($recipient_code, @$item->product_code));exit;
				if( recipient_helper::find_inventory_card($recipient_code, @$item->product_code)){
					if( !$this->recipients_m->delete_card_detail(@$recipient_code, @$item->product_code))
					{
						$response["error"] = "Delete item in card inventory failed";
						$responde["status"] = "error";
						$response["code"] = "500";
					}
				}else{
					$response["error"] = "Item in inventory card not found!";
					$response["status"] = "error";
					$response["code"] = "404";
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

	
	public function details( $recipient_code='', $is_edit = FALSE, $is_cancel = FALSE )
	{
		//print_r($is_edit);exit;
		if( $recipient_code == '' )
		{
			$recipient_code = $this->input->get_post( 'code', TRUE );
		}
		
		$data = array(
				"recipient_code" => $recipient_code,
				"form_action" => base_url("inventory/receipt/items")."?recipient_code={$recipient_code}",
				"populate_url" => base_url("inventory/receipt/detail_collection")."?recipient_code={$recipient_code}",
				"lookup_products" => base_url("inventory/receipt/lookup_products"), 
				"create_url" => base_url("inventory/receipt/item_create")."?recipient_code={$recipient_code}",
				"update_url" => base_url("inventory/receipt/item_update")."?recipient_code={$recipient_code}",
				"delete_url" => base_url("inventory/receipt/item_delete")."?recipient_code={$recipient_code}",
				"details_module_url" => base_url("components/services/autocomplete"),
				"details_search_url" => base_url("components/services/autocomplete"),
				"details_apply_url" => base_url("general_ledger/journals/component_apply")."?journal_number={$recipient_code}",
				"form" => TRUE,
				"datatables" => TRUE,
				"is_cancel" => $is_cancel,
			);
		
		if ($is_edit === TRUE)
		{
			//print_r("Twerk!");exit;
			return	$this->load->view( "recipients/details/tables_edit", $data );		
			
		} else 
		{
			return	$this->load->view( "recipients/details/tables", $data );		
		}
		
	}
	
	public function lookup_products( $trId = 0, $journal_number = NULL ){
	
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'recipients/lookup/product', array("trId" => $trId, "recipients_code" => $journal_number) );
		} 
	}


	private function _apply_supplier( $supplier_id )
	{
		$this->load->model( "common/supplier_m" );
		$supplier = $this->supplier_m
			->as_array()
			->get(array("state" => 1, "code" => $supplier_id))
			;
		if( $supplier )
		{
			$this->session->set_flashdata( "applied.supplier", $supplier );
			return $supplier;
		}
		
		return FALSE;
	}
	
	private function _bind_supplier( Array &$reservation )
	{
		if( $supplier = $this->session->userdata( "applied.supplier" ) )
		{
			foreach( $reservation as $key => $val )
			{
				if( in_array( $key, array("state","created_at","updated_at","deleted_at") ) )
				{
					continue;
				}
				
				if( isset($supplier[$key]) )
				{
					$supplier[$key] = $supplier[$key];
				}
			}
		}
	}
	
	public function lookup_suppliers( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			if( $this->input->get_post("is_modal") ){ $data["is_modal"] = TRUE; }
			
			$this->load->view( 'recipients/lookup/supplier', (isset($data) ? $data : NULL) );
		} else
		{
			redirect( base_url( "common/suppliers/lookup" ) );
		}
	}
	
	public function lookup( $resource=false )
	{
		//echo'Fuk';exit;
		if( $this->input->is_ajax_request() )
		{
			$this->load->view( 'recipients/lookup/datatable', array("resource" => $resource) );
		} else
		{
			$data = array(
					'page' => $this->page,
					'datatables' => TRUE,
					'form' => TRUE,
					'resource' => $resource,
				);
			
			$this->template
				->set( "heading", "Lookup Box" )
				->set_breadcrumb( lang("common:page"), base_url("common") )
				->set_breadcrumb( "Lookup Box" )
				->build('recipients/lookup', (isset($data) ? $data : NULL));
		}
	}
	
	public function detail_collection( $state = false, $journal_number = false )
    {
        $start = $this->input->get_post('start', true);
        $length = $this->input->get_post('length', true);
        $order = $this->input->get_post('order', true);
        $columns = $this->input->get_post('columns', true);
        $search = $this->input->get_post('search', true);
        $draw = $this->input->get_post('draw', true);
		
		$db_from = "iv_recipient_order_detail a";
		$db_where = array();
		$db_like = array();
		
		// prepare defautl flter
		$db_where['a.deleted_at'] = NULL;
		if( $state !== false )
		{
			$db_where['a.state >='] = $state;
		}

		$db_where['a.recipient_code'] = $journal_number;
		if( $journal_number == false )
		{
			$db_where['a.recipient_code'] = $this->input->get_post('recipient_code');
		}
		
		// preparing default
		if( isset($search['value']) && ! empty($search['value']) )
        {
            $keywords = $this->db->escape_str( $search['value'] );
						
			$db_like[ $this->db->escape_str("a.product_code") ] = $keywords;
			$db_like[ $this->db->escape_str("a.purchase_price") ] = $keywords;
			$db_like[ $this->db->escape_str("a.qty_recipient") ] = $keywords;
			$db_like[ $this->db->escape_str("a.product_code") ] = $keywords;
			$db_like[ $this->db->escape_str("b.product_name") ] = $keywords;

        }
		
		// get total records
		$this->db->from( $db_from );
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		$records_total = $this->db->count_all_results();
		
		// get total filtered
		$this->db
			->from( $db_from )
			->join( "common_products b", "a.product_code = b.code", "LEFT OUTER" )
			;
			
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }		
		$records_filtered = $this->db->count_all_results();
		
		// get result filtered
		$db_select = <<<EOSQL
			a.id,
			a.product_code,
			b.product_name,
			a.qty_recipient,
			a.purchase_price,
			a.unit_code,
			a.discount_in_percen,
			a.discount_in_amount,
			a.total_by_product,
			a.state,
			a.created_at,
			a.updated_at
EOSQL;

		$this->db
			->select( $db_select )
			->from( $db_from )
			->join( "common_products b", "a.product_code = b.code", "LEFT OUTER" )
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
					
		//print_r($result);exit;
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
		
		//print_r($result);exit;
		$this->template
			->build_json( $output );
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
		
		$db_where = array();
		$db_like = array();
		
		// prepare defautl flter
		$db_where["a.deleted_at"] = NULL;
		if( $state !== false )
		{
			$db_where["a.state"] = 1;
		}
		
		$db_select = array(
				"a.id", 
				"a.code",
				"a.supplier_code",
				"b.supplier_name", 
				"a.state",
				"a.date",
				"a.created_at",
				"a.updated_at",
			);
		$db_like = array(
				"a.code" => $search['value'],
				"a.supplier_code" => $search['value'],
				"b.supplier_name" => $search['value'],
				"a.state" => $search['value'],
				"a.updated_at" => $search['value'],
			);
		
		// get total records
		$this->db->from( "iv_recipient_order a" );
		$this->db->join( "common_suppliers b","a.supplier_code= b.code","LEFT OUTER");		
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		$records_total = $this->db->count_all_results();
		
		// get total filtered
		//$this->db->select( "a.id,a.code,a.product_name,a.state,c.category_name,d.class_name,f.unit_name" );
		$this->db->from( "iv_recipient_order a" );
		$this->db->join( "common_suppliers b","a.supplier_code= b.code","LEFT OUTER");		
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }		
		$records_filtered = $this->db->count_all_results();
		
		// get result filtered		
		$this->db->select( $db_select );
		$this->db->from( "iv_recipient_order a" );
		$this->db->join( "common_suppliers b","a.supplier_code = b.code","LEFT OUTER");		
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
		
		// get//
		$result = $this->db
					->get()
					->result();
		
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

}


