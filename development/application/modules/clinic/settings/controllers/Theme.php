<?php if ( !defined( 'BASEPATH' ) ){ exit( 'No direct script access allowed' ); }

class Theme extends Admin_Controller
{
	protected $_translation = 'setting_theme';
	
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
		$heading = lang('theme:heading');
		$heading_icon = "fa-code";
		
		$data[ 'page' ]         = $this->page;
        $data[ 'form' ]         = TRUE;
		//$data[ 'summernote' ]	= TRUE;
		//$data[ 'codemirror' ]	= TRUE;
        //$data[ 'editor' ]       = TRUE;
        //$data[ 'fuelux' ]       = TRUE;
        //$data[ 'datatables' ]   = TRUE;
		
		$data[ 'theme_collection' ] = array(
				'intuitive' => 'Intuitive',
				'coco' => 'CoCo',
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
			->build( 'setting/theme', isset( $data ) ? $data : NULL )
			;
	}
	
	protected function _save( $setting = '' )
    {
        
        
        $this->load->library( 'form_validation' );
        $this->form_validation->set_error_delimiters( '<span style="color:red">', '</span><br>' );
        
		$this->form_validation->set_rules( 'settings', 'Settings', 'required' );
		$this->form_validation->set_rules( 'website_name', lang( 'site_name' ), 'required' );
        
		if ( $this->form_validation->run() == FALSE )
        {
            $this->session->set_flashdata( 'response_status', 'error' );
            $this->session->set_flashdata( 'message', lang( 'message:save_failed' ) );
            $this->session->set_flashdata( 'form_error', validation_errors() );
        }
        else
        {
            if ( file_exists( $_FILES[ 'iconfile' ][ 'tmp_name' ] ) || is_uploaded_file( $_FILES[ 'iconfile' ][ 'tmp_name' ] ) )
			{
				$this->_upload_favicon( $_FILES );
			}
			
			if ( file_exists( $_FILES[ 'invoicelogo' ][ 'tmp_name' ] ) || is_uploaded_file( $_FILES[ 'invoicelogo' ][ 'tmp_name' ] ) )
			{
				$this->_upload_invoice_logo( $_FILES );
			}
			
			if ( file_exists( $_FILES[ 'appleicon' ][ 'tmp_name' ] ) || is_uploaded_file( $_FILES[ 'appleicon' ][ 'tmp_name' ] ) )
			{
				$this->_upload_appleicon( $_FILES );
			}
			
			if ( file_exists( $_FILES[ 'logofile' ][ 'tmp_name' ] ) || is_uploaded_file( $_FILES[ 'logofile' ][ 'tmp_name' ] ) )
			{
				$this->_upload_logo( $_FILES );
			}
			
			foreach ( $this->input->post() as $key => $value )
            {
                //print "{$key} => {$value}<br>";
				if( 'settings' == $key ){ continue; }
				
                if( ! $this->db->where( 'config_key', $key )->get( 'config' )->num_rows() ){ $this->db->insert( 'config', array('config_key' => $key, 'value' => $value) ); }
				else { $this->db->where( 'config_key', $key )->update( 'config', array('value' => $value) ); }
            }
			//exit();
            
            $this->session->set_flashdata( 'response_status', 'success' );
            $this->session->set_flashdata( 'message', lang( 'message:save_success' ) );
        }
		
		redirect( current_url() );
    }
	
	protected function _upload_favicon( $files )
    {
        
        
        if ( $files )
        {
            $config[ 'upload_path' ]   = './resource/images/';
            $config[ 'allowed_types' ] = 'jpg|jpeg|png|ico';
            $config[ 'max_width' ]     = '300';
            $config[ 'max_height' ]    = '300';
            $config[ 'overwrite' ]     = TRUE;            
			$this->load->library( 'upload', $config );
			
            if ( ! $this->upload->do_upload( 'iconfile' ) )
            {
                $this->session->set_flashdata( 'response_status', 'error' );
                $this->session->set_flashdata( 'message', lang( 'message:logo_upload_error' ) );
                redirect( current_url() );
            }
			
			$data      = $this->upload->data();
			$file_name = $data[ 'file_name' ];
			$data      = array('value' => $file_name);			
			$this->db->where( 'config_key', 'site_favicon' )->update( 'config', $data );
        }
		
		return TRUE;
    }
    
    protected function _upload_appleicon( $files )
    {
        
        
        if ( $files )
        {
            $config[ 'upload_path' ]   = './resource/images/';
            $config[ 'allowed_types' ] = 'jpg|jpeg|png|ico';
            $config[ 'max_width' ]     = '300';
            $config[ 'max_height' ]    = '300';
            $config[ 'overwrite' ]     = TRUE;
            $this->load->library( 'upload', $config );
			
            if ( ! $this->upload->do_upload( 'appleicon' ) )
            {
				$this->session->set_flashdata( 'response_status', 'error' );
                $this->session->set_flashdata( 'message', lang( 'message:logo_upload_error' ) );
                redirect( current_url() );
			}
			
			$data      = $this->upload->data();
			$file_name = $data[ 'file_name' ];
			$data      = array('value' => $file_name);
			$this->db->where( 'config_key', 'site_appleicon' )->update( 'config', $data );
        }
		
		return TRUE;
    }
    
    protected function _upload_logo( $files )
    {
        
        
        if ( $files )
        {
            $config[ 'upload_path' ]   	= './resource/images/';
            $config[ 'allowed_types' ] 	= 'jpg|jpeg|png';
            $config[ 'max_width' ]     	= '300';
            $config[ 'max_height' ]    	= '300';
            $config[ 'remove_spaces' ] 	= TRUE;            
            $config[ 'overwrite' ]	  	= TRUE;
            $this->load->library( 'upload', $config );
			
            if ( ! $this->upload->do_upload( 'logofile' ) )
            {
                $this->session->set_flashdata( 'response_status', 'error' );
                $this->session->set_flashdata( 'message', lang( 'message:logo_upload_error' ) );
                redirect( current_url() );
            }
			
			$filedata  = $this->upload->data();
			$file_name = $filedata[ 'file_name' ];
			$data      = array('value' => $file_name);
			$this->db->where( 'config_key', 'company_logo' )->update( 'config', $data );
        }
		
		return TRUE;
    }
    
	protected function _upload_invoice_logo( $files )
    {
        
        
        if ( $files )
        {
            $config[ 'upload_path' ]   = './resource/images/logos/';
            $config[ 'allowed_types' ] = 'jpg|jpeg|png';
            $config[ 'remove_spaces' ] = TRUE;
            $config[ 'file_name' ]     = 'invoice_logo';
            $config[ 'overwrite' ]     = TRUE;
            $this->load->library( 'upload', $config );
			
            if ( ! $this->upload->do_upload() )
            {
                $this->session->set_flashdata( 'response_status', 'error' );
                $this->session->set_flashdata( 'message', lang( 'message:logo_upload_error' ) );
                redirect( current_url() );
            }
			
			$data      = $this->upload->data();
			$file_name = $data[ 'file_name' ];
			$data      = array('value' => $file_name);
			$this->db->where( 'config_key', 'invoice_logo' )->update( 'config', $data );
        }
		
		return TRUE;
    }
}

