<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Structure_m extends Public_Model
{
	public $table = 'Mst_GroupAkun';
	public $primary_key = 'Group_ID';
	public $table_detail = 'Mst_GroupAkunDetail';
	public $primary_key_detail = 'GroupAkunDetailId';
	
	public function __construct()
	{
		$this->rules = array(
				'insert' => array(
					'Komponen' => array(
							'field' => 'Komponen',
							'label' => lang( 'structures:component_label' ),
							'rules' => 'required'
						),
					'Group_Name' => array(
							'field' => 'Group_Name',
							'label' => lang( 'structures:group_name_label' ),
							'rules' => 'required'
						),
					'Keterangan' => array(
							'field' => 'Keterangan',
							'label' => lang( 'structures:description_label' ),
							'rules' => ''
						),
			));
		
		parent::__construct();
	}

	public function get_row( $id )
	{
		$query = $this->db
			->where( $this->primary_key, $id)
			->get( $this->table )
			;
			
		return $query->num_rows() > 0 ? $query->row_array() : FALSE;
	}	
	
	public function get_header( $Komponen )
	{
		$query = $this->db
			->where("Komponen", $Komponen)
			->get( $this->table )
			;
			
		return $query->num_rows() > 0 ? $query->result() : FALSE;
	}	

	public function get_detail( $GroupAkunID )
	{
		$query = $this->db
			->where( "GroupAkunId", $GroupAkunID)
			->get( $this->table_detail )
			;
			
		return $query->num_rows() > 0 ? $query->result() : FALSE;
	}	

	public function gen_header_queue( $Komponen )
	{
		$query = $this->db
			->select("MAX(NomorUrut) AS MAX")
			->where( "Komponen", $Komponen)
			->get( $this->table )
			;
			
		return $query->num_rows() > 0 ? $query->row()->MAX + 1 : 1;
	}	
	
	public function create_data( $header, $details)
	{			
			$this->db->trans_begin();
				$this->db->insert( $this->table, $header );
				$header_id = $this->db->insert_id();
				
				foreach( $details as $row ){
					$this->db->insert( $this->table_detail, $this->prepare_detail( $header_id, $row) );				
				}
				
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
				return FALSE;
			}
			
			$this->db->trans_commit();
			return TRUE;
	}	
	
	public function update_data( $id, $header, $details)
	{			
			$this->db->trans_begin();
				$this->db->update( $this->table, array("Group_Name" => $header["Group_Name"], "Keterangan" => $header["Keterangan"]  ), array( $this->primary_key => $id ));
				
				$details_id = array();
				foreach( $details as $row ){
					if ( $row[ $this->primary_key_detail ] != 0 ) {
						$this->db->update( $this->table_detail, $this->prepare_detail($id, $row), array( $this->primary_key_detail => $row[ $this->primary_key_detail ] ) );
						$details_id[] = $row[ $this->primary_key_detail ];
					} else {
						$this->db->insert( $this->table_detail, $this->prepare_detail( $id, $row) );				
						$details_id[] = $this->db->insert_id();
					}
				}
				
				// Delete Details
				$this->db->where_not_in( $this->primary_key_detail, $details_id )
					->where( "GroupAkunId", $id)
					->delete( $this->table_detail );
				
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
				return FALSE;
			}
			
			$this->db->trans_commit();
			return TRUE;
	}	

	public function delete_data( $id)
	{			
			$this->db->trans_begin();
				$this->db->delete( $this->table, array( $this->primary_key => $id ) );
				$this->db->delete( $this->table_detail, array( "GroupAkunId" => $id ) );
				
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
				return FALSE;
			}
			
			$this->db->trans_commit();
			return TRUE;
	}	
		
	private function prepare_detail( $header_id, $data ){
		return array(
			"GroupAkunId" => $header_id,
			"GroupAkunDetailName" => $data['GroupAkunDetailName'],
			"Cash" => $data['Cash'],
			"Bank" => $data['Bank'],
			"NoUrut" => $data['NoUrut'],
		);
	}
		
	public function options_account_level()
	{
		$options = $this
			->as_dropdown( "accounting_account_level" )
			->order_by( "level" )
			->get_all(array("state" => 1, "deleted_at" => NULL))
			;
			
		return (is_array($options) ? $options : array());
	}	
}


