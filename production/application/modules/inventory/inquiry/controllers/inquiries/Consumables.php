<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Consumables extends Admin_Controller
{
	protected $_translation = 'poly';	
	protected $_model = 'poly_m';
	
	public function __construct()
	{
		parent::__construct();
		
		$this->load->model("poly_m");
		$this->load->helper("poly");
		
		$this->page = "common_icd";
		$this->template->title( lang("icd:page") . ' - ' . $this->config->item('company_name') );
	}
	
	public function index( $item = NULL )
	{
		$data = array(
				"collection" => $this->poly_m->get_child_data( "BILLFarmasi", array("NoReg" => @$item->NoReg ) ),
				"form" => TRUE,
				'datatables' => TRUE,
				'create_consumable' => base_url("poly/emergencies/consumables/item_create"),
				'delete_consumable' => base_url("poly/emergencies/consumables/item_delete"),
				'view_consumable' => base_url("poly/emergencies/consumables/item_view"),
			);

		$this->load->view( 'emergency/form/consumables', $data );		
	}
	
	public function lookup_prescription( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'emergency/lookup/consumables' );
		} 
	}

	public function lookup_supplier( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'emergency/consumables/lookup/suppliers' );
		} 
	}
	
	public function lookup_product( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request == true )
		{	
			$this->load->view( 'emergency/consumables/lookup/products' );
		} 
	}

	public function item_create()
	{
		$item_data = array(
				'NoBukti' => poly_helper::gen_bhp_number(),
				'IncludeJasa' => 1,
			);
			
		//print_r($item_data);exit;
		
		$this->load->library( 'my_object', $item_data, 'item' );
		
		if( $this->input->post() ) 
		{
			
			
			//print_r($this->input->post());exit;
			
			$bhp = $this->input->post("f");
			$detail = $this->input->post("details");
			
			$this->load->library( 'form_validation' );
			
			$this->item->addData( $this->input->post("f") );
			$this->form_validation->set_rules( $this->get_model()->rules['insert'] );
			$this->form_validation->set_data( $this->item->toArray() );
			
			if( !$this->form_validation->run() )
			{

				$this->db->trans_begin();
					$resep['UserID'] = $this->user_auth->User_ID;
					$this->db->insert("BILLFarmasi", $bhp );				
					$this->db->insert_batch("BILLFarmasiDetail", $detail );
					
				if ($this->db->trans_status() === FALSE)
				{
					$this->db->trans_rollback();
					$response = array(
							"status" => 'error',
							"message" => lang('global:created_failed'),
							"code" => 500
						);
				}
				else
				{
					$this->db->trans_commit();
					$response = array(
							"status" => 'success',
							"message" => lang('global:created_successfully'),
							"code" => 200
						);
				}				

			} else
			{
				$response = array(
						'response_status' => 'error',
						'message' => $this->form_validation->get_all_error_string()
					);
			}
			
			print_r(json_encode($response, JSON_NUMERIC_CHECK));
			exit(0);
		}
		
		$option_pharmacy = $this->poly_m->get_options("SIMmSection", array("KelompokSection"  => "FARMASI", "GroupSection" => "4"));
		$lookup_supplier = base_url("poly/emergencies/consumables/lookup_supplier");
		$lookup_product = base_url("poly/emergencies/consumables/lookup_product");

		if( $this->input->is_ajax_request() )
		{
			$data = array(
					'item' => (object) $this->item->toArray(),
					"is_ajax_request" => TRUE,
					"is_modal" => TRUE,
					"option_pharmacy" => $option_pharmacy,
					"lookup_supplier" => $lookup_supplier,
					"lookup_product" => $lookup_product
				);
			
			$this->load->view( 'emergency/consumables/form', $data );
		} else
		{
			$data = array(
					"page" => $this->page."_".strtolower(__FUNCTION__),
					"item" => $this->item,
					"form" => TRUE,
				);
			
			$this->template
				->set( "heading", lang("icd:create_heading") )
				->set_breadcrumb( lang("common:page"), base_url("common") )
				->set_breadcrumb( lang("services:breadcrumb"), base_url("common/icd") )
				->set_breadcrumb( lang("icd:create_heading") )
				->build('icd/form', $data);
		}
	}

	public function item_delete()
	{
		
		if( $this->input->post() ) 
		{
			
			
			//print_r($this->input->post());exit;
			
			$NoBukti = $this->input->post("NoBukti");
			
			$this->load->library( 'form_validation' );
			
			//$this->item->addData( $this->input->post("f") );
			//$this->form_validation->set_rules( $this->get_model()->rules['insert'] );
			//$this->form_validation->set_data( $this->item->toArray() );
			
			if( !$this->form_validation->run() )
			{
				$this->db->trans_begin();

					$this->db->delete("BILLFarmasi", array("NoBukti" => $NoBukti) );				
					$this->db->delete("BILLFarmasiDetail", array("NoBukti" => $NoBukti) );
					
				if ($this->db->trans_status() === FALSE)
				{
					$this->db->trans_rollback();
					$response = array(
							"status" => 'error',
							"message" => lang('global:deleted_failed'),
							"code" => 500
						);
				}
				else
				{
					$this->db->trans_commit();
					$response = array(
							"status" => 'success',
							"message" => lang('global:deleted_successfully'),
							"code" => 200
						);
				}				

			} else
			{
				$response = array(
						'response_status' => 'error',
						'message' => $this->form_validation->get_all_error_string()
					);
			}
			
			print_r(json_encode($response, JSON_NUMERIC_CHECK));
			exit(0);
		}

		if( $this->input->is_ajax_request() )
		{
			$data = array(
					'item' => (object) $this->item->toArray(),
					"is_ajax_request" => TRUE,
					"is_modal" => TRUE,
					"option_pharmacy" => $option_pharmacy,
					"lookup_supplier" => $lookup_supplier,
					"lookup_product" => $lookup_product
				);
			
			$this->load->view( 'emergency/consumables/form', $data );
		} 
	}			

	public function item_view( $NoBukti )
	{

		if( $this->input->is_ajax_request() )
		{
			
			$item = $this->poly_m->get_row_data("BILLFarmasi", array("NoBukti"  => $NoBukti));
			$doctor = $this->poly_m->get_row_data("mSupplier", array("Kode_Supplier"  => $item->DokterID));
			$collection = $this->poly_m->get_consumable_detail_data( array("NoBukti"  => $NoBukti));
			$option_pharmacy = $this->poly_m->get_options("SIMmSection", array("KelompokSection"  => "FARMASI", "GroupSection" => "4"));
			$lookup_supplier = base_url("poly/emergencies/consumables/lookup_supplier");
			$lookup_product = base_url("poly/emergencies/consumables/lookup_product");

			$data = array(
					'item' => $item,
					'doctor' => $doctor,
					"collection" => $collection,
					"option_pharmacy" => $option_pharmacy,
					"lookup_supplier" => $lookup_supplier,
					"lookup_product" => $lookup_product,
					"is_ajax_request" => TRUE,
					"is_modal" => TRUE,
					"is_edit" => TRUE,
				);
			
			$this->load->view( 'emergency/consumables/form_view', $data );
		} 
	}
	
	public function create()
	{
		$item_data = array(
				'id' => 0,
				'code' => null,
				'version' => "ICD10",
				'section' => null,
				'subsection' => null,
				'long_desc' => null,
				'short_desc' => null,
				'status' => 1,
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
					$this->get_model()->delete_cache( 'common_icd.collection' );
					
					make_flashdata(array(
							'response_status' => 'success',
							'message' => lang('global:created_successfully')
						));
						
					redirect( 'common/icd' );
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
					'icd/modal/create_edit', 
					array('form_child' => $this->load->view('icd/form', $data, true))
				);
		} else
		{
			$data = array(
					"page" => $this->page."_".strtolower(__FUNCTION__),
					"item" => $this->item,
					"form" => TRUE,
				);
			
			$this->template
				->set( "heading", lang("icd:create_heading") )
				->set_breadcrumb( lang("common:page"), base_url("common") )
				->set_breadcrumb( lang("services:breadcrumb"), base_url("common/icd") )
				->set_breadcrumb( lang("icd:create_heading") )
				->build('icd/form', $data);
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
					$this->get_model()->delete_cache( 'common_icd.collection' );
					
					make_flashdata(array(
							'response_status' => 'success',
							'message' => lang('global:updated_successfully')
						));
						
					redirect( 'common/icd' );
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
					'icd/modal/create_edit', 
					array('form_child' => $this->load->view('icd/form', $data, true))
				);
		} else
		{
			$data = array(
					"page" => $this->page,
					"item" => $this->item,
					"form" => TRUE,
				);
			
			$this->template
				->set( "heading", lang("icd:edit_heading") )
				->set_breadcrumb( lang("common:page"), base_url("common") )
				->set_breadcrumb( lang("services:breadcrumb"), base_url("common/icd") )
				->set_breadcrumb( lang("icd:edit_heading") )
				->build('icd/form', $data);
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
				
				$this->get_model()->delete_cache( 'common_icd.collection' );
				
				make_flashdata(array(
						'response_status' => 'success',
						'message' => lang('global:deleted_successfully')
					));
			}
			
			redirect( $this->input->post( 'r_url' ) );
		}
		
		$this->load->view( 'icd/modal/delete', array('item' => $this->item) );
	}
	
	public function lookup( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'icd/lookup/datatable' );
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
				->build('icd/lookup', (isset($data) ? $data : NULL));
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
		$this->db->from( "common_icd" );
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		$records_total = $this->db->count_all_results();
		
		// get total filtered
		$this->db->from( "common_icd" );
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }		
		$records_filtered = $this->db->count_all_results();
		
		// get result filtered
		$this->db->from( "common_icd" );
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