<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class File extends Admin_Controller
{
	protected $_translation = 'poly';	
	protected $_model = 'file_m';
	protected $nameroutes = 'poly/outpatients/file';
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('outpatient');
		
		$this->load->model("file_m");
		$this->load->model("poly_m");
		
		$this->load->helper("poly");	
	}
	
	public function index( $NoBukti, $SectionID, $NRM)
	{
		$data = array(
				'NoBukti' => $NoBukti,
				'collection' => $this->file_m->get_file_data( ["NoBukti" => $NoBukti, "a.SectionID" => $SectionID] ),
				'nameroutes' => $this->nameroutes,
				'create_file' => base_url("{$this->nameroutes}/file_create/{$NoBukti}/{$SectionID}/{$NRM}"),
				'delete_file' => base_url("{$this->nameroutes}/file_delete"),
				'view_file' => base_url("{$this->nameroutes}/file_view"),
			);

		$this->load->view( 'outpatient/form/file', $data );		
	}

	public function file_create($NoBukti, $SectionID, $NRM)
	{
		$item = [
			'NoBukti' => $NoBukti,
			'SectionID' => $SectionID,
			'NRM' => $NRM,
			'NamaFile' => NULL,
			'UserID_Create' => $this->simple_login->get_user()->User_ID,
			'TglCreate' => date('Y-m-d H:i:s'),
			'Gambar' => NULL,
		];
		
		if( $this->input->post() ) 
		{
			$item = array_merge((array) $item, (array) $this->input->post());
			
			$this->load->library( 'form_validation' );
			$this->form_validation->set_rules( $this->get_model()->rules['insert'] );
			$this->form_validation->set_data($item);

			if( $this->form_validation->run() )
			{
				$this->db->trans_begin();
				
				$id = $this->file_m->create($item );	

				if ( $this->db->trans_status() === FALSE )
				{
					$this->db->trans_rollback();
					$response = [
						"status" => 'error',
						"message" => lang('global:created_failed'),
						"code" => 500
					];
				}
				else
				{
					$this->db->trans_commit();
					$response = [
						"status" => 'success',
						"message" => lang('global:created_successfully'),
						"code" => 200
					];
				}				

			} else
			{
				$response = [
					'status' => 'error',
					'message' => $this->form_validation->get_all_error_string()
				];
			}
			
			response_json($response);
		}
		

		if( $this->input->is_ajax_request() )
		{
			$data = array(
				'item' => $item,
				'nameroutes' => $this->nameroutes,
				"typeahead" => TRUE,
			);

			$this->load->view( 'outpatient/file/form', $data );
		} 
	}

	public function file_delete()
	{
		if( $this->input->post() ) 
		{
			$ID = $this->input->post("ID");
			
			$this->load->library( 'form_validation' );
			
			if( !$this->form_validation->run() )
			{
				$this->db->trans_begin();

					$this->db->delete("SIMtrFIle", ["ID" => $ID] );				

				if ($this->db->trans_status() === FALSE )
				{
					$this->db->trans_rollback();
					$response = [
						"status" => 'error',
						"message" => lang('global:deleted_failed'),
						"code" => 500
					];
				}
				else
				{
					$this->db->trans_commit();
					$response = [
						"status" => 'success',
						"message" => lang('global:deleted_successfully'),
						"code" => 200
					];
				}		

			} else
			{
				$response = [
					'status' => 'error',
					'message' => $this->form_validation->get_all_error_string()
				];
			}
			
			response_json($response);
		}

		if( $this->input->is_ajax_request() )
		{
			$data = [
				// 'item' => (object) $item,
			];
			
			$this->load->view( 'outpatient/file/form', $data );
		} 
	}			

	public function file_view( $ID )
	{
			
		$item = $this->file_m->get_one($ID );	

		$data = array(
				'item' => $item,
			);
		
		$this->load->view( 'outpatient/file/form_view', $data );
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
		$db_where['NoBukti'] = $this->input->post("NoBukti");
		// print_r($db_where);exit;
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
		$this->db->from( "SIMtrFile" );
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		$records_total = $this->db->count_all_results();
		
		// get total filtered
		$this->db->from( "SIMtrFile" );
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }		
		$records_filtered = $this->db->count_all_results();
		
		// get result filtered
		$this->db->from( "SIMtrFile" );
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
}