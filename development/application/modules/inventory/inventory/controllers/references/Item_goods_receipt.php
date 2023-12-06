<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Item_goods_receipt extends ADMIN_Controller
{
	protected $nameroutes;
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('inventory');
		
		$this->data['nameroutes'] = $this->nameroutes = 'inventory/references/item_goods_receipt'; 
		
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
		$db_where["{$t_item}.BarangKonsinyasi"] = 0;
		$db_where["{$t_item_location}.Aktif"] = 1;
		// $db_where["{$t_item_location}.Lokasi_ID"] = 1426;
		
		if( $this->input->post("Lokasi_ID") ){
			$db_where["{$t_item_location}.Lokasi_ID"] = $this->input->post("Lokasi_ID");
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
				{$t_item}.Harga_Jual,
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
				{$t_item_location}.Lokasi_ID,
				{$t_item_unit}.Kode_Satuan,
				{$t_item}.FormulariumHC,
				{$t_item_category}.Nama_Kategori";

		$this->db
			->select( $db_select )
			->from( $t_item )
			->join($t_item_category, "{$t_item_category}.Kategori_ID = {$t_item}.Kategori_ID", "LEFT")
			->join($t_item_location, "{$t_item_location}.Barang_ID = {$t_item}.Barang_ID", "INNER")
			->join($t_item_unit, "{$t_item}.Stok_Satuan_id = {$t_item_unit}.Satuan_Id", "INNER")
			// ->join($t_item_unit, "{$t_item}.Beli_Satuan_Id = {$t_item_unit}.Satuan_Id", "INNER")
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
			// print_r($row);exit;
			$row->StatusBarang = NULL;
			$row->StatusBarang = $row->Fast_Moving ? "FAST MOVING" : $row->StatusBarang;
			$row->StatusBarang = $row->Slow_Moving ? "SLOW MOVING" : $row->StatusBarang;
			$row->StatusBarang = $row->D_Stok ? "DEATH STOK" : $row->StatusBarang;
			$row->Lokasi_ID	   = $row->Lokasi_ID;
			
            $output['data'][] = $row;
        }
		
		response_json( $output );
	}
	
	public function lookup_depo_collection()
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
		// $db_where["{$t_item_location}.Lokasi_ID"] = 1426;
		
		if( $this->input->post("Lokasi_ID") ){
			$db_where["{$t_item_location}.Lokasi_ID"] = $this->input->post("Lokasi_ID");
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
				{$t_item}.Harga_Jual,
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
				{$t_item_location}.Lokasi_ID,
				{$t_item_unit}.Kode_Satuan,
				{$t_item}.FormulariumHC,
				{$t_item_category}.Nama_Kategori";

		$this->db
			->select( $db_select )
			->from( $t_item )
			->join($t_item_category, "{$t_item_category}.Kategori_ID = {$t_item}.Kategori_ID", "LEFT")
			->join($t_item_location, "{$t_item_location}.Barang_ID = {$t_item}.Barang_ID", "INNER")
			->join($t_item_unit, "{$t_item}.Stok_Satuan_id = {$t_item_unit}.Satuan_Id", "INNER")
			// ->join($t_item_unit, "{$t_item}.Beli_Satuan_Id = {$t_item_unit}.Satuan_Id", "INNER")
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

		$section_pharmacy = $this->session->userdata('pharmacy');
		foreach($result as $row)
        {      
			// print_r($row);exit;
			$HargaGrading = $this->db->query("Select * from GetHargaObatNew_WithStok(3,'xx',0,$row->Barang_ID,0,'". $section_pharmacy['section_id'] ."',0)")->row();					
			$row->Harga = $HargaGrading->Harga_Baru;
			$row->StatusBarang = NULL;
			$row->StatusBarang = $row->Fast_Moving ? "FAST MOVING" : $row->StatusBarang;
			$row->StatusBarang = $row->Slow_Moving ? "SLOW MOVING" : $row->StatusBarang;
			$row->StatusBarang = $row->D_Stok ? "DEATH STOK" : $row->StatusBarang;
			$row->Lokasi_ID	   = $row->Lokasi_ID;
			
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

