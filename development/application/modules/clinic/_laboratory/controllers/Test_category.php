<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Test_category extends Admin_Controller
{ 
	protected $_translation = 'laboratory';	
	protected $_model = 'test_category_m'; 
	protected $nameroutes; 
	  
	public function __construct()  
	{
		parent::__construct();
		$this->simple_login->check_user_role('laboratory');
		
		$this->page = "Kategori Test";
		$this->template->title( 'Setup Kategori Test' . ' - ' . $this->config->item('company_name') );
		
		$this->nameroutes = 'laboratory/test-category';
		
		$this->load->helper('laboratory');
		$this->load->model('test_category_m');
	}
	
	public function index()
	{
		$data = array(
				'page' => $this->page,
				"form" => TRUE,
				'datatables' => TRUE,
				'nameroutes' => $this->nameroutes
			);
		
		$this->template
			->set( "heading", "Kategori Test" )
			->set_breadcrumb( lang("laboratory:breadcrumb"), base_url("helper") )
			->set_breadcrumb( lang("helper:breadcrumb") )
			->build('test_category/datatable', (isset($data) ? $data : NULL));
	}
		
	public function create()
	{	  
		$item = (object) [
			'KategoriTestID' => laboratory_helper::gen_category_test_number(),
			'KategoriTestNama' => NULL,
			'FormatDefault' => 1, 
			'FormatNo' => NULL,
		];
				
		if( $this->input->post() ) 
		{		
			$post_data = (object) array_merge((array) $item, $this->input->post('f'));
			
			$this->load->library( 'form_validation' );
			$this->form_validation->set_rules( $this->get_model()->rules['insert'] );
			$this->form_validation->set_data( (array) $post_data );
			if( $this->form_validation->run() )
			{
				if( $this->get_model()->create( $post_data ) )
				{				
					make_flashdata(array(
							'response_status' => 'success',
							'message' => lang('global:created_successfully')
						));
					redirect( 'laboratory/test_category' );
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
			$data = [
				'item' => $item,
				"is_ajax_request" => TRUE,
				"is_modal" => TRUE,
			];
			
			$this->load->view( 
					'laboratory/test_category/modal/create_edit', 
					array('form_child' => $this->load->view('laboratory/test_category/form', $data, true))
				);
		} else
		{
			$data = [
				"page" => $this->page."_".strtolower(__FUNCTION__),
				"item" => $item,
				"form" => TRUE,
			];
				
			$this->template
				->set( "heading", "Kategori Test" )
				->set_breadcrumb( lang("laboratory:breadcrumb"), base_url("helper") )
				->set_breadcrumb( lang("laboratory:create_heading") )
				->build('test_category/form', $data);
		}
	}
		
	public function edit( $id=0 )
	{				
		$item = $this->get_model()->get_one($id);
	
		if( $this->input->post() ) 
		{
			$data_post = array_merge((array)$item, $this->input->post('f'));
			unset($data_post['KategoriTestID']);
			$this->load->library( 'form_validation' );
			$this->form_validation->set_rules( $this->get_model()->rules['update'] );
			$this->form_validation->set_data( $data_post );
			
			if( $this->form_validation->run() )
			{
				if( $this->get_model()->update( $data_post, $id) )
				{
					
					make_flashdata(array(
							'response_status' => 'success',
							'message' => lang('global:updated_successfully')
						));
						
					redirect( 'laboratory/test_category' );
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
					'test_category/modal/create_edit', 
					array('form_child' => $this->load->view('test_category/form', $data, true))
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
				->set( "heading", "Kategori Test" )
				->set_breadcrumb( lang("common:page"), base_url("common") )
				->set_breadcrumb( lang("registrations:breadcrumb"), base_url("common/registrations") )
				->set_breadcrumb( lang("registrations:edit_heading") )
				->build('test_category/form', $data);
		}
	}
	
	public function delete( $id=0 )
	{
				
		$item = $this->get_model()->get_one( $id );
		if( $this->input->post() ) 
		{		
			
			if( 0 == @$item->{$this->get_model()->index_key} )
			{
				make_flashdata(array(
						'response_status' => 'error',
						'message' => lang( 'global:get_failed' )
					));
			
				redirect( $this->input->post( 'r_url' ) );
			}
			
			if( @$item->{$this->get_model()->index_key} == $this->input->post( 'confirm' ) )
			{
				$this->get_model()->delete( $id );				
				
				make_flashdata(array(
						'response_status' => 'success',
						'message' => lang('global:deleted_successfully')
					));
			}
			
			redirect( $this->input->post( 'r_url' ) );
		}
		
		$this->load->view( 'test_category/modal/delete', array('item' => $item) );
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
					->order_by('registration_title', 'asc')
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
					$item->registration_price = (float) $item->registration_price;
					
					$attr_data = "data-id=\"{$item->id}\" data-code=\"{$item->code}\" data-title=\"{$item->registration_title}\" data-price=\"{$item->registration_price}\" ";
					
					if( $selected == $item->code)
					{
						$options_html .= "\n<option {$attr_data}value=\"{$item->code}\" selected>{$item->code} - {$item->registration_title}</option>";
					} else
					{
						$options_html .= "\n<option {$attr_data}value=\"{$item->code}\">{$item->code} - {$item->registration_title}</option>";
					}
				}
				
				print( $options_html );
				exit();
			}
		}
	}
	
	public function lookup_patient( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			if( $this->input->get_post("is_modal") ){ $data["is_modal"] = TRUE; }
			
			$this->load->view( 'lookup/patients', (isset($data) ? $data : NULL) );
		} else
		{
			redirect( base_url( "common/patients/lookup" ) );
		}
	}
	
	public function lookup_schedule( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			if( $this->input->get_post("is_modal") ){ $data["is_modal"] = TRUE; }
			
			$this->load->view( 'lookup/schedules', (isset($data) ? $data : NULL) );
		} else
		{
			redirect( base_url( "Registrations/lookup/lookup" ) );
		}
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
			$db_like[ $this->db->escape_str("a.KategoriTestID") ] = $keywords;
			$db_like[ $this->db->escape_str("a.KategoriTestNama") ] = $keywords;
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
			a.KategoriTestID,
			a.KategoriTestNama,
			a.NoUrut,
			
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
	
	public function autocomplete()
	{
		$words = $this->input->get_post('query');
		
		$this->db
			->select( array("id", "code", "registration_title") )
			;
			
		$this->db
			->from( "common_registrations" )
			;
		
		$this->db
			->group_start()
				->where(array(
						'deleted_at' => NULL,
						'state' => 1
					))
			->group_end()
			;
		
		$this->db
			->group_start()
			->or_like(array(
					"code" => $words,
					"registration_title" => $words,
					"registration_description" => $words,
				))
			->group_end();
			
		$result = $this->db
			->get()
			->result()
			;
		
		if( $result )
		{
			$collection = array();
			foreach( $result as $item )
			{
				array_push($collection, array(
						"name" => $item->registration_title,
						"id" => $item->id,
					));
			}
		} else
		{
			$collection = array(array(
					"value" => 0,
					"label" => lang( "global:no_match" ),
					"id" => 0,
				));
		}
		
		header( 'Content-Type: application/json; charset=utf-8' );
		echo json_encode($collection);
		exit(0);
	}
}



