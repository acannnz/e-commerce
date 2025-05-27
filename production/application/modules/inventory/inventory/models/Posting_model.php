<?php
defined('BASEPATH') or exit('No direct scriu access allowed');

class Posting_model extends CI_Model
{
	public $table = 'BL_trPenerimaan';
	protected $index_key = 'No_Penerimaan';
	public $rules;

	public function __construct()
	{
		parent::__construct();

		$this->rules['insert'] = [
			[
				'field' => 'Lokasi_ID',
				'label' => lang('label:section'),
				'rules' => 'required'
			],

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

	public function update_by($data, array $where)
	{
		$this->db->where($where);
		return $this->db->update($this->table, $data);
	}

	public function delete($key)
	{
		$this->db->where($this->index_key, $key);
		return $this->db->delete($this->table);
	}

	public function delete_by(array $where)
	{
		$this->db->where($where);
		return $this->db->delete($this->table);
	}

	public function delete_not_in($key, array $where_not_in)
	{
		$this->db->where($this->index_key, $key);
		$this->db->where_not_in('Barang_ID', $where, FALSE);
		return $this->db->delete($this->table);
	}

	public function get_one($key, $to_array = FALSE)
	{
		$this->db->where($this->index_key, $key);
		$query = $this->db->get($this->table, 1);
		return (TRUE == $to_array) ? $query->row_array() : $query->row();
	}

	public function get_by(array $where, $to_array = FALSE)
	{
		$this->db->where($where);
		$query = $this->db->get($this->table, 1);
		return (TRUE == $to_array) ? $query->row_array() : $query->row();
	}

	public function get_all($limit = NULL, $offset = 0, $where = NULL, $to_array = FALSE)
	{
		if (!is_null($where) && !empty($where)) {
			$this->db->where($where);
		}

		$query = $this->db
			->order_by($this->index_key, 'ASC')
			->get($this->table, $limit, $offset);
		return (TRUE == $to_array) ? $query->result_array() : $query->result();
	}

	public function count_all($where = NULL)
	{
		if (!is_null($where) && !empty($where)) {
			$this->db->where($where);
		}

		$this->db->where($where);
		return (int) ($this->db->count_all_results($this->table));
	}

	public function to_list_html($first_label = '')
	{
		$option_html = "<option value=\"0\">{$first_label}</option>";

		if ($items = $this->get_all()) {
			foreach ($items as $item) {
				$option_html .= "<option value=\"{$item->Kelas_ID}\">{$item->Kode_Kelas} - {$item->Nama_Kelas}</option>";
			}
		}

		return $option_html;
	}

	public function get_selected_posting_data(array $db_where_key_in, $is_cancel = FALSE)
	{
		$db_where = [];

		if ($is_cancel === FALSE) {
			$db_where['a.Status_Batal'] = 0;
			$db_where['a.Sumber_Penerimaan'] = 0;
			$db_where['a.Posting_GL'] = 0;
		}


		/* # select:
			'CN' = COALESCE
				(
					(
						SELECT SUM( VX.NilaiDiskon + VX.Diskon_RP ) FROM 
							(
								SELECT 
									COALESCE( SUM(( BLDetail.Qty_Penerimaan * BLDetail.Harga_Beli ) * BLDetail.Diskon_1 / 100), 0) AS NilaiDiskon, 
									COALESCE( SUM( BLDetail.Qty_Penerimaan * BLDetail.Diskon_Rp), 0) AS Diskon_RP,
									BLDetail.Barang_ID 
								FROM BL_trPenerimaanDetail AS BLDetail 
									INNER JOIN mBarang on mBarang.Barang_ID = BLDetail.Barang_ID
								WHERE 
									BLDetail.Penerimaan_ID = a.Penerimaan_ID 
									AND mBarang.FormulariumUmum = 1 
									AND ( BLDetail.Diskon_1 > 0 OR BLDetail.Diskon_Rp > 0 ) 
								GROUP BY BLDetail.Barang_ID
							) VX
					), 0
				)
		*/

		$db_select = <<<EOSQL
			d.JenisPengadaanID,
			a.Penerimaan_ID, 
			a.Tgl_JatuhTempo, 
			a.User_ID,
			a.No_Penerimaan,
			a.Tgl_Penerimaan,
			SUM( b.Qty_Penerimaan * b.Harga_Beli) AS NilaiTransaksi,
			SUM( b.Qty_Penerimaan * b.Diskon_Rp ) AS Diskon_RP,
			'NilaiDiskon' = SUM(
					CASE WHEN g.CN_Faktur = 0 OR g.CN_Faktur IS NULL
						THEN ( b.Qty_Penerimaan * b.Harga_Beli ) * b.Diskon_1 / 100 
						ELSE 0 
					END
				), 
			'CN' = SUM(
					CASE WHEN g.CN_Faktur > 0 
						THEN ( b.Qty_Penerimaan * b.Harga_Beli ) * b.Diskon_1 / 100 
						ELSE 0 
					END
				),
			a.Nilai_DP,
			a.Keterangan,
			a.Supplier_ID,
			a.Currency_ID,
			a.Lokasi_ID,
			c.SectionID,
			c.SectionName,
			a.No_DO, 
			a.Pajak AS NilaiPPN,
			a.Ongkos_Angkut,
			a.Potongan,
			c.PiutangSHAkun_ID,
			a.Total_Nilai,
			e.Nama_Supplier,
			'Penerimaan' AS JenisTransaksi,
EOSQL;

		$this->db
			->select($db_select)
			->from("{$this->goods_receipt_model->table} a")
			->join("{$this->goods_receipt_detail_model->table} b", "a.Penerimaan_ID = b.Penerimaan_ID", "LEFT OUTER")
			->join("{$this->section_model->table} c", "a.Lokasi_ID = c.Lokasi_ID", "LEFT OUTER")
			->join("{$this->order_model->table} d", "a.Order_ID = d.Order_ID", "LEFT OUTER")
			->join("{$this->supplier_model->table} e", "a.Supplier_ID = e.Supplier_ID", "LEFT OUTER")
			->join("{$this->item_model->table} f", "b.Barang_ID = f.Barang_ID", "INNER")
			->join("{$this->item_category_model->table} g", "f.Kategori_Id = g.Kategori_ID", "LEFT OUTER")
			->group_by([
				"d.JenisPengadaanID",
				"a.Penerimaan_ID",
				"a.Tgl_JatuhTempo",
				"a.User_ID",
				"a.No_Penerimaan",
				"a.Tgl_Penerimaan",
				"a.Nilai_DP",
				"a.Keterangan",
				"a.Supplier_ID",
				"a.Currency_ID",
				"a.Lokasi_ID",
				"c.SectionID",
				"c.SectionName",
				"a.No_DO",
				"a.Pajak",
				"a.Ongkos_Angkut",
				"a.Potongan",
				"c.PiutangSHAkun_ID",
				"a.Total_Nilai",
				"e.Nama_Supplier",
			]);
		if (!empty($db_where)) {
			$this->db->where($db_where);
		}
		if (!empty($db_where_key_in)) {
			$this->db->where_in("a.{$this->index_key}", $db_where_key_in);
		}

		$query = $this->db->get();

		$collection = [];
		foreach ($query->result() as $row) {

			if ($row->No_DO == "" || empty($row->No_DO) || $row->No_DO = "00" || $row->No_DO = "00.") {
				$row->NoBukti = trim($row->No_Penerimaan);
			} else {

				$date = DateTime::createFromFormat('Y-m-d H:i:s', $row->Tgl_Penerimaan);
				switch ($row->No_DO) {
					case 'CASH':
						$row->NoBukti = sprintf("%s-%s", $row->No_DO, trim($row->No_Penerimaan));
						break;
					default:
						$row->NoBukti = sprintf("%s-%s%s%s", $row->No_DO, $date->format('j'), $date->format('n'), $date->format('y'));
				}
			}

			$row->Tgl_Penerimaan = substr($row->Tgl_Penerimaan, 0, 10);
			$row->Tgl_JatuhTempo = substr($row->Tgl_JatuhTempo, 0, 10);
			$row->Keterangan = "Penerimaan {$row->No_Penerimaan}";
			$collection[] = $row;
		}

		return $collection;
	}

	public function get_selected_posting_data_not_order(array $db_where_key_in, $is_cancel = FALSE)
	{
		$db_where = [];

		if ($is_cancel === FALSE) {
			$db_where['a.Status_Batal'] = 0;
			$db_where['a.Sumber_Penerimaan'] = 0;
			$db_where['a.Posting_GL'] = 0;
		}


		/* # select:
			'CN' = COALESCE
				(
					(
						SELECT SUM( VX.NilaiDiskon + VX.Diskon_RP ) FROM 
							(
								SELECT 
									COALESCE( SUM(( BLDetail.Qty_Penerimaan * BLDetail.Harga_Beli ) * BLDetail.Diskon_1 / 100), 0) AS NilaiDiskon, 
									COALESCE( SUM( BLDetail.Qty_Penerimaan * BLDetail.Diskon_Rp), 0) AS Diskon_RP,
									BLDetail.Barang_ID 
								FROM BL_trPenerimaanDetail AS BLDetail 
									INNER JOIN mBarang on mBarang.Barang_ID = BLDetail.Barang_ID
								WHERE 
									BLDetail.Penerimaan_ID = a.Penerimaan_ID 
									AND mBarang.FormulariumUmum = 1 
									AND ( BLDetail.Diskon_1 > 0 OR BLDetail.Diskon_Rp > 0 ) 
								GROUP BY BLDetail.Barang_ID
							) VX
					), 0
				)
		*/

		$db_select = <<<EOSQL
			a.JenisPengadaanID,
			a.Penerimaan_ID, 
			a.Tgl_JatuhTempo, 
			a.User_ID,
			a.No_Penerimaan,
			a.Tgl_Penerimaan,
			SUM( b.Qty_Penerimaan * b.Harga_Beli) AS NilaiTransaksi,
			SUM( b.Qty_Penerimaan * b.Diskon_Rp ) AS Diskon_RP,
			'NilaiDiskon' = SUM(
					CASE WHEN g.CN_Faktur = 0 OR g.CN_Faktur IS NULL
						THEN ( b.Qty_Penerimaan * b.Harga_Beli ) * b.Diskon_1 / 100 
						ELSE 0 
					END
				), 
			'CN' = SUM(
					CASE WHEN g.CN_Faktur > 0 
						THEN ( b.Qty_Penerimaan * b.Harga_Beli ) * b.Diskon_1 / 100 
						ELSE 0 
					END
				),
			a.Nilai_DP,
			a.Keterangan,
			a.Supplier_ID,
			a.Currency_ID,
			a.Lokasi_ID,
			c.SectionID,
			c.SectionName,
			a.No_DO, 
			a.Pajak AS NilaiPPN,
			a.Ongkos_Angkut,
			a.Potongan,
			c.PiutangSHAkun_ID,
			a.Total_Nilai,
			e.Nama_Supplier,
			'Penerimaan' AS JenisTransaksi,
EOSQL;

		$this->db
			->select($db_select)
			->from("{$this->goods_receipt_model->table} a")
			->join("{$this->goods_receipt_detail_model->table} b", "a.Penerimaan_ID = b.Penerimaan_ID", "LEFT OUTER")
			->join("{$this->section_model->table} c", "a.Lokasi_ID = c.Lokasi_ID", "LEFT OUTER")
			// ->join("{$this->order_model->table} d", "a.Order_ID = d.Order_ID", "LEFT OUTER")
			->join("{$this->supplier_model->table} e", "a.Supplier_ID = e.Supplier_ID", "LEFT OUTER")
			->join("{$this->item_model->table} f", "b.Barang_ID = f.Barang_ID", "INNER")
			->join("{$this->item_category_model->table} g", "f.Kategori_Id = g.Kategori_ID", "LEFT OUTER")
			->group_by([
				"a.JenisPengadaanID",
				"a.Penerimaan_ID",
				"a.Tgl_JatuhTempo",
				"a.User_ID",
				"a.No_Penerimaan",
				"a.Tgl_Penerimaan",
				"a.Nilai_DP",
				"a.Keterangan",
				"a.Supplier_ID",
				"a.Currency_ID",
				"a.Lokasi_ID",
				"c.SectionID",
				"c.SectionName",
				"a.No_DO",
				"a.Pajak",
				"a.Ongkos_Angkut",
				"a.Potongan",
				"c.PiutangSHAkun_ID",
				"a.Total_Nilai",
				"e.Nama_Supplier",
			]);
		if (!empty($db_where)) {
			$this->db->where($db_where);
		}
		if (!empty($db_where_key_in)) {
			$this->db->where_in("a.{$this->index_key}", $db_where_key_in);
		}

		$query = $this->db->get();

		$collection = [];
		foreach ($query->result() as $row) {

			if ($row->No_DO == "" || empty($row->No_DO) || $row->No_DO = "00" || $row->No_DO = "00.") {
				$row->NoBukti = trim($row->No_Penerimaan);
			} else {

				$date = DateTime::createFromFormat('Y-m-d H:i:s', $row->Tgl_Penerimaan);
				switch ($row->No_DO) {
					case 'CASH':
						$row->NoBukti = sprintf("%s-%s", $row->No_DO, trim($row->No_Penerimaan));
						break;
					default:
						$row->NoBukti = sprintf("%s-%s%s%s", $row->No_DO, $date->format('j'), $date->format('n'), $date->format('y'));
				}
			}

			$row->Tgl_Penerimaan = substr($row->Tgl_Penerimaan, 0, 10);
			$row->Tgl_JatuhTempo = substr($row->Tgl_JatuhTempo, 0, 10);
			$row->Keterangan = "Penerimaan {$row->No_Penerimaan}";
			$collection[] = $row;
		}

		return $collection;
	}
}
