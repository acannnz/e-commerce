<?php
defined( 'BASEPATH' ) OR exit( 'No direct scriu access allowed' );

class Goods_receipt_model extends CI_Model
{
	public $table = 'BL_trPenerimaan'; 
	protected $index_key = 'Penerimaan_ID';
	public $rules;
	
	public function __construct()
	{
		parent::__construct();
		
		$this->rules = [
			"insert" => [
				[
					'field' => 'Tgl_Penerimaan',
					'label' => lang('label:date'),
					'rules' => 'required'
				],
				[
					'field' => 'No_Penerimaan',
					'label' => lang('label:goods_receipt_number'),
					'rules' => 'required'
				],
				[
					'field' => 'Lokasi_ID',
					'label' => lang('label:warehouse'),
					'rules' => 'required'
				],
				[
					'field' => 'Keterangan',
					'label' => lang('label:description'),
					'rules' => 'required'
				],
				[
					'field' => 'Order_ID',
					'label' => lang('label:no_po'),
					'rules' => 'required'
				],
				[
					'field' => 'Supplier_ID',
					'label' => lang('label:supplier'),
					'rules' => 'required'
				],
				[
					'field' => 'Tgl_JatuhTempo',
					'label' => lang('label:due_date'),
					'rules' => 'required'
				],
				[
					'field' => 'No_DO',
					'label' => lang('label:no_do'),
					'rules' => 'required'
				],
			]
		];
	}
	
	public function create($data)
	{
		$this->db->insert($this->table, $data);
		return (int) $this->db->insert_id(); 
	}
	
	public function mass_create($collection)
	{
		return $this->db->insert_batch($this->table, $collection);
	}

	public function update($data, $key)
	{
		$this->db->where($this->index_key, $key);
		return $this->db->update($this->table, $data);
	}
	
	public function update_by($data, Array $where)
	{
		$this->db->where($where);
		return $this->db->update($this->table, $data);
	}
	
	public function delete($key)
	{
		$this->db->where($this->index_key, $key);
		return $this->db->delete($this->table);
	}
	
	public function delete_by(Array $where)
	{
		$this->db->where($where);
		return $this->db->delete($this->table);
	}
	
	public function get_one($key, $to_array = FALSE)
	{
		$this->db->where($this->index_key, $key);
		$query = $this->db->get($this->table, 1);
		return (TRUE == $to_array) ? $query->row_array() : $query->row();
	}
	
	public function get_by(Array $where, $to_array = FALSE)
	{
		$this->db->where($where);
		$query = $this->db->get($this->table, 1);
		return (TRUE == $to_array) ? $query->row_array() : $query->row();
	}
	
	public function get_all($limit = NULL, $offset = 0, $where = NULL, $to_array = FALSE)
	{
		if (!is_null($where) && !empty($where)){ $this->db->where($where); }
		
		$query = $this->db
			->order_by($this->index_key, 'ASC')
			->get($this->table, $limit, $offset);		
		return (TRUE == $to_array) ? $query->result_array() : $query->result();
	}
	
	public function count_all($where = NULL)
	{
		if (!is_null($where) && !empty($where)){ $this->db->where($where); }
		
		$this->db->where($where);		
		return (int) ($this->db->count_all_results($this->table));
	}
	
	public function to_list_html($first_label = '')
	{
		$option_html = "<option value=\"0\">{$first_label}</option>";
		
		if ($items = $this->get_all())
		{
			foreach($items as $item)
			{
				$option_html .= "<option value=\"{$item->Kelas_ID}\">{$item->Kode_Kelas} - {$item->Nama_Kelas}</option>";
			}
		}
		
		return $option_html;
	}
	
	public function gen_request_number()
	{
		$date = date("Y-m-d");
		$month = date("m");
		$years = substr(date("Y"),2,2);
		$y = date("Y");
		
		
		$order_number = $this->db->query("SELECT MAX(No_Penerimaan) AS No_Penerimaan from BL_trPenerimaan where year(Tgl_Penerimaan) = '$y'")->row();
		//$order_number = $this->db->get();
		
		//print_r($order_number->No_Permintaan);exit;
		
		if (empty($order_number->No_Penerimaan))
		{
			$kode = "$years"."/"."$month"."/"."RC"."/"."000001";
		} else {
			$order_number->No_Penerimaan++;
			$kode = $order_number->No_Penerimaan;
		}
		return (string) $kode;
	}
	
	public function get_data_detail($id_order){
		$order = $this->db->query("SELECT VX.Barang_id,
								VX.Kode_Pajak,
								VX.Rate_Pajak,
								VX.Order_ID, 
								VX.Qty_Order, 
								VX.No_Permintaan,
								VX.Qty_Penerimaan, 
								round(VX.Harga_Order,0) as Harga_Order, 
								VX.Diskon_1, 
								VX.PPn, 
								VX.Kode_Barang, 
								VX.Kode_satuan,
								VX.NmJenis ,
								VX.Qty_Terima,
								VX.Nama_Supplier,
								VX.Tgl_Order,
								VX.Total_Nilai,
								VX.Type_Pembayaran,
								VX.Term_Pembayaran,
								VX.Nilai_DP,
								VX.Type_Diskon,
								VX.No_Order,
								VX.NmJenis,
								VX.Nama_Barang,
								VX.Konversi,
								vx.Urutan  
							FROM  
								(SELECT 
									BL_trOrderDetail.Urutan,
									BL_trOrderDetail.Barang_id,
									BL_trOrderDetail.Kode_Pajak,
									BL_trOrderDetail.Rate_Pajak,
									BL_trOrderDetail.Order_ID, 
									BL_trOrderDetail.Qty_Order, 
									BL_trOrderDetail.Qty_Penerimaan, 
									BL_trOrderDetail.Harga_Order, 
									BL_trOrderDetail.Diskon_1, 
									BL_trOrderDetail.No_Permintaan,
									BL_trOrderDetail.PPn, 
									Vw_BL_ORD.Nama_Supplier,
									Vw_BL_ORD.Tgl_Order,
									Vw_BL_ORD.Total_Nilai,
									Vw_BL_ORD.Type_Pembayaran,
									Vw_BL_ORD.Term_Pembayaran,
									Vw_BL_ORD.Nilai_DP,
									Vw_BL_ORD.Type_Diskon,
									Vw_BL_ORD.No_Order,
									mBarang.Nama_Barang,
									mBarang.Konversi,
									Vw_BarangSatuan.Kode_Barang, 
									BL_trOrderDetail.Kode_satuan,
									SIMmJenisBarang.NmJenis ,
									'Qty_Terima'=(Select coalesce(SUM(BL_trPenerimaanDetail.Qty_Penerimaan),0) as JmlTerima  
												from BL_trPenerimaan 
												inner join BL_trPenerimaanDetail 
												on BL_trPenerimaan.Penerimaan_ID=BL_trPenerimaanDetail.Penerimaan_ID  
												where BL_trPenerimaan.Status_Batal=0 
												and BL_trPenerimaan.Order_ID =BL_trOrderDetail.Order_ID 
												And BL_trPenerimaanDetail.Barang_ID=BL_trOrderdetail.Barang_ID)  
									FROM BL_trOrderDetail 
									JOIN Vw_BL_ORD
									ON BL_trOrderDetail.Order_ID = Vw_BL_ORD.Order_ID
									JOIN mBarang
									ON BL_trOrderDetail.Barang_ID = mBarang.Barang_ID
									INNER JOIN Vw_BarangSatuan 
									ON BL_trOrderDetail.Barang_ID = Vw_BarangSatuan.Barang_ID  
									INNER JOIN SIMmJenisBarang 
									ON BL_trOrderDetail.JenisBarangID=SIMmJenisBarang.IDJENis
									Where (BL_trOrderDetail.Order_ID = $id_order ))VX 
							WHERE VX.Qty_Order>VX.Qty_Terima  ORDER BY vx.Urutan,VX.Kode_Barang,VX.Kode_satuan")->result_array();
		return $order;
	}
	
	public function gen_request_id()
	{
		$date = date("Y-m-d");
		$month = date("m");
		$years = substr(date("Y"),2,2);
		$y = date("Y");
		
		
		$order_number = $this->db->query("SELECT MAX(Penerimaan_ID) AS Penerimaan_ID from BL_trPenerimaan")->row();
		$order_number->Penerimaan_ID++;
		$kode = $order_number->Penerimaan_ID;
		
		return (string) $kode;
	}
	
	public function summerize($permintaan_id){
		$sum = $this->db->query("Select (Qty_Permintaan * Harga_Terakhir) AS sub_total from BL_trPermintaanDetail where Permintaan_ID = '$permintaan_id' ")->result();
		
		//print_r($sum);exit;
		$jumlah_total = 0;
		foreach($sum as $row){
			$jumlah_total = $jumlah_total + $row->sub_total;
		}
		return $jumlah_total;
	}


}

