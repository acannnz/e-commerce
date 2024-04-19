<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Customers extends Admin_Controller
{
	protected $_translation = 'common';	
	protected $_model = 'customer_m';
	
	public function __construct()
	{
		parent::__construct();
		
		$this->load->model( "customer_m" );
		$this->load->model( "supplier_specialist_m" );
		$this->load->model( "supplier_sub_specialist_m" );

		$this->page = "common_customers";
		$this->template->title( lang("customers:page") . ' - ' . $this->config->item('company_name') );
	}
	
	public function index()
	{
		$data = array(
				'page' => $this->page,
				"form" => TRUE,
				'datatables' => TRUE,
			);
		
		$this->template
			->set( "heading", lang("customers:page") )
			->set_breadcrumb( lang("common:page"), base_url("common") )
			->set_breadcrumb( lang("customers:breadcrumb") )
			->build('customers/datatable', (isset($data) ? $data : NULL));
	}
	
	public function create()
	{
		$item_data = array(
				'id' => 0,
				'code' => null,
				'customer_title' => null,
				'customer_description' => null,
				'customer_price' => null,
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
					$this->get_model()->delete_cache( 'common_customers.collection' );
					
					make_flashdata(array(
							'response_status' => 'success',
							'message' => lang('global:created_successfully')
						));
						
					redirect( 'common/customers' );
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
			$data = array(
					'item' => $this->item,
					"is_ajax_request" => TRUE,
					"is_modal" => TRUE,
				);
			
			$this->load->view( 
					'customers/modal/create_edit', 
					array('form_child' => $this->load->view('customers/form', $data, true))
				);
		} else
		{
			$data = array(
					"page" => $this->page."_".strtolower(__FUNCTION__),
					"item" => $this->item,
					"form" => TRUE,
				);
			
			$this->template
				->set( "heading", lang("customers:create_heading") )
				->set_breadcrumb( lang("common:page"), base_url("common") )
				->set_breadcrumb( lang("customers:breadcrumb"), base_url("common/customers") )
				->set_breadcrumb( lang("customers:create_heading") )
				->build('customers/form', $data);
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
					$this->get_model()->delete_cache( 'common_customers.collection' );
					
					make_flashdata(array(
							'response_status' => 'success',
							'message' => lang('global:updated_successfully')
						));
						
					redirect( 'common/customers' );
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
			$data = array(
					'item' => $this->item,
					"is_ajax_request" => TRUE,
					"is_modal" => TRUE,
				);
			
			$this->load->view( 
					'customers/modal/create_edit', 
					array('form_child' => $this->load->view('customers/form', $data, true))
				);
		} else
		{
			$data = array(
					"page" => $this->page,
					"item" => $this->item,
					"form" => TRUE,
				);
			
			$this->template
				->set( "heading", lang("customers:edit_heading") )
				->set_breadcrumb( lang("common:page"), base_url("common") )
				->set_breadcrumb( lang("customers:breadcrumb"), base_url("common/customers") )
				->set_breadcrumb( lang("customers:edit_heading") )
				->build('customers/form', $data);
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
				
				$this->get_model()->delete_cache( 'common_customers.collection' );
				
				make_flashdata(array(
						'response_status' => 'success',
						'message' => lang('global:deleted_successfully')
					));
			}
			
			redirect( $this->input->post( 'r_url' ) );
		}
		
		$this->load->view( 'customers/modal/delete', array('item' => $this->item) );
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
					->order_by('customer_title', 'asc')
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
					$item->customer_price = (float) $item->customer_price;
					
					$attr_data = "data-id=\"{$item->id}\" data-code=\"{$item->code}\" data-title=\"{$item->customer_title}\" data-price=\"{$item->customer_price}\" ";
					
					if( $selected == $item->code)
					{
						$options_html .= "\n<option {$attr_data}value=\"{$item->code}\" selected>{$item->code} - {$item->customer_title}</option>";
					} else
					{
						$options_html .= "\n<option {$attr_data}value=\"{$item->code}\">{$item->code} - {$item->customer_title}</option>";
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
			$this->load->view( 'customers/lookup/datatable' );
		} else
		{
			$data = array(
					'page' => $this->page,
					'datatables' => TRUE,
					'form' => TRUE,
				);
			
			$this->template
				->set( "heading", "Lookup Box" )
				->set_breadcrumb( lang("common:page"), base_url("common") )
				->set_breadcrumb( "Lookup Box" )
				->build('customers/lookup', (isset($data) ? $data : NULL));
		}
	}

	public function lookup_back_office( $is_ajax_request = false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$data = array(
				"option_customer_category" => $this->get_model()->get_option_customer_category(),
			);
			
			$this->load->view( 'customers/lookup/datatable_back_office', $data );
			
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
		
		$db_from = "{$this->customer_m->table} a";
		$db_where = array();
		$db_like = array();
		
		// prepare defautl flter
		
		// preparing default
		if( isset($search['value']) && ! empty($search['value']) )
        {
            $keywords = $this->db->escape_str( $search['value'] );
			
			$db_like[ $this->db->escape_str("a.Kode_Customer") ] = $keywords;
			$db_like[ $this->db->escape_str("a.Nama_Customer") ] = $keywords;
			$db_like[ $this->db->escape_str("a.Alamat_1") ] = $keywords;
			$db_like[ $this->db->escape_str("b.SpesialisName") ] = $keywords;
			$db_like[ $this->db->escape_str("c.SubSpesialisName") ] = $keywords;
				
        }
		
		// get total records
		$this->db->from( $db_from );
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		$records_total = $this->db->count_all_results();
		
		// get total filtered
		$this->db
			->from( $db_from )
			->join( "{$this->supplier_specialist_m->table} b", "a.SpesialisID = b.SpesialisID", "LEFT OUTER" )
			->join( "{$this->supplier_sub_specialist_m->table} c", "b.SpesialisID = c.SpesialisID", "LEFT OUTER" )
			;
			
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }		
		$records_filtered = $this->db->count_all_results();

		
		// get result filtered
		$db_select = <<<EOSQL
			a.Kode_Customer,
			a.Nama_Customer,
			a.Alamat_1,
			b.SpesialisName,
			c.SubSpesialisName,

EOSQL;

		$this->db
			->select( $db_select )
			->from( $db_from )
			->join( "{$this->supplier_specialist_m->table} b", "a.SpesialisID = b.SpesialisID", "LEFT OUTER" )
			->join( "{$this->supplier_sub_specialist_m->table} c", "b.SpesialisID = c.SpesialisID", "LEFT OUTER" )
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
		
		$this->template
			->build_json( $output );
    }

	public function lookup_collection_back_office()
	{
		$this->datatable_collection_back_office( );
	}
			
	public function datatable_collection_back_office( )
    {
        $start = $this->input->get_post('start', true);
        $length = $this->input->get_post('length', true);
        $order = $this->input->get_post('order', true);
        $columns = $this->input->get_post('columns', true);
        $search = $this->input->get_post('search', true);
        $draw = $this->input->get_post('draw', true);
		
		$db_from = "{$this->customer_m->table} a";
		$db_where = array();
		$db_like = array();
		
		// prepare defautl flter
		$db_where['Active'] = 1; 
		if ( !empty( $this->input->post('customer_category') ) )
		{
			$db_where['a.Kode_Kategori'] = $this->input->post('customer_category');
		}
		
		// preparing default
		if( isset($search['value']) && ! empty($search['value']) )
        {
            $keywords = $this->db->escape_str( $search['value'] );
			
			$db_like[ $this->db->escape_str("a.Kode_Customer") ] = $keywords;
			$db_like[ $this->db->escape_str("a.Nama_Customer") ] = $keywords;
				
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
		
		$this->template
			->build_json( $output );
    }	
	
}



