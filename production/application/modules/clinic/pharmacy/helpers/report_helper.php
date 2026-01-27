<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Borders;

final class report_helper
{

	public static function get_warehouse_cards($date_start, $date_end, $BarangID, $LokasiID, $JenisBarangID = 0)
	{

		$db = self::ci()->db;

		//exce SIM_Rpt_KartuGudang_FIFO '2016-07-01','2016-07-30',387,296,0 	
		$query = "exec SIM_Rpt_KartuGudang_FIFO '$date_start', '$date_end', $BarangID, $LokasiID, $JenisBarangID";

		$query = $db->query($query);
		if ($query->num_rows()) {
			$collection = array();
			foreach ($query->result() as $item) {
				//select top 100 * from BILLFarmasi where NoBukti ='161101APT-00031'
				//select * from mSupplier where Kode_Supplier = 'DS019'
				//select * from mPasien where NRM = '09.12.19'
				if ($item->Kartu_ID > 0) {
					$data = $db->select("b.Nama_Supplier, c.NamaPasien, c.Alamat, d.Nama_Asli, d.Nama_Singkat, a.Keterangan, a.NoReg")
						->from("BILLFarmasi a")
						->join("mSupplier b", "a.DokterID = b.Kode_Supplier", "LEFT OUTER")
						->join("mPasien c", "a.NRM = c.NRM", "LEFT OUTER")
						->join("mUser d", "a.UserID = d.User_ID", "LEFT OUTER")
						->where("a.NoBukti", $item->No_Bukti)
						->get()->row();
					// print_r($data);
					// exit;
					$item->Nama_Supplier = !empty($data->Nama_Supplier) ? $data->Nama_Supplier : '-';
					if (@$data->NoReg == '-') {
						$item->NamaPasien = !empty(@$data->NamaPasien) ? 'OB - ' . @$data->NamaPasien : 'OB - ' . @$data->Keterangan;
					} else {
						$item->NamaPasien = !empty(@$data->NamaPasien) ? @$data->NamaPasien : @$item->Keterangan;
					}
					$item->Alamat = !empty(@$data->Alamat) ? @$data->Alamat : '-';
					$item->Nama_Asli = !empty(@$data->Nama_Asli) ? @$data->Nama_Asli : '-';
					$item->Nama_Singkat = !empty(@$data->Nama_Singkat) ? @$data->Nama_Singkat : '-';
				}

				$collection[] = $item;
			}

			return $collection;
		}

		return FALSE;
	}

	public static function get_recap_stocks($date_start, $date_end, $Lokasi_ID)
	{

		$db = self::ci()->db;

		// exec SIM_Rpt_RekapStok_FIFO '2016-11-01','2016-11-30',296
		$query = "exec SIM_Rpt_RekapStok_FIFO '$date_start', '$date_end', $Lokasi_ID";

		$query = $db->query($query);
		if ($query->num_rows()) {
			// Ambil data barang sesuai section, untuk mencari Kelompok jenis barang.
			$barang_section = self::get_barang_section($Lokasi_ID);

			$collection = array();
			foreach ($query->result() as $item) {
				// Pengelompokan Barang Berdasarkan Kelompok Jenis
				$collection[$barang_section[$item->KOde_Barang]->KelompokJenis][] = $item;
			}

			// Urutkan berdasarkan Kelompok Jenisnya
			ksort($collection);

			return $collection;
		}

		return FALSE;
	}

	public static function get_daily_stock_recap_data($date_start, $date_end, $Lokasi_ID)
	{
		$db = self::ci()->db;

		// exec SIM_Rpt_RekapStok_FIFO '2016-11-01','2016-11-30',296
		$query = "exec SIM_Rpt_RekapStok_FIFO '$date_start', '$date_end', $Lokasi_ID";

		$query = $db->query($query);
		if ($query->num_rows()) {
			$collection = array();
			foreach ($query->result() as $item) {
				// Pengelompokan Barang Berdasarkan Kelompok Jenis (NmJenis from SP)
				$group_name = !empty($item->NmJenis) ? $item->NmJenis : 'Lain-lain';
				$collection[$group_name][] = $item;
			}

			// Urutkan berdasarkan Kelompok Jenisnya
			ksort($collection);

			return $collection;
		}

		return FALSE;
	}

	public static function export_excel_stock_opname($date_start, $date_end, $section_id, $show_zero_difference = 0)
	{
		$_ci = self::ci();
		$_ci->load->model([
			'supplier_model',
			'section_model'
		]);

		$section = $_ci->section_model->get_one($section_id);
		$collection = self::get_stock_opname($date_start, $date_end, $section_id);
		$date_start = DateTime::createFromFormat("Y-m-d", $date_start);
		$date_end = DateTime::createFromFormat("Y-m-d", $date_end);
		$file_name = sprintf('%s %s periode %s s/d %s ', 'Laporan Stok Opname', $section->SectionName, $date_start->format('d F Y'), $date_end->format('d F Y'));

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
			->setTitle("Laporan Stok Opname {$section->SectionName}")
			->setSubject("Laporan Stok Opname {$section->SectionName}")
			->setDescription($file_name)
			->setKeywords($file_name);

		$setActiveSheetIndex = 0;
		$_sheet = $spreadsheet->setActiveSheetIndex($setActiveSheetIndex++);
		$spreadsheet->getActiveSheet()->setTitle($section->SectionName);

		// Default Style			
		//$spreadsheet->getDefaultStyle()->applyFromArray( self::_get_style( 'default' ) );

		$_sheet->mergeCells("A1:J1");
		$_sheet->setCellValue('A1', $file_name);
		$_sheet->getStyle("A1")->applyFromArray(self::_get_style('header'));
		$_sheet->getStyle("A1")->getAlignment()->setWrapText(true);
		$_sheet->getRowDimension('1')->setRowHeight(30);

		$tb_row = 3;
		foreach ($collection as $k_group => $v_group) :
			$_sheet->setCellValue("A{$tb_row}", $k_group);
			$_sheet->getStyle("A{$tb_row}")->applyFromArray(self::_get_style('header'));
			$_sheet->getStyle("A{$tb_row}")->getAlignment()->setWrapText(true);
			$tb_row++;

			if (!empty($v_group)) : foreach ($v_group as $k_date => $v_date) :
					list($date, $user, $evidence_number) = explode("|", $k_date);

					$_sheet->mergeCells("A{$tb_row}:C{$tb_row}");
					$_sheet->setCellValue("A{$tb_row}", "No. Bukti : {$evidence_number}");
					$_sheet->mergeCells("D{$tb_row}:F{$tb_row}");
					$_sheet->setCellValue("D{$tb_row}", "Tanggal " . substr($date, 0, 10));
					$_sheet->mergeCells("G{$tb_row}:I{$tb_row}");
					$_sheet->setCellValue("G{$tb_row}", "User : {$user}");
					$tb_row++;

					$_sheet->setCellValue("A{$tb_row}", 'NO');
					$_sheet->getStyle("A{$tb_row}")->applyFromArray(self::_get_style('thead'));
					$_sheet->setCellValue("B{$tb_row}", 'Kode');
					$_sheet->getStyle("B{$tb_row}")->applyFromArray(self::_get_style('thead'));
					$_sheet->setCellValue("C{$tb_row}", 'Barang');
					$_sheet->getStyle("C{$tb_row}")->applyFromArray(self::_get_style('thead'));
					$_sheet->setCellValue("D{$tb_row}", 'Satuan');
					$_sheet->getStyle("D{$tb_row}")->applyFromArray(self::_get_style('thead'));
					$_sheet->setCellValue("E{$tb_row}", 'Qty Sistem');
					$_sheet->getStyle("E{$tb_row}")->applyFromArray(self::_get_style('thead'));
					$_sheet->setCellValue("F{$tb_row}", 'Qty Fisik');
					$_sheet->getStyle("F{$tb_row}")->applyFromArray(self::_get_style('thead'));
					$_sheet->setCellValue("G{$tb_row}", 'Selisih');
					$_sheet->getStyle("G{$tb_row}")->applyFromArray(self::_get_style('thead'));
					$_sheet->setCellValue("H{$tb_row}", '@Harga');
					$_sheet->getStyle("H{$tb_row}")->applyFromArray(self::_get_style('thead'));
					$_sheet->setCellValue("I{$tb_row}", 'Jumlah');
					$_sheet->getStyle("I{$tb_row}")->applyFromArray(self::_get_style('thead'));
					$_sheet->setCellValue("J{$tb_row}", 'Keterangan');
					$_sheet->getStyle("J{$tb_row}")->applyFromArray(self::_get_style('thead'));

					$tb_row++;

					$no = 1;
					$sub_total = 0;
					if (!empty($v_date)) :
						foreach ($v_date as $row) :

							if ($show_zero_difference === 0 &&  @$row->Selisih == 0)
								continue;

							$_sheet->setCellValue("A{$tb_row}", $no++);
							$_sheet->getStyle("A{$tb_row}")->applyFromArray(self::_get_style('tbody'));
							$_sheet->setCellValue("B{$tb_row}", @$row->Kode_Barang);
							$_sheet->getStyle("B{$tb_row}")->applyFromArray(self::_get_style('tbody'));
							$_sheet->setCellValue("C{$tb_row}", $row->Nama_Barang);
							$_sheet->getStyle("C{$tb_row}")->applyFromArray(self::_get_style('tbody'));
							$_sheet->setCellValue("D{$tb_row}", $row->Satuan_Stok);
							$_sheet->getStyle("D{$tb_row}")->applyFromArray(self::_get_style('tbody'));
							$_sheet->setCellValue("E{$tb_row}", @$row->Stock_Akhir);
							$_sheet->getStyle("E{$tb_row}")->applyFromArray(self::_get_style('tbody'));
							$_sheet->setCellValue("F{$tb_row}", $row->Qty_Opname);
							$_sheet->getStyle("F{$tb_row}")->applyFromArray(self::_get_style('tbody'));
							$_sheet->setCellValue("G{$tb_row}", $row->Selisih);
							$_sheet->getStyle("G{$tb_row}")->applyFromArray(self::_get_style('tbody'));
							$_sheet->setCellValue("H{$tb_row}", $row->Harga_Rata);
							$_sheet->getStyle("H{$tb_row}")->applyFromArray(self::_get_style('currency'));
							$_sheet->getStyle("H{$tb_row}")->applyFromArray(self::_get_style('tbody'));
							$_sheet->setCellValue("I{$tb_row}", @$row->Selisih * @$row->Harga_Rata);
							$_sheet->getStyle("I{$tb_row}")->applyFromArray(self::_get_style('currency'));
							$_sheet->getStyle("I{$tb_row}")->applyFromArray(self::_get_style('tbody'));
							$_sheet->setCellValue("J{$tb_row}", $row->Keterangan);
							$_sheet->getStyle("J{$tb_row}")->applyFromArray(self::_get_style('tbody'));

							$sub_total += @$row->Selisih * @$row->Harga_Rata;
							$tb_row++;
						endforeach;
						$_sheet->mergeCells("A{$tb_row}:H{$tb_row}");
						$_sheet->setCellValue("A{$tb_row}", 'Subtotal');
						$_sheet->getStyle("A{$tb_row}:H{$tb_row}")->applyFromArray(self::_get_style('tbody'));
						$_sheet->getStyle("A{$tb_row}")->applyFromArray(self::_get_style('tfoot_value'));
						$_sheet->setCellValue("I{$tb_row}", $sub_total);
						$_sheet->getStyle("I{$tb_row}")->applyFromArray(self::_get_style('tfoot_value'));
						$_sheet->getStyle("I{$tb_row}")->applyFromArray(self::_get_style('currency'));
						$_sheet->setCellValue("J{$tb_row}", "");
					endif;

					$tb_row += 2;
				endforeach;
			endif;
		endforeach;



		// Rename worksheet
		//$spreadsheet->getActiveSheet()->setTitle( 'REKAP' );
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

	public static function get_used_drugs($date_start, $date_end, $section_id, $kerjasama_id)
	{

		$db = self::ci()->db;

		// exec FAR_Laporan_Penggunaan_Obat '2016-11-01','2016-11-30','SECT0002','3'
		$query = "exec FAR_Laporan_Penggunaan_Obat '$date_start', '$date_end', '$section_id','$kerjasama_id'";

		$query = $db->query($query);
		if ($query->num_rows()) {
			$collection = array();
			foreach ($query->result() as $item) {
				// Pengelompokan Opname Berdasarkan Section Name
				$collection[$item->SectionName][] = $item;
			}

			return $collection;
		}

		return FALSE;
	}

	public static function get_top_drugs($date_start, $date_end)
	{

		$db = self::ci()->db;

		$query = "
		select TOP 10 c.Barang_ID, c.Nama_Barang as NamaResepObat, COUNT(C.Nama_Barang) as Jumlah from BILLFarmasi a 
			join BILLFarmasiDetail b on a.NoBukti = b.NoBukti
			join mBarang c on b.Barang_ID = c.Barang_ID
			where Tanggal >= '$date_start'
			and Tanggal <= '$date_end'
			and c.KelompokGrading = 'OTC'
			and c.KelompokJenis != 'PAKET BHP'
			group by c.Nama_Barang, c.Barang_ID
			order by Jumlah DESC
				";

		$query = $db->query($query);
		if ($query->num_rows()) {
			$collection = array();
			foreach ($query->result() as $item) {
				// Pengelompokan Opname Berdasarkan Section Name
				$HargaGrading = $db->query("Select * from dbo.GetHargaObatNew_WithStok(3,'xx',0,$item->Barang_ID,0,'" . 'SECT0002' . "',0)")->row();
				$item->Harga = $HargaGrading->Harga_Baru;
				$collection[] = $item;
			}

			return $collection;
		}

		return FALSE;
	}
	public static function get_stock_opname($date_start, $date_end, $section_id)
	{

		$db = self::ci()->db;

		// exec SIM_Rpt_DataOpname_Total '2016-11-01','2016-11-30','APOTEK'
		$query = "exec SIM_Rpt_DataOpname_Total '$date_start', '$date_end', '$section_id'";

		$query = $db->query($query);
		if ($query->num_rows()) {
			$collection = array();
			foreach ($query->result() as $item) {
				// Pengelompokan Opname Berdasarkan Kelompk (Positif, Negatif), Tanggal, User, & No_Bukti opname
				$collection[$item->Kelompok][$item->Tgl_Opname . "|" . $item->Nama_Asli . "|" . $item->No_Bukti][] = $item;
			}

			if (!empty($collection['POSITIF']))
				// Urutkan Berdasarkan Tanggal
				ksort($collection['POSITIF']);

			if (!empty($collection['NEGATIF']))
				// Urutkan Berdasarkan Tanggal
				ksort($collection['NEGATIF']);

			// urutkan negatifnya lebih dahulu
			ksort($collection);

			return $collection;
		}

		return FALSE;
	}

	public static function export_excel_recap_transactions($date_start, $date_end, $section_id, $shift_id, $user_id)
	{
		$_ci = self::ci();

		$collection = self::get_recap_transactions($date_start, $date_end, $section_id, $shift_id, $user_id);
		$shift = $_ci->db->where("IDShift", $shift_id)->get("SIMmShift")->row();
		$user = $_ci->db->where("User_ID", $user_id)->get("mUser")->row();
		$date_start = DateTime::createFromFormat("Y-m-d", $date_start);
		$date_end = DateTime::createFromFormat("Y-m-d", $date_end);
		$file_name = sprintf('%s periode %s s/d %s ', lang('reports:recap_transaction_label'), $date_start->format('d F Y'), $date_end->format('d F Y'));

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
			->setTitle('Laporan Rekap Pendapatan dengan Grup Jasa')
			->setSubject('Laporan Rekap Pendapatan dengan Grup Jasa')
			->setDescription($file_name)
			->setKeywords($file_name);

		$_sheet = $spreadsheet->setActiveSheetIndex(0);

		// Default Style

		$spreadsheet->getDefaultStyle()->applyFromArray(self::_get_style('default'));

		$_sheet->mergeCells("A1:G1");
		$_sheet->setCellValue('A1', $file_name);
		$_sheet->getStyle("A1")->applyFromArray(self::_get_style('header'));
		$_sheet->getStyle("A1")->getAlignment()->setWrapText(true);
		//$_sheet->getRowDimension('1')->setRowHeight(30);

		$_sheet->mergeCells("A3:G3");
		$_sheet->setCellValue("A3", sprintf("%s : %s", 'User', (!empty(@$user->Nama_Asli)) ? @$user->Nama_Asli : 'Semua'));
		$_sheet->getStyle("A3")->applyFromArray(['font'  => ['bold'	=> TRUE, 'size'  => 11]]);

		$_sheet->mergeCells("A4:G4");
		$_sheet->setCellValue("A4", sprintf("%s : %s", 'Shift', (!empty(@$shift->Deskripsi)) ? @$shift->Deskripsi : 'Semua'));
		$_sheet->getStyle("A4")->applyFromArray(['font'  => ['bold'	=> TRUE, 'size'  => 11]]);

		$tb_row = 6;
		$grandtotal = 0;
		foreach ($collection['data'] as $key => $transaction) :

			$_sheet->mergeCells("A{$tb_row}:G{$tb_row}");
			$_sheet->setCellValue("A{$tb_row}", $key);
			$_sheet->getStyle("A{$tb_row}")->applyFromArray(['font'  => ['bold'	=> TRUE, 'size'  => 11]]);
			$tb_row++;

			$_sheet->setCellValue("A{$tb_row}", 'TRANSAKSI');
			$_sheet->getStyle("A{$tb_row}")->applyFromArray(self::_get_style('thead'));
			$_sheet->setCellValue("B{$tb_row}", 'TIPE PASIEN');
			$_sheet->getStyle("B{$tb_row}")->applyFromArray(self::_get_style('thead'));
			$_sheet->setCellValue("C{$tb_row}", 'ITEM');
			$_sheet->getStyle("C{$tb_row}")->applyFromArray(self::_get_style('thead'));
			$_sheet->setCellValue("D{$tb_row}", 'QTY');
			$_sheet->getStyle("D{$tb_row}")->applyFromArray(self::_get_style('thead'));
			$_sheet->setCellValue("E{$tb_row}", 'NILAI');
			$_sheet->getStyle("E{$tb_row}")->applyFromArray(self::_get_style('thead'));
			$_sheet->setCellValue("F{$tb_row}", 'JASA APOTEK');
			$_sheet->getStyle("F{$tb_row}")->applyFromArray(self::_get_style('thead'));
			$_sheet->setCellValue("G{$tb_row}", 'SUBTOTAL');
			$_sheet->getStyle("G{$tb_row}")->applyFromArray(self::_get_style('thead'));
			$_sheet->setCellValue("H{$tb_row}", 'DISKON');
			$_sheet->getStyle("H{$tb_row}")->applyFromArray(self::_get_style('thead'));
			$tb_row++;

			$tb_start = $tb_row;
			$total_subtotal = 0;
			$total_nilai = 0;
			$total_jasa_resep = 0;
			$diskon_total = 0;
			foreach ($transaction as $evidence_number => $items) :
				$tb_start_patient = $tb_row;
				//$_sheet->setCellValue("A{$tb_row}", $evidence_number);
				foreach ($items as  $item) :
					$item = (object) $item;
					$sub_total = $item->Qty * $item->Nilai + $item->JasaResep + $item->HExt;
					$total_subtotal += $sub_total;
					$total_nilai += $item->Nilai;
					$total_jasa_resep += $item->JasaResep;
					$diskon = $item->Qty * $item->Nilai * $item->Diskon / 100;
					$diskon_total += $diskon;

					$_sheet->setCellValue("A{$tb_row}", $evidence_number);
					//$_sheet->getStyle("A{$tb_row}")->applyFromArray( self::_get_style( 'tbody_merge' ) );
					$_sheet->getStyle("A{$tb_row}")->applyFromArray(self::_get_style('tbody'));
					$_sheet->setCellValue("B{$tb_row}", @$item->JenisKerjasama);
					$_sheet->getStyle("B{$tb_row}")->applyFromArray(self::_get_style('tbody'));
					$_sheet->setCellValue("C{$tb_row}", $item->NamaObat);
					$_sheet->getStyle("C{$tb_row}")->applyFromArray(self::_get_style('tbody'));
					$_sheet->setCellValue("D{$tb_row}", $item->Qty);
					$_sheet->getStyle("D{$tb_row}")->applyFromArray(self::_get_style('tbody'));
					$_sheet->setCellValue("E{$tb_row}", $item->Barang_ID != 0 ? $item->Nilai : $item->Nilai);
					$_sheet->getStyle("E{$tb_row}")->applyFromArray(self::_get_style('tbody'));
					$_sheet->setCellValue("F{$tb_row}", $item->Barang_ID != 0 ? $item->JasaResep : $item->JasaResep);
					$_sheet->getStyle("F{$tb_row}")->applyFromArray(self::_get_style('tbody'));
					$_sheet->setCellValue("G{$tb_row}",  "=(D{$tb_row} * E{$tb_row}) + F{$tb_row} + {$item->HExt}");
					$_sheet->getStyle("G{$tb_row}")->applyFromArray(self::_get_style('tbody'));
					$_sheet->setCellValue("H{$tb_row}", $item->Diskon > 0 ? "= {$item->Qty} * {$item->Nilai} * {$item->Diskon} / 100" : '');
					$_sheet->getStyle("H{$tb_row}")->applyFromArray(self::_get_style('tbody'));

					$_sheet->getStyle("E{$tb_row}:H{$tb_row}")->applyFromArray(self::_get_style('currency'));
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
			$_sheet->getStyle("A{$tb_row}:D{$tb_row}")->applyFromArray(self::_get_style('tbody'));
			$_sheet->getStyle("A{$tb_row}")->applyFromArray(['font'  => ['bold'	=> TRUE, 'size'  => 10]]);
			$_sheet->setCellValue("E{$tb_row}", "=SUM(E{$tb_start}:E{$tb_sum_till})");
			$_sheet->getStyle("E{$tb_row}")->applyFromArray(self::_get_style('sum_value'));
			$_sheet->getStyle("E{$tb_row}")->applyFromArray(self::_get_style('currency'));
			$_sheet->setCellValue("F{$tb_row}", "=SUM(F{$tb_start}:F{$tb_sum_till})");
			$_sheet->getStyle("F{$tb_row}")->applyFromArray(self::_get_style('sum_value'));
			$_sheet->getStyle("F{$tb_row}")->applyFromArray(self::_get_style('currency'));
			$_sheet->setCellValue("G{$tb_row}", "=SUM(G{$tb_start}:G{$tb_sum_till})");
			$_sheet->getStyle("G{$tb_row}")->applyFromArray(self::_get_style('sum_value'));
			$_sheet->getStyle("G{$tb_row}")->applyFromArray(self::_get_style('currency'));
			$_sheet->setCellValue("H{$tb_row}", "=SUM(H{$tb_start}:H{$tb_sum_till})");
			$_sheet->getStyle("H{$tb_row}")->applyFromArray(self::_get_style('sum_value'));
			$_sheet->getStyle("H{$tb_row}")->applyFromArray(self::_get_style('currency'));
			$tb_row++;
			$tb_row++;

			$grandtotal += $total_subtotal - $diskon_total;
		endforeach;
		$_sheet->mergeCells("A{$tb_row}:G{$tb_row}");
		$_sheet->setCellValue("A{$tb_row}", 'GRANDTOTAL');
		$_sheet->getStyle("A{$tb_row}:H{$tb_row}")->applyFromArray(self::_get_style('tbody'));
		$_sheet->getStyle("A{$tb_row}")->applyFromArray(['font'  => ['bold'	=> TRUE, 'size'  => 10]]);
		$_sheet->getStyle("H{$tb_row}")->applyFromArray(self::_get_style('sum_value'));
		$_sheet->getStyle("H{$tb_row}")->applyFromArray(self::_get_style('currency'));
		$_sheet->setCellValue("H{$tb_row}", $grandtotal);
		$tb_row += 2;
		// TIPE PEMBAYARAN
		$tb_row += 2;
		$_sheet->mergeCells("A{$tb_row}:B{$tb_row}");
		$_sheet->setCellValue("A{$tb_row}", "PEMBAYARAN");
		$_sheet->getStyle("A{$tb_row}")->applyFromArray(self::_get_style('header'));
		$tb_row++;

		$_sheet->setCellValue("A{$tb_row}", "Tipe Pembayaran");
		$_sheet->getStyle("A{$tb_row}")->applyFromArray(self::_get_style('thead'));
		$_sheet->setCellValue("B{$tb_row}", "Nilai");
		$_sheet->getStyle("B{$tb_row}")->applyFromArray(self::_get_style('thead'));
		$tb_row++;

		$_total_payment = 0;
		foreach ($collection['payment'] as $type => $val) :
			$_sheet->setCellValue("A{$tb_row}", $type);
			$_sheet->getStyle("A{$tb_row}")->applyFromArray(self::_get_style('tbody_merge'));
			$_sheet->setCellValue("B{$tb_row}", $val);
			$_sheet->getStyle("B{$tb_row}")->applyFromArray(self::_get_style('tbody'));
			$_sheet->getStyle("B{$tb_row}")->applyFromArray(self::_get_style('currency'));
			$_total_payment += $val;
			$tb_row++;
		endforeach;
		$_sheet->setCellValue("A{$tb_row}", "Total");
		$_sheet->getStyle("A{$tb_row}")->applyFromArray(self::_get_style('tbody_merge'));
		$_sheet->getStyle("A{$tb_row}")->applyFromArray(['font'  => ['bold'	=> TRUE, 'size'  => 10]]);
		$_sheet->setCellValue("B{$tb_row}", $_total_payment);
		$_sheet->getStyle("B{$tb_row}")->applyFromArray(self::_get_style('sum_value'));
		$_sheet->getStyle("B{$tb_row}")->applyFromArray(self::_get_style('currency'));

		// PASIEN DENGAN PEMBAYARAN MERCHAN
		$tb_row += 3;
		$_sheet->mergeCells("A{$tb_row}:B{$tb_row}");
		$_sheet->setCellValue("A{$tb_row}", "PASIEN PEMBAYARAN MERCHAN");
		$_sheet->getStyle("A{$tb_row}")->applyFromArray(self::_get_style('header'));
		$tb_row++;

		$_sheet->setCellValue("A{$tb_row}", "Pasien");
		$_sheet->getStyle("A{$tb_row}")->applyFromArray(self::_get_style('thead'));
		$_sheet->setCellValue("B{$tb_row}", "Nilai");
		$_sheet->getStyle("B{$tb_row}")->applyFromArray(self::_get_style('thead'));
		$tb_row++;

		$_total_payment = 0;
		foreach ($collection['merchan'] as $pay) :
			$_sheet->setCellValue("A{$tb_row}", $pay->NamaPasien);
			$_sheet->getStyle("A{$tb_row}")->applyFromArray(self::_get_style('tbody_merge'));
			$_sheet->setCellValue("B{$tb_row}", $pay->Nilai);
			$_sheet->getStyle("B{$tb_row}")->applyFromArray(self::_get_style('tbody'));
			$_sheet->getStyle("B{$tb_row}")->applyFromArray(self::_get_style('currency'));
			$_total_payment += $pay->Nilai;
			$tb_row++;
		endforeach;
		$_sheet->setCellValue("A{$tb_row}", "Total");
		$_sheet->getStyle("A{$tb_row}")->applyFromArray(self::_get_style('tbody'));
		$_sheet->getStyle("A{$tb_row}")->applyFromArray(['font'  => ['bold'	=> TRUE, 'size'  => 10]]);
		$_sheet->setCellValue("B{$tb_row}", $_total_payment);
		$_sheet->getStyle("B{$tb_row}")->applyFromArray(self::_get_style('sum_value'));
		$_sheet->getStyle("B{$tb_row}")->applyFromArray(self::_get_style('currency'));
		$tb_row++;


		/*$spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);*/

		// Rename worksheet
		$spreadsheet->getActiveSheet()->setTitle('REKAP');
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

	public static function export_excel_get_warehouse_cards($date_start, $date_end, $barang_id, $lokasi_id)
	{
		$_ci = self::ci();
		$_ci->load->model('section_model');
		$section = $_ci->section_model->get_by(['Lokasi_ID' => $lokasi_id]);
		$collection = self::get_warehouse_cards($date_start, $date_end, $barang_id, $lokasi_id);
		$barang = self::get_barang($barang_id, $lokasi_id);
		$last_data = end($collection);	// Mengambil data kartu terakhir untuk stok akhir

		$date_start = DateTime::createFromFormat("Y-m-d", $date_start);
		$date_end = DateTime::createFromFormat("Y-m-d", $date_end);
		$file_name = sprintf('%s periode %s s/d %s ', lang('reports:warehouse_card_label'), $date_start->format('d F Y'), $date_end->format('d F Y'));

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
			->setTitle(lang('reports:warehouse_card_label'))
			->setSubject(lang('reports:warehouse_card_label'))
			->setDescription($file_name)
			->setKeywords($file_name);

		$_sheet = $spreadsheet->setActiveSheetIndex(0);

		// Default Style

		$spreadsheet->getDefaultStyle()->applyFromArray(self::_get_style('default'));

		$_sheet->mergeCells("A1:I1");
		$_sheet->setCellValue('A1', $file_name);
		$_sheet->getStyle("A1")->applyFromArray(self::_get_style('header'));
		$_sheet->getStyle("A1")->getAlignment()->setWrapText(true);
		//$_sheet->getRowDimension('1')->setRowHeight(30);
		$_sheet->mergeCells("A3:B3");
		$_sheet->setCellValue("A3", "Section : " . $section->SectionName);
		$_sheet->getStyle("A3")->applyFromArray(['font'  => ['bold'	=> TRUE, 'size'  => 11]]);

		$_sheet->mergeCells("A4:B4");
		$_sheet->setCellValue("A4", "Kode Barang : " . $barang->Kode_Barang);
		$_sheet->getStyle("A4")->applyFromArray(['font'  => ['bold'	=> TRUE, 'size'  => 11]]);

		$_sheet->mergeCells("A5:B5");
		$_sheet->setCellValue("A5", "Nama Barang : " . $barang->Nama_Barang);
		$_sheet->getStyle("A5")->applyFromArray(['font'  => ['bold'	=> TRUE, 'size'  => 11]]);

		$_sheet->mergeCells("A6:B6");
		$_sheet->setCellValue("A6", "Kategori : " . $barang->Nama_Kategori);
		$_sheet->getStyle("A6")->applyFromArray(['font'  => ['bold'	=> TRUE, 'size'  => 11]]);

		$_sheet->mergeCells("A7:B7");
		$_sheet->setCellValue("A7", "Stok Awal Bulan : " . @$collection[0]->QtySaldo . " / " . @$barang->Kode_Satuan);
		$_sheet->getStyle("A7")->applyFromArray(['font'  => ['bold'	=> TRUE, 'size'  => 11]]);

		$_sheet->mergeCells("A8:B8");
		$_sheet->setCellValue("A8", "Stok Akhir : " . @$last_data->QtySaldo . " / " . @$barang->Kode_Satuan);
		$_sheet->getStyle("A8")->applyFromArray(['font'  => ['bold'	=> TRUE, 'size'  => 11]]);

		$tb_row = 9;
		$i = 1;

		$_sheet->setCellValue("A{$tb_row}", lang('reports:no_label'));
		$_sheet->getStyle("A{$tb_row}")->applyFromArray(self::_get_style('thead'));
		$_sheet->setCellValue("B{$tb_row}", lang('reports:evidence_number_label'));
		$_sheet->getStyle("B{$tb_row}")->applyFromArray(self::_get_style('thead'));
		$_sheet->setCellValue("C{$tb_row}", lang('reports:doctor_label'));
		$_sheet->getStyle("C{$tb_row}")->applyFromArray(self::_get_style('thead'));
		$_sheet->setCellValue("D{$tb_row}", lang('reports:patient_label'));
		$_sheet->getStyle("D{$tb_row}")->applyFromArray(self::_get_style('thead'));
		$_sheet->setCellValue("E{$tb_row}", lang('reports:address_label'));
		$_sheet->getStyle("E{$tb_row}")->applyFromArray(self::_get_style('thead'));
		$_sheet->setCellValue("F{$tb_row}", lang('reports:user_label'));
		$_sheet->getStyle("F{$tb_row}")->applyFromArray(self::_get_style('thead'));
		$_sheet->setCellValue("G{$tb_row}", '(+)');
		$_sheet->getStyle("G{$tb_row}")->applyFromArray(self::_get_style('thead'));
		$_sheet->setCellValue("H{$tb_row}", '(-)');
		$_sheet->getStyle("H{$tb_row}")->applyFromArray(self::_get_style('thead'));
		$_sheet->setCellValue("I{$tb_row}", lang('reports:last_stock_label'));
		$_sheet->getStyle("I{$tb_row}")->applyFromArray(self::_get_style('thead'));
		$tb_row++;

		$qty_saldo = (strpos($collection[0]->No_Bukti, 'SAS') !== FALSE) ? $collection[0]->QtySaldo : 0;
		if (!empty($collection)) :
			foreach ($collection as $row) :
				$qty_saldo = @$qty_saldo + (@$row->Qty_Masuk - @$row->QtyKeluar);

				$_sheet->setCellValue("A{$tb_row}", $i++);
				$_sheet->getStyle("A{$tb_row}")->applyFromArray(self::_get_style('tbody'));
				$_sheet->setCellValue("B{$tb_row}", @$row->No_Bukti);
				$_sheet->getStyle("B{$tb_row}")->applyFromArray(self::_get_style('tbody'));
				$_sheet->setCellValue("C{$tb_row}", @$row->Nama_Supplier);
				$_sheet->getStyle("C{$tb_row}")->applyFromArray(self::_get_style('tbody'));
				$_sheet->setCellValue("D{$tb_row}", @$row->NamaPasien);
				$_sheet->getStyle("D{$tb_row}")->applyFromArray(self::_get_style('tbody'));
				$_sheet->setCellValue("E{$tb_row}", @$row->Alamat);
				$_sheet->getStyle("E{$tb_row}")->applyFromArray(self::_get_style('tbody'));
				$_sheet->setCellValue("F{$tb_row}", @$row->Nama_Singkat);
				$_sheet->getStyle("F{$tb_row}")->applyFromArray(self::_get_style('tbody'));
				$_sheet->setCellValue("G{$tb_row}",  @$row->Qty_Masuk);
				$_sheet->getStyle("G{$tb_row}")->applyFromArray(self::_get_style('tbody'));
				$_sheet->setCellValue("H{$tb_row}", @$row->QtyKeluar);
				$_sheet->getStyle("H{$tb_row}")->applyFromArray(self::_get_style('tbody'));
				$_sheet->setCellValue("I{$tb_row}", @$qty_saldo);
				$_sheet->getStyle("I{$tb_row}")->applyFromArray(self::_get_style('tbody'));

				$tb_row++;
			endforeach;
		endif;

		/*$spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);*/

		// Rename worksheet
		$spreadsheet->getActiveSheet()->setTitle('REKAP');
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


	public static function export_excel_get_recap_stocks($date_start, $date_end, $lokasi_id)
	{
		$_ci = self::ci();
		$_ci->load->model('section_model');

		$section = $_ci->section_model->get_by(['Lokasi_ID' => $lokasi_id]);
		$collection = self::get_recap_stocks($date_start, $date_end, $lokasi_id);

		$date_start = DateTime::createFromFormat("Y-m-d", $date_start);
		$date_end = DateTime::createFromFormat("Y-m-d", $date_end);
		$file_name = sprintf('%s %s periode %s s/d %s ', lang('reports:recap_stock_label'), $section->SectionName, $date_start->format('d F Y'), $date_end->format('d F Y'));

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
			->setTitle(lang('reports:recap_stock_label'))
			->setSubject(lang('reports:recap_stock_label'))
			->setDescription($file_name)
			->setKeywords($file_name);

		$_sheet = $spreadsheet->setActiveSheetIndex(0);

		// Default Style

		$spreadsheet->getDefaultStyle()->applyFromArray(self::_get_style('default'));

		$_sheet->mergeCells("A1:H1");
		$_sheet->setCellValue('A1', $file_name);
		$_sheet->getStyle("A1")->applyFromArray(self::_get_style('header'));
		$_sheet->getStyle("A1")->getAlignment()->setWrapText(true);

		$tb_row = 3;
		$i = 1;
		if (!empty($collection)) : foreach ($collection as $key => $value) :

				$_sheet->mergeCells("A{$tb_row}:G{$tb_row}");
				$_sheet->setCellValue("A{$tb_row}", sprintf("%s : %s", lang("reports:group_label"), $key));
				$_sheet->getStyle("A{$tb_row}")->applyFromArray(['font'  => ['bold'	=> TRUE, 'size'  => 11]]);
				$tb_row++;

				$_sheet->setCellValue("A{$tb_row}", lang('reports:no_label'));
				$_sheet->getStyle("A{$tb_row}")->applyFromArray(self::_get_style('thead'));
				$_sheet->setCellValue("B{$tb_row}", lang('reports:code_label'));
				$_sheet->getStyle("B{$tb_row}")->applyFromArray(self::_get_style('thead'));
				$_sheet->setCellValue("C{$tb_row}", lang('reports:item_label'));
				$_sheet->getStyle("C{$tb_row}")->applyFromArray(self::_get_style('thead'));
				$_sheet->setCellValue("D{$tb_row}", lang('reports:unit_label'));
				$_sheet->getStyle("D{$tb_row}")->applyFromArray(self::_get_style('thead'));
				$_sheet->setCellValue("E{$tb_row}", lang('reports:beginning_balance_label'));
				$_sheet->getStyle("E{$tb_row}")->applyFromArray(self::_get_style('thead'));
				$_sheet->setCellValue("F{$tb_row}", lang('reports:in_label'));
				$_sheet->getStyle("F{$tb_row}")->applyFromArray(self::_get_style('thead'));
				$_sheet->setCellValue("G{$tb_row}", lang('reports:out_label'));
				$_sheet->getStyle("G{$tb_row}")->applyFromArray(self::_get_style('thead'));
				$_sheet->setCellValue("H{$tb_row}", lang('reports:ending_balance_label'));
				$_sheet->getStyle("H{$tb_row}")->applyFromArray(self::_get_style('thead'));
				$tb_row++;

				if (!empty($value)) : foreach ($value as $row) :
						$_sheet->setCellValue("A{$tb_row}", $i++);
						$_sheet->getStyle("A{$tb_row}")->applyFromArray(self::_get_style('tbody'));
						$_sheet->setCellValue("B{$tb_row}", @$row->KOde_Barang);
						$_sheet->getStyle("B{$tb_row}")->applyFromArray(self::_get_style('tbody'));
						$_sheet->setCellValue("C{$tb_row}", @$row->Nama_Barang);
						$_sheet->getStyle("C{$tb_row}")->applyFromArray(self::_get_style('tbody'));
						$_sheet->setCellValue("D{$tb_row}", @$row->Satuan_Stok);
						$_sheet->getStyle("D{$tb_row}")->applyFromArray(self::_get_style('tbody'));
						$_sheet->setCellValue("E{$tb_row}", @$row->SA);
						$_sheet->getStyle("E{$tb_row}")->applyFromArray(self::_get_style('tbody'));
						$_sheet->setCellValue("F{$tb_row}", @$row->MASUK);
						$_sheet->getStyle("F{$tb_row}")->applyFromArray(self::_get_style('tbody'));
						$_sheet->setCellValue("G{$tb_row}",  @$row->KELUAR);
						$_sheet->getStyle("G{$tb_row}")->applyFromArray(self::_get_style('tbody'));
						$_sheet->setCellValue("H{$tb_row}", abs($row->SA + $row->MASUK - $row->KELUAR));
						$_sheet->getStyle("H{$tb_row}")->applyFromArray(self::_get_style('tbody'));
						$tb_row++;
					endforeach;
				endif;

			endforeach;
		endif;
		/*$spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);*/

		// Rename worksheet
		$spreadsheet->getActiveSheet()->setTitle('REKAP');
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

	public static function export_excel_daily_stock_recap($date_start, $date_end, $lokasi_id)
	{
		$_ci = self::ci();
		$_ci->load->model('section_model');

		$section = $_ci->section_model->get_by(['Lokasi_ID' => $lokasi_id]);
		$collection = self::get_daily_stock_recap_data($date_start, $date_end, $lokasi_id);

		$date_start = DateTime::createFromFormat("Y-m-d", $date_start);
		$date_end = DateTime::createFromFormat("Y-m-d", $date_end);
		$file_name = sprintf('%s %s periode %s s/d %s ', 'Laporan Rekap Stok Harian', $section->SectionName, $date_start->format('d F Y'), $date_end->format('d F Y'));

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
			->setTitle('Laporan Rekap Stok Harian')
			->setSubject('Laporan Rekap Stok Harian')
			->setDescription($file_name)
			->setKeywords($file_name);

		$_sheet = $spreadsheet->setActiveSheetIndex(0);

		// Default Style
		$spreadsheet->getDefaultStyle()->applyFromArray(self::_get_style('default'));

		$_sheet->mergeCells("A1:H1");
		$_sheet->setCellValue('A1', $file_name);
		$_sheet->getStyle("A1")->applyFromArray(self::_get_style('header'));
		$_sheet->getStyle("A1")->getAlignment()->setWrapText(true);

		$tb_row = 3;
		$i = 1;
		if (!empty($collection)) : foreach ($collection as $key => $value) :

				$_sheet->mergeCells("A{$tb_row}:H{$tb_row}");
				$_sheet->setCellValue("A{$tb_row}", sprintf("%s : %s", lang("reports:group_label"), $key));
				$_sheet->getStyle("A{$tb_row}")->applyFromArray(['font'  => ['bold'	=> TRUE, 'size'  => 11]]);
				$tb_row++;

				$_sheet->setCellValue("A{$tb_row}", lang('reports:no_label'));
				$_sheet->getStyle("A{$tb_row}")->applyFromArray(self::_get_style('thead'));
				$_sheet->setCellValue("B{$tb_row}", lang('reports:code_label'));
				$_sheet->getStyle("B{$tb_row}")->applyFromArray(self::_get_style('thead'));
				$_sheet->setCellValue("C{$tb_row}", lang('reports:item_label'));
				$_sheet->getStyle("C{$tb_row}")->applyFromArray(self::_get_style('thead'));
				$_sheet->setCellValue("D{$tb_row}", lang('reports:unit_label'));
				$_sheet->getStyle("D{$tb_row}")->applyFromArray(self::_get_style('thead'));
				$_sheet->setCellValue("E{$tb_row}", 'SALDO AWAL');
				$_sheet->getStyle("E{$tb_row}")->applyFromArray(self::_get_style('thead'));
				$_sheet->setCellValue("F{$tb_row}", 'MASUK');
				$_sheet->getStyle("F{$tb_row}")->applyFromArray(self::_get_style('thead'));
				$_sheet->setCellValue("G{$tb_row}", 'KELUAR');
				$_sheet->getStyle("G{$tb_row}")->applyFromArray(self::_get_style('thead'));
				$_sheet->setCellValue("H{$tb_row}", 'STOK AKHIR');
				$_sheet->getStyle("H{$tb_row}")->applyFromArray(self::_get_style('thead'));
				$tb_row++;

				if (!empty($value)) : foreach ($value as $row) :
						$_sheet->setCellValue("A{$tb_row}", $i++);
						$_sheet->getStyle("A{$tb_row}")->applyFromArray(self::_get_style('tbody'));
						$_sheet->setCellValue("B{$tb_row}", @$row->KOde_Barang);
						$_sheet->getStyle("B{$tb_row}")->applyFromArray(self::_get_style('tbody'));
						$_sheet->setCellValue("C{$tb_row}", @$row->Nama_Barang);
						$_sheet->getStyle("C{$tb_row}")->applyFromArray(self::_get_style('tbody'));
						$_sheet->setCellValue("D{$tb_row}", @$row->Satuan_Stok);
						$_sheet->getStyle("D{$tb_row}")->applyFromArray(self::_get_style('tbody'));
						$_sheet->setCellValue("E{$tb_row}", @$row->SA);
						$_sheet->getStyle("E{$tb_row}")->applyFromArray(self::_get_style('tbody'));
						$_sheet->setCellValue("F{$tb_row}", @$row->MASUK);
						$_sheet->getStyle("F{$tb_row}")->applyFromArray(self::_get_style('tbody'));
						$_sheet->setCellValue("G{$tb_row}",  @$row->KELUAR);
						$_sheet->getStyle("G{$tb_row}")->applyFromArray(self::_get_style('tbody'));
						$_sheet->setCellValue("H{$tb_row}", (@$row->SA + @$row->MASUK - @$row->KELUAR));
						$_sheet->getStyle("H{$tb_row}")->applyFromArray(self::_get_style('tbody'));
						$tb_row++;
					endforeach;
				endif;

			endforeach;
		endif;

		// Set active sheet index to the first sheet
		$spreadsheet->setActiveSheetIndex(0);

		// Redirect output to a client’s web browser (Xls)
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="' . $file_name . '.xls"');
		header('Cache-Control: max-age=0');
		header('Cache-Control: max-age=1');
		header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
		header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
		header('Cache-Control: cache, must-revalidate');
		header('Pragma: public');

		$writer = IOFactory::createWriter($spreadsheet, 'Xls');
		$writer->save('php://output');
		exit;
	}


	public static function export_excel_get_used_drugs($date_start, $date_end, $section_id, $kerjasama_id)
	{
		$_ci = self::ci();
		$_ci->load->model('section_model');

		$section = $_ci->section_model->get_one($section_id);
		$collection = self::get_used_drugs($date_start, $date_end, $section_id, $kerjasama_id);

		$date_start = DateTime::createFromFormat("Y-m-d", $date_start);
		$date_end = DateTime::createFromFormat("Y-m-d", $date_end);
		$file_name = sprintf('%s periode %s s/d %s ', 'Laporan Penggunaan Obat', $date_start->format('d F Y'), $date_end->format('d F Y'));

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
			->setTitle('Laporan Penggunaan Obat')
			->setSubject('Laporan Penggunaan Obat')
			->setDescription($file_name)
			->setKeywords($file_name);

		$_sheet = $spreadsheet->setActiveSheetIndex(0);

		// Default Style

		$spreadsheet->getDefaultStyle()->applyFromArray(self::_get_style('default'));

		$_sheet->mergeCells("A1:I1");
		$_sheet->setCellValue('A1', $file_name);
		$_sheet->getStyle("A1")->applyFromArray(self::_get_style('header'));
		$_sheet->getStyle("A1")->getAlignment()->setWrapText(true);

		$tb_row = 3;
		$i = 1;
		if (!empty($collection)) : foreach ($collection as $key => $value) :

				$_sheet->mergeCells("A{$tb_row}:G{$tb_row}");
				$_sheet->setCellValue("A{$tb_row}", sprintf("%s : %s", 'Section ', $key));
				$_sheet->getStyle("A{$tb_row}")->applyFromArray(['font'  => ['bold'	=> TRUE, 'size'  => 11]]);
				$tb_row++;

				$_sheet->setCellValue("A{$tb_row}", 'No');
				$_sheet->getStyle("A{$tb_row}")->applyFromArray(self::_get_style('thead'));
				$_sheet->setCellValue("B{$tb_row}", 'Tanggal');
				$_sheet->getStyle("B{$tb_row}")->applyFromArray(self::_get_style('thead'));
				$_sheet->setCellValue("C{$tb_row}", 'Kode Obat');
				$_sheet->getStyle("C{$tb_row}")->applyFromArray(self::_get_style('thead'));
				$_sheet->setCellValue("D{$tb_row}", 'Nama Obat');
				$_sheet->getStyle("D{$tb_row}")->applyFromArray(self::_get_style('thead'));
				$_sheet->setCellValue("E{$tb_row}", 'HNA');
				$_sheet->getStyle("E{$tb_row}")->applyFromArray(self::_get_style('thead'));
				$_sheet->setCellValue("F{$tb_row}", 'Qty');
				$_sheet->getStyle("F{$tb_row}")->applyFromArray(self::_get_style('thead'));
				$_sheet->setCellValue("G{$tb_row}", 'CN On Faktur');
				$_sheet->getStyle("G{$tb_row}")->applyFromArray(self::_get_style('thead'));
				$_sheet->setCellValue("H{$tb_row}", 'CN Off Faktur');
				$_sheet->getStyle("H{$tb_row}")->applyFromArray(self::_get_style('thead'));
				$_sheet->setCellValue("I{$tb_row}", 'Total CN');
				$_sheet->getStyle("I{$tb_row}")->applyFromArray(self::_get_style('thead'));
				$tb_row++;

				if (!empty($value)) : foreach ($value as $row) :
						$_sheet->setCellValue("A{$tb_row}", $i++);
						$_sheet->getStyle("A{$tb_row}")->applyFromArray(self::_get_style('tbody'));
						$_sheet->setCellValue("B{$tb_row}", date('d-m-Y', strtotime(@$row->Tanggal)));
						$_sheet->getStyle("B{$tb_row}")->applyFromArray(self::_get_style('tbody'));
						$_sheet->setCellValue("C{$tb_row}", @$row->Kode_Barang);
						$_sheet->getStyle("C{$tb_row}")->applyFromArray(self::_get_style('tbody'));
						$_sheet->setCellValue("D{$tb_row}", @$row->Nama_Barang);
						$_sheet->getStyle("D{$tb_row}")->applyFromArray(self::_get_style('tbody'));
						$_sheet->setCellValue("E{$tb_row}", number_format(@$row->HNA, 2));
						$_sheet->getStyle("E{$tb_row}")->applyFromArray(self::_get_style('tbody'));
						$_sheet->setCellValue("F{$tb_row}", @$row->Jumlah);
						$_sheet->getStyle("F{$tb_row}")->applyFromArray(self::_get_style('tbody'));
						$_sheet->setCellValue("G{$tb_row}",  number_format(@$row->CNOnFaktur, 2));
						$_sheet->getStyle("G{$tb_row}")->applyFromArray(self::_get_style('tbody'));
						$_sheet->setCellValue("H{$tb_row}", number_format(@$row->CNOffFaktur, 2));
						$_sheet->getStyle("H{$tb_row}")->applyFromArray(self::_get_style('tbody'));
						$_sheet->setCellValue("I{$tb_row}", number_format(@$row->CNOnFaktur + @$row->CNOffFaktur, 2));
						$_sheet->getStyle("I{$tb_row}")->applyFromArray(self::_get_style('tbody'));
						$tb_row++;
					endforeach;
				endif;

			endforeach;
		endif;
		/*$spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);*/

		// Rename worksheet
		$spreadsheet->getActiveSheet()->setTitle('REKAP');
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

	public static function export_excel_get_top_drugs($date_start, $date_end)
	{
		$_ci = self::ci();
		$_ci->load->model('section_model');

		// $section = $_ci->section_model->get_one($section_id);
		$collection = self::get_top_drugs($date_start, $date_end);

		$date_start = DateTime::createFromFormat("Y-m-d", $date_start);
		$date_end = DateTime::createFromFormat("Y-m-d", $date_end);
		$file_name = sprintf('%s periode %s s/d %s ', 'Laporan Top 10 Penggunaan Obat', $date_start->format('d F Y'), $date_end->format('d F Y'));

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
			->setTitle('Laporan Top 10 Penggunaan Obat')
			->setSubject('Laporan Top 10 Penggunaan Obat')
			->setDescription($file_name)
			->setKeywords($file_name);

		$_sheet = $spreadsheet->setActiveSheetIndex(0);

		// Default Style

		$spreadsheet->getDefaultStyle()->applyFromArray(self::_get_style('default'));

		$_sheet->mergeCells("A1:I1");
		$_sheet->setCellValue('A1', $file_name);
		$_sheet->getStyle("A1")->applyFromArray(self::_get_style('header'));
		$_sheet->getStyle("A1")->getAlignment()->setWrapText(true);

		$tb_row = 3;
		$i = 1;

		$_sheet->setCellValue("A{$tb_row}", 'No');
		$_sheet->getStyle("A{$tb_row}")->applyFromArray(self::_get_style('thead'));
		$_sheet->setCellValue("B{$tb_row}", 'Nama Obat');
		$_sheet->getStyle("B{$tb_row}")->applyFromArray(self::_get_style('thead'));
		$_sheet->setCellValue("C{$tb_row}", 'Jumlah Obat Terpakai');
		$_sheet->getStyle("C{$tb_row}")->applyFromArray(self::_get_style('thead'));
		$tb_row++;

		if (!empty($collection)) : foreach ($collection as $row) :
				$_sheet->setCellValue("A{$tb_row}", $i++);
				$_sheet->getStyle("A{$tb_row}")->applyFromArray(self::_get_style('tbody'));
				$_sheet->setCellValue("B{$tb_row}", @$row->NamaResepObat);
				$_sheet->getStyle("B{$tb_row}")->applyFromArray(self::_get_style('tbody'));
				$_sheet->setCellValue("C{$tb_row}", @$row->Jumlah);
				$_sheet->getStyle("C{$tb_row}")->applyFromArray(self::_get_style('tbody'));
				$tb_row++;
			endforeach;
		endif;

		/*$spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);*/

		// Rename worksheet
		$spreadsheet->getActiveSheet()->setTitle('REKAP');
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

	public static function get_recap_transactions($date_start, $date_end, $section_id, $kelompokjenis, $shift_id, $user_id)
	{
		$_ci = self::ci();
		$_ci->load->model('section_model');
		$section = $_ci->section_model->get_one($section_id);

		$collection = ['data' => [], 'payment' => [], 'merchan' => []];
		// collection data
		$query = $_ci->db->query("exec FAR_Rpt_RekapTransaksi '{$date_start}','{$date_end}','{$section->SectionID}','{$kelompokjenis}','{$shift_id}','{$user_id}'");
		foreach ($query->result() as $row) {
			$collection['data'][$row->JenisKerjasama][$row->NoBukti . ' => ' . $row->Keterangan][] = [
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

	public static function export_doctor_drug_incentives($date_start, $date_end, $doctor_id)
	{
		$_ci = self::ci();
		$_ci->load->model('supplier_model');

		$collection = self::get_doctor_drug_incentives($date_start, $date_end, $doctor_id);
		$doctor = $_ci->supplier_model->get_by(['Kode_Supplier' => $doctor_id]);
		$date_start = DateTime::createFromFormat("Y-m-d", $date_start);
		$date_end = DateTime::createFromFormat("Y-m-d", $date_end);
		$file_name = sprintf('%s %s periode %s s/d %s ', 'Laporan Insentif Obat', $doctor->Nama_Supplier, $date_start->format('d F Y'), $date_end->format('d F Y'));

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
			->setTitle('Laporan Insentif Obat {$doctor->Nama_Supplier}')
			->setSubject('Laporan Insentif Obat {$doctor->Nama_Supplier}')
			->setDescription($file_name)
			->setKeywords($file_name);

		$setActiveSheetIndex = 0;
		$recap = [];
		foreach ($collection as $manufacturer => $items) :
			if ($setActiveSheetIndex > 0) {
				$_new_sheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'new blank');
				$spreadsheet->addSheet($_new_sheet, $setActiveSheetIndex);
			}
			$_sheet = $spreadsheet->setActiveSheetIndex($setActiveSheetIndex++);
			$spreadsheet->getActiveSheet()->setTitle($manufacturer);

			// Default Style			
			$spreadsheet->getDefaultStyle()->applyFromArray(self::_get_style('default'));

			/*$_sheet->mergeCells("A1:F1");
			$_sheet->setCellValue('A1', $file_name );
			$_sheet->getStyle("A1")->applyFromArray( self::_get_style( 'header' ) );
			$_sheet->getStyle("A1")->getAlignment()->setWrapText(true);*/
			//$_sheet->getRowDimension('1')->setRowHeight(30);

			$tb_row = 1;
			$_sheet->setCellValue("A{$tb_row}", 'NO');
			$_sheet->getStyle("A{$tb_row}")->applyFromArray(self::_get_style('thead'));
			$_sheet->setCellValue("B{$tb_row}", 'NAMA OBAT');
			$_sheet->getStyle("B{$tb_row}")->applyFromArray(self::_get_style('thead'));
			$_sheet->setCellValue("C{$tb_row}", 'PBF');
			$_sheet->getStyle("C{$tb_row}")->applyFromArray(self::_get_style('thead'));
			$_sheet->setCellValue("D{$tb_row}", 'HNA');
			$_sheet->getStyle("D{$tb_row}")->applyFromArray(self::_get_style('thead'));
			$_sheet->setCellValue("E{$tb_row}", 'DISC %');
			$_sheet->getStyle("E{$tb_row}")->applyFromArray(self::_get_style('thead'));
			$_sheet->setCellValue("F{$tb_row}", 'JUMLAH PEMAKAIAN');
			$_sheet->getStyle("F{$tb_row}")->applyFromArray(self::_get_style('thead'));
			$tb_row++;

			$no = 1;
			$recap[$manufacturer] = 0;
			foreach ($items as $item) :
				$item = (object) $item;
				$_sheet->setCellValue("A{$tb_row}", $no++);
				$_sheet->getStyle("A{$tb_row}")->applyFromArray(self::_get_style('tbody'));
				$_sheet->setCellValue("B{$tb_row}", @$item->Nama_Barang);
				$_sheet->getStyle("B{$tb_row}")->applyFromArray(self::_get_style('tbody'));
				$_sheet->setCellValue("C{$tb_row}", $item->Nama_Supplier);
				$_sheet->getStyle("C{$tb_row}")->applyFromArray(self::_get_style('tbody'));
				$_sheet->setCellValue("D{$tb_row}", $item->Harga_Jual);
				$_sheet->getStyle("D{$tb_row}")->applyFromArray(self::_get_style('tbody'));
				$_sheet->setCellValue("E{$tb_row}", @$item->Disc);
				$_sheet->getStyle("E{$tb_row}")->applyFromArray(self::_get_style('tbody'));
				$_sheet->setCellValue("F{$tb_row}", $item->Qty);
				$_sheet->getStyle("F{$tb_row}")->applyFromArray(self::_get_style('tbody'));

				$_sheet->getStyle("D{$tb_row}")->applyFromArray(self::_get_style('currency'));
				$tb_row++;

				$recap[$manufacturer] += ($item->Harga_Jual * 20 / 100) * $item->Qty;
			endforeach;

			$tb_row += 2;
		endforeach;



		## REKAP
		$_new_sheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'REKAP');
		$spreadsheet->addSheet($_new_sheet, $setActiveSheetIndex);
		$_sheet = $spreadsheet->setActiveSheetIndex($setActiveSheetIndex);
		$spreadsheet->getDefaultStyle()->applyFromArray(self::_get_style('default'));

		$tb_row = 1;
		$_sheet->setCellValue("A{$tb_row}", 'NO');
		$_sheet->getStyle("A{$tb_row}")->applyFromArray(self::_get_style('thead'));
		$_sheet->setCellValue("B{$tb_row}", 'PABRIKAN');
		$_sheet->getStyle("B{$tb_row}")->applyFromArray(self::_get_style('thead'));
		$_sheet->setCellValue("C{$tb_row}", 'TOTAL');
		$_sheet->getStyle("C{$tb_row}")->applyFromArray(self::_get_style('thead'));
		$tb_row++;

		$no = 1;
		$grand_total = 0;
		foreach ($recap as $k => $v) :
			$_sheet->setCellValue("A{$tb_row}", $no++);
			$_sheet->getStyle("A{$tb_row}")->applyFromArray(self::_get_style('tbody'));
			$_sheet->setCellValue("B{$tb_row}", @$k);
			$_sheet->getStyle("B{$tb_row}")->applyFromArray(self::_get_style('tbody'));
			$_sheet->setCellValue("C{$tb_row}", $v);
			$_sheet->getStyle("C{$tb_row}")->applyFromArray(self::_get_style('tbody'));
			$_sheet->getStyle("C{$tb_row}")->applyFromArray(self::_get_style('currency'));
			$grand_total += $v;
			$tb_row++;
		endforeach;

		// GRAND TOTAL
		$_sheet->mergeCells("A{$tb_row}:B{$tb_row}");
		$_sheet->getStyle("A{$tb_row}:B{$tb_row}")->applyFromArray(self::_get_style('sum_name'));
		$_sheet->setCellValue("A{$tb_row}", 'GRANDTOTAL');
		$_sheet->getStyle("C{$tb_row}")->applyFromArray(self::_get_style('sum_value'));
		$_sheet->getStyle("C{$tb_row}")->applyFromArray(self::_get_style('currency'));
		$_sheet->setCellValue("C{$tb_row}", $grand_total);

		// Rename worksheet
		//$spreadsheet->getActiveSheet()->setTitle( 'REKAP' );
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

	public static function get_doctor_drug_incentives($date_start, $date_end, $doctor_id)
	{
		$_ci = self::ci();
		$_ci->load->model('pharmacy_m');
		$_ci->load->model('pharmacy_detail_m');
		$_ci->load->model('drug_payment_m');
		$_ci->load->model('item_model');
		$_ci->load->model('item_category_model');

		$date_start = DateTime::createFromFormat('Y-m-d', $date_start)->setTime(0, 0);
		$date_start->add(new DateInterval('PT8H'));
		$date_end = DateTime::createFromFormat('Y-m-d', $date_end)->setTime(0, 0);
		$date_end->add(new DateInterval('P1DT8H'));

		$query = $_ci->db->select("d.Kode_Barang, d.Nama_Barang, f.Nama_Supplier, e.Nama_Kategori, e.CN_Faktur AS Disc, d.Harga_Jual, SUM(b.JmlObat - b.JmlRetur) Qty ")
			->from("{$_ci->pharmacy_m->table} a")
			->join("{$_ci->pharmacy_detail_m->table} b", "a.NoBukti = b.NoBukti", "INNER")
			->join("{$_ci->drug_payment_m->table} c", "a.NoBukti = c.NoBuktiFarmasi", "INNER")
			->join("{$_ci->item_model->table} d", "b.Barang_ID = d.Barang_ID", "INNER")
			->join("{$_ci->item_category_model->table} e", "d.Kategori_ID = e.Kategori_ID", "LEFT OUTER")
			->join("{$_ci->supplier_model->table} f", "d.Supplier_ID = f.Supplier_ID", "LEFT OUTER")
			->where(['a.DokterID' => $doctor_id, 'a.Tanggal >=' => $date_start->format('Y-m-d'), 'a.Tanggal <=' => $date_end->format('Y-m-t'), 'a.Batal' => 0, 'a.Retur' => 0, 'c.Batal' => 0])
			->where('e.CN_Faktur >', 0)
			//->where_in('e.Kategori_ID', [11, 13, 14, 22])
			->group_by('d.Kode_Barang, d.Nama_Barang, f.Nama_Supplier, e.Nama_Kategori, e.CN_Faktur, d.Harga_Jual')
			->get();

		$collection = [];
		foreach ($query->result() as $row) {
			$collection[$row->Nama_Kategori][] = $row;
		}

		return $collection;
	}

	public static function get_barang($Barang_ID, $Lokasi_ID)
	{

		$db = self::ci()->db;

		$db_from = "mBarangLokasiNew a";

		$query = $db->select("a.Kode_Satuan, b.Kode_Barang, b.Nama_Barang, c.Nama_Kategori")
			->from($db_from)
			->join("mBarang b", "a.Barang_ID = b.Barang_ID", "LEFT OUTER")
			->join("mKategori c", "b.Kategori_Id = c.Kategori_ID", "LEFT OUTER")
			->where(array("a.Barang_ID" => $Barang_ID, "a.Lokasi_ID" => $Lokasi_ID))
			->get();

		if ($query->num_rows() > 0) {
			return $query->row();
		}

		return FALSE;
	}

	public static function get_barang_section($Lokasi_ID)
	{

		$db = self::ci()->db;

		$db_from = "mBarangLokasiNew a";

		$query = $db->select("b.*")
			->from($db_from)
			->join("mBarang b", "a.Barang_ID = b.Barang_ID", "LEFT OUTER")
			->where(array("a.Lokasi_ID" => $Lokasi_ID))
			->get();

		if ($query->num_rows() > 0) {
			$collection = array();
			foreach ($query->result() as $row) {
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
			->get();

		if ($query->num_rows() > 0) {
			$collection = array();
			foreach ($query->result() as $row) {
				$collection[$row->Kode_Customer] = $row;
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
