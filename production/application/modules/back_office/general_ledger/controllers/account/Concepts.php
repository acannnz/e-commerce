<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Concepts extends Admin_Controller
{
	protected $_translation = 'general_ledger';	
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('general_ledger');
		
		$this->load->helper("general_ledger");
		$this->load->model("account/concept_m");
		
		$this->page = "general_ledger_concepts";
		$this->template->title( lang("concepts:page") . ' - ' . $this->config->item('company_name') );
	}
	
	public function index()
	{
		$data = array(
				'page' => $this->page,
				"form" => TRUE,
				'datatables' => TRUE,
			);
		
		$this->template
			->set( "heading", lang("concepts:page") )
			->set_breadcrumb( lang("general_ledger:page"), base_url("general_ledger") )
			->set_breadcrumb( lang("concepts:breadcrumb") )
			->build('accounts/concepts/datatable', (isset($data) ? $data : NULL));
	}
	
	public function create()
	{
		$item_data = array(
				'id' => 0,
				'house_id' => $this->_house_id,
				'level' => 0,
				'digit' => 0,
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
					$this->get_model()->delete_cache( 'general_ledger_concepts.collection' );
					
					make_flashdata(array(
							'response_status' => 'success',
							'message' => lang('global:created_successfully')
						));
						
					redirect( 'general_ledger/concepts' );
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
					'concepts/modal/create_edit', 
					array('form_child' => $this->load->view('concepts/form', array('item' => $this->item, 'is_modal' => TRUE), true))
				);
		} else
		{
			$data = array(
					"page" => $this->page,
					"item" => $this->item,
					"form" => TRUE,
				);
			
			$this->template
				->set( "heading", lang("concepts:create_heading") )
				->set_breadcrumb( lang("general_ledger:page"), base_url("general-ledger") )
				->set_breadcrumb( lang("concepts:breadcrumb"), base_url("general-ledger/account/concepts") )
				->set_breadcrumb( lang("concepts:create_heading") )
				->build('concepts/form', $data);
		}
	}
	
	public function edit( $id = 0 )
	{
		$id = (int) @$id;
		
		$item = $this->concept_m->get_row( $id );
		if( ! $item ){ $item = array('id' => 0); }
		$this->load->library( 'my_object', $item, 'item' );

		$collection = $this->concept_m->get_detail( $id );
		
		if( $this->input->post() ) 
		{
			
			
			$this->load->library( 'form_validation' );
			
			$this->item->addData( $this->input->post("f") );
			$this->form_validation->set_rules( $this->concept_m->rules['insert'] );
			$this->form_validation->set_data( $this->item->toArray() );
			
			$response = array(
					"message" => lang('global:updated_successfully'),
					"status" => "success",
					"code" => "200",
				);
				
			if( $this->form_validation->run() )
			{
				$details = $this->input->post("details");
				
				if( !$this->concept_m->update_data( $id, $this->item->toArray(), $details) )
				{
					$response["message"] = lang('global:updated_failed');
					$response["status"] = "error";
					$response["code"] = "500";
				}
			} else
			{
				$response["message"] = $this->form_validation->get_all_error_string();
				$response["status"] = "error";
				$response["code"] = "500";
			}
			
			print_r( json_encode($response, JSON_NUMERIC_CHECK) );
			exit(0);
		}

		if( $this->input->is_ajax_request() )
		{
			$data = array(
				'item' => (object) $this->item->getData(), 
				'collection' => $collection, 
				'is_modal' => TRUE
			);
			
			$this->load->view( 
					'accounts/concepts/modal/create_edit', 
					array('form_child' => $this->load->view('accounts/concepts/form', $data, true))
				);
		} else
		{
			$data = array(
					"page" => $this->page,
					"item" => (object) $this->item->getData(),
					"collection" => $collection,
					"form" => TRUE,
					"datatabled" => TRUE,
				);
			
			$this->template
				->set( "heading", lang("concepts:edit_heading") )
				->set_breadcrumb( lang("general_ledger:page"), base_url("general-ledger") )
				->set_breadcrumb( lang("concepts:breadcrumb"), base_url("general-ledger/account/concepts") )
				->set_breadcrumb( lang("concepts:edit_heading") )
				->build('accounts/concepts/form', $data);
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
				
				$this->get_model()->delete_cache( 'general_ledger_services.collection' );
				
				make_flashdata(array(
						'response_status' => 'success',
						'message' => lang('global:deleted_successfully')
					));
			}
			
			redirect( $this->input->post( 'r_url' ) );
		}
		
		$this->load->view( 'concepts/modal/delete', array('item' => $this->item) );
	}
	
	public function datatable_collection( )
    {
        $start = $this->input->get_post('start', true);
        $length = $this->input->get_post('length', true);
        $order = $this->input->get_post('order', true);
        $columns = $this->input->get_post('columns', true);
        $search = $this->input->get_post('search', true);
        $draw = $this->input->get_post('draw', true);
		
		$db_from = "{$this->concept_m->table} a";
		$db_where = array();
		$db_like = array();
		
		// prepare defautl flter		
		
		// preparing default
		if( isset($search['value']) && ! empty($search['value']) )
        {
            $keywords = $this->db->escape_str( $search['value'] );
						
			$db_like[ $this->db->escape_str("a.level") ] = $keywords;			
			$db_like[ $this->db->escape_str("a.digit") ] = $keywords;
			$db_like[ $this->db->escape_str("a.state") ] = $keywords;
			$db_like[ $this->db->escape_str("a.updated_at") ] = $keywords;
			
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

