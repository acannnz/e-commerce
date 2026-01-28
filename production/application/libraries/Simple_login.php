<?php if(! defined('BASEPATH')) exit('Akses langsung tidak diperbolehkan');
class Simple_login {
	var $ci = NULL;
 	public function __construct() {
		$this->ci =& get_instance();
 	}

	public function login($username, $password, $shift_id, $database = NULL) 
	{
		if( $database && config_item('multi_database') ){ 
			$this->ci->db = $this->ci->load->database( 'FO', TRUE ); 
		}
		
		$query = $this->ci->db
						->where(array('username'=>$username, 'Status_Aktif' => 1))
						->get("mUser");
		
		// Hash a new password for storing in the database.
		// The function automatically generates a cryptographically safe salt.
		// $hashPassword = password_hash($password, PASSWORD_DEFAULT);	
		
		// Check if the hash of the entered login password, matches the stored hash.
		// The salt and the cost factor will be extracted from $isPasswordCorrect.
		$isPasswordCorrect = password_verify($password, @$query->row()->PasswordWeb);
		
		if($query->num_rows() > 0 && $isPasswordCorrect === TRUE) {
			 
			 $data = $query->row();		
			 
			 if ( $data->Status_Aktif == 'Tidak Aktif' )
			 {
				 $this->ci->session->set_flashdata('response_status','error');
				 $this->ci->session->set_flashdata('message','Status Akun anda belum aktif! Silahkan Hubungi Admin untuk mengaktifkan Akun!');
				 
				 return FALSE;
			 }
			 $this->ci->session->set_userdata('username', $username);
			 $this->ci->session->set_userdata('user_id', $data->User_ID);

			 $this->ci->session->set_userdata('user', $data);
			 $user_role = self::get_user_role($data->User_ID);
			 foreach($user_role as $role):
			 	 $user_role_id[] = $role->Group_ID;
				 $user_role_code[] = $role->Kode_Group;
			 endforeach;
			 
			 $shift = config_item( 'shift' ) !== FALSE ? $this->ci->db->where("IDShift", $shift_id)->get("SIMmShift")->row() : (object) array();
			 $this->ci->session->set_userdata('shift_id', @$shift->IDShift);		 
			 $this->ci->session->set_userdata('shift_name', @$shift->Deskripsi);
			 
			 $this->ci->session->set_userdata('user_role_id', @$user_role_id);		 
			 $this->ci->session->set_userdata('user_role_code', @$user_role_code);

			 			 
			$this->set_shift_session($shift_id);
			 
			$this->set_user_clerk_session();
			 
			 if( $database ){ $this->ci->session->set_userdata('database', $database); }
			 
			 return TRUE;		 
		 }else{
			 $this->ci->session->set_flashdata('response_status','error');
			 $this->ci->session->set_flashdata('message','Maaf Username atau Password Anda salah!');
			 
			 return FALSE;
		 }
		 return FALSE;
 	}
	
	 public function set_shift_session($shift_id)
	 {
		 $shift = config_item( 'shift' ) !== FALSE 
			 ?$this->ci->db->where("IDShift", $shift_id)->get("SIMmShift")->row() 
			 :$this->ci->db->where(['JamMulai >=' => date('H:i:s'), 'JamBerakhir <=' => date('H:i:s')])->get("SIMmShift")->row();
			 
		 $this->ci->session->set_userdata('shift_id', @$shift->IDShift);		 
		 $this->ci->session->set_userdata('shift_name', @$shift->Deskripsi);
	 }

	 public function set_user_clerk_session()
	 {
		 $clerk =$this->ci->db->where([
						 'UserID' => $this->ci->session->userdata('user_id'), 
						 'StatusClerk' => 0
					 ])
					 ->get('SIMtrClerk')
					 ->row_array();
	 
		 if(!empty($clerk))			
			 $this->ci->session->set_userdata($clerk);
	 }

	public function check_login() 
	{
		if($this->ci->session->userdata('user') == '') {
			$this->ci->session->set_flashdata('response_status','error');
			$this->ci->session->set_flashdata('message','Anda belum login!');
			// Custom jika controller queue tidak perlu login
			return ($this->ci->router->fetch_class() == 'queue') ? true : false;
		}
		 
		return true;
	}
	
	public function check_user_role( $roles = FALSE)
	{
		$user_role_code = ['login', 'welcome'];
		$user_role_code = array_merge($user_role_code, (array) $this->ci->session->userdata('user_role_code'));
		$role_accessed = $roles ? $roles : $this->ci->session->flashdata('role_accessed'); // Role yang diakses

		if(!empty($role_accessed))
		{
			$granted = FALSE;
			if(is_array($role_accessed))
			{
				foreach($role_accessed as $role )
				{
					if(in_array($role, $user_role_code))
					{
						$granted = TRUE;
						$role_accessed = $role;
						break;
					}
				}
			}elseif(in_array($role_accessed, $user_role_code)) { 
				$granted = TRUE;
			}
			
			if($granted === TRUE)
			{
				switch($role_accessed):
					case 'reservation':
					case 'registration':
					case 'patients':
					case 'cashier':
						$this->ci->config->set_item('template.theme', 'intuitive');
						$this->ci->config->set_item('template.sidebar', 'registration');	
					break;
					case 'outpatient':
						$this->ci->config->set_item('template.theme', 'intuitive');
						$this->ci->config->set_item('template.sidebar', 'outpatient');	
					break;
					case 'inpatient':
						$this->ci->config->set_item('template.theme', 'intuitive');
						$this->ci->config->set_item('template.sidebar', 'inpatient');	
					break;
					case 'laboratory':
						$this->ci->config->set_item('template.theme', 'intuitive');
						$this->ci->config->set_item('template.sidebar', 'helper');	
					break;
					case 'pharmacy':
						$this->ci->config->set_item('template.theme', 'intuitive');
						$this->ci->config->set_item('template.sidebar', 'pharmacy');	
					break;
					case 'inventory':
						$this->ci->config->set_item('template.theme', 'bracketadmin');
						$this->ci->config->set_item('template.sidebar', 'inventory');	
					break;
					case 'verification':
						$this->ci->config->set_item('template.theme', 'bracketadmin');
						$this->ci->config->set_item('template.sidebar', 'verification');	
					break;
					case 'general_cashier':
						$this->ci->config->set_item('template.theme', 'intuitive');
						$this->ci->config->set_item('template.sidebar', 'general_cashier');	
						$this->ci->db = $this->ci->load->database('BO_1', TRUE);
					break;
					case 'payable':
						$this->ci->config->set_item('template.theme', 'intuitive');
						$this->ci->config->set_item('template.sidebar', 'payable');	
						$this->ci->db = $this->ci->load->database('BO_1', TRUE);
					break;
					case 'receivable':
						$this->ci->config->set_item('template.theme', 'intuitive');
						$this->ci->config->set_item('template.sidebar', 'receivable');	
						$this->ci->db = $this->ci->load->database('BO_1', TRUE);
					break;
					case 'general_ledger':
						$this->ci->config->set_item('template.theme', 'intuitive');
						$this->ci->config->set_item('template.sidebar', 'general_ledger');	
						$this->ci->db = $this->ci->load->database('BO_1', TRUE);
					break;
					case 'admin':
						$this->ci->config->set_item('template.theme', 'bracketadmin');
						$this->ci->config->set_item('template.sidebar', 'admin');	
					break;
					case 'login':
						$this->ci->config->set_item('template.theme', 'intuitive');
						$this->ci->config->set_item('template.sidebar', 'login');	
					break;
					case 'welcome':
						$this->ci->config->set_item('template.theme', 'intuitive');
						$this->ci->config->set_item('template.sidebar', 'welcome');	
					break;
					case 'integration_insurance':
						$this->ci->config->set_item('template.theme', 'intuitive');
						$this->ci->config->set_item('template.sidebar', 'integration_insurance');	
					break;
					case 'reports':
						$this->ci->config->set_item('template.theme', 'intuitive');
						$this->ci->config->set_item('template.sidebar', 'reports');
					break;
				endswitch;
				
				return TRUE;
			} else {
				$this->ci->session->set_flashdata('response_status', 'error');
				$this->ci->session->set_flashdata('message', 'Akses ditolak, Anda tidak memiliki otorisasi untuk mengakses menu tersebut!');
				redirect('');
			}
		}
	}
	
	// cek session petugas medis outpatient/inpatient, RJ/RI
	public function set_medics( $type = 'outpatient')
	{
		if(empty($this->ci->session->userdata($type)))
		{
			redirect("set-medics/{$type}");
		}
	}
	
	// cek session section Farmasi/Apotek
	public function set_pharmacy()
	{
		if(empty($this->ci->session->userdata('pharmacy')))
		{
			redirect("set-pharmacy");
		}
	}
 
 	public function get_user()
	{
		return $this->ci->session->userdata('user');
	} 
 	
	public function get_username()
	{
		return $this->ci->session->userdata('user')->Username;
	}
	
	public function get_user_role($id = FALSE)
 	{
		$user_id = $id ? $id : $this->ci->session->userdata('user_id');	
		$query = $this->ci->db
					->select("a.Group_ID, b.Kode_Group, b.Nama_Group")
					->from("mUserGroup a")
					->join("mGroup b", "a.Group_ID = b.Group_ID", "INNER")
					->where(['a.User_ID'=> $user_id])
					->get();
						
		return $query->result();
	}
 
	 // Fungsi logout
	public function logout() {
		$this->ci->session->userdata = [];
		
		$this->ci->session->set_flashdata('response_status','error');
		$this->ci->session->set_flashdata('message','Anda Berhasil Logout');	 
	}

	public function verify_password($password, $user_id)
	{
		$get_user = $this->ci->db->where('User_ID', $user_id)->get('mUser')->row();
		return password_verify($password, $get_user->PasswordWeb);
	}
}