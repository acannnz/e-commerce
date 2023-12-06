<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cash_flow extends Admin_Controller
{
	protected $_translation = 'general_ledger';	
	protected $_model = 'cash_flow_group_m';
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('general_ledger');
		
		$this->load->helper("cash_flow");
		$this->load->model("cash_flow_subgroup_m");
		$this->load->model("cash_flow_account_m");
		$this->load->model("account_m");
		
		$this->page = "cash_flow_groups";
		$this->template->title( lang("cash_flow:page") . ' - ' . $this->config->item('company_name') );
	}
	
	public function setup()
	{
		$data = array(
				'page' => $this->page,
				"form" => TRUE,
				'datatables' => TRUE,
			);
		
		$this->template
			->set( "heading", lang("cash_flow:page") )
			->set_breadcrumb( lang("cash_flow:breadcrumb") )
			->set_breadcrumb( lang("cash_flow:group_label"), base_url("general-ledger/cash-flow") )
			->build('cash_flow/datatable', (isset($data) ? $data : NULL));
	}
	
	public function create()
	{
		$item = [
			'Group_Name' => null,
			'GroupI_Name' => null,
		];
		
		if( $this->input->post() ) 
		{
			$post_data = $this->input->post('f');
			$this->load->library( 'form_validation' );			
			$this->form_validation->set_rules( $this->get_model()->rules['insert'] );
			$this->form_validation->set_data( $post_data );
			if( $this->form_validation->run() )
			{
				if( $this->cash_flow_subgroup_m->create( $post_data ) )
				{					
					$reponse = [
						'response_status' => 'success',
						'message' => lang('global:created_successfully')
					];
						
				} else
				{
					$reponse = [
						'response_status' => 'error',
						'message' => lang('global:created_failed')
					];
				}
			} else
			{
				$reponse = [
					'response_status' => 'error',
					'message' => $this->form_validation->get_all_error_string()
				];
			}
			response_json( $reponse );
		}
		
		if( $this->input->is_ajax_request() )
		{
			$this->load->view( 
					'cash_flow/modal/create_edit', 
					[
						'form_child' => $this->load->view('cash_flow/form', ['item' => (object) $item, 'option_group' => $this->get_model()->dropdown(), 'is_modal' => TRUE], true)
					]
				);
		} else {
			show_404();
		}
	}
	
	public function edit( $id=0 )
	{
		$item = $this->cash_flow_subgroup_m->get_one( $id );
		if( $this->input->post() ) 
		{
			$post_data = $this->input->post("f");
			
			$this->load->library( 'form_validation' );			
			$this->form_validation->set_rules( $this->get_model()->rules['insert'] );
			$this->form_validation->set_data( $post_data );
			
			if( $this->form_validation->run() )
			{
				if( $this->cash_flow_subgroup_m->update( $post_data, @$id ) )
				{					
					$reponse = [
						'response_status' => 'success',
						'message' => lang('global:updated_successfully')
					];
				} else
				{
					$reponse = [
						'response_status' => 'error',
						'message' => lang('global:updated_failed')
					];
				}
			} else
			{
				$reponse = [
					'response_status' => 'error',
					'message' => $this->form_validation->get_all_error_string()
				];
			}
			response_json($reponse);
		}
		
		if( $this->input->is_ajax_request() )
		{
			$this->load->view( 
					'cash_flow/modal/create_edit', 
					[
						'form_child' => $this->load->view('cash_flow/form', ['item' => (object) $item, 'option_group' => $this->get_model()->dropdown(), 'is_modal' => TRUE], true)
					]
				);
		} else
		{
			show_404();
		}
	}
	
	public function delete( $id=0 )
	{
		$item = $this->cash_flow_subgroup_m->get_one( $id );
		if( $this->input->post() ) 
		{
			if( 0 == $item->{$this->cash_flow_subgroup_m->index_key} )
			{
				make_flashdata(array(
						'response_status' => 'error',
						'message' => lang( 'global:get_failed' )
					));
			
				redirect( $this->input->post( 'r_url' ) );
			}
			
			if( $item->{$this->cash_flow_subgroup_m->index_key} == $this->input->post( 'confirm' ) )
			{
				make_flashdata(array(
						'response_status' => 'success',
						'message' => lang('global:deleted_successfully')
					));

				if ( $this->cash_flow_subgroup_m->delete($id ) === FALSE )
				{				
					make_flashdata(array(
							'response_status' => 'success',
							'message' => lang('global:deleted_failed')
						));
				}
			}
			
			redirect( $this->input->post( 'r_url' ) );
		}
		
		$this->load->view( 'cash_flow/modal/delete', ['item' => (object) $item, 'option_group' => $this->get_model()->dropdown()] );
	}
	
	public function account()
	{
		if( $this->input->post() ) 
		{
			$detail = $this->input->post('detail');
			$reponse = [
					'response_status' => 'success',
					'message' => lang('global:created_successfully')
				];
				
			$this->db->trans_begin();
				foreach($detail as $row):
					if($this->cash_flow_account_m->count_all(['Akun_id' => $row['Akun_id'], 'Normal_Pos' => $row['Normal_Pos']]))
					{
						$this->cash_flow_account_m->update_by(['GroupII_id' => $row['GroupII_id']], ['Akun_id' => $row['Akun_id'], 'Normal_Pos' => $row['Normal_Pos']]);
					} else {
						$this->cash_flow_account_m->create( $row );
					}
				endforeach;
			
			if($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
				$reponse = [
					'response_status' => 'error',
					'message' => lang('global:created_failed')
				];
			} 
			$this->db->trans_commit();
			
			response_json( $reponse );
		}
		$data = [
			'datatables' => TRUE,
			'option_subgroup' => $this->cash_flow_subgroup_m->dropdown()
		];
		$this->template
			->set( "heading", lang("cash_flow:account_heading") )
			->set_breadcrumb( lang("cash_flow:breadcrumb") )
			->set_breadcrumb( lang("cash_flow:account_label"), base_url("general-ledger/cash-flow/account") )
			->build('cash_flow/form_account_detail', (isset($data) ? $data : NULL));
	}

	public function lookup_accounts(  ){
	
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'cash_flow_groups/lookup/accounts', array() );
		} 
	}
	
	public function datatable_collection( $state=false )
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

		// preparing default
		if( isset($search['value']) && ! empty($search['value']) )
        {
            $keywords = $this->db->escape_str( $search['value'] );
						
			$db_like[ $this->db->escape_str("a.Group_Name") ] = $keywords;			
			$db_like[ $this->db->escape_str("b.Group_Name") ] = $keywords;
        }
		
		// get total records
		$this->db->from( $db_from );
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		$records_total = $this->db->count_all_results();
		
		// get total filtered
		$this->db
			->from( $db_from )
			->join("{$this->cash_flow_subgroup_m->table} b", "a.Group_Name = b.GroupI_Name", "LEFT OUTER")
			;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }		
		$records_filtered = $this->db->count_all_results();
		
		// get result filtered
		$db_select = <<<EOSQL
			a.Group_Name, 
			b.Group_Name AS Sub_Group_Name, 
			b.Group_id
EOSQL;

		$this->db
			->select( $db_select )
			->from( $db_from )
			->join("{$this->cash_flow_subgroup_m->table} b", "a.Group_Name = b.GroupI_Name", "LEFT OUTER")
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
					->order_by( $columns[intval($this->db->escape_str($sort_column))]['name'], $this->db->escape_str($sort_dir) );
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
	
	public function validate_account()
	{
		if($this->input->is_ajax_request())
		{
			$count_account = $this->db->where(['induk' => 0])
									->where_not_in("GroupAkunDetailID", "SELECT GroupAkunDetailID  FROM Mst_GroupAkunDetail", FALSE)
									->count_all_results($this->account_m->table);

			response_json(['response_status' => 'success', 'counted' => $count_account]);
		}
	}
	
	public function datatable_account_collection( $state=false )
    {
        $start = $this->input->get_post('start', true);
        $length = $this->input->get_post('length', true);
        $order = $this->input->get_post('order', true);
        $columns = $this->input->get_post('columns', true);
        $search = $this->input->get_post('search', true);
        $draw = $this->input->get_post('draw', true);
		
		$db_from = "{$this->account_m->table} a";
		$db_where = array();
		$db_like = array();
		
		$db_where['b.Cash'] = 0;
		$db_where['b.Bank'] = 0;
				
		// get total records
		$this->db->from( $db_from )	
				->join("Mst_GroupAkunDetail b", "a.GroupAkunDetailID = b.GroupAkunDetailId", "LEFT OUTER");
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		$records_total = $this->db->count_all_results();
		
		// get total filtered
		$this->db
			->from( $db_from )
			->join("Mst_GroupAkunDetail b", "a.GroupAkunDetailID = b.GroupAkunDetailId", "LEFT OUTER")
			
			;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }		
		$records_filtered = $this->db->count_all_results();
		
		// get result filtered
		$db_select = <<<EOSQL
			a.Akun_ID,
			a.Akun_No,
			a.Akun_Name,
			a.Induk,
			a.Level_Ke
EOSQL;

		$this->db
			->select( $db_select )
			->from( $db_from )
			->join("Mst_GroupAkunDetail b", "a.GroupAkunDetailID = b.GroupAkunDetailId", "LEFT OUTER")
			;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }		
		
		// ordering
        $this->db->order_by('a.Akun_No');
		
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
			$get_detail = $this->db->select("a.Group_Name, b.Normal_Pos, a.Group_id  ")
								->from("{$this->cash_flow_subgroup_m->table} a")
								->join("{$this->cash_flow_account_m->table} b", "a.Group_id = b.GroupII_id", "INNER")
								->where("b.Akun_id", $row->Akun_ID)
								->get()->result();
								
			$row->D = $row->K = $row->D_Name = $row->K_Name = '';
			foreach($get_detail as $val):
				$row->{$val->Normal_Pos} = $val->Group_id;
				$row->{$val->Normal_Pos.'_Name'} = $val->Group_Name;
			endforeach;
			
			$output['data'][] = $row;
		}
		
		$this->template
			->build_json( $output );
    }
	
	public function report()
	{
		if( $this->input->post() )
		{
			$this->load->helper( "export" );
			$this->load->helper( "cash_flow" );
			$period = $this->input->post('f[period]');
			
			switch ( $this->input->post("f[export_to]") ) :
				case "cash_flow_pdf" :
					$this->cash_flow_pdf();
				break;	
				case "cash_flow_excel" :			
					cash_flow_helper::export_cash_flow($period);
				break;
				case "cash_flow_detail_pdf" :
					$this->cash_flow_detail_pdf();
				break;	
				case "cash_flow_detail_excel" :
					cash_flow_helper::export_cash_flow_detail($period);
				break;
				case "cash_flow_transaction_pdf" :
					$this->cash_flow_transaction_pdf();
				break;	
				case "cash_flow_transaction_excel" :
					cash_flow_helper::export_cash_flow_transaction($period);
				break;
			endswitch;
		}
		
		$data = [
			"datepicker" => true,
			"form" => true,
		];
		
		$this->template
				->set( "heading", lang("cash_flow:report_heading"))
				->set_breadcrumb( lang("cash_flow:page"), base_url("general-ledger/cash-flow/report") )
				->set_breadcrumb( lang("cash_flow:report_heading") )
				->build('cash_flow/report', (isset($data) ? $data : NULL));
	}
	
	public function cash_flow_pdf()
	{
		if ($this->input->post())
		{
			$post_data = (object) $this->input->post("f") ;
			$collection = report_helper::get_recap_stocks($post_data->date_start, $post_data->date_end, $post_data->Lokasi_ID );	
			
			$data = [
				"post_data" => $post_data,	
				"collection" => $collection,
			];
						
			$html_content =  $this->load->view( "reports/recap_stocks/export/pdf", $data, TRUE ); 
			$footer = lang('reports:recap_stock_label')."&nbsp; : &nbsp;".date("d M Y")."&nbsp;".date("H:i:s");			
			$file_name = lang('reports:recap_stock_label');		
			
			export_helper::generate_pdf( $html_content, $file_name, $footer , $margin_bottom = 5, $header = NULL, $margin_top = 2, $orientation = 'L', $margin_left = 8, $margin_right = 8);
			exit(0);
		}		
		show_404();
	}
}

