<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Balance_sheet_m extends Public_Model
{
	public $table = 'tab_gl_monthly_closing';
	public $primary_key = 'id';
	
	public $rules;
	
	public function __construct()
	{
		$this->rules = array('insert' => array(
				'parent_id' => array(
						'field' => 'parent_id',
						'label' => lang( 'balance_sheets:parent_label' ),
						'rules' => 'required'
					),
				'parent_ids' => array(
						'field' => 'parent_ids',
						'label' => lang( 'balance_sheets:parent_ids_label' ),
						'rules' => ''
					),
				'level' => array(
						'field' => 'level',
						'label' => lang( 'balance_sheets:level_label' ),
						'rules' => 'required'
					),
				'normal_pos' => array(
						'field' => 'normal_pos',
						'label' => lang( 'balance_sheets:normal_pos_label' ),
						'rules' => 'required'
					),
				'component' => array(
						'field' => 'component',
						'label' => lang( 'balance_sheets:component_label' ),
						'rules' => 'required'
					),
				'balance_sheet_number' => array(
						'field' => 'balance_sheet_number',
						'label' => lang( 'balance_sheets:balance_sheet_number_label' ),
						'rules' => 'required'
					),
				'balance_sheet_name' => array(
						'field' => 'balance_sheet_name',
						'label' => lang( 'balance_sheets:balance_sheet_name_label' ),
						'rules' => 'required'
					),
				'balance_sheet_description' => array(
						'field' => 'balance_sheet_description',
						'label' => lang( 'balance_sheets:balance_sheet_description_label' ),
						'rules' => ''
					),
				'convert_permanent' => array(
						'field' => 'convert_permanent',
						'label' => lang( 'balance_sheets:convert_permanent_label' ),
						'rules' => ''
					),
				'currency_code' => array(
						'field' => 'currency_code',
						'label' => lang( 'balance_sheets:currency_code_label' ),
						'rules' => 'required'
					),
				'integration' => array(
						'field' => 'integration',
						'label' => lang( 'balance_sheets:integration_label' ),
						'rules' => ''
					),
				'integration_source' => array(
						'field' => 'integration_source',
						'label' => lang( 'balance_sheets:integration_source_label' ),
						'rules' => ''
					),
				'state' => array(
						'field' => 'state',
						'label' => lang( 'balance_sheeting:state_label' ),
						'rules' => ''
					),
			));
		
		parent::__construct();
	}

	public function get_summary_balance_sheet( $date )	
	{
		$date = DateTime::createFromFormat("Y-m", $date );
		$date_start = $date->format('Y-m-01');
		$date_end = $date->format('Y-m-t');
		
		$summary['activa']= number_format( (float) @$this->db
								->query("
									SELECT SUM( Nilai ) AS Nilai
										FROM dbo.Penjelasan_Neraca_Grid('{$date_start}', '{$date_end}', 0, 1, 1, 10, 1) 
										WHERE group_id = 1  AND LevelKe = 1 
								")
								->row()->Nilai, 2, ',', '.');

		$summary['pasiva']= number_format( (float) @$this->db
								->query("
									SELECT SUM( Nilai ) AS Nilai 
										FROM dbo.Penjelasan_Neraca_Grid('{$date_start}', '{$date_end}', 0, 1, 1, 10, 1) 
										WHERE group_id > 1  AND LevelKe = 1 
								")
								->row()->Nilai, 2, ',', '.');

		$summary['balance']= number_format( (float) @$this->db
								->query("
									SELECT 
										( 
											SELECT SUM(Nilai)
												FROM dbo.Penjelasan_Neraca_Grid('{$date_start}', '{$date_end}', 0, 1, 1, 10, 1) 
												WHERE group_id=1  and LevelKe=1 
										) - ( 
											SELECT SUM(Nilai)
												FROM dbo.Penjelasan_Neraca_Grid('{$date_start}', '{$date_end}', 0, 1, 1, 10, 1) 
												WHERE group_id > 1  and LevelKe=1 
										) AS Balance 

								")
								->row()->Balance, 2, ',', '.');		
		return $summary;
	}
		
	public function get_activa_balance( $date )
	{
		$date = DateTime::createFromFormat("Y-m", $date );
		$date_start = $date->format('Y-m-01');
		$date_end = $date->format('Y-m-t');
		
		return 
			$query = $this->db
					->query("
						SELECT a.*, Akun_ID, Induk,Integrasi,SumberIntegrasi  
						FROM dbo.Penjelasan_Neraca_Grid('{$date_start}', '{$date_end}', 0, 1, 1, 10, 1) a
						LEFT OUTER JOIN Mst_Akun b ON a.Akun_No = b.Akun_No
						WHERE a.group_id = 1  
						ORDER BY a.group_id,a.Akun_No
					")
					->result();

	}

	public function get_pasiva_balance( $date )
	{
		$date = DateTime::createFromFormat("Y-m", $date );
		$date_start = $date->format('Y-m-01');
		$date_end = $date->format('Y-m-t');
		
		return 
			$query = $this->db
					->query("
						SELECT a.*, Akun_ID, Induk,Integrasi,SumberIntegrasi  
						FROM dbo.Penjelasan_Neraca_Grid('{$date_start}', '{$date_end}', 0, 1, 1, 10, 1) a
						LEFT OUTER JOIN Mst_Akun b ON a.Akun_No = b.Akun_No
						WHERE a.group_id > 1  
						ORDER BY a.group_id,a.Akun_No
					")
					->result();

	}
	
	public function get_trial_balance( $date_start, $date_until )
	{		
		return 
			$query = $this->db
					->query("SELECT * FROM Penjelasan_NeracaSaldo_Grid('{$date_start}', '{$date_until}', 1,'','') ORDER BY Group_id, Akun_No")
					->result();

	}
		
}