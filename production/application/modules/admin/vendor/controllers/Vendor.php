<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Vendor extends ADMIN_Controller
{
	protected $nameroutes = 'vendor';
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('admin');
		
		$this->data['nameroutes'] = $this->nameroutes; 
		
		$this->load->language('vendor');		
		$this->load->helper('vendor');
		
		$this->load->model('vendor_model');
		$this->load->model('specialist_model');
		$this->load->model('sub_specialist_model');
		$this->load->model('category_model');
	}
	
	public function index()
	{
	
		$this->template
			->title(lang('heading:vendor'), lang('heading:vendor'))
			->set_breadcrumb(lang('heading:vendor') )
			->set_breadcrumb(lang('heading:vendor_list'), site_url($this->nameroutes))
			->build("vendor/datatable", $this->data);
	}
	
	public function create()
	{
	
		$item = (object) [
			'Kode_Supplier' => NULL,
			'HonorDefault' => 100,
			'Active' => 1
		];
		
		if( $this->input->post() ) 
		{
			$post_vendor = array_merge( (array) $item, $this->input->post("f") );
		
			$this->load->library('form_validation');
			$this->form_validation->set_rules($this->vendor_model->rules['insert']);
			$this->form_validation->set_data($post_vendor);
			if( $this->form_validation->run() )
			{							
				$this->db->trans_begin();
							
					$this->vendor_model->create( $post_vendor );
					
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
					//$this->db->trans_rollback();
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
		$this->data['dropdown_category'] = $this->category_model->dropdown_data();
		$this->data['dropdown_specialist'] = $this->specialist_model->dropdown_data();
		$this->data['dropdown_subspecialist'] = $this->sub_specialist_model->dropdown_data();
		
		$this->data['form_action'] = current_url();
			
		$this->template
			->title(lang('heading:vendor_create'),lang('heading:vendor'))
			->set_breadcrumb(lang('heading:vendor_list'), site_url($this->nameroutes))
			->set_breadcrumb(lang('heading:vendor_create'))
			->build("vendor/form", $this->data);
	}
	
	public function update($id = 0)
	{
		$this->data['item'] = $item = $this->vendor_model->get_one($id);
		
		if( $this->input->post() ) 
		{			
			$post_vendor = $this->input->post("f");
			
			$this->load->library('form_validation');
			$this->form_validation->set_rules($this->vendor_model->rules['update']);
			
			if(config_item('bpjs_bridging') == 'TRUE')
				$this->form_validation->set_rules('Kode_Supplier_BPJS', lang('label:code') .' BPJS', "is_edit_unique[{$this->vendor_model->table}.Kode_Supplier_BPJS.Supplier_ID.{$id}]");			
			
			$this->form_validation->set_data($post_vendor);
			if( $this->form_validation->run() )
			{								
				$this->db->trans_begin();
				
					$this->vendor_model->update( $post_vendor, $id );	
					
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
		$this->data['dropdown_category'] = $this->category_model->dropdown_data();
		$this->data['dropdown_specialist'] = $this->specialist_model->dropdown_data();
		$this->data['dropdown_subspecialist'] = $this->sub_specialist_model->dropdown_data();
		$this->data['form_action'] = current_url();
		
		$this->template
			->title(lang('heading:vendor'),lang('heading:vendor'))
			->set_breadcrumb(lang('heading:vendor_list'), site_url($this->nameroutes))
			->set_breadcrumb(lang('heading:vendor_update'))
			->build("vendor/form", $this->data);
	}
	
	public function delete($id = 0)
	{
		$this->data['item'] = $item = $this->vendor_model->get_one($id);
		
		if ($item  && (1 == $this->input->post('confirm') ) )
		{ 

			$this->db->trans_begin();

				$this->vendor_model->delete( $id );

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
		$this->load->view('vendor/modal/delete', $this->data);
	}
	
	private function do_upload( $personal_picture = NULL )
	{
			$config['upload_path'] = realpath(FCPATH . '../../assets/vendor-vendor/photos');
			$config['allowed_types'] = 'jpeg|jpg|png';
			$config['max_size'] = 0;
			$config['max_width'] = 0;
			$config['max_height'] = 0;
			$config['remove_spaces'] = TRUE;
			$config['encrypt_name'] = TRUE;
			$config['overwrite'] = TRUE;
			
			if( $personal_picture && file_exists( realpath(FCPATH . "../../assets/vendor-vendor/photos/{$personal_picture}") ) )
			{
				$config['encrypt_name'] = FALSE;
				$config['file_name'] = $personal_picture;
			}

			$this->load->library('upload', $config);

			return ( ! $this->upload->do_upload('PersonalPicture') )
				? ['status'=>'error', 'message' => $this->upload->display_errors()]
				: ['status'=>'success', 'upload_data' => $this->upload->data()];

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
		
		$db_from = "{$this->vendor_model->table} a";
		$db_where = array();
		$db_like = array();
		
		//prepare defautl flter

		// preparing default
		if( isset($search['value']) && ! empty($search['value']) )
        {
            $keywords = $this->db->escape_str( $search['value'] );
			
			$db_like[ $this->db->escape_str("a.Kode_Supplier") ] = $keywords;
			$db_like[ $this->db->escape_str("a.Nama_Supplier") ] = $keywords;
			$db_like[ $this->db->escape_str("b.Kategori_Name") ] = $keywords;
			$db_like[ $this->db->escape_str("a.Alamat_1") ] = $keywords;
			$db_like[ $this->db->escape_str("a.No_Telepon_1") ] = $keywords;

        }
		
		// get total records
		$this->db->from( $db_from );
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		$records_total = $this->db->count_all_results();
		
		// get total filtered
		$this->db
			->from( $db_from )
			->join( "{$this->category_model->table} b", "a.KodeKategoriVendor = b.Kode_Kategori", "INNER" )
			;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }		
		$records_filtered = $this->db->count_all_results();
		
		// get result filtered
		$db_select = <<<EOSQL
			a.*,
			b.Kategori_Name
EOSQL;

		$this->db
			->select( $db_select )
			->from( $db_from )
			->join( "{$this->category_model->table} b", "a.KodeKategoriVendor = b.Kode_Kategori", "INNER" )
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
	
	public function get_subspecialist_list()
	{
		$this->output
			->set_status_header(200)
			->set_content_type('text/html', 'utf-8')
			->set_output($this->sub_specialist_model->dropdown_html(['SpesialisID' => $this->input->post('parent')]))
			->_display();
		
		exit(0);
	}
}

