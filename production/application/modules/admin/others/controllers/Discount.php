<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Discount extends ADMIN_Controller
{
	protected $nameroutes = 'others/discount';
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('admin');
		
		$this->data['nameroutes'] = $this->nameroutes; 
		
		$this->load->language('others');		
		$this->load->helper('others');
		$this->load->model('discount_model');
		$this->load->model('discount_service_model');
		$this->load->model('account_model');
		$this->load->model('service_group_model');
		$this->load->model('service_component_model');
	}
	
	public function index()
	{
		$this->data['datatables'] = TRUE;
		
		$this->template
			->title(lang('heading:discount'), lang('heading:others'))
			->set_breadcrumb(lang('heading:others') )
			->set_breadcrumb(lang('heading:discount_list'), site_url($this->nameroutes))
			->build("discount/datatable", $this->data);
	}
	
	public function create()
	{
		$item = (object) [
			'IDDiscount' => NULL,
			'NamaDiscount' => NULL,
			'NamaInternational' => NULL,
			'AkunNo' => NULL,
			'HaveD' => 'F',
			'DiskonTotal' => 0,
			'DenganJasa' => 0,
			'DiskonKomponen' => 1,
			'KomponenBiayaID' => NULL,
			'DiskonGroupJasa' => 0,
			'GroupJasaID' => NULL,
			'DiskonTdkLangsung' => 0,
		];
		
		if( $this->input->post() ) 
		{
			$post_discount = array_merge( (array) $item, $this->input->post("f") );
			$post_discount_service = $this->input->post("discount_service");
		
			$this->load->library('form_validation');
			$this->form_validation->set_rules($this->discount_model->rules['insert']);
			$this->form_validation->set_data($post_discount);
			if( $this->form_validation->run() )
			{							
				$this->db->trans_begin();
							
					$this->discount_model->create( $post_discount );
					if(!empty($post_discount_service))
						$this->discount_service_model->mass_create( $post_discount_service );							
										
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
		$this->data['dropdown_service_group'] = $this->service_group_model->dropdown_data();
		$this->data['dropdown_service_component'] = $this->service_component_model->dropdown_data();
		$this->data['dropdown_haved'] = [ 'F' => lang('global:no'), 'T' => lang('global:yes') ];
		$this->data['form_action'] = current_url();
		$this->data['lookup_account'] = base_url("{$this->nameroutes}/lookup_data/lookup_account");
		$this->data['lookup_service'] = base_url("{$this->nameroutes}/lookup_data/lookup_service");
	
		$this->template
			->title(lang('heading:discount_create'),lang('heading:others'))
			->set_breadcrumb(lang('heading:discount_list'), site_url($this->nameroutes))
			->set_breadcrumb(lang('heading:discount_create'))
			->build("discount/form", $this->data);
	}
	
	public function update($id = 0)
	{
		$this->data['item'] = $item = $this->discount_model->get_one($id);
		
		if( $this->input->post() ) 
		{			
			$post_discount = $this->input->post("f");
			$post_discount_service = (array) $this->input->post("discount_service");
			
			$this->load->library('form_validation');
			$this->form_validation->set_rules($this->discount_model->rules['update']);
			$this->form_validation->set_data($post_discount);
			if( $this->form_validation->run() )
			{								
				$this->db->trans_begin();
				
					$this->discount_model->update( $post_discount, $id );	
					
					$delete_service = [];
					foreach( $post_discount_service as $row ):
						if($this->discount_service_model->count_all(['IDDiscount' => $id, 'IDJasa' => $row['IDJasa']])):
							$this->discount_service_model->update_by( $row, ['IDDiscount' => $id, 'IDJasa' => $row['IDJasa']]);	
						else:	
							$this->discount_service_model->create( $row );	
						endif;
						$delete_service[] = $row['IDJasa'];
					endforeach;
	
					if($delete_service)
						$this->discount_service_model->delete_not_in($id, $delete_service );	
					
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
		
		$this->data['account'] =$this->account_model->get_by(['Akun_No' => $item->AkunNo]);
		$this->data['discount_service'] = others_helper::get_all_dicount_service( $id );
		
		$this->data['dropdown_service_group'] = $this->service_group_model->dropdown_data();
		$this->data['dropdown_service_component'] = $this->service_component_model->dropdown_data();
		$this->data['dropdown_haved'] = [ 'F' => lang('global:no'), 'T' => lang('global:yes') ];				
		
		$this->data['is_edit'] = TRUE;		
		$this->data['form_action'] = current_url();
		$this->data['lookup_account'] = base_url("{$this->nameroutes}/lookup_data/lookup_account");
		$this->data['lookup_service'] = base_url("{$this->nameroutes}/lookup_data/lookup_service");

		$this->template
			->title(lang('heading:discount'),lang('heading:others'))
			->set_breadcrumb(lang('heading:discount_list'), site_url($this->nameroutes))
			->set_breadcrumb(lang('heading:discount_update'))
			->build("discount/form", $this->data);
	}
	
	public function delete($id = 0)
	{
		$this->data['item'] = $item = $this->discount_model->get_one($id);
		
		if ($item  && (1 == $this->input->post('confirm') ) )
		{ 

			$this->db->trans_begin();
				
				$this->discount_service_model->delete_by( ['IDDIscount' => $id ] );
				$this->discount_model->delete( $id );

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
		$this->load->view('discount/modal/delete', $this->data);
	}
	
	public function lookup_data( $view, $is_ajax_request=false )
	{	
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view("discount/lookup/{$view}");
		}
	}
	
	public function dropdown_html( $parent_id=0 )
	{
		if( $this->input->is_ajax_request() )
		{
			$parent_id = ($parent_id == 0) ? $this->input->get_post('parent_id') : $parent_id;
			
			$collection = array();
			$collection = $this->discount_model->dropdown_html( ['GroupJasa' => $parent_id] );
		
			response_json( $collection );
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
		
		$db_from = "{$this->discount_model->table} a";
		$db_where = array();
		$db_like = array();
		
		//prepare defautl flter

		// preparing default
		if( isset($search['value']) && ! empty($search['value']) )
        {
            $keywords = $this->db->escape_str( $search['value'] );
			
			$db_like[ $this->db->escape_str("a.{$this->discount_model->index_key}") ] = $keywords;
			$db_like[ $this->db->escape_str("a.NamaDiscount") ] = $keywords;
			$db_like[ $this->db->escape_str("b.Akun_No") ] = $keywords;
			$db_like[ $this->db->escape_str("b.Akun_Name") ] = $keywords;
        }
		
		// get total records
		$this->db->from( $db_from );
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		$records_total = $this->db->count_all_results();
		
		// get total filtered
		$this->db
			->from( $db_from )
			->join("{$this->account_model->table} b", "a.AkunNo = b.Akun_No", "LEFT OUTER")
			;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }		
		$records_filtered = $this->db->count_all_results();
		
		// get result filtered
		$db_select = <<<EOSQL
			a.*,
			b.Akun_No,
			b.Akun_Name
EOSQL;

		$this->db
			->select( $db_select )
			->from( $db_from )
			->join("{$this->account_model->table} b", "a.AkunNo = b.Akun_No", "LEFT OUTER")
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

