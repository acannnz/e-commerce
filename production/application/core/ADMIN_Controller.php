<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ADMIN_Controller extends MX_Controller
{ 
	protected $data;
	
	protected $login_user;
	
	public $user_auth;
	public $section;
	
	protected $autocomplete_min_collect;
	protected $autocomplete_len_collect;
	
	protected $_translation;	
	protected $_model;
	
	public function __construct()
	{
		parent::__construct();

		if ( $this->simple_login->check_login() === FALSE ) 
		{
			$this->session->set_flashdata('response_status', 'error');
			$this->session->set_flashdata('message', lang('access_denied'));
			
			if( $this->input->is_ajax_request() )
			{
				exit( lang('access_denied') );
			}			
			redirect('login');			
		}

		$this->user_auth = $this->simple_login->get_user();				
		if ( config_item('shift')) {
			$this->user_auth->shift_id = @$this->session->userdata("shift_id");
			$this->user_auth->shift_name = @$this->session->userdata("shift_name");
		}

		if ( !empty(config_item("section_id")) && config_item("section_id") !== FALSE )
		{
			$this->section = $this->db->where("SectionID", config_item("section_id"))->get("SIMmSection")->row();
		}
		
		$this->data = [];
		$this->data['active_menu'] = 'dashboard';
		
		$this->load->language('global');
		$this->load->language('admin');
		$this->load->language('nav');
		$this->load->language('buttons');

		$this->load->library('form_validation');
		$this->load->library('datatables');
		
		$this->load->helper('general');
		
		// Set app. location
		if (!$this->session->has_userdata('inv_location_id'))
		{
			$inv_location_id = 1368;
			$this->session->set_userdata('inv_location_id', $inv_location_id);
		}
		//define('INV_LOCATION_ID', $this->session->userdata('inv_location_id'));
		
		$this->config->set_item('template.layout', 'admin');
		
		if( $this->_translation )
		{
			$this->load->language( $this->_translation );
		}
		
		if( $this->_model )
		{
			$this->load->model( $this->_model );
		}
	}
	
	public function get_model()
	{
		$module_path = @explode( "/", $this->_model );
		$module_name = @end( $module_path );
		
		return $this->{ $module_name };
	}
}

