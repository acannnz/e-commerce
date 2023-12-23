<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Types extends Admin_Controller
{
	protected $_translation = 'payable';	
	protected $_model = 'type_m';
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('payable');
		
		$this->load->helper("payable");
		
		$this->page = "payable_types";
		$this->template->title( lang("types:page") . ' - ' . $this->config->item('company_name') );
	}
	
	public function index()
	{
		$data = array(
				'page' => $this->page,
				"form" => TRUE,
				'datatables' => TRUE,
			);
		
		$this->template
			->set( "heading", lang("types:page") )
			->set_breadcrumb( lang("payable:page"), base_url("payable") )
			->set_breadcrumb( lang("types:breadcrumb") )
			->build('types/datatable', (isset($data) ? $data : NULL));
	}
	
	public function create()
	{
		$item_data = array(
				'Nama_Type' => null,
				'Akun_ID' => 0,
				'Default_Type_Hutang' => 0,
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

				if( $this->get_model()->create_data( $this->item->toArray() ) )
				{					
					make_flashdata(array(
							'response_status' => 'success',
							'message' => lang('global:created_successfully')
						));
						
					redirect( 'payable/types' );
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
					'types/modal/create_edit', 
					array('form_child' => $this->load->view('types/form', array('item' => $this->item, 'is_modal' => TRUE), true))
				);
		} else
		{
			$data = array(
					"page" => $this->page,
					"item" => $this->item,
					"form" => TRUE,
					"datatables" => TRUE,
					"lookup_accounts" => base_url("payable/types/lookup_accounts")
				);
			
			$this->template
				->set( "heading", lang("types:create_heading") )
				->set_breadcrumb( lang("payable:page"), base_url("payable") )
				->set_breadcrumb( lang("types:breadcrumb"), base_url("payable/types") )
				->set_breadcrumb( lang("types:create_heading") )
				->build('types/form', $data);
		}
	}
	
	public function edit( $id=0 )
	{
		$item = $this->get_model()->get_row( $id );
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
				if( $this->get_model()->update_data( $this->input->post("f"), @$id ) )
				{					
					make_flashdata(array(
							'response_status' => 'success',
							'message' => lang('global:updated_successfully')
						));
						
					redirect( 'payable/types' );
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
					'types/modal/create_edit', 
					array('form_child' => $this->load->view('types/form', array('item' => $this->item, 'is_modal' => TRUE), true))
				);
		} else
		{
			$data = array(
					"page" => $this->page,
					"item" => (object) $this->item->toArray(),
					"form" => TRUE,
					"datatables" => TRUE,
					"lookup_accounts" => base_url("payable/types/lookup_accounts")
				);
			
			$this->template
				->set( "heading", lang("types:edit_heading") )
				->set_breadcrumb( lang("payable:page"), base_url("payable") )
				->set_breadcrumb( lang("types:breadcrumb"), base_url("payable/types") )
				->set_breadcrumb( lang("types:edit_heading") )
				->build('types/form', $data);
		}
	}
	
	public function delete( $id=0 )
	{
		$item = $this->get_model()->get_row( $id );
		if( ! $item ){ $item = array('id' => 0); }
		
		if( $this->input->post() ) 
		{
			
			
			if( 0 == $item[ $this->get_model()->primary_key ] )
			{
				make_flashdata(array(
						'response_status' => 'error',
						'message' => lang( 'global:get_failed' )
					));
			
				redirect( $this->input->post( 'r_url' ) );
			}
			
			if( $item[ $this->get_model()->primary_key ] == $this->input->post( 'confirm' ) )
			{
				make_flashdata(array(
						'response_status' => 'success',
						'message' => lang('global:deleted_successfully')
					));

				if ( $this->get_model()->delete_data( $item, $id ) === FALSE )
				{				
					make_flashdata(array(
							'response_status' => 'success',
							'message' => lang('global:deleted_failed')
						));
				}
			}
			
			redirect( $this->input->post( 'r_url' ) );
		}
		
		$this->load->view( 'types/modal/delete', array('item' => (object) $item) );
	}

	public function lookup_accounts(  ){
	
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'types/lookup/accounts', array() );
		} 
	}
	
	public function datatable_collection( $state=false )
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

		$this->load->model("general_ledger/account_m");
				
		// preparing default
		if( isset($search['value']) && ! empty($search['value']) )
        {
            $keywords = $this->db->escape_str( $search['value'] );
						
			$db_like[ $this->db->escape_str("a.Nama_Type") ] = $keywords;			
			$db_like[ $this->db->escape_str("a.Default_Type_Hutang") ] = $keywords;
			$db_like[ $this->db->escape_str("b.Akun_No") ] = $keywords;
			$db_like[ $this->db->escape_str("b.Akun_Name") ] = $keywords;
			
        }
		
		// get total records
		$this->db->from( $db_from );
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		$records_total = $this->db->count_all_results();
		
		// get total filtered
		$this->db
			->from( $db_from )
			->join("{$this->account_m->table} b", "a.Akun_ID = b.Akun_ID", "LEFT OUTER")
			;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }		
		$records_filtered = $this->db->count_all_results();
		
		// get result filtered
		$db_select = <<<EOSQL
			a.TypeHutang_ID, 
			a.Nama_Type, 
			a.Default_Type_Hutang,
			b.Akun_No,
			b.Akun_Name
EOSQL;

		$this->db
			->select( $db_select )
			->from( $db_from )
			->join("{$this->account_m->table} b", "a.Akun_ID = b.Akun_ID", "LEFT OUTER")
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

