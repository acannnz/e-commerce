<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Service extends ADMIN_Controller
{
	protected $nameroutes = 'service';
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('admin');
		
		$this->data['nameroutes'] = $this->nameroutes; 
		
		$this->load->language('service');		
		$this->load->helper('service');
		$this->load->model('service_model');
		$this->load->model('service_category_model');
		$this->load->model('service_group_model');
		$this->load->model('plafon_service_category_model');
		$this->load->model('account_model');
		$this->load->model('devices_model');
	}
	
	public function index()
	{
		$this->template
			->title(lang('heading:service_list'), lang('heading:service'))
			->set_breadcrumb(lang('heading:service_list'), site_url($this->nameroutes))
			->build("service/datatable", $this->data);
	}
	
	public function create()
	{
	
		$item = (object) [
			'JasaID' => service_helper::gen_service_code(),
			'JasaIDBPJS' => NULL,
			'JasaName' => NULL,
			'KategoriJasaID' => NULL,
			'GroupJasaID' => NULL,
			'Aktif' => 1,
			'CostRSPersen' => 0,
			'CostRSRupiah' => 0,
		];
		
		if( $this->input->post() ) 
		{
			$post_service = (object) array_merge( (array) $item, $this->input->post("service") );
			$post_service_price = (array)$this->input->post("service_price");
			$post_service_bhp = (array)$this->input->post("service_bhp");
			$post_service_section = (array)$this->input->post("service_section");
			$post_service_test = (array)$this->input->post("service_test");

			$this->load->library('form_validation');
			$this->form_validation->set_rules($this->service_model->rules['insert']);				
				
			$this->form_validation->set_data((array)$post_service);
			if( $this->form_validation->run() )
			{							
				$response  = service_helper::create_service( $post_service, $post_service_price, $post_service_bhp, $post_service_section, $post_service_test);
			} else
			{
				$response["message"] = $this->form_validation->get_all_error_string();
				$response["status"] = "error";
				$response["code"] = "500";
			}

			response_json( $response );
		}
		
		$this->data['item'] = $item;
		$this->data['dropdown_group'] = $this->service_group_model->dropdown_data();
		$this->data['dropdown_plafon_cateogry'] = $this->plafon_service_category_model->dropdown_data(["Tipe" => 'Per Satuan']);
		$this->data['dropdown_posting_group'] = $this->service_model->dropdown_static('KelompokPostingan');
		$this->data['dropdown_insentif_source'] = $this->service_model->dropdown_static('ModelInsentif');
		$this->data['dropdown_polyclinic'] = $this->service_model->dropdown_static('PoliKlinik');
		$this->data['dropdown_devices'] = $this->devices_model->dropdown_data();
		$this->data['form_action'] = current_url();
		
		$this->template
			->title(lang('heading:service_create'), lang('heading:service'))
			->set_breadcrumb(lang('heading:service_list'), site_url($this->nameroutes))
			->set_breadcrumb(lang('heading:service_create'))
			->build("service/form", $this->data);
	}
	
	public function update($id = 0)
	{
		$this->data['item'] = $item = $this->service_model->get_one($id);
		
		if( $this->input->post() ) 
		{			
			$post_service = (object) $this->input->post("service");
			$post_service_price = (array)$this->input->post("service_price");
			$post_service_bhp = (array)$this->input->post("service_bhp");
			$post_service_section = (array)$this->input->post("service_section");
			$post_service_test = (array)$this->input->post("service_test");

			$this->load->library('form_validation');
			$this->form_validation->set_rules($this->service_model->rules['update']);
			
			if(config_item('bpjs_bridging') == 'TRUE')
				$this->form_validation->set_rules('JasaIDBPJS', lang('label:code') .' BPJS', "is_edit_unique[{$this->service_model->table}.JasaIDBPJS.JasaID.{$id}]");			
				
			$this->form_validation->set_data((array)$post_service);
			if( $this->form_validation->run() )
			{							
				$response  = service_helper::update_service( $post_service, $id, $post_service_price, $post_service_bhp, $post_service_section, $post_service_test);
			} else
			{
				$response["message"] = $this->form_validation->get_all_error_string();
				$response["status"] = "error";
				$response["code"] = "500";
			}
			
			response_json( $response );
		}
				
		$this->data['is_edit'] = TRUE;		
		$this->data['dropdown_group'] = $this->service_group_model->dropdown_data();
		$this->data['dropdown_category'] = $this->service_category_model->dropdown_data();
		$this->data['dropdown_plafon_cateogry'] = $this->plafon_service_category_model->dropdown_data(["Tipe" => 'Per Satuan']);
		$this->data['dropdown_posting_group'] = $this->service_model->dropdown_static('KelompokPostingan');
		$this->data['dropdown_insentif_source'] = $this->service_model->dropdown_static('ModelInsentif');
		$this->data['dropdown_polyclinic'] = $this->service_model->dropdown_static('PoliKlinik');
		$this->data['dropdown_devices'] = $this->devices_model->dropdown_data();
		$this->data['form_action'] = current_url();
		
		$this->template
			->title(lang('heading:service_update'),lang('heading:service'))
			->set_breadcrumb(lang('heading:service_list'), site_url($this->nameroutes))
			->set_breadcrumb(lang('heading:service_update'))
			->build("service/form", $this->data);
	}
	
	public function delete($id = 0)
	{
		$this->data['item'] = $item = $this->service_model->get_one($id);

		if ($item  && (1 == $this->input->post('confirm') ) )
		{ 

			try {
				$this->db->trans_begin();
				
				//hapus data di SIMmJasaBHP
				$this->db->where('JasaID', $id)->delete('SIMmJasaBHP');
				//hapus data di simmlistjasa
				$this->service_model->delete( $id );

				if ($this->db->trans_status() === FALSE)
				{
					$error = $this->db->error();
					$this->db->trans_rollback();
					response_json(["status" => 'error', 'message' => $error['message'], 'success' => FALSE]);
				} else
				{
					$this->db->trans_commit();
					response_json(["status" => 'success', 'message' => lang('global:delete_successfully'), 'success' => TRUE]);
				}
			} catch (Exception $e) {
				$this->db->trans_rollback();
				response_json(["status" => 'error', 'message' => $e->getMessage(), 'success' => FALSE]);

			}

	
		} 
		
		$this->data['form_action'] = $form_action = current_url();
		$this->load->view('service/modal/delete', $this->data);
	}
	
	public function lookup_data( $view, $is_ajax_request=false )
	{	
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view("service/lookup/{$view}");
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
		
		$db_from = "{$this->service_model->table} a";
		$db_where = array();
		$db_like = array();
		
		//prepare defautl flter

		// preparing default
		if( isset($search['value']) && ! empty($search['value']) )
        {
            $keywords = $this->db->escape_str( $search['value'] );
			
			$db_like[ $this->db->escape_str("a.{$this->service_model->index_key}") ] = $keywords;
			$db_like[ $this->db->escape_str("a.JasaName") ] = $keywords;
			$db_like[ $this->db->escape_str("b.GroupJasaName") ] = $keywords;
			$db_like[ $this->db->escape_str("c.KategoriJasaName") ] = $keywords;
        }
		
		// get total records
		$this->db->from( $db_from );
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		$records_total = $this->db->count_all_results();
		
		// get total filtered
		$this->db
			->from( $db_from )
			->join("{$this->service_group_model->table} b", "a.GroupJasaID = b.{$this->service_group_model->index_key}", "INNER")
			->join("{$this->service_category_model->table} c", "a.KategoriJasaID = c.{$this->service_category_model->index_key}", "INNER")
			;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }		
		$records_filtered = $this->db->count_all_results();
		
		// get result filtered
		$db_select = <<<EOSQL
			a.*,
			b.GroupJasaName,
			c.KategoriJasaName
EOSQL;

		$this->db
			->select( $db_select )
			->from( $db_from )
			->join("{$this->service_group_model->table} b", "a.GroupJasaID = b.{$this->service_group_model->index_key}", "INNER")
			->join("{$this->service_category_model->table} c", "a.KategoriJasaID = c.{$this->service_category_model->index_key}", "INNER")
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

