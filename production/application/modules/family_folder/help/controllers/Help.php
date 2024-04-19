<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Help extends ADMIN_Controller
{
	protected $_translation = 'system';
	
	protected $_profile;
	protected $_user;
	
	public function __construct()
	{
		parent::__construct();
		
		$this->load->language('help');
	}
	
	public function index()
	{
		show_404();
	}
	
	public function ticket()
	{
		$this->load->config( "ticket" );
		
		$NOW = new DateTime("NOW");
		
		//$this->session->set_flashdata( "system.ticket", NULL );
		
		$item = (object) (array(
				'mail_to' => $this->config->item( "support_email" ),
				'mail_from' => @$this->_user->email,
				'mail_cc' => "",
				'mail_subject' => "",
				'mail_message' => "",
			));
		
		if( $sess_item = $this->session->flashdata( "system.ticket" ) ) 
		{
			$item = (object) (array(
					'mail_to' => @$sess_item->mail_to,
					'mail_from' => @$sess_item->_user->mail_from,
					'mail_cc' => @$sess_item->mail_cc,
					'mail_subject' => @$sess_item->mail_subject,
					'mail_message' => @$sess_item->mail_message,
				));
		}
		
		if( $this->input->post() )
		{
			$item = (object) ($this->input->post( "f" ));
			$this->session->set_flashdata( "system.ticket", $item );
			
			$this->load->library( 'form_validation' );
						
			$this->form_validation->set_rules('mail_from', lang('help:mail_from_label'), 'trim|required');
			$this->form_validation->set_rules('mail_to', lang('help:mail_to_label'), 'trim|required');
			//$this->form_validation->set_rules('email_cc', lang('help:mail_cc_label'), 'trim|required');
			$this->form_validation->set_rules('mail_subject', lang('help:mail_subject_label'), 'trim|required');
			$this->form_validation->set_rules('mail_message', lang('help:mail_message_label'), 'trim|required');
			$this->form_validation->set_data( (array) $item );			
			
			if( $this->form_validation->run() )
			{
				$this->session->set_flashdata( "system.ticket", NULL );
				
				// attachments
				$this->load->library('upload', array(
						'upload_path' => FCPATH."/resource/tmp",
						'allowed_types' => "xls|xlsx|doc|docx|pdf|zip|gif|jpg|png",
						'max_size' => 0,
						'overwrite' => TRUE,
						//'file_name' => sprintf("Ticket-%d", time())
					));
				if( $this->upload->do_upload() )
				{
					$attachment = (object) $this->upload->data();
					$item->attached_file = $attachment->full_path;
				} else 
				{
					// $this->upload->display_errors();
				}
				
				// check copy
				if( $this->input->get_post( "send_copy" ) )
				{
					$item->email_cc = ( $item->email_cc ) ? ($item->email_cc.", ".@$this->_user->email) : $item->email_cc;				
				}
				
				// send mail
				Modules::run( "fomailer/send_email", ((array) $item) );
				
				// delete attachment file
				if( ($item->attached_file) && (is_file($item->attached_file)) )
				{
					//@unlink($item->attached_file);
				}
				
				make_flashdata(array(
						'response_status' => 'success',
						'message' => lang('help:send_ticket_success')
					));		
				redirect( 'system/ticket' );
			} else
			{
				make_flashdata(array(
						'response_status' => 'error',
						'message' => $this->form_validation->get_all_error_string()
					));
			}
		}
		
		$data = array(
				'page' => lang("help:ticket_title"),
				'form' => TRUE,
				'summernote' => TRUE,
				'mail' => $item
			);
		
		$this->template
			->set('p_heading', lang("help:ticket"))
			->title(lang("help:heading")." - ".lang("help:ticket"))
			->set_breadcrumb(lang("help:heading"))
			->set_breadcrumb(lang("help:ticket"))
			->build( 'ticket/form', (isset($data) ? $data : NULL) );
	}
}