<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Contract extends ADMIN_Controller
{
	protected $nameroutes = 'marketing/contract';
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('admin');
		
		$this->data['nameroutes'] = $this->nameroutes; 
		
		$this->load->language('marketing');		
		$this->load->helper('marketing');
		$this->load->model('contract_model');
		$this->load->model('contract_service_model');
		$this->load->model('contract_service_component_model');
		$this->load->model('contract_drug_model');
		$this->load->model('customer_model');
		$this->load->model('cooperation_type_model');
		$this->load->model('class_model');
		$this->load->model('account_model');
	}
	
	public function index()
	{
		$this->template
			->title(lang('heading:contract'), lang('heading:marketing'))
			->set_breadcrumb(lang('heading:marketing') )
			->set_breadcrumb(lang('heading:contract_list'), site_url($this->nameroutes))
			->build("contract/datatable", $this->data);
	}
	
	public function create()
	{
		$item = (object) [
			'Active' => 1,
			'MaxHariRawatPerOpname' => 16,
			'MaxHariRawatPerTahun' => 60,
			'MaxRIRupiahPerTahun' => 0,
			'ObatUp' => 0,
			'ObatDiscount' => 0,
			'CoPay' => 0,
			'KelebihanPlafon' => 'PERUSAHAAN'
		];
		
		if( $this->input->post() ) 
		{
			$contract = array_merge( (array) $item, $this->input->post("f") );
			$contract_service = $this->input->post("service");
			$contract_component = $this->input->post("component");
			$contract_drug = $this->input->post("drug");
			
			$this->load->library('form_validation');
			$this->form_validation->set_rules($this->contract_model->rules['insert']);
			$this->form_validation->set_data($contract);
			if( $this->form_validation->run() )
			{														
				$response = marketing_helper::create_contract( $contract, $contract_service, $contract_component, $contract_drug );										
			} else
			{
				$response["message"] = $this->form_validation->get_all_error_string();
				$response["status"] = "error";
				$response["code"] = "500";
			}

			response_json( $response );
		}
		
		$this->data['item'] = $item;
		$this->data['cooperation_type_dropdown'] = $this->cooperation_type_model->dropdown_data();
		$this->data['class_dropdown'] = $this->class_model->dropdown_data();
		$this->data['borne_dropdown'] = ['ALL' => 'ALL', 'Rawat Jalan' => 'Rawat Jalan', 'Rawat Inap' => 'Rawat Inap'];
		$this->data['form_action'] = current_url();
		$this->data['lookup_customer'] = base_url("{$this->nameroutes}/lookup_data/lookup_customer");
		$this->data['lookup_receivable_account'] = base_url("{$this->nameroutes}/lookup_data/lookup_receivable_account");
			
		$this->template
			->title(lang('heading:contract_create'),lang('heading:marketing'))
			->set_breadcrumb(lang('heading:contract_list'), site_url($this->nameroutes))
			->set_breadcrumb(lang('heading:contract_create'))
			->build("contract/form", $this->data);
	}
	
	public function update($id = 0)
	{
		$this->data['item'] = $item = marketing_helper::get_contract($id);
		
		if( $this->input->post() ) 
		{			
			$contract = $this->input->post("f");
			$contract_service = $this->input->post("service");
			$contract_component = $this->input->post("component");
			$contract_drug = $this->input->post("drug");
			
			$this->load->library('form_validation');
			$this->form_validation->set_rules($this->contract_model->rules['update']);
			$this->form_validation->set_data($contract);
			if( $this->form_validation->run() )
			{								
				$response = marketing_helper::update_contract($id, $contract, $contract_service, $contract_component, $contract_drug);
			} else
			{
				$response["message"] = $this->form_validation->get_all_error_string();
				$response["status"] = "error";
				$response["code"] = "500";
			}
			
			response_json( $response );
		}
		
		$this->data['cooperation_type_dropdown'] = $this->cooperation_type_model->dropdown_data();
		$this->data['class_dropdown'] = $this->class_model->dropdown_data();
		$this->data['borne_dropdown'] = ['ALL' => 'ALL', 'Rawat Jalan' => 'Rawat Jalan', 'Rawat Inap' => 'Rawat Inap'];
		$this->data['is_edit'] = TRUE;		
		$this->data['form_action'] = current_url();
		$this->data['lookup_receivable_account'] = base_url("{$this->nameroutes}/lookup_data/lookup_receivable_account");
		
		$this->template
			->title(lang('heading:contract'),lang('heading:marketing'))
			->set_breadcrumb(lang('heading:contract_list'), site_url($this->nameroutes))
			->set_breadcrumb(lang('heading:contract_update'))
			->build("contract/form", $this->data);
	}
	
	public function delete($id = 0)
	{
		$this->data['item'] = $item = $this->contract_model->get_one($id);
		
		if ($item  && (1 == $this->input->post('confirm') ) )
		{ 

			$this->db->trans_begin();
				
				$this->contract_model->delete( $id );

			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
				response_json(["status" => 'error', 'message' => lang('global:delete_failed'), 'success' => FALSE]);
			} else
			{
				$this->db->trans_commit();
				response_json(["status" => 'success', 'message' => lang('global:delete_successfully'), 'success' => TRUE]);
			}
		} 
		
		$this->data['form_action'] = $form_action = current_url();
		$this->load->view('contract/modal/delete', $this->data);
	}
	
	public function lookup_data( $view, $is_ajax_request=false )
	{	
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view("contract/lookup/{$view}");
		}
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
		
		$db_from = "{$this->contract_model->table} a";
		$db_where = array();
		$db_like = array();
		
		//prepare defautl flter
		$db_where['a.Active'] = 1;
		
		// preparing default
		if( isset($search['value']) && ! empty($search['value']) )
        {
            $keywords = $this->db->escape_str( $search['value'] );
			
			$db_like[ $this->db->escape_str("b.Kode_Customer") ] = $keywords;
			$db_like[ $this->db->escape_str("b.Nama_Customer") ] = $keywords;
			$db_like[ $this->db->escape_str("c.JenisKerjasama") ] = $keywords;
			$db_like[ $this->db->escape_str("d.NamaKelas") ] = $keywords;
        }
		
		// get total records
		$this->db->from( $db_from );
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		$records_total = $this->db->count_all_results();
		
		// get total filtered
		$this->db
			->from( $db_from )
			->join("{$this->customer_model->table} b", "a.CustomerID = b.Customer_ID", "LEFT OUTER")
			->join("{$this->cooperation_type_model->table} c", "a.JenisKerjasamaID = c.JenisKerjasamaID", "LEFT OUTER")
			->join("{$this->class_model->table} d", "a.KelasID = d.KelasID", "LEFT OUTER")
			;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }		
		$records_filtered = $this->db->count_all_results();
		
		// get result filtered
		$db_select = <<<EOSQL
			a.CustomerKerjasamaID,
			b.Kode_Customer, 
			b.Nama_Customer, 
			c.JenisKerjasama, 
			d.NamaKelas, 
			a.StartDate, 
			a.EndDate,
			a.CoPay, 
			a.MaxHariRawatPerOpname, 
			a.MaxHariRawatPerTahun, 
			a.KelebihanPlafon,
			a.Active
EOSQL;

		$this->db
			->select( $db_select )
			->from( $db_from )
			->join("{$this->customer_model->table} b", "a.CustomerID = b.Customer_ID", "LEFT OUTER")
			->join("{$this->cooperation_type_model->table} c", "a.JenisKerjasamaID = c.JenisKerjasamaID", "LEFT OUTER")
			->join("{$this->class_model->table} d", "a.KelasID = d.KelasID", "LEFT OUTER")
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

