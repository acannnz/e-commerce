<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Journal_m extends Public_Model
{
	public $table = 'TBJ_Transaksi';
	public $primary_key = 'No_Bukti';
	public $table_detail = 'TBJ_Transaksi_Detail';
	
	public $rules;
	
	public function __construct()
	{
		$this->rules = array('insert' => array(
				'No_Bukti' => array(
						'field' => 'No_Bukti',
						'label' => lang( 'accounts:parent_label' ),
						'rules' => 'required'
					),
				'Transaksi_Date' => array(
						'field' => 'Transaksi_Date',
						'label' => lang( 'accounts:date_label' ),
						'rules' => 'required'
					),
				'Debit' => array(
						'field' => 'Debit',
						'label' => lang( 'accounts:debit_label' ),
						'rules' => 'required'
					),
				'Kredit' => array(
						'field' => 'Kredit',
						'label' => lang( 'accounts:credit_label' ),
						'rules' => 'required'
					),
				'Keterangan' => array(
						'field' => 'Keterangan',
						'label' => lang( 'accounts:description_label' ),
						'rules' => ''
					),
				'Kode_Proyek' => array(
						'field' => 'Kode_Proyek',
						'label' => lang( 'accounting:project_label' ),
						'rules' => 'required'
					),
			));
		
		parent::__construct();
	}

	public function get_row( $No_Bukti )
	{
		$query = $this->db
			->where( $this->primary_key, $No_Bukti)
			->get( $this->table )
			;
			
		return $query->num_rows() > 0 ? $query->row_array() : FALSE;
	}	

	public function get_detail( $No_Bukti )
	{
		$query = $this->db->select("a.*, b.Akun_No, b.Akun_Name, c.SectionName")
				->from("{$this->table_detail} a")
				->join("Mst_Akun b", "a.Akun_ID = b.Akun_ID", "LEFT OUTER")
				->join("SIMmSection c", "a.SectionID = c.SectionID", "LEFT OUTER")
				->where( $this->primary_key, $No_Bukti)
				->get()
				;
			
		return $query->num_rows() > 0 ? $query->result() : FALSE;
	}	
			
	public function create_data( $header, $detail )
	{	
		$user = $this->simple_login->get_user();
		
		$this->db->trans_begin();
			$this->db->insert( $this->table, $header );
			
			$date = date("Y-m-d");
			$time = date("Y-m-d H:i:s");
			$activities_description = sprintf( "%s # %s", "INPUT JURNAL.", $header->{$this->primary_key} );			
			$this->db->query("EXEC InsertUserActivities '{$date}','{$time}', {$user->User_ID} ,'{$header->No_Bukti}','{$activities_description}','{$this->table}'");				
			
			foreach( $detail as $row )
			{
				#(No_Bukti,Akun_ID, Debit, Kredit, Keterangan,Kode_Proyek,DivisiID,SectionID) 
				$row[$this->primary_key] = $header->{$this->primary_key};
				$row['Kode_Proyek'] = $header->Kode_Proyek;
				$row['DivisiID'] = $header->DivisiID;

				$this->db->insert( $this->table_detail, $row );				
				$time = date("Y-m-d H:i:s");
				$activities_description = sprintf( "%s # %s # %s", "INPUT DETAIL JURNAL.", $header->{$this->primary_key}, $row['Akun_ID'] );			
				$this->db->query("EXEC InsertUserActivities '{$date}','{$time}', {$user->User_ID} ,'{$header->No_Bukti}','{$activities_description}','{$this->table_detail}'");				
			}
							
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
		
		$this->db->trans_begin();

			$this->db->update( $this->table, $header, array( $this->primary_key => $header->{$this->primary_key} ) );
			
			$date = date("Y-m-d");
			$time = date("Y-m-d H:i:s");
			$activities_description = sprintf( "%s # %s", "EDIT JURNAL.", $header->{$this->primary_key} );			
			$this->db->query("EXEC InsertUserActivities '{$date}','{$time}', {$user->User_ID} ,'{$header->No_Bukti}','{$activities_description}','{$this->table}'");				
			
			$update_log = array(
					"No_Bukti" => $header->{$this->primary_key},
					"Tanggal_Update" => $time,
					"User_Id" => $user->User_ID
				);
			
			$this->db->insert("TBJ_Transaksi_Update", $update_log);
			
			$this->db->where($this->primary_key, $header->{$this->primary_key})
					->delete($this->table_detail);
			
			$db_where_not_in = [];
			foreach( $detail as $row )
			{
				$row[$this->primary_key] = $header->{$this->primary_key};
				$row['Kode_Proyek'] = $header->Kode_Proyek;
				$row['DivisiID'] = $header->DivisiID;
				
				$db_where_not_in[] = $row['Akun_ID'];

				$this->db->insert( $this->table_detail, $row );				
				$time = date("Y-m-d H:i:s");
				$activities_description = sprintf( "%s # %s # %s", "EDIT DETAIL JURNAL.", $header->{$this->primary_key}, $row['Akun_ID'] );			
				$this->db->query("EXEC InsertUserActivities '{$date}','{$time}', {$user->User_ID} ,'{$header->No_Bukti}','{$activities_description}','{$this->table_detail}'");				
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