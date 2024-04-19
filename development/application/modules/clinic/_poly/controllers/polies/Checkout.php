<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Checkout extends Admin_Controller
{
	protected $_translation = 'poly';	
	protected $_model = 'poly_m';
	protected $nameroutes = 'poly/polies';
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('poly');
		
		$this->load->model("poly_m");
		
	}
	
	public function index( $item = NULL )
	{
		$data = array(
				"collection" => $this->poly_m->get_checkout( array("NoReg" => @$item->RegNo, "SectionAsalID" => config_item('section_id') ) ),
				"form" => TRUE,
				'datatables' => TRUE,
				'nameroutes' => $this->nameroutes,
				"section_dropdown" => base_url("{$this->nameroutes}/checkout/section_dropdown"),
				"doctor_dropdown" => base_url("{$this->nameroutes}/checkout/doctor_dropdown"),
				"time_dropdown" => base_url("{$this->nameroutes}/checkout/time_dropdown"),
				"get_queue" => base_url("{$this->nameroutes}/checkout/get_queue"),
			);

		$this->load->view( 'polies/form/checkout', $data );		
	}
	
	public function lookup_checkout( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'polies/lookup/checkout' );
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

	public function section_dropdown( $selected='' )
	{
		if( $this->input->is_ajax_request() )
		{
			$items = $this->db
				->where( array("TipePelayanan" => "RJ", "StatusAktif" => 1))
				->order_by('SectionName', 'asc')
				->get("SIMmSection")
				->result()
				;
			
			$options_html = "";
			
			if( $selected == "" )
			{
				$options_html .= "\n<option data-sectionid=\"0\" data-sectionname=\"\" value=\"\" selected>".lang( 'global:select-empty' )."</option>";
			} else
			{
				$options_html .= "\n<option data-sectionid=\"0\" data-sectionname=\"\" value=\"\">".lang( 'global:select-empty' )."</option>";
			}
			
			foreach($items as $item)
			{
				
				$attr_data = "data-sectionid=\"{$item->SectionID}\" data-sectionname=\"{$item->SectionName}\" ";
				
				if( $selected == $item->SectionID)
				{
					$options_html .= "\n<option {$attr_data} value=\"{$item->SectionID}\" selected>{$item->SectionName}</option>";
				} else
				{
					$options_html .= "\n<option {$attr_data} value=\"{$item->SectionID}\">{$item->SectionName}</option>";
				}
			}
			
			print( $options_html );
			exit();
		}
	}

	public function doctor_dropdown( $selected='', $SectionID )
	{
		if( $this->input->is_ajax_request() )
		{
			$items = $this->db
				->select("a.*, b.Nama_Supplier")
				->from("SIMtrDokterJaga a")	
				->join("mSupplier b", "a.DokterID = b.Kode_Supplier", "LEFT OUTER")			
				->where( array("SectionID" => $SectionID) )
				->order_by('b.Nama_Supplier', 'asc')
				->get()
				->result()
				;
			
			$options_html = "";
			
			if( $selected == "" )
			{
				$options_html .= "\n<option data-dokterid=\"0\" data-nama_supplier=\"\" value=\"\" selected>".lang( 'global:select-empty' )."</option>";
			} else
			{
				$options_html .= "\n<option data-dokterid=\"0\" data-nama_supplier=\"\" value=\"\">".lang( 'global:select-empty' )."</option>";
			}
			
			foreach($items as $item)
			{
				
				$attr_data = "data-dokterid=\"{$item->DokterID}\" data-nama_supplier=\"{$item->Nama_Supplier}\" ";
				
				if( $selected == $item->DokterID)
				{
					$options_html .= "\n<option {$attr_data} value=\"{$item->DokterID}\" selected>{$item->Nama_Supplier}</option>";
				} else
				{
					$options_html .= "\n<option {$attr_data} value=\"{$item->DokterID}\">{$item->Nama_Supplier}</option>";
				}
			}
			
			print( $options_html );
			exit();
		}
	}

	public function time_dropdown( $selected='', $SectionID, $DokterID )
	{
		if( $this->input->is_ajax_request() )
		{
			$items = $this->db
				->select("a.*, b.Keterangan")
				->from("SIMtrDokterJagaDetail a")	
				->join("SIMmWaktuPraktek b", "a.WaktuID = b.WaktuID", "LEFT OUTER")			
				->where( array("SectionID" => $SectionID, "DokterID" => $DokterID, "Tanggal" => date("Y-m-d")) )
				->order_by('b.Keterangan', 'asc')
				->get()
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

	public function get_queue()
	{
		if ( $this->input->is_ajax_request() )		
		{
			$response = array(
					"status" => "success",
					"message" => "",
					"code" => 200
				);
			
			$data = $this->input->post();
			
			$query = $this->db->where( $data )
							->get( "SIMtrDokterJagaDetail" );
					

			if ( $query->num_rows() > 0 )
			{
				$response['NoUrut'] = ++$query->row()->NoAntrianTerakhir;
			} else {
				$response = array(
						"status" => "error",
						"message" => "Failed Generate No Urut",
						"code" => 500
					);
			}
			
			print_r(json_encode( $response, JSON_NUMERIC_CHECK ));
			exit(0);
					
		}
	}
}