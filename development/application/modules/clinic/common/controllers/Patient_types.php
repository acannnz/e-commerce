<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Patient_types extends Admin_Controller
{
	protected $_translation = 'common';	
	protected $_model = 'patient_type_m';
	
	public function __construct()
	{
		parent::__construct();
		
		$this->page = "common_patient_types";
		$this->template->title( lang("patient_types:page") . ' - ' . $this->config->item('company_name') );
	}
	
	public function index()
	{
		$data = array(
				'page' => $this->page,
				"form" => TRUE,
				'datatables' => TRUE,
			);
		
		$this->template
			->set( "heading", lang("patient_types:page") )
			->set_breadcrumb( lang("common:page"), base_url("common") )
			->set_breadcrumb( lang("patient_types:breadcrumb") )
			->build('patient/types/datatable', (isset($data) ? $data : NULL));
	}
	
	public function create()
	{
		$item_data = array(
				'id' => 0,
				'code' => null,
				'type_name' => null,
				'type_description' => null,
				'state' => 1,
			);
		
		$this->load->library( 'my_object', $item_data, 'item' );
		
		if( $this->input->post() ) 
		{
			
			
			$this->load->library( 'form_validation' );
			
			$this->item->addData( $this->input->post("f") );
			$this->form_validation->set_rules( $this->get_model()->rules['insert'] );
			$this->form_validation->set_data( $this->item->toArray() );
			
			if( $this->form_validation->run() )
			{
				if( $this->get_model()->insert( $this->item->toArray() ) )
				{
					$this->get_model()->delete_cache( 'common_services.collection' );
					
					make_flashdata(array(
							'response_status' => 'success',
							'message' => lang('global:created_successfully')
						));
						
					redirect( 'common/patient-types' );
				} else
				{
					make_flashdata(array(
							'response_status' => 'error',
							'message' => lang('global:created_failed')
						));
				}
			} else
			{
				make_flashdata(array(
						'response_status' => 'error',
						'message' => $this->form_validation->get_all_error_string()
					));
			}
		}
		
		if( $this->input->is_ajax_request() )
		{
			$this->load->view( 
					'patient/types/modal/create_edit', 
					array('form_child' => $this->load->view('patient/types/form', array('item' => $this->item, 'is_modal' => TRUE), true))
				);
		} else
		{
			$data = array(
					"page" => $this->page,
					"item" => $this->item,
					"form" => TRUE,
				);
			
			$this->template
				->set( "heading", lang("patient_types:create_heading") )
				->set_breadcrumb( lang("common:page"), base_url("common") )
				->set_breadcrumb( lang("patient_types:breadcrumb"), base_url("common/patient-types") )
				->set_breadcrumb( lang("patient_types:create_heading") )
				->build('patient/types/form', $data);
		}
	}
	
	public function edit( $id=0 )
	{
		$id = (int) @$id;
		
		$item = $this->get_model()->as_array()->get( $id );
		if( ! $item ){ $item = array('id' => 0); }
		$this->load->library( 'my_object', $item, 'item' );
		
		if( $this->input->post() ) 
		{
			
			
			$this->load->library( 'form_validation' );
			
			$this->item->addData( $this->input->post("f") );
			$this->form_validation->set_rules( $this->get_model()->rules['insert'] );
			$this->form_validation->set_data( $this->item->toArray() );
			
			if( $this->form_validation->run() )
			{
				if( $this->get_model()->update( $this->item->toArray(), @$id ) )
				{
					$this->get_model()->delete_cache( 'common_services.collection' );
					
					make_flashdata(array(
							'response_status' => 'success',
							'message' => lang('global:updated_successfully')
						));
						
					redirect( 'common/patient-types' );
				} else
				{
					make_flashdata(array(
							'response_status' => 'error',
							'message' => lang('global:updated_failed')
						));
				}
			} else
			{
				make_flashdata(array(
						'response_status' => 'error',
						'message' => $this->form_validation->get_all_error_string()
					));
			}
		}
		
		if( $this->input->is_ajax_request() )
		{
			$this->load->view( 
					'patient/types/modal/create_edit', 
					array('form_child' => $this->load->view('patient/types/form', array('item' => $this->item, 'is_modal' => TRUE), true))
				);
		} else
		{
			$data = array(
					"page" => $this->page,
					"item" => $this->item,
					"form" => TRUE,
				);
			
			$this->template
				->set( "heading", lang("patient_types:edit_heading") )
				->set_breadcrumb( lang("common:page"), base_url("common") )
				->set_breadcrumb( lang("patient_types:breadcrumb"), base_url("common/patient-types") )
				->set_breadcrumb( lang("patient_types:edit_heading") )
				->build('patient/types/form', $data);
		}
	}
	
	public function delete( $id=0 )
	{
		$id = (int) @$id;
		
		$item = $this->get_model()->as_array()->get( $id );
		if( ! $item ){ $item = array('id' => 0); }
		$this->load->library( 'my_object', $item, 'item' );
		
		if( $this->input->post() ) 
		{
			
			
			if( 0 == @$this->item->id )
			{
				make_flashdata(array(
						'response_status' => 'error',
						'message' => lang( 'global:get_failed' )
					));
			
				redirect( $this->input->post( 'r_url' ) );
			}
			
			if( $this->item->id == $this->input->post( 'confirm' ) )
			{
				$this->get_model()->where( $id )->delete();				
				
				$this->get_model()->delete_cache( 'common_services.collection' );
				
				make_flashdata(array(
						'response_status' => 'success',
						'message' => lang('global:deleted_successfully')
					));
			}
			
			redirect( $this->input->post( 'r_url' ) );
		}
		
		$this->load->view( 'patient/types/modal/delete', array('item' => $this->item) );
	}
	
	public function datatable_collection()
    {
        $start = $this->input->get_post('start', true);
        $length = $this->input->get_post('length', true);
        $order = $this->input->get_post('order', true);
        $columns = $this->input->get_post('columns', true);
        $search = $this->input->get_post('search', true);
        $draw = $this->input->get_post('draw', true);
		
		$params = array(
				"start" => $start,
				"length" => $length,
				"columns" => $columns,
				"search" => $search,
				"draw" => $draw,
			);
		
		// Total data set length
		$records_total = $this->_records_total( $params );
		// Data set length after filtering
		$records_filtered = $this->_records_filtered( $params );
		
		$from_table = $this->get_model()->table;
		$this->db
			->select( "id, code, type_name, type_description, state, created_at, updated_at" )
			->from( $from_table )
			;
		
		/* 
         * Filtering
         * NOTE this does not match the built-in DataTables filtering which does it
         * word by word on any field. It's possible to do here, but concerned about efficiency
         * on very large tables, and MySQL's regex functionality is very limited
         */
		 
		$this->db->group_start();
		$this->db->where('deleted_at', NULL);
		$this->db->group_end();
		 
        if( isset($search['value']) && ! empty($search['value']) )
        {
            $search_fields = array();
			
			$this->db->group_start();
			
			for($i=0; $i<count($columns); $i++)
            {
                // Individual column filtering
                if( isset($columns[$i]['searchable']) && $columns[$i]['searchable'] == 'true')
                {
					$column_name = $columns[$i]['data'];
					$column_value = $search['value'];
					
                    $this->db->or_like( $column_name, "%{$column_value}%" );
				}
            }
			
			$this->db->group_end();
        }
		
        // Ordering
        if( isset($order) )
        {
            $sort_column = $order[0]['column'];
			$sort_dir = $order[0]['dir'];
			
			if( $columns[$sort_column]['orderable'] == 'true' )
			{
				$this->db->order_by( $columns[intval($this->db->escape_str($sort_column))]['data'], $this->db->escape_str($sort_dir) );
			}
        }
		
		// Paging
        if( isset($start) && $length != '-1')
        {
            $this->db->limit( $length, $start );
        }
		
		// Select Data
        $result = $this->db
			->get()
			->result()
			//->result_array()
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
			$row->created_at = @strftime(config_item('date_format'), @$row->created_at);
			$row->updated_at = @strftime(config_item('date_format'), @$row->updated_at);
			
            $output['data'][] = $row;
        }
		
		//print_r($output);exit;
		
		$this->template
			->build_json( $output );
    }
	
	private function _records_total( $params )
	{
		@extract( $params, EXTR_OVERWRITE );
		
		$from_table = $this->get_model()->table;
		
		$this->db
			->select( "*" )
			->from( $from_table )
			;
		
		/* 
         * Filtering
         * NOTE this does not match the built-in DataTables filtering which does it
         * word by word on any field. It's possible to do here, but concerned about efficiency
         * on very large tables, and MySQL's regex functionality is very limited
         */		 
		//$this->db->group_start();
		$this->db->where('deleted_at', NULL);
		//$this->db->group_end();
		
		// Total data set length
		$records_total = $this->db->count_all_results();
		
       	return (int) $records_total;
	}
	
	private function _records_filtered( $params )
	{
		@extract( $params, EXTR_OVERWRITE );
		
		$from_table = $this->get_model()->table;
		
		$this->db
			->select( "*" )
			->from( $from_table )
			;
		
		/* 
         * Filtering
         * NOTE this does not match the built-in DataTables filtering which does it
         * word by word on any field. It's possible to do here, but concerned about efficiency
         * on very large tables, and MySQL's regex functionality is very limited
         */		 
		$this->db->group_start();
		$this->db->where('deleted_at', NULL);
		$this->db->group_end();
		
		if( isset($search['value']) && ! empty($search['value']) )
        {
            $search_fields = array();
			
			$this->db->group_start();
			
			for($i=0; $i<count($columns); $i++)
            {
                // Individual column filtering
                if( isset($columns[$i]['searchable']) && $columns[$i]['searchable'] == 'true')
                {
					$column_name = $columns[$i]['data'];
					$column_value = $search['value'];
					
                    $this->db->or_like( $column_name, "%{$column_value}%" );
				}
            }
			
			$this->db->group_end();
        }
		
		// Total data set length
		$records_filtered = $this->db->count_all_results();
		
       	return (int) $records_filtered;
	}
}

