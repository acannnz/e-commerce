<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Service_polies extends Admin_Controller
{
	protected $_translation = 'poly';	
	protected $_model = 'poly_m';
	protected $nameroutes = 'poly/polies';
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('poly');
		
		$this->load->model("poly_m");
		
		$this->page = "common_icd";
		$this->template->title( lang("icd:page") . ' - ' . $this->config->item('company_name') );
	}
	
	public function index( $item = NULL, $is_edit = FALSE )
	{
		
		$data = array(
				"item" => $item,
				"collection" => $this->poly_m->get_service( @$item->NoBukti, $item, $is_edit ),
				"form" => TRUE,
				'datatables' => TRUE,
				'nameroutes' => $this->nameroutes,
				"lookup_service" => base_url("{$this->nameroutes}/service_polies/lookup_service"),
				'view_service' => base_url("{$this->nameroutes}/service_polies/item_view"),
				"get_service_component" => base_url("{$this->nameroutes}/service_polies/get_service_component"),
				"get_service_consumable" => base_url("{$this->nameroutes}/service_polies/get_service_consumable"),
			);
	
		$this->load->view( 'polies/form/service_polies', $data );		
	}
	
	public function lookup_service( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'polies/lookup/service_polies' );
		} 
	}

	public function lookup_service_consumable( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'polies/service_polies/lookup/service_consumable' );
		} 
	}
	
	public function item_view( $indexRow = NULL, $JasaID = NULL )
	{

		if( $this->input->is_ajax_request() )
		{	
			$data = array(
					'indexRow' => $indexRow,
					'JasaID' => $JasaID,
					"lookup_product" => base_url("{$this->nameroutes}/prescriptions/lookup_product"),
					'nameroutes' => $this->nameroutes,
					"is_ajax_request" => TRUE,
					"is_modal" => TRUE,
					"is_edit" => TRUE,
				);
			
			$this->load->view( 'polies/service_polies/form_detail', $data );
	
		} 
	}	

	public function service_component( $indexRow = NULL )
	{
		$data = array(
				//"ListHargaID" => $ListHargaID,
				"indexRow" => $indexRow,
				//"collection" => $this->poly_m->get_service_component( array("a.ListHargaID" => $ListHargaID, "JenisKerjasamaID" => $JenisKerjasamaID, "a.KelasID" => "xx") ),
				'nameroutes' => $this->nameroutes,
				"form" => TRUE,
				'datatables' => TRUE,
			);
		$this->load->view( 'polies/service_polies/table/service_component', $data );		
	}

	public function get_service_component()
	{
		if ( $this->input->is_ajax_request())
		{
			$NoBukti = $this->input->post("NoBukti");
			$ListHargaID = $this->input->post("ListHargaID");
			$JasaID = $this->input->post("JasaID");
			$Nomor = $this->input->post("Nomor");
			
			if ( $this->poly_m->check_service_component_transaction( array("NoBukti" => $NoBukti, "JasaID" => $JasaID, "ListHargaID" => $ListHargaID, "Nomor" => @$Nomor )) )
			{
				$collection = $this->poly_m->get_service_component_transaction($NoBukti, $JasaID);
			} else {
				$collection = $this->poly_m->get_service_component( $this->input->post() );
			}
			
			$response = array(
				"collection" => $collection,
				"status" => 'success',
				"message" => "get successfull",
				"code" => 200
			);
			
			print_r( json_encode( $response, JSON_NUMERIC_CHECK ) );
			exit(0);
		}
	}
		
	public function service_consumable( $indexRow = NULL )
	{
		$data = array(
				//"JasaID" => $JasaID,
				"indexRow" => $indexRow,
				'nameroutes' => $this->nameroutes,
				"form" => TRUE,
				'datatables' => TRUE,
				"lookup_service_consumable" => base_url("{$this->nameroutes}/service_polies/lookup_service_consumable"),
				"get_service_consumable" => base_url("{$this->nameroutes}/service_polies/get_service_consumable"),
			);

		$this->load->view( 'polies/service_polies/table/service_consumable', $data );		
	}			
	
	public function get_service_consumable()
	{
		if ( $this->input->is_ajax_request())
		{
			$data = (object) $this->input->post();
			if ( $this->poly_m->check_service_component_transaction( array("NoBUkti" => $data->NoBukti, "JasaID" => $data->JasaID, "Nomor" => @$data->Nomor ) ) )
			{
				$collection = $this->poly_m->get_service_consumable_transaction($data->NoBukti, $data->JasaID);
			} else {
				$collection = $this->poly_m->get_service_consumable( array("a.JasaID" => $data->JasaID), $data );
			}
			
			$response = array(
				"collection" => $collection,
				"status" => 'success',
				"message" => "get successfull",
				"code" => 200
			);
			
			print_r( json_encode( $response, JSON_NUMERIC_CHECK ) );
			exit(0);
		}
	}

	public function create()
	{
		$item = array(
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
		
		if( $this->input->post() ) 
		{
			
			
			$this->load->library( 'form_validation' );
			$this->form_validation->set_rules( $this->get_model()->rules['insert'] );
			$this->form_validation->set_data( $this->input->post("f") );
			
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
					'item' => $$this->input->post("header"),
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
					"item" => $$this->input->post("header"),
					"form" => TRUE,
				);
			
			$this->template
				->set( "heading", lang("icd:create_heading") )
				->set_breadcrumb( lang("common:page"), base_url("common") )
				->set_breadcrumb( lang("charges:breadcrumb"), base_url("common/icd") )
				->set_breadcrumb( lang("icd:create_heading") )
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