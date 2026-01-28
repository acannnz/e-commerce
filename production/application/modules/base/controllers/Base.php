<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Base extends MY_Controller {

	public function index()
	{
		$data['title'] = 'Dashboard - Arcnad Store';
		$data['content'] = $this->load->view('dashboard', $data, TRUE);
		$this->load->view('layouts/main', $data);
	}

	public function login()
	{
		// Placeholder for modern login view
		$this->load->view('login');
	}
}
