<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Retur_m extends Public_Model
{
	public $table = 'ReturFarmasi';
	public $primary_key = 'NoRetur';
	public $rules;
	
	public function __construct()
	{
		$this->rules = [
			'insert' => [
				[
					'field' => 'NoRetur',
                	'label' => lang('retur:retur_number_label'),
               		'rules' => 'required'
				],
				[
					'field' => 'NoBukti',
                	'label' => lang('retur:evidence_number'),
               		'rules' => 'required'
				],
			]
		];	
		
		parent::__construct();
	}	
	
	public function get_retur( $id )
	{
		return
			$this->db->select('a.*, b.SectionName, c.Keterangan AS NamaPasien, d.NRM')
				->from("{$this->table} a")
				->join("SIMmSection b", "a.SectionID = b.SectionID", 'INNER')
				->join("BillFarmasi c", "a.NoBukti = c.NoBukti", 'INNER')
				->join("SIMtrRegistrasi d", "a.NoReg = d.NoReg", 'LEFT OUTER')
				->where('a.NoRetur', $id)
				->get()
				->row();
	}
	
	public function get_retur_detail( $id )
	{
		$this->load->model( "batch_card_model" );
		$query = $this->db->select('a.*, b.Kode_Barang, b.Nama_Barang')
				->from("ReturFarmasiDetail a")
				->join("mBarang b", "a.Barang_ID = b.Barang_ID", 'INNER')
				->where('a.NoRetur', $id)
				->get();
				
		$collection = [];
		if( $query->num_rows() > 0 ): 
			foreach($query->result() as $row):
				$batchs = $this->batch_card_model->get_all(NULL, 0, ['No_Bukti' => $id, 'Barang_ID' => $row->Barang_ID]);
				$row->batchs = [];
				foreach($batchs as $batch):
					$row->batchs[] = [
						'No_Batch' => $batch->No_Batch,
						'Qty_Batch' => $batch->Qty_Keluar,
					];
				endforeach;
				$collection[] = $row;
			endforeach;
		endif;
		
		return $collection;
	}
	
	public function get_pharmacy_detail( $NoBukti = NULL )
	{
		$this->load->model( "batch_card_model" );

		if ( !$NoBukti ) return [];

		$query = $this->db
					->select("a.*, b.Kode_Barang, b.Nama_Barang, c.Dosis as Dosis_view")
					->from("BILLFarmasiDetail a")
					->join("mBarang b", "a.Barang_ID = b.Barang_ID", "LEFT OUTER")
					->join("SIMmDosisObat c", "a.Dosis = c.Dosis", "LEFT OUTER")
					->where( "a.NoBukti", @$NoBukti )
					->get()
					;

		$collection = [];
		if( $query->num_rows() > 0 ): 
			foreach($query->result() as $row):
				$batchs = $this->batch_card_model->get_all(NULL, 0, ['No_Bukti' => $NoBukti, 'Barang_ID' => $row->Barang_ID]);
				$row->batchs = [];
				foreach($batchs as $batch):
					$row->batchs[] = [
						'No_Batch' => $batch->No_Batch,
						'Qty_Batch' => $batch->Qty_Keluar,
					];
				endforeach;
				$collection[] = $row;
			endforeach;
		endif;
		
		return $collection;
	}
}