<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Room_status extends ADMIN_Controller
{
	protected $nameroutes = 'others/room_status';
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('admin');
		
		$this->data['nameroutes'] = $this->nameroutes; 
		
		$this->load->language('others');		
		$this->load->helper('others');
		$this->load->model('room_status_model');
		$this->load->model('room_model');
		$this->load->model('section_model');
	}
	
	public function index()
	{

		$this->template
			->title(lang('heading:room_status'), lang('heading:others'))
			->set_breadcrumb(lang('heading:others') )
			->set_breadcrumb(lang('heading:room_status_list'), site_url($this->nameroutes))
			->build("room_status/datatable", $this->data);
	}
	
	public function create()
	{
		$item = (object) [
			'NoBukti' => date('dmy'). 'ROU-'. gen_unique_code(),
			'NoKamar' => NULL,
			'SectionID' => 0,
			'Status' => 'VC',
			'StartDate' => date('Y-m-d'),
			'Keterangan' => NULL,
			'Tanggal' => date('Y-m-d'),
			'Jam' => date('Y-m-d H:i:s'),
			'UserID' => $this->user_auth->User_ID,
		];
		
		if( $this->input->post() ) 
		{
			$post_room_status = array_merge( (array) $item, $this->input->post("f") );
			
			$this->load->library('form_validation');
			$this->form_validation->set_rules($this->room_status_model->rules['insert']);
			$this->form_validation->set_data($post_room_status);
			if( $this->form_validation->run() )
			{							
				$this->db->trans_begin();
							
					$this->room_status_model->create( $post_room_status );							
					$this->room_model->update(['Status' => $post_room_status['Status']], $post_room_status['NoKamar']);
										
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
		$this->data['dropdown_status'] = $this->room_status_model->dropdown_static();
		$this->data['form_action'] = current_url();
	
		$this->template
			->title(lang('heading:room_status_create'),lang('heading:others'))
			->set_breadcrumb(lang('heading:room_status_list'), site_url($this->nameroutes))
			->set_breadcrumb(lang('heading:room_status_create'))
			->build("room_status/form", $this->data);
	}
	
	public function update( $id )
	{
		$this->data['item'] = $item = $this->room_status_model->get_one($id);
		
		if( $this->input->post() ) 
		{			
			$post_room_status = $this->input->post("f");
			
			$this->load->library('form_validation');
			$this->form_validation->set_rules($this->room_status_model->rules['update']);
			$this->form_validation->set_data($post_room_status);
			if( $this->form_validation->run() )
			{								
				$this->db->trans_begin();
				
					$this->room_status_model->update( $post_room_status, $id );	
					$this->room_model->update(['Status' => $post_room_status['Status']], $item->NoKamar);
					
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
		$this->data['sal'] = $this->section_model->get_one($item->SectionID);
		$this->data['dropdown_status'] = $this->room_status_model->dropdown_static();
		$this->data['form_action'] = current_url();

		$this->template
			->title(lang('heading:room_status'),lang('heading:others'))
			->set_breadcrumb(lang('heading:room_status_list'), site_url($this->nameroutes))
			->set_breadcrumb(lang('heading:room_status_update'))
			->build("room_status/form", $this->data);
	}
	
	public function delete( $id )
	{
		$this->data['item'] = $item = $this->room_status_model->get_one($id);
		
		if ($item  && (1 == $this->input->post('confirm') ) )
		{ 

			$this->db->trans_begin();
				
				$this->room_status_model->delete( $id );

			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
				response_json(["status" => 'error', 'message' => lang('global:deleted_failed'), 'success' => FALSE]);
			} else
			{
				$this->db->trans_commit();
				response_json(["status" => 'success', 'message' => lang('global:deleted_successfully'), 'success' => TRUE]);
			}
		} 
		
		$this->data['form_action'] = $form_action = current_url();
		$this->load->view('room_status/modal/delete', $this->data);
	}
	
	public function lookup_data( $view, $is_ajax_request=false )
	{	
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view("room_status/lookup/{$view}");
		}
	}
	
	public function dropdown_html( $parent_id=0 )
	{
		if( $this->input->is_ajax_request() )
		{
			$parent_id = ($parent_id == 0) ? $this->input->get_post('parent_id') : $parent_id;
			
			$collection = array();
			$collection = $this->room_status_model->dropdown_html( ['GroupJasa' => $parent_id] );
		
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
		
		$db_from = "{$this->room_status_model->table} a";
		$db_where = array();
		$db_like = array();
		
		//prepare defautl flter

		// preparing default
		if( isset($search['value']) && ! empty($search['value']) )
        {
            $keywords = $this->db->escape_str( $search['value'] );
			
			$db_like[ $this->db->escape_str("a.{$this->room_status_model->index_key}") ] = $keywords;
			$db_like[ $this->db->escape_str("a.NoKamar") ] = $keywords;
			$db_like[ $this->db->escape_str("b.SectionName") ] = $keywords;
			$db_like[ $this->db->escape_str("a.Jam") ] = $keywords;
			$db_like[ $this->db->escape_str("a.Keterangan") ] = $keywords;
			$db_like[ $this->db->escape_str("a.StartDate") ] = $keywords;
        }
		
		// get total records
		$this->db->from( $db_from );
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		$records_total = $this->db->count_all_results();
		
		// get total filtered
		$this->db
			->from( $db_from )
			->join("{$this->section_model->table} b", "a.SectionID = b.SectionID", "LEFT OUTER")
			;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }		
		$records_filtered = $this->db->count_all_results();
		
		// get result filtered
		$db_select = <<<EOSQL
			a.NoKamar, 
			b.SectionName, 
			a.NoBukti, 
			a.Jam,
			a.Status, 
			a.Keterangan, 
			a.StartDate
EOSQL;

		$this->db
			->select( $db_select )
			->from( $db_from )
			->join("{$this->section_model->table} b", "a.SectionID = b.SectionID", "LEFT OUTER")
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
		
		$room_status = $this->room_status_model->dropdown_static();
		foreach($result as $row)
        {      
			$row->Status = @$room_status[$row->Status] ? $room_status[$row->Status] : 'NONE';     
			$output['data'][] = $row;
        }
		
		response_json( $output );
    }
}

