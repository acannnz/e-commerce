<?php
defined( 'BASEPATH' ) OR exit( 'No direct scriu access allowed' );

class Component_model extends CI_Model
{
	public $table = 'SIMmKomponenBiaya';
	public $index_key = 'KomponenBiayaID';
	public $rules;
	
	public function __construct()
	{
		$this->rules = [
			'insert' => [
				[
					'field' => $this->index_key,
                	'label' => lang('label:code'),
               		'rules' => "required|is_unique[{$this->table}.{$this->index_key}]"
				],
				[
					'field' => 'KomponenName',
                	'label' => lang('label:name'),
               		'rules' => "required"
				],
				[
					'field' => 'KelompokAkun',
                	'label' => lang('label:account_group'),
               		'rules' => "required"
				],
				[
					'field' => 'PostinganKe',
                	'label' => lang('label:posting_to'),
               		'rules' => "required"
				],
				[
					'field' => 'HutangKe',
                	'label' => lang('label:debt_to'),
               		'rules' => "required"
				],
				[
					'field' => 'KelompokJasa',
                	'label' => lang('label:service_group'),
               		'rules' => "required"
				],
			],
			'update' => [
				[
					'field' => 'KomponenName',
                	'label' => lang('label:name'),
               		'rules' => "required"
				],
			]
		];
	}
	
	public function create($data)
	{
		$this->db->insert($this->table, $data);
		return $this->db->insert_id(); 
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
	
	public function dropdown_data($where = NULL)
	{
		if($items = $this->get_all(NULL, 0, $where))
		{
			foreach($items as $item)
			{
				$option_data[$item->Kode_Kategori] = $item->Kategori_Name;
			}
		}	
		return $option_data;
	}
	
	public function dropdown_html($where = NULL)
	{
		if($items = $this->get_all(NULL, 0, $where))
		{
			foreach($items as $item)
			{
				$option_html .= "<option value=\"{$item->Kode_Kategori}\">{$item->Kode_Kategori} - {$item->Kategori_Name}</option>";
			}
		}	
		return $option_html;
	}
	
	public function dropdown_static($key)
	{
		$option_static['KelompokAkun'] = [
			'Pendapatan' => lang('label:revenue'),
			'Biaya' => lang('label:expense')
		];
		
		$option_static['PostinganKe'] = [
			'Hutang' => lang('label:payable'),
			'Piutang' => lang('label:receivable'),
			'GL' => lang('label:general_ledger'),
			'K.S.O' => lang('label:k.s.o')
		];
				
		$option_static['HutangKe'] = [
			'None' => lang('label:none'),
			'Dokter' => lang('label:docter'),
			'Dokter Anak' => lang('label:pediatrician'),
			'Dokter Asisten' => lang('label:doctor_assistant'),
			'Dokter Anastesi' => lang('label:anesthesiologist'),
			'Dokter Asisten Anastesi' => lang('label:doctor_assistant_anesthesiologist'),
			'Dokter Operator' => lang('label:doctor_operator'),
			'Asisten/Instrumen' => lang('label:assistant_instrument'),
			'On Loop' => lang('label:on_loop'),
		];

		$option_static['KelompokJasa'] = [
			'Jasa Sarana' => lang('label:service_facility'),
			'Jasa Pelayanan' => lang('label:service_treatment'),
			'Obat' => lang('label:drug')
		];
		
		return $option_static[$key];
	}
	
}

