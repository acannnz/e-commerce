<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Diagnosis extends Admin_Controller
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
				"collection" => $this->poly_m->get_icd( array("NoBukti" => @$item->NoBukti )),
				"form" => TRUE,
				'datatables' => TRUE,
				'nameroutes' => $this->nameroutes,
				"lookup_icd" => base_url("{$this->nameroutes}/diagnosis/lookup_icd")
			);

		$this->load->view( 'polies/form/diagnosis', $data );		
	}
	
	public function lookup_icd( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'polies/lookup/icd' );
		} 
	}
	
	public function lookup( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'icd/lookup/datatable' );
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