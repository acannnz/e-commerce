<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Public_Model extends CI_Model
{
	
	public function __construct()
	{
		$this->date_time = new DateTime();
		
		$this->cache_driver = 'file';
		$this->cache_prefix = sprintf('mm_%s', $this->table);
		
		$this->return_as = 'object';
		$this->soft_deletes = TRUE;
		
		parent::__construct();
		
		
	}
	
	public function load_database( $active_group )
	{
		return $this->load->database( $active_group, TRUE);
	}
}