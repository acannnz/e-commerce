<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Room extends ADMIN_Controller
{
	protected $nameroutes = 'others/room';
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('admin');
		
		$this->data['nameroutes'] = $this->nameroutes; 
		
		$this->load->language('others');		
		$this->load->helper('others');
		$this->load->model('room_model');
		$this->load->model('room_status_model');
		$this->load->model('section_model');
		$this->load->model('class_model');
	}
	
	public function index()
	{

		$this->template
			->title(lang('heading:room'), lang('heading:others'))
			->set_breadcrumb(lang('heading:others') )
			->set_breadcrumb(lang('heading:room_list'), site_url($this->nameroutes))
			->build("room/datatable", $this->data);
	}
	
	public function create()
	{
		$item = (object) [
			'NoKamar' => NULL,
			'SalID' => 0,
			'NoLantai' => 1,
			'KelasID' => 0,
			'Tambahan' => 0,
			'DipakaiBOR' => 0,
			'JmlBed' => 1,
			'Status' => 'VC'
		];
		
		if( $this->input->post() ) 
		{
			$post_room = array_merge( (array) $item, $this->input->post("f") );
			
			$this->load->library('form_validation');
			$this->form_validation->set_rules($this->room_model->rules['insert']);
			$this->form_validation->set_data($post_room);
			if( $this->form_validation->run() )
			{							
				$this->db->trans_begin();
							
					$this->room_model->create( $post_room );							
										
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
		$this->data['dropdown_sal'] = $this->section_model->dropdown_data(['StatusAktif' => 1, 'TipePelayanan' => 'RI']);
		$this->data['dropdown_class'] = $this->class_model->dropdown_data(['Active' => 1]);
		$this->data['form_action'] = current_url();
	
		$this->template
			->title(lang('heading:room_create'),lang('heading:others'))
			->set_breadcrumb(lang('heading:room_list'), site_url($this->nameroutes))
			->set_breadcrumb(lang('heading:room_create'))
			->build("room/form", $this->data);
	}
	
	public function update( $id )
	{
		$this->data['item'] = $item = $this->room_model->get_one($id);
		
		if( $this->input->post() ) 
		{			
			$post_room = $this->input->post("f");
			
			$this->load->library('form_validation');
			if($post_room['NoKamar'] == $item->NoKamar)
			{
				$this->form_validation->set_rules($this->room_model->rules['update']);
			} else {
				$this->form_validation->set_rules($this->room_model->rules['update_unique']);
			}
			$this->form_validation->set_data($post_room);
			if( $this->form_validation->run() )
			{								
				$this->db->trans_begin();
				
					$this->room_model->update( $post_room, $id );	
					
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
		$this->data['dropdown_sal'] = $this->section_model->dropdown_data(['StatusAktif' => 1, 'TipePelayanan' => 'RI']);
		$this->data['dropdown_class'] = $this->class_model->dropdown_data(['Active' => 1]);
		$this->data['form_action'] = current_url();

		$this->template
			->title(lang('heading:room'),lang('heading:others'))
			->set_breadcrumb(lang('heading:room_list'), site_url($this->nameroutes))
			->set_breadcrumb(lang('heading:room_update'))
			->build("room/form", $this->data);
	}
	
	public function delete( $id )
	{
		$this->data['item'] = $item = $this->room_model->get_one($id);
		
		if ($item  && (1 == $this->input->post('confirm') ) )
		{ 

			$this->db->trans_begin();
				
				$this->room_model->delete( $id );

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
		$this->load->view('room/modal/delete', $this->data);
	}
	
	public function lookup_data( $view, $is_ajax_request=false )
	{	
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view("room/lookup/{$view}");
		}
	}
	
	public function dropdown_html( $parent_id=0 )
	{
		if( $this->input->is_ajax_request() )
		{
			$parent_id = ($parent_id == 0) ? $this->input->get_post('parent_id') : $parent_id;
			
			$collection = array();
			$collection = $this->room_model->dropdown_html( ['GroupJasa' => $parent_id] );
		
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
		
		$db_from = "{$this->room_model->table} a";
		$db_where = array();
		$db_like = array();
		
		//prepare defautl flter

		// preparing default
		if( isset($search['value']) && ! empty($search['value']) )
        {
            $keywords = $this->db->escape_str( $search['value'] );
			
			$db_like[ $this->db->escape_str("a.{$this->room_model->index_key}") ] = $keywords;
			$db_like[ $this->db->escape_str("a.NoKamar") ] = $keywords;
			$db_like[ $this->db->escape_str("b.SectionName") ] = $keywords;
			$db_like[ $this->db->escape_str("a.JmlBed") ] = $keywords;
			$db_like[ $this->db->escape_str("a.NoLantai") ] = $keywords;
			$db_like[ $this->db->escape_str("c.NamaKelas") ] = $keywords;
        }
		
		// get total records
		$this->db->from( $db_from );
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		$records_total = $this->db->count_all_results();
		
		// get total filtered
		$this->db
			->from( $db_from )
			->join("{$this->section_model->table} b", "a.SalID = b.SectionID", "LEFT OUTER")
			->join("{$this->class_model->table} c", "a.KelasID = c.KelasID", "LEFT OUTER")
			;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }		
		$records_filtered = $this->db->count_all_results();
		
		// get result filtered
		$db_select = <<<EOSQL
			a.*, 
			b.SectionName, 
			c.NamaKelas
EOSQL;

		$this->db
			->select( $db_select )
			->from( $db_from )
			->join("{$this->section_model->table} b", "a.SalID = b.SectionID", "LEFT OUTER")
			->join("{$this->class_model->table} c", "a.KelasID = c.KelasID", "LEFT OUTER")
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

