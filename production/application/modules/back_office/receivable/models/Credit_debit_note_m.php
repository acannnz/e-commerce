<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Credit_debit_note_m extends Public_Model
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
				'Akun_ID' => array(
						'field' => 'Akun_ID',
						'label' => lang( 'invoices:account_label' ),
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
			g.Akun_ID,
			g.Akun_No,
			g.Akun_Name
			
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
			->join("Mst_Akun g", "a.Akun_ID = g.Akun_ID", "LEFT OUTER" )
			/*->join("mProyek c", "a.Kode_Proyek = c.Kode_Proyek_Real", "LEFT OUTER" )
			->join("Mst_Currency d", "a.Currency_ID = d.Currency_ID", "LEFT OUTER" )
			->join("mDivisi e", "a.DivisiID = e.Divisi_ID", "LEFT OUTER" )
			->join("mUser f", "a.User_ID= f.User_ID", "LEFT OUTER" )*/
			->where("a.{$this->primary_key}", $No_Invoice )
			->get()
			;
			
		return ($query->num_rows() > 0) ? $query->row() : NULL;
				
	}
	
	public function get_invoice_collection( $No_Invoice )
	{		
		$db_select = <<<EOSQL
			g.*,
			a.Debit,
			a.Kredit,
			(a.NilaiAsal + a.Debit - a.Kredit) AS Saldo
EOSQL;

		$query = $this->db
			->select( $db_select )
			->from( "{$this->table_detail} a" )
			->join("{$this->table} g", "a.No_Bukti = g.No_Invoice", "LEFT OUTER" )
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

	public function get_invoice_detail( $No_Invoice )
	{		
		$db_select = <<<EOSQL
			a.*
EOSQL;

		$query = $this->db
			->select( $db_select )
			->from( "AR_trFaktur a" )
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

	public function get_invoice_factur( $No_Invoice, $No_Bukti )
	{		
		$db_select = <<<EOSQL
			a.No_Bukti AS No_Invoice,
			a.No_Faktur,
			a.Sisa,
			a.Debet AS Debit,
			a.Kredit,
			b.Keterangan
EOSQL;

		$query = $this->db
			->select( $db_select )
			->from( "AR_trInvoiceFaktur a" )
			->join("{$this->foreign_table} b", "a.No_Faktur = b.No_Faktur", "INNER" )
			->where(array(
				"a.{$this->primary_key}" => $No_Invoice,
				"a.No_Bukti" => $No_Bukti,
			))
			->get();
			
		return ($query->num_rows() > 0) ? $query->result() : NULL;		
	}		

	public function get_credit_debit_note( $where = NULL )
	{
		if ( !is_array( $where ) ) return false;
		
		$query = $this->db->where( $where )
						->get( $this->table )
						;
						
		if ($query->num_rows() > 0)
		{
			return $query->row();
		}
		
		return false;
	}

	public function create_data( $nota, $invoice, $factur )
	{
		$this->load->model("type_m");
		$this->load->helper("receivable");
		
		$user = $this->simple_login->get_user();
		$date = date("Y-m-d");
		$time = date("Y-m-d H:i:s");

		$this->db->query("exec CekHisCurrency '". $nota['Tgl_Invoice'] ."' ");
		$HisCurrency_ID = receivable_helper::get_his_currency( $nota['Tgl_Invoice']) ;		
		$customer = receivable_helper::get_customer( $nota['Customer_ID'] );		
		$receivable_type =$this->type_m->get_row( $nota['JenisPiutang_ID'] );
		
		$nota_detail = array();
		$invoice_detail = array();
		$invoice_factur = array();

		$nota_debit = $nota_kredit = 0;
		foreach($invoice as $row)
		{
			// prepare nota detail
			$nota_detail[] = array(
				$this->primary_key  => $nota[ $this->primary_key ],
				$this->foreign_key => $row[ $this->primary_key ],
				"NilaiAsal" => $row['Nilai'],
				"Debit" => $row['Debit'],
				"Kredit" => $row['Kredit'],
				"Keterangan" => $row['Keterangan'],
				"Tgl_transaksi" => $nota['Tgl_Invoice'],
				"JTransaksi_ID" => ( $row['Debit'] == 0 && $row['Kredit'] > 0 ) ? 206 : 205,
				"SectionID" => config_item('SectionIDCorporate'),
			);
			
			// prepare mutation invoice detail
			$invoice_detail[] = array(
				$this->primary_key  => $row[ $this->primary_key ],
				$this->foreign_key => $nota[ $this->primary_key ],
				"NilaiAsal" => 0,
				"Debit" => $row['Debit'],
				"Kredit" => $row['Kredit'],
				"Keterangan" => $row['Keterangan'],
				"Tgl_transaksi" => $nota['Tgl_Invoice'],
				"JTransaksi_ID" => ( $row['Debit'] == 0 && $row['Kredit'] > 0 ) ? 206 : 205,
				"SectionID" => config_item('SectionIDCorporate'),
			);
				
			// prepare invoice factur
			foreach($factur[ $row[ $this->primary_key ] ] as $val)
			{
				$invoice_factur[] = array(
					$this->primary_key  => $nota[ $this->primary_key ],
					$this->foreign_key => $row[ $this->primary_key ],
					"No_Faktur" => $val['No_Faktur'],
					"Sisa" => $val['Sisa'],
					"Debet" => $val['Debit'],
					"Kredit" => $val['Kredit'],
				);
			}
			
			$nota_debit = $row['Debit'];
			$nota_kredit = $row['Kredit'];
		}	
		
		$nota['Sisa'] = $nota['Nilai'] = ( $invoice[0]['Debit'] == 0 && $invoice[0]['Kredit'] > 0 ) ? $nota_kredit : $nota_debit;
		$nota['JTransaksi_ID'] = ( $invoice[0]['Debit'] == 0 && $invoice[0]['Kredit'] > 0 ) ? 206 : 205;
		$nota['JenisPiutang_ID'] = $invoice[0]['JenisPiutang_ID'];
		
		$this->db->trans_begin();
			$this->db->insert( $this->table, $nota);
			
			$activities_description = sprintf( "%s # %s # %s # %s # %s ", "INSERT NOTA AR.", $nota[ $this->primary_key ], $receivable_type['Nama_Type'], $nota['Keterangan'], $customer->Nama_Customer );			
			$this->db->query("EXEC InsertUserActivities '{$date}','{$time}', {$user->User_ID} ,'".  $nota[ $this->primary_key ] ."','{$activities_description}','{$this->table}'");				
			
			foreach ( $nota_detail as $row )
			{
				$this->db->insert( $this->table_detail, $row);

				$activities_description = sprintf( "%s # %s # %s # %s # %s # %s", "INSERT NOTA(AR) DETAIL.", $nota[ $this->primary_key ], $row[ $this->foreign_key ], $row['Keterangan'], $row['NilaiAsal'], $row['Debit'] );			
				$this->db->query("EXEC InsertUserActivities '{$date}','{$time}', {$user->User_ID} ,'".  $row[ $this->foreign_key ] ."','{$activities_description}','{$this->table_detail}'");				
			}
			
			foreach ( $invoice_detail as $row )
			{
				$this->db->insert( $this->table_detail, $row);

				$this->db->set( "Sisa", "Sisa + ". $row['Debit'] ." - ". $row['Kredit'], FALSE)
						->where( $this->primary_key,  $row[ $this->primary_key ] )
						->update( $this->table );
				
				$activities_description = sprintf( "%s # %s # %s # %s # %s # %s", "INSERT MUTASI INVOICE DETAIL.", $nota[ $this->primary_key ], $row[ $this->foreign_key ], $row['Keterangan'], $row['NilaiAsal'], $row['Debit'] );			
				$this->db->query("EXEC InsertUserActivities '{$date}','{$time}', {$user->User_ID} ,'".  $row[ $this->foreign_key ] ."','{$activities_description}','{$this->table_detail}'");				
			}
			
			foreach ( $invoice_factur as $row )
			{
				$this->db->insert( "AR_trInvoiceFaktur", $row);

				$this->db->set( "Sisa", "Sisa + ". $row['Debet'] ." - ". $row['Kredit'], FALSE)
						->where( "No_Faktur",  $row['No_Faktur'] )
						->update( $this->foreign_table );
				
				$activities_description = sprintf( "%s # %s # %s # %s # %s # %s # %s", "INSERT INVOICE FAKTUR.", $nota[ $this->primary_key ], $row[ $this->foreign_key ], $row['No_Faktur'], $row['Sisa'], $row['Debet'], $row['Kredit'] );			
				$this->db->query("EXEC InsertUserActivities '{$date}','{$time}', {$user->User_ID} ,'".  $row['No_Faktur'] ."','{$activities_description}','AR_trInvoiceFaktur'");				
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
	
	public function delete_data( $nota, $invoice, $factur )
	{
		$this->load->model("type_m");
		$user = $this->simple_login->get_user();
		$date = date("Y-m-d");
		$time = date("Y-m-d H:i:s");	
		$receivable_type =$this->type_m->get_row( $nota['JenisPiutang_ID'] );
		
		
		$this->db->trans_begin();
			
			foreach($invoice as $row)
			{				
				// Update & Delete Nota Invoice
				$this->db->set( "Sisa", "Sisa - ". $row['Debit'] ." + ". $row['Kredit'], FALSE)
						->where( $this->primary_key,  $row[ $this->primary_key ] )
						->update( $this->table );
				
				$this->db->delete( $this->table_detail, array( $this->primary_key => $nota[ $this->primary_key ] ));
							
				$activities_description = sprintf( "%s # %s # %s # %s", "DELETE PNT (INVOICE) DETAIL.", $nota[ $this->primary_key ], $nota['Keterangan'], $row[ $this->primary_key ]);			
				$this->db->query("EXEC InsertUserActivities '{$date}','{$time}', {$user->User_ID} ,'".  $nota[ $this->primary_key ] ."','{$activities_description}','{$this->table_detail}'");				
											
				// Delete Invoice Faktur
				foreach($factur[ $row[ $this->primary_key ] ] as $val)
				{					
					$this->db->set( "Sisa", "Sisa - ". $val['Debit'] ." + ". $val['Kredit'], FALSE)
							->where( "No_Faktur", $val['No_Faktur'] )
							->update( $this->foreign_table );
	
					$activities_description = sprintf( "%s # %s # %s # %s # %s", "DELETE PNT DETAIL FAKTUR.", $nota[ $this->primary_key ], $nota['Keterangan'], $row[ $this->primary_key ], $val['No_Faktur']);			
					$this->db->query("EXEC InsertUserActivities '{$date}','{$time}', {$user->User_ID} ,'".  $nota[ $this->primary_key ] ."','{$activities_description}','{$this->foreign_table}'");				
				}
			}	
						
			$this->db->delete( $this->table_detail, array( $this->foreign_key => $nota[ $this->primary_key ]) );
			$this->db->delete( "AR_trKartuPiutang", array( $this->foreign_key => $nota[ $this->primary_key ]) );
			$this->db->delete( "TBJ_Transaksi_Detail", array( $this->foreign_key => $nota[ $this->primary_key ]) );
			$this->db->delete( "TBJ_Transaksi", array( $this->foreign_key => $nota[ $this->primary_key ]) );
			
			$this->db->update( $this->table, array("Cancel_Invoice" => 1), array( $this->primary_key => $nota[ $this->primary_key ]) );
			
			$activities_description = sprintf( "%s # %s # %s # Alasan : %s # %s ", "DELETE PNT.", $nota[ $this->primary_key ], $receivable_type['Nama_Type'], $nota['Keterangan'], $nota['Nama_Customer'] );			
			$this->db->query("EXEC InsertUserActivities '{$date}','{$time}', {$user->User_ID} ,'".  $nota[ $this->primary_key ]."','{$activities_description}','{$this->table}'");				

		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			return FALSE;
		}

		$this->db->trans_commit();
		return TRUE;
		
	}
}