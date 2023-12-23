<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Accounts extends Admin_Controller
{
	protected $_translation = 'general_ledger';	
	protected $_model = 'account_m';
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('general_ledger');
		
		$this->load->helper("account");
		
		$this->page = "accounting_account";
		$this->template->title( lang("accounts:page") . ' - ' . $this->config->item('company_name') );
	}
	
	public function index()
	{
		$data = array(
				'page' => $this->page,
				"form" => TRUE,
				'datatables' => TRUE,
			);
		
		$this->template
			->set( "heading", lang("accounts:page") )
			->set_breadcrumb( lang("common:page"), base_url("common") )
			->set_breadcrumb( lang("services:breadcrumb") )
			->build('accounts/datatable', (isset($data) ? $data : NULL));
	}

	public function tree()
	{
		
		$data = array(
				'page' => $this->page,
				"form" => TRUE,
				"jstree" => TRUE,
				"tree_collection" => base_url("general-ledger/accounts/tree_collection"),
				"create_url" => base_url("general-ledger/accounts/create"),
				"edit_url" => base_url("general-ledger/accounts/edit"),
				"add_child_url" => base_url("general-ledger/accounts/create"),
				"delete_url" => base_url("general-ledger/accounts/delete"),
			);
		
		$this->template
			->set( "heading", lang("accounts:page") )
			->set_breadcrumb( lang("common:page"), base_url("common") )
			->set_breadcrumb( lang("services:breadcrumb") )
			->build('accounts/tree', (isset($data) ? $data : NULL));
	}	
	
	public function create( $parent_id = NULL )
	{
		$parent = account_helper::get_parent( $parent_id );
		$item_data = array(
				'Akun_No' => account_helper::gen_account_number( @$parent_id ),
				'Akun_Name' => null,
				'Normal_Pos' => @$parent->Normal_Pos,
				'Group_ID' => @$parent->Group_ID,
				'Level_Ke' => (int) @$parent->Level_Ke + 1,
				'Kelompok' => @$parent->Kelompok,
				'Convert_Permanen' => null,
				'Currency_id' => 1,
				'GroupAkunDetailID' => null,
				'SumberIntegrasi' => 'NONE',
				'Integrasi' => null,
				'AccountNo' => 0,
				'AccountName' => null,
				'AccountLocation' => 0,
				'SwiftCode' => null
			);
		

		$this->load->library( 'my_object', $item_data, 'item' );
		
		if( $this->input->post() ) 
		{
			
						
			$this->load->library( 'form_validation' );
			
			$this->item->addData( $this->input->post("f") );			
			$this->form_validation->set_rules( $this->get_model()->rules['insert'] );
			$this->form_validation->set_data( $this->item->toArray() );
			
			$response = array(
					"message" => lang('global:created_successfully'),
					"status" => "success",
					"code" => "200",
				);
			
			if( $this->form_validation->run() )
			{
				if( !$this->get_model()->create_data( (object) $this->item->toArray() ) )
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
					"is_ajax_request" => TRUE,
					"is_modal" => TRUE,
					"option_group" => $this->get_model()->get_option_group(),
					"option_group_detail" => $this->get_model()->get_option_group_detail(),
					"option_currency" => $this->get_model()->get_option_currency(),
					"max_concept" => $this->get_model()->get_concept(),
					"form" => TRUE,
				);
				
			$this->load->view( 
					'accounts/modal/create_edit', 
					array('form_child' => $this->load->view('accounts/form', $data, true))
				);
		} else
		{
			$data = array(
					"page" => $this->page."_".strtolower(__FUNCTION__),
					'item' => (object) $this->item->getData(),
					"option_group" => $this->get_model()->get_option_group(),
					"option_group_detail" => $this->get_model()->get_option_group_detail(),
					"option_currency" => $this->get_model()->get_option_currency(),
					"max_concept" => $this->get_model()->get_concept(),
					"form" => TRUE,
				);
			
			$this->template
				->set( "heading", lang("accounts:create_heading") )
				->set_breadcrumb( lang("common:page"), base_url("common") )
				->set_breadcrumb( lang("services:breadcrumb"), base_url("common/account") )
				->set_breadcrumb( lang("accounts:create_heading") )
				->build('accounts/form', $data);
		}
	}
	
	public function edit( $id=0 )
	{
		
		$item = $this->get_model()->get_account( $id );
		
		if( ! $item ){ $item = array('id' => 0); }
		$this->load->library( 'my_object', (array) $item, 'item' );
		
		if( $this->input->post() ) 
		{
			
			
			$this->load->library( 'form_validation' );
			
			$this->item->addData( $this->input->post("f") );
			$this->form_validation->set_rules( $this->get_model()->rules['insert'] );
			$this->form_validation->set_data( $this->item->toArray() );
			
			$response = array(
					"message" => lang('global:updated_successfully'),
					"status" => "success",
					"code" => "200",
				);
			
			if( $this->form_validation->run() )
			{
				if( !$this->get_model()->update_data( (object) $this->item->toArray(), @$id, $item->Akun_Name ) )
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
		
		//print_r( $item );exit;

		if( $this->input->is_ajax_request() )
		{
			$data = array(
					"item" => (object) $this->item->getData(),
					"is_ajax_request" => TRUE,
					"is_modal" => TRUE,
					"is_edit" => TRUE,
					"is_trans" => $this->get_model()->check_account_transaction( $id ),
					"option_group" => $this->get_model()->get_option_group(),
					"option_group_detail" => $this->get_model()->get_option_group_detail(),
					"option_currency" => $this->get_model()->get_option_currency(),
					"max_concept" => $this->get_model()->get_concept(),
					"form" => TRUE,
				);

			$this->load->view( 
					'accounts/modal/create_edit', 
					array('form_child' => $this->load->view('accounts/form', $data, true))
				);
		} else
		{
			$data = array(
					"page" => $this->page."_".strtolower(__FUNCTION__),
					"is_edit" => TRUE,
					"is_trans" => $this->get_model()->check_account_transaction( $id ),
					"item" => (object) $this->item->getData(),
					"option_group" => $this->get_model()->get_option_group(),
					"option_group_detail" => $this->get_model()->get_option_group_detail(),
					"option_currency" => $this->get_model()->get_option_currency(),
					"max_concept" => $this->get_model()->get_concept(),
					"form" => TRUE,
				);
			
			$this->template
				->set( "heading", lang("accounts:edit_heading") )
				->set_breadcrumb( lang("common:page"), base_url("common") )
				->set_breadcrumb( lang("services:breadcrumb"), base_url("common/account") )
				->set_breadcrumb( lang("accounts:edit_heading") )
				->build('accounts/form', $data);
		}
	}
	
	public function delete( $id=0 )
	{
		$item = $this->get_model()->get_account( $id );
		if( ! $item ){ $item = array('id' => 0); }
		$this->load->library( 'my_object', (array) $item, 'item' );
		
		if( $this->input->post() ) 
		{
			
			
			$response = array(
					"message" => lang("global:deleted_successfully"),
					"status" => "success",
					"code" => "200",
				);

			if( 0 == @$item->Akun_ID )
			{			
				$response["message"] = lang( 'global:get_failed' );
				$response["status"] = "error";
				$response["code"] = "404";				

				print_r( json_encode($response, JSON_NUMERIC_CHECK) );			
				exit(0);
			}
			
			if( $item->Akun_ID == $this->input->post( 'confirm' ) )
			{
				if ( !$this->get_model()->delete_data( (object) $this->item->toArray() ) )
				{
					$response["message"] = lang( 'global:deleted_failed' );
					$response["status"] = "error";
					$response["code"] = "500";
				}				
			}
			
			print_r( json_encode($response, JSON_NUMERIC_CHECK) );			
			exit(0);
		}
		
		$data = array(
				"item" => $item,
				"is_trans" => $this->get_model()->check_account_transaction( $id ),
			);

		$this->load->view( 'accounts/modal/delete', $data );			
	}
	
	public function lookup( $is_ajax_request=false, $source = NULL )
	{
				
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
		
			$data = array(
					"source" => $source
				);

			$this->load->view( 'accounts/lookup', $data );
		} else
		{

			$data = array(
					'page' => $this->page,
					'datatables' => TRUE,
					'form' => TRUE,
					"source" => $source
				);
			
			$this->template
				->set( "heading", "Lookup Box" )
				->set_breadcrumb( lang("common:page"), base_url("common") )
				->set_breadcrumb( "Lookup Box" )
				->build('accounts/lookup', (isset($data) ? $data : NULL));
		}
	}
	
	public function lookup_collection( $source = NULL)
	{
			$this->datatable_collection( $source );
		
	}
	
	public function datatable_collection( $source = NULL )
    {
        
		$start = $this->input->get_post('start', true);
        $length = $this->input->get_post('length', true);
        $order = $this->input->get_post('order', true);
        $columns = $this->input->get_post('columns', true);
        $search = $this->input->get_post('search', true);
        $draw = $this->input->get_post('draw', true);
		
		$db_from = "{$this->get_model()->table} a";
		$db_where = array();
		$db_or_where = array();
		$db_or_where_group = array();
		$db_like = array();
		
		// prepare defautl flter
		
		switch ($source)
		{
			case "AR" : 
				$db_where['SumberIntegrasi'] = $source;
			break;
			case "AR-FACTUR" : 
				$db_or_where['SumberIntegrasi'] = 'AR';
				$db_where['Integrasi'] = 0;
				$db_where['Induk'] = 0;
			break;
			case "AP" : 
				$db_where['SumberIntegrasi'] = $source;
			break;
			case "AP-FACTUR" : 
				$db_or_where['SumberIntegrasi'] = 'AP';
				$db_where['Integrasi'] = 0;
				$db_where['Induk'] = 0;
			break;
			case "GC" : 
				$db_where['SumberIntegrasi'] = $source;
			break;
			case "GC-Cash" : 
				$db_where['Induk'] = 0;
				$db_where['b.Cash'] = 1;
				$db_where['a.Aktif'] = 1;
				//$db_where['a.Akun_ID !='] = 2040;
			break;
			case "GC-Bank" : 
				$db_where['Induk'] = 0;
				$db_where['b.Bank'] = 1;
				$db_where['a.Aktif'] = 1;
				//$db_where['a.Akun_ID !='] = 2040;
			break;
			case "GC-Cash-Bank" : 
				$db_where['Induk'] = 0;
				$db_or_where_group['b.Cash'] = 1;
				$db_or_where_group['b.Bank'] = 1;
				$db_where['a.Aktif'] = 1;
				//$db_where['a.Akun_ID !='] = 2040;
			break;
			case "GL" : 
				$db_where['SumberIntegrasi'] = $source;
			break;
			case "NONE" : 
				$db_where['Integrasi'] = 0;
				$db_where['Induk'] = 0;
			break;
		} 
		
		// preparing default
		if( isset($search['value']) && ! empty($search['value']) )
        {
            $keywords = $this->db->escape_str( $search['value'] );
						
			$db_like[ $this->db->escape_str("a.Normal_Pos") ] = $keywords;
			$db_like[ $this->db->escape_str("a.Akun_No") ] = $keywords;
			$db_like[ $this->db->escape_str("a.SumberIntegrasi") ] = $keywords;
			$db_like[ $this->db->escape_str("a.Akun_Name") ] = $keywords;
        }
		
		// get total records
		$this->db->from( $db_from )
			->join('Mst_GroupAkunDetail b', 'a.GroupAkunDetailId = b.GroupAkunDetailId', 'INNER');
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		
		$records_total = $this->db->count_all_results();
		
		// get total filtered
		$this->db
			->from( $db_from )
			->join('Mst_GroupAkunDetail b', 'a.GroupAkunDetailId = b.GroupAkunDetailId', 'INNER')
			;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_or_where) ){ $this->db->or_where( $db_or_where ); }
		if( !empty($db_or_where_group) ){ $this->db->group_start()->or_where( $db_or_where_group )->group_end(); }		
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }		
		$records_filtered = $this->db->count_all_results();
		
		// get result filtered
		$db_select = <<<EOSQL
			a.*
EOSQL;

		$this->db
			->select( $db_select )
			->from( $db_from )
			->join('Mst_GroupAkunDetail b', 'a.GroupAkunDetailId = b.GroupAkunDetailId', 'INNER')
			;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_or_where) ){ $this->db->or_where( $db_or_where ); }
		if( !empty($db_or_where_group) ){ $this->db->group_start()->or_where( $db_or_where_group )->group_end(); }		
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }		
		
		// ordering
        if( isset($order) )
        {
            $sort_column = $order[0]['column'];
			$sort_dir = $order[0]['dir'];
			
			if( !$columns[$sort_column]['orderable'] == 'true' )
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
	
	public function datatable_collection_for_general_cashier( $state=false )
    {
        
		$start = $this->input->get_post('start', true);
        $length = $this->input->get_post('length', true);
        $order = $this->input->get_post('order', true);
        $columns = $this->input->get_post('columns', true);
        $search = $this->input->get_post('search', true);
        $draw = $this->input->get_post('draw', true);
		
		$db_from = "{$this->get_model()->table} a";
		$db_where = array();
		$db_like = array();
		
		// prepare defautl flter
		$db_where['a.house_id'] = $this->_house_id;
		if( $state !== false )
		{
			$db_where['a.state >='] = $state;
		}
		
		if ( empty($this->input->post("master_modul")))
		{
			$childs = $this->get_model()->get_child_id( array("house_id" => $this->_house_id ) );
			$db_child = implode(', ', array_map(function ($entry) {
									return $entry['id'];
								}, $childs));
		}

		// preparing default
		if( isset($search['value']) && ! empty($search['value']) )
        {
            $keywords = $this->db->escape_str( $search['value'] );
						
			$db_like[ $this->db->escape_str("a.normal_pos") ] = $keywords;
			$db_like[ $this->db->escape_str("a.account_number") ] = $keywords;
			$db_like[ $this->db->escape_str("a.integration_source") ] = $keywords;

			$db_like[ $this->db->escape_str("a.account_number") ] = $keywords;
			$db_like[ $this->db->escape_str("a.account_name") ] = $keywords;
						

        }
		
		// get total records
		$this->db->from( $db_from );
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_child) ){ $this->db->where_in('id', $db_child, FALSE ); }
		
		$records_total = $this->db->count_all_results();
		
		// get total filtered
		$this->db
			->from( $db_from )
			;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_child) ){ $this->db->where_in('id', $db_child, FALSE ); }
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
		if( !empty($db_child) ){ $this->db->where_in('id', $db_child, FALSE ); }
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
	
	public function tree_collection( )
    {
        
		$collection = $this->get_model()->get_accounts();
		$concepts = $this->get_model()->get_concepts();
		
		$output = [];
		foreach( $collection as $row ){
			$data = array(
				"id" => (string) $row->Akun_No,
				"Akun_ID" => $row->Akun_ID,
				"Akun_No" => $row->Akun_No,
				"parent_id" => account_helper::get_parent_id( $row ),
				"text" => sprintf("%s %s", $row->Akun_No, $row->Akun_Name),
				"parent" => $this->_set_parent( $concepts, $row ),
				"type" => $this->_set_type( $row ),
				"add_child" => $this->_add_child( $row->Level_Ke ),
			);
			
			$data["icon"] = $this->_set_icon( $data["type"] ); 
			if ( $row->Level_Ke == 1) unset($data['icon']);
			
			$output[] = $data;
		}
		
		if( empty($output) ){
			$output[] = [
				"text" => lang('buttons:add'),
				"Akun_ID" => 0,
				"parent_id" => 0, 
				"parent" => "#",
				"type" => 'root',
				"add_child" => TRUE,
			];
		}
				
		print_r(json_encode($output)); 
		exit(0);
    }
	
	private function _set_parent( $concepts, $child )
	{
		$parent_level = $child->Level_Ke - 1;
		if( $parent_level == 0) return "#";
		
		$parent_digit = $concepts[ $parent_level ]->Jumlah_Digit;
		return (string) substr($child->Akun_No, 0, $parent_digit); // return parent Account Number
	}

	private function _set_type( $account )
	{
		if ( $account->Level_Ke == 1) return "root";
		
		$get_child = account_helper::get_child( $account->Akun_No, $account->Level_Ke);
		
		return !empty($get_child) ? "header" : "child";
	}

	private function _set_icon( $type )
	{
		switch ( $type )
		{
			case "header":
				$icon = "fa fa-folder-o"; break;
			case "child":
				$icon = "fa fa-file-o"; break;
			default:
				$icon = FALSE;
		}
		
		return $icon;
	}			
	
	private function _add_child( $level )
	{
		$max = $this->get_model()->get_concept();
		
		return ( $max->Jumlah_Level > $level ) ? TRUE : FALSE;
	}
	
	public function autocomplete()
	{
		$words = $this->input->get_post('query');
		
		$this->db
			->select( array("id", "code", "clinic_title") )
			;
			
		$this->db
			->from( "common_clinics" )
			;
		
		$this->db
			->group_start()
				->where(array(
						'deleted_at' => NULL,
						'state' => 1
					))
			->group_end()
			;
		
		$this->db
			->group_start()
			->or_like(array(
					"code" => $words,
					"clinic_title" => $words,
					"clinic_description" => $words,
				))
			->group_end();
			
		$result = $this->db
			->get()
			->result()
			;
		
		if( $result )
		{
			$collection = array();
			foreach( $result as $item )
			{
				array_push($collection, array(
						"name" => $item->clinic_title,
						"id" => $item->id,
					));
			}
		} else
		{
			$collection = array(array(
					"value" => 0,
					"label" => lang( "global:no_match" ),
					"id" => 0,
				));
		}
		
		header( 'Content-Type: application/json; charset=utf-8' );
		echo json_encode($collection);
		exit(0);
	}

//Let's assume you have your data from the database as such



}