<?php
class ProductSeeder extends Seeder 
{
	
	public function run()
	{
		$this->db->truncate('product');

		$data = [
				'category_id' => 1,
				'name' => 'CodeIgniter Book',
				'detail' => 'Very good CodeIgniter book.',
				'price' => 3800,
			];
		$this->db->insert('product', $data);

		$data = [
				'category_id' => 2,
				'name' => 'CodeIgniter CD',
				'detail' => 'Great CodeIgniter CD.',
				'price' => 4800,
			];
		$this->db->insert('product', $data);

		$data = [
				'category_id' => 3,
				'name' => 'CodeIgniter DVD',
				'detail' => 'Awesome CodeIgniter DVD.',
				'price' => 5800,
			];
		$this->db->insert('product', $data);
	}
}

