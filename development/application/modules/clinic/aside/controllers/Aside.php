<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Aside extends MX_Controller 
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function sidebar()
	{
		/*$data = array(
				'languages' => $this->applib->languages(),
				'num_activities' => $this->db->where('user', $this->tank_auth->get_user_id())->get(Applib::$activities_table)->num_rows(),
			);
		
		$role = $this->tank_auth->user_role($this->tank_auth->get_role_id()); */
		$role = !empty($this->config->item("department")) ? $this->config->item("department") :  "admin";
		$data["user_role"] = $role;
		
		if( in_array($this->template->get_module(), array('settings')) )
		{
			$this->load->view( "sidebar/settings", (isset($data) ? $data : NULL));
		} else
		{
			if( "admin" == strtolower(trim($role)) )
			{
				//return $this->template->load_view( "aside/sidebar", (isset($data) ? $data : NULL), true );
				$this->load->view( "sidebars/registrasi", (isset($data) ? $data : NULL));
			} else
			{
				//return $this->template->load_view( "aside/sidebar/{$role}", (isset($data) ? $data : NULL), true );
				$this->load->view( "sidebar/{$role}", (isset($data) ? $data : NULL));
			}
		}
	}
	
	public function admin_menu()
	{
		$data = array(
				'languages' => $this->applib->languages(),
			);
		
        $this->load->view('admin_menu',isset($data) ? $data : NULL);
	}
	
	public function collaborator_menu()
	{
		$this->load->view('collaborator_menu',isset($data) ? $data : NULL);
	}
	
	public function client_menu()
	{
		$data['languages'] = $this->applib->languages();
        $this->load->view('user_menu',isset($data) ? $data : NULL);
	}
	
	public function top_header()
	{
    	$this->load->view('top_header',isset($data) ? $data : NULL);
	}
	
	public function scripts()
	{
		$this->load->view('scripts/scripts',isset($data) ? $data : NULL);
	}
	
	public function buttom_scripts()
	{
		$data = array();
		
		$this->load->view('scripts/bottom', (isset($data) ? $data : NULL));
	}
	
	public function custom_scripts()
	{
		$data = array();
		
		$this->load->view('scripts/custom', (isset($data) ? $data : NULL));
	}
	
	public function flash_msg()
	{
		$this->load->view('flash_msg',isset($data) ? $data : NULL);
	}
}


/* End of file sidebar.php */