<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Item_supplier extends ADMIN_Controller
{
	protected $nameroutes;
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('inventory');
		
		$this->data['nameroutes'] = $this->nameroutes = 'inventory/references/item_supplier'; 
		
		$this->load->language('inventory');		
		$this->load->library('inventory');
		$this->load->helper('inventory');
		
		$this->load->model('item_supplier_model');
		$this->load->model('item_model');
		$this->load->model('supplier_model');
	}
	
	//load note list view
	public function index()
	{
		$this->template
			->title(lang('heading:item_suppliers'),lang('heading:references'))
			->set_breadcrumb(lang('heading:references'), site_url($this->nameroutes))
			->set_breadcrumb(lang('heading:item_suppliers'))
			->build("references/item_supplier/index", $this->data);
	}
	
	public function create()
	{
		$this->data['form_action'] = $form_action = site_url("{$this->nameroutes}/create_post");
		$this->load->view('references/item_supplier/modal/create', $this->data);
	}
	
	public function update($product_id = 0, $supplier_id = 0)
	{
		$product_id = (int) @$product_id;
		$supplier_id = (int) @$supplier_id;
		$this->data['item'] = $item = $this->get_model()->get_by(['Barang_ID' => $product_id, 'SupplierID' => $supplier_id]);
		$this->data['m_item'] = $m_item = $this->item_model->get_one($product_id);
		$this->data['m_supplier'] = $m_supplier = $this->supplier_model->get_one($supplier_id);
		
		$this->data['form_action'] = $form_action = site_url("{$this->nameroutes}/update_post/{$product_id}/{$supplier_id}");
		$this->load->view('references/item_supplier/modal/update', $this->data);
	}
	
	public function delete($product_id = 0, $supplier_id = 0)
	{
		$product_id = (int) @$product_id;
		$supplier_id = (int) @$supplier_id;
		$this->data['item'] = $item = $this->get_model()->get_by(['Barang_ID' => $product_id, 'SupplierID' => $supplier_id]);
		
		$this->data['form_action'] = $form_action = site_url("{$this->nameroutes}/delete_post/{$product_id}/{$supplier_id}");
		$this->load->view('references/item_supplier/modal/delete', $this->data);
	}
	
	public function create_post()
	{
		if ($this->input->post()) 
		{
			$post_data = $this->input->post("f");
			
			$this->form_validation->set_rules([
					['field' => 'Barang_ID', 'label' => 'Barang', 'rules' => 'required'],
					['field' => 'SupplierID', 'label' => 'Supplier', 'rules' => 'required'],
					['field' => 'Harga', 'label' => 'Harga Beli', 'rules' => 'required|numeric'],
					['field' => 'Tgl_Beli_Terakhir', 'label' => 'Tanggal Beli', 'rules' => 'required'],
					['field' => 'MinStok', 'label' => 'Minimum Order', 'rules' => 'required|is_natural'],
					['field' => 'MinOrder', 'label' => 'Minimum Stok', 'rules' => 'required|is_natural']
				]);
			$this->form_validation->set_data($post_data);
			
			if ($this->form_validation->run())
			{
				if ($this->get_model()->create($post_data))
				{
					echo response_json(["success" => true, 'message' => lang('message:create_success')]);
				} else
				{
					echo response_json(["success" => false, 'message' => lang('message:create_failed')]);
				}
			} else
			{
				echo response_json(["success" => false, 'message' => $this->form_validation->get_all_error_string()]);
			}
		} else
		{
			echo response_json(["success" => false, 'message' => lang('message:create_failed')]);
		}
	}
	
	public function update_post($product_id = 0, $supplier_id = 0)
	{
		$product_id = (int) @$product_id;
		$supplier_id = (int) @$supplier_id;
		$item = $this->get_model()->get_by(['Barang_ID' => $product_id, 'SupplierID' => $supplier_id]);
		
		if( $item && $this->input->post() ) 
		{
			$post_data = $this->input->post("f");
			
			$this->form_validation->set_rules([
					['field' => 'Barang_ID', 'label' => 'Barang', 'rules' => 'required'],
					['field' => 'SupplierID', 'label' => 'Supplier', 'rules' => 'required'],
					['field' => 'Harga', 'label' => 'Harga Beli', 'rules' => 'required|numeric'],
					['field' => 'Tgl_Beli_Terakhir', 'label' => 'Tanggal Beli', 'rules' => 'required'],
					['field' => 'MinStok', 'label' => 'Minimum Order', 'rules' => 'required|is_natural'],
					['field' => 'MinOrder', 'label' => 'Minimum Stok', 'rules' => 'required|is_natural']
				]);
			$this->form_validation->set_data($post_data);
			
			if ($this->form_validation->run())
			{
				if ($this->get_model()->update_by($post_data, ['Barang_ID' => $product_id, 'SupplierID' => $supplier_id]))
				{
					echo response_json(["success" => true, 'message' => lang('message:update_success')]);
				} else
				{
					echo response_json(["success" => false, 'message' => lang('message:update_failed')]);
				}
			} else
			{
				echo response_json(["success" => false, 'message' => $this->form_validation->get_all_error_string()]);
			}
		} else
		{
			echo response_json(["success" => false, 'message' => lang('message:update_failed')]);
		}
	}
	
	public function delete_post($product_id = 0, $supplier_id = 0)
	{
		$product_id = (int) @$product_id;
		$supplier_id = (int) @$supplier_id;
		$item = $this->get_model()->get_by(['Barang_ID' => $product_id, 'SupplierID' => $supplier_id]);
		
		if ($item && (1 == $this->input->post('confirm')))
		{
			if ($this->get_model()->delete_by(['Barang_ID' => $product_id, 'SupplierID' => $supplier_id]))
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
        $start = $this->input->get_post('start', true);
        $length = $this->input->get_post('length', true);
        $order = $this->input->get_post('order', true);
        $columns = $this->input->get_post('columns', true);
        $search = $this->input->get_post('search', true);
        $draw = $this->input->get_post('draw', true);
		
		$t_main = $this->db->dbprefix($this->get_model()->table);
		$t_item = $this->db->dbprefix('mBarang');
		$t_supplier = $this->db->dbprefix('mSupplier');
		
		
		// get total records
		$records_total = $this->db->count_all_results($t_main);
		
		// preparing filter
		$db_like = [];
		if (isset($search['value']) && ! empty($search['value']))
        {
            $words = $this->db->escape_str( $search['value'] );
			
			$db_like[$this->db->escape_str("{$t_item}.Kode_Barang")] = $words;
			$db_like[$this->db->escape_str("{$t_item}.Nama_Barang")] = $words;
			$db_like[$this->db->escape_str("{$t_supplier}.Kode_Supplier")] = $words;
			$db_like[$this->db->escape_str("{$t_supplier}.Nama_Supplier")] = $words;
        }
		
		// get total filtered
		if (! empty($db_like))
		{
			$this->db->group_start();
			foreach($db_like as $field => $match)
			{
				$this->db->or_like($field, $match, 'both', TRUE);
			}
			$this->db->group_end();
		}
		
		$this->db->join($t_item, "{$t_main}.Barang_ID = {$t_item}.Barang_ID","INNER")
			->join($t_supplier, "{$t_main}.SupplierID = {$t_supplier}.Supplier_ID","INNER");
		$records_filtered = $this->db->count_all_results($t_main);
		
		
		// get result filtered
		$db_select = <<<EOSQL
			{$t_item}.Kode_Barang, 
			{$t_item}.Nama_Barang,
			{$t_supplier}.Kode_Supplier,
			{$t_supplier}.Nama_Supplier,
			{$t_main}.Harga,
			{$t_main}.Tgl_Beli_Terakhir Tgl_Beli,
			{$t_main}.Kerjasama,
			{$t_main}.MinOrder,
			{$t_main}.MinStok,
			{$t_item}.Konversi,
			{$t_main}.SupplierID Supplier_ID,
			{$t_main}.Barang_ID
EOSQL;
		
		$this->db->select($db_select);
		
		if (! empty($db_like))
		{
			$this->db->group_start();
			foreach($db_like as $field => $match)
			{
				$this->db->or_like($field, $match, 'both', TRUE);
			}
			$this->db->group_end();
		}
		if (isset($order))
        {
            $sort_column = $order[0]['column'];
			$sort_dir = $order[0]['dir'];
			if( $columns[$sort_column]['orderable'] == 'true' )
			{
				$this->db->order_by( $columns[intval($this->db->escape_str($sort_column))]['data'], $this->db->escape_str($sort_dir) );
			}
        }
		if (isset($start) && $length != '-1')
        {
            $this->db->limit($length, $start);
        }
		
		$this->db->join($t_item, "{$t_main}.Barang_ID = {$t_item}.Barang_ID","INNER")
			->join($t_supplier, "{$t_main}.SupplierID = {$t_supplier}.Supplier_ID","INNER");
		$query = $this->db->get($t_main);
		$result = $query->result_array();
		
        $output = [
				'draw' => intval($draw),
				'recordsTotal' => $records_total,
				'recordsFiltered' => $records_filtered,
				'data' => []
			];        
        foreach($result as $row)
        {
			$a_id = (int) $row['Barang_ID'];
			$b_id = (int) $row['Supplier_ID'];
			
			$row['Harga'] = round($row['Harga'],2);
			$row['Tgl_Beli'] = date('d-M Y', strtotime($row['Tgl_Beli']));
			$row['Kerjasama'] = (1 == $row['Kerjasama']) ? 'Y' : 'T';
			
			
			$a_edit = anchor('#', '<i class="fa fa-pencil"></i> ' . lang('action:edit'), [
					'data-act' => 'ajax-modal', 
					'data-title' => lang('action:edit'),
					'data-action-url' => site_url("{$this->nameroutes}/update/{$a_id}/{$b_id}")
				]);
			$a_delete = anchor('#', '<i class="fa fa-trash-o"></i> ' . lang('action:delete'), [
					'data-act' => 'ajax-modal', 
					'data-title' => lang('action:delete'),
					'data-action-url' => site_url("{$this->nameroutes}/delete/{$a_id}/{$b_id}")
				]);
			
			$action = '<div class="text-center"><div class="btn-group text-left">'
				. '<button type="button" class="btn btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">'
				. lang('actions') . ' <span class="caret"></span></button>
				<ul class="dropdown-menu pull-right" role="menu">
					<li>' . $a_edit . '</li>
					<li>' . $a_delete . '</li>
				</ul>
			</div></div>';
			
			$row['Actions'] = $action;
			
			$output['data'][] = $row;
        }
		
		$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($output))
			->_display();
		
		exit(0);
    }
	
	public function get_model()
	{
		return $this->item_supplier_model;
	}
}

