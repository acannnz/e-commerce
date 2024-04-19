<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Config extends ADMIN_Controller
{
	protected $nameroutes;
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('verification');
		
		$this->data['nameroutes'] = $this->nameroutes = 'verification/preferences/config'; 
		
		$this->load->language('config');				
		$this->load->model('config_model');
		$this->load->model('account_model');
	}
	
	//load note list view
	public function index()
	{
		$config = (object) [];
		$populate = $this->get_model()->get_all();
		foreach($populate as $item)
		{
			$config->{$item->SetupName} = $item->Nilai;
		}
		$this->data['config'] = @$config;
		$this->data['acc_umum'] = $this->account_model->get_by(['Akun_No' => $config->AkunLawanPendatanUMUM ]);
		$this->data['acc_iks'] = $this->account_model->get_by(['Akun_No' => $config->AkunLawanPendatanIKS]);
		$this->data['acc_exe'] = $this->account_model->get_by(['Akun_No' => $config->AkunLawanPendatanEXECUTIVE]);
		$this->data['acc_bpjs'] = $this->account_model->get_by(['Akun_No' => $config->AkunLawanPendapatanBPJS]);
		$this->data['acc_hc'] = $this->account_model->get_by(['Akun_No' => $config->AkunLawanPendatanHC]);
		$this->data['acc_ma'] = $this->account_model->get_by(['Akun_No' => $config->AkunLawanPendatanMA]);
		
		if(config_item('multi_bo') == 'TRUE'){
			$this->data['acc_umum_2'] = $this->account_model->get_by(['Akun_No' => $config->AkunLawanPendatanUMUM_2 ], FALSE, 'BO_2');
			$this->data['acc_iks_2'] = $this->account_model->get_by(['Akun_No' => $config->AkunLawanPendatanIKS_2], FALSE, 'BO_2');
			$this->data['acc_exe_2'] = $this->account_model->get_by(['Akun_No' => $config->AkunLawanPendatanEXECUTIVE_2], FALSE, 'BO_2');
			$this->data['acc_bpjs_2'] = $this->account_model->get_by(['Akun_No' => $config->AkunLawanPendapatanBPJS_2], FALSE, 'BO_2');
			$this->data['acc_hc_2'] = $this->account_model->get_by(['Akun_No' => $config->AkunLawanPendatanHC_2], FALSE, 'BO_2');
			$this->data['acc_ma_2'] = $this->account_model->get_by(['Akun_No' => $config->AkunLawanPendatanMA_2], FALSE, 'BO_2');
		}
	
		$this->data['acc_cuthonor'] = $this->account_model->get_by(['Akun_No' => $config->AkunNoPotongHonor]);
		$this->data['acc_others'] = $this->account_model->get_by(['Akun_No' => $config->AkunNoOthers]);
		$this->data['acc_otc_income'] = $this->account_model->get_by(['Akun_No' => $config->AkunPendapatanObatBebas]);
		$this->data['acc_otc_tunai'] = $this->account_model->get_by(['Akun_No' => $config->AkunBayar_OB_Tunai]);
		$this->data['acc_otc_insurence'] = $this->account_model->get_by(['Akun_No' => $config->AkunBayar_OB_Asuransi]);
		$this->data['acc_otc_company'] = $this->account_model->get_by(['Akun_No' => $config->AkunBayar_OB_Perusahaan]);
		$this->data['acc_otc_credit'] = $this->account_model->get_by(['Akun_No' => $config->AkunBayar_OB_Kredit]);
		$this->data['acc_otc_log'] = $this->account_model->get_by(['Akun_No' => $config->AkunBayar_OB_LOG]);
		$this->data['acc_bpjs_income'] = $this->account_model->get_by(['Akun_No' => $config->AkunNoKeuntunganBPJS]);
		$this->data['acc_bpjs_loss'] = $this->account_model->get_by(['Akun_No' => $config->AkunNoKerugianBPJS]);
		$this->data['acc_bpjs_receivable'] = $this->account_model->get_by(['Akun_ID' => $config->IDAkunPiutangBPJS]);
		$this->data['acc_log_receivable'] = $this->account_model->get_by(['Akun_ID' => $config->IDAkunPiutangLOG]);
		$this->data['acc_biaya_insentif'] = $this->account_model->get_by(['Akun_No' => $config->AkunNoBiayaInsentif]);
		$this->data['acc_payable_insentif'] = $this->account_model->get_by(['Akun_No' => $config->AkunNoHutangInsentif]);
		
		$this->template
			->title(lang('heading:config'))
			->set_breadcrumb(lang('heading:preferences'), site_url($this->nameroutes))
			->set_breadcrumb(lang('heading:config'))
			->build("preferences/system_config/form", $this->data);
	}
	
	public function index_post()
	{
		if ($populate_data = $this->input->post('f')) 
		{
			$this->db->trans_begin();
			foreach ($populate_data as $name => $value)
			{
				if (!$this->get_model()->count_all(['SetupName' => $name]))
				{
					$this->get_model()->create(['Nilai' => $value, 'SetupName' => $name]);
				} else 
				{
					$this->get_model()->update_by(['Nilai' => $value], ['SetupName' => $name]);
				}
			}
			
			if($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
				echo response_json(["success" => false, 'message' => lang('message:update_failed')]);
			}
			
			$this->db->trans_commit();			
			echo response_json(["success" => true, 'message' => lang('message:update_success')]);
		} else
		{
			echo response_json(["success" => false, 'message' => lang('message:update_failed')]);
		}
	}
	
	public function get_model()
	{
		return $this->config_model;
	}
}

