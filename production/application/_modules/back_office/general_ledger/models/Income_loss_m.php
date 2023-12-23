<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Income_loss_m extends Public_Model
{
	public $table = 'tab_gl_monthly_posted';
	public $primary_key = 'id';
	
	public $rules;
	
	public function __construct()
	{
		$this->rules = array('insert' => array(
				'parent_id' => array(
						'field' => 'parent_id',
						'label' => lang( 'income_loss:parent_label' ),
						'rules' => 'required'
					),
				'parent_ids' => array(
						'field' => 'parent_ids',
						'label' => lang( 'income_loss:parent_ids_label' ),
						'rules' => ''
					),
				'level' => array(
						'field' => 'level',
						'label' => lang( 'income_loss:level_label' ),
						'rules' => 'required'
					),
				'normal_pos' => array(
						'field' => 'normal_pos',
						'label' => lang( 'income_loss:normal_pos_label' ),
						'rules' => 'required'
					),
				'component' => array(
						'field' => 'component',
						'label' => lang( 'income_loss:component_label' ),
						'rules' => 'required'
					),
				'balance_sheet_number' => array(
						'field' => 'balance_sheet_number',
						'label' => lang( 'income_loss:balance_sheet_number_label' ),
						'rules' => 'required'
					),
				'balance_sheet_name' => array(
						'field' => 'balance_sheet_name',
						'label' => lang( 'income_loss:balance_sheet_name_label' ),
						'rules' => 'required'
					),
				'balance_sheet_description' => array(
						'field' => 'balance_sheet_description',
						'label' => lang( 'income_loss:balance_sheet_description_label' ),
						'rules' => ''
					),
				'convert_permanent' => array(
						'field' => 'convert_permanent',
						'label' => lang( 'income_loss:convert_permanent_label' ),
						'rules' => ''
					),
				'currency_code' => array(
						'field' => 'currency_code',
						'label' => lang( 'income_loss:currency_code_label' ),
						'rules' => 'required'
					),
				'integration' => array(
						'field' => 'integration',
						'label' => lang( 'income_loss:integration_label' ),
						'rules' => ''
					),
				'integration_source' => array(
						'field' => 'integration_source',
						'label' => lang( 'income_loss:integration_source_label' ),
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
	
	public function find_balance_sheet_list( $options = array())
	{
		$this->db->order_by( 'balance_sheet_name', 'asc' );
		$this->db->where( $options );
		
		$query = $this->db->get( $this->table );
		
		$data  = array();

		if( $query->num_rows() > 0 )
		{
			foreach( $query->result_object() as $row )
			{
				$data[ $row->id ] = $row->balance_sheet_name;
			} //$query->result_array() as $row
		} //$query->num_rows() > 0
		
		return $data;
	}
	
	public function get_summary_income_loss( $date, $annual = FALSE )	
	{
		if( $annual )
		{
			$date = DateTime::createFromFormat("Y", $date );
			$date_start = $date->format('Y-01-01');
			$date_end = $date->format('Y-12-31');
		} else {
			$date = DateTime::createFromFormat("Y-m", $date );
			$date_start = $date->format('Y-m-01');
			$date_end = $date->format('Y-m-t');
		}
		
		return
			$this->db
				->query("
					SELECT * FROM
						(
							SELECT SUM(Nilai) AS Nilai, Group_ID 
							FROM dbo.Penjelasan_LabaRugi_Grid( '{$date_start}','{$date_end}',1,1,1,1,2011,1 ) summary 
						
							WHERE  LevelKe=1 AND Nilai <> 0 
							GROUP BY summary.Group_ID
						) laba_rugi 
						ORDER BY laba_rugi.Nilai DESC
				")
				->result();
	}
	
	public function get_detail_account_income_loss( $date, $annual = FALSE, $show_zero_value = 1 )
	{
		if( $annual )
		{
			$date = DateTime::createFromFormat("Y", $date );
			$date_start = $date->format('Y-01-01');
			$date_end = $date->format('Y-12-31');
		} else {
			$date = DateTime::createFromFormat("Y-m", $date );
			$date_start = $date->format('Y-m-01');
			$date_end = $date->format('Y-m-t');
		}
		
		$query = ( $show_zero_value ) 
			? $this->db
				->query("
					SELECT levelke, Group_ID, Akun_No, AkunName, Induk, SUM(nilai) AS Nilai  
						FROM dbo.Penjelasan_LabaRugi_Grid('{$date_start}','{$date_end}',1,10,1,1,2011,1) 
						
						GROUP BY levelke, Group_ID, Akun_No, AkunName, Induk 
						ORDER BY Group_ID,AKUn_NO
				")

			: $this->db
				->query("
					SELECT levelke, Mst_Akun.Group_ID, Mst_Akun.Akun_No, AkunName, Mst_Akun.Induk, SUM(laba_rugi.nilai) AS Nilai 
						FROM dbo.Penjelasan_LabaRugi_Grid('{$date_start}','{$date_end}',1,10,1,1,2011,1) laba_rugi 
						INNER JOIN Mst_Akun ON laba_rugi.Akun_No = Mst_Akun.Akun_NO 
						
						WHERE Mst_Akun.Induk = 1 OR nilai <> 0 
						GROUP BY levelke, Mst_Akun.Group_ID, Mst_Akun.Akun_No, AkunName, Mst_Akun.Induk  
						ORDER BY Mst_Akun.Group_ID, Mst_Akun.Akun_No
				");
				
		return $query->result();

	}
	
	public function get_one_account_income_loss($date, $Akun_No, $annual = FALSE)
	{
		if( $annual )
		{
			$date = DateTime::createFromFormat("Y", $date );
			$date_start = $date->format('Y-01-01');
			$date_end = $date->format('Y-12-31');
		} else {
			$date = DateTime::createFromFormat("Y-m", $date );
			$date_start = $date->format('Y-m-01');
			$date_end = $date->format('Y-m-t');
		}
		
		return 
			$this->db->query("
					SELECT * FROM dbo.Penjelasan_LabaRugi_Grid('{$date_start}','{$date_end}',1,10,1,1,2011,1 ) 
					WHERE Akun_No = '{$Akun_No}'
				")->row();
	}
	
	public function get_summary_income_loss_quarterly( $quarterly, $year )	
	{
		return
			$this->db
				->query("
					SELECT SUM(Nilai) as Nilai1, SUM(nilai2) as Nilai2, SUM(nilai3) as Nilai3, AkunName, Group_ID 
					FROM Penjelasan_LabaRugi_Comparative_pER_TW ({$quarterly}, {$year}) 
					WHERE LevelKe = 1 AND Nilai <> 0  GROUP BY AkunName, Group_ID
				") # AND (Nilai <> 0 AND Nilai2 <> 0 AND Nilai3 <> 0)
				->result();
	}
	
	public function get_detail_account_income_loss_quarterly( $quarterly, $year )
	{		
		$query = $this->db
				->query("
					SELECT *, LevelKe as levelke FROM Penjelasan_LabaRugi_Comparative_Per_TW ({$quarterly}, {$year}) ORDER BY Akun_NO
				");
				
		return $query->result();
	}
	
	public function get_one_account_income_loss_quarterly( $quarterly, $year, $Akun_No )
	{		
		return 
			$this->db->query("
					SELECT * FROM Penjelasan_LabaRugi_Comparative_Per_TW ({$quarterly}, {$year})
					WHERE Akun_No = '{$Akun_No}'
				")->row();
	}
}