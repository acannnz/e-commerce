<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Detail_mutations extends Admin_Controller
{
	protected $_translation = 'inquiry';	
	protected $_model = 'inquiry_m';
	
	public function __construct()
	{
		parent::__construct();
		
		$this->load->model("inquiry_m");
		$this->load->helper("inquiry");
		
		$this->page = "inquiry";
	}
	
	public function index( $item = NULL )
	{
		$data = array(
				'create_detail' => base_url("inquiry/inquiries/details/item_create"),
				'delete_detail' => base_url("inquiry/inquiries/details/item_delete"),
				'view_detail' => base_url("inquiry/inquiries/details/item_view"),
			);
			
		$this->load->view( 'inquiries/form/detail_mutations', $data );		
	}

	public function view( $item = NULL )
	{
		$data = array(
				"collection" => inquiry_helper::get_mutation_detail( $item->No_Bukti ),
			);
			
		$this->load->view( 'inquiries/form/view_detail_mutations', $data );		
	}

	public function lookup_detail( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'inquiries/lookup/details' );
		} 
	}

	public function lookup_supplier( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'inquiries/details/lookup/suppliers' );
		} 
	}
	
	public function lookup_product( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request == true )
		{	
			$this->load->view( 'inquiries/details/lookup/products' );
		} 
	}

	public function item_create()
	{
		$item = array(
				'NoResep' => inquiry_helper::gen_detail_number(),
			);
		
		if( $this->input->post() ) 
		{
			
			
			$resep = $this->input->post("f");
			$detail = $this->input->post("details");
			
			$this->load->library( 'form_validation' );		
			$this->form_validation->set_rules( $this->get_model()->rules['insert'] );
			$this->form_validation->set_data( $this->input->post("f") );
			
			if( !$this->form_validation->run() )
			{
				$this->db->trans_begin();
					$resep['User_ID'] = $this->user_auth->User_ID;
					$this->db->insert("SIMtrResep", $resep );				
					$this->db->insert_batch("SIMtrResepDetail", $detail );
					
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
		
		$option_inquiry = $this->inquiry_m->get_options("SIMmSection", array("KelompokSection"  => "FARMASI", "GroupSection" => "4"));
		$lookup_supplier = base_url("inquiry/inquiries/details/lookup_supplier");
		$lookup_product = base_url("inquiry/inquiries/details/lookup_product");

		if( $this->input->is_ajax_request() )
		{
			$data = array(
					'item' => (object) $item,
					"is_ajax_request" => TRUE,
					"is_modal" => TRUE,
					"option_inquiry" => $option_inquiry,
					"lookup_supplier" => $lookup_supplier,
					"lookup_product" => $lookup_product
				);
			
			$this->load->view( 'inquiries/details/form', $data );
		} else
		{
			$data = array(
					"page" => $this->page."_".strtolower(__FUNCTION__),
					"item" => $item,
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
			
			$NoResep = $this->input->post("NoResep");
			
			$this->load->library( 'form_validation' );
			
			//$this->item->addData( $this->input->post("f") );
			//$this->form_validation->set_rules( $this->get_model()->rules['insert'] );
			//$this->form_validation->set_data( $this->item->toArray() );
			
			if( !$this->form_validation->run() )
			{
				$this->db->trans_begin();

					$this->db->delete("SIMtrResep", array("NoResep" => $NoResep) );				
					$this->db->delete("SIMtrResepDetail", array("NoResep" => $NoResep) );
					
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
					'item' => (object) @$item,
					"is_ajax_request" => TRUE,
					"is_modal" => TRUE,
					"option_inquiry" => $option_inquiry,
					"lookup_supplier" => $lookup_supplier,
					"lookup_product" => $lookup_product
				);
			
			$this->load->view( 'inquiries/details/form', $data );
		} 
	}			

	public function item_view( $NoResep )
	{

		if( $this->input->is_ajax_request() )
		{
			
			$item = $this->inquiry_m->get_row_data("SIMtrResep", array("NoResep"  => $NoResep));
			$doctor = $this->inquiry_m->get_row_data("mSupplier", array("Kode_Supplier"  => $item->DokterID));
			$collection = $this->inquiry_m->get_details_detail_data( array("NoResep"  => $NoResep));
			$option_inquiry = $this->inquiry_m->get_options("SIMmSection", array("KelompokSection"  => "FARMASI", "GroupSection" => "4"));
			$lookup_supplier = base_url("inquiry/inquiries/details/lookup_supplier");
			$lookup_product = base_url("inquiry/inquiries/details/lookup_product");

			$data = array(
					'item' => $item,
					'doctor' => $doctor,
					"collection" => $collection,
					"option_inquiry" => $option_inquiry,
					"lookup_supplier" => $lookup_supplier,
					"lookup_product" => $lookup_product,
					"is_ajax_request" => TRUE,
					"is_modal" => TRUE,
					"is_edit" => TRUE,
				);
			
			$this->load->view( 'inquiries/details/form_view', $data );
		} 
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