<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product_model extends MY_Model {

    public $table = 'm_products';
    public $primary_key = 'id';
    public $fillable = ['name', 'description', 'price', 'stock', 'image', 'category_id'];

    public function __construct()
    {
        parent::__construct();
    }

    public function get_latest_products($limit = 8)
    {
        return $this->limit($limit)->order_by('created_at', 'DESC')->get_all();
    }
}
