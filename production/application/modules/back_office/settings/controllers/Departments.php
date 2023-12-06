<?php if ( !defined( 'BASEPATH' ) ){ exit( 'No direct script access allowed' ); }

class Departments extends Admin_Controller
{
	protected $_translation = 'setting_departments';
	
	public function __construct()
    {
        parent::__construct();
		
		/* disable module */
		$this->session->set_flashdata( 'response_status', 'error' );
		$this->session->set_flashdata( 'message', lang( 'message:access_denied' ) );
		redirect( 'settings' );
		/* end: disable module */
        
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
		$this->load->library( 'form_validation' );
		
		//$this->form_validation->set_error_delimiters( '<span style="color:red">', '</span><br>' );
			
		$this->template
			->set_layout( "settings" )
			;
    }
	
	public function index()
	{
		$heading = lang('departments:heading');
		$heading_icon = "fa-desktop";
		
		$data[ 'page' ]         = $this->page;
        $data[ 'form' ]         = TRUE;
		$data[ 'datatables' ]   = TRUE;
		
		$data[ 'collection' ] = $this->db->get( 'departments' )->result();
		
		//print_r( $data[ 'collection' ] );exit();
        
		$this->template
			->set( "heading", $heading )
			->set( "heading_icon", $heading_icon )
			->set_breadcrumb( lang( 'settings' ), base_url("settings") )
			->set_breadcrumb( $heading )
			->build( 'departments/list', isset( $data ) ? $data : NULL )
			;
	}
	
	public function create()
	{
		$heading = lang('departments:heading');
		$heading_icon = "fa-desktop";
		
		$data[ 'page' ] = $this->page;
        $data[ 'form' ] = TRUE;
		
		if( $this->input->post() ) 
		{
			
			
			$this->form_validation->set_rules( 'department', lang('departments:department'), 'required' );
			
			if ( $this->form_validation->run() == FALSE )
			{
				$this->session->set_flashdata( 'response_status', 'error' );
				$this->session->set_flashdata( 'message', lang( 'message:save_failed' ) );
				$this->session->set_flashdata( 'form_error', validation_errors() );
			} else
			{
				$post_data = array(
						"department" => $this->input->post( "department" ),
						"description" => $this->input->post( "description" ),
						"state" => $this->input->post( "state" ),
					);
				$this->db->insert( 'departments', $post_data );
				
				$this->session->set_flashdata( 'response_status', 'success' );
            	$this->session->set_flashdata( 'message', lang( 'message:save_success' ) );
			}
			
			redirect( "settings/departments" );
		}
		
		if( $this->input->is_ajax_request() )
		{
			$this->load->view( 
					'departments/department/modal/form', 
					array('form_child' => $this->load->view('departments/department/form', $data, true))
				);
		} else
		{
			$this->template
				->set( "heading", $heading )
				->set( "heading_icon", $heading_icon )
				->set_breadcrumb( lang( 'settings' ), base_url("settings") )
				->set_breadcrumb( $heading )
				->build( 'departments/department/form', isset( $data ) ? $data : NULL )
				;	
		}
	}
	
	public function update( $id )
	{
		$heading = lang('departments:heading');
		$heading_icon = "fa-desktop";
		
		$data[ 'page' ] = $this->page;
        $data[ 'form' ] = TRUE;
		
		$data[ 'item' ] = $this->db->where(array('id' => $id))->get( 'departments' )->row();
		
		if( $this->input->post() ) 
		{
			
			
			$this->form_validation->set_rules( 'settings', 'Settings', 'required' );
			
			if ( $this->form_validation->run() == FALSE )
			{
				$this->session->set_flashdata( 'response_status', 'error' );
				$this->session->set_flashdata( 'message', lang( 'message:save_failed' ) );
				$this->session->set_flashdata( 'form_error', validation_errors() );
			} else
			{
				$post_data = array(
						"department" => $this->input->post( "department" ),
						"description" => $this->input->post( "description" ),
						"state" => $this->input->post( "state" ),
					);				
				$this->db
					->where( 'id', $id )
					->update( 'departments', $post_data );
				
				$this->session->set_flashdata( 'response_status', 'success' );
            	$this->session->set_flashdata( 'message', lang( 'message:save_success' ) );				
			}
			
			redirect( "settings/departments" );			
		}
		
		if( $this->input->is_ajax_request() )
		{
			$this->load->view( 
					'departments/department/modal/form', 
					array('form_child' => $this->load->view('departments/department/form', $data, true))
				);
		} else
		{
			$this->template
				->set( "heading", $heading )
				->set( "heading_icon", $heading_icon )
				->set_breadcrumb( lang( 'settings' ), base_url("settings") )
				->set_breadcrumb( $heading )
				->build( 'departments/department/form', isset( $data ) ? $data : NULL )
				;	
		}
	}
	
	public function delete( $id )
	{
		$id = (int) @$id;		
		$data[ 'item' ] = $this->db->where(array('id' => $id))->get( 'departments' )->row();
		
		if( $this->input->is_ajax_request() )
		{
			$this->load->view( 'departments/department/modal/delete', $data );
		} else
		{
			if( $this->input->post( 'confirm' ) ) 
			{
				
				
				$this->db->delete('departments', array('id' => $id));
				
				$this->session->set_flashdata( 'response_status', 'success' );
            	$this->session->set_flashdata( 'message', lang( 'global:deleted_successfully' ) );
			}
			
			redirect( "settings/departments" );	
		}
	}
}

