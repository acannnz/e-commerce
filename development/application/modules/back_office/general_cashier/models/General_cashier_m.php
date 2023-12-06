<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class General_cashier_m extends Public_Model
{
	public $table = 'gc_general_cashier';
	public $primary_key = 'id';
	
	public $rules;
	
	public function __construct()
	{
		$this->rules = array('insert' => array(
				'house_id' => array(
						'field' => 'house_id',
						'label' => lang( 'general_cashier:house_label' ),
						'rules' => 'required'
					),
				'transaction_type' => array(
						'field' => 'transaction_type',
						'label' => lang( 'types:transaction_type_label' ),
						'rules' => 'required'
					),
				'evidence_number' => array(
						'field' => 'evidence_number',
						'label' => lang( 'general_cashier:evidence_number_label' ),
						'rules' => 'required'
					),
				'account_id' => array(
						'field' => 'account_id',
						'label' => lang( 'general_cashier:account_label' ),
						'rules' => 'required'
					),
				'transaction_date' => array(
						'field' => 'transaction_date',
						'label' => lang( 'general_cashier:transaction_date_label' ),
						'rules' => 'required'
					),
				'debit' => array(
						'field' => 'debit',
						'label' => lang( 'general_cashier:debit_label' ),
						'rules' => 'required'
					),
				'credit' => array(
						'field' => 'credit',
						'label' => lang( 'general_cashier:credit_label' ),
						'rules' => 'required'
					),
			));
		
		parent::__construct();
	}
	
	public function find_general_cashier_list( $options = array())
	{
		$this->db->order_by( 'general_cashier_name', 'asc' );
		$this->db->where( $options );
		
		$query = $this->db->get( $this->table );
		
		$data  = array();

		if( $query->num_rows() > 0 )
		{
			foreach( $query->result_object() as $row )
			{
				$data[ $row->id ] = $row->general_cashier_name;
			} //$query->result_array() as $row
		} //$query->num_rows() > 0
		
		return $data;
	}	

	public function find_currency_list( $options = array())
	{
		$this->db->order_by( 'code', 'asc' );
		$this->db->where( $options );
		
		$query = $this->db->get( "currencies" );
		
		$data  = array();

		if( $query->num_rows() > 0 )
		{
			foreach( $query->result_object() as $row )
			{
				$data[ $row->code ] = $row->symbol;
			} //$query->result_array() as $row
		} //$query->num_rows() > 0
		
		return $data;
	}	
		
	public function get_before_balance( $general_cashier_id = 0, $date )
	{
		$month = date('m', strtotime(" {$date} first day of -1 month")); 
		$year = date('Y', strtotime(" {$date} first day of -1 month")); 
		
		
		$query =  $this->db->where( array("general_cashier_id" => $general_cashier_id, "month" => $month, "year" => $year ) )
				->get("gl_monthly_posted")
				;
				
		if ( $query->num_rows() > 0 )
		{
			return $query->row();
		}		
	
	}

	public function get_general_cashier( $where = NULL )
	{
		if ( !is_array( $where ) ) return false;
		
		$query = $this->db->where( $where )
						->get( $this->table )
						;
						
		if ($query->num_rows() > 0)
		{
			return $query->row();
		}
		
		return false;
	}
	
	// Mencari data voucher atau invoice
	private function get_header( $header )
	{	
		if ( $header['integration_source'] == "AP" )	
		{
			$query = $this->db->where( "voucher_number", key($header['headers']) )
							->get("ap_vouchers")
							;
		} elseif( $header['integration_source'] == "AR" ){
			$query = $this->db->where( "invoice_number", key($header['headers']) )
							->get("ar_invoices")
							;
		}
		return $query->row();
		
	}
	
	// Mencari nilai sisa/remain dari faktur
	private function get_factur( $factur, $integration_source )
	{	
		$this->db->where( "factur_number", $factur['factur_number'] );
		
		if ( $integration_source == "AP" )	
		{
			$query = $this->db->get("ap_facturs")
							;
		} elseif( $integration_source == "AR" ){
			$query = $this->db->get("ar_facturs")
							;
		}
								
		return $query->row();
		
	}	
	
	public function process_transaction_general_cashier( $general_cashier, $credit_data, $debit_data )
	{
		// Menyiapkan data General cashier, JIkA type transaksi berbeda
		if ( in_array($general_cashier['transaction_type'], array("BBK", "BKK", "MUT")) )
		{
			unset($general_cashier['debit']);
			$opposite_detail = "debit";
			$general_cashier['account_id'] = key($credit_data);
			
		} else if(in_array($general_cashier['transaction_type'], array("BBM", "BKM")))
		{
			unset($general_cashier['credit']);
			$opposite_detail = "credit";
			$general_cashier['account_id'] = key($debit_data);
		}			

		
		$this->db->trans_start();
			
			// masukan data general_cashier ke tabel ap_general_cashier
			//$this->db->insert( "gc_general_cashier", $general_cashier );
			//$insert_id = $this->db->insert_id();
			
			//Memasukan data details dan details factur
			$general_cashier_details = array();
			$general_cashier_detail_facturs = array();
			
			$update_payable_voucher = array();
			$update_payable_factur = array();
			$payable_card = array();
			
			$update_receivable_invoice = array();
			$update_receivable_factur = array();
			$receivable_card = array();

			if ( $opposite_detail == "debit"): // opposite_detail adalah lawan dari header general cashier

				foreach( $debit_data as $k_debit => $v_debit):
					
					if ( $v_debit['integration_source'] == "AP" || $v_debit['integration_source'] == "AR"):
						// Menyiapkan data general_cashier_details					
						
						foreach( $v_debit['headers'] as $k_header => $v_header):
							$general_cashier_details[] = array(
										"house_id" => $this->_house_id,
										"evidence_number" => $general_cashier['evidence_number'],
										"referrer_number" => $v_debit['integration_source']== "AP" ?  $v_header['voucher_number'] : $v_header['invoice_number'],
										"account_id" => $v_debit['id'],
										"original_value" => $v_header['original_value'],
										"debit" => $v_header['value'],
										"credit" => 0.00,
										"description" => $v_header['description'],											
										'state' => 1,
										'created_at' => time(),
										'created_by' => $this->user,
										'updated_at' => time(),
										'updated_by' => $this->user,
									);
								
								// Update data voucher & invoice
								if ( $v_debit['integration_source']== "AP" ):
									
									$voucher = $this->get_header( $v_debit );
									$remain = $voucher->remain - $v_header['value'];
									
									$update_payable_voucher[] = array(
											"voucher_number" => $v_header['voucher_number'],
											"remain" => $remain,
											'updated_at' => time(),
											'updated_by' => $this->user,
										);
									$payable_card[] = array(
											"evidence_number" => $general_cashier['evidence_number'],
											"voucher_number" => $v_header['vouhcer_number'],
											"factur_number" => $v_header['vouhcer_number'], 
											"date" => $general_cashier['transaction_date'],
											"type_id" => $voucher->type_id,
											"supplier_id" => $voucher->supplier_id,
											"debit" => $v_header['value'],
											"description" => $general_cashier['description'],
											'state' => 1,
											'created_at' => time(),
											'created_by' => $this->user,
											'updated_at' => time(),
											'updated_by' => $this->user,
										);
								elseif ( $v_debit['integration_source']== "AR" ):
									$invoice = $this->get_header( $v_debit );
									$remain = $invoice->remain - $v_header['value'];
									
									$update_receivable_invoice[] = array(
											"invoice_number" => $v_header['invoice_number'],
											"remain" => $remain,
											'updated_at' => time(),
											'updated_by' => $this->user,
										);
									$receivable_card[] = array(
											"evidence_number" => $general_cashier['evidence_number'],
											"invoice_number" => $v_header['invoice_number'],
											"factur_number" => $v_header['invoice_number'], 
											"date" => $general_cashier['transaction_date'],
											"type_id" => $invoice->type_id,
											"customer_id" => $invoice->customer_id,
											"debit" => $v_header['value'],
											"description" => $general_cashier['description'],
											'state' => 1,
											'created_at' => time(),
											'created_by' => $this->user,
											'updated_at' => time(),
											'updated_by' => $this->user,
										);
								endif;
							// Menyiapkan data general_cashier_details_facturs
							foreach( $v_header['details'] as $k_detail => $v_detail):
							
								$general_cashier_detail_facturs[] = array(
											"house_id" => $this->_house_id,
											"evidence_number" => $general_cashier['evidence_number'],
											"referrer_number" =>$v_debit['integration_source']== "AP" ?  $v_header['voucher_number'] : $v_header['invoice_number'],
											"factur_number" => $v_detail['factur_number'],
											"account_id" => $v_debit['id'],
											"remain" => $v_detail['remain'],
											"paid" => $v_detail['value'],
											'state' => 1,
											'created_at' => time(),
											'created_by' => $this->user,
											'updated_at' => time(),
											'updated_by' => $this->user,
										); 
								// Update data voucher & invoice
								if ( $v_debit['integration_source']== "AP" ):
									
									$factur = $this->get_factur( $v_detail, "AP" );
									$remain = $factur->remain - $v_detail['value'];
									
									$update_payable_factur[] = array(
											"factur_number" => $v_detail['factur_number'],
											"remain" => $remain,
											'updated_at' => time(),
											'updated_by' => $this->user,
										);
									$payable_card[] = array(
											"evidence_number" => $general_cashier['evidence_number'],
											"voucher_number" => $v_header['vouhcer_number'],											
											"factur_number" => $v_detail['factur_number'],
											"date" => $general_cashier['transaction_date'],
											"type_id" => $factur->type_id,
											"supplier_id" => $factur->supplier_id,
											"debit" => $v_detail['value'],
											"description" => $general_cashier['description'],
											'state' => 1,
											'created_at' => time(),
											'created_by' => $this->user,
											'updated_at' => time(),
											'updated_by' => $this->user,
										);
								elseif ( $v_debit['integration_source']== "AR" ):
									$factur = $this->get_factur( $v_detail, "AR" );
									$remain = $factur->remain - $v_header['value'];
									
									$update_receivable_factur[] = array(
											"factur_number" => $v_detail['factur_number'],
											"remain" => $remain,
											'updated_at' => time(),
											'updated_by' => $this->user,
										);
									$receivable_card[] = array(
											"evidence_number" => $general_cashier['evidence_number'],
											"invoice_number" => $v_header['invoice_number'],											
											"factur_number" => $v_detail['factur_number'],
											"date" => $general_cashier['transaction_date'],
											"type_id" => $factur->type_id,
											"customer_id" => $factur->customer_id,
											"debit" => $v_detail['value'],
											"description" => $general_cashier['description'],
											'state' => 1,
											'created_at' => time(),
											'created_by' => $this->user,
											'updated_at' => time(),
											'updated_by' => $this->user,
										);
								endif;
																										
							endforeach;
							
							
						endforeach;
					else:
						$general_cashier_details[] = array(
									"house_id" => $this->_house_id,
									"evidence_number" => $general_cashier['evidence_number'],
									"account_id" => $v_debit['id'],
									"original_value" => $v_debit['value'],
									"debit" => $v_debit['value'],
									"credit" => 0.00,
									"description" => $v_debit['description'],											
									'state' => 1,
									'created_at' => time(),
									'created_by' => $this->user,
									'updated_at' => time(),
									'updated_by' => $this->user,
								);
					endif;
				endforeach;
				
			elseif ( $opposite_detail == "credit"):

				foreach( $credit_data as $k_credit => $v_credit):
					
					if ( $v_credit['integration_source'] == "AP" || $v_credit['integration_source'] == "AR"):
						// Menyiapkan data general_cashier_details					
						foreach( $v_credit['headers'] as $k_header => $v_header):
							$general_cashier_details[] = array(
										"house_id" => $this->_house_id,
										"evidence_number" => $general_cashier['evidence_number'],
										"referrer_number" =>$v_credit['integration_source']== "AP" ?  $v_header['voucher_number'] : $v_header['invoice_number'],
										"account_id" => $v_credit['id'],
										"original_value" => $v_header['original_value'],
										"debit" => 0.00,
										"credit" => $v_header['value'],
										"description" => $v_header['description'],											
										'state' => 1,
										'created_at' => time(),
										'created_by' => $this->user,
										'updated_at' => time(),
										'updated_by' => $this->user,
									);

								// Update data voucher & invoice
								if ( $v_credit['integration_source']== "AP" ):
									
									$voucher = $this->get_header( $v_credit );
									$remain = $voucher->remain - $v_header['value'];
									
									$update_payable_voucher[] = array(
											"voucher_number" => $v_header['voucher_number'],
											"remain" => $remain,
											'updated_at' => time(),
											'updated_by' => $this->user,
										);
									$payable_card[] = array(
											"evidence_number" => $general_cashier['evidence_number'],
											"voucher_number" => $v_header['vouhcer_number'],
											"factur_number" => $v_header['vouhcer_number'], 
											"date" => $general_cashier['transaction_date'],
											"type_id" => $voucher->type_id,
											"supplier_id" => $voucher->supplier_id,
											"credit" => $v_header['value'],
											"description" => $general_cashier['description'],
											'state' => 1,
											'created_at' => time(),
											'created_by' => $this->user,
											'updated_at' => time(),
											'updated_by' => $this->user,
										);
										
								elseif ( $v_credit['integration_source']== "AR" ):
								
									$invoice = $this->get_header( $v_credit );
									$remain = $invoice->remain - $v_header['value'];
									
									$update_receivable_invoice[] = array(
											"invoice_number" => $v_header['invoice_number'],
											"remain" => $remain,
											'updated_at' => time(),
											'updated_by' => $this->user,
										);
									$receivable_card[] = array(
											"evidence_number" => $general_cashier['evidence_number'],
											"invoice_number" => $v_header['invoice_number'],
											"factur_number" => $v_header['invoice_number'], 
											"date" => $general_cashier['transaction_date'],
											"type_id" => $invoice->type_id,
											"customer_id" => $invoice->customer_id,
											"credit" => $v_header['value'],
											"description" => $general_cashier['description'],
											'state' => 1,
											'created_at' => time(),
											'created_by' => $this->user,
											'updated_at' => time(),
											'updated_by' => $this->user,
										);
								endif;
								
							// Menyiapkan data general_cashier_details_facturs
							foreach( $v_header['details'] as $k_detail => $v_detail):
							
								$general_cashier_detail_facturs[] = array(
											"house_id" => $this->_house_id,
											"evidence_number" => $general_cashier['evidence_number'],
											"referrer_number" =>$v_credit['integration_source']== "AP" ?  $v_header['voucher_number'] : $v_header['invoice_number'],
											"factur_number" => $v_detail['factur_number'],
											"account_id" => $v_credit['id'],
											"remain" => $v_detail['remain'],
											"paid" => $v_detail['value'],
											'state' => 1,
											'created_at' => time(),
											'created_by' => $this->user,
											'updated_at' => time(),
											'updated_by' => $this->user,
										);

								// Update data voucher & invoice
								if ( $v_credit['integration_source']== "AP" ):
									
									$factur = $this->get_factur( $v_detail, "AP" );
									$remain = $factur->remain - $v_detail['value'];
									
									$update_payable_factur[] = array(
											"factur_number" => $v_detail['factur_number'],
											"remain" => $remain,
											'updated_at' => time(),
											'updated_by' => $this->user,
										);
									$payable_card[] = array(
											"evidence_number" => $general_cashier['evidence_number'],
											"voucher_number" => $v_header['vouhcer_number'],											
											"factur_number" => $v_detail['factur_number'],
											"date" => $general_cashier['transaction_date'],
											"type_id" => $factur->type_id,
											"supplier_id" => $factur->supplier_id,
											"credit" => $v_detail['value'],
											"description" => $general_cashier['description'],
											'state' => 1,
											'created_at' => time(),
											'created_by' => $this->user,
											'updated_at' => time(),
											'updated_by' => $this->user,
										);
										
								elseif ( $v_credit['integration_source']== "AR" ):
								
									$factur = $this->get_factur( $v_detail, "AR" );
									$remain = $factur->remain - $v_header['value'];
									
									$update_receivable_factur[] = array(
											"factur_number" => $v_detail['factur_number'],
											"remain" => $remain,
											'updated_at' => time(),
											'updated_by' => $this->user,
										);
									$receivable_card[] = array(
											"evidence_number" => $general_cashier['evidence_number'],
											"invoice_number" => $v_header['invoice_number'],											
											"factur_number" => $v_detail['factur_number'],
											"date" => $general_cashier['transaction_date'],
											"type_id" => $factur->type_id,
											"customer_id" => $factur->customer_id,
											"credit" => $v_detail['value'],
											"description" => $general_cashier['description'],
											'state' => 1,
											'created_at' => time(),
											'created_by' => $this->user,
											'updated_at' => time(),
											'updated_by' => $this->user,
										);
								endif;
																										
							endforeach;
							
							
						endforeach;
					else:
						$general_cashier_details[] = array(
									"house_id" => $this->_house_id,
									"evidence_number" => $general_cashier['evidence_number'],
									"account_id" => $v_credit['id'],
									"original_value" => $v_credit['value'],
									"debit" => $v_credit['value'],
									"credit" => 0.00,
									"description" => $v_credit['description'],											
									'state' => 1,
									'created_at' => time(),
									'created_by' => $this->user,
									'updated_at' => time(),
									'updated_by' => $this->user,
								);
					endif;
				endforeach;
			
			endif;


			/*echo "general_cashier : "; print_r($general_cashier);
			echo "general_cashier_details : "; print_r($general_cashier_details);
			echo "general_cashier_detail_facturs : "; print_r($general_cashier_detail_facturs);

			echo "Voucher : "; print_r($update_payable_voucher);
			echo "Factur Voucher : "; print_r($update_payable_factur);
			echo "Kartu Hutang : "; print_r($payable_card);

			echo "Invoice : "; print_r($update_receivable_invoice);
			echo "factur invoice : "; print_r($update_receivable_factur);
			echo "kartu piutang : "; print_r($receivable_card);

			exit;*/
		
			
			// Masukan data gc_general_cashier
			$this->db->insert( "gc_general_cashiers", $general_cashier );
			$insert_id = $this->db->insert_id();
			// masukan data details general_cashier
			$this->db->insert_batch( "gc_general_cashier_details", $general_cashier_details);
			if ( !empty($general_cashier_detail_facturs)):
				$this->db->insert_batch( "gc_general_cashier_detail_facturs", $general_cashier_detail_facturs);
			endif;
			
			if( !empty($update_payable_voucher)){
				$this->db->update_batch("ap_vouchers", $update_payable_voucher, "voucher_number");
			}

			if( !empty($update_payable_factur)){
				$this->db->update_batch("ap_facturs", $update_payable_factur, "factur_number");
			}

			if( !empty($payable_card)){
				$this->db->insert_batch("ap_card_payables", $payable_card);
			}

			if( !empty($update_receivable_invoice)){
				$this->db->update_batch("ar_invoices", $update_receivable_invoice, "invoice_number");
			}

			if( !empty($update_receivable_factur)){
				$this->db->update_batch("ar_facturs", $update_receivable_factur, "factur_number");
			}

			if( !empty($receivable_card)){
				$this->db->insert_batch("ar_card_receivables", $receivable_card);
			}


		$this->db->trans_complete();
		
		if ($this->db->trans_status() === FALSE)
		{
			$response["message"] = lang('global:created_failed');
			$response["status"] = "error";
			$response["code"] = "500";
	
			return $response;
		}
		
		$response = array(
				"status" => "success",
				"message" => "",
				"code" => "200",
				"id" => $insert_id
			);
		
		return $response;
	
	}

	protected function get_general_cashier_details()
	{
		$query = $this->db->where("evidence_number", $evidence_number)
						->get("gc_general_cashier_details")
						;
		if ( $query->num_rows() > 0 )
		{
			return $query->result();
		}
		
		return false;
	
	}
	
	protected function get_general_cashier_detail_facturs()
	{
		$query = $this->db->where("evidence_number", $evidence_number)
						->get("gc_general_cashier_detail_facturs")
						;
		if ( $query->num_rows() > 0 )
		{
			return $query->result();
		}
		
		return false;
	
	}	
	
	public function cancel_general_cashier( $evidence_number )
	{
		$general_cashier = $this->get_general_cashier( array( "evidence_number" => $evidence_number));
		$general_cashier_details = $this->get_general_cashier_details( $evidence_number );
		$general_cashier_detail_facturs = $this->get_general_cashier_details(  $evidence_number );
		
		$this->db->trans_start();
			$this->db->where( "evidence_number", $evidence_number )->update("ap_general_cashier", array("general_cashier_cancel" => 1, "voucher_cancel" => 1, "updated_at" => $time_stamp, "updated_by" => $user_id));
			$this->db->where( "voucher_number", $general_cashier->voucher_number )->update("ap_vouchers", array("voucher_cancel" => 1, "updated_at" => $time_stamp, "updated_by" => $user_id));
			$this->db->where( "evidence_number", $evidence_number )->delete("ap_card_general_cashier");
		$this->db->trans_complete();
		
		if ($this->db->trans_status() === FALSE)
		{
			return FALSE;
		}
		
		return TRUE;
	}

}