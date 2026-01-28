<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cash_bank_mutation_m extends Public_Model
{
	public $table = 'GC_trGeneralCashier';
	public $table_detail = 'GC_trGeneralCashierDetail';
	public $primary_key = 'No_Bukti';
	public $foreign_key = 'No_Referensi';
	
	public $rules;
	
	public function __construct()
	{
		$this->rules = array(
			'insert' => array(
				'No_Bukti' => array(
						'field' => 'No_Bukti',
						'label' => lang( 'cash_bank_mutation:evidence_number_label' ),
						'rules' => 'required'
					),
				'Tgl_Transaksi' => array(
						'field' => 'Tgl_Transaksi',
						'label' => lang( 'cash_bank_mutation:date_label' ),
						'rules' => 'required'
					),
				'Type_Transaksi' => array(
						'field' => 'Type_Transaksi',
						'label' => lang( 'cash_bank_mutation:type_label' ),
						'rules' => 'required'
					),
				'Kredit' => array(
						'field' => 'Kredit',
						'label' => lang( 'cash_bank_mutation:mutation_value_label' ),
						'rules' => 'required|greater_than[0]'
					),
				'AkunBG_ID' => array(
						'field' => 'AkunBG_ID',
						'label' => lang( 'cash_bank_mutation:account_origin_subtitle' ),
						'rules' => 'required'
					),
				'Keterangan' => array(
						'field' => 'Keterangan',
						'label' => lang( 'cash_bank_mutation:description_label' ),
						'rules' => 'required'
					),
				),
			'insert_detail' => array(
				'Akun_ID' => array(
						'field' => 'Akun_ID',
						'label' => lang( 'cash_bank_mutation:account_destination_subtitle' ),
						'rules' => 'required'
					),
				),
			);
		
		parent::__construct();
	}
	
	public function get_row( $No_Bukti )
	{		
		// get result filtered
		$db_select = <<<EOSQL
			a.*,
			c.Akun_ID, 
			c.Akun_No, 
			c.Akun_Name,
			d.Akun_ID AS Destination_Akun_ID, 
			d.Akun_No AS Destination_Akun_No, 
			d.Akun_Name AS Destination_Akun_Name
			
EOSQL;

		$query = $this->db
			->select( $db_select )
			->from( "{$this->table} a" )
			->join("GC_trGeneralCashierDetail b", "a.No_Bukti = b.No_Bukti", "INNER" )
			->join("Mst_Akun c", "a.AkunBG_ID = c.Akun_ID", "LEFT OUTER" )
			->join("Mst_Akun d", "b.Akun_ID = d.Akun_ID", "LEFT OUTER" )
			->where("a.{$this->primary_key}", $No_Bukti )
			->get()
			;
			
		return ($query->num_rows() > 0) ? $query->row() : NULL;
				
	}	

	public function create_data( $header, $detail )
	{
		$user = $this->simple_login->get_user();
		$date = date("Y-m-d");
		$time = date("Y-m-d H:i:s");

		// prepare header detail
		$prepare_detail = array(
			$this->primary_key  => $header[ $this->primary_key ],
			$this->foreign_key => "-",
			"Debet" => $detail['Debet'],
			"Kredit" => 0,
			"Akun_ID" => $detail["Akun_ID"],
			"Keterangan" => $detail['Keterangan'],
			"Kode_Proyek" => $header["Kode_Proyek"],
			"DivisiID" => $header["DivisiID"],
			"No_Kartu_Pembayaran" => '',
			"TypePembayaran_ID" => NULL,
			"Tgl_Tempo" => NULL,
			"SectionID" => $header['SectionID'],
		);
		
		$header['BG'] = !empty($header['NoBg']) && $header['NoBg'] !== "" ? 1 : 0;
					
		$this->db->trans_begin();
			$this->db->insert( $this->table, $header);
			
			$activities_description = sprintf( "%s %s# %s #", "INSERT ", $header[ 'Type_Transaksi' ] , $header[ $this->primary_key ] );			
			$this->db->query("EXEC InsertUserActivities '{$date}','{$time}', {$user->User_ID} ,'".  $header[ $this->primary_key ] ."','{$activities_description}','{$this->table}'");				
			
			$this->db->insert( $this->table_detail, $prepare_detail );
			$activities_description = sprintf( "%s %s # %s ", "INSERT DETAIL.", $header[ 'Type_Transaksi' ], $header[ $this->primary_key ] );			
			$this->db->query("EXEC InsertUserActivities '{$date}','{$time}', {$user->User_ID} ,'".  $prepare_detail[ $this->foreign_key ] ."','{$activities_description}','{$this->table_detail}'");				
		
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			return FALSE;
		}

		$this->db->trans_commit();
		return TRUE;
	}
	
	public function update_data( $header, $detail )
	{
		$user = $this->simple_login->get_user();
		$date = date("Y-m-d");
		$time = date("Y-m-d H:i:s");

		// prepare header detail
		$prepare_detail = array(
			"Debet" => $detail['Debet'],
			"Kredit" => 0,
			"Akun_ID" => $detail["Akun_ID"],
			"Keterangan" => $detail['Keterangan'],
		);
		
		$this->db->trans_begin();
			$this->db->update( $this->table, $header, array( $this->primary_key  => $header[ $this->primary_key ] ));
			
			$activities_description = sprintf( "%s %s # %s #", "UPDATE ", 'MUT' , $header[ $this->primary_key ] );			
			$this->db->query("EXEC InsertUserActivities '{$date}','{$time}', {$user->User_ID} ,'".  $header[ $this->primary_key ] ."','{$activities_description}','{$this->table}'");				
			
			$this->db->update( $this->table_detail, $prepare_detail, array( $this->primary_key  => $header[ $this->primary_key ] ));
			$activities_description = sprintf( "%s %s # %s ", "UPDATE DETAIL.", 'MUT', $header[ $this->primary_key ] );			
			$this->db->query("EXEC InsertUserActivities '{$date}','{$time}', {$user->User_ID} ,'-','{$activities_description}','{$this->table_detail}'");				
		
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			return FALSE;
		}

		$this->db->trans_commit();
		return TRUE;		
	}
	
	public function cancel_data( $header )
	{
		$user = $this->simple_login->get_user();
		$date = date("Y-m-d");
		$time = date("Y-m-d H:i:s");
		
		$this->db->trans_begin();
		
			$this->db->update( $this->table, array("Status_Batal" => 1), array( $this->primary_key  => $header->No_Bukti ));

			$activities_description = sprintf( "%s %s # %s #", "CANCEL ", $header->Type_Transaksi, $header->No_Bukti );			
			$this->db->query("EXEC InsertUserActivities '{$date}','{$time}', {$user->User_ID} ,'". $header->No_Bukti ."','{$activities_description}','{$this->table}'");				
			
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			return FALSE;
		}

		$this->db->trans_commit();
		return TRUE;
		
	}
}