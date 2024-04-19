<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Schedules extends Admin_Controller
{ 
	protected $_translation = 'schedules';	
	protected $_model = 'schedules_m'; 
	
	public function __construct()
	{
		parent::__construct();
		
		$this->page = "common_schedules";
		$this->template->title( lang("schedules:page") . ' - ' . $this->config->item('company_name') );
		
		$this->load->model("schedules_m");
		$this->load->model( "common/supplier_m" );
		$this->load->model( "common/supplier_specialist_m" );
		$this->load->model( "common/section_m" );
		$this->load->model( "common/time_m" );

	}
	
	public function index()
	{
		$data = array(
				'page' => $this->page,
				"form" => TRUE,
				'datatables' => TRUE,
			);
		
		$this->template
			->set( "heading", lang("schedules:page") )
			->set_breadcrumb( lang("common:page"), base_url("common") )
			->set_breadcrumb( lang("schedules:breadcrumb") )
			->build('schedules/datatable', (isset($data) ? $data : NULL));
	}
	
	public function create()
	{
	  
		$item = array();
		
		if( $this->input->post() ) 
		{
			
			
			$response = array(
					"status" => "success",
					"message" => "",
					"code" => 200
				);
				
			$data = $this->input->post();
			$header = $this->input->post('header');
			$detail = $this->input->post('detail');
			
			$this->load->library( 'form_validation' );		
			$this->form_validation->set_rules( $this->get_model()->rules['insert'] );
			$this->form_validation->set_data( $this->input->post("header") );
			
			if( !$this->form_validation->run() )
			{
				$this->db->trans_begin();
							
					$this->db->insert( "SIMtrDokterJaga", $header );

					$WaktuPraktek = $this->schedules_m->get_practice_schedule();
					$detail_data = array();
					foreach( $detail as $row )
					{
						$row['DokterID'] = $header['DokterID'];
						$row['SectionID'] = $header['SectionID']; 
						$row['FromJam'] = $WaktuPraktek[ $row['WaktuID'] ]->FromJam; 
						$row['ToJam'] = $WaktuPraktek[ $row['WaktuID'] ]->ToJam; 
						$row['JmlAntrian'] = 0;
						$row['Realisasi'] = 0;
						$row['NoAntrianTerakhir'] = 0;
						
						$detail_data[] = $row;
					}
					
					$this->db->insert_batch( "SIMtrDokterJagaDetail", $detail_data );
				
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
							"DokterID" => $header['DokterID'],
							"SectionID" => $header['SectionID'],
							"status" => 'success',
							"message" => lang('global:created_successfully'),
							"code" => 200
						);
				}		

			} else
			{
				$response = array(
						"status" => 'error',
						"message" => $this->form_validation->get_all_error_string(),
						"code" => 500
					);
			}
			
			print_r( json_encode($response, JSON_NUMERIC_CHECK) );
			exit(0);
		}

		$option_times = $this->schedules_m->get_option_times();
		$option_section = $this->schedules_m->get_options("SIMmSection", array("TipePelayanan" => "Rj", "StatusAktif" => 1));
		
		if( $this->input->is_ajax_request() )
		{
			$data = array(
					'item' => (object) $item,
					"is_ajax_request" => TRUE,
					"is_modal" => TRUE,
					"url_lookup_suppliers" => base_url("schedules/lookup_suppliers")."?is_modal=yes",
				);
			
			$this->load->view( 
					'schedules/modal/create_edit', 
					array('form_child' => $this->load->view('schedules/form', $data, true))
				);
		} else
		{
			$data = array(
					"page" => $this->page."_".strtolower(__FUNCTION__),
					"item" => (object) $item,
					"option_times" => $option_times,
					"option_section" => $option_section,
					"url_lookup_suppliers" => base_url("schedules/lookup_suppliers"),
					"url_lookup_replacement_suppliers" => base_url("schedules/lookup_replacement_suppliers"),
					"url_schedule_generate" => base_url("schedules/generate"),
					"form" => TRUE,
					"datatables" => TRUE,
				);
			
			$this->template
				->set( "heading", lang("schedules:create_heading") )
				->set_breadcrumb( lang("schedules:breadcrumb"), base_url("schedules") )
				->set_breadcrumb( lang("schedules:create_heading") )
				->build('schedules/form', $data);
		}
	}
	
	public function edit( $DokterID = 0, $SectionID = 0 )
	{
		$item = $this->schedules_m->get_schedule( $DokterID, $SectionID );
		$collection = $this->schedules_m->get_schedule_detail( $DokterID, $SectionID );
		
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
					$this->get_model()->delete_cache( 'common_schedules.collection' );
					
					make_flashdata(array(
							'response_status' => 'success',
							'message' => lang('global:updated_successfully')
						));
						
					redirect( 'common/schedules' );
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

		$option_times = $this->schedules_m->get_option_times();
		$option_section = $this->schedules_m->get_options("SIMmSection", array("TipePelayanan" => "Rj", "StatusAktif" => 1));
		
		if( $this->input->is_ajax_request() )
		{
			$data = array(
					'item' => $this->item,
					"is_ajax_request" => TRUE,
					"is_modal" => TRUE,
				);
			
			$this->load->view( 
					'schedules/modal/create_edit', 
					array('form_child' => $this->load->view('schedules/form', $data, true))
				);
		} else
		{
			$data = array(
					"page" => $this->page,
					"item" => $item,
					"collection" => $collection,
					"option_times" => $option_times,
					"option_section" => $option_section,
					"url_lookup_suppliers" => base_url("schedules/lookup_suppliers"),
					"url_lookup_replacement_suppliers" => base_url("schedules/lookup_replacement_suppliers"),
					"url_schedule_generate" => base_url("schedules/generate"),
					"form" => TRUE,
					"datatables" => TRUE,
				);
			
			$this->template
				->set( "heading", lang("schedules:edit_heading") )
				->set_breadcrumb( lang("schedules:breadcrumb"), base_url("schedules") )
				->set_breadcrumb( lang("schedules:edit_heading") )
				->build('schedules/form_edit', $data);
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
				
				$this->get_model()->delete_cache( 'common_schedules.collection' );
				
				make_flashdata(array(
						'response_status' => 'success',
						'message' => lang('global:deleted_successfully')
					));
			}
			
			redirect( $this->input->post( 'r_url' ) );
		}
		
		$this->load->view( 'schedules/modal/delete', array('item' => $this->item) );
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
	
	public function lookup( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'schedules/lookup/datatable' );
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
				->build('schedules/lookup', (isset($data) ? $data : NULL));
		}
	}
	
	public function lookup_doctor_schedule( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'schedules/lookup/datatable_view_doctor_schedule' );
		} 
	}

	
	public function lookup_suppliers( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			if( $this->input->get_post("is_modal") ){ $data["is_modal"] = TRUE; }
 			$data = array("type" => "doctor");
			
			$this->load->view( 'schedules/lookup/supplier', (isset($data) ? $data : NULL) );
		} else
		{
			redirect( base_url( "common/suppliers/lookup" ) );
		}
	}

	public function lookup_replacement_suppliers( $indexRow, $is_ajax_request = false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			if( $this->input->get_post("is_modal") ){ $data["is_modal"] = TRUE; }
 			$data = array(
				"type" => "doctor",
				"indexRow" => $indexRow
			);
			
			$this->load->view( 'schedules/lookup/replacement_supplier', (isset($data) ? $data : NULL) );
		} else
		{
			redirect( base_url( "common/suppliers/lookup" ) );
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
		
		$db_from = "{$this->schedules_m->table} a";
		$db_where = array();
		$db_like = array();
		
		// prepare defautl flter
		//$db_where['a.Active'] = 1;
		// preparing default
		if( isset($search['value']) && ! empty($search['value']) )
        {
            $keywords = $this->db->escape_str( $search['value'] );
			
			$db_like[ $this->db->escape_str("a.DokterID") ] = $keywords;
			
			$db_like[ $this->db->escape_str("a.SectionID") ] = $keywords;
			 
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
			->join( "{$this->supplier_m->table} b", "a.DokterID = b.Kode_Supplier", "LEFT OUTER" )
			->join( "{$this->supplier_specialist_m->table} e", "b.SpesialisID = e.SpesialisID", "LEFT OUTER" )
			->join( "{$this->section_m->table} c", "a.SectionID = c.SectionID", "LEFT OUTER" )
			;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }		
		$records_filtered = $this->db->count_all_results();
		
		// get result filtered
		$db_select = <<<EOSQL
			a.DokterID,
			a.SectionID,
			a.Senen,
			a.Selasa,
			a.Rabu,
			a.Kamis,
			a.Jumat,
			a.Sabtu,
			a.Minggu,
			a.FromJam,
			a.ToJam,
			b.Nama_Supplier,
			e.SpesialisName,
			c.SectionName,
EOSQL;

		$this->db
			->select( $db_select )
			->from( $db_from )
			->join( "{$this->supplier_m->table} b", "a.DokterID = b.Kode_Supplier", "LEFT OUTER" )
			->join( "{$this->supplier_specialist_m->table} e", "b.SpesialisID = e.SpesialisID", "LEFT OUTER" )
			->join( "{$this->section_m->table} c", "a.SectionID = c.SectionID", "LEFT OUTER" )
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
	
	public function doctor_schedule_collection()
    {
        $start = $this->input->get_post('start', true);
        $length = $this->input->get_post('length', true);
        $order = $this->input->get_post('order', true);
        $columns = $this->input->get_post('columns', true);
        $search = $this->input->get_post('search', true);
        $draw = $this->input->get_post('draw', true);
		
		$db_from = "SIMtrDokterJagaDetail AS a";
		$db_where = array();
		$db_like = array();
		
		if($this->input->post("today")){
			$db_where['a.tanggal'] = date("Y-m-d");
		}
		
		if($this->input->post("date_start")){
			$db_where['a.tanggal >='] = $this->input->post("date_start");
		}
		if($this->input->post("date_end")){
			$db_where['a.tanggal <='] = $this->input->post("date_end");
		}
				
		// preparing default
		if( isset($search['value']) && ! empty($search['value']) )
        {
            $keywords = $this->db->escape_str( $search['value'] );
						
			$db_like[ $this->db->escape_str("b.Nama_Supplier") ] = $keywords;
			$db_like[ $this->db->escape_str("e.SpesialisName") ] = $keywords;
			$db_like[ $this->db->escape_str("c.SectionName") ] = $keywords;
			$db_like[ $this->db->escape_str("d.Keterangan") ] = $keywords;
			$db_like[ $this->db->escape_str("a.Hari") ] = $keywords;
			$db_like[ $this->db->escape_str("a.Tanggal") ] = $keywords;

        }
		
		// get total records
		$this->db->from( $db_from )
			->join( "{$this->supplier_m->table} b", "a.DokterID = b.Kode_Supplier", "LEFT OUTER" )
			->join( "{$this->supplier_specialist_m->table} e", "b.SpesialisID = e.SpesialisID", "LEFT OUTER" )
			->join( "{$this->section_m->table} c", "a.SectionID = c.SectionID", "LEFT OUTER" )
			->join( "{$this->time_m->table} d", "a.WaktuID = d.WaktuID", "LEFT OUTER" )
		;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		$records_total = $this->db->count_all_results();
		
		// get total filtered
		$this->db
			->from( $db_from )
			->join( "{$this->supplier_m->table} b", "a.DokterID = b.Kode_Supplier", "LEFT OUTER" )
			->join( "{$this->supplier_specialist_m->table} e", "b.SpesialisID = e.SpesialisID", "LEFT OUTER" )
			->join( "{$this->section_m->table} c", "a.SectionID = c.SectionID", "LEFT OUTER" )
			->join( "{$this->time_m->table} d", "a.WaktuID = d.WaktuID", "LEFT OUTER" )
			;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }		
		$records_filtered = $this->db->count_all_results();
		
		// get result filtered
		$db_select = <<<EOSQL
			a.DokterID,
			a.SectionID,
			a.Cancel,
			a.DokterPenggantiID,
			a.NoAntrianTerakhir,
			a.WaktuID,
			a.Hari,
			a.Tanggal,
			b.Nama_Supplier,
			e.SpesialisName,
			c.SectionName,
			d.Keterangan
			
EOSQL;

		$this->db
			->select( $db_select )
			->from( $db_from )
			->join( "{$this->supplier_m->table} b", "a.DokterID = b.Kode_Supplier", "LEFT OUTER" )
			->join( "{$this->supplier_specialist_m->table} e", "b.SpesialisID = e.SpesialisID", "LEFT OUTER" )
			->join( "{$this->section_m->table} c", "a.SectionID = c.SectionID", "LEFT OUTER" )
			->join( "{$this->time_m->table} d", "a.WaktuID = d.WaktuID", "LEFT OUTER" )
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
			if( $row->Cancel == 1 && !empty($row->DokterPenggantiID) )
			{
				$row->NamaDokterPengganti = "-";
			} else if( $row->Cancel == 1 && empty($row->DokterPenggantiID) ) {
				$row->NamaDokterPengganti = "-";
			} else if( $row->Cancel == 0 ) {
				$row->NamaDokterPengganti = "-";
			}
			
			$date = DateTime::createFromFormat("Y-m-d H:i:s.u", $row->Tanggal);
			$row->Tanggal = $date->format('Y-m-d');

            $output['data'][] = $row;
        }
		
		//print_r($output);exit;
		
		$this->template
			->build_json( $output );
    }
	
	public function generate( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() && $this->input->post() )
		{

			$response = array(
					"status" => "success",
					"message" => "",
					"code" => 200
				);

			$Day = $this->input->post("Day");
			$DokterID = $this->input->post("DokterID");
			$SectionID = $this->input->post("SectionID");
			$date_start = new DateTime( $this->input->post("date_start") );
			$date_end = new DateTime( $this->input->post("date_end") );
			$date_end = $date_end->modify( '+1 day' );
			
			$date_interval = DateInterval::createFromDateString('1 day');
			$date_period = new DatePeriod($date_start, $date_interval, $date_end);
			
			$hari = array(
				"Mon" => "Senin",
				"Tue" => "Selasa",
				"Wed" => "Rabu",
				"Thu" => "Kamis",
				"Fri" => "Jumat",
				"Sat" => "Sabtu",
				"Sun" => "Minggu",
			);
			
			$generate_schedules = array();
			foreach ( $date_period as $dt ):

				$D = $dt->format("D");

				if ( !empty($Day[ $D ]) )
				{
					if ( $Day[ $D ]['WaktuPagiID'] > 1 )
					{
						$waktu = $this->db->where("WaktuID", $Day[ $D ]['WaktuPagiID'])->get("SIMmWaktuPraktek")->row();
						$generate_schedules[] = array(
								"Tanggal" => $dt->format("Y-m-d"),
								"WaktuID" => $Day[ $D ]['WaktuPagiID'],
								"Hari" => $hari[ $D ],
								"Keterangan" => $waktu->Keterangan,
								"Cancel" => 0,
								"DokterPenggantiID" => NULL,
								"Nama_Supplier" => NULL,
								"Ruangan" => $Day[ $D ]['Ruangan'],
							);
					}

					if ( $Day[ $D ]['WaktuSoreID'] > 1 )
					{
						$waktu = $this->db->where("WaktuID", $Day[ $D ]['WaktuSoreID'])->get("SIMmWaktuPraktek")->row();
						$generate_schedules[] = array(
								"Tanggal" => $dt->format("Y-m-d"),
								"WaktuID" => $Day[ $D ]['WaktuSoreID'],
								"Hari" => $hari[ $D ],
								"Keterangan" => $waktu->Keterangan,
								"Cancel" => 0,
								"DokterPenggantiID" => NULL,
								"Nama_Supplier" => NULL,
								"Ruangan" => $Day[ $D ]['Ruangan'],
							);
					}					
				}

			endforeach;
			
			$response = array(
					"status" => 'success',
					"message" => "Berhasil Generate Jadwal Dokter!",
					"code" => 200,
					"generate_schedules" => $generate_schedules,
				);

			print_r( json_encode($response, JSON_NUMERIC_CHECK) );
			exit(0);
		} 
		
	}	

}



