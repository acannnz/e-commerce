<?php 
if ( ! defined('BASEPATH') ){ exit('No direct script access allowed'); }

class Reports extends ADMIN_Controller 
{
	protected $nameroutes = 'reports';
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('welcome');
		
		
		$this->load->helper("reports");
		
	}
	
	public function medical_records()
	{		
		$data = [
			'nameroutes' => $this->nameroutes,
			'form' => TRUE,
			'datatables' => TRUE,
			'datatables_export' => TRUE,
			'datepicker' => TRUE,
			'navigation_minimized' => TRUE,
		];
		
		$this->template
			->build( 'reports/reports/medical_records/datatable', $data );
	}
	
	public function medical_records_collection()
    {
        $start = $this->input->get_post('start', true);
        $length = $this->input->get_post('length', true);
        $order = $this->input->get_post('order', true);
        $columns = $this->input->get_post('columns', true);
        $search = $this->input->get_post('search', true);
        $draw = $this->input->get_post('draw', true);
		
		$db_from = "SIMtrRJ a";
		$db_where = [];
		$db_like = [];
		
		$db_where['a.Batal'] = 0;
		$db_where['a.Tanggal >='] = $this->input->get_post('date_from', true);
		$db_where['a.Tanggal <='] = $this->input->get_post('date_till', true);
						
		// get result filtered
		$db_select = <<<EOSQL
			a.Tanggal, 
			a.RegNo,
			b.JenisKerjasama,
			c.SectionName,
			d.Nama_Supplier AS NamaDokter,
			a.NRM,
			e.NamaPasien,
			e.JenisKelamin,
			e.TglLahir,
			DATEDIFF(hour, e.TglLahir,GETDATE())/8766 AS Umur,
			e.Alamat,
			e.RiwayatAlergi,
			f.Subjective,
			f.Objective,
			f.Assessment,
			f.Plan
			
EOSQL;
		$this->db
			->select( $db_select )
			->from( $db_from )
			->join( "SIMmJenisKerjasama b", "a.JenisKerjasamaID = b.JenisKerjasamaID", "INNER" )
			->join( "SIMmSection c", "a.SectionID = c.SectionID", "INNER" )
			->join( "mSupplier d", "a.DokterID = d.Kode_Supplier", "INNER" )
			->join( "mPasien e", "a.NRM = e.NRM", "INNER" )
			->join( "SIMtrEMRSoapNotes f", "a.NoBukti = f.NoPemeriksaan", "INNER" )
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
				'recordsTotal' => count($result),
				'recordsFiltered' => count($result),
				'data' => array()
			);
        
        foreach($result as $row)
        {
            $output['data'][] = $row;
        }
		
		$this->template
			->build_json( $output );
    }
	
	public function get_monthly_section_visit()
	{
		if($this->input->is_ajax_request()):
			$type = $this->input->get('type');
			$date = $this->input->get('date');
			
			switch($type):
				case 'month':
					$response = reports_helper::get_monthly_section_visit($date);
					break;
				case 'year':
					$response = reports_helper::get_yearly_section_visit($date);
					break;
			endswitch;			

			response_json($response);
		endif;
	}
	
	public function get_monthly_type_visit()
	{
		if($this->input->is_ajax_request()):
			$type = $this->input->get('type');
			$date = $this->input->get('date');
			
			switch($type):
				case 'month':
					$response = reports_helper::get_monthly_type_visit($date);
					break;
				case 'year':
					$response = reports_helper::get_yearly_type_visit($date);
					break;
			endswitch;			

			response_json($response);
		endif;
	}
}






