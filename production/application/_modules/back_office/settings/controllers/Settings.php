<?php if ( !defined( 'BASEPATH' ) ){ exit( 'No direct script access allowed' ); }

// Includes all users operations
include APPPATH . '/libraries/Requests.php';

class Settings extends Admin_Controller
{
	public function __construct()
    {
        parent::__construct();
        
        Requests::register_autoloader();
    }
    
    public function index()
    {
    	redirect( 'settings/general' );
    }
}

/* End of file settings.php */ 