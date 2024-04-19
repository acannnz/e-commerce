<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Posting extends ADMIN_Controller
{
	protected $nameroutes;
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('cashier');
		
		$this->data['nameroutes'] = $this->nameroutes = 'cashier/posting'; 
		
		//$this->load->language('posting');		
		//$this->load->helper('posting');
		
		//$this->load->model('posting_model');
		
		$this->load->model('non_invoice_cash_expense_m');
		$this->load->model('non_invoice_receipt_m');
		$this->load->model('petty_cash_m');
		$this->load->model('cashier_model');
	}
	
	public function index()
	{
		$this->data['form_action'] = base_url("{$this->nameroutes}/posting");
		$this->data['form'] = TRUE;
		$this->data['datatables'] = TRUE;
		
		$this->template
			->title(lang('heading:posting'),lang('heading:cashier'))
			->set_breadcrumb(lang('heading:cashier'))
			->set_breadcrumb(lang('heading:posting'), site_url($this->nameroutes))
			->build("posting/index", $this->data);
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
				
		if($this->input->post('date_from'))
		{
			$date_from = DateTime::createFromFormat('Y-m-d', $this->input->post('date_from'))->setTime(0,0);
			$date_from->modify('+8 hour');
		}
	
		if($this->input->post('date_till'))
		{
			$date_till = DateTime::createFromFormat('Y-m-d', $this->input->post('date_till'))->setTime(0,0);
			$date_till->modify('+1 day');
			$date_till->modify('+8 hour');
		}
				
		$get_petty_cash = $this->db->select("
									NoBukti, Tanggal, NoBukti as NoInvoice, Tanggal as TglTransaksi, 
									'' AS Nama_Asisting, '' AS namapasien, (Kredit - Debet) AS Nilai,
									Deskripsi AS Keterangan, 'PETTY CASH' AS Tipe  
								")
								->from($this->petty_cash_m->table)
								->where(['Jam >= ' => $date_from->format('Y-m-d H:i:s'), 'Jam <=' => $date_till->format('Y-m-d H:i:s'), 'Batal' => 0 , 'Posted' => 0])
								->get_compiled_select();
		
		$get_pnp = $this->db->select("
									NoBukti, Tanggal, NoBukti AS NoInvoice, Tanggal AS TglTransaksi,
									'' AS Nama_Asisting, '' AS namapasien, Nilai, Keterangan, 'PNP' AS Tipe 
								")
								->from($this->non_invoice_receipt_m->table)
								->where(['Jam >= ' => $date_from->format('Y-m-d H:i:s'), 'Jam <=' => $date_till->format('Y-m-d H:i:s'), 'Batal' => 0 , 'Posting' => 0])
								->get_compiled_select();		
		
		$result = $this->db->query("{$get_petty_cash} UNION {$get_pnp}")->result();
							
        // Output
        $output = array(
				'draw' => intval($draw),
				'recordsTotal' => count($result),
				'recordsFiltered' => count($result),
				'data' => []
			);
			
        foreach($result as $row)
        {				
            $output['data'][] = $row;
        }
		
		$this->template
			->build_json( $output );		
    }		
}