<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Service_component extends ADMIN_Controller
{
	protected $nameroutes = 'service/service_component';
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('admin');
		
		$this->data['nameroutes'] = $this->nameroutes; 
		
		$this->load->language('service');		
		$this->load->helper('service');
		$this->load->model('service_component_model');
		$this->load->model('account_model');
	}
	
	public function index()
	{
		$this->template
			->title(lang('heading:service_component'), lang('heading:service'))
			->set_breadcrumb(lang('heading:service') )
			->set_breadcrumb(lang('heading:service_component_list'), site_url($this->nameroutes))
			->build("service_component/datatable", $this->data);
	}
	
	public function create()
	{
	
		$item = (object) [
			'KomponenBiayaID' => service_helper::gen_component_code(),
			'KomponenName' => NULL,
			'KelompokAkun' => 'Biaya',
			'PostinganKe' => 'Hutang',
			'IncludeInsentif' => 0,
			'ExcludeCostRS' => 0,
			'HutangKe' => 'None',
			'KelompokJasa' => 'Jasa Sarana',
		];
		
		if( $this->input->post() ) 
		{
			$post_component = array_merge( (array) $item, $this->input->post("f") );

			$this->load->library('form_validation');
			$this->form_validation->set_rules($this->service_component_model->rules['insert']);
			$this->form_validation->set_data($post_component);
			if( $this->form_validation->run() )
			{							
				$this->db->trans_begin();
							
					$this->service_component_model->create( $post_component );							
										
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
		$this->data['dropdown_account_group'] = $this->service_component_model->dropdown_static('KelompokAkun');
		$this->data['dropdown_posting_to'] = $this->service_component_model->dropdown_static('PostinganKe');
		$this->data['dropdown_debt_to'] = $this->service_component_model->dropdown_static('HutangKe');
		$this->data['dropdown_service_group'] = $this->service_component_model->dropdown_static('KelompokJasa');
		$this->data['form_action'] = current_url();

		$this->data['lookup_outpatient_account'] = base_url("{$this->nameroutes}/lookup_data/lookup_outpatient_account");
		$this->data['lookup_inpatient_account'] = base_url("{$this->nameroutes}/lookup_data/lookup_inpatient_account");
		$this->data['lookup_emergency_account'] = base_url("{$this->nameroutes}/lookup_data/lookup_emergency_account");
		$this->data['lookup_hpp_account'] = base_url("{$this->nameroutes}/lookup_data/lookup_hpp_account");
		$this->data['lookup_hpp_againts_account'] = base_url("{$this->nameroutes}/lookup_data/lookup_hpp_againts_account");
		
		if(config_item('multi_bo') == 'TRUE'){
			$this->data['lookup_outpatient_account_2'] = base_url("{$this->nameroutes}/lookup_data/lookup_outpatient_account_2");
			$this->data['lookup_inpatient_account_2'] = base_url("{$this->nameroutes}/lookup_data/lookup_inpatient_account_2");
			$this->data['lookup_emergency_account_2'] = base_url("{$this->nameroutes}/lookup_data/lookup_emergency_account_2");
			$this->data['lookup_hpp_account_2'] = base_url("{$this->nameroutes}/lookup_data/lookup_hpp_account_2");
			$this->data['lookup_hpp_againts_account_2'] = base_url("{$this->nameroutes}/lookup_data/lookup_hpp_againts_account_2");
		}
		
		$this->template
			->title(lang('heading:service_component_create'),lang('heading:service'))
			->set_breadcrumb(lang('heading:service_component_list'), site_url($this->nameroutes))
			->set_breadcrumb(lang('heading:service_component_create'))
			->build("service_component/form", $this->data);
	}
	
	public function update($id = 0)
	{
		$this->data['item'] = $item = $this->service_component_model->get_one($id);
		
		if( $this->input->post() ) 
		{			
			$post_component = $this->input->post("f");
			
			$this->load->library('form_validation');
			$this->form_validation->set_rules($this->service_component_model->rules['update']);
			$this->form_validation->set_data($post_component);
			if( $this->form_validation->run() )
			{								
				$this->db->trans_begin();
				
					$this->service_component_model->update( $post_component, $id );	
					
				if ($this->db->trans_status() === FALSE)
				{
					$this->db->trans_rollback();
					$response = array(
							"status" => 'error',
							"message" => lang('global:updated_failed'),
							"code" => 500
						);
				}
				else
				{
					$this->db->trans_commit();
					$response = array(
							"status" => 'success',
							"message" => lang('global:updated_successfully'),
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
				
		$this->data['is_edit'] = TRUE;		
		$this->data['dropdown_account_group'] = $this->service_component_model->dropdown_static('KelompokAkun');
		$this->data['dropdown_posting_to'] = $this->service_component_model->dropdown_static('PostinganKe');
		$this->data['dropdown_debt_to'] = $this->service_component_model->dropdown_static('HutangKe');
		$this->data['dropdown_service_group'] = $this->service_component_model->dropdown_static('KelompokJasa');
		$this->data['form_action'] = current_url();

		$this->data['outpatient'] = service_helper::get_service_group_account($item->AkunNoRJ, 'BO_1');
		$this->data['inpatient'] = service_helper::get_service_group_account($item->AkunNoRI, 'BO_1');
		$this->data['emergency'] = service_helper::get_service_group_account($item->AKunNoUGD, 'BO_1');
		$this->data['hpp'] = service_helper::get_service_group_account($item->AkunNoHPP, 'BO_1');
		$this->data['hpp_againts'] = service_helper::get_service_group_account($item->AkunNoLawanHPP, 'BO_1');
		$this->data['lookup_outpatient_account'] = base_url("{$this->nameroutes}/lookup_data/lookup_outpatient_account");
		$this->data['lookup_inpatient_account'] = base_url("{$this->nameroutes}/lookup_data/lookup_inpatient_account");
		$this->data['lookup_emergency_account'] = base_url("{$this->nameroutes}/lookup_data/lookup_emergency_account");
		$this->data['lookup_hpp_account'] = base_url("{$this->nameroutes}/lookup_data/lookup_hpp_account");
		$this->data['lookup_hpp_againts_account'] = base_url("{$this->nameroutes}/lookup_data/lookup_hpp_againts_account");
		
		if(config_item('multi_bo') == 'TRUE'){
			$this->data['outpatient_2'] = service_helper::get_service_group_account($item->AkunNoRJ_2, 'BO_2');
			$this->data['inpatient_2'] = service_helper::get_service_group_account($item->AkunNoRI_2, 'BO_2');
			$this->data['emergency_2'] = service_helper::get_service_group_account($item->AKunNoUGD_2, 'BO_2');
			$this->data['hpp_2'] = service_helper::get_service_group_account($item->AkunNoHPP_2, 'BO_2');
			$this->data['hpp_againts_2'] = service_helper::get_service_group_account($item->AkunNoLawanHPP_2, 'BO_2');
			$this->data['lookup_outpatient_account_2'] = base_url("{$this->nameroutes}/lookup_data/lookup_outpatient_account_2");
			$this->data['lookup_inpatient_account_2'] = base_url("{$this->nameroutes}/lookup_data/lookup_inpatient_account_2");
			$this->data['lookup_emergency_account_2'] = base_url("{$this->nameroutes}/lookup_data/lookup_emergency_account_2");
			$this->data['lookup_hpp_account_2'] = base_url("{$this->nameroutes}/lookup_data/lookup_hpp_account_2");
			$this->data['lookup_hpp_againts_account_2'] = base_url("{$this->nameroutes}/lookup_data/lookup_hpp_againts_account_2");
		}
		
		$this->template
			->title(lang('heading:service_component'),lang('heading:service'))
			->set_breadcrumb(lang('heading:service_component_list'), site_url($this->nameroutes))
			->set_breadcrumb(lang('heading:service_component_update'))
			->build("service_component/form", $this->data);
	}
	
	public function delete($id = 0)
	{
		$this->data['item'] = $item = $this->service_component_model->get_one($id);
		
		if ($item  && (1 == $this->input->post('confirm') ) )
		{ 

			$this->db->trans_begin();
				
				$this->service_component_model->delete( $id );

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
		$this->load->view('service_component/modal/delete', $this->data);
	}
	
	public function lookup_data( $view, $is_ajax_request=false )
	{	
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view("service_component/lookup/{$view}");
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
		
		$db_from = "{$this->service_component_model->table} a";
		$db_where = array();
		$db_like = array();
		
		//prepare defautl flter

		// preparing default
		if( isset($search['value']) && ! empty($search['value']) )
        {
            $keywords = $this->db->escape_str( $search['value'] );
			
			$db_like[ $this->db->escape_str("a.KomponenBiayaID") ] = $keywords;
			$db_like[ $this->db->escape_str("a.KomponenName") ] = $keywords;
        }
		
		// get total records
		$this->db->from( $db_from );
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
            $output['data'][] = $row;
        }
		
		response_json( $output );
    }
}

