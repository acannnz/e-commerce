<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Specialist extends ADMIN_Controller
{
	protected $nameroutes = 'vendor/specialist';
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('admin');
		
		$this->data['nameroutes'] = $this->nameroutes; 
		
		$this->load->language('vendor');		
		$this->load->helper('vendor');
		$this->load->model('specialist_model');
		$this->load->model('sub_specialist_model');
	}
	
	public function index()
	{
		$this->template
			->title(lang('heading:vendor_specialist'), lang('heading:vendor'))
			->set_breadcrumb(lang('heading:vendor') )
			->set_breadcrumb(lang('heading:vendor_specialist_list'), site_url($this->nameroutes))
			->build("specialist/datatable", $this->data);
	}
	
	public function create()
	{
		$item = (object) [
			'SpesialisName' => NULL,
		];
		
		if( $this->input->post() ) 
		{
			$post_specialist = array_merge( (array) $item, $this->input->post("specialist") );
			$post_sub_specialist = $this->input->post("sub_specialist");
			
			$this->load->library('form_validation');
			$this->form_validation->set_rules($this->specialist_model->rules['insert']);
			$this->form_validation->set_data($post_specialist);
			if( $this->form_validation->run() )
			{							
				$this->db->trans_begin();
							
					$specialist_id = $this->specialist_model->create( $post_specialist );							
					
					foreach($post_sub_specialist as $sub):
						$sub['SpesialisID'] = $specialist_id;
						$sub['SubSpesialisID'] = vendor_helper::gen_sub_specialist_number();
						$this->sub_specialist_model->create( $sub );							
					endforeach;
										
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
		$this->data['add_sub_specialist'] = base_url('vendor/sub_specialist/form');
			
		$this->template
			->title(lang('heading:vendor_specialist_create'),lang('heading:vendor'))
			->set_breadcrumb(lang('heading:vendor_specialist_list'), site_url($this->nameroutes))
			->set_breadcrumb(lang('heading:vendor_specialist_create'))
			->build("specialist/form", $this->data);
	}
	
	public function update($id = 0)
	{
		$this->data['item'] = $item = $this->specialist_model->get_one($id);
		
		if( $this->input->post() ) 
		{			
			$post_specialist = $this->input->post("specialist");
			$post_sub_specialist = $this->input->post("sub_specialist");
			
			$this->load->library('form_validation');
			$this->form_validation->set_rules($this->specialist_model->rules['update']);
			$this->form_validation->set_data($post_specialist);
			if( $this->form_validation->run() )
			{								
				$this->db->trans_begin();
									
					$delete_not_in = [];
					foreach($post_sub_specialist as $sub):					
						if($sub['SubSpesialisID'] == '-'):
							$sub['SpesialisID'] = $id;
							$sub['SubSpesialisID'] = vendor_helper::gen_sub_specialist_number();
							$this->sub_specialist_model->create( $sub );	
							
							$delete_not_in[] = $sub['SubSpesialisID'];
						else :
							$this->sub_specialist_model->update( $sub, $sub['SubSpesialisID'] );
							$delete_not_in[] = $sub['SubSpesialisID'];
						endif;
					endforeach;
					
					if(!empty($delete_not_in))
						$this->db->where('SpesialisID', $id)
								->where_not_in('SubSpesialisID', $delete_not_in)
								->delete($this->sub_specialist_model->table);
					
					$this->specialist_model->update( $post_specialist, $id );	
					
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
		$this->data['sub_specialist_collection'] = $this->sub_specialist_model->get_all(NULL, 0, ['SpesialisID' => $id]);
		$this->data['add_sub_specialist'] = base_url('vendor/sub_specialist/form');
		
		$this->template
			->title(lang('heading:vendor_specialist'),lang('heading:vendor'))
			->set_breadcrumb(lang('heading:vendor_specialist_list'), site_url($this->nameroutes))
			->set_breadcrumb(lang('heading:vendor_specialist_update'))
			->build("specialist/form", $this->data);
	}
	
	public function delete($id = 0)
	{
		$this->data['item'] = $item = $this->specialist_model->get_one($id);
		
		if ($item  && (1 == $this->input->post('confirm') ) )
		{ 

			$this->db->trans_begin();
				
				$this->specialist_model->delete( $id );

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
		$this->load->view('specialist/modal/delete', $this->data);
	}
	
	private function do_upload( $personal_picture = NULL )
	{
			$config['upload_path'] = realpath(FCPATH . '../../assets/specialist-vendor/photos');
			$config['allowed_types'] = 'jpeg|jpg|png';
			$config['max_size'] = 0;
			$config['max_width'] = 0;
			$config['max_height'] = 0;
			$config['remove_spaces'] = TRUE;
			$config['encrypt_name'] = TRUE;
			$config['overwrite'] = TRUE;
			
			if( $personal_picture && file_exists( realpath(FCPATH . "../../assets/specialist-vendor/photos/{$personal_picture}") ) )
			{
				$config['encrypt_name'] = FALSE;
				$config['file_name'] = $personal_picture;
			}

			$this->load->library('upload', $config);

			return ( ! $this->upload->do_upload('PersonalPicture') )
				? ['status'=>'error', 'message' => $this->upload->display_errors()]
				: ['status'=>'success', 'upload_data' => $this->upload->data()];
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
		
		$db_from = "{$this->specialist_model->table} a";
		$db_where = array();
		$db_like = array();
		
		//prepare defautl flter

		// preparing default
		if( isset($search['value']) && ! empty($search['value']) )
        {
            $keywords = $this->db->escape_str( $search['value'] );
			
			$db_like[ $this->db->escape_str("a.SpesialisID") ] = $keywords;
			$db_like[ $this->db->escape_str("a.SpesialisName") ] = $keywords;

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

