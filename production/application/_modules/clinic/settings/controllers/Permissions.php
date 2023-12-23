<?php if ( !defined( 'BASEPATH' ) ){ exit( 'No direct script access allowed' ); }

class Permissions extends Admin_Controller
{
	protected $_translation = 'setting_permissions';
	
	public function __construct()
    {
        parent::__construct();
        
		$this->load->model( 'settings_model', 'settings' );
		
		$this->load->library(array(
            	'tank_auth',
            	'form_validation',
				'encrypt'
        	));
			
        $this->user      = $this->tank_auth->get_user_id();
        $this->username  = $this->tank_auth->get_username(); // Set username
        $this->user_role = Applib::get_table_field( Applib::$user_table, array('id' => $this->user), 'role_id' );
		
		if ( $this->user_role != '22' )
        {
            $this->session->set_flashdata( 'response_status', 'error' );
            $this->session->set_flashdata( 'message', lang( 'message:access_denied' ) );
            
			redirect( 'login' );
        }
		
		$this->page = lang( 'settings' );
			
		$this->template
			->set_layout( "settings" )
			;
    }
	
	public function index()
	{
		$this->staff();
	}
	
	public function staff()
	{
		$heading = lang('permissions:heading');
		$heading_icon = "fa-lock";
		
		$data[ 'page' ]         = $this->page;
        $data[ 'form' ]         = TRUE;
		$data[ 'datatables' ]   = TRUE;
		
		$this->db->join('account_details','account_details.user_id = users.id');
		$users = $this->db
			->where_in("role_id", array(2,3,4,5,6,7,8,77))
			->get(Applib::$user_table)
			->result();
		$data[ 'users' ] = $users;
		
		$this->template
			->set( "heading", $heading )
			->set( "heading_icon", $heading_icon )
			->set_breadcrumb( lang( 'settings' ), base_url("settings") )
			->set_breadcrumb( $heading )
			->build( 'permissions/staff', isset( $data ) ? $data : NULL )
			;
	}
	
	public function permission( $user_id )
	{
		$heading = lang('permissions:heading');
		$heading_icon = "fa-lock";
		
		$user_id = (int) $user_id;
		if( 0 == $user_id )
		{
			redirect( "settings/permissions" );
		}
		
		$data[ 'page' ]         = $this->page;
        $data[ 'form' ]         = TRUE;
		$data[ 'datatables' ]   = TRUE;
		
		$data[ 'account' ] = $this->db->where(array('id' => $user_id))->get( 'users' )->row();
		$data[ 'role' ] = $this->db->where(array('r_id' => @$data[ 'account' ]->role_id))->get( 'roles' )->row();
		$data[ 'profile' ] = $this->db->where(array('user_id' => @$data[ 'account' ]->id))->get( 'account_details' )->row();
		$data[ 'permissions' ] = $this->db->where(array('state' => 1))->get( 'permissions' )->result();
	
		$user_json_permissions = @$data[ 'profile' ]->allowed_modules;
		if ($user_json_permissions == NULL){ $user_json_permissions = '{"settings": "permissions"}'; }
		$data[ 'user_permissions' ] = json_decode($user_json_permissions);
		
		//print_r( $data );exit();
		
		if( $this->input->post() ) 
		{
			
			$this->_save( $user_id );
		}
		
		$this->template
			->set( "heading", $heading )
			->set( "heading_icon", $heading_icon )
			->set_breadcrumb( lang( 'settings' ), base_url("settings") )
			->set_breadcrumb( $heading )
			->build( 'permissions/permission', (isset( $data ) ? $data : NULL) )
			;
	}
	
	protected function _save( $user_id )
    {
        
        
		$permissions = json_encode( $this->input->post() );
		$data = array('allowed_modules' => $permissions);
		$this->db->where( array('user_id' => $user_id))->update( 'account_details', $data );
		
		$this->session->set_flashdata( 'response_status', 'success' );
		$this->session->set_flashdata( 'message', lang( 'message:save_success' ) );
		redirect( "settings/permissions/permission/{$user_id}" );
    }
}

