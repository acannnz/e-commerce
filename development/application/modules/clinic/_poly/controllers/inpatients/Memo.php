<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Memo extends Admin_Controller
{
	protected $_translation = 'poly';	
	protected $_model = 'poly_m';
	protected $nameroutes = 'poly/inpatients/memo';
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('inpatient');
		
		$this->load->model("poly_m");
		$this->load->helper("poly");		
	}
	
	public function index( $NoReg )
	{
		$data = array(
				"collection" => $this->poly_m->get_memo_data( ["NOReg" => $NoReg] ),
				'nameroutes' => $this->nameroutes,
				'create_memo' => base_url("{$this->nameroutes}/item_create"),
				'delete_memo' => base_url("{$this->nameroutes}/item_delete"),
				'view_memo' => base_url("{$this->nameroutes}/item_view"),
			);

		$this->load->view( 'inpatient/form/memo', $data );		
	}

	public function item_create()
	{
		$item = array(
				'Memo' => NULL,
			);
		
		if( $this->input->post() ) 
		{
			
			
			$memo = $this->input->post("f");
			
			$this->load->library( 'form_validation' );
			$this->form_validation->set_rules( $this->get_model()->rules['insert'] );
			$this->form_validation->set_data( $this->input->post("f") );
			
			if( !$this->form_validation->run() )
			{

				$this->db->trans_begin();
					$memo['User_ID'] = $this->user_auth->User_ID;
					$this->db->insert("SIMtrMemo", $memo );				
					$NoUrut = $this->db->insert_id();
					
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
							"code" => 200,
							"NoUrut" => $NoUrut
						);
				}				

			} else
			{
				$response = array(
						'response_status' => 'error',
						'message' => $this->form_validation->get_all_error_string()
					);
			}
			
			print_r(json_encode($response, JSON_NUMERIC_CHECK));
			exit(0);
		}

		if( $this->input->is_ajax_request() )
		{
			$data = array(
					'item' => (object) $item,
					"is_ajax_request" => TRUE,
					"is_modal" => TRUE,
					'nameroutes' => $this->nameroutes,
					"user" => $this->user_auth,
				);
			
			$this->load->view( 'inpatient/memo/form', $data );
		} 
	}

	public function item_delete()
	{
		
		if( $this->input->post() ) 
		{
			
			
			$NoUrut = $this->input->post("NoUrut");
			
			$this->load->library( 'form_validation' );
			
			//$this->item->addData( $this->input->post("f") );
			//$this->form_validation->set_rules( $this->get_model()->rules['insert'] );
			//$this->form_validation->set_data( $this->item->toArray() );
			
			if( !$this->form_validation->run() )
			{
				$this->db->trans_begin();

					$this->db->delete("SIMtrMemo", array("NoUrut" => $NoUrut) );				
					
				if ($this->db->trans_status() === FALSE)
				{
					$this->db->trans_rollback();
					$response = array(
							"status" => 'error',
							"message" => lang('global:deleted_failed'),
							"code" => 500
						);
				}
				else
				{
					$this->db->trans_commit();
					$response = array(
							"status" => 'success',
							"message" => lang('global:deleted_successfully'),
							"code" => 200
						);
				}				

			} else
			{
				$response = array(
						'response_status' => 'error',
						'message' => $this->form_validation->get_all_error_string()
					);
			}
			
			print_r(json_encode($response, JSON_NUMERIC_CHECK));
			exit(0);
		}
	}			

	public function item_view( $NoUrut )
	{

		if( $this->input->is_ajax_request() )
		{
			
			$item = $this->poly_m->get_row_data("SIMtrMemo", array("NoUrut"  => $NoUrut));

			$data = array(
					'item' => $item,
					'nameroutes' => $this->nameroutes,
					"is_ajax_request" => TRUE,
					"is_modal" => TRUE,
					"is_edit" => TRUE,
				);
			
			$this->load->view( 'inpatient/memo/form_view', $data );
		} 
	}
}