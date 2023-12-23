<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Invoice_m extends Public_Model
{
	public $table = 'AR_trInvoice';
	public $table_detail = 'AR_trInvoiceDetail';
	public $foreign_table = 'AR_trFaktur';
	public $primary_key = 'No_Invoice';
	public $foreign_key = 'No_Bukti';
	
	public $rules;
	
	public function __construct()
	{
		$this->rules = array('insert' => array(
				'No_Invoice' => array(
						'field' => 'No_Invoice',
						'label' => lang( 'invoices:invoice_number_label' ),
						'rules' => 'required'
					),
				'Tgl_Invoice' => array(
						'field' => 'Tgl_Invoice',
						'label' => lang( 'invoices:date_label' ),
						'rules' => 'required'
					),
				'Customer_ID' => array(
						'field' => 'Customer_ID',
						'label' => lang( 'invoices:customer_label' ),
						'rules' => 'required'
					),
				'Tgl_Tempo' => array(
						'field' => 'Tgl_Tempo',
						'label' => lang( 'invoices:due_date_label' ),
						'rules' => 'required'
					),
				'Nilai' => array(
						'field' => 'Nilai',
						'label' => lang( 'invoices:value_label' ),
						'rules' => 'required'
					),
				'Sisa' => array(
						'field' => 'Sisa',
						'label' => lang( 'invoices:remain_label' ),
						'rules' => 'required'
					),
				'Keterangan' => array(
						'field' => 'Keterangan',
						'label' => lang( 'invoices:description_label' ),
						'rules' => 'required'
					),
			));
		
		parent::__construct();
	}
	
	public function get_row( $No_Invoice )
	{		
		// get result filtered
		$db_select = <<<EOSQL
			a.*,
			b.Kode_Customer, 
			b.Nama_Customer, 
			
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
			->join("mCustomer b", "a.Customer_ID = b.Customer_ID", "LEFT OUTER" )
			/*->join("mProyek c", "a.Kode_Proyek = c.Kode_Proyek_Real", "LEFT OUTER" )
			->join("Mst_Currency d", "a.Currency_ID = d.Currency_ID", "LEFT OUTER" )
			->join("mDivisi e", "a.DivisiID = e.Divisi_ID", "LEFT OUTER" )
			->join("mUser f", "a.User_ID= f.User_ID", "LEFT OUTER" )*/
			->where("a.{$this->primary_key}", $No_Invoice )
			->get()
			;
			
		return ($query->num_rows() > 0) ? $query->row() : NULL;
				
	}
	
	public function get_detail_collection( $No_Invoice )
	{		
		$db_select = <<<EOSQL
			a.*
EOSQL;

		$query = $this->db
			->select( $db_select )
			->from( "{$this->table_detail} a" )
			/*->join("mCustomer b", "a.Customer_ID = b.Customer_ID", "LEFT OUTER" )
			->join("mProyek c", "a.Kode_Proyek = c.Kode_Proyek_Real", "LEFT OUTER" )
			->join("Mst_Currency d", "a.Currency_ID = d.Currency_ID", "LEFT OUTER" )
			->join("mDivisi e", "a.DivisiID = e.Divisi_ID", "LEFT OUTER" )
			->join("mUser f", "a.User_ID= f.User_ID", "LEFT OUTER" )*/
			->where(array(
				"a.No_Invoice" => $No_Invoice,
				"a.Jtransaksi_ID" => 201,
			))
			->get()
			;
			
		return ($query->num_rows() > 0) ? $query->result() : NULL;		
	}

	public function get_detail_factur_collection( $No_Invoice )
	{		
		$db_select = <<<EOSQL
			a.*
EOSQL;

		$query = $this->db
			->select( $db_select )
			->from( "{$this->foreign_table} a" )
			/*->join("mCustomer b", "a.Customer_ID = b.Customer_ID", "LEFT OUTER" )
			->join("mProyek c", "a.Kode_Proyek = c.Kode_Proyek_Real", "LEFT OUTER" )
			->join("Mst_Currency d", "a.Currency_ID = d.Currency_ID", "LEFT OUTER" )
			->join("mDivisi e", "a.DivisiID = e.Divisi_ID", "LEFT OUTER" )
			->join("mUser f", "a.User_ID= f.User_ID", "LEFT OUTER" )*/
			->where(array(
				"a.No_Invoice" => $No_Invoice,
			))
			->get()
			;
			
		return ($query->num_rows() > 0) ? $query->result() : NULL;		
	}
	
	public function get_detail_mutation_collection( $No_Invoice )
	{		
		$db_select = <<<EOSQL
			a.*
EOSQL;

		$query = $this->db
			->select( $db_select )
			->from( "{$this->table_detail} a" )
			/*->join("mCustomer b", "a.Customer_ID = b.Customer_ID", "LEFT OUTER" )
			->join("mProyek c", "a.Kode_Proyek = c.Kode_Proyek_Real", "LEFT OUTER" )
			->join("Mst_Currency d", "a.Currency_ID = d.Currency_ID", "LEFT OUTER" )
			->join("mDivisi e", "a.DivisiID = e.Divisi_ID", "LEFT OUTER" )
			->join("mUser f", "a.User_ID= f.User_ID", "LEFT OUTER" )*/
			->where(array(
				"a.No_Invoice" => $No_Invoice,
			))
			->where_not_in("a.JTransaksi_ID", array(200, 201))
			->get();
			
		return ($query->num_rows() > 0) ? $query->result() : NULL;		
	}
	
	public function check_already_mutation( $No_Invoice )
	{
		return (boolean)
			$this->db->from( "{$this->table_detail} a" )
					->join( "{$this->table} b", "a.{$this->foreign_key} = b.{$this->primary_key}", "INNER")
					->where("b.{$this->primary_key}", $No_Invoice)
					->where("b.Cancel_Invoice", 0)
					->count_all_results();
	}
	
	public function create_data( $header, $detail )
	{
		$this->load->model("type_m");
		$this->load->helper("receivable");
		
		$user = $this->simple_login->get_user();
		$date = date("Y-m-d");
		$time = date("Y-m-d H:i:s");

		$this->db->query("exec CekHisCurrency '". $header['Tgl_Invoice'] ."' ");
		$HisCurrency_ID = receivable_helper::get_his_currency( $header['Tgl_Invoice']) ;		
		$customer = receivable_helper::get_customer( $header['Customer_ID'] );		
		$receivable_type =$this->type_m->get_row( $header['JenisPiutang_ID'] );
		
		$details = array();
		foreach($detail as $row)
		{
			$details[] = array(
				$this->primary_key  => $header[ $this->primary_key ],
				$this->foreign_key => $row[ $this->foreign_key ],
				"NilaiAsal" => $row['Debit'],
				"Debit" => $row['Debit'],
				"Kredit" => $row['Kredit'],
				"Keterangan" => $row['Keterangan'],
				"Tgl_transaksi" => $row['Tgl_transaksi'],
				"JTransaksi_ID" => 201,
				"SectionID" => config_item('SectionIDCorporate'),
			);
		}		
		
		$this->db->trans_begin();
			$this->db->insert( $this->table, $header);
			
			$activities_description = sprintf( "%s # %s # %s # %s # %s ", "INSERT INVOICE.", $header[ $this->primary_key ], $receivable_type['Nama_Type'], $header['Keterangan'], $customer->Nama_Customer );			
			$this->db->query("EXEC InsertUserActivities '{$date}','{$time}', {$user->User_ID} ,'".  $header[ $this->primary_key ] ."','{$activities_description}','{$this->table}'");				

			foreach ( $details as $row )
			{
				$this->db->insert( $this->table_detail, $row);
				$this->db->update( $this->foreign_table, array( $this->primary_key => $header[ $this->primary_key], "Cancel_Invoice" => 0), array("No_Faktur" =>  $row[ $this->foreign_key ] ) );

				$activities_description = sprintf( "%s # %s # %s # %s # %s # %s", "INSERT INVOICE DETAIL.", $header[ $this->primary_key ], $row[ $this->foreign_key ], $row['Keterangan'], $row['Debit'], $row['Debit'] );			
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
	
	public function update_data( $header, $No_Invoice )
	{
		$this->load->model("type_m");
		$user = $this->simple_login->get_user();
		$date = date("Y-m-d");
		$time = date("Y-m-d H:i:s");
		$receivable_type =$this->type_m->get_row( $header['JenisPiutang_ID'] );
						
		$this->db->trans_begin();
			$this->db->update( $this->table, $header, array( $this->primary_key => $No_Invoice));
			
			$activities_description = sprintf( "%s # %s # %s ", "UPDATE INVOICE.", $No_Invoice, $receivable_type['Nama_Type'] );			
			$this->db->query("EXEC InsertUserActivities '{$date}','{$time}', {$user->User_ID} ,'".  $No_Invoice ."','{$activities_description}','{$this->table}'");				
		
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
		$detail = $this->get_detail_collection( $item->No_Invoice );
		
		$this->load->model("type_m");
		$receivable_type =$this->type_m->get_row( $item->JenisPiutang_ID );
		
		$this->db->trans_begin();
			$this->db->update( $this->table, array("Cancel_Invoice" => 1), array( $this->primary_key => $item->No_Invoice));
			
			foreach( $detail as $row )
			{
				$this->db->update( $this->foreign_table, array("Cancel_Invoice" => 1), array( "No_Faktur" => $row->No_Bukti));
			}
			
			$activities_description = sprintf( "%s # %s # %s # %s # %s ", "CANCEL INVOICE.", $item->No_Invoice, $receivable_type['Nama_Type'], $item->Keterangan, $item->Nama_Customer );			
			$this->db->query("EXEC InsertUserActivities '{$date}','{$time}', {$user->User_ID} ,'".  $item->No_Invoice ."','{$activities_description}','{$this->table}'");				

		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			return FALSE;
		}

		$this->db->trans_commit();
		return TRUE;
		
	}
}