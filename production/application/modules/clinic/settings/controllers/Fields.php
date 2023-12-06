<?php if ( !defined( 'BASEPATH' ) ){ exit( 'No direct script access allowed' ); }

class Fields extends Admin_Controller
{
	protected $_translation = 'setting_fields';
	
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
	
	public function index( $department_id )
	{
		$heading = lang('fields:heading');
		$heading_icon = "fa-desktop";
		
		$department_id = (int) @$department_id;
		if( 0 == $department_id )
		{
			redirect( "settings/fields/department" );
		}
		
		$data[ 'page' ]         = $this->page;
        $data[ 'form' ]         = TRUE;
		
		$data[ 'department_id' ] = $department_id;
		$data[ 'department' ] = $this->db->where(array('id' => $department_id))->get( 'departments' )->row();
		$data[ 'fields' ] = $this->db->where(array('department_id' => $department_id))->get('fields')->result();
		
		$this->template
			->set( "heading", $heading )
			->set( "heading_icon", $heading_icon )
			->set_breadcrumb( lang( 'settings' ), base_url("settings") )
			->set_breadcrumb( $heading )
			->build( 'fields/fields', isset( $data ) ? $data : NULL )
			;
	}
	
	public function create( $department_id )
	{
		$heading = lang('fields:heading');
		$heading_icon = "fa-desktop";
		
		$department_id = (int) @$department_id;
		if( 0 == $department_id )
		{
			redirect( "settings/fields/department" );
		}
		
		$data[ 'page' ]         = $this->page;
        $data[ 'form' ]         = TRUE;
		
		$data[ 'department_id' ] = $department_id;
		$data[ 'department' ] = $this->db->where(array('id' => $department_id))->get( 'departments' )->row();
		$data[ 'item' ] = (object) array( 'id' => 0, 'name' => '', 'label' => '', 'type' => 'text', 'state' => 0 );
		
		if( $this->input->post() ) 
		{
			
			
			$this->form_validation->set_rules( 'name', lang('fields:field_name'), 'required' );
			$this->form_validation->set_rules( 'label', lang('fields:field_label'), 'required' );
			$this->form_validation->set_rules( 'type', lang('fields:field_type'), 'required' );
			// $this->form_validation->set_rules( 'department_id', lang('fields:department'), 'required' );
						
			if ( $this->form_validation->run() == FALSE )
			{
				$this->session->set_flashdata( 'response_status', 'error' );
				$this->session->set_flashdata( 'message', lang( 'message:save_failed' ) );
				$this->session->set_flashdata( 'form_error', validation_errors() );
			} else
			{
				$post_data = array(
						"name" => $this->input->post( "name" ),
						"label" => $this->input->post( "label" ),
						"type" => $this->input->post( "type" ),
						"state" => $this->input->post( "state" ),
						"uniq_id" => sprintf( "FF%d", @time() ),
						"department_id" => $department_id,
					);
				$this->db->insert( 'fields', $post_data );
				
				$this->session->set_flashdata( 'response_status', 'success' );
            	$this->session->set_flashdata( 'message', lang( 'message:save_success' ) );
				redirect( "settings/fields/index/{$department_id}" );
			}
		}
		
		if( $this->input->is_ajax_request() )
		{
			$this->load->view( 
					'fields/field/modal/form', 
					array('form_child' => $this->load->view('fields/field/form', $data, true))
				);
		} else
		{
			$this->template
				->set( "heading", $heading )
				->set( "heading_icon", $heading_icon )
				->set_breadcrumb( lang( 'settings' ), base_url("settings") )
				->set_breadcrumb( $heading )
				->build( 'fields/field/form', isset( $data ) ? $data : NULL )
				;	
		}
	}
	
	public function update( $department_id, $field_id )
	{
		$heading = lang('fields:heading');
		$heading_icon = "fa-desktop";
		
		$department_id = (int) @$department_id;
		if( 0 == $department_id )
		{
			redirect( "settings/fields/department" );
		}
		
		$field_id = (int) @$field_id;
		if( 0 == $field_id )
		{
			redirect( "settings/fields/department" );
		}
		
		$data[ 'page' ]         = $this->page;
        $data[ 'form' ]         = TRUE;
		
		$data[ 'department_id' ] = $department_id;
		$data[ 'department' ] = $this->db->where(array('id' => $department_id))->get( 'departments' )->row();
		$data[ 'item' ] = $this->db->where(array('department_id' => $department_id, 'id' => $field_id))->get( 'fields' )->row();
		
		if( $this->input->post() ) 
		{
			
			
			$this->form_validation->set_rules( 'name', lang('fields:field_name'), 'required' );
			$this->form_validation->set_rules( 'label', lang('fields:field_label'), 'required' );
			$this->form_validation->set_rules( 'type', lang('fields:field_type'), 'required' );
			// $this->form_validation->set_rules( 'department_id', lang('fields:department'), 'required' );
			
			if ( $this->form_validation->run() == FALSE )
			{
				$this->session->set_flashdata( 'response_status', 'error' );
				$this->session->set_flashdata( 'message', lang( 'message:save_failed' ) );
				$this->session->set_flashdata( 'form_error', validation_errors() );
			} else
			{
				$post_data = array(
						"name" => $this->input->post( "name" ),
						"label" => $this->input->post( "label" ),
						"type" => $this->input->post( "type" ),
						"state" => $this->input->post( "state" ),
					);			
				$this->db
					->where( 'id', $id )
					->update( 'fields', $post_data );
				
				$this->session->set_flashdata( 'response_status', 'success' );
            	$this->session->set_flashdata( 'message', lang( 'message:save_success' ) );
				redirect( "settings/fields/index/{$department_id}" );			
			}	
		}
		
		if( $this->input->is_ajax_request() )
		{
			$this->load->view( 
					'fields/field/modal/form', 
					array('form_child' => $this->load->view('fields/field/form', $data, true))
				);
		} else
		{
			$this->template
				->set( "heading", $heading )
				->set( "heading_icon", $heading_icon )
				->set_breadcrumb( lang( 'settings' ), base_url("settings") )
				->set_breadcrumb( $heading )
				->build( 'fields/field/form', isset( $data ) ? $data : NULL )
				;	
		}
	}
	
	public function delete( $department_id, $field_id )
	{
		$heading = lang('fields:heading');
		$heading_icon = "fa-desktop";
		
		$department_id = (int) @$department_id;
		if( 0 == $department_id )
		{
			redirect( "settings/fields/department" );
		}
		
		$field_id = (int) @$field_id;
		if( 0 == $field_id )
		{
			redirect( "settings/fields/department" );
		}
		
		$data[ 'page' ]         = $this->page;
        $data[ 'form' ]         = TRUE;
		
		$data[ 'department_id' ] = $department_id;
		$data[ 'department' ] = $this->db->where(array('id' => $department_id))->get( 'departments' )->row();
		$data[ 'item' ] = $this->db->where(array('department_id' => $department_id, 'id' => $field_id))->get( 'fields' )->row();
		
		if( $this->input->is_ajax_request() )
		{
			$this->load->view( 'fields/field/modal/delete', $data );
		} else
		{
			if( $this->input->post( 'confirm' ) ) 
			{
				
				
				$this->db->delete('fields', array('id' => $field_id));
				
				$this->session->set_flashdata( 'response_status', 'success' );
            	$this->session->set_flashdata( 'message', lang( 'global:deleted_successfully' ) );
			}
			
			redirect( "settings/fields/index/{$department_id}" );
		}
	}
	
	public function department( $department = '' )
	{
		$heading = lang('fields:heading');
		$heading_icon = "fa-desktop";
		
		$data[ 'collection' ] = $this->db->get( 'departments' )->result();
		
		if( $this->input->post() ) 
		{
			$department_id = (int) $this->input->post( 'department_id' );
			if( 0 < $department_id )
			{
				redirect( "settings/fields/index/{$department_id}" );
			}
		}
		
		if( $this->input->is_ajax_request() )
		{
			$this->load->view( 
					'fields/modal/department', 
					array('form_child' => $this->load->view('fields/department', $data, true))
				);
		} else
		{
			$this->template
				->set( "heading", $heading )
				->set( "heading_icon", $heading_icon )
				->set_breadcrumb( lang( 'settings' ), base_url("settings") )
				->set_breadcrumb( $heading )
				->build( 'fields/department', isset( $data ) ? $data : NULL )
				;	
		}
	}
}

