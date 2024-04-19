<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Borders;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

final class report_helper
{	
	public static function export_honor( $date_start, $date_end, $doctor_id = NULL )
	{
		$_ci = self::ci();
	
		$doctor = $_ci->supplier_model->get_by(['Kode_Supplier' => $doctor_id]);
		$collection = self::_get_honor( $date_start, $date_end, $doctor->Supplier_ID );	
		$date_start = DateTime::createFromFormat("Y-m-d", $date_start );
		$date_end = DateTime::createFromFormat("Y-m-d", $date_end );
		$file_name = sprintf('Laporan Detail Honor Dokter %s periode %s s/d %s ', !empty($doctor->Nama_Supplier) ? $doctor->Nama_Supplier : $section->SectionName, $date_start->format('d F Y'), $date_end->format('d F Y'));
		
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
				->setTitle( 'Laporan Rekap Honor Dokter' )
				->setSubject( 'Laporan Rekap Honor Dokter' )
				->setDescription( $file_name )
				->setKeywords( $file_name)
				;
		
		$_sheet = $spreadsheet->setActiveSheetIndex( 0 );
		
		// Rename worksheet
		$spreadsheet->getActiveSheet()->setTitle( 'Detail Periode' );
		// Default Style		
		$spreadsheet->getDefaultStyle()->applyFromArray( self::_get_style( 'default' ) );
		
		$_sheet->mergeCells("A1:J1");
		$_sheet->setCellValue('A1', "Laporan Detail Honor Dokter" );
		$_sheet->getStyle("A1")->applyFromArray( self::_get_style( 'header' ) );
		//$_sheet->getStyle("A1")->getAlignment()->setWrapText(true);
		//$_sheet->getRowDimension('1')->setRowHeight(30);

		$_sheet->mergeCells("A2:J2");
		$_sheet->setCellValue('A2', sprintf('%s Periode %s s/d %s ', !empty($doctor->Nama_Supplier) ? $doctor->Nama_Supplier : $section->SectionName, $date_start->format('d F Y'), $date_end->format('d F Y')));
		$_sheet->getStyle("A2")->applyFromArray( self::_get_style( 'header' ) );
		
		$tb_row  = 4;
		foreach($collection as $date => $group):
			$_sheet->mergeCells("A{$tb_row}:J{$tb_row}");
			$_sheet->setCellValue("A{$tb_row }", 'Tanggal: '. $date); 
			$tb_row++;
			
			$_sheet->setCellValue("A{$tb_row }", 'No'); 
			$_sheet->getStyle("A{$tb_row }")->applyFromArray( self::_get_style( 'thead' ) );
			$_sheet->setCellValue("B{$tb_row }", 'JK'); 
			$_sheet->getStyle("B{$tb_row }")->applyFromArray( self::_get_style( 'thead' ) );
			$_sheet->setCellValue("C{$tb_row }", 'NRM'); 
			$_sheet->getStyle("C{$tb_row }")->applyFromArray( self::_get_style( 'thead' ) );
			$_sheet->setCellValue("D{$tb_row }", 'Nama Pasien'); 
			$_sheet->getStyle("D{$tb_row }")->applyFromArray( self::_get_style( 'thead' ) ); 
			$_sheet->setCellValue("E{$tb_row }", 'Diagnonsa'); 
			$_sheet->getStyle("E{$tb_row }")->applyFromArray( self::_get_style( 'thead' ) ); 
			$_sheet->setCellValue("F{$tb_row }", 'Operator'); 
			$_sheet->getStyle("F{$tb_row }")->applyFromArray( self::_get_style( 'thead' ) ); 
			$_sheet->setCellValue("G{$tb_row }", 'Tindakan'); 
			$_sheet->getStyle("G{$tb_row }")->applyFromArray( self::_get_style( 'thead' ) ); 
			$_sheet->setCellValue("H{$tb_row }", 'Diskon'); 
			$_sheet->getStyle("H{$tb_row }")->applyFromArray( self::_get_style( 'thead' ) ); 
			$_sheet->setCellValue("I{$tb_row }", 'Keterangan Diskon'); 
			$_sheet->getStyle("I{$tb_row }")->applyFromArray( self::_get_style( 'thead' ) ); 
			$_sheet->setCellValue("J{$tb_row }", 'Tipe'); 
			$_sheet->getStyle("J{$tb_row }")->applyFromArray( self::_get_style( 'thead' ) ); 
			$tb_row ++;
	
			$sub_total = $sub_operator = $sub_tindakan = $sub_discount = 0; $no = 1;
			foreach ( $group as $val ):
				// NO,JK, NRM, NAmaPAsein, diagnosa, operator, Tindakan, NlaiDiskon, KeteranganDiskon		
				$_sheet->setCellValue("A{$tb_row}", $no++);
				$_sheet->getStyle("A{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
				$_sheet->setCellValue("B{$tb_row}", @$val->JenisKelamin);
				$_sheet->getStyle("B{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
				$_sheet->setCellValue("C{$tb_row}", @$val->NRM);
				$_sheet->getStyle("C{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
				$_sheet->setCellValue("D{$tb_row}", @$val->NamaPasien);
				$_sheet->getStyle("D{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
				$_sheet->setCellValue("E{$tb_row}", @$val->Diagnosa);
				$_sheet->getStyle("E{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
				$_sheet->setCellValue("F{$tb_row}", @$val->Operator);
				$_sheet->getStyle("F{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
				$_sheet->setCellValue("G{$tb_row}", @$val->Tindakan);
				$_sheet->getStyle("G{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
				$_sheet->setCellValue("H{$tb_row}", @$val->Discount);
				$_sheet->getStyle("H{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
				$_sheet->setCellValue("I{$tb_row}", @$val->KeteranganDiscount);
				$_sheet->getStyle("I{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
				$_sheet->setCellValue("J{$tb_row}", !empty($val->Nama_Customer) ? $val->Nama_Customer : $val->JenisPasien);
				$_sheet->getStyle("J{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
				$sub_operator += (float)@$val->Operator;
				$sub_tindakan += (float)@$val->Tindakan;
				$sub_discount += (float)@$val->Discount;
				$tb_row++;
			endforeach;
			$tb_row--;
			// add style currency to Valued Cell
			$_sheet->getStyle("F5:H{$tb_row}")->applyFromArray( self::_get_style( 'currency' ) );
			$tb_row++;
			
			// FOOTER
			// Total Pendapatan
			$_sheet->mergeCells("A{$tb_row}:E{$tb_row}");
			$_sheet->getStyle("A{$tb_row}:E{$tb_row}")->applyFromArray( self::_get_style( 'sum_name' ) );
			$_sheet->setCellValue("A{$tb_row}", 'TOTAL PENDAPATAN');
			$_sheet->getStyle("F{$tb_row}")->applyFromArray( self::_get_style( 'sum_value' ) );
			$_sheet->getStyle("F{$tb_row}")->applyFromArray( self::_get_style( 'currency' ) );
			$_sheet->setCellValue("F{$tb_row}", $sub_operator);
			$_sheet->getStyle("G{$tb_row}")->applyFromArray( self::_get_style( 'sum_value' ) );
			$_sheet->getStyle("G{$tb_row}")->applyFromArray( self::_get_style( 'currency' ) );
			$_sheet->setCellValue("G{$tb_row}", $sub_tindakan);
			$_sheet->getStyle("H{$tb_row}")->applyFromArray( self::_get_style( 'sum_value' ) );
			$_sheet->getStyle("H{$tb_row}")->applyFromArray( self::_get_style( 'currency' ) );
			$_sheet->setCellValue("H{$tb_row}", $sub_discount);
			$_sheet->getStyle("I{$tb_row}:J{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
			$tb_row++;
				
			// GRAND TOTAL
			$_sheet->mergeCells("A{$tb_row}:E{$tb_row}");
			$_sheet->getStyle("A{$tb_row}:E{$tb_row}")->applyFromArray( self::_get_style( 'sum_name' ) );
			$_sheet->setCellValue("A{$tb_row}", 'GRANDTOTAL (TOTAL PENDAPATAN - TOTAL DISKON)');
			$_sheet->mergeCells("F{$tb_row}:J{$tb_row}");
			$_sheet->getStyle("F{$tb_row}:J{$tb_row}")->applyFromArray( self::_get_style( 'sum_value' ) );
			$_sheet->getStyle("F{$tb_row}:J{$tb_row}")->applyFromArray( self::_get_style( 'currency' ) );
			$_sheet->setCellValue("F{$tb_row}", $sub_operator + $sub_tindakan - $sub_discount);		
			$tb_row += 2;
		endforeach;
		
		/*$spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);*/
		
		$_new_sheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Rekap');
		$spreadsheet->addSheet($_new_sheet, 1);
		$_sheet = $spreadsheet->setActiveSheetIndex( 1 );
		
		$_sheet->mergeCells("A1:J1");
		$_sheet->setCellValue('A1', "Laporan Rekap Honor Dokter" );
		$_sheet->getStyle("A1")->applyFromArray( self::_get_style( 'header' ) );
		$_sheet->mergeCells("A2:J2");
		$_sheet->setCellValue('A2', sprintf('%s Periode %s s/d %s ', !empty($doctor->Nama_Supplier) ? $doctor->Nama_Supplier : $section->SectionName, $date_start->format('d F Y'), $date_end->format('d F Y')));
		$_sheet->getStyle("A2")->applyFromArray( self::_get_style( 'header' ) );
		
		$tb_row  = 4;
		$_sheet->setCellValue("A{$tb_row }", 'Tanggal'); 
		$_sheet->getStyle("A{$tb_row }")->applyFromArray( self::_get_style( 'thead' ) );
		$_sheet->setCellValue("B{$tb_row }", 'Jumlah Pasien'); 
		$_sheet->getStyle("B{$tb_row }")->applyFromArray( self::_get_style( 'thead' ) );
		$_sheet->setCellValue("C{$tb_row }", 'Total'); 
		$_sheet->getStyle("C{$tb_row }")->applyFromArray( self::_get_style( 'thead' ) );
		$tb_row ++;
		
		$grand_total = 0;
		
		foreach($collection as $date => $group):
			
			$_sheet->setCellValue("A{$tb_row}", $date);
			$_sheet->getStyle("A{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
			
			$_patient = $_total = 0;
			foreach ( $group as $val ):
				$_patient++;
				$_total += @$val->Tindakan + @$val->Operator - @$val->Discount;
			endforeach;
			$_sheet->setCellValue("B{$tb_row}", @$_patient);
			$_sheet->getStyle("B{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
			$_sheet->setCellValue("C{$tb_row}", @$_total);
			$_sheet->getStyle("C{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );		
			$_sheet->getStyle("C{$tb_row}")->applyFromArray( self::_get_style( 'currency' ) );
			$grand_total += $_total;
			$tb_row++;
			
		endforeach;
		//$tb_row--;	

		// GRAND TOTAL
		$_sheet->mergeCells("A{$tb_row}:B{$tb_row}");
		$_sheet->getStyle("A{$tb_row}:B{$tb_row}")->applyFromArray( self::_get_style( 'sum_name' ) );
		$_sheet->setCellValue("A{$tb_row}", 'GRANDTOTAL');
		$_sheet->getStyle("C{$tb_row}")->applyFromArray( self::_get_style( 'sum_value' ) );
		$_sheet->getStyle("C{$tb_row}")->applyFromArray( self::_get_style( 'currency' ) );
		$_sheet->setCellValue("C{$tb_row}", $grand_total);		
		
		/*$spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);	*/
		
		
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
	
	private static function _get_honor( $date_start, $date_end, $doctor_id )
	{
		$_ci = self::ci();
	
		$date_start = DateTime::createFromFormat('Y-m-d', $date_start )->setTime(0, 0);
		$date_start->add(new DateInterval('PT8H'));
		$date_end = DateTime::createFromFormat('Y-m-d', $date_end )->setTime(0, 0);
		$date_end->add(new DateInterval('P1DT8H'));
		
		$_ci->load->model("patient_model");
		$_ci->load->model("cashier_discount_model");
		$_ci->load->model("discount_model");
		$_ci->load->model("poly_model");
		$_ci->load->model("poly_diagnosis_model");
		$_ci->load->model("icd_model");
		
		$query = self::ci()->db->select("a.*, b.JenisKelamin, b.JenisPasien, b.Nama_Customer")
							->from("{$_ci->audit_detail_ap_model->table} a")
							->join("VW_Registrasi b", "a.NoReg = b.NoReg", "INNER")
							->join("{$_ci->audit_model->table} c", "a.NoBukti = c.NoBukti", "INNER")
							->where(["TglClosing >=" => $date_start->format('Y-m-d H:i:s'), "TglClosing <=" => $date_end->format('Y-m-d H:i:s'), "Supplier_ID" => $doctor_id])
							->where("c.Batal", 0)
							->order_by("TglClosing")
							->get()
							->result();
		$collection = [];
		foreach($query as $row)	:
			$row->TglClosing = DateTime::createFromFormat('Y-m-d H:i:s.u', $row->TglClosing)->modify('-8 hours')->format('d F, Y');
			
			if(empty($collection[$row->TglClosing][$row->NoReg])):
				$collection[$row->TglClosing][$row->NoReg] = (object) [
					'JenisKelamin' => $row->JenisKelamin == 'F' ? 'P' : 'L',
					'NRM' => $row->NRM,
					'NamaPasien' => $row->NamaPasien,
					$row->KomponenName => $row->Tarif,
					'Discount' => $row->Discount,
					'JenisPasien' => $row->JenisPasien,
					'Nama_Customer' => $row->Nama_Customer,
				];
				
				$NoBuktiTransaksi = explode("#", $row->NoBuktiTransaksi);
				$NoInvoice = $NoBuktiTransaksi[0];
				
				# Diskon
				$_get_discount = $_ci->db
									->select("b.NamaDiscount, a.*")
									->from("{$_ci->cashier_discount_model->table} a")
									->join("{$_ci->discount_model->table} b", "a.IDDiscount = b.IDDiscount", "INNER")
									->where("NoBukti", $NoInvoice)
									->group_start()
										->where_in("b.KomponenBiayaID", ['DT01', 'DT02'])
										->or_where('a.IDDiscount', 'DSC67')
									->group_end()
									->get()
									->result();
				$_discount = [];
				foreach($_get_discount as $dis):
					if($dis->IDDiscount == 'DSC67')
					{
						$_discount[] = sprintf("%s (%s)", $dis->NamaDiscount, numb_format($row->Tarif * $dis->Persen / 100, 0));
					}else {
						$_discount[] = sprintf("%s (%s)", $dis->NamaDiscount, numb_format($dis->NilaiDiscount, 0));
					}
				endforeach;
				$collection[$row->TglClosing][$row->NoReg]->KeteranganDiscount = implode(', ', $_discount);
				
				# Diagnosa
				$_get_diagnosis = $_ci->db
									->select("c.Descriptions")
									->from("{$_ci->poly_model->table} a")
									->join("{$_ci->poly_diagnosis_model->table} b", "a.NoBukti = b.NOBukti", "INNER")
									->join("{$_ci->icd_model->table} c", "b.KodeICD = c.KodeICD", "INNER")
									->where("a.RegNo", $row->NoReg)
									->get()
									->result();
									
				$_diagnosis = [];					
				foreach($_get_diagnosis as $dia):
					$_diagnosis[] = $dia->Descriptions;
				endforeach;
				$collection[$row->TglClosing][$row->NoReg]->Diagnosa = implode(', ', $_diagnosis);
			else:
				$collection[$row->TglClosing][$row->NoReg]->{$row->KomponenName} = (float) @$collection[$row->TglClosing][$row->NoReg]->{$row->KomponenName} + $row->Tarif;
				$collection[$row->TglClosing][$row->NoReg]->Discount = (float) @$collection[$row->TglClosing][$row->NoReg]->Discount + $row->Discount;
			endif;
			
		endforeach;
		
		// JK, NRM, NAmaPAsein, diagnosa, operator, Tindakan, NlaiDiskon, KeteranganDiskon	
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
