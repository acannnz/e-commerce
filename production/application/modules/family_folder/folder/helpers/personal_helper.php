<?php
defined('BASEPATH') OR exit('No direct script access allowed');

final class Personal_helper
{		
	private static $user_auth;
	private static $_ci;
	
	public static function init()
	{
		self::$_ci = $_ci = get_instance();
		
		$_ci->load->model('family_model');
		$_ci->load->model('personal_model');
		$_ci->load->model('personal_to_family_model');
		$_ci->load->model('personal_to_environment_model');
		$_ci->load->model('personal_to_obgyn_model');
		$_ci->load->model('personal_to_immunization_model');

		$_ci->load->library('simple_login');		
		self::$user_auth = $_ci->simple_login->get_user();
		
	}
	
	
	public static function prepare_form( $relation_id = NULL )
	{
		self::init();
		$_ci = self::ci(); 
		
		$items = [];
		
		$roles = self::_get_roles( $relation_id );
		foreach( $roles as $key => $role ): foreach( $role as $row ):
		
			if( in_array($row['Format'], array("TEXT", "CHAR")) ){ $row['Note'] = trim($row['Note']); }
			else if( in_array($row['Format'], array("INT", "INTEGER")) ){ $row['Note'] = (int) $row['Note']; }
			else if( in_array($row['Format'], array("FLOAT", "DECIMAL")) ){ $row['Note'] = (float) $row['Note']; }
			else if( in_array($row['Format'], array("BOOL", "BOOLEAN")) ){ $row['Note'] = (bool) $row['Note']; }
			
			$items[$key][$row['Role']] = $row['Note'];
		endforeach; endforeach;
		
		return $items;
	}
	
	public static function prepare_insert( $note, $role, $type)
	{
		self::init();
		$_ci = self::ci(); 
		
		$roles = self::_get_roles();
		foreach( $roles[$type] as $row ):
			if( $row['Role'] == $role ):
			
				$row['Note'] = $note;
				$row['Description'] = lang( sprintf('label:%s', strtolower($note)) ) 
										? lang( sprintf('label:%s', strtolower($note)) ) 
										: lang( sprintf('label:%s', strtolower($role)) );
				return $row;
				
			endif;
		endforeach; 
		
		return FALSE;
	}
	
	private static function _get_roles( $resource_id = NULL )
	{
		$_ci = self::ci(); 
		
		$items = [
			'environment' => [
				["Specialist" => "GENERAL", "Type" => "ENVIRONMENT", "Group" => "FF", "Role" => "house_wall", "Title" => "House Wall", "Note" => "permanent", "Unit" => "", "Format" => "TEXT", "Description" => lang('label:permanent')],
				["Specialist" => "GENERAL", "Type" => "ENVIRONMENT", "Group" => "FF", "Role" => "house_floor", "Title" => "House Floor", "Note" => "cement", "Unit" => "", "Format" => "TEXT", "Description" => lang('label:cement')],
				["Specialist" => "GENERAL", "Type" => "ENVIRONMENT", "Group" => "FF", "Role" => "house_lighting", "Title" => "House Lighting", "Note" => "enough", "Unit" => "", "Format" => "TEXT", "Description" => lang('label:enough')],
				["Specialist" => "GENERAL", "Type" => "ENVIRONMENT", "Group" => "FF", "Role" => "house_krpl", "Title" => "House KRPL", "Note" => "exist", "Unit" => "", "Format" => "TEXT", "Description" => lang('label:exist')],
				["Specialist" => "GENERAL", "Type" => "ENVIRONMENT", "Group" => "FF", "Role" => "waste_disposal", "Title" => "Waste Disposal", "Note" => "burned", "Unit" => "", "Format" => "TEXT", "Description" => lang('label:burned')],
				["Specialist" => "GENERAL", "Type" => "ENVIRONMENT", "Group" => "FF", "Role" => "sewer", "Title" => "Sewer", "Note" => "exist", "Unit" => "", "Format" => "TEXT", "Description" => lang('label:exist')],
				["Specialist" => "GENERAL", "Type" => "ENVIRONMENT", "Group" => "FF", "Role" => "water_source", "Title" => "Water Source", "Note" => "SPT", "Unit" => "", "Format" => "TEXT", "Description" => lang('label:spt')],
				["Specialist" => "GENERAL", "Type" => "ENVIRONMENT", "Group" => "FF", "Role" => "toilet", "Title" => "Toilet", "Note" => "Cemplung", "Unit" => "", "Format" => "TEXT", "Description" => lang('label:cemplung')],
				["Specialist" => "GENERAL", "Type" => "ENVIRONMENT", "Group" => "FF", "Role" => "dietary_staple_food", "Title" => "Dietary Staple Food", "Note" => lang('label:dietary_rice'), "Unit" => "", "Format" => "TEXT", "Description" => lang('label:dietary_staple_food')],
				["Specialist" => "GENERAL", "Type" => "ENVIRONMENT", "Group" => "FF", "Role" => "dietary_side_dishes", "Title" => "Dietary Side Dishes", "Note" => 1, "Unit" => "", "Format" => "INT", "Description" => lang('label:dietary_side_dishes')],
				["Specialist" => "GENERAL", "Type" => "ENVIRONMENT", "Group" => "FF", "Role" => "dietary_vegetables", "Title" => "Dietary Vegetables", "Note" => 1, "Unit" => "", "Format" => "INT", "Description" => lang('label:dietary_vegetables')],
				["Specialist" => "GENERAL", "Type" => "ENVIRONMENT", "Group" => "FF", "Role" => "dietary_fruits", "Title" => "Dietary Fruits", "Note" => 1, "Unit" => "", "Format" => "INT", "Description" => lang('label:dietary_fruits')],
				["Specialist" => "GENERAL", "Type" => "ENVIRONMENT", "Group" => "FF", "Role" => "dietary_milk", "Title" => "Dietary Milk", "Note" => 1, "Unit" => "", "Format" => "INT", "Description" => lang('label:dietary_milk')],
			],
			'obgyn' => [
				["Specialist" => "GENERAL", "Type" => "OBGYN", "Group" => "FF", "Role" => "born_status", "Title" => "Born Status", "Note" => "born_alive", "Unit" => "", "Format" => "TEXT", "Description" => lang('label:born_alive')],
				["Specialist" => "GENERAL", "Type" => "OBGYN", "Group" => "FF", "Role" => "condition_status", "Title" => "Condition Status", "Note" => "condition_alive", "Unit" => "", "Format" => "TEXT", "Description" => lang('label:condition_alive')],
				["Specialist" => "GENERAL", "Type" => "OBGYN", "Group" => "FF", "Role" => "obgyn_by", "Title" => "OBGYN By", "Note" => "", "Unit" => "", "Format" => "TEXT", "Description" => lang('label:obgyn_by')],
			],
			'immunization' => [
				["Specialist" => "GENERAL", "Type" => "IMMUNIZATION", "Group" => "FF", "Role" => "BCG_1", "Title" => "Bacillus Calmette-Guérin 1", "Note" => 0, "Unit" => "", "Format" => "INT", "Description" => lang('label:bcg')],
				["Specialist" => "GENERAL", "Type" => "IMMUNIZATION", "Group" => "FF", "Role" => "DPT_1", "Title" => "Difteri, Pertusis, &amp; Tetanus 1", "Note" => 0, "Unit" => "", "Format" => "INT", "Description" => lang('label:dpt')],
				["Specialist" => "GENERAL", "Type" => "IMMUNIZATION", "Group" => "FF", "Role" => "DPT_2", "Title" => "Difteri, Pertusis, &amp; Tetanus 2", "Note" => 0, "Unit" => "", "Format" => "INT", "Description" => lang('label:dpt')],
				["Specialist" => "GENERAL", "Type" => "IMMUNIZATION", "Group" => "FF", "Role" => "DPT_3", "Title" => "Difteri, Pertusis, &amp; Tetanus 3", "Note" => 0, "Unit" => "", "Format" => "INT", "Description" => lang('label:dpt')],
				["Specialist" => "GENERAL", "Type" => "IMMUNIZATION", "Group" => "FF", "Role" => "polio_1", "Title" => "Polio 1", "Note" => 0, "Unit" => "", "Format" => "INT", "Description" => lang('label:polio')],
				["Specialist" => "GENERAL", "Type" => "IMMUNIZATION", "Group" => "FF", "Role" => "polio_2", "Title" => "Polio 2", "Note" => 0, "Unit" => "", "Format" => "INT", "Description" => lang('label:polio')],
				["Specialist" => "GENERAL", "Type" => "IMMUNIZATION", "Group" => "FF", "Role" => "polio_3", "Title" => "Polio 3", "Note" => 0, "Unit" => "", "Format" => "INT", "Description" => lang('label:polio')],
				["Specialist" => "GENERAL", "Type" => "IMMUNIZATION", "Group" => "FF", "Role" => "polio_4", "Title" => "Polio 4", "Note" => 0, "Unit" => "", "Format" => "INT", "Description" => lang('label:polio')],
				["Specialist" => "GENERAL", "Type" => "IMMUNIZATION", "Group" => "FF", "Role" => "hepatitis_b_1", "Title" => "Hepatitis B 1", "Note" => 0, "Unit" => "", "Format" => "INT", "Description" => lang('label:hepatitis_b')],
				["Specialist" => "GENERAL", "Type" => "IMMUNIZATION", "Group" => "FF", "Role" => "hepatitis_b_2", "Title" => "Hepatitis B 2", "Note" => 0, "Unit" => "", "Format" => "INT", "Description" => lang('label:hepatitis_b')],
				["Specialist" => "GENERAL", "Type" => "IMMUNIZATION", "Group" => "FF", "Role" => "hepatitis_b_3", "Title" => "Hepatitis B 3", "Note" => 0, "Unit" => "", "Format" => "INT", "Description" => lang('label:hepatitis_b')],				
				["Specialist" => "GENERAL", "Type" => "IMMUNIZATION", "Group" => "FF", "Role" => "campak_1", "Title" => "Campak 1", "Note" => 0, "Unit" => "", "Format" => "INT", "Description" => lang('label:campak')],
				["Specialist" => "GENERAL", "Type" => "IMMUNIZATION", "Group" => "FF", "Role" => "DT_1", "Title" => "Difteri &amp; Tetanus 1", "Note" => 0, "Unit" => "", "Format" => "INT", "Description" => lang('label:dt')],
				["Specialist" => "GENERAL", "Type" => "IMMUNIZATION", "Group" => "FF", "Role" => "DT_2", "Title" => "Difteri &amp; Tetanus 2", "Note" => 0, "Unit" => "", "Format" => "INT", "Description" => lang('label:dt')],				
			]
		];
		
		
		if( !empty($resource_id) )
		{
			$items['environment'] = array_merge($items['environment'], $_ci->personal_to_environment_model->get_all(NULL, 0, ['ResourceId' => $resource_id], TRUE));
			$items['obgyn'] = array_merge($items['obgyn'], $_ci->personal_to_obgyn_model->get_all(NULL, 0, ['ResourceId' => $resource_id], TRUE));
			$items['immunization'] = array_merge($items['immunization'], $_ci->personal_to_immunization_model->get_all(NULL, 0, ['ResourceId' => $resource_id], TRUE));
		}
	
		return $items;
	}
	
	public static function insert_history_log( $items )
	{
		self::init();
		$_ci = self::ci();

		$_loggen_in = self::_gen_loggen_in( $items[0]['Id'] );
		
		$batch = [];
		foreach( $items as $item )
		{
			$item['HistoryId'] = $item['Id'];
			$item['LoggedIn'] = $_loggen_in;
			$item['LoggedAt'] = time();
			$item['LoggedBy'] = self::$user_auth->User_ID;
			
			unset($item['Id']);			
			$batch[] = $item;
		}
		
		$_ci->db->insert_batch('FF_PersonalToHistoryLog', $batch);
	}
	
	private static function _gen_loggen_in( $history_id )
	{
		$_ci = self::ci();
		
		$max = (int) @$_ci->db->select("MAX(LoggedIn) AS max")
							->where("HistoryId", $history_id)
							->get("FF_PersonalToHistoryLog")
							->row()
							->max;
		return ++$max;
	}
	
	public static function search_array_by_role_note( $role, $note, Array $collection )
	{
		if( ($key = array_search($role, array_column($collection, 'Role'))) !== FALSE && !empty($collection) )
		{
			if( $collection[$key]['Note'] != $note )
			{
				return TRUE;
			}
		}
		return FALSE;
	}

	private static function & ci()
	{
		return get_instance();
	}	

}
