<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

final class Import_helper
{		
	private static $user_auth;
	private static $_ci;	
	public static function init()
	{
		self::$_ci = $_ci = self::ci();
		
		$_ci->load->library('simple_login');		
		self::$user_auth = $_ci->simple_login->get_user();
		
	}
	
	private static function gen_mr_number()
	{
		$get =  @self::ci()->db
			->select( "MAX(NRM) AS max" )
			->get( "mPasien" )
			->row();
		
		
		if( !empty($get->max) )
		{
			$mr_number = ++$get->max;
		} else {
			$mr_number = '00.00.01';
		}
		
		return (string) $mr_number;
	}

	public static function gen_family_number()
	{
		$_ci = self::ci();
		
		$query =  $_ci->db->select("MAX( Right(NoFamily, 8) ) as max_number")
						->get( "{$_ci->family_model->table}" )
						->row();
						
		$max_number = !empty($query->max_number) ? ++$query->max_number : 1;
		$number = (string) (sprintf("FF%08d", $max_number));
		return $number;
	}
	
	public static function previsew_patient()
	{
		self::init();
		$_ci = self::ci();
		
		$do_upload = self::do_upload_import_file('patient');
	
		if( $do_upload['status'] == 'success')
		{
			$inputFileType = ucfirst(str_replace('.', '', $do_upload['upload_data']['file_ext']));
			$inputFileName = realpath(FCPATH . '../../assets/import/'.$do_upload['upload_data']['file_name']);

			$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
						
			$spreadsheet = $reader->load($inputFileName);
			$activeSheet = $spreadsheet->getActiveSheet();
			$highestCell = $activeSheet->getHighestColumn() . $activeSheet->getHighestRow();
			$getCollection = $activeSheet->rangeToArray(
								"A2:{$highestCell}",     // The worksheet range that we want to retrieve
								NULL,        // Value that should be returned for empty cells
								TRUE,        // Should formulas be calculated (the equivalent of getCalculatedValue() for each cell)
								TRUE,        // Should values be formatted (the equivalent of getFormattedValue() for each cell)
								TRUE         // Should the array be indexed by cell row and cell column
							);
							
			$NRM = self::gen_mr_number();
			$_patient = [];
			foreach($getCollection as $key => $row):			
				if(empty($row['B'])){
					continue;
				}
				
				// [A] => NRMLama, [B] => NamaPasien, [C] => PasienHubungan, [D] => JK, [E] => Umur, [F]=> TGL Lahir 
				// [G] => NamaPendamping, [H]=> PendampingHubungan, [I]=> Alamat, [J]=> NoTelp, [K] => Pekerjaan 
				// [L] => Dokter Referensi
											
				// mPasien
				/*
					NRM
					NamaPasien
					JenisKerjasamaID
					NRMLama
				*/
				if( !empty($row['A']) && ! empty($row['B']) ):		
					$birthdate = date('Y-m-d');
					if(!empty($row['C'])):
						$birthdate = date('Y-m-d', strtotime($row['E']));
					endif;
					$_patient[] = [
						'NRM' => $NRM,
						'NamaPasien' => @$row['A']. ' ' . @$row['B'],
						'JenisKelamin' => (@$row['D'] == 'L') ? 'M' : 'F',
						'TglLahir' => $birthdate,
						'TglLahirDiketahui' => empty($row['E']) ? 0 : 1,
						'UmurSaatInput' => $row['F'],
						'Alamat' => $row['H'],
						'Pekerjaan' => $row['G'],
						'NoIdentitas' => $row['C'],
						'NoKartu' => $row['O'],
						'Phone' => $row['L'],
						'JenisPasien' => ($row['M'] == 'UMUM') ? 'UMUM' : 'BPJS',
						'JenisKerjasamaID' => ($row['N'] == 'UMUM') ? 3 : 9,
						'NationalityID' => 'INA',
						'PropinsiID' => $row['I'],
						'KabupatenID' => $row['J'],
						'KecamatanID' => $row['K'],
						'KodePos' => $row['P'],
						'Agama' => $row['Q'],
						'TempatLahir' => $row['R'],
						'PasienKTP' => 1,
						'TglInput' => date('Y-m-d'),
						'UserID' => self::$user_auth->User_ID,
						'DokterID_ReferensiPertama' => NULL,
						'PenanggungNama' => @$row['A']. ' ' . @$row['B'],
						'PenanggungAlamat' => $row['H'],
						'PenanggungPhone' => '',
						'PenanggungHubungan' => 'Pasien Sendiri',
						'PenanggungPekerjaan' => NULL,
						'Aktif' => 1,
						'NRMLama' => $row['S'],
					];
				endif;
			endforeach;
			
			response_json($_patient);exit;
		} else {
			response_json($do_upload);
		}
	}
	
	public static function processs_patient()
	{
		self::init();
		$_ci = self::ci();
		
		$do_upload = self::do_upload_import_file('patient');
		
		if( $do_upload['status'] == 'success')
		{
			$inputFileType = ucfirst(str_replace('.', '', $do_upload['upload_data']['file_ext']));
			$inputFileName = realpath(FCPATH . '../../assets/import/'.$do_upload['upload_data']['file_name']);

			$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
						
			$spreadsheet = $reader->load($inputFileName);
			$activeSheet = $spreadsheet->getActiveSheet();
			$highestCell = $activeSheet->getHighestColumn() . $activeSheet->getHighestRow();
			$getCollection = $activeSheet->rangeToArray(
								"A2:{$highestCell}",     // The worksheet range that we want to retrieve
								NULL,        // Value that should be returned for empty cells
								TRUE,        // Should formulas be calculated (the equivalent of getCalculatedValue() for each cell)
								TRUE,        // Should values be formatted (the equivalent of getFormattedValue() for each cell)
								TRUE         // Should the array be indexed by cell row and cell column
							);
			$_ci->db->trans_begin();
				$NRM = self::gen_mr_number();
				$_patient = [];
				foreach($getCollection as $key => $row) {				
					if(empty($row['B'])){
						continue;
					}
					
					// [A] => NRMLama, [B] => NamaPasien, [C] => PasienHubungan, [D] => JK, [E] => Umur, [F]=> TGL Lahir 
					// [G] => NamaPendamping, [H]=> PendampingHubungan, [I]=> Alamat, [J]=> NoTelp, [K] => Pekerjaan 
					// [L] => Dokter Referensi
										
					// mPasien
					/*
						NRM
						NamaPasien
						JenisKerjasamaID
						NRMLama
					*/
					if( !empty($row['A']) && ! empty($row['B']) ):		
						$birthdate = date('Y-m-d');
						if(!empty($row['C'])):
							$birthdate = date('Y-m-d', strtotime($row['C']));
						endif;
									
						$_patient = [
							'NRM' => $row['A'],
							'NamaPasien' => $row['B'],
							'JenisKelamin' => ($row['F'] == 'L') ? 'M' : 'F',
							'TglLahir' => $birthdate,
							'TglLahirDiketahui' => 0,
							'UmurSaatInput' => calculate_age($birthdate),
							'Alamat' => $row['D'],
							'Pekerjaan' => '',
							'NoKartu' => '',
							'Phone' => '',
							'JenisPasien' => ($row['E'] == 'UMUM') ? 'UMUM' : 'BPJS',
							'JenisKerjasamaID' => ($row['E'] == 'UMUM') ? 3 : 9,
							'NationalityID' => 'INA',
							'PropinsiID' => 1,
							'PasienKTP' => 1,
							'TglInput' => date('Y-m-d'),
							'UserID' => self::$user_auth->User_ID,
							'DokterID_ReferensiPertama' => NULL,
							'PenanggungNama' => $row['B'],
							'PenanggungAlamat' => $row['D'],
							'PenanggungPhone' => '',
							'PenanggungHubungan' => 'Pasien Sendiri',
							'PenanggungPekerjaan' => NULL,
							'Aktif' => 1,
							'NRMLama' => $row['A'],
						];
						$_ci->patient_model->create( $_patient );
						
						$NRM = 1 . str_replace('.', '', $NRM);
						$NRM = ++$NRM;
						$NRM = substr($NRM, 1); 
						$arrayNRM = @str_split( $NRM, 2 );
						$NRM = @implode( '.', $arrayNRM );
					endif;
					
				}
				
				$import_log = [
					'FileName' => $do_upload['upload_data']['file_name'],
					'ImportType' => __FUNCTION__,
					'Collection' => json_encode( $getCollection ),
					'CreatedAt' => date('Y-m-d H:i:s'),
					'CreatedBy' => self::$user_auth->User_ID
				];
				$_ci->import_model->create( $import_log );				
			
			if($_ci->db->trans_status() === FALSE)
			{
				$_ci->db->trans_rollback();
				return ["status" => 'error', "message" => lang('global:created_failed')];
			}
			//$_ci->db->trans_rollback();
			$_ci->db->trans_commit();
			return ["status" => 'success', "message" => lang('global:created_successfully')];
			
		} else {
			return $do_upload;
		}
	}
	
	public static function preview_family_folder_patient()
	{
		self::init();
		$_ci = self::ci();
		
		$do_upload = self::do_upload_import_file('ff_patient');
		
		if( $do_upload['status'] == 'success')
		{
			$inputFileType = ucfirst(str_replace('.', '', $do_upload['upload_data']['file_ext']));
			$inputFileName = realpath(FCPATH . '../../assets/import/'.$do_upload['upload_data']['file_name']);

			$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
						
			$spreadsheet = $reader->load($inputFileName);
			$activeSheet = $spreadsheet->getActiveSheet();
			$highestCell = $activeSheet->getHighestColumn() . $activeSheet->getHighestRow();
			$getCollection = $activeSheet->rangeToArray(
								"A2:{$highestCell}",     // The worksheet range that we want to retrieve
								NULL,        // Value that should be returned for empty cells
								TRUE,        // Should formulas be calculated (the equivalent of getCalculatedValue() for each cell)
								TRUE,        // Should values be formatted (the equivalent of getFormattedValue() for each cell)
								TRUE         // Should the array be indexed by cell row and cell column
							);
			
			$NRM = self::gen_mr_number();
			$NoFamily = self::gen_family_number();
			$_personal = $_family = $_personal_family = $_patient = [];
			foreach($getCollection as $key => $row) {				
				if(empty($row['B'])){
					continue;
				}
				
				// [A] => NRMLama, [B] => NamaPasien, [C] => PasienHubungan, [D] => JK, [E] => Umur, [F]=> TGL Lahir 
				// [G] => NamaPendamping, [H]=> PendampingHubungan, [I]=> Alamat, [J]=> NoTelp, [K] => Pekerjaan 
				// [L] => Dokter Referensi
							
				// FF_Personal
				$_personal[] = $primary = [
					'PersonalName' => $row['B'],
					'PersonalGender' => empty($row['D']) ? 'L' : $row['D'],
					'PersonalBirthPlace' => 'Bali',
					'PersonalBirthDate' => empty($row['F']) ? '1970-01-01' : strtotime($row['F']),
					'PersonalAge' => empty($row['E']) ? 0 : $row['E'],
					'PersonalNationality' => 'INA',
					'PersonalReligion' => NULL,
					'PersonalIDType' => NULL,
					'PersonalIDNumber' => NULL,
					'PersonalPicture' => NULL,
					'PersonalProfession' => $row['K'],
					'PersonalEducation' => '',
					'PersonalFirstVisitDate' => date('Y-m-01'),
					'PersonalAddress' => $row['I'],
					'PostalCode' => NULL,
					'CountryId' => 15,
					'CountryName' => 'INDONESIA',
					'ProvinceId' => 1,
					'ProvinceName' => 'BALI',
					'MobileNumber' => $row['J'],
				];
				
				if( !empty($row['G'])){ // Jika Terdapat data Pendamping
					$_personal[] = $secondary = [
						'PersonalName' => $row['G'],
						'PersonalGender' => $row['H'] == 'WIFE' ? 'P' : 'L',
						'PersonalBirthPlace' => 'Bali',
						'PersonalBirthDate' => '1970-01-01',
						'PersonalAge' => 0,
						'PersonalNationality' => 'INA',
						'PersonalReligion' => NULL,
						'PersonalIDType' => NULL,
						'PersonalIDNumber' => NULL,
						'PersonalPicture' => NULL,
						'PersonalProfession' => '',
						'PersonalEducation' => '',
						'PersonalFirstVisitDate' => NULL,
						'PersonalAddress' => $row['I'],
						'PostalCode' => NULL,
						'CountryId' => 15,
						'CountryName' => 'INDONESIA',
						'ProvinceId' => 1,
						'ProvinceName' => 'BALI',
						'MobileNumber' => $row['J'],
					];
				}
				
				// FF_Family
				/*
					NoFamily
					PersonalIdKK
					Address
				*/
				$_family[] = [
					'NoFamily' => $NoFamily++,
					'NoKK' => '-',
					'PersonalIdKK' => ($row['B'] == $row['G'] || empty($row['G']) ) ? $row['B'] : $row['G'],
					'Address' => $row['I'],
					'CountryId' => 15,
					'CountryName' => 'INDONESIA',
					'ProvinceId' => 1,
					'ProvinceName' => 'BALI'
				];
				
				// FF_PersonalToFamily
				/*
					FamilyId
					PersonalId
					Relation
				*/
				//$_personal_family[] = [];
				
				// mPasien
				/*
					NRM
					NamaPasien
					JenisKerjasamaID
					NRMLama
				*/
				if( !empty($row['A']) && ! empty($row['B']) ):					
					$_patient[] = [
						'NRM' => $NRM++,
						'NamaPasien' => $row['B'],
						'JenisKelamin' => empty($row['D']) ? 'L' : $row['D'],
						'TglLahir' => empty($row['F']) ? '1970-01-01' : strtotime($row['F']),
						'TglLahirDiketahui' => empty($row['F']) ? 0 : 1,
						'UmurSaatInput' => empty($row['E']) ? 0 : strtotime($row['E']),
						'Alamat' => $row['I'],
						'Pekerjaan' => $row['K'],
						'Phone' => $row['J'],
						'JenisPasien' => 'UMUM',
						'JenisKerjasamaID' => 3,
						'NationalityID' => 'INA',
						'PropinsiID' => 1,
						'PasienKTP' => 1,
						'TglInput' => date('Y-m-d'),
						'UserID' => self::$user_auth->User_ID,
						'DokterID_ReferensiPertama' => $row['L'],
						'PenanggungNama' => ($row['B'] == $row['G'] || empty($row['G']) ) ? $row['B'] : $row['G'],
						'PenanggungAlamat' => $row['I'],
						'PenanggungPhone' => $row['J'],
						'PenanggungHubungan' => ($row['B'] == $row['G'] || empty($row['G']) ) ? 'Pasien Sendiri' : 'Lainnya',
						'PenanggungPekerjaan' => $row['K'],
						'Aktif' => 1,
						'PersonalId' => 0,
						'NRMLama' => $row['A'],
					];
				endif;
			}
			
			$_ci->data['personal'] = $_personal;
			$_ci->data['family'] = $_family;
			//$_ci->data['personal_family'] = $_personal_family;
			$_ci->data['patient'] = $_patient;			
	
			
			$_ci->load->view('ff_pasien', $_ci->data);
		} else {
			response_json($do_upload);
		}
	}
	
	public static function process_family_folder_patient()
	{
		self::init();
		$_ci = self::ci();
		
		$do_upload = self::do_upload_import_file('ff_patient');
		
		if( $do_upload['status'] == 'success')
		{
			$inputFileType = ucfirst(str_replace('.', '', $do_upload['upload_data']['file_ext']));
			$inputFileName = realpath(FCPATH . '../../assets/import/'.$do_upload['upload_data']['file_name']);

			$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
						
			$spreadsheet = $reader->load($inputFileName);
			$activeSheet = $spreadsheet->getActiveSheet();
			$highestCell = $activeSheet->getHighestColumn() . $activeSheet->getHighestRow();
			$getCollection = $activeSheet->rangeToArray(
								"A2:{$highestCell}",     // The worksheet range that we want to retrieve
								NULL,        // Value that should be returned for empty cells
								TRUE,        // Should formulas be calculated (the equivalent of getCalculatedValue() for each cell)
								TRUE,        // Should values be formatted (the equivalent of getFormattedValue() for each cell)
								TRUE         // Should the array be indexed by cell row and cell column
							);
			$_ci->db->trans_begin();
				$NRM = self::gen_mr_number();
				$NoFamily = self::gen_family_number();
				$family_id = $secondary_personal_id = 0;
				$_secondary_personal_before = '';
				$_personal = $_family = $_personal_family = $_patient = [];
				foreach($getCollection as $key => $row) {				
					if(empty($row['B'])){
						continue;
					}
					
					// [A] => NRMLama, [B] => NamaPasien, [C] => PasienHubungan, [D] => JK, [E] => Umur, [F]=> TGL Lahir 
					// [G] => NamaPendamping, [H]=> PendampingHubungan, [I]=> Alamat, [J]=> NoTelp, [K] => Pekerjaan 
					// [L] => Dokter Referensi
					//$_ci->db->query("SET IDENTITY_INSERT [dbo].[{$_ci->personal_model->table}] OFF;");
					// FF_Personal
					$_primary_personal = [
						'PersonalName' => $row['B'],
						'PersonalGender' => empty($row['D']) ? 'L' : $row['D'],
						'PersonalBirthPlace' => 'BALI',
						'PersonalBirthDate' => empty($row['F']) ? '1970-01-01' : date('Y-m-d', strtotime($row['F'])),
						'PersonalAge' => empty($row['E']) ? 0 : (int) $row['E'],
						'PersonalNationality' => 'INA',
						'PersonalReligion' => NULL,
						'PersonalIDType' => NULL,
						'PersonalIDNumber' => NULL,
						'PersonalPicture' => NULL,
						'PersonalProfession' => $row['K'],
						'PersonalEducation' => '',
						'PersonalFirstVisitDate' => date('Y-m-01'),
						'PersonalAddress' => $row['I'].'.',
						'PostalCode' => NULL,
						'CountryId' => 15,
						'CountryName' => 'INDONESIA',
						'ProvinceId' => 1,
						'ProvinceName' => 'BALI',
						'MobileNumber' => $row['J'],
						'Status' => 1,
						'CreatedAt' => time(),
						'CreatedBy' => self::$user_auth->User_ID,
						'UpdatedAt' => NULL,
						'UpdatedBy' => 0,
						'DeletedAt' => NULL,
						'DeletedBy' => 0,
					];
					
					$_primary_personal['Id'] = $_ci->personal_model->create( $_primary_personal );
					
					if( !empty($row['G']) && $row['G'] != $_secondary_personal_before && $row['B'] != $row['G'] ){ // Jika Terdapat data Pendamping
						$_secondary_personal = [
							'PersonalName' => $row['G'],
							'PersonalGender' => $row['H'] == 'WIFE' ? 'P' : 'L',
							'PersonalBirthPlace' => 'BALI',
							'PersonalBirthDate' => '1970-01-01',
							'PersonalAge' => 0,
							'PersonalNationality' => 'INA',
							'PersonalReligion' => NULL,
							'PersonalIDType' => NULL,
							'PersonalIDNumber' => NULL,
							'PersonalPicture' => NULL,
							'PersonalProfession' => '',
							'PersonalEducation' => '',
							'PersonalFirstVisitDate' => NULL,
							'PersonalAddress' => $row['I'].'.',
							'PostalCode' => NULL,
							'CountryId' => 15,
							'CountryName' => 'INDONESIA',
							'ProvinceId' => 1,
							'ProvinceName' => 'BALI',
							'MobileNumber' => $row['J'],
							'Status' => 1,
							'CreatedAt' => time(),
							'CreatedBy' => self::$user_auth->User_ID,
							'UpdatedAt' => NULL,
							'UpdatedBy' => 0,
							'DeletedAt' => NULL,
							'DeletedBy' => 0,
						];
						//if( $_secondary_personal['PersonalName'] == 'SRI ASTUTI'){ print_r($_primary_personal); exit;}
						$_secondary_personal['Id'] = $secondary_personal_id = $_ci->personal_model->create( $_secondary_personal );
					}
					
					// FF_Family
					/*
						NoFamily
						PersonalIdKK
						Address
					*/
					if( !empty($row['A']) && ($row['G'] != $_secondary_personal_before) )
					{
						$_family = [
							'NoFamily' => $NoFamily++,
							'NoKK' => '-',
							'PersonalIdKK' => ($row['B'] == $row['G'] || empty($row['G']) ) 
												? $_primary_personal['Id'] : $_secondary_personal['Id'],
							'Address' => $row['I'].'.',
							'CountryId' => 15,
							'CountryName' => 'INDONESIA',
							'ProvinceId' => 1,
							'ProvinceName' => 'BALI',
							'Status' => 1,
							'CreatedAt' => time(),
							'CreatedBy' => self::$user_auth->User_ID,
							'UpdatedAt' => NULL,
							'UpdatedBy' => 0,
							'DeletedAt' => NULL,
							'DeletedBy' => 0,
						];
						$_family['Id'] = $family_id = $_ci->family_model->create( $_family );
					}
					// FF_PersonalToFamily
					/*
						FamilyId
						PersonalId
						Relation
					*/
					$_primary_personal_family = [
						'FamilyId' => $family_id,
						'PersonalId' => $_primary_personal['Id'],
						'Relation' => $row['C'],
						'Index' => $row['C'] == 'CHILD' ? 1 : 0,
						'HusbandPersonalId' => $row['H'] == 'HUSBAND' ? $secondary_personal_id : 0,
						'WifePersonalId' => $row['H'] == 'WIFE' ? $secondary_personal_id : 0,
						'Status' => 1,
						'CreatedAt' => time(),
						'CreatedBy' => self::$user_auth->User_ID,
						'UpdatedAt' => NULL,
						'UpdatedBy' => 0,
						'DeletedAt' => NULL,
						'DeletedBy' => 0,
					];
					$_ci->personal_to_family_model->create( $_primary_personal_family);
					
					if( !empty($row['G']) && $row['G'] != $_secondary_personal_before && $row['B'] != $row['G'] ){ // Jika Terdapat data Pendamping
						$_secondary_personal_family = [
							'FamilyId' => $family_id,
							'PersonalId' => $_secondary_personal['Id'],
							'Relation' => $row['H'],
							'Index' => $row['H'] == 'CHILD' ? 1 : 0,
							'HusbandPersonalId' => $row['C'] == 'HUSBAND' ? $_primary_personal['Id'] : 0,
							'WifePersonalId' => $row['C'] == 'WIFE' ? $_primary_personal['Id'] : 0,
							'Status' => 1,
							'CreatedAt' => time(),
							'CreatedBy' => self::$user_auth->User_ID,
							'UpdatedAt' => NULL,
							'UpdatedBy' => 0,
							'DeletedAt' => NULL,
							'DeletedBy' => 0,
						];
						$_ci->personal_to_family_model->create( $_secondary_personal_family);
					}
					
					// mPasien
					/*
						NRM
						NamaPasien
						JenisKerjasamaID
						NRMLama
					*/
					if( !empty($row['A']) && ! empty($row['B']) ):					
						$_patient = [
							'NRM' => $NRM,
							'NamaPasien' => $row['B'],
							'JenisKelamin' => empty($row['D']) ? 'L' : $row['D'],
							'TglLahir' => empty($row['F']) ? '1970-01-01' : date('Y-m-d', strtotime($row['F'])),
							'TglLahirDiketahui' => empty($row['F']) ? 0 : 1,
							'UmurSaatInput' => empty($row['E']) ? 0 : (int) $row['E'],
							'Alamat' => $row['I'],
							'Pekerjaan' => $row['K'],
							'Phone' => $row['J'],
							'JenisPasien' => 'UMUM',
							'JenisKerjasamaID' => 3,
							'NationalityID' => 'INA',
							'PropinsiID' => 1,
							'PasienKTP' => 1,
							'TglInput' => date('Y-m-d'),
							'UserID' => self::$user_auth->User_ID,
							'DokterID_ReferensiPertama' => $row['L'],
							'PenanggungNama' => ($row['B'] == $row['G'] || empty($row['G']) ) ? $row['B'] : $row['G'],
							'PenanggungAlamat' => $row['I'],
							'PenanggungPhone' => $row['J'],
							'PenanggungHubungan' => ($row['B'] == $row['G'] || empty($row['G']) ) ? 'Pasien Sendiri' : 'Lainnya',
							'PenanggungPekerjaan' => $row['K'],
							'Aktif' => 1,
							'PersonalId' => $_primary_personal['Id'],
							'NRMLama' => $row['A'],
						];
						$_ci->patient_model->create( $_patient );
						
						$NRM = 1 . str_replace('.', '', $NRM);
						$NRM = ++$NRM;
						$NRM = substr($NRM, 1); 
						$arrayNRM = @str_split( $NRM, 2 );
						$NRM = @implode( '.', $arrayNRM );
						
						$_secondary_personal_before = $row['G'];
					endif;
					
				}
				
				$import_log = [
					'FileName' => $do_upload['upload_data']['file_name'],
					'ImportType' => __FUNCTION__,
					'Collection' => json_encode( $getCollection ),
					'CreatedAt' => date('Y-m-d H:i:s'),
					'CreatedBy' => self::$user_auth->User_ID
				];
				$_ci->import_model->create( $import_log );				
			
			if($_ci->db->trans_status() === FALSE)
			{
				$_ci->db->trans_rollback();
				return ["status" => 'error', "message" => lang('global:created_failed')];
			}
			$_ci->db->trans_commit();
			return ["status" => 'success', "message" => lang('global:created_successfully')];
			
		} else {
			return $do_upload;
		}
	}
	
	public static function preview_service()
	{
		self::init();
		$_ci = self::ci();
		$_ci->load->model([
			'Service_model',
		]);

		$do_upload = self::do_upload_import_file('service');
	
		if( $do_upload['status'] == 'success')
		{
			$inputFileType = ucfirst(str_replace('.', '', $do_upload['upload_data']['file_ext']));
			$inputFileName = realpath(FCPATH . '../../assets/import/'.$do_upload['upload_data']['file_name']);

			$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
						
			$spreadsheet = $reader->load($inputFileName);
			$activeSheet = $spreadsheet->getActiveSheet();
			$highestCell = $activeSheet->getHighestColumn() . $activeSheet->getHighestRow();
			$getCollection = $activeSheet->rangeToArray(
								"A2:{$highestCell}",     // The worksheet range that we want to retrieve
								NULL,        // Value that should be returned for empty cells
								TRUE,        // Should formulas be calculated (the equivalent of getCalculatedValue() for each cell)
								TRUE,        // Should values be formatted (the equivalent of getFormattedValue() for each cell)
								TRUE         // Should the array be indexed by cell row and cell column
							);
			$get_lastest_service_id = @$_ci->db->select('MAX(JasaID) as MAX')->get($_ci->Service_model->table)->row()->MAX;
			$service_id = empty($get_lastest_service_id) ? 'JAS0001' : ++$get_lastest_service_id;
			$service = [];
			foreach($getCollection as $key => $row):			
				if(empty($row['A']) && empty($row['B'])):
					end($service);
					$jasa_key = key($service);
					$service[$jasa_key]['Komponen'][] = [
						'NamaKOmponen' => $row['J'],
						'HargaKomponen' => $row['K']
					];
				else:
				
					$service[] = [
						'JenisKerjasamaID' => $row['A'],
						'PasienKTP' => $row['B'],
						'JasaID' => $service_id++,
						'NamaJasa' => $row['D'],
						'KelasID' => !empty($row['E']) ? $row['E'] : 'XX',
						'Kategori' => $row['F'],
						'Cyto' => 0,
						'Harga' => $row['H'],
						'Komponen' => [
							[
								'NamaKOmponen' => $row['J'],
								'HargaKomponen' => $row['K']
							]
						]
					];
				endif;
			endforeach;
			
			print_r($service);exit;
		} else {
			response_json($do_upload);
		}
	}
	
	public static function process_service()
	{
		self::init();
		$_ci = self::ci();
		$_ci->load->model([
			'Service_model',
			'Service_category_model',
			'Service_group_model',
			'Service_component_model',
			'services/Bhp_model',
			'services/Price_detail_model',
			'services/Price_model',
			'services/Service_section_model',
			'services/Service_test_model',
		]);
		
		$do_upload = self::do_upload_import_file('service');
	
		if( $do_upload['status'] == 'success')
		{
			$inputFileType = ucfirst(str_replace('.', '', $do_upload['upload_data']['file_ext']));
			$inputFileName = realpath(FCPATH . '../../assets/import/'.$do_upload['upload_data']['file_name']);

			$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
						
			$spreadsheet = $reader->load($inputFileName);
			$activeSheet = $spreadsheet->getActiveSheet();
			$highestCell = $activeSheet->getHighestColumn() . $activeSheet->getHighestRow();
			$getCollection = $activeSheet->rangeToArray(
								"A2:{$highestCell}",     // The worksheet range that we want to retrieve
								NULL,        // Value that should be returned for empty cells
								TRUE,        // Should formulas be calculated (the equivalent of getCalculatedValue() for each cell)
								TRUE,        // Should values be formatted (the equivalent of getFormattedValue() for each cell)
								TRUE         // Should the array be indexed by cell row and cell column
							);
							
			$_ci->db->trans_begin();			
				foreach($_ci->Service_category_model->get_all() as $row)
				{
					$getCategory[$row->KategoriJasaName] = $row->KategoriJasaID;
				}
				
				foreach($_ci->Service_group_model->get_all() as $row)
				{
					$getGroup[$row->GroupJasaName] = $row->GroupJasaID;
				}
				
				foreach($_ci->Service_component_model->get_all() as $row)
				{
					$getComponent[$row->KomponenName] = $row->KomponenBiayaID;
				}
				
				$type_patient = ['UMUM' => 3, 'EXECUTIVE' => 4];
				$get_lastest_service_id = @$_ci->db->select('MAX(JasaID) as MAX')->get($_ci->Service_model->table)->row()->MAX;
				$service_id = empty($get_lastest_service_id) ? 'JAS0001' : ++$get_lastest_service_id;
				
				$service = [];
				foreach($getCollection as $key => $row):			
					if(empty($row['A']) && empty($row['B'])):
						end($service);
						$jasa_key = key($service);
						$service[$jasa_key]['Komponen'][] = [
							'NamaKOmponen' => $row['J'],
							'IDKomponen' => $getComponent[$row['J']],
							'HargaKomponen' => $row['K']
						];
					else :
					
						$service[] = [
							'JenisKerjasamaID' => $type_patient[$row['A']],
							'PasienKTP' => $row['B'],
							'JasaID' => $service_id++,
							'NamaJasa' => $row['D'],
							'KelasID' => !empty($row['E']) ? $row['E'] : 'XX',
							'Group' => $getGroup[$row['F']],
							'Kategori' => $getCategory[$row['F']],
							'Cyto' => 0,
							'Harga' => $row['H'],
							'Komponen' => [
								[
									'NamaKOmponen' => $row['J'],
									'IDKomponen' => $getComponent[$row['J']],
									'HargaKomponen' => $row['K']
								]
							]
						];
					endif;
				endforeach;
				
				foreach($service as $row):
					$insert_service = [
						'JasaID' => $row['JasaID'],
						'JasaIDBPJS' => NULL,
						'JasaName' => $row['NamaJasa'],
						'JasaNameEnglish' => $row['NamaJasa'],
						'KategoriJasaID' => $row['Kategori'],
						'GroupJasaID' => $row['Group'],
						'KelompokPostingan' => 'GROUP JASA',
						'Var_KategoriOperasi' => 0, 
						'Var_Cito' => 0, 
						'PoliKlinik' => 'NONE', 
						'WithDokter' => 0,
						'Aktif' => 1,
					];
					$_ci->Service_model->create($insert_service);
					
					$insert_price = [
						'JasaID' => $row['JasaID'],
						'KelasID' => $row['KelasID'],
						'JenisPasienID' => $row['JenisKerjasamaID'],
						'PasienKTP' => $row['PasienKTP'],
						'KategoriOperasiID' => 1,
						'DokterID' => 'XX',
						'Harga_Lama' => $row['Harga'],
						'Harga_Baru' => $row['Harga'],
						'HargaHC_Lama' => 0,
						'HargaHC_Baru' => 0,
						'TglHargaBaru' => date('Y-m-d H:i:s'),
						'SpesialisID' => 99,
						'Cyto' => 0,
						'Lokasi' => 'RJ',
						'DiscHCUmum' => 0,
						'SubSpesialis' => 0,
						'HargaBPJS' => 0,
						'HargaBPJS_Lama' => 0,
						'TglHargaBaruBPJS' => date('Y-m-d H:i:s'),
						'InsentifKomponen' => 0,
						'InsentifDetail' => 0,
					];
					$insert_price_id = $_ci->Price_model->create( $insert_price );
					
					foreach( $row['Komponen'] as $com ):
						$com = (object)$com;
						$insert_price_detail = [
							'ListHargaID' => $insert_price_id,
							'KomponenBiayaID' => $com->IDKomponen,
							'Qty' => 1,
							'HargaLama' => $com->HargaKomponen,
							'HargaBaru' => $com->HargaKomponen,
							'HargaAwal' => $com->HargaKomponen,
							'HargaAwalLama' => $com->HargaKomponen,
							'HargaHCLama' => 0,
							'HargaHCBaru' => 0,
							'PersenInsentifHC' => 0,
							'HargaBPJS' => 0,
							'HargaBPJS_Lama' => 0,
							'IncludeInsentif' => 0,
							'PersenInsentif' => 0,
							'PersenPajakTitipan' => 0,
							'AkunNo' => NULL,
							'AkunNoLawan' => NULL,
						];
						if(empty($com->HargaKomponen)){
							print_r($row);
							print_r($insert_price_detail);exit;
						}
						$_ci->Price_detail_model->create( $insert_price_detail );							
					endforeach;
					
					$insert_section = [
						'JasaID' => $row['JasaID'],
						'SectionID' => 'SEC005',
					];					
					$_ci->Service_section_model->create( $insert_section );
					
				endforeach;
			
				$import_log = [
					'FileName' => $do_upload['upload_data']['file_name'],
					'ImportType' => __FUNCTION__,
					'Collection' => json_encode( $service ),
					'CreatedAt' => date('Y-m-d H:i:s'),
					'CreatedBy' => self::$user_auth->User_ID
				];
				$_ci->import_model->create( $import_log );				
			
			if($_ci->db->trans_status() === FALSE)
			{
				$_ci->db->trans_rollback();
				return ["status" => 'error', "message" => lang('global:created_failed')];
			}
			//$_ci->db->trans_rollback();
			$_ci->db->trans_commit();
			return ["status" => 'success', "message" => lang('global:created_successfully')];
			
		} else {
			response_json($do_upload);
		}
	}
	
	public static function preview_icd()
	{
		self::init();
		$_ci = self::ci();

		$do_upload = self::do_upload_import_file('icd');
		
		$fileName = realpath(FCPATH . '../../assets/import/'.$do_upload['upload_data']['file_name']);
		foreach(@explode("\n",@file_get_contents($fileName))as $row)
		{
			print_r($row);
			echo "<br/>";
		}
	}
	
	public static function process_icd()
	{
		self::init();
		$_ci = self::ci();

		$do_upload = self::do_upload_import_file('icd');
		
		if( $do_upload['status'] == 'success')
		{
			$_ci->db->trans_begin();			
			
				$fileName = realpath(FCPATH . '../../assets/import/'.$do_upload['upload_data']['file_name']);
				$icd = [];
				foreach(@explode("\n", @file_get_contents($fileName))as $row)
				{
					if( empty($row)){ continue;}					
					$data = explode(' ', trim($row));
					$code = trim($data[0]); 
					unset($data[0]);
					$description = ltrim(implode(' ', $data));
					
					$icd[] = [
						'KodeICD' => $code,
						'Descriptions' => $description,
					];
				}
				
				if( !empty($icd) )
				{
					$_ci->icd_model->mass_create( $icd );
				}
				
				$import_log = [
					'FileName' => $do_upload['upload_data']['file_name'],
					'ImportType' => __FUNCTION__,
					'Collection' => json_encode( $icd ),
					'CreatedAt' => date('Y-m-d H:i:s'),
					'CreatedBy' => self::$user_auth->User_ID
				];
				$_ci->import_model->create( $import_log );
			
			if($_ci->db->trans_status() === FALSE)
			{
				$_ci->db->trans_rollback();
				return ["status" => 'error', "message" => lang('global:created_failed')];
			}
			$_ci->db->trans_commit();
			return ["status" => 'success', "message" => lang('global:created_successfully')];
			
		} else {
			return $do_upload;
		}
	}

	public static function preview_item()
	{
		self::init();
		$_ci = self::ci();

		$do_upload = self::do_upload_import_file('item');
		
		$inputFileType = ucfirst(str_replace('.', '', $do_upload['upload_data']['file_ext']));
		$inputFileName = realpath(FCPATH . '../../assets/import/'.$do_upload['upload_data']['file_name']);

		$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
					
		$spreadsheet = $reader->load($inputFileName);
		$activeSheet = $spreadsheet->getActiveSheet();
		$highestCell = $activeSheet->getHighestColumn() . $activeSheet->getHighestRow();
		$getCollection = $activeSheet->rangeToArray(
							"A2:{$highestCell}",     // The worksheet range that we want to retrieve
							NULL,        // Value that should be returned for empty cells
							TRUE,        // Should formulas be calculated (the equivalent of getCalculatedValue() for each cell)
							TRUE,        // Should values be formatted (the equivalent of getFormattedValue() for each cell)
							TRUE         // Should the array be indexed by cell row and cell column
						);
		
		foreach($getCollection as $row)
		{
			print_r($row);
			echo '<br/>';
		}
		exit;
	}
	
	public static function process_item()
	{
		self::init();
		$_ci = self::ci();

		$do_upload = self::do_upload_import_file('item');
		
		if( $do_upload['status'] == 'success')
		{
			$_ci->db->trans_begin();			
			
				$inputFileType = ucfirst(str_replace('.', '', $do_upload['upload_data']['file_ext']));
				$inputFileName = realpath(FCPATH . '../../assets/import/'.$do_upload['upload_data']['file_name']);
		
				$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
							
				$spreadsheet = $reader->load($inputFileName);
				$activeSheet = $spreadsheet->getActiveSheet();
				$highestCell = $activeSheet->getHighestColumn() . $activeSheet->getHighestRow();
				$getCollection = $activeSheet->rangeToArray(
									"A2:{$highestCell}",     // The worksheet range that we want to retrieve
									NULL,        // Value that should be returned for empty cells
									TRUE,        // Should formulas be calculated (the equivalent of getCalculatedValue() for each cell)
									TRUE,        // Should values be formatted (the equivalent of getFormattedValue() for each cell)
									TRUE         // Should the array be indexed by cell row and cell column
								);
							
				foreach($_ci->item_category_model->get_all() as $row)
				{
					$category[$row->Nama_Kategori] = $row->Kategori_ID;
				}
				foreach($_ci->item_subcategory_model->get_all() as $row)
				{
					$subcategory[$row->Nama_Sub_Kategori] = $row->SubKategori_ID;
				}
				foreach($_ci->item_unit_model->get_all() as $row)
				{
					$unit[$row->Nama_Satuan] = $row->Satuan_ID;
				}
				foreach($_ci->item_class_model->get_all() as $row)
				{
					$class[$row->Nama_Kelas] = $row->Kelas_ID;
				}
				$item_log = [];
				foreach($getCollection as $row)
				{
					if( empty($row['B']) )
					{
						continue;
					}
					$item = [
						'Kelas_ID' => @$class[$row['E']],
						'Lokasi_ID' => 1426,
						'Kode_Barang' => $row['A'],
						'Nama_Barang' => $row['B'],
						'Nama_Internasional' => $row['B'],
						'Metode_Inventori' => 'A',
						'PPn' => 0,
						'Keterangan' => '-',
						'Harga_Beli' => $row['L'],
						'Harga_Jual' => $row['M'],
						'Harga_Jual_Lama' => $row['M'],
						'TglBerlaku_Harga' => date('Y-m-d'),
						'Stok_Satuan_ID' => $unit[$row['I']],
						'Konversi' => $row['J'],
						'Aktif' => 1,
						'Beli' => 0,
						'Jual' => 0,
						'Inventory' => 0,
						'Beli_IncludeTax' => 1,
						'Beli_Satuan_Id' => $unit[$row['G']],
						'Jual_IncludeTax' => 0,
						'Jual_Satuan_Id' => $unit[$row['I']],
						'Kategori_Id' => @$category[$row['D']],
						'SubKategori_Id' => @$subcategory[$row['G']],
						'Kelompok' => 'OBAT',
						'GolonganID' => 2,
						'UserID' => self::$user_auth->User_ID,
						'DateUpdate' => date('Y-m-d'),
						'KelompokJenis' => 'ALL',
						'HRataRata' => 0,
						'HRataRata_Lama' => 0,
						'HExt' => 0,
						'Paket' => 0,
						'KelompokGrading' => $row['F'],
						'PPn_Persen' => 0,
						'Supplier_ID' => NULL,
						'Formularium' => 0,
						'Isi' => 0,
					];
					
					$item_id = $_ci->item_model->create( $item );
					
					$item_location = [
						[
							'Lokasi_ID' => 1366,
							'Barang_ID' => $item_id,
							'JenisBarangID' => 0,
							'Qty_Stok' => 0,
							'Min_Stok' => 1,
							'Max_Stok' => 10,
							'Aktif' => 1,
							'Kode_Satuan' => $row['H']
						],
						[
							'Lokasi_ID' => 296,
							'Barang_ID' => $item_id,
							'JenisBarangID' => 0,
							'Qty_Stok' => 0,
							'Min_Stok' => 1,
							'Max_Stok' => 10,
							'Aktif' => 1,
							'Kode_Satuan' => $row['H']
						],
					];
					
					$_ci->item_location_model->mass_create( $item_location );
					$item_log[] = $row;
				}
				
				$import_log = [
					'FileName' => $do_upload['upload_data']['file_name'],
					'ImportType' => __FUNCTION__,
					'Collection' => json_encode( $item_log ),
					'CreatedAt' => date('Y-m-d H:i:s'),
					'CreatedBy' => self::$user_auth->User_ID
				];
				$_ci->import_model->create( $import_log );

			if($_ci->db->trans_status() === FALSE)
			{
				$_ci->db->trans_rollback();
				return ["status" => 'error', "message" => lang('global:created_failed')];
			}
			//$_ci->db->trans_rollback();
			$_ci->db->trans_commit();
			return ["status" => 'success', "message" => lang('global:created_successfully')];
			
		} else {
			return $do_upload;
		}
	}		

	
	public static function preview_item_category()
	{
		self::init();
		$_ci = self::ci();

		$do_upload = self::do_upload_import_file('item_category');
		
		$inputFileType = ucfirst(str_replace('.', '', $do_upload['upload_data']['file_ext']));
		$inputFileName = realpath(FCPATH . '../../assets/import/'.$do_upload['upload_data']['file_name']);

		$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
					
		$spreadsheet = $reader->load($inputFileName);
		$activeSheet = $spreadsheet->getActiveSheet();
		$highestCell = $activeSheet->getHighestColumn() . $activeSheet->getHighestRow();
		$getCollection = $activeSheet->rangeToArray(
							"A2:{$highestCell}",     // The worksheet range that we want to retrieve
							NULL,        // Value that should be returned for empty cells
							TRUE,        // Should formulas be calculated (the equivalent of getCalculatedValue() for each cell)
							TRUE,        // Should values be formatted (the equivalent of getFormattedValue() for each cell)
							TRUE         // Should the array be indexed by cell row and cell column
						);
		
		foreach($getCollection as $row)
		{
			print_r($row);
			echo '<br/>';
		}
		exit;
	}
	
	public static function process_item_category()
	{
		self::init();
		$_ci = self::ci();

		$do_upload = self::do_upload_import_file('item_category');
		
		if( $do_upload['status'] == 'success')
		{
			$_ci->db->trans_begin();			
			
				$inputFileType = ucfirst(str_replace('.', '', $do_upload['upload_data']['file_ext']));
				$inputFileName = realpath(FCPATH . '../../assets/import/'.$do_upload['upload_data']['file_name']);
		
				$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
							
				$spreadsheet = $reader->load($inputFileName);
				$activeSheet = $spreadsheet->getActiveSheet();
				$highestCell = $activeSheet->getHighestColumn() . $activeSheet->getHighestRow();
				$getCollection = $activeSheet->rangeToArray(
									"A2:{$highestCell}",     // The worksheet range that we want to retrieve
									NULL,        // Value that should be returned for empty cells
									TRUE,        // Should formulas be calculated (the equivalent of getCalculatedValue() for each cell)
									TRUE,        // Should values be formatted (the equivalent of getFormattedValue() for each cell)
									TRUE         // Should the array be indexed by cell row and cell column
								);
				
				foreach($getCollection as $row)
				{
					if( empty($row['A']) )
					{
						continue;
					}
					$item_category[] = [
						'Kode_Kategori' => $row['A'],
						'Nama_Kategori' => $row['B'],
						'Kelompok' => $row['C']
					];
				}
				
				if( !empty($item_category) )
				{
					$_ci->item_category_model->mass_create( $item_category );
				}
				
				$import_log = [
					'FileName' => $do_upload['upload_data']['file_name'],
					'ImportType' => __FUNCTION__,
					'Collection' => json_encode( $item_category ),
					'CreatedAt' => date('Y-m-d H:i:s'),
					'CreatedBy' => self::$user_auth->User_ID
				];
				$_ci->import_model->create( $import_log );
			
			if($_ci->db->trans_status() === FALSE)
			{
				$_ci->db->trans_rollback();
				return ["status" => 'error', "message" => lang('global:created_failed')];
			}

			$_ci->db->trans_commit();
			return ["status" => 'success', "message" => lang('global:created_successfully')];
			
		} else {
			return $do_upload;
		}
	}

	public static function preview_item_subcategory()
	{
		self::init();
		$_ci = self::ci();

		$do_upload = self::do_upload_import_file('item_subcategory');
		
		$inputFileType = ucfirst(str_replace('.', '', $do_upload['upload_data']['file_ext']));
		$inputFileName = realpath(FCPATH . '../../assets/import/'.$do_upload['upload_data']['file_name']);

		$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
					
		$spreadsheet = $reader->load($inputFileName);
		$activeSheet = $spreadsheet->getActiveSheet();
		$highestCell = $activeSheet->getHighestColumn() . $activeSheet->getHighestRow();
		$getCollection = $activeSheet->rangeToArray(
							"A2:{$highestCell}",     // The worksheet range that we want to retrieve
							NULL,        // Value that should be returned for empty cells
							TRUE,        // Should formulas be calculated (the equivalent of getCalculatedValue() for each cell)
							TRUE,        // Should values be formatted (the equivalent of getFormattedValue() for each cell)
							TRUE         // Should the array be indexed by cell row and cell column
						);
		
		foreach($getCollection as $row)
		{
			print_r($row);
			echo '<br/>';
		}
		exit;
	}
	
	public static function process_item_subcategory()
	{
		self::init();
		$_ci = self::ci();

		$do_upload = self::do_upload_import_file('item_subcategory');
		
		if( $do_upload['status'] == 'success')
		{
			$_ci->db->trans_begin();			
			
				$inputFileType = ucfirst(str_replace('.', '', $do_upload['upload_data']['file_ext']));
				$inputFileName = realpath(FCPATH . '../../assets/import/'.$do_upload['upload_data']['file_name']);
		
				$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
							
				$spreadsheet = $reader->load($inputFileName);
				$activeSheet = $spreadsheet->getActiveSheet();
				$highestCell = $activeSheet->getHighestColumn() . $activeSheet->getHighestRow();
				$getCollection = $activeSheet->rangeToArray(
									"A2:{$highestCell}",     // The worksheet range that we want to retrieve
									NULL,        // Value that should be returned for empty cells
									TRUE,        // Should formulas be calculated (the equivalent of getCalculatedValue() for each cell)
									TRUE,        // Should values be formatted (the equivalent of getFormattedValue() for each cell)
									TRUE         // Should the array be indexed by cell row and cell column
								);
				
				foreach($getCollection as $row)
				{
					if( empty($row['A']) )
					{
						continue;
					}

					$item_subcategory[] = [
						'Kategori_ID' => 0,
						'Kode_Sub_Kategori' => $row['A'],
						'Nama_Sub_Kategori' => $row['B']
					];
				}
				
				if( !empty($item_subcategory) )
				{
					$_ci->item_subcategory_model->mass_create( $item_subcategory );
				}
				
				$import_log = [
					'FileName' => $do_upload['upload_data']['file_name'],
					'ImportType' => __FUNCTION__,
					'Collection' => json_encode( $item_subcategory ),
					'CreatedAt' => date('Y-m-d H:i:s'),
					'CreatedBy' => self::$user_auth->User_ID
				];
				$_ci->import_model->create( $import_log );
			
			if($_ci->db->trans_status() === FALSE)
			{
				$_ci->db->trans_rollback();
				return ["status" => 'error', "message" => lang('global:created_failed')];
			}

			$_ci->db->trans_commit();
			return ["status" => 'success', "message" => lang('global:created_successfully')];
			
		} else {
			return $do_upload;
		}
	}
	
	public static function preview_item_unit()
	{
		self::init();
		$_ci = self::ci();

		$do_upload = self::do_upload_import_file('item_unit');
		
		$inputFileType = ucfirst(str_replace('.', '', $do_upload['upload_data']['file_ext']));
		$inputFileName = realpath(FCPATH . '../../assets/import/'.$do_upload['upload_data']['file_name']);

		$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
					
		$spreadsheet = $reader->load($inputFileName);
		$activeSheet = $spreadsheet->getActiveSheet();
		$highestCell = $activeSheet->getHighestColumn() . $activeSheet->getHighestRow();
		$getCollection = $activeSheet->rangeToArray(
							"A2:{$highestCell}",     // The worksheet range that we want to retrieve
							NULL,        // Value that should be returned for empty cells
							TRUE,        // Should formulas be calculated (the equivalent of getCalculatedValue() for each cell)
							TRUE,        // Should values be formatted (the equivalent of getFormattedValue() for each cell)
							TRUE         // Should the array be indexed by cell row and cell column
						);
		
		foreach($getCollection as $row)
		{
			print_r($row);
			echo '<br/>';
		}
		exit;
	}
	
	public static function process_item_unit()
	{
		self::init();
		$_ci = self::ci();

		$do_upload = self::do_upload_import_file('item_unit');
		
		if( $do_upload['status'] == 'success')
		{
			$_ci->db->trans_begin();			
			
				$inputFileType = ucfirst(str_replace('.', '', $do_upload['upload_data']['file_ext']));
				$inputFileName = realpath(FCPATH . '../../assets/import/'.$do_upload['upload_data']['file_name']);
		
				$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
							
				$spreadsheet = $reader->load($inputFileName);
				$activeSheet = $spreadsheet->getActiveSheet();
				$highestCell = $activeSheet->getHighestColumn() . $activeSheet->getHighestRow();
				$getCollection = $activeSheet->rangeToArray(
									"A2:{$highestCell}",     // The worksheet range that we want to retrieve
									NULL,        // Value that should be returned for empty cells
									TRUE,        // Should formulas be calculated (the equivalent of getCalculatedValue() for each cell)
									TRUE,        // Should values be formatted (the equivalent of getFormattedValue() for each cell)
									TRUE         // Should the array be indexed by cell row and cell column
								);
				
				foreach($getCollection as $row)
				{
					if( empty($row['A']) )
					{
						continue;
					}
					$item_unit[] = [
						'Kode_Satuan' => $row['A'],
						'Nama_Satuan' => $row['B']
					];
				}
				
				if( !empty($item_unit) )
				{
					$_ci->item_unit_model->mass_create( $item_unit );
				}
				
				$import_log = [
					'FileName' => $do_upload['upload_data']['file_name'],
					'ImportType' => __FUNCTION__,
					'Collection' => json_encode( $item_unit ),
					'CreatedAt' => date('Y-m-d H:i:s'),
					'CreatedBy' => self::$user_auth->User_ID
				];
				$_ci->import_model->create( $import_log );
			
			if($_ci->db->trans_status() === FALSE)
			{
				$_ci->db->trans_rollback();
				return ["status" => 'error', "message" => lang('global:created_failed')];
			}

			$_ci->db->trans_commit();
			return ["status" => 'success', "message" => lang('global:created_successfully')];
			
		} else {
			return $do_upload;
		}
	}

	public static function preview_item_class()
	{
		self::init();
		$_ci = self::ci();

		$do_upload = self::do_upload_import_file('item_class');
		
		$inputFileType = ucfirst(str_replace('.', '', $do_upload['upload_data']['file_ext']));
		$inputFileName = realpath(FCPATH . '../../assets/import/'.$do_upload['upload_data']['file_name']);

		$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
					
		$spreadsheet = $reader->load($inputFileName);
		$activeSheet = $spreadsheet->getActiveSheet();
		$highestCell = $activeSheet->getHighestColumn() . $activeSheet->getHighestRow();
		$getCollection = $activeSheet->rangeToArray(
							"A2:{$highestCell}",     // The worksheet range that we want to retrieve
							NULL,        // Value that should be returned for empty cells
							TRUE,        // Should formulas be calculated (the equivalent of getCalculatedValue() for each cell)
							TRUE,        // Should values be formatted (the equivalent of getFormattedValue() for each cell)
							TRUE         // Should the array be indexed by cell row and cell column
						);
		
		foreach($getCollection as $row)
		{
			print_r($row);
			echo '<br/>';
		}
		exit;
	}
	
	public static function process_item_class()
	{
		self::init();
		$_ci = self::ci();

		$do_upload = self::do_upload_import_file('item_class');
		
		if( $do_upload['status'] == 'success')
		{
			$_ci->db->trans_begin();			
			
				$inputFileType = ucfirst(str_replace('.', '', $do_upload['upload_data']['file_ext']));
				$inputFileName = realpath(FCPATH . '../../assets/import/'.$do_upload['upload_data']['file_name']);
		
				$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
							
				$spreadsheet = $reader->load($inputFileName);
				$activeSheet = $spreadsheet->getActiveSheet();
				$highestCell = $activeSheet->getHighestColumn() . $activeSheet->getHighestRow();
				$getCollection = $activeSheet->rangeToArray(
									"A2:{$highestCell}",     // The worksheet range that we want to retrieve
									NULL,        // Value that should be returned for empty cells
									TRUE,        // Should formulas be calculated (the equivalent of getCalculatedValue() for each cell)
									TRUE,        // Should values be formatted (the equivalent of getFormattedValue() for each cell)
									TRUE         // Should the array be indexed by cell row and cell column
								);
				
				foreach($getCollection as $row)
				{
					if( empty($row['A']) )
					{
						continue;
					}
					$item_class[] = [
						'SubKategori_ID' => 0,
						'Kode_Kelas' => $row['A'],
						'Nama_Kelas' => $row['B']
					];
				}
				
				if( !empty($item_class) )
				{
					$_ci->item_class_model->mass_create( $item_class );
				}
				
				$import_log = [
					'FileName' => $do_upload['upload_data']['file_name'],
					'ImportType' => __FUNCTION__,
					'Collection' => json_encode( $item_class ),
					'CreatedAt' => date('Y-m-d H:i:s'),
					'CreatedBy' => self::$user_auth->User_ID
				];
				$_ci->import_model->create( $import_log );
			
			if($_ci->db->trans_status() === FALSE)
			{
				$_ci->db->trans_rollback();
				return ["status" => 'error', "message" => lang('global:created_failed')];
			}

			$_ci->db->trans_commit();
			return ["status" => 'success', "message" => lang('global:created_successfully')];
			
		} else {
			return $do_upload;
		}
	}		
	public static function do_upload_import_file( $import_type )
	{
		$_ci = self::ci();	
		
		$config['upload_path'] = realpath(FCPATH . '../../assets/import');
		$config['allowed_types'] =  'xlsx|csv|xls|txt|xml';
		$config['max_size'] = 0;
		$config['remove_spaces'] = TRUE;
		$config['encrypt_name'] = TRUE;
		$config['overwrite'] = TRUE;
		
		if( $import_type )
		{	
			$config['encrypt_name'] = FALSE;
			$config['file_name'] = 'import-'.$import_type.date('dmY').'-'.$_FILES['file']['name'];
		}

		$_ci->load->library('upload', $config);

		return ( ! $_ci->upload->do_upload('file') )
			? ['status'=>'error', 'message' => $_ci->upload->display_errors()]
			: ['status'=>'success', 'upload_data' => $_ci->upload->data()];

	}

	private static function & ci()
	{
		return get_instance();
	}	

}