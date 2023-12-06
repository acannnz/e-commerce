<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

final class balance_sheet_helper
{
	
	public static function export_balance_sheet( $date )
	{

		$date = DateTime::createFromFormat("Y-m", $date );
		$original_date = $date->format('Y-m');
		$period = $date->format('F Y');
		$period_date_end = $date->format('t/M/Y');

		$date_start = $date->format('Y-m-01');
		$date_end = $date->format('Y-m-t');
		$file_name = sprintf(lang("balance_sheets:excel_title"), $period);
				
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
				->setTitle( lang('balance_sheets:explanation_balance_label') )
				->setSubject( lang('balance_sheets:explanation_balance_label') )
				->setDescription( $file_name )
				->setKeywords( $file_name)
				;
		
		$_sheet = $spreadsheet->setActiveSheetIndex( 0 );
		
		// Default Style
		
		$spreadsheet->getDefaultStyle()->applyFromArray( self::_get_style( 'default' ) );
		
		$_sheet->mergeCells('A1:F1');
		$_sheet->setCellValue('A1', config_item("company_name") );
		$_sheet->getStyle("A1:F1")->applyFromArray( self::_get_style( 'header' ) );

		$_sheet->mergeCells('A2:F2');
		$_sheet->setCellValue('A2', lang('balance_sheets:explanation_balance_label') );
		$_sheet->getStyle("A2:F2")->applyFromArray( self::_get_style( 'header' ) );

		$_sheet->mergeCells('A3:F3');
		$_sheet->setCellValue('A3', sprintf(lang('balance_sheets:period_label'), $period_date_end));
		$_sheet->getStyle("A3:F3")->applyFromArray( self::_get_style( 'sub_header' ) );

		$_sheet->mergeCells('A4:F4');
		$_sheet->setCellValue('A4', lang('balance_sheets:in_rupiah_label'));
		$_sheet->getStyle("A4:F4")->applyFromArray( self::_get_style( 'sub_header' ) );

		$_sheet->mergeCells('A5:C5');
		$_sheet->setCellValue('A5', lang('balance_sheets:activa_label'));
		$_sheet->getStyle("A5:C5")->applyFromArray( self::_get_style( 'thead' ) );

		$_sheet->mergeCells('D5:F5');
		$_sheet->setCellValue('D5', lang('balance_sheets:pasiva_label'));
		$_sheet->getStyle("D5:F5")->applyFromArray( self::_get_style( 'thead' ) );
		
		self::ci()->load->model('balance_sheet_m');
		self::ci()->load->model('account_m');
		$activa_collection = self::ci()->balance_sheet_m->get_activa_balance( $original_date );
		$pasiva_collection = self::ci()->balance_sheet_m->get_pasiva_balance( $original_date );
		$balance_account = self::_get_balance_account();
		$concepts = self::ci()->account_m->get_concepts();
		
		$account_before = NULL;
		
		$tra = 5;
		foreach ( $activa_collection as $row ):
			
			$balance_account[ $row->Akun_No ]->Nilai = $row->Nilai;
			
			for ($i = 1; $i > 0; $i++ ):
				if ( $row->LevelKe < @$account_before->LevelKe  && $row->Akun_No != @$account_before->Akun_No )
				{
					$sum_account = $balance_account[ self::_get_parent( $concepts, $account_before ) ];
					
					++$tra;
					$_sheet->setCellValue("A{$tra}", sprintf( "%s %s %s", str_repeat(' ', (( (int) $sum_account->Level_Ke  ) - 1) * 5), "TOTAL", $sum_account->Akun_Name ) );				
					$_sheet->getStyle("A{$tra}")->applyFromArray( self::_get_style( 'sum_name' ) );
					
					if ( $sum_account->Level_Ke <= 2)
					{
						$_sheet->mergeCells("B{$tra}:C{$tra}");
						$_sheet->setCellValue("B{$tra}", $sum_account->Nilai );
						$_sheet->getStyle("B{$tra}:C{$tra}")->applyFromArray( self::_get_style( 'sum_value' ) );
						$_sheet->getStyle("B{$tra}:C{$tra}")->applyFromArray( self::_get_style( 'currency' ) );
					} else {
						$_sheet->setCellValue("B{$tra}", $sum_account->Nilai );
						$_sheet->getStyle("B{$tra}")->applyFromArray( self::_get_style( 'sum_value' ) );
						$_sheet->getStyle("B{$tra}")->applyFromArray( self::_get_style( 'currency' ) );
					}
					
					++$tra;
					
					$sum_account->LevelKe = $sum_account->Level_Ke;
					$account_before = $sum_account;
				} else {
					$account_before = $row;
					break;  
				}
			endfor;	
				
			++$tra;
			$_sheet->setCellValue("A{$tra}", sprintf( "%s %s %s", str_repeat(' ', (( (int) $row->LevelKe  ) - 1) * 5), $row->Akun_No, $row->AkunName ) );
			
			if( $row->Induk ){ 
			}else{ 
				$_sheet->setCellValue("B{$tra}", $row->Nilai );
				$_sheet->getStyle("B{$tra}")->applyFromArray( self::_get_style( 'sum_value' ) );
			}
			
			
		endforeach;
		
		/* Summary Akun Induk Aktiva Terakhir*/
		$account_before->LevelKe = 2;
		$sum_account = $balance_account[ self::_get_parent( $concepts, $account_before ) ];
		
		++$tra;
		$_sheet->setCellValue("A{$tra}", sprintf( "%s %s %s", str_repeat(' ', (( (int) $sum_account->Level_Ke  ) - 1) * 5), "TOTAL", $sum_account->Akun_Name ) );				
		$_sheet->getStyle("A{$tra}")->applyFromArray( self::_get_style( 'sum_name' ) );
		
		if ( $sum_account->Level_Ke <= 2)
		{
			$activa_total =  $sum_account->Level_Ke == 1 ? "=B{$tra}" : 0;
			$_sheet->mergeCells("B{$tra}:C{$tra}");
			$_sheet->setCellValue("B{$tra}", $sum_account->Nilai );
			$_sheet->getStyle("B{$tra}:C{$tra}")->applyFromArray( self::_get_style( 'sum_value' ) );
			$_sheet->getStyle("B{$tra}:C{$tra}")->applyFromArray( self::_get_style( 'currency' ) );
		} else {
			$_sheet->setCellValue("B{$tra}", $sum_account->Nilai );
			$_sheet->getStyle("B{$tra}")->applyFromArray( self::_get_style( 'sum_value' ) );
			$_sheet->getStyle("B{$tra}")->applyFromArray( self::_get_style( 'currency' ) );
		}
		
		++$tra;
		/* Akhir SUmary Akun Activa*/
		
		$account_before = NULL;
		$trp = 5;
		$pasiva_total = 0;
		foreach ( $pasiva_collection as $row ):
			$balance_account[ $row->Akun_No ]->Nilai = $row->Nilai;
			
			for ($i = 1; $i > 0; $i++ ):
				if ( $row->LevelKe < @$account_before->LevelKe  && $row->Akun_No != @$account_before->Akun_No )
				{
					$sum_account = $balance_account[ self::_get_parent( $concepts, $account_before ) ];
					
					++$trp;
					$_sheet->setCellValue("D{$trp}", sprintf( "%s %s %s", str_repeat(' ', (( (int) $sum_account->Level_Ke  ) - 1) * 5), "TOTAL", $sum_account->Akun_Name ) );				
					$_sheet->getStyle("D{$trp}")->applyFromArray( self::_get_style( 'sum_name' ) );
					
					if ( $sum_account->Level_Ke <= 2)
					{
						$pasiva_total =  $sum_account->Level_Ke == 1 ? "=E{$trp}": $pasiva_total;
						
						$_sheet->mergeCells("E{$trp}:F{$trp}");
						$_sheet->setCellValue("E{$trp}", $sum_account->Nilai );
						$_sheet->getStyle("E{$trp}:F{$trp}")->applyFromArray( self::_get_style( 'sum_value' ) );
						$_sheet->getStyle("E{$trp}:F{$trp}")->applyFromArray( self::_get_style( 'currency' ) );
					} else {
						$_sheet->setCellValue("E{$trp}", $sum_account->Nilai );
						$_sheet->getStyle("E{$trp}")->applyFromArray( self::_get_style( 'sum_value' ) );
						$_sheet->getStyle("E{$trp}")->applyFromArray( self::_get_style( 'currency' ) );
					}
					
					++$trp;
					
					$sum_account->LevelKe = $sum_account->Level_Ke;
					$account_before = $sum_account;
				} else {
					$account_before = $row;
					break;  
				}
			endfor;	
				
			++$trp;
			$_sheet->setCellValue("D{$trp}", sprintf( "%s %s %s", str_repeat(' ', (( (int) $row->LevelKe  ) - 1) * 5), $row->Akun_No, $row->AkunName ) );
			
			if( $row->Induk ){}else{ 
				$_sheet->setCellValue("E{$trp}", $row->Nilai );
				$_sheet->getStyle("E{$trp}")->applyFromArray( self::_get_style( 'currency' ) );
			}

		endforeach;

		/* Summary Akun Induk Pasiva Terakhir*/
		$account_before->LevelKe = 2;
		$sum_account = $balance_account[ self::_get_parent( $concepts, $account_before ) ];
		
		++$trp;
		$_sheet->setCellValue("D{$trp}", sprintf( "%s %s %s", str_repeat(' ', (( (int) $sum_account->Level_Ke  ) - 1) * 5), "TOTAL", $sum_account->Akun_Name ) );				
		$_sheet->getStyle("D{$trp}")->applyFromArray( self::_get_style( 'sum_name' ) );
		
		if ( $sum_account->Level_Ke <= 2)
		{
			$pasiva_total =  $sum_account->Level_Ke == 1 ? $pasiva_total."+E{$trp}" : 0;

			$_sheet->mergeCells("E{$trp}:F{$trp}");
			$_sheet->setCellValue("E{$trp}", $sum_account->Nilai );
			$_sheet->getStyle("E{$trp}:F{$trp}")->applyFromArray( self::_get_style( 'sum_value' ) );
			$_sheet->getStyle("E{$trp}:F{$trp}")->applyFromArray( self::_get_style( 'currency' ) );
		} else {
			$_sheet->setCellValue("E{$trp}", $sum_account->Nilai );
			$_sheet->getStyle("E{$trp}")->applyFromArray( self::_get_style( 'sum_value' ) );
			$_sheet->getStyle("E{$trp}")->applyFromArray( self::_get_style( 'currency' ) );
		}
		
		++$trp;
		/* Akhir SUmary Akun Pasiva*/
				
		$trmax = ($tra > $trp) ? $tra : $trp;
		$_sheet->getStyle("A6:C{$trmax}")->applyFromArray( self::_get_style( 'tbody' ) );
		$_sheet->getStyle("D6:F{$trmax}")->applyFromArray( self::_get_style( 'tbody' ) );
		
		// Set Money Format 
		$_sheet->getStyle("B6:C{$trmax}")->applyFromArray( self::_get_style( 'currency' ) );
		$_sheet->getStyle("E6:F{$trmax}")->applyFromArray( self::_get_style( 'currency' ) );
		++$trmax;
		
		// Total Activa & Pasiva
		$_sheet->setCellValue("A{$trmax}", lang('balance_sheets:activa_total_label'));
		$_sheet->getStyle("A{$trmax}")->applyFromArray( self::_get_style( 'thead' ) );

		$_sheet->mergeCells("B{$trmax}:C{$trmax}");
		$_sheet->setCellValue("B{$trmax}", @$balance_account[1]->Nilai );
		$_sheet->getStyle("B{$trmax}:C{$trmax}")->applyFromArray( self::_get_style( 'tfoot_value' ) );

		$_sheet->setCellValue("D{$trmax}", lang('balance_sheets:pasiva_total_label'));
		$_sheet->getStyle("D{$trmax}")->applyFromArray( self::_get_style( 'thead' ) );

		$_sheet->mergeCells("E{$trmax}:F{$trmax}");
		$_sheet->setCellValue("E{$trmax}", @$balance_account[2]->Nilai + @$balance_account[3]->Nilai ) ;
		$_sheet->getStyle("E{$trmax}:F{$trmax}")->applyFromArray( self::_get_style( 'tfoot_value' ) );
			

		/*$spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);*/


		// Rename worksheet
		$spreadsheet->getActiveSheet()->setTitle( lang('balance_sheets:explanation_balance_label') );
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
	
	private static function _get_balance_account()
	{
		$query = self::ci()->db->where_in("Group_ID", array(1,2,3), TRUE)
					->from("Mst_Akun a")
					->join("Mst_Currency b", "a.Currency_id = b.Currency_ID", "LEFT OUTER")
					->get()
					;
					
		$collection = array();			
		if ( $query->num_rows() > 0 ) : foreach ( $query->result() as $row ):
		
			$collection[ $row->Akun_No ] = $row;
			
		endforeach; endif;
		
		return $collection;
	}	
	
	private static function _get_parent( $concepts, $child )
	{
		$parent_level = $child->LevelKe - 1;
		if( $parent_level == 0) return "#";
		
		$parent_digit = $concepts[ $parent_level ]->Jumlah_Digit;
		return (string) substr($child->Akun_No, 0, $parent_digit); // return parent Account Number
	}

	private static function _number_format( $value )
	{
		return number_format( $value, 2, '.', ',');
	}
	
	private static function _get_style( $key )
	{
		$style['default'] = array(
			'fill' => array(
				'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
				'color' => array(
					'argb' => 'FFFFFFFF',
				),
			),
		);
				
		$style['header'] = array(
			'alignment' => array(
				'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
			),
			'font'  => array(
				'bold'  => true,
				'size'  => 12,
				'name'  => 'Arial'
			)
		);

		$style['sub_header'] = array(
			'alignment' => array(
				'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
			),
			'font'  => array(
				'size'  => 10,
				'name'  => 'Arial'
			)
		);
		
		$style['thead'] = array(
			'alignment' => array(
				'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
			),
			'font'  => array(
				'bold' => true,
				'size'  => 10,
				'name'  => 'Arial'
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
			'font'  => array(
				'size'  => 10,
				'name'  => 'Arial'
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
				'name'  => 'Arial'
			),
			'numberFormat'  => array(
				'FormatCode'  => \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
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
			'font'  => array(
				'bold'  => TRUE,
				'size'  => 10,
				'name'  => 'Arial'
			),
		);

		$style['sum_value'] = array(
			'font'  => array(
				'bold'	=> TRUE,
				'size'  => 10,
				'name'  => 'Arial'
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
