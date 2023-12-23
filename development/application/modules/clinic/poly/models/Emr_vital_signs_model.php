<?php
defined('BASEPATH') or exit('No direct scriu access allowed');

class Emr_vital_signs_model extends CI_Model
{
	public $table = 'SIMtrEMRVitalSigns';
	public $index_key = 'IdVitalSigns';
	public $rules;

	public function __construct()
	{
		$this->rules = [
			'insert' => [
				[
					'field' => 'Height',
					'label' => 'TINGGI BADAN',
					'rules' => "required"
				],
				[
					'field' => 'Weight',
					'label' => 'BERAT BADAN',
					'rules' => "required"
				],
				[
					'field' => 'Temperature',
					'label' => 'SUHU TUBUH',
					'rules' => "required"
				],
				[
					'field' => 'Systolic',
					'label' => 'TEKANAN DARAH SYSTOLIC',
					'rules' => "required"
				],
				[
					'field' => 'Diastolic',
					'label' => 'TEKANAN DARAH DIASTOLIC',
					'rules' => "required"
				],
				[
					'field' => 'HeartRate',
					'label' => 'DETAK JANTUNG PER MENIT',
					'rules' => "required"
				],
				[
					'field' => 'RespiratoryRate',
					'label' => 'FREKUENSI PERNAPASAN',
					'rules' => "required"
				],
				[
					'field' => 'lingkarPerut',
					'label' => 'LINGKAR PERUT',
					'rules' => "required"
				],
			],
			'update' => [
				[
					'field' => 'NoReg',
					'label' => lang('registrations:registration_number_label'),
					'rules' => "required"
				],
				[
					'field' => 'NRM',
					'label' => lang('registrations:mr_number_label'),
					'rules' => "required"
				],
			]
		];
	}

	public function create($data)
	{
		return $this->db->insert($this->table, $data);
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

	public function dropdown_data($where = NULL)
	{
		if ($items = $this->get_all(NULL, 0, $where)) {
			foreach ($items as $item) {
				$option_data[$item->{$this->index_key}] = $item->KelompokSection;
			}
		}
		return $option_data;
	}

	public function dropdown_html($where = NULL)
	{
		$option_html = '';
		if ($items = $this->get_all(NULL, 0, $where)) {
			foreach ($items as $item) {
				$option_html .= "<option value=\"{$item->{$this->index_key}}\">{$item->{$this->index_key}}</option>";
			}
		} else {
			$option_html .= "<option value=\"\">" . lang('global:select-empty') . "</option>";
		}

		return $option_html;
	}
}
