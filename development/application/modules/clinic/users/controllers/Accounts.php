<?php if ( !defined( 'BASEPATH' ) ){ exit( 'No direct script access allowed' ); }

class Accounts extends Admin_Controller
{    
    protected $_translation = 'users';
	protected $_model = 'user_model';
	
	public function __construct()
    {
        parent::__construct();
		
		$this->load->language( "settings/setting" );
        
		$this->load->library( 'tank_auth' );
        if ( ! in_array($this->tank_auth->user_role( $this->tank_auth->get_role_id() ), array('admin', 'doctor')) )
        {
            $this->session->set_flashdata( 'response_status', 'error' );
            $this->session->set_flashdata( 'message', lang( 'access_denied' ) );
            redirect( '' );
        }
		
		$this->page = "users_accounts";
    }
    
    public function index()
    {
        $this->template->title( lang( 'users:page' ) . ' - ' . $this->config->item( 'company_name' ) );
        
		$data[ 'page' ]       = $this->page;
        $data[ 'datatables' ] = TRUE;
        $data[ 'form' ]       = TRUE;
        $data[ 'users' ]      = $this->user_model->users();
        $data[ 'roles' ]      = $this->user_model->roles();
		
        $this->template
			//->set_layout( 'users' )
			->set( "heading", lang('users:page') )
			->set_breadcrumb( lang('users:page') )
			->build( 'users', isset( $data ) ? $data : NULL )
			;
    }
    
	public function create()
	{
		$data = array(
				"options_role" => array(),
			);
		
		$roles = $this->user_model->roles();
		foreach( $roles as $role )
		{
			$data[ 'options_role' ][ $role->r_id ] = strtoupper($role->role);
		}
		
		if( $this->input->post() )
		{
			$item = (object) $this->input->post();
			$this->session->set_flashdata( "accounts.create", $item );
			$data['item'] = $item;
			
			Modules::run( "auth/register_user", FALSE );
			if( $this->session->flashdata( 'response_status') == "success" )
			{
				$data["item"] = (object) (array());
				$data["done"] = TRUE;
			}
		} else
		{
			$data['item'] = $this->session->flashdata( "accounts.create" );
		}
		
		$this->load->view( "modal/create", $data );
	}
	
	public function edit( $user_id=0 )
	{
		if ( $this->input->post() )
        {
            if ( $this->config->item( 'demo_mode' ) == 'TRUE' )
            {
                $this->session->set_flashdata( 'response_status', 'error' );
                $this->session->set_flashdata( 'message', lang( 'demo_warning' ) );
                redirect( 'users/accounts' );
            } //$this->config->item( 'demo_mode' ) == 'TRUE'
            
			$this->load->library( 'form_validation' );
            $this->form_validation->set_error_delimiters( '<span style="color:red">', '</span><br>' );
            $this->form_validation->set_rules( 'fullname', 'Full Name', 'required' );
            if ( $this->form_validation->run() == FALSE )
            {
                $this->session->set_flashdata( 'response_status', 'error' );
                $this->session->set_flashdata( 'message', lang( 'operation_failed' ) );
                redirect( 'users/accounts' );
            } //$this->form_validation->run() == FALSE
            else
            {
                $user_id      = $this->input->post( 'user_id' );
                $profile_data = array(
						'fullname' => $this->input->post( 'fullname' ),
						'company' => $this->input->post( 'company' ),
						'phone' => $this->input->post( 'phone' ),
						'language' => $this->input->post( 'language' ),
						'locale' => $this->input->post( 'locale' ) 
					);
                
				if ( $this->input->post( 'department' ) )
                {
                    $profile_data[ 'department' ] = $this->input->post( 'department' );
                } //isset( $_POST[ 'department' ] )
                
                $this->db->where( 'user_id', $user_id )->update( 'account_details', $profile_data );
                
                $params[ 'user' ]            = $this->tank_auth->get_user_id();
                $params[ 'module' ]          = 'Users';
                $params[ 'module_field_id' ] = $user_id;
                $params[ 'activity' ]        = 'activity_updated_system_user';
                $params[ 'icon' ]            = 'fa-edit';
                $params[ 'value1' ]          = $this->input->post( 'fullname' );                
				modules::run( 'activity/log', $params ); //log activity
                
                $this->session->set_flashdata( 'response_status', 'success' );
                $this->session->set_flashdata( 'message', lang( 'user_edited_successfully' ) );
				
                redirect( 'users/accounts' );
            }
        } else
        {
            $data[ 'user_details' ] = $this->user_model->user_details( $user_id );
            $data[ 'languages' ]    = $this->applib->languages();
            $data[ 'locales' ]      = $this->applib->locales();
            $data[ 'roles' ]        = $this->user_model->roles();
            
            $this->load->view( 'modal/edit/user', $data );
        }
	}
	
	public function delete()
    {
        if ( $this->input->post() )
        {
         	if ( config_item( 'demo_mode' ) == 'TRUE' )
            {
                make_flashdata( array(
                    	'response_status' => 'error',
                    	'message' => lang( 'demo_warning' ) 
                	));
                redirect( $this->input->post( 'r_url' ) );
            }
            
            $this->load->library( 'form_validation' );
            $this->form_validation->set_rules( 'user_id', 'User ID', 'required' );
            if ( $this->form_validation->run() == FALSE )
            {
                $this->session->set_flashdata( 'response_status', 'error' );
                $this->session->set_flashdata( 'message', lang( 'delete_failed' ) );
                $this->input->post( 'r_url' );
            }
            else
            {
                $this->load->library( 'applib' );
				
				$user = $this->input->post( 'user_id' );
                if ( Applib::profile_info( $user )->avatar != 'default_avatar.jpg' )
                {
                    unlink( './resource/avatar/' . Applib::profile_info( $user )->avatar );
                }
                
                Applib::delete( Applib::$activities_table, array(
                    	'user' => $user 
                	));
                
                Applib::delete( Applib::$profile_table, array(
                    	'user_id' => $user 
                	));
                Applib::delete( Applib::$user_table, array(
                    	'id' => $user 
                	));
                
				// Log Activity
                $args = array(
						'user' => $this->tank_auth->get_user_id(),
						'module' => 'users',
						'module_field_id' => $user,
						'activity' => 'activity_deleted_system_user',
						'icon' => 'fa-trash-o' 
					);
                
				Applib::create( Applib::$activities_table, $args );
                
                make_flashdata( array(
						'response_status' => 'success',
						'message' => lang( 'user_deleted_successfully' ) 
                	));
                
				redirect( $this->input->post( 'r_url' ) );
            }
        }
        else
        {
            $data[ 'user_id' ] = $this->uri->segment( 4 );
            $this->load->view( "modal/delete_user", $data );
        }
    }
	
	public function auth()
    {
        if ( $this->input->post() )
        {
            if ( $this->config->item( 'demo_mode' ) == 'TRUE' )
            {
                $this->session->set_flashdata( 'response_status', 'error' );
                $this->session->set_flashdata( 'message', lang( 'demo_warning' ) );
                redirect( 'users/accounts' );
            }
            $user_password = $this->input->post( 'password' );
            $username      = $this->input->post( 'username' );
            
            $this->load->library( 'form_validation' );
            $this->form_validation->set_error_delimiters( '<span style="color:red">', '</span><br>' );
            $this->form_validation->set_rules( 'email', 'Email', 'required' );
            $this->form_validation->set_rules( 'username', 'User Name', 'required|trim' );
            
            if ( !empty( $user_password ) )
            {
                $this->form_validation->set_rules( 'password', 'Password', 'trim|required|min_length[' . $this->config->item( 'password_min_length', 'tank_auth' ) . ']|max_length[' . $this->config->item( 'password_max_length', 'tank_auth' ) . ']' );
                $this->form_validation->set_rules( 'confirm_password', 'Confirm Password', 'trim|required|matches[password]' );
            }
            
            if ( $this->form_validation->run() == FALSE )
            {
                $this->session->set_flashdata( 'response_status', 'error' );
                $this->session->set_flashdata( 'message', lang( 'operation_failed' ) );
                redirect( 'users/accounts' );
            }
            else
            {
                
                $user_id = $this->input->post( 'user_id' );
                $args    = array(
						'email' => $this->input->post( 'email' ),
						'role_id' => $this->input->post( 'role_id' ),
						'modified' => date( "Y-m-d H:i:s" ) 
					);
                
                $db_debug           = $this->db->db_debug; //save setting
                $this->db->db_debug = FALSE; //disable debugging for queries
                $result             = $this->db->set( 'username', $username )->where( 'id', $user_id )->update( Applib::$user_table ); //run query
                $this->db->db_debug = $db_debug; //restore setting
                
                if ( !$result )
                {
                    $this->session->set_flashdata( 'response_status', 'error' );
                    $this->session->set_flashdata( 'message', lang( 'username_not_available' ) );
                    redirect( 'users/accounts' );
                }
                
                Applib::update( Applib::$user_table, array(
                     'id' => $user_id 
                ), $args );
                
                if ( !empty( $user_password ) )
                {
                    $this->tank_auth->set_new_password( $user_id, $user_password );
                }
                $name = Applib::profile_info( $user_id )->fullname ? Applib::profile_info( $user_id )->fullname : Applib::login_info( $user_id )->username;
                
                $args = array(
						'user' => $this->tank_auth->get_user_id(),
						'module' => 'Users',
						'module_field_id' => $user_id,
						'activity' => 'activity_updated_system_user',
						'icon' => 'fa-edit',
						'value1' => $name 
					);
                Applib::create( Applib::$activities_table, $args );
                
                $this->session->set_flashdata( 'response_status', 'success' );
                $this->session->set_flashdata( 'message', lang( 'user_edited_successfully' ) );
                redirect( 'users/accounts' );
            }
        }
        else
        {
            $data[ 'user_details' ] = $this->user_model->user_details( $this->uri->segment( 4 ) );
            $data[ 'roles' ]        = $this->user_model->roles();
            
            $this->load->view( 'modal/edit/login', $data );
        }
    }
}

/* End of file account.php */ 


