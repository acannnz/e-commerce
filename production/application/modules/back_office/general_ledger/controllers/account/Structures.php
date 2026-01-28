<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Structures extends Admin_Controller
{
	protected $_translation = 'general_ledger';	
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('general_ledger');
		
		$this->load->helper("general_ledger");
		$this->load->model("account/structure_m");
		
		$this->page = "general_ledger_structures";
		$this->template->title( lang("structures:page") . ' - ' . $this->config->item('company_name') );
	}
	
	public function index()
	{
		
		$data = array(
				'page' => $this->page,
				"form" => TRUE,
				"jstree" => TRUE,
				"datatables" => TRUE,
				"tree_collection" => base_url("general-ledger/account/structures/tree_collection"),
				"create_url" => base_url("general-ledger/account/structures/create"),
				"edit_url" => base_url("general-ledger/account/structures/edit"),
				"delete_url" => base_url("general-ledger/account/structures/delete"),
			);
		
		$this->template
			->set( "heading", lang("accounts:page") )
			->set_breadcrumb( lang("general_ledger:page"), base_url("common") )
			->set_breadcrumb( lang("structures:breadcrumb") )
			->build('accounts/structures/tree', (isset($data) ? $data : NULL));
	}	
		
	public function create( $Komponen )
	{
		$item_data = array(
				'Komponen' => $Komponen,
				'Group_Name' => NULL,
				'Keterangan' => NULL,
				'NomorUrut' => $this->structure_m->gen_header_queue( $Komponen )
			);
		
		$this->load->library( 'my_object', $item_data, 'item' );
		
		if( $this->input->post() ) 
		{
			
			
			$this->load->library( 'form_validation' );
			
			$this->item->addData( $this->input->post("f") );
			$this->form_validation->set_rules( $this->structure_m->rules['insert'] );
			$this->form_validation->set_data( $this->item->toArray() );
			
			$response = array(
					"message" => lang('global:created_successfully'),
					"status" => "success",
					"code" => "200",
				);

			if( $this->form_validation->run() )
			{
				$details = $this->input->post("details");
				
				if( !$this->structure_m->create_data( $this->item->toArray(), $details) )
				{
					$response["message"] = lang('global:created_failed');
					$response["status"] = "error";
					$response["code"] = "500";
				}
			} else
			{
				$response["message"] = $this->form_validation->get_all_error_string();
				$response["status"] = "error";
				$response["code"] = "500";
			}
			
			print_r( json_encode($response, JSON_NUMERIC_CHECK) );
			exit(0);
		}

		if( $this->input->is_ajax_request() )
		{
			$data = array(
				'item' => (object) $this->item->getData(), 
				'is_modal' => TRUE
			);
			
			$this->load->view( 
					'accounts/structures/modal/create_edit', 
					array('form_child' => $this->load->view('accounts/structures/form', $data, true))
				);
		} else
		{
			$data = array(
					"page" => $this->page,
					"item" => (object) $this->item->getData(),
					"form" => TRUE,
				);
			
			$this->template
				->set( "heading", lang("structures:create_heading") )
				->set_breadcrumb( lang("general_ledger:page"), base_url("general-ledger") )
				->set_breadcrumb( lang("structures:breadcrumb"), base_url("general-ledger/account/structures") )
				->set_breadcrumb( lang("structures:create_heading") )
				->build('structures/form', $data);
		}
	}
	
	public function edit( $id = 0 )
	{
		$id = (int) @$id;
		
		$item = $this->structure_m->get_row( $id );
		if( ! $item ){ $item = array('id' => 0); }
		$this->load->library( 'my_object', $item, 'item' );

		$collection = $this->structure_m->get_detail( $id );
		
		if( $this->input->post() ) 
		{
			
			
			$this->load->library( 'form_validation' );
			
			$this->item->addData( $this->input->post("f") );
			$this->form_validation->set_rules( $this->structure_m->rules['insert'] );
			$this->form_validation->set_data( $this->item->toArray() );
			
			$response = array(
					"message" => lang('global:updated_successfully'),
					"status" => "success",
					"code" => "200",
				);
				
			if( $this->form_validation->run() )
			{
				$details = $this->input->post("details");
				
				if( !$this->structure_m->update_data( $id, $this->item->toArray(), $details) )
				{
					$response["message"] = lang('global:updated_failed');
					$response["status"] = "error";
					$response["code"] = "500";
				}
			} else
			{
				$response["message"] = $this->form_validation->get_all_error_string();
				$response["status"] = "error";
				$response["code"] = "500";
			}
			
			print_r( json_encode($response, JSON_NUMERIC_CHECK) );
			exit(0);
		}

		if( $this->input->is_ajax_request() )
		{
			$data = array(
				'item' => (object) $this->item->getData(), 
				'collection' => $collection, 
				'is_modal' => TRUE
			);
			
			$this->load->view( 
					'accounts/structures/modal/create_edit', 
					array('form_child' => $this->load->view('accounts/structures/form', $data, true))
				);
		} else
		{
			$data = array(
					"page" => $this->page,
					"item" => (object) $this->item->getData(),
					"collection" => $collection,
					"form" => TRUE,
					"datatabled" => TRUE,
				);
			
			$this->template
				->set( "heading", lang("structures:edit_heading") )
				->set_breadcrumb( lang("general_ledger:page"), base_url("general-ledger") )
				->set_breadcrumb( lang("structures:breadcrumb"), base_url("general-ledger/account/structures") )
				->set_breadcrumb( lang("structures:edit_heading") )
				->build('accounts/structures/form', $data);
		}
	}
	
	public function delete( $id=0 )
	{
		$id = (int) @$id;
		
		$item = $this->structure_m->get_row( $id );
		if( ! $item ){ $item = array('id' => 0); }
		$this->load->library( 'my_object', $item, 'item' );
		
		if( $this->input->post() ) 
		{
			
			
			if( 0 == @$item['Group_ID'] )
			{
				make_flashdata(array(
						'response_status' => 'error',
						'message' => lang( 'global:get_failed' )
					));
			
				redirect( $this->input->post( 'r_url' ) );
			}
			
			if( @$item['Group_ID'] == $this->input->post( 'confirm' ) )
			{
				if( !$this->structure_m->delete_data( @$item['Group_ID'] ) ){
					make_flashdata(array(
							'response_status' => 'error',
							'message' => lang('global:deleted_failed')
						));				
				} else {
				
					make_flashdata(array(
							'response_status' => 'success',
							'message' => lang('global:deleted_successfully')
						));
				}
			}
			
			redirect( $this->input->post( 'r_url' ) );
		}

		$data = array(
			'item' => (object)$this->item->getData()
		);

		$this->load->view( 'accounts/structures/modal/delete', $data );
	}
	
	public function tree_collection( $id = 0 )
    {
		$output = array();
      
	  	# NERACA
		$output[] = array(
			"id" => "root1",
			"text" => "NERACA",
			"type" => "root",
			"parent" => "#",
			"component" => 1,
			"state" => array("opened" => TRUE)
		);		
		
		$headers = $this->structure_m->get_header( 1 );
		if(!empty($headers)): foreach( $headers as $header ):
			$output[] = array(
				"id" => sprintf("%s-%s", "Group", $header->Group_ID),
				"text" => $header->Group_Name,
				"parent" => "root1",
				"icon" => "fa fa-folder-o",
				"type" => "header",
				"Group_ID" => $header->Group_ID
			);

			$details = $this->structure_m->get_detail( $header->Group_ID );
			if(!empty($details)): foreach($details as $detail):
				$output[] = array(
					"id" => sprintf("%s-%s", "GroupDetail", $detail->GroupAkunDetailId),
					"text" => $detail->GroupAkunDetailName,
					"parent" => sprintf("%s-%s", "Group", $header->Group_ID),
					"icon" => "fa fa-file-o",
					"type" => "detail"
				);	
			endforeach; endif;
			
		endforeach;endif;
		 
		# LABA RUGI
		$output[] = array(
			"id" => "root2",
			"text" => "LABA RUGI",
			"type" => "root",
			"parent" => "#",
			"component" => 2,
			"state" => array("opened" => TRUE)
		);
			
		$headers = $this->structure_m->get_header( 2 );
		if(!empty($headers)): foreach( $headers as $header ):
			$output[] = array(
				"id" => sprintf("%s-%s", "Group", $header->Group_ID),
				"text" => $header->Group_Name,
				"parent" => "root2",
				"icon" => "fa fa-folder-o",
				"type" => "header",
				"Group_ID" => $header->Group_ID
			);

			$details = $this->structure_m->get_detail( $header->Group_ID );
			if(!empty($details)): foreach($details as $detail):
				$output[] = array(
					"id" => sprintf("%s-%s", "GroupDetail", $detail->GroupAkunDetailId),
					"text" => $detail->GroupAkunDetailName,
					"parent" => sprintf("%s-%s", "Group", $header->Group_ID),
					"icon" => "fa fa-file-o",
					"type" => "detail"
				);	
			endforeach; endif;
			
		endforeach;endif;
		
		print_r(json_encode($output, JSON_NUMERIC_CHECK)); 
		exit(0);
    }
}

