<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Prescriptions extends Admin_Controller
{
	protected $_translation = 'poly';	
	protected $_model = 'poly_m';
	protected $nameroutes = 'poly/inpatients/prescriptions';
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('inpatient');
		
		$this->load->model("poly_m");
		
		$this->load->helper("poly");	
	}
	
	public function index( $NoReg, $SectionID )
	{
		$data = array(
				'collection' => $this->poly_m->get_prescriptions_data( array("NoRegistrasi" => $NoReg, "SectionID" => $SectionID ) ),
				'NoReg' => $NoReg,
				'SectionID' => $SectionID,
				'nameroutes' => $this->nameroutes,
				'create_prescription' => base_url("{$this->nameroutes}/item_create/{$NoReg}/{$SectionID}"),
				'delete_prescription' => base_url("{$this->nameroutes}/item_delete"),
				'view_prescription' => base_url("{$this->nameroutes}/item_view"),
			);
			
		$this->load->view( 'inpatient/form/prescriptions', $data );		
	}

	public function lookup_prescription( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'inpatient/lookup/prescriptions' );
		} 
	}

	public function lookup_supplier( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'inpatient/prescriptions/lookup/suppliers' );
		} 
	}
	
	public function lookup_product( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request == true )
		{	
			$this->load->view( 'inpatient/prescriptions/lookup/products' );
		} 
	}

	public function item_create($NoReg, $SectionID)
	{
		$item = poly_helper::get_inpatient_examination_by(['a.RegNo' => $NoReg, 'a.SectionID' => $SectionID], TRUE);
		$item->NoResep = poly_helper::gen_prescription_number();

		if( $this->input->post() ) 
		{			
			$resep = $this->input->post("f");
			$resep['NoResep'] = $item->NoResep;
			$resep['User_ID'] = $this->user_auth->User_ID;
			
			if(empty($this->input->post("details")))
			{
				$response = [
					'status' => 'error',
					'message' => 'Belum ada Obat yang dipilih'
				];
				response_json($response);
			}
			
			$this->load->library( 'form_validation' );
			$this->form_validation->set_rules( $this->get_model()->rules['insert'] );
			$this->form_validation->set_data(  $this->input->post("f") );
			
			if( !$this->form_validation->run() )
			{
				$this->db->trans_begin();
					$this->db->insert("SIMtrResep", $resep );				
					
					$detail = [];
					foreach( $this->input->post("details") as $row ){
						$row['NoResep'] = $resep['NoResep'];
						$detail[] = $row;
					}
					$this->db->insert_batch("SIMtrResepDetail", $detail );
				
				if ( $this->db->trans_status() === FALSE )
				{
					$this->db->trans_rollback();
					$response = [
						"status" => 'error',
						"message" => lang('global:created_failed'),
						"code" => 500
					];
				}
				else
				{
					$this->db->trans_commit();
					$response = [
						"NoResep" => $resep['NoResep'],
						"status" => 'success',
						"message" => lang('global:created_successfully'),
						"code" => 200
					];
				}				

			} else
			{
				$response = [
					'status' => 'error',
					'message' => $this->form_validation->get_all_error_string()
				];
			}
			
			response_json($response);
		}
		
		$option_pharmacy = $this->poly_m->get_options("SIMmSection", array("TipePelayanan" => "FARMASI", "StatusAktif" => 1), "SectionName ASC");
		$lookup_supplier = base_url("{$this->nameroutes}/lookup_supplier");
		$lookup_product = base_url("{$this->nameroutes}/lookup_product");
		$option_dosis = $this->poly_m->get_options("SIMmDosisObat", array(), "KodeDosis ASC");

		if( $this->input->is_ajax_request() )
		{
			$data = array(
					'item' => $item,
					'nameroutes' => $this->nameroutes,
					"option_pharmacy" => $option_pharmacy,
					"option_dosis" => $option_dosis,
					"lookup_supplier" => $lookup_supplier,
					"lookup_product" => $lookup_product,
				);			
			$this->load->view( 'inpatient/prescriptions/form', $data );
		} 
	}

	public function item_delete()
	{
		
		if( $this->input->post() ) 
		{
			$NoResep = $this->input->post("NoResep");
			$item = $this->db->where('NoResep', $NoResep)->get('SIMtrResep')->row();
			
			if(@$item->Realisasi == 1)
			{
				$response = [
					"status" => 'error',
					"message" => 'Resep tidak bisa dihapus, karena sudah realisasi',
					"code" => 500
				];
				response_json($response);
			}
			
			$this->load->library( 'form_validation' );	
			/*$this->item->addData( $this->input->post("f") );
			$this->form_validation->set_rules( $this->get_model()->rules['insert'] );
			$this->form_validation->set_data( $this->item->toArray() );*/			
			if( !$this->form_validation->run() )
			{
				$this->db->trans_begin();

					$this->db->delete("SIMtrResep", ["NoResep" => $NoResep] );				
					$this->db->delete("SIMtrResepDetail", ["NoResep" => $NoResep] );

				if ($this->db->trans_status() === FALSE )
				{
					$this->db->trans_rollback();
					$response = [
						"status" => 'error',
						"message" => lang('global:deleted_failed'),
						"code" => 500
					];
				}
				else
				{
					$this->db->trans_commit();
					$response = [
						"status" => 'success',
						"message" => lang('global:deleted_successfully'),
						"code" => 200
					];
				}		

			} else
			{
				$response = [
					'status' => 'error',
					'message' => $this->form_validation->get_all_error_string()
				];
			}
			
			response_json($response);
		}
	}			

	public function item_view( $NoResep )
	{

		if( $this->input->is_ajax_request() )
		{
			
			$item = $this->poly_m->get_row_data("SIMtrResep", array("NoResep"  => $NoResep));
			$doctor = $this->poly_m->get_row_data("mSupplier", array("Kode_Supplier"  => $item->DokterID));
			$collection = $this->poly_m->get_prescriptions_detail_data( array("NoResep"  => $NoResep));
			$lookup_supplier = base_url("{$this->nameroutes}/lookup_supplier");
			$lookup_product = base_url("{$this->nameroutes}/lookup_product");
			$option_pharmacy = $this->poly_m->get_options("SIMmSection", array("TipePelayanan" => "FARMASI", "StatusAktif" => 1), "SectionName ASC");
			$option_dosis = $this->poly_m->get_options("SIMmDosisObat", array(), "KodeDosis ASC");

			$data = array(
					'item' => $item,
					'doctor' => $doctor,
					"collection" => $collection,
					"option_pharmacy" => $option_pharmacy,
					"option_dosis" => $option_dosis,
					"lookup_supplier" => $lookup_supplier,
					"lookup_product" => $lookup_product,
					'nameroutes' => $this->nameroutes,
					"is_ajax_request" => TRUE,
					"is_modal" => TRUE,
					"is_edit" => TRUE,
				);
			
			$this->load->view( 'inpatient/prescriptions/form_view', $data );
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