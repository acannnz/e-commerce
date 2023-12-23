<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Section_m extends Public_Model
{
	public $table = 'SIMmSection';
	public $primary_key = 'SectionID';
	
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

	public function get_option()
	{
		$query = $this->db->select('SectionID,SectionName')
						->where("StatusAktif", 1)
						->order_by("SectionName", "ASC")
						->get('SIMmSection');
		
		$collection = array();
		if( $query->num_rows() > 0): foreach($query->result() as $row):

			$collection[ $row->SectionID ] = $row->SectionName;

		endforeach; endif;

		return $collection;

	}	

	#statusaktif=1 
	public function get_all()
	{
		$result = array();
		$query = $this->db->select('*')
						->where("StatusAktif", 1)
						->from('SIMmSection')
						->get();
						
		return $query->result();

	}	

}