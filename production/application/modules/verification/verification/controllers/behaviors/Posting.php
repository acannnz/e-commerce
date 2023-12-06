<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Posting extends ADMIN_Controller
{
	protected $nameroutes;
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('verification');
		
		$this->data['nameroutes'] = $this->nameroutes = 'verification/behaviors/posting'; 
		
		$this->load->language('verification');		
		$this->load->library('verification');
		$this->load->helper('posting');
		$this->load->helper('verification');
		
		$this->load->model('posting_model');
		$this->load->model('audit_model');

		$this->load->model('registration_model');
		$this->load->model('cashier_model');
		$this->load->model('bill_pharmacy_model');
		$this->load->model('otc_drug_model');
		$this->load->model('patient_model');
		$this->load->model('customer_model');
		$this->load->model('section_model');
	}
	
	public function index()
	{
		$this->data['form_action'] = base_url("{$this->nameroutes}/posting");
		$this->data['view_audit_url'] = base_url("verification/transactions/revenue_recognition/update");
		$this->data['cancel_audit_url'] = base_url("verification/transactions/revenue_recognition/cancel_audit");
		$this->data['option_doctor'] = option_doctor();
		
		$this->template
			->title(lang('heading:posting'),lang('heading:behaviors'))
			->set_breadcrumb(lang('heading:behaviors'))
			->set_breadcrumb(lang('heading:posting'), site_url($this->nameroutes))
			->build("behaviors/posting/index", $this->data);
	}
	
	public function posting()
	{				
		if( $this->input->post('confirm') == 1 ) 
		{	
			$data = $this->input->post();
					
			$posting_data = $this->input->post("selected");
			$validation = [
				'NoBukti' => $posting_data,
			];
			
			$response = array(
					"status" => "success",
					"message" => "",
					"code" => 200
				);
			
			$this->load->library( 'form_validation' );
			$this->form_validation->set_rules( $this->posting_model->rules['insert'] );
			$this->form_validation->set_data( $validation );
			if( $this->form_validation->run() )
			{
				if( empty($posting_data) )
				{
					response_json( ['status' => 'error', 'message' => lang('message:empty_data')] );	
				}
					
				if ( ! $posting_data = $this->posting_model->get_selected_posting_data( $posting_data ) )
				{
					response_json( ['status' => 'error', 'message' => lang('message:error_refresh_data')] );	
				}
				
				posting_helper::init();
				
				$response = posting_helper::posting( $posting_data );								
										
				if ( $response['state'] == 0 || $response['state'] == 2 )
				{
					$response = array(
						"status" => 'error',
						"message" => @$response['message'] ? @$response['message'] : lang('message:posting_failed'),
						"code" => 500
					);
				}
				else
				{
					$response = array(
						"status" => 'success',
						"message" => @$response['message'] ? @$response['message'] : lang('message:posting_successfully'),
						"code" => 200
					);
				}				

			} else
			{
				$response = array(
						"status" => 'error',
						"message" => $this->form_validation->get_all_error_string(),
						"code" => 500
					);
			}
			
			response_json( $response );			
		}
		
		$this->load->view("behaviors/posting/modal/confirm", $this->data);
	}
	
	public function cancel()
	{
		$this->data['form_action'] = base_url("{$this->nameroutes}/cancel_posting");
		$this->data['view_audit_url'] = base_url("verification/transactions/revenue_recognition/update");
		$this->data['option_doctor'] = option_doctor();
		$this->data['is_cancel'] = TRUE;
				
		$this->template
			->title(lang('heading:cancel_posting'),lang('heading:behaviors'))
			->set_breadcrumb(lang('heading:behaviors'))
			->set_breadcrumb(lang('heading:posting'), site_url($this->nameroutes))
			->set_breadcrumb(lang('heading:cancel_posting'), site_url("{$this->nameroutes}/cancel"))
			->build("behaviors/posting/index", $this->data);
	}
	
	public function cancel_posting()
	{		
		
		if( $this->input->post('confirm') == 1 ) 
		{
			$data = $this->input->post();
					
			$posting_data = $this->input->post("selected");
			$approver = $this->input->post("approver");
			$additional = $this->input->post("additional");
			
			$response = array(
					"status" => "success",
					"message" => "",
					"code" => 200
				);
				
			$approver = $this->input->post("approver");
			$this->load->helper("Approval");
			if ( Approval_helper::approve( 'VERIFIKATOR CANCEL POSTING', $approver['username'], $approver['password'] ) === FALSE )
			{
				$response["message"] = lang('auth_incorrect');
				$response["status"] = "error";
				$response["code"] = "500";				
				
				response_json($response);
			}
			
			$already_process = [];
			verification_helper::init();			
			if(!empty($posting_data)): foreach($posting_data as $id):
				$item = $this->audit_model->get_one($id);
				
				$id_split = strpos($id, '-SPLIT') ? str_replace('-SPLIT', '', $id) : $id .'-SPLIT';
				
				if(in_array($id, $already_process) || in_array($id, $already_process)) continue;
				
				$item_split = $this->audit_model->get_one($id_split);
				
				if( verification_helper::cancel_posting( $item, $item_split ) === FALSE )
				{
					response_json( ["status" => 'error', "message" => lang('global:cancel_failed'), 'success' => FALSE] );		
				}
				
				$already_process[] = $id;
				if(!empty($item_split)) $already_process[] = $id_split;
			endforeach; endif;

			response_json( ["status" => 'success', "message" => lang('global:cancel_successfully'), 'success' => TRUE] );
		}
		
		$this->load->view("behaviors/posting/modal/cancel", $this->data);
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
		
		// preparing default filter
		$db_where['a.Batal'] = 0;
		$db_where['a.Posting'] = 0;

		if ($this->input->post("is_cancel"))
		{
			$db_where['a.Posting'] = 1;
		}
		
		if ($this->input->post("date_from"))
		{			
			$db_where['a.TglTransaksi >='] = $this->input->post("date_from");
		}

		if ($this->input->post("date_till"))
		{
			$db_where['a.TglTransaksi <='] = $this->input->post("date_till");
		}
		
		if($this->input->post("doctor_id"))
		{
			$db_where_or['e.DokterID'] = $this->input->post("doctor_id");
			$db_where_or['g.DokterID'] = $this->input->post("doctor_id");
		}
		
		if ($this->input->post("group"))
		{
			$db_where['a.Kelompok'] = $this->input->post("group");
		}
				
		if( isset($search['value']) && ! empty($search['value']) )
        {
            $keywords = $this->db->escape_str( $search['value'] );
						
			$db_like[ $this->db->escape_str("a.NoBukti") ] = $keywords;
			$db_like[ $this->db->escape_str("a.Tanggal") ] = $keywords;
			$db_like[ $this->db->escape_str("a.NoInvoice") ] = $keywords;
			$db_like[ $this->db->escape_str("a.TglTransaksi") ] = $keywords;
			$db_like[ $this->db->escape_str("a.Catatan") ] = $keywords;
			$db_like[ $this->db->escape_str("c.NamaPasien") ] = $keywords;
			$db_like[ $this->db->escape_str("d.Nama_Asisting") ] = $keywords;
        }
		
		// get total records
		$this->db->from( $db_from );
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		$records_total = $this->db->count_all_results();
		
		// get total filtered
		$this->db
			->from( $db_from )
			->join( "{$this->registration_model->table} b", "a.NoReg = b.NoReg", "LEFT OUTER" )
			->join( "{$this->cashier_model->table} e", "a.NoInvoice = e.NoBukti", "LEFT OUTER" )
			->join( "{$this->otc_drug_model->table} f", "a.NoInvoice = f.NoBukti", "LEFT OUTER" )
			->join( "{$this->bill_pharmacy_model->table} g", "f.NoBuktiFarmasi = g.NoBukti", "LEFT OUTER" )
			->join( "{$this->patient_model->table} c", "b.NRM = c.NRM", "LEFT OUTER" )
			->join( "{$this->customer_model->table} d", "b.AssCompanyID_MA = d.Kode_Customer", "LEFT OUTER" );
			;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_where_or) ){ $this->db->group_start()->or_where( $db_where_or )->group_end(); }		
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }		
		$records_filtered = $this->db->count_all_results();
		
		// get result filtered
		$db_select = <<<EOSQL
			a.NoBukti,
			a.Tanggal,
			a.NoInvoice,
			a.TglTransaksi,
			a.Catatan,
			a.PostingKeBackOffice,
			c.NamaPasien,
			d.Nama_Customer AS Nama_Asisting,
			g.Keterangan
EOSQL;
				
		$this->db
			->select( $db_select )
			->from( $db_from ) 
			->join( "{$this->registration_model->table} b", "a.NoReg = b.NoReg", "LEFT OUTER" )
			->join( "{$this->cashier_model->table} e", "a.NoInvoice = e.NoBukti", "LEFT OUTER" )
			->join( "{$this->otc_drug_model->table} f", "a.NoInvoice = f.NoBukti", "LEFT OUTER" )
			->join( "{$this->bill_pharmacy_model->table} g", "f.NoBuktiFarmasi = g.NoBukti", "LEFT OUTER" )
			->join( "{$this->patient_model->table} c", "b.NRM = c.NRM", "LEFT OUTER" )
			->join( "{$this->customer_model->table} d", "b.AssCompanyID_MA = d.Kode_Customer", "LEFT OUTER" );
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_where_or) ){ $this->db->group_start()->or_where( $db_where_or )->group_end(); }		
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
			$row->Tanggal = substr($row->Tanggal, 0, 10);
			$row->TglTransaksi = substr($row->TglTransaksi, 0, 10);
			
            $output['data'][] = $row;
        }
		
		$this->template
			->build_json( $output );		
    }		
}