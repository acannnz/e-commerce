<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Item_location extends ADMIN_Controller
{
	protected $nameroutes;
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('inventory');
		
		$this->data['nameroutes'] = $this->nameroutes = 'inventory/references/item_location'; 
		
		$this->load->language('inventory');		
		$this->load->library('inventory');
		$this->load->helper('inventory');
		
		$this->load->model('item_location_model'); // main model
		$this->load->model('section_model');
		$this->load->model('item_model');
		$this->load->model('item_type_model');
		$this->load->model('item_unit_model');
		$this->load->model('item_subcategory_model');
	}
	
	//load note list view
	public function index()
	{
		$this->template
			->title(lang('heading:item_locations'),lang('heading:references'))
			->set_breadcrumb(lang('heading:references'), site_url($this->nameroutes))
			->set_breadcrumb(lang('heading:item_locations'))
			->build("references/item_location/index", $this->data);
	}
	
	public function update($location_id = 0, $product_id = 0)
	{
		$location_id = (int) @$location_id;
		$product_id = (int) @$product_id;
		
		$this->data['item'] = $item = $this->get_model()->get_by(['Lokasi_ID' => $location_id, 'Barang_ID' => $product_id]);
		
		$this->data['m_item'] = $m_item = $this->item_model->get_one($product_id); // > item
		$this->data['m_section'] = $m_section = $this->section_model->get_by(['Lokasi_ID' => @$item->Lokasi_ID]); // > item
		$this->data['m_satuan'] = $m_satuan = $this->item_unit_model->get_by(['Kode_Satuan' => @$item->Kode_Satuan]); // > item
		$this->data['m_type'] = $m_type = $this->item_type_model->get_one(@$item->JenisBarangID); // > item
		
		$this->data['form_action'] = $form_action = site_url("{$this->nameroutes}/update_post/{$location_id}/{$product_id}");
		$this->load->view('references/item_location/modal/update', $this->data);
	}
	
	public function update_post($location_id = 0, $product_id = 0)
	{
		$location_id = (int) @$location_id;
		$product_id = (int) @$product_id;
		
		$item = $this->get_model()->get_by(['Lokasi_ID' => $location_id, 'Barang_ID' => $product_id]);
		
		if( $item && $this->input->post() ) 
		{
			$post_data = $this->input->post("f");
			$post_data['LastUpdate'] = @date('Y-m-d H:i:s', now());
			
			$this->form_validation->set_rules([
					['field' => 'Min_Stok', 'label' => 'Minimum Stok', 'rules' => 'required|is_natural'],
					['field' => 'Max_Stok', 'label' => 'Maximum Stok', 'rules' => 'required|is_natural'],
					['field' => 'Death_Stok', 'label' => 'Death Stok', 'rules' => 'required|is_natural']
				]);
			$this->form_validation->set_data($post_data);
			
			if ($this->form_validation->run())
			{
				if ($this->get_model()->update_by($post_data, ['Lokasi_ID' => $location_id, 'Barang_ID' => $product_id]))
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
	
	public function collection()
    {
        $start = $this->input->get_post('start', true);
        $length = $this->input->get_post('length', true);
        $order = $this->input->get_post('order', true);
        $columns = $this->input->get_post('columns', true);
        $search = $this->input->get_post('search', true);
        $draw = $this->input->get_post('draw', true);
		
		$t_main = $this->db->dbprefix($this->get_model()->table);
		$t_section = $this->db->dbprefix($this->section_model->table);
		//$t_item = $this->db->dbprefix($this->item_model->table);
		$t_item = 'Vw_Barang'; // Ini view
		$t_item_type = $this->db->dbprefix($this->item_type_model->table);
		
		
		// get total records
		$records_total = $this->db->count_all_results($t_main);
		
		// preparing filter
		$db_like = [];
		if (isset($search['value']) && ! empty($search['value']))
        {
            $words = $this->db->escape_str( $search['value'] );
			
			$db_like[$this->db->escape_str("{$t_section}.SectionName")] = $words;
			$db_like[$this->db->escape_str("{$t_item}.Kode_Barang")] = $words;
			$db_like[$this->db->escape_str("{$t_item}.Nama_Barang")] = $words;			
			$db_like[$this->db->escape_str("{$t_item_type}.NmJenis")] = $words;
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
		
		$this->db
			->join($t_section, "{$t_main}.Lokasi_ID = {$t_section}.Lokasi_ID","LEFT OUTER")
			->join($t_item, "{$t_main}.Barang_ID = {$t_item}.Barang_ID","LEFT OUTER")
			->join($t_item_type, "{$t_main}.JenisBarangID = {$t_item_type}.IDJenis","LEFT OUTER");
		$records_filtered = $this->db->count_all_results($t_main);
		
		
		// get result filtered
		$db_select = <<<EOSQL
			{$t_section}.SectionName AS Section_Name, 
			{$t_item}.Kode_Barang, 
			{$t_item}.Nama_Barang,
			{$t_item}.Satuan_Stok AS Satuan,			
			{$t_item_type}.NmJenis AS Nama_Jenis,
			
			{$t_main}.Qty_Stok,
			{$t_main}.Min_Stok,
			{$t_main}.Max_Stok,
			{$t_main}.Death_Stok,
			{$t_main}.Lokasi_ID,
			{$t_main}.Barang_ID,
			{$t_main}.JenisBarangID Jenis_Barang_ID,
			
			{$t_main}.LastUpdate,
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
		
		$this->db->join($t_section, "{$t_main}.Lokasi_ID = {$t_section}.Lokasi_ID","LEFT OUTER")
			->join($t_item, "{$t_main}.Barang_ID = {$t_item}.Barang_ID","LEFT OUTER")
			->join($t_item_type, "{$t_main}.JenisBarangID = {$t_item_type}.IDJenis","LEFT OUTER");
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
			$a_id = (int) $row['Lokasi_ID'];
			$b_id = (int) $row['Barang_ID'];
			
			$row['LastUpdate'] = date('d-M Y', strtotime($row['LastUpdate']));
			
			
			$a_edit = anchor('#', '<i class="fa fa-pencil"></i> ' . lang('action:edit'), [
					'data-act' => 'ajax-modal', 
					'data-title' => lang('action:edit'),
					'data-action-url' => site_url("{$this->nameroutes}/update/{$a_id}/{$b_id}")
				]);
			
			$action = '<div class="text-center"><div class="btn-group text-left">'
				. '<button type="button" class="btn btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">'
				. lang('actions') . ' <span class="caret"></span></button>
				<ul class="dropdown-menu pull-right" role="menu">
					<li>' . $a_edit . '</li>
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
		return $this->item_location_model;
	}
}

