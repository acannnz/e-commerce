<?php
defined( 'BASEPATH' ) OR exit( 'No direct scriu access allowed' );

class Audit_service_model extends CI_Model
{
	public $table = 'SIMtrAuditPendapatanJasa';  
	protected $index_key = 'NoBuktiJurnal';
	public $rules;
	
	public function __construct()
	{
		parent::__construct();
		
		$this->rules = [
			'insert' => [
				[
					'field' => 'Keterangan',
                	'label' => lang('label:description'),
               		'rules' => 'required'
				],
			]
		];	
	}
	
	public function create($data)
	{
		$this->db->insert($this->table, $data);
		return $this->db->affected_rows();
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
		$this->load->model('section_model');
		$this->db->where($this->index_key, $key);
		
		$this->db->select('
					a.*,
					b.Lokasi_ID AS Lokasi_Asal,
					b.SectionName AS SectionAsalName,
					c.Lokasi_ID AS Lokasi_Tujuan,
					c.SectionName AS SectionTujuanName
				')
				->from("{$this->table} a")
				->join("{$this->section_model->table} b", "a.SectionAsal = b.SectionID", "INNER")
				->join("{$this->section_model->table} c", "a.SectionTujuan = c.SectionID", "INNER")
				;
						
		$query = $this->db->get();
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
	
	public function gen_id()
	{
		$date = date("Y-m-d");
		$month = date("m");
		$years = substr(date("Y"),2,2);
		$y = date("Y");
		
		
		$order_number = $this->db->query("SELECT MAX(Right([NoBukti], 6))  AS MyID FROM [GD_trAmprahan] WHERE LEN([NoBukti])=14 AND LEFT(LTRIM([NoBukti]),2)='18' AND RIGHT(LEFT(LTRIM([NoBukti]),7),3)='AMP'")->row();
		
		if (empty($order_number->MyID))
		{
			$kode = "$years$month"."/"."AMP"."/"."000001";
		} else {
			$order_number->MyID++;
			$kode = $order_number->MyID;
		}
		return (string) $kode;
	}
	
	
	public function datatable_list(){
		$list_data = $this->db->query("
								SELECT  
									dbo.GD_trAmprahan.NoBukti, 
									dbo.GD_trAmprahan.Tanggal, 
									dbo.SIMmSection.SectionName 
								FROM dbo.GD_trAmprahan	
								INNER JOIN dbo.SIMmSection 
								ON dbo.GD_trAmprahan.SectionAsal = dbo.SIMmSection.SectionID  
								INNER JOIN dbo.SIMmSection SIMmSection_1 
								ON dbo.GD_trAmprahan.SectionTujuan = SIMmSection_1.SectionID 
								WHERE Batal=0 and Realisasi=0 and SIMmSection_1.SectionNAme='GUDANG UMUM'")
					->result_array();
									
		return $list_data;
	}
	


}

