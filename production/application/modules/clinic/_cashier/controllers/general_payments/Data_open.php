<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Data_open extends Admin_Controller
{
	protected $_translation = 'general_payment';	
	protected $_model = 'general_payment_m'; 
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('cashier');
	
		$this->load->model("general_payment_m");
		$this->load->model("registrations/registration_model");
		$this->load->model("common/patient_m");
		$this->load->model("common/customer_m");
		$this->load->model("common/patient_type_m");
		$this->load->model("common/section_m");
				
		$this->load->helper('general_payment');
	}
	
	public function index()
	{

		$data = array(
			'option_section' => $this->registration_model->get_option_section(),
			"option_doctor" => option_doctor(),
		);
					
		$this->load->view('general_payment/datatable/open', (isset($data) ? $data : NULL));
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
				->set_breadcrumb( lang("drug_payment:page"), base_url("drug_payment") )
				->set_breadcrumb( "Lookup Box" )
				->build('registrations/lookup', (isset($data) ? $data : NULL));
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
		
		$db_from = "{$this->registration_model->table} a";
		$db_where = array();
		$db_like = array();
		$db_custome_where = NULL;
		
		// prepare default
		$db_where['a.Batal'] = 0;	
		// $db_custome_where = "a.StatusPeriksa ='CO' AND a.StatusBayar='Belum'";	
		$db_custome_where = "(a.StatusPeriksa ='Sudah' OR a.StatusPeriksa ='CO')  AND a.StatusBayar='Belum'";	

		
		if( $this->input->post("date_from") ){
			$db_where['a.TglReg >='] = $this->input->post("date_from");
		}

		if( $this->input->post("date_till") ){
			$db_where['a.TglReg <='] = $this->input->post("date_till");
		}
		
		$db_where['a.ProsesPayment'] = $this->input->post("show_onprocess");
		
		if( $this->input->post("NRM") ){
			$db_like['a.NRM'] = $this->input->post("NRM");
		}

		if( $this->input->post("Nama") ){
			$db_like['a.NamaPasien_Reg'] = $this->input->post("Nama");
		}

		if( $this->input->post("SectionPerawatanID") ){
			$db_where['a.SectionPerawatanID'] = $this->input->post("SectionPerawatanID");
		}
		
		if( $this->input->post("DokterID")){
			$db_where['a.DokterRawatID'] = $this->input->post("DokterID");
		}
		
		if( isset($search['value']) && ! empty($search['value']) )
        {
            $keywords = $this->db->escape_str( $search['value'] );
			
			$db_like[ $this->db->escape_str("a.NoBukti") ] = $keywords;
			$db_like[ $this->db->escape_str("b.NRM") ] = $keywords;
			$db_like[ $this->db->escape_str("b.NoReg") ] = $keywords;
			$db_like[ $this->db->escape_str("b.NamaPasien_Reg") ] = $keywords;			
        }
		
		// get total records
		$this->db->from( $db_from )
				;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_custome_where) ){ $this->db->where( $db_custome_where ); }
		$records_total = $this->db->count_all_results();
		
		// get total filtered
		$this->db
			->from( $db_from )
			->join( "mPasien b", "a.NRM = b.NRM", "LEFT OUTER" )
			->join( "mCustomer c", "a.KodePerusahaan = c.Kode_Customer", "LEFT OUTER" )
			->join( "SIMmJenisKerjasama d", "a.JenisKerjasamaID = d.JenisKerjasamaID", "LEFT OUTER" )
			;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }		
		if( !empty($db_custome_where) ){ $this->db->where( $db_custome_where ); }
		$records_filtered = $this->db->count_all_results();
		
		// get result filtered
		$db_select = <<<EOSQL
			a.NoReg,
			a.TglReg,
			a.JamReg,
			a.NRM,
			b.JenisKelamin,
			b.NamaPasien,
			b.Alamat,
			d.JenisKerjasama,
			c.Nama_Customer,
			a.NoAnggota AS NoKartu,
			a.PxKeluar_Dirujuk,
			a.PxKeluar_PlgPaksa,
			a.PxKeluar_Pulang,
			a.PxMeninggal,
			a.Status,
			e.SectionName
EOSQL;

		$this->db
			->select( $db_select )
			->from( $db_from )
			->join( "mPasien b", "a.NRM = b.NRM", "LEFT OUTER" )
			->join( "mCustomer c", "a.KodePerusahaan = c.Kode_Customer", "LEFT OUTER" )
			->join( "SIMmJenisKerjasama d", "a.JenisKerjasamaID = d.JenisKerjasamaID", "LEFT OUTER" )
			->join( "SIMmSection e", "a.SectionID = e.SectionID", "LEFT OUTER" )
			;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }		
		if( !empty($db_custome_where) ){ $this->db->where( $db_custome_where ); }
		
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
			$date_reg = DateTime::createFromFormat("Y-m-d H:i:s.u", $row->JamReg);
			
			$row->JamReg = $date_reg->format('Y-m-d H:i:s');
      
            $output['data'][] = $row;
        }
		
		$this->template
			->build_json( $output );
    }
}



