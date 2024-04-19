<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Section extends ADMIN_Controller
{
	protected $nameroutes;
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('inventory');
		
		$this->data['nameroutes'] = $this->nameroutes = 'inventory/references/section'; 
		
		$this->load->language('inventory');		
		$this->load->library('inventory');
		$this->load->helper('inventory');
		
		$this->load->model('section_model');
		$this->load->model('business_model');
		$this->load->model('customer_model');
		
		$this->data['populate_report'] = [
				0 => 'Pilih',
				1 => 'PT',
				2 => 'Hopital',
				3 => 'Ditagihkan',
				4 => 'Unit Bisnis',
			];
		$this->data['populate_service'] = [
				'' => 'Pilih',
				'RI' => 'Rawat Inap',
				'RJ' => 'Rawat Jalan',
				'PENUNJANG' => 'Penunjang',
				'PENUNJANG2' => 'Penunjang 2',
				'FARMASI' => 'Farmasi',
				'KASIR' => 'Kasir',
				'GUDANG' => 'Gudang',
				'ALL' => 'Semua',
			];
		$this->data['populate_business'] = ([0 => 'Pilih']) + $this->business_model->to_list_data();
	}
	
	//load note list view
	public function index()
	{
		$this->template
			->title(lang('heading:sections'))
			->set_breadcrumb(lang('heading:references'), site_url($this->nameroutes))
			->set_breadcrumb(lang('heading:sections'))
			->build("references/section/index", $this->data);
	}
	
	public function create()
	{
		$this->data['form_action'] = $form_action = site_url("{$this->nameroutes}/create_post");
		$this->load->view('references/section/modal/create', $this->data);
	}
	
	public function update($key = 0)
	{
		$key = (string) urldecode(@$key);
		$this->data['item'] = $item = $this->get_model()->get_one($key);
		
		$this->data['form_action'] = $form_action = site_url("{$this->nameroutes}/update_post/{$key}");
		$this->load->view('references/section/modal/update', $this->data);
	}
	
	public function delete($key = 0)
	{
		$key = (string) urldecode(@$key);
		$this->data['item'] = $item = $this->get_model()->get_one($key);
		
		$this->data['form_action'] = $form_action = site_url("{$this->nameroutes}/delete_post/{$key}");
		$this->load->view('references/section/modal/delete', $this->data);
	}
	
	public function mass_action()
	{
		$this->form_validation->set_rules('mass_action', 'Mass Action', 'required');
		
		if ($this->form_validation->run())
		{
			if (!empty($this->input->post('val', TRUE)))
			{
				if ($this->input->post('mass_action') == 'delete')
				{
					foreach ($this->input->post('val', TRUE) as $key)
					{
						$this->get_model()->delete($key);
					}
					$this->session->set_flashdata('message', lang("message:mass_delete_success"));
					redirect($this->nameroutes,'refresh');
				}
			} else
			{
				$this->session->set_flashdata('error', lang("message:no_selected"));
				redirect($this->nameroutes,'refresh');
			}
		} else
		{
			$this->session->set_flashdata('error', validation_errors());
			redirect($this->nameroutes,'refresh');
		}
	}
	
	public function create_post()
	{
		if( $this->input->post() ) 
		{
			$post_data = $this->input->post("f");
			
			$this->form_validation->set_rules([
					['field' => 'SectionID', 'label' => 'ID', 'rules' => 'required'],
					['field' => 'SectionName', 'label' => 'Nama Section', 'rules' => 'required'],
					['field' => 'PenanggungJawab', 'label' => 'Penanggung Jawab', 'rules' => 'required']
				]);
			$this->form_validation->set_data($post_data);
			
			if( $this->form_validation->run())
			{
				if($inserted_id = $this->get_model()->create($post_data))
				{
					echo response_json(["success" => true, "inserted_id" => $inserted_id, 'message' => lang('message:create_success')]);
				} else
				{
					echo response_json(["success" => false, 'message' => lang('message:create_failed')]);
				}
			} else
			{
				echo response_json(["success" => false, 'message' => $this->form_validation->get_all_error_string()]);
			}
		} else
		{
			echo response_json(["success" => false, 'message' => lang('message:create_failed')]);
		}
	}
	
	public function update_post($key = 0)
	{
		$key = (string) urldecode(@$key);
		$item = $this->get_model()->get_one($key);
		
		if( $item && $this->input->post() ) 
		{
			$post_data = $this->input->post("f");
			
			$this->form_validation->set_rules([
					//['field' => 'SectionID', 'label' => 'ID', 'rules' => 'required'],
					['field' => 'SectionName', 'label' => 'Nama Section', 'rules' => 'required'],
					['field' => 'PenanggungJawab', 'label' => 'Penanggung Jawab', 'rules' => 'required']
				]);
			$this->form_validation->set_data($post_data);
			
			if( $this->form_validation->run())
			{
				if($inserted_id = $this->get_model()->update($post_data, $key))
				{
					echo response_json(["success" => true, "updated_id" => $inserted_id, 'message' => lang('message:update_success')]);
				} else
				{
					echo response_json(["success" => false, 'message' => lang('message:update_failed')]);
				}
			} else
			{
				echo response_json(["success" => false, 'message' => $this->form_validation->get_all_error_string()]);
			}
		} else
		{
			echo response_json(["success" => false, 'message' => lang('message:update_failed')]);
		}
	}
	
	public function delete_post($key)
	{
		$key = (string) urldecode(@$key);
		$item = $this->get_model()->get_one($key);
		
		if ($item && (1 == $this->input->post('confirm')))
		{
			if ($this->get_model()->delete($key))
			{
				echo response_json(["success" => true, 'message' => lang('message:delete_success')]);
			} else
			{
				echo response_json(["success" => false, lang('message:delete_failed')]);
			}
		} else
		{
			echo response_json(["success" => false, lang('message:delete_failed')]);
		}
	}
	
	public function collection()
	{
		$a_edit = anchor('#', '<i class="fa fa-pencil"></i> ' . lang('action:edit'), [
				'data-act' => 'ajax-modal', 
				'data-title' => lang('action:edit'),
				'data-action-url' => site_url($this->nameroutes.'/update/$1')
			]);
		$a_delete = anchor('#', '<i class="fa fa-trash-o"></i> ' . lang('action:delete'), [
				'data-act' => 'ajax-modal', 
				'data-title' => lang('action:delete'),
				'data-action-url' => site_url($this->nameroutes.'/delete/$1')
			]);
		
		$action = '<div class="text-center"><div class="btn-group text-left">'
			. '<button type="button" class="btn btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">'
			. lang('actions') . ' <span class="caret"></span></button>
			<ul class="dropdown-menu pull-right" role="menu">
				<li>' . $a_edit . '</li>
				<li>' . $a_delete . '</li>
			</ul>
		</div></div>';
		
		$this->datatables
			->select("SectionID,SectionName,PenanggungJawab,TipePelayanan")
			->from($this->get_model()->table);
		
		$this->datatables->add_column("actions", $action, "SectionID");		
		
		$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output($this->datatables->generate())
			->_display();
		
		exit(0);
	}
	
	public function lookup()
    {
        $start = $this->input->get_post('start', true);
        $length = $this->input->get_post('length', true);
        $order = $this->input->get_post('order', true);
        $columns = $this->input->get_post('columns', true);
        $search = $this->input->get_post('search', true);
        $draw = $this->input->get_post('draw', true);
		
		$t_main = $this->db->dbprefix($this->get_model()->table);
		
		// get total records
		$records_total = $this->db->count_all_results($t_main);
		
		// preparing filter
		$db_like = [];
		if (isset($search['value']) && ! empty($search['value']))
        {
            $words = $this->db->escape_str($search['value']);			
			$db_like[$this->db->escape_str("{$t_main}.SectionName")] = $words;
        }
		
		// get total filtered
		if (! empty($db_like))
		{
			$this->db->group_start();
			foreach($db_like as $field => $match)
			{
				$this->db->or_like($field, $match, 'both', TRUE);
			}
			$this->db->group_end();
		}
		$records_filtered = $this->db->count_all_results($t_main);
		
		
		// get result filtered
		$db_select = <<<EOSQL
			{$t_main}.SectionID AS Id, 
			{$t_main}.SectionName AS Nama, 
			{$t_main}.KodeNoBukti AS Kode,
			{$t_main}.Lokasi_ID, 
EOSQL;
		
		$db_order = [ 
				"Nama" => "{$t_main}.SectionName",
			];
		
		$this->db->select($db_select)->where('StatusAktif','1');
		
		if (! empty($db_like))
		{
			$this->db->group_start();
			foreach($db_like as $field => $match)
			{
				$this->db->or_like($field, $match, 'both', TRUE);
			}
			$this->db->group_end();
		}
		if (isset($order))
        {
            $sort_column = $order[0]['column'];
			$sort_dir = $order[0]['dir'];
			if( $columns[$sort_column]['orderable'] == 'true' )
			{
				$field = $columns[intval($this->db->escape_str($sort_column))]['data'];
				$this->db->order_by($db_order[$field], $this->db->escape_str($sort_dir));
			}
        }
		if (isset($start) && $length != '-1')
        {
            $this->db->limit($length, $start);
        }
		
		$query = $this->db->get($t_main);
		$result = $query->result_array();
		
        $output = [
				'draw' => intval($draw),
				'recordsTotal' => $records_total,
				'recordsFiltered' => $records_filtered,
				'data' => []
			];        
        foreach($result as $row)
        {
			$output['data'][] = $row;
        }
		
		$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($output))
			->_display();
		
		exit(0);
    }
	
	public function get_model()
	{
		return $this->section_model;
	}
}

