<?php if ( !defined( 'BASEPATH' ) ){ exit( 'No direct script access allowed' ); }

class Unlock extends MX_Controller
{    
    public function __construct()
    {
        parent::__construct();
		
		if( ! $this->tank_auth->is_logged_in() )
		{
			redirect( 'logout' );
		}
		
		$this->load->language( "tank_auth" );
		$this->load->language( "users" );
        
        $this->load->helper(array('form','url'));
        $this->load->library(array('tank_auth', 'form_validation'));
		
		foreach ( config_item( 'tank_auth' ) as $key => $value )
        {
            $this->config->set_item( $key, $value );
        }
    }
	
	public function index()
	{
//		if( ! $this->tank_auth->is_logged_in() )     
//        {
//            redirect( 'logout' );
//        }
		
		if( $_POST )
		{
			$data[ 'login_by_username' ] = ( config_item( 'login_by_username' ) AND config_item( 'use_username' ) );
			$data[ 'login_by_email' ] = config_item( 'login_by_email' );
			
			$this->form_validation->set_rules( 'login', 'Login', 'trim|required' );
			$this->form_validation->set_rules( 'password', 'Password', 'trim|required' );
			$this->form_validation->set_rules( 'remember', 'Remember me', 'integer' );
			
            $data[ 'errors' ] = array();
            
            if ( $this->form_validation->run() )
            {
                if ( $this->tank_auth->login( 
					$this->form_validation->set_value( 'login' ), 
					$this->form_validation->set_value( 'password' ), 
					$this->form_validation->set_value( 'remember' ), 
					$data[ 'login_by_username' ], 
					$data[ 'login_by_email' ] ) )
                {
                    $this->session->unset_userdata( "lockscreen" );
					
					/*if( $this->session->userdata( 'requested_page' ) )
					{
						redirect( $this->session->userdata( 'requested_page' ) );
					}*/
					
					redirect( "" );                   
                } else
				{
					$message = "";					
					$errors = $this->tank_auth->get_error_message();
					foreach( $errors as $line )
					{ 
						$message = $this->lang->line( $line );
						break;
					}
					
					make_flashdata( array(
							'response_status' => 'error',
							'message' => $message
						));
				}
            } else
			{
				$message = $this->form_validation->get_all_error_string();
				make_flashdata( array(
						'response_status' => 'error',
						'message' => $message,
					));
			}
		}
		
		redirect( 'users/lock' );
	}
}


