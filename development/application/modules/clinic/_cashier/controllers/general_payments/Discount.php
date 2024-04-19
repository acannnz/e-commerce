<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Discount extends Admin_Controller
{
	protected $_translation = '';	
	protected $_model = '';
	
	public function __construct()  
	{
		parent::__construct();
		$this->simple_login->check_user_role('cashier');
		
		$this->load->model("cashier_discount_model");
		$this->load->model("discount_model");
		$this->load->model("supplier_model");
		
		$this->load->model("service_model");
		$this->load->model("class_model");
		
		$this->load->model("poly_model");
		$this->load->model("poly_transaction_model");
		$this->load->model("poly_transaction_detail_model");

		
		$this->load->helper("general_payment");
		
	}
	
	public function index( $item = NULL )
	{
		@$data = array(
				"item" => $item, 
				"collection" => general_payment_helper::get_detail_discount( @$item->NoBukti ),
				"delete_url" => base_url("cashier/general-payments/discount/item_delete"),
				"lookup_discount" => base_url("cashier/general-payments/discount/lookup_discount"),
				"lookup_supplier" => base_url("cashier/general-payments/discount/lookup_supplier"),
				"lookup_discount_jasa" => base_url("cashier/general-payments/discount/lookup_discount_jasa"),
			);
						
		$this->load->view( 'general_payment/form/discount', @$data );	
	}

	public function view( $item = NULL )
	{
		
		@$data = array(
				"item" => $item, 
				"collection" => general_payment_helper::get_detail_discount( @$item->cashier->NoBukti ),
			);
						
		$this->load->view( 'general_payment/form/discount_view', @$data );	
	}
		
	public function item_delete()
	{
		
		if( ! $this->input->is_ajax_request() )
		{
			show_error( "Bad Request", 400 );
		}
		$response = array(
				"status" => "success",
				"error" => "",
				"code" => "200"
			);
		
		if( $this->input->post() )
		{
			$post_data = $this->input->post( 'f', TRUE );
			$item = (object) $post_data;
			if( general_payment_helper::find_discount( $item->NoBukti, $item->IDDiscount ) )
			{
				if( !$this->db->delete( "SIMtrKasirDiscount", array("NoBukti" => @$item->NoBukti, "IDDiscount"=> @$item->IDDiscount) ) )
				{
					$response["error"] = "Internal Server Error";
					$response["status"] = "error";			
					$response["code"] = "500";
				}
			} else
			{
				$response["error"] = "Not Found";
				$response["status"] = "error";
				$response["code"] = "404";
			}
		} else
		{
			$response["error"] = "Precondition Failed";
			$response["status"] = "error";
			$response["code"] = "412";
		}
		
		$this->template->build_json( $response );
	}
	
	public function get_service_by_doctor(){		
		if($this->input->get())
		{
			$doctor_id = $this->input->get('DokterID');
			$no_reg = $this->input->get('NoReg');
			$id_discount = $this->input->get('IDDiscount');
			$discount = $this->discount_model->get_one($id_discount);
			
								

			$this->db->select("
						d.JasaID, d.JasaName, e.IDDiscount, b.Keterangan,
						'NamaKelas' = case  
							when b.Titip=1 then g.NamaKelas  
							when b.Titip=0 then f.NamaKelas  
						end
					")
					->from("{$this->poly_model->table} a")
					->join("{$this->poly_transaction_model->table} b", "a.NoBukti = b.NoBukti", "INNER")
					->join("{$this->service_model->table} d", "b.JasaID = d.JasaID", "INNER")				
					->join("{$this->class_model->table} f", "b.KelasID = f.KelasID", "LEFT OUTER")
					->join("{$this->class_model->table} g", "b.KelasAsalID = g.KelasID", "LEFT OUTER")
					->where([
						// 'b.DokterID' => $doctor_id, 
						'a.RegNo' => $no_reg, 
						'e.IDDiscount' => $id_discount
					]);

			if($discount->DiskonKomponen == 1){
				$this->db->join("{$this->poly_transaction_detail_model->table} c", "b.NoBukti = c.NoBukti AND b.JasaID = c.JasaID", "INNER");
				$this->db->join("{$this->discount_model->table} e", "c.KomponenID = e.KomponenBiayaID", "INNER");
			} elseif($discount->DiskonGroupJasa == 1){
				$this->db->join("{$this->discount_model->table} e", "d.GroupJasaID = e.GroupJasaID", "INNER");
			}
					
			$query = $this->db->get();
			response_json(['collection' => $query->result(), 'status' => 'success']);
		}
	}
	
	/*
		select sum(Nilai) as Nilai from dbo.GetDetailKomponenBiaya_ForDiskon_GABUNGAN('180723REG-000010','','','') 
		where KomponenID='DT01' and JasaID='JAS0087'  
		and ( DokterID='DOK-001' OR DokterAssBedahID='DOK-001' OR DOkterAnakID='DOK-001' OR DokterAnasID='DOK-001' OR DokterBacaID='DOK-001' OR DokterRujukanID='DOK-001') 
		and Dirujuk=0 AND Keterangan='dr. I B Semadi Putra, SpOG'
	*/
	public function get_discount_value(){		
		if($this->input->get())
		{
			$doctor_id = $this->input->get('DokterID');
			$no_reg = $this->input->get('NoReg');
			$service_id = $this->input->get('JasaID');
			$id_discount = $this->input->get('IDDiscount');
			$discount = $this->discount_model->get_one($id_discount);
			$supplier = $this->supplier_model->get_by(['Kode_Supplier' => $doctor_id]);
			
			if( $discount->DiskonTotal == 1)
			{
				$this->db->select("ROUND(SUM((Qty *(Nilai - (Nilai * disc / 100)) + HExt)), 0) AS Nilai");
				$this->db->from("GetDetailRincianBiayaGabungan('{$no_reg}','','','')");
			} elseif( $discount->DiskonKomponen == 1)
			{
				$this->db->select("SUM(Nilai) AS Nilai");
				$this->db->from("GetDetailKomponenBiaya_ForDiskon_GABUNGAN('{$no_reg}','','','')");
				$this->db->where([
						'KomponenID' => $discount->KomponenBiayaID, 
						'JasaID' => $service_id, 
						'Dirujuk' => 0,
						//'Keterangan' => $supplier->Nama_Supplier
					])
					->group_by("DokterID, DokterAssBedahID, DOkterAnakID, DokterAnasID, DokterBacaID, DokterRujukanID");
					#MATIKAN KARNA ERROR
					// ->group_start()
					// 	->or_where([
					// 		'DokterID' => $doctor_id, 
					// 		'DokterAssBedahID' => $doctor_id, 
					// 		'DOkterAnakID' => $doctor_id,
					// 		'DokterAnasID' => $doctor_id,
					// 		'DokterBacaID' => $doctor_id,
					// 		'DokterRujukanID' => $doctor_id
					// 	])
					// ->group_end();
			}
			
			$query = $this->db->get();
				
			response_json(['collection' => $query->row(), 'status' => 'success']);
		}
	}
	
	
	public function lookup_discount( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'cashier/general_payment/lookup/discounts' );
		} 
	}
	
	public function lookup_supplier( $iddiscount='', $is_ajax_request=false )
	{		
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$data = array("iddiscount"=>$iddiscount);
			$this->load->view( 'cashier/general_payment/lookup/suppliers', $data );
		} 
	}
	
	public function lookup_discount_jasa( $iddiscount='', $noreg='', $idindex='',$is_ajax_request=false )
	{		
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$data = array("iddiscount"=>$iddiscount,"noreg"=>$noreg, "idindex"=>$idindex);
			$this->load->view( 'cashier/general_payment/lookup/discount_jasa', $data );
		} 
	}
		
}