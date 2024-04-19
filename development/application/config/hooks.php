<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	http://codeigniter.com/user_guide/general/hooks.html
|
*/

/*$hook['display_override'][] = array(
	'class'  	=> 'Develbar',
    'function' 	=> 'debug',
    'filename' 	=> 'Develbar.php',
    'filepath' 	=> 'third_party/DevelBar/hooks'
);*/

// Load Config from DB
$hook['pre_controller'][] = array(
		'class'    => '',
		'function' => 'load_config',
		'filename' => 'App_config.php',
		'filepath' => 'hooks'
	);

// Load template
$hook['post_controller_constructor'][] = array(
		'class'    => '',
		'function' => 'load_template',
		'filename' => 'App_config.php',
		'filepath' => 'hooks'
	);

/* End of file hooks.php */
/* Location: ./application/config/hooks.php */