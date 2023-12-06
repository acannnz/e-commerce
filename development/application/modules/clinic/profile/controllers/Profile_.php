<?php
if ( !defined( 'BASEPATH' ) )
    exit( 'No direct script access allowed' );

class Profile extends Admin_Controller
{
    protected $_translation = 'profile';	
	protected $_model = 'profile_model';
	protected $nameroutes = "receivable/aging";
    
	public function __construct()
    {
        parent::__construct();
		$this->simple_login->check_user_role('welcome');
        
		$this->load->language( "settings/setting" );
		$this->template->title( lang( 'profile' ) . ' - ' . $this->config->item( 'company_name' ) );
    }
	
    public function index()
    {
        if ( $this->input->post() )
        {
            if ( $this->config->item( 'demo_mode' ) == 'TRUE' )
            {
                $this->session->set_flashdata( 'response_status', 'error' );
                $this->session->set_flashdata( 'message', lang( 'demo_warning' ) );
                redirect( 'profile/settings' );
            } //$this->config->item( 'demo_mode' ) == 'TRUE'
            
            $this->load->library( 'form_validation' );
            $this->form_validation->set_rules( 'fullname', 'Full Name', 'required' );
            $this->form_validation->set_error_delimiters( '<span style="color:red">', '</span><br>' );
            
            if ( $this->form_validation->run() == FALSE ) // validation hasn't been passed
            {
                $this->session->set_flashdata( 'response_status', 'error' );
                $this->session->set_flashdata( 'message', lang( 'error_in_form' ) );
                $_POST = '';
                $this->settings();
                //redirect('profile/settings');
            } //$this->form_validation->run() == FALSE
            else
            {
                $form_data = $_POST;
                
                $this->db->where( 'user_id', $this->tank_auth->get_user_id() );
                $this->db->update( 'account_details', $form_data );
                
                $this->session->set_flashdata( 'response_status', 'success' );
                $this->session->set_flashdata( 'message', lang( 'profile_updated_successfully' ) );
                redirect( 'profile/settings' );
            }
            
            
        } //$_POST
				
		$data['page'] = "profile_settings";
		$data['form'] = TRUE;

		$this->template
			->set( "heading", lang("profile:edit_heading") )
			->set_breadcrumb( lang("nav:users"), base_url("users/accounts") )
			->set_breadcrumb( lang("profile:edit_heading") )
			->build( 'profile', (isset( $data ) ? $data : NULL) )
			;
    }
    
    public function changeavatar()
    {
        if ( $this->input->post() )
        {
            
            
            
            if ( file_exists( $_FILES[ 'userfile' ][ 'tmp_name' ] ) || is_uploaded_file( $_FILES[ 'userfile' ][ 'tmp_name' ] ) )
            {
                
                $config[ 'upload_path' ]   = './resource/avatar/';
                $config[ 'allowed_types' ] = 'gif|jpg|png|jpeg';
                $config[ 'file_name' ]     = strtoupper( 'USER-' . $this->tank_auth->get_username() ) . '-AVATAR';
                $config[ 'overwrite' ]     = TRUE;
                
                $this->load->library( 'upload', $config );
                
                if ( !$this->upload->do_upload() )
                {
                    $this->session->set_flashdata( 'response_status', 'error' );
                    $this->session->set_flashdata( 'message', lang( 'avatar_upload_error' ) );
                    redirect( $this->input->post( 'r_url', TRUE ) );
                } //!$this->upload->do_upload()
                else
                {
                    $data      = $this->upload->data();
                    $file_name = $this->profile_model->update_avatar( $data[ 'file_name' ] );
                    
                }
            } //file_exists( $_FILES[ 'userfile' ][ 'tmp_name' ] ) || is_uploaded_file( $_FILES[ 'userfile' ][ 'tmp_name' ] )
            
            if ( isset( $_POST[ 'use_gravatar' ] ) AND $_POST[ 'use_gravatar' ] == 'on' )
            {
                
                $this->db->where( 'user_id', $this->tank_auth->get_user_id() )->set( 'use_gravatar', 'Y' )->update( Applib::$profile_table );
                
            } //isset( $_POST[ 'use_gravatar' ] ) AND $_POST[ 'use_gravatar' ] == 'on'
            else
            {
                
                $this->db->where( 'user_id', $this->tank_auth->get_user_id() )->set( 'use_gravatar', 'N' )->update( Applib::$profile_table );
            }
            
            $this->session->set_flashdata( 'response_status', 'success' );
            $this->session->set_flashdata( 'message', lang( 'avatar_uploaded_successfully' ) );
            redirect( $this->input->post( 'r_url', TRUE ) );
            
            
        } //$this->input->post()
        else
        {
            $this->session->set_flashdata( 'response_status', 'error' );
            $this->session->set_flashdata( 'message', lang( 'no_avatar_selected' ) );
            redirect( 'profile/settings' );
        }
    }
    
    public function activities()
    {
        //$this->load->model( 'profile_model' );
        //$this->load->module( 'layouts' );
        //$this->load->library( 'template' );
        
		$this->template->title( lang( 'profile' ) . ' - ' . $this->config->item( 'company_name' ) );
        
		$data[ 'page' ]       = "profile_activities";
        $data[ 'datatables' ] = TRUE;
        $data[ 'activities' ] = $this->profile_model->activities( $this->tank_auth->get_user_id() );
        
		$this->template
			//->set_layout( 'users' )
			->set( "heading", lang("activities") )
			->set( "heading_helper", lang("all_activities") )
			->set_breadcrumb( lang("profile"), base_url("profile") )
			->set_breadcrumb( lang("activities") )
			->build( 'activities', isset( $data ) ? $data : NULL )
			;
    }
    
    public function help()
    {
        //$this->load->model( 'profile_model' );
        //$this->load->module( 'layouts' );
        //$this->load->library( 'template' );
        
		$this->template->title( lang( 'profile' ) . ' - ' . $this->config->item( 'company_name' ) );
        $data[ 'page' ] = lang( 'home' );
        $this->template
			//->set_layout( 'users' )
			->build( 'intro', isset( $data ) ? $data : NULL )
			;
    }
}

/* End of file profile.php */ 

