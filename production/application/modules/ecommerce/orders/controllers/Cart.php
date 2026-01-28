<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cart extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('ecommerce/catalog/Product_model', 'product_model');
    }

    public function index()
    {
        $data['title'] = 'Shopping Cart - Arcnad Store';
        $data['cart_items'] = $this->session->userdata('cart') ?: [];
        
        $data['content'] = $this->load->view('cart/index', $data, TRUE);
        $this->load->view('layouts/main', $data);
    }

    public function add($product_id)
    {
        $product = $this->product_model->get($product_id);
        if (!$product) {
            $this->session->set_flashdata('error', 'Product not found.');
            redirect('ecommerce/catalog');
        }

        $cart = $this->session->userdata('cart') ?: [];
        
        if (isset($cart[$product_id])) {
            $cart[$product_id]['qty']++;
        } else {
            $cart[$product_id] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'image' => $product->image,
                'qty' => 1
            ];
        }

        $this->session->set_userdata('cart', $cart);
        $this->session->set_flashdata('success', $product->name . ' added to cart.');
        redirect('ecommerce/orders/cart');
    }

    public function update()
    {
        $qtys = $this->input->post('qty');
        $cart = $this->session->userdata('cart') ?: [];

        if ($qtys) {
            foreach ($qtys as $id => $qty) {
                if (isset($cart[$id])) {
                    if ($qty <= 0) {
                        unset($cart[$id]);
                    } else {
                        $cart[$id]['qty'] = $qty;
                    }
                }
            }
        }

        $this->session->set_userdata('cart', $cart);
        redirect('ecommerce/orders/cart');
    }

    public function remove($product_id)
    {
        $cart = $this->session->userdata('cart') ?: [];
        if (isset($cart[$product_id])) {
            unset($cart[$product_id]);
        }
        $this->session->set_userdata('cart', $cart);
        redirect('ecommerce/orders/cart');
    }
}
