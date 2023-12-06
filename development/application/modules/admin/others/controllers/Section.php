<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Section extends ADMIN_Controller
{
	protected $nameroutes = 'others/section';
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('admin');
		
		$this->data['nameroutes'] = $this->nameroutes; 
		
		$this->load->language('others');		
		$this->load->helper('others');
		$this->load->model('section_model');
		$this->load->model('location_model');
		$this->load->model('section_group_model');
		$this->load->model('service_type_model');
		$this->load->model('business_model');
		$this->load->model('account_model');
		$this->load->model('customer_model');
	}
	
	public function index()
	{

		$this->template
			->title(lang('heading:section'), lang('heading:others'))
			->set_breadcrumb(lang('heading:others') )
			->set_breadcrumb(lang('heading:section_list'), site_url($this->nameroutes))
			->build("section/datatable", $this->data);
	}
	
	public function create()
	{
		$item = (object) [
			'SectionID' => others_helper::gen_section_number(),
			'SectionName' => NULL,
			'TipePelayanan' => 'NONE',
			'Pelayanan' => 'STANDARD',
			'PoliKlinik' => 'NONE'
		];
		
		if( $this->input->post() ) 
		{
			$post_section = array_merge( (array) $item, $this->input->post("f") );
			
			$this->load->library('form_validation');
			$this->form_validation->set_rules($this->section_model->rules['insert']);
			$this->form_validation->set_data($post_section);
			if( $this->form_validation->run() )
			{							
				$this->db->trans_begin();
							
					$id = $this->section_model->create( $post_section );	
					
					$data_location = [
						'Lokasi_ID' => $id,
						'Kode_Lokasi' => $post_section['SectionID'],
						'Nama_Lokasi' => $post_section['SectionName'], 
						'Status_Aktif' => $post_section['StatusAktif']
					];
					$this->location_model->create( $data_location );	
										
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
		
		// services = pelayanan
		$this->data['dropdown_services_type'] = $this->section_model->dropdown_static('TipePelayanan');
		$this->data['dropdown_section_group'] = $this->section_group_model->dropdown_data();
		$this->data['dropdown_polyclinic'] = $this->section_model->dropdown_static('PoliKlinik');
		$this->data['dropdown_business'] = $this->business_model->dropdown_data();
		$this->data['dropdown_services'] = $this->service_type_model->dropdown_data();
		
		$this->data['form_action'] = current_url();
		$this->data['lookup_account_in'] = base_url("{$this->nameroutes}/lookup_data/lookup_account_in");	
		$this->data['lookup_account_out'] = base_url("{$this->nameroutes}/lookup_data/lookup_account_out");	
		$this->data['lookup_account_drug_revenue'] = base_url("{$this->nameroutes}/lookup_data/lookup_account_drug_revenue");	
		$this->data['lookup_customer'] = base_url("{$this->nameroutes}/lookup_data/lookup_customer");	
		
		$this->template
			->title(lang('heading:section_create'),lang('heading:others'))
			->set_breadcrumb(lang('heading:section_list'), site_url($this->nameroutes))
			->set_breadcrumb(lang('heading:section_create'))
			->build("section/form", $this->data);
	}
	
	public function update($id = 0)
	{
		$this->data['item'] = $item = $this->section_model->get_one($id);
		
		if( $this->input->post() ) 
		{			
			$post_section = $this->input->post("f");
			
			$this->load->library('form_validation');
			$this->form_validation->set_rules($this->section_model->rules['update']);
			
			if(config_item('bpjs_bridging') == 'TRUE')
				$this->form_validation->set_rules('SectionIDBPJS', lang('label:code') .' BPJS', "is_edit_unique[{$this->section_model->table}.SectionIDBPJS.SectionID.{$id}]");			
			
			$this->form_validation->set_data($post_section);
			if( $this->form_validation->run() )
			{								
				$this->db->trans_begin();
				
					$this->section_model->update( $post_section, $id );	
					
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
		$this->data['dropdown_services_type'] = $this->section_model->dropdown_static('TipePelayanan');
		$this->data['dropdown_section_group'] = $this->section_group_model->dropdown_data();
		$this->data['dropdown_polyclinic'] = $this->section_model->dropdown_static('PoliKlinik');
		$this->data['dropdown_business'] = $this->business_model->dropdown_data();
		$this->data['dropdown_services'] = $this->service_type_model->dropdown_data();
		
		$this->data['account_in'] = $this->account_model->get_one( $item->MutasiMasukAkun_ID );
		$this->data['account_out'] = $this->account_model->get_one( $item->MutasiKeluarAkun_ID);
		$this->data['account_drug_revenue'] = $this->account_model->get_one( $item->PendapatanObatAkun_ID);
		$this->data['customer'] = $this->customer_model->get_one( $item->Customer_ID );
		
		$this->data['form_action'] = current_url();
		$this->data['lookup_account_in'] = base_url("{$this->nameroutes}/lookup_data/lookup_account_in");	
		$this->data['lookup_account_out'] = base_url("{$this->nameroutes}/lookup_data/lookup_account_out");	
		$this->data['lookup_account_drug_revenue'] = base_url("{$this->nameroutes}/lookup_data/lookup_account_drug_revenue");	
		$this->data['lookup_customer'] = base_url("{$this->nameroutes}/lookup_data/lookup_customer");	

		$this->template
			->title(lang('heading:section'),lang('heading:others'))
			->set_breadcrumb(lang('heading:section_list'), site_url($this->nameroutes))
			->set_breadcrumb(lang('heading:section_update'))
			->build("section/form", $this->data);
	}
	
	public function delete($id = 0)
	{
		$this->data['item'] = $item = $this->section_model->get_one($id);
		
		if ($item  && (1 == $this->input->post('confirm') ) )
		{ 

			$this->db->trans_begin();
				
				$this->location_model->delete( $item->Lokasi_ID );
				$this->section_model->delete( $id );

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
		
		$this->data['form_action'] = $form_action = current_url();
		$this->load->view('section/modal/delete', $this->data);
	}
	
	public function lookup_data( $view, $is_ajax_request=false )
	{	
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view("section/lookup/{$view}");
		}
	}
	
	public function dropdown_html( $parent_id=0 )
	{
		if( $this->input->is_ajax_request() )
		{
			$parent_id = ($parent_id == 0) ? $this->input->get_post('parent_id') : $parent_id;
			
			$collection = array();
			$collection = $this->section_model->dropdown_html( ['GroupJasa' => $parent_id] );
		
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
		
		$db_from = "{$this->section_model->table} a";
		$db_where = array();
		$db_like = array();
		
		//prepare defautl flter

		// preparing default
		if( isset($search['value']) && ! empty($search['value']) )
        {
            $keywords = $this->db->escape_str( $search['value'] );
			
			$db_like[ $this->db->escape_str("a.{$this->section_model->index_key}") ] = $keywords;
			$db_like[ $this->db->escape_str("a.SectionName") ] = $keywords;
			$db_like[ $this->db->escape_str("a.TipePelayanan") ] = $keywords;
			$db_like[ $this->db->escape_str("b.KelompokSection") ] = $keywords;
        }
		
		// get total records
		$this->db->from( $db_from );
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		$records_total = $this->db->count_all_results();
		
		// get total filtered
		$this->db
			->from( $db_from )
			->join("{$this->section_group_model->table} b", "a.KelompokSection = b.KelompokSectionID", 'LEFT OUTER' )
			;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }		
		$records_filtered = $this->db->count_all_results();
		
		// get result filtered
		$db_select = <<<EOSQL
			a.SectionID,
			a.SectionName,
			a.TipePelayanan,
			a.StatusAktif,
			b.KelompokSection
EOSQL;

		$this->db
			->select( $db_select )
			->from( $db_from )
			->join("{$this->section_group_model->table} b", "a.KelompokSection = b.KelompokSectionID", 'LEFT OUTER' )
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