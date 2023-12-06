<?php if ( !defined( 'BASEPATH' ) ){ exit( 'No direct script access allowed' ); }

// Includes all users operations
include APPPATH . '/libraries/Requests.php';

class Translations extends Admin_Controller
{
	protected $_translation = 'setting_translations';
	
	protected $_language_files;
	
	public function __construct()
    {
        parent::__construct();
		
		/* disable module */
		$this->session->set_flashdata( 'response_status', 'error' );
		$this->session->set_flashdata( 'message', lang( 'message:access_denied' ) );
		redirect( 'settings' );
		/* end: disable module */
        
		Requests::register_autoloader();
		
		$this->load->model( 'settings_model', 'settings' );
		$this->load->library(array( 'tank_auth', 'form_validation' ));
        
        $this->user      = $this->tank_auth->get_user_id();
        $this->username  = $this->tank_auth->get_username(); // Set username
        $this->user_role = Applib::get_table_field( Applib::$user_table, array('id' => $this->user), 'role_id' );
		
		if ( $this->user_role != '22' )
        {
            $this->session->set_flashdata( 'response_status', 'error' );
            $this->session->set_flashdata( 'message', lang( 'message:access_denied' ) );
            
			redirect( 'login' );
        }
		
		$this->_language_files  = array(
				"global_lang.php" => APPPATH . "language/",
				"buttons_lang.php" => APPPATH . "language/",
				"tank_auth_lang.php" => APPPATH . "language/",
				//"calendar_lang.php" => "./system/language/",
				//"date_lang.php" => "./system/language/",
				//"db_lang.php" => "./system/language/",
				//"email_lang.php" => "./system/language/",
				//"form_validation_lang.php" => "./system/language/",
				//"ftp_lang.php" => "./system/language/",
				//"imglib_lang.php" => "./system/language/",
				//"migration_lang.php" => "./system/language/",
				//"number_lang.php" => "./system/language/",
				//"profiler_lang.php" => "./system/language/",
				//"unit_test_lang.php" => "./system/language/",
				//"upload_lang.php" => "./system/language/" 
			);
			
		$this->template
			->set_layout( "settings" )
			->set( "is_setting", TRUE )
			;
    }
	
	public function index()
	{
		$this->languages();
	}
	
	public function languages()
	{
		$heading = lang('translations:heading');
		$heading_icon = "fa-language";
		
		$data[ 'languages' ]    = $this->applib->languages();
		$data[ 'available' ]    = $this->available_translations();
		$data[ 'translation_stats' ] = $this->settings->translation_stats( $this->_language_files );
		//$data[ 'language_files' ] = $this->_language_files;
		
		$this->template
			->set( "heading", $heading )
			->set( "heading_icon", $heading_icon )
			->set_breadcrumb( lang( 'settings' ), base_url("settings") )
			->set_breadcrumb( $heading )
			->build( 'translations/languages', isset( $data ) ? $data : NULL )
			;
	}
	
	public function files( $language = 'english' )
	{
		$heading = lang('translations:heading');
		$heading_icon = "fa-language";
		
		$data[ 'translation_stats' ] = $this->settings->translation_stats( $this->_language_files );
		$data[ 'language' ] = $language;
		$data[ 'language_files' ] = $this->_language_files;
		
		$this->template
			->set( "heading", $heading )
			->set( "heading_icon", $heading_icon )
			->set_breadcrumb( lang( 'settings' ), base_url("settings") )
			->set_breadcrumb( $heading )
			->build( 'translations/files', isset( $data ) ? $data : NULL )
			;
	}
	
	public function translation( $language = 'english', $file = '' )
	{
		if ( !file_exists( APPPATH . 'language/' . $language . '/' . $language . '-original-' . config_item( 'version' ) . '.json' ) )
		{
			$this->settings->backup_translation( $language, $this->_language_files, true );
		}
		
		$path = $this->_language_files[ $file . '_lang.php' ];
		$data[ 'language' ] = $language;
		$data[ 'english' ]  = $this->lang->load( $file, 'english', TRUE, TRUE, $path );
		if ( $language == 'english' )
		{
			$data[ 'translation' ] = $data[ 'english' ];
		} else
		{
			$data[ 'translation' ] = $this->lang->load( $file, $language, TRUE, TRUE );
		}
		
		$data[ 'language_file' ] = $file;
		
		$this->template
			->set( "heading", $heading )
			->set( "heading_icon", $heading_icon )
			->set_breadcrumb( lang( 'settings' ), base_url("settings") )
			->set_breadcrumb( $heading )
			->build( 'translations/translation', isset( $data ) ? $data : NULL )
			;
	}
	
	public function add( $language = 'english' )
	{
		$this->settings->add_translation( $language, $this->language_files );
		
		$this->session->set_flashdata( 'response_status', 'success' );
		$this->session->set_flashdata( 'message', lang( 'translation_added_successfully' ) );
		redirect( "settings/translations/{$language}" );
	}
	
	public function edit( $language = 'english' )
	{
		redirect( "settings/translations/{$language}" );
	}
	
	public function active( $language = 'english' )
	{
		$post_data = $this->input->post();
		$this->db->where( 'name', $language )->update( 'languages', $post_data );
		
		redirect( "settings/translations/{$language}" );
	}
	
	public function backup( $language = 'english' )
	{
		if ( !file_exists( './application/language/' . $language . '/' . $language . '-original-' . config_item( 'version' ) . '.json' ) )
		{
			$this->settings->backup_translation( $language, $this->_language_files, true );
		}
		$this->settings->backup_translation( $language, $this->_language_files );
		
		redirect( "settings/translations/{$language}" );
	}
	
	public function restore( $language = 'english' )
	{
		$this->settings->restore_translation( $language, $this->_language_files );
		redirect( "settings/translations/{$language}" );
	}
	
	public function send( $language = 'english' )
	{
		$path     = "./application/language/" . $language . "/" . $language . "-backup.json";
		if ( !file_exists( $path ) )
		{
			$this->settings->backup_translation( $language, $this->_language_files );
		}
		$params[ 'recipient' ]     = 'translations@gitbench.com';
		$params[ 'subject' ]       = 'User submitted translation: ' . ucwords( str_replace( "_", " ", $language ) );
		$params[ 'message' ]       = 'The .json language file is attached';
		$params[ 'attached_file' ] = $path;
		modules::run( 'fomailer/send_email', $params );
		
		redirect( "settings/translations/{$language}" );
	}
	
	protected function _update()
	{
		$heading = lang('system_settings');
		$heading_icon = "fa-desktop";
		
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
		
		$this->_language_files  = array(
				"global_lang.php" => "./application/language/",
				"buttons_lang.php" => "./application/language/",
				"tank_auth_lang.php" => "./application/language/",
				"calendar_lang.php" => "./system/language/",
				"date_lang.php" => "./system/language/",
				"db_lang.php" => "./system/language/",
				"email_lang.php" => "./system/language/",
				"form_validation_lang.php" => "./system/language/",
				"ftp_lang.php" => "./system/language/",
				"imglib_lang.php" => "./system/language/",
				"migration_lang.php" => "./system/language/",
				"number_lang.php" => "./system/language/",
				"profiler_lang.php" => "./system/language/",
				"unit_test_lang.php" => "./system/language/",
				"upload_lang.php" => "./system/language/" 
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
			->build( 'setting/translations', isset( $data ) ? $data : NULL )
			;
	}
	
	protected function _save( $setting = '' )
    {
        
        
        $this->load->library( 'form_validation' );
        $this->form_validation->set_error_delimiters( '<span style="color:red">', '</span><br>' );
        
		$this->form_validation->set_rules( 'settings', 'Settings', 'required' );
		$this->form_validation->set_rules( 'locale', lang('locale'), 'required' );
		$this->form_validation->set_rules( 'timezone', lang('timezone'), 'required' );
		$this->form_validation->set_rules( 'date_format', lang('default_date_format'), 'required' );
		$this->form_validation->set_rules( 'language', lang('default_language'), 'required' );
		$this->form_validation->set_rules( 'default_currency', lang('default_currency'), 'required' );
		$this->form_validation->set_rules( 'default_currency_symbol', lang('default_currency_symbol'), 'required' );
		$this->form_validation->set_rules( 'decimal_separator', lang('decimal_separator'), 'required' );
		$this->form_validation->set_rules( 'thousand_separator', lang('thousand_separator'), 'required' );
		$this->form_validation->set_rules( 'file_max_size', lang('locale'), 'required' );
        
		if ( $this->form_validation->run() == FALSE )
        {
            $this->session->set_flashdata( 'response_status', 'error' );
            $this->session->set_flashdata( 'message', lang( 'settings_update_failed' ) );
            $this->session->set_flashdata( 'form_error', validation_errors() );
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
            $this->session->set_flashdata( 'message', lang( 'settings_updated_successfully' ) );
        }
		
		redirect( current_url() );
    }
	
	public function translations()
    {
        $action = $this->uri->segment( 3 );
        
        if ( $_POST )
        {
            if ( $action == 'save' )
            {
                $_POST[ '_path' ] = $this->_language_files[ $_POST[ '_file' ] . '_lang.php' ];
                return $this->settings->save_translation( $_POST );
            }
			
            if ( $action == 'active' )
            {
                $language = $this->uri->segment( 4 );
                return $this->db->where( 'name', $language )->update( 'languages', $_POST );
            }
        } else
        {
            if ( $action == 'add' )
            {
                $language = $this->uri->segment( 4 );
                $this->settings->add_translation( $language, $this->_language_files );
                $this->session->set_flashdata( 'response_status', 'success' );
                $this->session->set_flashdata( 'message', lang( 'translation_added_successfully' ) );
                redirect( $_SERVER[ 'HTTP_REFERER' ] );
            }
			
            if ( $action == 'backup' )
            {
                $language = $this->uri->segment( 4 );
                if ( !file_exists( './application/language/' . $language . '/' . $language . '-original-' . config_item( 'version' ) . '.json' ) )
                {
                    $this->settings->backup_translation( $language, $this->_language_files, true );
                }
                return $this->settings->backup_translation( $language, $this->_language_files );
            }
			
            if ( $action == 'restore' )
            {
                $language = $this->uri->segment( 4 );
                return $this->settings->restore_translation( $language, $this->_language_files );
            }
            
			if ( $action == 'submit' )
            {
                $language = $this->uri->segment( 4 );
                $path     = "./application/language/" . $language . "/" . $language . "-backup.json";
                if ( !file_exists( $path ) )
                {
                    $this->settings->backup_translation( $language, $this->_language_files );
                }
                $params[ 'recipient' ]     = 'translations@gitbench.com';
                $params[ 'subject' ]       = 'User submitted translation: ' . ucwords( str_replace( "_", " ", $language ) );
                $params[ 'message' ]       = 'The .json language file is attached';
                $params[ 'attached_file' ] = $path;
                return modules::run( 'fomailer/send_email', $params );
            }
            
			$this->index();
        }
    }
    
    public function available_translations()
    {
    	$ex = $this->db->get( 'languages' )->result();
        foreach ( $ex as $e )
        {
            $existing[] = $e->name;
        }
        
		$ln = $this->db->group_by( 'language' )->get( 'locales' )->result();
        foreach ( $ln as $l )
        {
            if ( !in_array( $l->language, $existing ) )
            {
                $available[] = $l;
            }
        }
        
		return $available;
    }
}

