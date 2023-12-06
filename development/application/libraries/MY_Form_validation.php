<?php if (!defined('BASEPATH')) exit('No direct script access allowed.');
/**
 * MY_Form_validation
 * 
 * Extending the Form Validation class to add extra rules and model validation
 *
 * @package 	PyroCMS\Core\Libraries
 * @author      PyroCMS Dev Team
 * @copyright   Copyright (c) 2012, PyroCMS LLC
 */
class MY_Form_validation extends CI_Form_validation
{
	public function run($module = '', $group = '')
    {
       (is_object($module)) AND $this->CI = &$module;
        
		return parent::run($group);
    }
	
	public function get_all_error_string( $delimiter='<br>' )
	{
		$errors = $this->error_array();
		if( empty($errors) )
		{
			return '';
		}
		
		$string = array();
		foreach($errors as $field => $error )
		{
			array_push( $string, $error );	
		}
		return (string) @implode( $delimiter, $string );
	}
	
	public function is_edit_unique($str, $field)
    {
        sscanf($field, '%[^.].%[^.].%[^.].%[^.]', $table, $field, $id_field, $id_value);

        return isset($this->CI->db)
            ? ($this->CI->db->limit(1)->get_where($table, array($field => $str, "{$id_field} !=" => $id_value))->num_rows() === 0)
            : FALSE;
    }
}

/* End of file MY_Form_validation.php */
