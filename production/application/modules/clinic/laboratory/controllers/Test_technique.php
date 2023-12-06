<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Test_technique extends Admin_Controller
{ 
	protected $_translation = 'laboratory';	
	protected $_model = 'test_technique_m';  
	protected $nameroutes = 'laboratory/test-technique';
	  
	public function __construct()  
	{
		parent::__construct();
		$this->simple_login->check_user_role('laboratory');
		
		$this->page = "LAB";
		$this->template->title( 'Setup Tehnik Pemeriksaan' . ' - ' . $this->config->item('company_name') );
		
		$this->load->helper('laboratory');
	}
	
	public function index()
	{
		$data = array(
				'page' => $this->page,
				'nameroutes' => $this->nameroutes,
				"form" => TRUE,
				'datatables' => TRUE,
			);
		
		$this->template
			->set( "heading", "Teknik Pemeriksaan" )
			->set_breadcrumb( lang("helper:breadcrumb"), base_url("helper") )
			->set_breadcrumb( 'Daftar Teknik Pemeriksaan ')
			->build('test_technique/datatable', (isset($data) ? $data : NULL));
	}
		
	public function create()
	{	  
		$item = (object)[
			'TeknikPemeriksaan' => '',
			'Aktif' => 1
		];
		
		if( $this->input->post() ) 
		{
			$post_data = array_merge( (array) $item, $this->input->post('f'));
			$this->load->library( 'form_validation' );			
			$this->form_validation->set_rules( $this->get_model()->rules['insert'] );
			$this->form_validation->set_data( $post_data );
			if( $this->form_validation->run() )
			{
				if( $this->get_model()->create( $post_data ) )
				{
					make_flashdata(array(
							'response_status' => 'success',
							'message' => lang('global:created_successfully')
						));
					redirect( 'laboratory/test-technique' );
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
					'item' => $item,
					"is_ajax_request" => TRUE,
					"is_modal" => TRUE,
					"is_edit" => FALSE,
				);
			
			$this->load->view( 
					'test_technique/modal/create_edit', 
					array('form_child' => $this->load->view('test_technique/form', $data, true))
				);
		} else
		{
			$data = array(
					"page" => $this->page."_".strtolower(__FUNCTION__),
					"item" => $item,
					"nameroutes" => $this->nameroutes,
					"form" => TRUE,
					"datatables" => TRUE,
					"is_edit" => FALSE,
				);
			
			$this->template
				->set( "heading", "Kelola Teknik Pemeriksaan" )
				->set_breadcrumb( lang("laboratory:breadcrumb"), base_url("helper") )
				->set_breadcrumb( "Daftar Teknik Pemeriksaan", base_url("{$this->nameroutes}") )
				->set_breadcrumb( "Buat Teknik Pemeriksaan Baru" )
				->build('test_technique/form', $data);
		}
	}
		
	public function edit( $id = NULL )
	{
		$item = $this->get_model()->get_one($id);
		
		if( $this->input->post() ) 
		{
			$post_data = array_merge( (array) $item, $this->input->post('f'));
			$this->load->library( 'form_validation' );
			$this->form_validation->set_rules( $this->get_model()->rules['insert'] );
			$this->form_validation->set_data( $post_data );
			if( $this->form_validation->run() )
			{
				if( $this->get_model()->update( $post_data, $id ) )
				{					
					make_flashdata(array(
							'response_status' => 'success',
							'message' => lang('global:updated_successfully')
						));
						
					redirect( 'laboratory/test-technique' );
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
					'item' => $item,
					"is_ajax_request" => TRUE,
					"is_modal" => TRUE,
					"is_edit" => TRUE,
				);
						
			$this->load->view( 
					'test_technique/modal/create_edit', 
					array('form_child' => $this->load->view('test_technique/form', $data, true))
				);
		} else
		{
			$data = array(
					"page" => $this->page,
					"item" => $item,
					"form" => TRUE,
					"is_edit" => TRUE,
				);
			
			$this->template
				->set( "heading", "Kelola Teknik Pemeriksaan" )
				->set_breadcrumb( lang("laboratory:breadcrumb"), base_url("helper") )
				->set_breadcrumb( "Daftar Teknik Pemeriksaan", base_url("{$this->nameroutes}") )
				->set_breadcrumb( "Perbarui Teknik Pemeriksaan" )
				->build('test_technique/form', $data);
		}
	}
	
	public function delete( $id=0 )
	{
		$item = $this->get_model()->get_one( $id );	
		if( $this->input->post() ) 
		{
			if( empty($item->TeknikPemeriksaan) )
			{
				make_flashdata(array(
						'response_status' => 'error',
						'message' => lang( 'global:get_failed' )
					));
			
				redirect( $this->input->post( 'r_url' ) );
			}
			
			if( $item->TeknikPemeriksaan == $this->input->post( 'confirm' ) )
			{
				$this->get_model()->delete( $id );				
				
				make_flashdata(array(
						'response_status' => 'success',
						'message' => lang('global:deleted_successfully')
					));
			}
			
			redirect( $this->input->post( 'r_url' ) );
		}
		
		$this->load->view( 'test_technique/modal/delete', array('item' => $item) );
	}
		
	public function lookup( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'registrations/lookup/datatable' );
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
				->build('registrations/lookup', (isset($data) ? $data : NULL));
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
		
		$db_from = "{$this->get_model()->table} a";
		$db_where = array();
		$db_like = array();
		
		if( isset($search['value']) && ! empty($search['value']) )
        {
            $keywords = $this->db->escape_str( $search['value'] );
			$db_like[ $this->db->escape_str("a.TeknikPemeriksaan") ] = $keywords;
			$db_like[ $this->db->escape_str("a.aktif") ] = $keywords;
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



