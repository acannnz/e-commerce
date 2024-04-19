<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Item extends ADMIN_Controller
{
	protected $nameroutes;
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('inventory');
		
		$this->data['nameroutes'] = $this->nameroutes = 'inventory/references/item'; 
		
		$this->load->language('inventory');		
		$this->load->library('inventory');
		$this->load->helper('inventory');
		
		$this->load->model('item_model');
		$this->load->model('item_class_model');
		$this->load->model('item_category_model');
		$this->load->model('item_subcategory_model');
		$this->load->model('item_unit_model');
		$this->load->model('item_location_model');
		$this->load->model('item_package_model');
		$this->load->model('item_typegroup_model');
		$this->load->model('item_group_model');
		$this->load->model('item_supplier_model');
		$this->load->model('item_grading_group_model');
		$this->load->model('location_model');
		$this->load->model('section_model');
		$this->load->model('supplier_model');
		$this->load->model('purchase_request_detail_model');
		
		$t_main = $this->db->dbprefix($this->get_model()->table);
		$t_class = $this->db->dbprefix($this->item_class_model->table);
		$t_category = $this->db->dbprefix($this->item_category_model->table);
		
		$this->data['populate_group'] = [
				'' => 'Pilih Kelompok',
				'OBAT' => 'Obat',
				'UMUM' => 'Umum',
			];
	}
	
	//load note list view
	public function index()
	{
		$this->data['populate_filter'] = [
				'CODE' => 'Kode Barang',
				'NAME' => 'Nama Barang',
				'CATEGORY' => 'Kategori',
				'CLASS' => 'Kelas',
				'ALL' => 'Semua',
			];
		$this->data['populate_section'] = $this->section_model->to_list_data();
		
		$this->template
			->title(lang('heading:items'),lang('heading:references'))
			->set_breadcrumb(lang('heading:references'), site_url($this->nameroutes))
			->set_breadcrumb(lang('heading:items'))
			->build("references/item/index", $this->data);
	}
	
	public function create()
	{
		
		$item = array(
			'Kode_Barang' => inventory_helper::gen_item_code(),
			'Aktif' => 1,
			'Harga_Beli' => 0,
			'PPn_Persen' => 0,
			'Konversi' => 1,
			'Harga_Jual' => 0,
			'HRataRata' => 0,
		);
	

		if ($this->input->post()) 
		{
			$item = (array) array_merge( (array) $item, $this->input->post("f"));
			$location = (array) $this->input->post("collection_location");
			$package = (array) $this->input->post("collection_package");
			
			if( empty($location) ) response_json(["success" => false, 'message' => 'Lokasi Barang Dibutuhkan']);
			
			$this->form_validation->set_rules([
					['field' => 'Kode_Barang', 'label' => 'Kode Barang', 'rules' => 'required'],
					['field' => 'Barang_ID_BPJS', 'label' => 'Kode Barang', 'rules' => "is_unique[{$this->item_model->table}.Barang_ID_BPJS]"],
					['field' => 'Supplier_ID', 'label' => 'Supplier', 'rules' => ''],
					['field' => 'Harga_Beli', 'label' => 'Harga Beli', 'rules' => 'required'],
					['field' => 'Harga_Jual', 'label' => 'Harga Jual', 'rules' => 'required'],
					['field' => 'Beli_Satuan_Id', 'label' => 'Satuan Beli', 'rules' => 'required'],
					['field' => 'Stok_Satuan_ID', 'label' => 'Satuan Stok', 'rules' => 'required'],
					['field' => 'Kelompok', 'label' => 'Kelompok', 'rules' => 'required'],
					['field' => 'KelompokJenis', 'label' => 'Kelompok Jenis', 'rules' => 'required'],
				]);
			$this->form_validation->set_data($item);
			
			if ($this->form_validation->run())
			{
				if (inventory_helper::create_item($item, $location, $package))
				{
					echo response_json(["success" => true, 'message' => lang('message:create_success')]);
				} else
				{
					echo response_json(["success" => false, 'message' => lang('message:create_failed')]);
				}
			} else
			{
				echo response_json(["status" => 'error',"success" => false, 'message' => $this->form_validation->get_all_error_string()]);
			}
		}
		
		$this->data['item'] = (object) $item;
		$this->data['form_action'] = site_url("{$this->nameroutes}/create");
		$this->data['populate_category'] = $this->item_category_model->to_list_data();
		$this->data['populate_subcategory'] = $this->item_subcategory_model->to_list_data();
		$this->data['populate_class'] = $this->item_class_model->to_list_data();
		$this->data['populate_item_group'] = $this->item_group_model->to_list_data();
		$this->data['populate_item_grading_group'] = $this->item_grading_group_model->to_list_data();
		
		$this->data['populate_purchase_unit'] = $this->item_unit_model->to_list_data();
		$this->data['populate_stock_unit'] = $this->item_unit_model->to_list_data();
				
		
		$this->template
			->title(lang('heading:items'),lang('heading:references'))
			->set_breadcrumb(lang('heading:references'), site_url($this->nameroutes))
			->set_breadcrumb(lang('heading:items'), site_url($this->nameroutes))
			->set_breadcrumb(lang('action:add'))
			->build("references/item/form", $this->data);
		
		//$this->load->view('references/item/modal/create', $this->data);
	}
	
	public function update($id = 0)
	{
		$this->data['item'] = $item = $this->get_model()->get_one( $id );
		
		if ($this->input->post()) 
		{
			$item = $this->input->post("f");
			$location = (array) $this->input->post("collection_location");
			$package = (array) $this->input->post("collection_package");
			
			if( empty($location) ) response_json(["success" => false, 'message' => 'Lokasi Barang Dibutuhkan']);
			
			$this->form_validation->set_rules([
					['field' => 'Kode_Barang', 'label' => 'Kode Barang', 'rules' => 'required'],
					['field' => 'Barang_ID_BPJS', 'label' => 'Kode Barang BPJS', 'rules' => "is_edit_unique[{$this->item_model->table}.Barang_ID_BPJS.Barang_ID.{$id}]"],
					// ['field' => 'Supplier_ID', 'label' => 'Supplier', 'rules' => 'required'],
					['field' => 'Harga_Beli', 'label' => 'Harga Beli', 'rules' => 'required'],
					['field' => 'Harga_Jual', 'label' => 'Harga Jual', 'rules' => 'required'],
					['field' => 'Beli_Satuan_Id', 'label' => 'Satuan Beli', 'rules' => 'required'],
					['field' => 'Stok_Satuan_ID', 'label' => 'Satuan Stok', 'rules' => 'required'],
					['field' => 'Kelompok', 'label' => 'Kelompok', 'rules' => 'required'],
					['field' => 'KelompokJenis', 'label' => 'Kelompok Jenis', 'rules' => 'required'],
				]);
			$this->form_validation->set_data($item);
			
			if ($this->form_validation->run())
			{
				if (inventory_helper::update_item($id, $item, $location, $package))
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
		}

		$this->data['collection_location'] = inventory_helper::get_item_location( $id );
		$this->data['collection_package'] = inventory_helper::get_item_package( $id );
		$this->data['supplier'] = $this->supplier_model->get_one( $item->Supplier_ID );
		
		$this->data['populate_category'] = $this->item_category_model->to_list_data();
		$this->data['populate_subcategory'] = $this->item_subcategory_model->to_list_data();
		$this->data['populate_class'] = $this->item_class_model->to_list_data();
		$this->data['populate_item_group'] = $this->item_group_model->to_list_data();
		$this->data['populate_item_grading_group'] = $this->item_grading_group_model->to_list_data();
		$this->data['populate_type_group'] = $this->item_typegroup_model->to_list_data( $item->Kelompok );
		
		$this->data['populate_purchase_unit'] = $this->item_unit_model->to_list_data();
		$this->data['populate_stock_unit'] = $this->item_unit_model->to_list_data();
		
		$this->data['form_action'] = $form_action = site_url("{$this->nameroutes}/update/{$id}");
		$this->data['is_edit'] = TRUE;
				
		$this->template
			->title(lang('heading:items'),lang('heading:references'))
			->set_breadcrumb(lang('heading:references'), site_url($this->nameroutes))
			->set_breadcrumb(lang('heading:items'), site_url($this->nameroutes))
			->set_breadcrumb(lang('action:add'))
			->build("references/item/form", $this->data);
			
	}
	
	public function delete($product_id = 0, $supplier_id = 0)
	{
		$product_id = (int) @$product_id;
		$supplier_id = (int) @$supplier_id;
		$this->data['item'] = $item = $this->get_model()->get_by(['Barang_ID' => $product_id, 'SupplierID' => $supplier_id]);
		
		$this->data['form_action'] = $form_action = site_url("{$this->nameroutes}/delete_post/{$product_id}/{$supplier_id}");
		$this->load->view('references/item/modal/delete', $this->data);
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
		
		$advanced_search = $this->input->get_post('advanced_search', true);
		
		$t_main = $this->db->dbprefix($this->get_model()->table);
		$t_class = $this->db->dbprefix($this->item_class_model->table);
		$t_category = $this->db->dbprefix($this->item_category_model->table);
		$t_subcategory = $this->db->dbprefix($this->item_subcategory_model->table);
		$t_unit = $this->db->dbprefix($this->item_unit_model->table);
		$t_unit_2 = $this->db->dbprefix($this->item_unit_model->table." t_unit_2");
		$t_item_location = $this->db->dbprefix($this->item_location_model->table);
		$t_location = $this->db->dbprefix($this->location_model->table);
		
		// get total records
		$records_total = $this->db->count_all_results($t_main);
		
		// preparing filter
		$db_where = [];
		$db_like = [];
		$db_or_like = [];
		
		if (1 == $advanced_search['status'])
		{
			$db_where[$this->db->escape_str("{$t_main}.Aktif")] = 1;
		} else if (2 == $advanced_search['status'])
		{
			$db_where[$this->db->escape_str("{$t_main}.Aktif")] = 0;
		} else if (4 == $advanced_search['status'])
		{
			$db_where[$this->db->escape_str("{$t_main}.BarangKonsinyasi")] = 1;
		}
		
		if (0 != $advanced_search['location'])
		{
			$db_where[$this->db->escape_str("{$t_item_location}.Lokasi_ID")] = $advanced_search['location'];
		}
		
		if ('' != $advanced_search['words'])
		{
			if ('CODE' == $advanced_search['filter'])
			{
				$db_like[$this->db->escape_str("{$t_main}.Kode_Barang")] = $this->db->escape_str($advanced_search['words']);
				
			} else if ('NAME' == $advanced_search['filter'])
			{
				$db_like[$this->db->escape_str("{$t_main}.Nama_Barang")] = $this->db->escape_str($advanced_search['words']);
			
			} else if ('CATEGORY' == $advanced_search['filter'])
			{
				$db_like[$this->db->escape_str("{$t_category}.Nama_KAtegori")] = $this->db->escape_str($advanced_search['words']);
			
			} else if ('CLASS' == $advanced_search['filter'])
			{
				$db_like[$this->db->escape_str("{$t_class}.Nama_Kelas")] = $this->db->escape_str($advanced_search['words']);
			
			} else if ('ALL' == $advanced_search['filter'])
			{
				$search['value'] = $advanced_search['words'];
			}
		}
		
		if (isset($search['value']) && ! empty($search['value']))
        {
            $words = $this->db->escape_str($search['value']);
			
			$db_or_like[$this->db->escape_str("{$t_main}.Kode_Barang")] = $words;
			$db_or_like[$this->db->escape_str("{$t_main}.Nama_Barang")] = $words;
			$db_or_like[$this->db->escape_str("{$t_category}.Nama_KAtegori")] = $words;
			$db_or_like[$this->db->escape_str("{$t_class}.Nama_Kelas")] = $words;
        }
		
		// get total filtered
		if (!empty($db_where))
		{
			$this->db->group_start();
			foreach($db_where as $field => $match){ $this->db->where($field, $match); }
			$this->db->group_end();
		}
		if (!empty($db_like))
		{
			$this->db->group_start();
			foreach($db_like as $field => $match){ $this->db->like($field, $match, 'both', TRUE); }
			$this->db->group_end();
		}
		if (!empty($db_or_like))
		{
			$this->db->group_start();
			foreach($db_or_like as $field => $match){ $this->db->or_like($field, $match, 'both', TRUE); }
			$this->db->group_end();
		}
		
		$this->db->join($t_category, "{$t_main}.Kategori_ID = {$t_category}.Kategori_ID","LEFT OUTER")
			->join($t_class, "{$t_main}.Kelas_ID = {$t_class}.Kelas_ID","LEFT OUTER")
			->join($t_unit, "{$t_main}.Stok_Satuan_ID = {$t_unit}.Satuan_ID","LEFT OUTER")
			->join($t_unit_2, "{$t_main}.Beli_Satuan_Id = t_unit_2.Satuan_ID","LEFT OUTER")
			->join($t_location, "{$t_main}.Lokasi_ID = {$t_location}.Lokasi_ID","LEFT OUTER")
			->join($t_item_location, "{$t_main}.Barang_ID = {$t_item_location}.Barang_ID","LEFT OUTER")
			;
		$records_filtered = $this->db->count_all_results($t_main);
		
		// get result filtered
		/*$db_select = <<<EOSQL
			{$t_main}.Kode_Barang, 
			{$t_main}.Nama_Barang,
			{$t_class}.Nama_Kelas,			
			{$t_main}.Jual_IncludeTax Include_Tax,
			{$t_main}.FormulariumHC Formularium_HC,
			{$t_main}.KelompokJenis Kelompok_Jenis,			
			{$t_category}.Nama_KAtegori Nama_Kategori,
			{$t_location}.Nama_Lokasi,			
			(SELECT SUM({$t_item_location}.Qty_Stok) FROM {$t_item_location} WHERE ({$t_item_location}.Barang_ID = {$t_main}.Barang_ID)) AS Qty_Stok,			
			{$t_unit}.Nama_Satuan Satuan_Stok,			
			{$t_main}.Aktif,
			{$t_main}.Barang_ID
EOSQL;*/

		$db_select = <<<EOSQL
			{$t_main}.Kode_Barang, 
			{$t_main}.Nama_Barang,
			{$t_class}.Nama_Kelas,			
			{$t_main}.Jual_IncludeTax Include_Tax,
			{$t_main}.FormulariumHC Formularium_HC,
			{$t_main}.KelompokJenis Kelompok_Jenis,			
			{$t_category}.Nama_KAtegori Nama_Kategori,
			{$t_location}.Nama_Lokasi,			
			{$t_item_location}.Qty_Stok AS Qty_Stok,	
			{$t_unit}.Nama_Satuan Satuan_Stok,			
			{$t_main}.Aktif,
			{$t_main}.Barang_ID
EOSQL;
		
		$this->db->select($db_select);
		
		if (!empty($db_where))
		{
			$this->db->group_start();
			foreach($db_where as $field => $match){ $this->db->where($field, $match); }
			$this->db->group_end();
		}
		if (!empty($db_like))
		{
			$this->db->group_start();
			foreach($db_like as $field => $match){ $this->db->like($field, $match, 'both', TRUE); }
			$this->db->group_end();
		}
		if (!empty($db_or_like))
		{
			$this->db->group_start();
			foreach($db_or_like as $field => $match){ $this->db->or_like($field, $match, 'both', TRUE); }
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
		
		$this->db->join($t_category, "{$t_main}.Kategori_ID = {$t_category}.Kategori_ID","LEFT OUTER")
			->join($t_class, "{$t_main}.Kelas_ID = {$t_class}.Kelas_ID","LEFT OUTER")
			->join($t_unit, "{$t_main}.Stok_Satuan_ID = {$t_unit}.Satuan_ID","LEFT OUTER")
			->join($t_unit_2, "{$t_main}.Beli_Satuan_Id = t_unit_2.Satuan_ID","LEFT OUTER")
			->join($t_location, "{$t_main}.Lokasi_ID = {$t_location}.Lokasi_ID","LEFT OUTER")
			->join($t_item_location, "{$t_main}.Barang_ID = {$t_item_location}.Barang_ID","LEFT OUTER")
			;
		
		$query = $this->db->get($t_main);
		$result = $query->result_array();
		
		//echo $this->db->last_query();exit(0);
		
        $output = [
				'draw' => intval($draw),
				'recordsTotal' => $records_total,
				'recordsFiltered' => $records_filtered,
				'data' => []
			];        
        foreach($result as $row)
        {
			$a_id = (int) $row['Barang_ID'];
			
			$a_edit = anchor(site_url("{$this->nameroutes}/update/{$a_id}"), '<i class="fa fa-pencil"></i> ' . lang('action:edit'), [
					//'data-act' => 'ajax-modal', 
					//'data-title' => lang('action:edit'),
					//'data-action-url' => site_url("{$this->nameroutes}/update/{$a_id}"),
					//'data-modal-lg' => 1,
				]);
			$a_delete = anchor('#', '<i class="fa fa-trash-o"></i> ' . lang('action:delete'), [
					'data-act' => 'ajax-modal', 
					'data-title' => lang('action:delete'),
					'data-action-url' => site_url("{$this->nameroutes}/delete/{$a_id}")
				]);
			
			// $action = '<div class="text-center"><div class="btn-group text-left">'
			// 	. '<button type="button" class="btn btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">'
			// 	. lang('actions') . ' <span class="caret"></span></button>
			// 	<ul class="dropdown-menu pull-right" role="menu">
			// 		<li>' . $a_edit . '</li>
			// 		<li>' . $a_delete . '</li>
			// 	</ul>
			// </div></div>';

						
			$action = '<div class="text-center"><div class="btn-group text-left">'
				. '<button type="button" class="btn btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">'
				. lang('actions') . ' <span class="caret"></span></button>
				<ul class="dropdown-menu pull-right" role="menu">
					<li>' . $a_edit . '</li>
				</ul>
			</div></div>';
			
			$row['Actions'] = $action;
			$row['Qty_Stok'] = !empty($row['Qty_Stok']) ? round($row['Qty_Stok'], 2, PHP_ROUND_HALF_DOWN ) : 0;
			
			$output['data'][] = $row;
        }
		
		$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($output))
			->_display();
		
		exit(0);
    }
	
	public function get_typegroup_list()
	{
		$this->output
			->set_status_header(200)
			->set_content_type('text/html', 'utf-8')
			->set_output($this->item_typegroup_model->to_list_html('Pilih Jenis', $this->input->post('g')))
			->_display();
		
		exit(0);
	}
	
	public function get_model()
	{
		return $this->item_model;
	}
	
	public function lookup()
	{
		$this->load->view( 'references/item/modal/lookup' );
	}
	
	public function lookup_collection()
	{						
        $start = $this->input->get_post('start', true);
        $length = $this->input->get_post('length', true);
        $order = $this->input->get_post('order', true);
        $columns = $this->input->get_post('columns', true);
        $search = $this->input->get_post('search', true);
        $draw = $this->input->get_post('draw', true);
		
		$t_item = $this->item_model->table;
		$t_item_category = $this->item_category_model->table;
		$t_item_unit = $this->item_unit_model->table;
		$t_item_location = $this->item_location_model->table;

		$db_where = array();
		$db_like = array();
		
		//prepare defautl flter
		$db_where["{$t_item}.Aktif"] = 1;
		$db_where["{$t_item_location}.Aktif"] = 1;
		
		if( $this->input->post("Gudang_ID") ){
			$db_where["{$t_item_location}.Lokasi_ID"] = $this->input->post("Gudang_ID");
		}
		
		if( $this->input->post("SectionID") ){
			$section = $this->section_model->get_one( $this->input->post("SectionID") );
			$db_where["{$t_item_location}.Lokasi_ID"] = $section->Lokasi_ID;
		}
		
		if( $this->input->post("location_to") ){
			$db_where["{$t_item_location}.Lokasi_ID"] = $this->input->post("location_to");
		}
	
		if( $this->input->post("is_stock_opname") ){
			$db_where["{$t_item_location}.Lokasi_ID"] = $this->input->post("location_id");
			if($this->input->post("type_group"))
			{
				$db_where["{$t_item}.KelompokJenis"] = $this->input->post("type_group");
			}
		}
		
		// preparing default
		if( isset($search['value']) && ! empty($search['value']) )
        {
            $keywords = $this->db->escape_str( $search['value'] );
			
			$db_like[ $this->db->escape_str("{$t_item}.Kode_Barang") ] = $keywords;
			$db_like[ $this->db->escape_str("{$t_item}.Nama_Barang") ] = $keywords;
			 
        }
		
		// get total records
		$this->db->from( $t_item );
		$records_total = $this->db->count_all_results();
		
		// get total filtered
		$this->db
			->from( $t_item )
			->join($t_item_category, "{$t_item_category}.Kategori_ID = {$t_item}.Kategori_ID", "INNER")
			->join($t_item_location, "{$t_item_location}.Barang_ID = {$t_item}.Barang_ID", "INNER")
			->join($t_item_unit, "{$t_item}.Stok_Satuan_id = {$t_item_unit}.Satuan_Id", "INNER")
			;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }		
		$records_filtered = $this->db->count_all_results();
		
		// get result filtered
		$db_select = "
				{$t_item}.Kode_Barang,
				{$t_item}.Barang_ID,
				{$t_item}.Nama_Barang,
				{$t_item}.Harga_Beli,
				{$t_item}.HRataRata,
				round({$t_item_location}.Qty_Stok,2,1) as Qty_Stok,
				{$t_item}.Konversi,
				{$t_item_location}.JenisBarangID,
				{$t_item_location}.Min_Stok,
				{$t_item_location}.Max_Stok,
				{$t_item_location}.Qty_Stok,
				{$t_item_location}.Fast_Moving,
				{$t_item_location}.Slow_Moving,
				{$t_item_location}.D_Stok,
				{$t_item_unit}.Kode_Satuan,
				{$t_item}.FormulariumHC,
				{$t_item_category}.Nama_Kategori";

		$this->db
			->select( $db_select )
			->from( $t_item )
			->join($t_item_category, "{$t_item_category}.Kategori_ID = {$t_item}.Kategori_ID", "LEFT")
			->join($t_item_location, "{$t_item_location}.Barang_ID = {$t_item}.Barang_ID", "INNER")
			->join($t_item_unit, "{$t_item}.Stok_Satuan_id = {$t_item_unit}.Satuan_Id", "INNER")
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
			$row->StatusBarang = NULL;
			$row->StatusBarang = $row->Fast_Moving ? "FAST MOVING" : $row->StatusBarang;
			$row->StatusBarang = $row->Slow_Moving ? "SLOW MOVING" : $row->StatusBarang;
			$row->StatusBarang = $row->D_Stok ? "DEATH STOK" : $row->StatusBarang;
			
            $output['data'][] = $row;
        }
		
		response_json( $output );
	}
	
	public function lookup_item_collection()
	{						
        $start = $this->input->get_post('start', true);
        $length = $this->input->get_post('length', true);
        $order = $this->input->get_post('order', true);
        $columns = $this->input->get_post('columns', true);
        $search = $this->input->get_post('search', true);
        $draw = $this->input->get_post('draw', true);
		
		$t_item = $this->item_model->table;
		$t_item_category = $this->item_category_model->table;
		$t_item_unit = $this->item_unit_model->table;
		$t_item_location = $this->item_location_model->table;

		$db_where = array();
		$db_like = array();
		
		//prepare defautl flter
		$db_where["{$t_item}.Aktif"] = 1;
		$db_where["{$t_item}.Aktif"] = 1;
		
		// preparing default
		if( isset($search['value']) && ! empty($search['value']) )
        {
            $keywords = $this->db->escape_str( $search['value'] );
			
			$db_like[ $this->db->escape_str("{$t_item}.Kode_Barang") ] = $keywords;
			$db_like[ $this->db->escape_str("{$t_item}.Nama_Barang") ] = $keywords;
        }
		
		// get total records
		$this->db->from( $t_item );
		$records_total = $this->db->count_all_results();
		
		// get total filtered
		$this->db
			->from( $t_item )
			->join($t_item_category, "{$t_item_category}.Kategori_ID = {$t_item}.Kategori_ID", "INNER")
			->join($t_item_unit, "{$t_item}.Stok_Satuan_id = {$t_item_unit}.Satuan_Id", "INNER")
			;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }		
		$records_filtered = $this->db->count_all_results();
		
		// get result filtered
		$db_select = "
				{$t_item}.Kode_Barang,
				{$t_item}.Barang_ID,
				{$t_item}.Nama_Barang,
				{$t_item}.Harga_Beli,
				{$t_item}.HRataRata,
				{$t_item}.Konversi,
				{$t_item_unit}.Kode_Satuan,
				{$t_item}.FormulariumHC,
				{$t_item_category}.Nama_Kategori";

		$this->db
			->select( $db_select )
			->from( $t_item )
			->join($t_item_category, "{$t_item_category}.Kategori_ID = {$t_item}.Kategori_ID", "INNER")
			->join($t_item_unit, "{$t_item}.Stok_Satuan_id = {$t_item_unit}.Satuan_Id", "INNER")
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


}

