<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Print_out extends Admin_Controller 
{ 
	protected $_translation = 'general_payment';	
	protected $_model = 'general_payment_m';  
	protected $nameroutes = 'cashier/general_payment/general_payments/print_out';
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('cashier');
		
		$this->load->helper( "general_payment" );
		$this->load->model("poly_transaction_model");
		$this->load->model("poly_transaction_detail_model");
		$this->load->model("poly_transaction_pop_model");
		
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
		$registration = $this->db->where("NoReg", $NoBukti)->get("SIMtrRegistrasi")->row();
		if( ! $registration )
		{ 
			$cashier = $this->db->where("NoBukti", $NoBukti)->get("SIMtrKasir")->row();
			$registration = $this->db->where("NoReg", $cashier->NoReg)->get("SIMtrRegistrasi")->row();
		}		
		 
		$get_rincian = $this->db->query("select * from GetDetailRincianBiaya('{$registration->NoReg}',1)")->result();
		$collection = array();		
		foreach($get_rincian as $row){
			
			$drug_component = $this->poly_transaction_detail_model->get_by(['NoBukti' => $row->NoBukti, 'JasaID' => $row->KodeJenisBiaya, 'KomponenID' => 'DT51']);
			if(!empty($drug_component))
			{
				$row->Nilai = $row->Nilai - $drug_component->Harga;
				$_drug = (object) ((array) $row);
				$_drug->JenisBiaya = 'Obat';
				$_drug->Nilai = $drug_component->Harga;
				$collection[ $row->GroupBiaya ][ $row->GroupJasa][] = $_drug;
			}			
			
			$collection[ $row->GroupBiaya ][ $row->GroupJasa ][] = $row;
		}
		
		$getdetailpasien = $this->db->select("NRM, NamaPasien, Alamat, JenisPasien")->where("NRM", $registration->NRM)->get( "mPasien" )->row();
		$convert_date = substr($registration->TglReg, 0, 11);
		$date = date('Y-M-d', strtotime(str_replace('-','/', $convert_date)));
		
		$data = array(
					"detail_patient"=> $getdetailpasien,
					"detail_reg" => $registration,
					"detail_data" => $collection,
					"date_reg" => $date,
				);
		
		$html_content =  $this->load->view( "general_payment/print/cost_breakdown", $data, TRUE ); 
		
		$file_name = "Detail";		
		$this->load->helper( "export" );

		export_helper::print_pdf( $html_content, $file_name, date("d-M-Y") , $margin_bottom = 3.0, $header = NULL, $margin_top = 3.0, $orientation = 'P');
		exit(0);
	}
	
	public function invoice( $NoBukti = NULL ){
		$cashier = $this->db->where("NoBukti", $NoBukti)->get("SIMtrKasir")->row();
		$registration = $this->db->where("NoReg", $cashier->NoReg)->get("SIMtrRegistrasi")->row();
		//$patient = $this->db->where("NoReg", $cashier->NoReg)->get("mPasien")->row();

		$get_rincian = $this->db->query("select * from GetDetailRincianBiaya('{$registration->NoReg}', 1)")->result();

		$collection = array();
		foreach($get_rincian as $row){
			$drug_component = $this->poly_transaction_detail_model->get_by(['NoBukti' => $row->NoBukti, 'JasaID' => $row->KodeJenisBiaya, 'KomponenID' => 'DT51']);
			if(!empty($drug_component))
			{
				$row->Nilai = $row->Nilai - $drug_component->Harga;
				$_drug = (object) ((array) $row);
				$_drug->JenisBiaya = 'Obat';
				$_drug->Nilai = $drug_component->Harga;
				$collection[ $row->GroupJasa ]['Obat'][] = $_drug;
			}	
			$collection[ $row->GroupJasa ][$row->JenisBiaya][] = $row;
		}
		
		$new_collection = array();
		$sum = array();
		$summarise = array();
		
		foreach($collection as $row => $key){
			$sum = 0;
			foreach($key as $r_row => $k_key){
				
				foreach ($k_key as $l_row) {
    if ($row == 'Obat / Medicine') {
        $discountPercentage = $l_row->Disc; 

        if ($discountPercentage > 0) {
            $discountAmount = ($l_row->Nilai * $l_row->Qty * $discountPercentage) / 100; 
            $sum += (($l_row->Nilai * $l_row->Qty) - $discountAmount) + $l_row->BiayaResep;
        } else {
            $sum += ($l_row->Nilai * $l_row->Qty) + $l_row->BiayaResep;
        }
    } else {
        $sum += ($l_row->Nilai * $l_row->Qty) - $l_row->Disc + $l_row->KelebihanPlafon;
    }
}
			}
			$summarise[$row] = $sum;
			//print_r($row);exit;
			//$summarise['amount'][] = $sum;
			//$summarise['groupservice'][] = $row;
		}			
		


		$getdetailpasien = $this->db->select("NRM,NamaPasien,Alamat,JenisPasien,JenisKelamin,TglLahir,Phone,NoIdentitas,Email")->where("NRM", $registration->NRM)->get( "mPasien" )->row();
		//print_r($getdetailpasien);exit;
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

		//GET GRANDTOTAL BIAYA
		if(!empty($collection)): $grandtotal_total = 0;
			foreach($collection as $group_biaya => $key): 
				$grandtotal_total += $summarise[$group_biaya];
			endforeach;
		endif;
		
		//print_r($collection);exit;
		//TERBILANG
		@$money_to_format = general_payment_helper::money_to_text($grandtotal_total - $cashier->NilaiDiscount + $cashier->AddCharge);
		@$money_to_format_english = general_payment_helper::money_to_text_english($grandtotal_total - $cashier->NilaiDiscount + $cashier->AddCharge);
		//print_r($cashier->AddCharge);exit;

		$convert_date = substr($getdetailreg->TglReg,0,11);
		$date = date('Y-M-d', strtotime(str_replace('-','/', $convert_date)));
		
		$type_payment = $this->db->select("a.*, b.Description")
								->from('SIMtrKasirDetail a')
								->join('mJenisBayar b', 'a.IDBayar = b.IDBayar', 'INNER')
								->where('NoBukti', $NoBukti)
								->get()->result();
		

		$data = array(
					"detail_patient"=> $getdetailpasien,
					"detail_reg" => $registration,
					"detail_data" => $collection,
					"detail_gender" => ($getdetailpasien->JenisKelamin == 'M') ? 'Laki-Laki' : 'Perempuan',
					"detail_doctor" => $getdoctor,
					"detail_cashier" => $cashier,
					"detail_discount" => $new_discount,
					"detail_money_to_text" => @$money_to_format,
					"detail_money_to_text_english" => @$money_to_format_english,
					"detail_money" => $cashier->Nilai - $cashier->NilaiDiscount,
					"type_payment" => $type_payment,
					"date_reg" => $date,
					"detail_summerise" => (object)$summarise,
					"user" => $this->user_auth,
				);
				
			//print_r($registration);exit;

		$html_content =  $this->load->view( "general_payment/print/invoice", $data, TRUE ); 
		//print_r($money_to_format);exit;
		
		$file_name = "Invoice {$NoBukti}";		
		$this->load->helper( "export" );
		
		export_helper::generate_pdf( $html_content, $file_name, date("d-M-Y") , $margin_bottom = 3.0, $header = NULL, $margin_top = 3.0, $orientation = 'P');
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

			$item = general_payment_helper::get_kwitansi( $NoBukti );
			$spelled = general_payment_helper::money_to_text( number_format($item->JumlahBayar , 0, '', '') ); // terbilang
			
			$data = array(
				"item" => $item,
				"spelled" => $spelled,
				"for_payment" => "Biaya Perawatan Rawat Jalan di Apotek PIP Renon",
				"user" => $this->user_auth,
			);

			// PDF Content
			$html_content = $this->load->view( "general_payment/print/kwitansi", $data, TRUE );    
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
