<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ordering_m extends CI_Model
{
	/** @var null
     * Sets table name
     */
    public $table = 'common_orderings';

    /**
     * @var null
     * Sets PRIMARY KEY
     */
    public $primary_key = 'id';
	
	private $_date_time = null;
	
	public function __construct()
	{
		parent::__construct();
		
		$this->_date_time = new DateTime();
	}
	
	public static function gen_chart_number()
	{
		$date = $this->_date_time->format( "Y-m-d" );
		$date_y = $this->_date_time->format( "Y" );
		$date_m = $this->_date_time->format( "m" );
		$date_d = $this->_date_time->format( "d" );
		
		$count = $CI->registration_m->count( array("DATE_FORMAT(registration_date, '%Y') =" => $date_y) );
		$count++;
		
		$number = (string) (sprintf(self::_get_format_registration_number(), $date_y, $date_m, $date_d, $count));		
		return $number;
	}
	
	public static function gen_registration_number()
	{
		$date = $this->_date_time->format( "Y-m-d" );
		$date_y = $this->_date_time->format( "Y" );
		$date_m = $this->_date_time->format( "m" );
		$date_d = $this->_date_time->format( "d" );
		
		$CI->load->model( "registrations/registration_m" );
		$count = $CI->registration_m->count( array("DATE_FORMAT(registration_date, '%Y') =" => $date_y) );
		$count++;
		
		$number = (string) (sprintf(self::_get_format_registration_number(), $date_y, $date_m, $date_d, $count));		
		return $number;
	}
	
	public static function gen_reservation_number()
	{
		$date = $this->_date_time->format( "Y-m-d" );
		$date_y = $this->_date_time->format( "Y" );
		$date_m = $this->_date_time->format( "m" );
		$date_d = $this->_date_time->format( "d" );
		
		if( $_ordering = $this->_get_ordering_by( "RESERVATION" ) )
		{
			$number = (string) (sprintf( $_ordering->format, $date_y, $date_m, $date_d, ($_ordering->ordering + 1) ));		
			return $number;
		}
		
		return FALSE;
	}
	
	public static function gen_mr_number( $length=8, $split_length=2, $split_separator="." )
	{
		return self::_gen_mr_number( $length, $split_length, $split_separator );
	}
	
	private static function _gen_mr_number( $length=8, $split_length=2, $split_separator="." )
	{
		$this->db
			->select( "resource, `format`, reference, ordering, latest_formated, latest_referenced, latest_ordered, state, updated_at" )
			->where( "resource", "PATIENT" )
			->get()
			;
		
		
		
		self::ci()->load->model( "common/patient_m" );		
		$order_number = (int) self::ci()->patient_m->count();
		$order_number++;
		
		$string_order_number = sprintf( "%0{$length}d", $order_number );
		$array_order_number = @str_split( $string_order_number, $split_length );
		//print_r( $array_order_number );exit();
		$mr_number = @implode( $split_separator, $array_order_number );
		//exit($mr_number);
		
		return (string) $mr_number;
	}
	
	private function _get_ordering_by( $resource )
	{
		$result = $this->db
			->select( "*" )
			->where(array(
					"deleted_at" => NULL,
					"state" => 1,
					"resource" => $resource
				))
			->get( $this->table )
			;
			
		if( $result )
		{
			$row = $result->row();
			
			switch( $row->reference )
			{
				case "MINUTE":
					if( $row->latest_referenced != $this->_date_time->format( "i" ) )
					{
						$row->latest_referenced = $this->_date_time->format( "i" );
						$row->latest_ordered = $row->ordering;
						$row->ordering = 0;
						
						return $this->_replace_ordering_by( $resource, $row );					
					}					
					break;
					
				case "HOUR":
					if( $row->latest_referenced != $this->_date_time->format( "H" ) )
					{
						$row->latest_referenced = $this->_date_time->format( "H" );
						$row->latest_ordered = $row->ordering;
						$row->ordering = 0;
						
						return $this->_replace_ordering_by( $resource, $row );
					}
					break;
				
				case "DAY":
					if( $row->latest_referenced != $this->_date_time->format( "d" ) )
					{
						$row->latest_referenced = $this->_date_time->format( "d" );
						$row->latest_ordered = $row->ordering;
						$row->ordering = 0;
						
						return $this->_replace_ordering_by( $resource, $row );
					}
					break;
					
				case "WEEK":
					if( $row->latest_referenced != $this->_date_time->format( "N" ) )
					{
						$row->latest_ordered = $this->_date_time->format( "N" );
						$row->latest_ordered = $row->ordering;
						$row->ordering = 0;
						
						return $this->_replace_ordering_by( $resource, $row );
					}
					break;
				
				case "MONTH":
					if( $row->latest_referenced != $this->_date_time->format( "m" ) )
					{
						$row->latest_referenced = $this->_date_time->format( "m" );
						$row->latest_ordered = $row->ordering;
						$row->ordering = 0;
						
						return $this->_replace_ordering_by( $resource, $row );
					}
					break;
					
				case "YEAR":
					if( $row->latest_referenced != $this->_date_time->format( "Y" ) )
					{
						$row->latest_referenced = $this->_date_time->format( "Y" );
						$row->latest_ordered = $row->ordering;
						$row->ordering = 0;
						
						return $this->_replace_ordering_by( $resource, $row );
					}
					break;
			}
			
			return $result->row();
		}
		
		return FALSE;
	}
	
	private function _replace_ordering_by( $resource, $data )
	{
		$this->db
			->set( $data )
			->where( 'resource', $resource )
			->update( $this->table )
			;
			
		return $data;
	}
	
	public function setup()
	{
		
	}
}