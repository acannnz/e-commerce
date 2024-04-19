<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Posting_cancel_m extends Public_Model
{
	public $table = 'ar_facturs';
	public $primary_key = 'id';
	
	public $rules;
	public $user;
	public $HisCurrency_ID = 1;
	public $_header;
	
	public function __construct()
	{
		$this->rules = array('insert' => array(
				'remain' => array(
						'field' => 'remain',
						'label' => lang( 'facturs:remain_label' ),
						'rules' => 'required'
					),
			));
			
		parent::__construct();
	}

	private function _insert_detail_case_401( $No_Bukti )
	{			
		$this->db->update('AP_trFaktur', array("Posted" => 0), array("No_Faktur" => $No_Bukti));
	}
	
	private function _insert_detail_case_402( $No_Bukti )
	{		
		$this->db->update('AP_trVoucher', array("Posted" => 0), array("No_Voucher" => $No_Bukti));
		$this->db->update('AP_trFaktur', array("Posted" => 0), array("No_Faktur" => $No_Bukti));
	
	}
	
	private function _insert_detail_case_403( $No_Bukti )
	{	
		$this->db->update('AP_trVoucher', array("Posted" => 0), array("No_Voucher" => $No_Bukti));
		$this->db->update('AP_trFaktur', array("Posted" => 0), array("No_Faktur" => $No_Bukti));
	}
	
	private function _insert_detail_case_405( $No_Bukti )
	{		
		$this->db->update('AP_trVoucher', array("Posted" => 0), array("No_Voucher" => $No_Bukti));                                
	}

	private function _insert_detail_case_406( $No_Bukti )
	{					
		$this->db->update('AP_trVoucher', array("Posted" => 0), array("No_Voucher" => $No_Bukti));        
	}    
	
	private function _insert_detail_case_407( $No_Bukti )
	{
		
		$this->db->update('AP_trVoucher', array("Posted" => 0), array("No_Voucher" => $No_Bukti));
		
	}
	
	public function posting_cancel_data( $postings )
	{
		$date = date("Y-m-d");
		$time = date("Y-m-d H:i:s");
		$user = $this->simple_login->get_user();
		
		set_time_limit(0);

		$this->db->trans_begin();
		
			foreach ( $postings as $posting ):

				$this->db->delete("TBJ_Transaksi_Detail", array('No_Bukti' => $posting['No_Bukti']) );
				$this->db->delete("TBJ_Transaksi", array('No_Bukti' => $posting['No_Bukti']) );
				
				// update AP posted = 0
				call_user_func( array( $this, "_insert_detail_case_".$posting['JTransaksi_ID'] ), $posting['No_Bukti']);  
				
				$activities_description = sprintf( "%s # %s", "CANCEL HUTANGG TO GL.", $posting[ 'No_Bukti' ] );			
				$this->db->query("EXEC InsertUserActivities '{$date}','{$time}', {$user->User_ID} ,'".   $posting[ 'No_Bukti' ] ."','{$activities_description}','". $_SERVER['REMOTE_ADDR'] ."'");				

			endforeach;
		
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			return FALSE;
		}

		$this->db->trans_commit();
		return TRUE;
	}

	public function check_general_ledger_closing( $No_Bukti )
	{		
		$query = $this->db->where("No_Bukti", $No_Bukti)
						->get("TBJ_Transaksi")
						;
		
		return (boolean) @$query->row()->Posted;
	}
		
}