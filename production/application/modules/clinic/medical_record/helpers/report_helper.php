<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

final class report_helper
{
	private static $user_auth;
	private static $_ci;

	public static function init()
	{
		self::$_ci = $_ci = self::ci();

		$_ci->load->library('simple_login');
		self::$user_auth = $_ci->simple_login->get_user();
	}

	public static function get_registration_patient_types( $date_start, $date_end, $patient_type, $section )
	{
			$_ci = self::ci();
			$_ci->load->model([
				'registration_model',
				'patient_type_model',
				'patient_model',
				'section_model'
			]);

			if (!is_null($patient_type) && !empty($patient_type)){ 
				$_ci->db->where("a.JenisKerjasamaID", $patient_type); 
			}	
			if (!is_null($section) && !empty($section)){ 
				$_ci->db->where("a.SectionID", $section); 
			}	

			$query = $_ci->db
					->select(
						"a.NoReg,
						a.TglReg,
						a.PenanggungNama,
						b.JenisKerjasama,
						c.NRM,
						c.NamaPasien,
						c.JenisKelamin,
						c.TglLahir,
						d.SectionName
						"
					)
					->from( "{$_ci->registration_model->table} a" )
					->join( "{$_ci->patient_type_model->table} b", "a.JenisKerjasamaID = b.JenisKerjasamaID", "INNER" )
					->join( "{$_ci->patient_model->table} c", "a.NRM = c.NRM", "INNER" )
					->join( "{$_ci->section_model->table} d", "a.SectionID = d.SectionID", "INNER" )
					->where([
						"a.Batal" => 0,
						"TglReg >=" => $date_start,
						"TglReg <=" => $date_end,
					])
					->order_by('a.TglReg','ASC')
					->get()
					;

			$collection = [];
			// collection data
			if ( $query->num_rows() > 0 )
			{
				foreach( $query->result() as $row )
				{	
					$collection[ $row->JenisKerjasama ][] = (object) [
						'NoReg' => $row->NoReg,
						'TglReg' => date('d/m/Y', strtotime($row->TglReg)),
						'PenanggungNama' => $row->PenanggungNama,
						'JenisKerjasama' => $row->JenisKerjasama,
						'NRM' => $row->NRM,
						'NamaPasien' => $row->NamaPasien,
						'JenisKelamin' => ($row->JenisKelamin == 'M') ? 'Laki-Laki' : 'Perempuan',
						'TglLahir' => $row->TglLahir,
						'SectionName' => $row->SectionName
					];
				}
				
				return $collection;
			}

			return FALSE;
		
	}

	public static function export_excel_registration_patient_types(  $date_start, $date_end, $patient_type, $section)
	{
		$_ci = self::ci();
		$_ci->load->model([
			'section_model'
		]);

		$collection = self::get_registration_patient_types( $date_start, $date_end, $patient_type, $section);	

		$section = $_ci->section_model->get_one( $section );
		$date_start = DateTime::createFromFormat("Y-m-d", $date_start );
		$date_end = DateTime::createFromFormat("Y-m-d", $date_end );
		$file_name = sprintf('%s periode %s s/d %s ', 'Laporan Registrasi Pasien', $date_start->format('d F Y'), $date_end->format('d F Y'));
		
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
				->setTitle( 'Laporan Registrasi Pasien' )
				->setSubject( 'Laporan Registrasi Pasien' )
				->setDescription( $file_name )
				->setKeywords( $file_name)
				;
		
		$_sheet = $spreadsheet->setActiveSheetIndex( 0 );
		
		// Default Style
		
		$spreadsheet->getDefaultStyle()->applyFromArray( self::_get_style( 'default' ) );
		
		$_sheet->mergeCells("A1:G1");
		$_sheet->setCellValue('A1', $file_name );
		$_sheet->getStyle("A1")->applyFromArray( self::_get_style( 'header' ) );
		$_sheet->getStyle("A1")->getAlignment()->setWrapText(true);
		//$_sheet->getRowDimension('1')->setRowHeight(30);

		$tb_row = 3; $grandtotal = 0;
		foreach ( $collection as $key => $value ):
			
			$_sheet->mergeCells("A{$tb_row}:G{$tb_row}");
			$_sheet->setCellValue("A{$tb_row}", $key); 
			$_sheet->getStyle("A{$tb_row}")->applyFromArray(['font'  => ['bold'	=> TRUE, 'size'  => 11 ]]);
			$tb_row++;
			
			$_sheet->setCellValue("A{$tb_row}", 'TGL REG'); 
			$_sheet->getStyle("A{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) );
			$_sheet->setCellValue("B{$tb_row}", 'NO REGISTRASI'); 
			$_sheet->getStyle("B{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) );
			$_sheet->setCellValue("C{$tb_row}", 'NRM'); 
			$_sheet->getStyle("C{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) ); 
			$_sheet->setCellValue("D{$tb_row}", 'NAMA PASIEN'); 
			$_sheet->getStyle("D{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) ); 
			$_sheet->setCellValue("E{$tb_row}", 'JENIS KELAMIN'); 
			$_sheet->getStyle("E{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) ); 
			$_sheet->setCellValue("F{$tb_row}", 'TGL LAHIR'); 
			$_sheet->getStyle("F{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) ); 
			$_sheet->setCellValue("G{$tb_row}", 'JENIS KERJASAMA'); 
			$_sheet->getStyle("G{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) );
			$_sheet->setCellValue("H{$tb_row}", 'SECTION'); 
			$_sheet->getStyle("H{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) );
			$tb_row++;
			
			$sub_count = 0;;
				foreach($value as  $row):
					$sub_count += count($row->TglReg);
					$_sheet->setCellValue("A{$tb_row}", @$row->TglReg);
					$_sheet->getStyle("A{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
					$_sheet->setCellValue("B{$tb_row}", @$row->NoReg);
					$_sheet->getStyle("B{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
					$_sheet->setCellValue("C{$tb_row}", @$row->NRM);
					$_sheet->getStyle("C{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
					$_sheet->setCellValue("D{$tb_row}",  @$row->NamaPasien);
					$_sheet->getStyle("D{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
					$_sheet->setCellValue("E{$tb_row}", @$row->JenisKelamin);
					$_sheet->getStyle("E{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
					$_sheet->setCellValue("F{$tb_row}", date('d/m/Y', strtotime(@$row->TglLahir)));
					$_sheet->getStyle("F{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
					$_sheet->setCellValue("G{$tb_row}",  @$row->JenisKerjasama);
					$_sheet->getStyle("G{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
					$_sheet->setCellValue("H{$tb_row}", @$row->SectionName);
					$_sheet->getStyle("H{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
					
					$_sheet->getStyle("E{$tb_row}:H{$tb_row}")->applyFromArray( self::_get_style( 'currency' ) );
					$tb_row++;
				endforeach;
			
			$grandtotal += $sub_count;

			$_sheet->mergeCells("A{$tb_row}:G{$tb_row}");
			$_sheet->setCellValue("A{$tb_row}", 'JUMLAH');
			$_sheet->setCellValue("H{$tb_row}", $sub_count);
			$_sheet->getStyle("A{$tb_row}:H{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
			$_sheet->getStyle("A{$tb_row}")->applyFromArray(['font'  => ['bold'	=> TRUE, 'size'  => 10 ]]);
			$tb_row++;
			$tb_row++;

		endforeach;
		$_sheet->mergeCells("A{$tb_row}:G{$tb_row}");
		$_sheet->setCellValue("A{$tb_row}", 'TOTAL PASIEN');
		$_sheet->getStyle("A{$tb_row}:H{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
		$_sheet->getStyle("A{$tb_row}")->applyFromArray(['font'  => ['bold'	=> TRUE, 'size'  => 10 ]]);
		$_sheet->setCellValue("H{$tb_row}", $grandtotal);
		$tb_row += 2;

	
		
		/*$spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);*/
					
		// Rename worksheet
		$spreadsheet->getActiveSheet()->setTitle( 'LAPORAN REGISTRASI PASIEN' );
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

	public static function get_most_diseases( $date_start, $date_end )
	{
			$_ci = self::ci();
			$collection = $_ci->db->query("EXEC KlinikBMC_SIMRptPenyakitTerbayak '{$date_start}','{$date_end}'");
			// collection data
			if ( $collection->num_rows() > 0 )
			{
				return $collection->result();
			}

			return FALSE;
		
	}

	public static function export_excel_most_diseases(  $date_start, $date_end)
	{
		$_ci = self::ci();
		$_ci->load->model([
			'section_model'
		]);

		$collection = self::get_most_diseases( $date_start, $date_end);	

		$date_start = DateTime::createFromFormat("Y-m-d", $date_start );
		$date_end = DateTime::createFromFormat("Y-m-d", $date_end );
		$file_name = sprintf('%s periode %s s/d %s ', 'Laporan 10 Besar Penyakit', $date_start->format('d F Y'), $date_end->format('d F Y'));
		
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
				->setTitle( 'Laporan 10 Besar Penyakit' )
				->setSubject( 'Laporan 10 Besar Penyakit' )
				->setDescription( $file_name )
				->setKeywords( $file_name)
				;
		
		$_sheet = $spreadsheet->setActiveSheetIndex( 0 );
		
		// Default Style
		
		$spreadsheet->getDefaultStyle()->applyFromArray( self::_get_style( 'default' ) );
		
		$_sheet->mergeCells("A1:G1");
		$_sheet->setCellValue('A1', $file_name );
		$_sheet->getStyle("A1")->applyFromArray( self::_get_style( 'header' ) );
		$_sheet->getStyle("A1")->getAlignment()->setWrapText(true);
		//$_sheet->getRowDimension('1')->setRowHeight(30);

		$tb_row = 3; $no = 1;
			$_sheet->setCellValue("A{$tb_row}", 'NO'); 
			$_sheet->getStyle("A{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) );
			$_sheet->setCellValue("B{$tb_row}", 'KODE ICD'); 
			$_sheet->getStyle("B{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) );
			$_sheet->setCellValue("C{$tb_row}", 'DIAGNOSA'); 
			$_sheet->getStyle("C{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) ); 
			$_sheet->setCellValue("D{$tb_row}", 'JUMLAH'); 
			$_sheet->getStyle("D{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) ); 
			$tb_row++;
			
				if(!empty($collection)) : foreach($collection as  $row):
					$_sheet->setCellValue("A{$tb_row}", @$no++);
					$_sheet->getStyle("A{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
					$_sheet->setCellValue("B{$tb_row}", @$row->KodeICD);
					$_sheet->getStyle("B{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
					$_sheet->setCellValue("C{$tb_row}", @$row->Descriptions);
					$_sheet->getStyle("C{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
					$_sheet->setCellValue("D{$tb_row}",  @$row->Jumlah);
					$_sheet->getStyle("D{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
					
					$tb_row++;
				endforeach; endif;

		
		/*$spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);*/
					
		// Rename worksheet
		$spreadsheet->getActiveSheet()->setTitle( 'LAPORAN 10 BESAR PENYAKIT' );
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

	public static function get_most_diseases_index( $date_start, $date_end, $section )
	{
			$_ci = self::ci();
			$collection = $_ci->db->query("EXEC KlinikIndexPenyakitRawatJalan '{$date_start}','{$date_end}','{$section}'");
			// collection data
			if ( $collection->num_rows() > 0 )
			{
				return $collection->result();
			}

			return FALSE;
		
	}

	public static function export_excel_most_diseases_index(  $date_start, $date_end, $section, $report_title)
	{
		$_ci = self::ci();
		$_ci->load->model([
			'section_model'
		]);

		$collection = self::get_most_diseases_index( $date_start, $date_end, $section);	

		$date_start = DateTime::createFromFormat("Y-m-d", $date_start );
		$date_end = DateTime::createFromFormat("Y-m-d", $date_end );
		$file_name = sprintf('%s periode %s s/d %s ', $report_title, $date_start->format('d F Y'), $date_end->format('d F Y'));
		
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
				->setTitle( $report_title )
				->setSubject( $report_title)
				->setDescription( $file_name )
				->setKeywords( $file_name)
				;
		
		$_sheet = $spreadsheet->setActiveSheetIndex( 0 );
		
		// Default Style
		
		$spreadsheet->getDefaultStyle()->applyFromArray( self::_get_style( 'default' ) );
		
		$_sheet->mergeCells("A1:R1");
		$_sheet->setCellValue('A1', config_item("company_name"));
		$_sheet->getStyle("A1")->applyFromArray( self::_get_style( 'header' ) );
		$_sheet->getStyle("A1")->getAlignment()->setWrapText(true);

		$_sheet->mergeCells("A2:R2");
		$_sheet->setCellValue('A2', $file_name );
		$_sheet->getStyle("A2")->applyFromArray( self::_get_style( 'header' ) );
		$_sheet->getStyle("A2")->getAlignment()->setWrapText(true);
		//$_sheet->getRowDimension('1')->setRowHeight(30);

		$tb_row = 3;$tb_row2 = 4; $no = 1;
			$_sheet->mergeCells("A{$tb_row}:A{$tb_row2}");
			$_sheet->setCellValue("A{$tb_row}", 'NO'); 
			$_sheet->getStyle("A{$tb_row}:A{$tb_row2}")->applyFromArray( self::_get_style( 'thead' ) );
			$_sheet->mergeCells("B{$tb_row}:B{$tb_row2}");
			$_sheet->setCellValue("B{$tb_row}", 'KODE ICD'); 
			$_sheet->getStyle("B{$tb_row}:B{$tb_row2}")->applyFromArray( self::_get_style( 'thead' ) );
			$_sheet->mergeCells("C{$tb_row}:C{$tb_row2}");
			$_sheet->setCellValue("C{$tb_row}", 'NAMA ICD'); 
			$_sheet->getStyle("C{$tb_row}:C{$tb_row2}")->applyFromArray( self::_get_style( 'thead' ) ); 
			$_sheet->mergeCells("D{$tb_row}:O{$tb_row}");
			$_sheet->setCellValue("D{$tb_row}", 'UMUR'); 
			$_sheet->getStyle("D{$tb_row}:O{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) ); 
			
			$tb_row++;
			$_sheet->setCellValue("D{$tb_row}", '0 - 7'); 
			$_sheet->getStyle("D{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) );
			$_sheet->setCellValue("E{$tb_row}", '8 - 28'); 
			$_sheet->getStyle("E{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) );
			$_sheet->setCellValue("F{$tb_row}", '< 1'); 
			$_sheet->getStyle("F{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) );
			$_sheet->setCellValue("G{$tb_row}", '1 - 4'); 
			$_sheet->getStyle("G{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) );
			$_sheet->setCellValue("H{$tb_row}", '5 - 9'); 
			$_sheet->getStyle("H{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) );
			$_sheet->setCellValue("I{$tb_row}", '10 - 14'); 
			$_sheet->getStyle("I{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) );
			$_sheet->setCellValue("J{$tb_row}", '15 - 19'); 
			$_sheet->getStyle("J{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) );
			$_sheet->setCellValue("K{$tb_row}", '20 - 44'); 
			$_sheet->getStyle("K{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) );
			$_sheet->setCellValue("L{$tb_row}", '45 - 54'); 
			$_sheet->getStyle("L{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) );
			$_sheet->setCellValue("M{$tb_row}", '55 - 59'); 
			$_sheet->getStyle("M{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) );
			$_sheet->setCellValue("N{$tb_row}", '60 - 69'); 
			$_sheet->getStyle("N{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) );
			$_sheet->setCellValue("O{$tb_row}", '70'); 
			$_sheet->getStyle("O{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) );

			$_sheet->mergeCells("P3:P{$tb_row2}");
			$_sheet->setCellValue("P3", 'LAKI-LAKI'); 
			$_sheet->getStyle("P3:P{$tb_row2}")->applyFromArray( self::_get_style( 'thead' ) ); 
			$_sheet->mergeCells("Q3:Q{$tb_row2}");
			$_sheet->setCellValue("Q3", 'PEREMPUAN'); 
			$_sheet->getStyle("Q3:Q{$tb_row2}")->applyFromArray( self::_get_style( 'thead' ) ); 
			$_sheet->mergeCells("R3:R{$tb_row2}");
			$_sheet->setCellValue("R3", 'JUMLAH'); 
			$_sheet->getStyle("R3:R{$tb_row2}")->applyFromArray( self::_get_style( 'thead' ) ); 
			$tb_row++;
			
				if(!empty($collection)) : foreach($collection as  $row):
					$_sheet->setCellValue("A{$tb_row}", @$no++);
					$_sheet->getStyle("A{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
					$_sheet->setCellValue("B{$tb_row}", @$row->KodeICD);
					$_sheet->getStyle("B{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
					$_sheet->setCellValue("C{$tb_row}", @$row->Descriptions);
					$_sheet->getStyle("C{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
					$_sheet->setCellValue("D{$tb_row}",  @$row->K_1);
					$_sheet->getStyle("D{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
					$_sheet->setCellValue("E{$tb_row}",  @$row->K_2);
					$_sheet->getStyle("E{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
					$_sheet->setCellValue("F{$tb_row}",  @$row->K_3);
					$_sheet->getStyle("F{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
					$_sheet->setCellValue("G{$tb_row}",  @$row->K_4);
					$_sheet->getStyle("G{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
					$_sheet->setCellValue("H{$tb_row}",  @$row->K_5);
					$_sheet->getStyle("H{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
					$_sheet->setCellValue("I{$tb_row}",  @$row->K_6);
					$_sheet->getStyle("I{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
					$_sheet->setCellValue("J{$tb_row}",  @$row->K_7);
					$_sheet->getStyle("J{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
					$_sheet->setCellValue("K{$tb_row}",  @$row->K_8);
					$_sheet->getStyle("K{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
					$_sheet->setCellValue("L{$tb_row}",  @$row->K_9);
					$_sheet->getStyle("L{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
					$_sheet->setCellValue("M{$tb_row}",  @$row->K_10);
					$_sheet->getStyle("M{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
					$_sheet->setCellValue("N{$tb_row}",  @$row->K_11);
					$_sheet->getStyle("N{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
					$_sheet->setCellValue("O{$tb_row}",  @$row->K_12);
					$_sheet->getStyle("O{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
					$_sheet->setCellValue("P{$tb_row}",  @$row->M);
					$_sheet->getStyle("P{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
					$_sheet->setCellValue("Q{$tb_row}",  @$row->F);
					$_sheet->getStyle("Q{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
					$_sheet->setCellValue("R{$tb_row}",  (@$row->M + @$row->F));
					$_sheet->getStyle("R{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );

					$tb_row++;
				endforeach; endif;

		
		/*$spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);*/
					
		// Rename worksheet
		$spreadsheet->getActiveSheet()->setTitle("LAPORAN PENYAKIT TERBANYAK RJ");
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


	public static function widget_total_patient()
	{
		self::init();
		$_ci = self::ci();

		return (int) @$_ci->db->count_all_results('mPasien');
	}

	public static function widget_total_visite()
	{
		self::init();
		$_ci = self::ci();

		return (int) @$_ci->db
			->where(['StatusBayar' => 'Sudah Bayar', 'TglReg' => date('Y-m-d')])
			->count_all_results('SIMtrRegistrasi');
	}

	public static function widget_total_drug()
	{
		self::init();
		$_ci = self::ci();

		return (int) @$_ci->db
			->where(['ClosePayment' => 1, 'Tanggal' => date('Y-m-d')])
			->count_all_results('BILLFarmasi');
	}

	public static function widget_total_receipt()
	{
		self::init();
		$_ci = self::ci();

		return (int) @$_ci->db
			->where(['Status_Batal' => 0, 'Tgl_Penerimaan >=' => date('Y-m-01'), 'Tgl_Penerimaan <=' => date('Y-m-t')])
			->count_all_results('BL_trPenerimaan');
	}

	public static function get_monthly_section_visit($month = NULL)
	{
		self::init();
		$_ci = self::ci();
		$collection = [];
		$month = $month ? $month : date('Y-m');
		$date = DateTime::createFromFormat('Y-m', $month);
		$section = $_ci->db->from('SIMmSection')
			->where(['StatusAktif' => 1])
			->group_start()
			->or_where([
				'TipePelayanan' => 'RJ',
				'TipePelayanan ' => 'PENUNJANG'
			])
			->group_end()
			->get()->result();

		$db_select = <<<EOSQL
			datepart(DAY,a.Tanggal) AS Hari, 
			COUNT(a.NoBukti) AS JumlahKunjungan, 
			b.SectionName
EOSQL;

		$query = $_ci->db
			->select($db_select)
			->from("SIMtrKasir a")
			->join("SImmSection b", "a.SectionPerawatanID = b.SectionID", "INNER")
			->where(['a.Tanggal >=' => $date->format('Y-m-01'), 'a.Tanggal <=' => $date->format('Y-m-t 23:59:59')])
			->group_by("datepart(DAY,a.Tanggal), b.SectionName")
			->order_by("Hari")
			->get();

		$categories = array_map(function ($row) {
			return 0;
		}, array_flip(range(1, $date->format('t'))));
		$collection['categories'] = array_keys($categories);

		$series = [];
		foreach ($section as $sec) :
			$series[$sec->SectionName] = [
				'name' => $sec->SectionName,
				'data' => $categories
			];
		endforeach;

		foreach ($query->result() as $row) :
			$series[$row->SectionName]['data'][$row->Hari] = $row->JumlahKunjungan;
		endforeach;

		foreach ($series as $row) :
			$row['data'] = array_values($row['data']);
			$collection['series'][] = $row;
		endforeach;

		return $collection;
	}

	public static function get_yearly_section_visit($year = NULL)
	{
		self::init();
		$_ci = self::ci();
		$collection = [];
		$year = $year ? $year : date('Y');
		$date = DateTime::createFromFormat('Y', $year);
		$section = $_ci->db->from('SIMmSection')
			->where(['StatusAktif' => 1])
			->group_start()
			->or_where([
				'TipePelayanan' => 'RJ',
				'TipePelayanan ' => 'PENUNJANG'
			])
			->group_end()
			->get()->result();

		$db_select = <<<EOSQL
			datepart(MONTH, a.Tanggal) AS Bulan, 
			COUNT(a.NoBukti) AS JumlahKunjungan, 
			b.SectionName
EOSQL;

		$query = $_ci->db
			->select($db_select)
			->from("SIMtrKasir a")
			->join("SImmSection b", "a.SectionPerawatanID = b.SectionID", "INNER")
			->where(['a.Tanggal >=' => $date->format('Y-01-01'), 'a.Tanggal <=' => $date->format('Y-12-31 23:59:59')])
			->group_by("datepart(MONTH, a.Tanggal), b.SectionName")
			->order_by("Bulan")
			->get();

		$categories = array_map(function ($row) {
			return 0;
		}, array_flip(range(1, $date->format('12'))));
		$collection['categories'] = array_keys($categories);

		$series = [];
		foreach ($section as $sec) :
			$series[$sec->SectionName] = [
				'name' => $sec->SectionName,
				'data' => $categories
			];
		endforeach;

		foreach ($query->result() as $row) :
			$series[$row->SectionName]['data'][$row->Bulan] = $row->JumlahKunjungan;
		endforeach;

		foreach ($series as $row) :
			$row['data'] = array_values($row['data']);
			$collection['series'][] = $row;
		endforeach;

		return $collection;
	}

	public static function get_monthly_type_visit($month = NULL)
	{
		self::init();
		$_ci = self::ci();
		$collection = [];
		$month = $month ? $month : date('Y-m');
		$date = DateTime::createFromFormat('Y-m', $month);
		$type = $_ci->db->order_by('JenisKerjasama')->get('SIMmJenisKerjasama')->result();

		$db_select = <<<EOSQL
			datepart(DAY,a.Tanggal) AS Hari, 
			COUNT(a.NoBukti) AS JumlahKunjungan, 
			c.JenisKerjasama
EOSQL;

		$query = $_ci->db
			->select($db_select)
			->from("SIMtrKasir a")
			->join("SIMtrRegistrasi b", "a.NoReg = b.NoReg", "INNER")
			->join("SIMmJenisKerjasama c", "b.JenisKerjasamaID = c.JenisKerjasamaID", "INNER")
			->where(['a.Tanggal >=' => $date->format('Y-m-01'), 'a.Tanggal <=' => $date->format('Y-m-t 23:59:59')])
			->group_by("datepart(DAY,a.Tanggal), c.JenisKerjasama")
			->order_by("Hari")
			->get();

		$categories = array_map(function ($row) {
			return 0;
		}, array_flip(range(1, $date->format('t'))));
		$collection['categories'] = array_keys($categories);

		$series = [];
		foreach ($type as $tp) :
			$series[$tp->JenisKerjasama] = [
				'name' => $tp->JenisKerjasama,
				'data' => $categories
			];
		endforeach;

		foreach ($query->result() as $row) :
			$series[$row->JenisKerjasama]['data'][$row->Hari] = $row->JumlahKunjungan;
		endforeach;

		foreach ($series as $row) :
			$row['data'] = array_values($row['data']);
			$collection['series'][] = $row;
		endforeach;

		return $collection;
	}

	public static function get_yearly_type_visit($year = NULL)
	{
		self::init();
		$_ci = self::ci();
		$collection = [];
		$year = $year ? $year : date('Y');
		$date = DateTime::createFromFormat('Y', $year);
		$type = $_ci->db->order_by('JenisKerjasama')->get('SIMmJenisKerjasama')->result();

		$db_select = <<<EOSQL
			datepart(MONTH, a.Tanggal) AS Bulan, 
			COUNT(a.NoBukti) AS JumlahKunjungan, 
			c.JenisKerjasama
EOSQL;

		$query = $_ci->db
			->select($db_select)
			->from("SIMtrKasir a")
			->join("SIMtrRegistrasi b", "a.NoReg = b.NoReg", "INNER")
			->join("SIMmJenisKerjasama c", "b.JenisKerjasamaID = c.JenisKerjasamaID", "INNER")
			->where(['a.Tanggal >=' => $date->format('Y-01-01'), 'a.Tanggal <=' => $date->format('Y-12-31 23:59:59')])
			->group_by("datepart(MONTH, a.Tanggal), c.JenisKerjasama")
			->order_by("Bulan")
			->get();

		$categories = array_map(function ($row) {
			return 0;
		}, array_flip(range(1, $date->format('12'))));
		$collection['categories'] = array_keys($categories);

		$series = [];
		foreach ($type as $tp) :
			$series[$tp->JenisKerjasama] = [
				'name' => $tp->JenisKerjasama,
				'data' => $categories
			];
		endforeach;

		foreach ($query->result() as $row) :
			$series[$row->JenisKerjasama]['data'][$row->Bulan] = $row->JumlahKunjungan;
		endforeach;

		foreach ($series as $row) :
			$row['data'] = array_values($row['data']);
			$collection['series'][] = $row;
		endforeach;

		return $collection;
	}

	public static function export_all_patient()
	{
		set_time_limit(0);
		$_ci = self::ci();
		$collection = $_ci->db->get('mPasien')->result();

		$file_name = sprintf('Data seluruh pasien sampai dengan %s', date('d-M-Y'));

		$helper = new Sample();
		if ($helper->isCli()) {
			$helper->log('403. Forbidden Access!' . PHP_EOL);
			return false;
		}

		$agama = [
			'BD' => 'BUDHA',
			'HD' => 'HINDU',
			'IS' => 'ISLAM',
			'KC' => 'KONGHUCU',
			'KR' => 'KRISTEN',
			'KT' => 'KHATOLIK',
			'LL' => '-',
			'' => '-',
		];

		// Create new Spreadsheet object
		$spreadsheet = new Spreadsheet();

		// Set document properties
		$spreadsheet->getProperties()->setCreator(config_item("company_name"))
			->setLastModifiedBy(config_item("company_name"))
			->setTitle('Data seluruh pasien')
			->setSubject('Data seluruh pasien')
			->setDescription($file_name)
			->setKeywords($file_name);

		$_sheet = $spreadsheet->setActiveSheetIndex(0);

		// Default Style

		$_sheet->setCellValue('A1', $file_name);
		$_sheet->getStyle("A1")->applyFromArray(self::_get_style('header'));
		$_sheet->mergeCells("A1:Q2");

		//header title
		$_sheet->setCellValue('A3', 'NRM');
		$_sheet->getStyle("A3")->applyFromArray(self::_get_style('thead'));
		$_sheet->setCellValue('B3', 'No Kartu');
		$_sheet->getStyle("B3")->applyFromArray(self::_get_style('thead'));
		$_sheet->setCellValue('C3', 'Nama Pasien');
		$_sheet->getStyle("C3")->applyFromArray(self::_get_style('thead'));
		$_sheet->setCellValue('D3', 'Jenis Kelamin');
		$_sheet->getStyle("D3")->applyFromArray(self::_get_style('thead'));
		$_sheet->setCellValue('E3', 'Agama');
		$_sheet->getStyle("E3")->applyFromArray(self::_get_style('thead'));
		$_sheet->setCellValue('F3', 'Tanggal lahir');
		$_sheet->getStyle("F3")->applyFromArray(self::_get_style('thead'));
		$_sheet->setCellValue('G3', 'KTP');
		$_sheet->getStyle("G3")->applyFromArray(self::_get_style('thead'));
		$_sheet->setCellValue('H3', 'Tempat lahir');
		$_sheet->getStyle("H3")->applyFromArray(self::_get_style('thead'));
		$_sheet->setCellValue('I3', 'Alamat');
		$_sheet->getStyle("I3")->applyFromArray(self::_get_style('thead'));
		$_sheet->setCellValue('J3', 'Phone');
		$_sheet->getStyle("J3")->applyFromArray(self::_get_style('thead'));
		$_sheet->setCellValue('K3', 'Email');
		$_sheet->getStyle("K3")->applyFromArray(self::_get_style('thead'));
		$_sheet->setCellValue('L3', 'Pekerjaan');
		$_sheet->getStyle("L3")->applyFromArray(self::_get_style('thead'));
		$_sheet->setCellValue('M3', 'Nama penanggung');
		$_sheet->getStyle("M3")->applyFromArray(self::_get_style('thead'));
		$_sheet->setCellValue('N3', 'Alamat penanggung');
		$_sheet->getStyle("N3")->applyFromArray(self::_get_style('thead'));
		$_sheet->setCellValue('O3', 'KTP penanggung');
		$_sheet->getStyle("O3")->applyFromArray(self::_get_style('thead'));
		$_sheet->setCellValue('P3', 'Phone penanggung');
		$_sheet->getStyle("P3")->applyFromArray(self::_get_style('thead'));
		$_sheet->setCellValue('Q3', 'Hubungan penanggung');
		$_sheet->getStyle("Q3")->applyFromArray(self::_get_style('thead'));

		$row = 4;

		foreach ($collection as $key => $value) {
			$value->Agama = empty($value->Agama) ? '' : $value->Agama;

			$_sheet->setCellValue("A{$row}", $value->NRM);
			$_sheet->setCellValue("B{$row}", empty($value->NoKartu) ? '-' : $value->NoKartu);
			$_sheet->setCellValue("C{$row}", $value->NamaPasien);
			$_sheet->setCellValue("D{$row}", $value->JenisKelamin == 'F' ? 'Perempuan' : 'Laki-laki');
			$_sheet->setCellValue("E{$row}", $agama[$value->Agama]);
			$_sheet->setCellValue("F{$row}", date('Y-m-d', strtotime($value->TglLahir)));
			$_sheet->setCellValue("G{$row}", empty($value->NoKartu) ? '-' : $value->NoKartu);
			$_sheet->setCellValue("H{$row}", empty($value->TempatLahir) ? '-' : $value->TempatLahir);
			$_sheet->setCellValue("I{$row}", empty($value->Alamat) ? '-' : $value->Alamat);
			$_sheet->setCellValue("J{$row}", empty($value->Phone) ? '-' : $value->Phone);
			$_sheet->setCellValue("K{$row}", empty($value->Email) ? '-' : $value->Email);
			$_sheet->setCellValue("L{$row}", empty($value->Pekerjaan) ? '-' : $value->Pekerjaan);
			$_sheet->setCellValue("M{$row}", empty($value->PenanggungNama) ? '-' : $value->PenanggungNama);
			$_sheet->setCellValue("N{$row}", empty($value->PenanggungAlamat) ? '-' : $value->PenanggungAlamat);
			$_sheet->setCellValue("O{$row}", empty($value->PenanggungKTP) ? '-' : $value->PenanggungKTP);
			$_sheet->setCellValue("P{$row}", empty($value->PenanggungPhone) ? '-' : $value->PenanggungPhone);
			$_sheet->setCellValue("Q{$row}", empty($value->PenanggungHubungan) ? '-' : $value->PenanggungHubungan);
			$row++;
		}

		// Rename worksheet
		$spreadsheet->getActiveSheet()->setTitle('Data pasien');
		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$spreadsheet->setActiveSheetIndex(0);

		// Redirect output to a client’s web browser (Xls)
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="' . $file_name . '.xls"');
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

	private static function _get_style($key)
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

		return $style[$key];
	}

	private static function &ci()
	{
		return get_instance();
	}
}
