<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Family extends ADMIN_Controller
{
	protected $nameroutes = 'folder/family';
	
	public function __construct()
	{
		parent::__construct();
		
		$this->data['nameroutes'] = $this->nameroutes; 
		
		$this->load->language('family');		
		$this->load->helper('family');
		family_helper::init();
		$this->load->helper('personal');
		
		$this->load->model('family_model');
		$this->load->model('personal_model');
		$this->load->model('personal_to_family_model');
		
		$this->load->model('personal_to_environment_model');
		$this->load->model('personal_to_obgyn_model');
		$this->load->model('personal_to_immunization_model');
		
		$this->load->model('nationality_model');
		$this->load->model('country_model');
		$this->load->model('province_model');
		$this->load->model('county_model');
		$this->load->model('district_model');
		$this->load->model('village_model');
		$this->load->model('area_model');
	}
	
	public function index()
	{
		$this->template
			->title(lang('heading:family'),lang('heading:folder'))
			->set_breadcrumb(lang('heading:folder') )
			->set_breadcrumb(lang('heading:family_list'), site_url($this->nameroutes))
			->build("family/datatable", $this->data);
	}
	
	public function create()
	{
	
		$item = (object) [
			'NoFamily' => family_helper::gen_family_number(),
			'NoKK' => NULL,
			'PersonalIdKK' => 0,
			'ReffNoFamily' => NULL,
			'Address' => NULL,
			'PostalCode' => NULL,
			'CountryId' => 0,
			'CountryName' => NULL,
			'ProvinceId' => 0,
			'ProvinceName' => NULL,
			'CountyId' => 0,
			'CountyName' => NULL,
			'DistrictId' => 0,
			'DistrictName' => NULL,
			'VillageId' => 0,
			'VillageName' => NULL,
			'Note' => NULL,
			'Status' => 1,
			'CreatedAt' => time(),
			'CreatedBy' => $this->user_auth->User_ID,
			'UpdatedAt' => time(),
			'UpdatedBy' => $this->user_auth->User_ID,
			'DeletedAt' => NULL,
			'DeletedBy' => 0,
		];
		
		if( $this->input->post() ) 
		{
			$_term = [
				'Status' => 1,
				'CreatedAt' => time(),
				'CreatedBy' => $this->user_auth->User_ID,
				'UpdatedAt' => time(),
				'UpdatedBy' => $this->user_auth->User_ID,
				'DeletedAt' => NULL,
				'DeletedBy' => 0,
			];
					
			$post_family = array_merge( (array) $item, $this->input->post("family") );
			$post_personal = array_merge( $_term, $this->input->post("personal") );
			$post_relation = array_merge( $_term, $this->input->post("relation") );
			$post_additional = $this->input->post("additional");
			
			$this->load->library('form_validation');
			$this->form_validation->set_rules($this->family_model->rules['insert']);
			$this->form_validation->set_data($post_family);
			if( $this->form_validation->run() )
			{							
				$this->db->trans_begin();
					
				
					$personal_id = $this->personal_model->create( $post_personal );			
					
					$post_family['PersonalIdKK'] = $post_additional['IsPatriarch'] ? $personal_id : NULL;
					$family_id = $this->family_model->create( $post_family );							
					
					$post_relation['PersonalId'] = $personal_id;
					$post_relation['FamilyId'] = $family_id;
					$this->personal_to_family_model->create( $post_relation );
					
				if ($this->db->trans_status() === FALSE)
				{
					$this->db->trans_rollback();
					$response = array(
							"status" => 'error',
							"message" => lang('global:created_failed'),
							"code" => 500
						);
				}
				else
				{
					$this->db->trans_commit();
					$response = array(
							"id" => $family_id,
							"status" => 'success',
							"message" => lang('global:created_successfully'),
							"code" => 200
						);
				}			
			} else
			{
				$response["message"] = $this->form_validation->get_all_error_string();
				$response["status"] = "error";
				$response["code"] = "500";
			}

			response_json( $response );
		}
		
		$this->data['item'] = $item;
		$this->data['form_action'] = current_url();
		$this->data['option_nationality'] = $this->nationality_model->to_list_data();
		$this->data['option_country'] = $this->country_model->to_list_data();
		$this->data['option_province'] = $this->province_model->to_list_data( 15 );
		//$this->data['option_county'] = $this->county_model->to_list_data( 1 );	
		$this->data['family_reff_search_url'] = base_url("{$this->nameroutes}/autocomplete");	
			
		$this->template
			->title(lang('heading:family_create'),lang('heading:folder'))
			->set_breadcrumb(lang('heading:family_list'), site_url($this->nameroutes))
			->set_breadcrumb(lang('heading:family_create'))
			->build("family/form", $this->data);
	}
	
	public function update($id = 0)
	{
		$this->data['item'] = $item = $this->family_model->get_one($id);
		
		if( $this->input->post() ) 
		{
			$_term = [
				'UpdatedAt' => time(),
				'UpdatedBy' => $this->user_auth->User_ID,
			];
			
			$post_family = array_merge( $_term, $this->input->post("family"));
			
			$this->load->library('form_validation');
			$this->form_validation->set_rules($this->family_model->rules['update']);
			$this->form_validation->set_data($post_family);
			if( $this->form_validation->run() )
			{								
				$this->db->trans_begin();
									
					$this->family_model->update( $post_family, $id );	
					
				if ($this->db->trans_status() === FALSE)
				{
					$this->db->trans_rollback();
					$response = array(
							"status" => 'error',
							"message" => lang('global:updated_failed'),
							"code" => 500
						);
				}
				else
				{
					$this->db->trans_commit();
					$response = array(
							"id" => $id,
							"status" => 'success',
							"message" => lang('global:updated_successfully'),
							"code" => 200
						);
				}			
			} else
			{
				$response["message"] = $this->form_validation->get_all_error_string();
				$response["status"] = "error";
				$response["code"] = "500";
			}
			
			response_json( $response );
		}
				
		$this->data['is_edit'] = TRUE;		
		$this->data['form_action'] = current_url();
		$this->data['option_personal'] = family_helper::get_option_family_personal( $item->Id );
		$this->data['option_nationality'] = $this->nationality_model->to_list_data();
		$this->data['option_country'] = $this->country_model->to_list_data();
		$this->data['option_province'] = $this->province_model->to_list_data( $item->CountryId );
		$this->data['option_county'] = $this->county_model->to_list_data( $item->ProvinceId );	
		$this->data['option_district'] = $this->district_model->to_list_data( $item->CountyId );	
		$this->data['option_village'] = $this->village_model->to_list_data( $item->DistrictId );	
		#$this->data['option_area'] = $this->area_model->to_list_data( $item->VillageId );	
		$this->data['family_reff_search_url'] = base_url("{$this->nameroutes}/autocomplete");	
		
		$this->template
			->title(lang('heading:family'),lang('heading:folder'))
			->set_breadcrumb(lang('heading:family_list'), site_url($this->nameroutes))
			->set_breadcrumb(lang('heading:family_update'))
			->build("family/update", $this->data);
	}
	
	/*
		Add new personals to Family
		@params
		(String) id -> Id Family 
	*/
	public function personal( $id )
	{
		$this->data['item'] = $item = $this->family_model->get_one($id);
		
		if( $this->input->post() || $_FILES )
		{
			$_term = [
				'Status' => 1,
				'CreatedAt' => time(),
				'CreatedBy' => $this->user_auth->User_ID,
				'UpdatedAt' => time(),
				'UpdatedBy' => $this->user_auth->User_ID,
				'DeletedAt' => NULL,
				'DeletedBy' => 0,
			];
						
			$post_personal = array_merge( $_term, $this->input->post("personal"));
			$post_relation = array_merge( $_term, $this->input->post("relation"));
			$post_additional = $this->input->post("additional");
			
			$post_environment = $this->input->post("environment");
			$post_obgyn = $this->input->post("obgyn");
			$post_immunization = $this->input->post("immunization");
			
			$this->load->library('form_validation');
			$this->form_validation->set_rules($this->personal_model->rules['insert']);
			$this->form_validation->set_data($post_personal);
			if( $this->form_validation->run())
			{							
				$this->db->trans_begin();
				
					$personal_id = $this->personal_model->create( $post_personal );		
					
					if( $post_additional['IsPatriarch'] )
					{
						$this->family_model->update(['PersonalIdKK' => $personal_id], $item->Id);
					}	
					
					$post_relation['PersonalId'] = $personal_id;
					$post_relation['FamilyId'] = $item->Id;
					$relation_id = $this->personal_to_family_model->create( $post_relation );
					
					if( !empty($_FILES) )
					{
						$do_upload = $this->do_upload();
						$this->personal_model->update( ['PersonalPicture' => @$do_upload['upload_data']['file_name']], $personal_id );
					}
					
					foreach($post_environment as $role => $note):
						$prepare_insert =  array_merge( $_term, personal_helper::prepare_insert( $note, $role, 'environment') );
						$prepare_insert['ResourceId'] = $relation_id;
						$this->personal_to_environment_model->create( $prepare_insert );
					endforeach;
					
					if( $post_relation['Relation'] == 'CHILD' ): // Jika status hubungan adalah CHILD(ANAK)
						foreach($post_obgyn as $role => $note):
							$prepare_insert =  array_merge( $_term, personal_helper::prepare_insert( $note, $role, 'obgyn') );
							$prepare_insert['ResourceId'] = $relation_id;
							$this->personal_to_obgyn_model->create( $prepare_insert );
						endforeach;
	
						foreach($post_immunization as $role => $note):
							$prepare_insert =  array_merge( $_term, personal_helper::prepare_insert( $note, $role, 'immunization') );
							$prepare_insert['ResourceId'] = $relation_id;
							$this->personal_to_immunization_model->create( $prepare_insert );
						endforeach;
	
					endif;
					
				if ($this->db->trans_status() === FALSE || @$do_upload['status'] == 'error')
				{
					$this->db->trans_rollback();
					$response = array(
							"status" => 'error',
							"message" => sprintf('%s %s', lang('global:created_failed'), @$do_upload['message'] ),
							"code" => 500
						);
				}
				else
				{
					$this->db->trans_commit();
					$response = array(
							"id" => $personal_id,
							"status" => 'success',
							"message" => lang('global:created_successfully'),
							"code" => 200
						);
				}			
			} else
			{
				$response["message"] = $this->form_validation->get_all_error_string();
				$response["status"] = "error";
				$response["code"] = "500";
			}

			response_json( $response );		
		}
		
		$prepare_form = personal_helper::prepare_form();
		
		$this->data['environment'] = (object) $prepare_form['environment'];
		$this->data['obgyn'] = (object) $prepare_form['obgyn'];
		$this->data['immunization'] = (object) $prepare_form['immunization'];
		
		$this->data['form_action'] = base_url("{$this->nameroutes}/personal/{$item->Id}");
		$this->data['option_husband'] = family_helper::get_option_family_personal_by_relation( $item->Id, 'HUSBAND');
		$this->data['option_wife'] = family_helper::get_option_family_personal_by_relation( $item->Id, 'WIFE');
		$this->data['option_nationality'] = $this->nationality_model->to_list_data();
		$this->data['option_country'] = $this->country_model->to_list_data();
		$this->data['option_province'] = $this->province_model->to_list_data( 15 );
		#$this->data['option_county'] = $this->county_model->to_list_data( 1 );	
		$this->data['is_edit'] = TRUE;
		
		$this->data['view_form_personal'] = $this->load->view('family/personal/form_personal', $this->data, TRUE );
		$this->data['view_form_environment'] = $this->load->view('family/personal/form_environment', $this->data, TRUE );
		$this->data['view_form_obgyn'] = $this->load->view('family/personal/form_obgyn', $this->data, TRUE );
		$this->data['view_form_immunization'] = $this->load->view('family/personal/form_immunization', $this->data, TRUE );

		
		if ( $this->input->is_ajax_request() )
		{
			$this->load->view('family/personal', $this->data);
		} else {
			$this->template
				->title(lang('heading:family_personal'),lang('heading:folder'))
				->set_breadcrumb(lang('heading:family_list'), site_url($this->nameroutes))
				->set_breadcrumb(lang('heading:family_personal'))
				->build("family/personal", $this->data);		
		}
	}
	
	/*
		Add new personals to Family
		@params
		(String) family_id -> Id Family 
		(String) personal_id -> Id Family 
	*/
	public function personal_update( $family_id, $personal_id )
	{
		$this->data['item'] = $item = $this->family_model->get_one($family_id);
		$this->data['personal'] = $personal = $this->personal_model->get_one($personal_id);
		$this->data['relation'] = $relation = $this->personal_to_family_model->get_by(['FamilyId' => $family_id, 'PersonalId' => $personal_id]);
		
		if( $this->input->post() )
		{
			$_term = [
				'UpdatedAt' => time(),
				'UpdatedBy' => $this->user_auth->User_ID,
			];
			
			$_term_insert = [
				'Status' => 1,
				'CreatedAt' => time(),
				'CreatedBy' => $this->user_auth->User_ID,
				'UpdatedAt' => time(),
				'UpdatedBy' => $this->user_auth->User_ID,
				'DeletedAt' => NULL,
				'DeletedBy' => 0,
			];
			
			$post_personal = array_merge( $_term, $this->input->post("personal"));
			$post_relation = array_merge( $_term, $this->input->post("relation"));
			$post_additional = $this->input->post("additional");
			
			$post_environment = $this->input->post("environment");
			$post_obgyn = $this->input->post("obgyn");
			$post_immunization = $this->input->post("immunization");

			$this->load->library('form_validation');
			$this->form_validation->set_rules($this->personal_model->rules['insert']);
			$this->form_validation->set_data($post_personal);
			if( $this->form_validation->run())
			{							
				$this->db->trans_begin();
				
					$this->personal_model->update( $post_personal, $personal->Id );	
						
					if( $post_additional['IsPatriarch'] )
					{
						$this->family_model->update(['PersonalIdKK' => $personal->Id], $item->Id);
					}	
					
					$this->personal_to_family_model->update( $post_relation, $relation->Id );
					
					if( !empty($_FILES) )
					{
						$do_upload = $this->do_upload( $personal->PersonalPicture );
						$this->personal_model->update( ['PersonalPicture' => @$do_upload['upload_data']['file_name']], $personal_id );
					}
					
					
					$_get_environment = $this->personal_to_environment_model->get_all(NULL, 0, ['ResourceId' => $relation->Id], TRUE);
			
					foreach($post_environment as $role => $note):
											
						if( personal_helper::search_array_by_role_note($role, $note, $_get_environment) === TRUE )
						{
							$log_insert = TRUE;
						}
						
						$prepare_update = array_merge( $_term, ['note' => $note] );
						$update = $this->personal_to_environment_model->update_by( $prepare_update, ['ResourceId' => $relation->Id, 'role' => $role] );
						if( $update == 0 && @$log_insert !== TRUE )
						{
							$prepare_insert =  array_merge( $_term_insert, personal_helper::prepare_insert( $note, $role, 'environment') );
							$prepare_insert['ResourceId'] = $relation->Id;
							$this->personal_to_environment_model->create( $prepare_insert );
						}
					endforeach;
					
					if( @$log_insert )
					{
						 personal_helper::insert_history_log( $_get_environment );
					}
					
					if( $post_relation['Relation'] == 'CHILD' ): // Jika status hubungan adalah CHILD(ANAK)
						foreach($post_obgyn as $role => $note):
						
							$prepare_update = array_merge( $_term, ['note' => $note] );
							$update = $this->personal_to_obgyn_model->update_by( $prepare_update, ['ResourceId' => $relation->Id, 'role' => $role] );
							if( $update == 0)
							{
								$prepare_insert =  array_merge( $_term_insert, personal_helper::prepare_insert( $note, $role, 'obgyn') );
								$prepare_insert['ResourceId'] = $relation->Id;
								$this->personal_to_obgyn_model->create( $prepare_insert );
							}
						endforeach;
	
						foreach($post_immunization as $role => $note):
	
							$prepare_update = array_merge( $_term, ['note' => $note] );
							$update = $this->personal_to_immunization_model->update_by( $prepare_update, ['ResourceId' => $relation->Id, 'role' => $role] );
							if( $update == 0 )
							{
								$prepare_insert =  array_merge( $_term_insert, personal_helper::prepare_insert( $note, $role, 'immunization') );
								$prepare_insert['ResourceId'] = $relation->Id;
								$this->personal_to_immunization_model->create( $prepare_insert );
							}
						endforeach;
	
					endif;
				
				if ($this->db->trans_status() === FALSE || @$do_upload['status'] == 'error')
				{
					$this->db->trans_rollback();
					$response = array(
							"status" => 'error',
							"message" => sprintf('%s %s', lang('global:updated_failed'), @$do_upload['message'] ),
							"code" => 500
						);
				}
				else
				{
					//$this->db->trans_rollback();
					$this->db->trans_commit();
					$response = array(
							"id" => $personal->Id,
							"status" => 'success',
							"message" => lang('global:updated_successfully'),
							"code" => 200
						);
				}			
			} else
			{
				$response["message"] = $this->form_validation->get_all_error_string();
				$response["status"] = "error";
				$response["code"] = "500";
			}

			response_json( $response );		
		}
		
		$prepare_form = personal_helper::prepare_form( $relation->Id );
		$this->data['environment'] = (object) @$prepare_form['environment'];
		$this->data['obgyn'] = (object) @$prepare_form['obgyn'];
		$this->data['immunization'] = (object) @$prepare_form['immunization'];
		
		$this->data['form_action'] = base_url("{$this->nameroutes}/personal_update/{$item->Id}/{$personal->Id}");
		$this->data['option_husband'] = family_helper::get_option_family_personal_by_relation( $item->Id, 'HUSBAND');
		$this->data['option_wife'] = family_helper::get_option_family_personal_by_relation( $item->Id, 'WIFE');
		$this->data['option_nationality'] = $this->nationality_model->to_list_data();
		$this->data['option_country'] = $this->country_model->to_list_data();
		$this->data['option_province'] = $this->province_model->to_list_data( $personal->CountryId );
		$this->data['option_county'] = $this->county_model->to_list_data( $personal->ProvinceId );	
		$this->data['option_district'] = $this->district_model->to_list_data( $personal->CountyId );	
		$this->data['option_village'] = $this->village_model->to_list_data( $personal->DistrictId );	
		//$this->data['option_area'] = $this->area_model->to_list_data( $personal->VillageId );	
		$this->data['is_edit'] = TRUE;	
		
		$this->data['view_form_personal'] = $this->load->view('family/personal/form_personal', $this->data, TRUE );
		$this->data['view_form_environment'] = $this->load->view('family/personal/form_environment', $this->data, TRUE );
		$this->data['view_form_obgyn'] = $this->load->view('family/personal/form_obgyn', $this->data, TRUE );
		$this->data['view_form_immunization'] = $this->load->view('family/personal/form_immunization', $this->data, TRUE );
			
		if ( $this->input->is_ajax_request() )
		{
			$this->load->view('family/personal', $this->data);
		} else {
			$this->template
				->title(lang('heading:family_personal'),lang('heading:folder'))
				->set_breadcrumb(lang('heading:family_list'), site_url($this->nameroutes))
				->set_breadcrumb(lang('heading:family_personal'))
				->build("family/personal", $this->data);		
		}
	}
	
	private function do_upload( $personal_picture = NULL )
	{
		$config['upload_path'] = realpath(FCPATH . '../../assets/family-folder/photos');
		$config['allowed_types'] = 'jpeg|jpg|png';
		$config['max_size'] = 0;
		$config['max_width'] = 0;
		$config['max_height'] = 0;
		$config['remove_spaces'] = TRUE;
		$config['encrypt_name'] = TRUE;
		$config['overwrite'] = TRUE;
		
		if( $personal_picture && file_exists( realpath(FCPATH . "../../assets/family-folder/photos/{$personal_picture}") ) )
		{
			$config['encrypt_name'] = FALSE;
			$config['file_name'] = $personal_picture;
		}

		$this->load->library('upload', $config);

		return ( ! $this->upload->do_upload('PersonalPicture') )
			? ['status'=>'error', 'message' => $this->upload->display_errors()]
			: ['status'=>'success', 'upload_data' => $this->upload->data()];

	}
		
	public function lookup_collection ()
	{
		$this->datatable_collection( 1 );
	}
	
	public function datatable_collection( $Status = 1 )
	{
		$start = $this->input->get_post('start', true);
        $length = $this->input->get_post('length', true);
        $order = $this->input->get_post('order', true);
        $columns = $this->input->get_post('columns', true);
        $search = $this->input->get_post('search', true);
        $draw = $this->input->get_post('draw', true);
		
		$db_from = "{$this->family_model->table} a";
		$db_where = array();
		$db_like = array();
		
		//prepare defautl flter
		$db_where['a.Status'] = $Status;

				
		// preparing default
		if( isset($search['value']) && ! empty($search['value']) )
        {
            $keywords = $this->db->escape_str( $search['value'] );
			
			$db_like[ $this->db->escape_str("a.NoFamily") ] = $keywords;
			$db_like[ $this->db->escape_str("a.NoKK") ] = $keywords;
			$db_like[ $this->db->escape_str("a.ReffNoFamily") ] = $keywords;
			$db_like[ $this->db->escape_str("a.Address") ] = $keywords;
			$db_like[ $this->db->escape_str("b.PersonalName") ] = $keywords;	 
        }
		
		// get total records
		$this->db->from( $db_from );
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		$records_total = $this->db->count_all_results();
		
		// get total filtered
		$this->db
			->from( $db_from )
			->join("{$this->personal_model->table} b", "a.PersonalIdKK = b.Id", "INNER")
			;
		if( !empty($db_where) ){ $this->db->where( $db_where ); }
		if( !empty($db_like) ){ $this->db->group_start()->or_like( $db_like )->group_end(); }		
		$records_filtered = $this->db->count_all_results();
		
		// get result filtered
		$db_select = <<<EOSQL
			a.*,
			b.PersonalName
EOSQL;

		$this->db
			->select( $db_select )
			->from( $db_from )
			->join("{$this->personal_model->table} b", "a.PersonalIdKK = b.Id", "INNER")
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
		
		response_json( $output );
    }
	
	public function lookup_purchase_order( $is_ajax_request=false )
	{	
		if( $this->input->is_ajax_request() || $is_ajax_request !== false )
		{
			$this->load->view( 'family/lookup/lookup_personal', $this->data );
		}
	}
	
	public function family_personal( $id = NULL )
	{
		
		$id = ($id) ? $id : $this->input->post_get("id");
		
		if( $this->input->is_ajax_request() )
		{
			
			$data['collection'] = family_helper::get_family_personal( $id );
			$data['item'] = $item = $this->family_model->get_one( $id );
			$data['nameroutes'] = $this->nameroutes;
			$data['add_personal_url'] = base_url("{$this->nameroutes}/personal/{$item->Id}");
			$this->load->view( 'family/personal/details', $data );		
		}
	}
	
	public function autocomplete()
	{
		$post_data = $this->input->post();
		$db_like = ['NoFamily' => $post_data['query'], 'NoKK' => $post_data['query'], 'PersonalName' => $post_data['query'] ];
		$db_where = @$post_data['FamilyId'] ? ['a.Id !=' => @$post_data['FamilyId']] : NULL;
		
		$result = family_helper::get_all( $db_like, $db_where );
		
		$collection = [];
		if( ! empty($result) )
		{
			foreach( $result as $row )
			{
				 $collection[] = [
					"Id"	=> $row->Id,
					"NoFamily"	=> $row->NoFamily,
					"NoKK"	=> $row->NoKK,
					"PersonalName"	=> $row->PersonalName
				];
			}
		}
		
		response_json( $collection );	
    }
}

