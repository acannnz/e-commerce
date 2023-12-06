<?php if ( !defined( 'BASEPATH' ) ){ exit( 'No direct script access allowed' ); }

class Templates extends Admin_Controller
{
	protected $_translation = 'setting_templates';
	
	public function __construct()
    {
        parent::__construct();
        
		$this->load->library(array(
            	'tank_auth',
            	'form_validation' 
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
        $this->load->model( 'settings_model', 'settings' );
			
		$this->template
			->set_layout( "settings" )
			;
    }
	
	public function index( $group = 'user', $email = 'activate_account' )
	{
		$this->_update( $group, $email );
	}
	
	protected function _update( $group = 'user', $email = 'activate_account' )
	{
		$heading = lang('templates:heading');
		$heading_icon = "fa-pencil-square";
		
		$data[ 'page' ]         = $this->page;
        $data[ 'form' ]         = TRUE;
		$data[ 'summernote' ]	= TRUE;
		//$data[ 'codemirror' ]	= TRUE;
        //$data[ 'editor' ]       = TRUE;
        //$data[ 'fuelux' ]       = TRUE;
        //$data[ 'datatables' ]   = TRUE;
		$data[ 'navigation_minimized' ] = TRUE;
		
		$data[ 'templates' ] = array(
				'user' => array( 'activate_account', 'change_email', 'forgot_password', 'registration', 'reset_password' ),
			);
		$data[ 'template_group' ] = $group;
		$data[ 'template_email' ] = $email;
        
		if( $this->input->post() ) 
		{
			
			$this->_save();
		}
		
		$this->template
			->set( "heading", $heading )
			->set( "heading_icon", $heading_icon )
			->set_breadcrumb( lang( 'settings' ), base_url("settings") )
			->set_breadcrumb( $heading )
			->build( 'setting/templates', isset( $data ) ? $data : NULL )
			;
	}
	
	protected function _save( $setting = '' )
    {
        
        
        $this->load->library( 'form_validation' );
        $this->form_validation->set_error_delimiters( '<span style="color:red">', '</span><br>' );
        
		$this->form_validation->set_rules( 'settings', 'Settings', 'required' );
		$this->form_validation->set_rules( 'email', lang('templates:email'), 'required' );
		$this->form_validation->set_rules( 'subject', lang('templates:subject'), 'required' );
		$this->form_validation->set_rules( 'email_template', lang('templates:message'), 'required' );
		
        if ( $this->form_validation->run() == FALSE )
        {
            $this->session->set_flashdata( 'response_status', 'error' );
            $this->session->set_flashdata( 'message', lang( 'message:save_failed' ) );
            $this->session->set_flashdata( 'form_error', validation_errors() );
        }
        else
        {
            $data = array(
					'subject' => $this->input->post( 'subject' ),
					'template_body' => $this->input->post( 'email_template' ) 
				);
			$this->db
				->where( array('email_group' => $this->input->post( 'email' )) )
				->update( 'email_templates', $data );
            
            $this->session->set_flashdata( 'response_status', 'success' );
            $this->session->set_flashdata( 'message', lang( 'message:save_success' ) );            
        }
		
		redirect( current_url() );     
    }
}

