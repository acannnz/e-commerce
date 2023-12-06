<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Fomailer extends Admin_Controller 
{
	public function __construct()
	{
		parent::__construct();
		
		$this->load->model('mailer_model','mail_m');
	}
	
	public function send_email($params)
	{
		if($this->config->item('use_postmark') == 'TRUE')
		{
        	// If postmark API is being used
			$this->load->library('postmark');
        	
			$this->postmark->from( $params['mail_from'] );
			$this->postmark->reply_to( $params['mail_from'] );
			$this->postmark->to( $params['mail_to'] );
			if( isset($params['mail_cc']) && ($params['mail_cc'] != '') )
			{
				$this->postmark->cc( $params['mail_cc'] );
			}
			
			$this->postmark->subject( $params['mail_subject'] );
			$this->postmark->message_plain( $params['mail_message'] );
			$this->postmark->message_html( $params['mail_message'] );
			if( isset($params['attached_file']) && ($params['attached_file'] != '') )
			{ 
				$this->postmark->attach($params['attached_file']);
			}
			
        	$this->postmark->send();

    	} else 
		{
    		// If using SMTP
			if ($this->config->item('protocol') == 'smtp') 
			{
				$this->load->library('encrypt');
				$raw_smtp_pass =  $this->encrypt->decode($this->config->item('smtp_pass'));
				$config = array(
						'smtp_host' => config_item('smtp_host'),
						'smtp_port' => config_item('smtp_port'),
						'smtp_user' => config_item('smtp_user'),
						'smtp_pass' => $raw_smtp_pass,
						'crlf' 		=> "\r\n",    							
						'protocol'	=> config_item('protocol'),
					);						
			}
				
			// Send email 
			$config['mailtype'] = "html";
			$config['newline'] = "\r\n";
			$config['charset'] = 'utf-8';
			$config['wordwrap'] = TRUE;
			
			$this->load->library( 'email', $config );
			
			$this->email->from( $params['mail_from'] );
			$this->email->reply_to( $params['mail_from'] );
			$this->email->to( $params['mail_to'] );
			if( isset($params['mail_cc']) && ($params['mail_cc'] != '') )
			{
				$this->email->cc( $params['mail_cc'] );
			}
			
			$this->email->subject( $params['mail_subject'] );
			$this->email->message( $params['mail_message'] );
			if( isset($params['attached_file']) && ($params['attached_file'] != '') )
			{ 
				$this->email->attach($params['attached_file']);
			}
			
			$this->email->send();
    	}
	}
}

