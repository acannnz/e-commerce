<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Borders;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

final class income_loss_helper
{
	
	public static function export_income_loss( $date, $annual = FALSE )
	{
		if( $annual )
		{
			$date = DateTime::createFromFormat("Y", $date );
			$original_date = $date->format('Y');
			$period = $date->format('Y');
			$period_date_end = $date->format('31/12/Y');
			
			$date_start = $date->format('Y-01-01');
			$date_end = $date->format('Y-12-31');
		} else {
			$date = DateTime::createFromFormat("Y-m", $date );
			$original_date = $date->format('Y-m');
			$period = $date->format('F Y');
			$period_date_end = $date->format('t/M/Y');
	
			$date_start = $date->format('Y-m-01');
			$date_end = $date->format('Y-m-t');
		}
		
		
		$file_name = sprintf(lang("income_loss:excel_title"), $period);

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
				->setTitle( lang('income_loss:explanation_balance_label') )
				->setSubject( lang('income_loss:explanation_balance_label') )
				->setDescription( $file_name )
				->setKeywords( $file_name)
				;
		
		$_sheet = $spreadsheet->setActiveSheetIndex( 0 );
		
		// Default Style
		
		$spreadsheet->getDefaultStyle()->applyFromArray( self::_get_style( 'default' ) );
		
		$_sheet->mergeCells('A1:C1');
		$_sheet->setCellValue('A1', config_item("company_name") );
		$_sheet->getStyle("A1:C1")->applyFromArray( self::_get_style( 'header' ) );

		$_sheet->mergeCells('A2:C2');
		$_sheet->setCellValue('A2', lang('income_loss:explanation_balance_label') );
		$_sheet->getStyle("A2:C2")->applyFromArray( self::_get_style( 'header' ) );

		$_sheet->mergeCells('A3:C3');
		$_sheet->setCellValue('A3', sprintf(lang('income_loss:period_label'), $period_date_end));
		$_sheet->getStyle("A3:C3")->applyFromArray( self::_get_style( 'sub_header' ) );

		$_sheet->mergeCells('A4:C4');
		$_sheet->setCellValue('A4', lang('income_loss:in_rupiah_label'));
		$_sheet->getStyle("A4:C4")->applyFromArray( self::_get_style( 'sub_header' ) );

		$_sheet->setCellValue('A5', lang('income_loss:description_label'));
		$_sheet->getStyle("A5")->applyFromArray( self::_get_style( 'thead' ) );
		
		$_sheet->mergeCells('B5:C5');
		$_sheet->setCellValue('B5', lang('income_loss:value_label'));
		$_sheet->getStyle("A5:C5")->applyFromArray( self::_get_style( 'thead' ) );

		self::ci()->load->model('income_loss_m');
		self::ci()->load->model('account_m');
		$income_loss_collection = self::_get_detail_account_income_loss( $original_date, $annual );
		$summary_income_loss = self::_get_summary_income_loss( $original_date, $annual );
		$income_loss_account = self::_get_income_loss_account();
		$concepts = self::ci()->account_m->get_concepts();

		$account_before = NULL;
			
		$tr = 5;
		foreach ( @$summary_income_loss['order'] as $group ):
		
			# Order $group : array(4, 5, 'gross_profit', 6, 'ebitda', 8, 'depreciation', 7, 'ebit', 10, 'eat'),
			switch ( $group ):
			 case "gross_profit" :
						
					for ($i = 1; $i > 0; $i++ ):
						if ( @$account_before->levelke > 1 )
						{
							$sum_account = $income_loss_account[ self::_get_parent( $concepts, $account_before ) ];
							
							++$tr;
							$_sheet->setCellValue("A{$tr}", sprintf( "%s%s %s", str_repeat(' ', (( (int) $sum_account->Level_Ke  ) - 1) * 5), "TOTAL", $sum_account->Akun_Name ) );				
							$_sheet->getStyle("A{$tr}")->applyFromArray( self::_get_style( 'sum_name' ) );
							
							if ( $sum_account->Level_Ke == 1 )
							{
								$_sheet->getStyle("A{$tr}")->applyFromArray( self::_get_style( 'sum_name_xl' ) );
								$_sheet->mergeCells("B{$tr}:C{$tr}");
								$_sheet->setCellValue("B{$tr}", $sum_account->Nilai );
								$_sheet->getStyle("B{$tr}:C{$tr}")->applyFromArray( self::_get_style( 'sum_value' ) );
								$_sheet->getStyle("B{$tr}:C{$tr}")->applyFromArray( self::_get_style( 'currency' ) );
							} else {
								$_sheet->setCellValue("B{$tr}", $sum_account->Nilai );
								$_sheet->getStyle("B{$tr}")->applyFromArray( self::_get_style( 'sum_value' ) );
								$_sheet->getStyle("B{$tr}")->applyFromArray( self::_get_style( 'currency' ) );
							}
							
							++$tr;
							
							$sum_account->levelke = $sum_account->Level_Ke;
							$account_before = $sum_account;
						} else {
							break;  
						}
					endfor;	

					++$tr;

					$_sheet->setCellValue("A{$tr}","LABA KOTOR" );				
					$_sheet->getStyle("A{$tr}")->applyFromArray( self::_get_style( 'sum_name_xl' ) );
					
					$_sheet->mergeCells("B{$tr}:C{$tr}");
					$_sheet->setCellValue("B{$tr}", $summary_income_loss[ $group ] );
					$_sheet->getStyle("B{$tr}:C{$tr}")->applyFromArray( self::_get_style( 'sum_value_xl' ) );
					$_sheet->getStyle("B{$tr}:C{$tr}")->applyFromArray( self::_get_style( 'currency' ) );
						
					++$tr;
			 break;

			 case "ebitda" :
						
					for ($i = 1; $i > 0; $i++ ):
						if ( @$account_before->levelke > 1 )
						{
							$sum_account = $income_loss_account[ self::_get_parent( $concepts, $account_before ) ];
							
							++$tr;
							$_sheet->setCellValue("A{$tr}", sprintf( "%s%s %s", str_repeat(' ', (( (int) $sum_account->Level_Ke  ) - 1) * 5), "TOTAL", $sum_account->Akun_Name ) );				
							$_sheet->getStyle("A{$tr}")->applyFromArray( self::_get_style( 'sum_name' ) );
							
							if ( $sum_account->Level_Ke == 1 )
							{
								$_sheet->getStyle("A{$tr}")->applyFromArray( self::_get_style( 'sum_name_xl' ) );
								$_sheet->mergeCells("B{$tr}:C{$tr}");
								$_sheet->setCellValue("B{$tr}", $sum_account->Nilai );
								$_sheet->getStyle("B{$tr}:C{$tr}")->applyFromArray( self::_get_style( 'sum_value' ) );
								$_sheet->getStyle("B{$tr}:C{$tr}")->applyFromArray( self::_get_style( 'currency' ) );
							} else {
								$_sheet->setCellValue("B{$tr}", $sum_account->Nilai );
								$_sheet->getStyle("B{$tr}")->applyFromArray( self::_get_style( 'sum_value' ) );
								$_sheet->getStyle("B{$tr}")->applyFromArray( self::_get_style( 'currency' ) );
							}
							
							++$tr;
							
							$sum_account->levelke = $sum_account->Level_Ke;
							$account_before = $sum_account;
						} else {
							break;  
						}
					endfor;	

					++$tr;

					$_sheet->setCellValue("A{$tr}","LABA OPERASIONAL" );				
					$_sheet->getStyle("A{$tr}")->applyFromArray( self::_get_style( 'sum_name_xl' ) );
					
					$_sheet->mergeCells("B{$tr}:C{$tr}");
					$_sheet->setCellValue("B{$tr}", $summary_income_loss[ $group ] );
					$_sheet->getStyle("B{$tr}:C{$tr}")->applyFromArray( self::_get_style( 'sum_value_xl' ) );
					$_sheet->getStyle("B{$tr}:C{$tr}")->applyFromArray( self::_get_style( 'currency' ) );
						
					++$tr;
			 break;

			 case "depreciation" :
						
					for ($i = 1; $i > 0; $i++ ):
						if ( @$account_before->levelke > 1 )
						{
							$sum_account = $income_loss_account[ self::_get_parent( $concepts, $account_before ) ];
							
							++$tr;
							$_sheet->setCellValue("A{$tr}", sprintf( "%s%s %s", str_repeat(' ', (( (int) $sum_account->Level_Ke  ) - 1) * 5), "TOTAL", $sum_account->Akun_Name ) );				
							$_sheet->getStyle("A{$tr}")->applyFromArray( self::_get_style( 'sum_name' ) );
							
							if ( $sum_account->Level_Ke == 1 )
							{
								$_sheet->getStyle("A{$tr}")->applyFromArray( self::_get_style( 'sum_name_xl' ) );
								$_sheet->mergeCells("B{$tr}:C{$tr}");
								$_sheet->setCellValue("B{$tr}", $sum_account->Nilai );
								$_sheet->getStyle("B{$tr}:C{$tr}")->applyFromArray( self::_get_style( 'sum_value' ) );
								$_sheet->getStyle("B{$tr}:C{$tr}")->applyFromArray( self::_get_style( 'currency' ) );
							} else {
								$_sheet->setCellValue("B{$tr}", $sum_account->Nilai );
								$_sheet->getStyle("B{$tr}")->applyFromArray( self::_get_style( 'sum_value' ) );
								$_sheet->getStyle("B{$tr}")->applyFromArray( self::_get_style( 'currency' ) );
							}
							
							++$tr;
							
							$sum_account->levelke = $sum_account->Level_Ke;
							$account_before = $sum_account;
						} else {
							break;  
						}
					endfor;	

					++$tr;

					$_sheet->setCellValue("A{$tr}","LABA SEBELUM PENYUSUTAN" );				
					$_sheet->getStyle("A{$tr}")->applyFromArray( self::_get_style( 'sum_name_xl' ) );
					
					$_sheet->mergeCells("B{$tr}:C{$tr}");
					$_sheet->setCellValue("B{$tr}", $summary_income_loss[ $group ] );
					$_sheet->getStyle("B{$tr}:C{$tr}")->applyFromArray( self::_get_style( 'sum_value_xl' ) );
					$_sheet->getStyle("B{$tr}:C{$tr}")->applyFromArray( self::_get_style( 'currency' ) );
						
					++$tr;
			 break;
			 
			 case "ebit" :
						
					for ($i = 1; $i > 0; $i++ ):
						if ( @$account_before->levelke > 1 )
						{
							$sum_account = $income_loss_account[ self::_get_parent( $concepts, $account_before ) ];
							
							++$tr;
							$_sheet->setCellValue("A{$tr}", sprintf( "%s%s %s", str_repeat(' ', (( (int) $sum_account->Level_Ke  ) - 1) * 5), "TOTAL", $sum_account->Akun_Name ) );				
							$_sheet->getStyle("A{$tr}")->applyFromArray( self::_get_style( 'sum_name' ) );
							
							if ( $sum_account->Level_Ke == 1 )
							{
								$_sheet->getStyle("A{$tr}")->applyFromArray( self::_get_style( 'sum_name_xl' ) );
								$_sheet->mergeCells("B{$tr}:C{$tr}");
								$_sheet->setCellValue("B{$tr}", $sum_account->Nilai );
								$_sheet->getStyle("B{$tr}:C{$tr}")->applyFromArray( self::_get_style( 'sum_value' ) );
								$_sheet->getStyle("B{$tr}:C{$tr}")->applyFromArray( self::_get_style( 'currency' ) );
							} else {
								$_sheet->setCellValue("B{$tr}", $sum_account->Nilai );
								$_sheet->getStyle("B{$tr}")->applyFromArray( self::_get_style( 'sum_value' ) );
								$_sheet->getStyle("B{$tr}")->applyFromArray( self::_get_style( 'currency' ) );
							}
							
							++$tr;
							
							$sum_account->levelke = $sum_account->Level_Ke;
							$account_before = $sum_account;
						} else {
							break;  
						}
					endfor;	

					++$tr;

					$_sheet->setCellValue("A{$tr}","LABA SEBELUM BUNGA DAN PAJAK" );				
					$_sheet->getStyle("A{$tr}")->applyFromArray( self::_get_style( 'sum_name_xl' ) );
					
					$_sheet->mergeCells("B{$tr}:C{$tr}");
					$_sheet->setCellValue("B{$tr}", $summary_income_loss[ $group ] );
					$_sheet->getStyle("B{$tr}:C{$tr}")->applyFromArray( self::_get_style( 'sum_value_xl' ) );
					$_sheet->getStyle("B{$tr}:C{$tr}")->applyFromArray( self::_get_style( 'currency' ) );
						
					++$tr;
			 break;

			 case "eat" :
						
					for ($i = 1; $i > 0; $i++ ):
						if ( @$account_before->levelke > 1 )
						{
							$sum_account = $income_loss_account[ self::_get_parent( $concepts, $account_before ) ];
							
							++$tr;
							$_sheet->setCellValue("A{$tr}", sprintf( "%s%s %s", str_repeat(' ', (( (int) $sum_account->Level_Ke  ) - 1) * 5), "TOTAL", $sum_account->Akun_Name ) );				
							$_sheet->getStyle("A{$tr}")->applyFromArray( self::_get_style( 'sum_name' ) );
							
							if ( $sum_account->Level_Ke == 1 )
							{
								$_sheet->getStyle("A{$tr}")->applyFromArray( self::_get_style( 'sum_name_xl' ) );
								$_sheet->mergeCells("B{$tr}:C{$tr}");
								$_sheet->setCellValue("B{$tr}", $sum_account->Nilai );
								$_sheet->getStyle("B{$tr}:C{$tr}")->applyFromArray( self::_get_style( 'sum_value' ) );
								$_sheet->getStyle("B{$tr}:C{$tr}")->applyFromArray( self::_get_style( 'currency' ) );
							} else {
								$_sheet->setCellValue("B{$tr}", $sum_account->Nilai );
								$_sheet->getStyle("B{$tr}")->applyFromArray( self::_get_style( 'sum_value' ) );
								$_sheet->getStyle("B{$tr}")->applyFromArray( self::_get_style( 'currency' ) );
							}
							
							++$tr;
							
							$sum_account->levelke = $sum_account->Level_Ke;
							$account_before = $sum_account;
						} else {
							break;  
						}
					endfor;	

					++$tr;

					$_sheet->setCellValue("A{$tr}","LABA BERSIH" );				
					$_sheet->getStyle("A{$tr}")->applyFromArray( self::_get_style( 'sum_name_xl' ) );
					
					$_sheet->mergeCells("B{$tr}:C{$tr}");
					$_sheet->setCellValue("B{$tr}", $summary_income_loss[ $group ] );
					$_sheet->getStyle("B{$tr}:C{$tr}")->applyFromArray( self::_get_style( 'sum_value_xl' ) );
					$_sheet->getStyle("B{$tr}:C{$tr}")->applyFromArray( self::_get_style( 'currency' ) );
						
					++$tr;
			 break;
			 			 			 
			 default:
			 	if(!empty($income_loss_collection[ $group ])):
					foreach ( @$income_loss_collection[ $group ] as $row ):
						
							$income_loss_account[ $row->Akun_No ]->Nilai = $row->Nilai;
							
							for ($i = 1; $i > 0; $i++ ):
								if ( $row->levelke < @$account_before->levelke  && $row->Akun_No != @$account_before->Akun_No )
								{
									$sum_account = $income_loss_account[ self::_get_parent( $concepts, $account_before ) ];
									
									++$tr;
									$_sheet->setCellValue("A{$tr}", sprintf( "%s%s %s", str_repeat(' ', (( (int) $sum_account->Level_Ke  ) - 1) * 5), "TOTAL", $sum_account->Akun_Name ) );				
									$_sheet->getStyle("A{$tr}")->applyFromArray( self::_get_style( 'sum_name' ) );
									
									if ( $sum_account->Level_Ke == 1 )
									{
										$_sheet->getStyle("A{$tr}")->applyFromArray( self::_get_style( 'sum_name_xl' ) );
										$_sheet->mergeCells("B{$tr}:C{$tr}");
										$_sheet->setCellValue("B{$tr}", (float) $sum_account->Nilai );
										$_sheet->getStyle("B{$tr}:C{$tr}")->applyFromArray( self::_get_style( 'sum_value' ) );
										$_sheet->getStyle("B{$tr}:C{$tr}")->applyFromArray( self::_get_style( 'currency' ) );
									} else {
										$_sheet->setCellValue("B{$tr}", (float) $sum_account->Nilai );
										$_sheet->getStyle("B{$tr}")->applyFromArray( self::_get_style( 'sum_value' ) );
										$_sheet->getStyle("B{$tr}")->applyFromArray( self::_get_style( 'currency' ) );
									}
									
									++$tr;
									
									$sum_account->levelke = $sum_account->Level_Ke;
									$account_before = $sum_account;
								} else {
									$account_before = $row;
									break;  
								}
							endfor;	
								
							++$tr;
							$_sheet->setCellValue("A{$tr}", sprintf( "%s%s %s", str_repeat(' ', (( (int) $row->levelke  ) - 1) * 5), $row->Akun_No, $row->AkunName ) );
							
							if ( $row->Induk ){
							}else{ 
								$_sheet->setCellValue("B{$tr}", $row->Nilai );	
								$_sheet->getStyle("B{$tr}")->applyFromArray( self::_get_style( 'currency' ) );				
							}	
					endforeach;
				endif;

				/* Summary Akun Induk Terakhir*/
				/*$sum_account = $income_loss_account[ self::_get_parent( $concepts, $account_before ) ];
				
				
				if ( $sum_account->Level_Ke == 1)
				{
					++$tr;
					$_sheet->setCellValue("A{$tr}", sprintf( "%s%s %s", str_repeat(' ', (( (int) $sum_account->Level_Ke  ) - 1) * 5), "TOTAL", $sum_account->Akun_Name ) );				
					$_sheet->getStyle("A{$tr}")->applyFromArray( self::_get_style( 'sum_name' ) );

					$_sheet->mergeCells("B{$tr}:C{$tr}");
					$_sheet->setCellValue("B{$tr}",  $sum_account->Nilai );
					$_sheet->getStyle("B{$tr}:C{$tr}")->applyFromArray( self::_get_style( 'sum_value' ) );
					$_sheet->getStyle("B{$tr}:C{$tr}")->applyFromArray( self::_get_style( 'currency' ) );
					++$tr;
					$account_before = $sum_account;
				} */
				
				/* Akhir SUmary Akun Activa*/
				
				//break;

			endswitch;
		endforeach;
		
		
		// Set Border and Style		
		$_sheet->getStyle("A6:C{$tr}")->applyFromArray( self::_get_style( 'tbody' ) );
		
		// Set Money Format 
		//$_sheet->getStyle("B6:C{$tr}")->applyFromArray( self::_get_style( 'currency' ) );
		
	/*	$spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);*/
			
		// Rename worksheet
		$spreadsheet->getActiveSheet()->setTitle( lang('income_loss:explanation_balance_label') );
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
	
	private static function _get_summary_income_loss( $date, $annual = FALSE )
	{
		$get_summary  = self::ci()->income_loss_m->get_summary_income_loss( $date, $annual );
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
			PEND/BIAYA NON OPERASIONAL 	= -3748579415.15 (8)
			(-)
			Penyusutan, Amortasi & cadangan	= -1152170944.00 (7)
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
		$depreciation		= $ebitda - @$summary[7];
		$pac 				= @$summary[8]; #Penyusutan, Amortasi & cadangan
		$ebit 				= $ebitda - @$summary[7] - @$pac;
		$interest_taxes		= @$summary[10];
		$eat				= $ebit - @$summary[10];


		$output = array(
			4 				=> $income,
			5 				=> $hpp,
			'gross_profit' 	=> $gross_profit,
			6 				=> $operating_cost,
			'ebitda' 		=> $ebitda,
			8 				=> $pac, //Penyusutan, Amortasi & cadangan
			'depreciation'	=> $depreciation,
			7				=> $non_operating_cost,
			'ebit' 			=> $ebit,
			10				=> $interest_taxes,
			'eat'			=> $eat,
			'order'			=> array(4, 5, 'gross_profit', 6, 'ebitda', 7, 'depreciation', 8, 'ebit', 10, 'eat'),
		);
		
		return $output;
	}
	
	private static function _get_detail_account_income_loss( $date, $annual = FALSE, $show_zero_value = 1 )
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
			? self::ci()->db
				->query("
					SELECT levelke, Group_ID, Akun_No, AkunName, Induk, SUM(nilai) AS Nilai  
						FROM dbo.Penjelasan_LabaRugi_Grid('{$date_start}','{$date_end}',1,10,1,1,2011,1) 
						
						GROUP BY levelke, Group_ID, Akun_No, AkunName, Induk 
						ORDER BY Group_ID,AKUn_NO
				")

			: self::ci()->db
				->query("
					SELECT levelke, Mst_Akun.Group_ID, Mst_Akun.Akun_No, AkunName, Mst_Akun.Induk, SUM(laba_rugi.nilai) AS Nilai 
						FROM dbo.Penjelasan_LabaRugi_Grid('{$date_start}','{$date_end}',1,10,1,1,2011,1) laba_rugi 
						INNER JOIN Mst_Akun ON laba_rugi.Akun_No = Mst_Akun.Akun_NO 
						
						WHERE Mst_Akun.Induk = 1 OR nilai <> 0 
						GROUP BY levelke, Mst_Akun.Group_ID, Mst_Akun.Akun_No, AkunName, Mst_Akun.Induk  
						ORDER BY Mst_Akun.Group_ID, Mst_Akun.Akun_No
				");
		
		$collection = array();		
		if ($query->num_rows() > 0 ): foreach ( $query->result() as $row ):
			
			$collection[ $row->Group_ID ][ $row->Akun_No ] = $row;
			
		endforeach; endif;
		
		return $collection;

	}

	private static function _get_income_loss_account()
	{
		$query = self::ci()->db->where("Group_ID >=", 4)
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
		$parent_level = $child->levelke - 1;
		if( $parent_level == 0) return "#";
		
		$parent_digit = $concepts[ $parent_level ]->Jumlah_Digit;
		return (string) substr($child->Akun_No, 0, $parent_digit); // return parent Account Number
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
			'font'  => array(
				'bold'  => TRUE,
				'size'  => 10,
			),
		);

		$style['sum_value'] = array(
			'font'  => array(
				'bold'	=> TRUE,
				'size'  => 10,
			),
			'borders' => array(
				'top' => array(
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
