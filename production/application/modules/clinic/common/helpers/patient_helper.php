<?php if (!defined('BASEPATH')) exit('No direct script access allowed.');

final class patient_helper
{
	public static function do_upload( $file_name = NULL )
	{
		$_ci = self::ci();
		
		$config['upload_path'] = './assets/les-patients/';
		$config['allowed_types'] = 'jpeg|jpg|png|doc|docx|xls|xlsx|pdf';
		$config['max_size'] = 0;
		$config['max_width'] = 0;
		$config['max_height'] = 0;
		$config['remove_spaces'] = TRUE;
		$config['encrypt_name'] = TRUE;
		$config['overwrite'] = TRUE;
		
		if( $file_name && file_exists("./assets/les-patients/{$file_name}")  )
		{
			$config['encrypt_name'] = FALSE;
			$config['file_name'] = $file_name;
		}


		$_ci->load->library('upload', $config);

		return ( ! $_ci->upload->do_upload('file_attachment') )
			? ['status'=>'error', 'error_message' => $_ci->upload->display_errors()]
			: ['status'=>'success', 'upload_data' => $_ci->upload->data()];
	}

	public static function create_patient( $item )
	{
		// self::init();
		$_ci = self::ci();
		
		$_ci->db->trans_begin();
				
			$NRM = $item->NRM;	
			$JenisPasien = $_ci->patient_type_model->get_one($item->JenisKerjasamaID);
			$item->CustomerKerjasamaID = (int) @$item->CustomerKerjasamaID;
			// print_r($item);exit;			
			// Jika Pasien adalah Anggota Kerjasama Baru
			if($_ci->input->post("f")){
				if(empty($item->NoAnggota)){
					$_ci->db->trans_rollback();
					return [
						"status" => 'error',
						"message" => 'Nomor Kartu Anggota Kerjasama belum terisi',
						"code" => 500
					];
				}
				// print_r('asasas');exit;
				if($_ci->cooperation_member_model->count_all(['NoAnggota' => $item->NoAnggota, "NRM" => $NRM, "CustomerKerjasamaID" => $item->CustomerKerjasamaID]))
				{
					$cooperation_card = [
						"CustomerKerjasamaID" => $item->CustomerKerjasamaID,
						"NRM" => $NRM,
						"Nama" => $item->NamaPasien,
						"Active" => 1,
						"Klp" => @$item->Klp,
						"TglLahir" => $item->TglLahir,
						"Alamat" => $item->Alamat,
						"Phone" => $item->Phone,
						"Gender" => $item->JenisKelamin,
					];
					// print_r('qwqwqwqwqw');exit;
					$_ci->cooperation_member_model->update( $cooperation_card, $item->NoAnggota);	
				} else {
					
					if($_ci->cooperation_member_model->count_all(['NoAnggota' => $item->NoAnggota]))
					{
						$_ci->db->trans_rollback();
						return [
							"status" => 'error',
							"message" => "Nomor Kartu {$item->NoAnggota} sudah pernah terdaftar disistem, tidak dapat menyimpan sebagai Anggota baru",
							"code" => 500
						];
					}
					
					$cooperation_card = [
						"CustomerKerjasamaID" => $item->CustomerKerjasamaID,
						"NRM" => $NRM,
						"NoAnggota" => $item->NoAnggota,
						"Nama" => $item->NamaPasien,
						"Active" => 1,
						"Klp" => @$item->Klp,
						"TglLahir" => $item->TglLahir,
						"Alamat" => $item->Alamat,
						"Phone" => $item->Phone,
						"Gender" => $item->JenisKelamin,
					];
					// print_r($cooperation_card);exit;
					$_ci->cooperation_member_model->create( $cooperation_card );				
				}
			}
			
			$_ci->patient_m->create( $item );							
			

		if ($_ci->db->trans_status() === FALSE)
		{
			$_ci->db->trans_rollback();
			return [
				"status" => 'error',
				"message" => lang('global:created_failed'),
				"code" => 500
			];
		}
		//$_ci->db->trans_rollback();
		$_ci->db->trans_commit();
		return [
			"NRM" => $NRM,
			"status" => 'success',
			"message" => lang('global:created_successfully'),
			"code" => 200
		];
	}


	public static function process_item_image()
	{
		$_ci = self::ci();
		$do_upload = self::do_upload_import_file('pdf');
		
		if( $do_upload['status'] == 'success')
		{
			$_ci->db->trans_commit();
			return ["status" => 'success', "message" => lang('global:created_successfully')];
			
		} else {
			return $do_upload;
		}
	}

	public static function do_upload_import_file( $import_type )
	{
		$_ci = self::ci();
		$config['upload_path'] = realpath(FCPATH . '../../assets/les-patients');
		$config['allowed_types'] =  'xlsx|csv|xls|txt|xml|pdf';
		$config['max_size'] = 0;
		$config['remove_spaces'] = TRUE;
		$config['encrypt_name'] = TRUE;
		$config['overwrite'] = TRUE;
		
		if( $import_type )
		{	
			$config['encrypt_name'] = FALSE;
			$config['file_name'] = 'import-'.$import_type.date('dmY').'-'.$_FILES['file']['name'];
		}
		
		if( $import_type == 'pdf' )
		{	
			$config['upload_path'] = realpath(FCPATH . '../../assets/les-patients');
			$config['allowed_types'] =  "docx|doc|pdf";
			$config['max_size'] = 500;
			$config['file_name'] = $_FILES['file']['name'];
		}

		$_ci->load->library('upload', $config);

		return ( ! $_ci->upload->do_upload('file') )
			? ['status'=>'error', 'message' => $_ci->upload->display_errors()]
			: ['status'=>'success', 'upload_data' => $_ci->upload->data()];

	}

	public static function get_total_patients_per_areas( $length=-1 )
	{
		if( self::get_total_patients() )
		{
/*
			$sql = <<<EOSQL
SELECT 
    b.zone_name AS country_name,
    c.zone_name AS province_name,
    d.zone_name AS county_name,
    e.zone_name AS district_name,
    f.zone_name AS area_name,
    COUNT(a.ID) AS total_patients
FROM
    common_patients a
    LEFT OUTER JOIN common_zones b ON (a.country_id = b.id)
    LEFT OUTER JOIN common_zones c ON (a.province_id = c.id)
    LEFT OUTER JOIN common_zones d ON (a.county_id = d.id)
    LEFT OUTER JOIN common_zones e ON (a.district_id = e.id)
    LEFT OUTER JOIN common_zones f ON (a.area_id = f.id)
WHERE
    a.country_id <> 0 AND 
    a.province_id <> 0 AND 
    a.county_id <> 0 AND 
    a.district_id <> 0 AND 
    a.area_id <> 0
GROUP BY
    b.zone_name,
    c.zone_name,
    d.zone_name,
    e.zone_name,
    f.zone_name
ORDER BY
    b.zone_name,
    c.zone_name, 
    d.zone_name, 
    d.zone_name, 
    f.zone_name
EOSQL;
*/

			$select_table = <<<EOSQL
				b.zone_name AS country_name,
				c.zone_name AS province_name,
				d.zone_name AS county_name,
				e.zone_name AS district_name,
				f.zone_name AS area_name,
				COUNT(a.ID) AS total_patients
EOSQL;
			
			self::ci()->db
				->select( $select_table )
				->from( "common_patients a" )
				->join( "common_zones b", "a.country_id = b.id", "LEFT OUTER" )
				->join( "common_zones c", "a.province_id = c.id", "LEFT OUTER" )
				->join( "common_zones d", "a.county_id = d.id", "LEFT OUTER" )
				->join( "common_zones e", "a.district_id = e.id", "LEFT OUTER" )
				->join( "common_zones f", "a.area_id = f.id", "LEFT OUTER" )
				;
				
			self::ci()->db
				->where('a.deleted_at', NULL)
				->order_by( "b.zone_name", "asc" )
				->order_by( "c.zone_name", "asc" )
				->order_by( "d.zone_name", "asc" )
				->order_by( "e.zone_name", "asc" )
				->order_by( "f.zone_name", "asc" )
				->group_by(array(
						"b.zone_name",
						"c.zone_name",
						"d.zone_name",
						"e.zone_name",
						"f.zone_name"
					)); 
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
	
	public static function get_latest_patients( $length=5 )
	{
		if( self::get_total_patients() )
		{
			$select_table = <<<EOSQL
				a.id,
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
				->from( "common_patients a" )
				->join( "patient_types b", "a.type_id = b.id", "LEFT OUTER" )
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
					->limit( $length, $start )
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
	
	public static function get_total_patients_year( $today="NOW" )
	{
		$NOW = new DateTime( $today );
		
		self::ci()->load->model( "common/patient_m" );
		$total_patients = self::ci()->patient_m->count(array(
				'state >=' => 1,
				'FROM_UNIXTIME(created_at, \'%Y\') =' => $NOW->format( 'Y' ),
			));
		return (int) $total_patients;
	}
	
	public static function get_total_patients_today( $today="NOW" )
	{
		$NOW = new DateTime( $today );
		
		self::ci()->load->model( "common/patient_m" );
		$total_patients = self::ci()->patient_m->count(array(
				'state >=' => 1,
				'FROM_UNIXTIME(created_at, \'%Y-%m-%d\') =' => $NOW->format( 'Y-m-d' ),
			));
		return (int) $total_patients;
	}
	
	public static function get_total_patients()
	{
		self::ci()->load->model( "common/patient_m" );
		$total_patients = self::ci()->patient_m->count(array(
				'state >=' => 1,
			));
		return (int) $total_patients;
	}
	
	public static function get_age( MY_Object $patient )
	{
		$NOW = new DateTime( "NOW" );
		$BIRTH = new DateTime( $patient->personal_birth_date );
		
		$age = $NOW->diff( $BIRTH );
		return $age;
	}
	
	public static function get_profile_completed( MY_Object $profile )
	{
		$fields = array(
				"personal_name",
				"personal_gender",
				"personal_birth_date",
				"personal_nationality",
				"county_name",
				"province_name",
				"county_name",
				"district_name",
				"area_name",
				"phone_number",
				"email_address",
				"personal_profession",
			);
			
		$grade = @floor(100/(@count($fields)));
		$completed = 0;
		
		foreach( $fields as $field )
		{
			if( $profile->hasData($field) && $profile->getData($field) )
			{
				$completed += $grade;
			}
		}
		
		return @floor($completed);
	}
	
	public static function get_by_number( $mr_number )
	{
		self::ci()->load->model( "common/patient_m" );
		self::ci()->load->model( "common/patient_type_m" );
		self::ci()->load->model( "common/zone_m" );
		self::ci()->load->model( "common/nationality_m" );
		
		$db = self::ci()->db;
		$_patient_m = self::ci()->patient_m;
		$_patient_type_m = self::ci()->patient_type_m;
		$_zone_m = self::ci()->zone_m;
		$_nationality_m = self::ci()->nationality_m;
		
		$from_table = "{$_patient_m->table} a";
		$select_table = <<<EOSQL
			a.id,
			a.mr_number,
			a.type_id,
			b.type_name,
			a.personal_name,
			a.personal_gender,
			a.personal_birth_place,
			a.personal_birth_date,
			a.personal_age,
			a.personal_nationality,
			a.personal_mother_name,
			a.personal_religion,
			a.personal_id_type,
			a.personal_id_number,
			a.personal_picture,
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
			a.state,
			a.created_at,
			a.updated_at
EOSQL;
		
		$db
			->select( $select_table )
			->from( $from_table )
			->join( "{$_patient_type_m->table} b", "a.type_id = b.id", "LEFT OUTER" )
			->join( "{$_zone_m->table} c", "a.country_id = c.id", "LEFT OUTER" )
			->join( "{$_zone_m->table} d", "a.province_id = d.id", "LEFT OUTER" )
			->join( "{$_zone_m->table} e", "a.county_id = e.id", "LEFT OUTER" )
			->join( "{$_zone_m->table} f", "a.district_id = f.id", "LEFT OUTER" )
			->join( "{$_zone_m->table} g", "a.area_id = g.id", "LEFT OUTER" )
			;
			
		$db->group_start();
		$db->where('a.deleted_at', NULL);
		$db->where('a.mr_number', $mr_number);
		$db->group_end();
		
		if( $result = $db->get() )
		{
			return $result->row_array();
			//return $result->row();
		}
		
		return FALSE;
	}
	
	public static function get_type_name( $id, $empty="Undefined" )
	{
		static $populate;
		if( ! isset($populate) || ! is_array($populate) )
		{
			$populate = array();
		}
		
		$id = (int) $id;
		
		if( ! isset($populate[$id]) )
		{
			if( $item = self::ci()->load->model( "common/patient_type_m" )->get($id) )
			{
				$populate[$id] = $item->type_name;
			} else
			{
				$populate[$id] = $empty;
			}
		}
		
		return $populate[$id];
	}
	
	public static function force_create_patient( Array &$data )
	{
		self::ci()->load->model( "common/patient_type_m" );
		self::ci()->load->model( "common/patient_m" );
		self::ci()->load->library( 'form_validation' );
		
		if( (! isset($data['mr_number'])) || (empty($data['mr_number'])) )
		{
			$mr_number = self::gen_mr_number();
			$data['mr_number'] = $mr_number;
		}
		
		if( (! isset($data['type_id'])) || (empty($data['type_id'])) )
		{
			$type_id = (int) self::ci()->config->item( "default_patient_type_id" );
			if( $type_id ){ $type_code = @self::ci()->patient_type_m->get( $type_id )->code; }
			
			$data['type_id'] = $type_id;
			$data['type_code'] = @$type_code;
		}
		
		self::ci()->form_validation->set_rules( self::ci()->patient_m->rules['insert'] );
		self::ci()->form_validation->set_data( $data );
		if( ! self::ci()->form_validation->run( self::ci()->patient_m ) )
		{
			return FALSE;
		}
		
		if( ! ($inserted_id = self::ci()->patient_m->insert( $data )) )
		{
			return FALSE;
		}
		
		$data['id'] = $inserted_id;
		
		// Inject spog insert method
		if( "TRUE" == self::ci()->config->item( "enable_chart_spog" ) )
		{
			self::ci()->load->helper( "spog/spog_patient" );
			spog_patient_helper::prepare_spog_from_patient( $data[ "mr_number" ] );
		} // end: Inject spog insert method
		
		return (array) ($data);
	}
	
	public static function dropdown_referer( $key=null )
	{
		$populate = array(
				"SELF" 				=> lang( "referer:self" ),
				"VENDOR" 			=> lang( "referer:vendor" ),
				"PATIENT_HC"		=> lang( "referer:patient_hc" ),
				"PATIENT_IKS"		=> lang( "referer:patient_iks" ),
				"PATIENT_GENERAL"	=> lang( "referer:patient_general" ),
				"FAMILY"			=> lang( "referer:patient_family" ),
				"FRIEND"			=> lang( "referer:patient_friend" ),
			);
			
		if( ! is_null($key) )
		{
			return $populate[$key];
		}
		
		return $populate;
	}
	
	public static function dropdown_id_type( $key=null )
	{
		$populate = array(
				"KTP" 				=> lang( "id_type:ktp" ),
				"SIM" 				=> lang( "id_type:sim" ),
				"Kartu Pelajar"		=> lang( "id_type:kartu_pelajar" ),
				"Kartu Mahasiswa"	=> lang( "id_type:kartu_mahasiswa" )
			);
			
		if( ! is_null($key) )
		{
			return $populate[$key];
		}
		
		return $populate;
	}
	
	public static function dropdown_relegion( $key=null )
	{
		$populate = array(
				"Hindu" => lang( "relegion:hindu" ),
				"Budha" => lang( "relegion:budha" )
			);
			
		if( ! is_null($key) )
		{
			return $populate[$key];
		}
		
		return $populate;
	}
	
	public static function dropdown_gender( $key=null )
	{
		$populate = array(
				"MALE" => lang( "gender:male" ),
				"FEMALE" => lang( "gender:female" )
			);
			
		if( ! is_null($key) )
		{
			return $populate[$key];
		}
		
		return $populate;
	}
	
	public static function textStatus( $status, $print=false )
	{
		$text = (1==(int) $status) ? lang('global:active') : lang('global:inactive');
		if( true === $print )
		{
			echo $text;
		}
		
		return $text;
	}
	
	public static function update_patient( $data, $mr_number )
	{
		$data = (array) $data;
		
		self::ci()->load->model( "common/patient_m" );		
		self::ci()->load->library( 'form_validation' );
			
		self::ci()->form_validation->set_rules( self::ci()->patient_m->rules['insert'] );
		self::ci()->form_validation->set_data( $data );
		
		if( self::ci()->form_validation->run() === FALSE )
		{
			make_flashdata(array(
					'response_status' => 'error',
					'message' => self::ci()->form_validation->get_all_error_string()
				));
			return FALSE;
		} else 
		{
			if( self::ci()->patient_m->update( $data, array("mr_number" => $mr_number) ) === FALSE )
			{
				make_flashdata(array(
						'response_status' => 'error',
						'message' => lang('patients:updated_failed')
					));
			}
			return FALSE;
		}
		
		return TRUE;
	}
	
	public static function get_patient_profile( $id )
	{
		self::ci()->load->model( "common/patient_m" );
		self::ci()->load->model( "common/patient_type_m" );
		self::ci()->load->model( "common/zone_m" );
		self::ci()->load->model( "common/nationality_m" );
		
		$db = self::ci()->db;
		$_patient_m = self::ci()->patient_m;
		$_patient_type_m = self::ci()->patient_type_m;
		$_zone_m = self::ci()->zone_m;
		$_nationality_m = self::ci()->nationality_m;
		
		$from_table = "{$_patient_m->table} a";
		$select_table = <<<EOSQL
			a.id,
			a.mr_number,
			a.type_id,
			b.type_name,
			a.personal_name,
			a.personal_gender,
			a.personal_birth_place,
			a.personal_birth_date,
			a.personal_age,
			a.personal_nationality,
			a.personal_mother_name,
			a.personal_religion,
			a.personal_id_type,
			a.personal_id_number,
			a.personal_picture,
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
			a.state,
			a.created_at,
			a.updated_at
EOSQL;
		
		$db
			->select( $select_table )
			->from( $from_table )
			->join( "{$_patient_type_m->table} b", "a.type_id = b.id", "LEFT OUTER" )
			->join( "{$_zone_m->table} c", "a.country_id = c.id", "LEFT OUTER" )
			->join( "{$_zone_m->table} d", "a.province_id = d.id", "LEFT OUTER" )
			->join( "{$_zone_m->table} e", "a.county_id = e.id", "LEFT OUTER" )
			->join( "{$_zone_m->table} f", "a.district_id = f.id", "LEFT OUTER" )
			->join( "{$_zone_m->table} g", "a.area_id = g.id", "LEFT OUTER" )
			;
			
		$db->group_start();
		$db->where('a.deleted_at', NULL);
		$db->where('a.id', $id);
		$db->group_end();
		
		return $db
			->get()
			->row()
			;
	}
	
	public static function get_patient( $mr_number )
	{
		self::ci()->load->model( "common/patient_m" );
		$patient = self::ci()->patient_m
			->as_object()
			->get(array(
					"mr_number" => $mr_number,
				))
			;
		
		if( $patient )
		{
			return $patient;
		}
		
		return FALSE;
	}
	
	public static function find_patient( $mr_number )
	{
		self::ci()->load->model( "common/patient_m" );
		$found = self::ci()->patient_m
			->where(array(
					self::ci()->patient_m->primary_key => $mr_number,
				))
			->count_all_results( self::ci()->patient_m->table )
			;
		
		return (bool) $found;
	}
	
	public static function gen_mr_number( $length=6, $split_length=2, $split_separator="." )
	{
		return self::_gen_mr_number( $length, $split_length, $split_separator );
	}
	
	private static function _gen_mr_number( $length=6, $split_length=2, $split_separator="." )
	{
		$mr_number =  @self::ci()->db
			->select( "MAX(NRM) AS mr_number" )
			->get( "mPasien" )
			->row()
			->mr_number;
		
		
		if( !empty($mr_number) )
		{
			$mr_number++;
		} else {
			$mr_number = '00.00.01';
		}
		
		return (string) $mr_number++;
	}
	
	private static function & ci()
	{
		return get_instance();
	}
}





