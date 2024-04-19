<?php if ( !defined( 'BASEPATH' ) ){ exit( 'No direct script access allowed' ); }

class Database extends Admin_Controller
{
	protected $_translation = 'setting';
	
	public function __construct()
    {
        parent::__construct();
        
		/* disable module */
		$this->session->set_flashdata( 'response_status', 'error' );
		$this->session->set_flashdata( 'message', lang( 'access_denied' ) );
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
            $this->session->set_flashdata( 'message', lang( 'access_denied' ) );
            
			redirect( 'login' );
        }
			
		$this->template
			->set_layout( "settings" )
			->set( "is_setting", TRUE )
			;
    }
	
	public function index()
	{
		$this->session->set_flashdata( 'response_status', 'error' );
		$this->session->set_flashdata( 'message', lang( 'access_denied' ) );
		redirect( 'settings' );
	}
	
	public function backup()
    {
        
		
		$this->load->helper( 'file' );
        $this->load->dbutil();
        $prefs = array(
				'format' => 'zip', // gzip, zip, txt
				'filename' => 'database-full-backup_' . date( 'Y-m-d' ) . '.zip',
				'add_drop' => TRUE, // Whether to add DROP TABLE statements to backup file
				'add_insert' => TRUE, // Whether to add INSERT data to backup file
				'newline' => "\n" // Newline character used in backup file
			);
        $backup =& $this->dbutil->backup( $prefs );
        
        if ( !write_file( './resource/backup/database-full-backup_' . date( 'Y-m-d' ) . '.zip', $backup ) )
        {
            $this->session->set_flashdata( 'response_status', 'error' );
            $this->session->set_flashdata( 'message', 'The resource/backup folder is not writable.' );
            redirect( $_SERVER[ 'HTTP_REFERER' ] );
        }
        $this->load->helper( 'download' );
        force_download( 'database-full-backup_' . date( 'Y-m-d' ) . '.zip', $backup );
    }
}