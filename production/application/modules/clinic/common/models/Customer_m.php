<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Customer_m extends Public_Model
{
	public $table = 'mCustomer';
	public $primary_key = 'Customer_ID';
	
	public function __construct()
	{
		$this->rules = array('insert' => array(
				'code' => array(
						'field' => 'code',
						'label' => lang( 'code_label' ),
						'rules' => 'required'
					),
				'service_title' => array(
						'field' => 'service_title',
						'label' => lang( 'title_label' ),
						'rules' => 'required'
					),
				'service_description' => array(
						'field' => 'service_description',
						'label' => lang( 'description_label' ),
						'rules' => ''
					),
				'service_price' => array(
						'field' => 'service_price',
						'label' => lang( 'price_label' ),
						'rules' => ''
					),
				'state' => array(
						'field' => 'state',
						'label' => lang( 'status_label' ),
						'rules' => ''
					),
			));
		
		parent::__construct();
	}

	public function get_option_customer_category()
	{
		$this->db->order_by( 'Kode_Kategori' );
		
		$query = $this->db->get( "mKategori_Customer" );
		
		$data  = array();

		if( $query->num_rows() > 0 )
		{
			foreach( $query->result() as $row )
			{
				$data[ $row->Kode_Kategori ] = $row->Kategori_Name;
			} 
		} 
		
		return $data;
	}

}