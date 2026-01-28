<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Catalog extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Product_model');
    }

    public function index()
    {
        $data['title'] = 'Product Catalog - Arcnad Store';
        $data['products'] = $this->Product_model->get_latest_products();
        
        $data['content'] = $this->load->view('index', $data, TRUE);
        $this->load->view('layouts/main', $data);
    }

    public function detail($id)
    {
        $product = $this->Product_model->get($id);
        if (!$product) {
            show_404();
        }

        $data['title'] = $product->name . ' - Arcnad Store';
        $data['product'] = $product;
        
        $data['content'] = $this->load->view('detail', $data, TRUE);
        $this->load->view('layouts/main', $data);
    }
}
