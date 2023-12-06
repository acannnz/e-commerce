<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Outpatient extends Admin_Controller
{
	protected $_translation = 'poly';	
	protected $_model = 'poly_m';
	protected $nameroutes = 'poly/outpatient';
	protected $viewroutes;
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('outpatient');
		$this->simple_login->set_medics('outpatient');
		
		$this->page = "poly";
		$this->viewroutes = config_item('viewroutes') ? config_item('viewroutes') : 'outpatient'; 
		$this->template
			->title( lang('nav:outpatient') .' - '. config_item('company_name') )
			->set_breadcrumb( lang('nav:outpatient'), base_url($this->nameroutes) );
					
		$this->load->model("poly_m");
		$this->load->model("poly_nurse_model");
		$this->load->model("poly_transaction_detail_model");
		$this->load->model("poly_transaction_model");
		$this->load->model("poly_transaction_pop_model");
		$this->load->model("poly_destination_model");
		$this->load->model("poly_initial_diagnosis_model");
		$this->load->model("emr_vital_signs_model");
		$this->load->model("emr_soap_notes_model");		
				
		$this->load->model("reservation_model");
		$this->load->model("registration_model");
		$this->load->model("registration_data_model");
		$this->load->model("helper_memo_model");

		$this->load->model("patient_model");
		$this->load->model("patient_type_model");
		$this->load->model("supplier_model");
		$this->load->model("section_model");
			
		$this->load->helper( "poly" );
	}
	
	public function index()
	{
		$data = [
			'page' => $this->page,
			'nameroutes' => $this->nameroutes,
			'option_doctor' => poly_helper::option_doctor(),
			'form' => TRUE,
			'datatables' => TRUE,
		];
		
		$this->template
			->set( "heading", lang('poly:list_heading') .' '. lang('nav:outpatient'))
			->build('outpatient/datatable', $data);
	}
	
	public function create( $NoReg, $SectionID )
	{
		$examination = $this->poly_m->get_by(["RegNo" => $NoReg, "SectionID" => $SectionID, 'Batal' => 0]);		
		if(!empty($examination->NoBukti)){
			redirect("{$this->nameroutes}/edit/{$examination->NoBukti}");
		}

		$item = poly_helper::get_outpatient( $NoReg, $SectionID);
		$item->NoBukti = poly_helper::gen_evidence_number($SectionID);
		$item->TindakLanjut_Pulang = 1;
		
		if($item->DokterID == 'XX'):
			$medics = $this->session->userdata('outpatient');
			$item->DokterID = $medics['doctor_id'];
			$item->NamaDokter = $this->supplier_model->get_by(['Kode_Supplier' => $item->DokterID])->Nama_Supplier;
		endif;
		
		if( $this->input->post() ) 
		{
			$rj = $this->input->post("rj");
			$diagnosis = (array) $this->input->post("diagnosis");
			$service = (array) $this->input->post("service");
			$service_component = (array) $this->input->post("service_component");
			$service_consumable = (array) $this->input->post("service_consumable");
			$vital = (array) $this->input->post("vital");
			$soap = (array) $this->input->post("soap");
			$nurse = (array) $this->input->post("nurse");
			$helper = (array) $this->input->post("helper");
			$checkout = $this->input->post("checkout");	
			$consult = (array)$this->input->post("consult");	
			$patient = (array)$this->input->post("patient");	
			$odontogram = (array)$this->input->post("odontogram");
			
			$this->load->library( 'form_validation' );
			$this->form_validation->set_rules( $this->get_model()->rules['insert'] );
			$this->form_validation->set_data( $this->input->post("rj") );			
			if( $this->form_validation->run() )
			{
				$message = poly_helper::create_examination($rj, $diagnosis, $service, $service_component, $service_consumable, $vital, $soap, $nurse, $helper, $checkout, $consult, $patient, $odontogram);	
			} else
			{
				$message = [
					"status" => 'error',
					"message" => $this->form_validation->get_all_error_string(),
					"code" => 500
				];
			}
			response_json( $message );		
		}

		$option_patient_type = $this->poly_m->get_option_patient_type();
		
		if( $this->input->is_ajax_request() )
		{
			$data = [
				'item' => $item,
				"patient" => $patient,
				"cooperation" => $cooperation,
				"is_ajax_request" => TRUE,
				"is_modal" => TRUE,
			];
			
			$this->load->view( 
					'outpatient/modal/create_edit', 
					array('form_child' => $this->load->view('outpatient/form', $data, true))
				);
		} else
		{
			$data = [
				"page" => $this->page."_".strtolower(__FUNCTION__),
				"item" => $item,
				"option_patient_type" => $option_patient_type,
				"user" => $this->simple_login->get_user(),
				"lookup_supplier" => base_url("{$this->nameroutes}/lookup_supplier"),
				"lookup_supplier_consul" => base_url("{$this->nameroutes}/lookup_supplier_consul"),
				"nameroutes" => $this->nameroutes,
				"viewroutes" => $this->viewroutes,
				"form" => TRUE,
				"datatables" => TRUE,
			];
			
			$this->template
				->set( "heading", sprintf(lang('poly:examination_heading'), ucfirst_case( $item->SectionName )) )
				->set_breadcrumb( sprintf(lang('poly:examination_heading'), ucfirst_case( $item->SectionName )) )
				->build("{$this->viewroutes}/form", $data);
		}
	}
	
	public function edit( $NoBukti = NULL )
	{
		$examination = $this->poly_m->get_one($NoBukti);
		$item = poly_helper::get_outpatient( $examination->RegNo, $examination->SectionID, TRUE);
		$item->NoBukti = $examination->NoBukti;
		$item->TindakLanjutCekUpUlang = $examination->TindakLanjutCekUpUlang;
		$item->TglCekUp = $examination->TglCekUp;
		$item->Symptom = $examination->Symptom;
		$item->Therapi = $examination->Therapi;

		if ($item->StatusBayar == "Sudah Bayar" || $item->ProsesPayment == 1)
		{
			redirect("{$this->nameroutes}/view/{$NoBukti}");
		}

		if( $this->input->post() ) 
		{
			$rj = $this->input->post("rj");
			$diagnosis = (array) $this->input->post("diagnosis");
			$service = (array) $this->input->post("service");
			$service_component = (array) $this->input->post("service_component");
			$service_consumable = (array) $this->input->post("service_consumable");
			$vital = (array) $this->input->post("vital");
			$soap = (array) $this->input->post("soap");
			$nurse = (array) $this->input->post("nurse");
			$helper = (array) $this->input->post("helper");
			$checkout = $this->input->post("checkout");	
			$consult = (array)$this->input->post("consult");	
			$patient = (array)$this->input->post("patient");
			$odontogram = (array)$this->input->post("odontogram");	
			
			$this->load->library( 'form_validation' );		
			$this->form_validation->set_rules( $this->get_model()->rules['insert'] );
			$this->form_validation->set_data(  $rj );
			if( $this->form_validation->run() )
			{
				$message = poly_helper::update_examination($rj, $diagnosis, $service, $service_component, $service_consumable, $vital, $soap, $nurse, $helper, $checkout, $consult, $patient, $odontogram);
			} else
			{
				$message = [
					"status" => 'error',
					"message" => $this->form_validation->get_all_error_string(),
					"code" => 500
				];
			}
			
			response_json($message);
		}
		
		$option_patient_type = $this->poly_m->get_option_patient_type();
		if( $this->input->is_ajax_request() )
		{
			$data = array(
					'item' => (object)$item,
					"is_ajax_request" => TRUE,
					"is_modal" => TRUE,
				);
			
			$this->load->view( 
					'outpatient/modal/create_edit', 
					array('form_child' => $this->load->view('outpatient/form', $data, true))
				);
		} else
		{
			$data = array(
					"page" => $this->page."_".strtolower(__FUNCTION__),
					"item" => $item,
					"option_patient_type" => $option_patient_type,
					"user" => $this->simple_login->get_user(),
					"lookup_supplier" => base_url("{$this->nameroutes}/lookup_supplier"),
					"cancel_link" => base_url("{$this->nameroutes}/cancel/{$NoBukti}"),
					'nameroutes' => $this->nameroutes,
					"viewroutes" => $this->viewroutes,
					"form" => TRUE,
					"datatables" => TRUE,
					"is_edit" => TRUE,
					"typeahead" => TRUE,
					"collection_odontogram" => $this->db->where("a.NoBukti", $NoBukti)->from("SIMtrEMROdontogram a")->join("SIMmOdontogram b", "a.Odontogram_ID=b.Odontogram_ID", "INNER")->get()->result(),
				);
		
			$this->template
				->set( "heading", sprintf(lang('poly:examination_heading'), ucfirst_case( $item->SectionName )) )
				->set_breadcrumb( sprintf(lang('poly:examination_heading'), ucfirst_case( $item->SectionName )) )
				->build("{$this->viewroutes}/form", $data);
		}
	}

	public function view( $NoBukti = 0 )
	{
		
		$examination = $this->poly_m->get_one($NoBukti);
		$item = poly_helper::get_outpatient( $examination->RegNo, $examination->SectionID, TRUE);
		$item->NoBukti = $examination->NoBukti;
		$item->TindakLanjutCekUpUlang = $examination->TindakLanjutCekUpUlang;
		$item->TglCekUp = $examination->TglCekUp;
		$item->Symptom = $examination->Symptom;
		$item->Therapi = $examination->Therapi;

		if($item->StatusBayar == "Belum" && $item->ProsesPayment == 0)
		{
			redirect("{$this->nameroutes}/edit/{$NoBukti}");
		}
			
		$option_patient_type = $this->poly_m->get_option_patient_type();

		if( $this->input->is_ajax_request() )
		{
			$data = array(
					'item' => $item,
					"is_ajax_request" => TRUE,
					"is_modal" => TRUE,
				);
			
			$this->load->view( 
					'outpatient/modal/create_edit', 
					array('form_child' => $this->load->view('outpatient/form', $data, true))
				);
		} else
		{
			$data = array(
					"page" => $this->page."_".strtolower(__FUNCTION__),
					"item" => $item,
					"option_patient_type" => @$option_patient_type,
					"user" => $this->user_auth,
					"lookup_supplier" => base_url("{$this->nameroutes}/lookup_supplier"),
					"cancel_link" => base_url("{$this->nameroutes}/cancel/{$NoBukti}"),
					'nameroutes' => $this->nameroutes,
					"viewroutes" => $this->viewroutes,
					"form" => TRUE,
					"datatables" => TRUE,
					"is_edit" => TRUE,
				);
		
			$this->template
				->set( "heading", sprintf(lang('poly:examination_heading'), ucfirst_case( $item->SectionName )) )
				->set_breadcrumb( sprintf(lang('poly:examination_heading'), ucfirst_case( $item->SectionName )) )
				->build("{$this->viewroutes}/form", $data);
		}
	}
	
	public function cancel( $NoBukti = NULL )
	{
		$item = $this->db->where("NoBukti", $NoBukti)->get( $this->poly_m->table )->row();
		$registration = $this->registration_model->get_one( $item->RegNo );
		
		if( ! $item ){ $item = array('NoBukti' => NULL); }		
		if( $this->input->post() ) 
		{
			if( empty($item ) )
			{
				make_flashdata(array(
						'response_status' => 'error',
						'message' => lang( 'global:get_failed' )
					));
			
				redirect( $this->input->post( 'r_url' ) );
			}


			if( $registration->StatusBayar != "Belum" || $registration->StatusBayar == "Sudah Bayar" || $registration->StatusBayar == "Proses" )
			{
				make_flashdata(array(
						'response_status' => 'error',
						'message' => "Gagal Batal Pemeriksaan! Pasien dengan Pemeriksaan ini sudah dilakukan Pembayaran."
					));
			
				redirect( "{$this->nameroutes}/view/{$NoBukti}" );
			}
			
			if( $item->NoBukti == $this->input->post( 'confirm' ) )
			{
				$this->db->trans_begin();

					$this->registration_model->update( ["StatusPeriksa" => 'Belum'], $item->RegNo );				
					$this->poly_m->update( ["Batal" => 1], $NoBukti );		
					// $this->registration_data_model->update( ["SudahPeriksa" => 0], $item->RegNo );			
	
				if ($this->db->trans_status() === FALSE)
				{
					$this->db->trans_rollback();
					make_flashdata([
						"response_status" => 'error',
						"message" => "Gagal Batal Pemeriksaan",
					]);
				}
				else
				{	
					$this->db->trans_commit();
					make_flashdata([
						"response_status" => 'success',
						"message" => "Berhasil Batal Pemeriksaan",
					]);
				}
			}
			
			redirect( $this->input->post( 'r_url' ) );
		}
		
		$this->load->view( 'outpatient/modal/cancel', array('item' => $item) );
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
					->order_by('poly_title', 'asc')
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
					$item->poly_price = (float) $item->poly_price;
					
					$attr_data = "data-id=\"{$item->id}\" data-code=\"{$item->code}\" data-title=\"{$item->poly_title}\" data-price=\"{$item->poly_price}\" ";
					
					if( $selected == $item->code)
					{
						$options_html .= "\n<option {$attr_data}value=\"{$item->code}\" selected>{$item->code} - {$item->poly_title}</option>";
					} else
					{
						$options_html .= "\n<option {$attr_data}value=\"{$item->code}\">{$item->code} - {$item->poly_title}</option>";
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
			$this->load->view( 'outpatient/lookup/datatable' );
		} else
		{
			$data = array(
					'page' => $this->page,
					'datatables' => TRUE,
					'form' => TRUE,
				);
			
			$this->template
				->set( "heading", "Lookup Box" )
				->set_breadcrumb( lang("common:page"), base_url("polys") )
				->set_breadcrumb( "Lookup Box" )
				->build('outpatient/lookup', (isset($data) ? $data : NULL));
		}
	}

	public function lookup_doctor_schedule_from_poly( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'outpatient/lookup/view_doctor_schedules' );
		} 
	}

	public function lookup_doctor_schedule( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'outpatient/lookup/datatable_view_doctor_schedule' );
		} 
	}

	public function lookup_section( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'outpatient/lookup/sections' );
		} 
	}

	public function lookup_doctor_section( $index = NULL, $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'outpatient/lookup/supplier_sections', array( "index" => $index ) );
		} 
	}

	public function lookup_reservation_from_poly( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'outpatient/lookup/reservations' );
		} 
	}

	public function lookup_reservation( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'outpatient/lookup/datatable_view_reservations' );
		} 
	}

	public function lookup_insurer( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'outpatient/lookup/insurers' );
		} 
	}

	public function lookup_supplier( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'outpatient/lookup/suppliers', array("type" => "doctor" ) );
		} 
	}
	
	public function lookup_supplier_consul( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'outpatient/lookup/suppliers_consul', array("type" => "doctor" ) );
		} 
	}
	
	// Cooperation == Perusahaan yang diajak kerja sama (BPJS, IKS)
	public function lookup_cooperation( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'outpatient/lookup/cooperations', array() );
		} 
	}
	
	// Lookup kartu anggota kerja sama patient(BPJS, IKS)
	public function lookup_patient_cooperation_card( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'outpatient/lookup/patient_cooperation_cards', array() );
		} 
	}
	
	// lookup_second_insurer == Pertanggungan Kedua (IKS)
	public function lookup_second_insurer( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'outpatient/lookup/second_insurers', array() );
		} 
	}
	
	// Lookup kartu anggota kerja sama kedua patient(IKS)
	public function lookup_patient_second_insurer_card( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'outpatient/lookup/patient_second_insurer_cards', array() );
		} 
	}

	public function lookup_collection()
	{
		$this->datatable_collection( 1 );
	}

	public function datatable_collection()
    {
        $start = $this->input->get_post('start', true);
        $length = $this->input->get_post('length', true);
        $order = $this->input->get_post('order', true);
        $columns = $this->input->get_post('columns', true);
        $search = $this->input->get_post('search', true);
        $draw = $this->input->get_post('draw', true);
		
		//$db_from = "{$this->poly_m->table} a";
		$db_from = "SIMtrDataRegPasien a";
		$this->load->model("registrations/registration_m");
		$db_where = array();
		$db_like = array();
		
		$db_where['a.SudahPeriksa'] = 0;
		$db_where['a.SectionID'] = config_item('section_id');
		
		if ($this->input->post("date_from"))
		{
			$db_where['b.TglReg >='] = $this->input->post("date_from");
		}

		if ($this->input->post("date_till"))
		{
			$db_where['b.TglReg <='] = $this->input->post("date_till");
		}
				
		// preparing default
		if( isset($search['value']) && ! empty($search['value']) )
        {
            $keywords = $this->db->escape_str( $search['value'] );
						
			$db_like[ $this->db->escape_str("a.NoReg") ] = $keywords;
			$db_like[ $this->db->escape_str("b.NRM") ] = $keywords;
			$db_like[ $this->db->escape_str("b.JamReg") ] = $keywords;
			$db_like[ $this->db->escape_str("c.NamaPasien") ] = $keywords;
			$db_like[ $this->db->escape_str("d.JenisKerjasama") ] = $keywords;

        }
		
		// get total records
		$this->db->from( $db_from )
			->join( "{$this->registration_m->table} b", "a.NoReg = b.NoReg", "LEFT OUTER" )
			->join( "{$this->patient_model->table} c", "b.NRM = c.NRM", "LEFT OUTER" )
			->join( "{$this->patient_type_model->table} d", "b.JenisKerjasamaID = d.JenisKerjasamaID", "LEFT OUTER" )
			->join( "{$this->supplier_model->table} e", "a.DokterID = e.Kode_Supplier", "LEFT OUTER" )
		;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		$records_total = $this->db->count_all_results();
		
		// get total filtered
		$this->db
			->from( $db_from )
			->join( "{$this->registration_m->table} b", "a.NoReg = b.NoReg", "LEFT OUTER" )
			->join( "{$this->patient_model->table} c", "b.NRM = c.NRM", "LEFT OUTER" )
			->join( "{$this->patient_type_model->table} d", "b.JenisKerjasamaID = d.JenisKerjasamaID", "LEFT OUTER" )
			->join( "{$this->supplier_model->table} e", "a.DokterID = e.Kode_Supplier", "LEFT OUTER" )
			;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }		
		$records_filtered = $this->db->count_all_results();
		
		// get result filtered
		$db_select = <<<EOSQL
			a.NoUrut,
			a.NoReg,
			b.NRM,
			b.JamReg,
			c.NamaPasien,
			c.JenisKelamin,
			d.JenisKerjasama,
			e.Nama_Supplier
			
			
EOSQL;

		$this->db
			->select( $db_select )
			->from( $db_from )
			->join( "{$this->registration_m->table} b", "a.NoReg = b.NoReg", "LEFT OUTER" )
			->join( "{$this->patient_model->table} c", "b.NRM = c.NRM", "LEFT OUTER" )
			->join( "{$this->patient_type_model->table} d", "b.JenisKerjasamaID = d.JenisKerjasamaID", "LEFT OUTER" )
			->join( "{$this->supplier_model->table} e", "a.DokterID = e.Kode_Supplier", "LEFT OUTER" )
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
					->order_by( $columns[intval($this->db->escape_str($sort_column))]['name'], $this->db->escape_str($sort_dir) );
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
		
		//print_r($output);exit;
		
		$this->template
			->build_json( $output );
    }
	
	public function autocomplete()
	{
		$words = $this->input->get_post('query');
		
		$this->db
			->select( array("id", "code", "poly_title") )
			;
			
		$this->db
			->from( "common_polys" )
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
					"poly_title" => $words,
					"poly_description" => $words,
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
						"name" => $item->poly_title,
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

	public function time_dropdown( $selected='' )
	{
		if( $this->input->is_ajax_request() )
		{
			$items = $this->db
				->order_by('Keterangan', 'asc')
				->get("SIMmWaktuPraktek")
				->result()
				;
			
			$options_html = "";
			
			if( $selected == "" )
			{
				$options_html .= "\n<option data-waktuid=\"0\" data-keterangan=\"\" value=\"\" selected>".lang( 'global:select-empty' )."</option>";
			} else
			{
				$options_html .= "\n<option data-waktuid=\"0\" data-keterangan=\"\" value=\"\">".lang( 'global:select-empty' )."</option>";
			}
			
			foreach($items as $item)
			{
				
				$attr_data = "data-waktuid=\"{$item->WaktuID}\" data-keterangan=\"{$item->Keterangan}\" ";
				
				if( $selected == $item->WaktuID)
				{
					$options_html .= "\n<option {$attr_data} value=\"{$item->WaktuID}\" selected>{$item->Keterangan}</option>";
				} else
				{
					$options_html .= "\n<option {$attr_data} value=\"{$item->WaktuID}\">{$item->Keterangan}</option>";
				}
			}
			
			print( $options_html );
			exit();
		}
	}

	public function print_report($nrm,$dob, $stat = false)
	{
		if ($stat)
		{
			
			str_replace('%20','',$dob);
			$data = array(
							"nrm" => $nrm,
							"dob" => date("Y-m-d",strtotime($dob)),
						);
			
			//print_r($data);exit;
			$html_content =  $this->load->view( "outpatient/print", $data, 'Label '); 
			
			$file_name = 'Print  Label';		
			$this->load->helper( "report" );
	
			report_helper::generate_pdf( $html_content, $file_name, date("Y-M-d") , $margin_bottom = 1.0, $header = NULL, $margin_top = 0.3, $orientation = 'P');
	
			
	
			exit(0);
		}
	}

}



