<?php defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH."third_party/MX/Config.php";

class MY_Config extends MX_Config
{
	public $_config_paths =	array( FCPATH, APPPATH );
	
	public function __construct()
	{
		// Rewrite config
		if( defined("SITE_DOMAIN") )
		{
			if( file_exists($file_path = SITE_PATH.'/config/config.php') )
			{
				require( $file_path );
				$this->config =& get_config( $config );
			}
		}
		
		parent::__construct();
	}
}

