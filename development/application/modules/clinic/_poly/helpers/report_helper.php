<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Borders;
final class report_helper 
{
 
	public static function get_warehouse_cards( $date_start, $date_end, $BarangID, $LokasiID, $JenisBarangID = 0 )
	{
		
		$db = self::ci()->db;
		
		//exce SIM_Rpt_KartuGudang_FIFO '2016-07-01','2016-07-30',387,296,0 	
		$query = "exec SIM_Rpt_KartuGudang_FIFO '$date_start', '$date_end', $BarangID, $LokasiID, $JenisBarangID";
		
		$query = $db->query( $query );
		if( $query->num_rows() )
		{
			$collection = array();
			foreach( $query->result() as $item )
			{
				//select top 100 * from BILLFarmasi where NoBukti ='161101APT-00031'
				//select * from mSupplier where Kode_Supplier = 'DS019'
				//select * from mPasien where NRM = '09.12.19'
				if ( $item->Kartu_ID > 0 ) 
				{
					$data = $db->select("b.Nama_Supplier, c.NamaPasien, c.Alamat, d.Nama_Asli, d.Nama_Singkat")
									->from("BILLFarmasi a")
									->join("mSupplier b", "a.DokterID = b.Kode_Supplier", "LEFT OUTER")
									->join("mPasien c", "a.NRM = c.NRM", "LEFT OUTER")
									->join("mUser d", "a.UserID = d.User_ID", "LEFT OUTER")
									->where("a.NoBukti", $item->No_Bukti)
									->get()->row()
									;
									
					$item->Nama_Supplier = !empty($data->Nama_Supplier) ? $data->Nama_Supplier : '-' ;
					$item->NamaPasien = !empty($data->NamaPasien) ? $data->NamaPasien : '-' ;
					$item->Alamat = !empty($data->Alamat) ? $data->Alamat : '-' ;
					$item->Nama_Asli = !empty($data->Nama_Asli) ? $data->Nama_Asli : '-' ;
					$item->Nama_Singkat = !empty($data->Nama_Singkat) ? $data->Nama_Singkat : '-' ;
				}
				
				$collection[] = $item;
			}
			
			return $collection;
		}		
		
		return FALSE;
	}

	public static function get_recap_stocks( $date_start, $date_end, $Lokasi_ID )
	{
		
		$db = self::ci()->db;
		
		// exec SIM_Rpt_RekapStok_FIFO '2016-11-01','2016-11-30',296
		$query = "exec SIM_Rpt_RekapStok_FIFO '$date_start', '$date_end', $Lokasi_ID";
		print_r($query);exit;
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

	public static function get_stock_opname( $date_start, $date_end, $SectionName )
	{
		
		$db = self::ci()->db;
		
		// exec SIM_Rpt_DataOpname_Total '2016-11-01','2016-11-30','APOTEK'
		$query = "exec SIM_Rpt_DataOpname_Total '$date_start', '$date_end', '$SectionName'";
		
		$query = $db->query( $query );
		if( $query->num_rows() )
		{			
			$collection = array();
			foreach( $query->result() as $item )
			{	
				// Pengelompokan Opname Berdasarkan Kelompk (Positif, Negatif), Tanggal, User, & No_Bukti opname
				$collection[ $item->Kelompok ][ $item->Tgl_Opname ."|". $item->Nama_Asli ."|". $item->No_Bukti ][] = $item;
			}
			
			if ( !empty( $collection['POSITIF'] ))
				// Urutkan Berdasarkan Tanggal
				ksort($collection['POSITIF']);

			if ( !empty( $collection['NEGATIF'] ))
				// Urutkan Berdasarkan Tanggal
				ksort($collection['NEGATIF']);

			// urutkan negatifnya lebih dahulu
			ksort($collection);
			
			return $collection;
		}		
		
		return FALSE;
	}
		
	public static function get_barang( $Barang_ID, $Lokasi_ID)
	{
		
		$db = self::ci()->db;
		
		$db_from = "mBarangLokasiNew a";
		
		$query = $db->select("a.Kode_Satuan, b.Kode_Barang, b.Nama_Barang, c.Nama_Kategori")
					->from($db_from)
					->join("mBarang b", "a.Barang_ID = b.Barang_ID", "LEFT OUTER")
					->join("mKategori c", "b.Kategori_Id = c.Kategori_ID", "LEFT OUTER")
					->where(array("a.Barang_ID" => $Barang_ID, "a.Lokasi_ID" => $Lokasi_ID))
					->get()
					;
					
		if( $query->num_rows() > 0 )
		{			
			return $query->row();
		}		
		
		return FALSE;
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
	
	public static function export_excel_patient_symptom_therapi( $date_start, $date_end, $doctor_id )
	{
		$_ci = self::ci();
		$_ci->load->model('supplier_model');
		$collection = self::get_patient_symptom_therapi( $date_start, $date_end, $doctor_id );	
		$date_start = DateTime::createFromFormat("Y-m-d", $date_start );
		$date_end = DateTime::createFromFormat("Y-m-d", $date_end );
		$doctor = $_ci->supplier_model->get_by(['Kode_Supplier' => $doctor_id]);
		$file_name = sprintf('%s periode %s s/d %s ', 'Laporan Symptom Dan Terapi Pasien', $date_start->format('d F Y'), $date_end->format('d F Y'));
		
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
				->setTitle( 'Laporan Systom Dan Terapi Pasien' )
				->setSubject( 'Laporan Systom Dan Terapi Pasien' )
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
		//$_sheet->getRowDimension('1')->setRowHeight(30);
		
		$_sheet->mergeCells("A3:H3");
		$_sheet->setCellValue("A3", "Dokter: ". $doctor->Nama_Supplier); 
		$_sheet->getStyle("A3")->applyFromArray(['font'  => ['bold'	=> TRUE, 'size'  => 11 ]]);
		
		$_sheet->mergeCells("A4:A5");
		$_sheet->setCellValue("A4", 'No'); 
		$_sheet->getStyle("A4:A5")->applyFromArray( self::_get_style( 'thead' ) );
		
		$_sheet->mergeCells("B4:B5");
		$_sheet->setCellValue("B4", 'L/B'); 
		$_sheet->getStyle("B4:B5")->applyFromArray( self::_get_style( 'thead' ) );
		
		$_sheet->mergeCells("C4:D4");
		$_sheet->setCellValue("C4", 'Pasien'); 
		$_sheet->getStyle("C4:D4")->applyFromArray( self::_get_style( 'thead' ) ); 
		$_sheet->setCellValue("C5", 'Ibu'); 
		$_sheet->getStyle("C5")->applyFromArray( self::_get_style( 'thead' ) ); 
		$_sheet->setCellValue("D5", 'Ayah'); 
		$_sheet->getStyle("D5")->applyFromArray( self::_get_style( 'thead' ) ); 
		
		$_sheet->mergeCells("E4:E5");
		$_sheet->setCellValue("E4", 'Tgl Lahir'); 
		$_sheet->getStyle("E4:E5")->applyFromArray( self::_get_style( 'thead' ) ); 
		
		$_sheet->mergeCells("F4:F5");
		$_sheet->setCellValue("F4", 'Umur'); 
		$_sheet->getStyle("F4:F5")->applyFromArray( self::_get_style( 'thead' ) ); 
		
		$_sheet->mergeCells("G4:G5");
		$_sheet->setCellValue("G4", 'JK'); 
		$_sheet->getStyle("G4:G5")->applyFromArray( self::_get_style( 'thead' ) ); 
		
		$_sheet->mergeCells("H4:H5");
		$_sheet->setCellValue("H4", 'Alamat'); 
		$_sheet->getStyle("H4:H5")->applyFromArray( self::_get_style( 'thead' ) ); 
		
		$_sheet->mergeCells("I4:I5");
		$_sheet->setCellValue("I4", 'Telp.'); 
		$_sheet->getStyle("I4:I5")->applyFromArray( self::_get_style( 'thead' ) ); 
		
		$_sheet->mergeCells("J4:J5");
		$_sheet->setCellValue("J4", 'Symptom'); 
		$_sheet->getStyle("J4:J5")->applyFromArray( self::_get_style( 'thead' ) );
		
		$_sheet->mergeCells("K4:K5");
		$_sheet->setCellValue("K4", 'Therapi'); 
		$_sheet->getStyle("K4:K5")->applyFromArray( self::_get_style( 'thead' ) );

		$tb_row = 6; $no = 1;
		foreach ( $collection['data'] as $row ):
			
			$_sheet->setCellValue("A{$tb_row}", $no++ );
			$_sheet->getStyle("A{$tb_row}")->applyFromArray( self::_get_style( 'tbody_merge' ) );
			
			$_sheet->setCellValue("B{$tb_row}", $row->PasienBaru ? 'Baru' : 'Lama' );
			$_sheet->getStyle("B{$tb_row}")->applyFromArray( self::_get_style( 'tbody_merge' ) );
			
			$_sheet->setCellValue("C{$tb_row}", $row->NamaPasien );
			$_sheet->getStyle("C{$tb_row}")->applyFromArray( self::_get_style( 'tbody_merge' ) );
			
			$_sheet->setCellValue("D{$tb_row}", $row->PenanggungNama );
			$_sheet->getStyle("D{$tb_row}")->applyFromArray( self::_get_style( 'tbody_merge' ) );
			
			$_sheet->setCellValue("E{$tb_row}", DateTime::createFromFormat('Y-m-d H:i:s.u', $row->TglLahir)->format('d-m-Y'));
			$_sheet->getStyle("E{$tb_row}")->applyFromArray( self::_get_style( 'tbody_merge' ) );
			
			$_sheet->setCellValue("F{$tb_row}", sprintf("%s Thn %s Bln %s Hr", $row->UmurThn, $row->UmurBln, $row->UmurHr ));
			$_sheet->getStyle("F{$tb_row}")->applyFromArray( self::_get_style( 'tbody_merge' ) );
			
			$_sheet->setCellValue("G{$tb_row}", $row->JenisKelamin == 'F' ? 'Peramuan' : 'Laki-laki' );
			$_sheet->getStyle("G{$tb_row}")->applyFromArray( self::_get_style( 'tbody_merge' ) );
			
			$_sheet->setCellValue("H{$tb_row}", $row->Alamat );
			$_sheet->getStyle("H{$tb_row}")->applyFromArray( self::_get_style( 'tbody_merge' ) );

			$_sheet->setCellValue("I{$tb_row}", $row->Phone );
			$_sheet->getStyle("I{$tb_row}")->applyFromArray( self::_get_style( 'tbody_merge' ) );

			$_sheet->setCellValue("J{$tb_row}", $row->Symptom );
			$_sheet->getStyle("J{$tb_row}")->applyFromArray( self::_get_style( 'tbody_merge' ) );
			
			$_sheet->setCellValue("K{$tb_row}", $row->Therapi );
			$_sheet->getStyle("K{$tb_row}")->applyFromArray( self::_get_style( 'tbody_merge' ) );
			
			$tb_row++;
			
		endforeach;
		
		/*$spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);*/
					
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

	public static function get_patient_symptom_therapi( $date_start, $date_end, $doctor_id )
	{
		
		$date_start = DateTime::createFromFormat('Y-m-d', $date_start)->setTime(0, 0);
		$date_start->add(new DateInterval('PT8H'));
		$date_end = DateTime::createFromFormat('Y-m-d', $date_end)->setTime(0, 0);
		$date_end->add(new DateInterval('P1DT8H'));
		
		$query = self::ci()->db->select('
								a.PasienBaru, c.NamaPasien, c.PenanggungNama, c.Alamat, c.TglLahir, c.JenisKelamin,
								a.UmurThn, a.UmurBln, a.UmurHr, b.Symptom, b.Therapi, c.Phone 
							')
							->from('SIMtrRegistrasi a')
							->join('SIMtrRJ b', 'b.RegNo = a.NoReg', 'INNER')
							->join('mPasien c', 'c.NRM = a.NRM', 'INNER')
							->where([
								'a.JamReg >=' => $date_start->format('Y-m-d H:i:s'),
								'a.JamReg <=' => $date_end->format('Y-m-d H:i:s'),
								'b.DokterID' => $doctor_id,
								'b.SectionID' => config_item('section_id'),
							])
							->get();
			
		$collection = ['data' => $query->result()];
		
		return $collection;
	}
	
	public static function export_excel_spog_medical_record( $date_start, $date_end, $doctor_id )
	{
		$_ci = self::ci();
		$_ci->load->model('supplier_model');
		$collection = self::get_spog_medical_record( $date_start, $date_end, $doctor_id );	
		$date_start = DateTime::createFromFormat("Y-m-d", $date_start );
		$date_end = DateTime::createFromFormat("Y-m-d", $date_end );
		$doctor = $_ci->supplier_model->get_by(['Kode_Supplier' => $doctor_id]);
		$file_name = sprintf('%s periode %s s/d %s ', 'Laporan REKAM MEDIK SPOG', $date_start->format('d F Y'), $date_end->format('d F Y'));
		
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
				->setTitle( 'Laporan REKAM MEDIK SPOG' )
				->setSubject( 'Laporan REKAM MEDIK SPOG' )
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
		//$_sheet->getRowDimension('1')->setRowHeight(30);
		
		$_sheet->mergeCells("A3:H3");
		$_sheet->setCellValue("A3", "Dokter: ". $doctor->Nama_Supplier); 
		$_sheet->getStyle("A3")->applyFromArray(['font'  => ['bold'	=> TRUE, 'size'  => 11 ]]);
		
		$_sheet->mergeCells("A4:A5");
		$_sheet->setCellValue("A4", 'No'); 
		$_sheet->getStyle("A4:A5")->applyFromArray( self::_get_style( 'thead' ) );
		
		$_sheet->mergeCells("B4:B5");
		$_sheet->setCellValue("B4", 'TGL KUNJ.'); 
		$_sheet->getStyle("B4:B5")->applyFromArray( self::_get_style( 'thead' ) );
		
		$_sheet->mergeCells("C4:D4");
		$_sheet->setCellValue("C4", 'NAMA'); 
		$_sheet->getStyle("C4:D4")->applyFromArray( self::_get_style( 'thead' ) ); 
		$_sheet->setCellValue("C5", 'IBU'); 
		$_sheet->getStyle("C5")->applyFromArray( self::_get_style( 'thead' ) ); 
		$_sheet->setCellValue("D5", 'SUAMI'); 
		$_sheet->getStyle("D5")->applyFromArray( self::_get_style( 'thead' ) ); 
		
		$_sheet->mergeCells("E4:E5");
		$_sheet->setCellValue("E4", 'UMUR IBU'); 
		$_sheet->getStyle("E4:E5")->applyFromArray( self::_get_style( 'thead' ) ); 
		
		$_sheet->mergeCells("F4:F5");
		$_sheet->setCellValue("F4", 'ALAMAT'); 
		$_sheet->getStyle("F4:F5")->applyFromArray( self::_get_style( 'thead' ) ); 
		
		$_sheet->mergeCells("G4:G5");
		$_sheet->setCellValue("G4", 'HAMIL GPA'); 
		$_sheet->getStyle("G4:G5")->applyFromArray( self::_get_style( 'thead' ) ); 
		
		$_sheet->mergeCells("H4:H5");
		$_sheet->setCellValue("H4", 'UMUR ANAK'); 
		$_sheet->getStyle("H4:H5")->applyFromArray( self::_get_style( 'thead' ) ); 
		
		$_sheet->mergeCells("I4:I5");
		$_sheet->setCellValue("I4", 'UK'); 
		$_sheet->getStyle("I4:I5")->applyFromArray( self::_get_style( 'thead' ) ); 
		
		$_sheet->mergeCells("J4:J5");
		$_sheet->setCellValue("J4", 'HPHT'); 
		$_sheet->getStyle("J4:J5")->applyFromArray( self::_get_style( 'thead' ) );
		
		$_sheet->mergeCells("K4:K5");
		$_sheet->setCellValue("K4", 'TD'); 
		$_sheet->getStyle("K4:K5")->applyFromArray( self::_get_style( 'thead' ) );
	
		$_sheet->mergeCells("L4:L5");
		$_sheet->setCellValue("L4", 'BB'); 
		$_sheet->getStyle("L4:L5")->applyFromArray( self::_get_style( 'thead' ) );
	
		$_sheet->mergeCells("M4:M5");
		$_sheet->setCellValue("M4", 'LILA'); 
		$_sheet->getStyle("M4:M5")->applyFromArray( self::_get_style( 'thead' ) );
	
		$_sheet->mergeCells("N4:Q4");
		$_sheet->setCellValue("N4", 'LAB'); 
		$_sheet->setCellValue("N5", 'BB'); 
		$_sheet->setCellValue("O5", 'PPIA'); 
		$_sheet->setCellValue("P5", 'HBSAG'); 
		$_sheet->setCellValue("Q5", 'SPILIS'); 
		$_sheet->getStyle("N4:Q4")->applyFromArray( self::_get_style( 'thead' ) );
		$_sheet->getStyle("N5:Q5")->applyFromArray( self::_get_style( 'thead' ) );

		$_sheet->mergeCells("R4:R5");
		$_sheet->setCellValue("R4", 'IMUN TD'); 
		$_sheet->getStyle("R4:R5")->applyFromArray( self::_get_style( 'thead' ) );

		$_sheet->mergeCells("S4:S5");
		$_sheet->setCellValue("S4", 'KUNJ K1, K4'); 
		$_sheet->getStyle("S4:S5")->applyFromArray( self::_get_style( 'thead' ) );

		$_sheet->mergeCells("T4:T5");
		$_sheet->setCellValue("T4", 'RIW PERSALINAN'); 
		$_sheet->getStyle("T4:T5")->applyFromArray( self::_get_style( 'thead' ) );
		
		$_sheet->mergeCells("U4:V4");
		$_sheet->setCellValue("U4", 'JENIS'); 
		$_sheet->setCellValue("U5", 'RESTI'); 
		$_sheet->setCellValue("V5", 'KOMPI'); 
		$_sheet->getStyle("U4:V4")->applyFromArray( self::_get_style( 'thead' ) );
		$_sheet->getStyle("U5:V5")->applyFromArray( self::_get_style( 'thead' ) );

		$_sheet->mergeCells("W4:W5");
		$_sheet->setCellValue("W4", 'RUJUK'); 
		$_sheet->getStyle("W4:W5")->applyFromArray( self::_get_style( 'thead' ) );

		$_sheet->mergeCells("X4:X5");
		$_sheet->setCellValue("X4", 'KET'); 
		$_sheet->getStyle("X4:X5")->applyFromArray( self::_get_style( 'thead' ) );

		$tb_row = 6; $no = 1;
		foreach ( $collection['data'] as $row ):
			
			$_sheet->setCellValue("A{$tb_row}", $no++ );
			$_sheet->getStyle("A{$tb_row}")->applyFromArray( self::_get_style( 'tbody_center' ) );
			
			$_sheet->setCellValue("B{$tb_row}", DateTime::createFromFormat('Y-m-d H:i:s.u', $row->Tanggal)->format('d-m-Y') );
			$_sheet->getStyle("B{$tb_row}")->applyFromArray( self::_get_style( 'tbody_center' ) );
			
			$_sheet->setCellValue("C{$tb_row}", $row->NamaPasien );
			$_sheet->getStyle("C{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
			
			$_sheet->setCellValue("D{$tb_row}", $row->PenanggungNama );
			$_sheet->getStyle("D{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
			
			$_sheet->setCellValue("E{$tb_row}", $row->UmurThn ." Thn");
			$_sheet->getStyle("E{$tb_row}")->applyFromArray( self::_get_style( 'tbody_right' ) );
			
			$_sheet->setCellValue("F{$tb_row}", $row->Alamat);
			$_sheet->getStyle("F{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
			
			$_sheet->setCellValue("G{$tb_row}", $row->Gapah);
			$_sheet->getStyle("G{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
			
			$_sheet->setCellValue("H{$tb_row}", $row->UmurAnakTerakhir ." Thn");
			$_sheet->getStyle("H{$tb_row}")->applyFromArray( self::_get_style( 'tbody_right' ) );

			$_sheet->setCellValue("I{$tb_row}", $row->UmurKehamilan );
			$_sheet->getStyle("I{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );

			$_sheet->setCellValue("J{$tb_row}", $row->HariPertamaHaidTerakhir );
			$_sheet->getStyle("J{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
			
			$_sheet->setCellValue("K{$tb_row}", $row->TekananDarah ." mmHg" );
			$_sheet->getStyle("K{$tb_row}")->applyFromArray( self::_get_style( 'tbody_right' ) );

			$_sheet->setCellValue("L{$tb_row}", $row->BeratBadan ." KG");
			$_sheet->getStyle("L{$tb_row}")->applyFromArray( self::_get_style( 'tbody_right' ) );

			$_sheet->setCellValue("M{$tb_row}", $row->LingkarLengan ." CM" );
			$_sheet->getStyle("M{$tb_row}")->applyFromArray( self::_get_style( 'tbody_right' ) );

			$_sheet->setCellValue("N{$tb_row}", $row->HB == 1 ? '✓' : '' );
			$_sheet->getStyle("N{$tb_row}")->applyFromArray( self::_get_style( 'tbody_center' ) );

			$_sheet->setCellValue("O{$tb_row}", $row->PPIA == 1 ? '✓' : '' );
			$_sheet->getStyle("O{$tb_row}")->applyFromArray( self::_get_style( 'tbody_center' ) );

			$_sheet->setCellValue("P{$tb_row}", $row->HBSAG == 1 ? '✓' : '' );
			$_sheet->getStyle("P{$tb_row}")->applyFromArray( self::_get_style( 'tbody_center' ) );

			$_sheet->setCellValue("Q{$tb_row}", $row->SPILIS == 1 ? '✓' : '' );
			$_sheet->getStyle("Q{$tb_row}")->applyFromArray( self::_get_style( 'tbody_center' ) );

			$_sheet->setCellValue("R{$tb_row}", $row->ImunisasiTD == 1 ? '✓' : '' );
			$_sheet->getStyle("R{$tb_row}")->applyFromArray( self::_get_style( 'tbody_center' ) );

			$_sheet->setCellValue("S{$tb_row}", $row->KunjunganTrisemester );
			$_sheet->getStyle("S{$tb_row}")->applyFromArray( self::_get_style( 'tbody_center' ) );

			$_sheet->setCellValue("T{$tb_row}", $row->RiwayatPersalinan );
			$_sheet->getStyle("T{$tb_row}")->applyFromArray( self::_get_style( 'tbody_center' ) );

			$_sheet->setCellValue("U{$tb_row}", $row->ResikoKehamilan );
			$_sheet->getStyle("U{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );

			$_sheet->setCellValue("V{$tb_row}", $row->KomplikasiPersalinan );
			$_sheet->getStyle("V{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );

			$_sheet->setCellValue("W{$tb_row}", @$row->Rujuk );
			$_sheet->getStyle("W{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );

			$_sheet->setCellValue("X{$tb_row}", $row->Keterangan );
			$_sheet->getStyle("X{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
			
			$tb_row++;
			
		endforeach;
		
		/*$spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);*/
					
		// Rename worksheet
		$spreadsheet->getActiveSheet()->setTitle( 'REKAM MEDIK' );
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

	public static function get_spog_medical_record( $date_start, $date_end, $doctor_id )
	{
		
		$date_start = DateTime::createFromFormat('Y-m-d', $date_start)->setTime(0, 0);
		$date_start->add(new DateInterval('PT8H'));
		$date_end = DateTime::createFromFormat('Y-m-d', $date_end)->setTime(0, 0);
		$date_end->add(new DateInterval('P1DT8H'));
		
		$query = self::ci()->db->select('
								a.Tanggal, c.NamaPasien, c.PenanggungNama, b.UmurThn, c.Alamat, a.Gapah, 
								a.UmurAnakTerakhir, a.UmurKehamilan, a.HariPertamaHaidTerakhir,
								a.TekananDarah, a.BeratBadan, a.LingkarLengan, a.HB, a.PPIA, a.HBSAG, 
								a.SPILIS, a.ImunisasiTD, a.KunjunganTrisemester, a.RiwayatPersalinan,
								a.ResikoKehamilan, a.KomplikasiPersalinan, a.Keterangan
							')
							->from('SIMtrRJ a')
							->join('SIMtrRegistrasi b', 'a.RegNo = b.NoReg', 'INNER')
							->join('mPasien c', 'a.NRM = c.NRM', 'INNER')
							->where([
								'a.Jam >=' => $date_start->format('Y-m-d H:i:s'),
								'a.Jam <=' => $date_end->format('Y-m-d H:i:s'),
								'a.DokterID' => $doctor_id,
								'a.SectionID' => config_item('section_id'),
								'a.Batal' => 0,
								'b.StatusBayar' => 'Sudah Bayar' 
							])
							->get();
			
		$collection = ['data' => $query->result()];

		return $collection;
	}
	
	public static function export_excel_spa_medical_record( $date_start, $date_end, $doctor_id )
	{
		$_ci = self::ci();
		$_ci->load->model('supplier_model');
		$collection = self::get_spa_medical_record( $date_start, $date_end, $doctor_id );	
		$date_start = DateTime::createFromFormat("Y-m-d", $date_start );
		$date_end = DateTime::createFromFormat("Y-m-d", $date_end );
		$doctor = $_ci->supplier_model->get_by(['Kode_Supplier' => $doctor_id]);
		$file_name = sprintf('%s periode %s s/d %s ', 'Laporan REKAM MEDIK SPA', $date_start->format('d F Y'), $date_end->format('d F Y'));

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
				->setTitle( 'Laporan REKAM MEDIK SPA' )
				->setSubject( 'Laporan REKAM MEDIK SPA' )
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
		//$_sheet->getRowDimension('1')->setRowHeight(30);
		
		$_sheet->mergeCells("A3:H3");
		$_sheet->setCellValue("A3", "Dokter: ". $doctor->Nama_Supplier); 
		$_sheet->getStyle("A3")->applyFromArray(['font'  => ['bold'	=> TRUE, 'size'  => 11 ]]);
		
		$_sheet->mergeCells("A4:A6");
		$_sheet->setCellValue("A4", 'No'); 
		$_sheet->getStyle("A4:A6")->applyFromArray( self::_get_style( 'thead' ) );
		
		$_sheet->mergeCells("B4:C6");
		$_sheet->setCellValue("B4", 'NAMA BAYI/ORANGTUA'); 
		$_sheet->getStyle("B4:C6")->applyFromArray( self::_get_style( 'thead' ) );
		
		$_sheet->mergeCells("D4:D6");
		$_sheet->setCellValue("D4", 'JK L/P'); 
		$_sheet->getStyle("D4:D6")->applyFromArray( self::_get_style( 'thead' ) ); 
		
		$_sheet->mergeCells("E4:F6");
		$_sheet->setCellValue("E4", 'TGL LAHIR (UMUR)'); 
		$_sheet->getStyle("E4:F6")->applyFromArray( self::_get_style( 'thead' ) ); 
				
		$_sheet->mergeCells("G4:G6");
		$_sheet->setCellValue("G4", 'ALAMAT DESA WISMA'); 
		$_sheet->getStyle("G4:G6")->applyFromArray( self::_get_style( 'thead' ) ); 
		
		$_sheet->mergeCells("H4:U4");
		$_sheet->setCellValue("H4", 'VAKSIN'); 
		$_sheet->getStyle("H4:U4")->applyFromArray( self::_get_style( 'thead' ) ); 
		
		$_sheet->mergeCells("H5:H6");
		$_sheet->setCellValue("H5", 'HB 0 0-7 HARI'); 
		$_sheet->getStyle("H5:H6")->applyFromArray( self::_get_style( 'thead' ) ); 
		
		$_sheet->mergeCells("I5:I6");
		$_sheet->setCellValue("I5", 'BCG'); 
		$_sheet->getStyle("I5:I6")->applyFromArray( self::_get_style( 'thead' ) ); 
		
		$_sheet->mergeCells("J5:J6");
		$_sheet->setCellValue("J5", 'POLIO 1'); 
		$_sheet->getStyle("J5:J6")->applyFromArray( self::_get_style( 'thead' ) );
		
		$_sheet->mergeCells("K5:K6");
		$_sheet->setCellValue("K5", 'DPT/HB/Hib 1'); 
		$_sheet->getStyle("K5:K6")->applyFromArray( self::_get_style( 'thead' ) );
	
		$_sheet->mergeCells("L5:L6");
		$_sheet->setCellValue("L5", 'POLIO 2'); 
		$_sheet->getStyle("L5:L6")->applyFromArray( self::_get_style( 'thead' ) );
	
		$_sheet->mergeCells("M5:M6");
		$_sheet->setCellValue("M5", 'DPT/HB/Hib 2'); 
		$_sheet->getStyle("M5:M6")->applyFromArray( self::_get_style( 'thead' ) );
	
		$_sheet->mergeCells("N5:N6");
		$_sheet->setCellValue("N5", 'POLIO 3'); 
		$_sheet->getStyle("N5:N6")->applyFromArray( self::_get_style( 'thead' ) );

		$_sheet->mergeCells("O5:O6");
		$_sheet->setCellValue("O5", 'DPT/HB/Hib 3'); 
		$_sheet->getStyle("O5:O6")->applyFromArray( self::_get_style( 'thead' ) );

		$_sheet->mergeCells("P5:P6");
		$_sheet->setCellValue("P5", 'POLIO 4'); 
		$_sheet->getStyle("P5:P6")->applyFromArray( self::_get_style( 'thead' ) );

		$_sheet->mergeCells("Q5:Q6");
		$_sheet->setCellValue("Q5", 'DPT/HB/Hib 4'); 
		$_sheet->getStyle("Q5:Q6")->applyFromArray( self::_get_style( 'thead' ) );
		
		$_sheet->mergeCells("R5:T5");
		$_sheet->setCellValue("R5", 'IMUNISASI LANJUTAN'); 
		$_sheet->getStyle("R5:T5")->applyFromArray( self::_get_style( 'thead' ) );
		
		$_sheet->setCellValue("R6", 'DPT/HB/Hib'); 
		$_sheet->getStyle("R6")->applyFromArray( self::_get_style( 'thead' ) );

		$_sheet->setCellValue("S6", 'CAMPAK'); 
		$_sheet->getStyle("S6")->applyFromArray( self::_get_style( 'thead' ) );

		$_sheet->setCellValue("T6", 'IPV'); 
		$_sheet->getStyle("T6")->applyFromArray( self::_get_style( 'thead' ) );

		$_sheet->mergeCells("U5:U6");
		$_sheet->setCellValue("U5", 'KET'); 
		$_sheet->getStyle("U5:U6")->applyFromArray( self::_get_style( 'thead' ) );

		$tb_row = 7; $no = 1;
		foreach ( $collection['data'] as $row ):
			
			$_sheet->setCellValue("A{$tb_row}", $no++ );
			$_sheet->getStyle("A{$tb_row}")->applyFromArray( self::_get_style( 'tbody_center' ) );
			
			$_sheet->setCellValue("B{$tb_row}", $row->NamaPasien );
			$_sheet->getStyle("B{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
			
			$_sheet->setCellValue("C{$tb_row}", $row->PenanggungNama );
			$_sheet->getStyle("C{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
			
			$_sheet->setCellValue("D{$tb_row}", $row->JenisKelamin == 'M' ? 'L' : 'P' );
			$_sheet->getStyle("D{$tb_row}")->applyFromArray( self::_get_style( 'tbody_center' ) );
			
			$_sheet->setCellValue("E{$tb_row}", DateTime::createFromFormat('Y-m-d H:i:s.u', $row->TglLahir)->format('d-m-Y'));
			$_sheet->getStyle("E{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
			
			$_sheet->setCellValue("F{$tb_row}", calculate_age(DateTime::createFromFormat('Y-m-d H:i:s.u', $row->TglLahir)->format('Y-m-d')));
			$_sheet->getStyle("F{$tb_row}")->applyFromArray( self::_get_style( 'tbody_right' ) );
			
			$_sheet->setCellValue("G{$tb_row}", $row->Alamat);
			$_sheet->getStyle("G{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
			
			$_sheet->setCellValue("H{$tb_row}", $row->Z23_1 == 1 ? '✓' : '');
			$_sheet->getStyle("H{$tb_row}")->applyFromArray( self::_get_style( 'tbody_center' ) );

			$_sheet->setCellValue("I{$tb_row}", $row->z23_2 == 1 ? '✓' : '');
			$_sheet->getStyle("I{$tb_row}")->applyFromArray( self::_get_style( 'tbody_center' ) );

			$_sheet->setCellValue("J{$tb_row}", $row->Z23_3 == 1 ? '✓' : '' );
			$_sheet->getStyle("J{$tb_row}")->applyFromArray( self::_get_style( 'tbody_center' ) );
			
			$_sheet->setCellValue("K{$tb_row}", $row->z23_4 == 1 ? '✓' : '' );
			$_sheet->getStyle("K{$tb_row}")->applyFromArray( self::_get_style( 'tbody_center' ) );

			$_sheet->setCellValue("L{$tb_row}", $row->z23_5 == 1 ? '✓' : '');
			$_sheet->getStyle("L{$tb_row}")->applyFromArray( self::_get_style( 'tbody_center' ) );

			$_sheet->setCellValue("M{$tb_row}", $row->z23_6 == 1 ? '✓' : '' );
			$_sheet->getStyle("M{$tb_row}")->applyFromArray( self::_get_style( 'tbody_center' ) );

			$_sheet->setCellValue("N{$tb_row}", $row->Z23_7 == 1 ? '✓' : '' );
			$_sheet->getStyle("N{$tb_row}")->applyFromArray( self::_get_style( 'tbody_center' ) );

			$_sheet->setCellValue("O{$tb_row}", $row->Z23_8 == 1 ? '✓' : '' );
			$_sheet->getStyle("O{$tb_row}")->applyFromArray( self::_get_style( 'tbody_center' ) );

			$_sheet->setCellValue("P{$tb_row}", $row->Z23_9 == 1 ? '✓' : '' );
			$_sheet->getStyle("P{$tb_row}")->applyFromArray( self::_get_style( 'tbody_center' ) );

			$_sheet->setCellValue("Q{$tb_row}", $row->Z23_10 == 1 ? '✓' : '' );
			$_sheet->getStyle("Q{$tb_row}")->applyFromArray( self::_get_style( 'tbody_center' ) );

			$_sheet->setCellValue("R{$tb_row}", $row->Z23_11 == 1 ? '✓' : '' );
			$_sheet->getStyle("R{$tb_row}")->applyFromArray( self::_get_style( 'tbody_center' ) );

			$_sheet->setCellValue("S{$tb_row}", $row->Z23_12 == 1 ? '✓' : '' );
			$_sheet->getStyle("S{$tb_row}")->applyFromArray( self::_get_style( 'tbody_center' ) );

			$_sheet->setCellValue("T{$tb_row}", $row->Z23_13 == 1 ? '✓' : '' );
			$_sheet->getStyle("T{$tb_row}")->applyFromArray( self::_get_style( 'tbody_center' ) );

			$_sheet->setCellValue("U{$tb_row}", $row->Keterangan );
			$_sheet->getStyle("U{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
			
			$tb_row++;
			
		endforeach;
		
		/*$spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);*/
					
		// Rename worksheet
		$spreadsheet->getActiveSheet()->setTitle( 'REKAM MEDIK' );
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

	public static function get_spa_medical_record( $date_start, $date_end, $doctor_id )
	{
		
		$date_start = DateTime::createFromFormat('Y-m-d', $date_start)->setTime(0, 0);
		$date_start->add(new DateInterval('PT8H'));
		$date_end = DateTime::createFromFormat('Y-m-d', $date_end)->setTime(0, 0);
		$date_end->add(new DateInterval('P1DT8H'));
		
		$query = self::ci()->db->select("
								b.NamaPasien, b.PenanggungNama, b.JenisKelamin, b.TglLahir, b.Alamat,
								'Z23_1' = CASE WHEN c.KodeICD = 'Z23.1' THEN 1 ELSE 0  END,
								'z23_2' = CASE WHEN c.KodeICD = 'z23.2' THEN 1 ELSE 0  END,
								'Z23_3' = CASE WHEN c.KodeICD = 'Z23.3' THEN 1 ELSE 0  END,
								'z23_4' = CASE WHEN c.KodeICD = 'z23.4' THEN 1 ELSE 0  END,
								'z23_5' = CASE WHEN c.KodeICD = 'z23.5' THEN 1 ELSE 0  END,
								'z23_6' = CASE WHEN c.KodeICD = 'z23.6' THEN 1 ELSE 0  END,
								'Z23_7' = CASE WHEN c.KodeICD = 'Z23.7' THEN 1 ELSE 0  END,
								'Z23_8' = CASE WHEN c.KodeICD = 'Z23.8' THEN 1 ELSE 0  END,
								'Z23_9' = CASE WHEN c.KodeICD = 'Z23.9' THEN 1 ELSE 0  END,
								'Z23_10' = CASE WHEN c.KodeICD = 'Z23.10' THEN 1 ELSE 0  END,
								'Z23_11' = CASE WHEN c.KodeICD = 'Z23.11' THEN 1 ELSE 0  END,
								'Z23_12' = CASE WHEN c.KodeICD = 'Z23.12' THEN 1 ELSE 0  END,
								'Z23_13' = CASE WHEN c.KodeICD = 'Z23.13' THEN 1 ELSE 0  END,
								a.Keterangan
							")
							->from('SIMtrRJ a')
							->join('mPasien b', 'a.NRM = b.NRM', 'INNER')
							->join('SIMtrRJDiagnosaAwal c', 'a.NoBukti = c.NOBukti', 'INNER')
							->join('SIMtrRegistrasi d', 'a.RegNo = d.NoReg', 'INNER')
							->where_in('c.KodeICD', explode(",", config_item('IcdImmunization')))
							->where([
								'a.Jam >=' => $date_start->format('Y-m-d H:i:s'),
								'a.Jam <=' => $date_end->format('Y-m-d H:i:s'),
								'a.DokterID' => $doctor_id,
								'a.SectionID' => config_item('section_id'),
								'a.Batal' => 0,
								'd.StatusBayar' => 'Sudah Bayar' 
							])
							->get();
			
		$collection = ['data' => $query->result()];

		return $collection;
	}
	
	public static function export_excel_unit_performance( $date_start, $date_end )
	{
		$_ci = self::ci();
		$_ci->load->model('section_model');
		$collection = self::get_unit_performance( $date_start, $date_end, config_item('section_id') );	
		$date_start = DateTime::createFromFormat("Y-m-d", $date_start );
		$date_end = DateTime::createFromFormat("Y-m-d", $date_end );
		$section = $_ci->section_model->get_one(config_item('section_id'));
		$file_name = sprintf('%s %s periode %s s/d %s ', 'Laporan Kinerja Unit', $section->SectionName, $date_start->format('d F Y'), $date_end->format('d F Y'));

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
				->setTitle( 'Laporan REKAM MEDIK SPA' )
				->setSubject( 'Laporan REKAM MEDIK SPA' )
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
		//$_sheet->getRowDimension('1')->setRowHeight(30);		
		

		$tb_row = 3; $no = 1;
		foreach ($collection as $key => $groups) :
			$sub_total = 0;
			$_sheet->setCellValue("A{$tb_row}", $key); 
			$tb_row++;
		
			$_sheet->setCellValue("A{$tb_row}", 'Kelompok'); 
			$_sheet->getStyle("A{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) );
			$_sheet->setCellValue("B{$tb_row}", 'Kategori Servis'); 
			$_sheet->getStyle("B{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) );
			$_sheet->setCellValue("C{$tb_row}", 'Nama Servis'); 
			$_sheet->getStyle("C{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) );
			$_sheet->setCellValue("D{$tb_row}", 'Qty'); 
			$_sheet->getStyle("D{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) );
			$_sheet->setCellValue("E{$tb_row}", 'Jumlah'); 
			$_sheet->getStyle("E{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) );
			$tb_row++;
			
			$group_before = NULL;
			foreach ($groups as $group_k => $group):
				foreach ($group as $v): 
			
					$_sheet->setCellValue("A{$tb_row}", @$v->GroupJasaName == $group_before ? '' : $v->GroupJasaName );
					$_sheet->getStyle("A{$tb_row}")->applyFromArray( self::_get_style( 'tbody_center' ) );
					
					$_sheet->setCellValue("B{$tb_row}", @$v->KategoriJasaName );
					$_sheet->getStyle("B{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
					
					$_sheet->setCellValue("C{$tb_row}", @$v->JasaName );
					$_sheet->getStyle("C{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
					
					$_sheet->setCellValue("D{$tb_row}", @$v->QTy );
					$_sheet->getStyle("D{$tb_row}")->applyFromArray( self::_get_style( 'tbody_center' ) );
					
					$_sheet->setCellValue("E{$tb_row}", $v->Nilai);
					$_sheet->getStyle("E{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
					$_sheet->getStyle("E{$tb_row}")->applyFromArray( self::_get_style( 'currency' ) );
					
					$group_before = $v->GroupJasaName; $sub_total = $sub_total + $v->Nilai;
					$grand_total[ $key ] = @$grand_total[ $key ] + $v->Nilai;

					$tb_row++;				
				endforeach;
			endforeach;	
			
			$_sheet->mergeCells("A{$tb_row}:C{$tb_row}");
			$_sheet->setCellValue("A{$tb_row}", $key );
			$_sheet->getStyle("A{$tb_row}:C{$tb_row}")->applyFromArray( self::_get_style( 'sum_name' ) );
			
			$_sheet->mergeCells("D{$tb_row}:E{$tb_row}");
			$_sheet->setCellValue("D{$tb_row}", $sub_total );
			$_sheet->getStyle("D{$tb_row}:E{$tb_row}")->applyFromArray( self::_get_style( 'sum_value' ) );
			$_sheet->getStyle("D{$tb_row}:E{$tb_row}")->applyFromArray( self::_get_style( 'currency' ) );
			$tb_row++;
			
		endforeach;
		
		$tb_row++;	
		$gap = 0; 
		foreach ($grand_total as $key => $val) :
			$_sheet->setCellValue("A{$tb_row}", "Total {$key}"); 
			$_sheet->getStyle("A{$tb_row}")->applyFromArray( self::_get_style( 'sum_name_xl' ) );
			$_sheet->getStyle("A{$tb_row}")->applyFromArray( self::_get_style( 'currency' ) );
			$_sheet->getStyle("A{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
			
			$_sheet->setCellValue("B{$tb_row}", $val); 
			$_sheet->getStyle("B{$tb_row}")->applyFromArray( self::_get_style( 'sum_value_xl' ) );
			$_sheet->getStyle("B{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
			$_sheet->getStyle("B{$tb_row}")->applyFromArray( self::_get_style( 'currency' ) );
			
			$gap = abs( $gap - $val );
			$tb_row++;	
		endforeach;
		
		$_sheet->setCellValue("A{$tb_row}", "Balance"); 
		$_sheet->getStyle("A{$tb_row}")->applyFromArray( self::_get_style( 'sum_name_xl' ) );
		$_sheet->getStyle("A{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
		$_sheet->setCellValue("B{$tb_row}", $gap); 
		$_sheet->getStyle("B{$tb_row}")->applyFromArray( self::_get_style( 'sum_value_xl' ) );
		$_sheet->getStyle("B{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
		$_sheet->getStyle("B{$tb_row}")->applyFromArray( self::_get_style( 'currency' ) );
		
		/*$spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);*/
					
		// Rename worksheet
		$spreadsheet->getActiveSheet()->setTitle( 'Kinerja Unit' );
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
	
	public static function get_unit_performance( $date_start, $date_end, $Section_ID )
	{
		
		$db = self::ci()->db;
		
		// exec SIM_Rpt_KinerjaUnit_I '01-Dec-2009','28-Dec-2009','SEC098'
		$query = "exec SIM_Rpt_KinerjaUnit_I '$date_start', '$date_end', $Section_ID";
		
		$query = $db->query( $query );
		if( $query->num_rows() )
		{
			$collection = [];
			foreach( $query->result() as $item )
			{	
				// Pengelompokan jasa berdasarkan Kategori
				$collection[ $item->Kategori ][ $item->KategoriJasaName ][] = $item;
			}
			// Urutkan berdasarkan Kategorinya
			ksort($collection);
			
			return $collection;
		}			
		return FALSE;
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
		
		$style['tbody_center'] = array(
			'alignment' => array(
				'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
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
		
		$style['tbody_right'] = array(
			'alignment' => array(
				'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
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