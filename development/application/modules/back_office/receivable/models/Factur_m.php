<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Factur_m extends Public_Model
{
	public $table = 'AR_trFaktur';
	public $table_detail = 'AR_trFakturDetail';
	public $primary_key = 'No_Faktur';
	
	public $rules;

	public function __construct()
	{
		$this->rules = 
			array(
				'insert' => array(
					'JenisPiutang_ID' => array(
							'field' => 'JenisPiutang_ID',
							'label' => lang( 'types:type_label' ),
							'rules' => 'required'
						),
					'No_Faktur' => array(
							'field' => 'No_Faktur',
							'label' => lang( 'facturs:factur_number_label' ),
							'rules' => 'required'
						),
					'Tgl_Faktur' => array(
							'field' => 'Tgl_Faktur',
							'label' => lang( 'facturs:factur_date_label' ),
							'rules' => 'required'
						),
					'Tgl_JatuhTempo' => array(
							'field' => 'Tgl_JatuhTempo',
							'label' => lang( 'facturs:due_date_label' ),
							'rules' => 'required'
						),
					'Customer_ID' => array(
							'field' => 'Customer_ID',
							'label' => lang( 'facturs:customer_label' ),
							'rules' => 'required'
						),
					'Currency_ID' => array(
							'field' => 'Currency_ID',
							'label' => lang( 'facturs:currency_label' ),
							'rules' => 'required'
						),
					'Nilai_Faktur' => array(
							'field' => 'Nilai_Faktur',
							'label' => lang( 'facturs:value_label' ),
							'rules' => 'required'
						),
					'Keterangan' => array(
							'field' => 'Keterangan',
							'label' => lang( 'facturs:description_label' ),
							'rules' => 'required'
						),
				),
				'update' => array(
					'JenisPiutang_ID' => array(
							'field' => 'JenisPiutang_ID',
							'label' => lang( 'types:type_label' ),
							'rules' => 'required'
						),
					'No_Faktur' => array(
							'field' => 'No_Faktur',
							'label' => lang( 'facturs:factur_number_label' ),
							'rules' => 'required'
						),
					'Tgl_Faktur' => array(
							'field' => 'Tgl_Faktur',
							'label' => lang( 'facturs:factur_date_label' ),
							'rules' => 'required'
						),
					'Tgl_JatuhTempo' => array(
							'field' => 'Tgl_JatuhTempo',
							'label' => lang( 'facturs:due_date_label' ),
							'rules' => 'required'
						),
					'Keterangan' => array(
							'field' => 'Keterangan',
							'label' => lang( 'facturs:description_label' ),
							'rules' => 'required'
						),
					)
			);
		
		parent::__construct();
	}
	
	public function find_factur_list( $options = array())
	{
		$this->db->order_by( 'factur_name', 'asc' );
		$this->db->where( $options );
		
		$query = $this->db->get( $this->table );
		
		$data  = array();

		if( $query->num_rows() > 0 )
		{
			foreach( $query->result_object() as $row )
			{
				$data[ $row->id ] = $row->factur_name;
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
	
	public function cancel_factur( $factur_number )
	{
		if (empty($factur_number))
		{
			return false;
		}
		
		$factur = $this->db->where("factur_number", $factur_number)->get("ar_facturs")->row();
		$user_id = $this->tank_auth->get_user_id();
		$time_stamp = time();
		
		$this->db->trans_start();
		$this->db->where( "factur_number", $factur_number )->update("ar_facturs", array("factur_cancel" => 1, "invoice_cancel" => 1, "updated_at" => $time_stamp, "updated_by" => $user_id));
		$this->db->where( "invoice_number", $factur->invoice_number )->update("ar_invoices", array("invoice_cancel" => 1, "updated_at" => $time_stamp, "updated_by" => $user_id));
		$this->db->where( "factur_number", $factur_number )->delete("ar_card_receivables");
		$this->db->trans_complete();
		
		if ($this->db->trans_status() === FALSE)
		{
			return FALSE;
		}
		
		return TRUE;
	
	}
	
	public function get_before_balance( $factur_id = 0, $date )
	{
		$month = date('m', strtotime(" {$date} first day of -1 month")); 
		$year = date('Y', strtotime(" {$date} first day of -1 month")); 
		
		
		$query =  $this->db->where( array("factur_id" => $factur_id, "month" => $month, "year" => $year ) )
				->get("gl_monthly_posted")
				;
				
		if ( $query->num_rows() > 0 )
		{
			return $query->row();
		}		
	
	}

	public function get_row( $No_Faktur )
	{		
		// get result filtered
		$db_select = <<<EOSQL
			a.*,
			b.Kode_Customer, 
			b.Nama_Customer, 
			c.Nama_Proyek, 
			d.Currency_Code, 
			e.Nama_Divisi, 
			f.Nama_Singkat
EOSQL;

		$query = $this->db
			->select( $db_select )
			->from( "{$this->table} a" )
			->join("mCustomer b", "a.Customer_ID = b.Customer_ID", "LEFT OUTER" )
			->join("mProyek c", "a.Kode_Proyek = c.Kode_Proyek_Real", "LEFT OUTER" )
			->join("Mst_Currency d", "a.Currency_ID = d.Currency_ID", "LEFT OUTER" )
			->join("mDivisi e", "a.DivisiID = e.Divisi_ID", "LEFT OUTER" )
			->join("mUser f", "a.User_ID= f.User_ID", "LEFT OUTER" )
			->where("a.No_Faktur", $No_Faktur )
			->get()
			;
			
		return ($query->num_rows() > 0) ? $query->row() : NULL;
				
	}
	
	public function get_detail_collection( $No_Faktur )
	{
		return
			$this->db->select("a.*, b.Akun_No, b.Akun_Name, c.SectionName")
					->from("{$this->table_detail} a")
					->join("Mst_Akun b", "a.Akun_ID = b.Akun_ID", "LEFT OUTER")
					->join("SIMmSection c", "a.SectionID = c.SectionID", "LEFT OUTER")
					->where("a.No_Faktur", $No_Faktur)
					->get()
					->result();
	}
	
	public function check_debit_credit_note( $invoice_number = NULL )
	{
		if (empty($invoice_number)){ return false; }
		
		$query = $this->db->where("evidence_number", $invoice_number)
						->where_in("transaction_type_id", array(406,407), FALSE)
						->count_all_results(" ar_invoice_details ")
						;
						
		return $query;
		
	}

	public function check_closing_period( $date )
	{
		
		$date = DateTime::createFromFormat("Y-m-d", $date );
		$month = $date->format('m');
		$year = $date->format('Y');
		
		$check = $this->db->where(array(
								"DATEPART(month, Tgl_Faktur) =" => $month,
								"DATEPART(year, Tgl_Faktur) =" => $year,
								"TutupBuku"	=> 1,
						))
						->count_all_results("AR_trFaktur")
						;
							
		return (boolean) $check;
	
	}
	
	public function check_already_created_invoices( $No_Faktur )
	{
		$check = $this->db->where(array(
								"No_Faktur" => $No_Faktur,
								"Cancel_Invoice" => 0,
								"No_Invoice !=" => '-',
						))
						->count_all_results("AR_trFaktur")
						;
							
		return (boolean) $check;
	}

	public function create_data( $header, $detail )
	{
		$this->load->model("type_m");
		$user = $this->simple_login->get_user();
		$date = date("Y-m-d");
		$time = date("Y-m-d H:i:s");
		
		$details = array();
		foreach($detail as $row)
		{
			$row['No_Faktur'] = $header['No_Faktur'];
			$details[] = $row;
		}		
		
		# Prepare Detail Untuk Rekening Tipe Piutang ( sebagai lawan Rekening )
		$receivable_type =$this->type_m->get_row( $header['JenisPiutang_ID'] );
		$details[] = array(
				"No_Faktur" => $header['No_Faktur'],
				"Akun_ID" => $receivable_type['Akun_ID'],
				"Keterangan" => $row['Keterangan'],
				"Harga_Transaksi" => $header['Nilai_Faktur'],
				"SectionID" => $row['SectionID'],
				"Pos" => "D",
				"Qty" => 1
			);
		
		$this->db->trans_begin();
			$this->db->insert('AR_trFaktur', $header);
			
			$activities_description = sprintf( "%s # %s # %s ", "INSERT FAKTUR PIUTANG.", $header['No_Faktur'], $receivable_type['Nama_Type'] );			
			$this->db->query("EXEC InsertUserActivities '{$date}','{$time}', {$user->User_ID} ,'".  $header['No_Faktur'] ."','{$activities_description}','{$this->table}'");				

			foreach ( $details as $row )
			{
				$this->db->insert('AR_trFakturDetail', $row);
				
				$activities_description = sprintf( "%s # %s # %s # %s # %s # %s", "INSERT FAKTUR PIUTANG DETAIL.", $row['No_Faktur'], $row['Akun_ID'], $row['Keterangan'], $row['Harga_Transaksi'], $row['Pos'] );			
				$this->db->query("EXEC InsertUserActivities '{$date}','{$time}', {$user->User_ID} ,'".  $row['No_Faktur'] ."','{$activities_description}','{$this->table_detail}'");				
			}
		
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			return FALSE;
		}

		$this->db->trans_commit();
		return TRUE;
		
	}

	public function update_data( $header, $detail, $No_Faktur )
	{
		$this->load->model("type_m");
		$user = $this->simple_login->get_user();
		$date = date("Y-m-d");
		$time = date("Y-m-d H:i:s");
		$receivable_type =$this->type_m->get_row( $header['JenisPiutang_ID'] );
		
		$details = array();
		foreach($detail as $row)
		{
			$row['No_Faktur'] = $No_Faktur;
			$details[] = $row;
		}		
						
		$this->db->trans_begin();
			$this->db->update('AR_trFaktur', $header, array( $this->primary_key => $No_Faktur));
			
			$activities_description = sprintf( "%s # %s # %s ", "UPDATE FAKTUR PIUTANG.", $No_Faktur, $receivable_type['Nama_Type'] );			
			$this->db->query("EXEC InsertUserActivities '{$date}','{$time}', {$user->User_ID} ,'".  $No_Faktur ."','{$activities_description}','{$this->table}'");				

			foreach ( $details as $row )
			{
				( $row['Pos']) == 'K'
				? $this->db->update('AR_trFakturDetail', $row, array( $this->primary_key => $No_Faktur, "Akun_ID" => $row['Akun_ID'] ))
				: $this->db->update('AR_trFakturDetail', $row, array( $this->primary_key => $No_Faktur, "Pos" => "D" ));
				
				$activities_description = sprintf( "%s # %s # %s # %s # %s # %s", "UPDATE FAKTUR PIUTANG DETAIL.", $No_Faktur, $row['Akun_ID'], $row['Keterangan'], $row['Harga_Transaksi'], $row['Pos'] );			
				$this->db->query("EXEC InsertUserActivities '{$date}','{$time}', {$user->User_ID} ,'".  $No_Faktur ."','{$activities_description}','{$this->table_detail}'");				
			}
		
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			return FALSE;
		}

		$this->db->trans_commit();
		return TRUE;
		
	}

	public function cancel_data( $No_Faktur )
	{
		$user = $this->simple_login->get_user();
		$date = date("Y-m-d");
		$time = date("Y-m-d H:i:s");
		
		
		$this->db->trans_begin();
			$this->db->update('AR_trFaktur', array("Cancel_Faktur" => 1), array( $this->primary_key => $No_Faktur));
			
			$activities_description = sprintf( "%s # %s ", "CANCEL FAKTUR PIUTANG.", $No_Faktur );			
			$this->db->query("EXEC InsertUserActivities '{$date}','{$time}', {$user->User_ID} ,'".  $No_Faktur ."','{$activities_description}','{$this->table}'");				

		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			return FALSE;
		}

		$this->db->trans_commit();
		return TRUE;
		
	}
}