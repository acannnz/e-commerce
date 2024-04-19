<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Income_loss extends Admin_Controller
{
	protected $_translation = 'general_ledger';	
	protected $_model = 'income_loss_m';
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('general_ledger');
		
		$this->page = "balance_sheeting_balance_sheet";
		$this->template->title( lang("income_loss:page") . ' - ' . $this->config->item('company_name') );
	}
	
	public function index()
	{		
		$data = array(
				'page' => $this->page,
				"form" => TRUE,
				"jstree" => TRUE,
				"tree_collection" => base_url("general-ledger/income-loss/tree_collection")
			);
					
		$this->template
			->set( "heading", lang("income_loss:page") )
			->set_breadcrumb( lang("general_ledger:page") )
			->set_breadcrumb( lang("income_loss:page"), base_url("general-ledger:income-loss") )
			->build('income_loss/tree', (isset($data) ? $data : NULL));
	}
	
	public function get_summary()
	{
		$date = $this->input->post('date');		
		$get_summary  = $this->get_model()->get_summary_income_loss( $date );
		
		$summary = array();
		foreach($get_summary AS $row)
		{
			$summary[$row->Group_ID] = $row->Nilai;
		}
		/*
			TOTAL PENDAPATAN 		= 7720862783.00	(4)
			(-)
			TOTAL HPP 			= 4008738610.72	(5)
			(-)
			GROSS PROFIT			= 3712124172.00
			(-)
			BIAYA OPERASIONAL 		= 4964955548.62	(6)
			(-)
			EBITDA				= -1252831376.00
			(-)
			PEND/BIAYA NON OPERASIONAL 	= -3748579415.15 (7)
			(-)
			Penyusutan, Amortasi & cadangan	= -1152170944.00 (8)
			(=)
			EBIT 				= 3647918981.00
			(-)
			BUNGA DAN PAJAK 		= 427927337.00	 (10)
			(=)
			EAT 				= 3219991646.00
		*/

		$income 			= @$summary[4];
		$hpp 				= @$summary[5];
		$gross_profit 		= @$summary[4] - @$summary[5];
		$operating_cost 	= @$summary[6];
		$ebitda 			= $gross_profit - $operating_cost;
		$non_operating_cost = @$summary[7];
		$pac 				= @$summary[8]; #Penyusutan, Amortasi & cadangan
		$ebit 				= $ebitda - @$summary[7] - @$pac;
		$interest_taxes		= @$summary[10];
		$eat				= $ebit - @$summary[10];


		$output = array(
			'income' 			=> number_format($income, 2, ',','.'),
			'hpp' 				=> number_format($hpp, 2, ',','.'),
			'gross_profit' 		=> number_format($gross_profit, 2, ',','.'),
			'operating_cost' 	=> number_format($operating_cost, 2, ',','.'),
			'ebitda' 			=> number_format($ebitda, 2, ',','.'),
			'non_operating_cost'=> number_format($non_operating_cost, 2, ',','.'),
			'pac' 				=> number_format($pac, 2, ',','.'), //Penyusutan, Amortasi & cadangan
			'ebit' 				=> number_format($ebit, 2, ',','.'),
			'interest_taxes'	=> number_format($interest_taxes, 2, ',','.'),
			'eat'				=> number_format($eat, 2, ',','.'),
		);
		
		print_r(json_encode($output, JSON_NUMERIC_CHECK));
		exit(0);
	}
	
	public function tree_collection()
    {
		$this->load->helper("account");
		$this->load->model("account_m");
		$date = $this->input->post('date');
		$show_zero_value = $this->input->post('show_zero_value');
		
		$detail  = $this->get_model()->get_detail_account_income_loss( $date, FALSE, $show_zero_value );
		
		$concepts = $this->account_m->get_concepts();
		
		$tree_collection = array();
		foreach( $detail as $row ){
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
			if ( $row->levelke == 1) unset($data['icon']);
			
			$tree_collection[] = $data;
		}
				
		print_r(json_encode($tree_collection, JSON_NUMERIC_CHECK));
		exit(0);
		
    }
	
	public function quarterly()
	{
		$data = array(
				'page' => $this->page,
				"form" => TRUE,
				"jstree" => TRUE,
				"tree_collection" => base_url("general-ledger/income-loss/tree_quarterly_collection")
			);
					
		$this->template
			->set( "heading", lang("income_loss:page") . ' Triwulan')
			->set_breadcrumb( lang("general_ledger:page") )
			->set_breadcrumb( lang("income_loss:page") . ' Triwulan', base_url("general-ledger:income-loss") )
			->build('income_loss/tree_quarterly', (isset($data) ? $data : NULL));
	}
	
	public function get_quarterly_summary()
	{
		$quarterly = $this->input->post('quarterly');
		$year = $this->input->post('year');	
		$get_summary  = $this->get_model()->get_summary_income_loss_quarterly( $quarterly, $year );
		
		$summary = [];
		foreach($get_summary AS $row):
			$summary['Nilai'][$row->Group_ID] = $row->Nilai1;
			$summary['Nilai2'][$row->Group_ID] = $row->Nilai2;
			$summary['Nilai3'][$row->Group_ID] = $row->Nilai3;
		endforeach;
		/*
			TOTAL PENDAPATAN 		= 7720862783.00	(4)
			(-)
			TOTAL HPP 			= 4008738610.72	(5)
			(-)
			GROSS PROFIT			= 3712124172.00
			(-)
			BIAYA OPERASIONAL 		= 4964955548.62	(6)
			(-)
			EBITDA				= -1252831376.00
			(-)
			PEND/BIAYA NON OPERASIONAL 	= -3748579415.15 (7)
			(-)
			Penyusutan, Amortasi & cadangan	= -1152170944.00 (8)
			(=)
			EBIT 				= 3647918981.00
			(-)
			BUNGA DAN PAJAK 		= 427927337.00	 (10)
			(=)
			EAT 				= 3219991646.00
		*/
		
		$output = [];
		foreach($summary AS $key => $row):		
			$income 			= @$row[4];
			$hpp 				= @$row[5];
			$gross_profit 		= @$row[4] - @$row[5];
			$operating_cost 	= @$row[6];
			$ebitda 			= $gross_profit - $operating_cost;
			$non_operating_cost = @$row[7];
			$pac 				= @$row[8]; #Penyusutan, Amortasi & cadangan
			$ebit 				= $ebitda - @$row[7] - @$pac->{$key};
			$interest_taxes		= @$row[10];
			$eat				= $ebit - @$row[10];
	
	
			$output[] = [
				'income' 			=> number_format($income, 2, ',','.'),
				'hpp' 				=> number_format($hpp, 2, ',','.'),
				'gross_profit' 		=> number_format($gross_profit, 2, ',','.'),
				'operating_cost' 	=> number_format($operating_cost, 2, ',','.'),
				'ebitda' 			=> number_format($ebitda, 2, ',','.'),
				'non_operating_cost'=> number_format($non_operating_cost, 2, ',','.'),
				'pac' 				=> number_format($pac->{$key}, 2, ',','.'), //Penyusutan, Amortasi & cadangan
				'ebit' 				=> number_format($ebit, 2, ',','.'),
				'interest_taxes'	=> number_format($interest_taxes, 2, ',','.'),
				'eat'				=> number_format($eat, 2, ',','.'),
			];
		endforeach;
		
		print_r(json_encode($output, JSON_NUMERIC_CHECK));
		exit(0);
	}
	
	public function tree_quarterly_collection()
    {
		$this->load->helper("account");
		$this->load->model("account_m");
		$quarterly = $this->input->post('quarterly');
		$year = $this->input->post('year');
		$show_zero_value = $this->input->post('show_zero_value');
		
		$detail  = $this->get_model()->get_detail_account_income_loss_quarterly( $quarterly, $year );
		
		$concepts = $this->account_m->get_concepts();
		
		$tree_collection = [];
		foreach( $detail as $row ){
			$data = array(
				"id" => (string) $row->Akun_No,
				"Akun_No" => $row->Akun_No,
				"parent" => $this->_set_parent( $concepts, $row ),
				"type" => $this->_set_type( $row ),
				"value" => $row->Nilai,
			);
			
			$data["text"] = $row->Akun_No .' '. $row->AkunName;
			$data['data'] = [
				'Nilai' => number_format($row->Nilai, 2, ',', '.'),
				'Nilai2' => number_format($row->Nilai2, 2, ',', '.'),
				'Nilai3' => number_format($row->Nilai3, 2, ',', '.')
			];
			$data["icon"] = $this->_set_icon( $data["type"] ); 
			if ( $row->levelke == 1) unset($data['icon']);
			
			$tree_collection[] = $data;
		}
				
		print_r(json_encode($tree_collection, JSON_NUMERIC_CHECK));
		exit(0);
		
    }
	
	public function annual()
	{		
		$data = array(
				'page' => $this->page,
				"form" => TRUE,
				"jstree" => TRUE,
				"tree_collection" => base_url("general-ledger/income-loss/tree_annual_collection")
			);
					
		$this->template
			->set( "heading", lang("income_loss:page") . " Tahunan" )
			->set_breadcrumb( lang("general_ledger:page") )
			->set_breadcrumb( lang("income_loss:page") . " Tahunan", base_url("general-ledger:income-loss") )
			->build('income_loss/tree_annual', (isset($data) ? $data : NULL));
	}
	
	public function get_annual_summary()
	{
		$date = $this->input->post('date');		
		$get_summary  = $this->get_model()->get_summary_income_loss( $date, TRUE );
		
		$summary = array();
		foreach($get_summary AS $row)
		{
			$summary[$row->Group_ID] = $row->Nilai;
		}
		/*
			TOTAL PENDAPATAN 		= 7720862783.00	(4)
			(-)
			TOTAL HPP 			= 4008738610.72	(5)
			(-)
			GROSS PROFIT			= 3712124172.00
			(-)
			BIAYA OPERASIONAL 		= 4964955548.62	(6)
			(-)
			EBITDA				= -1252831376.00
			(-)
			PEND/BIAYA NON OPERASIONAL 	= -3748579415.15 (7)
			(-)
			Penyusutan, Amortasi & cadangan	= -1152170944.00 (8)
			(=)
			EBIT 				= 3647918981.00
			(-)
			BUNGA DAN PAJAK 		= 427927337.00	 (10)
			(=)
			EAT 				= 3219991646.00
		*/
		
		$income 			= @$summary[4];
		$hpp 				= @$summary[5];
		$gross_profit 		= @$summary[4] - @$summary[5];
		$operating_cost 	= @$summary[6] ; 
		$ebitda 			= $gross_profit - $operating_cost;
		$non_operating_cost = @$summary[7];
		$pac 				= @$summary[8]; #Penyusutan, Amortasi & cadangan
		$ebit 				= $ebitda - @$summary[7] - @$pac;
		$interest_taxes		= @$summary[10];
		$eat				= $ebit - @$summary[10];


		$output = array(
			'income' 			=> number_format($income, 2, ',','.'),
			'hpp' 				=> number_format($hpp, 2, ',','.'),
			'gross_profit' 		=> number_format($gross_profit, 2, ',','.'),
			'operating_cost' 	=> number_format($operating_cost, 2, ',','.'),
			'ebitda' 			=> number_format($ebitda, 2, ',','.'),
			'non_operating_cost'=> number_format($non_operating_cost, 2, ',','.'),
			'pac' 				=> number_format($pac, 2, ',','.'), //Penyusutan, Amortasi & cadangan
			'ebit' 				=> number_format($ebit, 2, ',','.'),
			'interest_taxes'	=> number_format($interest_taxes, 2, ',','.'),
			'eat'				=> number_format($eat, 2, ',','.'),
		);
		
		print_r(json_encode($output, JSON_NUMERIC_CHECK));
		exit(0);
	}
	
	public function tree_annual_collection()
    {
		$this->load->helper("account");
		$this->load->model("account_m");
		$date = $this->input->post('date');
		$show_zero_value = $this->input->post('show_zero_value');
		
		$detail  = $this->get_model()->get_detail_account_income_loss( $date, TRUE, $show_zero_value );
		
		$concepts = $this->account_m->get_concepts();
		
		$tree_collection = array();
		foreach( $detail as $row ){
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
			if ( $row->levelke == 1) unset($data['icon']);
			
			$tree_collection[] = $data;
		}
				
		print_r(json_encode($tree_collection, JSON_NUMERIC_CHECK));
		exit(0);
		
    }

	private function _set_parent( $concepts, $child )
	{
		$parent_level = $child->levelke - 1;
		if( $parent_level == 0) return "#";
		
		$parent_digit = $concepts[ $parent_level ]->Jumlah_Digit;
		return (string) substr($child->Akun_No, 0, $parent_digit); // return parent Account Number
	}

	private function _set_type( $account )
	{
		if ( $account->levelke == 1) return "root";
		
		$get_child = account_helper::get_child( $account->Akun_No, $account->levelke);
		
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
	
	public function export( $date = NULL )
	{
		if ( $this->input->post("date") )
		{
			$date = $this->input->post("date"); 
			$annual = $this->input->post("annual") ? TRUE : FALSE; 
			
			$this->load->helper( "income_loss" );		
	
			income_loss_helper::export_income_loss( $date, $annual );		
			exit(0);
		}
	}
		
}