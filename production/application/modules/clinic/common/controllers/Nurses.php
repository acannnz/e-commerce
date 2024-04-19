<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Nurses extends Admin_Controller
{
	protected $_translation = 'common';	
	protected $_model = 'nurse_m';
	
	public function __construct()
	{
		parent::__construct();
		
		$this->load->model( "nurse_m" );

		$this->page = "common_nurses";
		$this->template->title( lang("nurses:page") . ' - ' . $this->config->item('company_name') );
	}
	
	public function index()
	{
		$data = array(
				'page' => $this->page,
				"form" => TRUE,
				'datatables' => TRUE,
			);
		
		$this->template
			->set( "heading", lang("nurses:page") )
			->set_breadcrumb( lang("common:page"), base_url("common") )
			->set_breadcrumb( lang("nurses:breadcrumb") )
			->build('nurses/datatable', (isset($data) ? $data : NULL));
	}
	
	public function create()
	{
		$item_data = array(
				'id' => 0,
				'code' => null,
				'nurse_title' => null,
				'nurse_description' => null,
				'nurse_price' => null,
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
					$this->get_model()->delete_cache( 'common_nurses.collection' );
					
					make_flashdata(array(
							'response_status' => 'success',
							'message' => lang('global:created_successfully')
						));
						
					redirect( 'common/nurses' );
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
					'nurses/modal/create_edit', 
					array('form_child' => $this->load->view('nurses/form', $data, true))
				);
		} else
		{
			$data = array(
					"page" => $this->page."_".strtolower(__FUNCTION__),
					"item" => $this->item,
					"form" => TRUE,
				);
			
			$this->template
				->set( "heading", lang("nurses:create_heading") )
				->set_breadcrumb( lang("common:page"), base_url("common") )
				->set_breadcrumb( lang("nurses:breadcrumb"), base_url("common/nurses") )
				->set_breadcrumb( lang("nurses:create_heading") )
				->build('nurses/form', $data);
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
					$this->get_model()->delete_cache( 'common_nurses.collection' );
					
					make_flashdata(array(
							'response_status' => 'success',
							'message' => lang('global:updated_successfully')
						));
						
					redirect( 'common/nurses' );
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
					'nurses/modal/create_edit', 
					array('form_child' => $this->load->view('nurses/form', $data, true))
				);
		} else
		{
			$data = array(
					"page" => $this->page,
					"item" => $this->item,
					"form" => TRUE,
				);
			
			$this->template
				->set( "heading", lang("nurses:edit_heading") )
				->set_breadcrumb( lang("common:page"), base_url("common") )
				->set_breadcrumb( lang("nurses:breadcrumb"), base_url("common/nurses") )
				->set_breadcrumb( lang("nurses:edit_heading") )
				->build('nurses/form', $data);
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
				
				$this->get_model()->delete_cache( 'common_nurses.collection' );
				
				make_flashdata(array(
						'response_status' => 'success',
						'message' => lang('global:deleted_successfully')
					));
			}
			
			redirect( $this->input->post( 'r_url' ) );
		}
		
		$this->load->view( 'nurses/modal/delete', array('item' => $this->item) );
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
					->order_by('nurse_title', 'asc')
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
					$item->nurse_price = (float) $item->nurse_price;
					
					$attr_data = "data-id=\"{$item->id}\" data-code=\"{$item->code}\" data-title=\"{$item->nurse_title}\" data-price=\"{$item->nurse_price}\" ";
					
					if( $selected == $item->code)
					{
						$options_html .= "\n<option {$attr_data}value=\"{$item->code}\" selected>{$item->code} - {$item->nurse_title}</option>";
					} else
					{
						$options_html .= "\n<option {$attr_data}value=\"{$item->code}\">{$item->code} - {$item->nurse_title}</option>";
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
			$this->load->view( 'nurses/lookup/datatable' );
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
				->build('nurses/lookup', (isset($data) ? $data : NULL));
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
		
		$db_from = "{$this->nurse_m->table} a";
		$db_where = array();
		$db_like = array();
		
		// prepare defautl flter
		
		// preparing default
		if( isset($search['value']) && ! empty($search['value']) )
        {
            $keywords = $this->db->escape_str( $search['value'] );
			
			$db_like[ $this->db->escape_str("a.PersonalID") ] = $keywords;
			$db_like[ $this->db->escape_str("a.Nama") ] = $keywords;
			$db_like[ $this->db->escape_str("a.Phone") ] = $keywords;
			$db_like[ $this->db->escape_str("a.Gender") ] = $keywords;
				
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
				a.PersonalID
				,a.TglMasuk
				,a.Nama
				,a.Gender
				,a.GolDarah
				,a.TglLahir
				,a.TempatLahir
				,a.AlamatSekarang
				,a.Kota
				,a.KodePos
				,a.Phone
				,a.AlamatTetap
				,a.KotaTetap
				,a.KodePosTetap
				,a.PhoneTetap
				,a.NoHP
				,a.Email
				,a.BankNama
				,a.BankRekening
				,a.NomorIDCard
				,a.NPWP
				,a.NoJamsostek
				,a.NoAsuransi
				,a.Agama
				,a.MaritalStatus
				,a.KategoriPegawaiID
				,a.StatusPegawai
				,a.FilePicture
				,a.DateUpdate
				,a.IDUserUpdate
				,a.Picture
				,a.UnitBisnisID
				,a.PangkatID
				,a.StrukturCode
				,a.PosisiID
				,a.GajiPokok
				,a.IndexID
				,a.ExSHI
				,a.JabatanID
				
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
			->select( array("id", "code", "nurse_title") )
			;
			
		$this->db
			->from( "common_nurses" )
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
					"nurse_title" => $words,
					"nurse_description" => $words,
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
						"name" => $item->nurse_title,
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



