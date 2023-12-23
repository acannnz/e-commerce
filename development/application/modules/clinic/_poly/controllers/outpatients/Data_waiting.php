<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Data_waiting extends Admin_Controller
{
	protected $_translation = 'poly';	
	protected $_model = 'poly_m';
	protected $nameroutes = 'poly/outpatients/data_waiting';
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('outpatient');
		
		$this->load->model( "patient_model" );
		$this->load->model( "patient_type_model" );
		$this->load->model( "supplier_model" );
	}
	
	public function index()
	{
		$data = [
			'option_doctor' => poly_helper::option_doctor(),
			'option_section' => $this->section_model->to_list_data(['TipePelayanan' => 'RJ','StatusAktif' => 1]),
			'medics' => $this->session->userdata('outpatient'),
			'cek_data' =>  base_url("poly/outpatients/data_waiting/cek_data")
		];
		$this->load->view('outpatient/datatable/waiting', $data);
	}

	
	//pengecekan resep belum realisasi
	public function cek_data()
	{
		if ($this->input->is_ajax_request()) {
			$this->db->trans_begin();
			$params = (object) $this->input->post();
			$queue_left = $this->db
				->select("COUNT(NoReg) as queue_left")
				->where([
					"RJ" => 1,
					"SectionID" => $params->SectionID,
					"SudahPeriksa" => 0,
					"Batal" => 0,
					"Tanggal >=" => $params->date_from,
					"Tanggal <=" => $params->date_till
				])
				->get('SIMtrDataRegPasien')->row();

			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
			} else {
				// $this->db->trans_rollback();
				$this->db->trans_commit();

				$response = array(
					"status" => "success",
					"message" => "",
					"code" => 200,
					"queue_left" => empty($queue_left) ? 0 : @$queue_left->queue_left,
				);

				$response['queue_left'] = intval($response['queue_left']) < 0 ? 0 : $response['queue_left'];

				$response['number_spell'] = explode('#', str_replace(' ', '', $this->number_spell($response['queue_left'])));
				unset($response['number_spell'][count($response['number_spell']) - 1]);
			}

			response_json($response);
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
		
		//$db_from = "{$this->poly_m->table} a";
		$db_from = "SIMtrDataRegPasien a";
		$this->load->model("registration_model");
		$db_where = array();
		$db_like = array();
		
		$db_where['a.RJ'] = 1;
		$db_where['a.SudahPeriksa'] = 0;
		$db_where['a.Batal'] = 0;
		
		if ($this->input->post("date_from"))
		{
			$db_where['a.Tanggal >='] = $this->input->post("date_from");
		}

		if ($this->input->post("date_till"))
		{
			$db_where['a.Tanggal <='] = $this->input->post("date_till");
		}

		if( $this->input->post("NRM") && strpos($this->input->post("NRM"), "_") === FALSE ){
			$db_like['b.NRM'] = $this->input->post("NRM");
		}

		if( $this->input->post("Nama") ){
			$db_like['b.NamaPasien_Reg'] = $this->input->post("Nama");
		}

		if( $this->input->post("DokterID") ){
			$db_where['a.DokterID'] = $this->input->post("DokterID");
		}
		
		if( $this->input->post("SectionID") ){
			$db_where['a.SectionID'] = $this->input->post("SectionID");
		}
				
		// preparing default
		if( isset($search['value']) && ! empty($search['value']) )
        {
            $keywords = $this->db->escape_str( $search['value'] );
						
			$db_like[ $this->db->escape_str("a.NoReg") ] = $keywords;
			$db_like[ $this->db->escape_str("b.NRM") ] = $keywords;
			$db_like[ $this->db->escape_str("a.Jam") ] = $keywords;
			$db_like[ $this->db->escape_str("c.NamaPasien") ] = $keywords;
			$db_like[ $this->db->escape_str("d.JenisKerjasama") ] = $keywords;

        }
		
		// get total records
		$this->db->from( $db_from )
		;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		$records_total = $this->db->count_all_results();
		
		// get total filtered
		$this->db
			->from( $db_from )
			->join( "{$this->registration_model->table} b", "a.NoReg = b.NoReg", "LEFT OUTER" )
			->join( "{$this->patient_model->table} c", "b.NRM = c.NRM", "LEFT OUTER" )
			->join( "{$this->patient_type_model->table} d", "b.JenisKerjasamaID = d.JenisKerjasamaID", "LEFT OUTER" )
			->join( "{$this->supplier_model->table} e", "a.DokterID = e.Kode_Supplier", "LEFT OUTER" )
			;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }		
		$records_filtered = $this->db->count_all_results();
		
		// get result filtered
		$db_select = <<<EOSQL
			a.NoAntri,
			a.NoReg,
			a.SectionID,
			b.NRM,
			b.Waktu,
			a.Jam,
			c.NamaPasien,
			c.JenisKelamin,
			d.JenisKerjasama,
			e.Nama_Supplier			
EOSQL;

		$this->db
			->select( $db_select )
			->from( $db_from )
			->join( "{$this->registration_model->table} b", "a.NoReg = b.NoReg", "LEFT OUTER" )
			->join( "{$this->patient_model->table} c", "b.NRM = c.NRM", "LEFT OUTER" )
			->join( "{$this->patient_type_model->table} d", "b.JenisKerjasamaID = d.JenisKerjasamaID", "LEFT OUTER" )
			->join( "{$this->supplier_model->table} e", "a.DokterID = e.Kode_Supplier", "LEFT OUTER" )
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
		
		print_r( json_encode( @$output, JSON_NUMERIC_CHECK ) );
		exit(0);
    }

	// generate terbilang angka, digunakan untuk memanggil antrian
	public function number_spell($nilai = 0)
	{
		$nilai = abs($nilai);
		$huruf = array("", "satu#", "dua#", "tiga#", "empat#", "lima#", "enam#", "tujuh#", "delapan#", "sembilan#", "sepuluh#", "sebelas#");
		$temp = "";
		if ($nilai < 12) {
			$temp = " " . $huruf[$nilai];
		} else if ($nilai < 20) {
			$temp = $this->number_spell($nilai - 10) . " belas#";
		} else if ($nilai < 100) {
			$temp = $this->number_spell($nilai / 10) . " puluh#" . $this->number_spell($nilai % 10);
		} else if ($nilai < 200) {
			$temp = " seratus#" . $this->number_spell($nilai - 100);
		} else if ($nilai < 1000) {
			$temp = $this->number_spell($nilai / 100) . " ratus#" . $this->number_spell($nilai % 100);
		} else if ($nilai < 2000) {
			$temp = " seribu#" . $this->number_spell($nilai - 1000);
		} else if ($nilai < 1000000) {
			$temp = $this->number_spell($nilai / 1000) . " ribu#" . $this->number_spell($nilai % 1000);
		} else if ($nilai < 1000000000) {
			$temp = $this->number_spell($nilai / 1000000) . " juta#" . $this->number_spell($nilai % 1000000);
		} else if ($nilai < 1000000000000) {
			$temp = $this->number_spell($nilai / 1000000000) . " milyar#" . $this->number_spell(fmod($nilai, 1000000000));
		} else if ($nilai < 1000000000000000) {
			$temp = $this->number_spell($nilai / 1000000000000) . " trilyun#" . $this->number_spell(fmod($nilai, 1000000000000));
		}
		return $temp;
	}
}



