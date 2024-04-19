<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Type_m extends Public_Model
{
	public $table = 'AP_mTypeHutang';
	public $primary_key = 'TypeHutang_ID';
	
	public function __construct()
	{
		$this->rules = array(
				'insert' => array(
					'Nama_Type' => array(
							'field' => 'Nama_Type',
							'label' => lang( 'types:type_label' ),
							'rules' => 'required'
						),
					'Akun_ID' => array(
							'field' => 'Akun_ID',
							'label' => lang( 'types:account_label' ),
							'rules' => 'required'
						),
					'Default_Type_Hutang' => array(
							'field' => 'Default_Type_Hutang',
							'label' => lang( 'types:default_label' ),
							'rules' => ''
						),
			));
		
		parent::__construct();
	}
	
	public function get_option_type()
	{
		$this->db->order_by( 'Default_Type_Hutang', 'DESC' );
		
		$query = $this->db->get( "AP_mTypeHutang" );
		
		$data  = array();

		if( $query->num_rows() > 0 )
		{
			foreach( $query->result() as $row )
			{
				$data[ $row->TypeHutang_ID ] = $row->Nama_Type;
			} 
		} 
		
		return $data;
	}	
	
	public function get_row( $id )
	{
		$this->load->model("general_ledger/account_m");
		$query = $this->db->select("a.*, b.Akun_No, b.Akun_Name")
						->from( "{$this->table} a" )
						->join("{$this->account_m->table} b", "a.Akun_ID = b.Akun_ID", "LEFT OUTER")
						->where( $this->primary_key, $id)
						->get();
								
		return $query->row_array();	
	}

	public function get_result()
	{
		$this->load->model("general_ledger/account_m");
		$query = $this->db->select("a.*, b.Akun_No, b.Akun_Name")
						->from( "{$this->table} a" )
						->join("{$this->account_m->table} b", "a.Akun_ID = b.Akun_ID", "LEFT OUTER")
						->get();
								
		return $query->result();	
	}

	public function get_type_by_account_id( $account_id )
	{
		$query = $this->db->select(" a.* ")
						->from( $this->table." a" )
						->join("accounting_accounts b", "a.account_id = b.id", "LEFT OUTER")
						->where( "b.id", $account_id)
						->get()
						;

		if ($query->num_rows() > 0 )
		{
			return $query->row();
		}
		
		return false;
	
	}
	
	public function create_data ( $post_data )
	{
		
		$this->db->trans_begin();
			$this->db->insert( $this->table, $post_data );
			$insert_id = $this->db->insert_id();
			
			if ( $post_data['Default_Type_Hutang'] )
			{
				$this->db->update( $this->table, array("Default_Type_Hutang" => 0), array( "{$this->primary_key} !=" => $insert_id));
			}			
		
			$user = $this->simple_login->get_user();
			$date = date("Y-m-d");
			$time = date("Y-m-d H:i:s");
			$activities_description = sprintf( "%s # %s # %s # %s ", "INSERT TYPE PIUTANG.", $id, $post_data['Nama_Type'], $post_data['Akun_ID'] );			
			$this->db->query("EXEC InsertUserActivities '{$date}','{$time}', {$user->User_ID} ,'{$id}','{$activities_description}','{$this->table}'");				

		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			return FALSE;
		}

		$this->db->trans_commit();
		return TRUE;
	}

	public function update_data( $post_data, $id )
	{
		
		$this->db->trans_begin();
			$this->db->update( $this->table, $post_data, array( $this->primary_key => $id) );
			
			if ( $post_data['Default_Type_Hutang'] )
			{
				$this->db->update( $this->table, array("Default_Type_Hutang" => 0), array( "{$this->primary_key} !=" => $id));
			}			

			$user = $this->simple_login->get_user();
			$date = date("Y-m-d");
			$time = date("Y-m-d H:i:s");
			$activities_description = sprintf( "%s # %s # %s # %s # %s # %s", "UPDATE TYPE PIUTANG.", $id, $post_data['Nama_Type'], $post_data['Akun_ID'], $post_data['Akun_No'], $post_data['Akun_Name'] );			
			$this->db->query("EXEC InsertUserActivities '{$date}','{$time}', {$user->User_ID} ,'{$id}','{$activities_description}','{$this->table}'");				
		
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			return FALSE;
		}

		$this->db->trans_commit();
		return TRUE;
	}

	public function delete_data( $post_data, $id )
	{
		
		# DELETE FROM AP_mTypeHutang  WHERE TypeHutang_ID=6
		# EXEC InsertUserActivities '2018-01-24','2018-01-24 14:27:07',1145,'6','DELETE TYPE PIUTANG.#6#tes2# 2469# 1010301009',''

		$this->db->trans_begin();
			$this->db->delete( $this->table, array( $this->primary_key => $id) );
			
			$user = $this->simple_login->get_user();
			$date = date("Y-m-d");
			$time = date("Y-m-d H:i:s");
			$activities_description = sprintf( "%s # %s # %s # %s # %s # %s", "DELETE TYPE PIUTANG.", $id, $post_data['Nama_Type'], $post_data['Akun_ID'], $post_data['Akun_No'], $post_data['Akun_Name'] );			
			$this->db->query("EXEC InsertUserActivities '{$date}','{$time}', {$user->User_ID} ,'{$id}','{$activities_description}','{$this->table}'");				
		
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			return FALSE;
		}

		$this->db->trans_commit();
		return TRUE;
	}
}


