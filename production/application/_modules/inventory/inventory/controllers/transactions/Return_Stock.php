<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Return_Stock extends ADMIN_Controller
{
	protected $nameroutes;
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('inventory');
		
		$this->data['nameroutes'] = $this->nameroutes = 'inventory/transactions/return_stock'; 
		
		$this->load->language('inventory');		
		$this->load->library('inventory');
		$this->load->helper('inventory');
		
		$this->load->model('return_stock_model');
		$this->load->model('return_stock_detail_model');
		$this->load->model('supplier_model');
		$this->load->model('section_model');
		$this->load->model('procurement_model');
		$this->load->model('item_model');
		$this->load->model('item_category_model');
		$this->load->model('item_unit_model');
	}
	
	//load note list view
	public function index()
	{
		$this->data['dropdown_section'] = $this->section_model->for_dropdown();		
		$this->template
			->title(lang('heading:purchase_request'),lang('heading:transactions'))
			->set_breadcrumb(lang('heading:transactions'), site_url($this->nameroutes))
			->set_breadcrumb(lang('heading:purchase_request'))
			->build("transactions/return_stock/index", $this->data);
	}
	
	public function create()
	{
		$this->data['form_action'] 	    = $form_action = site_url("{$this->nameroutes}/create_post");
		$this->data['lookup_supplier']  = 'lookup_supplier';
		$this->data['item_lookup'] 		= 'lookup_item';
		$this->data['tgl_retur'] 		= date("Y/m/d");
		$this->data['gen_retur_number'] = $this->return_stock_model->gen_retur_number();	
		$this->data['gen_request_id']   = $this->return_stock_model->gen_request_id();
		$this->data['dropdown_section'] = $this->section_model->for_dropdown();
		
		$this->template
			->title(lang('heading:purchase_request'),lang('heading:transactions'))
			->set_breadcrumb(lang('heading:transactions'), site_url($this->nameroutes))
			->set_breadcrumb(lang('heading:purchase_request'))
			->build("transactions/return_stock/create", $this->data);
	}
	
	public function update($id = 0)
	{
		$id = (int) $_GET['id'];
		
		$this->data['form_action'] = site_url("{$this->nameroutes}/update_post/{$id}");
		$this->data['item'] = $this->return_stock_model->get_one($id);
		$this->data['supplier'] = $this->supplier_model->get_one($this->data['item']->Supplier_ID);
		
		$this->data['lookup_supplier']  = 'lookup_supplier';
		$this->data['item_lookup'] 		= 'lookup_item';
		$this->data['tgl_retur'] 		= date("Y/m/d");
		$this->data['gen_retur_number'] = $this->return_stock_model->gen_retur_number();	
		$this->data['gen_request_id']   = $this->return_stock_model->gen_request_id();
		$this->data['dropdown_section'] = $this->section_model->for_dropdown();
		
		$this->template
			->title(lang('heading:purchase_request'),lang('heading:transactions'))
			->set_breadcrumb(lang('heading:transactions'), site_url($this->nameroutes))
			->set_breadcrumb(lang('heading:purchase_request'))
			->build("transactions/return_stock/update", $this->data);
	}
	
	public function delete($id = 0)
	{
		$id = (int) @$id;
		$this->data['item'] = $item = $this->get_model()->get_one($id);
		
		$this->data['form_action'] = $form_action = site_url("{$this->nameroutes}/delete_post/{$id}");
		$this->load->view('references/item_class/modal/delete', $this->data);
	}
	
	public function mass_action()
	{
		$this->form_validation->set_rules('mass_action', 'Mass Action', 'required');
		
		if ($this->form_validation->run())
		{
			if (!empty($this->input->post('val', TRUE)))
			{
				if ($this->input->post('mass_action') == 'delete')
				{
					foreach ($this->input->post('val', TRUE) as $id)
					{
						$this->get_model()->delete($id);
					}
					$this->session->set_flashdata('message', lang("message:mass_delete_success"));
					redirect($this->nameroutes,'refresh');
				}
			} else
			{
				$this->session->set_flashdata('error', lang("message:no_selected"));
				redirect($this->nameroutes,'refresh');
			}
		} else
		{
			$this->session->set_flashdata('error', validation_errors());
			redirect($this->nameroutes,'refresh');
		}
	}
	
	public function cek_stock($id_lokasi,$id_barang){
		$cek_stock = $this->return_stock_model->cek_stock($id_lokasi,$id_barang);
		if($cek_stock->Qty_Stok == 0){
			$response = array(
				"status" => 'error',
				"message" => 'Transaksi Tidak Dapat Dilanjutkan, Karena Stok Tidak Mencukupi',
				"Nama_Barang" => $cek_stock->Nama_Barang,
				"code" => 500
			);
			$this->template->build_json( $response );
		}else{
			$response = array(
				"status" => 'success',
				"message" => 'Success',
				"Nama_Barang" => '',
				"code" => 200
			);
			$this->template->build_json( $response );
		}
	}
	
	public function create_post()
	{
		if( $this->input->post() ) 
		{
			$post_data = $this->input->post("f");
			$extend	   = $this->input->post("h");
			$details = explode(",", $this->input->post("details"));
			$post_data_details = array();
			//print_r($this->input->post("h"));exit;
			
			
			foreach($details as $i){
				parse_str($i, $include);
				$include['Retur_ID'] = $this->input->post("h[Retur_ID]");
				array_push($post_data_details,$include);
			}
			
			//$this->form_validation->set_rules($this->purchase_request_model->rules['insert']);
			//$this->form_validation->set_data($post_data);
			
			if( !$this->form_validation->run())
			{
				$post_data['User_ID'] = 1876;
				$post_data['Tgl_Update'] = date("Y-m-d");
				$post_data['Supplier_ID'] = $extend['Supplier_ID'];
				$post_data['Lokasi_ID'] = $extend['Gudang_ID'];				
				
				//print_r($post_data_details);exit;
				
					$this->db->trans_begin();
					if($inserted_id = $this->return_stock_model->create($post_data))
					{
						$this->return_stock_detail_model->mass_create($post_data_details);
						
						foreach($post_data_details as $rows){
							$Qty 		 = $rows['Qty_Retur'];
							$harga_order = $rows['Harga_Retur'];
							$order_id 	 = $this->input->post("f[Retur_ID]");
							$barang_id	 = $rows['Barang_ID'];
							$no_bukti	 = $this->input->post("f[No_Retur]");
							$tgl_trans	 = $this->input->post("f[Tgl_Retur]");
							$lokasi_id	 = $this->input->post("h[Gudang_ID]");
							$exp_date    = $rows['Exp_Date'] != NULL ? $rows['Exp_Date'] : NULL;
							$kode_satuan = $rows["Kode_Satuan"];
							$jenis		 = $this->input->post("JenisBarangID");
							
							$this->db->query("EXEC IsiKartuGudangFIFO $lokasi_id, $barang_id, '$kode_satuan', $Qty, $harga_order, '$no_bukti',565,0,'$tgl_trans','$exp_date',$jenis");
						}
					} else
					{
						echo response_json(["error" => false, 'message' => lang('message:create_failed')]);
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
						$this->db->trans_commit();
						$response = array(
								"Permintaan_ID" => $this->input->post("h[Permintaan_ID]"),
								"status" => 'success',
								"message" => lang('global:created_successfully'),
								"code" => 200
							);
					}	
				//}else{
//					$response = array(
//						"status" => 'error',
//						"message" => 'Transaksi Tidak Dapat Dilanjutkan, Karena Stok Tidak Mencukupi',
//						"Nama_Barang" => $cek_stock->Nama_Barang,
//						"code" => 500
//					);
//				}
			} else
			{
				//echo response_json(["success" => false, 'message' => $this->form_validation->get_all_error_string()]);
				$response["message"] = $this->form_validation->get_all_error_string();
				$response["status"] = "error";
				$response["code"] = "500";
			}
		} else
		{
			//echo response_json(["error" => false, 'message' => lang('message:create_failed')]);
			$response["message"] = lang('global:created_failed');
			$response["status"] = "error";
			$response["code"] = "500";
		}
		
		$this->template->build_json( $response );
	}
	
	public function update_post($id = 0)
	{
		//print_r($id);exit;
		
		$id = (int) @$id;
		$item = $this->purchase_request_model->get_one($id);
		
		if( $item && $this->input->post() ) 
		{
			$post_data = $this->input->post("f");
			$details = explode(",", $this->input->post("details"));
			$post_data_details = array();
			
			foreach($details as $i){
				parse_str($i, $include);
				$include['Permintaan_ID'] = $this->input->post("h[Permintaan_ID]");
				array_push($post_data_details,$include);
			}
			
			//print_r($details);exit;
			
			if( !$this->form_validation->run())
			{
				$post_data['User_ID'] = 1876;
				$post_data['Tgl_Update'] = date("Y-m-d");
				$post_data['Currency_ID'] = 1;
				$post_data['Departemen_ID'] = NULL;
				$post_data['Supplier_ID'] = NULL;
				$post_data['Pembelian_Asset'] = 0;
				
				//print_r($post_data_details);exit;
				
				$this->db->trans_begin();
				$this->purchase_request_detail_model->delete($id);
				if($this->purchase_request_model->update($post_data, $id)){
					$this->purchase_request_detail_model->mass_create($post_data_details);
				} else {
					echo response_json(["error" => false, 'message' => lang('message:create_failed')]);
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
					$this->db->trans_commit();
					$response = array(
							"Permintaan_ID" => $include['Permintaan_ID'],
							"status" => 'success',
							"message" => lang('global:created_successfully'),
							"code" => 200
						);
				}			
			} else
			{
				//echo response_json(["success" => false, 'message' => $this->form_validation->get_all_error_string()]);
				$response["message"] = $this->form_validation->get_all_error_string();
				$response["status"] = "error";
				$response["code"] = "500";
			}
		} else
		{
			$response["message"] = lang('global:created_failed');
			$response["status"] = "error";
			$response["code"] = "500";
		}
		$this->template->build_json( $response );	
	}
	
	public function delete_post($id)
	{
		$id = (int) @$id;
		$item = $this->get_model()->get_one($id);
		
		if ($item && (1 == $this->input->post('confirm')))
		{
			if ($this->get_model()->delete($id))
			{
				echo response_json(["success" => true, 'message' => lang('message:delete_success')]);
			} else
			{
				echo response_json(["success" => false, lang('message:delete_failed')]);
			}
		} else
		{
			echo response_json(["success" => false, lang('message:delete_failed')]);
		}
	}
	
	public function collection()
	{	
		
		$date_from = $this->input->post('date_from');
		$date_till = $this->input->post('date_till');
		
		$t_return 	     = $this->return_stock_model->table;
		$t_return_detail = $this->return_stock_detail_model->table;
		$t_supplier		 = $this->supplier_model->table;
		$t_section		 = $this->section_model->table;
		
		$this->datatables
			->select(
					"{$t_return}.Tgl_Retur,
					 {$t_return}.No_Retur,
					 {$t_supplier}.Nama_Supplier,
					 {$t_section}.SectionName,
					 {$t_return}.Retur_ID")
			->from("({$t_return} LEFT OUTER JOIN {$t_supplier} ON {$t_return}.Supplier_ID={$t_supplier}.Supplier_ID)")
			->join("{$t_section}","{$t_return}.Lokasi_ID={$t_section}.Lokasi_ID","LEFT OUTER")
			->where("({$t_return}.No_Retur<>'')")
			->where("{$t_return}.Tgl_Retur >=",$date_from)
			->where("{$t_return}.Tgl_Retur <",$date_till)
			;
				
		$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output($this->datatables->generate())
			->_display();
		
		exit(0);
	}
	
	public function get_category_list()
	{
		$this->output
			->set_status_header(200)
			->set_content_type('text/html', 'utf-8')
			->set_output($this->item_category_model->to_list_html(lang('select_category')))
			->_display();
		
		exit(0);
	}
	
	public function get_subcategory_list($parent_id = 0)
	{
		$parent_id = (int) $parent_id;
		if (0 == $parent_id){ $parent_id = (int) $this->input->post('category_id', TRUE); }
		
		print $parent_id;
		
		$this->output
			->set_status_header(200)
			->set_content_type('text/html', 'utf-8')
			->set_output($this->item_subcategory_model->to_list_html($parent_id, lang('select_subcategory')))
			->_display();
		
		exit(0);
	}
	
	public function get_model()
	{
		return $this->item_class_model;
	}
	
	public function table_item(){
		$this->data['datatables'] = TRUE;
		$this->data['collection'] = $this->purchase_request_detail_model->get_child_data();
		$this->load->view('transactions/purchase_request/modal/form/item', $this->data);
		
	}
	
	public function datatable_collection( $id_retur = '' )
    {
        $start = $this->input->get_post('start', true);
        $length = $this->input->get_post('length', true);
        $order = $this->input->get_post('order', true);
        $columns = $this->input->get_post('columns', true);
        $search = $this->input->get_post('search', true);
        $draw = $this->input->get_post('draw', true);
		
		$db_from = "{$this->return_stock_detail_model->table} a";
		$db_where = array();
		$db_like = array();
		
		// prepare defautl flter
		//$db_where['a.Batal'] = 0;
		
		//empty field
		
		if(isset($_POST['Retur_ID'])){
			$db_where['a.Retur_ID'] = $_POST['Retur_ID'];
			//$db_where['a.Barang_ID'] = 0;
		}else{
			$db_where['a.Retur_ID'] = 0;
			//$db_where['a.Barang_ID'] = 0;
		}
		
		//testing for data
		//$db_where['a.Permintaan_ID'] = 87002;
		//$db_where['a.Barang_ID'] = 1605;
		
		// preparing default
		if( isset($search['value']) && ! empty($search['value']) )
        {
            $keywords = $this->db->escape_str( $search['value'] );
			
			//$db_like[ $this->db->escape_str("a.NoReservasi") ] = $keywords;
			
			//$db_like[ $this->db->escape_str("a.NRM") ] = $keywords;
			 
        }
		
		// get total records
		$this->db->from( $db_from );
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		$records_total = $this->db->count_all_results();
		
		// get total filtered
		$this->db
			->from( $db_from )
			->join("{$this->item_model->table} b","a.Barang_ID=b.Barang_ID","LEFT OUTER")
			->join("{$this->item_unit_model->table} c","b.Stok_Satuan_ID=c.Satuan_ID","LEFT OUTER")
			->join("{$this->item_category_model->table} d","b.Kategori_ID=d.Kategori_ID","LEFT OUTER")
			;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }		
		$records_filtered = $this->db->count_all_results();
		
		// get result filtered
		$db_select = <<<EOSQL
			a.Retur_ID,
			b.Kode_Barang,
			b.Nama_Barang,
			a.Qty_Retur,
			b.Konversi,
			c.Nama_Satuan as Kode_Satuan,
			b.Barang_ID,
			b.Harga_Beli as Harga_Retur,
			(b.Harga_Beli * a.Qty_Retur) as Jumlah_Total
EOSQL;

		$this->db
			->select( $db_select )
			->from( $db_from )
			->join("{$this->item_model->table} b","a.Barang_ID=b.Barang_ID","LEFT OUTER")
			->join("{$this->item_unit_model->table} c","b.Stok_Satuan_ID=c.Satuan_ID","LEFT OUTER")
			->join("{$this->item_category_model->table} d","b.Kategori_ID=d.Kategori_ID","LEFT OUTER")
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
			/*$date = DateTime::createFromFormat("Y-m-d H:i:s.u", $row->Tanggal);
			$time = DateTime::createFromFormat("Y-m-d H:i:s.u", $row->Jam ); 
			
			$row->Tanggal = $date->format('Y-m-d');
			$row->Jam = $time->format('H:i:s'); */
      
            $output['data'][] = $row;
        }
		
		$this->template
			->build_json( $output );
    }
	
	
	public function table_item_collection()
	{
		$a_edit = anchor('#', '<i class="fa fa-pencil"></i> ' . lang('action:edit'), [
				'data-act' => 'ajax-modal', 
				'data-title' => lang('action:edit'),
				'data-action-url' => site_url($this->nameroutes.'/update/$1')
			]);
		$a_delete = anchor('#', '<i class="fa fa-trash-o"></i> ' . lang('action:delete'), [
				'data-act' => 'ajax-modal', 
				'data-title' => lang('action:delete'),
				'data-action-url' => site_url($this->nameroutes.'/delete/$1')
			]);
		
		$action = '<div class="text-center"><div class="btn-group text-left">'
			. '<button type="button" class="btn btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">'
			. lang('actions') . ' <span class="caret"></span></button>
			<ul class="dropdown-menu pull-right" role="menu">
				<li>' . $a_edit . '</li>
				<li>' . $a_delete . '</li>
			</ul>
		</div></div>';
		
		$jumlah = '<input type="text" name="jumlah">';
		
		$t_item = $this->item_model->table;
		$t_item_category = $this->item_category_model->table;
		$t_item_unit = $this->item_unit_model->table;
		$t_purchase_request_detail = $this->purchase_request_detail_model->table;
		
		$this->datatables
			->select("
					 {$t_purchase_request_detail}.Permintaan_ID,
					 {$t_item}.Kode_Barang,
					 {$t_item}.Nama_Barang,
					 {$t_item}.Konversi,
					 {$t_item_category}.Nama_Kategori,
					 {$t_item_unit}.Nama_Satuan,
					 {$t_item}.Min_Stock,
					 {$t_item}.Max_Stock,
					 {$t_item}.Stock_Akhir,
					 {$t_purchase_request_detail}.Qty_Permintaan,
					 {$t_item}.Harga_Beli
					 ")
			->from("{$t_item}")
			->join("{$t_item_category}","{$t_item_category}.Kategori_ID={$t_item}.Kategori_ID")
			->join("{$t_item_unit}","{$t_item_unit}.Satuan_ID={$t_item}.Stok_Satuan_ID")
			->join("{$t_purchase_request_detail}","{$t_purchase_request_detail}.Barang_ID={$t_item}.Barang_ID")
			->where("{$t_item}.Barang_ID",'1605')
			->where("{$t_purchase_request_detail}.Permintaan_ID",'87002');
		
		//$this->datatables->add_column("actions", $action, "Permintaan_ID");	
		$this->datatables->add_column("qty", "", "0");	
		$this->datatables->add_column("jumlah_total", "", "");	
		
		$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output($this->datatables->generate())
			->_display();
		
		exit(0);
	}
	
	public function lookup_supplier( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'transactions/return_stock/lookup/lookup_supplier' );
		}else{
			$this->load->view( 'transactions/return_stock/lookup/lookup_supplier' );
		}
	}
	
	public function lookup_item( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'transactions/return_stock/lookup/lookup_item' );
		}else{
			$this->load->view( 'transactions/return_stock/lookup/lookup_item' );
		}
	}


}

