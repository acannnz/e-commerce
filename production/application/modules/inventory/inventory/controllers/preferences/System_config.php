<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class System_config extends ADMIN_Controller
{
	protected $nameroutes;
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('inventory');
		
		$this->data['nameroutes'] = $this->nameroutes = 'inventory/preferences/system_config'; 
		
		$this->load->language('inventory');		
		$this->load->library('inventory');
		$this->load->helper('inventory');
		
		$this->load->model('system_config_model');
	}
	
	//load note list view
	public function index()
	{
		$config = [];
		$populate = $this->get_model()->get_all();
		foreach($populate as $item)
		{
			$config[$item->SetupName] = $item->Nilai;
		}
		$this->data['config'] = $config;
		
		$this->template
			->title(lang('heading:system_config'))
			->set_breadcrumb(lang('heading:preferences'), site_url($this->nameroutes))
			->set_breadcrumb(lang('heading:system_config'))
			->build("preferences/system_config/form", $this->data);
	}
	
	public function index_post()
	{
		if ($populate_data = $this->input->post('f')) 
		{
			foreach ($populate_data as $name => $value)
			{
				if (!$this->get_model()->count_all(['SetupName' => $name]))
				{
					$this->get_model()->update(['Nilai' => $value, 'SetupName' => $name]);
				} else 
				{
					$this->get_model()->update_by(['Nilai' => $value], ['SetupName' => $name]);
				}
			}
			
			echo response_json(["success" => true, 'message' => lang('message:update_success')]);
		} else
		{
			echo response_json(["success" => false, 'message' => lang('message:update_failed')]);
		}
	}
	
	public function get_model()
	{
		return $this->system_config_model;
	}
}

