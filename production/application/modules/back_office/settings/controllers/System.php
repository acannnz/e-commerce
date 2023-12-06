<?php if ( !defined( 'BASEPATH' ) ){ exit( 'No direct script access allowed' ); }

class System extends Admin_Controller
{
	protected $_translation = 'setting_system';
	
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
		$heading = lang('system:heading');
		$heading_icon = "fa-desktop";
		
		$data[ 'page' ]         = $this->page;
        $data[ 'form' ]         = TRUE;
		//$data[ 'summernote' ]	= TRUE;
		//$data[ 'codemirror' ]	= TRUE;
        //$data[ 'editor' ]       = TRUE;
        //$data[ 'fuelux' ]       = TRUE;
        //$data[ 'datatables' ]   = TRUE;
        $data[ 'countries' ]    = $this->settings->countries();
        $data[ 'locales' ]      = $this->applib->locales();
        $data[ 'timezones' ]    = $this->settings->timezones();
        $data[ 'currencies' ]   = $this->applib->currencies();
        $data[ 'languages' ]    = $this->applib->languages();
        $data[ 'locale_name' ]  = $this->applib->get_any_field( 'locales', array('locale' => config_item( 'locale' )), 'name' );
		
		$data[ 'medical_specialists' ] = array(
				'general' => lang( 'specialists:general' ),
				'Sp.A' => lang( 'specialists:spa' ),
				'Sp.OG' => lang( 'specialists:spog' ),
				'Sp.M' => lang( 'specialists:spm' ),
			);
		
		if( $this->input->post() ) 
		{
			
			$this->_save();
		}
		
		$this->template
			->set( "heading", $heading )
			->set( "heading_icon", $heading_icon )
			->set_breadcrumb( lang( 'settings' ), base_url("settings") )
			->set_breadcrumb( $heading )
			->build( 'setting/system', isset( $data ) ? $data : NULL )
			;
	}
	
	protected function _save( $setting = '' )
    {
        
        
        $this->load->library( 'form_validation' );
        $this->form_validation->set_error_delimiters( '<span style="color:red">', '</span><br>' );
        
		$this->form_validation->set_rules( 'settings', 'Settings', 'required' );
		$this->form_validation->set_rules( 'locale', lang('system:locale'), 'required' );
		$this->form_validation->set_rules( 'timezone', lang('system:timezone'), 'required' );
		$this->form_validation->set_rules( 'date_format', lang('system:default_date_format'), 'required' );
		$this->form_validation->set_rules( 'language', lang('system:default_language'), 'required' );
		$this->form_validation->set_rules( 'default_currency', lang('system:default_currency'), 'required' );
		$this->form_validation->set_rules( 'default_currency_symbol', lang('system:default_currency_symbol'), 'required' );
		$this->form_validation->set_rules( 'decimal_separator', lang('system:decimal_separator'), 'required' );
		$this->form_validation->set_rules( 'thousand_separator', lang('system:thousand_separator'), 'required' );
		$this->form_validation->set_rules( 'file_max_size', lang('system:locale'), 'required' );
        
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
            
            //Set date format for date picker
            switch ( $_POST[ 'date_format' ] )
            {
                case "%d-%m-%Y":
                    $picker  = "dd-mm-yyyy";
                    $phptime = "d-m-Y";
                    break;
                case "%m-%d-%Y":
                    $picker  = "mm-dd-yyyy";
                    $phptime = "m-d-Y";
                    break;
                case "%Y-%m-%d":
                    $picker  = "yyyy-mm-dd";
                    $phptime = "Y-m-d";
                    break;
            }
            $this->db->where( 'config_key', 'date_picker_format' )->update( 'config', array(
                 "value" => $picker 
            ) );
            $this->db->where( 'config_key', 'date_php_format' )->update( 'config', array(
                 "value" => $phptime 
            ) );
            
            $this->session->set_flashdata( 'response_status', 'success' );
            $this->session->set_flashdata( 'message', lang( 'message:save_success' ) );
        }
		
		redirect( current_url() );
    }
}

