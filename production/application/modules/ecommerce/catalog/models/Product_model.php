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

    public function get_filtered_products($filters = [], $limit = 12, $offset = 0)
    {
        $this->db->select('*');
        $this->db->from($this->table);

        // Filter by Category
        if (!empty($filters['category_id'])) {
            $this->db->where('category_id', $filters['category_id']);
        }

        // Filter by Price Range
        if (!empty($filters['min_price'])) {
            $this->db->where('price >=', $filters['min_price']);
        }
        if (!empty($filters['max_price'])) {
            $this->db->where('price <=', $filters['max_price']);
        }

        // Search Query
        if (!empty($filters['search'])) {
            $this->db->like('name', $filters['search']);
        }

        // Sorting
        $sort = isset($filters['sort']) ? $filters['sort'] : 'latest';
        switch ($sort) {
            case 'price_low':
                $this->db->order_by('price', 'ASC');
                break;
            case 'price_high':
                $this->db->order_by('price', 'DESC');
                break;
            case 'oldest':
                $this->db->order_by('created_at', 'ASC');
                break;
            case 'latest':
            default:
                $this->db->order_by('created_at', 'DESC');
                break;
        }

        // Clone query for total count before limit/offset
        $temp_db = clone $this->db;
        $total_rows = $temp_db->count_all_results('', FALSE);

        // Pagination
        $this->db->limit($limit, $offset);
        $query = $this->db->get();

        return [
            'data' => $query->result(),
            'total_rows' => $total_rows
        ];
    }

    public function get_categories()
    {
        // For now returning static categories since m_categories might not exist yet
        // or we can use DISTINCT category_id if they exist in products
        return [
            ['id' => 1, 'name' => 'Fashion'],
            ['id' => 2, 'name' => 'Electronics'],
            ['id' => 3, 'name' => 'Home & Living'],
            ['id' => 4, 'name' => 'Accessories']
        ];
    }

    public function get_latest_products($limit = 8)
    {
        $result = $this->get_filtered_products([], $limit, 0);
        return $result['data'];
    }
}
