<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Item_unit extends ADMIN_Controller
{
	protected $nameroutes;
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('inventory');
		
		$this->data['nameroutes'] = $this->nameroutes = 'inventory/references/item_unit'; 
		
		$this->load->language('inventory');		
		$this->load->library('inventory');
		$this->load->helper('inventory');
		
		$this->load->model('item_unit_model');
	}
	
	//load note list view
	public function index()
	{
		$this->template
			->title(lang('heading:item_units'),lang('heading:references'))
			->set_breadcrumb(lang('heading:references'), site_url($this->nameroutes))
			->set_breadcrumb(lang('heading:item_units'))
			->build("references/item_unit/index", $this->data);
	}
	
	public function create()
	{
		$this->data['form_action'] = $form_action = site_url("{$this->nameroutes}/create_post");
		$this->load->view('references/item_unit/modal/create', $this->data);
	}
	
	public function update($id = 0)
	{
		$id = (int) @$id;
		$this->data['item'] = $item = $this->get_model()->get_one($id);
		
		$this->data['form_action'] = $form_action = site_url("{$this->nameroutes}/update_post/{$id}");
		$this->load->view('references/item_unit/modal/update', $this->data);
	}
	
	public function delete($id = 0)
	{
		$id = (int) @$id;
		$this->data['item'] = $item = $this->get_model()->get_one($id);
		
		$this->data['form_action'] = $form_action = site_url("{$this->nameroutes}/delete_post/{$id}");
		$this->load->view('references/item_unit/modal/delete', $this->data);
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
					foreach ($this->input->post('val', TRUE) as $id)
					{
						$this->get_model()->delete($id);
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
					['field' => 'Kode_Satuan', 'label' => lang('label:code'), 'rules' => 'required'],
					['field' => 'Nama_Satuan', 'label' => lang('label:category'), 'rules' => 'required']
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
	
	public function update_post($id = 0)
	{
		$id = (int) @$id;
		$item = $this->get_model()->get_one($id);
		
		if( $item && $this->input->post() ) 
		{
			$post_data = $this->input->post("f");
			
			$this->form_validation->set_rules([
					['field' => 'Kode_Satuan', 'label' => lang('label:code'), 'rules' => 'required'],
					['field' => 'Nama_Satuan', 'label' => lang('label:category'), 'rules' => 'required']
				]);
			$this->form_validation->set_data($post_data);
			
			if( $this->form_validation->run())
			{
				if($inserted_id = $this->get_model()->update($post_data, $id))
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
	
	public function delete_post($id)
	{
		$id = (int) @$id;
		$item = $this->get_model()->get_one($id);
		
		if ($item && (1 == $this->input->post('confirm')))
		{
			if ($this->get_model()->delete($id))
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
			->select("Satuan_ID,Kode_Satuan,Nama_Satuan")
			->from($this->get_model()->table);
		
		$this->datatables->add_column("actions", $action, "Satuan_ID");		
		
		$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output($this->datatables->generate())
			->_display();
		
		exit(0);
	}
	
	public function get_model()
	{
		return $this->item_unit_model;
	}
}

