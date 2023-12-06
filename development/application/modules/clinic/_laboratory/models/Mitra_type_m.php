<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mitra_type_m extends Public_Model
{
	public $table = 'SIMmJenisKerjasama';
	public $primary_key = 'JenisKerjasamaID';
	
	public $rules;
	
	public function __construct()
	{
		$this->rules = array('insert' => array(
			array(
					'field' => 'KategoriTestID',
					'label' => lang('registrations:registration_number_label'),
					'rules' => ''
				),
			array(
					'field' => 'KategoriTestNama',
					'label' => lang('registrations:date_label'),
					'rules' => ''
				),
			array(
					'field' => 'FormatDefault',
					'label' => lang('registrations:time_label'),
					'rules' => ''
				),
			array(
					'field' => 'NoUrut',
					'label' => lang('registrations:mr_number_label'),
					'rules' => ''
				),
			array(
					'field' => 'FormatNo',
					'label' => lang('registrations:type_label'),
					'rules' => ''
				),
		));
		
		parent::__construct();
	}
	

}