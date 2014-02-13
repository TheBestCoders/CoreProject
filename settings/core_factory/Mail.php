<?php

date_default_timezone_set('Asia/Kolkata');

class Mail
{
	public $_host;
	public $_port;
	public $_username;
	public $_password;
	public $_to;
	public $_toName;
	public $_from;
	public $_fromName;
	public $_subject;
	public $_message;
	private $mail_type;
	public $error = '';
	public $debug = 0;
	
	public function Mail($to = NULL, $toName = NULL, $from = NULL, $fromName = NULL, $subject = NULL, $message = NULL){
		$this->_to = $to;
		$this->_toName = $toName;
		$this->_from = $from;
		$this->_fromName = $fromName;
		$this->_subject = $subject;
		$this->_message = $message;
		$this->mail_type = _DEFAULT_MAIL_TYPE_;
		$this->_host = _MAIL_SMTP_;
		$this->_port = _MAIL_PORT_;
		$this->_username = _MAIL_USERNAME_;
		$this->_password = _MAIL_PASSWORD_;
		
		if(!Core::isEmail($this->_to)){
			$this->error .= 'Please provide a valid email: '.$this->_to;
			return false;
		}
	}
	
	public function Send(){
		
		if($this->mail_type == 'SMTP'){
			if(empty($this->error) && $this->error==''){
				$mail = new PHPMailer();
				$mail->IsSMTP();							// telling the class to use SMTP
				$mail->SMTPDebug	= $this->debug;					// enables SMTP debug information (for testing)
															// 0 = disable
															// 1 = errors and messages
															// 2 = messages only
				$mail->SMTPAuth 	= true; 				// enable SMTP authentication
				$mail->SMTPSecure	= "ssl";				// sets the prefix to the servier
				$mail->Host			= $this->_host;			// sets GMAIL as the SMTP server
				$mail->Port			= $this->_port;			// set the SMTP port for the GMAIL server
				$mail->Username		= $this->_username;		// GMAIL username
				$mail->Password		= $this->_password;		// GMAIL password
				
				$mail->SetFrom($this->_from, $this->_fromName);
				//$mail->AddReplyTo($this->_to, $this->_toName);
				$mail->Subject    = $this->_subject;
				$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
				$mail->MsgHTML($this->_message);
				$mail->AddAddress($this->_to, $this->_toName);
				//if(!empty($this->_cc_email) && !empty($this->_cc_name))
					//$mail->AddCC($this->_cc_email, $this->_cc_name);
				
				//$mail->AddAttachment("Path to uploaded files");
				
				if($mail->Send()) {
					return true;
				} else {
					$this->error .= "Mailer Error: " . $mail->ErrorInfo;
				}
			}
		} elseif($this->mail_type == 'MIME'){
			$mail = new HTMLMimeMail();
			$mail->setFrom($this->_from);
			$mail->setSubject($this->_subject);
			$mail->setHtml($this->_message);
			//$mail->setCc();
			$result = $mail->send(array($this->_to));
			if($result) {
				return true;
			} else {
				$this->error .= "Mailer Error: " . $mail->errors;
				return false;
			}
		} else {
			// To send HTML mail, the Content-type header must be set
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			
			// Additional headers
			$headers .= 'To: '.$this->_toName.' <'.$this->_to.'>'."\r\n";
			$headers .= 'From: '.$this->_fromName.' <'.$this->_from.'>'."\r\n";
			//$headers .= 'Cc: wonder_a7@yahoo.co.in' . "\r\n";
			//$headers .= 'Bcc: alauddin_ansari@live.com' . "\r\n";
			$mail = mail($this->_to, $this->_subject, $this->_message, $headers);
			if($mail){
				return true;
			} else {
				$this->error .= "Mailer Error: There is an error in sending mail.";
				return false;
			}
		}
	}
	
}

?>