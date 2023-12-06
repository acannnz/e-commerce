<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users extends ADMIN_Controller
{
	protected $populate_access = [];
	protected $populate_state = [];
	
	public function __construct()
	{
		parent::__construct();
		
		$this->load->language('users');
		
		$this->load->library('form_validation');
		$this->load->library('encrypt');
		
		//$this->load->model('user_model');
		
		$this->populate_access = [
				'vendor' => lang('access:vendor'),
				'owner' => lang('access:owner'),
				'admin' => lang('access:admin'),
				'subscriber' => lang('access:subscriber'),
				'guest' => lang('access:guest'),
				'customer' => lang('access:customer'),
				'supplier' => lang('access:supplier'),
			];
		$this->populate_state = [
				0 => lang('state:inactive'),
				1 => lang('state:active'),
				2 => lang('state:suspend'),
			];
	}
	
	public function index()
	{
		$this->template
			->set('p_heading', lang("users:heading"))
			->title(lang("heading:system")." - ".lang("users:heading"))
			->set_breadcrumb(lang("heading:system"))
			->set_breadcrumb(lang("users:heading"))
			->build("users/index", []);
	}
	
	public function create()
	{
		$item_data = (object) [
				'id' => 0,
				'name' => '',
				'phone' => '',
				'email' => '',
				'username' => '',
				'password' => '',
				'access' => 1,
				'state' => 1,
			];
			
		$this->load->view('users/modal/form', [
				'form_action' => base_url('system/users/create_post'),
				'populate_access' => $this->populate_access,
				'populate_state' => $this->populate_state,
				'item' => $item_data
			]);
	}
	
	public function edit($id = NULL)
	{
		if (is_null($id) || empty($id))
		{
			$id = $this->user_model->login_user_id();
		}
		$id = (int) @$id;
		
		$item_data = (object) $this->get_model()->get_one($id);
		
		$item_data->password = '';
		
		$this->load->view('users/modal/form', [
				'populate_access' => $this->populate_access,
				'populate_state' => $this->populate_state,
				'form_action' => base_url("system/users/edit_post/{$id}"),
				'item' => $item_data
			]);
	}
	
	public function delete($id = 0)
	{
		$id = (int) @$id;
		$item = $this->get_model()->get_one($id);
		if (! $item){ $item = ['id' => 0]; }
		
		$item_data = (object) $item;
		
		$this->load->view("users/modal/delete", array(
				"form_action" => base_url("system/users/delete_post"),
				"id" => $id,
				"item" => $item_data
			));
	}
	
	public function create_post()
	{
		if( $this->input->post() ) 
		{
			$post_data = $this->input->post('f',[]);
			
			$this->form_validation->set_rules('f[access]', lang('label:access'), 'required');
			$this->form_validation->set_rules('f[name]', lang('label:name'), 'required');
			$this->form_validation->set_rules('f[username]', lang('label:username'), 'required|min_length[3]|max_length[14]|is_unique[users.username]');
			$this->form_validation->set_rules('f[email]', lang('label:email'), 'required|valid_email|is_unique[users.email]');
			$this->form_validation->set_rules('f[mobile]', lang('label:mobile'), 'required|is_unique[users.mobile]');
			$this->form_validation->set_rules('f[password]', lang('label:password'), 'required|min_length[4]|max_length[16]');
			//$this->form_validation->set_data($post_data);
			
			if ($this->form_validation->run())
			{
				$post_data['password'] = crypt($post_data['password'], $this->config->item('encryption_key'));
				
				$post_data['created_at'] = now();
				$post_data['created_by'] = 0;
				$post_data['updated_at'] = now();
				$post_data['updated_by'] = 0;
				
				if($this->get_model()->create($post_data))
				{
					echo json_encode(["success" => true, "data" => $post_data, 'message' => lang('message:add_success')]);
				} else
				{
					echo json_encode(["success" => false, 'message' => lang('message:add_invalid')]);
				}
			} else
			{
				echo json_encode(["success" => false, 'message' => $this->form_validation->get_all_error_string()]);
			}
		} else
		{
			echo json_encode(["success" => false, 'message' => lang('message:add_invalid')]);
		}
	}
	
	public function edit_post($id = 0)
	{
		global $item;
		
		$id = (int) @$id;
		$item = $this->user_model->get_one($id);
		
		if ($this->input->post()) 
		{
			$post_data = $this->input->post("f");
			
			$this->form_validation->set_rules('f[access]', lang('label:access'), 'required');
			$this->form_validation->set_rules('f[name]', lang('label:name'), 'required');
			$this->form_validation->set_rules('f[username]', lang('label:username'), ['required','min_length[3]','max_length[14]',['username_in_use', function($value){
					global $item;
					
					if ($this->user_model->count_all(['username =' => $value, 'id !=' => $item->id]))
					{
						$this->form_validation->set_message('username_in_use', sprintf(lang('message:user_field_x_used'), lang('label:username')));
						return FALSE;
					}
					return TRUE;
				}]]);
			$this->form_validation->set_rules('f[email]', lang('label:email'), ['required','valid_email',['email_in_use', function($value){
					global $item;
					
					if ($this->user_model->count_all(['email =' => $value, 'id !=' => $item->id]))
					{
						$this->form_validation->set_message('email_in_use', sprintf(lang('message:user_field_x_used'), lang('label:email')));
						return FALSE;
					}
					return TRUE;
				}]]);
			$this->form_validation->set_rules('f[mobile]', lang('label:mobile'), ['required',['mobile_in_use', function($value){
					global $item;
					
					if ($this->user_model->count_all(['mobile =' => $value, 'id !=' => $item->id]))
					{
						$this->form_validation->set_message('mobile_in_use', sprintf(lang('message:user_field_x_used'), lang('label:mobile')));
						return FALSE;
					}
					return TRUE;
				}]]);
			if ($this->input->post('f[password]'))
			{
				$this->form_validation->set_rules('f[password]', lang('label:password'), 'required|min_length[4]|max_length[16]');
			}
			//$this->form_validation->set_data($post_data);
			
			if ($this->form_validation->run())
			{
				if ($post_data['password'] != '')
				{
					$post_data['password'] = crypt($post_data['password'], $this->config->item('encryption_key'));
				} else
				{
					unset($post_data['password']);
				}
				
				$post_data['updated_at'] = now();
				$post_data['updated_by'] = 0;
				
				if ($this->get_model()->update($post_data, $id))
				{
					echo json_encode(["success" => true, "data" => $post_data, 'id' => $id, 'message' => lang('message:edit_success')]);
				} else
				{
					echo json_encode(["success" => false, 'message' => lang('message:edit_invalid')]);
				}
			} else
			{
				echo json_encode(["success" => false, 'message' => $this->form_validation->get_all_error_string()]);
			}
		} else
		{
			echo json_encode(["success" => false, 'message' => lang('message:edit_invalid')]);
		}
	}
	
	public function delete_post()
	{
		$id = (int) $this->input->post( 'id' );
		if ($id != 0)
		{
			if ($this->get_model()->delete_by(["id" => $id]))
			{
				echo json_encode(["success" => true, 'message' => lang('message:delete_success')]);
			} else
			{
				echo json_encode(["success" => false, lang('message:delete_invalid')]);
			}
		} else
		{
			echo json_encode(["success" => false, lang('message:delete_invalid')]);
		}
	}

	public function collection( $state=false )
    {
        $start = $this->input->get_post('start', true);
        $length = $this->input->get_post('length', true);
        $order = $this->input->get_post('order', true);
        $columns = $this->input->get_post('columns', true);
        $search = $this->input->get_post('search', true);
        $draw = $this->input->get_post('draw', true);
		
		$db_from = "users a";
		$db_where = array();
		$db_like = array();
		
		// prepare defautl flter
		$db_where['a.deleted_at'] = NULL;
		if( $state !== false )
		{
			$db_where['a.state >='] = $state;
		}
		
		$user_id = (int) $this->user_model->login_user_id();
		$db_where['a.id !='] = $user_id;
		
		// preparing default
		if( isset($search['value']) && ! empty($search['value']) )
        {
            $keywords = $this->db->escape_str( $search['value'] );
			
			$db_like[ $this->db->escape_str("a.name") ] = $keywords;
			$db_like[ $this->db->escape_str("a.username") ] = $keywords;
			$db_like[ $this->db->escape_str("a.email") ] = $keywords;
			$db_like[ $this->db->escape_str("a.mobile") ] = $keywords;
        }
		
		// get total records
		$this->db->from( $db_from );
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		$records_total = $this->db->count_all_results();
		
		// get total filtered
		$this->db
			->from( $db_from )
			;

		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }		
		$records_filtered = $this->db->count_all_results();
		
		// get result filtered
		$db_select = <<<EOSQL
			
EOSQL;

		$this->db
			//->select( $db_select )
			->from( $db_from )
			;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }		
		
		// ordering
        if( isset($order) )
        {
            $sort_column = $order[0]['column'];
			$sort_dir = $order[0]['dir'];
			
			if( $columns[$sort_column]['orderable'] == 'true' )
			{
				$this->db
					->order_by( $columns[intval($this->db->escape_str($sort_column))]['data'], $this->db->escape_str($sort_dir) );
			}
        }
		
		// paging
		if( isset($start) && $length != '-1')
        {
            $this->db
				->limit( $length, $start );
        }
		
		// get
		$result = $this->db
					->get()
					->result()
					;
		
        // Output
        $output = array(
				'draw' => intval($draw),
				'recordsTotal' => $records_total,
				'recordsFiltered' => $records_filtered,
				'data' => array()
			);
        
        foreach($result as $row)
        {
			$row->created_at = @strftime("%d/%m/%Y", @$row->created_at);
			$row->updated_at = @strftime("%d/%m/%Y", @$row->updated_at);
			
            $output['data'][] = $row;
        }
		
		$this->template
			->build_json( $output );
    }	
	
	public function get_model()
	{
		return $this->user_model;
	}
}