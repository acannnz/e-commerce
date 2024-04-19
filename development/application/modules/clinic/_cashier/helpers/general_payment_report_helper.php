<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Borders;

final class general_payment_report_helper
{
	
	public static function export_transaction_recap_by_section_doctor( $date_start, $date_end, $section_id, $doctor_id = NULL )
	{
		$_ci = self::ci();
		
		$collection = self::_get_transaction_recap_by_section_doctor( $date_start, $date_end, $section_id, $doctor_id );
		// print_r($date_end);exit;
		$date_start = DateTime::createFromFormat("Y-m-d", $date_start );
		$date_end = DateTime::createFromFormat("Y-m-d", $date_end );
		$doctor = $_ci->supplier_model->get_by(['Kode_Supplier' => $doctor_id]);
		$section = $_ci->section_model->get_one($section_id);
		$file_name = sprintf('Laporan Rekap Pendapatan %s periode %s s/d %s', !empty($doctor->Nama_Supplier) ? $doctor->Nama_Supplier : $section->SectionName, $date_start->format('d F Y'), $date_end->format('d F Y'));
		
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
				->setTitle( 'Laporan Rekap Pendapatan' )
				->setSubject( 'Laporan Rekap Pendapatan' )
				->setDescription( $file_name )
				->setKeywords( $file_name)
				;
		
		$_sheet = $spreadsheet->setActiveSheetIndex( 0 );
		
		// Default Style
		
		$spreadsheet->getDefaultStyle()->applyFromArray( self::_get_style( 'default' ) );
		
		$_sheet->setCellValue('A1', $file_name );
		$_sheet->getStyle("A1")->applyFromArray( self::_get_style( 'header' ) );

		$_sheet->setCellValue('A2', config_item("company_name") );
		$_sheet->getStyle("A2")->applyFromArray( self::_get_style( 'header' ) );

		/*$_sheet->mergeCells('A3:C3');
		$_sheet->setCellValue('A3', sprintf(lang('income_loss:period_label'), $period_date_end));
		$_sheet->getStyle("A3:C3")->applyFromArray( self::_get_style( 'sub_header' ) );

		$_sheet->mergeCells('A4:C4');
		$_sheet->setCellValue('A4', lang('income_loss:in_rupiah_label'));
		$_sheet->getStyle("A4:C4")->applyFromArray( self::_get_style( 'sub_header' ) );*/

		$_sheet->setCellValue('A4', 'No'); 
		$_sheet->getStyle("A4")->applyFromArray( self::_get_style( 'thead' ) );
		$_sheet->setCellValue('B4', 'REG'); 
		$_sheet->getStyle("B4")->applyFromArray( self::_get_style( 'thead' ) );
		$_sheet->setCellValue('C4', 'L/B'); 
		$_sheet->getStyle("C4")->applyFromArray( self::_get_style( 'thead' ) );
		$_sheet->setCellValue('D4', 'RM'); 
		$_sheet->getStyle("D4")->applyFromArray( self::_get_style( 'thead' ) ); 
		$_sheet->setCellValue('E4', 'NAMA PASIEN'); 
		$_sheet->getStyle("E4")->applyFromArray( self::_get_style( 'thead' ) );
		//$_sheet->setCellValue('F4', 'TD'); 
		$_sheet->setCellValue('F4', 'TIPE PASIEN'); 
		$_sheet->getStyle("F4")->applyFromArray( self::_get_style( 'thead' ) );
		$_sheet->setCellValue('G4', 'BB'); 
		$_sheet->getStyle("G4")->applyFromArray( self::_get_style( 'thead' ) );
		$_sheet->setCellValue('H4', 'Diagnosa'); 
		$_sheet->getStyle("H4")->applyFromArray( self::_get_style( 'thead' ) );
		$_sheet->setCellValue('I4', 'Jasa'); 
		$_sheet->getStyle("I4")->applyFromArray( self::_get_style( 'thead' ) );
		
		// Komponen thead
		$th_col = 'J';
		foreach($collection['component'] as $key => $val):
			$_sheet->setCellValue("{$th_col}4", $val);
			$_sheet->getStyle("{$th_col}4")->applyFromArray( self::_get_style( 'thead' ) );
			$th_col++;
		endforeach;
		$_sheet->mergeCells("A1:{$th_col}1");
		$_sheet->mergeCells("A2:{$th_col}2");

		// $_sheet->setCellValue("{$th_col}4", 'Obat');
		// $_sheet->getStyle("{$th_col}4")->applyFromArray( self::_get_style( 'thead' ) );
		// $th_col++;
		
		$_sheet->setCellValue("{$th_col}4", 'Total Nilai');
		$_sheet->getStyle("{$th_col}4")->applyFromArray( self::_get_style( 'thead' ) );
		$th_col++;
		
		// Discount thead
		$_sheet->setCellValue("{$th_col}4", 'Nilai Diskon');
		$_sheet->getStyle("{$th_col}4")->applyFromArray( self::_get_style( 'thead' ) );
		$th_col++;
		$_sheet->setCellValue("{$th_col}4", 'Keterangan Diskon');
		$_sheet->getStyle("{$th_col}4")->applyFromArray( self::_get_style( 'thead' ) );
		$th_col++;
		$_sheet->setCellValue("{$th_col}4", 'Beban/Keuntungan');
		$_sheet->getStyle("{$th_col}4")->applyFromArray( self::_get_style( 'thead' ) );
		$th_col++;
		$_sheet->setCellValue("{$th_col}4", 'Nama Dokter');
		$_sheet->getStyle("{$th_col}4")->applyFromArray( self::_get_style( 'thead' ) );
		$th_col++;
		$_sheet->setCellValue("{$th_col}4", 'Tunai');
		$_sheet->getStyle("{$th_col}4")->applyFromArray( self::_get_style( 'thead' ) );
		$th_col++;
		$_sheet->setCellValue("{$th_col}4", 'Kartu Kredit/Debit');
		$_sheet->getStyle("{$th_col}4")->applyFromArray( self::_get_style( 'thead' ) );
		$th_col++;
		$_sheet->setCellValue("{$th_col}4", 'Dijamin Ke Perusahaan');
		$_sheet->getStyle("{$th_col}4")->applyFromArray( self::_get_style( 'thead' ) );
		$th_col++;
		$_sheet->setCellValue("{$th_col}4", 'Dijamin BPJS');
		$_sheet->getStyle("{$th_col}4")->applyFromArray( self::_get_style( 'thead' ) );
		$th_col++;
		$_sheet->setCellValue("{$th_col}4", 'Event Healthy Day');
		$_sheet->getStyle("{$th_col}4")->applyFromArray( self::_get_style( 'thead' ) );
		$th_col++;
		$_sheet->setCellValue("{$th_col}4", 'SKTM');
		$_sheet->getStyle("{$th_col}4")->applyFromArray( self::_get_style( 'thead' ) );
		$th_col++;
		$_sheet->setCellValue("{$th_col}4", 'Bon Karyawan');
		$_sheet->getStyle("{$th_col}4")->applyFromArray( self::_get_style( 'thead' ) );
		$th_col++;
		$_sheet->setCellValue("{$th_col}4", 'Kartu Bali Sehat');
		$_sheet->getStyle("{$th_col}4")->applyFromArray( self::_get_style( 'thead' ) );
		$th_col++;
		$_sheet->setCellValue("{$th_col}4", 'Kredit/Bon');
		$_sheet->getStyle("{$th_col}4")->applyFromArray( self::_get_style( 'thead' ) );
		
		$tb_row = 5; $no = 1;
		foreach ( $collection['data'] as $key => $val ): 
			$val = (object) $val; $tb_row_start = $tb_row;
			$_sheet->setCellValue("A{$tb_row}", $no);
			$_sheet->setCellValue("B{$tb_row}", @$val->NoReg);
			$_sheet->setCellValue("C{$tb_row}", $val->PasienBaru ? 'B' : 'L');
			$_sheet->setCellValue("D{$tb_row}", $val->NRM);
			$_sheet->setCellValue("E{$tb_row}", $val->NamaPasien);
			//$_sheet->setCellValue("F{$tb_row}", @$val->TD);
			$_sheet->setCellValue("F{$tb_row}", @$val->Tipe);
			$_sheet->setCellValue("G{$tb_row}", @$val->BB);
			$_sheet->setCellValue("H{$tb_row}", empty($val->Diagnosa) ? '' : implode(';', $val->Diagnosa));

			$_multi_service = count($val->Jasa) > 1 ? TRUE : FALSE;
			$sub_total = 0;
			$sub_beban = 0;
			
			foreach($val->Jasa as $ser => $com): 
				$_sheet->setCellValue("I{$tb_row}", @$ser);
				$_sheet->getStyle("I{$tb_row}")->applyFromArray( self::_get_style( 'tbody_merge' ) );
				$tb_col = 'J'; //$sub_total = 0;
				foreach($collection['component'] as $k => $v): // List Component Header (thead) 
					$_sheet->setCellValue("{$tb_col}{$tb_row}", (@$com[$v] > 0 )? @$com[$v] : '');
					$_sheet->getStyle("{$tb_col}{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
					$sub_total = $sub_total + (float) @$com[$v];
					$sub_beban = $sub_total - @$val->NilaiDiskon;
					$tb_col++;
				endforeach;
				
				//$_sheet->setCellValue("{$tb_col}{$tb_row}", $sub_total);
				//$_sheet->getStyle("{$tb_col}{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
				
				if($_multi_service) $tb_row++;
			endforeach;
			
			if($_multi_service) $tb_row--;
			
			if($_multi_service)
				$_sheet->mergeCells("{$tb_col}{$tb_row_start}:{$tb_col}{$tb_row}");
			$_sheet->setCellValue("{$tb_col}{$tb_row_start}", $sub_total);
			$_sheet->getStyle("{$tb_col}{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
			
			// add style currency to component and sub total
			$_sheet->getStyle("J{$tb_row_start}:{$tb_col}{$tb_row}")->applyFromArray( self::_get_style( 'currency' ) );

			// Discount and Discount Description			
			// $tb_col++;
			// if($_multi_service)
			// 	$_sheet->mergeCells("{$tb_col}{$tb_row_start}:{$tb_col}{$tb_row}");
			// $_sheet->setCellValue("{$tb_col}{$tb_row_start}", $sub_beban);
			// $_sheet->getStyle("{$tb_col}{$tb_row_start}:{$tb_col}{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
			// $_sheet->getStyle("{$tb_col}{$tb_row_start}:{$tb_col}{$tb_row}")->applyFromArray( self::_get_style( 'currency' ) );

			$tb_col++; 
			if($_multi_service)
				$_sheet->mergeCells("{$tb_col}{$tb_row_start}:{$tb_col}{$tb_row}");
			$_sheet->setCellValue("{$tb_col}{$tb_row_start}", $val->NilaiDiskon > 0 ? $val->NilaiDiskon : '');
			$_sheet->getStyle("{$tb_col}{$tb_row_start}:{$tb_col}{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
			$_sheet->getStyle("{$tb_col}{$tb_row_start}:{$tb_col}{$tb_row}")->applyFromArray( self::_get_style( 'currency' ) );

			$tb_col++;
			if($_multi_service)
				$_sheet->mergeCells("{$tb_col}{$tb_row_start}:{$tb_col}{$tb_row}");
			$_sheet->setCellValue("{$tb_col}{$tb_row_start}", $val->KeteranganDiskon);
			$_sheet->getStyle("{$tb_col}{$tb_row_start}:{$tb_col}{$tb_row}")->applyFromArray( self::_get_style( 'tbody_merge' ) );

			$tb_col++;
			if($_multi_service)
				$_sheet->mergeCells("{$tb_col}{$tb_row_start}:{$tb_col}{$tb_row}");
			$_sheet->setCellValue("{$tb_col}{$tb_row_start}", $val->BebanKeuntunganKlinik);
			$_sheet->getStyle("{$tb_col}{$tb_row_start}:{$tb_col}{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
			$_sheet->getStyle("{$tb_col}{$tb_row_start}:{$tb_col}{$tb_row}")->applyFromArray( self::_get_style( 'currency' ) );

			$tb_col++;
			if($_multi_service)
				$_sheet->mergeCells("{$tb_col}{$tb_row_start}:{$tb_col}{$tb_row}");
			$_sheet->setCellValue("{$tb_col}{$tb_row_start}", $val->NamaDOkter);
			$_sheet->getStyle("{$tb_col}{$tb_row_start}:{$tb_col}{$tb_row}")->applyFromArray( self::_get_style( 'tbody_merge' ) );

			$tb_col++;
			if($_multi_service)
				$_sheet->mergeCells("{$tb_col}{$tb_row_start}:{$tb_col}{$tb_row}");
			$_sheet->setCellValue("{$tb_col}{$tb_row_start}", $val->Tunai);
			$_sheet->getStyle("{$tb_col}{$tb_row_start}:{$tb_col}{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
			$_sheet->getStyle("{$tb_col}{$tb_row_start}:{$tb_col}{$tb_row}")->applyFromArray( self::_get_style( 'currency' ) );

			$tb_col++;
			if($_multi_service)
				$_sheet->mergeCells("{$tb_col}{$tb_row_start}:{$tb_col}{$tb_row}");
			$_sheet->setCellValue("{$tb_col}{$tb_row_start}", $val->KartuDebitKredit);
			$_sheet->getStyle("{$tb_col}{$tb_row_start}:{$tb_col}{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
			$_sheet->getStyle("{$tb_col}{$tb_row_start}:{$tb_col}{$tb_row}")->applyFromArray( self::_get_style( 'currency' ) );

			$tb_col++;
			if($_multi_service)
				$_sheet->mergeCells("{$tb_col}{$tb_row_start}:{$tb_col}{$tb_row}");
			$_sheet->setCellValue("{$tb_col}{$tb_row_start}", $val->DijaminKePerusahaan);
			$_sheet->getStyle("{$tb_col}{$tb_row_start}:{$tb_col}{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
			$_sheet->getStyle("{$tb_col}{$tb_row_start}:{$tb_col}{$tb_row}")->applyFromArray( self::_get_style( 'currency' ) );

			$tb_col++;
			if($_multi_service)
				$_sheet->mergeCells("{$tb_col}{$tb_row_start}:{$tb_col}{$tb_row}");
			$_sheet->setCellValue("{$tb_col}{$tb_row_start}", $val->DijaminBPJS);
			$_sheet->getStyle("{$tb_col}{$tb_row_start}:{$tb_col}{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
			$_sheet->getStyle("{$tb_col}{$tb_row_start}:{$tb_col}{$tb_row}")->applyFromArray( self::_get_style( 'currency' ) );

			$tb_col++;
			if($_multi_service)
				$_sheet->mergeCells("{$tb_col}{$tb_row_start}:{$tb_col}{$tb_row}");
			$_sheet->setCellValue("{$tb_col}{$tb_row_start}", $val->EventHealthyDay);
			$_sheet->getStyle("{$tb_col}{$tb_row_start}:{$tb_col}{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
			$_sheet->getStyle("{$tb_col}{$tb_row_start}:{$tb_col}{$tb_row}")->applyFromArray( self::_get_style( 'currency' ) );

			$tb_col++;
			if($_multi_service)
				$_sheet->mergeCells("{$tb_col}{$tb_row_start}:{$tb_col}{$tb_row}");
			$_sheet->setCellValue("{$tb_col}{$tb_row_start}", $val->SKTM);
			$_sheet->getStyle("{$tb_col}{$tb_row_start}:{$tb_col}{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
			$_sheet->getStyle("{$tb_col}{$tb_row_start}:{$tb_col}{$tb_row}")->applyFromArray( self::_get_style( 'currency' ) );

			$tb_col++;
			if($_multi_service)
				$_sheet->mergeCells("{$tb_col}{$tb_row_start}:{$tb_col}{$tb_row}");
			$_sheet->setCellValue("{$tb_col}{$tb_row_start}", $val->SKTM);
			$_sheet->getStyle("{$tb_col}{$tb_row_start}:{$tb_col}{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
			$_sheet->getStyle("{$tb_col}{$tb_row_start}:{$tb_col}{$tb_row}")->applyFromArray( self::_get_style( 'currency' ) );

			$tb_col++;
			if($_multi_service)
				$_sheet->mergeCells("{$tb_col}{$tb_row_start}:{$tb_col}{$tb_row}");
			$_sheet->setCellValue("{$tb_col}{$tb_row_start}", $val->BONKaryawan);
			$_sheet->getStyle("{$tb_col}{$tb_row_start}:{$tb_col}{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
			$_sheet->getStyle("{$tb_col}{$tb_row_start}:{$tb_col}{$tb_row}")->applyFromArray( self::_get_style( 'currency' ) );

			$tb_col++;
			if($_multi_service)
				$_sheet->mergeCells("{$tb_col}{$tb_row_start}:{$tb_col}{$tb_row}");
			$_sheet->setCellValue("{$tb_col}{$tb_row_start}", $val->KartuBaliSehat);
			$_sheet->getStyle("{$tb_col}{$tb_row_start}:{$tb_col}{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
			$_sheet->getStyle("{$tb_col}{$tb_row_start}:{$tb_col}{$tb_row}")->applyFromArray( self::_get_style( 'currency' ) );

			if($_multi_service)
				$_sheet->mergeCells("{$tb_col}{$tb_row_start}:{$tb_col}{$tb_row}");
			$_sheet->setCellValue("{$tb_col}{$tb_row_start}", $val->KreditBON);
			$_sheet->getStyle("{$tb_col}{$tb_row_start}:{$tb_col}{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
			$_sheet->getStyle("{$tb_col}{$tb_row_start}:{$tb_col}{$tb_row}")->applyFromArray( self::_get_style( 'currency' ) );
			
			
			if($_multi_service):
				$_sheet->mergeCells("A{$tb_row_start}:A{$tb_row}");
				$_sheet->mergeCells("B{$tb_row_start}:B{$tb_row}");
				$_sheet->mergeCells("C{$tb_row_start}:C{$tb_row}");
				$_sheet->mergeCells("D{$tb_row_start}:D{$tb_row}");
				$_sheet->mergeCells("E{$tb_row_start}:E{$tb_row}");
				$_sheet->mergeCells("F{$tb_row_start}:F{$tb_row}");
				$_sheet->mergeCells("G{$tb_row_start}:G{$tb_row}");
				$_sheet->mergeCells("H{$tb_row_start}:H{$tb_row}");
			endif;
			
			$_sheet->getStyle("A{$tb_row_start}:A{$tb_row}")->applyFromArray( self::_get_style( 'tbody_merge' ) );
			$_sheet->getStyle("B{$tb_row_start}:B{$tb_row}")->applyFromArray( self::_get_style( 'tbody_merge' ) );
			$_sheet->getStyle("C{$tb_row_start}:C{$tb_row}")->applyFromArray( self::_get_style( 'tbody_merge' ) );
			$_sheet->getStyle("D{$tb_row_start}:D{$tb_row}")->applyFromArray( self::_get_style( 'tbody_merge' ) );
			$_sheet->getStyle("E{$tb_row_start}:E{$tb_row}")->applyFromArray( self::_get_style( 'tbody_merge' ) );
			$_sheet->getStyle("F{$tb_row_start}:F{$tb_row}")->applyFromArray( self::_get_style( 'tbody_merge' ) );
			$_sheet->getStyle("G{$tb_row_start}:G{$tb_row}")->applyFromArray( self::_get_style( 'tbody_merge' ) );
			$_sheet->getStyle("H{$tb_row_start}:H{$tb_row}")->applyFromArray( self::_get_style( 'tbody_merge' ) );
			
			
			$tb_row++; $no++;
		endforeach;
		
		// FOOTER
		$_sheet->mergeCells("A{$tb_row}:I{$tb_row}");
		$_sheet->getStyle("A{$tb_row}:I{$tb_row}")->applyFromArray( self::_get_style( 'sum_name' ) );
		$_sheet->setCellValue("A{$tb_row}", 'TOTAL');
		
		$tf_col = 'J'; $sum_till = $tb_row - 1;
		foreach($collection['component'] as $key => $val):
			$_sheet->setCellValue("{$tf_col}{$tb_row}", "=SUM({$tf_col}5:{$tf_col}{$sum_till})");
			$_sheet->getStyle("{$tf_col}{$tb_row}")->applyFromArray( self::_get_style( 'sum_value' ) );
			$_sheet->getStyle("{$tf_col}{$tb_row}")->applyFromArray( self::_get_style( 'currency' ) );
			$tf_col++;
		endforeach;
		
		// Obat Total
		// $obat_col = $tf_col;
		// $_sheet->setCellValue("{$tf_col}{$tb_row}", "=SUM({$tf_col}5:{$tf_col}{$sum_till})");
		// $_sheet->getStyle("{$tf_col}{$tb_row}")->applyFromArray( self::_get_style( 'sum_value' ) );
		// $_sheet->getStyle("{$tf_col}{$tb_row}")->applyFromArray( self::_get_style( 'currency' ) );
		// $tf_col++;

		// Pendapatan Total
		$_sheet->setCellValue("{$tf_col}{$tb_row}", "=SUM({$tf_col}5:{$tf_col}{$sum_till})");
		$_sheet->getStyle("{$tf_col}{$tb_row}")->applyFromArray( self::_get_style( 'sum_value' ) );
		$_sheet->getStyle("{$tf_col}{$tb_row}")->applyFromArray( self::_get_style( 'currency' ) );
		$pendapatan_col = $tf_col++;
		
		// Diskon Total
		$_sheet->setCellValue("{$tf_col}{$tb_row}", "=SUM({$tf_col}5:{$tf_col}{$sum_till})");
		$_sheet->getStyle("{$tf_col}{$tb_row}")->applyFromArray( self::_get_style( 'sum_value' ) );
		$_sheet->getStyle("{$tf_col}{$tb_row}")->applyFromArray( self::_get_style( 'currency' ) );
		$discount_col = $tf_col++;

		// Keterangan Diskon
		$_sheet->setCellValue("{$tf_col}{$tb_row}", "");
		$_sheet->getStyle("{$tf_col}{$tb_row}")->applyFromArray( self::_get_style( 'sum_value' ) );
		$_sheet->getStyle("{$tf_col}{$tb_row}")->applyFromArray( self::_get_style( 'currency' ) );
		$keterangan_col = $tf_col++;

		// Beban/Keuntungan Total
		$_sheet->setCellValue("{$tf_col}{$tb_row}", "=SUM({$tf_col}5:{$tf_col}{$sum_till})");
		$_sheet->getStyle("{$tf_col}{$tb_row}")->applyFromArray( self::_get_style( 'sum_value' ) );
		$_sheet->getStyle("{$tf_col}{$tb_row}")->applyFromArray( self::_get_style( 'currency' ) );
		$beban_col = $tf_col++;

		// NamaDokter Diskon
		$_sheet->setCellValue("{$tf_col}{$tb_row}", "");
		$_sheet->getStyle("{$tf_col}{$tb_row}")->applyFromArray( self::_get_style( 'sum_value' ) );
		$_sheet->getStyle("{$tf_col}{$tb_row}")->applyFromArray( self::_get_style( 'currency' ) );
		$namadokter_col = $tf_col++;

		// Tunai Diskon
		$_sheet->setCellValue("{$tf_col}{$tb_row}", "=SUM({$tf_col}5:{$tf_col}{$sum_till})");
		$_sheet->getStyle("{$tf_col}{$tb_row}")->applyFromArray( self::_get_style( 'sum_value' ) );
		$_sheet->getStyle("{$tf_col}{$tb_row}")->applyFromArray( self::_get_style( 'currency' ) );
		$tunai_col = $tf_col++;

		// Kredit/Debit Diskon
		$_sheet->setCellValue("{$tf_col}{$tb_row}", "=SUM({$tf_col}5:{$tf_col}{$sum_till})");
		$_sheet->getStyle("{$tf_col}{$tb_row}")->applyFromArray( self::_get_style( 'sum_value' ) );
		$_sheet->getStyle("{$tf_col}{$tb_row}")->applyFromArray( self::_get_style( 'currency' ) );
		$kreditdebit_col = $tf_col++;

		// Dijamin Ke Perusahaan Diskon
		$_sheet->setCellValue("{$tf_col}{$tb_row}", "=SUM({$tf_col}5:{$tf_col}{$sum_till})");
		$_sheet->getStyle("{$tf_col}{$tb_row}")->applyFromArray( self::_get_style( 'sum_value' ) );
		$_sheet->getStyle("{$tf_col}{$tb_row}")->applyFromArray( self::_get_style( 'currency' ) );
		$jaminperusahaan_col = $tf_col++;

		// Dijamin BPJS Diskon
		$_sheet->setCellValue("{$tf_col}{$tb_row}", "=SUM({$tf_col}5:{$tf_col}{$sum_till})");
		$_sheet->getStyle("{$tf_col}{$tb_row}")->applyFromArray( self::_get_style( 'sum_value' ) );
		$_sheet->getStyle("{$tf_col}{$tb_row}")->applyFromArray( self::_get_style( 'currency' ) );
		$jaminbpjs_col = $tf_col++;

		// EventHealthyDay Diskon
		$_sheet->setCellValue("{$tf_col}{$tb_row}", "=SUM({$tf_col}5:{$tf_col}{$sum_till})");
		$_sheet->getStyle("{$tf_col}{$tb_row}")->applyFromArray( self::_get_style( 'sum_value' ) );
		$_sheet->getStyle("{$tf_col}{$tb_row}")->applyFromArray( self::_get_style( 'currency' ) );
		$EventHealthyDay_col = $tf_col++;

		// SKTM Diskon
		$_sheet->setCellValue("{$tf_col}{$tb_row}", "=SUM({$tf_col}5:{$tf_col}{$sum_till})");
		$_sheet->getStyle("{$tf_col}{$tb_row}")->applyFromArray( self::_get_style( 'sum_value' ) );
		$_sheet->getStyle("{$tf_col}{$tb_row}")->applyFromArray( self::_get_style( 'currency' ) );
		$SKTM_col = $tf_col++;

		// BONKaryawan Diskon
		$_sheet->setCellValue("{$tf_col}{$tb_row}", "=SUM({$tf_col}5:{$tf_col}{$sum_till})");
		$_sheet->getStyle("{$tf_col}{$tb_row}")->applyFromArray( self::_get_style( 'sum_value' ) );
		$_sheet->getStyle("{$tf_col}{$tb_row}")->applyFromArray( self::_get_style( 'currency' ) );
		$BONKaryawan_col = $tf_col++;

		// KartuBaliSehat Diskon
		$_sheet->setCellValue("{$tf_col}{$tb_row}", "=SUM({$tf_col}5:{$tf_col}{$sum_till})");
		$_sheet->getStyle("{$tf_col}{$tb_row}")->applyFromArray( self::_get_style( 'sum_value' ) );
		$_sheet->getStyle("{$tf_col}{$tb_row}")->applyFromArray( self::_get_style( 'currency' ) );
		$KartuBaliSehat_col = $tf_col++;

		// KreditBON Diskon
		$_sheet->setCellValue("{$tf_col}{$tb_row}", "=SUM({$tf_col}5:{$tf_col}{$sum_till})");
		$_sheet->getStyle("{$tf_col}{$tb_row}")->applyFromArray( self::_get_style( 'sum_value' ) );
		$_sheet->getStyle("{$tf_col}{$tb_row}")->applyFromArray( self::_get_style( 'currency' ) );
		$KreditBON_col = $tf_col;


		// Set Border and Style		
		$_sheet->getStyle("A5:{$th_col}{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
		
		// GRAND TOTAL FOOTER
		$tb_row++;
		$_sheet->mergeCells("A{$tb_row}:I{$tb_row}");
		$_sheet->getStyle("A{$tb_row}:I{$tb_row}")->applyFromArray( self::_get_style( 'sum_name' ) );
		$_sheet->setCellValue("A{$tb_row}", 'GRANDTOTAL (TOTAL PENDAPATAN - TOTAL BEBAN/KEUNTUNGAN)');
		
		// $tf_col++;
		// $_sheet->mergeCells("J{$tb_row}:{$tf_col}{$tb_row}");
		$_sheet->mergeCells("J{$tb_row}:{$tf_col}{$tb_row}");
		$sub_total_row = $tb_row - 1;
		// print_r($sub_total_row);exit;
		$_sheet->setCellValue("J{$tb_row}", "={$pendapatan_col}{$sub_total_row} - {$beban_col}{$sub_total_row}");
		$_sheet->getStyle("J{$tb_row}:{$tf_col}{$tb_row}")->applyFromArray( self::_get_style( 'sum_value' ) );
		$_sheet->getStyle("J{$tb_row}:{$tf_col}{$tb_row}")->applyFromArray( self::_get_style( 'currency' ) );
		
		// TIPE PEMBAYARAN
		$tb_row += 3;
		$_sheet->mergeCells("E{$tb_row}:F{$tb_row}");
		$_sheet->setCellValue("E{$tb_row}", "PEMBAYARAN"); 
		$_sheet->getStyle("E{$tb_row}")->applyFromArray( self::_get_style( 'header' ) );
		$tb_row++;
		
		$_sheet->setCellValue("E{$tb_row}", "Tipe Pembayaran"); 
		$_sheet->getStyle("E{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) );
		$_sheet->setCellValue("F{$tb_row}", "Nilai"); 
		$_sheet->getStyle("F{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) );
		$tb_row++;
		
		$_total_payment = 0;
		foreach($collection['payment'] as $pay ):
			$_sheet->setCellValue("E{$tb_row}", $pay->TipeBayar);
			$_sheet->getStyle("E{$tb_row}")->applyFromArray( self::_get_style( 'tbody_merge' ) );
			$_sheet->setCellValue("F{$tb_row}", $pay->Nilai);
			$_sheet->getStyle("F{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
			$_sheet->getStyle("F{$tb_row}")->applyFromArray( self::_get_style( 'currency' ) );
			$_total_payment += $pay->Nilai;
			$tb_row++;
		endforeach;
		$_sheet->setCellValue("E{$tb_row}", "Total"); 
		$_sheet->getStyle("E{$tb_row}")->applyFromArray( self::_get_style( 'sum_name' ) );
		$_sheet->setCellValue("F{$tb_row}", $_total_payment); 
		$_sheet->getStyle("F{$tb_row}")->applyFromArray( self::_get_style( 'sum_value' ));
		$_sheet->getStyle("F{$tb_row}")->applyFromArray( self::_get_style( 'currency' ) );
		
		// PASIEN DENGAN PEMBAYARAN MERCHAN
		$tb_row += 3;
		$_sheet->mergeCells("E{$tb_row}:F{$tb_row}");
		$_sheet->setCellValue("E{$tb_row}", "PASIEN PEMBAYARAN MERCHAN"); 
		$_sheet->getStyle("E{$tb_row}")->applyFromArray( self::_get_style( 'header' ) );
		$tb_row++;
		
		$_sheet->setCellValue("E{$tb_row}", "Pasien"); 
		$_sheet->getStyle("E{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) );
		$_sheet->setCellValue("F{$tb_row}", "Nilai"); 
		$_sheet->getStyle("F{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) );
		$tb_row++;
		
		$_total_payment = 0;
		foreach($collection['merchan'] as $pay ):
			$_sheet->setCellValue("E{$tb_row}", $pay->NamaPasien);
			$_sheet->getStyle("E{$tb_row}")->applyFromArray( self::_get_style( 'tbody_merge' ) );
			$_sheet->setCellValue("F{$tb_row}", $pay->Nilai);
			$_sheet->getStyle("F{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
			$_sheet->getStyle("F{$tb_row}")->applyFromArray( self::_get_style( 'currency' ) );
			$_total_payment += $pay->Nilai;
			$tb_row++;
		endforeach;
		$_sheet->setCellValue("E{$tb_row}", "Total"); 
		$_sheet->getStyle("E{$tb_row}")->applyFromArray( self::_get_style( 'sum_name' ) );
		$_sheet->setCellValue("F{$tb_row}", $_total_payment); 
		$_sheet->getStyle("F{$tb_row}")->applyFromArray( self::_get_style( 'sum_value' ));
		$_sheet->getStyle("F{$tb_row}")->applyFromArray( self::_get_style( 'currency' ) );
		$tb_row++;
		
		
		/*$spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);*/
			
		// Rename worksheet
		$spreadsheet->getActiveSheet()->setTitle( 'REKAP' );
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
	
	private static function _get_transaction_recap_by_section_doctor( $date_start, $date_end, $section_id, $doctor_id )
	{		
		$date_start = DateTime::createFromFormat('Y-m-d', $date_start )->setTime(0, 0);
		$date_start->add(new DateInterval('PT8H'));
		$date_end = DateTime::createFromFormat('Y-m-d', $date_end )->setTime(0, 0);
		$date_end->add(new DateInterval('P0DT8H'));
		
		$where_doctor = (!empty($doctor_id)) ? "AND SIMtrKasir.DokterID = '{$doctor_id}'" : '';
		$query = self::ci()->db
				->query("EXEC RekapPendapatan_Klinik '{$date_start->format('Y-m-d H:i:s')}', '{$date_end->format('Y-m-d H:i:s')}', '$section_id'");
				// inner join SIMtrAudit on SIMtrKasir.NoBukti=SIMtrAudit.NoInvoice
				// and SIMtrAudit.Batal =0 
				// and NOT (SIMtrAudit.NoBukti LIKE '%-SPLIT%')
						
		$collection = ['data' => [], 'component' => [], 'payment' => []];	
		foreach($query->result() as $row)
		{ 
			if(empty($collection['data'][ $row->NoReg ]))
			{
				$collection['data'][ $row->NoReg ] =[
					'NoReg' => $row->NoReg,
					'PasienBaru' => $row->PasienBaru,
					'NRM' => $row->NRM,
					'NamaPasien' => $row->NamaPasien,
					// 'Tipe' => sprintf('%s: %s', $row->JenisKerjasama, $row->Nama_Customer),
					'Tipe' => $row->JenisKerjasama,
					'Diagnosa' => [],
					'Jasa' => [],
					'NilaiDiskon' => 0,
					'KeteranganDiskon' => '',
					'BebanKeuntunganKlinik' => $row->BebanKeuntunganKlinik,
					'NamaDOkter' => $row->NamaDOkter,
					'Obat'	=> $row->Obat,
					'Tunai' => $row->Tunai,
					'KartuDebitKredit' => $row->KartuDebitKredit,
					'DijaminKePerusahaan' => $row->DijaminKePerusahaan,
					'DijaminBPJS' => $row->DijaminBPJS,
					'EventHealthyDay' => $row->EventHealthyDay,
					'SKTM' => $row->SKTM,
					'BONKaryawan' => $row->BONKaryawan,
					'KartuBaliSehat' => $row->KartuBaliSehat,
					'KreditBON' => $row->KreditBON
				];
				
				$_get_discount = self::ci()->db->select('b.NamaDiscount, a.NilaiDiscount ')
											->from('SIMtrKasirDiscount a')
											->join('mDiscount b', 'a.IDDiscount = b.IDDiscount')
											->where('a.NoBukti', $row->NoBukti)
											->get()
											->result();
				
				foreach($_get_discount as $disc):
					$collection['data'][ $row->NoReg ]['NilaiDiskon'] += (float) $disc->NilaiDiscount;
					$collection['data'][ $row->NoReg ]['KeteranganDiskon'] .= sprintf('%s(%s); ', $disc->NamaDiscount, self::_number_format($disc->NilaiDiscount));
				endforeach;
				
				$_get_diagnosa = self::ci()->db->select('b.Descriptions')
									->from('SIMtrRJDiagnosaAwal a')
									->join('mICD b', 'a.KodeICD = b.KodeICD', 'INNER')
									->where('a.NOBukti', $row->NoPeriksa)
									->get();
				foreach( $_get_diagnosa->result() as $diag ):
					$collection['data'][ $row->NoReg ]['Diagnosa'][] = $diag->Descriptions;
				endforeach;
			}
			
			$collection['data'][ $row->NoReg ]['Jasa'][$row->JasaName][$row->KomponenName] = $row->Nilai;
			
			$collection['component'][$row->KomponenBiayaID] = $row->KomponenName;
		}
		
		if(!empty($collection['component']))
			asort($collection['component']);
		
		// Pasien dengan pembayaran Merchan		
		if(!empty($doctor_id))
			self::ci()->db->where('a.DokterID', $doctor_id);
		$collection['merchan'] = self::ci()->db->select("e.NamaPasien, SUM(b.NilaiBayar) AS Nilai")
											->from('SIMtrKasir a')
											->join('SIMtrKasirDetail b', 'a.NoBUkti = b.NoBukti', 'INNER')
											->join('mJenisBayar c', 'b.IDBayar = c.IDBayar', 'INNER')
											//->join('SIMtrAudit d', 'a.NoBukti = d.NoInvoice', 'INNER')
											->join('VW_Registrasi e', 'a.NoReg = e.NoReg', 'INNER')
											->where([
												'e.JamReg >=' => $date_start->format('Y-m-d H:i:s'), 
												'e.JamReg <=' => $date_end->format('Y-m-d H:i:s'),
												'b.IDBayar' => 7,
												'b.NilaiBayar >' => 0,											
												'a.Batal' => 0,
												//'d.Batal' => 0,											
												'a.SectionPerawatanID' => $section_id
											])
											//->not_like('d.NoBukti', '-SPLIT')
											->group_by('e.NamaPasien')
											->get()
											->result();
		
		// Total Jenis Pembayaran
		if(!empty($doctor_id))
			self::ci()->db->where('a.DokterID', $doctor_id);
		$collection['payment'] = self::ci()->db->select("c.Description AS TipeBayar, SUM(b.NilaiBayar) AS Nilai")
											->from('SIMtrKasir a')
											->join('SIMtrKasirDetail b', 'a.NoBUkti = b.NoBukti', 'INNER')
											->join('mJenisBayar c', 'b.IDBayar = c.IDBayar', 'INNER')
											//->join('SIMtrAudit d', 'a.NoBukti=d.NoInvoice', 'INNER')
											->join('VW_Registrasi e', 'a.NoReg = e.NoReg', 'INNER')
											->where([
												'e.JamReg >=' => $date_start->format('Y-m-d H:i:s'), 
												'e.JamReg <=' => $date_end->format('Y-m-d H:i:s'),
												'a.Batal' => 0,
												//'d.Batal' => 0,											
												'a.SectionPerawatanID' => $section_id
											])
											//->not_like('d.NoBukti', '-SPLIT')
											->group_by('c.Description')
											->get()
											->result();
		return $collection;
	}
	
	public static function export_transaction_recap_by_service_group( $date_start, $date_end, $section_id, $doctor_id = NULL )
	{
		$_ci = self::ci();
		
		$collection = self::_get_transaction_recap_by_service_group( $date_start, $date_end, $section_id, $doctor_id );	
		$date_start = DateTime::createFromFormat("Y-m-d", $date_start );
		$date_end = DateTime::createFromFormat("Y-m-d", $date_end );
		$doctor = $_ci->supplier_model->get_by(['Kode_Supplier' => $doctor_id]);
		$section = $_ci->section_model->get_one($section_id);
		$file_name = sprintf('Laporan Rekap Pendapatan Dengan Grup Jasa %s periode %s s/d %s ', !empty($doctor->Nama_Supplier) ? $doctor->Nama_Supplier : $section->SectionName, $date_start->format('d F Y'), $date_end->format('d F Y'));
		
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
				->setTitle( 'Laporan Rekap Pendapatan dengan Grup Jasa' )
				->setSubject( 'Laporan Rekap Pendapatan dengan Grup Jasa' )
				->setDescription( $file_name )
				->setKeywords( $file_name)
				;
		
		$_sheet = $spreadsheet->setActiveSheetIndex( 0 );
		
		// Default Style
		
		$spreadsheet->getDefaultStyle()->applyFromArray( self::_get_style( 'default' ) );
		
		$_sheet->mergeCells("A1:D1");
		$_sheet->setCellValue('A1', "Laporan Rekap Pendapatan Dengan Grup Jasa" );
		$_sheet->getStyle("A1")->applyFromArray( self::_get_style( 'header' ) );
		//$_sheet->getStyle("A1")->getAlignment()->setWrapText(true);
		//$_sheet->getRowDimension('1')->setRowHeight(30);

		$_sheet->mergeCells("A2:D2");
		$_sheet->setCellValue('A2', sprintf('%s Periode %s s/d %s ', !empty($doctor->Nama_Supplier) ? $doctor->Nama_Supplier : $section->SectionName, $date_start->format('d F Y'), $date_end->format('d F Y')));
		$_sheet->getStyle("A2")->applyFromArray( self::_get_style( 'header' ) );

		$_sheet->setCellValue('A4', 'JASA'); 
		$_sheet->getStyle("A4")->applyFromArray( self::_get_style( 'thead' ) );
		$_sheet->setCellValue('B4', 'QTY'); 
		$_sheet->getStyle("B4")->applyFromArray( self::_get_style( 'thead' ) );
		$_sheet->setCellValue('C4', 'TARIF'); 
		$_sheet->getStyle("C4")->applyFromArray( self::_get_style( 'thead' ) );
		$_sheet->setCellValue('D4', 'SUB TOTAL'); 
		$_sheet->getStyle("D4")->applyFromArray( self::_get_style( 'thead' ) ); 
		
		$tb_row = 5; $sub_total = 0;
		foreach ( $collection['data'] as $service_group => $services ):
			$_sheet->mergeCells("A{$tb_row}:D{$tb_row}");
			$_sheet->setCellValue("A{$tb_row}", "{$service_group} :");
			$_sheet->getStyle("A{$tb_row}:D{$tb_row}")->applyFromArray( self::_get_style( 'tbody_merge' ) );
			$_sheet->getStyle("A{$tb_row}")->applyFromArray(['font'  => ['bold'	=> TRUE, 'size'  => 10 ]]);
		
			$tb_row++;
			foreach($services as $service => $val):
				$val = (object) $val;
				$_sheet->setCellValue("A{$tb_row}", $service);
				$_sheet->getStyle("A{$tb_row}")->applyFromArray( self::_get_style( 'tbody_merge' ) );
				$_sheet->setCellValue("B{$tb_row}", @$val->Qty);
				$_sheet->getStyle("B{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
				$_sheet->setCellValue("C{$tb_row}", $val->Tarif);
				$_sheet->getStyle("C{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
				$_sheet->setCellValue("D{$tb_row}", $val->Nilai);
				$_sheet->getStyle("D{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
				$sub_total += $val->Nilai;
				$tb_row++;
			endforeach;
		endforeach;
		
		// add style currency to Valued Cell
		$_sheet->getStyle("C5:D{$tb_row}")->applyFromArray( self::_get_style( 'currency' ) );
		$tb_row++;
		
		// FOOTER
		// Total Pendapatan
		$_sheet->mergeCells("A{$tb_row}:C{$tb_row}");
		$_sheet->getStyle("A{$tb_row}:C{$tb_row}")->applyFromArray( self::_get_style( 'sum_name' ) );
		$_sheet->setCellValue("A{$tb_row}", 'TOTAL PENDAPATAN');
		$_sheet->getStyle("D{$tb_row}")->applyFromArray( self::_get_style( 'sum_value' ) );
		$_sheet->getStyle("D{$tb_row}")->applyFromArray( self::_get_style( 'currency' ) );
		$_sheet->setCellValue("D{$tb_row}", $sub_total);
		$tb_row++;
		
		// Total Diskon
		$_sheet->mergeCells("A{$tb_row}:C{$tb_row}");
		$_sheet->getStyle("A{$tb_row}:C{$tb_row}")->applyFromArray( self::_get_style( 'sum_name' ) );
		$_sheet->setCellValue("A{$tb_row}", 'TOTAL DISKON');
		$_sheet->getStyle("D{$tb_row}")->applyFromArray( self::_get_style( 'sum_value' ) );
		$_sheet->getStyle("D{$tb_row}")->applyFromArray( self::_get_style( 'currency' ) );
		$_sheet->setCellValue("D{$tb_row}", $collection['discount']);
		$tb_row++;
		
		// GRAND TOTAL
		$_sheet->mergeCells("A{$tb_row}:C{$tb_row}");
		$_sheet->getStyle("A{$tb_row}:C{$tb_row}")->applyFromArray( self::_get_style( 'sum_name' ) );
		$_sheet->setCellValue("A{$tb_row}", 'GRANDTOTAL (TOTAL PENDAPATAN - TOTAL DISKON)');
		$_sheet->getStyle("D{$tb_row}")->applyFromArray( self::_get_style( 'sum_value' ) );
		$_sheet->getStyle("D{$tb_row}")->applyFromArray( self::_get_style( 'currency' ) );
		$_sheet->setCellValue("D{$tb_row}", $sub_total - $collection['discount']);
		
		// TIPE PEMBAYARAN
		$tb_row += 3;
		$_sheet->mergeCells("A{$tb_row}:B{$tb_row}");
		$_sheet->setCellValue("A{$tb_row}", "PEMBAYARAN"); 
		$_sheet->getStyle("A{$tb_row}")->applyFromArray( self::_get_style( 'header' ) );
		$tb_row++;
		
		$_sheet->setCellValue("A{$tb_row}", "Tipe Pembayaran"); 
		$_sheet->getStyle("A{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) );
		$_sheet->setCellValue("B{$tb_row}", "Nilai"); 
		$_sheet->getStyle("B{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) );
		$tb_row++;
		
		$_total_payment = 0;
		foreach($collection['payment'] as $pay ):
			$_sheet->setCellValue("A{$tb_row}", $pay->TipeBayar);
			$_sheet->getStyle("A{$tb_row}")->applyFromArray( self::_get_style( 'tbody_merge' ) );
			$_sheet->setCellValue("B{$tb_row}", $pay->Nilai);
			$_sheet->getStyle("B{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
			$_sheet->getStyle("B{$tb_row}")->applyFromArray( self::_get_style( 'currency' ) );
			$_total_payment += $pay->Nilai;
			$tb_row++;
		endforeach;
		$_sheet->setCellValue("A{$tb_row}", "Total"); 
		$_sheet->getStyle("A{$tb_row}")->applyFromArray( self::_get_style( 'tbody_merge' ) );
		$_sheet->setCellValue("B{$tb_row}", $_total_payment); 
		$_sheet->getStyle("B{$tb_row}")->applyFromArray( self::_get_style( 'sum_value' ));
		$_sheet->getStyle("B{$tb_row}")->applyFromArray( self::_get_style( 'currency' ) );
		
		// PASIEN DENGAN PEMBAYARAN MERCHAN
		$tb_row += 3;
		$_sheet->mergeCells("A{$tb_row}:B{$tb_row}");
		$_sheet->setCellValue("A{$tb_row}", "PASIEN PEMBAYARAN MERCHAN"); 
		$_sheet->getStyle("A{$tb_row}")->applyFromArray( self::_get_style( 'header' ) );
		$tb_row++;
		
		$_sheet->setCellValue("A{$tb_row}", "Pasien"); 
		$_sheet->getStyle("A{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) );
		$_sheet->setCellValue("B{$tb_row}", "Nilai"); 
		$_sheet->getStyle("B{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) );
		$tb_row++;
		
		$_total_payment = 0;
		foreach($collection['merchan'] as $pay ):
			$_sheet->setCellValue("A{$tb_row}", $pay->NamaPasien);
			$_sheet->getStyle("A{$tb_row}")->applyFromArray( self::_get_style( 'tbody_merge' ) );
			$_sheet->setCellValue("B{$tb_row}", $pay->Nilai);
			$_sheet->getStyle("B{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
			$_sheet->getStyle("B{$tb_row}")->applyFromArray( self::_get_style( 'currency' ) );
			$_total_payment += $pay->Nilai;
			$tb_row++;
		endforeach;
		$_sheet->setCellValue("A{$tb_row}", "Total"); 
		$_sheet->getStyle("A{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
		$_sheet->setCellValue("B{$tb_row}", $_total_payment); 
		$_sheet->getStyle("B{$tb_row}")->applyFromArray( self::_get_style( 'sum_value' ));
		$_sheet->getStyle("B{$tb_row}")->applyFromArray( self::_get_style( 'currency' ) );
		$tb_row++;
		
		
		/*$spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);*/
					
		// Rename worksheet
		$spreadsheet->getActiveSheet()->setTitle( 'REKAP' );
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
	
	private static function _get_transaction_recap_by_service_group( $date_start, $date_end, $section_id, $doctor_id )
	{
		$date_start = DateTime::createFromFormat('Y-m-d', $date_start )->setTime(0, 0);
		$date_start->add(new DateInterval('PT8H'));
		$date_end = DateTime::createFromFormat('Y-m-d', $date_end )->setTime(0, 0);
		$date_end->add(new DateInterval('P1DT8H'));
		
		$where_doctor = (!empty($doctor_id)) ? "AND SIMtrKasir.DokterID = '{$doctor_id}'" : '';
		$query = self::ci()->db
				->query("
					SELECT SIMtrKasir.RJ, SIMtrRJ.Tanggal AS TglTindakan,
							SIMtrKasir.Tanggal AS TglClosing,
							VW_Registrasi.NoReg,
							VW_Registrasi.NRM,
							VW_Registrasi.NamaPasien,
							VW_Registrasi.JenisKerjasama,
							VW_Registrasi.Nama_Customer,
							VW_Registrasi.PasienBaru,
							SIMmListJasa.JasaName,
							SIMmGroupJasa.GroupJasaName,
							SUM(SIMtrRJTransaksi.Qty * SIMtrRJTransaksi.Tarif) AS Nilai,
							Vw_Dokter.NamaDOkter,
							SIMtrKasir.NoBukti, SIMtrRJTransaksi.JasaID, 
							SIMtrRJ.NoBukti AS NoPeriksa
					FROM SIMtrRJ 
						INNER JOIN SIMtrRJTransaksi ON SIMtrRJ.NoBukti = SIMtrRJTransaksi.NoBukti
						INNER JOIN SIMmListJasa ON SIMtrRJTransaksi.JasaID = SIMmListJasa.JasaID 
						INNER JOIN VW_Registrasi ON SIMtrRJ.RegNo = VW_Registrasi.NoReg
						INNER JOIN SIMmGroupJasa ON SIMmGroupJasa.GroupJasaID = SIMmListJasa.GroupJasaID 
						INNER JOIN SIMtrKasir ON VW_Registrasi.NoReg = SIMtrKasir.NoReg
						INNER JOIN Vw_Dokter ON SIMtrKasir.DokterID = Vw_Dokter.DokterID   
					WHERE 
						SIMtrRJ.Batal = 0
						AND SIMtrKasir.Batal = 0
						AND VW_Registrasi.JamReg >= '". $date_start->format('Y-m-d H:i:s') ."'
						AND VW_Registrasi.JamReg <= '". $date_end->format('Y-m-d H:i:s') ."'
						". $where_doctor ."
						AND SIMtrKasir.SectionPerawatanID = '{$section_id}'
						AND SIMtrRJTransaksi.Tarif <> 0
					GROUP BY SIMtrKasir.RJ,
						SIMtrRJ.Tanggal,
						SIMtrKasir.Tanggal,
						VW_Registrasi.NoReg,
						VW_Registrasi.NRM,
						VW_Registrasi.NamaPasien,
						VW_Registrasi.JenisKerjasama,
						VW_Registrasi.Nama_Customer,
						VW_Registrasi.PasienBaru,
						SIMmListJasa.JasaName,
						SIMmGroupJasa.GroupJasaName,
						Vw_Dokter.NamaDOkter,
						SIMtrKasir.NoBukti, SIMtrRJTransaksi.JasaID, 
						SIMtrRJ.NoBukti
				");
				// INNER JOIN SIMtrAudit ON SIMtrKasir.NoBukti = SIMtrAudit.NoInvoice
				// AND SIMtrAudit.Batal = 0
				// AND SIMtrAudit.NoBukti NOT LIKE '%-SPLIT%'
		$discount = []; // Untuk Penanda Bahwa data Sudah Dicari Diskonnya
		$collection = ['data' => [], 'payment' => [], 'discount' => 0];	
		foreach($query->result() as $row)
		{
			if(empty($collection['data'][ $row->GroupJasaName ][ $row->JasaName ]))
			{
				$collection['data'][ $row->GroupJasaName ][ $row->JasaName ]['Nilai'] = $row->Nilai;
				$collection['data'][ $row->GroupJasaName ][ $row->JasaName ]['Qty'] = 1;
				$collection['data'][ $row->GroupJasaName ][ $row->JasaName ]['Tarif'] = $row->Nilai;
			} else {
				$collection['data'][ $row->GroupJasaName ][ $row->JasaName ]['Nilai'] += $row->Nilai;
				$collection['data'][ $row->GroupJasaName ][ $row->JasaName ]['Qty'] += 1;			
			}
			
			if(empty($discount[ $row->NoReg]))
			{	
				$discount[ $row->NoReg] = $row->NoReg;			
				$_get_discount = self::ci()->db->select('SUM(a.NilaiDiscount) AS Nilai')
											->from('SIMtrKasirDiscount a')
											->join('mDiscount b', 'a.IDDiscount = b.IDDiscount')
											->where('a.NoBukti', $row->NoBukti)
											->get()
											->row();
											
				$collection['discount'] += $_get_discount->Nilai;
			}
			
		}	
		
		if(!empty($collection['data']))
			asort($collection['data']);
		
		// Pasien dengan pembayaran Merchan		
		if(!empty($doctor_id))
			self::ci()->db->where('a.DokterID', $doctor_id);
		$collection['merchan'] = self::ci()->db->select("e.NamaPasien, SUM(b.NilaiBayar) AS Nilai")
											->from('SIMtrKasir a')
											->join('SIMtrKasirDetail b', 'a.NoBUkti = b.NoBukti', 'INNER')
											->join('mJenisBayar c', 'b.IDBayar = c.IDBayar', 'INNER')
											//->join('SIMtrAudit d', 'a.NoBukti = d.NoInvoice', 'INNER')
											->join('VW_Registrasi e', 'a.NoReg = e.NoReg', 'INNER')
											->where([
												'e.JamReg >=' => $date_start->format('Y-m-d H:i:s'), 
												'e.JamReg <=' => $date_end->format('Y-m-d H:i:s'),
												'b.IDBayar' => 7,
												'b.NilaiBayar >' => 0,											
												'a.Batal' => 0,
												//'d.Batal' => 0,											
												'a.SectionPerawatanID' => $section_id
											])
											//->not_like('d.NoBukti', '-SPLIT')
											->group_by('e.NamaPasien')
											->get()
											->result();
		
		// Total Jenis Pembayaran
		if(!empty($doctor_id))
			self::ci()->db->where('a.DokterID', $doctor_id);
		$collection['payment'] = self::ci()->db->select("c.Description AS TipeBayar, SUM(b.NilaiBayar) AS Nilai")
											->from('SIMtrKasir a')
											->join('SIMtrKasirDetail b', 'a.NoBUkti = b.NoBukti', 'INNER')
											->join('mJenisBayar c', 'b.IDBayar = c.IDBayar', 'INNER')
											//->join('SIMtrAudit d', 'a.NoBukti = d.NoInvoice', 'INNER')
											->join('VW_Registrasi e', 'a.NoReg = e.NoReg', 'INNER')
											->where([
												'e.JamReg >=' => $date_start->format('Y-m-d H:i:s'), 
												'e.JamReg <=' => $date_end->format('Y-m-d H:i:s'),
												'a.Batal' => 0,
												//'d.Batal' => 0,											
												'a.SectionPerawatanID' => $section_id
											])
											//->not_like('d.NoBukti', '-SPLIT')
											->group_by('c.Description')
											->get()
											->result();
		//print_r($collection);exit;
		return $collection;
	}

	private static function _number_format( $value )
	{
		return $value > 0 
			? number_format( $value, 2, '.', ',')
			: sprintf("(%s)", number_format( abs($value), 2, '.', ',') );
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

		$style['sum_value'] = array(
			'alignment' => array(
				'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
			),
			'font'  => array(
				'bold'	=> TRUE,
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

		$style['sum_name_xl'] = array(
			'alignment' => array(
				'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
			),
			'font'  => array(
				'bold'  => TRUE,
				'size'  => 12,
			),
		);

		$style['sum_value_xl'] = array(
			'font'  => array(
				'bold'	=> TRUE,
				'size'  => 12,
			),
			'borders' => array(
				'top' => array(
						'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
					),
			)
		);

		$style['currency'] = array(
			'numberFormat'  => array(
				'formatCode'  => \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
			),
			'alignment' => array(
				'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
			),
		);
		
		return $style[ $key ];
	}
	
	private static function & ci()
	{
		return get_instance();
	}

}
