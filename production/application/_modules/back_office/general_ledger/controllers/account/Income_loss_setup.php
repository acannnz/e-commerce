<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Income_loss_setup extends Admin_Controller
{
	protected $_translation = 'general_ledger';	
	protected $_model = 'account/income_loss_setup_m';
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('general_ledger');
		
		$this->load->helper("general_ledger");
		
		$this->page = "general_ledger_income_loss_setup";
		$this->template->title( lang("income_loss_setup:page") . ' - ' . $this->config->item('company_name') );
	}
	
	public function index()
	{
		$data = array(
				'page' => $this->page,
				"form" => TRUE,
				'datatables' => TRUE,
			);
		
		$this->template
			->set( "heading", lang("income_loss_setup:page") )
			->set_breadcrumb( lang("general_ledger:page"), base_url("general-ledger") )
			->set_breadcrumb( lang("income_loss_setup:breadcrumb") )
			->build('accounts/income_loss_setup/datatable', (isset($data) ? $data : NULL));
	}
	
	public function setup()
	{
		$item = [
			$this->get_model()->primary_key => 0,
			'Akun_No' => NULL,
			'Akun_Name' => NULL,
			'Type_Akun' => NULL,
		];
						
		if( $this->input->post() ) 
		{
			$this->load->library( 'form_validation' );
			
			$post_data = array_merge($item, $this->input->post("f"));
			$this->form_validation->set_rules( $this->get_model()->rules['insert'] );
			$this->form_validation->set_data( $post_data );

			if( $this->form_validation->run() )
			{
				if( $this->get_model()->setup_data( $post_data ) )
				{					
					make_flashdata(array(
							'response_status' => 'success',
							'message' => lang('global:updated_successfully')
						));
						
					redirect( 'general-ledger/account/income-loss-setup' );
				} else
				{
					make_flashdata(array(
							'response_status' => 'error',
							'message' => lang('global:updated_failed')
						));
				}
			} else
			{
				make_flashdata(array(
						'response_status' => 'error',
						'message' => $this->form_validation->get_all_error_string()
					));
			}
		}
		
		$data = array(
				"page" => $this->page,
				"item" => (object) $item,
				"option_income_loss" => $this->get_model()->get_option_income_loss(),
				"form" => TRUE,
				"datatables" => TRUE,
				"lookup_accounts" => base_url("general-ledger/account/income-loss-setup/lookup_accounts")
			);

		$this->template
			->set( "heading", lang("income_loss_setup:setup_heading") )
			->set_breadcrumb( lang("general_ledger:page"), base_url("general-ledger") )
			->set_breadcrumb( lang("income_loss_setup:breadcrumb"), base_url("general-ledger/account/income-loss-setup") )
			->set_breadcrumb( lang("income_loss_setup:setup_heading") )
			->build('accounts/income_loss_setup/form', $data);
	
	}

	public function edit( $id = 0 )
	{
		$id = (int) @$id;
		
		$item = $this->get_model()->get_row( $id );					
		if( $this->input->post() ) 
		{			
			$this->load->library( 'form_validation' );
			
			$post_data = array_merge($item, $this->input->post("f"));
			$this->form_validation->set_rules( $this->get_model()->rules['insert'] );
			$this->form_validation->set_data( $post_data );
			
			if( $this->form_validation->run() )
			{
				if( $this->get_model()->setup_data( $post_data ) )
				{					
					make_flashdata(array(
							'response_status' => 'success',
							'message' => lang('global:updated_successfully')
						));
						
					redirect( 'general-ledger/account/income-loss-setup' );
				} else
				{
					make_flashdata(array(
							'response_status' => 'error',
							'message' => lang('global:updated_failed')
						));
				}
			} else
			{
				make_flashdata(array(
						'response_status' => 'error',
						'message' => $this->form_validation->get_all_error_string()
					));
			}
		}

		$data = array(
				"page" => $this->page,
				"item" => (object) $item,
				"option_income_loss" => $this->get_model()->get_option_income_loss(),
				"is_edit" => TRUE,
				"form" => TRUE,
				"datatables" => TRUE,
				"lookup_accounts" => base_url("general-ledger/account/income-loss-setup/lookup_accounts")
			);

		$this->template
			->set( "heading", lang("income_loss_setup:edit_heading") )
			->set_breadcrumb( lang("general_ledger:page"), base_url("general-ledger") )
			->set_breadcrumb( lang("income_loss_setup:breadcrumb"), base_url("general-ledger/account/income-loss-setup") )
			->set_breadcrumb( lang("income_loss_setup:edit_heading") )
			->build('accounts/income_loss_setup/form', $data);
	
	}
		
	public function delete( $id=0 )
	{
		$id = (int) @$id;
		
		$item = $this->get_model()->get_row( $id );
		
		if( $this->input->post() ) 
		{
			if( 0 == @$item[$this->get_model()->primary_key] )
			{
				make_flashdata(array(
						'response_status' => 'error',
						'message' => lang( 'global:get_failed' )
					));
			
				redirect( $this->input->post( 'r_url' ) );
			}
			
			if( $item[$this->get_model()->primary_key] == $this->input->post( 'confirm' ) )
			{
				$post_data = $this->input->post("f");
				$this->get_model()->setup_data( $post_data );
				
				make_flashdata(array(
						'response_status' => 'success',
						'message' => lang('global:deleted_successfully')
					));
			}
			
			redirect( $this->input->post( 'r_url' ) );
		}
		
		$data = array('item' => (object)$item);
		
		$this->load->view('accounts/income_loss_setup/modal/delete', $data );
	}

	public function lookup_accounts(  ){
	
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'accounts/income_loss_setup/lookup/accounts', array() );
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
		
		// prepare defautl flter
		$db_where['Type_Akun !='] = 0;
		
		// preparing default
		if( isset($search['value']) && ! empty($search['value']) )
        {
            $keywords = $this->db->escape_str( $search['value'] );
						
			$db_like[ $this->db->escape_str("a.Akun_No") ] = $keywords;			
			$db_like[ $this->db->escape_str("a.Akun_Name") ] = $keywords;
			$db_like[ $this->db->escape_str("b.Keterangan") ] = $keywords;
        }
		
		// get total records
		$this->db->from( $db_from )
				->join("Setup_LabaRugi b", "a.Type_Akun=b.ID", "INNER")
				;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		$records_total = $this->db->count_all_results();
		
		// get total filtered
		$this->db
			->from( $db_from )
			->join("Setup_LabaRugi b", "a.Type_Akun=b.ID", "INNER")
			;
			
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }		
		$records_filtered = $this->db->count_all_results();
		
		// get result filtered
		$db_select = <<<EOSQL
			a.*,
			b.Keterangan,
EOSQL;

		$this->db
			->select( $db_select )
			->from( $db_from )
			->join("Setup_LabaRugi b", "a.Type_Akun=b.ID", "INNER")
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

		$this->template
			->build_json( $output );
    }


	
}

