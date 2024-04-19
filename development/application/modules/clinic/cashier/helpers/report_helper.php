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

	//function get total obat per no reg
	// private static function _get_total_obat( $NoReg )
	// {
	// 	$_ci = self::ci();
	// 	$query = $_ci->db->select_sum('Total', 'Obat')
	// 			->where([
	// 				'Retur' => 0,
	// 				'Batal' => 0,
	// 				'NoReg' => $NoReg
	// 			])
	// 			->get('BILLFarmasi')
	// 			->result();

	// 	return ($query[0]->Obat > 0) ? $query[0]->Obat : 0;
	// }

	//function get total pemeriksaan per no reg
	// private static function _get_total_pemeriksaaan_dokter( $NoReg )
	// {
	// 	$_ci = self::ci();
	// 	$query = $_ci->db->select("SUM(b.Qty * c.Harga) as Pemeriksaan")
	// 			->from('SIMtrRJ a')
	// 			->join('SIMtrRJTransaksi b','a.NoBukti = b.NoBukti','LEFT OUTER')
	// 			->join('SIMtrRJTransaksiDetail c','c.NoBukti = b.NoBukti','INNER')
	// 			->join('SIMmListJasa d','d.JasaID = b.JasaID','INNER')
	// 			->where([
	// 				'a.Batal' => 0,
	// 				'a.RegNo' => $NoReg
	// 			])
	// 			->get()
	// 			->result();
		
	// 	return ($query[0]->Pemeriksaan > 0) ? $query[0]->Pemeriksaan : 0;
	// }

	public static function get_income_recap( $date_start, $date_end, $tipe_pasien, $section_id, $doctor_id )
	{		
		$query = self::ci()->db->query("exec KlinikBMC_RekapTransaksiKasir '{$date_start}','{$date_end}','{$tipe_pasien}','{$section_id}','{$doctor_id}'");
		$obat_bebas = self::ci()->db->select("a.Tanggal,a.NoBukti, a.Total, b.NoBukti as NoBuktiPembayaran, c.NilaiDiscount")
							->from("BillFarmasi a")
							->join("SIMtrPembayaranObatBebas b","a.NoBukti = b.NoBuktiFarmasi","INNER")
							->join("SIMtrKasir c","a.NoReg = c.NoReg","LEFT OUTER")
							->where([
								"a.ObatBebas" => 1,
								"a.Batal" => 0,
								"a.Retur" => 0,
								"a.Tanggal >=" => $date_start,
								"a.Tanggal <=" => $date_end,
								"b.Batal" => 0
							])
							->order_by('a.Tanggal','ASC')
							->get();

		// response_json($obat_bebas->result());exit;

		$resep_luar = self::ci()->db->select("a.Tanggal,a.NoBukti, a.Total, b.NoBukti as NoBuktiPembayaran, c.NilaiDiscount")
							->from("BillFarmasi a")
							->join("SIMtrPembayaranObatBebas b","a.NoBukti = b.NoBuktiFarmasi","INNER")
							->join("SIMtrKasir c","a.NoReg = c.NoReg","LEFT OUTER")
							->where([
								"a.ResepLuar" => 1,
								"a.Batal" => 0,
								"a.Retur" => 0,
								"a.Tanggal >=" => $date_start,
								"a.Tanggal <=" => $date_end,
								"b.Batal" => 0
							])
							->order_by('a.Tanggal','ASC')
							->get();

		$collection = ['data' => [], 'obat' => [], 'resep_luar' => []];	
		//DATA PASIEN
		if( $query->num_rows() > 0 ):
			foreach($query->result() as $row)
			{
				// Get Nilai Obat
				// $row->Obat = self::_get_total_obat($row->NoReg);
				// $row->Dokter = self::_get_total_pemeriksaaan_dokter($row->NoReg);
				$row->JenisKelamin = ($row->JenisKelamin == 'M') ? 'Laki-Laki' : 'Perempuan';
				$collection['data'][$row->JenisKerjasama][] = (object) $row;
			}
		endif;
		//OBAT BEBAS
		if( $obat_bebas->num_rows() > 0 ):
			foreach($obat_bebas->result() as $row):
				$collection['obat'][] = (object) $row;
			endforeach;
		endif;

		//RESEP LUAR
		if( $resep_luar->num_rows() > 0 ):
			foreach($resep_luar->result() as $row):
				$collection['resep_luar'][] = (object) $row;
			endforeach;
		endif;

		return $collection;
	}


	public static function export_excel_income_recap(  $date_start, $date_end, $tipe_pasien, $section_id, $doctor_id)
	{
		$_ci = self::ci();
		$_ci->load->model([
			'section_model'
		]);

		$collection = self::get_income_recap( $date_start, $date_end, $tipe_pasien, $section_id, $doctor_id);	

		$date_start = DateTime::createFromFormat("Y-m-d", $date_start );
		$date_end = DateTime::createFromFormat("Y-m-d", $date_end );
		$file_name = sprintf('%s periode %s s/d %s ', 'Laporan Rekap Transaksi Kasir', $date_start->format('d F Y'), $date_end->format('d F Y'));
		
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
				->setTitle( 'Laporan Rekap Transaksi Kasir' )
				->setSubject( 'Laporan Rekap Transaksi Kasir' )
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

		$tb_row = 3;
		//PASIEN
		if(!empty($collection['data'])) : foreach ( $collection['data'] as $key => $value ):
			
			$_sheet->mergeCells("A{$tb_row}:J{$tb_row}");
			$_sheet->setCellValue("A{$tb_row}", $key); 
			$_sheet->getStyle("A{$tb_row}")->applyFromArray(['font'  => ['bold'	=> TRUE, 'size'  => 11 ]]);
			$tb_row++;

			$_sheet->setCellValue("A{$tb_row}", 'NO'); 
			$_sheet->getStyle("A{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) );
			$_sheet->setCellValue("B{$tb_row}", 'TANGGAL'); 
			$_sheet->getStyle("B{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) );
			$_sheet->setCellValue("C{$tb_row}", 'NO BUKTI'); 
			$_sheet->getStyle("C{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) ); 
			$_sheet->setCellValue("D{$tb_row}", 'NRM'); 
			$_sheet->getStyle("D{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) ); 
			$_sheet->setCellValue("E{$tb_row}", 'NAMA PASIEN'); 
			$_sheet->getStyle("E{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) ); 
			$_sheet->setCellValue("F{$tb_row}", 'TIPE PASIEN'); 
			$_sheet->getStyle("F{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) ); 
			$_sheet->setCellValue("G{$tb_row}", 'PEMERIKSAAN'); 
			$_sheet->getStyle("G{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) );
			$_sheet->setCellValue("H{$tb_row}", 'TINDAKAN'); 
			$_sheet->getStyle("H{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) );
			$_sheet->setCellValue("I{$tb_row}", 'OBAT'); 
			$_sheet->getStyle("I{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) );
			$_sheet->setCellValue("J{$tb_row}", 'Diskon'); 
			$_sheet->getStyle("J{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) );
			$_sheet->setCellValue("K{$tb_row}", 'TOTAL'); 
			$_sheet->getStyle("K{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) );
			$tb_row++;
			
			$total_pemeriksaan = 0; $total_tindakan = 0; $total_obat = 0; $total_diskon = 0; $grandtotal = 0; $no = 1;
			if(!empty($value)) : foreach ($value as $row):
					$total 	= @$row->Tindakan + @$row->Obat - @$row->NilaiDiscount;
					$total_tindakan += @$row->Tindakan; 
					$total_pemeriksaan += @$row->Dokter; 
					$total_obat += @$row->Obat; 
					$total_diskon += @$row->NilaiDiscount;
					$grandtotal += $total;

					$_sheet->setCellValue("A{$tb_row}", $no++);
					$_sheet->getStyle("A{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
					$_sheet->setCellValue("B{$tb_row}", date('d/m/Y', strtotime(@$row->TglClosing)));
					$_sheet->getStyle("B{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
					$_sheet->setCellValue("C{$tb_row}", @$row->NoBukti);
					$_sheet->getStyle("C{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
					$_sheet->setCellValue("D{$tb_row}",  @$row->NRM);
					$_sheet->getStyle("D{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
					$_sheet->setCellValue("E{$tb_row}", @$row->NamaPasien);
					$_sheet->getStyle("E{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
					$_sheet->setCellValue("F{$tb_row}", @$row->JenisKerjasama);
					$_sheet->getStyle("F{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
					$_sheet->setCellValue("G{$tb_row}",  @$row->Dokter);
					$_sheet->getStyle("G{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
					$_sheet->setCellValue("H{$tb_row}", @$row->Tindakan);
					$_sheet->getStyle("H{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
					$_sheet->setCellValue("I{$tb_row}", @$row->Obat);
					$_sheet->getStyle("I{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
					$_sheet->setCellValue("J{$tb_row}", @$row->NilaiDiscount);
					$_sheet->getStyle("J{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
					$_sheet->setCellValue("K{$tb_row}", @$total);
					$_sheet->getStyle("K{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
					
					$_sheet->getStyle("G{$tb_row}:K{$tb_row}")->applyFromArray( self::_get_style( 'currency' ) );
					$tb_row++;
				endforeach;
			endif;

			$_sheet->mergeCells("A{$tb_row}:F{$tb_row}");
			$_sheet->setCellValue("A{$tb_row}", 'GRAND TOTAL');

			$_sheet->setCellValue("G{$tb_row}", $total_pemeriksaan);
			$_sheet->getStyle("G{$tb_row}")->applyFromArray( self::_get_style( 'currency' ) );
			$_sheet->getStyle("G{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );

			$_sheet->setCellValue("H{$tb_row}", $total_tindakan);
			$_sheet->getStyle("H{$tb_row}")->applyFromArray( self::_get_style( 'currency' ) );
			$_sheet->getStyle("H{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );

			$_sheet->setCellValue("I{$tb_row}", $total_obat);
			$_sheet->getStyle("I{$tb_row}")->applyFromArray( self::_get_style( 'currency' ) );
			$_sheet->getStyle("I{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );

			$_sheet->setCellValue("J{$tb_row}", $total_diskon);
			$_sheet->getStyle("J{$tb_row}")->applyFromArray( self::_get_style( 'currency' ) );
			$_sheet->getStyle("J{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );

			$_sheet->setCellValue("K{$tb_row}", $grandtotal);
			$_sheet->getStyle("K{$tb_row}")->applyFromArray( self::_get_style( 'currency' ) );
			$_sheet->getStyle("K{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );

			$_sheet->getStyle("A{$tb_row}:K{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) );
			$_sheet->getStyle("A{$tb_row}")->applyFromArray(['font'  => ['bold'	=> TRUE, 'size'  => 10 ]]);
			$tb_row++;
			$tb_row++;
			
		endforeach; endif;


		//OBAT BEBAS
			$_sheet->mergeCells("A{$tb_row}:J{$tb_row}");
			$_sheet->setCellValue("A{$tb_row}", "OBAT BEBAS"); 
			$_sheet->getStyle("A{$tb_row}")->applyFromArray(['font'  => ['bold'	=> TRUE, 'size'  => 11 ]]);
			$tb_row++;

			$_sheet->setCellValue("A{$tb_row}", 'NO'); 
			$_sheet->getStyle("A{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) );
			$_sheet->setCellValue("B{$tb_row}", 'TANGGAL'); 
			$_sheet->getStyle("B{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) );
			$_sheet->setCellValue("C{$tb_row}", 'NO BUKTI'); 
			$_sheet->getStyle("C{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) ); 
			$_sheet->setCellValue("D{$tb_row}", 'NRM'); 
			$_sheet->getStyle("D{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) ); 
			$_sheet->setCellValue("E{$tb_row}", 'NAMA PASIEN'); 
			$_sheet->getStyle("E{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) ); 
			$_sheet->setCellValue("F{$tb_row}", 'TIPE PASIEN'); 
			$_sheet->getStyle("F{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) ); 
			$_sheet->setCellValue("G{$tb_row}", 'DOKTER'); 
			$_sheet->getStyle("G{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) );
			$_sheet->setCellValue("H{$tb_row}", 'TINDAKAN'); 
			$_sheet->getStyle("H{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) );
			$_sheet->setCellValue("I{$tb_row}", 'OBAT'); 
			$_sheet->getStyle("I{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) );
			$_sheet->setCellValue("J{$tb_row}", 'Diskon'); 
			$_sheet->getStyle("J{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) );
			$_sheet->setCellValue("K{$tb_row}", 'TOTAL'); 
			$_sheet->getStyle("K{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) );
			$tb_row++;
			
			$GrandTotalObat = 0; $GrandTotalDisc = 0; $GrandTotal = 0; $Total = 0; $no = 1;
			if(!empty($collection['obat'])) : foreach ($collection['obat'] as $row): 
					$GrandTotalObat += @$row->Total;
					$GrandTotalDisc += @$row->NilaiDiscount;
					$Total = @$row->Total - @$row->NilaiDiscount;
					$GrandTotal += $Total;

					$_sheet->setCellValue("A{$tb_row}", $no++);
					$_sheet->getStyle("A{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
					$_sheet->setCellValue("B{$tb_row}", date('d/m/Y', strtotime(@$row->Tanggal)));
					$_sheet->getStyle("B{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
					$_sheet->setCellValue("C{$tb_row}", @$row->NoBuktiPembayaran);
					$_sheet->getStyle("C{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
					$_sheet->setCellValue("D{$tb_row}",  "");
					$_sheet->getStyle("D{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
					$_sheet->setCellValue("E{$tb_row}", "");
					$_sheet->getStyle("E{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
					$_sheet->setCellValue("F{$tb_row}", "");
					$_sheet->getStyle("F{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
					$_sheet->setCellValue("G{$tb_row}",  "");
					$_sheet->getStyle("G{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
					$_sheet->setCellValue("H{$tb_row}", 0);
					$_sheet->getStyle("H{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
					$_sheet->setCellValue("I{$tb_row}", @$row->Total);
					$_sheet->getStyle("I{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
					$_sheet->setCellValue("J{$tb_row}", @$row->NilaiDiscount);
					$_sheet->getStyle("J{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
					$_sheet->setCellValue("K{$tb_row}", @$Total);
					$_sheet->getStyle("K{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
					
					$_sheet->getStyle("H{$tb_row}:K{$tb_row}")->applyFromArray( self::_get_style( 'currency' ) );
					$tb_row++;
				endforeach;
			endif;

			$_sheet->mergeCells("A{$tb_row}:F{$tb_row}");
			$_sheet->setCellValue("A{$tb_row}", 'GRAND TOTAL');

			$_sheet->setCellValue("G{$tb_row}", 0);
			$_sheet->getStyle("G{$tb_row}")->applyFromArray( self::_get_style( 'currency' ) );
			$_sheet->getStyle("G{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );

			$_sheet->setCellValue("H{$tb_row}", 0);
			$_sheet->getStyle("H{$tb_row}")->applyFromArray( self::_get_style( 'currency' ) );
			$_sheet->getStyle("H{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );

			$_sheet->setCellValue("I{$tb_row}", $GrandTotalObat);
			$_sheet->getStyle("I{$tb_row}")->applyFromArray( self::_get_style( 'currency' ) );
			$_sheet->getStyle("I{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );

			$_sheet->setCellValue("J{$tb_row}", $GrandTotalDisc);
			$_sheet->getStyle("J{$tb_row}")->applyFromArray( self::_get_style( 'currency' ) );
			$_sheet->getStyle("J{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );

			$_sheet->setCellValue("K{$tb_row}", $GrandTotal);
			$_sheet->getStyle("K{$tb_row}")->applyFromArray( self::_get_style( 'currency' ) );
			$_sheet->getStyle("K{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );

			$_sheet->getStyle("A{$tb_row}:K{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) );
			$_sheet->getStyle("A{$tb_row}")->applyFromArray(['font'  => ['bold'	=> TRUE, 'size'  => 10 ]]);
			$tb_row++;
			$tb_row++;


			//RESEP LUAR
			$_sheet->mergeCells("A{$tb_row}:J{$tb_row}");
			$_sheet->setCellValue("A{$tb_row}", "RESEP LUAR"); 
			$_sheet->getStyle("A{$tb_row}")->applyFromArray(['font'  => ['bold'	=> TRUE, 'size'  => 11 ]]);
			$tb_row++;

			$_sheet->setCellValue("A{$tb_row}", 'NO'); 
			$_sheet->getStyle("A{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) );
			$_sheet->setCellValue("B{$tb_row}", 'TANGGAL'); 
			$_sheet->getStyle("B{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) );
			$_sheet->setCellValue("C{$tb_row}", 'NO BUKTI'); 
			$_sheet->getStyle("C{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) ); 
			$_sheet->setCellValue("D{$tb_row}", 'NRM'); 
			$_sheet->getStyle("D{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) ); 
			$_sheet->setCellValue("E{$tb_row}", 'NAMA PASIEN'); 
			$_sheet->getStyle("E{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) ); 
			$_sheet->setCellValue("F{$tb_row}", 'TIPE PASIEN'); 
			$_sheet->getStyle("F{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) ); 
			$_sheet->setCellValue("G{$tb_row}", 'DOKTER'); 
			$_sheet->getStyle("G{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) );
			$_sheet->setCellValue("H{$tb_row}", 'TINDAKAN'); 
			$_sheet->getStyle("H{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) );
			$_sheet->setCellValue("I{$tb_row}", 'OBAT'); 
			$_sheet->getStyle("I{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) );
			$_sheet->setCellValue("J{$tb_row}", 'Diskon'); 
			$_sheet->getStyle("J{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) );
			$_sheet->setCellValue("K{$tb_row}", 'TOTAL'); 
			$_sheet->getStyle("K{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) );
			$tb_row++;
				
				$GrandTotalResepLuar = 0; $GrandTotalDisc = 0; $GrandTotal = 0; $Total = 0; $no = 1;
				if(!empty($collection['resep_luar'])) : foreach ($collection['resep_luar'] as $row):
						$GrandTotalResepLuar += @$row->Total;
						$GrandTotalDisc += @$row->NilaiDiscount;
						$Total = @$row->Total - @$row->NilaiDiscount;
						$GrandTotal += $Total;
	
						$_sheet->setCellValue("A{$tb_row}", $no++);
						$_sheet->getStyle("A{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
						$_sheet->setCellValue("B{$tb_row}", date('d/m/Y', strtotime(@$row->Tanggal)));
						$_sheet->getStyle("B{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
						$_sheet->setCellValue("C{$tb_row}", @$row->NoBuktiPembayaran);
						$_sheet->getStyle("C{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
						$_sheet->setCellValue("D{$tb_row}",  "");
						$_sheet->getStyle("D{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
						$_sheet->setCellValue("E{$tb_row}", "");
						$_sheet->getStyle("E{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
						$_sheet->setCellValue("F{$tb_row}", "");
						$_sheet->getStyle("F{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
						$_sheet->setCellValue("G{$tb_row}",  "");
						$_sheet->getStyle("G{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
						$_sheet->setCellValue("H{$tb_row}", 0);
						$_sheet->getStyle("H{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
						$_sheet->setCellValue("I{$tb_row}", @$row->Total);
						$_sheet->getStyle("I{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
						$_sheet->setCellValue("J{$tb_row}", @$row->NilaiDiscount);
						$_sheet->getStyle("J{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
						$_sheet->setCellValue("K{$tb_row}", @$Total);
						$_sheet->getStyle("K{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
						
						$_sheet->getStyle("H{$tb_row}:K{$tb_row}")->applyFromArray( self::_get_style( 'currency' ) );
						$tb_row++;
					endforeach;
				endif;
	
				$_sheet->mergeCells("A{$tb_row}:F{$tb_row}");
				$_sheet->setCellValue("A{$tb_row}", 'GRAND TOTAL');
	
				$_sheet->setCellValue("G{$tb_row}", 0);
				$_sheet->getStyle("G{$tb_row}")->applyFromArray( self::_get_style( 'currency' ) );
				$_sheet->getStyle("G{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
	
				$_sheet->setCellValue("H{$tb_row}", 0);
				$_sheet->getStyle("H{$tb_row}")->applyFromArray( self::_get_style( 'currency' ) );
				$_sheet->getStyle("H{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
	
				$_sheet->setCellValue("I{$tb_row}", $GrandTotalResepLuar);
				$_sheet->getStyle("I{$tb_row}")->applyFromArray( self::_get_style( 'currency' ) );
				$_sheet->getStyle("I{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
	
				$_sheet->setCellValue("J{$tb_row}", $GrandTotalDisc);
				$_sheet->getStyle("J{$tb_row}")->applyFromArray( self::_get_style( 'currency' ) );
				$_sheet->getStyle("J{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );

				$_sheet->setCellValue("K{$tb_row}", $GrandTotal);
				$_sheet->getStyle("K{$tb_row}")->applyFromArray( self::_get_style( 'currency' ) );
				$_sheet->getStyle("K{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
	
				$_sheet->getStyle("A{$tb_row}:K{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) );
				$_sheet->getStyle("A{$tb_row}")->applyFromArray(['font'  => ['bold'	=> TRUE, 'size'  => 10 ]]);
				$tb_row++;
			
					
					
		// Rename worksheet
		$spreadsheet->getActiveSheet()->setTitle( 'LAPORAN REKAP TRANSAKSI KASIR' );
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

	public static function get_daily_cash_report_fo( $date_start, $date_end, $user_id, $shift)
	{		
		$query = self::ci()->db->query("exec SIM_Rpt_LaporanKasHarianFO '{$date_start}','{$date_end}',{$user_id},{$shift}");

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
