<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Inquiry extends Admin_Controller
{
	protected $_translation = 'inquiry';	
	protected $_model = 'inquiry_m';
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role(['pharmacy', 'inpatient', 'outpatient']);
		
		$this->page = "inquiry";
		$this->template->title( "Amprahan" . ' - ' . $this->config->item('company_name') );

		$this->load->model( "common/supplier_m" );
		$this->load->model( "common/supplier_specialist_m" );
		$this->load->model( "common/section_m" );
				
		$this->load->helper( "inquiry" );
	}
	
	public function request_list($type = 'pharmacy')
	{
		$location = $this->session->userdata($type);
		$section = $this->inquiry_m->get_row_data("SIMmSection", ["SectionID" => $location['section_id']]);
				
		$data = [
			'page' => $this->page,
			'section' => $section,
			'type' => $type,
			'form' => TRUE,
			'datatables' => TRUE,
			'create_link' => base_url("inquiry/request/{$type}"),
		];
		
		$this->template
			->set_partial( 'aside', 'partials/admin/aside/'. $type )
			->set( "heading", "List Amprahan {$section->SectionName}" )
			->set_breadcrumb( "Amprahan" )
			->build('inquiries/datatable_request', (isset($data) ? $data : NULL));
	}
	
	public function request($type = 'pharmacy')
	{
		$location = $this->session->userdata($type);
		$item = [
			'NoBukti' => inquiry_helper::gen_evidence_number( $location['section_id'] ),
			'Tanggal' => date("Y-m-d"),
			'SectionAsal' => $location['section_id'],
		];
		
		if( $this->input->post() ) 
		{
			$amprahan = $this->input->post("amprahan");
			$amprahan["UserID"] = $this->user_auth->User_ID; // tambhakan data user id			
			$amprahan_detail = $this->input->post("amprahan_detail");

			$this->load->library( 'form_validation' );
			$this->form_validation->set_rules( $this->get_model()->rules['insert'] );
			$this->form_validation->set_data($this->input->post("amprahan") );
			
			$response = array(
					"status" => "success",
					"message" => "",
					"code" => 200
				);

			if( !$this->form_validation->run() )
			{

				$this->db->trans_begin();
										
					$this->db->insert("GD_trAmprahan", $amprahan );				
					$this->db->insert_batch("GD_trAmprahanDetail", $amprahan_detail );
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
							"NoBukti" => $amprahan['NoBukti'],
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

		$section_from = $this->inquiry_m->get_row_data("SIMmSection", array("SectionID" => $location['section_id']));
		
		$option_section_from = $this->inquiry_m->get_options("SIMmSection", array("TipePelayanan" => $section_from->TipePelayanan, "StatusAktif" => 1), array("by" => "SectionName", "sort" => "ASC"));
		$option_section_pharmacy = $this->inquiry_m->get_option_section_pharmacy();

		if( $this->input->is_ajax_request() )
		{
			$data = array(
					'item' => (object) @$item,
					"patient" => @$patient,
					"cooperation" => @$cooperation,
					"is_ajax_request" => TRUE,
					"is_modal" => TRUE,
				);
			
			$this->load->view( 
					'inquiry/modal/create_edit', 
					array('form_child' => $this->load->view('inquiry/form', $data, true))
				);
		} else
		{
			$data = array(
					"page" => $this->page."_".strtolower(__FUNCTION__),
					"item" => (object) @$item,
					"type" => $type,
					"inquiry" => @$inquiry,
					"section" => @$section_from,
					"option_section_from" => $option_section_from,
					"option_section_pharmacy" => $option_section_pharmacy,
					"form" => TRUE,
					"datatables" => TRUE,
					"lookup_products" => base_url("inquiry/inquiries/details/lookup_product"),
					"create_url" => current_url(),
				);
				
			$this->template
				->set_partial( 'aside', 'partials/admin/aside/'. $type )
				->build('inquiries/form_request', $data);
		}
	}
	
	public function request_view($type = 'pharmacy', $request_number )
	{
		$location = $this->session->userdata($type);
		$item = inquiry_helper::get_request( $request_number );

		$section_from = $this->inquiry_m->get_row_data("SIMmSection", array("SectionID" => $location['section_id']));
		$option_section_from = $this->inquiry_m->get_options("SIMmSection", array("TipePelayanan" => $section_from->TipePelayanan, "StatusAktif" => 1), array("by" => "SectionName", "sort" => "ASC"));
		$option_section_pharmacy = $this->inquiry_m->get_option_section_pharmacy();

		if( $this->input->is_ajax_request() )
		{
			$data = array(
					'item' => $item,
					"is_ajax_request" => TRUE,
					"is_modal" => TRUE,
				);
			
			$this->load->view( 
					'inquiry/modal/create_edit', 
					array('form_child' => $this->load->view('inquiry/form', $data, true))
				);
		} else
		{
			$data = array(
					"page" => $this->page."_".strtolower(__FUNCTION__),
					'item' => $item,
					'type' => $type,
					"section" => @$section_from,
					"option_section_from" => $option_section_from,
					"option_section_pharmacy" => $option_section_pharmacy,
					"user" => $this->simple_login->get_user(),
					"is_edit" => TRUE,
					"form" => TRUE,
					"datatables" => TRUE,
					"cancel_url" => base_url("inquiry/request_cancel/{$type}/{$request_number}"),
				);
			
			$this->template
				->set( "heading", "Lihat Amprahan" )
				// ->set_partial( 'aside', 'partials/admin/aside/'. $type )
				->set_breadcrumb( 'Amprahan', base_url("inquiry/request-list/{$type}") )
				->set_breadcrumb( "Lihat Amprahan" )
				->build('inquiries/form_request', $data);
		}
	}
	
	public function request_cancel($type = 'pharmacy', $id = 0)
	{
		$this->load->model('amprahan_model');
		$this->data['item'] = $item = $this->amprahan_model->get_one($id);
		
		if ($item  && (1 == $this->input->post('confirm') ) )
		{ 
			if ( $item->Realisasi == 1 )
			{
				$response = array(
							"status" => 'error',
							"message" => lang('message:already_realization'),
						);
				response_json( $response );
			}
			
			$this->db->trans_begin();
				
				$this->amprahan_model->update( ['Batal' => 1 ], $id );

				$activities_description = sprintf( "Delete Amprahan: # %s # %s # %s ", $item->NoBukti, $item->SectionAsal, $item->SectionTujuan );
				insert_user_activity( $activities_description, $item->NoBukti, $this->amprahan_model->table);
	
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
				response_json(["status" => 'error', 'message' => lang('global:cancel_failed'), 'success' => FALSE]);
			} else
			{
				$this->db->trans_commit();
				response_json(["status" => 'success', 'message' => lang('global:cancel_successfully'), 'success' => TRUE]);
			}
		} 
		
		$this->data['form_action'] = $form_action = current_url();
		$this->data['type'] = $type;
		$this->load->view('inquiries/modal/cancel_request', $this->data);
	}
	
	public function lookup_collection()
	{
		$this->datatable_collection();
	}
	
	public function datatable_collection()
    {
        $start = $this->input->get_post('start', true);
        $length = $this->input->get_post('length', true);
        $order = $this->input->get_post('order', true);
        $columns = $this->input->get_post('columns', true);
        $search = $this->input->get_post('search', true);
        $draw = $this->input->get_post('draw', true);
		
		$db_from = "{$this->inquiry_m->table} a";
		$db_where = array();
		$db_like = array();
		
		if ( $this->input->post("SectionID") )
		{
			$db_where['a.SectionAsal'] = $this->input->post("SectionID");
			$db_where['a.Batal'] = 0;
			$db_where['a.Realisasi'] = 0;
		}
				
		if ( $this->input->post("SectionTujuanID") )
		{
			$db_where['a.SectionTujuan'] = $this->input->post("SectionTujuanID");
			$db_where['a.Batal'] = 0;
			$db_where['a.Realisasi'] = 0;
		}
		
		if ($this->input->post("date_from"))
		{
			$db_where['a.Tanggal >='] = $this->input->post("date_from");
		}

		if ($this->input->post("date_till"))
		{
			$db_where['a.Tanggal <='] = $this->input->post("date_till");
		}
				
		// preparing default
		if( isset($search['value']) && ! empty($search['value']) )
        {
            $keywords = $this->db->escape_str( $search['value'] );
						
			$db_like[ $this->db->escape_str("a.NoBukti") ] = $keywords;

        }
		
		// get total records
		$this->db->from( $db_from )
			->join( "{$this->section_m->table} b", "a.SectionAsal = b.SectionID", "LEFT OUTER" )
			->join( "{$this->section_m->table} c", "a.SectionTujuan = c.SectionID", "LEFT OUTER" )
			->join( "mUser d", "a.UserID = d.User_ID", "LEFT OUTER" )
			->join( "mUser e", "a.DisetujuiUserID = e.User_ID", "LEFT OUTER")
		;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		$records_total = $this->db->count_all_results();
		
		// get total filtered
		$this->db
			->from( $db_from )
			->join( "{$this->section_m->table} b", "a.SectionAsal = b.SectionID", "LEFT OUTER" )
			->join( "{$this->section_m->table} c", "a.SectionTujuan = c.SectionID", "LEFT OUTER" )
			->join( "mUser d", "a.UserID = d.User_ID", "LEFT OUTER" )
			->join( "mUser e", "a.DisetujuiUserID = e.User_ID", "LEFT OUTER" )
			;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }		
		$records_filtered = $this->db->count_all_results();
		
		// get result filtered
		$db_select = <<<EOSQL
			a.NoBukti,
			a.Tanggal,
			a.SectionAsal AS SectionAsalID,
			a.SectionTujuan AS SectionTujuanID,
			b.SectionName AS SectionAsalName,
			b.Lokasi_ID AS Lokasi_Tujuan,
			c.SectionName AS SectionTujuanName,
			c.Lokasi_ID AS Lokasi_Asal,
			a.Disetujui,
			a.Realisasi,
			a.Keterangan,
			e.Nama_Singkat			
EOSQL;

		$this->db
			->select( $db_select )
			->from( $db_from )
			->join( "{$this->section_m->table} b", "a.SectionAsal = b.SectionID", "LEFT OUTER" )
			->join( "{$this->section_m->table} c", "a.SectionTujuan = c.SectionID", "LEFT OUTER" )
			->join( "mUser d", "a.UserID = d.User_ID", "LEFT OUTER" )
			->join( "mUser e", "a.DisetujuiUserID = e.User_ID", "LEFT OUTER" )
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
			
        $is_mutation = $this->input->post("mutation");
        foreach($result as $row)
        {	
			if ( $is_mutation )
			{
				$row->detail = $this->inquiry_m->get_inquiry_detail( $row->NoBukti );
			}		
			
			$row->Disetujui = $row->Disetujui == 1 ? 'Sudah' : 'Belum';
			$row->Realisasi = $row->Realisasi == 1 ? 'Sudah' : 'Belum';
            $output['data'][] = $row;
        }
		
		$this->template
			->build_json( $output );
    }
	
	public function mutation_list( $type = 'pharmacy' )
	{
		$location = $this->session->userdata($type);
		$section = $this->inquiry_m->get_row_data("SIMmSection", ["SectionID" => $location['section_id']]);

		$data = array(
				'page' => $this->page,
				'section' => $section,
				'type' => $type,
				"form" => TRUE,
				'datatables' => TRUE,
				'create_link' => base_url("inquiry/mutation/{$type}"),
			);
		
		$this->template
			->set_partial( 'aside', 'partials/admin/aside/'. $type )
			->title( "Mutasi" )
			->set( "heading", "List Mutasi {$section->SectionName}" )
			->set_breadcrumb( "Daftar Mutasi")
			->build('inquiries/datatable_mutation', (isset($data) ? $data : NULL));
	}

	public function mutation( $type = 'pharmacy' )
	{
		$this->page = "mutation";
		$location = $this->session->userdata($type);
		
		$item = array(
			'NoBukti' => inquiry_helper::gen_mutation_evidence_number( $location['section_id'] ),
			'Tanggal' => date("Y-m-d"),
			'SectionAsal' => $location['section_id'],
		);
		
		if( $this->input->post() ) 
		{
			$mutasi = $this->input->post("mutasi");
			$mutasi_detail = $this->input->post("mutasi_detail");

			$this->load->library( 'form_validation' );
			$this->form_validation->set_rules( $this->get_model()->rules['insert'] );
			$this->form_validation->set_data( $this->input->post("mutasi") );
			
			$response = array(
					"status" => "success",
					"message" => "",
					"code" => 200
				);

			if( !$this->form_validation->run() )
			{

				$this->db->trans_begin();
										
					$this->db->insert("GD_trMutasi", $mutasi );				
					$this->db->insert_batch("GD_trMutasiDetail", $mutasi_detail );

					foreach( $mutasi_detail as $k => $v )
					{						
						$_insert_warehouse_fifo_mutation = [
							'location_id' => $mutasi['Lokasi_Asal'], 
							'item_id' => $v['Barang_ID'],  
							'item_unit_code' => $v['Kode_Satuan'],  
							'qty' => $v['Qty'], 
							'price' => $v['Harga'],  
							'evidence_number' => $v['No_Bukti'],  
							'trans_type_id' => 520,
							'trans_date' => $mutasi['Tgl_Mutasi'],  
							'item_type_id' => $v['JenisBarangID'], 
							'to_location_id' => $mutasi['Lokasi_Tujuan'],
							'exp_date' => $mutasi['Tgl_Mutasi'],  
						];
						inquiry_helper::insert_warehouse_fifo_mutation( $_insert_warehouse_fifo_mutation );

						//Update data Pada GD_trAmprahanDetail
						$this->db->update("GD_trAmprahanDetail", array("Realisasi" => 1, "QtyRealisasiPertama" => $v["Qty"] ), array("NoBukti" => $mutasi['NoAmprahan'], "Barang_ID" => $v["Barang_ID"] ));
					}
					
					//Update data Pada GD_trAmprahan
					$this->db->update("GD_trAmprahan", [
							"Realisasi" => 1, 
							"JamUpdate" => date("Y-m-d H:i:s"),
							'Disetujui' => 1, 
							'DisetujuiTgl' => date('Y-m-d'), 
							'DisetujuiUserID' => $this->user_auth->User_ID
						], ["NoBukti" => $mutasi['NoAmprahan']]);
										
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
							"No_Bukti" => $mutasi['No_Bukti'],
							"type" => $type,
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

		$section_from = $this->inquiry_m->get_row_data("SIMmSection", array("SectionID" => $location['section_id']));
		
		$option_section_from = $this->inquiry_m->get_options("SIMmSection", array("TipePelayanan" => $section_from->TipePelayanan, "StatusAktif" => 1), array("by" => "SectionName", "sort" => "ASC"));
		$option_section_pharmacy = $this->inquiry_m->get_option_section_pharmacy();
		
		$data = array(
				"page" => $this->page."_".strtolower(__FUNCTION__),
				"item" => (object) @$item,
				"type" => $type,
				"inquiry" => @$inquiry,
				"section" => @$section_from,
				"user" => $this->simple_login->get_user(),
				"form" => TRUE,
				"datatables" => TRUE,
				"lookup_inquiry" => base_url("inquiry/lookup_inquiry"),
				"lookup_products" => base_url("inquiry/inquiries/details/lookup_product"),
				"create_url" => base_url("inquiry/mutation"),
			);
		
		$this->template
			->set_partial( 'aside', 'partials/admin/aside/'. $type )
			->title('Mutasi')
			->set( "heading", 'Mutasi' )
			->set_breadcrumb('Daftar Mutasi', base_url("inquiry/mutation-list/{$type}"))
			->set_breadcrumb('Kelola Mutasi')
			->build('inquiries/form_mutation', $data);
	}

	public function mutation_view( $type = 'pharmacy', $mutation_number )
	{
		$this->page = "mutation";
		$location = $this->session->userdata($type);
		$item = inquiry_helper::get_mutation( $mutation_number );

		$section_from = $this->inquiry_m->get_row_data("SIMmSection", array("SectionID" => $location['section_id']));
		$option_section_from = $this->inquiry_m->get_options("SIMmSection", array("TipePelayanan" => $section_from->TipePelayanan, "StatusAktif" => 1), array("by" => "SectionName", "sort" => "ASC"));
		$option_section_pharmacy = $this->inquiry_m->get_option_section_pharmacy();

		if( $this->input->is_ajax_request() )
		{
			$data = array(
					'item' => $item,
					"patient" => @$patient,
					"cooperation" => @$cooperation,
					"is_ajax_request" => TRUE,
					"is_modal" => TRUE,
				);
			
			$this->load->view( 
					'inquiry/modal/create_edit', 
					array('form_child' => $this->load->view('inquiry/form', $data, true))
				);
		} else
		{
			$data = array(
					"page" => $this->page."_".strtolower(__FUNCTION__),
					'item' => $item,
					'type' => $type,
					"section" => @$section_from,
					"user" => $this->simple_login->get_user(),
					"form" => TRUE,
					"datatables" => TRUE,
					"lookup_inquiry" => base_url("inquiry/lookup_inquiry"),
					"lookup_products" => base_url("inquiry/inquiries/details/lookup_product"),
					"create_url" => base_url("inquiry/mutation-list/{$type}"),
				);

			$this->template
				->set_partial( 'aside', 'partials/admin/aside/'. $type )
				->set( "heading", 'Lihat Mutasi' )
				->set_breadcrumb('Daftar Mutasi', base_url("inquiry/mutation-list/{$type}"))
				->set_breadcrumb('Lihat Mutasi')
				->build('inquiries/form_mutation_view', $data);
		}
	}
	
	public function datatable_mutation()
    {
        $start = $this->input->get_post('start', true);
        $length = $this->input->get_post('length', true);
        $order = $this->input->get_post('order', true);
        $columns = $this->input->get_post('columns', true);
        $search = $this->input->get_post('search', true);
        $draw = $this->input->get_post('draw', true);
		
		$db_from = "GD_trMutasi a";
		$db_where = array();
		$db_like = array();
		
		if ( $this->input->post("section_id") )
		{
			$db_where['d.SectionID'] = $this->input->post("section_id");
		}

		if ( $this->input->post("date_from") )
		{
			$db_where['a.Tgl_Mutasi >='] = $this->input->post("date_from");
		}

		if ( $this->input->post("date_till") )
		{
			$db_where['a.Tgl_Mutasi <='] = $this->input->post("date_till");
		}
										
		// preparing default
		if( isset($search['value']) && ! empty($search['value']) )
        {
            $keywords = $this->db->escape_str( $search['value'] );
						
			$db_like[ $this->db->escape_str("a.NoBukti") ] = $keywords;
			$db_like[ $this->db->escape_str("a.Keterangan") ] = $keywords;

        }
		
		// get total records
		$this->db->from( $db_from )
			->join( "mLokasi b", "a.Lokasi_Asal = b.Lokasi_ID", "LEFT OUTER" )
			->join( "mLokasi c", "a.Lokasi_Tujuan = c.Lokasi_ID", "LEFT OUTER" )
			->join( "SImmSection d", "a.Lokasi_Asal = d.Lokasi_ID")
		;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		$records_total = $this->db->count_all_results();
		
		// get total filtered
		$this->db
			->from( $db_from )
			->join( "mLokasi b", "a.Lokasi_Asal = b.Lokasi_ID", "LEFT OUTER" )
			->join( "mLokasi c", "a.Lokasi_Tujuan = c.Lokasi_ID", "LEFT OUTER" )
			->join( "SImmSection d", "a.Lokasi_Asal = d.Lokasi_ID")
			;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }		
		$records_filtered = $this->db->count_all_results();
		
		// get result filtered
		$db_select = <<<EOSQL
			a.No_Bukti, 
			a.Tgl_Mutasi, 
			b.Nama_Lokasi NamaLokasiAsal, 
			c.Nama_Lokasi NamaLokasiTujuan, 
			a.Keterangan
						
EOSQL;

		$this->db
			->select( $db_select )
			->from( $db_from )
			->join( "mLokasi b", "a.Lokasi_Asal = b.Lokasi_ID", "LEFT OUTER" )
			->join( "mLokasi c", "a.Lokasi_Tujuan = c.Lokasi_ID", "LEFT OUTER" )
			->join( "SImmSection d", "a.Lokasi_Asal = d.Lokasi_ID")
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
		
		$this->template
			->build_json( $output );
    }
	
	public function mutation_return_list( $type = 'pharmacy')
	{
		$location = $this->session->userdata($type);
		$section = $this->inquiry_m->get_row_data("SIMmSection", ["SectionID" => $location['section_id']]);

		$data = array(
				'page' => $this->page,
				'section' => $section,
				'type' => $type,
				'form' => TRUE,
				'datatables' => TRUE,
				'create_link' => base_url("inquiry/mutation_return/{$type}"),
			);
		
		$this->template
			->set_partial( 'aside', 'partials/admin/aside/'. $type )
			->set( "heading", "List Retur Mutasi Farmasi" )
			->title( "Retur Mutasi" . ' - ' . $this->config->item('company_name') )
			->set_breadcrumb( "Retur Mutasi")
			->build('inquiries/datatable_mutation_return', (isset($data) ? $data : NULL));
	}
		
	public function mutation_return($type = 'pharmacy')
	{
		$this->page = "mutation_return";
		$location = $this->session->userdata($type);

		$item = array(
			'NoBukti' => inquiry_helper::gen_mutation_return_evidence_number( $location['section_id'] ),
			'Tanggal' => date("Y-m-d"),
			'SectionAsal' => $location['section_id'],
		);

		if( $this->input->post() ) 
		{		
			$retur_mutasi = $this->input->post("retur_mutasi");
			$retur_mutasi_detail = $this->input->post("retur_mutasi_detail");

			$this->load->library( 'form_validation' );
			$this->form_validation->set_rules( $this->get_model()->rules['insert'] );
			$this->form_validation->set_data( $this->input->post("retur_mutasi") );
			
			$response = array(
					"status" => "success",
					"message" => "",
					"code" => 200
				);

			if( !$this->form_validation->run() )
			{

				$this->db->trans_begin();
										
					$this->db->insert("GD_trReturMutasi", $retur_mutasi );				
					$this->db->insert_batch("GD_trReturMutasiDetail", $retur_mutasi_detail );

					foreach( $retur_mutasi_detail as $k => $v )
					{						
						$_insert_fifo_out = [
							'location_id' => $retur_mutasi['Lokasi_Asal'], 
							'item_id' => $v['Barang_ID'],  
							'item_unit_code' => $v['Kode_Satuan'],  
							'qty' => $v['Qty'], 
							'price' => $v['Harga'],  
							'conversion' => 1,  
							'evidence_number' => $retur_mutasi['No_Bukti'],  
							'trans_type_id' => 566,
							'in_out_state' => 0,
							'trans_date' => date('Y-m-d'),  
							'exp_date' => 'Null',  
							'item_type_id' => 0, 
						];
						inquiry_helper::insert_warehouse_fifo( $_insert_fifo_out );
						
						$_insert_fifo_in = [
							'location_id' => $retur_mutasi['Lokasi_Tujuan'], 
							'item_id' => $v['Barang_ID'],  
							'item_unit_code' => $v['Kode_Satuan'],  
							'qty' => $v['Qty'], 
							'price' => $v['Harga'],  
							'conversion' => 1,  
							'evidence_number' => $retur_mutasi['No_Bukti'],  
							'trans_type_id' => 566,
							'in_out_state' => 1,
							'trans_date' => date('Y-m-d'),  
							'exp_date' => 'Null',  
							'item_type_id' => 0, 
						];
						inquiry_helper::insert_warehouse_fifo( $_insert_fifo_in );
					}
															
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
							"No_Bukti" => $retur_mutasi['No_Bukti'],
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

		$section_from = $this->inquiry_m->get_row_data("SIMmSection", ["SectionID" => $location['section_id']]);
		
		$option_section_from = $this->inquiry_m->get_options("SIMmSection", array("TipePelayanan" => "RJ", "StatusAktif" => 1), array("by" => "SectionName", "sort" => "ASC"));
		$option_section_pharmacy = $this->inquiry_m->get_option_section_pharmacy();

		if( $this->input->is_ajax_request() )
		{
			$data = array(
					'item' => (object) $item,
					"patient" => @$patient,
					"cooperation" => @$cooperation,
					"is_ajax_request" => TRUE,
					"is_modal" => TRUE,
				);
			
			$this->load->view( 
					'inquiry/modal/create_edit', 
					array('form_child' => $this->load->view('inquiry/form', $data, true))
				);
		} else
		{
			
			
			$data = array(
					"page" => $this->page."_".strtolower(__FUNCTION__),
					"item" => (object) @$item,
					"type" => $type,
					"section" => @$section_from,
					"option_section_from" => $option_section_from,
					"user" => $this->simple_login->get_user(),
					"form" => TRUE,
					"datatables" => TRUE,
					"lookup_inquiry" => base_url("inquiry/lookup_inquiry"),
					"lookup_products" => base_url("inquiry/inquiries/details/lookup_product"),
					"create_url" => base_url("inquiry/mutation_return/{$type}"),
				);
			
			$this->template
				->set_partial( 'aside', 'partials/admin/aside/'. $type )
				->set( "heading", "Retur Mutasi" )
				->title( "Retur Mutasi" . ' - ' . $this->config->item('company_name') )
				->set_breadcrumb( "List Retur Mutasi", base_url("inquiry/mutation-return-list/{$type}")  )
				->set_breadcrumb( "Retur Mutasi" )
				->build('inquiries/form_mutation_return', $data);
		}
	}

	public function mutation_return_view( $type = 'pharmacy', $mutation_return_number )
	{

		$this->page = "mutation_return";
		$location = $this->session->userdata($type);
		$item = inquiry_helper::get_mutation_return( $mutation_return_number );

		$section_tujuan = $this->inquiry_m->get_row_data("SIMmSection", ["Lokasi_ID" => $item->Lokasi_Tujuan])->SectionName;
		$section_from = $this->inquiry_m->get_row_data("SIMmSection", ["SectionID" => $location['section_id']]);

		
		$option_section_from = $this->inquiry_m->get_options("SIMmSection", array("TipePelayanan" => "RJ", "StatusAktif" => 1), array("by" => "SectionName", "sort" => "ASC"));
		$option_section_pharmacy = $this->inquiry_m->get_option_section_pharmacy();

		if( $this->input->is_ajax_request() )
		{
			$data = array(
					'item' => $item,
					"is_ajax_request" => TRUE,
					"is_modal" => TRUE,
				);
			
			$this->load->view( 
					'inquiry/modal/create_edit', 
					array('form_child' => $this->load->view('inquiry/form', $data, true))
				);
		} else
		{
			
			$data = array(
					"page" => $this->page."_".strtolower(__FUNCTION__),
					"item" => $item,
					"type" => $type,
					"section" => @$section_from,
					"option_section_from" => $option_section_from,
					"user" => $this->simple_login->get_user(),
					"form" => TRUE,
					"datatables" => TRUE,
					"lookup_inquiry" => base_url("inquiry/lookup_inquiry"),
					"lookup_products" => base_url("inquiry/inquiries/details/lookup_product"),
					"create_url" => base_url("inquiry/mutation_return/{$type}"),
					"section_tujuan" => @$section_tujuan
				);
			
			$this->template
				->set_partial( 'aside', 'partials/admin/aside/'. $type )
				->set( "heading", "Lihat Retur Mutasi" )
				->title( "Retur Mutasi" . ' - ' . $this->config->item('company_name') )
				->set_breadcrumb( "List Retur Mutasi", base_url("inquiry/mutation-return-list/{$type}")  )
				->set_breadcrumb( "Retur Mutasi" )
				->build('inquiries/form_mutation_return_view', $data);
		}
	}
	
	public function datatable_mutation_return()
    {
        $start = $this->input->get_post('start', true);
        $length = $this->input->get_post('length', true);
        $order = $this->input->get_post('order', true);
        $columns = $this->input->get_post('columns', true);
        $search = $this->input->get_post('search', true);
        $draw = $this->input->get_post('draw', true);
		
		$db_from = "GD_trReturMutasi a";
		$db_where = array();
		$db_like = array();
			
		if ( $this->input->post("section_id") )
		{
			$db_where['d.SectionID'] = $this->input->post("section_id");
		}

		if ( $this->input->post("date_from") )
		{
			$db_where['a.Tgl_Mutasi >='] = $this->input->post("date_from");
		}

		if ( $this->input->post("date_till") )
		{
			$db_where['a.Tgl_Mutasi <='] = $this->input->post("date_till");
		}
										
		// preparing default
		if( isset($search['value']) && ! empty($search['value']) )
        {
            $keywords = $this->db->escape_str( $search['value'] );
						
			$db_like[ $this->db->escape_str("a.NoBukti") ] = $keywords;
			$db_like[ $this->db->escape_str("a.Keterangan") ] = $keywords;

        }
		
		// get total records
		$this->db->from( $db_from )
			->join( "mLokasi b", "a.Lokasi_Asal = b.Lokasi_ID", "LEFT OUTER" )
			->join( "mLokasi c", "a.Lokasi_Tujuan = c.Lokasi_ID", "LEFT OUTER" )
			->join( "SImmSection d", "a.Lokasi_Asal = d.Lokasi_ID")
		;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		$records_total = $this->db->count_all_results();
		
		// get total filtered
		$this->db
			->from( $db_from )
			->join( "mLokasi b", "a.Lokasi_Asal = b.Lokasi_ID", "LEFT OUTER" )
			->join( "mLokasi c", "a.Lokasi_Tujuan = c.Lokasi_ID", "LEFT OUTER" )
			->join( "SImmSection d", "a.Lokasi_Asal = d.Lokasi_ID")
			;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }		
		$records_filtered = $this->db->count_all_results();
				
		// get result filtered
		$db_select = <<<EOSQL
			a.No_Bukti, 
			a.Tgl_Mutasi, 
			b.Nama_Lokasi NamaLokasiAsal, 
			c.Nama_Lokasi NamaLokasiTujuan, 
			a.Keterangan
						
EOSQL;

		$this->db
			->select( $db_select )
			->from( $db_from )
			->join( "mLokasi b", "a.Lokasi_Asal = b.Lokasi_ID", "LEFT OUTER" )
			->join( "mLokasi c", "a.Lokasi_Tujuan = c.Lokasi_ID", "LEFT OUTER" )
			->join( "SImmSection d", "a.Lokasi_Tujuan = d.Lokasi_ID")
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
		
		$this->template
			->build_json( $output );
    }
	
	public function stock_opname_list($type = 'pharmacy')
	{
		$location = $this->session->userdata($type);
		$section = $this->inquiry_m->get_row_data("SIMmSection", ["SectionID" => $location['section_id'] ]);
		$option_section_opname = $this->inquiry_m->get_option_section_opname();
		$option_kelompok_jenis = $this->inquiry_m->get_options("SIMmKelompokJenisObat", array("Kelompok" => "OBAT"));

		$data = array(
				'page' => $this->page,
				'section' => $section,
				'type' => $type,
				'option_section_opname' => $option_section_opname,
				'option_kelompok_jenis' => $option_kelompok_jenis,
				"form" => TRUE,
				'datatables' => TRUE,
				"create_url" => base_url("inquiry/stock-opname/{$type}")
			);
		
		$this->template
			->set_partial( 'aside', 'partials/admin/aside/'. $type )
			->title('Stock Opname')
			->set( "heading", 'Stok Opname' )
			->set_breadcrumb('Daftar Stok Opname', base_url("inquiry/stock-opname-list/{$type}"))
			->build('inquiries/datatable_opname', (isset($data) ? $data : NULL));
	}

	public function stock_opname($type = 'pharmacy')
	{

		$this->page = "stock_opname";
		$location = $this->session->userdata($type);

		$item = array(
			'No_Bukti' => inquiry_helper::gen_opname_evidence_number(),
			'Tanggal' => date("Y-m-d"),
			'SectionAsal' => $location['section_id'],
		);
		
		if( $this->input->post() ) 
		{
			$opname = $this->input->post("opname");
			$opname['No_Bukti'] = inquiry_helper::gen_opname_evidence_number();
			$opname_detail = $this->input->post("opname_detail");

			$this->load->library( 'form_validation' );
			$this->form_validation->set_rules( $this->get_model()->rules['insert'] );
			$this->form_validation->set_data( $this->input->post("opname") );
			
			$response = array(
					"status" => "success",
					"message" => "",
					"code" => 200
				);

			if( !$this->form_validation->run() )
			{

				$this->db->trans_begin();
										
					$this->db->insert("GD_trOpname", $opname );		
					foreach($opname_detail as $row):
						$row['No_Bukti'] = $opname['No_Bukti'];
						$this->db->insert("GD_trOpnameDetail", $row );
					endforeach;

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
							"No_Bukti" => $opname['No_Bukti'],
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

		$section_from = $this->inquiry_m->get_row_data("SIMmSection", array("SectionID" => $location['section_id']));
		
		$option_kelompok_jenis_obat = $this->inquiry_m->get_options("SIMmKelompokJenisObat", array("Kelompok" => "OBAT"), array("by" => "KelompokJenis", "sort" => "ASC"));

		if( $this->input->is_ajax_request() )
		{
			$data = array(
					'item' => (object) $item,
					"patient" => @$patient,
					"cooperation" => @$cooperation,
					"is_ajax_request" => TRUE,
					"is_modal" => TRUE,
				);
			
			$this->load->view( 
					'inquiry/modal/create_edit', 
					array('form_child' => $this->load->view('inquiry/form', $data, true))
				);
		} else
		{
			$data = array(
					"page" => $this->page."_".strtolower(__FUNCTION__),
					"item" => (object) @$item,
					"type" => $type,
					"section" => @$section_from,
					"option_kelompok_jenis_obat" => $option_kelompok_jenis_obat,
					"user" => $this->simple_login->get_user(),
					"form" => TRUE,
					"datatables" => TRUE,
					"lookup_inquiry" => base_url("inquiry/lookup_inquiry"),
					"lookup_product_opname" => base_url("inquiry/inquiries/detail_opnames/lookup_product_opname"),
					"create_url" => base_url("inquiry/stock_opname"),
				);
			
			$this->template
				->set_partial( 'aside', 'partials/admin/aside/'. $type )
				->title('Kelola Stock Opname')
				->set( "heading", 'Stok Opname' )
				->set_breadcrumb('Daftar Stok Opname', base_url("inquiry/stock-opname-list/{$type}"))
				->set_breadcrumb('Kelola Stok Opname')
				->build('inquiries/form_stock_opname', $data);
		}
	}

	public function stock_opname_view( $type= 'pharmacy', $No_Bukti )
	{
		$this->page = "stock_opname";
		$location = $this->session->userdata($type);
		$item = $this->db->where("No_Bukti", $No_Bukti)->get( "GD_trOpname" )->row();	
		
		if( $this->input->post() ) 
		{			
			$opname = $this->input->post("opname");
			$opname_detail = $this->input->post("opname_detail");

			$this->load->library( 'form_validation' );
			$this->form_validation->set_rules( $this->get_model()->rules['insert'] );
			$this->form_validation->set_data( $this->input->post("opname") );
			
			$response = array(
					"status" => "success",
					"message" => "",
					"code" => 200
				);

			if( !$this->form_validation->run() )
			{

				$this->db->trans_begin();
					$this->db->delete("GD_trOpnameDetail", array("No_Bukti" => $No_Bukti) );
					$this->db->insert_batch("GD_trOpnameDetail", $opname_detail );

					if($this->input->post("Posted") == 1)
					{		
						$this->db->update("GD_trOpname", array("Tgl_Update" => date("Y-m-d H:i:s")), array("No_Bukti" => $No_Bukti));										
					}

					if($this->input->post("Posted") == 1)
					{		
						$this->db->update("GD_trOpname", array("Posted" => 1, "Tgl_Update" => date("Y-m-d H:i:s")), array("No_Bukti" => $No_Bukti));										

						foreach( $opname_detail as $k => $v )
						{							
							$_insert_warehouse_fifo = [
								'location_id' => $opname['Lokasi_ID'],
								'item_id' => $v['Barang_ID'],  
								'item_unit_code' => $v['Kode_Satuan'],  
								'qty' => abs( $v['Stock_Akhir'] - $v['Qty_Opname'] ), 
								'price' => $v['Harga_Rata'],  
								'conversion' => 1,  
								'evidence_number' => $v["No_Bukti"],  
								'trans_date' => date("Y-m-d"),  
								'exp_date' => @$v['Tgl_Expired'] ? $v['Tgl_Expired'] : 'NULL',  
								'item_type_id' => $v['JenisBarangID'], 
							];
							
							if( $v['Qty_Opname'] > $v['Stock_Akhir'] ) 
							{
								$_insert_warehouse_fifo['trans_type_id'] = 560; // Stock opname bernilai plus
								$_insert_warehouse_fifo['in_out_state'] = 1;
								
							} elseif ( $v['Qty_Opname'] < $v['Stock_Akhir'] ) {
								
								$_insert_warehouse_fifo['trans_type_id'] = 561; // Stock opname bernilai min
								$_insert_warehouse_fifo['in_out_state'] = 0;					
							}
							inquiry_helper::insert_warehouse_fifo( $_insert_warehouse_fifo );
							
							// Update tanggal expired di mBarangLokasiNew, Jika Terdapat data Tanggal expired
							if( !empty($v["Tgl_Expired"]))
							{
								$this->db->update("mBarangLokasiNew", array("Tgl_Expired" => $v["Tgl_Expired"]), array("Lokasi_ID" => $opname['Lokasi_ID'], "Barang_ID" => $v["Barang_ID"]) );
							}
							
						}
					}
															
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
					//$this->db->trans_rollback();
					$this->db->trans_commit();
					$response = array(
							"No_Bukti" => $opname['No_Bukti'],
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

		$section_from = $this->inquiry_m->get_row_data("SIMmSection", array("SectionID" => $location['section_id']));
		
		$option_kelompok_jenis_obat = $this->inquiry_m->get_options("SIMmKelompokJenisObat", array("Kelompok" => "OBAT"), array("by" => "KelompokJenis", "sort" => "ASC"));

		if( $this->input->is_ajax_request() )
		{
			$data = array(
					'item' => $item,
					"patient" => @$patient,
					"cooperation" => @$cooperation,
					"is_ajax_request" => TRUE,
					"is_modal" => TRUE,
				);
			
			$this->load->view( 
					'inquiry/modal/create_edit', 
					array('form_child' => $this->load->view('inquiry/form', $data, true))
				);
		} else
		{
			$data = array(
					"page" => $this->page."_".strtolower(__FUNCTION__),
					"item" => @$item,
					"type" => $type,
					"section" => @$section_from,
					"option_kelompok_jenis_obat" => $option_kelompok_jenis_obat,
					"user" => $this->simple_login->get_user(),
					"form" => TRUE,
					"datatables" => TRUE,
					"lookup_inquiry" => base_url("inquiry/lookup_inquiry"),
					"lookup_product_opname" => base_url("inquiry/inquiries/detail_opnames/lookup_product_opname"),
					"print_stock_opname" =>base_url("inquiry/inquiries/report/stock_opname/{$item->No_Bukti}"),
					"create_url" => base_url("inquiry/stock_opname/{$type}"),
				);

			$this->template
				->set_partial( 'aside', 'partials/admin/aside/'. $type )
				->title('Kelola Stock Opname')
				->set( "heading", 'Stok Opname' )
				->set_breadcrumb('Daftar Stok Opname', base_url("inquiry/stock-opname-list/{$type}"))
				->set_breadcrumb('Kelola Stok Opname')
				->build('inquiries/form_stock_opname_view', $data);
		}
	}
	
	public function datatable_opname()
    {
        $start = $this->input->get_post('start', true);
        $length = $this->input->get_post('length', true);
        $order = $this->input->get_post('order', true);
        $columns = $this->input->get_post('columns', true);
        $search = $this->input->get_post('search', true);
        $draw = $this->input->get_post('draw', true);
		
		//$db_from = "{$this->inquiry_m->table} a";
		$db_from = "GD_trOpname a";
		$db_where = array();
		$db_like = array();
		
		if ( $this->input->post("Lokasi_ID") )
		{
			$db_where['a.Lokasi_ID'] = $this->input->post("Lokasi_ID");
		}

		if ( $this->input->post("KelompokJenis") != "ALL" )
		{
			$db_where['a.KelompokJenis'] = $this->input->post("KelompokJenis");
		}
						
		if ($this->input->post("Periode"))
		{
			$date = date_create_from_format("Y-m", $this->input->post("Periode") ); 
			$db_where['a.Tgl_Opname >= '] = date_format($date, 'Y-m-01');
			$db_where['a.Tgl_Opname <= '] = date_format($date, 'Y-m-t');
		}
				
		// preparing default
		if( isset($search['value']) && ! empty($search['value']) )
        {
            $keywords = $this->db->escape_str( $search['value'] );
						
			$db_like[ $this->db->escape_str("a.No_Bukti") ] = $keywords;
			$db_like[ $this->db->escape_str("a.Keterangan") ] = $keywords;

        }
		
		// get total records
		$this->db->from( $db_from )
			->join( "mUser b", "a.User_ID = b.User_ID", "LEFT OUTER" )
		;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		$records_total = $this->db->count_all_results();
		
		// get total filtered
		$this->db
			->from( $db_from )
			->join( "mUser b", "a.User_ID = b.User_ID", "LEFT OUTER" )
			;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }		
		$records_filtered = $this->db->count_all_results();
		
		// get result filtered
		$db_select = <<<EOSQL
			a.No_Bukti,
			a.Tgl_Opname,
			a.KelompokJenis,
			a.Keterangan,
			a.Posted,
			b.Nama_Singkat
			
			
EOSQL;

		$this->db
			->select( $db_select )
			->from( $db_from )
			->join( "mUser b", "a.User_ID = b.User_ID", "LEFT OUTER" )
			
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
				
	public function lookup_product( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'inquiries/lookup/products' );
		} 
	}

	public function lookup_inquiry( $type = 'pharmacy' )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$location = $this->session->userdata($type);
			
			$data = [
				"SectionID" => $location['section_id']
			];
			$this->load->view( 'inquiries/lookup/inquiries', $data );
		} 
	}
}



