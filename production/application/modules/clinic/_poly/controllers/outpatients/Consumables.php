<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Consumables extends Admin_Controller
{
	protected $_translation = 'poly';	
	protected $_model = 'poly_m';
	protected $nameroutes = 'poly/outpatients/consumables';
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('outpatient');
		
		$this->load->model("poly_m");
		$this->load->model("section_model");
		$this->load->helper("poly");
		
	}
	
	public function index( $NoReg, $SectionID )
	{
		$data = [
			'collection' => $this->poly_m->get_child_data( "SIMtrPOP", ["Batal" => 0,"NoReg" => $NoReg] ),
			'nameroutes' => $this->nameroutes,
			'create_consumable' => base_url("{$this->nameroutes}/item_create/{$NoReg}/$SectionID"),
			'delete_consumable' => base_url("{$this->nameroutes}/item_delete"),
			'view_consumable' => base_url("{$this->nameroutes}/item_view"),
		];

		$this->load->view( 'outpatient/form/consumables', $data );		
	}

	public function lookup_prescription( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'outpatient/lookup/consumables' );
		} 
	}

	public function lookup_supplier( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'outpatient/consumables/lookup/suppliers', array("type" => "doctor" ));
		} 
	}
	
	public function lookup_product( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request == true )
		{	
			$this->load->view( 'outpatient/consumables/lookup/products' );
		} 
	}

	public function item_create($NoReg, $SectionID)
	{
		$item = poly_helper::get_outpatient($NoReg, $SectionID);
		$item->NoBukti = poly_helper::gen_bhp_number();
		$item->Ditagihkan = 1;
		// print_r($item->NoBukti);exit;
		if( $this->input->post() ) 
		{
			$bhp = $this->input->post("f");
			$bhp['NoBuktiPOP'] = $item->NoBukti;
			$detail = $this->input->post("details");
			// print_r($bhp);exit;
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
					$bhp['User_ID'] = $this->user_auth->User_ID;
					// print_r($detail);exit;
					// $this->db->insert("EXEC Pelayanan_SIMtrPOP_Insert1 '$bhp->NoBukti', '$bhp->Tanggal', '$bhp->Jam', '$bhp->SectionID', '','$bhp->UserID', '', '', '$bhp->DokterID', '', '$bhp->NRM', '$bhp->NoReg', '$bhp->Ditagihkan', '1'" );
					$this->db->insert("SIMtrPOP", $bhp );				

					$section = "SECT0002";
					foreach( $detail as $k => $v ) 
					{
						$v['NoBuktiPOP'] = $bhp['NoBuktiPOP'];
						// print_r($v);exit;
						$this->db->insert("SIMtrDetailPOP", $v );
						
						# Pengurangan Stock
						$_insert_fifo = [
							'location_id' => $section, 
							'item_id' => $v["Barang_ID"],  
							'item_unit_code' => $v["Satuan"],  
							'qty' => $v["Qty"], 
							'price' => $v["HargaPersediaan"],  
							'conversion' => 1,  
							'evidence_number' => $item->NoBukti,  
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
					$this->db->trans_commit();
					$response = [
						"NoBukti" => $bhp['NoBuktiPOP'],
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
			
			$this->load->view( 'outpatient/consumables/form', $data );
		}
	}

	public function item_delete()
	{
		
		if( $this->input->post() ) 
		{

			$NoBukti = $this->input->post("NoBuktiPOP");

			$this->load->library( 'form_validation' );
			/*$this->item->addData( $this->input->post("f") );
			$this->form_validation->set_rules( $this->get_model()->rules['insert'] );
			$this->form_validation->set_data( $this->item->toArray() );			*/
			if( !$this->form_validation->run() )
			{
				$this->db->trans_begin();
					
					$this->db->update("SIMtrPOP", ["Batal" => 1], ["NoBuktiPOP" => @$NoBukti]);
					$detailPOP = $this->db->where( ["NoBuktiPOP" => @$NoBukti] )->get("SIMtrDetailPOP")->result();
					$itemPOP = $this->db->where('NoBuktiPOP', $NoBukti)->get('SIMtrPOP')->row();
					// print_r($itemPOP);exit;
					$section = $this->section_model->get_one($itemPOP->SectionID);
					foreach( $detailPOP as $v )
					{
						$_insert_warehouse_fifo = [
							'location_id' => $section->Lokasi_ID, 
							'item_id' => $v->Barang_Id,  
							'item_unit_code' => $v->Satuan,  
							'qty' => $v->Qty, 
							'price' => $v->HargaOrig,  
							'conversion' => 1,  
							'evidence_number' => $NoBukti.'-R',  
							'trans_type_id' => 562,
							'in_out_state' => 1,
							'trans_date' => date('Y-m-d'),  
							'exp_date' => '',  
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
				// $this->db->trans_rollback();
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
			
			$item = $this->poly_m->get_row_data("SIMtrPOP", array("NoBuktiPOP"  => $NoBukti));
			$doctor = $this->poly_m->get_row_data("mSupplier", array("Kode_Supplier"  => $item->DokterID));
			$collection = $this->poly_m->get_consumable_detail_data( array("NoBuktiPOP"  => $NoBukti));
			$section = $this->section_model->get_one($item->SectionID);
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
					"section" => $section
				);
			
			$this->load->view( 'outpatient/consumables/form_view', $data );
		} 
	}
		
	public function lookup( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'icd/lookup/datatable' );
		} 
	}
}