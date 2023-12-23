<?php
defined('BASEPATH') OR exit('No direct script access allowed');

final class Reports_helper
{		
	private static $user_auth;
	private static $_ci;
	
	public static function init()
	{
		self::$_ci = $_ci = self::ci();
		
		$_ci->load->library('simple_login');		
		self::$user_auth = $_ci->simple_login->get_user();
		
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
			->select( $db_select )
			->from("SIMtrKasir a")
			->join("SImmSection b", "a.SectionPerawatanID = b.SectionID", "INNER")
			->where(['a.Tanggal >=' => $date->format('Y-m-01'), 'a.Tanggal <=' => $date->format('Y-m-t 23:59:59')])
			->group_by("datepart(DAY,a.Tanggal), b.SectionName")
			->order_by("Hari")
			->get()
			;
			
		$categories = array_map(function($row){ return 0;}, array_flip(range(1, $date->format('t'))));
		$collection['categories'] = array_keys($categories);
		
		$series = [];
		foreach($section as $sec):
			$series[$sec->SectionName] = [
				'name' => $sec->SectionName,
				'data' => $categories
			];
		endforeach;
				
		foreach($query->result() as $row):
			$series[$row->SectionName]['data'][$row->Hari] = $row->JumlahKunjungan;
		endforeach;
		
		foreach($series as $row):
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
			->select( $db_select )
			->from("SIMtrKasir a")
			->join("SImmSection b", "a.SectionPerawatanID = b.SectionID", "INNER")
			->where(['a.Tanggal >=' => $date->format('Y-01-01'), 'a.Tanggal <=' => $date->format('Y-12-31 23:59:59')])
			->group_by("datepart(MONTH, a.Tanggal), b.SectionName")
			->order_by("Bulan")
			->get()
			;
			
		$categories = array_map(function($row){ return 0;}, array_flip(range(1, $date->format('12'))));
		$collection['categories'] = array_keys($categories);
		
		$series = [];
		foreach($section as $sec):
			$series[$sec->SectionName] = [
				'name' => $sec->SectionName,
				'data' => $categories
			];
		endforeach;
				
		foreach($query->result() as $row):
			$series[$row->SectionName]['data'][$row->Bulan] = $row->JumlahKunjungan;
		endforeach;
		
		foreach($series as $row):
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
			->select( $db_select )
			->from("SIMtrKasir a")
			->join("SIMtrRegistrasi b", "a.NoReg = b.NoReg", "INNER")
			->join("SIMmJenisKerjasama c", "b.JenisKerjasamaID = c.JenisKerjasamaID", "INNER")
			->where(['a.Tanggal >=' => $date->format('Y-m-01'), 'a.Tanggal <=' => $date->format('Y-m-t 23:59:59')])
			->group_by("datepart(DAY,a.Tanggal), c.JenisKerjasama")
			->order_by("Hari")
			->get()
			;
			
		$categories = array_map(function($row){ return 0;}, array_flip(range(1, $date->format('t'))));
		$collection['categories'] = array_keys($categories);
		
		$series = [];
		foreach($type as $tp):
			$series[$tp->JenisKerjasama] = [
				'name' => $tp->JenisKerjasama,
				'data' => $categories
			];
		endforeach;
				
		foreach($query->result() as $row):
			$series[$row->JenisKerjasama]['data'][$row->Hari] = $row->JumlahKunjungan;
		endforeach;
		
		foreach($series as $row):
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
			->select( $db_select )
			->from("SIMtrKasir a")
			->join("SIMtrRegistrasi b", "a.NoReg = b.NoReg", "INNER")
			->join("SIMmJenisKerjasama c", "b.JenisKerjasamaID = c.JenisKerjasamaID", "INNER")
			->where(['a.Tanggal >=' => $date->format('Y-01-01'), 'a.Tanggal <=' => $date->format('Y-12-31 23:59:59')])
			->group_by("datepart(MONTH, a.Tanggal), c.JenisKerjasama")
			->order_by("Bulan")
			->get()
			;
			
		$categories = array_map(function($row){ return 0;}, array_flip(range(1, $date->format('12'))));
		$collection['categories'] = array_keys($categories);
		
		$series = [];
		foreach($type as $tp):
			$series[$tp->JenisKerjasama] = [
				'name' => $tp->JenisKerjasama,
				'data' => $categories
			];
		endforeach;
				
		foreach($query->result() as $row):
			$series[$row->JenisKerjasama]['data'][$row->Bulan] = $row->JumlahKunjungan;
		endforeach;
		
		foreach($series as $row):
			$row['data'] = array_values($row['data']);
			$collection['series'][] = $row;
		endforeach;
		
		return $collection;		
	}
	
	private static function & ci()
	{
		return get_instance();
	}	

}
