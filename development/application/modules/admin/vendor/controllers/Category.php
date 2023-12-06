<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Category extends ADMIN_Controller
{
	protected $nameroutes = 'vendor/category';
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('admin');
		
		$this->data['nameroutes'] = $this->nameroutes; 
		
		$this->load->language('vendor');		
		$this->load->helper('vendor');
		$this->load->model('category_model');
	}
	
	public function index()
	{
		$this->template
			->title(lang('heading:vendor_category'), lang('heading:vendor'))
			->set_breadcrumb(lang('heading:vendor') )
			->set_breadcrumb(lang('heading:vendor_category_list'), site_url($this->nameroutes))
			->build("category/datatable", $this->data);
	}
	
	public function create()
	{
	
		$item = (object) [
			'Kode_Kategori' => vendor_helper::gen_category_number(),
			'Kategori_Name' => NULL,
			'TypeHutang_ID' => NULL,
			'AkunPajak_No' => NULL,
		];
		
		if( $this->input->post() ) 
		{
			$post_category = array_merge( (array) $item, $this->input->post("category") );
			
			$this->load->library('form_validation');
			$this->form_validation->set_rules($this->category_model->rules['insert']);
			$this->form_validation->set_data($post_category);
			if( $this->form_validation->run() )
			{							
				$this->db->trans_begin();
							
					$this->category_model->create( $post_category );							
										
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
							"id" => $item->Kode_Kategori,
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
			
		$this->template
			->title(lang('heading:vendor_category_create'),lang('heading:vendor'))
			->set_breadcrumb(lang('heading:vendor_category_list'), site_url($this->nameroutes))
			->set_breadcrumb(lang('heading:vendor_category_create'))
			->build("category/form", $this->data);
	}
	
	public function update($id = 0)
	{
		$this->data['item'] = $item = $this->category_model->get_one($id);
		
		if( $this->input->post() ) 
		{			
			$post_category = $this->input->post("category");
			
			$this->load->library('form_validation');
			$this->form_validation->set_rules($this->category_model->rules['update']);
			$this->form_validation->set_data($post_category);
			if( $this->form_validation->run() )
			{								
				$this->db->trans_begin();
				
					$this->category_model->update( $post_category, $id );	
					
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
							"id" => $id,
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
		$this->data['form_action'] = current_url();
		
		$this->template
			->title(lang('heading:vendor_category'),lang('heading:vendor'))
			->set_breadcrumb(lang('heading:vendor_category_list'), site_url($this->nameroutes))
			->set_breadcrumb(lang('heading:vendor_category_update'))
			->build("category/form", $this->data);
	}
	
	public function delete($id = 0)
	{
		$this->data['item'] = $item = $this->category_model->get_one($id);
		
		if ($item  && (1 == $this->input->post('confirm') ) )
		{ 

			$this->db->trans_begin();
				
				$this->category_model->delete( $id );

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
		$this->load->view('category/modal/delete', $this->data);
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
		
		$db_from = "{$this->category_model->table} a";
		$db_where = array();
		$db_like = array();
		
		//prepare defautl flter

		// preparing default
		if( isset($search['value']) && ! empty($search['value']) )
        {
            $keywords = $this->db->escape_str( $search['value'] );
			
			$db_like[ $this->db->escape_str("a.Kode_Kategori") ] = $keywords;
			$db_like[ $this->db->escape_str("a.Kategori_Name") ] = $keywords;

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

