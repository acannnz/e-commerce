<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Acyanotic_ekg extends Public_Controller
{
	protected $_translation = "chart_file";	
	protected $_model = "chart_file_m";
	protected $_page = "files_cardiology_acyanotic_ekg";
	
	private $_file_type = "cardiology_acyanotic_ekg";
	
	public function __construct()
    {
        parent::__construct();
		
		$this->load->language( "cardiology/acyanotic" );
		
		$this->load->helper( "chart_file" );
		$this->load->helper( "charts/chart" );	
    } 
	
	public function index()
	{
		if( $this->input->is_ajax_request() )
		{
			echo "<script language=\"javascript\">window.location=\"".base_url("")."\";</script>";
			exit();
		} else
		{
			redirect( base_url("") );
		}
	}
	
	public function update( $chart_number, $inline=FALSE )
	{
		if( $chart_number == '' ){ $chart_number = $this->input->get_post( 'chart_num', TRUE ); }
		
		if( $this->session->has_userdata( "applied.chart" ) )
		{
			$chart = $this->session->userdata( "applied.chart" );
		} else
		{
			if( chart_helper::find_chart($chart_number) )
			{
				$chart = chart_helper::get_chart( $chart_number );
			}
		}
		
		if( chart_file_helper::find_file_by_chart($chart_number, $this->_file_type) )
		{
			$item = chart_file_helper::get_file_by_chart($chart_number, $this->_file_type);
		} else
		{
			$item = (object) (array());
		}
		
		//print_r($item);exit(0);
		
		$data = array(
				"update_action" => base_url( "files/cardiology/acyanotic/ekg/update/{$chart_number}" ),
				"file_upload_action" => base_url( "files/cardiology/acyanotic/ekg/file_upload/{$chart_number}" ),
				"file_delete_action" => base_url( "files/cardiology/acyanotic/ekg/file_delete/{$chart_number}" ),
				"chart_number" => $chart_number,
				"item" => $item,
				"acyanotic_support" => "ekg",				
			);
		
		$redirect = base_url("examinations/proceed/{$chart_number}");
		
		if( $this->input->post() )
		{
			
			
			$form_data = $this->input->post( "f" );
			
			$this->load->library( 'form_validation' );
			$this->form_validation->set_data( $form_data );
			$this->form_validation->set_rules('file_description', lang('chart_file:description_label'), 'trim|max_length[255]');
			
			if( $this->form_validation->run() )
			{
				if( $this->get_model()->update( $form_data, array("chart_number" => $chart_number) ) )
				{
					if( $this->input->is_ajax_request() )
					{
						header("Content-Type: application/json; charset=utf-8");
						echo (json_encode(array(
								"status" => "success",
								"message" => lang('chart_file:update_success'),
								"code" => 200
							)));
						exit(0);
					} else
					{
						make_flashdata(array(
								'response_status' => 'success',
								'message' => lang('chart_file:update_success')
							));
						redirect($redirect);
					}
				} else
				{
					if( $this->input->is_ajax_request() )
					{
						header("Content-Type: application/json; charset=utf-8");
						echo (json_encode(array(
								"status" => "error",
								"message" => lang('chart_file:update_error'),
								"code" => 500
							)));
						exit(0);
					} else
					{
						make_flashdata(array(
								'response_status' => 'error',
								'message' => lang('chart_file:update_error')
							));
						redirect($redirect);
					}
				}
			} else
			{
				if( $this->input->is_ajax_request() )
				{
					header("Content-Type: application/json; charset=utf-8");
					echo (json_encode(array(
							"status" => "error",
							"message" => $this->form_validation->get_all_error_string(),
							"code" => 500
						)));
					exit(0);
				} else
				{
					make_flashdata(array(
							'response_status' => 'error',
							'message' => $this->form_validation->get_all_error_string()
						));
					redirect($redirect);
				}
			}
			
			exit(0);
		}
		
		// ajax view
		if( $this->input->is_ajax_request() )
		{
			header("Content-Type: text/plain; charset=utf-8");
			echo ($this->load->view( "cardiology/acyanotic/ekg/form/update", $data, TRUE ));
			exit(0);
		}
		
		// inline view
		if( TRUE == $inline )
		{
			return ($this->load->view( "cardiology/acyanotic/ekg/form/update", $data, TRUE ));
		}
		
		// html view
		$data["form"] = TRUE;
		$data["datepicker"] = TRUE;
		
		$this->template
			->set( "heading", lang("chart_file:update_heading") )
			->set_breadcrumb( lang("chart_file:breadcrumb"), base_url("examinations/proceed/{$chart_number}") )
			->set_breadcrumb( lang("chart_file:update_breadcrumb") )
			->build('cardiology/acyanotic/ekg/form/update', $data);
	}
	
	public function file_upload( $chart_number )
	{
		if( $chart_number == '' ){ $chart_number = $this->input->get_post( 'chart_num', TRUE ); }
		
		if( $this->session->has_userdata( "applied.chart" ) )
		{
			$chart = $this->session->userdata( "applied.chart" );
		} else
		{
			if( chart_helper::find_chart($chart_number) )
			{
				$chart = chart_helper::get_chart( $chart_number );
			}
		}
		
		if( chart_file_helper::find_file_by_chart($chart_number, $this->_file_type) )
		{
			$item = chart_file_helper::get_file_by_chart($chart_number, $this->_file_type);
		} else
		{
			$item = (object) (array());
		}
		
		$data = array(
				"item" => $item,
				"chart_number" => $chart_number,
			);
			
		$redirect = base_url("examinations/proceed/{$chart_number}");
			
		if( $_FILES )
		{
			if( $this->_file_upload( $item ) )
			{
				if( $this->input->is_ajax_request() )
				{
					header("Content-Type: application/json; charset=utf-8");
					echo (json_encode(array(
							"item" => array(
									"file_name" => $item->file_name,
								),
							"status" => "success",
							"message" => "Uploaded!",
							"code" => 200
						)));
					exit(0);
				} else
				{
					redirect($redirect);
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
					redirect($redirect);
				}
			}
		}
		
		// ajax view
		if( $this->input->is_ajax_request() )
		{
			header("Content-Type: text/plain; charset=utf-8");
			echo ($this->load->view( 
					'cardiology/acyanotic/ekg/modal/file_upload', 
					array('form_child' => $this->load->view('cardiology/acyanotic/ekg/form/upload', $data, TRUE)),
					TRUE
				));
			exit(0);
		}
		
		// html view
		$data["form"] = TRUE;
		$data["simpleupload"] = TRUE;
		
		$this->template
			->set( "heading", lang("chart_file:upload_heading") )
			->set_breadcrumb( lang("chart_file:breadcrumb"), base_url("examinations/proceed/{$chart_number}") )
			->set_breadcrumb( lang("chart_file:upload_breadcrumb") )
			->build('cardiology/acyanotic/ekg/form/upload', $data);
	}
	
	public function file_delete( $chart_number )
	{
		if( ! $this->input->is_ajax_request() )
		{
			show_error( "Not Acceptable", 406 );
		}
		
		if( $chart_number == '' ){ $chart_number = $this->input->get_post( 'chart_num', TRUE ); }
		
		if( $this->session->has_userdata( "applied.chart" ) )
		{
			$chart = $this->session->userdata( "applied.chart" );
		} else
		{
			if( chart_helper::find_chart($chart_number) )
			{
				$chart = chart_helper::get_chart( $chart_number );
			}
		}
		
		if( chart_file_helper::find_file_by_chart($chart_number, $this->_file_type) )
		{
			$item = chart_file_helper::get_file_by_chart($chart_number, $this->_file_type);
		} else
		{
			$item = (object) (array());
		}
		
		$data = array(
				"item" => $item,
				"chart_number" => $chart_number,
			);
			
		if( $this->input->post( 'confirm' ) )
		{
			$this->get_model()->update(array("file_name" => NULL), array("file_type" => $this->_file_type, "chart_number" => $chart_number));				
			
			header("Content-Type: application/json; charset=utf-8");
			echo (json_encode(array(
					"status" => "success",
					"message" => lang('global:deleted_successfully'),
					"code" => 200
				)));
			exit(0);
		}
		
		header("Content-Type: text/plain; charset=utf-8");
		echo $this->load->view( 'cardiology/acyanotic/ekg/modal/file_delete', $data, TRUE );
		exit(0);
	}
	
	protected function _file_upload( & $file )
    {
        
		
		if( $_FILES )
		{
			if ( @file_exists( $_FILES[ 'userfile' ][ 'tmp_name' ] ) || @is_uploaded_file( $_FILES[ 'userfile' ][ 'tmp_name' ] ) )
			{
				$config[ 'upload_path' ]	= './resource/patients/cardiology/acyanotic/';
				$config[ 'allowed_types' ]	= 'jpg';
				$config[ 'file_name' ]		= sprintf('PATIENT_%s_CARDILOGY_ACYANOTIC_EKG', strtoupper(str_replace(".","-",$file->mr_number)));
				$config[ 'overwrite' ]		= TRUE;
				//$config[ 'encrypt_name' ]	= TRUE;
				//$config[ 'max_size' ]		= 1024 * 8;
				
				//print_r($config);exit(0);
				
				$this->load->library( 'upload', $config );
				
				if ( ! $this->upload->do_upload() )
				{
					return FALSE;
				}
				
				$upload_data = (object) $this->upload->data();
				$file->file_name = $upload_data->file_name;
				
				$this->get_model()->update(array("file_name" => $file->file_name), array("file_type" => $this->_file_type, "chart_number" => $file->chart_number));	
			}
		}
		
		return TRUE;
    }
}


