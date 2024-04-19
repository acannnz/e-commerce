<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Customer extends ADMIN_Controller
{
	protected $nameroutes = 'customer';
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('admin');
		
		$this->data['nameroutes'] = $this->nameroutes; 
		
		$this->load->language('customer');		
		$this->load->helper('customer');
		$this->load->model('customer_model');
		$this->load->model('contact_person_model');
		$this->load->model('service_category_model');
		$this->load->model('currency_model');
	}
	
	public function index()
	{
		$this->template
			->title(lang('heading:customer'), lang('heading:customer'))
			->set_breadcrumb(lang('heading:customer') )
			->set_breadcrumb(lang('heading:customer_list'), site_url($this->nameroutes))
			->build("customer/datatable", $this->data);
	}
	
	public function create()
	{
	
		$item = (object) [
			'Kode_Customer' => NULL,
			'Nama_Customer' => NULL,
		];
		
		if( $this->input->post() ) 
		{
			$post_customer = array_merge( (array) $item, $this->input->post("customer") );
			$post_contact_person = $this->input->post("contact_person");

			$this->load->library('form_validation');
			$this->form_validation->set_rules($this->customer_model->rules['insert']);
			$this->form_validation->set_data($post_customer);
			if( $this->form_validation->run() )
			{							
				$this->db->trans_begin();
							
					$customer_id = $this->customer_model->create( $post_customer );
					
					foreach( $post_contact_person as $row )
					{
						$row[$this->customer_model->index_key] = $customer_id;
						$this->contact_person_model->create( $row );
					}
					
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
		$this->data['dropdown_category'] = $this->service_category_model->dropdown_data();
		$this->data['dropdown_paymenttype'] = ['T' => 'Tunai', 'K' => 'Kredit'];
		$this->data['dropdown_currency'] = $this->currency_model->dropdown_data();
		$this->data['form_action'] = current_url();
		$this->data['add_contact_person'] = base_url("{$this->nameroutes}/contact_person/create");
			
		$this->template
			->title(lang('heading:customer_create'),lang('heading:customer'))
			->set_breadcrumb(lang('heading:customer_list'), site_url($this->nameroutes))
			->set_breadcrumb(lang('heading:customer_create'))
			->build("customer/form", $this->data);
	}
	
	public function update($id = 0)
	{
		$this->data['item'] = $item = $this->customer_model->get_one($id);
		
		if( $this->input->post() ) 
		{			
			$post_customer = $this->input->post("customer");
			$post_contact_person = $this->input->post("contact_person");
			
			$this->load->library('form_validation');
			$this->form_validation->set_rules($this->customer_model->rules['update']);
			$this->form_validation->set_data($post_customer);
			if( $this->form_validation->run() )
			{								
				$this->db->trans_begin();
				
					$this->customer_model->update( $post_customer, $id );	
					
					$delete_not_in = [];
					foreach( $post_contact_person as $row )
					{
						if(empty($row[ $this->customer_model->index_key ] ) || $row[ $this->customer_model->index_key ] == 0)
						{
							$row[ $this->customer_model->index_key ] = $id;
							$contact_person_id = $this->contact_person_model->create( $row );
						} else {
							$contact_person_id = $row[ $this->contact_person_model->index_key ];
							unset($row[ $this->contact_person_model->index_key ]);
							$this->contact_person_model->update( $row, $contact_person_id );
						}
						$delete_not_in[] = $contact_person_id;
					}
					
					$this->contact_person_model->delete_not_in( $delete_not_in, [$this->customer_model->index_key => $id]);
					
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
		$this->data['contact_person_collection'] = $this->contact_person_model->get_all(NULL, 0, ['Customer_ID' => $id]);
		$this->data['dropdown_category'] = $this->service_category_model->dropdown_data();
		$this->data['dropdown_paymenttype'] = ['T' => 'Tunai', 'K' => 'Kredit'];
		$this->data['dropdown_currency'] = $this->currency_model->dropdown_data();
		$this->data['form_action'] = current_url();
		$this->data['add_contact_person'] = base_url("{$this->nameroutes}/contact_person/create");
		
		$this->template
			->title(lang('heading:customer'),lang('heading:customer'))
			->set_breadcrumb(lang('heading:customer_list'), site_url($this->nameroutes))
			->set_breadcrumb(lang('heading:customer_update'))
			->build("customer/form", $this->data);
	}
	
	public function delete($id = 0)
	{
		$this->data['item'] = $item = $this->customer_model->get_one($id);
		
		if ($item  && (1 == $this->input->post('confirm') ) )
		{ 

			$this->db->trans_begin();
				
				$this->contact_person_model->delete_by([$this->customer_model->index_key => $id]);
				$this->customer_model->delete( $id );

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
		$this->load->view('customer/modal/delete', $this->data);
	}
	
	private function do_upload( $personal_picture = NULL )
	{
			$config['upload_path'] = realpath(FCPATH . '../../assets/customer-customer/photos');
			$config['allowed_types'] = 'jpeg|jpg|png';
			$config['max_size'] = 0;
			$config['max_width'] = 0;
			$config['max_height'] = 0;
			$config['remove_spaces'] = TRUE;
			$config['encrypt_name'] = TRUE;
			$config['overwrite'] = TRUE;
			
			if( $personal_picture && file_exists( realpath(FCPATH . "../../assets/customer-customer/photos/{$personal_picture}") ) )
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
		
		$db_from = "{$this->customer_model->table} a";
		$db_where = array();
		$db_like = array();
		
		//prepare defautl flter

		// preparing default
		if( isset($search['value']) && ! empty($search['value']) )
        {
            $keywords = $this->db->escape_str( $search['value'] );
			
			$db_like[ $this->db->escape_str("a.Kode_Customer") ] = $keywords;
			$db_like[ $this->db->escape_str("a.Nama_Customer") ] = $keywords;
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

