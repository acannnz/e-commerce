<?php
if ( !defined( 'BASEPATH' ) )
    exit( 'No direct script access allowed' );

class Profile extends Admin_Controller
{
    protected $_translation = 'profile';	
	protected $_model = 'profile_model';
    
	public function __construct()
    {
        parent::__construct();
        $this->simple_login->check_user_role('welcome');
        $this->load->model('user_model');
        
		$this->load->language( "settings/setting" );
		$this->template->title( lang( 'profile' ) . ' - ' . $this->config->item( 'company_name' ) );
    }
	
    public function index()
    {
        $data['user_auth'] = $item = $this->user_model->get_one($this->user_auth->User_ID);

        if ($this->input->post()) {
			$post_user = $this->input->post("f");
            $post_user['Status_Aktif'] = $item->Status_Aktif;
			$old_password = $this->input->post("c[OldPasswordWeb]");
			$confirm_password = $this->input->post("c[PasswordWeb]");

			if (!empty($post_user['PasswordWeb'])) {
				$isPasswordCorrect = password_verify($old_password, $item->PasswordWeb);
				if (!$isPasswordCorrect) {
					response_json(['status' => 'error', 'message' => 'Password Lama tidak Valid!']);
				}
				if ($post_user['PasswordWeb'] === $confirm_password) {
					$post_user['PasswordWeb'] = password_hash($post_user['PasswordWeb'], PASSWORD_DEFAULT);
				} else {
					response_json(['status' => 'error', 'message' => 'Password Konfirmasi tidak sama!']);
				}
			} else {
				unset($post_user['PasswordWeb']);
			}

			$this->load->library('form_validation');
			$this->form_validation->set_rules(($item->Username != $post_user['Username']) ? $this->user_model->rules['update'] : $this->user_model->rules['update_unique']);
			$this->form_validation->set_data($post_user);
			if ($this->form_validation->run()) {
				$this->db->trans_begin();

				$this->user_model->update($post_user, $this->user_auth->User_ID);

				if ($this->db->trans_status() === FALSE) {
					$this->db->trans_rollback();
					$response = array(
						"status" => 'error',
						"message" => lang('global:updated_failed'),
						"code" => 500
					);
				} else {
					$this->db->trans_commit();
					$response = array(
						"status" => 'success',
						"message" => 'Update profile berhasil!',
						"code" => 200
					);
				}
			} else {
				$response["message"] = $this->form_validation->get_all_error_string();
				$response["status"] = "error";
				$response["code"] = "500";
			}

			response_json($response);
		}
				
		$data['page'] = "profile_settings";
		$data['form'] = TRUE;
        $data['form_action'] = current_url();

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
                $config[ 'file_name' ]     = strtoupper( 'USER-' . $this->user_auth->Username ) . '-AVATAR';
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
                
                $this->db->where( 'user_id', $this->user_auth->User_ID )->set( 'use_gravatar', 'Y' )->update( Applib::$profile_table );
                
            } //isset( $_POST[ 'use_gravatar' ] ) AND $_POST[ 'use_gravatar' ] == 'on'
            else
            {
                
                $this->db->where( 'user_id', $this->user_auth->User_ID )->set( 'use_gravatar', 'N' )->update( Applib::$profile_table );
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
        $data[ 'activities' ] = $this->profile_model->activities( $this->user_auth->User_ID );
        
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

