<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Zones extends Admin_Controller
{
	protected $_translation = 'common';	
	protected $_model = 'zone_m';
	
	public function __construct()
	{
		parent::__construct();
		
		$this->page = "common_zones";
		$this->template->title( lang("zones:page") . ' - ' . $this->config->item('company_name') );
	}
	
	public function index( $zone_type="country", $parent_id=0 )
	{
		$item = $this->get_model()->as_array()->get( $parent_id );
		if( ! $item ){ $item = array('id' => 0); }
		$this->load->library( 'my_object', $item, 'item' );
		
		$data = array(
				'page' => sprintf("%s_%s", $this->page, $zone_type),
				"form" => TRUE,
				'datatables' => TRUE,
				'zone_type' => $zone_type,
				'item' => $this->item,
			);
		
		switch( $zone_type )
		{
			case "country":
				$this->template->set( "heading", lang("zones:countries_heading") );
				
				$this->template
					->set_breadcrumb( lang("common:page"), base_url("common") )
					->set_breadcrumb( lang("zones:breadcrumb"), base_url("common/zones/country") )
					->set_breadcrumb( lang("zones:countries_breadcrumb") )
					;
				break;
			case "province":
				//$this->template->set( "heading", lang("zones:provinces_heading") );
				$this->template->set( "heading", ((0 == $this->item->id) ? lang("zones:provinces_heading") : sprintf("%s %s %s", lang("zones:countries_heading"), lang("zones:at"), $this->item->zone_name)) );
				
				$this->template
					->set_breadcrumb( lang("common:page"), base_url("common") )
					->set_breadcrumb( lang("zones:breadcrumb"), base_url("common/zones/country") )
					->set_breadcrumb( lang("zones:countries_breadcrumb"), base_url("common/zones/country") )
					->set_breadcrumb( lang("zones:provinces_breadcrumb") )
					;
				break;
			case "county":
				//$this->template->set( "heading", lang("zones:counties_heading") );
				$this->template->set( "heading", ((0 == $this->item->id) ? lang("zones:counties_heading") : sprintf("%s %s %s", lang("zones:counties_heading"), lang("zones:at"), $this->item->zone_name)) );
				
				$this->template
					->set_breadcrumb( lang("common:page"), base_url("common") )
					->set_breadcrumb( lang("zones:breadcrumb"), base_url("common/zones/country") )
					->set_breadcrumb( lang("zones:countries_breadcrumb"), base_url("common/zones/country") )
					->set_breadcrumb( lang("zones:provinces_breadcrumb"), base_url("common/zones/province") )
					->set_breadcrumb( lang("zones:counties_breadcrumb"))
					;
				break;
			case "district":
				//$this->template->set( "heading", lang("zones:districts_heading") );
				$this->template->set( "heading", ((0 == $this->item->id) ? lang("zones:districts_heading") : sprintf("%s %s %s", lang("zones:districts_heading"), lang("zones:at"), $this->item->zone_name)) );
				
				$this->template
					->set_breadcrumb( lang("common:page"), base_url("common") )
					->set_breadcrumb( lang("zones:breadcrumb"), base_url("common/zones/country") )
					->set_breadcrumb( lang("zones:countries_breadcrumb"), base_url("common/zones/country") )
					->set_breadcrumb( lang("zones:provinces_breadcrumb"), base_url("common/zones/province") )
					->set_breadcrumb( lang("zones:counties_breadcrumb"), base_url("common/zones/county") )
					->set_breadcrumb( lang("zones:districts_breadcrumb"))
					;
				break;
			case "area":
				//$this->template->set( "heading", lang("zones:areas_heading") );
				$this->template->set( "heading", ((0 == $this->item->id) ? lang("zones:areas_heading") : sprintf("%s %s %s", lang("zones:areas_heading"), lang("zones:at"), $this->item->zone_name)) );
				
				$this->template
					->set_breadcrumb( lang("common:page"), base_url("common") )
					->set_breadcrumb( lang("zones:breadcrumb"), base_url("common/zones/country") )
					->set_breadcrumb( lang("zones:countries_breadcrumb"), base_url("common/zones/country") )
					->set_breadcrumb( lang("zones:provinces_breadcrumb"), base_url("common/zones/province") )
					->set_breadcrumb( lang("zones:counties_breadcrumb"), base_url("common/zones/county") )
					->set_breadcrumb( lang("zones:districts_breadcrumb"), base_url("common/zones/district") )
					->set_breadcrumb( lang("zones:areas_breadcrumb"))
					;
				break;
		}
		
		$this->template
			->build("zones/datatable/{$zone_type}", (isset($data) ? $data : NULL));
	}
	
	public function create( $zone_type="country", $parent_id=0 )
	{
		$item_data = array(
				'id' => 0,
				'code' => null,
				'parent_id' => $parent_id,
				'zone_type' => strtoupper($zone_type),
				'zone_name' => null,
				'zone_island' => null,
				'zone_postcode' => null,
				'zone_description' => null,
				'state' => 1,
				'created_at' => null,
				'created_by' => 0,
				'updated_at' => null,
				'updated_by' => 0,
				'deleted_at' => null,
				'deleted_by' => 0,
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
						
					redirect( "common/zones/{$zone_type}" );
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
		
		$options_country = array();
		$options_province = array();
		$options_county = array();
		$options_district = array();
		
		$country_id = 0;
		$province_id = 0;
		$county_id = 0;
		$district_id = 0;
		
		switch( $zone_type )
		{
			case "country":
				//
				break;
			
			case "province":
				$options_country = $this->get_model()->options_country();
				
				break;
			
			case "county":
				$province_id = @$this->item->parent_id;
				$country_id = (int) @$this->get_model()->as_object()->get(array("parent_id" => $province_id))->id;
				
				$options_country = $this->get_model()->options_country();
				$options_province = $this->get_model()->options_province( $country_id );
				
				break;
			
			case "district":
				$county_id = @$this->item->parent_id;
				$province_id = (int) @$this->get_model()->as_object()->get(array("parent_id" => $county_id))->id;
				$country_id = (int) @$this->get_model()->as_object()->get(array("parent_id" => $province_id))->id;
				
				$options_country = $this->get_model()->options_country();
				$options_province = $this->get_model()->options_province( $country_id );
				$options_county = $this->get_model()->options_county( $province_id );
				
				break;
			
			case "area":
				$district_id = @$this->item->parent_id;
				$county_id = (int) @$this->get_model()->as_object()->get(array("parent_id" => $district_id))->id;
				$province_id = (int) @$this->get_model()->as_object()->get(array("parent_id" => $county_id))->id;
				$country_id = (int) @$this->get_model()->as_object()->get(array("parent_id" => $province_id))->id;
				
				$options_country = $this->get_model()->options_country();
				$options_province = $this->get_model()->options_province( $country_id );
				$options_county = $this->get_model()->options_county( $province_id );
				$options_district = $this->get_model()->options_district( $county_id );
				
				break;
		}
		
		if( $this->input->is_ajax_request() )
		{
			$data = array(
					"item" => $this->item,
					"options_country" => $options_country,
					"options_province" => $options_province,
					"options_county" => $options_county,
					"options_district" => $options_district,
					"country_id" => $country_id,
					"province_id" => $province_id,
					"county_id" => $county_id,
					"district_id" => $district_id,
					"is_ajax_request" => TRUE,
					"is_modal" => TRUE,
				);
			
			$this->load->view( 
					'zones/modal/create_edit', 
					array('form_child' => $this->load->view("zones/form/{$zone_type}", $data, true))
				);
		} else
		{
			$data = array(
					"page" => sprintf("%s_%s_%s", $this->page, $zone_type, strtolower(__FUNCTION__)),
					"item" => $this->item,
					"form" => TRUE,
					"options_country" => $options_country,
					"options_province" => $options_province,
					"options_county" => $options_county,
					"options_district" => $options_district,
					"country_id" => $country_id,
					"province_id" => $province_id,
					"county_id" => $county_id,
					"district_id" => $district_id,
				);
			
			$this->template
				->set( "heading", lang("zones:create_heading") )
				->set_breadcrumb( lang("common:page"), base_url("common") )
				->set_breadcrumb( lang("zones:breadcrumb"), base_url("common/zones") )
				->set_breadcrumb( lang("zones:create_heading") )
				->build("zones/form/{$zone_type}", $data);
		}
	}
	
	public function edit( $zone_type="country", $id=0 )
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
				$data = $this->input->post( "f" );
				$item_data = $this->item->addData( $data )->toArray();
				
				if( $this->get_model()->update( $item_data, @$id ) )
				{
					$this->get_model()->delete_cache( 'common_services.collection' );
					
					make_flashdata(array(
							'response_status' => 'success',
							'message' => lang('global:updated_successfully')
						));
						
					redirect( "common/zones/{$zone_type}" );
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
		
		$options_country = array();
		$options_province = array();
		$options_county = array();
		$options_district = array();
		
		$country_id = 0;
		$province_id = 0;
		$county_id = 0;
		$district_id = 0;
		
		switch( $zone_type )
		{
			case "country":
				//
				break;
			
			case "province":
				$options_country = $this->get_model()->options_country();
				
				break;
			
			case "county":
				$province_id = @$this->item->parent_id;
				$country_id = (int) @$this->get_model()->as_object()->get(array("id" => $province_id))->parent_id;
				
				$options_country = $this->get_model()->options_country();
				$options_province = $this->get_model()->options_province( $country_id );
				
				break;
			
			case "district":
				$county_id = @$this->item->parent_id;
				$province_id = (int) @$this->get_model()->as_object()->get(array("id" => $county_id))->parent_id;
				$country_id = (int) @$this->get_model()->as_object()->get(array("id" => $province_id))->parent_id;
				
				$options_country = $this->get_model()->options_country();
				$options_province = $this->get_model()->options_province( $country_id );
				$options_county = $this->get_model()->options_county( $province_id );
				
				break;
			
			case "area":
				$district_id = @$this->item->parent_id;
				$county_id = (int) @$this->get_model()->as_object()->get(array("id" => $district_id))->parent_id;
				$province_id = (int) @$this->get_model()->as_object()->get(array("id" => $county_id))->parent_id;
				$country_id = (int) @$this->get_model()->as_object()->get(array("id" => $province_id))->parent_id;
				
				$options_country = $this->get_model()->options_country();
				$options_province = $this->get_model()->options_province( $country_id );
				$options_county = $this->get_model()->options_county( $province_id );
				$options_district = $this->get_model()->options_district( $county_id );
				
				break;
		}
		
		if( $this->input->is_ajax_request() )
		{
			$data = array(
					"item" => $this->item,
					"options_country" => $options_country,
					"options_province" => $options_province,
					"options_county" => $options_county,
					"options_district" => $options_district,
					"country_id" => $country_id,
					"province_id" => $province_id,
					"county_id" => $county_id,
					"district_id" => $district_id,
					"is_ajax_request" => TRUE,
					"is_modal" => TRUE,
				);
			
			$this->load->view( 
					'zones/modal/create_edit', 
					array('form_child' => $this->load->view("zones/form/{$zone_type}", $data, true))
				);
		} else
		{
			$data = array(
					"page" => sprintf("%s_%s", $this->page, $zone_type),
					"item" => $this->item,
					"form" => TRUE,
					"options_country" => $options_country,
					"options_province" => $options_province,
					"options_county" => $options_county,
					"options_district" => $options_district,
					"country_id" => $country_id,
					"province_id" => $province_id,
					"county_id" => $county_id,
					"district_id" => $district_id,
				);
			
			$this->template
				->set( "heading", lang("zones:create_heading") )
				->set_breadcrumb( lang("common:page"), base_url("common") )
				->set_breadcrumb( lang("zones:breadcrumb"), base_url("common/zones") )
				->set_breadcrumb( lang("zones:create_heading") )
				->build("zones/form/{$zone_type}", $data);
		}
	}
	
	public function delete( $zone_type="country", $id=0 )
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
			
			if( 0 < $this->get_model()->count(array("parent_id" => @$this->item->id)) )
			{
				make_flashdata(array(
						'response_status' => 'error',
						'message' => lang( 'global:deleted_failed' )
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
		
		$this->load->view( 'zones/modal/delete', array('zone_type' => $zone_type, 'item' => $this->item) );
	}
	
	public function datatable_collection( $zone_type='country', $parent_id=0 )
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
				"zone_type" => $zone_type,
				"parent_id" => $parent_id,
			);
		
		// Total data set length
		$records_total = $this->_records_total( $params );
		// Data set length after filtering
		$records_filtered = $this->_records_filtered( $params );
		
		$from_table = $this->get_model()->table;
		$this->db
			->select( "id, code, parent_id, zone_type, zone_name, zone_postcode, zone_island, state, updated_at" )
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
		$this->db->where('zone_type', strtoupper($zone_type));
		if( $parent_id )
		{
			$this->db->where('parent_id', $parent_id);
		}
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
			$row->code = ($row->code != null) ? $row->code : "n/a";
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
			->select( "id, code, parent_id, zone_type, zone_name, state, updated_at" )
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
		$this->db->where('zone_type', strtoupper($zone_type));
		if( $parent_id )
		{
			$this->db->where('parent_id', $parent_id);
		}
		$this->db->group_end();
		
		// Total data set length
		$records_total = $this->db->count_all_results();
		
       	return (int) $records_total;
	}
	
	private function _records_filtered( $params )
	{
		@extract( $params, EXTR_OVERWRITE );
		
		$from_table = $this->get_model()->table;
		
		$this->db
			->select( "id, code, parent_id, zone_type, zone_name, state, updated_at" )
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
		$this->db->where('zone_type', strtoupper($zone_type));
		if( $parent_id )
		{
			$this->db->where('parent_id', $parent_id);
		}
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
	
	public function populate_country()
	{
		$item["populate"] = array();
		if( $result = $this->get_model()->options_country() )
		{
			foreach( $result as $value => $label )
			{
				array_push( $item["populate"], array("value" => @$value, "label" => @$label) );
			}
		}
		
		$this->template->build_json( $item );
	}
	
	public function populate_province( $parent_id=0 )
	{
		$item["populate"] = array();
		if( $result = $this->get_model()->options_province( $parent_id ) )
		{
			foreach( $result as $value => $label )
			{
				array_push( $item["populate"], array("value" => @$value, "label" => @$label) );
			}
		}
		
		$this->template->build_json( $item );
	}
	
	public function populate_county( $parent_id=0 )
	{
		$item["populate"] = array();
		if( $result = $this->get_model()->options_county( $parent_id ) )
		{
			foreach( $result as $value => $label )
			{
				array_push( $item["populate"], array("value" => @$value, "label" => @$label) );
			}
		}
		
		$this->template->build_json( $item );
	}
	
	public function populate_district( $parent_id=0 )
	{
		$item["populate"] = array();
		if( $result = $this->get_model()->options_district( $parent_id ) )
		{
			foreach( $result as $value => $label )
			{
				array_push( $item["populate"], array("value" => @$value, "label" => @$label) );
			}
		}
		
		$this->template->build_json( $item );
	}
	
	public function populate_area( $parent_id=0 )
	{
		$item["populate"] = array();
		if( $result = $this->get_model()->options_area( $parent_id ) )
		{
			foreach( $result as $value => $label )
			{
				array_push( $item["populate"], array("value" => @$value, "label" => @$label) );
			}
		}
		
		$this->template->build_json( $item );
	}
	
//	public function parser_zones()
//	{
//		$this->load->model( "zone_m", "country_m" );
//		$this->load->model( "zone_m", "province_m" );
//		$this->load->model( "zone_m", "county_m" );
//		$this->load->model( "zone_m", "district_m" );
//		$this->load->model( "zone_m", "village_m" );
//		
//		$this->province_m->table = "zone";
//		$this->county_m->table = "zone__cities";
//		$this->district_m->table = "zone__districts";
//		$this->village_m->table = "zone__areas";
//		
//		set_time_limit(0);
//		
//		
//		$_country_id = 100;
//		
//		$r_country = array(
//				'code' => 'ID',
//				'parent_id' => 0,
//				'zone_type' => 'COUNTRY',
//				'zone_name' => "Indonesia",
//				'zone_description' => "Indonesia Raya",
//				'state' => 1,
//			);
//		
//		if( ! $this->zone_m->count(array('zone_type' => 'COUNTRY', 'zone_name' => 'Indonesia',)) )
//		{
//			$country_id = $this->zone_m->insert( $r_country );
//		}
//		
//		$provinces = $this->province_m->get_all( array("country_id" => $_country_id) );
//		if( $provinces )
//		{
//			$i = 0;
//			foreach( $provinces as $province )
//			{
//				
//				
//				//sleep(2);
//				
//				$r_province = array(
//						'code' => $province->code,
//						'parent_id' => $_country_id,
//						'zone_type' => 'PROVINCE',
//						'zone_name' => $province->name,
//						'zone_description' => $province->name,
//						'state' => 1,
//					);
//					
//				if( $this->zone_m->count(array('zone_type' => 'PROVINCE', 'zone_name' => $province->name,)) )
//				{
//					continue;
//				}
//				
//				print "{$province->name}<br>";
//				
//				$i++;
//					
//				$province_id = $this->zone_m->insert( $r_province );
//					
//				// Render kabupaten
//				$counties = $this->county_m->get_all( array("zone_id" => $province->id) );
//				if( $counties )
//				{
//					foreach( $counties as $county )
//					{
//						$r_county = array(
//								'code' => @$county->code,
//								'parent_id' => $province_id,
//								'zone_type' => 'COUNTY',
//								'zone_name' => $county->city_name,
//								'zone_description' => $county->city_name,
//								'zone_island' => $county->city_island,
//								'state' => 1,
//							);
//						
//						$county_id = $this->zone_m->insert( $r_county );	
//						
//						// Render kecamatan
//						$districts = $this->district_m->get_all( array("city_id" => $county->id) );
//						if( $districts )
//						{
//							foreach( $districts as $district )
//							{
//								$r_district = array(
//										'code' => @$district->code,
//										'parent_id' => $county_id,
//										'zone_type' => 'DISTRICT',
//										'zone_name' => $district->district_name,
//										'zone_description' => $district->district_name,
//										'zone_island' => $county->city_island,
//										'state' => 1,
//									);
//									
//								$district_id = $this->zone_m->insert( $r_district );
//									
//								// Render desa
//								$villages = $this->village_m->get_all( array("district_id" => $district->id) );
//								if( $villages )
//								{
//									foreach( $villages as $village )
//									{
//										$r_village = array(
//												'code' => $village->code,
//												'parent_id' => $district_id,
//												'zone_type' => 'AREA',
//												'zone_name' => $village->area_name,
//												'zone_description' => $village->area_name,
//												'zone_postcode' => $village->area_postcode,
//												'zone_island' => $county->city_island,
//												'state' => 1,
//											);
//											
//										$this->zone_m->insert( $r_village );
//									}
//								}
//							}
//						}
//					}
//				}
//			
//				if( 5 == $i )
//				{
//					print "{$i}<br>";
//					break;
//				}
//			}
//		}
//		
//		exit();
//	}
}