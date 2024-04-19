<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Odontogram extends Admin_Controller
{
	// protected $_translation = 'dentist';
	// protected $_model = 'Odontogram_list_model';
	protected $nameroutes = 'poly/dentist/odontogram';

	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('outpatient');

		// $this->load->model("poly_m");
	}

	public function index()
	{
		$data = array(
			"odontogram_collection" => $this->db->from('SIMmOdontogram')->get()->result(),
			'nameroutes' => $this->nameroutes,
		);


		$this->load->view('dentist/medical_record/odontogram', $data);
	}
}
