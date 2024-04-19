<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Consumables extends Admin_Controller
{
	protected $_translation = 'poly';	
	protected $_model = 'poly_m';
	protected $nameroutes = 'poly/inpatients/consumables';
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('inpatient');
		
		$this->load->model("poly_m");
		$this->load->helper("poly");		
	}
	
	public function index( $NoReg, $SectionID )
	{
		$data = [
			'collection' => $this->poly_m->get_child_data( "BILLFarmasi", ["NoReg" => $NoReg, "SectionAsalID" => $SectionID, "Retur" => 0] ),
			'nameroutes' => $this->nameroutes,
			'create_consumable' => base_url("{$this->nameroutes}/item_create/{$NoReg}/{$SectionID}"),
			'delete_consumable' => base_url("{$this->nameroutes}/item_delete"),
			'view_consumable' => base_url("{$this->nameroutes}/item_view"),
		];

		$this->load->view( 'inpatient/form/consumables', $data );		
	}

	public function lookup_prescription( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'inpatient/lookup/consumables' );
		} 
	}

	public function lookup_supplier( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'inpatient/consumables/lookup/suppliers' );
		} 
	}
	
	public function lookup_product( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request == true )
		{	
			$this->load->view( 'inpatient/consumables/lookup/products' );
		} 
	}

	public function item_create($NoReg, $SectionID)
	{
		$item = poly_helper::get_inpatient_examination_by(['a.RegNo' => $NoReg, 'a.SectionID' => $SectionID], TRUE);
		$item->NoBukti = poly_helper::gen_bhp_number();
		$item->IncludeJasa = 1;
			
		if( $this->input->post() ) 
		{
			$bhp = $this->input->post("f");
			$bhp['NoBukti'] = $item->NoBukti;
			$detail = $this->input->post("details");
			if(empty($detail))
			{
				$response = [
					'status' => 'error',
					'message' => 'Belum ada Obat yang dipilih'
				];
				response_json($response);
			}
			
			$this->load->library( 'form_validation' );		
			$this->form_validation->set_rules( $this->get_model()->rules['insert'] );
			$this->form_validation->set_data( $this->input->post("f") );			
			if( !$this->form_validation->run() )
			{
				$this->db->trans_begin();
					$bhp['Tanggal'] = date('Y-m-d');
					$bhp['Jam'] = date('Y-m-d H:i:s');
					$bhp['UserID'] = $this->user_auth->User_ID;
					
					$this->db->insert("BILLFarmasi", $bhp );				

					$section = $this->section_model->get_one($SectionID);
					foreach( $detail as $k => $v )
					{
						$v['NoBukti'] = $bhp['NoBukti'];
						$this->db->insert("BILLFarmasiDetail", $v );
						
						# Pengurangan Stock
						$_insert_fifo = [
							'location_id' => $section->Lokasi_ID, 
							'item_id' => $v["Barang_ID"],  
							'item_unit_code' => $v["Satuan"],  
							'qty' => $v["JmlObat"], 
							'price' => $v["Harga"],  
							'conversion' => 1,  
							'evidence_number' => $bhp['NoBukti'],  
							'trans_type_id' => 564,
							'in_out_state' => 0,
							'trans_date' => date('Y-m-d'),  
							'exp_date' => NULL,  
							'item_type_id' => 0, 
						];
						poly_helper::insert_warehouse_fifo( $_insert_fifo );
					}
										
				if ($this->db->trans_status() === FALSE)
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
					//$this->db->trans_rollback();
					$this->db->trans_commit();
					$response = [
						"NoBukti" => $bhp['NoBukti'],
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
		
		$option_pharmacy = $this->poly_m->get_options("SIMmSection", array("KelompokSection"  => "FARMASI", "GroupSection" => "4"));
		$lookup_supplier = base_url("{$this->nameroutes}/lookup_supplier");
		$lookup_product = base_url("{$this->nameroutes}/lookup_product");

		if( $this->input->is_ajax_request() )
		{
			$data = [
				'item' => $item,
				'nameroutes' => $this->nameroutes,
				"option_pharmacy" => $option_pharmacy,
				"lookup_supplier" => $lookup_supplier,
				"lookup_product" => $lookup_product
			];
			
			$this->load->view( 'inpatient/consumables/form', $data );
		}
	}

	public function item_delete()
	{		
		if( $this->input->post() ) 
		{
			$NoBukti = $this->input->post("NoBukti");	
			$this->load->library( 'form_validation' );
			/*$this->item->addData( $this->input->post("f") );
			$this->form_validation->set_rules( $this->get_model()->rules['insert'] );
			$this->form_validation->set_data( $this->item->toArray() );*/
			if( !$this->form_validation->run() )
			{
				$this->db->trans_begin();
					
					$this->db->update("BILLFarmasi", ["Retur" => 1], ["NoBukti" => @$NoBukti]);
					$detail = $this->db->where( ["NoBukti" => @$NoBukti] )->get("BILLFarmasiDetail")->result();
					$item = $this->db->where('NoBukti', $NoBukti)->get('BILLFarmasi')->row();
					
					$section = $this->section_model->get_one($item->SectionID);
					foreach( $detail as $v )
					{
						$_insert_warehouse_fifo = [
							'location_id' => $this->section->Lokasi_ID, 
							'item_id' => $v->Barang_ID,  
							'item_unit_code' => $v->Satuan,  
							'qty' => $v->JmlObat, 
							'price' => $v->Harga,  
							'conversion' => 1,  
							'evidence_number' => $NoBukti.'-R',  
							'trans_type_id' => 562,
							'in_out_state' => 1,
							'trans_date' => date('Y-m-d'),  
							'exp_date' => NULL,  
							'item_type_id' => 0, 
						];
						
						poly_helper::insert_warehouse_fifo( $_insert_warehouse_fifo );
					}
										
				if ($this->db->trans_status() === FALSE)
				{
					$this->db->trans_rollback();
					$response = [
						"status" => 'error',
						"message" => lang('global:deleted_failed'),
						"code" => 500
					];
				}
				//$this->db->trans_rollback();
				$this->db->trans_commit();
				$response = [
					"status" => 'success',
					"message" => lang('global:deleted_successfully'),
				];

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

	public function item_view( $NoBukti )
	{

		if( $this->input->is_ajax_request() )
		{
			
			$item = $this->poly_m->get_row_data("BILLFarmasi", array("NoBukti"  => $NoBukti));
			$doctor = $this->poly_m->get_row_data("mSupplier", array("Kode_Supplier"  => $item->DokterID));
			$collection = $this->poly_m->get_consumable_detail_data( array("NoBukti"  => $NoBukti));
			$option_pharmacy = $this->poly_m->get_options("SIMmSection", array("KelompokSection"  => "FARMASI", "GroupSection" => "4"));
			$lookup_supplier = base_url("{$this->nameroutes}/lookup_supplier");
			$lookup_product = base_url("{$this->nameroutes}/lookup_product");

			$data = array(
					'item' => $item,
					'doctor' => $doctor,
					"collection" => $collection,
					"option_pharmacy" => $option_pharmacy,
					"lookup_supplier" => $lookup_supplier,
					"lookup_product" => $lookup_product,
					'nameroutes' => $this->nameroutes,
					"is_ajax_request" => TRUE,
					"is_modal" => TRUE,
					"is_edit" => TRUE,
				);
			
			$this->load->view( 'inpatient/consumables/form_view', $data );
		} 
	}
		
	public function lookup( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'icd/lookup/datatable' );
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