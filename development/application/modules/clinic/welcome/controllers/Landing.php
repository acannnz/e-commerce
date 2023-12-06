<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Landing extends Admin_Controller 
{
	public function __construct()
	{
		parent::__construct();

		if( $this->simple_login->check_login() === FALSE ) 
		{
			$this->session->set_flashdata('message',lang('login_required'));
			redirect('logout');
		}
		$this->page = "landing";
	}

	public function index( $role = 'welcome' )
	{
		$this->simple_login->check_user_role($role);
		load_template($role);
		$this->template->title(sprintf("%s - %s", lang('home'), config_item('company_name')));
		
		$data = [
			'page' => $this->page,
			'is_dashboard' => TRUE,
			'form' => TRUE,
			'navigation_minimized' => TRUE,
		];
				
		$this->template
			->build("landing/{$role}", $data);
	}
}

/* End of file landing.php */