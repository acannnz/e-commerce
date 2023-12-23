<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include_once("Inquiry.php")	;

class Pharmacy extends Inquiry
{
	protected $_translation = 'inquiry';	
	protected $_model = 'inquiry_m';
	
	public function __construct()
	{
		parent::__construct();
				
	}
	
	public function request( )
	{
			
		$this->template
			->set( "heading", "Amprah Barang Farmasi" )
			->set_breadcrumb( "Amprahan", base_url("inquiry/pharmacy/request") )
			->set_breadcrumb( "Amprah Barang Farmasi" )
			;

		$this->request_( "SECT0002" );
	}

	public function request_list( )
	{

		$section = $this->inquiry_m->get_row_data("SIMmSection", array("SectionID" => "SECT0002" ));

		$data = array(
				'page' => $this->page,
				'section' => $section,
				"form" => TRUE,
				'datatables' => TRUE,
				'create_link' => base_url("inquiry/pharmacy/request"),
			);
		
		$this->template
			->set( "heading", "List Amprahan Farmasi" )
			->set_breadcrumb( lang("inquiry:breadcrumb") )
			->set_breadcrumb( "Amprahan Farmasi", current_url() )
			->build('pharmacies/datatable', (isset($data) ? $data : NULL));
	}
	
	public function mutation( )
	{

		$this->template
			->set( "heading", "Mutasi Barang Farmasi" )
			->set_breadcrumb( "Mutasi", current_url() )
			->set_breadcrumb( "Mutasi Barang Farmasi" )
			;
			
		$this->mutation_( "SECT0002" );
	}
	
	public function mutation_view( $mutation_number )
	{

		$this->template
			->set( "heading", "Mutasi Barang Farmasi" )
			->set_breadcrumb( "Mutasi", current_url() )
			->set_breadcrumb( "Mutasi Barang Farmasi" )
			;
			
		$this->mutation_view_( $mutation_number, "SECT0002" );
	}

	public function mutation_list( )
	{

		$section = $this->inquiry_m->get_row_data("SIMmSection", array("SectionID" => "SECT0002" ));

		$data = array(
				'page' => $this->page,
				'section' => $section,
				"form" => TRUE,
				'datatables' => TRUE,
				'create_link' => base_url("inquiry/pharmacy/mutation"),
			);
		
		$this->template
			->set( "heading", "List Mutasi Farmasi" )
			->set_breadcrumb( "Mutasi" )
			->set_breadcrumb( "Mutasi Farmasi", current_url() )
			->build('pharmacies/datatable_mutation', (isset($data) ? $data : NULL));
	}
	
	public function mutation_return( )
	{

		$this->template
			->set( "heading", "Retur Mutasi Barang Farmasi" )
			->set_breadcrumb( "Retur", current_url() )
			->set_breadcrumb( "Retur Mutasi Barang Farmasi" )
			;
			
		$this->mutation_return_( "SECT0002" );
	}

	public function mutation_return_view( $mutation_return_number )
	{

		$this->template
			->set( "heading", "Lihat Retur Mutasi Barang Farmasi" )
			->set_breadcrumb( "Retur", current_url() )
			->set_breadcrumb( "Lihat Retur Mutasi Barang Farmasi" )
			;
			
		$this->mutation_return_view_( $mutation_return_number, "SECT0002" );
	}

	public function mutation_return_list( )
	{

		$section = $this->inquiry_m->get_row_data("SIMmSection", array("SectionID" => "SECT0002" ));

		$data = array(
				'page' => $this->page,
				'section' => $section,
				"form" => TRUE,
				'datatables' => TRUE,
				'create_link' => base_url("inquiry/pharmacy/mutation_return"),
			);
		
		$this->template
			->set( "heading", "List Retur Mutasi Farmasi" )
			->set_breadcrumb( "Retur Mutasi" )
			->set_breadcrumb( "Retur Mutasi Farmasi", current_url() )
			->build('pharmacies/datatable_mutation_return', (isset($data) ? $data : NULL));
	}
	
	public function stock_opname( )
	{
		$this->template
			->set( "heading", "Stok Opname Farmasi" )
			->set_breadcrumb( "Stok Opname", current_url() )
			->set_breadcrumb( "Stok Opname Farmasi" )
			;
			
		$this->stock_opname_( "SECT0002" );
	}

	public function stock_opname_view( $No_Bukti )
	{
			$this->template
				->set( "heading", "Stok Opname Farmasi" )
				->set_breadcrumb( "Stok Opname", current_url() )
				->set_breadcrumb( "Stok Opname Farmasi" )
				;
				
		$this->stock_opname_view_( "SECT0002", $No_Bukti );
	}

	public function stock_opname_list()
	{
		
		$section = $this->inquiry_m->get_row_data("SIMmSection", array("SectionID" => "SECT0002" ));
		$option_section_opname = $this->inquiry_m->get_option_section_opname();
		$option_kelompok_jenis = $this->inquiry_m->get_options("SIMmKelompokJenisObat", array("Kelompok" => "OBAT"));

		$data = array(
				'page' => $this->page,
				'section' => $section,
				'option_section_opname' => $option_section_opname,
				'option_kelompok_jenis' => $option_kelompok_jenis,
				"form" => TRUE,
				'datatables' => TRUE,
				"create_link" => base_url("inquiry/pharmacy/stock-opname")
			);
		
		$this->template
			->set( "heading", "List Stok Opname Farmasi" )
			->set_breadcrumb( "Stok Opname" )
			->set_breadcrumb( "Stok Opname Farmasi", current_url() )
			->build('pharmacies/datatable_opname', (isset($data) ? $data : NULL));
	}

	public function item_create()
	{
		$item_data = array(
				'id' => 0,
				'NoResep' => inquiry_helper::gen_prescription_number("UGD"),
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
			
		//print_r($item_data);exit;
		
		$this->load->library( 'my_object', $item_data, 'item' );
		
		if( $this->input->post() ) 
		{
			
			
			print_r($this->input->post());exit;
			
			$resep = $this->input->post("f");
			$resep_detail = $this->input->post("details");
			
			$this->load->library( 'form_validation' );
			
			$this->item->addData( $this->input->post("f") );
			$this->form_validation->set_rules( $this->get_model()->rules['insert'] );
			$this->form_validation->set_data( $this->item->toArray() );
			
			if( !$this->form_validation->run() )
			{

				$this->db->trans_begin();
				
					$this->db->insert("SIMtrResep", $resep );				
					$this->db->insert_batch("SIMtrResepDetail", $resep_detail );
					
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
		}
		
		
		$option_pharmacy = $this->inquiry_m->get_options("SIMmSection", array("KelompokSection"  => "FARMASI", "GroupSection" => "4"));
		$lookup_supplier = base_url("pharmacy/emergencies/pharmacy/lookup_supplier");
		$lookup_product = base_url("pharmacy/emergencies/pharmacy/lookup_product");

		if( $this->input->is_ajax_request() )
		{
			$data = array(
					'item' => (object) $this->item->toArray(),
					"is_ajax_request" => TRUE,
					"is_modal" => TRUE,
					"option_pharmacy" => $option_pharmacy,
					"lookup_supplier" => $lookup_supplier,
					"lookup_product" => $lookup_product
				);
			
			$this->load->view( 'emergency/pharmacy/form', $data );
		} else
		{
			$data = array(
					"page" => $this->page."_".strtolower(__FUNCTION__),
					"item" => $this->item,
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
			
	public function create()
	{
		$item_data = array(
				'id' => 0,
				'NoBuktiMemo' => null,
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
					'item' => $this->item,
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
					"item" => $this->item,
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
					$this->get_model()->delete_cache( 'common_icd.collection' );
					
					make_flashdata(array(
							'response_status' => 'success',
							'message' => lang('global:updated_successfully')
						));
						
					redirect( 'common/icd' );
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
					'icd/modal/create_edit', 
					array('form_child' => $this->load->view('icd/form', $data, true))
				);
		} else
		{
			$data = array(
					"page" => $this->page,
					"item" => $this->item,
					"form" => TRUE,
				);
			
			$this->template
				->set( "heading", lang("icd:edit_heading") )
				->set_breadcrumb( lang("common:page"), base_url("common") )
				->set_breadcrumb( lang("services:breadcrumb"), base_url("common/icd") )
				->set_breadcrumb( lang("icd:edit_heading") )
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
	
	public function lookup( )
	{
		$this->_lookup( "SECT0002" );
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
		
		//$db_from = "{$this->inquiry_m->table} a";
		$db_from = "{$this->inquiry_m->table} a";
		$db_where = array();
		$db_like = array();
		
		$db_where['a.SectionAsal'] = $this->input->post("SectionID");
		
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
		;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		$records_total = $this->db->count_all_results();
		
		// get total filtered
		$this->db
			->from( $db_from )
			->join( "{$this->section_m->table} b", "a.SectionAsal = b.SectionID", "LEFT OUTER" )
			->join( "{$this->section_m->table} c", "a.SectionTujuan = c.SectionID", "LEFT OUTER" )
			->join( "mUser d", "a.UserID = d.User_ID", "LEFT OUTER" )
			;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }		
		$records_filtered = $this->db->count_all_results();
		
		// get result filtered
		$db_select = <<<EOSQL
			a.NoBukti,
			a.Tanggal,
			b.SectionName AS SectionNameAsal,
			c.SectionName AS SectionNameTujuan,
			a.Disetujui,
			a.Keterangan,
			d.Nama_Singkat
			
			
EOSQL;

		$this->db
			->select( $db_select )
			->from( $db_from )
			->join( "{$this->section_m->table} b", "a.SectionAsal = b.SectionID", "LEFT OUTER" )
			->join( "{$this->section_m->table} c", "a.SectionTujuan = c.SectionID", "LEFT OUTER" )
			->join( "mUser d", "a.UserID = d.User_ID", "LEFT OUTER" )
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
}