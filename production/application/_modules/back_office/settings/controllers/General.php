<?php if ( !defined( 'BASEPATH' ) ){ exit( 'No direct script access allowed' ); }

class General extends Admin_Controller
{
	protected $_translation = 'setting_general';
	
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
	
	public function index()
	{
		$this->_update();
	}
	
	protected function _update()
	{
		$heading = lang('general:heading');
		$heading_icon = "fa-desktop";
		
		$data[ 'page' ]         = $this->page;
        $data[ 'form' ]         = TRUE;
		//$data[ 'summernote' ]	= TRUE;
		//$data[ 'codemirror' ]	= TRUE;
        //$data[ 'editor' ]       = TRUE;
        //$data[ 'fuelux' ]       = TRUE;
        //$data[ 'datatables' ]   = TRUE;
        $data[ 'countries' ]    = $this->settings->countries();
        //$data[ 'locales' ]      = $this->applib->locales();
        //$data[ 'timezones' ]    = $this->settings->timezones();
        //$data[ 'currencies' ]   = $this->applib->currencies();
        //$data[ 'languages' ]    = $this->applib->languages();
		//$data[ 'translations' ] = $this->applib->translations();
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
			->build( 'setting/general', isset( $data ) ? $data : NULL )
			;
	}
	
	protected function _save( $setting = '' )
    {
        
        
        $this->load->library( 'form_validation' );
        $this->form_validation->set_error_delimiters( '<span style="color:red">', '</span><br>' );
        
		$this->form_validation->set_rules( 'settings', 'Settings', 'required' );
		
		$this->form_validation->set_rules( 'company_name', lang('general:company_name'), 'required' );
		$this->form_validation->set_rules( 'company_legal_name', lang('general:company_legal_name'), 'required' );
		$this->form_validation->set_rules( 'company_vat', lang('general:company_vat'), 'required' );
		
		$this->form_validation->set_rules( 'company_address', lang('general:company_address'), 'required' );
		$this->form_validation->set_rules( 'city', lang('general:city'), 'required' );
		$this->form_validation->set_rules( 'zip_code', lang('general:zip_code'), 'required' );
		$this->form_validation->set_rules( 'country', lang('general:country'), 'required' );
		
		$this->form_validation->set_rules( 'contact_person', lang('general:contact_person'), 'required' );
		$this->form_validation->set_rules( 'company_phone', lang('general:company_phone'), 'required' );
		$this->form_validation->set_rules( 'company_email', lang('general:company_email'), 'required' );
		$this->form_validation->set_rules( 'company_domain', lang('general:company_domain'), 'required' );
        
		if ( $this->form_validation->run() == FALSE )
        {
            $this->session->set_flashdata( 'response_status', 'error' );
            $this->session->set_flashdata( 'message', lang( 'message:save_failed' ) );
            $this->session->set_flashdata( 'form_error', validation_errors() );
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

