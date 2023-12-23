<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

final class registration_helper
{
	public static function get_upcoming_tasks( $today="NOW", $length=10 )
	{
		$DT = new DateTime( $today );
		
		$DB = self::ci()->db;
		
		$DB
			->select( 'id, registration_number, reservation_number, mr_number, personal_name, personal_gender, personal_age, schedule_date, schedule_time, schedule_queue, state, created_at' )
			->from( 'transaction_registrations' )
			->where(array(
					'deleted_at' => NULL,
					'state <=' => 2,
					'DATE_FORMAT(schedule_date, \'%Y-%m-%d\') =' => $DT->format( 'Y-m-d' ),
				))
			->order_by('state', 'ASC')
			->order_by('schedule_queue', 'ASC')
			;
		
		if( -1 != $length )
		{
			$DB->limit( $length );
		}
		
		$result = $DB
			->get()
			->result()
			//->result_array()
			;
			
		return (array) ($result);
	}
	
	public static function find_upcoming_tasks( $today="NOW" )
	{
		$DT = new DateTime( $today );
		
		$found = self::ci()->db
			->where(array(
					'deleted_at' => NULL,
					'state <=' => 2,
					'DATE_FORMAT(schedule_date, \'%Y-%m-%d\') =' => $DT->format( 'Y-m-d' ),
				))
			->count_all_results( 'transaction_registrations' )
			;
			
		return (int) ($found);
	}
	
	public static function fix_registrations_yesterday( $today="NOW" )
	{
		$NOW = new DateTime( $today );
		$NOW->sub(new DateInterval('P2D'));
		
		self::ci()->load->model( "registrations/registration_m" );
		$total_progress = self::ci()->registration_m->count(array(
				'progress >' => 0,
				'state' => 2,
				'FROM_UNIXTIME(created_at, \'%Y-%m-%d\') <=' => $NOW->format( 'Y-m-d' ),
			));
		
		if( 0 < $total_progress )
		{
			$items = self::ci()->registration_m->get_all(array(
					'progress >' => 0,
					'state' => 2,
					'FROM_UNIXTIME(created_at, \'%Y-%m-%d\') <=' => $NOW->format( 'Y-m-d' ),
				));
				
			foreach( $items as $item )
			{
				self::update_registration_state( $item->registration_number, 3 );
			}
		}
	}
	
	public static function update_registration_progress( $registration_number, $progress )
	{
		if( self::find_registration($registration_number) )
		{
			$progress = (float) $progress;
			
			self::ci()->load->model( "registrations/registration_m" );
			$updated = self::ci()->registration_m->update(
					array( "progress" => $progress 
						),
					array( "progress <=" => $progress,
							"registration_number" => $registration_number
						)
					);
					
			if( $updated )
			{
				if( ! empty($item->reservation_number) )
				{
					$item = self::get_registration( $registration_number );
					
					self::ci()->load->helper( "reservations/reservation" );
					reservation_helper::update_reservation_progress( $item->reservation_number, $progress );
				}
				
				return TRUE;
			}
		}
		
		return FALSE;
	}
	
	public static function update_registration_state( $registration_number, $state )
	{
		if( self::find_registration($registration_number) )
		{
			$state = (int) $state;
			
			self::ci()->load->model( "registrations/registration_m" );
			$updated = self::ci()->registration_m->update(
					array( "state" => $state 
						),
					array( "state <=" => $state,
							"registration_number" => $registration_number
						)
					);
					
			if( $updated )
			{
				if( ! empty($item->reservation_number) )
				{
					$item = self::get_registration( $registration_number );
					
					self::ci()->load->helper( "reservations/reservation" );
					switch( $state )
					{
						case 3:
							reservation_helper::update_reservation_state( $item->registration_number, 4 ); // completed				
							break;
						
						case 2:
							reservation_helper::update_reservation_state( $item->registration_number, 3 ); // progress / incomplete				
							break;
							
						case 1:
							reservation_helper::update_reservation_state( $item->registration_number, 2 ); // registered				
							break;
					}
				}
				
				return TRUE;
			}
		}
		
		return FALSE;
	}
	
	public static function get_registration_by_chart( $chart_number )
	{
		$select_table = <<<EOSQL
				a.id AS registration_id,
				a.registration_number,
				a.reservation_id,
				a.reservation_number,
				a.patient_id,
				a.mr_number,
				b.id AS patient_type_id,
				b.code AS patient_type_code,
				b.type_name AS patient_type,
				a.personal_name,
				a.personal_gender,
				a.personal_birth_date,
				a.personal_age,
				a.personal_nationality,
				a.personal_religion,
				a.personal_id_type,
				a.personal_id_number,
				a.personal_profession,
				a.personal_address,
				a.country_id,
				c.zone_name AS county_name,
				a.province_id,
				d.zone_name AS province_name,
				a.county_id,
				e.zone_name AS county_name,
				a.district_id,
				f.zone_name AS district_name,
				a.area_id,
				g.zone_name AS area_name,
				g.zone_postcode,
				a.phone_number,
				a.mobile_number,
				a.email_address,
				a.schedule_date,
				a.schedule_time,
				a.schedule_queue,
				a.state,
				a.created_at,
				a.updated_at
EOSQL;
			
		self::ci()->db
			->select( $select_table )
			->from( "transaction_registrations a" )
			->join( "patient_types b", "a.patient_type_id = b.id", "LEFT OUTER" )
			->join( "common_zones c", "a.country_id = c.id", "LEFT OUTER" )
			->join( "common_zones d", "a.province_id = d.id", "LEFT OUTER" )
			->join( "common_zones e", "a.county_id = e.id", "LEFT OUTER" )
			->join( "common_zones f", "a.district_id = f.id", "LEFT OUTER" )
			->join( "common_zones g", "a.area_id = g.id", "LEFT OUTER" )
			->join( "transaction_charts h", "a.registration_number = h.registration_number", "LEFT OUTER" )
			;
			
		self::ci()->db
			->where('a.deleted_at', NULL)
			->where('h.deleted_at', NULL)
			->where('h.chart_number', $chart_number)
			;
		
		if( $result = self::ci()->db->get() )
		{
			return $result->row_array();
			//return $result->row();
		}
		
		return FALSE;
	}
	
	public static function get_personal_registration( $registration_number )
	{
		$select_table = <<<EOSQL
				a.id AS registration_id,
				a.registration_number,
				a.reservation_id,
				a.reservation_number,
				a.patient_id,
				a.mr_number,
				b.id AS patient_type_id,
				b.code AS patient_type_code,
				b.type_name AS patient_type,
				a.personal_name,
				a.personal_gender,
				a.personal_birth_date,
				a.personal_age,
				a.personal_nationality,
				a.personal_religion,
				a.personal_id_type,
				a.personal_id_number,
				a.personal_profession,
				a.personal_address,
				a.country_id,
				c.zone_name AS county_name,
				a.province_id,
				d.zone_name AS province_name,
				a.county_id,
				e.zone_name AS county_name,
				a.district_id,
				f.zone_name AS district_name,
				a.area_id,
				g.zone_name AS area_name,
				g.zone_postcode,
				a.phone_number,
				a.mobile_number,
				a.email_address,
				a.personal_profession,
				a.schedule_date,
				a.schedule_time,
				a.schedule_queue,
				a.state,
				a.created_at,
				a.updated_at
EOSQL;
			
		self::ci()->db
			->select( $select_table )
			->from( "transaction_registrations a" )
			->join( "patient_types b", "a.patient_type_id = b.id", "LEFT OUTER" )
			->join( "common_zones c", "a.country_id = c.id", "LEFT OUTER" )
			->join( "common_zones d", "a.province_id = d.id", "LEFT OUTER" )
			->join( "common_zones e", "a.county_id = e.id", "LEFT OUTER" )
			->join( "common_zones f", "a.district_id = f.id", "LEFT OUTER" )
			->join( "common_zones g", "a.area_id = g.id", "LEFT OUTER" )
			;
			
		self::ci()->db
			->where('a.deleted_at', NULL)
			->where('a.registration_number', $registration_number)
			;
		
		if( $result = self::ci()->db->get() )
		{
			return $result->row_array();
			//return $result->row();
		}
		
		return FALSE;
	}
	
	public static function get_registration( $registration_number )
	{
		self::ci()->load->model( "registrations/registration_m" );
		$registration = self::ci()->registration_m
			->as_object()
			->get(array(
					"state >=" => 1, 
					"registration_number" => $registration_number,
				))
			;
		
		if( $registration )
		{
			return $registration;
		}
		
		return FALSE;
	}
	
//	public static function find_inprogress_passed_registration( $today="NOW" )
//	{
//		$_today = new DateTime( $today );
//		
//		self::ci()->load->model( "registrations/registration_m" );
//		$found = self::ci()->registration_m
//			->count(array(
//					"state" => 3, 
//					'FROM_UNIXTIME(created_at, \'%Y-%m-%d\') <' => $_today->format( 'Y-m-d' ),
//				))
//			;
//		
//		return (bool) (0 < $found);
//	}
	
//	public static function find_incomplete_passed_registration( $today="NOW" )
//	{
//		$_today = new DateTime( $today );
//		
//		self::ci()->load->model( "registrations/registration_m" );
//		$found = self::ci()->registration_m
//			->count(array(
//					"state <" => 4, 
//					'FROM_UNIXTIME(created_at, \'%Y-%m-%d\') <' => $_today->format( 'Y-m-d' ),
//				))
//			;
//		
//		return (bool) (0 < $found);
//	}
	
//	public static function find_inprogress_registration_by_patient( $mr_number )
//	{
//		self::ci()->load->model( "registrations/registration_m" );
//		$found = self::ci()->registration_m
//			->count(array(
//					"state" => 3,
//					"mr_number" => $mr_number,
//				))
//			;
//		
//		return (bool) (0 < $found);
//	}
	
	public static function find_incomplete_registration_by_patient( $mr_number )
	{
		self::ci()->load->model( "registrations/registration_m" );
		$found = self::ci()->registration_m
			->count(array(
					"state" => 1,
					"mr_number" => $mr_number,
				))
			;
		
		return (bool) (0 < $found);
	}
	
	public static function find_incomplete_registration_by_reservation( $reservation_number )
	{
		self::ci()->load->model( "registrations/registration_m" );
		$found = self::ci()->registration_m
			->count(array(
					"state =" => 1,
					"reservation_number" => $reservation_number,
				))
			;
		
		return (bool) (0 < $found);
	}
	
	public static function find_registration( $registration_number )
	{
		self::ci()->load->model( "registrations/registration_m" );
		$found = self::ci()->registration_m
			->count(array(
					"state >=" => 1, 
					"registration_number" => $registration_number,
				))
			;
		
		return (bool) (0 < $found);
	}
	
	public static function get_latest_registrations( $length=5 )
	{
		if( self::get_total_registrations() )
		{
			$select_table = <<<EOSQL
				a.id,
				a.registration_number,
				a.reservation_number,
				a.mr_number,
				b.type_name AS patient_type,
				a.personal_name,
				a.personal_gender,
				a.personal_birth_date,
				a.personal_age,
				a.personal_nationality,
				a.personal_religion,
				a.personal_id_type,
				a.personal_id_number,
				a.personal_profession,
				a.personal_address,
				a.country_id,
				c.zone_name AS county_name,
				a.province_id,
				d.zone_name AS province_name,
				a.county_id,
				e.zone_name AS county_name,
				a.district_id,
				f.zone_name AS district_name,
				a.area_id,
				g.zone_name AS area_name,
				g.zone_postcode,
				a.phone_number,
				a.mobile_number,
				a.email_address,
				a.schedule_date,
				a.schedule_time,
				a.schedule_queue,
				a.state,
				a.created_at,
				a.updated_at
EOSQL;
			
			self::ci()->db
				->select( $select_table )
				->from( "transaction_registrations a" )
				->join( "patient_types b", "a.patient_type_id = b.id", "LEFT OUTER" )
				->join( "common_zones c", "a.country_id = c.id", "LEFT OUTER" )
				->join( "common_zones d", "a.province_id = d.id", "LEFT OUTER" )
				->join( "common_zones e", "a.county_id = e.id", "LEFT OUTER" )
				->join( "common_zones f", "a.district_id = f.id", "LEFT OUTER" )
				->join( "common_zones g", "a.area_id = g.id", "LEFT OUTER" )
				;
				
			self::ci()->db
				->where('a.deleted_at', NULL)
				->order_by( "a.id", "desc" )
				;
			
			if( $length != -1 )
			{
				self::ci()->db
					->limit( $length )
					;
			}
			
			// Select Data
			$result = self::ci()->db
				->get()
				->result()
				//->result_array()
				;
				
			return $result;
		}
		
		return FALSE;
	}
	
	public static function get_total_registrations_year( $today="NOW" )
	{
		$NOW = new DateTime( $today );
		
		self::ci()->load->model( "registrations/registration_m" );
		$registrations_today = self::ci()->registration_m->count(array(
				'state >=' => 1,
				//'DATE_FORMAT(schedule_date, \'%Y\') =' => $NOW->format( 'Y' ),
				'FROM_UNIXTIME(created_at, \'%Y\') =' => $NOW->format( 'Y' ),
			));
		return (int) $registrations_today;
	}
	
	public static function get_total_registrations_today( $today="NOW" )
	{
		$NOW = new DateTime( $today );
		
		self::ci()->load->model( "registrations/registration_m" );
		$registrations_today = self::ci()->registration_m->count(array(
				'state >=' => 1,
				//'DATE_FORMAT(schedule_date, \'%Y-%m-%d\') =' => $NOW->format( 'Y-m-d' ),
				'FROM_UNIXTIME(created_at, \'%Y-%m-%d\') =' => $NOW->format( 'Y-m-d' ),
			));
		return (int) $registrations_today;
	}
	
	public static function get_total_registrations()
	{
		self::ci()->load->model( "registrations/registration_m" );
		$total_registrations = self::ci()->registration_m->count(array(
				'state >=' => 1,
			));
		return (int) $total_registrations;
	}
	
	public static function gen_registration_number()
	{
		$CI = self::ci();
		$NOW = new DateTime();
		
		$date_start = $NOW->format( "Y-m-d 00:00:00.000" );
		$date_end = $NOW->format( "Y-m-t 00:00:00.000" );
		$date_y = $NOW->format( "y" );
		$date_m = $NOW->format( "m" );
		$date_d = $NOW->format( "d" );

		//$CI->load->model( "registrations/registration_m" );
		//$count = $CI->registration_m->count( array("DATE_FORMAT(registration_date, '%Y') =" => $date_y) );
		$count = (int) $CI->db
							->where(array(
									"TglReg >=" => $date_start,
									"TglReg <=" => $date_end,
								))
							->count_all_results( "SIMtrRegistrasi" )
							;
		$count++;
		
		$number = (string) (sprintf(self::_get_format_registration_number(), $date_y, $date_m, $date_d, $count));		
		return $number;
	}
	
	private static function _get_format_registration_number()
	{
		$format = "%02d%02d%02dREG-%06d";
		return $format;
	}

	public static function gen_queue( $schedule_date=null )
	{
		$CI = self::ci();
		$CI->load->helper( "reservations/reservation" );
		$CI->load->helper( "registration" );
		
		if( ! is_null($schedule_date) )
		{
			$DATE = new DateTime( $schedule_date );
		} else
		{
			$DATE = new DateTime( "NOW" );
		}
		
		$queue1 = reservation_helper::get_queue_by_date( $DATE->format( "Y-m-d" ) );		
		$queue2 = self::get_queue_by_today();
		
		return (int) @max(array($queue1, $queue2));
	}
	
	public static function get_queue_by_today()
	{
		$CI = self::ci();
		$CI->load->model( "registrations/registration_m" );
		
		$_date = new DateTime( "NOW" );
		$schedule_date = $_date->format( "Y-m-d" );
		
		$count = $CI->db
			->where( "UNIX_TIMESTAMP(schedule_date) = UNIX_TIMESTAMP('{$schedule_date}')" )
			->count_all_results( $CI->registration_m->table )
			;
		
		$count++;
		
		return (int) $count;
	}
	
	private static function & ci()
	{
		return get_instance();
	}
}
