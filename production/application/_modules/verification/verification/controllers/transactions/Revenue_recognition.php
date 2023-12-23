<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Revenue_recognition extends ADMIN_Controller
{
	protected $nameroutes;
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('verification');
		
		$this->data['nameroutes'] = $this->nameroutes = 'verification/transactions/revenue_recognition'; 
		
		$this->load->language('verification');		
		$this->load->library('verification');
		$this->load->helper('verification');
		
		$this->load->model('audit_model');
		$this->load->model('audit_revenue_model');
		$this->load->model('audit_journal_payment_model');
		$this->load->model('audit_detail_ar_model');
		$this->load->model('audit_detail_ap_model');
		$this->load->model('audit_honor_model');
		$this->load->model('audit_cost_model');
		$this->load->model('audit_coefficient_model');
		$this->load->model('cashier_discount_model');
		
		$this->load->model('cost_component_model');
		$this->load->model('merchan_model');
		
		$this->load->model('registration_model');
		$this->load->model('patient_model');
		$this->load->model('section_model');
		$this->load->model('type_cooperation_model');
		$this->load->model('user_model');
	}
	
	public function index()
	{
		$this->data['form_action'] = base_url("{$this->nameroutes}/process"); 
				
		$this->template
			->title(lang('heading:revenue_recognition'),lang('heading:transactions'))
			->set_breadcrumb(lang('heading:transactions'))
			->set_breadcrumb(lang('heading:revenue_recognition'), site_url($this->nameroutes))
			->build("transactions/revenue_recognition/form", $this->data);
	}
	
	public function process()
	{	
		if( $this->input->post() ) 
		{
			$this->_check_system_setup(); // Mengecek Konfigurasi pada Setup Awal
			
			$post_data = $this->input->post();		
				
			$this->load->library( 'form_validation' );
			$this->form_validation->set_rules( $this->audit_model->rules['process'] );
			$this->form_validation->set_data( $post_data );
			if( $this->form_validation->run() )
			{
				verification_helper::init();
				
				switch ( $post_data['case'] )
				{
					case 'inpatient':
						$_response = verification_helper::audit_inpatient( $post_data['date'] );
					break;
	
					case 'outpatient':
						$_response = verification_helper::audit_outpatient( $post_data['date'] );
					break;

					case 'otc_drug':
						$_response = verification_helper::audit_otc_drug( $post_data['date'] );
					break;

					case 'deposit':
						$_response = verification_helper::audit_deposit( $post_data['date'] );
					break;

					case 'outstanding':
						$_response = verification_helper::audit_outstanding( $post_data['date'] );
					break;

					case 'copayment':
					break;
				}
	
				/*
					State Of Progress:
					0 -> error : All data must be rollback
					1 -> success : All data must be commit
					2 -> unfinish : break transaction, commit all data before error, and update data status which error!
				*/			
				if ( $_response['state'] == 1 )
				{
					$response = [
						"status" => 'success',
						"message" => lang('message:revenue_recognition_successfully'),
						"code" => 200
					];
				} else {
					$response = [
						"status" => 'error',
						"message" => @$_response['message'] ? $_response['message'] : lang('message:revenue_recognition_failed'),
						"code" => 500
					];
				}

			} else
			{
				$response = [
						"status" => 'error',
						"message" => $this->form_validation->get_all_error_string(),
						"code" => 500
					];
			}
			
			response_json( $response );			
		}
	}
	
	private function _check_system_setup()
	{
		if( config_item('AkunNoBiayaInsentif') == '' )
		{
			response_json(['status' => 'error', 'message' => lang('message:account_incentive_fee')]);
		}
		
		if( config_item('AkunNoHutangInsentif') == '' )
		{
			response_json(['status' => 'error', 'message' => lang('message:accounts_payable_incentive')]);
		}
		
		if( config_item('AkunKelebihanDeposit') == '' )
		{
			response_json(['status' => 'error', 'message' => lang('message:account_excess_deposit')]);
		}
		
		if( config_item('TypePiutangBPJS') == 0 )
		{
			response_json(['status' => 'error', 'message' => lang('message:receivable_type_bpjs')]);
		}
		
		if( config_item('IDAkunPiutangBPJS') == 0 )
		{
			response_json(['status' => 'error', 'message' => lang('message:account_receivable_bpjs')]);
		}
		
		if( config_item('TypePiutangLOG') == 0 )
		{
			response_json(['status' => 'error', 'message' => lang('message:receivable_type_log')]);
		}
		
		if( config_item('IDAkunPiutangLOG') == 0 )
		{
			response_json(['status' => 'error', 'message' => lang('message:account_receivable_log')]);
		}
	}
	
	public function view()
	{

		$this->template
			->title(lang('heading:revenue_recognition'),lang('heading:transactions'))
			->set_breadcrumb(lang('heading:transactions'))
			->set_breadcrumb(lang('heading:revenue_recognition_list'), site_url("{$this->nameroutes}/view"))
			->build("transactions/revenue_recognition/index", $this->data);
	}
	
	/*
		@params
		(String) id -> NoBukti Audit
	*/
	public function update($id = 0)
	{
		$this->data['item'] = $item = $this->audit_model->get_one( $id );
		
		$this->data["view_detail_revenue"] = $this->_view_detail_revenue( $item );
		$this->data["view_detail_cash"] = $this->_view_detail_cash( $item );
		$this->data["view_detail_receivable"] = $this->_view_detail_receivable( $item );
		$this->data["view_detail_honor"] = $this->_view_detail_honor( $item );
		$this->data["view_detail_discount"] = $this->_view_detail_discount( $item );
		$this->data["view_detail_cost"] = $this->_view_detail_cost( $item );
		$this->data["view_detail_coefficient"] = $this->_view_detail_coefficient( $item );
		$this->data["form_actions"] = current_url();
		$this->data["cancel_audit_url"] = base_url("{$this->nameroutes}/cancel_audit/{$id}");
		$this->data["cancel_posting_url"] = base_url("{$this->nameroutes}/cancel_posting/{$id}");
		$this->data["form"] = TRUE;
		$this->data["datatables"] = TRUE;
		
		if( $this->input->is_ajax_request() )
		{
			$this->data["is_modal"] = TRUE;
			$this->load->view("transactions/revenue_recognition/form/update", $this->data);
		} else {
		
			$this->template
				->title(lang('heading:revenue_recognition_view'),lang('heading:transactions'))
				->set_breadcrumb(lang('heading:transactions'))
				->set_breadcrumb(lang('heading:revenue_recognition_list'), site_url("{$this->nameroutes}/view"))
				->set_breadcrumb(lang('heading:revenue_recognition_view'))
				->build("transactions/revenue_recognition/form/update", $this->data);
		}
	}
	
	private function _view_detail_revenue( $item = NULL)
	{
		$data = array(
				"collection" => $this->audit_revenue_model->get_collection( $item->NoBukti, $item->PostingKeBackOffice),
			);
			
		return $this->load->view( 'transactions/revenue_recognition/form/detail_revenue', $data, TRUE );		
	}
	
	private function _view_detail_cash( $item = NULL)
	{
		$data = array(
				"collection" => $this->audit_journal_payment_model->get_collection( $item->NoBukti, $item->PostingKeBackOffice),
			);
		
		return $this->load->view( 'transactions/revenue_recognition/form/detail_cash', $data, TRUE );		
	}
	
	private function _view_detail_receivable( $item = NULL)
	{
		$data = array(
				"collection" => $this->audit_detail_ar_model->get_collection( $item->NoBukti, $item->PostingKeBackOffice),
			);
		
		return $this->load->view( 'transactions/revenue_recognition/form/detail_receivable', $data, TRUE );		
	}
	
	private function _view_detail_honor( $item = NULL)
	{
		$data = array(
				"collection" => $this->audit_detail_ap_model->get_collection( $item->NoBukti),
			);
		
		return $this->load->view( 'transactions/revenue_recognition/form/detail_honor', $data, TRUE );		
	}
	
	private function _view_detail_discount( $item = NULL)
	{
		$data = array(
				"collection" => $this->cashier_discount_model->get_collection( $item->NoInvoice, $item->PostingKeBackOffice),
			);
			
		return $this->load->view( 'transactions/revenue_recognition/form/detail_discount', $data, TRUE );		
	}

	private function _view_detail_cost( $item = NULL)
	{
		$data = array(
				"collection" => $this->audit_cost_model->get_collection( $item->NoBukti, $item->PostingKeBackOffice),
			);
			
		return $this->load->view( 'transactions/revenue_recognition/form/detail_cost', $data, TRUE );		
	}
	
	private function _view_detail_coefficient( $item = NULL)
	{
		$data = array(
				"collection" => $this->audit_coefficient_model->get_collection( $item->NoBukti, $item->PostingKeBackOffice),
			);
			
		return $this->load->view( 'transactions/revenue_recognition/form/detail_coefficient', $data, TRUE );		
	}
		
	public function cancel_audit($id = 0)
	{
		$this->data['item'] = $item = $this->audit_model->get_one($id);
		
		if ($item  && (1 == $this->input->post('confirm') ) )
		{ 
			$id_split = strpos($item->NoBukti, '-SPLIT') 
						? str_replace('-SPLIT', '', $item->NoBukti) : $item->NoBukti.'-SPLIT';
			
			$item_split = $this->audit_model->get_one($id_split);
			if(!empty($item_split))
			{
				if ( $item->Posting == 1 || $item_split->Posting == 1 )
				{
					response_json( ["status" => 'error', "message" => lang('message:data_split_already_posted'), 'response' => 200] );
				}						
			} else {
				if ( $item->Posting == 1 )
				{
					response_json( ["status" => 'error', "message" => lang('message:data_already_posted'), 'response' => 200] );
				}			
			}

						
			verification_helper::init();
			$response = verification_helper::cancel_audit( $item, $item_split );
			
			if( $response === FALSE )
			{
				response_json([
					"status" => 'error', 
					"message" => $response['message'] ? $response['message'] : lang('message:cancel_audit_failed'), 
					'success' => FALSE
				]);		
			}

			response_json( ["status" => 'success', "message" => lang('message:cancel_audit_successfully'), 'success' => TRUE] );
		} 
		
		$this->data['form_action'] = current_url();
		$this->load->view('transactions/revenue_recognition/modal/cancel', $this->data);
	}
	
	public function cancel_posting($id = 0)
	{
		$this->data['item'] = $item = $this->audit_model->get_one($id);
		
		if ($item  && (1 == $this->input->post('confirm') ) )
		{ 
			$approver = $this->input->post("approver");
			$this->load->helper("Approval");
			if ( Approval_helper::approve( 'VERIFIKATOR CANCEL POSTING', $approver['username'], $approver['password'] ) === FALSE )
			{
				$response["message"] = lang('auth_incorrect');
				$response["status"] = "error";
				$response["code"] = "500";				
				
				response_json($response);
			}
			
			verification_helper::init();
			
			if( verification_helper::cancel_posting( $item ) === FALSE )
			{
				response_json( ["status" => 'error', "message" => lang('global:cancel_failed'), 'success' => FALSE] );		
			}

			response_json( ["status" => 'success', "message" => lang('global:cancel_successfully'), 'success' => TRUE] );
		} 
		
		$this->data['form_action'] = current_url();
		$this->load->view('transactions/revenue_recognition/modal/cancel_posting', $this->data);
	}
	
	public function lookup_collection()
	{
		$this->datatable_collection();
	}
	
	public function datatable_collection( )
    {

        $start = $this->input->get_post('start', true);
        $length = $this->input->get_post('length', true);
        $order = $this->input->get_post('order', true);
        $columns = $this->input->get_post('columns', true);
        $search = $this->input->get_post('search', true);
        $draw = $this->input->get_post('draw', true);
		
		$db_from = "{$this->audit_model->table} a";
		$db_where = array();
		$db_like = array();
		
		if ( $this->input->post("group") )
		{
			$db_where['a.Kelompok'] = $this->input->post("group");
		}
				
		if ($this->input->post("date_from"))
		{
			$db_where['a.Tanggal >='] = $this->input->post("date_from");
		}

		if ($this->input->post("date_till"))
		{
			$db_where['a.Tanggal <='] = $this->input->post("date_till");
		}
				
		// preparing default
		if( isset($search['value']) && ! empty($search['value']) )
        {
            $keywords = $this->db->escape_str( $search['value'] );
						
			$db_like[ $this->db->escape_str("a.NoBukti") ] = $keywords;
			$db_like[ $this->db->escape_str("a.NoInvoice") ] = $keywords;
			$db_like[ $this->db->escape_str("b.NRM") ] = $keywords;
			$db_like[ $this->db->escape_str("c.NamaPasien") ] = $keywords;
			$db_like[ $this->db->escape_str("d.JenisKerjasama") ] = $keywords;
        }
		
		// get total records
		$this->db->from( $db_from );
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		$records_total = $this->db->count_all_results();
		
		// get total filtered
		$this->db
			->from( $db_from )
			->join( "{$this->registration_model->table} b", "a.NoReg = b.NoReg", "LEFT OUTER" )
			->join( "{$this->patient_model->table} c", "b.NRM = c.NRM", "LEFT OUTER" )
			->join( "{$this->type_cooperation_model->table} d", "b.JenisKerjasamaID = d.JenisKerjasamaID", "LEFT OUTER" )
			;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }		
		$records_filtered = $this->db->count_all_results();
		
		// get result filtered
		$db_select = <<<EOSQL
			a.NoBukti, 
			a.NoInvoice,
			a.TglTransaksi, 
			a.Jam,
			a.Catatan,
			a.Posting,
			a.Batal,
			b.NRM,
			b.NoReg,
			c.NamaPasien,
			d.JenisKerjasama
EOSQL;

		$this->db
			->select( $db_select )
			->from( $db_from )
			->join( "{$this->registration_model->table} b", "a.NoReg = b.NoReg", "LEFT OUTER" )
			->join( "{$this->patient_model->table} c", "b.NRM = c.NRM", "LEFT OUTER" )
			->join( "{$this->type_cooperation_model->table} d", "b.JenisKerjasamaID = d.JenisKerjasamaID", "LEFT OUTER" )
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
			$row->TglTransaksi = substr($row->TglTransaksi, 0, 10);
            $output['data'][] = $row;
        }
		
		$this->template
			->build_json( $output );		
    }	
}

