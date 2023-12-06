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
					$item->NamaPasien = !empty($data->NamaPasien) ? $data->NamaPasien : $item->Keterangan ;
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

	
	public static function export_purchase_receipt($date_start, $date_end, $location, $supplier, $opsi, $payment_type)
	{
		$_ci = self::ci();

		$payment_type_name = [
			'' => '(Semua tipe bayar)',
			1 => '(Kredit)',
			2 => '(Tunai)',
			3 => '(Konsinyasi)',
		];

		$section = $_ci->section_model->get_by(['Lokasi_ID' => $location]);

		$collection = self::get_purchase_receipt($date_start, $date_end, $location, $supplier, $opsi, $payment_type);
		$date_start = DateTime::createFromFormat("Y-m-d", $date_start);
		$date_end = DateTime::createFromFormat("Y-m-d", $date_end);
		$file_name = sprintf("%s %s s/d %s", 'Laporan Penerimaan Pembelian ' . $payment_type_name[$payment_type], $date_start->format(' d F Y'), $date_end->format('d F Y'));

		$helper = new Sample();
		if ($helper->isCli()) {
			$helper->log('403. Forbidden Access!' . PHP_EOL);
			return false;
		}

		// Create new Spreadsheet object
		$spreadsheet = new Spreadsheet();

		// Set document properties
		$spreadsheet->getProperties()->setCreator(config_item("company_name"))
			->setLastModifiedBy(config_item("company_name"))
			->setTitle('Laporan Penerimaan Pembelian {$section->SectionName} ' . $payment_type_name[$payment_type])
			->setSubject('Laporan Penerimaan Pembelian {$section->SectionName} ' . $payment_type_name[$payment_type])
			->setDescription($file_name)
			->setKeywords($file_name);

		$_sheet = $spreadsheet->setActiveSheetIndex(0);

		// Default Style		
		$spreadsheet->getDefaultStyle()->applyFromArray(self::_get_style('default'));

		$_sheet->mergeCells("A1:I1");
		$_sheet->setCellValue('A1', $file_name);
		$_sheet->getStyle("A1")->applyFromArray(self::_get_style('header'));
		$_sheet->getStyle("A1")->getAlignment()->setWrapText(true);

		$tb_row = 4;
		$grand_total = 0;
		foreach ($collection as $supplier => $facturs) :

			$_sheet->setCellValue("A{$tb_row}", 'Supplier');
			$_sheet->setCellValue("B{$tb_row}", ': ' . $supplier);
			$_sheet->getStyle("B{$tb_row}")->applyFromArray(['font'  => ['bold'	=> TRUE, 'size'  => 11]]);
			$tb_row++;

			foreach ($facturs as $factur => $items) :

				$_sheet->setCellValue("A{$tb_row}", 'Nomor');
				$_sheet->setCellValue("B{$tb_row}", ': ' . $factur);
				$_sheet->getStyle("B{$tb_row}")->applyFromArray(['font'  => ['bold'	=> TRUE, 'size'  => 11]]);
				$do_row = $tb_row;

				$tb_row++;
				$_sheet->setCellValue("A{$tb_row}", 'NO');
				$_sheet->getStyle("A{$tb_row}")->applyFromArray(self::_get_style('thead'));
				$_sheet->setCellValue("B{$tb_row}", 'Tanggal');
				$_sheet->getStyle("B{$tb_row}")->applyFromArray(self::_get_style('thead'));
				$_sheet->setCellValue("C{$tb_row}", 'Keterangan');
				$_sheet->getStyle("C{$tb_row}")->applyFromArray(self::_get_style('thead'));
				$_sheet->setCellValue("D{$tb_row}", 'Jumlah');
				$_sheet->getStyle("D{$tb_row}")->applyFromArray(self::_get_style('thead'));
				$_sheet->setCellValue("E{$tb_row}", 'Satuan');
				$_sheet->getStyle("E{$tb_row}")->applyFromArray(self::_get_style('thead'));
				$_sheet->setCellValue("F{$tb_row}", 'Harga@Satuan');
				$_sheet->getStyle("F{$tb_row}")->applyFromArray(self::_get_style('thead'));
				$_sheet->setCellValue("G{$tb_row}", 'Diskon %');
				$_sheet->getStyle("G{$tb_row}")->applyFromArray(self::_get_style('thead'));
				$_sheet->setCellValue("H{$tb_row}", 'Total');
				$_sheet->getStyle("H{$tb_row}")->applyFromArray(self::_get_style('thead'));
				$tb_row++;

				$sub_total = 0;
				$no = 1;
				$tb_start = $tb_row;
				foreach ($items  as  $item) :
					$item = (object) $item;
					$_sheet->setCellValue("A{$tb_row}", $no++);
					$_sheet->getStyle("A{$tb_row}")->applyFromArray(self::_get_style('tbody'));
					$_sheet->setCellValue("B{$tb_row}", substr($item->Tgl_Penerimaan, 0, 10));
					$_sheet->getStyle("B{$tb_row}")->applyFromArray(self::_get_style('tbody'));
					$_sheet->setCellValue("C{$tb_row}", $item->Nama_Barang);
					$_sheet->getStyle("C{$tb_row}")->applyFromArray(self::_get_style('tbody'));
					$_sheet->setCellValue("D{$tb_row}", $item->Qty);
					$_sheet->getStyle("D{$tb_row}")->applyFromArray(self::_get_style('tbody'));
					$_sheet->setCellValue("E{$tb_row}", $item->Kode_Satuan);
					$_sheet->getStyle("E{$tb_row}")->applyFromArray(self::_get_style('tbody'));
					$_sheet->setCellValue("F{$tb_row}", $item->Harga_Beli);
					$_sheet->getStyle("F{$tb_row}")->applyFromArray(self::_get_style('tbody'));
					$_sheet->setCellValue("G{$tb_row}", $item->Diskon_1);
					$_sheet->getStyle("G{$tb_row}")->applyFromArray(self::_get_style('tbody'));
					$_sheet->setCellValue("H{$tb_row}", $item->sub_total);
					$_sheet->getStyle("H{$tb_row}")->applyFromArray(self::_get_style('tbody'));

					$_sheet->getStyle("F{$tb_row}")->applyFromArray(self::_get_style('currency'));
					$_sheet->getStyle("H{$tb_row}")->applyFromArray(self::_get_style('currency'));

					$sub_total = $sub_total + $item->sub_total;
					$tb_row++;
				endforeach; // end Item


				$_sheet->setCellValue("G{$do_row}", 'No DO');
				$_sheet->setCellValue("H{$do_row}", ': ' . $item->No_DO);
				$_sheet->getStyle("H{$do_row}")->applyFromArray(['font'  => ['bold'	=> TRUE, 'size'  => 11]]);

				$_sheet->mergeCells("A{$tb_row}:G{$tb_row}");
				$_sheet->setCellValue("A{$tb_row}", 'SUB TOTAL');
				$_sheet->getStyle("A{$tb_row}:H{$tb_row}")->applyFromArray(self::_get_style('tfoot_value'));
				$_sheet->setCellValue("H{$tb_row}", $sub_total);
				$_sheet->getStyle("H{$tb_row}")->applyFromArray(self::_get_style('thead'));
				$_sheet->getStyle("H{$tb_row}")->applyFromArray(self::_get_style('currency'));
				$tb_row++;

				$_sheet->mergeCells("A{$tb_row}:G{$tb_row}");
				$_sheet->setCellValue("A{$tb_row}", 'POTONGAN');
				$_sheet->getStyle("A{$tb_row}:H{$tb_row}")->applyFromArray(self::_get_style('tfoot_value'));
				$_sheet->setCellValue("H{$tb_row}", $item->Potongan);
				$_sheet->getStyle("H{$tb_row}")->applyFromArray(self::_get_style('thead'));
				$_sheet->getStyle("H{$tb_row}")->applyFromArray(self::_get_style('currency'));
				$tb_row++;

				$_sheet->mergeCells("A{$tb_row}:G{$tb_row}");
				$_sheet->setCellValue("A{$tb_row}", 'PPN');
				$_sheet->getStyle("A{$tb_row}:H{$tb_row}")->applyFromArray(self::_get_style('tfoot_value'));
				$_sheet->setCellValue("H{$tb_row}", $item->PPN);
				$_sheet->getStyle("H{$tb_row}")->applyFromArray(self::_get_style('thead'));
				$_sheet->getStyle("H{$tb_row}")->applyFromArray(self::_get_style('currency'));
				$tb_row++;

				$_sheet->mergeCells("A{$tb_row}:G{$tb_row}");
				$_sheet->setCellValue("A{$tb_row}", 'ONGKOS KIRIM');
				$_sheet->getStyle("A{$tb_row}:H{$tb_row}")->applyFromArray(self::_get_style('tfoot_value'));
				$_sheet->setCellValue("H{$tb_row}", $item->Ongkos_Angkut);
				$_sheet->getStyle("H{$tb_row}")->applyFromArray(self::_get_style('thead'));
				$_sheet->getStyle("H{$tb_row}")->applyFromArray(self::_get_style('currency'));
				$tb_row++;

				$_sheet->mergeCells("A{$tb_row}:G{$tb_row}");
				$_sheet->setCellValue("A{$tb_row}", 'TOTAL');
				$_sheet->getStyle("A{$tb_row}:H{$tb_row}")->applyFromArray(self::_get_style('tfoot_value'));
				$_sheet->setCellValue("H{$tb_row}", $item->Total_Nilai);
				$_sheet->getStyle("H{$tb_row}")->applyFromArray(self::_get_style('thead'));
				$_sheet->getStyle("H{$tb_row}")->applyFromArray(self::_get_style('currency'));
				$tb_row++;

				$grand_total = $grand_total + $item->Total_Nilai;

			endforeach; // end Facturs
			$tb_row++;
		endforeach; // end Collection

		$_sheet->mergeCells("A{$tb_row}:G{$tb_row}");
		$_sheet->setCellValue("A{$tb_row}", 'GRAND TOTAL');
		$_sheet->getStyle("A{$tb_row}:H{$tb_row}")->applyFromArray(self::_get_style('tfoot_value'));
		$_sheet->setCellValue("H{$tb_row}", $grand_total);
		$_sheet->getStyle("H{$tb_row}")->applyFromArray(self::_get_style('thead'));
		$_sheet->getStyle("H{$tb_row}")->applyFromArray(self::_get_style('currency'));


		// Rename worksheet
		$spreadsheet->getActiveSheet()->setTitle('Penerimaan Pemebelian');
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

	public static function get_purchase_receipt($date_start, $date_end, $location, $supplier, $opsi, $payment_type)
	{

		$db = self::ci()->db;

		if ($opsi == 'by_supplier' && !empty($supplier))
			$db->where('b.Supplier_ID', $supplier);

		if (!empty($payment_type)) {
			$db->where('b.Type_Pembayaran', $payment_type);
		}

		$db->select(" 
				b.Tgl_Penerimaan, b.No_Penerimaan, c.Nama_Barang, (a.Qty_PO - a.Qty_Telah_Terima) AS Qty, 
				b.IncludePPN, a.Kode_Satuan, a.Harga_Beli, a.Diskon_1, a.Diskon_Rp, 
				(a.Harga_Beli * (a.Qty_PO - a.Qty_Telah_Terima) - Diskon_Rp ) AS sub_total,
				d.Nama_Supplier, b.Pajak AS PPN, b.Total_Nilai, b.No_DO, b.Potongan, b.Ongkos_Angkut
			")
			->from("BL_trPenerimaanDetail a")
			->join("BL_trPenerimaan b", "a.Penerimaan_ID = b.Penerimaan_ID", "INNER")
			->join("mbarang c", "c.Barang_ID = a.Barang_ID", "INNER")
			->join("mSupplier d", "b.Supplier_ID = d.Supplier_ID", "INNER")
			->where([
				'c.Kelompok' => 'OBAT',
				'b.Tgl_Penerimaan >=' => $date_start,
				'b.Tgl_Penerimaan <=' => $date_end,
				'b.Status_Batal' => 0,
				'b.Lokasi_ID' => $location
			])
			->order_by('b.Supplier_ID, b.Penerimaan_ID');;

		$query = $db->get();
		$collection = [];
		if ($query->num_rows()) :
			foreach ($query->result() as $item) :

				$collection[$item->Nama_Supplier][$item->No_Penerimaan][] = $item;

			endforeach;
			ksort($collection);
			return $collection;
		endif;

		return $collection;
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
	
	public static function export_excel_inventory_value( $date, $location, $group )
	{
		$_ci = self::ci();
		
		$section = $_ci->section_model->get_by(['Lokasi_ID' => $location]);
		
		$collection = [];
		$query = "exec SIM_Rpt_NilaiPersediaanObat '{$date}', '{$group}', {$location}";
		$get_all = $_ci->db->query( $query )->result();
		foreach($get_all as $row ):
			$collection[$row->KelompokJenis][] = $row;
		endforeach;
		
		$date = DateTime::createFromFormat("Y-m-d", $date );
		$file_name = sprintf('%s %s %s', 'Laporan Nilai Persediaan', $date->format('d F Y'), $section->SectionName);
		
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
				->setTitle( 'Laporan Nilai Persediaan {$section->SectionName}' )
				->setSubject( 'Laporan Nilai Persediaan {$section->SectionName}' )
				->setDescription( $file_name )
				->setKeywords( $file_name)
				;
		
		$_sheet = $spreadsheet->setActiveSheetIndex( 0 );
		
		// Default Style
		
		$spreadsheet->getDefaultStyle()->applyFromArray( self::_get_style( 'default' ) );
		
		$_sheet->mergeCells("A1:J1");
		$_sheet->setCellValue('A1', $file_name );
		$_sheet->getStyle("A1")->applyFromArray( self::_get_style( 'header' ) );
		$_sheet->getStyle("A1")->getAlignment()->setWrapText(true);
		
		$_sheet->mergeCells("B2:J2");
		$_sheet->setCellValue('B2', "Section {$section->SectionName}" );
		$_sheet->getStyle("B2")->applyFromArray( self::_get_style( 'header' ) );
		$_sheet->getStyle("B2")->getAlignment()->setWrapText(true);
		//$_sheet->getRowDimension('1')->setRowHeight(30);

		$tb_row = 4; $sub_total = 0; $no = 1;
		foreach ( $collection as $type_group => $items ):
			
			$_sheet->mergeCells("A{$tb_row}:J{$tb_row}");
			$_sheet->setCellValue("A{$tb_row}", $type_group); 
			$_sheet->getStyle("A{$tb_row}")->applyFromArray(['font'  => ['bold'	=> TRUE, 'size'  => 11 ]]);
			$tb_row++;
			
			$_sheet->setCellValue("A{$tb_row}", 'NO'); 
			$_sheet->getStyle("A{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) );
			$_sheet->setCellValue("B{$tb_row}", 'KODE'); 
			$_sheet->getStyle("B{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) );
			$_sheet->setCellValue("C{$tb_row}", 'NAMA BARANG'); 
			$_sheet->getStyle("C{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) ); 
			$_sheet->setCellValue("D{$tb_row}", 'SA'); 
			$_sheet->getStyle("D{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) ); 
			$_sheet->setCellValue("E{$tb_row}", 'IN'); 
			$_sheet->getStyle("E{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) ); 
			$_sheet->setCellValue("F{$tb_row}", 'OUT'); 
			$_sheet->getStyle("F{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) ); 
			$_sheet->setCellValue("G{$tb_row}", 'SATUAN'); 
			$_sheet->getStyle("G{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) );
			$_sheet->setCellValue("H{$tb_row}", 'QTY STOCK'); 
			$_sheet->getStyle("H{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) );
			//$_sheet->setCellValue("I{$tb_row}", 'HARGA@'); 
			$_sheet->setCellValue("I{$tb_row}", 'H.RATA@'); 
			$_sheet->getStyle("I{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) );
			$_sheet->setCellValue("J{$tb_row}", 'NILAI PERSEDIAAN'); 
			$_sheet->getStyle("J{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) );
			$tb_row++;
			
			$tb_start = $tb_row; 
			foreach($items  as  $item):
					$item = (object) $item;
					$_sheet->setCellValue("A{$tb_row}", $no++ );
					$_sheet->getStyle("A{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
					$_sheet->setCellValue("B{$tb_row}", @$item->Kode_Barang);
					$_sheet->getStyle("B{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
					$_sheet->setCellValue("C{$tb_row}", $item->Nama_Barang);
					$_sheet->getStyle("C{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
					$_sheet->setCellValue("D{$tb_row}", $item->SA);
					$_sheet->getStyle("D{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
					$_sheet->setCellValue("E{$tb_row}", $item->IN);
					$_sheet->getStyle("E{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
					$_sheet->setCellValue("F{$tb_row}", $item->OUT);
					$_sheet->getStyle("F{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
					$_sheet->setCellValue("G{$tb_row}", $item->Kode_Satuan );
					$_sheet->getStyle("G{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
					$_sheet->setCellValue("H{$tb_row}", $item->Qty );
					$_sheet->getStyle("H{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
					//$_sheet->setCellValue("I{$tb_row}", $item->Harga_Satuan);
					$_sheet->setCellValue("I{$tb_row}", $item->HRataRata);
					$_sheet->getStyle("I{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
					//$_sheet->setCellValue("J{$tb_row}", $item->NilaiPersediaan );
					$_sheet->setCellValue("J{$tb_row}", $item->HRataRata * $item->Qty );
					$_sheet->getStyle("J{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
					
					$_sheet->getStyle("I{$tb_row}:J{$tb_row}")->applyFromArray( self::_get_style( 'currency' ) );
					$tb_row++;
			endforeach;
		endforeach;
		
		$tb_sum_till = $tb_row - 1;
		$tb_row++;
			
		$_sheet->mergeCells("A{$tb_row}:I{$tb_row}");
		$_sheet->setCellValue("A{$tb_row}", 'TOTAL PERSEDIAAN');
		$_sheet->getStyle("A{$tb_row}:I{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
		$_sheet->getStyle("A{$tb_row}")->applyFromArray(['font'  => ['bold'	=> TRUE, 'size'  => 11 ]]);
		$_sheet->setCellValue("J{$tb_row}", "=SUM(J6:J{$tb_sum_till})");
		$_sheet->getStyle("J{$tb_row}")->applyFromArray( self::_get_style( 'sum_value' ) );
		$_sheet->getStyle("J{$tb_row}")->applyFromArray( self::_get_style( 'currency' ) );
		$tb_row++;
			
		/*$spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);*/
					
		// Rename worksheet
		$spreadsheet->getActiveSheet()->setTitle( 'Nilai Persediaan' );
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
	
	public static function export_excel_recap_transactions( $date_start, $date_end )
	{
		$_ci = self::ci();
		
		$collection = self::get_recap_transactions( $date_start, $date_end );	
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

		$tb_row = 4; $sub_total = 0;
		foreach ( $collection['data'] as $doctor => $transaction ):
			
			$_sheet->mergeCells("A{$tb_row}:G{$tb_row}");
			$_sheet->setCellValue("A{$tb_row}", "Dokter: ". $doctor); 
			$_sheet->getStyle("A{$tb_row}")->applyFromArray(['font'  => ['bold'	=> TRUE, 'size'  => 11 ]]);
			$tb_row++;
			
			$_sheet->setCellValue("A{$tb_row}", 'TRANSAKSI'); 
			$_sheet->getStyle("A{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) );
			$_sheet->setCellValue("B{$tb_row}", 'ITEM'); 
			$_sheet->getStyle("B{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) );
			$_sheet->setCellValue("C{$tb_row}", 'QTY'); 
			$_sheet->getStyle("C{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) ); 
			$_sheet->setCellValue("D{$tb_row}", 'NILAI'); 
			$_sheet->getStyle("D{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) ); 
			$_sheet->setCellValue("E{$tb_row}", 'JASA APOTEK'); 
			$_sheet->getStyle("E{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) ); 
			$_sheet->setCellValue("F{$tb_row}", 'SUBTOTAL'); 
			$_sheet->getStyle("F{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) ); 
			$_sheet->setCellValue("G{$tb_row}", 'DISKON'); 
			$_sheet->getStyle("G{$tb_row}")->applyFromArray( self::_get_style( 'thead' ) );
			$tb_row++;
			
			$tb_start = $tb_row; $total_per_doctor = 0;
			foreach($transaction as $evidence_number => $items):
				$tb_start_patient = $tb_row;
				$_sheet->setCellValue("A{$tb_row}", $evidence_number);
				foreach($items as  $item):
					$item = (object) $item;
					$_sheet->getStyle("A{$tb_row}")->applyFromArray( self::_get_style( 'tbody_merge' ) );
					$_sheet->setCellValue("B{$tb_row}", @$item->NamaObat);
					$_sheet->getStyle("B{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
					$_sheet->setCellValue("C{$tb_row}", $item->Qty);
					$_sheet->getStyle("C{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
					$_sheet->setCellValue("D{$tb_row}", $item->Barang_ID != 0 ? $item->Nilai : $item->Nilai);
					$_sheet->getStyle("D{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
					$_sheet->setCellValue("E{$tb_row}", $item->Barang_ID != 0 ? $item->JasaResep : $item->JasaResep);
					$_sheet->getStyle("E{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
					$_sheet->setCellValue("F{$tb_row}", "=(C{$tb_row} * D{$tb_row}) + E{$tb_row} + {$item->HExt}");
					$_sheet->getStyle("F{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
					$_sheet->setCellValue("G{$tb_row}", $item->Diskon > 0 ? "= {$item->Qty} * {$item->Nilai} * {$item->Diskon} / 100" : '');
					$_sheet->getStyle("G{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
					
					$_sheet->getStyle("D{$tb_row}:G{$tb_row}")->applyFromArray( self::_get_style( 'currency' ) );
					$tb_row++;
				endforeach;
				$tb_row--;
				$_sheet->mergeCells("A{$tb_start_patient}:A{$tb_row}");
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
			$tb_row++;
			
			$_sheet->mergeCells("A{$tb_row}:F{$tb_row}");
			$_sheet->setCellValue("A{$tb_row}", 'GRANDTOTAL');
			$_sheet->getStyle("A{$tb_row}:F{$tb_row}")->applyFromArray( self::_get_style( 'tbody' ) );
			$_sheet->getStyle("A{$tb_row}")->applyFromArray(['font'  => ['bold'	=> TRUE, 'size'  => 10 ]]);
			$_sheet->getStyle("G{$tb_row}")->applyFromArray( self::_get_style( 'sum_value' ) );
			$_sheet->getStyle("G{$tb_row}")->applyFromArray( self::_get_style( 'currency' ) );
			$_sheet->setCellValue("G{$tb_row}", "=SUM(F{$tb_start}:F{$tb_sum_till}) - SUM(G{$tb_start}:G{$tb_sum_till})");
			$tb_row += 2;
		endforeach;
		
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

	public static function get_recap_transactions( $date_start, $date_end )
	{
		
		$date_start = DateTime::createFromFormat('Y-m-d', $date_start)->setTime(0, 0);
		$date_start->add(new DateInterval('PT8H'));
		$date_end = DateTime::createFromFormat('Y-m-d', $date_end)->setTime(0, 0);
		$date_end->add(new DateInterval('P1DT8H'));
		
		//$query = "exec SIM_RptFarmasi_RekapTransaksi '$date_start', '$date_end', '$SectionID'";
		$section = self::ci()->db->where('SectionID', config_item('section_id'))->get('SIMmSection')->row();
		$query = self::ci()->db->query("
			SELECT 
				d.NoBukti, a.Jam, a.ObatBebas, e.Nama_Supplier, c.NRM, c.NamaPasien, a.Keterangan, 
				b.Barang_ID, b.Nama_Barang, b.JmlObat, b.Harga AS Nilai, b.HExt, b.Disc, b.NamaResepObat, b.BiayaResep
			FROM BILLFarmasi a
				INNER JOIN BILLFarmasiDetail b ON b.NoBukti = a.NoBukti
				LEFT OUTER JOIN VW_Registrasi c ON c.NoReg = a.NoReg
				INNER JOIN SIMtrPembayaranObatBebas d ON d.NoBuktiFarmasi = a.NoBukti
				LEFT OUTER JOIN mSupplier e ON a.DokterID = e.Kode_SUpplier
			WHERE RIGHT(LEFT(LTRIM(a.NoBukti),9),3) = '{$section->KodeNoBukti}'
				AND a.Jam >= '". $date_start->format('Y-m-d H:i:s') ."' AND a.Jam <= '". $date_end->format('Y-m-d H:i:s') ."'
				AND a.IncludeJasa = 0 AND a.Batal = 0 AND a.Retur = 0 AND d.Batal = 0 AND a.SectionID = '".config_item('section_id')."'
		");
			
		$collection = ['data' => [], 'payment' => [], 'merchan' => []];
		foreach( $query->result() as $row )
		{	
			$collection['data'][ $row->Nama_Supplier ][$row->NoBukti .' => '. $row->Keterangan][] = [
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
		$collection['merchan'] = self::ci()->db->select("a.Keterangan AS NamaPasien, b.NilaiPembayaranCC AS Nilai")
											->from('BILLFarmasi a')
											->join('SIMtrPembayaranObatBebas b', 'b.NoBuktiFarmasi = a.NoBukti', 'INNER')
											->where([
												'a.Jam >=' => $date_start->format('Y-m-d H:i:s'), 
												'a.Jam <=' => $date_end->format('Y-m-d H:i:s'),
												'a.Batal' => 0,
												'a.Retur' => 0,
												'a.IncludeJasa' => 0,
												'b.Batal' => 0,		
												'b.NilaiPembayaranCC >' => 0,
												'a.SectionID' => config_item('section_id')
											])
											->get()
											->result();
		
		// Total Jenis Pembayaran
		$collection['payment'] = self::ci()->db->select("
												SUM(b.NilaiPembayaran) AS Tunai, 
												SUM(b.NilaiPembayaranCC) AS Bank, 
												SUM(b.NilaiPembayaranBPJS) AS BPJS, 
												SUM(b.NilaiPembayaranIKS) AS IKS
											")
											->from('BILLFarmasi a')
											->join('SIMtrPembayaranObatBebas b', 'b.NoBuktiFarmasi = a.NoBukti', 'INNER')
											->where([
												'a.Jam >=' => $date_start->format('Y-m-d H:i:s'), 
												'a.Jam <=' => $date_end->format('Y-m-d H:i:s'),
												'a.Batal' => 0,
												'a.Retur' => 0,
												'a.IncludeJasa' => 0,
												'b.Batal' => 0,		
												'a.SectionID' => config_item('section_id')
											])
											->get()
											->row();
		
		return $collection;
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

	public static function get_all_customer()
	{
		
		$db = self::ci()->db;
		
		$db_from = "mCustomer a";
		
		$query = $db->from($db_from)
					->get()
					;
					
		if( $query->num_rows() > 0 )
		{			
			$collection = array();		
			foreach ($query->result() as $row )
			{
				$collection[$row->Kode_Customer] = $row;
			}
			
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