<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Inventory
{
	private $ci;
	
	/**
    * Copies an instance of CI
    */
	public function __construct()
	{
		$this->ci =& get_instance();
	}
}