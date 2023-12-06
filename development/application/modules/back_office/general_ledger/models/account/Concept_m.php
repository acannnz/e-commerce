<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Concept_m extends Public_Model
{
	public $table = 'Setup_Akun';
	public $primary_key = 'Setup_ID';
	public $table_detail = 'Setup_AkunDetail';
	public $primary_key_detail = 'ID';
	
	public function __construct()
	{
		$this->rules = array(
				'insert' => array(
					'Jumlah_Level' => array(
							'field' => 'Jumlah_Level',
							'label' => lang( 'concepts:level_label' ),
							'rules' => 'required'
						),
					'Jumlah_Digit' => array(
							'field' => 'Jumlah_Digit',
							'label' => lang( 'concepts:digit_label' ),
							'rules' => 'required'
						),
					'Keterangan' => array(
							'field' => 'Keterangan',
							'label' => lang( 'concepts:description_label' ),
							'rules' => ''
						),
			));
		
		parent::__construct();
	}

	public function get_row($id)
	{
		$query = $this->db
			->where( $this->primary_key, $id)
			->get( $this->table )
			;
			
		return $query->num_rows() > 0 ? $query->row_array() : FALSE;
	}	

	public function get_detail($id)
	{
		$query = $this->db
			->where( $this->primary_key, $id)
			->get( $this->table_detail )
			;
			
		return $query->num_rows() > 0 ? $query->result() : FALSE;
	}	

	public function update_data( $id, $header, $details)
	{			
			$this->db->trans_begin();
				$this->db->update( $this->table, $header, array( $this->primary_key => $id ));
				
				$details_id = array();
				foreach( $details as $row ){
					if ( $row['ID'] != 0 ) {
						$this->db->update( $this->table_detail, $this->prepare_detail($row), array( $this->primary_key_detail => $row['ID'] ) );
						$details_id[] = $row['ID'];
					} else {
						$this->db->insert( $this->table_detail, $this->prepare_detail($row) );
						$details_id[] = $this->db->insert_id();
					}
				}
				
				// Delete Details
				$this->db->where_not_in( $this->primary_key_detail, $details_id )
					->where( $this->primary_key, $id)
					->delete( $this->table_detail );
				
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
				return FALSE;
			}
			
			$this->db->trans_commit();
			return TRUE;
	}	
	
	private function prepare_detail( $data ){
		return array(
			"Setup_ID" => $data['Setup_ID'],
			"Level_Ke" => $data['Level_Ke'],
			"Jumlah_Digit" => $data['Jumlah_Digit'],
			"Jumlah_Digit2" => $data['Jumlah_Digit2']
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


