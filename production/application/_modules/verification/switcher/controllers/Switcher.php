<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Switcher extends MX_Controller
{
    public function __construct() 
	{
        parent::__construct();     
    }
 
    public function index($language = '', $from_language = '') 
	{
        
        if( is_null($language) || empty($language) )
		{
			if (!$language = $this->input->get('language', '', TRUE))
			{
				$language = $this->input->get('lang', $this->config->item('default_language'), TRUE);
			}
		}
		$this->session->set_userdata('current_lang', $language);

		if (isset($_SERVER['HTTP_REFERER'])) 
		{
			redirect($_SERVER['HTTP_REFERER']);
		} else
		{
			redirect("");
		}
    }
	
	public function widget($template = NULL)
	{
		$data = [
				'current_language' => $this->config->item('language'),
				'default_language' => $this->config->item('default_language'),
				'populate_language' => $this->config->item('languages'),
			];
		
		if( is_null($template) || empty($template) ){ $template = "switcher/widget/switcher"; }
		echo $this->template->load_view($template, $data);
	}
}