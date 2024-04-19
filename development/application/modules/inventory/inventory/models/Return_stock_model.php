<?php
defined( 'BASEPATH' ) OR exit( 'No direct scriu access allowed' );

class Return_Stock_model extends CI_Model
{
	public $table = 'BL_trRetur'; 
	protected $index_key = 'Retur_ID';
	
	public function create($data)
	{
		//$this->db->query("SET IDENTITY_INSERT BL_trPermintaan ON");
		$this->db->insert($this->table, $data);
		//$this->db->query("SET IDENTITY_INSERT BL_trPermintaan OFF");
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
	
	public function gen_retur_number()
	{
		$date = date("Y-m-d");
		$month = date("m");
		$years = substr(date("Y"),2,2);
		$y = date("Y");
		
		
		$order_number = $this->db->query("SELECT MAX(No_Retur) AS MyID from BL_trRetur where year(Tgl_Retur) = '$y'")->row();
		
		if (empty($order_number->MyID))
		{
			$kode = "RT"."/"."$y"."/"."0001";
		} else {
			$order_number->MyID++;
			$kode = $order_number->MyID;
		}
		return (string) $kode;
	}
	
	public function gen_request_id()
	{
		$date = date("Y-m-d");
		$month = date("m");
		$years = substr(date("Y"),2,2);
		$y = date("Y");
		
		$order_number = $this->db->query("SELECT MAX(Retur_ID) AS Retur_ID from BL_trRetur")->row();
		//$order_number = $this->db->get();
		
		//print_r($order_number->No_Permintaan);exit;
		
		$order_number->Retur_ID++;
		$kode = $order_number->Retur_ID;
		
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
	
	public function cek_stock($lokasi_id,$barang_id){
		$cek_stock = $this->db->query("Select Qty_Stok,Barang_ID from mBarangLokasiNew where Lokasi_ID=$lokasi_id and Barang_ID=$barang_id ")->row();
		$item_data = $this->db->query("Select Nama_Barang From mBarang Where Barang_ID=$cek_stock->Barang_ID")->row();
		$data = (object)array("Qty_Stok"=>$cek_stock->Qty_Stok, "Nama_Barang"=>$item_data->Nama_Barang);
		return $data;
	}


}

