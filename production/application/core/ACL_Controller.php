<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ACL_Controller extends MY_Controller
{ 
	public $login_user;
	public $login_house;
	public $login_connect_bpjs;
    
	public $has_connect_bpjs;
	public $has_connect_jasaraharja;
	public $has_connect_kbs;
	
	protected $access_type = "";
    protected $allowed_members = [];
	
	public function __construct()
	{
		parent::__construct();
		
		$CI = get_instance();
		
		$this->load->helper([
				'general', 
				'widget', 
				'activity_logs',
			]);
		
		$this->load->model([
				'crm/Tickets_model',
				'crm/Timesheets_model',
				'messages/Messages_model',
			]);
			
		// Check user's login status, if not logged in redirect to signin page
        $login_user_id = $this->Users_model->login_user_id();
        if (!$login_user_id) 
		{
            redirect('signin');
        }

        // Initialize login users required information
        $this->login_user = $this->Users_model->get_access_info($login_user_id);
		
        // Initialize login users access permissions
        if ($this->login_user->permissions) 
		{
            $permissions = unserialize($this->login_user->permissions);
            $this->login_user->permissions = is_array($permissions) ? $permissions : array();
        } else 
		{
            $this->login_user->permissions = [];
        }
		
		// Initialize login house
		$this->has_connect_bpjs = FALSE;
		$this->has_connect_jasaraharja = FALSE;
		$this->has_connect_kbs = FALSE;
		
		if ('house' == $this->login_user->user_type)
		{
			if ($this->login_house = $this->Users_model->get_access_house($login_user_id))
			{
				$this->login_house->has_connect_bpjs = (1 == $this->login_house->has_connect_bpjs) ? TRUE : FALSE;
				$this->login_house->has_connect_jasaraharja = (1 == $this->login_house->has_connect_jasaraharja) ? TRUE : FALSE;
				$this->login_house->has_connect_kbs = (1 == $this->login_house->has_connect_kbs) ? TRUE : FALSE;
				
				$this->has_connect_bpjs = $this->login_house->has_connect_bpjs;
				$this->has_connect_jasaraharja = $this->login_house->has_connect_jasaraharja;
				$this->has_connect_kbs = $this->has_connect_kbs;
				
				if (TRUE == $this->login_house->has_connect_bpjs)
				{
					if ($this->login_connect_bpjs = $this->Users_model->get_access_connect_bpjs($login_user_id))
					{
						$this->login_connect_bpjs->has_api_reference = (1 == $this->login_connect_bpjs->has_api_reference) ? TRUE : FALSE;
						$this->login_connect_bpjs->has_api_sep = (1 == $this->login_connect_bpjs->has_api_sep) ? TRUE : FALSE;
						$this->login_connect_bpjs->has_api_room = (1 == $this->login_connect_bpjs->has_api_room) ? TRUE : FALSE;
						
						// Set PPK code into system config
						$this->config->set_item('ppk_code', $this->login_connect_bpjs->ppk_code);
						
						if (in_array(TRUE, [$this->login_connect_bpjs->has_api_reference,$this->login_connect_bpjs->has_api_sep,$this->login_connect_bpjs->has_api_room]))
						{
							// Apply connect BPJS into system config 
							$this->config->set_item('home_connect_bpjs', ['default' => [
									'base_url' => @$this->login_connect_bpjs->api_base_url,
									'customer_id' => @$this->login_connect_bpjs->api_cons_id,
									'customer_secret' => @$this->login_connect_bpjs->api_secret_key,
									'customer_ppk_code' => @$this->login_connect_bpjs->ppk_code,
									'customer_expired' => NULL,
									'default_limit' => 20
								]]);
						}
						
						$CI->login_connect_bpjs = $this->login_connect_bpjs;
						
						// Set connect BPJS into Login House
						$this->login_house->connect_bpjs = $this->login_connect_bpjs;
					}
				}
				
				$CI->login_house = $this->login_house;
				
				// Set Login House into Login User
				$this->login_user->user_house = $this->login_house;
			}
		}
		
		$CI->login_user = $this->login_user;
		//print_r($CI->login_user);exit(0);
		//print_r($CI->login_house);exit(0);
	}
	
	//initialize the login user's permissions with readable format
    protected function init_permission_checker($module) 
	{
        $info = $this->get_access_info($module);
        $this->access_type = $info->access_type;
        $this->allowed_members = $info->allowed_members;
    }

    //prepear the login user's permissions
    protected function get_access_info($module) 
	{
        $info = new stdClass();
        $info->access_type = "";
        $info->allowed_members = [];

        //admin users has access to everything
        if ($this->login_user->is_admin) 
		{
            $info->access_type = "all";
        } else 
		{
			//print_r([$this->login_user->permissions, $module]);exit(0);
			
			//not an admin user? check module wise access permissions
            $module_permission = get_array_value($this->login_user->permissions, $module);

            if ($module_permission === "all") 
			{
                //this user's has permission to access/manage everything of this module (same as admin)
                $info->access_type = "all";
            } else if ($module_permission === "specific") 
			{
                //this user's has permission to access/manage sepcific items of this module

                $info->access_type = "specific";
                $module_permission = get_array_value($this->login_user->permissions, $module . "_specific");
                $permissions = explode(",", $module_permission);

                //check the accessable users list
                if ($module === "leave" || $module === "attendance") 
				{
                    $info->allowed_members = [$this->login_user->id];
                    $allowed_teams = [];
                    foreach ($permissions as $vlaue) 
					{
                        $permission_on = explode(":", $vlaue);
                        $type = get_array_value($permission_on, "0");
                        $type_value = get_array_value($permission_on, "1");
                        if ($type === "member") 
						{
                            array_push($info->allowed_members, $type_value);
                        } else if ($type === "team") 
						{
                            array_push($allowed_teams, $type_value);
                        }
                    }

                    if (count($allowed_teams)) 
					{
                        $team = $this->Team_model->get_members($allowed_teams)->result();
                        foreach ($team as $value) 
						{
                            $info->allowed_members+=explode(",", $value->members);
                        }
                    }
                }
            }
        }
        return $info;
    }

    //only allowed to access for team members 
    protected function access_only_team_members() 
	{
        if ($this->login_user->user_type !== "staff") 
		{
            redirect("forbidden");
        }
    }

    //only allowed to access for admin users
    protected function access_only_admin() 
	{
        if (!$this->login_user->is_admin) 
		{
            redirect("forbidden");
        }
    }

    //access only allowed team members
    protected function access_only_allowed_members() 
	{
        if ($this->access_type !== "all") 
		{
            redirect("forbidden");
        }
    }

    //access only allowed team members
    protected function access_only_allowed_members_or_house_contact($house_id) 
	{
        if (!($this->access_type === "all" || $this->login_user->house_id === $house_id)) 
		{
            redirect("forbidden");
        }
    }

    //allowed team members and clint himself can access  
    protected function access_only_allowed_members_or_contact_personally($user_id) 
	{
        if (!($this->access_type === "all" || $user_id === $this->login_user->id)) 
		{
            redirect("forbidden");
        }
    }

    
    //access all team members and house contact
    protected function access_only_team_members_or_house_contact($house_id) 
	{
        if (!($this->login_user->user_type === "staff" || $this->login_user->house_id === $house_id)) 
		{
            redirect("forbidden");
        }
    }
	
	//check ip restriction for none admin users
    protected function check_allowed_ip() 
	{
        if (!$this->login_user->is_admin) 
		{
            $ip = get_real_ip();
            $allowed_ips = $this->Settings_model->get_setting("allowed_ip_addresses");
            if ($allowed_ips) 
			{
                $allowed_ip_array = array_map('trim', preg_split('/\R/', $allowed_ips));
                if (!in_array($ip, $allowed_ip_array)) 
				{
                    redirect("forbidden");
                }
            }
        }
    }
	
	protected function is_logged_house()
	{
		if (! ('house' === $this->login_user->user_type))
		{
			return FALSE;
		}
		return TRUE;
	}
	
	protected function is_logged_personal()
	{
		if (! ('personal' === $this->login_user->user_type))
		{
			return FALSE;
		}
		return TRUE;
	}
	
	protected function is_logged_staff()
	{
		if (! ('staff' === $this->login_user->user_type))
		{
			return FALSE;
		}
		return TRUE;
	}
	
	protected function is_logged_house_admin()
	{
		if (! ('house' === $this->login_user->user_type && 1 === $this->login_user->is_primary_contact))
		{
			return FALSE;
		}
		return TRUE;
	}
	
	protected function is_logged_staff_admin()
	{
		if (! ('staff' === $this->login_user->user_type && 1 === $this->login_user->is_admin))
		{
			return FALSE;
		}
		return TRUE;
	}
	
	protected function get_logged_user_id()
	{
		return (int) $this->login_user->id;
	}
	
	protected function get_logged_user_house_id()
	{
		return (int) $this->login_user->house_id;
	}
}

