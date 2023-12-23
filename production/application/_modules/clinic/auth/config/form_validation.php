<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config = array('add_user' => array(
	array(
			'field' => 'username',
			'label' => 'Username',
			'rules' => 'trim|required'
		),
	array(
			'field' => 'email',
			'label' => 'Email',
			'rules' => 'trim|required|valid_email'
		),
	array(
			'field' => 'password',
			'label' => 'Password',
			'rules' => 'trim|required'
		),
	array(
			'field' => 'confirm_password',
			'label' => 'Confirm Password',
			'rules' => 'trim|matches[password]'
		)
	));