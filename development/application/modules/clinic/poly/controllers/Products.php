<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Products extends Admin_Controller
{
	protected $_translation = 'poly';	
	protected $_model = 'poly_m';
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role(['outpatient', 'inpatient']);
		
		$this->load->model("poly_m");
		$this->load->helper("poly");
		
		$this->page = "Paket Obat";
		$this->template->title( "Paket Obat" . ' - ' . $this->config->item('company_name') );
	}

	public function product_package( )
	{
		$data = array(
				"form" => TRUE,
				'datatables' => TRUE,
			);

			$this->template
				->set( "heading", "Buat Paket Obat" )
				->set_breadcrumb( "Poly" )
				->set_breadcrumb( "Paket Obat", base_url("poly/products") )
				->set_breadcrumb( "List Paket Obat" )
				->build('products/datatable_product_package', $data);
	}

	public function bhp_package( )
	{
		$data = array(
				"form" => TRUE,
				'datatables' => TRUE,
			);

			$this->template
				->set( "heading", "Buat Paket BHP" )
				->set_breadcrumb( "Poly" )
				->set_breadcrumb( "Paket BHP", base_url("poly/products") )
				->set_breadcrumb( "List Paket BHP" )
				->build('products/datatable_bhp_package', $data);
	}

	public function product_package_create()
	{
		$item = array(
				'KodePaket' => poly_helper::gen_product_package_number(), //Kode bisa: OBAT atau BHP
			);
					
		if( $this->input->post() ) 
		{
			
						
			$paket = $this->input->post("f");
			$detail = $this->input->post("details");
			
			$this->load->library( 'form_validation' );
			$this->form_validation->set_rules( $this->get_model()->rules['insert'] );
			$this->form_validation->set_data( $this->input->post("f") );
			
			if( !$this->form_validation->run() )
			{

				$this->db->trans_begin();
				
					$this->db->insert("SIMmPaketObat", $paket );				
					$this->db->insert_batch("SIMmDetailPaketObat", $detail );
					
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
							"KodePaket" => $paket['KodePaket'],
							"status" => 'success',
							"message" => lang('global:created_successfully'),
							"code" => 200
						);
				}				
			} else
			{
				$response = array(
						'status' => 'error',
						'message' => $this->form_validation->get_all_error_string()
					);
			}
			
			print_r(json_encode($response, JSON_NUMERIC_CHECK));
			exit;
		}
		
		$option_section = $this->poly_m->get_options("SIMmSection");
		$lookup_supplier = base_url("poly/emergencies/prescriptions/lookup_supplier");
		$lookup_product = base_url("poly/products/lookup_product");

		if( $this->input->is_ajax_request() )
		{
			$data = array(
					'item' => (object) $item,
					"is_ajax_request" => TRUE,
					"is_modal" => TRUE,
					"option_section" => $option_section,
					"lookup_supplier" => $lookup_supplier,
					"lookup_product" => $lookup_product
				);
			
			$this->load->view( 'products/form', $data );
		} else
		{
			$data = array(
					"page" => $this->page."_".strtolower(__FUNCTION__),
					'item' => (object) $item,
					"form" => TRUE,
					"datatables" => TRUE,
					"option_section" => $option_section,
					"lookup_supplier" => $lookup_supplier,
					"lookup_product" => $lookup_product
				);

			$this->template
				->set( "heading", "Buat Paket Obat" )
				->set_breadcrumb( "Poly" )
				->set_breadcrumb( "Paket Obat", base_url("poly/products/product_package") )
				->set_breadcrumb( "Buat Paket Obat" )
				->build('products/form_product_package', $data);
		}
	}

	public function product_package_edit( $KodePaket )
	{
		$item = $this->db->where("KodePaket", $KodePaket)->get("SIMmPaketObat")->row_array();
			
		if( $this->input->post() ) 
		{
			
			
			$paket = $this->input->post("f");
			$detail = $this->input->post("details");
			
			$this->load->library( 'form_validation' );		
			$this->form_validation->set_rules( $this->get_model()->rules['insert'] );
			$this->form_validation->set_data( $this->input->post("f") );
			
			if( !$this->form_validation->run() )
			{

				$this->db->trans_begin();
				
					$this->db->update("SIMmPaketObat", $paket, array("KodePaket" => $KodePaket) );				
					$this->db->delete("SIMmDetailPaketObat", array("KodePaket" => $KodePaket) );				
					
					$this->db->insert_batch("SIMmDetailPaketObat", $detail );
					
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
							"KodePaket" => $KodePaket,
							"status" => 'success',
							"message" => lang('global:created_successfully'),
							"code" => 200
						);
				}				
			} else
			{
				$response = array(
						'status' => 'error',
						'message' => $this->form_validation->get_all_error_string()
					);
			}
			
			print_r(json_encode($response, JSON_NUMERIC_CHECK));
			exit;
		}
		
		$collection = $this->poly_m->get_product_package_detail("SIMmDetailPaketObat", array("a.KodePaket" => $item_data['KodePaket']));
		$option_section = $this->poly_m->get_options("SIMmSection");
		$lookup_supplier = base_url("poly/emergencies/prescriptions/lookup_supplier");
		$lookup_product = base_url("poly/products/lookup_product");

		if( $this->input->is_ajax_request() )
		{
			$data = array(
					'item' => (object) $item,
					"is_ajax_request" => TRUE,
					"is_modal" => TRUE,
					"collection" => $collection,
					"option_section" => $option_section,
					"lookup_supplier" => $lookup_supplier,
					"lookup_product" => $lookup_product
				);
			
			$this->load->view( 'products/form', $data );
		} else
		{
			$data = array(
					"page" => $this->page."_".strtolower(__FUNCTION__),
					'item' => (object) $item,
					"form" => TRUE,
					"datatables" => TRUE,
					"collection" => $collection,
					"option_section" => $option_section,
					"lookup_supplier" => $lookup_supplier,
					"lookup_product" => $lookup_product
				);

			$this->template
				->set( "heading", "Edit Paket Obat" )
				->set_breadcrumb( "Poly" )
				->set_breadcrumb( "Paket Obat", base_url("poly/products/product_package") )
				->set_breadcrumb( "Edit Paket Obat" )
				->build('products/form_product_package', $data);
		}
	}

	public function bhp_package_create()
	{
		$item = array(
				'Kode' => poly_helper::gen_bhp_package_number(), //Kode bisa: OBAT atau BHP
			);
			
		if( $this->input->post() ) 
		{
			
			
			
			$paket = $this->input->post("f");
			$detail = $this->input->post("details");
			
			$this->load->library( 'form_validation' );		
			$this->form_validation->set_rules( $this->get_model()->rules['insert'] );
			$this->form_validation->set_data( $this->input->post("f") );
			
			if( !$this->form_validation->run() )
			{

				$this->db->trans_begin();
				
					$this->db->insert("SIMmPaketBHP", $paket );				
					$this->db->insert_batch("SIMmDetailPaketBHP", $detail );
					
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
							"KodePaket" => $paket['Kode'],
							"status" => 'success',
							"message" => lang('global:created_successfully'),
							"code" => 200
						);
				}				
			} else
			{
				$response = array(
						'status' => 'error',
						'message' => $this->form_validation->get_all_error_string()
					);
			}
			
			print_r(json_encode($response, JSON_NUMERIC_CHECK));
			exit;
		}
		
		$option_section = $this->poly_m->get_options("SIMmSection");
		$lookup_supplier = base_url("poly/emergencies/prescriptions/lookup_supplier");
		$lookup_product = base_url("poly/products/lookup_product/true/all_section");

		if( $this->input->is_ajax_request() )
		{
			$data = array(
					'item' => (object) $item,
					"is_ajax_request" => TRUE,
					"is_modal" => TRUE,
					"option_section" => $option_section,
					"lookup_supplier" => $lookup_supplier,
					"lookup_product" => $lookup_product
				);
			
			$this->load->view( 'products/form', $data );
		} else
		{
			$data = array(
					"page" => $this->page."_".strtolower(__FUNCTION__),
					'item' => (object) $item,
					"form" => TRUE,
					"datatables" => TRUE,
					"option_section" => $option_section,
					"lookup_supplier" => $lookup_supplier,
					"lookup_product" => $lookup_product
				);

			$this->template
				->set( "heading", "Buat Paket BHP" )
				->set_breadcrumb( "Poly" )
				->set_breadcrumb( "Paket BHP", base_url("poly/products/bhp_package") )
				->set_breadcrumb( "Buat Paket BHP" )
				->build('products/form_bhp_package', $data);
		}
	}

	public function bhp_package_edit( $KodePaket )
	{
		$item = $this->db->where("Kode", $KodePaket)->get("SIMmPaketBHP")->row_array();
			
		if( $this->input->post() ) 
		{
			
			
			//print_r($this->input->post());exit;
			
			$paket = $this->input->post("f");
			$detail = $this->input->post("details");
			
			$this->load->library( 'form_validation' );		
			$this->form_validation->set_rules( $this->get_model()->rules['insert'] );
			$this->form_validation->set_data( $this->input->post("f") );
			
			if( !$this->form_validation->run() )
			{

				$this->db->trans_begin();
				
					$this->db->update("SIMmPaketBHP", $paket, array("Kode" => $KodePaket) );				
					$this->db->delete("SIMmDetailPaketBHP", array("KodePaket" => $KodePaket) );				
					
					$this->db->insert_batch("SIMmDetailPaketBHP", $detail );
					
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
							"KodePaket" => $KodePaket,
							"status" => 'success',
							"message" => lang('global:created_successfully'),
							"code" => 200
						);
				}				
			} else
			{
				$response = array(
						'status' => 'error',
						'message' => $this->form_validation->get_all_error_string()
					);
			}
			
			print_r(json_encode($response, JSON_NUMERIC_CHECK));
			exit;
		}
		
		$collection = $this->poly_m->get_bhp_package_detail("SIMmDetailPaketBHP", array("a.KodePaket" => $item_data['Kode']));
		$option_section = $this->poly_m->get_options("SIMmSection");
		$lookup_supplier = base_url("poly/emergencies/prescriptions/lookup_supplier");
		$lookup_product = base_url("poly/products/lookup_product/true/all_section");

		if( $this->input->is_ajax_request() )
		{
			$data = array(
					'item' => (object) $item,
					"is_ajax_request" => TRUE,
					"is_modal" => TRUE,
					"collection" => $collection,
					"option_section" => $option_section,
					"lookup_supplier" => $lookup_supplier,
					"lookup_product" => $lookup_product
				);
			
			$this->load->view( 'products/form', $data );
		} else
		{
			$data = array(
					"page" => $this->page."_".strtolower(__FUNCTION__),
					'item' => (object) $item,
					"form" => TRUE,
					"datatables" => TRUE,
					"collection" => $collection,
					"option_section" => $option_section,
					"lookup_supplier" => $lookup_supplier,
					"lookup_product" => $lookup_product
				);

			$this->template
				->set( "heading", "Edit Paket BHP" )
				->set_breadcrumb( "Poly" )
				->set_breadcrumb( "Paket BHP", base_url("poly/products/bhp_package") )
				->set_breadcrumb( "Edit Paket BHP" )
				->build('products/form_bhp_package', $data);
		}
	}

	public function lookup_product( $is_ajax_request=false, $type = '' )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			if ( $type == '')
			{
				$this->load->view( 'products/lookup/products_section' );
			}
			if ( $type == 'all_section')
			{
				$this->load->view( 'products/lookup/products_all_section' );
			}
			
		} 
	}

	public function lookup_product_section( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request == true )
		{
			$this->load->view( 'products/lookup/datatable_section' );
		} 
	}

	public function lookup_product_section_bhp( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request == true )
		{
			$this->load->view( 'products/lookup/datatable_section_bhp' );
		} 
	}

	public function lookup_product_all_section( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'products/lookup/datatable_all_section' );
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
				->build('products/lookup/datatable_all_section', (isset($data) ? $data : NULL));
		}
	}
	
	public function lookup_product_section_usage( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request == true )
		{
			$this->load->view( 'products/lookup/datatable_section_usage' );
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
	
	public function lookup_collection( $is_ajax_request=false)
	{
		if( $this->input->is_ajax_request())
		{
			if ( $this->input->post("all_section") )
			{
				$this->datatable_product_all_collection();
			} else {
				// $this->datatable_product_all_collection();
				$this->datatable_product_section_collection();
			}			
	
		} 
	}
		
	public function datatable_product_section_collection()
    {
        $start = $this->input->get_post('start', true);
        $length = $this->input->get_post('length', true);
        $order = $this->input->get_post('order', true);
        $columns = $this->input->get_post('columns', true);
        $search = $this->input->get_post('search', true);
        $draw = $this->input->get_post('draw', true);
		
		$db_from = "mBarangLokasiNew a";
		$db_where = array();
		$db_like = array();
		
		if ($this->input->post('SectionID'))
		{
			$farmasi = $this->poly_m->get_row_data("SIMmSection", array("SectionID" => $this->input->post('SectionID')) );
			$db_where['a.Lokasi_ID'] = $farmasi->Lokasi_ID;
			$db_where['b.KelompokJenis !='] = 'BHP';
		}

		if ($this->input->post('Farmasi_SectionID'))
		{
			$farmasi = $this->poly_m->get_row_data("SIMmSection", array("SectionID" => $this->input->post('Farmasi_SectionID')) );
			$db_where['a.Lokasi_ID'] = $farmasi->Lokasi_ID;
		}
		
		// preparing default
		if( isset($search['value']) && ! empty($search['value']) )
        {
            $keywords = $this->db->escape_str( $search['value'] );
						
			$db_like[ $this->db->escape_str("a.Barang_ID") ] = $keywords;
			$db_like[ $this->db->escape_str("b.Nama_Barang") ] = $keywords;

        }
		
		// get total records
		$this->db->from( $db_from )
			->join( "mBarang b", "a.Barang_ID = b.Barang_ID", "LEFT OUTER" )
			->join( "mSatuan c", "b.Stok_Satuan_ID = c.Satuan_ID", "LEFT OUTER" )
			->join( "mKategori d", "b.Kategori_id = d.Kategori_ID", "LEFT OUTER" )
			->join( "mSubKategori e", "b.SubKategori_id = e.SubKategori_ID", "LEFT OUTER" )
		;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		$records_total = $this->db->count_all_results();
		
		// get total filtered
		$this->db
			->from( $db_from )
			->join( "mBarang b", "a.Barang_ID = b.Barang_ID", "LEFT OUTER" )
			->join( "mSatuan c", "b.Stok_Satuan_ID = c.Satuan_ID", "LEFT OUTER" )
			->join( "mKategori d", "b.Kategori_id = d.Kategori_ID", "LEFT OUTER" )
			->join( "mSubKategori e", "b.SubKategori_id = e.SubKategori_ID", "LEFT OUTER" )
			;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }		
		$records_filtered = $this->db->count_all_results();
		
		// get result filtered
		$db_select = <<<EOSQL
			a.Barang_ID,
			b.Kode_Barang,
			b.Nama_Barang,
			a.Qty_Stok,
			c.Nama_Satuan AS Satuan,
			d.Nama_Kategori AS Kategori,
			e.Nama_Sub_Kategori AS Sub_Kategori,
			b.Harga_Jual,
			b.Harga_Beli,
			b.Stok_Satuan_ID,
			b.Kategori_id,
			b.SubKategori_id
			
EOSQL;

		$this->db
			->select( $db_select )
			->from( $db_from )
			->join( "mBarang b", "a.Barang_ID = b.Barang_ID", "LEFT OUTER" )
			->join( "mSatuan c", "b.Stok_Satuan_ID = c.Satuan_ID", "LEFT OUTER" )
			->join( "mKategori d", "b.Kategori_id = d.Kategori_ID", "LEFT OUTER" )
			->join( "mSubKategori e", "b.SubKategori_id = e.SubKategori_ID", "LEFT OUTER" )
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
			
        $JenisKerjasamaID = $this->input->post("JenisKerjasamaID");
		$PasienKTP = $this->input->post("PasienKTP");
        $SectionID = $this->input->post("SectionID");
		$CustomerKerjasamaID = $this->input->post("CustomerKerjasamaID");

        foreach($result as $row)
        {
			
			$HargaGrading = $this->db->query("Select * from dbo.GetHargaObatNew_WithStok($JenisKerjasamaID, 'xx', $PasienKTP, $row->Barang_ID, $CustomerKerjasamaID, '$SectionID', 0)")->row();					
			$row->HNA = $HargaGrading->HPP_Baru;
			$row->HPP = $row->Harga_Beli;
			$row->Harga = $HargaGrading->Harga_Baru;
			$row->Jumlah = $HargaGrading->Harga_Baru;
			$row->HargaOrig = $HargaGrading->Harga_Baru;
			$row->HargaPersediaan =  $HargaGrading->HPP_Baru;
			$row->Stok =  $HargaGrading->Stok;

            $output['data'][] = $row;
        }
		
		$this->template
			->build_json( $output );
    }

	public function datatable_product_section_bhp_collection()
    {
        $start = $this->input->get_post('start', true);
        $length = $this->input->get_post('length', true);
        $order = $this->input->get_post('order', true);
        $columns = $this->input->get_post('columns', true);
        $search = $this->input->get_post('search', true);
        $draw = $this->input->get_post('draw', true);
		
		$db_from = "mBarangLokasiNew a";
		$db_where = array();
		$db_like = array();
		
		if ($this->input->post('SectionID'))
		{
			$farmasi = $this->poly_m->get_row_data("SIMmSection", array("SectionID" => $this->input->post('SectionID')) );
			$db_where['a.Lokasi_ID'] = 1426;
			$db_where['b.KelompokJenis'] = 'BHP';
		}

		if ($this->input->post('Farmasi_SectionID'))
		{
			$farmasi = $this->poly_m->get_row_data("SIMmSection", array("SectionID" => $this->input->post('Farmasi_SectionID')) );
			$db_where['a.Lokasi_ID'] = $farmasi->Lokasi_ID;
		}
		
		// preparing default
		if( isset($search['value']) && ! empty($search['value']) )
        {
            $keywords = $this->db->escape_str( $search['value'] );
						
			$db_like[ $this->db->escape_str("a.Barang_ID") ] = $keywords;
			$db_like[ $this->db->escape_str("b.Nama_Barang") ] = $keywords;

        }
		
		// get total records
		$this->db->from( $db_from )
			->join( "mBarang b", "a.Barang_ID = b.Barang_ID", "LEFT OUTER" )
			->join( "mSatuan c", "b.Stok_Satuan_ID = c.Satuan_ID", "LEFT OUTER" )
			->join( "mKategori d", "b.Kategori_id = d.Kategori_ID", "LEFT OUTER" )
			->join( "mSubKategori e", "b.SubKategori_id = e.SubKategori_ID", "LEFT OUTER" )
		;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		$records_total = $this->db->count_all_results();
		
		// get total filtered
		$this->db
			->from( $db_from )
			->join( "mBarang b", "a.Barang_ID = b.Barang_ID", "LEFT OUTER" )
			->join( "mSatuan c", "b.Stok_Satuan_ID = c.Satuan_ID", "LEFT OUTER" )
			->join( "mKategori d", "b.Kategori_id = d.Kategori_ID", "LEFT OUTER" )
			->join( "mSubKategori e", "b.SubKategori_id = e.SubKategori_ID", "LEFT OUTER" )
			;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }		
		$records_filtered = $this->db->count_all_results();
		
		// get result filtered
		$db_select = <<<EOSQL
			a.Barang_ID,
			b.Kode_Barang,
			b.Nama_Barang,
			a.Qty_Stok,
			c.Nama_Satuan AS Satuan,
			d.Nama_Kategori AS Kategori,
			e.Nama_Sub_Kategori AS Sub_Kategori,
			b.Harga_Jual,
			b.Harga_Beli,
			b.Stok_Satuan_ID,
			b.Kategori_id,
			b.SubKategori_id
			
EOSQL;

		$this->db
			->select( $db_select )
			->from( $db_from )
			->join( "mBarang b", "a.Barang_ID = b.Barang_ID", "LEFT OUTER" )
			->join( "mSatuan c", "b.Stok_Satuan_ID = c.Satuan_ID", "LEFT OUTER" )
			->join( "mKategori d", "b.Kategori_id = d.Kategori_ID", "LEFT OUTER" )
			->join( "mSubKategori e", "b.SubKategori_id = e.SubKategori_ID", "LEFT OUTER" )
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
			
        $JenisKerjasamaID = $this->input->post("JenisKerjasamaID");
		$PasienKTP = $this->input->post("PasienKTP");
        $SectionID = $this->input->post("SectionID");
		$CustomerKerjasamaID = $this->input->post("CustomerKerjasamaID");

        foreach($result as $row)
        {
			
			$HargaGrading = $this->db->query("Select * from dbo.GetHargaObatNew_WithStok($JenisKerjasamaID, 'xx', $PasienKTP, $row->Barang_ID, $CustomerKerjasamaID, '$SectionID', 0)")->row();					
			$row->HNA = $HargaGrading->HPP_Baru;
			$row->HPP = $row->Harga_Beli;
			$row->Harga = $HargaGrading->Harga_Baru;
			$row->Jumlah = $HargaGrading->Harga_Baru;
			$row->HargaOrig = $HargaGrading->Harga_Baru;
			$row->HargaPersediaan =  $HargaGrading->HPP_Baru;
			$row->Stok =  $HargaGrading->Stok;

            $output['data'][] = $row;
        }
		
		$this->template
			->build_json( $output );
    }

	public function datatable_product_all_collection()
    {
        $start = $this->input->get_post('start', true);
        $length = $this->input->get_post('length', true);
        $order = $this->input->get_post('order', true);
        $columns = $this->input->get_post('columns', true);
        $search = $this->input->get_post('search', true);
        $draw = $this->input->get_post('draw', true);
		
		$db_from = "mBarang b";
		$db_where = array();
		$db_like = array();
		
		// preparing default
		if( isset($search['value']) && ! empty($search['value']) )
        {
            $keywords = $this->db->escape_str( $search['value'] );
						
			$db_like[ $this->db->escape_str("b.Barang_ID") ] = $keywords;
			$db_like[ $this->db->escape_str("b.Nama_Barang") ] = $keywords;

        }
		
		// get total records
		$this->db->from( $db_from )
		;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		$records_total = $this->db->count_all_results();
		
		// get total filtered
		$this->db
			->from( $db_from )
			->join( "mSatuan c", "b.Stok_Satuan_ID = c.Satuan_ID", "LEFT OUTER" )
			->join( "mKategori d", "b.Kategori_id = d.Kategori_ID", "LEFT OUTER" )
			->join( "mSubKategori e", "b.SubKategori_id = e.SubKategori_ID", "LEFT OUTER" )
			;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }		
		$records_filtered = $this->db->count_all_results();
		
		// get result filtered
		$db_select = <<<EOSQL
			b.Barang_ID,
			b.Kode_Barang,
			b.Nama_Barang,
			b.KONVERSI,
			c.Nama_Satuan AS Satuan,
			d.Nama_Kategori AS Kategori,
			e.Nama_Sub_Kategori AS Sub_Kategori,
			b.Harga_Jual,
			b.Stok_Satuan_ID,
			b.Kategori_id,
			b.SubKategori_id,
			b.Barang_ID AS Qty_Stok
			
EOSQL;

		$this->db
			->select( $db_select )
			->from( $db_from )
			->join( "mSatuan c", "b.Stok_Satuan_ID = c.Satuan_ID", "LEFT OUTER" )
			->join( "mKategori d", "b.Kategori_id = d.Kategori_ID", "LEFT OUTER" )
			->join( "mSubKategori e", "b.SubKategori_id = e.SubKategori_ID", "LEFT OUTER" )
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

	public function datatable_product_package_collection()
    {
        $start = $this->input->get_post('start', true);
        $length = $this->input->get_post('length', true);
        $order = $this->input->get_post('order', true);
        $columns = $this->input->get_post('columns', true);
        $search = $this->input->get_post('search', true);
        $draw = $this->input->get_post('draw', true);
		
		$db_from = "SIMmPaketObat a";
		$db_where = array();
		$db_like = array();
		
		if ($this->input->post('SectionID'))
		{
			$farmasi = $this->poly_m->get_row_data("SIMmSection", array("SectionID" => $this->input->post('SectionID')) );
			$db_where['a.Lokasi_ID'] = $farmasi->Lokasi_ID;
		}
		
		// preparing default
		if( isset($search['value']) && ! empty($search['value']) )
        {
            $keywords = $this->db->escape_str( $search['value'] );
						
			$db_like[ $this->db->escape_str("a.Barang_ID") ] = $keywords;
			$db_like[ $this->db->escape_str("b.Nama_Barang") ] = $keywords;

        }
		
		// get total records
		$this->db->from( $db_from )
		;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		$records_total = $this->db->count_all_results();
		
		// get total filtered
		$this->db
			->from( $db_from )
			->join( "SIMmSection b", "a.SectionID = b.SectionID", "LEFT OUTER" )
			;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }		
		$records_filtered = $this->db->count_all_results();
		
		// get result filtered
		$db_select = <<<EOSQL
			a.KodePaket,
			a.NamaPaket,
			a.Keterangan,
			a.LastUpdate,
			
			b.SectionName
			
EOSQL;

		$this->db
			->select( $db_select )
			->from( $db_from )
			->join( "SIMmSection b", "a.SectionID = b.SectionID", "LEFT OUTER" )
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

	public function datatable_bhp_package_collection()
    {
        $start = $this->input->get_post('start', true);
        $length = $this->input->get_post('length', true);
        $order = $this->input->get_post('order', true);
        $columns = $this->input->get_post('columns', true);
        $search = $this->input->get_post('search', true);
        $draw = $this->input->get_post('draw', true);
		
		$db_from = "SIMmPaketBHP a";
		$db_where = array();
		$db_like = array();
		
		if ($this->input->post('SectionID'))
		{
			$farmasi = $this->poly_m->get_row_data("SIMmSection", array("SectionID" => $this->input->post('SectionID')) );
			$db_where['a.Lokasi_ID'] = $farmasi->Lokasi_ID;
		}
		
		// preparing default
		if( isset($search['value']) && ! empty($search['value']) )
        {
            $keywords = $this->db->escape_str( $search['value'] );
						
			$db_like[ $this->db->escape_str("a.Barang_ID") ] = $keywords;
			$db_like[ $this->db->escape_str("b.Nama_Barang") ] = $keywords;

        }
		
		// get total records
		$this->db->from( $db_from )
		;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		$records_total = $this->db->count_all_results();
		
		// get total filtered
		$this->db
			->from( $db_from )
			->join( "SIMmSection b", "a.SectionID = b.SectionID", "LEFT OUTER" )
			;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }		
		$records_filtered = $this->db->count_all_results();
		
		// get result filtered
		$db_select = <<<EOSQL
			a.Kode,
			a.NamaPaket,
			a.Ditagihkan,
			
			b.SectionName
			
EOSQL;

		$this->db
			->select( $db_select )
			->from( $db_from )
			->join( "SIMmSection b", "a.SectionID = b.SectionID", "LEFT OUTER" )
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

	public function lookup_item_usage_collection( $is_ajax_request=false)
	{
		if( $this->input->is_ajax_request())
		{
			
			$this->datatable_item_usage_collection();		
			
		} 
	}
		
	public function datatable_item_usage_collection()
    {
        $start = $this->input->get_post('start', true);
        $length = $this->input->get_post('length', true);
        $order = $this->input->get_post('order', true);
        $columns = $this->input->get_post('columns', true);
        $search = $this->input->get_post('search', true);
        $draw = $this->input->get_post('draw', true);
		
		$db_from = "mBarangLokasiNew a";
		$db_where = array();
		$db_like = array();
		
		if ($this->input->post('SectionID'))
		{
			$farmasi = $this->poly_m->get_row_data("SIMmSection", array("SectionID" => $this->input->post('SectionID')) );
			$db_where['a.Lokasi_ID'] = $farmasi->Lokasi_ID;
		}
		
		// preparing default
		if( isset($search['value']) && ! empty($search['value']) )
        {
            $keywords = $this->db->escape_str( $search['value'] );
						
			$db_like[ $this->db->escape_str("a.Barang_ID") ] = $keywords;
			$db_like[ $this->db->escape_str("b.Nama_Barang") ] = $keywords;

        }
		
		// get total records
		$this->db->from( $db_from )
			->join( "mBarang b", "a.Barang_ID = b.Barang_ID", "LEFT OUTER" )
			->join( "mSatuan c", "b.Stok_Satuan_ID = c.Satuan_ID", "LEFT OUTER" )
			->join( "mKategori d", "b.Kategori_id = d.Kategori_ID", "LEFT OUTER" )
			->join( "mSubKategori e", "b.SubKategori_id = e.SubKategori_ID", "LEFT OUTER" )
		;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		$records_total = $this->db->count_all_results();
		
		// get total filtered
		$this->db
			->from( $db_from )
			->join( "mBarang b", "a.Barang_ID = b.Barang_ID", "LEFT OUTER" )
			->join( "mSatuan c", "b.Stok_Satuan_ID = c.Satuan_ID", "LEFT OUTER" )
			->join( "mKategori d", "b.Kategori_id = d.Kategori_ID", "LEFT OUTER" )
			->join( "mSubKategori e", "b.SubKategori_id = e.SubKategori_ID", "LEFT OUTER" )
			;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }		
		$records_filtered = $this->db->count_all_results();
		
		// get result filtered
		$db_select = <<<EOSQL
			a.Barang_ID,
			b.Kode_Barang,
			b.Nama_Barang,
			a.Qty_Stok,
			c.Nama_Satuan AS Satuan,
			d.Nama_Kategori AS Kategori,
			e.Nama_Sub_Kategori AS Sub_Kategori,
			b.Harga_Jual,
			b.Harga_Beli,
			b.Stok_Satuan_ID,
			b.Kategori_id,
			b.SubKategori_id
			
EOSQL;

		$this->db
			->select( $db_select )
			->from( $db_from )
			->join( "mBarang b", "a.Barang_ID = b.Barang_ID", "LEFT OUTER" )
			->join( "mSatuan c", "b.Stok_Satuan_ID = c.Satuan_ID", "LEFT OUTER" )
			->join( "mKategori d", "b.Kategori_id = d.Kategori_ID", "LEFT OUTER" )
			->join( "mSubKategori e", "b.SubKategori_id = e.SubKategori_ID", "LEFT OUTER" )
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

}