<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Import extends ADMIN_Controller
{
	protected $nameroutes = 'import';
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('admin');
		
		$this->data['nameroutes'] = $this->nameroutes; 
		
		$this->load->language('import');		
		$this->load->helper('import');
		
		$this->load->model('import_model');
		$this->load->model('patient_model');
		
		$this->load->model('family_model');
		$this->load->model('personal_model');
		$this->load->model('personal_to_family_model');
		
		$this->load->model('personal_to_environment_model');
		$this->load->model('personal_to_obgyn_model');
		$this->load->model('personal_to_immunization_model');

		$this->load->model('icd_model');
	
		$this->load->model('item_model');
		$this->load->model('item_location_model');
		$this->load->model('item_category_model');
		$this->load->model('item_subcategory_model');
		$this->load->model('item_unit_model');
		$this->load->model('item_class_model');

	}
	
	public function index()
	{
		$this->data['dropdown_import_type'] = [
			'' => lang('global:select-none'),
			'family_folder' => 'Family Folder',
			'patient' => 'Patient',
			'family_folder_patient' => 'Family Folder + Patient',
			'item' => 'Barang',
			'item_category' => 'Kategori Barang',
			'item_subcategory' => 'Sub Kategori Barang',
			'item_unit' => 'Satuan Barang',
			'item_class' => 'Kelas Barang',
			'service' => 'Jasa',
			'coa' => 'Chart Of Account',
			'icd' => 'ICD'
		];		
		$this->data['form_action'] = base_url("{$this->nameroutes}/do_import");
		
		$this->template
			->title(lang('heading:import'), lang('heading:admin'))
			->set_breadcrumb(lang('heading:import') )
			->set_breadcrumb(lang('heading:import_list'), site_url($this->nameroutes))
			->build("form", $this->data);
	}
	
	public function do_import()
	{		
		if( $this->input->post() ) 
		{
			$post_data = $this->input->post("f");
			
			$this->load->library('form_validation');
			$this->form_validation->set_rules([['field' => 'import_type', 'label' => lang('label:import_type'), 'rules' => 'required']]);
			$this->form_validation->set_data($post_data);
			if( $this->form_validation->run() && !empty($_FILES) )
			{	
				$action = $post_data['action'];
				$method = $post_data['import_type'];
				$message = call_user_func( "import_helper::{$action}_{$method}" );
			} else
			{
				$message = [ 
					"message" => empty($_FILES) ? 'Empty File, File Import is required!' : $this->form_validation->get_all_error_string(),
					"status" => "error"
				];
			}

			response_json( $message );
		}
		
		redirect($this->nameroutes);	
	}
}

