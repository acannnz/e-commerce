<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tool_usage extends Admin_Controller
{
	protected $_translation = 'poly';	
	protected $_model = 'supporting_tool_usage_model';
	protected $nameroutes = 'poly/inpatients/tool_usage';
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('inpatient');
		
		$this->load->model("supporting_tool_model");
		$this->load->model("supporting_tool_usage_model");
		$this->load->helper("poly");		
	}
	
	public function index( $NoReg, $SectionID )
	{
		$data = [
			'NoReg' => $NoReg,
			'SectionID' => $SectionID,
			'collection' => poly_helper::get_supporting_tool_usage($NoReg, $SectionID),
			'option_tool' => $this->supporting_tool_model->to_list_html(),
			'nameroutes' => $this->nameroutes,
		];

		$this->load->view( 'inpatient/form/tool_usage', $data );		
	}
}