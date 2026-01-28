<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Print_out extends Admin_Controller 
{ 
	protected $_translation = 'receivable';	
	protected $_model = 'receivable_m';  
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('receivable');
		
		$this->load->helper( "receivable" );
		
		$this->page = "Print Detail Biaya";
		$this->template->title( $this->page . ' - ' . $this->config->item('company_name') );
	}
	
	public function index()
	{
		if( $this->input->is_ajax_request() )
		{
			echo "<script language=\"javascript\">window.location=\"".base_url("cahier/general-payment")."\";</script>";
			exit();
		} else
		{
			redirect( "cahier/general-payment" );
		}
	}
	
	// Rincian Biaya (Jasa) --> Untuk Obat tidak di include (?)
	public function cost_breakdown( $NoBukti = NULL ){
		$cashier = $this->db->where("NoBukti", $NoBukti)->get("SIMtrKasir")->row();
		$registration = $this->db->where("NoReg", $cashier->NoReg)->get("SIMtrRegistrasi")->row();
		 
		$get_rincian = $this->db->query("select * from GetDetailRincianBiaya('{$registration->NoReg}',1)")->result();
		$collection = array();
		
		foreach($get_rincian as $row){
			$collection[ $row->GroupBiaya ][ $row->GroupJasa ][] = $row;
		}
		
		$getdetailpasien = $this->db->select("NRM,NamaPasien,Alamat,JenisPasien")->where("NRM", $registration->NRM)->get( "mPasien" )->row();

		$convert_date = substr($registration->TglReg, 0, 11);
		$date = date('Y-M-d', strtotime(str_replace('-','/', $convert_date)));
		
		$data = array(
					"detail_patient"=> $getdetailpasien,
					"detail_reg" => $registration,
					"detail_data" => $collection,
					"date_reg" => $date,
				);
				
		$html_content =  $this->load->view( "receivable/print/cost_breakdown", $data, TRUE ); 

		$file_name = "Detail";		
		$this->load->helper( "export" );

		export_helper::generate_pdf( $html_content, $file_name, date("Y-M-d") , $margin_bottom = 3.0, $header = NULL, $margin_top = 3.0, $orientation = 'L');
		exit(0);
	}
	
	public function invoice( $NoBukti = NULL ){
		$cashier = $this->db->where("NoBukti", $NoBukti)->get("SIMtrKasir")->row();
		$registration = $this->db->where("NoReg", $cashier->NoReg)->get("SIMtrRegistrasi")->row();
		
		$get_rincian = $this->db->query("select * from GetDetailRincianBiaya('{$registration->NoReg}', 1)")->result();
		$collection = array();
		foreach($get_rincian as $row){
			$collection[ $row->GroupJasa ][$row->JenisBiaya][] = $row;
		}
		
		$new_collection = array();
		$sum = array();
		$summarise = array();
		
		foreach($collection as $row=>$key){
			//print_r($row);oke
			foreach($key as $r_row=>$k_key){
				foreach($k_key as $l_row){
					$sum = $l_row->Nilai * $l_row->Qty;
				}
			}
			$summarise[$row]= $sum;
			//$summarise['amount'][] = $sum;
			//$summarise['groupservice'][] = $row;
		}			
		

		$getdetailpasien = $this->db->select("NRM,NamaPasien,Alamat,JenisPasien,JenisKelamin")->where("NRM", $registration->NRM)->get( "mPasien" )->row();
		$getdetailreg = $this->db->select("TglReg,NoReg,UmurThn")->where("NoReg", $registration->NoReg)->get( "SIMtrRegistrasi" )->row();
		$getdoctor = $this->db->select("Nama_Supplier")->where("Kode_Supplier", $cashier->DokterID)->get("mSupplier")->row();		
		
		//print_r($getkasir);exit;
		
		$getdetaildiscount = $this->db->query("
												  select
												  	a.NoBukti,
													a.IDDiscount,
													a.DokterID,
													a.NilaiDiscount,
													b.NamaDiscount
												  from
												  	SIMtrKasirDiscount a
												  left join 
												  	mDiscount b ON a.IDDiscount=b.IDDiscount
												  where 
												  	a.NoBukti = '{$NoBukti}'
												  ")->result();
		$new_discount = array();
		foreach($getdetaildiscount as $r_row){
			$new_discount[ $r_row->DokterID ][] = $r_row;
		}
		//print_r($getdetaildiscount);exit;
		$money_to_format = receivable_helper::money_to_text($cashier->Nilai - $cashier->NilaiDiscount);

		$convert_date = substr($getdetailreg->TglReg,0,11);
		$date = date('Y-M-d', strtotime(str_replace('-','/', $convert_date)));
		//print_r($date);exit;
		if($getdetailpasien->JenisKelamin == 'M'){
			$gender = 'Laki-Laki';
		}else{
			$gender = 'Perempuan';
		}
		
		
		$data = array(
					"detail_patient"=> $getdetailpasien,
					"detail_reg" => $registration,
					"detail_data" => $collection,
					"detail_gender" => $gender,
					"detail_doctor" => $getdoctor,
					"detail_cashier" => $cashier,
					"detail_discount" => $new_discount,
					"detail_money_to_text" => $money_to_format,
					"detail_money" => $cashier->Nilai - $cashier->NilaiDiscount,
					"date_reg" => $date,
					"detail_summerise" => (object)$summarise,
					"user" => $this->user_auth,
				);
		$html_content =  $this->load->view( "receivable/print/invoice", $data, TRUE ); 
		$file_name = "Detail";		
		$this->load->helper( "export" );

		export_helper::generate_pdf( $html_content, $file_name, date("Y-M-d") , $margin_bottom = 3.0, $header = NULL, $margin_top = 3.0, $orientation = 'L');
		exit(0);
		
	}
	
	public function kwitansi( $NoBukti = NULL )
	{
		$NoBukti = $this->input->get("NoBukti") ? $this->input->get("NoBukti") : $NoBukti;
		
		if ( $this->input->is_ajax_request() || $NoBukti )		
		{

			$response = array(
				"status" => "success",
				"message" => "",
				"code" => 200
			);
						
			if ( $NoBukti == 0 )
			{
				$response = array(
					"status" => "error",
					"message" => lang( 'global:get_failed' ),
					"code" => 500
				);
				print_r(json_encode( $response, JSON_NUMERIC_CHECK ));
				exit(0);
			}

			$item = receivable_helper::get_kwitansi( $NoBukti );
			$spelled = receivable_helper::money_to_text( number_format($item->Nilai - $item->NilaiDiscount , 0, '', '') ); // terbilang
			
			$data = array(
				"item" => $item,
				"spelled" => $spelled,
				"for_payment" => "Biaya Perawatan Rawat Jalan di Klinik KULHEN",
				"user" => $this->user_auth,
			);

			// PDF Content
			$html_content = $this->load->view( "receivable/print/kwitansi", $data, TRUE );    
			$file_name = "Kwitansi-Pembayaran-Kasir-$NoBukti.pdf";
			$footer = '';
			$this->load->helper("export");
			
			export_helper::generate_pdf( $html_content, $file_name, $footer , $margin_bottom = 5, $header = NULL, $margin_top = 2, $orientation = 'P', $margin_left = 2, $margin_right = 2);
			exit(0);
					
		}
	}
	
	public function print_report( $NoReg='',$bill_type='', $nrm='', $state = '1')
	{
		if ($bill_type == 'Obat')
		{
			$getdetail = $this->db->query("select 
										  	NoBukti,
											Tanggal,
											JenisBiaya,
											Qty,
											Nilai,
											GroupBiaya,
											SectionName,
											Disc,
											DokterName
										from 
											GetDetailRincianBiaya('$NoReg',$state) where GroupBiaya like '%OBAT%'")->result();
			//print_r($nrm);exit;
		}else{
			$getdetail = $this->db->query("select 
										  	NoBukti,
											Tanggal,
											JenisBiaya,
											Qty,
											Nilai,
											GroupBiaya,
											SectionName,
											Disc,
											DokterName
										from 
											GetDetailRincianBiaya('$NoReg',$state) where GroupBiaya like '%PERAWATAN%'")->result();
			//print_r($nrm);exit;
			}
			$getdetailpasien = $this->db->select("NRM,NamaPasien,Alamat")->where("NRM", $nrm)->get( "mPasien" )->row();
			//print_r($getdetailpasien);exit;
			
			$sub_total='';			
			foreach ($getdetail as $obat) :
				$sub_total += $obat->Nilai;
			endforeach;
			
			
			//print_r($getdetail);exit;
			
			$data = array(
							"reports" => $getdetail,
							"sub_total" => $sub_total,
							"detail_pasien" => $getdetailpasien,
							"noreg" => $NoReg,
							"bill_type" => $bill_type,
							"file_name" => "Biaya $bill_type",
						);
			$html_content =  $this->load->view( "cashier/print/print", $data, "Bukti $bill_type" ); 
			
			$file_name = "Detail $bill_type";		
			$this->load->helper( "report" );
	
			export_helper::generate_pdf( $html_content, $file_name, date("Y-M-d") , $margin_bottom = 1.0, $header = NULL, $margin_top = 0.3, $orientation = 'L');
			exit(0);
		
	}

}
