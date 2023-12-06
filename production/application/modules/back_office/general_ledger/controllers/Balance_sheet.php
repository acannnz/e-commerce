<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Balance_sheet extends Admin_Controller
{
	protected $_translation = 'general_ledger';	
	protected $_model = 'balance_sheet_m';
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('general_ledger');
				
		$this->page = "balance_sheeting_balance_sheet";
		$this->template->title( lang("balance_sheets:page") . ' - ' . $this->config->item('company_name') );
	}
	
	public function index()
	{
		$data = array(
				"page" => $this->page,
				"form" => TRUE,
				"jstree" => TRUE,
				"activa_tree_collection" => base_url("general-ledger/balance-sheet/activa_tree_collection"),
				"pasiva_tree_collection" => base_url("general-ledger/balance-sheet/pasiva_tree_collection"),
			);
					
		$this->template
			->set( "heading", lang("balance_sheets:page") )
			->set_breadcrumb( lang("general_ledger:page") )
			->set_breadcrumb( lang("balance_sheets:page"), base_url("general-ledger/balance-sheets") )
			->build('balance_sheets/tree', (isset($data) ? $data : NULL));
	}
	
	public function get_summary()
	{
		$date = $this->input->post('date');		
		$summary = $this->get_model()->get_summary_balance_sheet( $date );

		print_r(json_encode($summary, JSON_NUMERIC_CHECK));
		exit(0);
	}
	
	public function activa_tree_collection()
    {
		$this->load->helper("account");
		$this->load->model("account_m");

		$date = $this->input->post('date');
		
		$concepts = $this->account_m->get_concepts();
		$collection = $this->get_model()->get_activa_balance( $date );
		
		$tree_collection = array();
		foreach( $collection as $row ){
			$data = array(
				"id" => (string) $row->Akun_No,
				"Akun_No" => $row->Akun_No,
				"parent" => $this->_set_parent( $concepts, $row ),
				"type" => $this->_set_type( $row ),
				"value" => $row->Nilai,
			);
			
			$data["text"] = $data["type"] == "header"
							? sprintf("%s %s <span class='pull-right'><b>%s</b></span>", $row->Akun_No, $row->AkunName, number_format($row->Nilai), 2, ',', '.')
							: sprintf("%s %s <span class='pull-right'>%s</span>", $row->Akun_No, $row->AkunName, number_format($row->Nilai), 2, ',', '.');
			$data["icon"] = $this->_set_icon( $data["type"] ); 
			if ( $row->LevelKe == 1) 
			{ 
				$data["text"] = sprintf("%s %s <span class='pull-right text-danger'><b>%s</b></span>", $row->Akun_No, $row->AkunName, number_format($row->Nilai), 2, ',', '.');
				unset($data['icon']);
			}

			$data['state'] =($row->LevelKe <= 2) ?  array("opened" => TRUE) : array();
			
			$tree_collection[] = $data;
		}
				
		print_r(json_encode($tree_collection, JSON_NUMERIC_CHECK));
		exit(0);		
    }

	public function pasiva_tree_collection( )
    {
        
		$this->load->helper("account");
		$this->load->model("account_m");

		$date = $this->input->post('date');
		
		$concepts = $this->account_m->get_concepts();
		$collection = $this->get_model()->get_pasiva_balance( $date );
		
		$tree_collection = array();
		foreach( $collection as $row ){
			$data = array(
				"id" => (string) $row->Akun_No,
				"Akun_No" => $row->Akun_No,
				"parent" => $this->_set_parent( $concepts, $row ),
				"type" => $this->_set_type( $row ),
				"value" => $row->Nilai,
			);
			
			$data["text"] = $data["type"] == "header"
							? sprintf("%s %s <span class='pull-right'><b>%s</b></span>", $row->Akun_No, $row->AkunName, number_format($row->Nilai), 2, ',', '.')
							: sprintf("%s %s <span class='pull-right'>%s</span>", $row->Akun_No, $row->AkunName, number_format($row->Nilai), 2, ',', '.');
			$data["icon"] = $this->_set_icon( $data["type"] ); 
			if ( $row->LevelKe == 1) 
			{ 
				$data["text"] = sprintf("%s %s <span class='pull-right text-danger'><b>%s</b></span>", $row->Akun_No, $row->AkunName, number_format($row->Nilai), 2, ',', '.');
				unset($data['icon']);
			}

			$data['state'] =($row->LevelKe <= 2) ?  array("opened" => TRUE) : array();

			$tree_collection[] = $data;
		}
				
		print_r(json_encode($tree_collection, JSON_NUMERIC_CHECK));
		exit(0);
    }
	
	public function trial_balance()
	{
		$data = array(
				"page" => $this->page,
				"form" => TRUE,
				"datatables" => TRUE,
				"trial_balance_collection" => base_url("general-ledger/balance-sheet/trial_balance_collection"),
			);
					
		$this->template
			->set( "heading", "NERACA SALDO" )
			->set_breadcrumb( lang("general_ledger:page") )
			->set_breadcrumb( "Neraca Saldo", base_url("general-ledger/balance-sheets/trial_balance") )
			->build('balance_sheets/trial_balance', (isset($data) ? $data : NULL));
	}


	public function trial_balance_collection()
    {
		$start = $this->input->get_post('start', true);
        $length = $this->input->get_post('length', true);
        $order = $this->input->get_post('order', true);
        $columns = $this->input->get_post('columns', true);
        $search = $this->input->get_post('search', true);
        $draw = $this->input->get_post('draw', true);
		
		$this->load->helper("account");
		$this->load->model("account_m");

		$date_start = $this->input->post('date_start');
		$date_until = $this->input->post('date_until');
		$collection = $this->get_model()->get_trial_balance( $date_start, $date_until );
						
		// Output
        $output = array(
				'draw' => intval($draw),
				'recordsTotal' => count($collection),
				'recordsFiltered' => count($collection),
				'data' => array()
			);
        
        foreach($collection as $row)
        {
			$space = $row->LevelKe * 2;
			$row->Akun_No = sprintf("%s%s", str_repeat('&nbsp;', $space), $row->Akun_No);
            $output['data'][] = $row;
        }

		$this->template
			->build_json( $output );
    }

	private function _set_parent( $concepts, $child )
	{
		$parent_level = $child->LevelKe - 1;
		if( $parent_level == 0) return "#";
		
		$parent_digit = $concepts[ $parent_level ]->Jumlah_Digit;
		return (string) substr($child->Akun_No, 0, $parent_digit); // return parent Account Number
	}

	private function _set_type( $account )
	{
		if ( $account->LevelKe == 1) return "root";
		
		$get_child = account_helper::get_child( $account->Akun_No, $account->LevelKe);
		
		return !empty($get_child) ? "header" : "child";
	}

	private function _set_icon( $type )
	{
		switch ( $type )
		{
			case "header":
				$icon = "fa fa-folder-o"; break;
			case "child":
				$icon = "fa fa-file-o"; break;
			default:
				$icon = FALSE;
		}
		
		return $icon;
	}	
	
	public function export()
	{
		if ( $this->input->post("date") )
		{
			$date = $this->input->post("date"); 
			
			$this->load->helper( "balance_sheet" );		
	
			balance_sheet_helper::export_balance_sheet( $date);		
			exit(0);
		}
	}
		
}