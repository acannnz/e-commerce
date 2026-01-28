<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Account_m extends Public_Model
{
	public $table = 'Mst_Akun';
	public $primary_key = 'Akun_ID';
	
	public $rules;
	
	public function __construct()
	{
		$this->rules = array('insert' => array(
				'Level_Ke' => array(
						'field' => 'Level_Ke',
						'label' => lang( 'accounts:level_label' ),
						'rules' => 'required'
					),
				'Normal_Pos' => array(
						'field' => 'Normal_Pos',
						'label' => lang( 'accounts:normal_pos_label' ),
						'rules' => 'required'
					),
				'Kelompok' => array(
						'field' => 'Kelompok',
						'label' => lang( 'accounts:component_label' ),
						'rules' => 'required'
					),
				'Akun_No' => array(
						'field' => 'Akun_No',
						'label' => lang( 'accounts:account_number_label' ),
						'rules' => 'required'
					),
				'Akun_Name' => array(
						'field' => 'Akun_Name',
						'label' => lang( 'accounts:account_name_label' ),
						'rules' => 'required'
					),
				'Convert_Permanen' => array(
						'field' => 'Convert_Permanen',
						'label' => lang( 'accounts:convert_permanent_label' ),
						'rules' => ''
					),
				'Currency_id' => array(
						'field' => 'Currency_id',
						'label' => lang( 'accounts:currency_code_label' ),
						'rules' => 'required'
					),
				'Integrasi' => array(
						'field' => 'Integrasi',
						'label' => lang( 'accounts:integration_label' ),
						'rules' => ''
					),
				'SumberIntegrasi' => array(
						'field' => 'SumberIntegrasi',
						'label' => lang( 'accounts:integration_source_label' ),
						'rules' => ''
					),
				'Group_ID' => array(
						'field' => 'Group_ID',
						'label' => lang( 'accounts:group_label' ),
						'rules' => 'required'
					),				
				'GroupAkunDetailID' => array(
						'field' => 'GroupAkunDetailID',
						'label' => lang( 'accounts:sub_group_label' ),
						'rules' => ''
					),
			));
		
		parent::__construct();
	}

	public function get_account( $id ){
		
		$query = $this->db->where( $this->primary_key, $id)
					->get( $this->table );
		
		return $query->num_rows() > 0 ? $query->row() : FALSE;
	}
		
	public function get_accounts(){
		
		$query = $this->db->order_by("Akun_No", "ASC")
					->get( $this->table );
		
		return $query->result();
	}

	public function get_concept(){
		
		$query = $this->db->get( "Setup_Akun" );
		
		return ($query->num_rows() > 0) ? $query->row() : FALSE;
	}
	
	public function get_concepts(){
		
		$query = $this->db->order_by("Level_Ke", "ASC")
					->get( "Setup_AkunDetail" );
		
		$collection = array(0 => 0);
		if ($query->num_rows() > 0):  foreach($query->result() as $row ):

				$collection[] = $row;

			endforeach; 
			return $collection;
		endif;
		
		return FALSE;
	}
		
	public function get_option_group()
	{
		$this->db->order_by( 'Group_Name', 'ASC' );
				
		$query = $this->db->get( "Mst_GroupAkun" );
		
		$data  = array();

		if( $query->num_rows() > 0 )
		{
			foreach( $query->result() as $row )
			{
				$data[ $row->Komponen ][ $row->Group_ID ] = $row->Group_Name;
			} 
		} 
		
		return $data;
	}	

	public function get_option_group_detail()
	{
		$this->db->order_by( 'GroupAkunDetailName', 'ASC' );
		
		$query = $this->db->get( "Mst_GroupAkunDetail" );
		
		$data  = array();

		if( $query->num_rows() > 0 )
		{
			foreach( $query->result() as $row )
			{
				$data[ $row->GroupAkunId ][ $row->GroupAkunDetailId ] = $row->GroupAkunDetailName;
			} 
		} 
		
		return $data;
	}
	
	public function get_option_currency()
	{
		$this->db->order_by( 'Currency_default', 'DESC' );
		
		$query = $this->db->get( "Mst_Currency" );
		
		$data  = array();

		if( $query->num_rows() > 0 )
		{
			foreach( $query->result() as $row )
			{
				$data[ $row->Currency_ID ] = $row->Currency_Name;
			} 
		} 
		
		return $data;
	}

	public function get_option_income_loss()
	{
		$this->db->order_by( 'Keterangan', 'ASC' );
				
		$query = $this->db->get( "Setup_LabaRugi" );
		
		$data  = array();

		if( $query->num_rows() > 0 )
		{
			foreach( $query->result() as $row )
			{
				$data[ $row->ID ] = $row->Keterangan;
			} 
		} 
		
		return $data;
	}	
		
	public function find_account ( $where = array()) {
		
		$query = $this->db->where($where)
						->get( $this->table )
						;
		
		if( $query->num_rows() > 0 )
		{
			return $query->row();
		} 
		
		return false;			
	}
	
	public function count_all($where = NULL)
	{
		if (!is_null($where) && !empty($where)){ $this->db->where($where); }
		
		$this->db->where($where);		
		return (int) ($this->db->count_all_results($this->table));
	}

	public function get_child_id ( $where = array() )
	{
		
		$parents = $this->db->select('parent_id')
				->where( $where )
			 	->get('accounting_accounts')
			 	->result();
			 
	
		foreach($parents as $item) {
			$array[] = $item->parent_id;         
		}
		
		$ignore = implode(',', array_unique($array));
		
		
		$data = $this->db->select("id")
						->where_not_in('id', $ignore, FALSE)
						->where( $where )
						->get("accounting_accounts")
						;

		return $data->result_array();
	}	

	public function create_data( $data )
	{	
		$user = $this->simple_login->get_user();
		
		$this->db->trans_begin();
			$this->db->insert( $this->table, $data );
			$insert_id = $this->db->insert_id();
			$data->{$this->primary_key} = $insert_id;
			
			$concepts = $this->get_concepts();
			$parent_level = $data->Level_Ke - 1;
			
			if( $parent_level > 0) 
			{
				$parent_digit = $concepts[ $parent_level ]->Jumlah_Digit;
				$parent_number = substr($data->Akun_No, 0, $parent_digit); 	

				$parent = $this->db->where("Akun_No", $parent_number)->get( $this->table )->row();					
				if( $parent->Induk == 0 && $this->check_account_transaction( $parent->{$this->primary_key} ) )
				{					
					// Mengupdate Rekening anak menjadi Rekening induk jika Rekening anak tsb sudah ada transaksinya.
					$this->db->update("TBJ_Transaksi_Detail", array("Akun_ID" => $data->{$this->primary_key}), array("Akun_ID" => $parent->{$this->primary_key}) );
					$this->db->update("TBJ_Anggaran_Detail", array("Akun_id" => $data->{$this->primary_key}), array("Akun_id" => $parent->{$this->primary_key}) );
					$this->db->update("TBJ_PostedBulanan", array("Akun_ID" => $data->{$this->primary_key}), array("Akun_ID" => $parent->{$this->primary_key}) );
					$this->db->update("TBJ_PostedLabaRugiBulanan", array("Akun_Id" => $data->{$this->primary_key}), array("Akun_Id" => $parent->{$this->primary_key}) );
					$this->db->update("TBCF_PostedBulanan", array("Akun_id" => $data->{$this->primary_key}), array("Akun_id" => $parent->{$this->primary_key}) );
					$this->db->update("TBCF_Detail", array("Akun_id" => $data->{$this->primary_key}), array("Akun_id" => $parent->{$this->primary_key}) );
				}		

				$this->db->update( $this->table, array("Induk" => 1), array( $this->primary_key => $parent->{$this->primary_key}) );
			}
			
			$date = date("Y-m-d");
			$time = date("Y-m-d H:i:s");
			$activities_description = sprintf( "%s # %s # %s", "INPUT COA.", $data->Akun_No, $data->Akun_Name );			
			$this->db->query("EXEC InsertUserActivities '{$date}','{$time}', {$user->User_ID} ,'{$data->Akun_No}','{$activities_description}','{$this->table}'");				
							
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			return FALSE;
		}
		
		$this->db->trans_commit();
		return TRUE;
	}
	
	public function update_data( $data, $id, $account_name_before )
	{	
		$user = $this->simple_login->get_user();
		
		$this->db->trans_begin();
			unset($data->{$this->primary_key}); // Destroy primary key before update
			$this->db->update( $this->table, $data, array( $this->primary_key => $id ));
						
			$date = date("Y-m-d");
			$time = date("Y-m-d H:i:s");
			$activities_description = sprintf( "%s # %s # Rekening Name Before: %s # Rekening Name After: %s", "EDIT COA.", $data->Akun_No, $account_name_before, $data->Akun_Name );			
			$this->db->query("EXEC InsertUserActivities '{$date}','{$time}', {$user->User_ID} ,'{$id}','{$activities_description}','{$this->table}'");			
						
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			return FALSE;
		}
		
		$this->db->trans_commit();
		return TRUE;
	}

	public function delete_data( $data )
	{	
		$user = $this->simple_login->get_user();
		
		$this->db->trans_begin();
			$this->db->delete( $this->table, array( $this->primary_key => $data->{$this->primary_key} ));
									
			$date = date("Y-m-d");
			$time = date("Y-m-d H:i:s");
			$activities_description = sprintf( "%s # %s # %s # %s", "DELETE COA.", $data->Akun_ID, $data->Akun_No, $data->Akun_Name );			
			$this->db->query("EXEC InsertUserActivities '{$date}','{$time}', {$user->User_ID} ,'{$data->Akun_ID}','{$activities_description}','{$this->table}'");			
			
			$concepts = $this->get_concepts();
			$parent_level = $data->Level_Ke - 1;
			if( $parent_level > 0) 
			{
				$parent_digit = $concepts[ $parent_level ]->Jumlah_Digit;
				$parent_number = substr($data->Akun_No, 0, $parent_digit);
				$parent = $this->db->where("Akun_No", $parent_number)->get( $this->table )->row();
				
				if( !account_helper::get_child($parent->Akun_No, $parent->Level_Ke) )
				{
					$this->db->update($this->table, array("Induk" => 0), array($this->primary_key => $parent->{$this->primary_key}));
				}
			}
			
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			return FALSE;
		}
		
		$this->db->trans_commit();
		return TRUE;
	}
		
	public function check_account_transaction( $Akun_ID ){
		$trans = array();
		$trans[] = $this->db->where("Akun_ID", $Akun_ID)->count_all_results("TBJ_Transaksi_Detail");
		$trans[] = $this->db->where(array("Akun_ID" => $Akun_ID, "Saldoawal" => 1))->count_all_results("TBJ_PostedBulanan");
		$trans[] = $this->db->where("Akun_id", $Akun_ID)->count_all_results("TBJ_Anggaran_Detail");
		$trans[] = $this->db->where("Akun_ID", $Akun_ID)->count_all_results("TBJ_PostedBulanan");
		$trans[] = $this->db->where("Akun_Id", $Akun_ID)->count_all_results("TBJ_PostedLabaRugiBulanan");
		$trans[] = $this->db->where("Akun_id", $Akun_ID)->count_all_results("TBCF_PostedBulanan");
		//$trans[] = $this->db->where("Akun_id", $Akun_ID)->count_all_results("TBCF_Detail");
		
		foreach( $trans as $stat)
		{
			if ($stat > 0) return TRUE;
		}
		
		return FALSE;
	}
	
}