<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class AUTH_Controller extends MX_Controller
{ 
	protected $data;
	
	protected $login_user;
	public $section;
	protected $autocomplete_min_collect;
	protected $autocomplete_len_collect;
	
	public function __construct()
	{
		parent::__construct();
		
		$this->data = [];
		
		$this->load->language('admin');
		$this->load->language('buttons');		
		$this->load->library('form_validation');
		$this->load->helper('general');
		
		if ( !empty(config_item("section_id")) && config_item("section_id") !== FALSE )
		{
			$this->section = $this->db->where("SectionID", config_item("section_id"))->get("SIMmSection")->row();
		}
		
		// Set app. location
		if (!$this->session->has_userdata('inv_location_id'))
		{
			$inv_location_id = 1368;
			$this->session->set_userdata('inv_location_id', $inv_location_id);
		}
		//define('INV_LOCATION_ID', $this->session->userdata('inv_location_id'));
		
		$this->config->set_item('template.layout', 'auth');
		
	}
}

