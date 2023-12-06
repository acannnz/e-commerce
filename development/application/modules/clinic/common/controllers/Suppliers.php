<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Suppliers extends Admin_Controller
{
	protected $_translation = 'common';	
	protected $_model = 'supplier_m';
	
	public function __construct()
	{
		parent::__construct();
		
		$this->load->model( "supplier_m" );
		$this->load->model( "supplier_specialist_m" );
		$this->load->model( "supplier_sub_specialist_m" );

		$this->page = "common_suppliers";
		$this->template->title( lang("suppliers:page") . ' - ' . $this->config->item('company_name') );
	}
	
	public function index()
	{
		$data = array(
				'page' => $this->page,
				"form" => TRUE,
				'datatables' => TRUE,
			);
		
		$this->template
			->set( "heading", lang("suppliers:page") )
			->set_breadcrumb( lang("common:page"), base_url("common") )
			->set_breadcrumb( lang("suppliers:breadcrumb") )
			->build('suppliers/datatable', (isset($data) ? $data : NULL));
	}
	
	public function create()
	{
		$item_data = array(
				'id' => 0,
				'code' => null,
				'supplier_title' => null,
				'supplier_description' => null,
				'supplier_price' => null,
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
					$this->get_model()->delete_cache( 'common_suppliers.collection' );
					
					make_flashdata(array(
							'response_status' => 'success',
							'message' => lang('global:created_successfully')
						));
						
					redirect( 'common/suppliers' );
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
					'suppliers/modal/create_edit', 
					array('form_child' => $this->load->view('suppliers/form', $data, true))
				);
		} else
		{
			$data = array(
					"page" => $this->page."_".strtolower(__FUNCTION__),
					"item" => $this->item,
					"form" => TRUE,
				);
			
			$this->template
				->set( "heading", lang("suppliers:create_heading") )
				->set_breadcrumb( lang("common:page"), base_url("common") )
				->set_breadcrumb( lang("suppliers:breadcrumb"), base_url("common/suppliers") )
				->set_breadcrumb( lang("suppliers:create_heading") )
				->build('suppliers/form', $data);
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
					$this->get_model()->delete_cache( 'common_suppliers.collection' );
					
					make_flashdata(array(
							'response_status' => 'success',
							'message' => lang('global:updated_successfully')
						));
						
					redirect( 'common/suppliers' );
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
					'suppliers/modal/create_edit', 
					array('form_child' => $this->load->view('suppliers/form', $data, true))
				);
		} else
		{
			$data = array(
					"page" => $this->page,
					"item" => $this->item,
					"form" => TRUE,
				);
			
			$this->template
				->set( "heading", lang("suppliers:edit_heading") )
				->set_breadcrumb( lang("common:page"), base_url("common") )
				->set_breadcrumb( lang("suppliers:breadcrumb"), base_url("common/suppliers") )
				->set_breadcrumb( lang("suppliers:edit_heading") )
				->build('suppliers/form', $data);
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
				
				$this->get_model()->delete_cache( 'common_suppliers.collection' );
				
				make_flashdata(array(
						'response_status' => 'success',
						'message' => lang('global:deleted_successfully')
					));
			}
			
			redirect( $this->input->post( 'r_url' ) );
		}
		
		$this->load->view( 'suppliers/modal/delete', array('item' => $this->item) );
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
					->order_by('supplier_title', 'asc')
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
					$item->supplier_price = (float) $item->supplier_price;
					
					$attr_data = "data-id=\"{$item->id}\" data-code=\"{$item->code}\" data-title=\"{$item->supplier_title}\" data-price=\"{$item->supplier_price}\" ";
					
					if( $selected == $item->code)
					{
						$options_html .= "\n<option {$attr_data}value=\"{$item->code}\" selected>{$item->code} - {$item->supplier_title}</option>";
					} else
					{
						$options_html .= "\n<option {$attr_data}value=\"{$item->code}\">{$item->code} - {$item->supplier_title}</option>";
					}
				}
				
				print( $options_html );
				exit();
			}
		}
	}

	public function lookup_back_office( $is_ajax_request = false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'suppliers/lookup/datatable_back_office' );
		} 
	}
		
	public function lookup( $is_ajax_request=false, $type= 'all' )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'suppliers/lookup/datatable', array("type" => $type ) );
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
				->build('suppliers/lookup', (isset($data) ? $data : NULL));
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
		
		$db_from = "{$this->supplier_m->table} a";
		$db_where = array();
		$db_where_in = array();
		$db_or_where = array();
		$db_like = array();
		
		$db_where['a.Active'] = 1;
		// prepare defautl flter
		if ($this->input->post('type') == 'doctor')	
		{
			$db_where_in_col = 'a.KodeKategoriVendor';
			$db_where_in = array( 'V-002','V-009');
		}

		if ($this->input->post('type') == 'nurse')	
		{
			$db_where_in_col = 'a.KodeKategoriVendor';
			$db_where_in = array( 'V-003','V-004');
			//$db_where['a.KodeKategoriVendor'] = 'V-003';
		}
		
		if ($this->input->post('type') == 'analys')	
		{
			$db_where_in_col = 'a.KodeKategoriVendor';
			$db_where_in = array( 'V-008','V-003','V-004');
			//$db_where['a.KodeKategoriVendor'] = 'V-003';
		}

		if ($this->input->post('type') == 'hospitals')	
		{
			$db_where_in_col = 'a.KodeKategoriVendor';
			$db_where_in = array( 'V-002','V-003','V-004','V-005','V-006','V-007','V-008','V-009','V-012');
			//$db_where['a.KodeKategoriVendor'] = 'V-003';
		}

		
		
		// preparing default
		if( isset($search['value']) && ! empty($search['value']) )
        {
            $keywords = $this->db->escape_str( $search['value'] );
			
			$db_like[ $this->db->escape_str("a.Kode_Supplier") ] = $keywords;
			$db_like[ $this->db->escape_str("a.Nama_Supplier") ] = $keywords;
			$db_like[ $this->db->escape_str("a.Alamat_1") ] = $keywords;
			$db_like[ $this->db->escape_str("b.SpesialisName") ] = $keywords;
			$db_like[ $this->db->escape_str("c.SubSpesialisName") ] = $keywords;
				
        }
		
		// get total records
		$this->db->from( $db_from );
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_or_where) ){ $this->db->or_where( $db_or_where ); }
		$records_total = $this->db->count_all_results();
		
		// get total filtered
		$this->db
			->from( $db_from )
			->join( "{$this->supplier_specialist_m->table} b", "a.SpesialisID = b.SpesialisID", "LEFT OUTER" )
			->join( "{$this->supplier_sub_specialist_m->table} c", "a.SubSpesialisID = c.SubSpesialisID", "LEFT OUTER" )
			;
			
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_where_in) ){ $this->db->where_in( $db_where_in_col,$db_where_in ); }
		if( !empty($db_or_where) ){ $this->db->or_where( $db_or_where ); }
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }		
		$records_filtered = $this->db->count_all_results();

		
		// get result filtered
		$db_select = <<<EOSQL
			a.Kode_Supplier,
			a.Nama_Supplier,
			a.Kode_Supplier_BPJS,
			a.Alamat_1,
			b.SpesialisName,
			c.SubSpesialisName,

EOSQL;

		$this->db
			->select( $db_select )
			->from( $db_from )
			->join( "{$this->supplier_specialist_m->table} b", "a.SpesialisID = b.SpesialisID", "LEFT OUTER" )
			->join( "{$this->supplier_sub_specialist_m->table} c", "a.SubSpesialisID = c.SubSpesialisID", "LEFT OUTER" )
			;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_where_in) ){ $this->db->where_in( $db_where_in_col,$db_where_in ); }
		if( !empty($db_or_where) ){ $this->db->or_where( $db_or_where ); }
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
		
		//$this->db->group_by("Kode_Supplier");
		
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
	
	public function lookup_collection_back_office( )
    {
        $start = $this->input->get_post('start', true);
        $length = $this->input->get_post('length', true);
        $order = $this->input->get_post('order', true);
        $columns = $this->input->get_post('columns', true);
        $search = $this->input->get_post('search', true);
        $draw = $this->input->get_post('draw', true);
		
		$db_from = "{$this->get_model()->table} a";
		$db_where = array();
		$db_like = array();
		
		// prepare defautl flter
		$db_where['Active'] = 1; 
		
		// preparing default
		if( isset($search['value']) && ! empty($search['value']) )
        {
            $keywords = $this->db->escape_str( $search['value'] );
			
			$db_like[ $this->db->escape_str("a.Kode_Supplier") ] = $keywords;
			$db_like[ $this->db->escape_str("a.Nama_Supplier") ] = $keywords;
				
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



