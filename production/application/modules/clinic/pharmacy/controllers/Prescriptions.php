<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Prescriptions extends Admin_Controller
{
	protected $_translation = 'pharmacy';	
	protected $_model = 'pharmacy_m';
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('pharmacy');
		
		$this->load->model( "common/patient_m" );
		$this->load->model( "common/patient_type_m" );
		$this->load->model( "common/zone_m" );
		$this->load->model( "common/nationality_m" );
		$this->load->model( "common/customer_m" );
		$this->load->model( "common/supplier_m" );
		$this->load->model( "common/supplier_specialist_m" );
		$this->load->model( "common/section_m" );
		$this->load->model( "common/time_m" );
		
		$this->load->helper( "pharmacy" );
		$this->load->helper( "common/patient" );
		$this->load->helper( "common/zone" );

	}

	public function lookup_supplier( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'prescriptions/lookup/suppliers' );
		} 
	}
		
	public function lookup( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'prescriptions/lookup/datatable' );
		} else
		{
			$data = array(
					'page' => $this->page,
					'datatables' => TRUE,
					'form' => TRUE,
				);
			
			$this->template
				->set( "heading", "Lookup Box" )
				->set_breadcrumb( lang("common:page"), base_url("pharmacys") )
				->set_breadcrumb( "Lookup Box" )
				->build('pharmacies/lookup', (isset($data) ? $data : NULL));
		}
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
		
		$db_from = "SIMtrResep a";
		$this->load->model("registration_m");
		$db_where = array();
		$db_like = array();
		
		if ($this->input->post("Realisasi"))
		{
			$db_where['a.Realisasi'] = $this->input->post("Realisasi");
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
						
			$db_like[ $this->db->escape_str("a.NoResep") ] = $keywords;
			$db_like[ $this->db->escape_str("a.NoReg") ] = $keywords;
			$db_like[ $this->db->escape_str("b.NRM") ] = $keywords;
			$db_like[ $this->db->escape_str("b.NamaPasien") ] = $keywords;
			$db_like[ $this->db->escape_str("d.JenisKerjasama") ] = $keywords;
			$db_like[ $this->db->escape_str("e.Nama_Supplier") ] = $keywords;

        }
		
		// get total records
		$this->db->from( $db_from )
			->join( "{$this->registration_m->table} b", "a.NoRegistrasi = b.NoReg", "LEFT OUTER" )
			->join( "{$this->patient_m->table} c", "b.NRM = c.NRM", "LEFT OUTER" )
			->join( "{$this->patient_type_m->table} d", "b.JenisKerjasamaID = d.JenisKerjasamaID", "LEFT OUTER" )
			->join( "{$this->supplier_m->table} e", "a.DokterID = e.Kode_Supplier", "LEFT OUTER" )
			->join( "{$this->section_m->table} f", "a.SectionID = f.SectionID", "LEFT OUTER" )
			->join( "{$this->customer_m->table} g", "a.CompanyID = g.Kode_Customer", "LEFT OUTER" )
		;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		$records_total = $this->db->count_all_results();
		
		// get total filtered
		$this->db
			->from( $db_from )
			->join( "{$this->registration_m->table} b", "a.NoRegistrasi = b.NoReg", "LEFT OUTER" )
			->join( "{$this->patient_m->table} c", "b.NRM = c.NRM", "LEFT OUTER" )
			->join( "{$this->patient_type_m->table} d", "b.JenisKerjasamaID = d.JenisKerjasamaID", "LEFT OUTER" )
			->join( "{$this->supplier_m->table} e", "a.DokterID = e.Kode_Supplier", "LEFT OUTER" )
			->join( "{$this->section_m->table} f", "a.SectionID = f.SectionID", "LEFT OUTER" )
			->join( "{$this->customer_m->table} g", "a.CompanyID = g.Kode_Customer", "LEFT OUTER" )
			;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }		
		$records_filtered = $this->db->count_all_results();
		
		// get result filtered
		$db_select = <<<EOSQL
			a.NoResep,
			a.NoRegistrasi,
			a.Tanggal,
			a.Jam,
			b.NRM,
			c.NamaPasien,
			c.JenisKelamin,
			c.Alamat,
			c.TglLahir,
			b.UmurThn,
			b.UmurBln,
			a.JenisKerjasamaID,
			d.JenisKerjasama,
			a.DokterID,
			e.Nama_Supplier,
			a.SectionID,
			f.SectionName,
			a.CompanyID,
			g.Nama_Customer,
			a.NoKartu,
			a.KTP		
			
EOSQL;

		$this->db
			->select( $db_select )
			->from( $db_from )
			->join( "{$this->registration_m->table} b", "a.NoRegistrasi = b.NoReg", "LEFT OUTER" )
			->join( "{$this->patient_m->table} c", "b.NRM = c.NRM", "LEFT OUTER" )
			->join( "{$this->patient_type_m->table} d", "b.JenisKerjasamaID = d.JenisKerjasamaID", "LEFT OUTER" )
			->join( "{$this->supplier_m->table} e", "a.DokterID = e.Kode_Supplier", "LEFT OUTER" )
			->join( "{$this->section_m->table} f", "a.SectionID = f.SectionID", "LEFT OUTER" )
			->join( "{$this->customer_m->table} g", "a.CompanyID = g.Kode_Customer", "LEFT OUTER" )
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
		
		$is_billing = $this->input->post("is_billing");
		
        foreach($result as $row)
        {	
			if ( $is_billing )		
			{
				$row->resep_detail = $this->pharmacy_m->get_prescription_detail( $row->NoResep, $row );
			}
			
            $output['data'][] = $row;
        }
		
		$this->template
			->build_json( $output );
    }
}