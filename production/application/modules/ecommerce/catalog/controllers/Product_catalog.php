<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product_catalog extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Product_model');
    }

    public function index()
    {
        $data['title'] = 'Semua Produk - Arcnad Store';
        
        // Filter Inputs
        $filters = [
            'category_id' => $this->input->get('category'),
            'min_price'   => $this->input->get('min_price'),
            'max_price'   => $this->input->get('max_price'),
            'sort'        => $this->input->get('sort'),
            'search'      => $this->input->get('search')
        ];

        // Pagination setup
        $limit = 12;
        $page = $this->input->get('page') ? (int)$this->input->get('page') : 1;
        $offset = ($page - 1) * $limit;

        // Fetch Data
        $result = $this->Product_model->get_filtered_products($filters, $limit, $offset);
        $data['products'] = $result['data'];
        $data['total_rows'] = $result['total_rows'];
        $data['categories'] = $this->Product_model->get_categories();
        
        // Pass current filters back to view
        $data['current_filters'] = $filters;
        $data['current_page'] = $page;
        $data['total_pages'] = ceil($result['total_rows'] / $limit);

        $data['content'] = $this->load->view('v_product_list', $data, TRUE);
        $this->load->view('layouts/landing', $data);
    }

    public function detail($id)
    {
        $product = $this->Product_model->get($id);
        if (!$product) {
            show_404();
        }

        $data['title'] = $product->name . ' - Arcnad Store';
        $data['product'] = $product;
        
        $data['content'] = $this->load->view('v_product_detail', $data, TRUE);
        $this->load->view('layouts/landing', $data);
    }
}
