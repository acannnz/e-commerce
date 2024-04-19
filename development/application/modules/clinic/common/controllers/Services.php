<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Services extends Admin_Controller
{
	protected $_translation = 'common';	
	protected $_model = 'service_m';
	
	public function __construct()
	{
		parent::__construct();
		
		$this->page = "common_services";
		$this->template->title( lang("services:page") . ' - ' . $this->config->item('company_name') );
	}
	
	public function index()
	{
		$data = array(
				'page' => $this->page,
				"form" => TRUE,
				'datatables' => TRUE,
			);
		
		$this->template
			->set( "heading", lang("services:page") )
			->set_breadcrumb( lang("common:page"), base_url("common") )
			->set_breadcrumb( lang("services:breadcrumb") )
			->build('services/datatable', (isset($data) ? $data : NULL));
	}
	
	public function create()
	{
		$item_data = array(
				'id' => 0,
				'code' => null,
				'service_title' => null,
				'service_description' => null,
				'service_price' => null,
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
					$this->get_model()->delete_cache( 'common_services.collection' );
					
					make_flashdata(array(
							'response_status' => 'success',
							'message' => lang('global:created_successfully')
						));
						
					redirect( 'common/services' );
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
					'services/modal/create_edit', 
					array('form_child' => $this->load->view('services/form', $data, true))
				);
		} else
		{
			$data = array(
					"page" => $this->page."_".strtolower(__FUNCTION__),
					"item" => $this->item,
					"form" => TRUE,
				);
			
			$this->template
				->set( "heading", lang("services:create_heading") )
				->set_breadcrumb( lang("common:page"), base_url("common") )
				->set_breadcrumb( lang("services:breadcrumb"), base_url("common/services") )
				->set_breadcrumb( lang("services:create_heading") )
				->build('services/form', $data);
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
					$this->get_model()->delete_cache( 'common_services.collection' );
					
					make_flashdata(array(
							'response_status' => 'success',
							'message' => lang('global:updated_successfully')
						));
						
					redirect( 'common/services' );
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
					'services/modal/create_edit', 
					array('form_child' => $this->load->view('services/form', $data, true))
				);
		} else
		{
			$data = array(
					"page" => $this->page,
					"item" => $this->item,
					"form" => TRUE,
				);
			
			$this->template
				->set( "heading", lang("services:edit_heading") )
				->set_breadcrumb( lang("common:page"), base_url("common") )
				->set_breadcrumb( lang("services:breadcrumb"), base_url("common/services") )
				->set_breadcrumb( lang("services:edit_heading") )
				->build('services/form', $data);
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
				
				$this->get_model()->delete_cache( 'common_services.collection' );
				
				make_flashdata(array(
						'response_status' => 'success',
						'message' => lang('global:deleted_successfully')
					));
			}
			
			redirect( $this->input->post( 'r_url' ) );
		}
		
		$this->load->view( 'services/modal/delete', array('item' => $this->item) );
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
					->order_by('service_title', 'asc')
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
					$item->service_price = (float) $item->service_price;
					
					$attr_data = "data-id=\"{$item->id}\" data-code=\"{$item->code}\" data-title=\"{$item->service_title}\" data-price=\"{$item->service_price}\" ";
					
					if( $selected == $item->code)
					{
						$options_html .= "\n<option {$attr_data}value=\"{$item->code}\" selected>{$item->code} - {$item->service_title}</option>";
					} else
					{
						$options_html .= "\n<option {$attr_data}value=\"{$item->code}\">{$item->code} - {$item->service_title}</option>";
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
			$this->load->view( 'services/lookup/datatable' );
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
				->build('services/lookup', (isset($data) ? $data : NULL));
		}
	}

	public function lookup_service_charges( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'services/lookup/datatable_charges' );
		} 
	}
	
	public function lookup_collection()
	{
		$this->datatable_collection( 1 );
	}
	
	public function datatable_collection( $state=false )
    {
        
		// Total data set length
        $records_total = $this->get_model()->count();
		
		$start = $this->input->get_post('start', true);
        $length = $this->input->get_post('length', true);
        $order = $this->input->get_post('order', true);
        $columns = $this->input->get_post('columns', true);
        $search = $this->input->get_post('search', true);
        $draw = $this->input->get_post('draw', true);
		
		/* 
         * Filtering
         * NOTE this does not match the built-in DataTables filtering which does it
         * word by word on any field. It's possible to do here, but concerned about efficiency
         * on very large tables, and MySQL's regex functionality is very limited
         */
		 
		if( $state !== false )
		{
			$this->get_model()->where( array("state" => 1) );
		}
		
        if( isset($search['value']) && ! empty($search['value']) )
        {
            $search_fields = array();
			
			for($i=0; $i<count($columns); $i++)
            {
                // Individual column filtering
                if( isset($columns[$i]['searchable']) && $columns[$i]['searchable'] == 'true')
                {
                    array_push($search_fields, $columns[$i]['data']);
				}
            }
			
			$this->get_model()->where( $search_fields, 'like', $search['value'], true );
        }
        
        // Data set length after filtering
        $records_filtered = $this->get_model()->count();
    	
		// Ordering
        if( isset($order) )
        {
            $sort_column = $order[0]['column'];
			$sort_dir = $order[0]['dir'];
			
			if( $columns[$sort_column]['orderable'] == 'true' )
			{
				$this->get_model()->order_by( $columns[intval($this->db->escape_str($sort_column))]['data'], $this->db->escape_str($sort_dir) );
			}
        }
		
		// Paging
        if( isset($start) && $length != '-1')
        {
            $this->get_model()->limit( $length, $start );
        }
		
		// Select Data
        $result = $this->get_model()->get_all();
		
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

	public function datatable_charge_collection( $state=false )
    {
        
    	$start = $this->input->get_post('start', true);
        $length = $this->input->get_post('length', true);
        $order = $this->input->get_post('order', true);
        $columns = $this->input->get_post('columns', true);
        $search = $this->input->get_post('search', true);
        $draw = $this->input->get_post('draw', true);
		
		$db_from = "SIMmListJasaSection a";
		$db_where = array();
		$db_like = array();
		$db_group = NULL;
		
		if ( $this->input->post("SectionID") )
		{
			$db_where['a.SectionID'] = $this->input->post("SectionID");
		}

		if ( $this->input->post("show_all") )
		{
			unset($db_where['a.SectionID']);
			$db_group = "b.JasaID, b.JasaName";
		}
		
		$db_where['b.Aktif'] = 1;
		// preparing default
		if( isset($search['value']) && ! empty($search['value']) )
        {
            $keywords = $this->db->escape_str( $search['value'] );
						
			$db_like[ $this->db->escape_str("b.JasaID") ] = $keywords;
			$db_like[ $this->db->escape_str("b.JasaName") ] = $keywords;

        }
		
		// get total records
		$this->db->from( $db_from )
			->join( "{$this->service_m->table} b", "a.JasaID = b.JasaID", "LEFT OUTER" )
		;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		$records_total = $this->db->count_all_results();
		
		// get total filtered
		if( !empty($db_group) ){ $this->db->select( $db_group ); }
		$this->db
			->from( $db_from )
			->join( "{$this->service_m->table} b", "a.JasaID = b.JasaID", "LEFT OUTER" )
			;
			
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }		
		if( !empty($db_group) ){ $this->db->group_by( $db_group ); }
		$records_filtered = $this->db->count_all_results();
		
		// get result filtered
		$db_select = <<<EOSQL
			b.JasaID,
			b.JasaName
			
EOSQL;

		$this->db
			->select( $db_select )
			->from( $db_from )
			->join( "{$this->service_m->table} b", "a.JasaID = b.JasaID", "LEFT OUTER" )
			;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }		
		if( !empty($db_group) ){ $this->db->group_by( $db_group ); }
		
		// ordering
        if( isset($order) )
        {
            $sort_column = $order[0]['column'];
			$sort_dir = $order[0]['dir'];
			
			if( $columns[$sort_column]['orderable'] == 'true' )
			{
				$this->db->order_by( $columns[intval($this->db->escape_str($sort_column))]['name'], $this->db->escape_str($sort_dir) );
			}
        }
		//exit;
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

        $JenisKerjasamaID = $this->input->post("JenisKerjasamaID");
        $PasienKTP = (int) $this->input->post("PasienKTP");
		$NoAnggota = $this->input->post("NoAnggota");
		$DokterID = !empty($this->input->post("DokterID")) ? $this->input->post("DokterID") : 'xx';
		$KelasID = !empty($this->input->post("KelasID")) ? $this->input->post("KelasID") : 'XX';
		$Lokasi = $this->input->post("Lokasi");
        foreach($result as $row)
        {	
			if( in_array($JenisKerjasamaID, [2, 3, 4]) )
			{
				#(@JasaID varchar(50),@DokterID varchar(50),@KelasID varchar(50),@Cyto int,@KategoriOperasiID int,@TipePasienID int,@UnitBisnisID varchar(50))
				if(!empty($PasienKTP))
					$this->db->where('KTP', $PasienKTP);
				if(!empty($Lokasi))
					$this->db
						->group_start()
						->or_where(['Lokasi' => $Lokasi, 'Lokasi =' => 'XX'])
						->group_end();
					
				$tariff_jasa = $this->db->select("*, 0 AS KenaikanProsen")
							->from("dbo.GetTarifBiayaNonKerjasama ('{$row->JasaID}' ,'{$DokterID}', '{$KelasID}', 0, 1, {$JenisKerjasamaID}, 1) ")
							->order_by('Lokasi')
							->get()
							->row();
			
			} elseif($JenisKerjasamaID == 9) {
				if(!empty($Lokasi))
					$this->db
						->group_start()
						->or_where(['Lokasi' => $Lokasi, 'Lokasi =' => 'XX'])
						->group_end();
						
				$tariff_jasa = $this->db->select("*, 0 AS KenaikanProsen")
							->from("dbo.GetTarifBiayaJKN_BUFF ('{$row->JasaID}', '{$DokterID}', '{$KelasID}', 0, 1, '{$NoAnggota}')	")
							->order_by('Lokasi')
							->get()
							->row();	
			}
				
			$row = (object) array_merge((array) $row, (array) $tariff_jasa);
								
			$row->user_id = $this->user_auth->User_ID;
			
            $output['data'][] = $row;
        }
		
		$this->template
			->build_json( $output );
    }
		
	public function autocomplete()
	{
		$words = $this->input->get_post('query');
		
		$this->db
			->select( array("id", "code", "service_title") )
			;
			
		$this->db
			->from( "common_services" )
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
					"service_title" => $words,
					"service_description" => $words,
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
						"name" => $item->service_title,
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



