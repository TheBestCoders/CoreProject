<?php 
error_reporting(E_ALL);

require_once ('includes/config/config.php');
require_once ('homeconn.php');
require_once ('includes/classes/dbclass.php');
require_once ('includes/define/define.php');
require_once ('includes/functions/functions.php');

$objDB = new dbClass();

// generating ticket when email arrive

/* Sales */
$department_id = 1; // sales
$assign_to = 7;
/* connect to gmail */
$hostname = '{imap.gmail.com:993/imap/ssl}INBOX';
/*$username = 'test@click2resume.com';
$password = 'hello@test';*/

$username = 'support@click2resume.com';
$password = 'click2@123';

genTicket($hostname, $username, $password, $department_id, $assign_to);
 
/* Sales ends */









/* creating ticket and sending mail */
function genTicket($hostname, $username, $password, $department_id = 0, $assign_to)
{
	global $objDB;
	
	$cnt = 0;
	/* try to connect */
	$inbox = imap_open($hostname,$username,$password) or write_log(json_encode(print_r(imap_errors())));
	/* grab emails */
	$emails = imap_search($inbox,'UNSEEN'); // ALL, UNSEEN, SEEN 
	/* if emails are returned, cycle through each... */
	if($emails && count($emails) > 0) {
		/* put the newest emails on top */
		rsort($emails);
		
		/* for every email... */
		foreach($emails as $email_number) {
			ob_start();
			$cnt++; // counter
			
			/* get information specific to this email */
			$overview = imap_fetch_overview($inbox,$email_number,0);
			$message = imap_fetchbody($inbox,$email_number, 1.2);
			echo 'found at: 1.2';
			
			if(!strlen($message)>0){
				$message = imap_fetchbody($inbox,$email_number, 1.1);
				echo 'found at: 1.1';
			}
			if(!strlen($message)>0){
				$message = imap_fetchbody($inbox,$email_number, 2);
				echo 'found at: 2';
			}
			if(!strlen($message)>0){
				$message = imap_fetchbody($inbox,$email_number, 1);
				echo 'found at: 1';
			}
			
			
			if(strlen($message)>0){
				$attachment = '';
				//echo $message;
				$existAttachments = existAttachment($inbox, $email_number);
				
				if($existAttachments && count($existAttachments) > 0)
				{
					$attachment = $existAttachments['filename'];
				}
				$message = quoted_printable_decode($message);
				$message = strip_tags($message, '<a><br>');
				
				$message = $objDB->escape($message);
				//$message = html_entity_decode($message);
				
				/*echo '<pre>';
				print_r($existAttachments);
				echo $attachment.' -';
				echo $message; exit;*/
				
				/*var_dump($message);
				$flag_staus = imap_setflag_full($inbox, $email_number, "\\UNSEEN");
				
				continue;*/
				//exit;
				
				$header = imap_header($inbox, $overview[0]->msgno);
				
				$flag_staus = imap_setflag_full($inbox, $email_number, "\\Seen");
				
				$subject = isset($overview[0]->subject) ? $overview[0]->subject : 'No Subject';
				$from_name = $overview[0]->from;
				$from_email = $header->from[0]->mailbox.'@'.$header->from[0]->host;
				$date = date('Y-m-d H:i:s', strtotime($overview[0]->date));
				$ticket_number = strtoupper(uniqid('C2RTK'));
				$type = 3;
				$priority = 3;
				$subadmin_id = $assign_to;
				$member_id = 0;
				
				/*$sql = "SELECT `mid` FROM mailer_admin WHERE mailer_admin_type > 1 AND mailer_department = '".$department_id."' AND zone_id = '0' LIMIT 1";
				$subadmin_result = $objDB->query($sql);
				$subadmin_id = $objDB->fetch_assoc($subadmin_result);*/
				
				// gtting user id from email
				$mem_sql = "SELECT id FROM user_registration WHERE email_address = '".$from_email."' AND `status` = '1'";
				$mem_result = $objDB->query($mem_sql);
				if($mem_result){
					$mem_id = $objDB->fetch_assoc($mem_result);
					$member_id = (int)$mem_id['id'];
				}
				
				if(strpos($subject, 'urgent') || strpos($message, 'urgent'))
					$priority = 4;
				
				
				// checking ticket already exits according to email
				$ticket_id = 0;
				$already_sql = "SELECT id, ticket_number FROM submit_ticket WHERE user_email = '".$from_email."' AND member_id = '".$member_id."' AND STATUS = '1' ORDER BY creation_date DESC LIMIT 1;";
				$already_result = $objDB->query($already_sql);
				if($objDB->num_rows()>0){
					$result = $objDB->fetch_assoc($already_result);
					$ticket_id = (int)$result['id'];
					if(!empty($result['ticket_number']))
						$ticket_number = $result['ticket_number'];
				}
				
				$data = array();
				if(!$ticket_id || $ticket_id == 0){
					$data = array(
							'user_name' => $from_name,
							'user_email' => $from_email,
							'subject' => 0,
							'custom_subject' => $subject,
							'creation_date' => $date,
							'modified_date' => $date,
							'ticket_number' => $ticket_number,
							'cat_id' => $department_id,
							'priority' => $priority,
							'status' => '1',
							'member_id' => $member_id,
							'type' => $type,
							'assign_to' => $subadmin_id,
							'assign_date' => $date
						);
					//print_r($data);
					$objDB->insert_into_table('submit_ticket', $data);
				} else {
					$objDB->query("UPDATE submit_ticket SET modified_date = NOW() WHERE id = '".$ticket_id."'");
				}
				
				$data2 = array(
							'support_id' => 0,
							'matter' => $message,
							'image' => $attachment,
							'created_date' => $date,
							'ticket_id' => $ticket_number,
							'approved' => '1'
						);
				
				$objDB->insert_into_table('submit_ticket_trans', $data2);
				
				// sending mail
				$to = $from_email;
				$from = $username; // department email
				$m_subject = 'Ticket ID: '.$ticket_number.': '.$subject;
				$message = 'Thank you for contacting click2resume.com. A support ticket has now been opened for your request. You will be notified when a response is made by email. The details of your ticket are shown below. <br/><br/>
				Ticket Number: '.$ticket_number.'<br>
				Subject: '.$subject.'<br>
				Visit: <a href="'.DIR_FS_CATALOG.'conversion.php?ticket_number='.$ticket_number.'">Click Here</a>
				';
				
				//send_mail($to, $from, $m_subject, $message);
				
				echo '#'.$cnt.' Name:'.$from_name.', Email:'.$from_email.', Ticket #:'.$ticket_number.', UserID:'.$member_id.', Department:'.$department_id.', Date:'.$date;
				
				$log = ob_get_contents();
				ob_end_clean();
				write_log($log);
			} else {
				$flag_staus = imap_setflag_full($inbox, $email_number, "\\Seen");
			}
		}
	}

	/* close the connection */
	imap_close($inbox);
	// ends
}

function existAttachment($inbox, $email_number)
{
	$structure = imap_fetchstructure($inbox, $email_number, FT_UID);
	if(isset($structure->parts)){
		$parts = $structure->parts;
		
		$attach = false;
		foreach($parts as $part){
			if (isset($part->disposition)){
				if (strtolower($part->disposition) == 'attachment'){
					$fileData = array();
					$fileData['encoding'] = $part->encoding;
					$fileData['name'] = $part->dparameters[0]->value;
					$fileData['filetype'] = $part->subtype;
					$fileData['filesize'] = $part->bytes;
					
					$filename = $part->dparameters[0]->value;
					//echo '<p>' . $part->dparameters[0]->value . '</p>';
					// here you can create a link to the file whose name is  $part->dparameters[0]->value to download it 
					
				
					$mege = imap_fetchbody($inbox, $email_number, 2);
					
					$new_name = strtoupper(uniqid('C2RTKMAIL'));
					$ext = substr($filename, strrpos($filename, '.'));
					$att = $new_name.$ext;
					
					$fp = fopen(dirname(__FILE__).'/att/'.$att,"w");
					$data = getdecodevalue($mege, $part->type);
					fputs($fp,$data);
					fclose($fp);
					
					$fileData['filename'] = $att;
					$attach = true;
					
					return $fileData;
				}
			}
		}
	}
}

function getphpdecodevalue($message, $coding){
	switch($coding) {
		/*case 0:
		case 1:
			$message = imap_8bit($message);
		break;
		case 2:
			$message = imap_binary($message);
		break;*/
		case 3:
		case 5:
			$message= base64_decode($message);
		break;
		case 4:
			$message = quoted_printable_decode($message);
		break;
	}
	return $message;
}

function getdecodevalue($message,$coding) {
	switch($coding) {
		case 0:
		case 1:
			$message = imap_8bit($message);
		break;
		case 2:
			$message = imap_binary($message);
		break;
			case 3:
			case 5:
			$message=imap_base64($message);
		break;
		case 4:
			$message = imap_qprint($message);
		break;
	}
	return $message;
}

// ----------------------------------------------LOG---------------------------------------
function write_log($_log=''){
	$myFile = "log/cron_mail_log_".date('Y_m_d').".txt";
	$fContent = fopen($myFile, 'a+');
	$fDataString = "\n".$_log." -:- ".date('d-m-Y H:i:s')."\n";
	fwrite($fContent, $fDataString);
	fclose($fContent);
	echo $fDataString.'<br />';
}

?>