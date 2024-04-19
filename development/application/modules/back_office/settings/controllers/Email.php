<?php if ( !defined( 'BASEPATH' ) ){ exit( 'No direct script access allowed' ); }

class Email extends Admin_Controller
{
	protected $_translation = 'setting_email';
	
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
		$heading = lang('email_settings');
		$heading_icon = "fa-envelope-o";
		
		$data[ 'page' ]         = $this->page;
        $data[ 'form' ]         = TRUE;
		//$data[ 'summernote' ]	= TRUE;
		//$data[ 'codemirror' ]	= TRUE;
        //$data[ 'editor' ]       = TRUE;
        //$data[ 'fuelux' ]       = TRUE;
        //$data[ 'datatables' ]   = TRUE;
		
        //$data[ 'countries' ]    = $this->settings->countries();
        //$data[ 'locales' ]      = $this->applib->locales();
        //$data[ 'timezones' ]    = $this->settings->timezones();
        //$data[ 'currencies' ]   = $this->applib->currencies();
        //$data[ 'languages' ]    = $this->applib->languages();
        //$data[ 'locale_name' ]  = $this->applib->get_any_field( 'locales', array('locale' => config_item( 'locale' )), 'name' );
		
		if( $this->input->post() ) 
		{
			$this->_save();
		}
		
		$this->template
			->set( "heading", $heading )
			->set( "heading_icon", $heading_icon )
			->set_breadcrumb( lang( 'settings' ), base_url("settings") )
			->set_breadcrumb( $heading )
			->build( 'setting/email', isset( $data ) ? $data : NULL )
			;
	}
	
	protected function _save( $setting = '' )
    {
        
        
        $this->load->library( 'form_validation' );
        $this->form_validation->set_error_delimiters( '<span style="color:red">', '</span><br>' );
        
		$this->form_validation->set_rules( 'settings', 'Settings', 'required' );
		$this->form_validation->set_rules( 'company_email', lang( 'email:company_email' ), 'required' );
		$this->form_validation->set_rules( 'protocol', lang( 'email:email_protocol' ), 'required' );
		
		if ( $this->form_validation->run() == FALSE )
        {
            $this->session->set_flashdata( 'response_status', 'error' );
            $this->session->set_flashdata( 'form_error', validation_errors() );
            $this->session->set_flashdata( 'message', lang( 'message:save_failed' ) );
        }
        else
        {
            
            foreach ( $_POST as $key => $value )
            {
                if( 'settings' == $key )
				{
					continue;
				}
				
				if ( strtolower( $value ) == 'on' )
                {
                    $value = 'TRUE';
                }
                elseif ( strtolower( $value ) == 'off' )
                {
                    $value = 'FALSE';
                }
                
                $data = array('value' => $value);
                $this->db->where( 'config_key', $key )->update( 'config', $data );
            }
			
            if ( isset( $_POST[ 'smtp_pass' ] ) )
            {
                $raw_smtp_pass = $this->input->post( 'smtp_pass' );
                $smtp_pass = $this->encrypt->encode( $raw_smtp_pass );
                $data = array( 'value' => $smtp_pass );
                $this->db->where( 'config_key', 'smtp_pass' )->update( 'config', $data );
            }
            
			$this->session->set_flashdata( 'response_status', 'success' );
            $this->session->set_flashdata( 'message', lang( 'message:save_success' ) );           
        }
		
		redirect( current_url() );
    }
}

