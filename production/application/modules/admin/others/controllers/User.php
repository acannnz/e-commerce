<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class User extends ADMIN_Controller
{
	protected $nameroutes = 'others/user';
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('admin');

		$this->data['nameroutes'] = $this->nameroutes; 
		$this->load->language('others');		
		$this->load->model('user_model');
		$this->load->model('user_group_model');
		$this->load->model('group_model');
	}
	
	public function index()
	{

		$this->template
			->title(lang('heading:user'), lang('heading:auth'))
			->set_breadcrumb(lang('heading:auth') )
			->set_breadcrumb(lang('heading:user_list'), site_url($this->nameroutes))
			->build("user/datatable", $this->data);
	}
	
	public function create()
	{
		$item = (object) [
			'Username' => NULL,
			'Nama_Asli' => NULL,
			'Nama_Singkat' => NULL,
			'Password' => 'null',
			'Status_Aktif' => 1,
			'PasswordWeb' => NULL,
		];
		
		if( $this->input->post() ) 
		{
			$post_user = array_merge( (array) $item, $this->input->post("f") );
			$post_role = $this->input->post("role");
			$confirm_password = $this->input->post("c[PasswordWeb]");
			
			if( !empty($post_user['PasswordWeb']) && ($post_user['PasswordWeb'] === $confirm_password))
			{
				$post_user['PasswordWeb'] = password_hash($post_user['PasswordWeb'], PASSWORD_DEFAULT);	
			} else {
				response_json(['status' => 'error', 'message' => 'Password Konfirmasi tidak sama!']);	
			}
			
			$this->load->library('form_validation');
			$this->form_validation->set_rules($this->user_model->rules['insert']);
			$this->form_validation->set_data($post_user);
			if( $this->form_validation->run() )
			{							
				$this->db->trans_begin();							
	
					$user_id = $this->user_model->create( $post_user );	
					if(!empty($post_role)):
						foreach($post_role as $role):
							$insert_role = [
								'User_ID' => $user_id,
								'Group_ID' => $role
							];
							$this->user_group_model->create( $insert_role );	
						endforeach;
					endif;
				
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
					//$this->db->trans_rollback();
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
		$this->data['role_collection'] = $this->group_model->get_all(['Status' => 1]);
		$this->data['role_selected'] = [0];
		$this->data['form_action'] = current_url();
	
		$this->template
			->title(lang('heading:user_create'),lang('heading:auth'))
			->set_breadcrumb(lang('heading:auth') )
			->set_breadcrumb(lang('heading:user_list'), site_url($this->nameroutes))
			->set_breadcrumb(lang('heading:user_create'))
			->build("user/form", $this->data);
	}
	
	public function update( $id )
	{
		$this->data['item'] = $item = $this->user_model->get_one($id);
		
		if( $this->input->post() ) 
		{			
			$post_user = $this->input->post("f");
			$post_role = $this->input->post("role");
			$old_password = $this->input->post("c[OldPasswordWeb]");
			$confirm_password = $this->input->post("c[PasswordWeb]");
			
			if( !empty($post_user['PasswordWeb']) )
			{
				$isPasswordCorrect = password_verify($old_password, $item->PasswordWeb);
				if( ! $isPasswordCorrect )	
				{
					response_json(['status' => 'error', 'message' => 'Password Lama tidak Valid!']);	
				}
				if ($post_user['PasswordWeb'] === $confirm_password)
				{
					$post_user['PasswordWeb'] = password_hash($post_user['PasswordWeb'], PASSWORD_DEFAULT);		
				} else {
					response_json(['status' => 'error', 'message' => 'Password Konfirmasi tidak sama!']);	
				}
			} else {
				unset($post_user['PasswordWeb']);
			}
			
			$this->load->library('form_validation');
			$this->form_validation->set_rules(($item->Username != $post_user['Username']) ? $this->user_model->rules['update'] : $this->user_model->rules['update_unique']);
			$this->form_validation->set_data($post_user);
			if( $this->form_validation->run() )
			{								
				$this->db->trans_begin();
				
					$this->user_model->update( $post_user, $id );	
				
					$role_selected = $this->user_group_model->get_role_id_by(['User_ID' => $id]);	
					foreach($post_role as $role):
						if(! in_array($role, $role_selected)):
							$insert_role = [
								'User_ID' => $id,
								'Group_ID' => $role
							];
							$this->user_group_model->create( $insert_role );	
						endif;
					endforeach;
					
					$delete_by = "User_ID = {$id} AND Group_ID NOT IN (". implode(',', $post_role) .")";
					$this->user_group_model->delete_by($delete_by);
					
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
		$this->data['role_selected'] = $this->user_group_model->get_role_id_by(['User_ID' => $id]);
		$this->data['role_collection'] = $this->group_model->get_all(['Status' => 1]);
		$this->data['form_action'] = current_url();

		$this->template
			->title(lang('heading:user'),lang('heading:auth'))
			->set_breadcrumb(lang('heading:auth') )
			->set_breadcrumb(lang('heading:user_list'), site_url($this->nameroutes))
			->set_breadcrumb(lang('heading:user_update'))
			->build("user/form", $this->data);
	}
	
	public function delete( $id )
	{
		$this->data['item'] = $item = $this->user_model->get_one($id);
		
		if ($item  && (1 == $this->input->post('confirm') ) )
		{ 

			$this->db->trans_begin();
				
				$this->user_model->delete( $id );

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
		$this->load->view('user/modal/delete', $this->data);
	}
	
	public function lookup_data( $view, $is_ajax_request=false )
	{	
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view("user/lookup/{$view}");
		}
	}
	
	public function dropdown_html( $parent_id=0 )
	{
		if( $this->input->is_ajax_request() )
		{
			$parent_id = ($parent_id == 0) ? $this->input->get_post('parent_id') : $parent_id;
			
			$collection = array();
			$collection = $this->user_model->dropdown_html( ['GroupJasa' => $parent_id] );
		
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
		
		$db_from = "{$this->user_model->table} a";
		$db_where = array();
		$db_like = array();
		
		//prepare defautl flter

		// preparing default
		if( isset($search['value']) && ! empty($search['value']) )
        {
            $keywords = $this->db->escape_str( $search['value'] );
			
			$db_like[ $this->db->escape_str("a.Username") ] = $keywords;
			$db_like[ $this->db->escape_str("a.Nama_Asli") ] = $keywords;
			$db_like[ $this->db->escape_str("a.Nama_Singkat") ] = $keywords;
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
			a.User_ID,
			a.Username,
			a.Nama_Asli,
			a.Nama_Singkat,
			a.Status_Aktif
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

