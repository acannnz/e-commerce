<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

final class system_helper
{
	public static function check_internet_connection( $ping_host = "www.google.com" )
	{
		return (bool) @fsockopen($ping_host, 80, $err_number, $err_message, 7);
	}
}

