<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Sub_specialist extends ADMIN_Controller
{
	protected $nameroutes = 'vendor/sub_specialist';
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('admin');
		
		$this->data['nameroutes'] = $this->nameroutes; 
		
		$this->load->language('vendor');		
		$this->load->helper('vendor');
		$this->load->model('sub_specialist_model');
	}
	
	public function index()
	{
		$this->template
			->title(lang('heading:vendor_sub_specialist'), lang('heading:vendor'))
			->set_breadcrumb(lang('heading:vendor') )
			->set_breadcrumb(lang('heading:vendor_sub_specialistlist'), site_url($this->nameroutes))
			->build("sub_specialist/datatable", $this->data);
	}
	
	public function form( $row_index = NULL )
	{
		$this->data['item'] = (object) ['SubSpesialisID' => '-'];
		$this->data['row_index'] = $row_index;
		$this->data['form_action'] = current_url();
		
		if( $this->input->is_ajax_request() )
		{
			$this->load->view('sub_specialist/form', $this->data);
		}
	}
	
	public function update($id = 0)
	{
		$this->data['item'] = $item = $this->_model->get_one($id);
		
		if( $this->input->post() ) 
		{			
			$post_sub_specialist = $this->input->post("");
			
			$this->load->library('form_validation');
			$this->form_validation->set_rules($this->_model->rules['update']);
			$this->form_validation->set_data($post_);
			if( $this->form_validation->run() )
			{								
				$this->db->trans_begin();
				
					$this->sub_specialist_model->update( $post_sub_specialist, $id );	
					
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
			->title(lang('heading:vendor_sub_specialist'),lang('heading:vendor'))
			->set_breadcrumb(lang('heading:vendor_sub_specialist_list'), site_url($this->nameroutes))
			->set_breadcrumb(lang('heading:vendor_sub_specialist_update'))
			->build("sub_specialist/form", $this->data);
	}
	
	public function delete($id = 0)
	{
		$this->data['item'] = $item = $this->_model->get_one($id);
		
		if ($item  && (1 == $this->input->post('confirm') ) )
		{ 

			$this->db->trans_begin();
				
				$this->_model->delete( $id );

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
		$this->load->view('sub_specialists/modal/delete', $this->data);
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
		
		$db_from = "{$this->sub_specialist_model->table} a";
		$db_where = array();
		$db_like = array();
		
		//prepare defautl flter

		// preparing default
		if( isset($search['value']) && ! empty($search['value']) )
        {
            $keywords = $this->db->escape_str( $search['value'] );
			
			$db_like[ $this->db->escape_str("a.Nama") ] = $keywords;
			$db_like[ $this->db->escape_str("a.Alamat") ] = $keywords;
			$db_like[ $this->db->escape_str("a.No_Telepon_Kantor") ] = $keywords;
			$db_like[ $this->db->escape_str("a.No_Handphone") ] = $keywords;
			$db_like[ $this->db->escape_str("a.Alamat_Email") ] = $keywords;
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

