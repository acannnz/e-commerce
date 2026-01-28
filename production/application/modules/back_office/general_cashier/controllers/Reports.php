<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reports extends Admin_Controller
{
	protected $_translation = 'general_cashier';	
	protected $_model = 'report_m';
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('general_cashier');
				
		$this->load->model( "report_m" );
		
		$this->load->helper("general_cashier");
		
		
		$this->page = "general_cashier";
		$this->template->title( lang("reports:page") . ' - ' . $this->config->item('company_name') );
	}
	
	public function index()
	{
		$data = array(
				'page' => $this->page,
				'house' => $this->house_m->get_house( $this->_house_id ),
				'options_type' => $this->type_m->options_type(),
				"lookup_suppliers" => base_url("general_cashier/reports/lookup_suppliers"),
				"form" => TRUE,
				"fileinput" => TRUE,
				'datatables' => TRUE,
			);
		
		$this->template
			->set( "heading", lang("reports:page") )
			->set_breadcrumb( lang("general_cashiers:page"), base_url("general_cashier/facturs") )
			->set_breadcrumb( lang("reports:page"), base_url("general_cashier/reports") )
			->build('reports/form', (isset($data) ? $data : NULL));
	}
		
	public function lookup_suppliers( ){
	
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'reports/lookup/suppliers' );
		} 
	}

	public function print_general_cashier_card( )
	{
		if ( $this->input->post())
		{
			$this->load->helper( "report" );

			$post_data = (object) $this->input->post("f");
			
			$house_id = $this->_house_id;
	
			$data['collections'] = $this->db->where("supplier_id", $post_data->supplier_id )
										->where("date >=", $post_data->date_start )
										->where("date <=", $post_data->date_end )
										->from("ap_card_general_cashiers")
										->order_by("date", "ASC")
										->get()
										->result()
										;
	
	
			$data["file_name"] = sprintf("Kartu-Hutang-%s-%s.pdf", date("Y m d", strtotime($post_data->date_start)), date("Y m d", strtotime($post_data->date_end)));
			$data["house"] = $this->house_m->get_house( $this->_house_id );
			$data["data"] = $post_data;
			//print_r($this->input->post());
			//print_r($data['collections']);
			//exit(0);
				
			$html_header = "";
			$html_footer = "";
			$html_content = $this->load->view( "reports/print/general_cashier_card", $data, TRUE );
			
			//print $html_content;exit(0);
			
			$this->load->helper( "report" );
			report_helper::print_pdf( $html_content, $data['file_name'] );
			
			exit(0);
		}
	}

	public function print_general_cashier_recap( )
	{
		if ( $this->input->post())
		{
			$this->load->helper( "report" );

			$post_data = (object) $this->input->post("f");
			
			$house_id = $this->_house_id;
			
			if ( $post_data->type_id == 0  )
			{
				$type = $this->type_m->options_type();
				
				if ( !empty( $type ) ): foreach( $type as $k => $v ):
				$data['collections'][$v] = (object) $this->db->select("a.*, b.code, b.supplier_name, SUM(a.beginning_balance) AS sum_beginning_balance, SUM(a.debit) AS sum_debit, SUM(a.credit) AS sum_credit")
											->where("a.date >=", $post_data->date_start )
											->where("a.date <=", $post_data->date_end )
											->where("a.type_id", $k)
											->from("ap_card_general_cashiers a")
											->join("common_suppliers b", "a.supplier_id = b.id", "LEFT OUTER")
											->group_by("a.supplier_id")
											->order_by("a.date", "ASC")
											->get()
											->result()
											;
				endforeach; endif;
	
			} else {
				
				$type = $this->db->where("id", $post_data->type_id)->get("ap_types")->row();
				
				$data['collections'][$type->type_name] = $this->db->select("a.*, b.code, b.supplier_name, SUM(a.beginning_balance) AS sum_beginning_balance, SUM(a.debit) AS sum_debit, SUM(a.credit) AS sum_credit")
											->where("a.date >=", $post_data->date_start )
											->where("a.date <=", $post_data->date_end )
											->where("a.type_id", $post_data->type_id )
											->from("ap_card_general_cashiers a")
											->join("common_suppliers b", "a.supplier_id = b.id", "LEFT OUTER")
											->group_by("a.supplier_id")
											->order_by("a.date", "ASC")
											->get()
											->result()
											;			
			}
			
			$data["file_name"] = sprintf("Kartu-Hutang-%s-%s.pdf", date("Y m d", strtotime($post_data->date_start)), date("Y m d", strtotime($post_data->date_end)));
			$data["house"] = $this->house_m->get_house( $this->_house_id );
			$data["data"] = $post_data;
			//print_r($this->input->post());
			//print_r($data['collections']);
			//exit(0);
				
			$html_header = "";
			$html_footer = "";
			$html_content = $this->load->view( "reports/print/general_cashier_recap", $data, TRUE );
			
			//print $html_content;exit(0);
			
			$this->load->helper( "report" );
			report_helper::print_pdf( $html_content, $data['file_name'] );
			
			exit(0);
		}
	}
}



