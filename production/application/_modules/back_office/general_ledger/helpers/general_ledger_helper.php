<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

final class general_ledger_helper
{
	private static $_tbl = "gl_journals";
	private static $data =  array();
	
	public static function get_all_services()
	{
		return (array) self::ci()->db
			->select( "*" )
			->from( self::$_tbl )
			->where(array(
					"deleted_at" => NULL,
				))
			->get()
			->result()
			;
	}
	
	public static function find_all_services()
	{
		return (int) self::ci()->db
			->where(array(
					"deleted_at" => NULL,
				))
			->count_all_results( self::$_tbl )
			;
	}

	public static function get_option_currency()
	{
		self::ci()->db->order_by( 'Currency_default', 'DESC' );
		
		$query = self::ci()->db->get( "Mst_Currency" );
		
		$data  = array();

		if( $query->num_rows() > 0 )
		{
			foreach( $query->result() as $row )
			{
				$data[ $row->Currency_ID ] = $row->Currency_Name;
			} 
		} 
		
		return $data;
	}

	public static function get_option_division()
	{
		self::ci()->db->order_by( 'Nama_Divisi' );
		
		$query = self::ci()->db->get( "mDivisi" );
		
		$data  = array();

		if( $query->num_rows() > 0 )
		{
			foreach( $query->result() as $row )
			{
				$data[ $row->Divisi_ID ] = $row->Nama_Divisi;
			} 
		} 
		
		return $data;
	}
	
	public static function get_option_project()
	{
		self::ci()->db->order_by( 'Nama_Proyek' );
		
		$query = self::ci()->db->get( "mProyek" );
		
		$data  = array();

		if( $query->num_rows() > 0 )
		{
			foreach( $query->result() as $row )
			{
				$data[ $row->Kode_Proyek ] = $row->Nama_Proyek;
			} 
		} 
		
		return $data;
	}

	public static function get_option_section()
	{
		self::ci()->db->where("StatusAktif", 1)
					->order_by('SectionName');
		
		$query = self::ci()->db->get( "SIMmSection" );
		
		$data  = array();

		if( $query->num_rows() > 0 )
		{
			foreach( $query->result() as $row )
			{
				$data[ $row->SectionID ] = $row->SectionName;
			} 
		} 
		
		return $data;
	}
		
	public static function get_his_currency( $date = NULL )
	{
		$date = empty($date) ? date('Y-m-d') : $date;
		
		self::ci()->db->query("exec CekHisCurrency '". $date ."' ");		
		$query = self::ci()->db->where("Tanggal", $date)
							->get("TBJ_HisCurrency");
							
		return ($query->num_rows() > 0 ) ? @$query->row()->HisCurrency_ID : 1;	
	}

	// Generate HisCurrency or if already exist return HisCurrency_ID
	public static function _gen_his_currency( $date )
	{
				
		if ( !$check = self::check_rate_currency($date) )
		{
			if( self::ci()->db->where("Tanggal", $date)->count_all_results("TBJ_HisCurrency") == 0 )
			{
				self::ci()->db->insert("TBJ_HisCurrency", array("Tanggal" => $date));
				$HisCurrency_ID = self::ci()->db->insert_id();
			} else {
				$HisCurrency_ID = self::ci()->db->where("Tanggal", $date)->get("TBJ_HisCurrency")->row()->HisCurrency_ID;
			}
			
			$detail = self::ci()->db->where("HisCurrency_ID IN ( SELECT MAX(HisCurrency_ID) FROM TBJ_HisCurrencyDetail )")
							->get("TBJ_HisCurrencyDetail")
							->result();
		} else {
			
			
			$HisCurrency_ID = self::ci()->db->where("Tanggal", $date)->get("TBJ_HisCurrency")->row()->HisCurrency_ID;
			
			$detail = self::ci()->db->where("HisCurrency_ID", $HisCurrency_ID)
							->get("TBJ_HisCurrencyDetail")
							->result();		
		}
		
		$date = DateTime::createFromFormat("Y-m-d", $date );
		$date->add(new DateInterval('P1D'));
		$currency_day = $date->format('d');
							
		foreach( $detail as $row )
		{
			$data = array(
					'Currency_ID' => $row->Currency_ID,
					'HisCurrency_ID' => $HisCurrency_ID,
					'Rate' => $row->Rate,
				);
				
			( !$check ) ? self::ci()->db->insert('TBJ_HisCurrencyDetail', $data ) : FALSE;
			
			( $currency_day == '01' && $check == 0 ) ? self::ci()->db->insert('TBJ_HisCurrencyPosted', $data ) : FALSE;
		}
		
							
		return (int) @$HisCurrency_ID;
		
	}

	public static function check_rate_currency( $date )
	{
		if ( !$check = $HisCurrency_ID = self::ci()->db->where( "Tanggal", $date )->count_all_results("TBJ_HisCurrency"))
		{
			return FALSE;
		}

		$HisCurrency_ID = self::ci()->db->select("MAX(HisCurrency_ID) AS HisCurrency_ID")->where( "Tanggal", $date )->get("TBJ_HisCurrency")->row()->HisCurrency_ID;
		
		$currencies = self::ci()->db->get("Mst_Currency")->result();	
		foreach ( $currencies as $row ) 
		{
			// check rate currency
			$check = self::ci()->db->where(array("HisCurrency_ID" => $HisCurrency_ID, "Currency_ID" => $row->Currency_ID))
							->count_all_results("TBJ_HisCurrencyDetail");
			if( $check == 0 )
			{
				return FALSE;
			}
		}
		return TRUE;

	}

	public static function get_rate_currency( $date = NULL )
	{
		$date = empty($date) ? date("Y-m-d") : $date;
		
		$HisCurrency_ID = self::get_his_currency( $date );
		
		$query = self::ci()->db
				->from("Mst_Currency a")
				->join("TBJ_HisCurrencyDetail b", "a.Currency_ID = b.Currency_ID", "LEFT OUTER")
				->join("TBJ_HisCurrency c", "b.HisCurrency_ID = c.HisCurrency_ID", "LEFT OUTER")
				->where(array(
						"c.HisCurrency_ID" => $HisCurrency_ID,
					))
				->get();

		return $query->num_rows() > 0 ? $query->result() : self::_gen_his_currency($date);						
	}

	//exchange rate
	public static function get_currency_exchange_rate( $id, $date = NULL )
	{
		$date = empty($date) ? date("Y-m-d") : $date;
		$row = (float) @self::ci()->db
				->select("b.Rate")
				->from("Mst_Currency a")
				->join("TBJ_HisCurrencyDetail b", "a.Currency_ID = b.Currency_ID", "LEFT OUTER")
				->join("TBJ_HisCurrency c", "b.HisCurrency_ID = c.HisCurrency_ID", "LEFT OUTER")
				->where(array(
						"a.Currency_ID" => $id,
						"c.Tanggal" => $date,
					))
				->get()
				->row()
				->Rate;
				
		return $row > 0 ? $row : 1;						
	}	

	public static function get_default_currency_id()
	{
		$query = self::ci()->db
				->where(array(
						"Currency_default" => 1,
					))
				->get("mCurrency")
				->row();
		
		return (int) @$query->Currency_ID;
	}	
			
	public static function get_journal_by_id( $from, $till, $account_id )
	{
		
		$query = self::ci()->db
			->select( "a.journal_date, b.*" )
			->from( self::$_tbl." a" )
			->join( "gl_journal_details b", "a.No_Bukti = b.No_Bukti", "LEFT OUTER")
			->where(array(
					"b.account_id" => $account_id,
					"a.journal_date >=" => $from,
					"a.journal_date <=" => $till,
				))
			->get()
			;
		
		if ($query->num_rows() > 0) 
		{
			return $query->result();
		}
		
		return false;
		
	}
	
	public static function find_journal( $No_Bukti )
	{
		return (int) self::ci()->db
			->where(array(
					"deleted_at" => NULL, 
					"No_Bukti" => $No_Bukti,
				))
			->count_all_results( "gl_journals" )
			;
	}
	
	public static function find_child( $parent_id = 0 )
	{
		return (int) self::ci()->db
			->where(array(
					"deleted_at" => NULL, 
					"parent_id" => $parent_id,
				))
			->count_all_results( self::$_tbl )
			;
	}
	

	public static function gen_journal_number( $date = NULL )
	{
		$CI = self::ci();
		
		$NOW = $date ? DateTime::createFromFormat('Y-m-d', $date) : new DateTime();
		$date_start = $NOW->format( "Y-m-01" );
		$date_end = $NOW->format( "Y-m-t" );
		$date_y = $NOW->format( "Y" );
		$date_m = $NOW->format( "m" );
		$date_d = $NOW->format( "d" );
		
		$query =  $CI->db->select("MAX(No_Bukti) AS MAX")
							->where(array("Transaksi_Date >=" => $date_start, "Transaksi_Date <=" => $date_end))
							->like("No_Bukti", "JUM")
							->get( "TBJ_Transaksi" )
							->row()
							;
		if (!empty($query->MAX))
		{
			$number = $query->MAX;
			$number++;
		} else {
			$number = (string) (sprintf(self::_gen_format_journal_number(), $date_y, $date_m, 'JUM', 1));		
		}
		
		return $number;
	}
	
	private static function _gen_format_journal_number()
	{
		$format = "%02d/%02d/%s/%04d";
		return $format;
	}
	
	public static function prepare_journal_account( $No_Bukti )
	{	
		$account = array(array(
				'No_Bukti' => $No_Bukti,
				'account_id' => 0,
				'Debit' => 0,
				'Kredit' => 0,
				'Keterangan' => null,
				'state' => 1,
			));
			
		self::ci()->load->model( "general_ledger/journal_detail_m" );
		if( ! self::ci()->journal_detail_m->insert( $account ) )
		{
			return FALSE;
		}
		
		return TRUE;
	}
	
	/*SELECT Tanggal from TBJ_PostedBulanan 
	inner join TBJ_HisCurrency  on tbj_postedbulanan.HisCurrency_ID=tbj_hiscurrency.HisCurrency_ID  
	where datepart(month,tbj_hiscurrency.Tanggal)=1 and datepart(year,tbj_hiscurrency.Tanggal)=2017*/
	
	public static function check_beginning_balance( $date = NULL  )
	{
		$date = empty($date) ? date("Y-m-d") : $date;
		$date = DateTime::createFromFormat("Y-m-d", $date );
		
		$query = self::ci()->db
				->select("Tanggal")
				->from("TBJ_PostedBulanan a")
				->join("TBJ_HisCurrency b", "a.HisCurrency_ID=b.HisCurrency_ID", "INNER")
				->where(array(
						"datepart(month, b.Tanggal) =" => $date->format('n'),
						"datepart(year, b.Tanggal) =" => $date->format('Y'),
					))
				->count_all_results();
		
		return (int) @$query;
	}	
		
	/*
		@FromDate, 
		@ToDate,
		@intIDCurrency,
		@intIDCurrencyConvert,
		@curRateSekarang ,
		@AkunID,
		@NormalPos ,
		@RateTransaksi,
		@Convert
	*/
	
	# SELECT sum(debit-kredit) as saldoD, sum(kredit-Debit) as SaldoK 
	# from dbo.BukuBesar('01/Jan/2017','31/Jan/2017',0,1,1,2445,'D',0,1) Alias  
	# WHERE Alias.Keterangan='Saldo Awal'

	public static function get_beginning_balance_akun( $data )
	{
		self::ci()->load->model("account_m");
		$account = self::ci()->account_m->get_account( $data->Akun_ID );
		
		$query = self::ci()->db
				->select("SUM(Debit - Kredit) as D, SUM(Kredit - Debit) as K")
				->from("dbo.BukuBesar('$data->date_start', '$data->date_till', $data->Currency_ID, $data->convertCurrency_ID, $data->currencyRate, $account->Akun_ID,'$account->Normal_Pos', 0, $data->convert) ")
				->where(array(
						"Keterangan" => "Saldo Awal",
					))
				->get();
		
		return $query->num_rows() > 0 ? $query->row() : FALSE;
	}	

	# SELECT sum(debit) as Debit, sum(kredit) as Kredit 
	# from dbo.BukuBesar('01/Jan/2017','31/Jan/2017',0,1,1,2445,'D',0,1) Alias  
	# WHERE Alias.Keterangan<>'Saldo Awal'

	public static function get_credit_debit_summary( $data )
	{
		self::ci()->load->model("account_m");
		$account = self::ci()->account_m->get_account( $data->Akun_ID );
		
		$query = self::ci()->db
				->select("sum(debit) as Debit, sum(kredit) as Kredit ")
				->from("dbo.BukuBesar('$data->date_start', '$data->date_till', $data->Currency_ID, $data->convertCurrency_ID, $data->currencyRate, $account->Akun_ID,'$account->Normal_Pos', 0, $data->convert) ")
				->where(array(
						"Keterangan <>" => "Saldo Awal",
					))
				->get();
		
		return $query->num_rows() > 0 ? $query->row() : FALSE;
	}	
	
	# SELECT * from dbo.BukuBesar('01/Jan/2017','31/Jan/2017',0,1,1,2445,'D',0,1) 
	# ORDER BY TANGGAL,KREDIT,Nomor,NoBukti
	
	public static function get_general_ledger_details( $data  )
	{
		self::ci()->load->model("account_m");
		$account = self::ci()->account_m->get_account( $data->Akun_ID );
		
		$query = self::ci()->db
				->from("dbo.BukuBesar('$data->date_start', '$data->date_till', $data->Currency_ID, $data->convertCurrency_ID, $data->currencyRate, $account->Akun_ID,'$account->Normal_Pos', 0, $data->convert) ")
				//->order_by("Nomor")
				->get();
		
		return $query->num_rows() > 0 ? $query->result() : FALSE;
	}	
	
	public static function get_beginning_balance_date()
	{
		$date = DateTime::createFromFormat("Y-m-d", config_item('Tanggal Mulai System') );
		$date->sub(new DateInterval('P1D'));
		return $date->format('Y-m-d');
	}

	# Get Jurnal Umum
	public static function get_general_details( $data  )
	{
		switch ( $data->journal_type ) :
			case "Jurnal Umum" :
				$db_where = "NoBukti Like '%JUM%'";
				//dStrSeleksiReport = "JUM"
				break;
			case "Jurnal Hutang" :
				$db_where = " Kode_Transfer='AP'";
				//dStrSeleksiReport = "AP"
				break;
			case "Jurnal Piutang" :
				$db_where = " Kode_Transfer='AR'";
				//dStrSeleksiReport = "AR"
				break;
			case "Jurnal General Cashier" :
				$db_where = " Kode_Transfer='GC'";
				//dStrSeleksiReport = "GC"
				break;
			case "Jurnal Gudang" :
				$db_where = " Kode_Transfer='GUDANG'";
				//dStrSeleksiReport = "GUDANG"
				break;
			case "Semua Jurnal" :
				$db_where = NULL;
				//dStrSeleksiReport = ""
				break;
			default:
				$db_where = NULL;
				//dStrSeleksiReport = ""			
		endswitch;
		
		!empty($db_where) ? self::ci()->db->where( $db_where ) : NULL;
		$output['collection'] = self::ci()->db
				->from("dbo.JurnalUmum('$data->date_start', '$data->date_till', $data->Currency_ID, 0, 1, 0, 0) ")
				->order_by('NoBukti,Tanggal')
				->get()
				->result();
				
		!empty($db_where) ? self::ci()->db->where( $db_where ) : NULL;
		$output['summary'] = self::ci()->db->select("COALESCE( SUM( Debit ), 0) AS Debit, COALESCE( SUM( kredit ), 0) AS Kredit ")
				->from("dbo.JurnalUmum('$data->date_start', '$data->date_till', $data->Currency_ID, 0, 1, 0, 0) ")
				->get()
				->row();
				
		return $output;
	}	
	
	public static function export_general_ledger($params)
	{
		$_ci = self::ci();
		
		$cash_flow = self::get_general_ledger_by_account( $params );	
				
		$date_start = DateTime::createFromFormat("Y-m-d", $params->date_start );
		$date_till = DateTime::createFromFormat("Y-m-d", $params->date_till );
		$get_general_ledger = self::get_general_ledger_by_account($params);
		$file_name = sprintf('%s Periode %s s/d %s ', "Laporan Buku Besar {$params->Akun_No}-{$params->Akun_Name}", $date_start->format('d F Y'), $date_till->format('d F Y'));
		
		$helper = new Sample();
		if ($helper->isCli()) {
			$helper->log('403. Forbidden Access!' . PHP_EOL);
			return false;
		}
		
		// Create new Spreadsheet object
		$spreadsheet = new Spreadsheet();
		
		// Set document properties
		$spreadsheet->getProperties()->setCreator( config_item("company_name") )
				->setLastModifiedBy(config_item("company_name"))
				->setTitle( "Laporan Buku Besar {$params->Akun_No}-{$params->Akun_Name}" )
				->setSubject( "Laporan Buku Besar {$params->Akun_No}-{$params->Akun_Name}" )
				->setDescription( $file_name )
				->setKeywords( $file_name)
				;	

		$_sheet = $spreadsheet->setActiveSheetIndex( 0 );
		$spreadsheet->getActiveSheet()->setTitle("Buku Besar");
			
		// Default Style			
		//$spreadsheet->getDefaultStyle()->applyFromArray( self::_get_style( 'default' ) );
		
		$_sheet->mergeCells("A1:G1");
		$_sheet->setCellValue('A1', $file_name );
		$_sheet->getStyle("A1")->applyFromArray( self::_get_style( 'header' ) );
		$_sheet->getStyle("A1")->getAlignment()->setWrapText(true);
		$_sheet->getRowDimension('1')->setRowHeight(30);
		
		
		$tb_row = 3;
		$_sheet->setCellValue("B{$tb_row}", "Saldo Awal"); 
		$_sheet->getStyle("B{$tb_row}")->applyFromArray( self::_get_style( 'sub_header' ) );
		$_sheet->setCellValue("C{$tb_row}", $get_general_ledger->beginning_value); 
		$_sheet->getStyle("C{$tb_row}")->applyFromArray( self::_get_style( 'text_right' ) );
		$_sheet->getStyle("C{$tb_row}")->applyFromArray( self::_get_style( 'currency' ) );
		$tb_row++;
		$_sheet->setCellValue("B{$tb_row}", "Saldo Akhir"); 
		$_sheet->getStyle("B{$tb_row}")->applyFromArray( self::_get_style( 'sub_header' ) );
		$_sheet->setCellValue("C{$tb_row}", $get_general_ledger->ending_value); 
		$_sheet->getStyle("C{$tb_row}")->applyFromArray( self::_get_style( 'text_right' ) );
		$_sheet->getStyle("C{$tb_row}")->applyFromArray( self::_get_style( 'currency' ) );
		$tb_row++;
		$_sheet->setCellValue("B{$tb_row}", "Debit"); 
		$_sheet->getStyle("B{$tb_row}")->applyFromArray( self::_get_style( 'sub_header' ) );
		$_sheet->setCellValue("C{$tb_row}", $get_general_ledger->debit_summary); 
		$_sheet->getStyle("C{$tb_row}")->applyFromArray( self::_get_style( 'text_right' ) );
		$_sheet->getStyle("C{$tb_row}")->applyFromArray( self::_get_style( 'currency' ) );
		$tb_row++;
		$_sheet->setCellValue("B{$tb_row}", "Kredit"); 
		$_sheet->getStyle("B{$tb_row}")->applyFromArray( self::_get_style( 'sub_header' ) );
		$_sheet->setCellValue("C{$tb_row}", $get_general_ledger->credit_summary); 
		$_sheet->getStyle("C{$tb_row}")->applyFromArray( self::_get_style( 'text_right' ) );
		$_sheet->getStyle("C{$tb_row}")->applyFromArray( self::_get_style( 'currency' ) );
		$tb_row++;
		
		$_sheet->setCellValue("A{$tb_row}", "No"); 
		$_sheet->getStyle("A{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) );
		$_sheet->setCellValue("B{$tb_row}", "Tanggal"); 
		$_sheet->getStyle("B{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) );
		$_sheet->setCellValue("C{$tb_row}", 'Nomor Transaksi'); 
		$_sheet->getStyle("C{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) );		
		$_sheet->setCellValue("D{$tb_row}", 'Keterangan'); 
		$_sheet->getStyle("D{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) );		
		$_sheet->setCellValue("E{$tb_row}", 'Debit'); 
		$_sheet->getStyle("E{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) );		
		$_sheet->setCellValue("F{$tb_row}", 'Kredit'); 
		$_sheet->getStyle("F{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) );		
		$_sheet->setCellValue("G{$tb_row}", 'Saldo'); 
		$_sheet->getStyle("G{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) );		
		$tb_row++;
		$tb_row_from = $tb_row;
		$tb_row;
		foreach($get_general_ledger->collection as $val):
			$_sheet->setCellValue("A{$tb_row}", $val->Nomor);
			$_sheet->setCellValue("B{$tb_row}", $val->Tanggal);
			$_sheet->setCellValue("C{$tb_row}", $val->NoBukti);
			$_sheet->setCellValue("D{$tb_row}", $val->Keterangan);
			$_sheet->setCellValue("E{$tb_row}", $val->Debit);
			$_sheet->getStyle("E{$tb_row}")->applyFromArray( self::_get_style( 'text_right' ) );
			$_sheet->getStyle("E{$tb_row}")->applyFromArray( self::_get_style( 'currency' ) );
			$_sheet->setCellValue("F{$tb_row}", $val->Kredit );	
			$_sheet->getStyle("F{$tb_row}")->applyFromArray( self::_get_style( 'text_right' ) );
			$_sheet->getStyle("F{$tb_row}")->applyFromArray( self::_get_style( 'currency' ) );
			$_sheet->setCellValue("G{$tb_row}", $val->Saldo );	
			$_sheet->getStyle("G{$tb_row}")->applyFromArray( self::_get_style( 'text_right' ) );
			$_sheet->getStyle("G{$tb_row}")->applyFromArray( self::_get_style( 'currency' ) );
			$tb_row++;
		endforeach;
		$_sheet->getStyle("A{$tb_row_from}:G{$tb_row}")->applyFromArray( self::_get_style( 'all_border' ) );
		
		$_sheet->mergeCells("A{$tb_row}:D{$tb_row}");
		$_sheet->setCellValue("A{$tb_row}", "TOTAL");
		$_sheet->getStyle("A{$tb_row}")->applyFromArray( self::_get_style( 'sub_header' ) );
		
		$_sheet->setCellValue("E{$tb_row}", $get_general_ledger->debit_summary);
		$_sheet->getStyle("E{$tb_row}")->applyFromArray( self::_get_style( 'text_right' ) );
		$_sheet->getStyle("E{$tb_row}")->applyFromArray( self::_get_style( 'currency' ) );
		$_sheet->setCellValue("F{$tb_row}", $get_general_ledger->credit_summary );	
		$_sheet->getStyle("F{$tb_row}")->applyFromArray( self::_get_style( 'text_right' ) );
		$_sheet->getStyle("F{$tb_row}")->applyFromArray( self::_get_style( 'currency' ) );
		$_sheet->setCellValue("G{$tb_row}", $get_general_ledger->ending_value );	
		$_sheet->getStyle("G{$tb_row}")->applyFromArray( self::_get_style( 'text_right' ) );
		$_sheet->getStyle("G{$tb_row}")->applyFromArray( self::_get_style( 'currency' ) );
		
		
		// Rename worksheet
		//$spreadsheet->getActiveSheet()->setTitle( 'REKAP' );
		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$spreadsheet->setActiveSheetIndex(0);
		
		// Redirect output to a client’s web browser (Xls)
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$file_name.'.xls"');
		header('Cache-Control: max-age=0');
		
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');
		// If you're serving to IE over SSL, then the following may be needed
		header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
		header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header('Pragma: public'); // HTTP/1.0
		
		$writer = IOFactory::createWriter($spreadsheet, 'Xls');
		$writer->save('php://output');
		exit;
	}
	
	public static function get_general_ledger_by_account($params)
    {
		$_ci = self::ci();	
		
		$params->currencyRate = (self::get_currency_exchange_rate( $params->Currency_ID, $params->date_till) == 0)
								? 1
								: self::get_currency_exchange_rate( $params->Currency_ID, $params->date_till);			
		$params->convertCurrency_ID = $params->convertCurrency_ID ? $params->convertCurrency_ID : NULL;
		$params->convert = !empty($params->convertCurrency_ID) ? 1 : 0;
		
		$_ci->db->query("EXEC CekHisCurrency '$params->date_till'");		
		
		$credit_summary = $debit_summary = 0;
		$beginning_balance = self::get_beginning_balance_akun($params);
		
		$transactions = self::get_general_ledger_details($params);
		foreach( $transactions as $row ){
			$credit_summary = $credit_summary + $row->Kredit;
			$debit_summary = $debit_summary + $row->Debit;
			$row->Debit = $row->Debit;
			$row->Kredit = $row->Kredit;
			$row->Saldo = $row->Saldo;
			$row->Tanggal = substr($row->Tanggal, 0, 10);
			$collection[] = $row;
		}
		
        $output = (object)[
			'beginning_value' => $beginning_balance->D > 0 ? $beginning_balance->D : $beginning_balance->K,
			'ending_value' => $row->Saldo,
			'credit_summary' => $credit_summary,
			'debit_summary' => $debit_summary,
			'collection' => $collection,
		];
			
		return $output;
    }
	
	private static function _get_style( $key )
	{
		$style['default'] = array(
			'font'  => array(
				'size'  => 10,
				'name'  => 'Calibri'
			),
			'fill' => array(
				'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
				'color' => array(
					'argb' => 'FFFFFFFF',
				),
			),
			'borders' => array(
			  	'allBorders' => array(
					'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
			  	)
			)
		);
				
		$style['header'] = array(
			'alignment' => array(
				'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
			),
			'font'  => array(
				'bold'  => true,
				'size'  => 12,
			)
		);

		$style['sub_header'] = array(
			'alignment' => array(
				'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
			),
			'font'  => array(
				'size'  => 10,
			)
		);
		
		$style['thead'] = array(
			'alignment' => array(
				'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
			),
			'font'  => array(
				'bold' => true,
				'size'  => 10,
			),
			'borders' => array(
				'top' => array(
						'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
					),
				'bottom' => array(
						'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
					),
				'left' => array(
						'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
					),
				'right' => array(
						'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
					),
			)
		);
		
		$style['tbody'] = array(
			'borders' => array(
				'top' => array(
						'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
					),
				'bottom' => array(
						'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
					),
				'left' => array(
						'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
					),
				'right' => array(
						'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
					),
			)
		);
		
		$style['tbody_merge'] = array(
			'alignment' => array(
				'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
				'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP,
			),
			'borders' => array(
				'top' => array(
						'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
					),
				'bottom' => array(
						'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
					),
				'left' => array(
						'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
					),
				'right' => array(
						'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
					),
			)
		);

		$style['tfoot_value'] = array(
			'alignment' => array(
				'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
			),
			'font'  => array(
				'bold' => true,
				'size'  => 10,
			),
			'numberFormat'  => array(
				'FormatCode'  => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
			),
			'borders' => array(
				'top' => array(
						'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
					),
				'bottom' => array(
						'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
					),
				'left' => array(
						'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
					),
				'right' => array(
						'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
					),
			)
		);
		
		$style['sum_name'] = array(
			'alignment' => array(
				'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
			),
			'font'  => array(
				'bold'  => TRUE,
				'size'  => 10,
			),
		);
		
		$style['sum_value'] = array(
			'alignment' => array(
				'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
			),
			'font'  => array(
				'bold'	=> TRUE,
				'size'  => 10,
			),
		);

		$style['sum_name_xl'] = array(
			'alignment' => array(
				'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
			),
			'font'  => array(
				'bold'	=> TRUE,
				'size'  => 12,
			),
		);

		$style['sum_value_xl'] = array(
			'font'  => array(
				'bold'	=> TRUE,
				'size'  => 12,
			),
		);

		$style['currency'] = array(
			'numberFormat'  => array(
				'formatCode'  => \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
			),
			'alignment' => array(
				'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
			),
		);
		
		$style['all_border'] = array(
			'borders' => array(
				'top' => array(
						'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
					),
				'bottom' => array(
						'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
					),
				'left' => array(
						'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
					),
				'right' => array(
						'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
					),
			)
		);
		
		$style['text_right'] = array(
			'alignment' => array(
				'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
			),
		);
		
		$style['text_left'] = array(
			'alignment' => array(
				'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
			),
		);
		
		$style['text_left_bold'] = array(
			'alignment' => array(
				'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
			),
			'font'  => array(
				'bold'  => TRUE,
			),
		);
		
		
		return $style[ $key ];
	}
	
	private static function & ci()
	{
		return get_instance();
	}
}
