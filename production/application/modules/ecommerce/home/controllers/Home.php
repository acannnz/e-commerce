<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('catalog/Product_model');
    }

    public function index()
    {
        $data['title'] = 'Arcand Store - Experience Modern Shopping';
        
        // Fetch data for the landing page
        $data['featured_products'] = $this->Product_model->get_latest_products(8);
        
        // Load the view with the landing layout
        $data['content'] = $this->load->view('v_home_landing', $data, TRUE);
        $this->load->view('layouts/landing', $data);
    }

    public function about()
    {
        $data['title'] = 'About Us - Arcand Store';
        $data['content'] = $this->load->view('v_about', $data, TRUE);
        $this->load->view('layouts/landing', $data);
    }
}
