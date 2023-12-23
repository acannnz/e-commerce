<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Patients extends Admin_Controller
{
	protected $_translation = 'common';	
	protected $_model = 'patient_m';
	
	public function __construct()
	{
		parent::__construct();
		$this->simple_login->check_user_role('patients');
		
		$this->load->model( "patient_m" );
		$this->load->model( "patient_type_m" );
		$this->load->model( "zone_m" );
		$this->load->model( "nationality_m" );
		$this->load->model( "regional_m" );
		$this->load->model( "vw_regional_m" );
		
		$this->load->model( "section_group_model" );
		$this->load->model( "patient_nrm_model" );
		$this->load->model( "patient_type_model" );
		$this->load->model( "registrations/cooperation_member_model" );
		
		$this->load->helper( "patient" );
		
		$this->page = "common_patients";
		$this->template->title( lang( "patients:page" ) . ' - ' . $this->config->item('company_name') );
	}
	
	public function index()
	{
		$data = array(
				'page' => $this->page,
				'datatables' => TRUE,
				'form' => TRUE,
				"fileinput" => TRUE,
				'navigation_minimized' => TRUE,
			);
		
		$this->template
			->set( "heading", lang( "patients:page" ) )
			->set_breadcrumb( lang("common:page"), base_url("common") )
			->set_breadcrumb( lang("patients:breadcrumb") )
			->build('patients/datatable', (isset($data) ? $data : NULL));
	}
	
	public function create()
	{
		$item = (object) array(
			"NRM" => patient_helper::gen_mr_number(),
			"NamaPasien" => NULL,
			"NoIdentitas" => NULL,
			"JenisKelamin" => "F",
			"TglLahir" => date('Y-m-d'),
			"TglLahirDiketahui" => 1,
			"Alamat" => NULL,
			"Phone" => NULL,
			"Email" => NULL,
			"JenisPasien" => 3,
			"PasienVVIP" => 0,
			"PasienKTP" => 1,
			"TglInput" => date('Y-m-d'),
			"CaraDatangPertama" => '',
			"CompanyID" => NULL,
			"NoKartu" => NULL,
			"Klp" => NULL,
			"JabatanDiPerusahaan" => NULL,
			"JenisKerjasamaID" => 3,
			"UserID" => $this->user_auth->User_ID,
			"DokterID_ReferensiPertama" => NULL,
			"NationalityID" => 'INA',
			"PropinsiID" => 1,
    		"KabupatenID" => NULL,
			"KecamatanID" => NULL,
			"DesaID" => NULL,
			"UmurSaatInput" => 0,
			"KodePos" => NULL,
			"EtnisID" => NULL,
			"Pekerjaan" => NULL,
			"AnggotaBaru" => NULL,
			"CustomerKerjasamaID" => 0,
			"NoKartu" => NULL,
			"NoANggotaE" => NULL,
			"NamaAnggotaE" => NULL,
			"GenderAnggotaE" => NULL,
			"Agama" => 'HD',
			"NamaIbuKandung" => NULL,
			"KdKelas" => 'xx',
			"NonPBI" => NULL,
			"TempatLahir" => NULL,
			"NamaAlias" => NULL,
			"PasienBaru" => 1
		);
				
		if( $this->input->post() ) 
		{
			$item = (object) array_merge( (array) $item, $this->input->post("f") );
			$TipePasien = $this->db->where('JenisKerjasamaID', $item->JenisKerjasamaID)->get('SIMmJenisKerjasama')->row();
			$item->JenisPasien = $TipePasien->JenisKerjasama;

			$message = NULL;
			$validation = TRUE;

			if($item->AnggotaBaru){
				if(empty($item->NoAnggota)){
					response_json( [
						'status' => 'error',
						"message" => 'Nomor Kartu Anggota Kerjasama Belum Terisi',
						"code" => 500
					] );	
				}
				
				if($this->cooperation_member_model->count_all(['NoAnggota' => $item->NoAnggota]))
				{
					response_json( [
						'status' => 'error',
						"message" => "Nomor Kartu {$item->NoAnggota} Sudah Pernah Terdaftar Disistem, tidak dapat menyimpan sebagai Anggota baru",
						"code" => 500
					] );
				}
			}		

			$this->load->library( 'form_validation' );
			$this->form_validation->set_data( (array) $item );
			$this->form_validation->set_rules( $this->get_model()->rules['insert'] );
			if( $this->form_validation->run() && $validation )
			{
				
				if( $this->get_model()->create( $item ) )
				{	
					if($item->AnggotaBaru){
						if($this->cooperation_member_model->count_all(['NoAnggota' => $item->NoAnggota, "NRM" => $item->NRM, "CustomerKerjasamaID" => $item->CustomerKerjasamaID]))
						{
							$cooperation_card = [
								"CustomerKerjasamaID" => $item->CustomerKerjasamaID,
								"NRM" => $item->NRM,
								"Nama" => $item->NamaPasien,
								"Active" => 1,
								"Klp" => NULL,
								"TglLahir" => $item->TglLahir,
								"Alamat" => $item->Alamat,
								"Phone" => $item->Phone,
								"Gender" => $item->JenisKelamin,
							];

							$this->cooperation_member_model->update( $cooperation_card, $item->NoAnggota);	
						} else {
							$cooperation_card = [
								"CustomerKerjasamaID" => $item->CustomerKerjasamaID,
								"NRM" => $item->NRM,
								"NoAnggota" => $item->NoAnggota,
								"Nama" => $item->NamaPasien,
								"Active" => 1,
								"Klp" => NULL,
								"TglLahir" => $item->TglLahir,
								"Alamat" => $item->Alamat,
								"Phone" => $item->Phone,
								"Gender" => $item->JenisKelamin,
							];

							$this->cooperation_member_model->create( $cooperation_card );				
						}
					}			

					$message = [
						"status" => 'success',
						'message' => lang('patients:created_successfully'),
						"code" => 200
					];

				} else
				{
					$message = [
						"status" => 'success',
						'message' => lang('patients:created_failed'),
						"code" => 500
					];

				}
			} else
			{
				$message = [
					"status" => 'error',
					'message' => !empty($message) ? $message : $this->form_validation->get_all_error_string(),
					"code" => 500
				];
			}
			
			response_json( $message );

		}
		
		// dropdown options
		$option_patient_type = $this->patient_m->get_option_patient_type();
		$option_nationality = $this->patient_m->get_option_nationality();
		$option_province = $this->patient_m->get_option_zones( "mPropinsi" );
		$default_province = $this->patient_m->get_default_zones( "mPropinsi", array("Propinsi_Default" => 1) );
		$option_county = $this->patient_m->get_option_zones( "mKabupaten", array("PropinsiID" => $default_province->Propinsi_ID) );
		$option_district = $this->patient_m->get_option_zones( "mKecamatan" );
		$option_village = $this->patient_m->get_option_zones( "mDesa");
		$option_area = $this->patient_m->get_option_zones( "mBanjar");

		if( $this->input->is_ajax_request() )
		{
			$data = array(
					"item" => $item,
					"is_ajax_request" => TRUE,
					"is_modal" => TRUE,
					"datatables" => TRUE,
					"option_patient_type" => $option_patient_type,
					"option_nationality" => $option_nationality,
					"option_province" => $option_province,
					"option_county" => $option_county,
					"option_district" => $option_district,
					"option_village" => $option_village,
					"option_area" => $option_area,
					"lookup_cooperation" => base_url("common/patients/lookup_cooperation"),
					"lookup_patient_cooperation_card" => base_url("common/patients/lookup_patient_cooperation_card"),
				);
			
			$this->load->view( 
					'patients/modal/create_edit', 
					array('form_child' => $this->load->view('patients/form', $data, true))
				);
		} else
		{
			$data = array(
					"page" => $this->page."_".strtolower(__FUNCTION__),
					"item" => $item,
					"form" => TRUE,
					"datatables" => TRUE,
					"fileinput" => TRUE,
					"option_patient_type" => $option_patient_type,
					"option_nationality" => $option_nationality,
					"option_province" => $option_province,
					"option_county" => $option_county,
					"option_district" => $option_district,
					"option_village" => $option_village,
					"option_area" => $option_area,
					"list_provinsi" => array_replace(['' => '-- Pilih --'], $this->regional_m->dropdown_data(['Level_Ke' => 1])),
					"lookup_cooperation" => base_url("common/patients/lookup_cooperation"),
					"lookup_patient_cooperation_card" => base_url("common/patients/lookup_patient_cooperation_card"),
					"regional_lookup" => base_url("common/patients/lookup_regional"),
				);
			
			$this->template
				->set( "heading", lang("patients:create_heading") )
				->set_breadcrumb( lang("common:page"), base_url("common") )
				->set_breadcrumb( lang("patients:breadcrumb"), base_url("common/patients") )
				->set_breadcrumb( lang("patients:create_heading") )
				->build('patients/form', $data);
		}
	}
	

	public function edit( $NRM = NULL )
	{
		if ( empty($NRM) )
		{
			redirect('common/patients');
		}
		
		$item = $this->get_model()->get_patient( $NRM );
		$regional = $this->vw_regional_m->get_by(['DesaId' => @$item->KodeRegional]);
		$this->load->library( 'my_object', (array)$item, 'item' );
				
		if( $this->input->post() ) 
		{
			
			$item = (object) array_merge( (array) $item, $this->input->post("f") );
			$TipePasien = $this->db->where('JenisKerjasamaID', $item->JenisKerjasamaID)->get('SIMmJenisKerjasama')->row();
			$item->JenisPasien = $TipePasien->JenisKerjasama;

			$message = NULL;
			$validation = TRUE;
			

			if($item->AnggotaBaru){
				if(empty($item->NoAnggota)){
					response_json( [
						'status' => 'error',
						"message" => 'Nomor Kartu Anggota Kerjasama Belum Terisi',
						"code" => 500
					] );	
				}
				
				if($this->cooperation_member_model->count_all(['NoAnggota' => $item->NoAnggota]))
				{
					response_json( [
						'status' => 'error',
						"message" => "Nomor Kartu {$item->NoAnggota} Sudah Pernah Terdaftar Disistem, tidak dapat menyimpan sebagai Anggota baru",
						"code" => 500
					] );
				}
			}	

			$this->load->library( 'form_validation' );
			$this->form_validation->set_data( (array) $item );
			$this->form_validation->set_rules( $this->get_model()->rules['modify'] );
			if( $this->form_validation->run() && $validation )
			{
				
				if( $this->get_model()->update( $item, @$NRM ) )
				{				
					if($item->AnggotaBaru){
						if($this->cooperation_member_model->count_all(['NoAnggota' => $item->NoAnggota, "NRM" => $item->NRM, "CustomerKerjasamaID" => $item->CustomerKerjasamaID]))
						{
							$cooperation_card = [
								"CustomerKerjasamaID" => $item->CustomerKerjasamaID,
								"NRM" => $item->NRM,
								"Nama" => $item->NamaPasien,
								"Active" => 1,
								"Klp" => NULL,
								"TglLahir" => $item->TglLahir,
								"Alamat" => $item->Alamat,
								"Phone" => $item->Phone,
								"Gender" => $item->JenisKelamin,
							];

							$this->cooperation_member_model->update( $cooperation_card, $item->NoAnggota);	
						} else {
							$cooperation_card = [
								"CustomerKerjasamaID" => $item->CustomerKerjasamaID,
								"NRM" => $item->NRM,
								"NoAnggota" => $item->NoAnggota,
								"Nama" => $item->NamaPasien,
								"Active" => 1,
								"Klp" => NULL,
								"TglLahir" => $item->TglLahir,
								"Alamat" => $item->Alamat,
								"Phone" => $item->Phone,
								"Gender" => $item->JenisKelamin,
							];

							$this->cooperation_member_model->create( $cooperation_card );				
						}
					}	

					$message = [
						"status" => 'success',
						'message' => lang('patients:created_successfully'),
						"code" => 200
					];

				} else
				{
					$message = [
						"status" => 'success',
						'message' => lang('patients:created_failed'),
						"code" => 500
					];

				}
			} else
			{
				$message = [
					"status" => 'error',
					'message' => !empty($message) ? $message : $this->form_validation->get_all_error_string(),
					"code" => 500
				];
			}
			
			response_json( $message );

		}
				
		// dropdown options
		$option_patient_type = $this->get_model()->get_option_patient_type();
		$option_nationality = $this->get_model()->get_option_nationality();
		$option_province = $this->get_model()->get_option_zones( "mPropinsi" );
		$default_province = $this->get_model()->get_default_zones( "mPropinsi", array("Propinsi_Default" => 1) );
		$option_county = $this->get_model()->get_option_zones( "mKabupaten", array("PropinsiID" => $default_province->Propinsi_ID) );
		$option_district = $this->get_model()->get_option_zones( "mKecamatan" );
		$option_village = $this->get_model()->get_option_zones( "mDesa");
		$option_area = $this->get_model()->get_option_zones( "mBanjar");
		// $cooperation = $this->get_model()->get_customer( array("Kode_Customer" => $item['KodePerusahaan']) );
		// print_r($cooperation);exit;
		if( $this->input->is_ajax_request() )
		{
			$data = array(
					"item" => $item,
					"is_ajax_request" => TRUE,
					"is_modal" => TRUE,
					"datatables" => TRUE,
					"option_patient_type" => $option_patient_type,
					"option_nationality" => $option_nationality,
					"option_province" => $option_province,
					"option_county" => $option_county,
					"option_district" => $option_district,
					"option_village" => $option_village,
					"option_area" => $option_area,
					// "cooperation" => $this->get_model->get_customer( array("Kode_Customer" => $item['KodePerusahaan']) ), // Perusahaan Kerja sama
					"lookup_cooperation" => base_url("common/patients/lookup_cooperation"),
					"lookup_patient_cooperation_card" => base_url("common/patients/lookup_patient_cooperation_card"),
				);
				print_r($data);exit;
			
			$this->load->view( 
					'patients/modal/create_edit', 
					array('form_child' => $this->load->view('patients/form', $data, true))
				);
		} else
		{
			$data = array(
					"page" => $this->page,
					"item" => $item,
					"regional" => $regional,
					"form" => TRUE,
					"fileinput" => TRUE,
					"datatables" => TRUE,
					"option_patient_type" => $option_patient_type,
					"option_nationality" => $option_nationality,
					"option_province" => $option_province,
					"option_county" => $option_county,
					"option_district" => $option_district,
					"option_village" => $option_village,
					"option_area" => $option_area,
					// "cooperation" => $this->get_model->get_customer( array("Kode_Customer" => $item['KodePerusahaan']) ), // Perusahaan Kerja sama
					"lookup_cooperation" => base_url("common/patients/lookup_cooperation"),
					"lookup_patient_cooperation_card" => base_url("common/patients/lookup_patient_cooperation_card"),
					"list_provinsi" => array_replace(['' => '-- Pilih --'], $this->regional_m->dropdown_data(['Level_Ke' => 1])),
					"regional_lookup" => base_url("common/patients/lookup_regional"),
				);
				
			$this->template
				->set( "heading", lang("patients:create_heading") )
				->set_breadcrumb( lang("common:page"), base_url("common") )
				->set_breadcrumb( lang("patients:breadcrumb"), base_url("common/patients") )
				->set_breadcrumb( lang("patients:create_heading") )
				->build('patients/form', $data);
		}
	}
	
	public function delete( $id=0 )
	{
		$id = (int) @$id;
		
		$item = $this->get_model()->as_array()->get( $id );
		if( ! $item ){ $item = array('id' => 0); }
		$this->load->library( 'my_object', $item, 'item' );
		
		if( $this->input->post() ) 
		{
			
			
			if( 0 == @$this->item->id )
			{
				make_flashdata(array(
						'response_status' => 'error',
						'message' => lang( 'global:get_failed' )
					));
			
				redirect( $this->input->post( 'r_url' ) );
			}
			
			if( $this->item->id == $this->input->post( 'confirm' ) )
			{
				// Inject spog delete method
				if( "TRUE" == $this->config->item( "enable_chart_spog" ) )
				{
					$this->load->model( "spog/spog_patient_m" );
					$this->spog_patient_m->delete(array("patient_id" => $this->item->id));
				} // end: Inject spog delete method
				
				$this->get_model()->where( $id )->delete();				
				
				$this->get_model()->delete_cache( 'common_patients.collection' );
				
				make_flashdata(array(
						'response_status' => 'success',
						'message' => lang('patients:deleted_successfully')
					));
			}
			
			redirect( $this->input->post( 'r_url' ) );
		}
		
		$this->load->view( 'patients/modal/delete', array('item' => $this->item) );
	}
	
	public function picture( $id=0 )
	{
		if( 0 == $id ){ $id = $this->input->get_post( "patient_id" ); }
		if( 0 == $id ){ $id = $this->input->get_post( "p_id" ); }
		if( 0 == $id ){ $id = $this->input->get_post( "pid" ); }
		if( 0 == $id ){ $id = $this->input->get_post( "id" ); }
		
		$id = (int) $id;
		
		$this->load->library( 'my_object' );
		$profile_data = (array) patient_helper::get_patient_profile( $id );
		$profile = new My_object( $profile_data );
		$profile_id = (int) @$profile->id;
		
		if( $this->input->is_ajax_request() )
		{
			$data = array(
					"is_ajax_request" => TRUE,
					"profile" => $profile,
					"profile_id" => (int) @$profile->id,
				);
			
			$this->load->view( 
					'patients/modal/picture', 
					array('form_child' => $this->load->view('patients/form/picture', $data, true))
				);
		} else
		{
			$data = array(
					"page" => $this->page."_".__FUNCTION__,
					"form" => TRUE,
					"fileinput" => TRUE,
					"webcam" => TRUE,
					"imagecrop" => TRUE,
					"simpleupload" => TRUE,
					"profile" => $profile,
					"profile_id" => (int) @$profile->id,
				);
			
			$this->template
				->set( "heading", lang("patients:picture_heading") )
				->set_breadcrumb( lang("common:page"), base_url("common") )
				->set_breadcrumb( lang("patients:breadcrumb"), base_url("common/patients") )
				->set_breadcrumb( lang("patients:picture_heading") )
				->build('patients/form/picture', $data);
		}
	}
	
	public function picture_upload( $id=0 )
	{
		if( 0 == $id ){ $id = $this->input->get_post( "patient_id" ); }
		if( 0 == $id ){ $id = $this->input->get_post( "p_id" ); }
		if( 0 == $id ){ $id = $this->input->get_post( "pid" ); }
		if( 0 == $id ){ $id = $this->input->get_post( "id" ); }
		
		$id = (int) $id;
		
		$this->load->library( 'my_object' );
		$profile_data = (array) patient_helper::get_patient_profile( $id );
		$profile = new My_object( $profile_data );
		$profile_id = (int) @$profile->id;
		
		if( $_FILES )
		{
			if( $this->_picture_upload( $profile ) )
			{
				if( $this->input->is_ajax_request() )
				{
					header("Content-Type: application/json; charset=utf-8");
					echo (json_encode(array(
							"status" => "success",
							"message" => "Uploaded!",
							"code" => 200
						)));
					exit(0);
				} else
				{
					redirect(base_url(sprintf("common/patients/%s/%d", "picture_crop", $profile_id)));
				}
			} else
			{
				if( $this->input->is_ajax_request() )
				{
					header("Content-Type: application/json; charset=utf-8");
					echo (json_encode(array(
							"status" => "error",
							"message" => "Upload failed.",
							"code" => 500
						)));
					exit(0);
				} else
				{
					redirect(base_url(sprintf("common/patients/%s/%d", __FUNCTION__, $profile_id)));
				}
			}
		}
		
		if( $this->input->is_ajax_request() )
		{
			$data = array(
					"is_ajax_request" => TRUE,
					"modal_type" => "modal-md",
					"picture_action" => base_url( "common/patients/picture/{$profile_id}" ),
					"upload_action" => base_url( "common/patients/picture_upload/{$profile_id}" ),
					"success_action" => base_url( "common/patients/picture_crop/{$profile_id}" ),
					"profile" => $profile,
					"profile_id" => $profile_id,					
				);
			
			$this->load->view( 
					'patients/modal/picture', 
					array('form_child' => $this->load->view('patients/form/picture/picture_upload', $data, true))
				);
		} else
		{
			$data = array(
					"page" => $this->page."_".__FUNCTION__,
					"form" => TRUE,
					"fileinput" => TRUE,
					"simpleupload" => TRUE,
					"picture_action" => base_url( "common/patients/picture/{$profile_id}" ),
					"upload_action" => base_url( "common/patients/picture_upload/{$profile_id}" ),
					"success_action" => base_url( "common/patients/picture_crop/{$profile_id}" ),
					"profile" => $profile,
					"profile_id" => $profile_id,
				);
			
			$this->template
				->set( "heading", lang("patients:picture_heading") )
				->set_breadcrumb( lang("common:page"), base_url("common") )
				->set_breadcrumb( lang("patients:breadcrumb"), base_url("common/patients") )
				->set_breadcrumb( lang("patients:picture_heading") )
				->build('patients/form/picture/picture_upload', $data);
		}
	}
	
	public function picture_capture( $id=0 )
	{
		if( 0 == $id ){ $id = $this->input->get_post( "patient_id" ); }
		if( 0 == $id ){ $id = $this->input->get_post( "p_id" ); }
		if( 0 == $id ){ $id = $this->input->get_post( "pid" ); }
		if( 0 == $id ){ $id = $this->input->get_post( "id" ); }
		
		$id = (int) $id;
		
		$this->load->library( 'my_object' );
		$profile_data = (array) patient_helper::get_patient_profile( $id );
		$profile = new My_object( $profile_data );
		$profile_id = (int) @$profile->id;
		
		if( $this->input->post() )
		{
			if( $this->_picture_rewrite( $profile ) )
			{
				if( $this->input->is_ajax_request() )
				{
					header("Content-Type: application/json; charset=utf-8");
					echo (json_encode(array(
							"status" => "success",
							"message" => "Taken!",
							"code" => 200
						)));
					exit(0);
				} else
				{
					redirect(base_url(sprintf("common/patients/%s/%d", "picture_crop", $profile_id)));
				}
			} else
			{
				if( $this->input->is_ajax_request() )
				{
					header("Content-Type: application/json; charset=utf-8");
					echo (json_encode(array(
							"status" => "error",
							"message" => "Take failed.",
							"code" => 500
						)));
					exit(0);
				} else
				{
					redirect(base_url(sprintf("common/patients/%s/%d", __FUNCTION__, $profile_id)));
				}
			}	
		}
		
		if( $this->input->is_ajax_request() )
		{
			$data = array(
					"is_ajax_request" => TRUE,
					"profile" => $profile,
					"profile_id" => $profile_id,
					"modal_type" => "modal-capture",
				);
			
			$this->load->view( 
					'patients/modal/picture', 
					array('form_child' => $this->load->view('patients/form/picture/picture_capture', $data, true))
				);
		} else
		{
			$data = array(
					"page" => $this->page."_".__FUNCTION__,
					"form" => TRUE,
					"webcam" => TRUE,
					"profile" => $profile,
					"profile_id" => $profile_id,
				);
			
			$this->template
				->set( "heading", lang("patients:picture_heading") )
				->set_breadcrumb( lang("common:page"), base_url("common") )
				->set_breadcrumb( lang("patients:breadcrumb"), base_url("common/patients") )
				->set_breadcrumb( lang("patients:picture_heading") )
				->build('patients/form/picture/picture_capture', $data);
		}
	}
	
	public function picture_crop( $id=0 )
	{
		// http://danielhellier.com/crop/index.html
		
		if( 0 == $id ){ $id = $this->input->get_post( "patient_id" ); }
		if( 0 == $id ){ $id = $this->input->get_post( "p_id" ); }
		if( 0 == $id ){ $id = $this->input->get_post( "pid" ); }
		if( 0 == $id ){ $id = $this->input->get_post( "id" ); }
		
		$id = (int) $id;
		
		$this->load->library( 'my_object' );
		$profile_data = (array) patient_helper::get_patient_profile( $id );
		$profile = new My_object( $profile_data );
		$profile_id = (int) @$profile->id;
		
		if( $this->input->post() )
		{
			if( TRUE === $this->_picture_rewrite( $profile ) )
			{
				if( $this->input->is_ajax_request() )
				{
					header("Content-Type: application/json; charset=utf-8");
					echo (json_encode(array(
							"status" => "success",
							"message" => "Cropped!",
							"code" => 200
						)));
					exit(0);
				} else
				{
					redirect(base_url(sprintf("common/patients/%s/%d", "picture_crop", $profile_id)));
				}
			} else
			{
				if( $this->input->is_ajax_request() )
				{
					header("Content-Type: application/json; charset=utf-8");
					echo (json_encode(array(
							"status" => "error",
							"message" => "Crop failed.",
							"code" => 500
						)));
					exit(0);
				} else
				{
					redirect(base_url(sprintf("common/patients/%s/%d", __FUNCTION__, $profile_id)));
				}
			}
		}
		
		if( $this->input->is_ajax_request() )
		{
			$data = array(
					"is_ajax_request" => TRUE,
					"profile" => $profile,
					"profile_id" => (int) @$profile->id,
					"modal_type" => "modal-imagecrop",
				);
			
			$this->load->view( 
					'patients/modal/picture', 
					array('form_child' => $this->load->view('patients/form/picture/picture_crop', $data, true))
				);
		} else
		{
			$data = array(
					"page" => $this->page."_".__FUNCTION__,
					"form" => TRUE,
					"imagecrop" => TRUE,
					"profile" => $profile,
					"profile_id" => (int) @$profile->id,
				);
			
			$this->template
				->set( "heading", lang("patients:picture_heading") )
				->set_breadcrumb( lang("common:page"), base_url("common") )
				->set_breadcrumb( lang("patients:breadcrumb"), base_url("common/patients") )
				->set_breadcrumb( lang("patients:picture_heading") )
				->build('patients/form/picture/picture_crop', $data);
		}
	}
	
	public function overview( $id=0 )
	{
		if( 0 == $id ){ $id = $this->input->get_post( "patient_id" ); }
		if( 0 == $id ){ $id = $this->input->get_post( "p_id" ); }
		if( 0 == $id ){ $id = $this->input->get_post( "pid" ); }
		if( 0 == $id ){ $id = $this->input->get_post( "id" ); }
		
		$id = (int) $id;
		
		$this->load->library( 'my_object' );
		$profile_data = (array) patient_helper::get_patient_profile( $id );
		$profile = new My_object( $profile_data );
		$profile_completed = patient_helper::get_profile_completed( $profile );
		
		$age_now = patient_helper::get_age( $profile );
		$profile->addData(array(
				"personal_age_y" => $age_now->y,
				"personal_age_m" => $age_now->m,
				"personal_age_d" => $age_now->d,
			));
		
		$data = array(
				"profile" => $profile,
				"profile_completed" => $profile_completed,
				"id" => $id,
			);
		//print_r($data);exit(0);
		
		if( $this->input->is_ajax_request() )
		{
			$this->load->view( 
					'patients/modal/overview', 
					array('overview_child' => $this->load->view('patients/overview/profile', $data, true))
				);
		} else
		{
			$this->template
				->set( "heading", lang("patients:overview_heading") )
				->set_breadcrumb( lang("common:page"), base_url("common") )
				->set_breadcrumb( lang("patients:breadcrumb"), base_url("common/patients") )
				->set_breadcrumb( lang("patients:overview_heading") )
				->build('patients/overview', $data);
		}
	}

	public function list_downline( $id=0 )
	{
		if( 0 == $id ){ $id = $this->input->get_post( "id" ); }
		
		$id = (int) $id;
		
		$this->load->library( 'my_object' );
		
		$data = array(
				"referrer_id" => $id,
			);

		if( $this->input->is_ajax_request() )
		{
			$this->load->view( 
					'patients/modal/downline', 
					array('downline_details' => $this->load->view('patients/tables/downline', $data, true))
				);
		} else
		{
			$this->template
				->set( "heading", lang("patients:list_downline_heading") )
				->set_breadcrumb( lang("common:page"), base_url("common") )
				->set_breadcrumb( lang("patients:breadcrumb"), base_url("common/patients") )
				->set_breadcrumb( lang("patients:list_downline_heading") )
				->build('patients/tables/downline', $data);
		}
	}
		
	public function lookup_referrer( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{			
			$this->load->view( 'patients/lookup/referrer', (isset($data) ? $data : NULL) );
		} 
	}

	public function lookup_datatable_referrer( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{			
			$this->load->view( 'patients/lookup/datatable_referrer', (isset($data) ? $data : NULL) );
		} 
	}
		
	public function lookup( $ajax = FALSE )
	{
		if( $this->input->is_ajax_request() || $ajax )
		{
			$this->load->view( 'patients/lookup/datatable', array() );
		} else
		{
			$data = array(
					'page' => $this->page,
					'datatables' => TRUE,
					'form' => TRUE,
					'resource' => $resource,
				);
			
			$this->template
				->set( "heading", "Lookup Box" )
				->set_breadcrumb( lang("common:page"), base_url("common") )
				->set_breadcrumb( "Lookup Box" )
				->build('patients/lookup', (isset($data) ? $data : NULL));
		}
	}

	// Cooperation == Perusahaan yang diajak kerja sama (BPJS, IKS)
	public function lookup_cooperation( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'patients/lookup/cooperations', array() );
		} 
	}
	
	// Lookup kartu anggota kerja sama patient(BPJS, IKS)
	public function lookup_patient_cooperation_card( $is_ajax_request=false )
	{
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'patients/lookup/patient_cooperation_cards', array() );
		} 
	}

	public function lookup_insurers( $ajax = FALSE )
	{
		if( $this->input->is_ajax_request() || $ajax )
		{
			$this->load->view( 'patients/lookup/datatable_insurers', array() );
		} 
		else
		{
			$data = array(
					'page' => $this->page,
					'datatables' => TRUE,
					'form' => TRUE,
					// 'resource' => $resource,
				);
			
			$this->template
				->set( "heading", "Lookup Box" )
				->set_breadcrumb( lang("common:page"), base_url("common") )
				->set_breadcrumb( "Lookup Box" )
				->build('patients/lookup', (isset($data) ? $data : NULL));
		}
	}
		
	public function lookup_collection( $state=false )
	{
		$this->datatable_collection( $state );
	}
	
	public function datatable_collection( $state=false )
    {
        $start = $this->input->get_post('start', true);
        $length = $this->input->get_post('length', true);
        $order = $this->input->get_post('order', true);
        $columns = $this->input->get_post('columns', true);
        $search = $this->input->get_post('search', true);
        $draw = $this->input->get_post('draw', true);
		
		$db_from = "{$this->patient_m->table} a";
		$db_where = array();
		$db_like = array();
				
		// preparing default
		if( isset($search['value']) && ! empty($search['value']) )
        {
            $keywords = $this->db->escape_str( $search['value'] );
			
			$db_like[ $this->db->escape_str("a.NRM") ] = $keywords;
			$db_like[ $this->db->escape_str("a.NamaPasien") ] = $keywords;
			$db_like[ $this->db->escape_str("a.JenisPasien") ] = $keywords;
			$db_like[ $this->db->escape_str("a.Phone") ] = $keywords;
			$db_like[ $this->db->escape_str("a.Alamat") ] = $keywords;
			$db_like[ $this->db->escape_str("a.Pekerjaan") ] = $keywords;
			
        }
		
		// get total records
		$this->db->from( $db_from );
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		$records_total = $this->db->count_all_results();
		
		// get total filtered
		$this->db
			->from( $db_from )
			;
			
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }		
		$records_filtered = $this->db->count_all_results();

		
		// get result filtered
		$db_select = <<<EOSQL
			a.NRM
			,a.NamaPasien
			,a.NoIdentitas
			,a.JenisKelamin
			,a.TglLahir
			,a.TglLahirDiketahui
			,a.UmurSaatInput
			,a.Pekerjaan
			,a.Alamat
			,a.PropinsiID
			,a.KabupatenID
			,a.KecamatanID
			,a.DesaID
			,a.BanjarID
			,a.Phone
			,a.Email
			
			,a.JenisPasien
			,a.JenisKerjasamaID
			,a.AnggotaBaru
			,a.CustomerKerjasamaID
			,a.NoKartu
			,a.Klp
			,a.JabatanDiPerusahaan
			,a.PasienLoyal
			
			,a.TotalKunjunganRawatInap
			,a.TotalKunjunganRawatJalan
			,a.KunjunganRJ_TahunIni
			,a.KunjunganRI_TahunIni
			
			,a.EtnisID
			,a.NationalityID
			,a.PasienVVIP
			,a.PasienKTP
			,a.TglInput
			,a.UserID
			,a.CaraDatangPertama
			,a.DokterID_ReferensiPertama
			,a.SedangDirawat
			,a.KodePos
			
			,a.TglRegKasusKecelakaanBaru
			,a.NoRegKecelakaanBaru
			,a.Aktive_Keanggotaan
			,a.Agama
			,a.NoANggotaE
			,a.NamaAnggotaE
			,a.GenderAnggotaE
			,a.TglTidakAktif
			,a.TipePasienAsal
			,a.NoKartuAsal
			,a.NamaPerusahaanAsal

			,a.PenanggungIsPasien
			,a.PenanggungNRM
			,a.PenanggungNama
			,a.PenanggungAlamat
			,a.PenanggungPhone
			,a.PenanggungKTP
			,a.PenanggungHubungan
			,a.PenanggungPekerjaan
			
			,a.Aktif
			,a.PasienBlackList
			,a.NamaIbuKandung
			,a.NonPBI
			,a.KdKelas
			,a.Prematur
			,a.NamaAlias
			
			,d.Nama_Customer
			,d.Kode_Customer AS CompanyID
			
			
EOSQL;

		$this->db
			->select( $db_select )
			->from( $db_from )
			->join( "SIMmJenisKerjasama b", "a.JenisKerjasamaID = b.JenisKerjasamaID", "LEFT OUTER" )
			->join( "SIMdCustomerKerjasama c", "a.CustomerKerjasamaID = c.CustomerKerjasamaID", "LEFT OUTER" )
			->join( "mCustomer d", "c.CustomerID = d.Customer_ID", "LEFT OUTER" )
			->join( "mPropinsi f", "a.PropinsiID = f.Propinsi_ID", "LEFT OUTER" )
			->join( "mKabupaten g", "a.KabupatenID = g.Kode_Kabupaten", "LEFT OUTER" )
			->join( "mKecamatan h", "a.KecamatanID = h.KecamatanID", "LEFT OUTER" )
			->join( "mDesa i", "a.DesaID = i.DesaID", "LEFT OUTER" )
			->join( "mBanjar j", "a.BanjarID = j.BanjarID", "LEFT OUTER" )
			->join( "mNationality k", "a.NationalityID = k.NationalityID", "LEFT OUTER" )
			;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }		
		
		// ordering
        if( isset($order) )
        {
            $sort_column = $order[0]['column'];
			$sort_dir = $order[0]['dir'];
			
			if( $columns[$sort_column]['orderable'] == 'true' )
			{
				$this->db
					->order_by( $columns[intval($this->db->escape_str($sort_column))]['data'], $this->db->escape_str($sort_dir) );
			}
        }
		
		// paging
		if( isset($start) && $length != '-1')
        {
            $this->db
				->limit( $length, $start );
        }
		
		// get
		$result = $this->db
					->get()
					->result()
					;
		
        // Output
        $output = array(
				'draw' => intval($draw),
				'recordsTotal' => $records_total,
				'recordsFiltered' => $records_filtered,
				'data' => array()
			);
        
        foreach($result as $row)
        {			
            $output['data'][] = $row;
        }
		
		$this->template
			->build_json( $output );
    }
	
	public function datatable_collection_multi_nrm( $state=false )
    {
        $start = $this->input->get_post('start', true);
        $length = $this->input->get_post('length', true);
        $order = $this->input->get_post('order', true);
        $columns = $this->input->get_post('columns', true);
        $search = $this->input->get_post('search', true);
        $draw = $this->input->get_post('draw', true);
		
		$db_from = "{$this->patient_nrm_model->table} a";
		$db_where = array();
		$db_like = array();
				
		// preparing default
		if( isset($search['value']) && ! empty($search['value']) )
        {
            $keywords = $this->db->escape_str( $search['value'] );
			
			$db_like[ $this->db->escape_str("a.NRM") ] = $keywords;
			$db_like[ $this->db->escape_str("b.NRM") ] = $keywords;
			$db_like[ $this->db->escape_str("b.NamaPasien") ] = $keywords;
			$db_like[ $this->db->escape_str("b.JenisPasien") ] = $keywords;
			$db_like[ $this->db->escape_str("b.Phone") ] = $keywords;
			$db_like[ $this->db->escape_str("b.Alamat") ] = $keywords;
			$db_like[ $this->db->escape_str("b.Pekerjaan") ] = $keywords;
			
        }
		
		// get total records
		$this->db->from( $db_from );
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		$records_total = $this->db->count_all_results();
		
		// get total filtered
		$this->db
			->from( $db_from )
			->join( "mPasien b", "a.GeneralNRM = b.NRM", "INNER" )
			->join( "SIMdCustomerKerjasama c", "b.CustomerKerjasamaID = c.CustomerKerjasamaID", "LEFT OUTER" )
			->join( "mCustomer d", "c.CustomerID = d.Customer_ID", "LEFT OUTER" )
			;
			
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }		
		$records_filtered = $this->db->count_all_results();

		
		// get result filtered
		$db_select = <<<EOSQL
			a.NRM
			,a.KelompokSectionID
			,a.GeneralNRM
			,b.NamaPasien
			,b.NoIdentitas
			,b.JenisKelamin
			,b.TglLahir
			,b.TglLahirDiketahui
			,b.UmurSaatInput
			,b.Pekerjaan
			,b.Alamat
			,b.PropinsiID
			,b.KabupatenID
			,b.KecamatanID
			,b.DesaID
			,b.BanjarID
			,b.Phone
			,b.Email
			
			,b.JenisPasien
			,b.JenisKerjasamaID
			,b.AnggotaBaru
			,b.CustomerKerjasamaID
			,b.NoKartu
			,b.Klp
			,b.JabatanDiPerusahaan
			,b.PasienLoyal
			
			,b.TotalKunjunganRawatInap
			,b.TotalKunjunganRawatJalan
			,b.KunjunganRJ_TahunIni
			,b.KunjunganRI_TahunIni
			
			,b.EtnisID
			,b.NationalityID
			,b.PasienVVIP
			,b.PasienKTP
			,b.TglInput
			,b.UserID
			,b.CaraDatangPertama
			,b.DokterID_ReferensiPertama
			,b.SedangDirawat
			,b.KodePos
			
			,b.TglRegKasusKecelakaanBaru
			,b.NoRegKecelakaanBaru
			,b.Aktive_Keanggotaan
			,b.Agama
			,b.NoANggotaE
			,b.NamaAnggotaE
			,b.GenderAnggotaE
			,b.TglTidakAktif
			,b.TipePasienAsal
			,b.NoKartuAsal
			,b.NamaPerusahaanAsal

			,b.PenanggungIsPasien
			,b.PenanggungNRM
			,b.PenanggungNama
			,b.PenanggungAlamat
			,b.PenanggungPhone
			,b.PenanggungKTP
			,b.PenanggungHubungan
			,b.PenanggungPekerjaan
			
			,b.Aktif
			,b.PasienBlackList
			,b.NamaIbuKandung
			,b.NonPBI
			,b.KdKelas
			,b.Prematur
			,b.NamaAlias
			
			,d.Nama_Customer
			,d.Kode_Customer AS CompanyID
			
			
EOSQL;

		$this->db
			->select( $db_select )
			->from( $db_from )
			->join( "mPasien b", "a.GeneralNRM = b.NRM", "INNER" )
			->join( "SIMdCustomerKerjasama c", "b.CustomerKerjasamaID = c.CustomerKerjasamaID", "LEFT OUTER" )
			->join( "mCustomer d", "c.CustomerID = d.Customer_ID", "LEFT OUTER" )
			;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }		
		
		// ordering
        if( isset($order) )
        {
            $sort_column = $order[0]['column'];
			$sort_dir = $order[0]['dir'];
			
			if( $columns[$sort_column]['orderable'] == 'true' )
			{
				$this->db
					->order_by( $columns[intval($this->db->escape_str($sort_column))]['name'], $this->db->escape_str($sort_dir) );
			}
        }
		
		// paging
		if( isset($start) && $length != '-1')
        {
            $this->db
				->limit( $length, $start );
        }
		
		// get
		$result = $this->db
					->get()
					->result()
					;
		
        // Output
        $output = array(
				'draw' => intval($draw),
				'recordsTotal' => $records_total,
				'recordsFiltered' => $records_filtered,
				'data' => array()
			);
        
        foreach($result as $row)
        {			
            $output['data'][] = $row;
        }
		
		$this->template
			->build_json( $output );
    }
	
	protected function _picture_rewrite( & $profile )
	{
		
		
		if( $this->input->post() )
		{
			if( $picture_row = $this->input->post("picture_row") )
			{				
//				// remove the base64 part
//				$base64 = base64_decode(preg_replace('#^data:image/[^;]+;base64,#', '', $picture_row));				
//				// create image
//				$source = imagecreatefromstring( $base64 );				
//				// save image
//				imagejpeg($source, $url, 75);
				
				$picture_type = $this->input->post( "picture_type" );
				
				$personal_picture = $profile->personal_picture;
				if( ! $personal_picture )
				{
					$personal_picture = sprintf('PATIENT_%s_PIC.%s', strtoupper(str_replace(".","-",$profile->mr_number)), $picture_type );
				}
				
//				$picture_source = str_replace('data:image/'.$picture_type.';base64,', '', $picture_row);
//				$picture_source = str_replace(' ', '+', $picture_source);
//				
//				$picture_path = FCPATH."resource/patients/pictures/{$personal_picture}";
//				
//				if( ! file_put_contents($picture_path, base64_decode($picture_source)) )
//				{
//					make_flashdata(array(
//							'response_status' => 'error',
//							'message' => lang( 'patients:picture_upload_error' )
//						));
//					return FALSE;
//				}

				$picture_path = FCPATH."resource/patients/pictures/{$personal_picture}";				
				$picture_content = base64_decode(preg_replace('#^data:image/[^;]+;base64,#', '', $picture_row));
				$picture_source = imagecreatefromstring( $picture_content );
				imagejpeg( $picture_source, $picture_path, 90 );			
				
				if( ! $profile->personal_picture )
				{
					$profile->personal_picture = $personal_picture;					
					$this->get_model()->update(array("personal_picture" => $profile->personal_picture), array("id" => $profile->id));	
				}
			}
			
			make_flashdata(array(
					'response_status' => 'success',
					'message' => lang( 'patients:picture_uploaded_successfully' )
				));
		}
		
		return TRUE;
	}

	protected function _les_upload( & $item )
    {
		if( $_FILES )
		{
				$config[ 'upload_path' ]	= realpath(FCPATH . '../../resource/patients/les');
				$config[ 'allowed_types' ]	= 'jpeg|jpg|png|doc|docx|xls|xlsx|pdf';
				$config[ 'file_name' ]		= "{$item->NRM}.pdf";
				$config[ 'overwrite' ]		= TRUE;
				//$config[ 'encrypt_name' ]	= TRUE;
				//$config[ 'max_size' ]		= 1024 * 8;

				$this->load->library( 'upload', $config );
				return ( ! $this->upload->do_upload('PasienLes') )
				? ['status'=>'error', 'message' => $this->upload->display_errors()]
				: ['status'=>'success', 'upload_data' => $this->upload->data()];
		}
		
		return TRUE;
    }
	
	protected function _picture_upload( & $profile )
    {
        
		
		if( $_FILES )
		{
			if ( file_exists( $_FILES[ 'userfile' ][ 'tmp_name' ] ) || is_uploaded_file( $_FILES[ 'userfile' ][ 'tmp_name' ] ) )
			{
				$config[ 'upload_path' ]	= './resource/patients/pictures/';
				$config[ 'allowed_types' ]	= 'jpg';
				$config[ 'file_name' ]		= sprintf('PATIENT_%s_PIC', strtoupper(str_replace(".","-",$profile->mr_number)));
				$config[ 'overwrite' ]		= TRUE;
				//$config[ 'encrypt_name' ]	= TRUE;
				//$config[ 'max_size' ]		= 1024 * 8;
				
				//print_r($config);exit(0);
				
				$this->load->library( 'upload', $config );
				
				if ( ! $this->upload->do_upload() )
				{
					make_flashdata(array(
							'response_status' => 'error',
							'message' => $this->upload->display_errors()
						));
					return FALSE;
				}
				
				$picture = (object) $this->upload->data();
				$profile->personal_picture = $picture->file_name;
				
				$this->get_model()->update(array("personal_picture" => $profile->personal_picture), array("id" => $profile->id));	
			}
			
			make_flashdata(array(
					'response_status' => 'success',
					'message' => lang( 'patients:picture_uploaded_successfully' )
				));
		}
		
		return TRUE;
    }
	
	public function valid_mr_number( $str )
	{
		if( preg_match("/(\d{2})\.(\d{2})\.(\d{2})$/", $str, $matches) )
		{
			return TRUE;
		}

		$this->form_validation->set_message('valid_mr_number', lang('patients:invalid_mr_number'));
		return FALSE;
	}
	
	public function exist_mr_number( $str )
	{
		if( patient_helper::find_patient($str) === FALSE )
		{
			return TRUE;
		}
		
		$this->form_validation->set_message('exist_mr_number', lang('patients:exist_mr_number'));
		return FALSE;
	}
	public function lookup_regional($is_ajax_request = false)
	{
		if ($this->input->is_ajax_request() || $is_ajax_request !== false) {
			$this->data['provinsi'] = $this->input->get('Provinsi');
			$this->load->view('patients/lookup/lookup_regional', $this->data);
		}
	}

	public function datatable_regional_collection()
	{
		$start = $this->input->get_post('start', true);
		$length = $this->input->get_post('length', true);
		$order = $this->input->get_post('order', true);
		$columns = $this->input->get_post('columns', true);
		$search = $this->input->get_post('search', true);
		$draw = $this->input->get_post('draw', true);

		$db_from = "Vw_mRegional a";
		$db_where = array();
		$db_like = array();

		if ($this->input->post_get("provinsi")) {
			$db_where['a.ProvinsiId'] = $this->input->post_get("provinsi");
		}else{
			$db_where['a.ProvinsiId'] = $this->input->post_get("provinsi");
		}

		//prepare defautl flter

		// preparing default
		if (isset($search['value']) && !empty($search['value'])) {
			$keywords = $this->db->escape_str($search['value']);
			$db_like[$this->db->escape_str("a.KabupatenNama")] = $keywords;
			$db_like[$this->db->escape_str("a.KecamatanNama")] = $keywords;
			$db_like[$this->db->escape_str("a.DesaNama")] = $keywords;
		}

		// get total records
		$this->db->from($db_from);
		if (!empty($db_where)) {
			$this->db->where($db_where);
		}
		$records_total = $this->db->count_all_results();

		// get total filtered
		$this->db
			->from($db_from);
		if (!empty($db_where)) {
			$this->db->where($db_where);
		}
		if (!empty($db_like)) {
			$this->db->group_start()->or_like($db_like)->group_end();
		}
		$records_filtered = $this->db->count_all_results();

		// get result filtered
		$db_select = <<<EOSQL
			a.*
EOSQL;

		$this->db
			->select($db_select)
			->from($db_from);

		if (!empty($db_where)) {
			$this->db->where($db_where);
		}
		if (!empty($db_like)) {
			$this->db->group_start()->or_like($db_like)->group_end();
		}

		// ordering
		if (isset($order)) {
			$sort_column = $order[0]['column'];
			$sort_dir = $order[0]['dir'];

			if ($columns[$sort_column]['orderable'] == 'true') {
				$this->db
					->order_by($columns[intval($this->db->escape_str($sort_column))]['data'], $this->db->escape_str($sort_dir));
			}
		}

		// paging
		if (isset($start) && $length != '-1') {
			$this->db
				->limit($length, $start);
		}

		// get
		$result = $this->db
			->get()
			->result();

		// Output
		$output = array(
			'draw' => intval($draw),
			'recordsTotal' => $records_total,
			'recordsFiltered' => $records_filtered,
			'data' => array()
		);

		foreach ($result as $row) {
			$output['data'][] = $row;
		}

		response_json($output);
	}
}


