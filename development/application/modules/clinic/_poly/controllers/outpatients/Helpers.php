<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Helpers extends Admin_Controller
{
	protected $_translation = 'poly';	
	protected $_model = 'poly_m';
	protected $nameroutes = 'poly/outpatients/helpers';
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('outpatient');
		
		$this->load->model("poly_m");
		$this->load->helper("poly");	
	}
	
	public function index( $item = NULL )
	{
		$data = array(
				"collection" => $this->poly_m->get_helper( array("NoBuktiHeader" => @$item->NoBukti ) ),
				"form" => TRUE,
				'datatables' => TRUE,
				'nameroutes' => $this->nameroutes,
				"get_helper_number" => base_url("{$this->nameroutes}/get_helper_number"),
				"get_helper_section" => base_url("{$this->nameroutes}/get_helper_section"),
			);

		$this->load->view( 'outpatient/form/helpers', $data );		
	}

	public function get_helper_number(){

		if ($this->input->is_ajax_request())
		{
			$response = array(
						"status" => 'success',
						"NoBuktiMemo" => poly_helper::gen_helper_number()		,
						"code" => 200
					);
			
			response_json($response);
		}

	}

	public function get_helper_section( $selected='' ){

		if ($this->input->is_ajax_request())
		{
			$items = $this->db
				->order_by('SectionID', 'asc')
				->where( array("TipePelayanan " => "PENUNJANG", "StatusAktif" => 1))
				->get("SIMmSection")
				->result()
				;
			
			$options_html = "";
			
			if( $selected == "" )
			{
				$options_html .= "\n<option data-SectionID=\"0\" data-SectionName=\"\" value=\"\" selected>".lang( 'global:select-empty' )."</option>";
			} else
			{
				$options_html .= "\n<option data-SectionID=\"0\" data-SectionName=\"\" value=\"\">".lang( 'global:select-empty' )."</option>";
			}
			
			foreach($items as $item)
			{
				
				$attr_data = "data-SectionID=\"{$item->SectionID}\" data-SectionName=\"{$item->SectionName}\" ";
				
				if( $selected == $item->SectionID)
				{
					$options_html .= "\n<option {$attr_data} value=\"{$item->SectionID}\" selected>{$item->SectionName}</option>";
				} else
				{
					$options_html .= "\n<option {$attr_data} value=\"{$item->SectionID}\">{$item->SectionName}</option>";
				}
			}
			
			print( $options_html );
			exit();
		}

	}

}