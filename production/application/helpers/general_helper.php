<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * check the array key and return the value 
 * 
 * @param array $array
 * @return extract array value safely
 */
if (!function_exists('get_array_value')) {

    function get_array_value(array $array, $key) {
        if (array_key_exists($key, $array)) {
            return $array[$key];
        }
    }

}

/**
 * prepare a anchor tag for any js request
 * 
 * @param string $title
 * @param array $attributes
 * @return html link of anchor tag
 */
if (!function_exists('js_anchor')) {

    function js_anchor($title = '', $attributes = '') {
        $title = (string) $title;
        $html_attributes = "";

        if (is_array($attributes)) {
            foreach ($attributes as $key => $value) {
                $html_attributes .= ' ' . $key . '="' . $value . '"';
            }
        }

        return '<a href="#"' . $html_attributes . '>' . $title . '</a>';
    }

}


/**
 * prepare a anchor tag for modal 
 * 
 * @param string $url
 * @param string $title
 * @param array $attributes
 * @return html link of anchor tag
 */
if (!function_exists('modal_anchor')) {

    function modal_anchor($url, $title = '', $attributes = '') {
        $attributes["data-act"] = "ajax-modal";
        if (get_array_value($attributes, "data-modal-title")) {
            $attributes["data-title"] = get_array_value($attributes, "data-modal-title");
        } else {
            $attributes["data-title"] = get_array_value($attributes, "title");
        }
        $attributes["data-action-url"] = $url;

        return js_anchor($title, $attributes);
    }

}

/**
 * prepare a anchor tag for ajax request
 * 
 * @param string $url
 * @param string $title
 * @param array $attributes
 * @return html link of anchor tag
 */
if (!function_exists('ajax_anchor')) {

    function ajax_anchor($url, $title = '', $attributes = '') {
        $attributes["data-act"] = "ajax-request";
        $attributes["data-action-url"] = $url;
        return js_anchor($title, $attributes);
    }

}

/**
 * use this to print link location
 *
 * @param string $uri
 * @return print url
 */
if (!function_exists('echo_uri')) {

    function echo_uri($uri = "") {
        echo get_uri($uri);
    }

}

/**
 * prepare uri
 * 
 * @param string $uri
 * @return full url 
 */
if (!function_exists('get_uri')) {

    function get_uri($uri = "") {
        $ci = get_instance();
        $index_page = $ci->config->item('index_page');
        return base_url($index_page . '/' . $uri);
    }

}

/**
 * validate post data using the codeigniter's form validation method
 * 
 * @param string $address
 * @return throw error if foind any inconsistancy
 */
if (!function_exists('validate_submitted_data')) {

    function validate_submitted_data($fields = array()) {
        $ci = get_instance();
        foreach ($fields as $field_name => $requirement) {
            $ci->form_validation->set_rules($field_name, $field_name, $requirement);
        }

        if ($ci->form_validation->run() == FALSE) {
            if (ENVIRONMENT === 'production') {
                $message = lang('something_went_wrong');
            } else {
                $message = validation_errors();
            }
            echo json_encode(array("success" => false, 'message' => $message));
            exit();
        }
    }

}

if (! function_exists('message_box')) 
{	
	function message_box($msg, $status = 'success')
	{
		$response = '';
		$class    = 'danger';
		if($status == 'success')
		{
			$class = 'success';
		}
		if(!empty($msg))
		{
			$response = '<div class="alert alert-' . $class . ' no-margin" style="margin-bottom:15px!important;">' . $msg . '</div>';
		}
		return $response;
	}
}

/**
 * json response
 * 
 * @param array/object $response
 * @param number $http_code
 * @print json 
 */
if (!function_exists('response_json')) 
{
	function response_json($response, $http_code = 200) 
	{
        $CI = get_instance();
        
		$CI->output
			->set_status_header($http_code)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ))
			->_display();
		
		exit(0);
    }
}

if (!function_exists('output_json')) 
{
	function output_json($response, $http_code = 200) 
	{
        response_json($response, $http_code);
    }
}

/**
 * insert user activity
 * 
 * @param string $description
 * @param string/number $evidence_number
 * @print string/number $affect
 */
 
if (!function_exists('insert_user_activity'))
{
	function insert_user_activity( $description = NULL, $evidence_number = NULL, $affect = NULL)
	{
		$_ci = get_instance();
		
		$_ci->load->library('simple_login');
		
		$user = $_ci->simple_login->get_user();	
		$date = date('Y-m-d');
		$time = date('Y-m-d H:i:s');
		
		if ( !empty($description) && !empty($evidence_number))
		{
			$_ci->db->query("EXEC InsertUserActivities '{$date}', '{$time}', {$user->User_ID}, '{$evidence_number}', '{$description}', '{$affect}' ");
			return TRUE;	
		} 
		return FALSE;
	}
}

/**
 * Capital all first letter
 * 
 * @param string $string
 */

if (!function_exists('ucfirst_case'))
{
	function ucfirst_case($string) { 
		if ($string) { // input
			$string = preg_replace('/'.chr(32).chr(32).'+/', chr(32), $string); // recursively replaces all double spaces with a space
			if (($x = substr($string, 0, 10)) && ($x == strtoupper($x))) $string = strtolower($string); // sample of first 10 chars is ALLCAPS so convert $string to lowercase; if always done then any proper capitals would be lost
	
			$na = array(' ','. ', '! ', '? '); // punctuation needles
			foreach ($na as $n) { // each punctuation needle
				if (strpos($string, $n) !== false) { // punctuation needle found
					$sa = explode($n, $string); // split
					foreach ($sa as $s) $ca[] = ucfirst($s); // capitalize
					$string = implode($n, $ca); // replace $string with rebuilt version
					unset($ca); //  clear for next loop
				}
			}
			
			return ucfirst(trim($string)); // capitalize first letter in case no punctuation needles found
		}
	} 
}


function make_flashdata( $data )
{
	$CI =& get_instance();
	foreach ($data as $key => $value) 
	{
		$CI->session->set_flashdata($key, $value);
	}
}


/**
 * generate unique code or number
 * 
 * @param int $lenght, lenght of unique code or number
 */
 
if (!function_exists('gen_unique_code'))
{
	function gen_unique_code($length = 5) {
		$characters = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[mt_rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}
}

if (!function_exists('gen_unique_number'))
{
	function gen_unique_number($length = 5) {
		$characters = '1234567890';
		$charactersLength = strlen($characters);
		$randomNumber = '';
		for ($i = 0; $i < $length; $i++) {
			$randomNumber .= $characters[mt_rand(0, $charactersLength - 1)];
		}
		return $randomNumber;
	}
}

function numb_format( $numbers, $decimals=2, $dec_point='.', $thousands_sep=',' )
{
	return @number_format( $numbers, $decimals, $dec_point, $thousands_sep );
}


function currency_ceil( $operand, $_increment = 500 )
{
	return ceil($operand / $_increment)	 * $_increment;
}

function calculate_age( $birth_date )
{
	return 
		DateTime::createFromFormat('Y-m-d', $birth_date)
		 ->diff(new DateTime('now'))
		 ->y;
}

if (!function_exists('option_doctor'))
{
	function option_doctor( array $where = NULL)
	{
		$_ci = get_instance();
		if(!empty($where))
		{
			$_ci->db->where($where);
		}
		$result = $_ci->db->select('Kode_Supplier AS DokterID, Nama_Supplier AS NamaDokter')
						->where_in('KodeKategoriVendor', ['V-002', 'V-009'])
						->get('mSupplier')
						->result();
		$collection = [];
		foreach( $result as $v)
		{
			$collection[ $v->DokterID ] = $v->NamaDokter;
		}
		return $collection;
	}
}

if (!function_exists('option_nurse'))
{
	function option_nurse( array $where = NULL)
	{
		$_ci = get_instance();
		if(!empty($where))
		{
			$_ci->db->where($where);
		}
		$result = $_ci->db->select('Kode_Supplier AS DokterID, Nama_Supplier AS NamaDokter')
						->where_in('KodeKategoriVendor', ['V-003', 'V-004'])
						->get('mSupplier')
						->result();
		$collection = [];
		foreach( $result as $v)
		{
			$collection[ $v->DokterID ] = $v->NamaDokter;
		}
		return $collection;
	}
}

if (!function_exists('option_analys'))
{
	function option_analys( array $where = NULL)
	{
		$_ci = get_instance();
		if(!empty($where))
		{
			$_ci->db->where($where);
		}
		$result = $_ci->db->select('Kode_Supplier AS DokterID, Nama_Supplier AS NamaDokter')
						->where_in('KodeKategoriVendor', ['V-008'])
						->get('mSupplier')
						->result();
		$collection = [];
		foreach( $result as $v)
		{
			$collection[ $v->DokterID ] = $v->NamaDokter;
		}
		return $collection;
	}
}

if (!function_exists('option_section'))
{
	function option_section( $where = NULL)
	{
		$_ci = get_instance();
		if(!empty($where))
		{
			$_ci->db->where($where);
		}
		$result = $_ci->db->select('SectionID, SectionName')
						->get('SIMmSection')
						->result();
		$collection = [];
		foreach( $result as $v)
		{
			$collection[ $v->SectionID ] = $v->SectionName;
		}
		return $collection;
	}
}

