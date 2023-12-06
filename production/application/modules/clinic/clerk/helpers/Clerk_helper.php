<?php
defined('BASEPATH') OR exit('No direct script access allowed');

final class Clerk_helper
{		
	private static $user_auth;
	private static $_ci;
	
	public static function init()
	{
		self::$_ci = $_ci = self::ci();
		
		$_ci->load->library('simple_login');		
		self::$user_auth = $_ci->simple_login->get_user();
		
	}

	public static function gen_code($prefix, $infix, $suffix_lenght = 4)
	{
		self::init();
		$_ci = self::ci();
		
		return sprintf("%s-%s-%s", $prefix, $infix, gen_unique_code( $suffix_lenght ));
	}
	
	public static function check_clerk()
	{
		self::init();
		$_ci = self::ci();
		
		$check = $_ci->clerk_model->get_by(['UserID' => self::$user_auth->User_ID, 'StatusClerk' => 0]);
		
		return empty($check) ? 'start' : 'end';		
	}
		
	public static function start_clerk( Array $header )
	{
		self::init();
		$_ci = self::ci();
		
		$_ci->db->trans_begin();
			
			$_ci->clerk_model->create($header);
			
													
		if( $_ci->db->trans_status() === FALSE )
		{
			$_ci->db->trans_rollback();
			return [
					"status" => 'error',
					"message" => lang('global:created_failed'),
				];
		}
		//$_ci->db->trans_rollback();
		$_ci->db->trans_commit();
		
		return [
				"status" => 'success',
				"message" => lang('message:done_clerk_start'),
			];
	}
	
	public static function end_clerk( $KodeClerk, Array $header, Array $details )
	{
		self::init();
		$_ci = self::ci();
		
		$_ci->db->trans_begin();
			
			$header['JumlahTotalSelisih'] = $header['JumlahAwalUangKasir'] + $header['JumlahTotalSystem'] - $header['JumlahTotalClerk'];
			$header['StatusClerk'] = 1;
			$_ci->clerk_model->update($header, $KodeClerk);
			
			if($_ci->clerk_det_model->count_all(['KodeClerk' => $KodeClerk])):
				$_ci->clerk_det_model->delete_by(['KodeClerk' => $KodeClerk]);
			endif;
			
			$_ci->clerk_det_model->mass_create($details);
													
		if( $_ci->db->trans_status() === FALSE )
		{
			$_ci->db->trans_rollback();
			return [
					"status" => 'error',
					"message" => lang('global:created_failed'),
				];
		}
		//$_ci->db->trans_rollback();
		$_ci->db->trans_commit();
		return [
				"status" => 'success',
				"message" => lang('message:done_clerk_end'),
			];
	}
	
	public static function get_clerk_qty_sales( $UserID, $WaktuMulaiClerk)
	{
		$_ci = self::ci();
		$get = $_ci->db->from('BILLFarmasi a')
				->join('SIMtrPembayaranObatBebas b', 'a.NoBukti = b."NoBuktiFarmasi"', 'INNER')
				->where([
					'b.UserID' => $UserID, 
					'a.Jam >=' => $WaktuMulaiClerk,
					'a.ClosePayment' => 1,
					'a.Batal' =>  0,
					'a.Retur' => 0,
					'b.Batal' => 0,
				]);
		
		return (int) $get->count_all_results();
	}
	
	public static function get_clerk_amount_system( $UserID, $WaktuMulaiClerk)
	{
		$_ci = self::ci();		
		$get = $_ci->db->select('SUM(Total) AS JumlahTotalSystem')
				->from('BILLFarmasi a')
				->join('SIMtrPembayaranObatBebas b', 'a.NoBukti = b."NoBuktiFarmasi"', 'INNER')
				->where([
					'b.UserID' => $UserID, 
					'a.Jam >=' => $WaktuMulaiClerk,
					'a.ClosePayment' => 1,
					'a.Batal' =>  0,
					'a.Retur' => 0,
					'b.Batal' => 0,
				])
				->get()
				->row();
		
		return (float) @$get->JumlahTotalSystem;
	}
	
	public static function get_clerk_payment($UserID, $WaktuMulaiClerk)
	{
		$_ci = self::ci();
		$payment_total = $_ci->db->select('
					SUM(b.NilaiPembayaran) AS TUN, 
					SUM(b.NilaiPembayaranIKS) AS IKS, 
					SUM(b.NilaiPembayaranBPJS) AS BPJ,
					SUM(b.NilaiPembayaranCC) AS BNK,
					SUM(b.Kredit) AS KRE,
					SUM(b.NilaiPembayaranBebanRS) AS BEB
				')
				->from("BILLFarmasi a")
				->join('SIMtrPembayaranObatBebas b', 'a.NoBukti = b."NoBuktiFarmasi"', 'INNER')
				->where([
					'b.UserID' => $UserID, 
					'a.Jam >=' => $WaktuMulaiClerk,
					'a.ClosePayment' => 1,
					'a.Batal' =>  0,
					'a.Retur' => 0,
					'b.Batal' => 0,
				])
				->get()
				->row_array();
		

		$payment_type = $_ci->db->where('Active', 1)->get('mJenisBayar')->result();
		$collection = [];
		foreach($payment_type as $key => $val):
			$collection[] = (object)[
				'JenisBayarID' => $val->IDBayar,
				'Description' => $val->Description,
				'JumlahTotal' => (float) $payment_total[$val->Kode]
			];
		endforeach;
		
		return $collection;
	}
	
	public static function get_clerk_detail($KodeClerk)
	{
		$_ci = self::ci();		
		$get_details = $_ci->clerk_det_model->get_all(['KodeClerk' => $KodeClerk]);
		
		$collection = [];
		foreach($get_details as $row):
			$collection[$row->JenisBayarID] = $row;
		endforeach;

		return $collection;
	}
		
	private static function & ci()
	{
		return get_instance();
	}	

}
