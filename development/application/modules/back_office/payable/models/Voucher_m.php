<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Voucher_m extends Public_Model
{
	public $table = 'AP_trVoucher';
	public $table_detail = 'AP_trVoucherDetail';
	public $foreign_table = 'AP_trFaktur';
	public $primary_key = 'No_Voucher';
	public $foreign_key = 'No_Bukti';
	
	public $rules;
	
	public function __construct()
	{
		$this->rules = array('insert' => array(
				'No_Voucher' => array(
						'field' => 'No_Voucher',
						'label' => lang( 'vouchers:voucher_number_label' ),
						'rules' => 'required'
					),
				'Tgl_Voucher' => array(
						'field' => 'Tgl_Voucher',
						'label' => lang( 'vouchers:date_label' ),
						'rules' => 'required'
					),
				'Supplier_ID' => array(
						'field' => 'Supplier_ID',
						'label' => lang( 'vouchers:supplier_label' ),
						'rules' => 'required'
					),
				'Tgl_Tempo' => array(
						'field' => 'Tgl_Tempo',
						'label' => lang( 'vouchers:due_date_label' ),
						'rules' => 'required'
					),
				'Nilai' => array(
						'field' => 'Nilai',
						'label' => lang( 'vouchers:value_label' ),
						'rules' => 'required'
					),
				'Sisa' => array(
						'field' => 'Sisa',
						'label' => lang( 'vouchers:remain_label' ),
						'rules' => 'required'
					),
				'Keterangan' => array(
						'field' => 'Keterangan',
						'label' => lang( 'vouchers:description_label' ),
						'rules' => 'required'
					),
			),
			'update' => array(
				'Tgl_Voucher' => array(
						'field' => 'Tgl_Voucher',
						'label' => lang( 'vouchers:date_label' ),
						'rules' => 'required'
					),
				'Tgl_Tempo' => array(
						'field' => 'Tgl_Tempo',
						'label' => lang( 'vouchers:due_date_label' ),
						'rules' => 'required'
					),
				'Keterangan' => array(
						'field' => 'Keterangan',
						'label' => lang( 'vouchers:description_label' ),
						'rules' => 'required'
					),
			));
		
		parent::__construct();
	}
	
	public function get_row( $No_Voucher )
	{		
		// get result filtered
		$db_select = <<<EOSQL
			a.*,
			b.Kode_Supplier, 
			b.Nama_Supplier, 
			
EOSQL;
		/*
			c.Nama_Proyek, 
			d.Currency_Code, 
			e.Nama_Divisi, 
			f.Nama_Singkat
		*/

		$query = $this->db
			->select( $db_select )
			->from( "{$this->table} a" )
			->join("mSupplier b", "a.Supplier_ID = b.Supplier_ID", "LEFT OUTER" )
			/*->join("mProyek c", "a.Kode_Proyek = c.Kode_Proyek_Real", "LEFT OUTER" )
			->join("Mst_Currency d", "a.Currency_ID = d.Currency_ID", "LEFT OUTER" )
			->join("mDivisi e", "a.DivisiID = e.Divisi_ID", "LEFT OUTER" )
			->join("mUser f", "a.User_ID= f.User_ID", "LEFT OUTER" )*/
			->where("a.{$this->primary_key}", $No_Voucher )
			->get()
			;
			
		return ($query->num_rows() > 0) ? $query->row() : NULL;
				
	}
	
	public function get_detail_collection( $No_Voucher )
	{		
		$db_select = <<<EOSQL
			a.*
EOSQL;

		$query = $this->db
			->select( $db_select )
			->from( "{$this->table_detail} a" )
			/*->join("mSupplier b", "a.Supplier_ID = b.Supplier_ID", "LEFT OUTER" )
			->join("mProyek c", "a.Kode_Proyek = c.Kode_Proyek_Real", "LEFT OUTER" )
			->join("Mst_Currency d", "a.Currency_ID = d.Currency_ID", "LEFT OUTER" )
			->join("mDivisi e", "a.DivisiID = e.Divisi_ID", "LEFT OUTER" )
			->join("mUser f", "a.User_ID= f.User_ID", "LEFT OUTER" )*/
			->where(array(
				"a.No_Voucher" => $No_Voucher,
				"a.Jtransaksi_ID" => 401,
			))
			->get()
			;
			
		return ($query->num_rows() > 0) ? $query->result() : NULL;		
	}

	public function get_detail_factur_collection( $No_Voucher )
	{		
		$db_select = <<<EOSQL
			a.*
EOSQL;

		$query = $this->db
			->select( $db_select )
			->from( "{$this->foreign_table} a" )
			/*->join("mSupplier b", "a.Supplier_ID = b.Supplier_ID", "LEFT OUTER" )
			->join("mProyek c", "a.Kode_Proyek = c.Kode_Proyek_Real", "LEFT OUTER" )
			->join("Mst_Currency d", "a.Currency_ID = d.Currency_ID", "LEFT OUTER" )
			->join("mDivisi e", "a.DivisiID = e.Divisi_ID", "LEFT OUTER" )
			->join("mUser f", "a.User_ID= f.User_ID", "LEFT OUTER" )*/
			->where(array(
				"a.No_Voucher" => $No_Voucher,
			))
			->get()
			;
			
		return ($query->num_rows() > 0) ? $query->result() : NULL;		
	}
	
	public function get_detail_mutation_collection( $No_Voucher )
	{		
		$db_select = <<<EOSQL
			a.*
EOSQL;

		$query = $this->db
			->select( $db_select )
			->from( "{$this->table_detail} a" )
			/*->join("mSupplier b", "a.Supplier_ID = b.Supplier_ID", "LEFT OUTER" )
			->join("mProyek c", "a.Kode_Proyek = c.Kode_Proyek_Real", "LEFT OUTER" )
			->join("Mst_Currency d", "a.Currency_ID = d.Currency_ID", "LEFT OUTER" )
			->join("mDivisi e", "a.DivisiID = e.Divisi_ID", "LEFT OUTER" )
			->join("mUser f", "a.User_ID= f.User_ID", "LEFT OUTER" )*/
			->where(array(
				"a.No_Voucher" => $No_Voucher,
			))
			->where_not_in("a.JTransaksi_ID", array(400, 401))
			->get();
			
		return ($query->num_rows() > 0) ? $query->result() : NULL;		
	}
	
	public function check_already_mutation( $No_Voucher )
	{
		return (boolean)
			$this->db->from( "{$this->table_detail} a" )
					->join( "{$this->table} b", "a.{$this->foreign_key} = b.{$this->primary_key}", "INNER")
					->where("b.{$this->primary_key}", $No_Voucher)
					->where("b.Cancel_Voucher", 0)
					->count_all_results();
	}
	
	public function create_data( $header, $detail )
	{
		$this->load->model("type_m");
		$this->load->helper("payable");
		
		$user = $this->simple_login->get_user();
		$date = date("Y-m-d");
		$time = date("Y-m-d H:i:s");

		$this->db->query("exec CekHisCurrency '". $header['Tgl_Voucher'] ."' ");
		$HisCurrency_ID = payable_helper::get_his_currency( $header['Tgl_Voucher']) ;		
		$supplier = payable_helper::get_supplier( $header['Supplier_ID'] );		
		$payable_type =$this->type_m->get_row( $header['JenisHutang_ID'] );
		
		$details = array();
		foreach($detail as $row)
		{
			$details[] = array(
				$this->primary_key  => $header[ $this->primary_key ],
				$this->foreign_key => $row[ $this->foreign_key ],
				"NilaiAsal" => $row['Kredit'],
				"Debit" => $row['Debit'],
				"Kredit" => $row['Kredit'],
				"Keterangan" => $row['Keterangan'],
				"Tgl_transaksi" => $row['Tgl_transaksi'],
				"JTransaksi_ID" => 401,
				"SectionID" => config_item('SectionIDCorporate'),
			);
		}		
		
		$this->db->trans_begin();
			$this->db->insert( $this->table, $header);
			
			$activities_description = sprintf( "%s # %s # %s # %s # %s ", "INSERT VOUCHER.", $header[ $this->primary_key ], $payable_type['Nama_Type'], $header['Keterangan'], $supplier->Nama_Supplier );			
			$this->db->query("EXEC InsertUserActivities '{$date}','{$time}', {$user->User_ID} ,'".  $header[ $this->primary_key ] ."','{$activities_description}','{$this->table}'");				

			foreach ( $details as $row )
			{
				$this->db->insert( $this->table_detail, $row);
				$this->db->update( $this->foreign_table, array( $this->primary_key => $header[ $this->primary_key], "Cancel_Voucher" => 0), array("No_Faktur" =>  $row[ $this->foreign_key ] ) );

				$activities_description = sprintf( "%s # %s # %s # %s # %s # %s", "INSERT VOUCHER DETAIL.", $header[ $this->primary_key ], $row[ $this->foreign_key ], $row['Keterangan'], $row['Debit'], $row['Debit'] );			
				$this->db->query("EXEC InsertUserActivities '{$date}','{$time}', {$user->User_ID} ,'".  $row[ $this->foreign_key ] ."','{$activities_description}','{$this->table_detail}'");				
			}
		
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			return FALSE;
		}

		$this->db->trans_commit();
		return TRUE;
	}
	
	public function update_data( $header, $No_Voucher )
	{
		$this->load->model("type_m");
		$user = $this->simple_login->get_user();
		$date = date("Y-m-d");
		$time = date("Y-m-d H:i:s");
						
		$this->db->trans_begin();
			$this->db->update( $this->table, $header, array( $this->primary_key => $No_Voucher));
			
			$activities_description = sprintf( "%s # %s", "UPDATE VOUCHER.", $No_Voucher);			
			$this->db->query("EXEC InsertUserActivities '{$date}','{$time}', {$user->User_ID} ,'".  $No_Voucher ."','{$activities_description}','{$this->table}'");				
		
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			return FALSE;
		}

		$this->db->trans_commit();
		return TRUE;
	}
	
	public function cancel_data( $item )
	{
		$user = $this->simple_login->get_user();
		$date = date("Y-m-d");
		$time = date("Y-m-d H:i:s");	
		$detail = $this->get_detail_collection( $item->No_Voucher );
		
		$this->load->model("type_m");
		$payable_type =$this->type_m->get_row( $item->JenisHutang_ID );
		
		$this->db->trans_begin();
			$this->db->update( $this->table, array("Cancel_Voucher" => 1), array( $this->primary_key => $item->No_Voucher));
			
			foreach( $detail as $row )
			{
				$this->db->update( $this->foreign_table, array("Cancel_Voucher" => 1), array( "No_Faktur" => $row->No_Bukti));
			}
			
			$activities_description = sprintf( "%s # %s # %s # %s # %s ", "CANCEL VOUCHER.", $item->No_Voucher, $payable_type['Nama_Type'], $item->Keterangan, $item->Nama_Supplier );			
			$this->db->query("EXEC InsertUserActivities '{$date}','{$time}', {$user->User_ID} ,'".  $item->No_Voucher ."','{$activities_description}','{$this->table}'");				

		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			return FALSE;
		}

		$this->db->trans_commit();
		return TRUE;
		
	}
}