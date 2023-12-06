<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends Admin_Controller 
{
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('welcome');
		
		$this->page = "welcome";
	}

	public function index()
	{		
		$this->template->title(sprintf("%s - %s", lang('home'), config_item('company_name')));
		
		$data = [
			'page' => $this->page,
			'user_role' => $this->db->where_in('Group_ID', $this->session->userdata('user_role_id'))->order_by('Nomor_Urut, Group_ID')->get('mGroup')->result()
		];
		
		$this->template
			->build('welcome', $data);
	}
}

/* End of file welcome.php */