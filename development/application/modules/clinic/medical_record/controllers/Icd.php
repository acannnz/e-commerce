<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Icd extends ADMIN_Controller
{
	protected $nameroutes = 'medical_record/icd';
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('medical_record');
		
		$this->load->model([
			"icd_model",
		]);
		$this->load->language('common');	

		$this->data['nameroutes'] = $this->nameroutes;

		$this->page = lang("icd:breadcrumb");
		$this->template->title( $this->page . ' - ' . $this->config->item('company_name') );
	}
	
	public function index()
	{
		$data = array(
				'page' => $this->page,
				'nameroutes' => $this->nameroutes,
				'datatables' => TRUE,
				'form' => TRUE,
				"fileinput" => TRUE,
				'navigation_minimized' => TRUE,
			);
		
		$this->template
			->set( "heading", $this->page )
			->set_breadcrumb( lang("icd:widget_heading"))
			->build('medical_record/icd/datatable', (isset($data) ? $data : NULL));
	}
	
	public function create()
	{
		$item = (object) [
			'KodeICD' => NULL,
			'Descriptions' => NULL,
		];
		
		if( $this->input->post() ) 
		{
			$post_icd = array_merge( (array) $item, $this->input->post("f") );
			$post_icd['ICDName'] = $post_icd['Descriptions'];
			
			$this->load->library('form_validation');
			$this->form_validation->set_rules($this->icd_model->rules['insert']);
			$this->form_validation->set_data($post_icd);
			if( $this->form_validation->run() )
			{							
				$this->db->trans_begin();
							
					$this->icd_model->create( $post_icd );							
										
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
							"status" => 'success',
							"message" => lang('global:created_successfully'),
							"code" => 200
						);
				}			
			} else
			{
				$response["message"] = $this->form_validation->get_all_error_string();
				$response["status"] = "error";
				$response["code"] = "500";
			}

			response_json( $response );
		}
		
		$this->data['item'] = $item;
		$this->data['is_edit'] = FALSE;		
		$this->data['form_action'] = current_url();
	
		$this->template
			->title(lang('icd:create_heading'),'')
			->set_breadcrumb(lang('icd:widget_heading'), site_url($this->nameroutes))
			->set_breadcrumb(lang('icd:create_heading'))
			->build("medical_record/icd/form", $this->data);
	}
	

	public function edit($id = 0)
	{
		$this->data['item'] = $item = $this->icd_model->get_one($id);
		
		if( $this->input->post() ) 
		{			
			$post_icd = $this->input->post("f");
			$post_icd['ICDName'] = $post_icd['Descriptions'];
			
			$this->load->library('form_validation');
			$this->form_validation->set_rules($this->icd_model->rules['update']);
			$this->form_validation->set_data($post_icd);
			if( $this->form_validation->run() )
			{								
				$this->db->trans_begin();
				
					$this->icd_model->update( $post_icd, $id );	
					
				if ($this->db->trans_status() === FALSE)
				{
					$this->db->trans_rollback();
					$response = array(
							"status" => 'error',
							"message" => lang('global:updated_failed'),
							"code" => 500
						);
				}
				else
				{
					$this->db->trans_commit();
					$response = array(
							"status" => 'success',
							"message" => lang('global:updated_successfully'),
							"code" => 200
						);
				}			
			} else
			{
				$response["message"] = $this->form_validation->get_all_error_string();
				$response["status"] = "error";
				$response["code"] = "500";
			}
			
			response_json( $response );
		}
				
		$this->data['is_edit'] = TRUE;		
		$this->data['form_action'] = current_url();

		$this->template
			->title(lang('icd:edit_heading'),'')
			->set_breadcrumb(lang('icd:widget_heading'), site_url($this->nameroutes))
			->set_breadcrumb(lang('icd:edit_heading'))
			->build("medical_record/icd/form", $this->data);
	}
	
	public function delete( $id=0 )
	{
		$id = (int) @$id;
		
		$item = $this->get_model()->as_array()->get( $id );
		if( ! $item ){ $item = array('id' => 0); }
		$this->load->library( 'my_object', $item, 'item' );
		
		if( $this->input->post() ) 
		{
			
			
			if( 0 == @$this->item->id )
			{
				make_flashdata(array(
						'response_status' => 'error',
						'message' => lang( 'global:get_failed' )
					));
			
				redirect( $this->input->post( 'r_url' ) );
			}
			
			if( $this->item->id == $this->input->post( 'confirm' ) )
			{
				// Inject spog delete method
				if( "TRUE" == $this->config->item( "enable_chart_spog" ) )
				{
					$this->load->model( "spog/spog_patient_m" );
					$this->spog_patient_m->delete(array("patient_id" => $this->item->id));
				} // end: Inject spog delete method
				
				$this->get_model()->where( $id )->delete();				
				
				$this->get_model()->delete_cache( 'common_patients.collection' );
				
				make_flashdata(array(
						'response_status' => 'success',
						'message' => lang('patients:deleted_successfully')
					));
			}
			
			redirect( $this->input->post( 'r_url' ) );
		}
		
		$this->load->view( 'patients/modal/delete', array('item' => $this->item) );
	}

	public function lookup_collection( $state=false )
	{
		$this->datatable_collection( $state );
	}
	
	public function datatable_collection( $state=false )
    {
		$start = $this->input->get_post('start', true);
        $length = $this->input->get_post('length', true);
        $order = $this->input->get_post('order', true);
        $columns = $this->input->get_post('columns', true);
        $search = $this->input->get_post('search', true);
        $draw = $this->input->get_post('draw', true);
		
		$db_from = "{$this->icd_model->table} a";
		$db_where = array();
		$db_like = array();
		
		//prepare defautl flter

		// preparing default
		if( isset($search['value']) && ! empty($search['value']) )
        {
            $keywords = $this->db->escape_str( $search['value'] );
			
			$db_like[ $this->db->escape_str("a.{$this->icd_model->index_key}") ] = $keywords;
			$db_like[ $this->db->escape_str("a.Descriptions") ] = $keywords;
        }
		
		// get total records
		$this->db->from( $db_from );
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		$records_total = $this->db->count_all_results();
		
		// get total filtered
		$this->db
			->from( $db_from )
			;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }		
		$records_filtered = $this->db->count_all_results();
		
		// get result filtered
		$db_select = <<<EOSQL
			a.*
EOSQL;

		$this->db
			->select( $db_select )
			->from( $db_from )
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
		
		response_json( $output );
    }
	
}


