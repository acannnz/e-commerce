<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Merchan extends ADMIN_Controller
{
	protected $nameroutes = 'vendor/merchan';
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('admin');
		
		$this->data['nameroutes'] = $this->nameroutes; 
		
		$this->load->language('vendor');		
		$this->load->helper('vendor');
		$this->load->model('merchan_model');
		$this->load->model('account_model');
	}
	
	public function index()
	{
		$this->template
			->title(lang('heading:merchan'), lang('heading:vendor'))
			->set_breadcrumb(lang('heading:vendor') )
			->set_breadcrumb(lang('heading:merchan_list'), site_url($this->nameroutes))
			->build("merchan/datatable", $this->data);
	}
	
	public function create()
	{
	
		$item = (object) [
			'ID' => NULL,
			'NamaBank' => NULL,
			'Akun_ID_Tujuan' => NULL,
			'AddCharge_Debet' => 0,
			'AddCharge_Kredit' => 0,
			'Diskon' => 0,
		];
		
		if( $this->input->post() ) 
		{
			$post_merchan = array_merge( (array) $item, $this->input->post("f") );
			
			$this->load->library('form_validation');
			$this->form_validation->set_rules($this->merchan_model->rules['insert']);
			$this->form_validation->set_data($post_merchan);
			if( $this->form_validation->run() )
			{							
				$this->db->trans_begin();
							
					$this->merchan_model->create( $post_merchan );							
										
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
		$this->data['form_action'] = current_url();
		$this->data['lookup_account'] = base_url("{$this->nameroutes}/lookup_data/lookup_account");
			
		$this->template
			->title(lang('heading:merchan_create'),lang('heading:vendor'))
			->set_breadcrumb(lang('heading:merchan_list'), site_url($this->nameroutes))
			->set_breadcrumb(lang('heading:merchan_create'))
			->build("merchan/form", $this->data);
	}
	
	public function update($id = 0)
	{
		$this->data['item'] = $item = $this->merchan_model->get_one($id);
		
		if( $this->input->post() ) 
		{			
			$post_merchan = $this->input->post("f");
			
			$this->load->library('form_validation');
			$this->form_validation->set_rules($this->merchan_model->rules['update']);
			$this->form_validation->set_data($post_merchan);
			if( $this->form_validation->run() )
			{								
				$this->db->trans_begin();
				
					$this->merchan_model->update( $post_merchan, $id );	
					
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
				
		$this->data['account'] = $this->account_model->get_one( $item->Akun_ID_Tujuan );
		$this->data['is_edit'] = TRUE;		
		$this->data['form_action'] = current_url();
		$this->data['lookup_account'] = base_url("{$this->nameroutes}/lookup_data/lookup_account");
		
		$this->template
			->title(lang('heading:merchan'),lang('heading:vendor'))
			->set_breadcrumb(lang('heading:merchan_list'), site_url($this->nameroutes))
			->set_breadcrumb(lang('heading:merchan_update'))
			->build("merchan/form", $this->data);
	}
	
	public function delete($id = 0)
	{
		$this->data['item'] = $item = $this->merchan_model->get_one($id);
		
		if ($item  && (1 == $this->input->post('confirm') ) )
		{ 

			$this->db->trans_begin();
				
				$this->merchan_model->delete( $id );

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
		$this->load->view('merchan/modal/delete', $this->data);
	}
	
	public function lookup_data( $view, $is_ajax_request=false )
	{	
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view("merchan/lookup/{$view}");
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
		
		$db_from = "{$this->merchan_model->table} a";
		$db_where = array();
		$db_like = array();
		
		//prepare defautl flter

		// preparing default
		if( isset($search['value']) && ! empty($search['value']) )
        {
            $keywords = $this->db->escape_str( $search['value'] );
			
			$db_like[ $this->db->escape_str("a.ID") ] = $keywords;
			$db_like[ $this->db->escape_str("a.NamaBank") ] = $keywords;
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
			->join("{$this->account_model->table} b", "a.Akun_ID_Tujuan = b.Akun_ID", "LEFT OUTER")
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
			->join("{$this->account_model->table} b", "a.Akun_ID_Tujuan = b.Akun_ID", "LEFT OUTER")
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

