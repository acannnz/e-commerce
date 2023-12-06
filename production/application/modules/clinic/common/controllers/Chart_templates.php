<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Chart_templates extends Admin_Controller
{
	protected $_translation = 'common';	
	protected $_model = 'chart_template_m';
	
	public function __construct()
	{
		parent::__construct();
		
		$this->load->helper( "chart_template" );
		
		$this->page = "common_chart_templates";
		$this->template->title( lang("chart_template:page") . ' - ' . $this->config->item('company_name') );
	}
	
	public function index()
	{
		$data = array(
				'page' => $this->page,
				'datatables' => TRUE,
			);
		
		$this->template
			->set( "heading", lang("chart_template:page") )
			->set_breadcrumb( lang("common:page"), base_url("common") )
			->set_breadcrumb( lang("chart_template:breadcrumb") )
			->build('chart/templates/datatable', (isset($data) ? $data : NULL));
	}
	
	public function create()
	{
		$item_data = array(
				'id' => 0,
				'chief_complaint' => null,
				'subjective' => null,
				'objective' => null,
				'assessment' => null,
				'plan' => null,
				'service_component_id' => 0,
				'product_component_id' => 0,
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
					$this->get_model()->delete_cache( 'chart_templates.collection' );
					
					make_flashdata(array(
							'response_status' => 'success',
							'message' => lang('global:created_successfully')
						));
						
					redirect( 'common/chart-templates' );
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
		
		$service_comp_options = array();
		$product_comp_options = array();
		
		$this->load->helper( "components/component_service" );
		$service_comp_options = component_service_helper::dropdown();
		
		if( 'TRUE' == $this->config->item( "enable_chart_drug" ) )
		{
			$this->load->helper( "components/component_product" );
			$product_comp_options = component_product_helper::dropdown();
		}
		
		if( $this->input->is_ajax_request() )
		{
			$data = array(
					"item" => $this->item,
					"service_comp_options" => $service_comp_options,
					"product_comp_options" => $product_comp_options,
					"form_action" => base_url("common/chart-templates/create"),
					"is_ajax_request" => TRUE,
					"is_modal" => TRUE,
				);
			
			$this->load->view( 
					'chart/templates/modal/create_edit', 
					array('form_child' => $this->load->view('chart/templates/form', $data, true))
				);
		} else
		{
			$data = array(
					"page" => $this->page."_".strtolower(__FUNCTION__),
					"item" => $this->item,
					"service_comp_options" => $service_comp_options,
					"product_comp_options" => $product_comp_options,
					"form" => TRUE,
					"form_action" => base_url("common/chart-templates/create"),
					"summernote" => TRUE,					
				);
			
			$this->template
				->set( "heading", lang("chart_template:create_heading") )
				->set_breadcrumb( lang("common:page"), base_url("common") )
				->set_breadcrumb( lang("chart_template:breadcrumb"), base_url("common/chart-templates") )
				->set_breadcrumb( lang("chart_template:create_heading") )
				->build('chart/templates/form', $data);
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
			
//			print_r($id);
//			print_r($this->input->post("f"));exit;
			
			$this->load->library( 'form_validation' );
			
			$this->item->addData( $this->input->post("f") );
			$this->form_validation->set_rules( $this->get_model()->rules['insert'] );
			$this->form_validation->set_data( $this->item->toArray() );
			
			if( $this->form_validation->run() )
			{
				if( $this->get_model()->update( $this->item->toArray(), @$id ) )
				{
					$this->get_model()->delete_cache( 'chart_templates.collection' );
					
					make_flashdata(array(
							'response_status' => 'success',
							'message' => lang('global:updated_successfully')
						));
						
					redirect( 'common/chart-templates' );
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
		
		$service_comp_options = array();
		$product_comp_options = array();
		
		$this->load->helper( "components/component_service" );
		$service_comp_options = component_service_helper::dropdown();
		
		if( 'TRUE' == $this->config->item( "enable_chart_drug" ) )
		{
			$this->load->helper( "components/component_product" );
			$product_comp_options = component_product_helper::dropdown();
		}
		
		if( $this->input->is_ajax_request() )
		{
			$data = array(
					"item" => $this->item,
					"service_comp_options" => $service_comp_options,
					"product_comp_options" => $product_comp_options,
					"form_action" => base_url("common/chart-templates/edit/{$id}"),
					"is_ajax_request" => TRUE,
					"is_modal" => TRUE,
				);
			
			$this->load->view( 
					'chart/templates/modal/create_edit', 
					array('form_child' => $this->load->view('chart/templates/form', $data, true))
				);
		} else
		{
			$data = array(
					"page" => $this->page,
					"item" => $this->item,
					"service_comp_options" => $service_comp_options,
					"product_comp_options" => $product_comp_options,
					"form" => TRUE,
					"form_action" => base_url("common/chart-templates/edit/{$id}"),
					"summernote" => TRUE,
				);
			
			$this->template
				->set( "heading", lang("chart_template:edit_heading") )
				->set_breadcrumb( lang("common:page"), base_url("common") )
				->set_breadcrumb( lang("chart_template:breadcrumb"), base_url("common/chart-templates") )
				->set_breadcrumb( lang("chart_template:edit_heading") )
				->build('chart/templates/form', $data);
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
			
			if( 1 == @$this->item->is_default )
			{
				make_flashdata(array(
						'response_status' => 'error',
						'message' => lang( 'chart_templates:delete_default_error' )
					));			
				redirect( $this->input->post( 'r_url' ) );
			}
			
			if( $this->item->id == $this->input->post( 'confirm' ) )
			{
				$this->get_model()->where( $id )->delete();				
				
				$this->get_model()->delete_cache( 'chart_templates.collection' );
				
				make_flashdata(array(
						'response_status' => 'success',
						'message' => lang('global:deleted_successfully')
					));
			}
			
			redirect( $this->input->post( 'r_url' ) );
		}
		
		$this->load->view( 'chart/templates/modal/delete', array('item' => $this->item) );
	}
	
	public function save_as( $chart_number='', $registration_number=null )
	{
		if( ! $this->input->is_ajax_request() )
		{
			show_error( 'Bad Request', 400 );
		}
		
		if( ! $this->input->post() )
		{
			$this->template->build_json(array(
					"status" => "error",
					"error" => "Method Failure",
					"code" => "420"
				));
		}
		
		
		
		if( $chart_number == "" ){ $chart_number = $this->input->get_post( 'chart_num', TRUE ); }
		if( $registration_number == "" ){ $registration_number = $this->input->get_post( 'reg_num', TRUE ); }
		
		$item_data = $this->input->post( "f" );
		$item_data['state'] = 1;
		$this->load->library( 'my_object', $item_data, 'item' );
		
		$this->load->library( 'form_validation' );
		$this->form_validation->set_rules( $this->get_model()->rules['insert'] );
		$this->form_validation->set_data( $this->item->toArray() );
		if( ! $this->form_validation->run() )
		{
			$this->template->build_json(array(
					"status" => "error",
					"error" => $this->form_validation->get_all_error_string(),
					"code" => "500"
				));
		}
		
		$chief_complaint = (string) trim( $this->item->chief_complaint );
		$template_id = 0;
		
		if( ! chart_template_helper::find_drug_by_cc($chief_complaint) )
		{
			if( ! $template_id = $this->get_model()->insert( $this->item->toArray() ) )
			{
				$this->template->build_json(array(
						"status" => "error",
						"error" => lang('global:created_successfully'),
						"code" => "500"
					));
			}
		} else
		{
			if( ! $this->get_model()->update( $this->item->toArray(), array("chief_complaint" => $chief_complaint) ) )
			{
				$this->template->build_json(array(
						"status" => "error",
						"error" => lang('global:updated_successfully'),
						"code" => "500"
					));
			}
			
			$template_id = (int) @chart_template_helper::get_drug_by_cc( $chief_complaint )->id;
		}
		
		$this->load->helper( "examinations/chart" );
		if( chart_helper::find_chart( $chart_number ) )
		{
			chart_helper::update_chart_template( $chart_number, $template_id );
		}
		
		$this->template->build_json(array(
				"status" => "success",
				"error" => "",
				"code" => "200"
			));
	}
	
	public function dropdown( $selected='' )
	{
		if( $this->input->is_ajax_request() )
		{
			if( $this->get_model()->count() )
			{
				$items = $this->get_model()
					->as_object()
					->where(array("state" => 1))
					->order_by('service_title', 'asc')
					->get_all()
					;
				
				$options_html = "";
				
				if( $selected == "" )
				{
					$options_html .= "\n<option data-id=\"0\" data-code=\"\" data-title=\"\" data-price=\"0\" value=\"\" selected>".lang( 'global:select-empty' )."</option>";
				} else
				{
					$options_html .= "\n<option data-id=\"0\" data-code=\"\" data-title=\"\" data-price=\"0\" value=\"\">".lang( 'global:select-empty' )."</option>";
				}
				
				foreach($items as $item)
				{
					$item->id = (int) $item->id;
					$item->service_price = (float) $item->service_price;
					
					$attr_data = "data-id=\"{$item->id}\" data-code=\"{$item->code}\" data-title=\"{$item->service_title}\" data-price=\"{$item->service_price}\" ";
					
					if( $selected == $item->code)
					{
						$options_html .= "\n<option {$attr_data}value=\"{$item->code}\" selected>{$item->code} - {$item->service_title}</option>";
					} else
					{
						$options_html .= "\n<option {$attr_data}value=\"{$item->code}\">{$item->code} - {$item->service_title}</option>";
					}
				}
				
				print( $options_html );
				exit();
			}
		}
	}
	
	public function lookup( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'chart/templates/lookup/datatable' );
		} else
		{
			$data = array(
					'page' => $this->page,
					'datatables' => TRUE,
					'form' => TRUE,
				);
			
			$this->template
				->set( "heading", lang("chart_template:page")." / "."Lookup Box" )
				->set_breadcrumb( lang("common:page"), base_url("common") )
				->set_breadcrumb( lang("chart_template:breadcrumb"), base_url("common/chart-templates") )
				->set_breadcrumb( "Lookup Box" )
				->build('chart/templates/lookup', (isset($data) ? $data : NULL));
		}
	}
	
	public function lookup_collection()
	{
		$this->datatable_collection( 1 );
	}
	
	public function datatable_collection( $state=false )
    {
        
		$start = $this->input->get_post('start', true);
		$length = $this->input->get_post('length', true);
		$order = $this->input->get_post('order', true);
		$columns = $this->input->get_post('columns', true);
		$search = $this->input->get_post('search', true);
		$draw = $this->input->get_post('draw', true);
		
		$db_where = array();
		$db_like = array();
		
		// prepare defautl flter
		$db_where['deleted_at'] = NULL;
		if( $state !== false )
		{
			$db_where['state'] = 1;
		}
		
		// preparing default
		if( isset($search['value']) && ! empty($search['value']) )
        {
            for($i=0; $i<count($columns); $i++)
            {
                if( isset($columns[$i]['searchable']) && $columns[$i]['searchable'] == 'true')
                {
                	$db_like[$columns[$i]['data']] = $search['value'];
				}
            }
        }
		
		// get total records
		$this->db->from( "chart_templates" );
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		$records_total = $this->db->count_all_results();
		
		// get total filtered
		$this->db->from( "chart_templates" );
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }		
		$records_filtered = $this->db->count_all_results();
		
		// get result filtered
		$this->db->from( "chart_templates" );
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
			$row->created_at = strftime(config_item('date_format'), @$row->created_at);
			$row->updated_at = strftime(config_item('date_format'), @$row->updated_at);
			
            $output['data'][] = $row;
        }
		
		$this->template
			->build_json( $output );
    }
}