<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Income_loss_setup_m extends Public_Model
{
	public $table = 'Mst_Akun';
	public $primary_key = 'Akun_ID';
	
	public $rules;
	
	public function __construct()
	{
		$this->rules = array('insert' => array(
				$this->primary_key => array(
						'field' => $this->primary_key,
						'label' => lang( 'accounts:account_label_label' ),
						'rules' => 'required'
					),
				'Type_Akun' => array(
						'field' => 'Type_Akun',
						'label' => lang( 'accounts:type_label' ),
						'rules' => 'required'
					),
			));
		
		parent::__construct();
	}
	
	public function get_row( $id )
	{
		$query = $this->db->where( $this->primary_key, $id)->get( $this->table );
		
		return $query->num_rows() > 0 ? $query->row_array() : FALSE;
	}
	
	public function get_option_income_loss()
	{
		$this->db->order_by( 'Keterangan', 'ASC' )
				->where( 'MultiCurrency', 0 )
				->select("a.*, b.{$this->primary_key}, b.Akun_Name, b.Akun_No");
				
		$query = $this->db->from( "Setup_LabaRugi a" )
						->join("{$this->table} b", "a.ID = b.Type_Akun", "LEFT OUTER")
						->get();

		return ( $query->num_rows() > 0 ) ? $query->result() : FALSE ;
	}	
	
	public function setup_data( $data )
	{

		$this->db->trans_begin();
			
			$this->db->update($this->table, array("Type_Akun" => $data['Type_Akun']), array($this->primary_key => $data[$this->primary_key]) );
						
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			return FALSE;
		}
		
		$this->db->trans_commit();
		return TRUE;
	}
		
}