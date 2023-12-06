<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Close_book_m extends Public_Model
{
	public $table = '';
	public $primary_key = 'id';
	
	public function __construct()
	{
		$this->rules = array(
				'insert' => array(
					'level' => array(
							'field' => 'level',
							'label' => lang( 'close_books:level_label' ),
							'rules' => 'required'
						),
					'digit' => array(
							'field' => 'digit',
							'label' => lang( 'close_books:digit_label' ),
							'rules' => ''
						),
					'state' => array(
							'field' => 'state',
							'label' => lang( 'close_books:state_label' ),
							'rules' => ''
						),
			));
		
		parent::__construct();
	}
	
	
	public function close_book( $close_date, $house_id )
	{

		$close_date_first_day = date("Y-m-01", strtotime( $close_date ));
		$close_date_last_day = date("Y-m-t", strtotime( $close_date ));
		$close_date_before = date("Y-m-t", strtotime( $close_date_first_day ." -1 day" ));
		
		$monthly_posted = $card_payable = array();
		
		$this->db->trans_begin();
			
			// Update Status Tutup Buku	menjadi 1
			$this->db->where("DATE_FORMAT(`factur_date`, '%Y-%m') = '$close_date'")
					->where("house_id", $house_id)
					->update("ap_facturs", array("close_book" => 1))
					;

			$this->db->where("DATE_FORMAT(`voucher_date`, '%Y-%m') = '$close_date'")
					->where("house_id", $house_id)
					->update("ap_vouchers", array("close_book" => 1))
					;
			
			// ambil data factur dan data saldo sebelumnya kemudian di jumlahkan dengan group per supplier dan per type
			$sql_query = "
							SELECT
								supplier_id, type_id, SUM(remain) AS remain
							FROM
								(	
									SELECT 
										`supplier_id`, `type_id`, SUM(remain) AS remain 
									FROM `tab_ap_facturs` 
									WHERE 
										DATE_FORMAT(`factur_date`, '%Y-%m') = '$close_date' 
										AND `house_id` = '1' 
									GROUP BY `supplier_id`, `type_id`
		
								UNION
		
									SELECT 
										`supplier_id`, `type_id`, SUM(value) AS remain 
									FROM `tab_ap_monthly_posted` 
									WHERE 
										DATE_FORMAT(`date`, '%Y-%m-%d') = '$close_date_before' 
										AND `house_id` = '1' 
									GROUP BY `supplier_id`, `type_id`
								) T
							GROUP BY `supplier_id`, `type_id`
						";
						
			$_facturs = $this->db->query($sql_query);	
				
			if ( $_facturs->num_rows() > 0 ): 
				foreach( $_facturs->result() as $row ):
				$data = array(
						"house_id" => $house_id,
						"supplier_id" => $row->supplier_id,
						"type_id" => $row->type_id,
						"value" => $row->remain,
						"date" => date("Y-m-t", strtotime( $close_date )),
						"beginning_balance" => 1,
						'state' => 0,
						'created_at' => time(),
						'created_by' => $this->tank_auth->get_user_id(),
						'updated_at' => time(),
						'updated_by' => $this->tank_auth->get_user_id(),
						'deleted_at' => null,
						'deleted_by' => 0,
					);

				$monthly_posted[] = $data;
				
				$card_date = date("Y-m-01", strtotime( $close_date_last_day ." +1 day" ));	
				$data = array(
						"house_id" => $house_id,
						"supplier_id" => $row->supplier_id,
						"type_id" => $row->type_id,
						"evidence_number" => payable_helper::gen_beginning_card_number( $house_id, $row->supplier_id, $row->type_id, $card_date ),
						"voucher_number" => "SA",
						"factur_number" => "SA",
						"beginning_balance" => $row->remain,
						"date" => $card_date,
						"description" => "Saldo Awal",
						'state' => 0,
						'created_at' => time(),
						'created_by' => $this->tank_auth->get_user_id(),
						'updated_at' => time(),
						'updated_by' => $this->tank_auth->get_user_id(),
						'deleted_at' => null,
						'deleted_by' => 0,
					);

				$card_payable[] = $data;

				endforeach; 
				
				$this->db->insert_batch("ap_monthly_posted", $monthly_posted);
				$this->db->insert_batch("ap_card_payables", $card_payable);
				
			endif;
			
		if ( false === $this->db->trans_status() )
		{
			$this->db->trans_rollback();
			return false;
		}
		
		$this->db->trans_commit();
		
		return TRUE;
	}	
	
	public function cancel_close_books( $cancel_date, $house_id )
	{
		$card_date = date("Y-m-t", strtotime( $cancel_date ));
		$card_date = date("Y-m", strtotime( $card_date ." +1 day" ));
		
		$this->db->trans_start();
			
			// hapus data monthly posted
			$this->db->where("DATE_FORMAT(`date`, '%Y-%m') = '$cancel_date'")
					->where("house_id", $house_id)
					->delete('ap_monthly_posted')
					; 

			// hapus data card payable
			$this->db->where("DATE_FORMAT(`date`, '%Y-%m') = '$card_date'")
					->where("house_id", $house_id)
					->where("beginning_balance >", 0)
					->delete('ap_card_payables')
					; 
			
			// Update Status Tutup Buku	menjadi 0
			$this->db->where("DATE_FORMAT(`factur_date`, '%Y-%m') = '$cancel_date'")
					->where("house_id", $house_id)
					->update("ap_facturs", array("close_book" => 0))
					;

			$this->db->where("DATE_FORMAT(`voucher_date`, '%Y-%m') = '$cancel_date'")
					->where("house_id", $house_id)
					->update("ap_vouchers", array("close_book" => 0))
					;

		$this->db->trans_complete();
		
					
		if ($this->db->trans_status() === FALSE)
		{
			return FALSE;
		}
		
		return TRUE;
				
	}
	
	# Mengecek apakah data Faktur atau voucher yang belum di POSTING
	public function check_un_posting_data( $close_date )
	{
		$facturs = $this->db->where("close_book", 0)
						->where("factur_cancel", 0)
						->where("DATE_FORMAT(`factur_date`, '%Y-%m') = '$close_date'")
						->count_all_results("ap_facturs")
						;

		$vouchers = $this->db->where("close_book", 0)
						->where("voucher_cancel", 0)
						->where("DATE_FORMAT(`voucher_date`, '%Y-%m') = '$close_date'")
						->count_all_results("ap_vouchers")
						;
						
		if ( $facturs or $vouchers)
		{
			return TRUE;
		}
		
		return FALSE;

	}
	
	public function last_close_books()
	{
		$query = $this->db->select("date")
				->order_by("date DESC")
				->get("ap_monthly_posted")
				;
		if ( $query->num_rows() > 0 )
		{
			return $query->row();
		}
		
	}
}


