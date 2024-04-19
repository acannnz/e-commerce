<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Item_class extends ADMIN_Controller
{
	protected $nameroutes;
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('inventory');
		
		$this->data['nameroutes'] = $this->nameroutes = 'inventory/references/item_class'; 
		
		$this->load->language('inventory');		
		$this->load->library('inventory');
		$this->load->helper('inventory');
		
		$this->load->model('item_class_model');
		$this->load->model('item_category_model');
		$this->load->model('item_subcategory_model');
	}
	
	//load note list view
	public function index()
	{
		$this->template
			->title(lang('heading:item_classes'),lang('heading:references'))
			->set_breadcrumb(lang('heading:references'), site_url($this->nameroutes))
			->set_breadcrumb(lang('heading:item_classes'))
			->build("references/item_class/index", $this->data);
	}
	
	public function create()
	{
		$this->data['form_action'] = $form_action = site_url("{$this->nameroutes}/create_post");
		$this->load->view('references/item_class/modal/create', $this->data);
	}
	
	public function update($id = 0)
	{
		$id = (int) @$id;
		$this->data['item'] = $item = $this->get_model()->get_one($id);
		
		$this->data['form_action'] = $form_action = site_url("{$this->nameroutes}/update_post/{$id}");
		$this->load->view('references/item_class/modal/update', $this->data);
	}
	
	public function delete($id = 0)
	{
		$id = (int) @$id;
		$this->data['item'] = $item = $this->get_model()->get_one($id);
		
		$this->data['form_action'] = $form_action = site_url("{$this->nameroutes}/delete_post/{$id}");
		$this->load->view('references/item_class/modal/delete', $this->data);
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
					['field' => 'Kode_Kelas', 'label' => lang('label:code'), 'rules' => 'required'],
					['field' => 'Nama_Kelas', 'label' => lang('label:class_name'), 'rules' => 'required'],
					['field' => 'SubKategori_ID', 'label' => lang('label:category'), 'rules' => 'required']
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
					['field' => 'Kode_Kelas', 'label' => lang('label:code'), 'rules' => 'required'],
					['field' => 'Nama_Kelas', 'label' => lang('label:class_name'), 'rules' => 'required'],
					['field' => 'SubKategori_ID', 'label' => lang('label:category'), 'rules' => 'required']
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
		
		$t_class = $this->item_class_model->table;
		$t_category = $this->item_category_model->table;
		$t_subcategory = $this->item_subcategory_model->table;
		
		$this->datatables
			->select("{$t_class}.Kelas_ID,{$t_class}.Kode_Kelas,{$t_class}.Nama_Kelas,{$t_subcategory}.Nama_Sub_Kategori")
			->from($t_class)
			->join($t_subcategory,"{$t_class}.SubKategori_ID = {$t_subcategory}.SubKategori_ID","left outer");
		
		$this->datatables->add_column("actions", $action, "Kelas_ID");		
		
		$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output($this->datatables->generate())
			->_display();
		
		exit(0);
	}
	
	public function get_category_list()
	{
		$this->output
			->set_status_header(200)
			->set_content_type('text/html', 'utf-8')
			->set_output($this->item_category_model->to_list_html(lang('select_category')))
			->_display();
		
		exit(0);
	}
	
	public function get_subcategory_list($parent_id = 0)
	{
		$parent_id = (int) $parent_id;
		if (0 == $parent_id){ $parent_id = (int) $this->input->post('category_id', TRUE); }
		
		print $parent_id;
		
		$this->output
			->set_status_header(200)
			->set_content_type('text/html', 'utf-8')
			->set_output($this->item_subcategory_model->to_list_html($parent_id, lang('select_subcategory')))
			->_display();
		
		exit(0);
	}
	
	public function get_model()
	{
		return $this->item_class_model;
	}
}

