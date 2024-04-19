<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Item_grading extends ADMIN_Controller
{
	protected $nameroutes = 'inventory/references/item_grading';
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('inventory');
		
		$this->data['nameroutes'] = $this->nameroutes; 
		
		$this->load->model('item_grading_model');
		$this->load->model('item_group_model');
		$this->load->model('item_typegroup_model');
		$this->load->model('item_grading_group_model');
		$this->load->model('class_model');
		$this->load->model('patient_type_model');
		$this->load->model('service_type_model');
	
		$this->load->language('inventory');		
		$this->load->helper('inventory');
	}
	
	public function index()
	{
		$this->data['datatables'] = TRUE;
		
		$this->template
			->title(lang('heading:item_grading'), lang('heading:references'))
			->set_breadcrumb(lang('heading:references') )
			->set_breadcrumb(lang('heading:item_grading_list'), site_url($this->nameroutes))
			->build("references/item_grading/datatable", $this->data);
	}
	
	public function create()
	{
		$item = (object) [
			'TipePelayanan' => 'STANDARD',
			'JenisKerjasamaID' => 3,
			'KelasID' => 'xx',
			'KTP' => 0,
			'StartHarga' => 0,
			'EndHarga' => 0,
			'ProsentaseUp' => 0,
			'ProsentaseDiscount' => 0,
			'PPN' => 0,
			'Golongan' => 'ALL',
			'KelompokJenis' => 'ORAL',
			'TglBerlaku' => date('Y-m-d')
		];
		
		if( $this->input->post() ) 
		{
			$post_item_grading = (object) array_merge( (array) $item, $this->input->post("f") );
			
			// Cek Duplicate Constraine data
			$check = [
					'TipePelayanan' => $post_item_grading->TipePelayanan,
					'JenisKerjasamaID' => $post_item_grading->JenisKerjasamaID,
					'KelasID' => $post_item_grading->KelasID,
					'KTP' => $post_item_grading->KTP,
					'StartHarga' => $post_item_grading->StartHarga,
					'KelompokJenis' => $post_item_grading->KelompokJenis,
				];
			
			if( $this->item_grading_model->count_all( $check ) ){
				response_json( ["status" => 'error', "message" => lang('message:duplicate_format_grading_data'), "code" => 500] );
			}
			
			$this->load->library('form_validation');
			$this->form_validation->set_rules($this->item_grading_model->rules['insert']);
			$this->form_validation->set_data((array) $post_item_grading);
			if( $this->form_validation->run() )
			{							
				$this->db->trans_begin();
											
					$this->item_grading_model->create( $post_item_grading );
										
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
		$this->data['dropdown_service_type'] = $this->service_type_model->dropdown_data();
		$this->data['dropdown_patient_type'] = $this->patient_type_model->dropdown_data();
		$this->data['dropdown_class'] = $this->class_model->dropdown_data();
		$this->data['dropdown_item_group'] = $this->item_group_model->dropdown_data();
		// $this->data['dropdown_item_typegroup'] = $this->item_typegroup_model->dropdown_data();
		$this->data['dropdown_item_typegroup'] = $this->item_grading_group_model->dropdown_data();
		$this->data['form_action'] = current_url();
	
		$this->template
			->title(lang('heading:item_grading'),lang('heading:references'))
			->set_breadcrumb(lang('heading:item_grading_list'), site_url($this->nameroutes))
			->set_breadcrumb(lang('heading:item_grading'))
			->build("references/item_grading/form", $this->data);
	}
	
	public function update()
	{
		$item_grading = (object) $this->input->get();
		$get_by = [
			'TipePelayanan' => $item_grading->TipePelayanan,
			'JenisKerjasamaID' => $item_grading->JenisKerjasamaID,
			'KelasID' => $item_grading->KelasID,
			'KTP' => $item_grading->KTP,
			'StartHarga' => $item_grading->StartHarga,
			'KelompokJenis' => $item_grading->KelompokJenis,
		];
		$this->data['item'] = $item = $this->item_grading_model->get_by($get_by);
		
		if( $this->input->post() ) 
		{			
			$post_item_grading = $this->input->post("f");
			
			$this->load->library('form_validation');
			$this->form_validation->set_rules($this->item_grading_model->rules['update']);
			$this->form_validation->set_data($post_item_grading);
			if( $this->form_validation->run() )
			{								
				$this->db->trans_begin();
				
					$this->item_grading_model->update_by( $post_item_grading, $get_by );	
										
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
		
		$this->data['dropdown_service_type'] = $this->service_type_model->dropdown_data();
		$this->data['dropdown_patient_type'] = $this->patient_type_model->dropdown_data();
		$this->data['dropdown_class'] = $this->class_model->dropdown_data();
		$this->data['dropdown_item_group'] = $this->item_group_model->dropdown_data();
		// $this->data['dropdown_item_typegroup'] = $this->item_typegroup_model->dropdown_data();	
		$this->data['dropdown_item_typegroup'] = $this->item_grading_group_model->dropdown_data();			
		
		$this->data['is_edit'] = TRUE;		
		$this->data['form_action'] = current_url() .'?'. http_build_query( $get_by );

		$this->template
			->title(lang('heading:item_grading'),lang('heading:references'))
			->set_breadcrumb(lang('heading:item_grading_list'), site_url($this->nameroutes))
			->set_breadcrumb(lang('heading:item_grading'))
			->build("references/item_grading/form", $this->data);
	}
	
	public function delete()
	{
		$item_grading = (object) $this->input->get();
		$get_by = [
			'TipePelayanan' => $item_grading->TipePelayanan,
			'JenisKerjasamaID' => $item_grading->JenisKerjasamaID,
			'KelasID' => $item_grading->KelasID,
			'KTP' => $item_grading->KTP,
			'StartHarga' => $item_grading->StartHarga,
			'KelompokJenis' => $item_grading->KelompokJenis,
		];
		$this->data['item'] = $item = $this->item_grading_model->get_by($get_by);
		
		if ($item  && (1 == $this->input->post('confirm') ) )
		{ 

			$this->db->trans_begin();
				
				$this->item_grading_model->delete_by( $get_by );

			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
				response_json(["status" => 'error', 'message' => lang('global:delete_failed'), 'success' => FALSE]);
			} else
			{
				$this->db->trans_commit();
				response_json(["status" => 'success', 'message' => lang('global:delete_successfully'), 'success' => TRUE]);
			}
		} 
		
		$this->data['form_action'] = $form_action = current_url() .'?'. http_build_query( $get_by );
		$this->load->view('references/item_grading/modal/delete', $this->data);
	}
	
	public function lookup_data( $view, $is_ajax_request=false )
	{	
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view("references/item_grading/lookup/{$view}");
		}
	}
	
	public function dropdown_html( $parent_id=0 )
	{
		if( $this->input->is_ajax_request() )
		{
			$parent_id = ($parent_id == 0) ? $this->input->get_post('parent_id') : $parent_id;
			
			$collection = array();
			$collection = $this->item_grading_model->dropdown_html( ['GroupJasa' => $parent_id] );
		
			response_json( $collection );
		}
	}
		
	public function lookup_collection ()
	{
		$this->datatable_collection( 1 );
	}
	
	public function datatable_collection( $Status = 1 )
	{
		$start = $this->input->get_post('start', true);
        $length = $this->input->get_post('length', true);
        $order = $this->input->get_post('order', true);
        $columns = $this->input->get_post('columns', true);
        $search = $this->input->get_post('search', true);
        $draw = $this->input->get_post('draw', true);
		
		$db_from = "{$this->item_grading_model->table} a";
		$db_where = array();
		$db_like = array();
		
		//prepare defautl flter

		// preparing default
		if( isset($search['value']) && ! empty($search['value']) )
        {
            $keywords = $this->db->escape_str( $search['value'] );
			
			$db_like[ $this->db->escape_str("a.{$this->item_grading_model->index_key}") ] = $keywords;
			$db_like[ $this->db->escape_str("a.TipePelayanan") ] = $keywords;
			$db_like[ $this->db->escape_str("a.StartHarga") ] = $keywords;
			$db_like[ $this->db->escape_str("a.EndHarga") ] = $keywords;
			$db_like[ $this->db->escape_str("a.ProsentaseUp") ] = $keywords;
			$db_like[ $this->db->escape_str("b.JenisKerjasama") ] = $keywords;
			$db_like[ $this->db->escape_str("c.NamaKelas") ] = $keywords;
			$db_like[ $this->db->escape_str("a.KelompokJenis") ] = $keywords;
			$db_like[ $this->db->escape_str("a.Golongan") ] = $keywords;
        }
		
		// get total records
		$this->db->from( $db_from );
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		$records_total = $this->db->count_all_results();
		
		// get total filtered
		$this->db
			->from( $db_from )
			->join("{$this->patient_type_model->table} b", "a.JenisKerjasamaID = b.JenisKerjasamaID", "INNER")
			->join("{$this->class_model->table} c", "a.KelasID = c.KelasID", "INNER");
			
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }		
		$records_filtered = $this->db->count_all_results();
		
		// get result filtered
		$db_select = <<<EOSQL
			a.*,
			b.JenisKerjasama,
			c.NamaKelas
EOSQL;

		$this->db
			->select( $db_select )
			->from( $db_from )
			->join("{$this->patient_type_model->table} b", "a.JenisKerjasamaID = b.JenisKerjasamaID", "INNER")
			->join("{$this->class_model->table} c", "a.KelasID = c.KelasID", "INNER")
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

