<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Drug_commission extends ADMIN_Controller
{
	protected $nameroutes = 'vendor/drug_commission';
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('admin');
		
		$this->data['nameroutes'] = $this->nameroutes; 
		
		$this->load->language('vendor');		
		$this->load->helper('vendor');
		$this->load->model('drug_commission_model');
		$this->load->model('drug_commission_patient_model');
		$this->load->model('drug_commission_item_model');
		$this->load->model('drug_commission_tht_model');
		$this->load->model('vendor_model');
		$this->load->model('item_model');
		$this->load->model('patient_type_model');
	}
	
	public function index()
	{
		$this->template
			->title(lang('heading:drug_commission'), lang('heading:vendor'))
			->set_breadcrumb(lang('heading:vendor') )
			->set_breadcrumb(lang('heading:drug_commission_list'), site_url($this->nameroutes))
			->build("drug_commission/datatable", $this->data);
	}
	
	public function update($id = 0)
	{
		$this->data['item'] = $item = $this->drug_commission_model->get_one($id);
		
		if( $this->input->post() ) 
		{			
			$post_dc = $this->input->post("f");
			$post_dc_item = $this->input->post("commission_item");
			$post_dc_patient = $this->input->post("commission_patient");
			$post_dc_tht = $this->input->post("commission_tht");

			$this->load->library('form_validation');
			$this->form_validation->set_rules($this->drug_commission_model->rules['update']);
			$this->form_validation->set_data($post_dc);
			if( $this->form_validation->run() )
			{												
				$message = vendor_helper::drug_commission_update( $post_dc, $post_dc_item, $post_dc_patient, $post_dc_tht );	
					
			} else
			{
				$message["message"] = $this->form_validation->get_all_error_string();
				$message["status"] = "error";
				$message["code"] = "500";
			}			
			response_json( $message );
		}
				
		$this->data['commission_patient'] = vendor_helper::get_drug_commission_patient( $item->Supplier_ID );		
		$this->data['commission_item'] = vendor_helper::get_drug_commission_item( $item->Supplier_ID );		
		$this->data['commission_tht'] = vendor_helper::get_drug_commission_tht( $item->Supplier_ID );		
		$this->data['patient_dropdown'] = $this->patient_type_model->dropdown_data();		
		$this->data['is_edit'] = TRUE;		
		$this->data['form_action'] = current_url();
		$this->data['add_sub_drug_commission'] = base_url('vendor/sub_drug_commission/form');
		
		$this->template
			->title(lang('heading:drug_commission'),lang('heading:vendor'))
			->set_breadcrumb(lang('heading:drug_commission_list'), site_url($this->nameroutes))
			->set_breadcrumb(lang('heading:drug_commission_update'))
			->build("drug_commission/form", $this->data);
	}
	
	public function lookup_collection ()
	{
		$this->datatable_collection( 1 );
	}
	
	public function datatable_collection( $Status = 1 )
	{
		$start = $this->input->get_post('start', true);
        $length = $this->input->get_post('length', true);
        $order = $this->input->get_post('order', true);
        $columns = $this->input->get_post('columns', true);
        $search = $this->input->get_post('search', true);
        $draw = $this->input->get_post('draw', true);
		
		$db_from = "{$this->drug_commission_model->table} a";
		$db_where = array();
		$db_like = array();
		
		//prepare defautl flter
		$db_category_in = ['V-002','V-009','V-012'];
		
		// preparing default
		if( isset($search['value']) && ! empty($search['value']) )
        {
            $keywords = $this->db->escape_str( $search['value'] );
			
			$db_like[ $this->db->escape_str("a.Kode_Supplier") ] = $keywords;
			$db_like[ $this->db->escape_str("a.Nama_Supplier") ] = $keywords;
        }
		
		// get total records
		$this->db->from( $db_from );
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_category_in) ){ $this->db->where_in('KodeKategoriVendor', $db_category_in ); }
		$records_total = $this->db->count_all_results();
		
		// get total filtered
		$this->db
			->from( $db_from )
			;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }		
		if( !empty($db_category_in) ){ $this->db->where_in('KodeKategoriVendor', $db_category_in ); }
		$records_filtered = $this->db->count_all_results();
		
		// get result filtered
		$db_select = <<<EOSQL
			a.Supplier_ID,
			a.Kode_Supplier, 
			a.Nama_Supplier, 
			a.Alamat_1, 
			a.TglMulaiTHT, 
			a.SelesaiTHT 
EOSQL;

		$this->db
			->select( $db_select )
			->from( $db_from )
			;
			
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }		
		if( !empty($db_category_in) ){ $this->db->where_in('KodeKategoriVendor', $db_category_in ); }
		
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

