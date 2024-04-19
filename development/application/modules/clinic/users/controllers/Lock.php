<?php if ( !defined( 'BASEPATH' ) ){ exit( 'No direct script access allowed' ); }

class Lock extends MX_Controller
{    
    public function __construct()
    {
        parent::__construct();
		
		/*if( ! $this->tank_auth->is_logged_in() )
		{
			redirect( 'logout' );
		}*/
		
		$this->load->language( "tank_auth" );
		$this->load->language( "users" );
        
        $this->load->helper(array('form','url'));
      	///$this->load->library(array('tank_auth', 'form_validation'));
		
		$this->load->config( "template" );
		$this->load->library( "parser" );
		$this->load->library( 'template' );
		
		/*foreach ( config_item( 'tank_auth' ) as $key => $value )
        {
            $this->config->set_item( $key, $value );
        }*/
		
		$this->template
			->set_theme( 'intuitive' )
			->set_layout( 'lock' )
			
			->set_partial( 'fonts', 'partials/fonts' )
			->set_partial( 'head', 'partials/lock/head' )
			
			->set_partial( 'header', 'partials/lock/header' )
			->set_partial( 'footer', 'partials/lock/footer' )
			->set_partial( 'modal', 'partials/admin/modal' )
			
			->set_partial( 'styles', 'partials/lock/styles' )
			->set_partial( 'scripts', 'partials/lock/scripts' )
			->set_partial( 'bottom_scripts', 'partials/lock/scripts/bottom' )
			;
    }
	
	public function index()
	{
		$data = array();
		
		/*if( ! $this->tank_auth->is_logged_in() )
		{
			redirect( 'logout' );
		}
		
		$this->load->model( "users/user_model", "user_m" );
		$users = $this->user_m->user_details( $this->tank_auth->get_user_id() );
		$users = $this->user_m->user_details( $this->session->userdata("user_id") );
		$user = $users[0];
		$user->role_name = strtoupper($this->tank_auth->user_role( $user->role_id ));
		$data['user'] = $user;*/
		
		$data['user'] = array();
		//$data['login_by_username'] = ( config_item( 'login_by_username' ) AND config_item( 'use_username' ) );
		//$data['login_by_email'] = config_item( 'login_by_email' );
		
		if( ! $this->session->has_userdata( "lockscreen" ) )
		{
			$this->session->set_userdata( "lockscreen", @time() );
		}
		$this->template
			->build( "lock/user", (isset( $data ) ? $data : NULL));
	}
}


