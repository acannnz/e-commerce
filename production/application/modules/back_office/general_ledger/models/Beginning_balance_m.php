<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Beginning_balance_m extends Public_Model
{
	public $table = 'TBJ_PostedBulanan';
	public $primary_key = 'Akun_ID';
	
	public $rules;
	
	public function __construct()
	{
		$this->rules = array('insert' => array(
				'Akun_ID' => array(
						'field' => 'Akun_ID',
						'label' => lang( 'beginning_balances:account_label' ),
						'rules' => 'required'
					),
				'Nilai' => array(
						'field' => 'Nilai',
						'label' => lang( 'beginning_balances:value_label' ),
						'rules' => 'required'
					),
				'Currency_ID' => array(
						'field' => 'Currency_ID',
						'label' => lang( 'beginning_balances:currency_label' ),
						'rules' => 'required'
					),
				'HisCurrency_ID' => array(
						'field' => 'HisCurrency_ID',
						'label' => lang( 'beginning_balances:date_label' ),
						'rules' => 'required'
					),
				'Saldoawal' => array(
						'field' => 'Saldoawal',
						'label' => lang( 'beginning_balances:beginning_balance_label' ),
						'rules' => 'required'
					),
				'Nilai_Tukar' => array(
						'field' => 'Nilai_Tukar',
						'label' => lang( 'beginning_balances:state_label' ),
						'rules' => ''
					),
				'User_id' => array(
						'field' => 'User_id',
						'label' => lang( 'beginning_balances:state_label' ),
						'rules' => 'required'
					),
				'Konsolidasi' => array(
						'field' => 'Konsolidasi',
						'label' => lang( 'beginning_balances:state_label' ),
						'rules' => ''
					),
			));
		
		parent::__construct();
	}

	public function get_begin_his_currency()
	{
		$date = DateTime::createFromFormat("Y-m-d", config_item('Tanggal Mulai System') );
		$date->sub(new DateInterval('P1D'));
		$begin_balance_date = $date->format('Y-m-d');
		
		$his_currency = $this->db->where("Tanggal", $begin_balance_date)
							->get("TBJ_HisCurrency")
							->row();
							
		return (int) @$his_currency->HisCurrency_ID;	
	}
		
	public function get_beginning_balance_value( $account, $his_currency_id )
	{
		$query = $this->db->select("a.Nilai, a.Nilai_Tukar, a.Currency_ID, b.Currency_Code")
					->from( "{$this->table} a")
					->join("Mst_Currency b", "a.Currency_ID = b.Currency_ID", "LEFT OUTER")
					->where($this->primary_key, $account->{$this->primary_key})
					->where( "HisCurrency_ID", $his_currency_id)
					->get()
					;
					
		return $query->num_rows() > 0 ? $query->row() : FALSE;
	}

	public function get_beginning_balance_account()
	{
		$query = $this->db->where_in("Group_ID", array(1,2,3), TRUE)
					->from("Mst_Akun a")
					->join("Mst_Currency b", "a.Currency_id = b.Currency_ID", "LEFT OUTER")
					->get()
					;
					
		$collection = array();			
		if ( $query->num_rows() > 0 ) : foreach ( $query->result() as $row ):
		
			$collection[ $row->{$this->primary_key} ] = $row;
			$collection[ $row->Akun_No ] =& $collection[ $row->{$this->primary_key} ] ;
			
		endforeach; endif;
		
		return $collection;
	}	
	
	public function check_rate_currency( $get_date = NULL )
	{
		$begin_date = DateTime::createFromFormat("Y-m-d", general_ledger_helper::get_beginning_balance_date() );
		$date = empty($get_date) ? $begin_date->format('Y-m-d') : $get_date;
		
		$HisCurrency_ID = empty($get_date) 
							? $this->db->select("MAX(HisCurrency_ID) AS HisCurrency_ID")->where( "Tanggal < ", $date )->get("TBJ_HisCurrency")->row()->HisCurrency_ID
							: $this->db->select("MAX(HisCurrency_ID) AS HisCurrency_ID")->where( "Tanggal", $date )->get("TBJ_HisCurrency")->row()->HisCurrency_ID;
		
		$currencies = $this->db->get("Mst_Currency")->result();	
		foreach ( $currencies as $row ) 
		{
			// check rate currency
			$check = $this->db->where(array("HisCurrency_ID" => $HisCurrency_ID, "Currency_ID" => $row->Currency_ID))
							->count_all_results("TBJ_HisCurrencyDetail");
			if( $check == 0 )
			{
				return FALSE;
			}
		}
		return TRUE;

	}

	public function check_existing_next_monthly_posted()
	{
		$begin_date = general_ledger_helper::get_beginning_balance_date();
		$check = $this->db->where(array("Tanggal >" => $begin_date))
						->from("TBJ_PostedBulanan a")
						->join("TBJ_HisCurrency b", "a.HisCurrency_ID = b.HisCurrency_ID", "INNER")
						->count_all_results();
		return (boolean) $check;

	}

	public function check_existing_transaction()
	{
		$begin_date = general_ledger_helper::get_beginning_balance_date();
		$check = $this->db->where(array("Transaksi_Date <=" => $begin_date))
						->count_all_results("TBJ_Transaksi");
						
		return (boolean) $check;

	}
		
	public function create_data( $activa, $pasiva, $keterangan )
	{
		$this->load->helper("general_ledger_helper");
		$begin_date = DateTime::createFromFormat("Y-m-d", general_ledger_helper::get_beginning_balance_date() );
		$date = $begin_date->format('Y-m-d');
		$month = $begin_date->format('m');
		$year = $begin_date->format('Y');
		$start_date = $begin_date->format('Y-m-01');
		$end_date = $begin_date->format('Y-m-t');

		$user = $this->simple_login->get_user();
		$type_accounting_period = config_item('TypePeriodeAkuntansi');	
		
		set_time_limit(0);

		$this->db->trans_begin();
		
			/*
				Delete from TBJ_PostedBulanan where SaldoAwal=1
				Delete from TBJ_PostedTahunan where SaldoAwal=1					
				Delete from TBJ_TutupBukuLabaRugiBulanan
				Delete From TBJ_TutupBukuLabaRugiTahunan
				Delete from TBJ_TutupBukuVerticalBulanan
				Delete From TBJ_TutupBukuVerticalTahunan
			*/
			
			$this->db->delete("TBJ_PostedBulanan", array("SaldoAwal" => 1));
			$this->db->delete("TBJ_PostedTahunan", array("SaldoAwal" => 1));
			/*$this->db->delete("TBJ_TutupBukuLabaRugiBulanan");
			$this->db->delete("TBJ_TutupBukuLabaRugiTahunan");
			$this->db->delete("TBJ_TutupBukuVerticalBulanan");
			$this->db->delete("TBJ_TutupBukuVerticalTahunan");*/

			$HisCurrency_ID = $this->_gen_his_currency( $date );
			
			$accounts = $this->get_beginning_balance_account();
			
			$end_of_period = ( $month == 12 ) ? 1 : 0;
			
			$activa_monthly_posted = array();
			$activa_annual_posted = array();
			foreach ( $activa as $index => $row )
			{
				$data = $accounts[ $row[$this->primary_key] ];
				
				$activa_monthly_posted[$index] = array(
							"Akun_ID" => $data->{$this->primary_key},
							"Currency_ID" => $data->Currency_ID,
							"HisCurrency_ID" => $HisCurrency_ID,
							"Nilai" => $row['Nilai'],
							"Nilai_Tukar" => $this->_exchange_rate( $HisCurrency_ID, $data->Currency_ID ),
							"User_id" => $user->User_ID,
							"Saldoawal" => 1,
							"Konsolidasi" => 0,
						);

				if ( $end_of_period )
				{
					$activa_annual_posted[$index] = $activa_monthly_posted[ $index ];
					$activa_annual_posted[$index]['Tahun'] = $year;
					unset($activa_annual_posted[$index]['Konsolidasi']);
				}

			}

			$pasiva_monthly_posted = array();
			$pasiva_annual_posted = array();
			foreach ( $pasiva as $index => $row )
			{
				$data = $accounts[ $row[$this->primary_key] ];
				
				$pasiva_monthly_posted[$index] = array(
							"Akun_ID" => $data->{$this->primary_key},
							"Currency_ID" => $data->Currency_ID,
							"HisCurrency_ID" => $HisCurrency_ID,
							"Nilai" => $row['Nilai'],
							"Nilai_Tukar" => $this->_exchange_rate( $HisCurrency_ID, $data->Currency_ID ),
							"User_id" => $user->User_ID,
							"Saldoawal" => 1,
							"Konsolidasi" => 0,
						);

				if ( $end_of_period )
				{
					$pasiva_annual_posted[$index] = $pasiva_monthly_posted[ $index ];
					$pasiva_annual_posted[$index]['Tahun'] = $year;
					unset($pasiva_annual_posted[$index]['Konsolidasi']);
				}
			}
			
			$response = array(
					"status" => "success",
					"error" => "",
					"code" => "200",
				);
						
			$this->db->insert_batch('TBJ_PostedBulanan', $activa_monthly_posted);
			$this->db->insert_batch('TBJ_PostedBulanan', $pasiva_monthly_posted);

			if ( $end_of_period )
			{
				$this->db->insert_batch('TBJ_PostedTahunan', $activa_annual_posted);
				$this->db->insert_batch('TBJ_PostedTahunan', $pasiva_annual_posted);
			}
			
			# EXEC TutupBuku_Skontro : Vertikal, TutukBukuBulanan, tbj_tutupbukuTahunan, TBJ_TutupBukuLabaRugiTahunan
			$this->db->query("EXEC TutupBuku_Skontro '{$start_date}', '{$end_date}', {$end_of_period}, {$type_accounting_period}, {$user->User_ID} ");

		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			return FALSE;
		}
		
		$this->db->trans_commit();
		return TRUE;

	}
	
	// Generate HisCurrency or if already exist return HisCurrency_ID
	private function _gen_his_currency( $date )
	{
				
		if ( !$check = $this->check_rate_currency($date) )
		{
			if( $this->db->where("Tanggal", $date)->count_all_results("TBJ_HisCurrency") == 0 )
			{
				$this->db->insert("TBJ_HisCurrency", array("Tanggal" => $date));
				$HisCurrency_ID = $this->db->insert_id();
			} else {
				$HisCurrency_ID = $this->db->where("Tanggal", $date)->get("TBJ_HisCurrency")->row()->HisCurrency_ID;
			}
			
				$detail = $this->db->where("HisCurrency_ID IN ( SELECT MAX(HisCurrency_ID) FROM TBJ_HisCurrency WHERE Tanggal < '$date' AND HisCurrency_ID IN (SELECT MAX(HisCurrency_ID) FROM TBJ_HisCurrencyDetail))")
										->get("TBJ_HisCurrencyDetail")
										->result();
		} else {
			
			
			$HisCurrency_ID = $this->db->where("Tanggal", $date)->get("TBJ_HisCurrency")->row()->HisCurrency_ID;
			
			$detail = $this->db->where("HisCurrency_ID", $HisCurrency_ID)
							->get("TBJ_HisCurrencyDetail")
							->result();		
		}
		
		$date = DateTime::createFromFormat("Y-m-d", $date );
		$date->add(new DateInterval('P1D'));
		$currency_day = $date->format('d');
							
		foreach( $detail as $row )
		{
			$data = array(
					'Currency_ID' => $row->Currency_ID,
					'HisCurrency_ID' => $HisCurrency_ID,
					'Rate' => $row->Rate,
				);
				
			( !$check ) ? $this->db->insert('TBJ_HisCurrencyDetail', $data ) : FALSE;
			
			( $currency_day == '01' && $check == 0 ) ? $this->db->insert('TBJ_HisCurrencyPosted', $data ) : FALSE;
		}
		
							
		return (int) @$HisCurrency_ID;
		
	}
	
	private function _exchange_rate( $HisCurrency_ID, $Currency_ID )
	{
		return 
			@$this->db->where(array("HisCurrency_ID" => $HisCurrency_ID, "Currency_ID" => $Currency_ID))
					->get("TBJ_HisCurrencyDetail")
					->row()
					->Rate;
	}
	
	public function submit_rate_currency( $post )
	{
		$this->load->helper("general_ledger_helper");
		$begin_date = general_ledger_helper::get_beginning_balance_date();
		$user = $this->simple_login->get_user();
		
		$this->db->trans_begin();
		
			if( $check = $this->db->where("Tanggal", $begin_date)->count_all_results("TBJ_HisCurrency") == 0 )
			{
				$this->db->insert("TBJ_HisCurrency", array("Tanggal" => $begin_date));
				$HisCurrency_ID = $this->db->insert_id();
			} else {
				$HisCurrency_ID = $this->db->where("Tanggal", $begin_date)->get("TBJ_HisCurrency")->row()->HisCurrency_ID;
			}
			
		$date = DateTime::createFromFormat("Y-m-d", $begin_date );
		$date->add(new DateInterval('P1D'));
		$currency_day = $date->format('d');
		
		$this->db->delete("TBJ_HisCurrencyDetail", array("HisCurrency_ID" => $HisCurrency_ID));
		( $currency_day == '01' && $check == 0 ) ? $this->db->delete("TBJ_HisCurrencyPosted", array("HisCurrency_ID" => $HisCurrency_ID)) : FALSE;
							
		foreach( $post as $row )
		{
			$data = array(
					'Currency_ID' => $row['Currency_ID'],
					'HisCurrency_ID' => $HisCurrency_ID,
					'Rate' => str_replace(',', '', $row['Rate']),
				);
				
			$this->db->insert('TBJ_HisCurrencyDetail', $data );
			
			( $currency_day == '01' && $check == 0 ) ? $this->db->insert('TBJ_HisCurrencyPosted', $data ) : FALSE;
		}

		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			return FALSE;
		}
		
		$this->db->trans_commit();
		return TRUE;

	}
}