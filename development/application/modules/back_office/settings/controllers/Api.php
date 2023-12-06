<?php if ( !defined( 'BASEPATH' ) ){ exit( 'No direct script access allowed' ); }

class Api extends Admin_Controller
{
	protected $_translation = 'setting_api';
	
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
		$this->_update();
	}
	
	protected function _update()
	{
		$heading = lang('api:heading');
		$heading_icon = "fa-cloud";
		
		$data[ 'page' ]         = $this->page;
        $data[ 'form' ]         = TRUE;
		
		if( $this->input->post() ) 
		{
			$this->_save();
		}
		
		$this->template
			->set( "heading", $heading )
			->set( "heading_icon", $heading_icon )
			->set_breadcrumb( lang( 'settings' ), base_url("settings") )
			->set_breadcrumb( $heading )
			->build( 'setting/api', isset( $data ) ? $data : NULL )
			;
	}
	
	protected function _save( $setting = '' )
    {
        
        
        $this->load->library( 'form_validation' );
        $this->form_validation->set_error_delimiters( '<span style="color:red">', '</span><br>' );
        
		$this->form_validation->set_rules( 'settings', 'Settings', 'required' );
		$this->form_validation->set_rules( 'x_api_host', lang( 'api:x_api_host' ), 'required' );
		$this->form_validation->set_rules( 'x_api_key', lang( 'api:x_api_key' ), 'required' );
		
		if ( $this->form_validation->run() == FALSE )
        {
            $this->session->set_flashdata( 'response_status', 'error' );
            $this->session->set_flashdata( 'form_error', validation_errors() );
            $this->session->set_flashdata( 'message', lang( 'message:save_failed' ) );
        }
        else
        {
            
            foreach ( $this->input->post() as $key => $value )
            {
                if( 'settings' == $key ){ continue; }
				
				if ( strtolower( $value ) == 'on' ){ $value = 'TRUE'; }
                elseif ( strtolower( $value ) == 'off' ){ $value = 'FALSE'; }
                
                if( ! $this->db->where( 'config_key', $key )->get( 'config' )->num_rows() ){ $this->db->insert( 'config', array('config_key' => $key, 'value' => $value) ); }
				else { $this->db->where( 'config_key', $key )->update( 'config', array('value' => $value) ); }
            }
            
			$this->session->set_flashdata( 'response_status', 'success' );
            $this->session->set_flashdata( 'message', lang( 'message:save_success' ) );           
        }
		
		redirect( current_url() );
    }
}

