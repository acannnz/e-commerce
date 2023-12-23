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

	public static function get_registration_patient_types( $date_start, $date_end, $patient_type, $section, $jeniskelamin, $desa, $kelas, $patient_age )
	{
		// print_r($patient_age);exit;
			$_ci = self::ci();
			$_ci->load->model([
				'registration_model',
				'patient_type_model',
				'patient_model',
				'section_model',
				'desa_model',
				'class_model'
			]);

			$range = [
				'1' => array(0,14),
				'2' => array(15,24),
				'3' => array(25,34),
				'4' => array(35,44),
				'5' => array(45,54),
				'6' => array(55,64),
				'7' => array(65,150),
			];

			if (!is_null($patient_type) && !empty($patient_type)){ 
				$_ci->db->where("a.JenisKerjasamaID", $patient_type); 
			}	
			if (!is_null($section) && !empty($section)){ 
				$_ci->db->where("a.SectionID", $section); 
			}
			if (!is_null($jeniskelamin) && !empty($jeniskelamin)){ 
				$_ci->db->where("c.JenisKelamin", $jeniskelamin); 
			}		
			if (!is_null($desa) && !empty($desa)){ 
				$_ci->db->where("e.DesaID", $desa); 
			}	
			if (!is_null($kelas) && !empty($kelas)){ 
				$_ci->db->where("f.NamaKelas", $kelas); 
			}
			if (!empty($patient_age)) {
				$_ci->db->where("DATEDIFF(hour, c.TglLahir,GETDATE())/24 >", $range[$patient_age][0] * 365);
				$_ci->db->where("DATEDIFF(hour, c.TglLahir,GETDATE())/24 <",  $range[$patient_age][1] * 365);
			}

			// print_r($range[5]);
			// print_r($range[6]);
			// exit;

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
						DATEDIFF(hour, c.TglLahir,GETDATE())/8766 AS Umur,
						d.SectionName,
						e.DesaNama,
						f.NamaKelas
						"
					)
					->from( "{$_ci->registration_model->table} a" )
					->join( "{$_ci->patient_type_model->table} b", "a.JenisKerjasamaID = b.JenisKerjasamaID", "INNER" )
					->join( "{$_ci->patient_model->table} c", "a.NRM = c.NRM", "INNER" )
					->join( "{$_ci->section_model->table} d", "a.SectionID = d.SectionID", "INNER" )
					->join( "{$_ci->desa_model->table} e", "c.DesaID = e.DesaID", "INNER" )
					->join( "{$_ci->class_model->table} f", "a.KdKelas = f.KelasID", "INNER" )
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
						'SectionName' => $row->SectionName,
						'Desa'	=> $row->DesaNama,
						'NamaKelas' => $row->NamaKelas,
						'Umur'	=> $row->Umur 
					];
					// print_r($collection);exit;
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

	public static function get_daily_cash_report_fo( $date_start, $date_end, $user_id, $shift)
	{		
		$query = self::ci()->db->query("exec SIM_Rpt_LaporanKasHarianFO '{$date_start}','{$date_end}','{$user_id}','{$shift}'");

		$collection = ['data' => []];	
		// Data Laporan Kas Harian FO
		if( $query->num_rows() > 0 ):
			foreach($query->result() as $row)
			{
				$collection['data'][] = (object) $row;
			}

			return $collection;

		endif;

		return FALSE;
		
	}

	public static function export_excel_daily_cash_report_fo(  $date_start, $date_end, $user_id, $shift)
	{
		$_ci = self::ci();
		$_ci->load->model([
			'section_model'
		]);

		$collection = self::get_daily_cash_report_fo( $date_start, $date_end, $user_id, $shift);	

		$date_start = DateTime::createFromFormat("Y-m-d", $date_start );
		$date_end = DateTime::createFromFormat("Y-m-d", $date_end );
		$file_name = sprintf('%s periode %s s/d %s ', 'Laporan Harian Kas FO', $date_start->format('d F Y'), $date_end->format('d F Y'));
		
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
				->setTitle( 'Laporan Harian Kas FO' )
				->setSubject( 'Laporan Harian Kas FO' )
				->setDescription( $file_name )
				->setKeywords( $file_name)
				;
		
		$_sheet = $spreadsheet->setActiveSheetIndex( 0 );
		
		// Default Style
		
		$spreadsheet->getDefaultStyle()->applyFromArray( self::_get_style( 'default' ) );
		
		$_sheet->mergeCells("A1:I1");
		$_sheet->setCellValue('A1', $file_name );
		$_sheet->getStyle("A1")->applyFromArray( self::_get_style( 'header' ) );
		$_sheet->getStyle("A1")->getAlignment()->setWrapText(true);

		$tb_row = 3;

			$_sheet->setCellValue("A{$tb_row}", 'NO'); 
			$_sheet->getStyle("A{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) );
			$_sheet->setCellValue("B{$tb_row}", 'NO BUKTI'); 
			$_sheet->getStyle("B{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) );
			$_sheet->setCellValue("C{$tb_row}", 'NAMA USER'); 
			$_sheet->getStyle("C{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) ); 
			$_sheet->setCellValue("D{$tb_row}", 'NRM'); 
			$_sheet->getStyle("D{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) ); 
			$_sheet->setCellValue("E{$tb_row}", 'PASIEN'); 
			$_sheet->getStyle("E{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) ); 
			$_sheet->setCellValue("F{$tb_row}", 'SALDO AWAL'); 
			$_sheet->getStyle("F{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) ); 
			$_sheet->setCellValue("G{$tb_row}", 'PENERIMAAN'); 
			$_sheet->getStyle("G{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) );
			$_sheet->setCellValue("H{$tb_row}", 'PENGELUARAN'); 
			$_sheet->getStyle("H{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) );
			$_sheet->setCellValue("I{$tb_row}", 'SUMBER'); 
			$_sheet->getStyle("I{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) );
			$tb_row++;
			
			$_tot_saldo_awal = 0; $_tot_penerimaan = 0; $_tot_pengeluaran = 0; $no = 1; if(!empty($collection['data'])) : foreach ($collection['data'] as $row) : 
			$_tot_saldo_awal += $row->Saldoawal;
			$_tot_penerimaan += $row->Penerimaan;
			$_tot_pengeluaran += $row->Pengeluaran;

					$_sheet->setCellValue("A{$tb_row}", $no++);
					$_sheet->getStyle("A{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
					$_sheet->setCellValue("B{$tb_row}", @$row->NoBukti);
					$_sheet->getStyle("B{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
					$_sheet->setCellValue("C{$tb_row}", @$row->NamaUser);
					$_sheet->getStyle("C{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
					$_sheet->setCellValue("D{$tb_row}",  @$row->NRM);
					$_sheet->getStyle("D{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
					$_sheet->setCellValue("E{$tb_row}", @$row->NamaPasien);
					$_sheet->getStyle("E{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
					$_sheet->setCellValue("F{$tb_row}", @$row->Saldoawal);
					$_sheet->getStyle("F{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
					$_sheet->setCellValue("G{$tb_row}",  @$row->Penerimaan);
					$_sheet->getStyle("G{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
					$_sheet->setCellValue("H{$tb_row}", @$row->Pengeluaran);
					$_sheet->getStyle("H{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
					$_sheet->setCellValue("I{$tb_row}", @$row->Tipe);
					$_sheet->getStyle("I{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
					
					$_sheet->getStyle("F{$tb_row}:I{$tb_row}")->applyFromArray( self::_get_style( 'currency' ) );
					$tb_row++;
				endforeach;
			endif;

			$tb_row++;
			$_sheet->mergeCells("A{$tb_row}:E{$tb_row}");
			$_sheet->setCellValue("A{$tb_row}", '');

			$_sheet->setCellValue("F{$tb_row}", 'SALDO AWAL');
			$_sheet->getStyle("F{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) );

			$_sheet->setCellValue("G{$tb_row}", 'PENERIMAAN');
			$_sheet->getStyle("G{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) );

			$_sheet->setCellValue("H{$tb_row}", 'PENGELUARAN');
			$_sheet->getStyle("H{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) );

			$_sheet->setCellValue("I{$tb_row}", 'SALDO AKHIR');
			$_sheet->getStyle("I{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) );

			$tb_row++;
			$_sheet->mergeCells("A{$tb_row}:E{$tb_row}");
			$_sheet->setCellValue("A{$tb_row}", '');


			$_sheet->setCellValue("F{$tb_row}", $_tot_saldo_awal);
			$_sheet->getStyle("F{$tb_row}")->applyFromArray( self::_get_style( 'currency' ) );
			$_sheet->getStyle("F{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );

			$_sheet->setCellValue("G{$tb_row}", $_tot_penerimaan);
			$_sheet->getStyle("G{$tb_row}")->applyFromArray( self::_get_style( 'currency' ) );
			$_sheet->getStyle("G{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );

			$_sheet->setCellValue("H{$tb_row}", $_tot_pengeluaran);
			$_sheet->getStyle("H{$tb_row}")->applyFromArray( self::_get_style( 'currency' ) );
			$_sheet->getStyle("H{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );

			$_sheet->setCellValue("I{$tb_row}", ($_tot_saldo_awal + $_tot_penerimaan) - $_tot_pengeluaran);
			$_sheet->getStyle("I{$tb_row}")->applyFromArray( self::_get_style( 'currency' ) );
			$_sheet->getStyle("I{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );

			$_sheet->getStyle("F{$tb_row}:I{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) );
			$tb_row++;

					
		// Rename worksheet
		$spreadsheet->getActiveSheet()->setTitle( 'LAPORAN HARIAN KAS FO' );
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

	public static function get_recap_transactions( $date_start, $date_end, $section_id, $shift_id, $user_id )
	{
		$_ci = self::ci();
		$_ci->load->model('section_model');
		$section = $_ci->section_model->get_one($section_id);

		$collection = ['data' => [], 'payment' => [], 'merchan' => []];
		// collection data
		$query = $_ci->db->query("exec FAR_Rpt_RekapTransaksi '{$date_start}','{$date_end}','{$section->SectionID}','{$shift_id}','{$user_id}'");
		foreach( $query->result() as $row )
		{	
			$collection['data'][ $row->JenisKerjasama ][$row->NoBukti .' => '. $row->Keterangan][] = [
				'JenisKerjasama' => $row->JenisKerjasama,
				'Barang_ID' => $row->Barang_ID,
				'NamaObat' => $row->Nama_Barang,
				'Qty' => $row->JmlObat,
				'Nilai' => $row->Nilai,
				'HExt' => $row->HExt,
				'Diskon' => $row->Disc,
				'JasaResep' => $row->BiayaResep,
				'NamaResepObat' => $row->NamaResepObat,
			];
		}
		// Pasien dengan pembayaran Merchan		
		$collection['merchan'] = $_ci->db->query("exec FAR_Rpt_RekapTransaksiMerchan '{$date_start}','{$date_end}','{$section->SectionID}', '{$shift_id}', '{$user_id}'")->result();
		// Total Jenis Pembayaran
		$collection['payment'] = $_ci->db->query("exec FAR_Rpt_RekapTransaksiPayment '{$date_start}','{$date_end}','{$section->SectionID}','{$shift_id}', '{$user_id}'")->row();
		
		return $collection;
	}
	
	public static function export_excel_recap_transactions( $date_start, $date_end , $section_id, $shift_id, $user_id)
	{
		$_ci = self::ci();
		
		$collection = self::get_recap_transactions( $date_start, $date_end, $section_id, $shift_id, $user_id);	
		$shift = $_ci->db->where("IDShift", $shift_id )->get("SIMmShift")->row();
		$user = $_ci->db->where("User_ID", $user_id)->get("mUser")->row();	
		$date_start = DateTime::createFromFormat("Y-m-d", $date_start );
		$date_end = DateTime::createFromFormat("Y-m-d", $date_end );
		$file_name = sprintf('%s periode %s s/d %s ', lang('reports:recap_transaction_label'), $date_start->format('d F Y'), $date_end->format('d F Y'));
		
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
		
		$_sheet->mergeCells("A1:G1");
		$_sheet->setCellValue('A1', $file_name );
		$_sheet->getStyle("A1")->applyFromArray( self::_get_style( 'header' ) );
		$_sheet->getStyle("A1")->getAlignment()->setWrapText(true);
		//$_sheet->getRowDimension('1')->setRowHeight(30);

		$_sheet->mergeCells("A3:G3");
		$_sheet->setCellValue("A3", sprintf("%s : %s", 'User', (!empty(@$user->Nama_Asli)) ? @$user->Nama_Asli : 'Semua' )); 
		$_sheet->getStyle("A3")->applyFromArray(['font'  => ['bold'	=> TRUE, 'size'  => 11 ]]);

		$_sheet->mergeCells("A4:G4");
		$_sheet->setCellValue("A4", sprintf("%s : %s", 'Shift', (!empty(@$shift->Deskripsi)) ? @$shift->Deskripsi : 'Semua' )); 
		$_sheet->getStyle("A4")->applyFromArray(['font'  => ['bold'	=> TRUE, 'size'  => 11 ]]);

		$tb_row = 6; $grandtotal = 0;
		foreach ( $collection['data'] as $key => $transaction ):
			
			$_sheet->mergeCells("A{$tb_row}:G{$tb_row}");
			$_sheet->setCellValue("A{$tb_row}", $key); 
			$_sheet->getStyle("A{$tb_row}")->applyFromArray(['font'  => ['bold'	=> TRUE, 'size'  => 11 ]]);
			$tb_row++;
			
			$_sheet->setCellValue("A{$tb_row}", 'TRANSAKSI'); 
			$_sheet->getStyle("A{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) );
			$_sheet->setCellValue("B{$tb_row}", 'TIPE PASIEN'); 
			$_sheet->getStyle("B{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) );
			$_sheet->setCellValue("C{$tb_row}", 'ITEM'); 
			$_sheet->getStyle("C{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) ); 
			$_sheet->setCellValue("D{$tb_row}", 'QTY'); 
			$_sheet->getStyle("D{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) ); 
			$_sheet->setCellValue("E{$tb_row}", 'NILAI'); 
			$_sheet->getStyle("E{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) ); 
			$_sheet->setCellValue("F{$tb_row}", 'JASA APOTEK'); 
			$_sheet->getStyle("F{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) ); 
			$_sheet->setCellValue("G{$tb_row}", 'SUBTOTAL'); 
			$_sheet->getStyle("G{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) );
			$_sheet->setCellValue("H{$tb_row}", 'DISKON'); 
			$_sheet->getStyle("H{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) );
			$tb_row++;
			
			$tb_start = $tb_row;
			$total_subtotal = 0; $total_nilai = 0; $total_jasa_resep = 0; $diskon_total = 0;
			foreach($transaction as $evidence_number => $items):
				$tb_start_patient = $tb_row;
				//$_sheet->setCellValue("A{$tb_row}", $evidence_number);
				foreach($items as  $item):
					$item = (object) $item;
					$sub_total = $item->Qty * $item->Nilai + $item->JasaResep + $item->HExt; 
					$total_subtotal += $sub_total;
					$total_nilai += $item->Nilai;
					$total_jasa_resep += $item->JasaResep;
					$diskon = $item->Qty * $item->Nilai * $item->Diskon / 100;
					$diskon_total += $diskon;

					$_sheet->setCellValue("A{$tb_row}", $evidence_number);
					//$_sheet->getStyle("A{$tb_row}")->applyFromArray( self::_get_style( 'tbody_merge' ) );
					$_sheet->getStyle("A{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
					$_sheet->setCellValue("B{$tb_row}", @$item->JenisKerjasama);
					$_sheet->getStyle("B{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
					$_sheet->setCellValue("C{$tb_row}", $item->NamaObat);
					$_sheet->getStyle("C{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
					$_sheet->setCellValue("D{$tb_row}", $item->Qty);
					$_sheet->getStyle("D{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
					$_sheet->setCellValue("E{$tb_row}", $item->Barang_ID != 0 ? $item->Nilai : $item->Nilai);
					$_sheet->getStyle("E{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
					$_sheet->setCellValue("F{$tb_row}", $item->Barang_ID != 0 ? $item->JasaResep : $item->JasaResep);
					$_sheet->getStyle("F{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
					$_sheet->setCellValue("G{$tb_row}",  "=(D{$tb_row} * E{$tb_row}) + F{$tb_row} + {$item->HExt}");
					$_sheet->getStyle("G{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
					$_sheet->setCellValue("H{$tb_row}", $item->Diskon > 0 ? "= {$item->Qty} * {$item->Nilai} * {$item->Diskon} / 100" : '');
					$_sheet->getStyle("H{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
					
					$_sheet->getStyle("E{$tb_row}:H{$tb_row}")->applyFromArray( self::_get_style( 'currency' ) );
					$tb_row++;
				endforeach;
				$tb_row--;
				//$_sheet->mergeCells("A{$tb_start_patient}:A{$tb_row}");
				// $_sheet->setCellValue("H{$tb_start_patient}", "=SUM(G{$tb_start_patient}:G{$tb_row})");
				// $_sheet->getStyle("H{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
				// $_sheet->mergeCells("H{$tb_start_patient}:H{$tb_row}");
				$tb_row++;
			endforeach;
			
			$tb_sum_till = $tb_row - 1;
			
			$_sheet->mergeCells("A{$tb_row}:D{$tb_row}");
			$_sheet->setCellValue("A{$tb_row}", 'TOTAL');
			$_sheet->getStyle("A{$tb_row}:D{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
			$_sheet->getStyle("A{$tb_row}")->applyFromArray(['font'  => ['bold'	=> TRUE, 'size'  => 10 ]]);
			$_sheet->setCellValue("E{$tb_row}", "=SUM(E{$tb_start}:E{$tb_sum_till})");
			$_sheet->getStyle("E{$tb_row}")->applyFromArray( self::_get_style( 'sum_value' ) );
			$_sheet->getStyle("E{$tb_row}")->applyFromArray( self::_get_style( 'currency' ) );
			$_sheet->setCellValue("F{$tb_row}", "=SUM(F{$tb_start}:F{$tb_sum_till})");
			$_sheet->getStyle("F{$tb_row}")->applyFromArray( self::_get_style( 'sum_value' ) );
			$_sheet->getStyle("F{$tb_row}")->applyFromArray( self::_get_style( 'currency' ) );
			$_sheet->setCellValue("G{$tb_row}", "=SUM(G{$tb_start}:G{$tb_sum_till})");
			$_sheet->getStyle("G{$tb_row}")->applyFromArray( self::_get_style( 'sum_value' ) );
			$_sheet->getStyle("G{$tb_row}")->applyFromArray( self::_get_style( 'currency' ) );
			$_sheet->setCellValue("H{$tb_row}", "=SUM(H{$tb_start}:H{$tb_sum_till})");
			$_sheet->getStyle("H{$tb_row}")->applyFromArray( self::_get_style( 'sum_value' ) );
			$_sheet->getStyle("H{$tb_row}")->applyFromArray( self::_get_style( 'currency' ) );
			$tb_row++;
			$tb_row++;
			
			$grandtotal += $total_subtotal - $diskon_total;
		endforeach;
		$_sheet->mergeCells("A{$tb_row}:G{$tb_row}");
		$_sheet->setCellValue("A{$tb_row}", 'GRANDTOTAL');
		$_sheet->getStyle("A{$tb_row}:H{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
		$_sheet->getStyle("A{$tb_row}")->applyFromArray(['font'  => ['bold'	=> TRUE, 'size'  => 10 ]]);
		$_sheet->getStyle("H{$tb_row}")->applyFromArray( self::_get_style( 'sum_value' ) );
		$_sheet->getStyle("H{$tb_row}")->applyFromArray( self::_get_style( 'currency' ) );
		$_sheet->setCellValue("H{$tb_row}", $grandtotal);
		$tb_row += 2;
		// TIPE PEMBAYARAN
		$tb_row += 2;
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
		foreach($collection['payment'] as $type => $val ):
			$_sheet->setCellValue("A{$tb_row}", $type);
			$_sheet->getStyle("A{$tb_row}")->applyFromArray( self::_get_style( 'tbody_merge' ) );
			$_sheet->setCellValue("B{$tb_row}", $val);
			$_sheet->getStyle("B{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
			$_sheet->getStyle("B{$tb_row}")->applyFromArray( self::_get_style( 'currency' ) );
			$_total_payment += $val;
			$tb_row++;
		endforeach;
		$_sheet->setCellValue("A{$tb_row}", "Total"); 
		$_sheet->getStyle("A{$tb_row}")->applyFromArray( self::_get_style( 'tbody_merge' ) );
		$_sheet->getStyle("A{$tb_row}")->applyFromArray(['font'  => ['bold'	=> TRUE, 'size'  => 10 ]]);
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
		$_sheet->getStyle("A{$tb_row}")->applyFromArray(['font'  => ['bold'	=> TRUE, 'size'  => 10 ]]);
		$_sheet->setCellValue("B{$tb_row}", $_total_payment); 
		$_sheet->getStyle("B{$tb_row}")->applyFromArray( self::_get_style( 'sum_value' ));
		$_sheet->getStyle("B{$tb_row}")->applyFromArray( self::_get_style( 'currency' ) );
		$tb_row++;		
	
		
		/*$spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);*/
					
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

	public static function get_recap_stocks( $date_start, $date_end, $Lokasi_ID )
	{
		
		$db = self::ci()->db;
		
		// exec SIM_Rpt_RekapStok_FIFO '2016-11-01','2016-11-30',296
		$query = "exec SIM_Rpt_RekapStok_FIFO '$date_start', '$date_end', $Lokasi_ID";
		
		$query = $db->query( $query );
		if( $query->num_rows() )
		{
			// Ambil data barang sesuai section, untuk mencari Kelompok jenis barang.
			$barang_section = self::get_barang_section($Lokasi_ID);
			
			$collection = array();
			foreach( $query->result() as $item )
			{	
				// Pengelompokan Barang Berdasarkan Kelompok Jenis
				$collection[ $barang_section[$item->KOde_Barang]->KelompokJenis ][] = $item;
			}
			
			// Urutkan berdasarkan Kelompok Jenisnya
			ksort($collection);
			
			return $collection;
		}		
		
		return FALSE;
	}
	public static function export_excel_get_recap_stocks( $date_start, $date_end , $lokasi_id)
	{
		$_ci = self::ci();
		$_ci->load->model('section_model');

		$section = $_ci->section_model->get_by(['Lokasi_ID' => $lokasi_id]);
		$collection = self::get_recap_stocks( $date_start, $date_end, $lokasi_id);	

		$date_start = DateTime::createFromFormat("Y-m-d", $date_start );
		$date_end = DateTime::createFromFormat("Y-m-d", $date_end );
		$file_name = sprintf('%s %s periode %s s/d %s ', lang('reports:recap_stock_label'), $section->SectionName, $date_start->format('d F Y'), $date_end->format('d F Y'));
		
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
				->setTitle( lang('reports:recap_stock_label') )
				->setSubject( lang('reports:recap_stock_label') )
				->setDescription( $file_name )
				->setKeywords( $file_name)
				;
		
		$_sheet = $spreadsheet->setActiveSheetIndex( 0 );
		
		// Default Style
		
		$spreadsheet->getDefaultStyle()->applyFromArray( self::_get_style( 'default' ) );
		
		$_sheet->mergeCells("A1:H1");
		$_sheet->setCellValue('A1', $file_name );
		$_sheet->getStyle("A1")->applyFromArray( self::_get_style( 'header' ) );
		$_sheet->getStyle("A1")->getAlignment()->setWrapText(true);

		$tb_row = 3;
		$i = 1;
		if(!empty($collection)) : foreach ($collection as $key => $value) :
			
			$_sheet->mergeCells("A{$tb_row}:G{$tb_row}");
			$_sheet->setCellValue("A{$tb_row}", sprintf("%s : %s", lang("reports:group_label"), $key )); 
			$_sheet->getStyle("A{$tb_row}")->applyFromArray(['font'  => ['bold'	=> TRUE, 'size'  => 11 ]]);
			$tb_row++;
			
			$_sheet->setCellValue("A{$tb_row}", lang('reports:no_label')); 
			$_sheet->getStyle("A{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) );
			$_sheet->setCellValue("B{$tb_row}", lang('reports:code_label')); 
			$_sheet->getStyle("B{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) );
			$_sheet->setCellValue("C{$tb_row}", lang('reports:item_label')); 
			$_sheet->getStyle("C{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) ); 
			$_sheet->setCellValue("D{$tb_row}", lang('reports:unit_label')); 
			$_sheet->getStyle("D{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) ); 
			$_sheet->setCellValue("E{$tb_row}", lang('reports:beginning_balance_label')); 
			$_sheet->getStyle("E{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) ); 
			$_sheet->setCellValue("F{$tb_row}", lang('reports:in_label')); 
			$_sheet->getStyle("F{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) ); 
			$_sheet->setCellValue("G{$tb_row}", lang('reports:out_label')); 
			$_sheet->getStyle("G{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) );
			$_sheet->setCellValue("H{$tb_row}", lang('reports:ending_balance_label')); 
			$_sheet->getStyle("H{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) );
			$tb_row++;
			
			if(!empty($value)) : foreach ($value as $row) :
					$_sheet->setCellValue("A{$tb_row}", $i++);
					$_sheet->getStyle("A{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
					$_sheet->setCellValue("B{$tb_row}", @$row->KOde_Barang);
					$_sheet->getStyle("B{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
					$_sheet->setCellValue("C{$tb_row}", @$row->Nama_Barang);
					$_sheet->getStyle("C{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
					$_sheet->setCellValue("D{$tb_row}", @$row->Satuan_Stok);
					$_sheet->getStyle("D{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
					$_sheet->setCellValue("E{$tb_row}", @$row->SA);
					$_sheet->getStyle("E{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
					$_sheet->setCellValue("F{$tb_row}", @$row->MASUK);
					$_sheet->getStyle("F{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
					$_sheet->setCellValue("G{$tb_row}",  @$row->KELUAR);
					$_sheet->getStyle("G{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
					$_sheet->setCellValue("H{$tb_row}",abs($row->SA + $row->MASUK - $row->KELUAR));
					$_sheet->getStyle("H{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
					$tb_row++;
			endforeach; endif;

		endforeach; endif;
		/*$spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);*/
					
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

	public static function get_barang_section( $Lokasi_ID )
	{
		
		$db = self::ci()->db;
		
		$db_from = "mBarangLokasiNew a";
		
		$query = $db->select("b.*")
					->from($db_from)
					->join("mBarang b", "a.Barang_ID = b.Barang_ID", "LEFT OUTER")
					->where(array("a.Lokasi_ID" => $Lokasi_ID))
					->get()
					;
					
		if( $query->num_rows() > 0 )
		{	
			$collection = array();		
			foreach ($query->result() as $row )
			{
				$collection[$row->Kode_Barang] = $row;
			}
			
			return $collection;
		}		
		
		return FALSE;
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
