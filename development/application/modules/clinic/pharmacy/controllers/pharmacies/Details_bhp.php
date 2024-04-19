<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Details_bhp extends Admin_Controller
{
	protected $_translation = 'pharmacy';	
	protected $_model = 'pharmacy_m';
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('pharmacy');
		
		$this->load->model("pharmacy_m");		
		$this->load->helper("pharmacy");	
	}
	
	public function index( $item = NULL )
	{
        // print_r($item);exit;
		$prescription_detail = $this->pharmacy_m->get_prescription_detail_bhp( @$item->NoBuktiPOP, $item );

		$collection = [];
		if ( !empty($prescription_detail)): foreach( $prescription_detail as $row):
			$row = [
				"Barang_ID" => $row->Barang_Id,
				"Kode_Barang" => ($row->Satuan != 'RACIKAN') ? $row->Kode_Barang : "RACIKAN",
				"Nama_Barang" => ($row->Satuan != 'RACIKAN') ? $row->Nama_Barang : $row->NamaResepObat,
				"Satuan" => $row->Satuan,
				"JmlObat" => $row->Qty,
				"Harga" => number_format($row->HargaSatuan, 2, ".", ""),
				"Disc" => 0.00,
				"BiayaResep" => (!empty(@$row->BiayaResep)) ? @$row->BiayaResep : 0.00,
				"Total" => $row->Qty * $row->HargaSatuan,
				"HExt" => currency_ceil( $row->Qty * $row->HargaSatuan ) - ($row->Qty * $row->HargaSatuan),
				"Stok" =>  $row->Stok,
				"TglED" => "",
				// "Dosis" => $row->Dosis,
				// "Dosis2" => $row->Dosis2,
				"NamaResepObat" => $row->Nama_Barang,
				"Keterangan" => ($row->Nama_Barang == $row->Nama_Barang) && ($row->Satuan != 'RACIKAN') 
								? "UMUM" : $row->Nama_Barang,
				"HNA" => $row->HNA,
				"HPP" => $row->HPP,
				"Harga" => $row->Harga,
				"HargaOrig" => $row->HargaOrig,
				"HargaPersediaan" => $row->HargaPersediaan,
				"batchs" => [],
			];
			
			$collection[] = $row;			
		endforeach; endif;
        // print_r($collection);exit;
		$data = [
				"item" => $item,
				"collection" => $collection,
				"option_dosis" => $this->pharmacy_m->get_options("SIMmDosisObat", array(), array("by" => "KodeDosis", "sort" => "ASC")),
			];
			
		$this->load->view( 'pharmacies/form/details_bhp', $data );		
	}

	public function view_bhp( $item = NULL )
	{
		$data = [
				"item" => $item,
				"collection" => $this->pharmacy_m->get_bhp_detail( @$item->NoBuktiPOP ),
				'print_etiket' => base_url("pharmacy/pharmacies/details/print_etiket")
			];
       
		$this->load->view( 'pharmacies/form/details_view_bhp', $data );		
	}

		// Print Etiket
		public function print_etiket()
		{
			if ($this->input->post()) 
			{
				$data_post = (object) $this->input->post();
				//exce KlinikFarmasiEtiket 'NoBuktiFarmasi','BarangID'
				$query = "exec KlinikFarmasiEtiket '$data_post->NoBukti', '$data_post->BarangID'";
				$query = $this->db->query( $query );
				$item = $query->row();

				$item->NoEtiket = substr($item->NoBukti, -5);
				$data = array(
					"item" => $item,
					"user" => $this->user_auth,
					"data_post" => $data_post
				);
	
				// PDF Content
				$html_content = $this->load->view( "pharmacies/print/etiket", $data, TRUE );    
				$file_name = "R-{$item->NoBukti}.pdf";
	
				$this->load->helper("export");
				
				$data_print = chunk_split(base64_encode(export_helper::print_pdf_string($html_content, $file_name, $footer = NULL, $margin_bottom = NULL, $header = NULL, $margin_top = NULL, $orientation = 'P', $margin_left = 4, $margin_right = 4)));
	
				$message =  [
					"data_print" => $data_print,
					"status" => 'success',
					"message" => 'Berhasil mencetak!',
					"code" => 200
				];
				response_json($message);
			}
		}
	
	public function lookup_detail( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'emergency/lookup/details' );
		} 
	}

	public function lookup_supplier( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'emergency/details/lookup/suppliers' );
		} 
	}
	
	public function lookup_product_bhp( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request == true )
		{	
			$this->load->view( 'pharmacies/details/lookup/products_bhp' );
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