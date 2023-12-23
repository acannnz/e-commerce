<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Borders;
final class cash_flow_helper 
{
	public static function export_cash_flow( $period, $annual = FALSE )
	{
		$_ci = self::ci();
		
		$cash_flow = self::get_cash_flow( $period, $annual );	
		if(@$cash_flow['response_status'] == 'error')
		{
			echo $cash_flow['message'];
			echo "<script type='text/javascript'>setTimeout(function(){window.close()}, 3000);</script>";
			exit;			
		}
		
		$date = DateTime::createFromFormat("Y-m", $period );
		$file_name = sprintf('%s Periode %s s/d %s ', 'Laporan Cash Flow', $date->format('01 F Y'), $date->format('t F Y'));
		
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
				->setTitle( 'Laporan Cash Flow' )
				->setSubject( 'Laporan Cash Flow' )
				->setDescription( $file_name )
				->setKeywords( $file_name)
				;	

		$_sheet = $spreadsheet->setActiveSheetIndex( 0 );
		$spreadsheet->getActiveSheet()->setTitle("Cash Flow");
			
		// Default Style			
		$spreadsheet->getDefaultStyle()->applyFromArray( self::_get_style( 'default' ) );
		
		$_sheet->mergeCells("A1:C1");
		$_sheet->setCellValue('A1', $file_name );
		$_sheet->getStyle("A1")->applyFromArray( self::_get_style( 'header' ) );
		$_sheet->getStyle("A1")->getAlignment()->setWrapText(true);
		$_sheet->getRowDimension('1')->setRowHeight(30);
		
		$_sheet->mergeCells("A3:B3");
		$_sheet->setCellValue("A3", 'GROUP'); 
		$_sheet->getStyle("A3:B3")->applyFromArray( self::_get_style( 'thead' ) );
		$_sheet->setCellValue("C3", 'NILAI'); 
		$_sheet->getStyle("C3")->applyFromArray( self::_get_style( 'thead' ) );		
		
		$tb_row = 4; $grand_total = 0;
		foreach($cash_flow['collection'] as $group => $subgroups):
				$_sheet->setCellValue("A{$tb_row}", $group);
				$tb_row++;

				$group_val = 0;
				foreach($subgroups as $subgroup => $val):
					$_sheet->setCellValue("B{$tb_row}", @$subgroup);
					$_sheet->setCellValue("C{$tb_row}", $val->NilaiRealisasiI < 0 ? sprintf("(%s)", numb_format(abs($val->NilaiRealisasiI))) : $val->NilaiRealisasiI );	
					$_sheet->getStyle("C{$tb_row}")->applyFromArray( self::_get_style( 'text_right' ) );
					
					$tb_row++;
					$group_val += $val->NilaiRealisasiI;
				endforeach;
				
				$grand_total += $group_val;
				$_sheet->setCellValue("B{$tb_row}", "       Arus Kas Bersih {$group}");
				$_sheet->getStyle("B{$tb_row}")->applyFromArray( self::_get_style( 'sum_name' ) );
				
				$_sheet->setCellValue("C{$tb_row}", $group_val < 0 ? sprintf("(%s)", numb_format(abs($group_val))) : $group_val );
				$_sheet->getStyle("C{$tb_row}")->applyFromArray( self::_get_style( 'sum_value' ) );
				$tb_row++;
		endforeach;
		$tb_row--;
		$_sheet->getStyle("A4:B{$tb_row}")->applyFromArray( self::_get_style( 'all_border' ) );
		$_sheet->getStyle("C4:C{$tb_row}")->applyFromArray( self::_get_style( 'all_border' ) );
		
		$tb_row++;
		$from_row = $tb_row;
		
		// SUMMARY
		$_sheet->mergeCells("A{$tb_row}:B{$tb_row}");
		$_sheet->setCellValue("A{$tb_row}", 'Kenaikan (Penurunan) Bersih Kas Setara :');
		$_sheet->getStyle("A{$tb_row}:B{$tb_row}")->applyFromArray( self::_get_style( 'sum_name' ) );
		
		$_sheet->setCellValue("C{$tb_row}", $grand_total < 0 ? sprintf("(%s)", numb_format(abs($grand_total))) : $grand_total);								
		$_sheet->getStyle("C{$tb_row}")->applyFromArray( self::_get_style( 'sum_value' ) );
		$tb_row++;
		
		$_sheet->mergeCells("A{$tb_row}:B{$tb_row}");
		$_sheet->setCellValue("A{$tb_row}", 'Saldo Awal Kas Setara :');
		$_sheet->getStyle("A{$tb_row}:B{$tb_row}")->applyFromArray( self::_get_style( 'sum_name' ) );
		
		$_sheet->setCellValue("C{$tb_row}", $cash_flow['beginning_balance']);								
		$_sheet->getStyle("C{$tb_row}")->applyFromArray( self::_get_style( 'sum_value' ) );
		$_sheet->getStyle("C{$tb_row}")->applyFromArray( self::_get_style( 'currency' ) );
		$tb_row++;
		
		$_sheet->mergeCells("A{$tb_row}:B{$tb_row}");
		$_sheet->setCellValue("A{$tb_row}", 'Saldo Akhir Kas Setara :');
		$_sheet->getStyle("A{$tb_row}:B{$tb_row}")->applyFromArray( self::_get_style( 'sum_name' ) );
		
		$ending_balance = $cash_flow['beginning_balance'] - $grand_total;
		$_sheet->setCellValue("C{$tb_row}", $ending_balance);								
		$_sheet->getStyle("C{$tb_row}")->applyFromArray( self::_get_style( 'sum_value' ) );
		$_sheet->getStyle("C{$tb_row}")->applyFromArray( self::_get_style( 'currency' ) );
		$_sheet->getStyle("C{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
		
		$_sheet->getStyle("A{$from_row}:B{$tb_row}")->applyFromArray( self::_get_style( 'all_border' ) );
		$_sheet->getStyle("C{$from_row}:C{$tb_row}")->applyFromArray( self::_get_style( 'all_border' ) );
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

	public static function get_cash_flow( $period, $annual = FALSE )
	{
		$_ci = self::ci();
		$type = 1;
		$date = DateTime::createFromFormat('Y-m', $period);
		
		$rate = self::check_rate_currency($date->format('Y-m-t'));
		if($rate === FALSE)
		{
			return ['response_status' => 'error', 'message' => "Rate Currency Tanggal {$date->format('Y-m-d')} belum di Setup"];
		}
		
		if(! self::check_hiscurrency($date->format('Y-m-t')))
		{
			return ['response_status' => 'error', 'message' => "His Currency Tanggal {$date->format('Y-m-d')} belum di Setup"];
		}
		
		if(! self::check_closing_period($date->format('Y-m')))
		{
			return ['response_status' => 'error', 'message' => "Belum ada Tutup Buku periode {$date->format('Y-m')}"];
		}
				
		$beginning_balance = $_ci->db->query("Select dbo.GetSaldoAwalCashFlow('{$date->format('Y-m-01')}', '{$date->format('Y-m-t')}') AS balance")->row()->balance;
		
		/*
		Type Seleksi adalah
		-- 1. Bulanan 
		-- 2. Triwulan
		-- 3. Catur wulan
		-- 4. Semester
		-- 5. Year To Date
  		
		 Nilai Seleksi adalah
		--Sesuai dengan nilai seleksi
		-- Misalnya bulan 1 (Januari)
		-- Misalnya Triwulan 4
		-- Misalnya Semester 2
		*/
		
		#exec ProcCashFlow date_start, date_end, date_start2, date_end2, rate, rate2, type, NilaiSeleksi, NilaiSeleksi2, TahunSeleksi, TahunSeleksi2
		$cash_flow = $_ci->db->query("exec ProcCashFlow '{$date->format('Y-m-01')}','{$date->format('Y-m-t')}', '30/Dec/1899', '30/Dec/1899', {$rate}, {$rate}, {$type}, {$date->format('m')}, {$date->format('m')}, {$date->format('Y')}, {$date->format('Y')}")
							->result();
		$collection = [];
		foreach( $cash_flow as $row )
		{	
			$collection[$row->GroupI][ $row->GroupII ] = (object)[
				'NilaiRealisasiI' => $row->NilaiRealisasiI,
				'NilaiRealisasiII' => $row->NilaiRealisasiII
			];
		}
		
		return ['beginning_balance' => $beginning_balance, 'collection' => $collection];
	}
	
	public static function export_cash_flow_detail( $period, $annual = FALSE )
	{
		$_ci = self::ci();
		
		$cash_flow = self::get_cash_flow_detail( $period, $annual );	
		if(@$cash_flow['response_status'] == 'error')
		{
			echo $cash_flow['message'];
			echo "<script type='text/javascript'>setTimeout(function(){window.close()}, 3000);</script>";
			exit;			
		}
		
		$date = DateTime::createFromFormat("Y-m", $period );
		$file_name = sprintf('%s Periode %s s/d %s ', 'Laporan Cash Flow Detail', $date->format('01 F Y'), $date->format('t F Y'));
		
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
				->setTitle( 'Laporan Cash Flow Detail' )
				->setSubject( 'Laporan Cash Flow Detail' )
				->setDescription( $file_name )
				->setKeywords( $file_name)
				;	

		$_sheet = $spreadsheet->setActiveSheetIndex( 0 );
		$spreadsheet->getActiveSheet()->setTitle("Cash Flow");
			
		// Default Style			
		$spreadsheet->getDefaultStyle()->applyFromArray( self::_get_style( 'default' ) );
		
		$_sheet->mergeCells("A1:E1");
		$_sheet->setCellValue('A1', $file_name );
		$_sheet->getStyle("A1")->applyFromArray( self::_get_style( 'header' ) );
		$_sheet->getStyle("A1")->getAlignment()->setWrapText(true);
		$_sheet->getRowDimension('1')->setRowHeight(30);
		
		$_sheet->mergeCells("A3:B3");
		$_sheet->setCellValue("A3", 'GROUP'); 
		$_sheet->getStyle("A3:B3")->applyFromArray( self::_get_style( 'thead' ) );
		$_sheet->setCellValue("C3", 'No. Rek'); 
		$_sheet->getStyle("C3")->applyFromArray( self::_get_style( 'thead' ) );		
		$_sheet->setCellValue("D3", 'Nama Rekening'); 
		$_sheet->getStyle("D3")->applyFromArray( self::_get_style( 'thead' ) );		
		$_sheet->setCellValue("E3", 'NILAI'); 
		$_sheet->getStyle("E3")->applyFromArray( self::_get_style( 'thead' ) );		
		
		$tb_row = 4; $grand_total = 0;
		foreach($cash_flow['collection'] as $group => $subgroups):
				$_sheet->setCellValue("A{$tb_row}", $group);
				$_sheet->getStyle("A{$tb_row}")->applyFromArray( self::_get_style( 'text_left_bold' ) );
				$tb_row++;

				$group_val = 0;
				foreach($subgroups as $subgroup => $accounts):
					$_sheet->setCellValue("B{$tb_row}", @$subgroup);
					$tb_row++;
					
					$subgroup_val = 0;
					foreach($accounts as $val):
						$_sheet->setCellValue("C{$tb_row}", @$val->Akun_No);
						$_sheet->getStyle("C{$tb_row}")->applyFromArray( self::_get_style( 'text_left' ) );
						$_sheet->setCellValue("D{$tb_row}", @$val->Akun_Name);
						$_sheet->setCellValue("E{$tb_row}", $val->NilaiRealisasi < 0 ? sprintf("(%s)", numb_format(abs($val->NilaiRealisasi))) : $val->NilaiRealisasi );	
						$_sheet->getStyle("E{$tb_row}")->applyFromArray( self::_get_style( 'text_right' ) );
						
						$tb_row++;
						$subgroup_val += $val->NilaiRealisasi;
					endforeach;
					$_sheet->setCellValue("B{$tb_row}", "Sub Total {$subgroup}");
					$_sheet->getStyle("B{$tb_row}")->applyFromArray( self::_get_style( 'text_left_bold' ) );
					
					$_sheet->setCellValue("E{$tb_row}", $subgroup_val < 0 ? sprintf("(%s)", numb_format(abs($subgroup_val))) : $subgroup_val );
					$_sheet->getStyle("E{$tb_row}")->applyFromArray( self::_get_style( 'sum_value' ) );
					
					$tb_row++;
					$group_val += $subgroup_val;
				endforeach;
				
				$grand_total += $group_val;
				$_sheet->setCellValue("A{$tb_row}", "Total {$group}");
				$_sheet->getStyle("A{$tb_row}")->applyFromArray( self::_get_style( 'text_left_bold' ) );
				
				$_sheet->setCellValue("E{$tb_row}", $group_val < 0 ? sprintf("(%s)", numb_format(abs($group_val))) : $group_val );
				$_sheet->getStyle("E{$tb_row}")->applyFromArray( self::_get_style( 'sum_value' ) );
				$tb_row++;
		endforeach;
		$tb_row--;
		$_sheet->getStyle("A4:E{$tb_row}")->applyFromArray( self::_get_style( 'all_border' ) );
			
		$tb_row++;
		$from_row = $tb_row;
		
		// SUMMARY
		$_sheet->mergeCells("A{$tb_row}:C{$tb_row}");
		$_sheet->setCellValue("A{$tb_row}", 'GRAND TOTAL : ');
		$_sheet->getStyle("A{$tb_row}:C{$tb_row}")->applyFromArray( self::_get_style( 'sum_name_xl' ) );
		
		$_sheet->setCellValue("E{$tb_row}", $grand_total < 0 ? sprintf("(%s)", numb_format(abs($grand_total))) : $grand_total);								
		$_sheet->getStyle("E{$tb_row}")->applyFromArray( self::_get_style( 'sum_name_xl' ) );
		
		$_sheet->getStyle("A{$tb_row}:E{$tb_row}")->applyFromArray( self::_get_style( 'all_border' ) );
			
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
	
	public static function get_cash_flow_detail( $period, $annual = FALSE )
	{
		$_ci = self::ci();
		$type = 1;
		$date = DateTime::createFromFormat('Y-m', $period);
		
		$rate = self::check_rate_currency($date->format('Y-m-t'));
		if($rate === FALSE)
		{
			return ['response_status' => 'error', 'message' => "Rate Currency Tanggal {$date->format('Y-m-d')} belum di Setup"];
		}
		
		if(! self::check_hiscurrency($date->format('Y-m-t')))
		{
			return ['response_status' => 'error', 'message' => "His Currency Tanggal {$date->format('Y-m-d')} belum di Setup"];
		}
		
		if(! self::check_closing_period($date->format('Y-m')))
		{
			return ['response_status' => 'error', 'message' => "Belum ada Tutup Buku periode {$date->format('Y-m')}"];
		}
				
		/*
			Type Seleksi adalah
			-- 1. Bulanan 
			-- 2. Triwulan
			-- 3. Catur wulan
			-- 4. Semester
			-- 5. Year To Date
			
			 Nilai Seleksi adalah
			--Sesuai dengan nilai seleksi
			-- Misalnya bulan 1 (Januari), 5 (Mei)
			-- Misalnya Triwulan 4
			-- Misalnya Semester 2
		*/
		
		# exec ProcCashFlowDetail date_start, date_end, rate, type, NilaiSeleksi, TahunSeleksi
		$cash_flow = $_ci->db->query("exec ProcCashFlowDetail '{$date->format('Y-m-01')}','{$date->format('Y-m-t')}', {$rate}, {$type}, {$date->format('m')}, {$date->format('Y')}")
							->result();
		$collection = [];
		foreach( $cash_flow as $row )
		{	
			$collection[$row->GroupI][ $row->GroupII ][] = (object)[
				'Akun_No' => $row->Akun_No,
				'Akun_Name' => $row->Akun_Name,
				'NilaiRealisasi' => $row->NilaiRealisasi
			];
		}
		
		return ['collection' => $collection];
	}
	
	public static function export_cash_flow_transaction( $period, $annual = FALSE )
	{
		$_ci = self::ci();
		
		$cash_flow = self::get_cash_flow_transaction( $period, $annual );	
		if(@$cash_flow['response_status'] == 'error')
		{
			echo $cash_flow['message'];
			echo "<script type='text/javascript'>setTimeout(function(){window.close()}, 3000);</script>";
			exit;			
		}
		
		$date = DateTime::createFromFormat("Y-m", $period );
		$file_name = sprintf('%s Periode %s s/d %s ', 'Laporan Cash Flow Transaksi', $date->format('01 F Y'), $date->format('t F Y'));
		
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
				->setTitle( 'Laporan Cash Flow' )
				->setSubject( 'Laporan Cash Flow' )
				->setDescription( $file_name )
				->setKeywords( $file_name)
				;	

		$_sheet = $spreadsheet->setActiveSheetIndex( 0 );
		$spreadsheet->getActiveSheet()->setTitle("Cash Flow");
			
		// Default Style			
		$spreadsheet->getDefaultStyle()->applyFromArray( self::_get_style( 'default' ) );
		
		$_sheet->mergeCells("A1:E1");
		$_sheet->setCellValue('A1', $file_name );
		$_sheet->getStyle("A1")->applyFromArray( self::_get_style( 'header' ) );
		$_sheet->getStyle("A1")->getAlignment()->setWrapText(true);
		$_sheet->getRowDimension('1')->setRowHeight(30);
		
		
		$tb_row = 3; $grand_total = 0;
		foreach($cash_flow['collection'] as $number => $details):			
			foreach($details as $name => $value)
				$_sheet->setCellValue("A{$tb_row}", "Rekening : {$number} --> {$name}"); 
				$tb_row++;
				
				$_sheet->setCellValue("A{$tb_row}", "Tanggal"); 
				$_sheet->getStyle("A{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) );
				$_sheet->setCellValue("B{$tb_row}", "No Bukti"); 
				$_sheet->getStyle("B{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) );
				$_sheet->setCellValue("C{$tb_row}", 'Curr'); 
				$_sheet->getStyle("C{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) );		
				$_sheet->setCellValue("D{$tb_row}", 'Debit'); 
				$_sheet->getStyle("D{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) );		
				$_sheet->setCellValue("E{$tb_row}", 'Kredit'); 
				$_sheet->getStyle("E{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) );		
				$_sheet->setCellValue("F{$tb_row}", 'Keterangan'); 
				$_sheet->getStyle("F{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) );		
				$tb_row++;
				
				$tb_body_from = $tb_row;
				$debit_val = $credit_val = 0;
				foreach($value as $val):
					$_sheet->setCellValue("A{$tb_row}", $val->Tanggal);
					$_sheet->setCellValue("B{$tb_row}", $val->NoBukti);
					$_sheet->setCellValue("C{$tb_row}", $val->MataUang);
					$_sheet->setCellValue("D{$tb_row}", $val->Debit * $val->NilaiTukar);
					$_sheet->getStyle("D{$tb_row}")->applyFromArray( self::_get_style( 'text_right' ) );
					$_sheet->getStyle("D{$tb_row}")->applyFromArray( self::_get_style( 'currency' ) );
					$_sheet->setCellValue("E{$tb_row}", $val->Kredit * $val->NilaiTukar);	
					$_sheet->getStyle("E{$tb_row}")->applyFromArray( self::_get_style( 'text_right' ) );
					$_sheet->getStyle("E{$tb_row}")->applyFromArray( self::_get_style( 'currency' ) );
					$_sheet->setCellValue("F{$tb_row}", "  {$val->Keterangan}");				
					$tb_row++;
					
					$debit_val += $val->Debit * $val->NilaiTukar;
					$credit_val += $val->Kredit * $val->NilaiTukar;
				endforeach;
				$_sheet->getStyle("A{$tb_body_from}:F{$tb_row}")->applyFromArray( self::_get_style( 'all_border' ) );
				
				$_sheet->mergeCells("A{$tb_row}:B{$tb_row}");
				$_sheet->setCellValue("A{$tb_row}", "TOTAL");
				$_sheet->getStyle("A{$tb_row}")->applyFromArray( self::_get_style( 'sub_header' ) );
				
				$_sheet->setCellValue("D{$tb_row}", $debit_val);
				$_sheet->getStyle("D{$tb_row}")->applyFromArray( self::_get_style( 'text_right' ) );
				$_sheet->getStyle("D{$tb_row}")->applyFromArray( self::_get_style( 'currency' ) );
				
				$_sheet->setCellValue("E{$tb_row}", $credit_val);
				$_sheet->getStyle("E{$tb_row}")->applyFromArray( self::_get_style( 'text_right' ) );
				$_sheet->getStyle("E{$tb_row}")->applyFromArray( self::_get_style( 'currency' ) );
				
				$_sheet->setCellValue("F{$tb_row}", abs($debit_val - $credit_val));
				$_sheet->getStyle("F{$tb_row}")->applyFromArray( self::_get_style( 'text_right' ) );
				$_sheet->getStyle("F{$tb_row}")->applyFromArray( self::_get_style( 'currency' ) );	
				
				$_sheet->getStyle("A{$tb_row}:F{$tb_row}")->applyFromArray( self::_get_style( 'all_border' ) );			
				$tb_row += 2;;
		endforeach;
		
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

	public static function get_cash_flow_transaction( $period, $annual = FALSE )
	{
		$_ci = self::ci();
		$type = 1;
		$date = DateTime::createFromFormat('Y-m', $period);
		
		$rate = self::check_rate_currency($date->format('Y-m-t'));
		if($rate === FALSE)
		{
			return ['response_status' => 'error', 'message' => "Rate Currency Tanggal {$date->format('Y-m-d')} belum di Setup"];
		}
		
		if(! self::check_hiscurrency($date->format('Y-m-t')))
		{
			return ['response_status' => 'error', 'message' => "His Currency Tanggal {$date->format('Y-m-d')} belum di Setup"];
		}
		
		if(! self::check_closing_period($date->format('Y-m')))
		{
			return ['response_status' => 'error', 'message' => "Belum ada Tutup Buku periode {$date->format('Y-m')}"];
		}
			
		#exec ProcTransaksiCashFlow date_start, date_end
		$cash_flow = $_ci->db->query("exec ProcTransaksiCashFlow '{$date->format('Y-m-01')}','{$date->format('Y-m-t')}'")
							->result();
		$collection = [];
		foreach( $cash_flow as $row )
		{	
			$collection["{$row->Akun_No}"][$row->Akun_Name][] = (object)[
				'Tanggal' => DateTime::createFromFormat('Y-m-d H:i:s.u', $row->Tanggal)->format('d-F-Y'),
				'NoBukti' => $row->NoBukti,
				'Debit' => $row->Debit,
				'Kredit' => $row->Kredit,
				'MataUang' => $row->MataUang,
				'NilaiTukar' => $row->NilaiTukar,
				'Keterangan' => $row->Keterangan,
			];
		}
		
		ksort($collection, SORT_STRING );
		return ['collection' => $collection];
	}
	
	public static function check_rate_currency( $date = NULL, $currency_id = 1)
	{
		$_ci = self::ci();	
		$date = $date ? $date : date('Y-m-t');
		
		$rate = @$_ci->db->select("Rate")
					->from("TBJ_HisCurrencyPosted a")
					->join("TBJ_HisCurrency b", "a.HisCurrency_iD = b.HisCurrency_ID", "INNER")
					->where(["b.Tanggal" => $date, "a.Currency_ID" => $currency_id])
					->get()
					->row()->Rate;

		return ($rate && $rate <> 0 ) ? TRUE : FALSE;
	}
	
	public static function check_hiscurrency( $date = NULL)
	{
		$_ci = self::ci();		
		$date = $date ? $date : date('Y-m-t');
		
		$his = @$_ci->db->query("SELECT dbo.GetHisCurrencyID('{$date}') AS HisCurrency")->row()->HisCurrency;
		if($his && $his <> 0 )
		{
			return $his;
		} else {
			@$_ci->db->query("exec CekHisCurrency'{$date}'");	
		}
		return @$_ci->db->query("SELECT dbo.GetHisCurrencyID('{$date}') AS HisCurrency")->row()->HisCurrency;
	}
	
	# @date Format 'Y-m'
	public static function check_closing_period($date)
	{
		$_ci = self::ci();	
		$date = $date ? $date : date('Y-m');
		$date = DateTime::createFromFormat('Y-m', $date);
		
		$closing = @$_ci->db->select("Tanggal")
						->from("TBJ_PostedBulanan a")
						->join("TBJ_HisCurrency b", "a.HisCurrency_iD = b.HisCurrency_ID", "INNER")
						->where(["DATEPART(MONTH, b.Tanggal) =" => $date->format('m'), "DATEPART(YEAR, b.Tanggal) =" => $date->format('Y')])
						->count_all_results();		
		
		return (int) $closing;
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