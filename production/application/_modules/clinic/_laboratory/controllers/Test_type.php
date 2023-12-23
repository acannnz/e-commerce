<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Test_type extends Admin_Controller
{ 
	protected $_translation = 'laboratory';	
	protected $_model = 'test_type_m'; 
	protected $nameroutes; 
	  
	public function __construct()  
	{ 
		parent::__construct();
		$this->simple_login->check_user_role('laboratory');
		
		$this->page = "Test Tipe";
		$this->template->title( 'Setup Jenis Test' . ' - ' . $this->config->item('company_name') );
		$this->nameroutes = 'laboratory/test-type';
		
		$this->load->helper('laboratory');
		$this->load->model('test_type_m');
		$this->load->model('test_category_m');
		$this->load->model('test_technique_m');
		$this->load->model('test_type_detail_m');
		$this->load->model('satuan_m');
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
			->set( "heading", "Jenis Test" )
			->set_breadcrumb( lang("laboratory:breadcrumb"), base_url("helper") )
			->set_breadcrumb( "Deftar Jenis Test")
			->build('test_type/datatable', (isset($data) ? $data : NULL));
	}
		
	public function create()
	{

		$test_category = $this->test_category_m->options_type();
		$test_tecnique = $this->test_technique_m->options_type();
		$satuan = $this->satuan_m->options_type();
	  
		$item = (object) [

		];	
				
		if( $this->input->post() ) 
		{			
			$response = [
				"status" => "success",
				"message" => "",
				"code" => 200
			];
			
			if ( empty($this->input->post("reference_value")))
			{
				$response = [
					"status" => "error",
					"message" => "Anda Belum Memasukan Data Nilai Rujukan!",
					"code" => 200
				];
					
				response_json($response, JSON_NUMERIC_CHECK);
			}
			
			$post_data = $this->input->post("f");			
			$reference_value = $this->input->post("reference_value");

			$this->load->library( 'form_validation' );			
			$this->form_validation->set_rules( $this->get_model()->rules['insert'] );
			$this->form_validation->set_data( $post_data );
			if( $this->form_validation->run() )
			{
				$this->db->trans_begin();
					$this->get_model()->create($post_data );				
					$this->test_type_detail_m->mass_create($reference_value );
				
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
					$response = [
						"status" => 'success',
						"message" => lang('global:created_successfully'),
						"code" => 200
					];
				}				

			} else
			{
				$response = [
					"status" => 'error',
					"message" => $this->form_validation->get_all_error_string(),
					"code" => 500
				];
			}
			
			response_json($response);
		}
		
		if( $this->input->is_ajax_request() )
		{
			$data = array(
					'item' => $item,
					"is_ajax_request" => TRUE,
					"is_modal" => TRUE,
					'nameroutes' => $this->nameroutes
				);
			
			$this->load->view( 
					'laboratory/modal/create_edit', 
					array('form_child' => $this->load->view('laboratory/form_test_type', $data, true))
				);
		} else
		{
			
			$data = [
					"page" => $this->page."_".strtolower(__FUNCTION__),
					"item" => $item,
					"form" => TRUE,
					"datatables" => TRUE,
					"option_test_category" => $test_category,
					"option_test_technique" => $test_tecnique,
					"option_satuan" => $satuan,
					'nameroutes' => $this->nameroutes,
					"populate_url" => base_url("laboratory/test_type/detail_type_collection"),
			];

			$this->template
				->set( "heading", "Kelola Jenis Test" )
				->set_breadcrumb( lang("laboratory:breadcrumb"), base_url("helper") )
				->set_breadcrumb( "Daftar Jenis Test", base_url("{$this->nameroutes}") )
				->set_breadcrumb( "Buat Jenis Test Baru" )
				->build('test_type/form', $data);
		}
	}
		
	public function edit( $TestID = NULL )
	{		
		$item = $this->get_model()->get_one( $TestID );
		$populate_reference_value = $this->test_type_detail_m->get_all(["TestID" => $TestID]);
		
		$test_category = $this->test_category_m->options_type();
		$test_tecnique = $this->test_technique_m->options_type();
		$satuan = $this->satuan_m->options_type();
		
		if( $this->input->post() ) 
		{
			if ( empty($this->input->post("reference_value")))
			{
				$response = [
					"status" => "error",
					"message" => "Anda Belum Memasukan Data Nilai Rujukan!",
					"code" => 200
				];
					
				response_json($response, JSON_NUMERIC_CHECK);
			}
			
			$post_data = $this->input->post("f");			
			$reference_value = $this->input->post("reference_value");

			$this->load->library( 'form_validation' );			
			$this->form_validation->set_rules( $this->get_model()->rules['insert'] );
			$this->form_validation->set_data( $post_data );
			
			if( $this->form_validation->run() )
			{
				$this->db->trans_begin();
					unset($post_data['TestID']);
					$this->get_model()->update($post_data, $TestID );	
					
					$this->test_type_detail_m->delete_by(['TestID' => $TestID]);		
					$this->test_type_detail_m->mass_create($reference_value );
				
				if ($this->db->trans_status() === FALSE)
				{
					$this->db->trans_rollback();
					$response = array(
							"status" => 'error',
							"message" => lang('global:updated_failed'),
							"code" => 500
						);
				}
				else
				{
					
					$this->db->trans_commit();
					$response = [
						"status" => 'success',
						"message" => lang('global:updated_successfully'),
						"code" => 200
					];
				}	
			} else
			{
				$response = [
					'response_status' => 'error',
					'message' => $this->form_validation->get_all_error_string()
				];
			}
			response_json($response);
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
					'laboratory/modal/create_edit', 
					array('form_child' => $this->load->view('laboratory/form_test_category', $data, true))
				);
		} else
		{
			$data = array(
					"page" => $this->page."_".strtolower(__FUNCTION__),
					"item" => $item,
					"option_test_category" => $test_category,
					"option_test_technique" => $test_tecnique,
					"option_satuan" => $satuan,
					"is_edit" => TRUE,
					"form" => TRUE,
					"datatables" => TRUE,
					'nameroutes' => $this->nameroutes,
					"populate_reference_value" => $populate_reference_value,
				);
			
			$this->template
				->set( "heading", "Kelola Jenis Test" )
				->set_breadcrumb( lang("laboratory:breadcrumb"), base_url("helper") )
				->set_breadcrumb( "Daftar Jenis Test", base_url("{$this->nameroutes}") )
				->set_breadcrumb( "Perbarui Jenis Test Baru" )
				->build('test_type/form', $data);
		}
	}
	
	public function delete( $id = NULL )
	{
		$item = $this->get_model()->get_one( $id );		
		if( $this->input->post() ) 
		{
			if( empty($item->TestID) )
			{
				make_flashdata(array(
						'response_status' => 'error',
						'message' => lang( 'global:get_failed' )
					));
			
				redirect( $this->input->post( 'r_url' ) );
			}
			
			if( $item->TestID == $this->input->post( 'confirm' ) )
			{					
				$this->db->trans_begin();
					
					$this->test_type_detail_m->delete_by(['TestID' => $id]);
					$this->get_model()->delete( $id );
									
				if ($this->db->trans_status() === FALSE)
				{
					$this->db->trans_rollback();
					$response = array(
							"status" => 'error',
							"message" => lang('global:deleted_failed'),
							"code" => 500
						);
				}
				else{
					
					$this->db->trans_commit();
					$response = [
						"status" => 'success',
						"message" => lang('global:deleted_successfully'),
						"code" => 200
					];
				}	
				
				make_flashdata($response);
			}
			
			redirect( $this->input->post( 'r_url' ) );
		}
		
		$this->load->view( 'test_type/modal/delete', array('item' => $item) );
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
		
		$db_from = "{$this->test_type_m->table} a";
		$db_where = array();
		$db_like = array();
		
		//if($this->input->post("reminder") == 1){
		//	$db_where['a.UntukTanggal'] = date('Y-m-d', strtotime("+3 days"));
		//}
		
		// prepare defautl flter
		//$db_where['a.Active'] = 1;
		// preparing default
		if( isset($search['value']) && ! empty($search['value']) )
        {
            $keywords = $this->db->escape_str( $search['value'] );
			
			$db_like[ $this->db->escape_str("a.TestID") ] = $keywords;
			
			$db_like[ $this->db->escape_str("a.KategoriTestiID") ] = $keywords;
			$db_like[ $this->db->escape_str("a.NamaTest") ] = $keywords;
			 
			/*for($i=0; $i<count($columns); $i++)  
            {
                if( isset($columns[$i]['searchable']) && $columns[$i]['searchable'] == 'true')
                {
                	$column_name = $columns[$i]['data'];
					$column_value = $search['value'];
					
					$db_like[$column_name] = $column_value; 
				}
            }*/
        }
		
		// get total records
		$this->db->from( $db_from );
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		$records_total = $this->db->count_all_results();
		
		// get total filtered
		$this->db
			->from( $db_from )
			->join( "{$this->test_category_m->table} b","a.KategoriTestiID=b.KategoriTestID","LEFT JOIN")
			;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }		
		$records_filtered = $this->db->count_all_results();
		
		// get result filtered
		$db_select = <<<EOSQL
			a.TestID,
			a.KategoriTestiID,
			a.NamaTest,
			a.ACN,
			a.HCN,
			a.Harga,
			a.Harga_HC,
			a.Harga_IKS,
			a.Satuan,
			a.PakeMesin,
			a.NoUrut,
			a.TeknikPemeriksaan,
			a.Aktif,
			a.HostCodeadvia,
			a.KeyMesin,
			b.KategoriTestNama,
			
EOSQL;

		$this->db
			->select( $db_select )
			->from( $db_from )
			->join( "{$this->test_category_m->table} b","a.KategoriTestiID=b.KategoriTestID","LEFT JOIN")
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
        
//        foreach($result as $row)
//        {
//			$row->created_at = strftime(config_item('date_format'), @$row->created_at);
//			$row->updated_at = strftime(config_item('date_format'), @$row->updated_at);
//			
//            $output['data'][] = $row;
//        }
		
		foreach($result as $row)
        {
			//$date = DateTime::createFromFormat("Y-m-d H:i:s.u", $row->Tanggal);
			//$time = DateTime::createFromFormat("Y-m-d H:i:s.u", $row->Jam ); 
			
			//$row->Tanggal = $date->format('Y-m-d');
			//$row->Jam = $time->format('H:i:s');
      
            $output['data'][] = $row;
        }
		
		$this->template
			->build_json( $output );
    }
	
	public function detail_type_collection( $test_id='',$state=false )
    {
        $start = $this->input->get_post('start', true);
        $length = $this->input->get_post('length', true);
        $order = $this->input->get_post('order', true);
        $columns = $this->input->get_post('columns', true);
        $search = $this->input->get_post('search', true);
        $draw = $this->input->get_post('draw', true);
		
		$db_from = "{$this->test_type_detail_m->table} a";
		$db_where = array();
		$db_like = array();
		
		//if($this->input->post("reminder") == 1){
		//	$db_where['a.UntukTanggal'] = date('Y-m-d', strtotime("+3 days"));
		//}
		
		// prepare defautl flter
		//$db_where['a.Active'] = 1;
		// preparing default
		if( isset($search['value']) && ! empty($search['value']) )
        {
            $keywords = $this->db->escape_str( $search['value'] );
			
			
			//$db_like[ $this->db->escape_str("a.TestID") ] = $keywords;
			
			//$db_like[ $this->db->escape_str("a.KategoriTestiID") ] = $keywords;
			//$db_like[ $this->db->escape_str("a.NamaTest") ] = $keywords;
			 
			/*for($i=0; $i<count($columns); $i++)  
            {
                if( isset($columns[$i]['searchable']) && $columns[$i]['searchable'] == 'true')
                {
                	$column_name = $columns[$i]['data'];
					$column_value = $search['value'];
					
					$db_like[$column_name] = $column_value; 
				}
            }*/
        }
		
		// get total records
		$this->db->from( $db_from );
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		$records_total = $this->db->count_all_results();
		
		// get total filtered
		$this->db
			->from( $db_from )
			//->join( "{$this->test_category_m->table} b","a.KategoriTestiID=b.KategoriTestID","LEFT JOIN")
			;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }		
		$records_filtered = $this->db->count_all_results();
		
		// get result filtered
		$db_select = <<<EOSQL
			a.TestID,
			a.NilaiRujukan,
			a.Satuan,
			a.TypeKelahiran,
			a.Sex,
			a.KelompokUmur,
			a.OperatorUmur1,
			a.Umur_Th_1,
			a.Umur_Bln_1,
			a.Umur_Hari_1,
			a.UmurTotal_Hr,
			a.OperatorUmur2,
			a.Umur_Th_2,
			a.Umur_Bln_2,
			a.Umur_Hari_2,
			a.Keterangan,
			a.UmurTotal_Hr2,
			
EOSQL;

		$this->db
			->select( $db_select )
			->from( $db_from )
			//->join( "{$this->test_category_m->table} b","a.KategoriTestiID=b.KategoriTestID","LEFT JOIN")
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
        
//        foreach($result as $row)
//        {
//			$row->created_at = strftime(config_item('date_format'), @$row->created_at);
//			$row->updated_at = strftime(config_item('date_format'), @$row->updated_at);
//			
//            $output['data'][] = $row;
//        }
		
		foreach($result as $row)
        {
			//$date = DateTime::createFromFormat("Y-m-d H:i:s.u", $row->Tanggal);
			//$time = DateTime::createFromFormat("Y-m-d H:i:s.u", $row->Jam ); 
			
			//$row->Tanggal = $date->format('Y-m-d');
			//$row->Jam = $time->format('H:i:s');
      
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



