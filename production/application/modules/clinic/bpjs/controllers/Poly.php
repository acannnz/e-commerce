<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Poly extends ADMIN_Controller
{
	protected $nameroutes = 'bpjs/poly';

	public function __construct()
	{
		parent::__construct();
		$this->data['nameroutes'] = $this->nameroutes;

		$this->load->language('bpjs');
		$this->load->helper('bpjs');
	}

	public function index()
	{
		show_404();
	}

	public function form_mapping($code = NULL)
	{
		$this->data['mapping'] = $mapping = (object) [
			'code' => $code,
		];
		$this->data['lookup_poly_bpjs'] = base_url("{$this->nameroutes}/lookup_poly");

		$this->load->view("poly/form", $this->data);
	}

	public function lookup_poly($is_ajax_request = false)
	{
		if ($this->input->is_ajax_request() || $is_ajax_request !== false) {
			$this->load->view("poly/lookup/lookup_poly");
		}
	}
}
