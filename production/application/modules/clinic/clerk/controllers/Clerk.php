<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Clerk extends Admin_Controller
{
	protected $_translation = 'clerk';	
	public $nameroutes = 'clerk';
	protected $_model = 'clerk_model';
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('pharmacy');
		
		// $this->load->language('clerk');		
		$this->load->helper('clerk');
		$this->load->model('clerk_det_model');
						
		$this->data['nameroutes'] = $this->nameroutes; 	
		$this->data['form'] = TRUE;
		$this->template
			->set('heading', lang('heading:clerk'))
			->set_breadcrumb(lang('heading:clerk'), site_url($this->nameroutes));
	}
	
	public function index()
	{
		$this->data['datatables'] = TRUE; 	
			
		$this->template
			->title(lang('heading:clerk'))
			->set('subtitle', lang('heading:clerk_list'))
			->build("clerk/datatable", $this->data);
	}
	
	public function start()
	{	
		$check = clerk_helper::check_clerk();
		if($check == 'end')	
			redirect("{$this->nameroutes}/{$check}");
				
		$pharmacy = $this->session->userdata('pharmacy');
		$item = (object) [
			'KodeClerk' => clerk_helper::gen_code('C', date('dmY'), 5),
			'UserID' => $this->user_auth->User_ID,
			'SectionID' => $pharmacy['section_id'],
			'WaktuMulaiClerk' => date('Y-m-d H:i:s'),
			'JumlahAwalUangKasir' => 0
		];
		
		if( $this->input->post() ) 
		{
			$header = array_merge((array)$item, $this->input->post("f"));

			$password = $this->input->post("password");

			if(! $this->simple_login->verify_password($password, $this->user_auth->User_ID))
			{
				response_json([
					"status" => 'error',
					"message" => lang('message:wrong_password'),
					"code" => 401
				]);
			}			

			$this->load->library('form_validation');
			$this->form_validation->set_rules($this->get_model()->rules['start']);
			$this->form_validation->set_data($header);

			

			if( $this->form_validation->run() )
			{			
				$response = clerk_helper::start_clerk($header);
				if($response['status'] == 'success')
				{
					$get_clerk = $this->clerk_model->get_one($header['KodeClerk']);
					$this->session->set_userdata((array)$get_clerk);
				}
				
			} else
			{
				$response = [
					"status" => 'error',
					"message" => $this->form_validation->get_all_error_string(),
					"code" => 500
				];
			}
		
			response_json( $response );
		}
						
		$this->data['item'] = $item;
		$this->data['form_action'] = current_url();
		
		$this->template
			->title(lang('heading:clerk_create'))
			->set('subtitle', lang('heading:clerk_create'))
			->set_breadcrumb(lang('heading:clerk_create'))
			->build("clerk/start", $this->data);
	}
	
	public function end( $KodeClerk = NULL)
	{	
		$KodeClerk = $KodeClerk ? $KodeClerk : $this->session->userdata('KodeClerk');

		$get_clerk = isset($KodeClerk)
					 ? $this->clerk_model->get_one($KodeClerk)
					 : $this->clerk_model->get_by(['UserID' => $this->user_auth->User_ID, 'StatusClerk' => 0]);
				
		if(empty($get_clerk))
			redirect("{$this->nameroutes}/start");

			
		$item = [
			'WaktuAkhirClerk' => date('Y-m-d H:i:s'),
			'JumlahTransaksi' => clerk_helper::get_clerk_qty_sales($this->user_auth->User_ID, $get_clerk->WaktuMulaiClerk),
			'JumlahTotalSystem' => clerk_helper::get_clerk_amount_system($this->user_auth->User_ID, $get_clerk->WaktuMulaiClerk),
			'JumlahTotalClerk' => 0,
			'JumlahTotalSelisih' => 0,
			'StatusClerk' => 0,
		];

		
		$item = $get_clerk->StatusClerk == 0
			 ? (object) array_merge((array) $get_clerk, $item)
			 : $get_clerk;
		

		if( $this->input->post() ) 
		{
			$header = array_merge( (array) $item, $this->input->post("f"));
			$KodeClerk = $this->input->post("KodeClerk");
			$details = $this->input->post("d");
			$type = $this->input->post("type");
			
			$password = $this->input->post("password");
			// if(! $this->simple_login->verify_password($password, $this->user_auth->User_ID))
			if(! $this->simple_login->verify_password($password, $get_clerk->UserID))
			{
				response_json([
					"status" => 'error',
					"message" => lang('message:wrong_password'),
					"code" => 401
				]);
			}			

			$this->load->library('form_validation');
			$this->form_validation->set_rules($this->get_model()->rules['end']);
			$this->form_validation->set_data($header);
			if( $this->form_validation->run() )
			{
				$response = clerk_helper::end_clerk($KodeClerk, $header, $details);		
				if($response['status'] == 'success')
				{
					$get_clerk = $this->clerk_model->get_one($KodeClerk);
					foreach($get_clerk as $key => $val)
					{
						$this->session->unset_userdata($key);	
					}
				}					
			} else
			{
				$response = [
					"status" => 'error',
					"message" => $this->form_validation->get_all_error_string(),
					"code" => 500
				];
			}
		
			response_json( $response );
		}
				
		$this->data['item'] = $item;
		$this->data['form_action'] = current_url();
		$this->data['clerk_detail'] = $clerk_detail = clerk_helper::get_clerk_detail($item->KodeClerk);
		$this->data['clerk_payment'] = $clerk_payment = clerk_helper::get_clerk_payment($this->user_auth->User_ID, $get_clerk->WaktuMulaiClerk);

		
		$this->template
			->title(lang('heading:clerk_view'))
			->set('subtitle', lang('heading:clerk_view'))
			->set_breadcrumb(lang('heading:clerk_view'))
			->build("clerk/end", $this->data);
	}
	
	public function lookup_collection ()
	{
		$this->datatable_collection( 1 );
	}
	
	public function datatable_collection( $Status = 1 )
	{
		$start = $this->input->get_post('start', true);
        $length = $this->input->get_post('length', true);
        $order = $this->input->get_post('order', true);
        $columns = $this->input->get_post('columns', true);
        $search = $this->input->get_post('search', true);
        $draw = $this->input->get_post('draw', true);
		
		$db_where = array();
		$db_like = array();
		
		//ambil role dari session
		$role_id = $this->session->userdata('user_role_id');
		$role_code = $this->session->userdata('user_role_code');
		//prepare defautl flter select * from mGroup
		if(in_array($role_id, [66, 64])) //kasir dan farmasi
		{
			$db_where['a.UserID'] = $this->user_auth->User_ID;
		}
		
		if($this->input->post('db_where')): foreach($this->input->post('db_where') as $where):
			$db_where[$where['key']] = $where['val'];
		endforeach;endif;
		
		if($this->input->post('db_like')): foreach($this->input->post('db_like') as $where):
			$db_like[$where['key']] = $where['val'];
		endforeach;endif;
		
		// preparing default
		if( isset($search['value']) && ! empty($search['value']) )
        {
            $keywords = $this->db->escape_str( $search['value'] );
			
			$db_like[ $this->db->escape_str("a.KodeClerk") ] = $keywords;
			$db_like[ $this->db->escape_str("b.Username") ] = $keywords;
			$db_like[ $this->db->escape_str("b.Nama_Asli") ] = $keywords;
			$db_like[ $this->db->escape_str("b.Nama_Singkat") ] = $keywords;
        }
		
		// get total records
		$records_total = $this->get_model()->dt_records_total($db_where);
		
		// get total filtered	
		$records_filtered = $this->get_model()->dt_records_filtered($db_where, $db_like);
		
		// get result filtered
		$result = $this->get_model()->dt_results($db_where, $db_like, $order, $columns, $start, $length);


        // Output
        $output = array(
				'draw' => intval($draw),
				'recordsTotal' => $records_total,
				'recordsFiltered' => $records_filtered,
				'data' => array()
			);
		
		foreach($result as $row)
        {    
			$row->editable = 1; 
			//filter dengan role code
			if(in_array($role_code, ['cashier', 'pharmacy']) && !empty($row->WaktuAkhirClerk)) //kasir dan farmasi
			{
				$datetime = DateTime::createFromFormat('Y-m-d H:i:s.u', $row->WaktuAkhirClerk);	
				$datetime->add(new DateInterval('PT8H'));
				$row->editable = $datetime->format('Y-m-d H:i:s') > date('Y-m-d H:i:s') ? 1 : 0; 
			}
			
            $output['data'][] = $row;
        }
		
		response_json( $output );
    }
}

