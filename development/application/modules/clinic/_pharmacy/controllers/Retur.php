<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Retur extends Admin_Controller
{ 
	protected $_translation = 'retur';	
	protected $_model = 'retur_m'; 
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('pharmacy');
		//jika berlaku clerk
		if(config_item('use_clerk') == 'TRUE')
		{
			if(empty($this->session->userdata('KodeClerk')))
			{
				redirect('clerk/start');
			}
		}

		
		$this->page = "retur";
		$this->template->title( lang("retur:page") . ' - ' . $this->config->item('company_name') );
		
		$this->load->model("retur_m");
		$this->load->model("registration_m");
		$this->load->model( "common/patient_m" );
		$this->load->model( "common/patient_type_m" );
		$this->load->model( "common/section_m" );
		$this->load->model( "common/supplier_m" );

		$this->load->helper("retur");
	}
	
	public function index()
	{
		$data = array(
				'page' => $this->page,
				"form" => TRUE,
				'datatables' => TRUE,
			);
		
		$this->template
			->set( "heading", lang("retur:page") )
			->set_breadcrumb( lang("retur:breadcrumb") )
			->build('retur/datatable', (isset($data) ? $data : NULL));
	}
	
	public function create()
	{
	  
		$item = (object) [
				"NoRetur" => retur_helper::gen_evidence_number(),
				"Tanggal" => date("Y-m-d"),
				"Jam" => date("Y-m-d H:i:s"),
				"SectionID" => config_item('section_id'),
				"Keterangan" => NULL, 
				"UserID" => $this->user_auth->User_ID,
				"ObatSudahDipakai" => 1,
				"NoReg" => NULL
			];
			
		if( $this->input->post() ) 
		{
			$response = array(
					"status" => "success",
					"message" => "",
					"code" => 200
				);
				
			$header = (object) array_merge( (array) $item, $this->input->post('f') );
			$detail = $this->input->post('d');
			
			$validation = TRUE;
			if ( empty($detail))
			{
				$validation = FALSE;
				$message = "Anda Belum Memilih Barang! Silahkan Pilih Terlebih Dahulu.";
			}
					
			$this->load->library( 'form_validation' );
			$this->form_validation->set_rules( $this->get_model()->rules['insert'] );
			$this->form_validation->set_data( (array) $header );
			
			if( $this->form_validation->run() && $validation )
			{
				$this->db->trans_begin();
					$header->NoReg = !empty($header->NoReg) ? $header->NoReg : NULL;
					$this->db->insert('ReturFarmasi', $header);
					
					$section = $this->db->where('SectionID', (!empty(config_item('section_id'))) ? config_item('section_id') : $header->SectionID)->get('SIMmSection')->row();
					$qty_remain = $total = 0;
					foreach( $detail as $row ):
						$row['NoRetur'] = $header->NoRetur;
						
						$this->db->insert('ReturFarmasiDetail', $row);
						
						$this->db->set('JmlRetur', 'JmlRetur + '.$row['Qty_Retur'], FALSE)
								->where(['NoBukti' => $header->NoBukti, 'Barang_ID' => $row['Barang_ID']])
								->update('BILLFarmasiDetail');
						
						$this->db->set('JmlRetur', 'JmlRetur + '.$row['Qty_Retur'], FALSE)
								->where(['NoBukti' => $header->NoBukti, 'Barang_ID' => $row['Barang_ID']])
								->update('BillFarmasiPemakaian');
						
						$this->db->set('Qty', 'Qty - '.$row['Qty_Retur'], FALSE)
								->where(['Lokasi_ID' => $section->Lokasi_ID, 'Barang_ID' => $row['Barang_ID'], 'JenisBarangID' => $row['JenisBarangID'], 'Tahun' => date('Y'), 'Bulan' => date('n')])
								->update('mBarangLokasiPemakaian');
						
						$_insert_fifo = [
							'location_id' => $section->Lokasi_ID, 
							'item_id' => $row['Barang_ID'],  
							'item_unit_code' => $row['Satuan'],  
							'qty' => $row['Qty_Retur'], 
							'price' => $row['Harga'],  
							'conversion' => 1,  
							'evidence_number' => $row['NoRetur'],  
							'trans_type_id' => 562,
							'in_out_state' => 1,
							'trans_date' => date('Y-m-d'),  
							'exp_date' => date('Y-m-d'),  
							'item_type_id' => (int) $row['JenisBarangID'], 
						];
						
						retur_helper::insert_warehouse_fifo( $_insert_fifo );
						
					endforeach;
					
					$bill_pharmacy_detail = $this->db->where(['NoBukti' => $header->NoBukti, 'Barang_ID !=' => 0])->get('BILLFarmasiDetail')->result();
					foreach($bill_pharmacy_detail as $row)
					{	
						$qty_remain = $qty_remain + ( $row->JmlObat - $row->JmlRetur);
						$sub_total = ($row->JmlObat - $row->JmlRetur) * $row->Harga;
						$hext = 0;
						if($row->Barang_ID != 0)
						{
							$ceil = currency_ceil($sub_total);
							$hext = $ceil - $sub_total;
							$this->db->set('HExt', $hext)
								->where(['NoBukti' => $header->NoBukti, 'Barang_ID' => $row->Barang_ID])
								->update('BILLFarmasiDetail');
						}
						
						$BiayaResep = $row->BiayaResep;
						if($sub_total <= 0)
						{
							$BiayaResep = 0;
							$this->db->set('BiayaResep', "BiayaResep - ". $row->BiayaResep, FALSE)
									->where('NoBukti', $header->NoBukti)
										->update('BILLFarmasi');
						}
						
						$total = $total + ( $sub_total + $hext) + $BiayaResep;
					}
					
					if( $qty_remain <= 0 )
					{						
						$this->db->set('Retur', 1)
								->where('NoBukti', $header->NoBukti)
								->update('BILLFarmasi');
					}

					$this->db->set('Total', $total)
							->where('NoBukti', $header->NoBukti)
								->update('BILLFarmasi');

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
					//$this->db->trans_rollback();
					$response = array(
							"status" => 'success',
							"message" => lang('global:created_successfully'),
							"code" => 200
						);
				}		

			} else
			{
				$response = array(
						"status" => 'error',
						"message" => !$validation ? $message : $this->form_validation->get_all_error_string(),
						"code" => 500
					);
			}
			
			response_json($response);
		}

		if( $this->input->is_ajax_request() )
		{
			$data = array(
					"item" => $item,
					"section" => $this->section,
					"lookup_product" => base_url("pharmacy/retur/lookup_product"),
					"is_ajax_request" => TRUE,
					"is_modal" => TRUE,
				);
			
			$this->load->view( 
					'retur/modal/create_edit', 
					array('form_child' => $this->load->view('retur/form', $data, true))
				);
		} else
		{
			$data = array(
					"page" => $this->page."_".strtolower(__FUNCTION__),
					"item" => $item,
					"section" => $this->section,
					"lookup_billing_pharmacy" => base_url("pharmacy/retur/lookup/billing_pharmacy"),
					"form" => TRUE,
					"datatables" => TRUE,
				);

			$this->template
				->set( "heading", lang("retur:create_heading") )
				->set_breadcrumb( lang("retur:breadcrumb"), base_url("pharmacy/retur") )
				->set_breadcrumb( lang("retur:create_heading") )
				->build('retur/form', $data);
		}
	}
	
	public function view( $id = 0 )
	{
		$item = $this->retur_m->get_retur( $id );
		$collection = $this->retur_m->get_retur_detail( $id );
		
		if( $this->input->is_ajax_request() )
		{
			$data = array(
					"item" => $item,
					"collection" => $collection,
					"is_ajax_request" => TRUE,
					"is_modal" => TRUE,
					"is_edit" => TRUE,
				);
			
			$this->load->view( 
					'retur/modal/create_edit', 
					array('form_child' => $this->load->view('retur/form', $data, true))
				);
		} else
		{
			$data = array(
					"page" => $this->page,
					"item" => $item,
					"collection" => $collection,
					"form" => TRUE,
					"datatables" => TRUE,
					"is_edit" => TRUE,
				);
			
			$this->template
				->set( "heading", lang("retur:view_heading") )
				->set_breadcrumb( lang("retur:breadcrumb"), base_url("pharmacy/retur") )
				->set_breadcrumb( lang("retur:view_heading") )
				->build('retur/form', $data);
		}
	}
	
	public function lookup( $view, $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( "retur/lookup/{$view}" );
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
		
		$db_from = "{$this->retur_m->table} a";
		$db_where = array();
		$db_like = array();
		
		if(!empty(config_item('section_id'))){
			$db_where['a.SectionID'] = config_item('section_id');
		}
		
		
		if ($this->input->post("date_from"))
		{
			$db_where['a.Tanggal >='] = $this->input->post("date_from");
		}

		if ($this->input->post("date_till"))
		{
			$db_where['a.Tanggal <='] = $this->input->post("date_till");
		}
		
		if( isset($search['value']) && ! empty($search['value']) )
        {
            $keywords = $this->db->escape_str( $search['value'] );
			
			$db_like[ $this->db->escape_str("a.NoRetur") ] = $keywords;
			$db_like[ $this->db->escape_str("a.NoBukti") ] = $keywords;
			$db_like[ $this->db->escape_str("a.NoReg") ] = $keywords;
			$db_like[ $this->db->escape_str("b.NRM") ] = $keywords;
			$db_like[ $this->db->escape_str("b.NamaPasien_Reg") ] = $keywords;
        }
		
		// get total records
		$this->db->from( $db_from )
			->join( "{$this->registration_m->table} b", "a.NoReg = b.NoReg", "LEFT OUTER" )
			;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		$records_total = $this->db->count_all_results();
		
		// get total filtered
		$this->db
			->from( $db_from )
			->join( "{$this->registration_m->table} b", "a.NoReg = b.NoReg", "LEFT OUTER" )
			;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }		
		$records_filtered = $this->db->count_all_results();
		
		// get result filtered
		$db_select = <<<EOSQL
			a.*,
			b.NRM,
			b.NamaPasien_Reg
EOSQL;

		$this->db->select( $db_select )
			->from( $db_from )
			->join( "{$this->registration_m->table} b", "a.NoReg = b.NoReg", "LEFT OUTER" )
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
	
	public function pharmacy_collection()
    {
        $start = $this->input->get_post('start', true);
        $length = $this->input->get_post('length', true);
        $order = $this->input->get_post('order', true);
        $columns = $this->input->get_post('columns', true);
        $search = $this->input->get_post('search', true);
        $draw = $this->input->get_post('draw', true);
								
		$db_from = "BILLFarmasi a";
		$this->load->model("registration_m");
		$db_where = array();
		$db_like = array();
		
		$db_where['a.ClosePayment'] = 0;
		$db_where['a.Retur'] = 0;
		$db_where['a.Batal'] = 0;
		if(!empty(config_item('section_id'))){
			$db_where['a.SectionID'] = config_item('section_id');
		}
		
		//$db_where['b.StatusBayar'] = 'Belum';
		
		// preparing default
		if( isset($search['value']) && ! empty($search['value']) )
        {
            $keywords = $this->db->escape_str( $search['value'] );
						
			$db_like[ $this->db->escape_str("a.NoBukti") ] = $keywords;
			$db_like[ $this->db->escape_str("a.Tanggal") ] = $keywords;
			$db_like[ $this->db->escape_str("a.Jam") ] = $keywords;
			$db_like[ $this->db->escape_str("a.DokterID") ] = $keywords;
			$db_like[ $this->db->escape_str("a.Keterangan") ] = $keywords;
			$db_like[ $this->db->escape_str("c.NamaPasien") ] = $keywords;
        }
		
		// get total records
		$this->db->from( $db_from )
		;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		$records_total = $this->db->count_all_results();
		
		// get total filtered
		$this->db
			->from( $db_from )
			->join( "{$this->registration_m->table} b", "a.NoReg = b.NoReg", "LEFT OUTER" )
			->join( "{$this->patient_m->table} c", "b.NRM = c.NRM", "LEFT OUTER" )
			;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }		
		$records_filtered = $this->db->count_all_results();
		
		// get result filtered
		$db_select = <<<EOSQL
			a.NoBukti,
			c.NRM,
			a.Jam,
			a.Tanggal,
			c.NamaPasien,
			d.JenisKerjasama,
			e.Nama_Supplier,
			f.SectionName,
			f.SectionID,
			a.ObatBebas,
			a.NoReg,
			a.Keterangan
			
EOSQL;

		$this->db
			->select( $db_select )
			->from( $db_from )
			->join( "{$this->registration_m->table} b", "a.NoReg = b.NoReg", "LEFT OUTER" )
			->join( "{$this->patient_m->table} c", "b.NRM = c.NRM", "LEFT OUTER" )
			->join( "{$this->patient_type_m->table} d", "b.JenisKerjasamaID = d.JenisKerjasamaID", "LEFT OUTER" )
			->join( "{$this->supplier_m->table} e", "a.DokterID = e.Kode_Supplier", "LEFT OUTER" )
			->join( "{$this->section_m->table} f", "a.SectionID = f.SectionID", "LEFT OUTER" )
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
	
	public function get_pharmacy_detail()
	{
		if( $this->input->get() )
		{
			$NoBukti = $this->input->get('NoBukti');
			$collection = $this->retur_m->get_pharmacy_detail( $NoBukti );
			response_json($collection);
		}
	}

}



