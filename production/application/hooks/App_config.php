<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Author Message
|--------------------------------------------------------------------------
|
| Set config variables using DB
| 
*/

//Loads configuration from database into global CI config
function load_config()
{
	$CI =& get_instance();
	
	if( $CI->session->userdata('database') && config_item('multi_database') )
	{
		$CI->db = $CI->load->database( $CI->session->userdata('database'), TRUE );
	}

	$configs = $CI->HookModel->get_config()->result();
	foreach($configs as $config)
	{
		//print "{$config->config_key}: {$config->value}<br>";
		$CI->config->set_item( $config->config_key, $config->value );
	}

	// Default Desktop Config (Setup Awal)
	$configs = $CI->HookModel->get_config_deskstop()->result();
	foreach($configs as $config)
	{
		$CI->config->set_item( $config->SetupName, $config->Nilai );
	}
}

function load_template($role)
{
	$CI =& get_instance();
	$role_accessed = $role ? $role : $CI->session->flashdata('role_accessed'); 
	
	
		switch ( sprintf('%s.%s', config_item('template.theme'), config_item('template.layout') ) ):
			
			case 'bracketadmin.auth' :
				
				$CI->template
					->set('app_name', config_item('app_name'))
					->set('app_logo', config_item('app_logo'))
					->set('app_description', config_item('app_description'))
					->set('app_author', config_item('app_author'))
					
					->set('base_url', base_url())
					->set('base_theme', base_url('themes'))
					->set('search_action', base_url(config_item('search_action')));
				
				$CI->template
					->set_theme('bracketadmin')
					->set_layout('auth')			
					->set_partial('head', 'partials/head')
					//->set_partial('loader', 'partials/loader')
					->set_partial('header', 'partials/header')
					->set_partial('footer', 'partials/footer')			
					->set_partial('modal', 'partials/modal')
					->set_partial('bottom_scripts', 'partials/bottom');		
			break;
			
			case 'bracketadmin.admin' :
				$CI->template
					->set('app_name', config_item('company_name'))
					->set('app_logo', config_item('app_logo'))
					->set('app_description', config_item('app_description'))
					->set('app_author', config_item('app_author'))
					
					->set('base_url', base_url())
					->set('base_theme', base_url('themes'))
					->set('search_action', base_url(config_item('search_action')));
				
				$CI->template
					->set_theme('bracketadmin')
					->set_layout('admin')			
					->set_partial('head', 'partials/head')
					->set_partial('loader', 'partials/loader')
					->set_partial('header', 'partials/header')
					->set_partial('footer', 'partials/footer')			
					->set_partial('aside', 'partials/aside/'. config_item('template.sidebar'))			
					->set_partial('modal', 'partials/modal')
					->set_partial('bottom_scripts', 'partials/bottom');
			break;
			
			case 'intuitive.auth' :
				
				$CI->template
					->set_theme('intuitive')
					->set_layout( 'auth' )
					->set_partial( 'head', 'partials/auth/head' )					
					->set_partial('loader', 'partials/loader')
					->set_partial( 'header', 'partials/auth/header' )
					->set_partial( 'footer', 'partials/auth/footer' )
					->set_partial( 'modal', 'partials/admin/modal' )					
					->set_partial( 'styles', 'partials/auth/styles' )
					->set_partial( 'bottom_scripts', 'partials/auth/scripts/bottom' );		
			break;
			
			case 'intuitive.admin' :
			
				if( $CI->session->userdata('database') && config_item('multi_database') ){
					$database_config = config_item('multi_database');
					$CI->template->set_breadcrumb( $database_config[$CI->session->userdata('database')] );
				}
			
				$CI->template
					->set_theme('intuitive')
					->set_layout( 'admin' )
					->set( 'user_role', "admin" )
					->set( 'user_profile', $CI->simple_login->get_user() )
					//->set( 'languages', $languages )
					//->set( 'active_language', $active_language )
					->set( 'timeout', TRUE )
					
					->set_partial( 'modal', 'partials/modal' )
					->set_partial('loader', 'partials/loader')
					->set_partial( 'head', 'partials/admin/head' )
					->set_partial( 'header', 'partials/admin/header' )
					->set_partial( 'aside', 'partials/admin/aside/'. config_item('template.sidebar') )
					->set_partial( 'footer', 'partials/admin/footer' )
					//->set_partial( 'right', 'partials/admin/right' )					
					->set_partial( 'bottom_scripts', 'partials/admin/scripts/bottom' )
					;
			break;
		
		endswitch;
	
}

function time_execute()
{
	// Script time start
	$time_start = microtime(true); 
	
	//Script time end
	echo 'Total execution code: ' . (microtime(true) - $time_start);
	echo "\n Total execution from start:" . (microtime(true) -  $_SERVER["REQUEST_TIME_FLOAT"]);
	exit;	
}
