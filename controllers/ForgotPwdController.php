<?php

$password_send = false;
$password_reset = false;
$reset_mode = false;
$email = '';

if($reset = Core::getValue('reset') && $email = Core::getValue('email'))
{
	$email = Core::getValue('email');
	$reset = Core::getValue('reset');
	
	$user = Db::getInstance()->getValue('SELECT user_id FROM '.front_users.' WHERE email = "'.$email.'" AND password = "'.$reset.'"');
	if($user){
		$reset_mode = true;
	} else {
		Core::displayError('Invalid password reset');
	}
}

if(Core::isSubmit('forgot_password'))
{
	$email = Core::getValue('email');
	$user = new User();
	
	if(empty($email)){
		Core::displayError('Please enter your email.');
	} elseif(!Core::isEmail($email)){
		Core::displayError('Invalid email.');
	} elseif(!$user->emailExist($email)){
		Core::displayError('Email not exists.');
	} else {
		
		$user_detail = $user->getUserByEmail($email);
		
		//print_this($user_detail);
		
		$subject = 'Password Request for '._SITE_NAME_.'';
		
		$vars = array(
			'email' => $user_detail->email,
			'page_link' => $link->getPageLink('forgot_password.php?reset='.$user_detail->password.'&email='.$user_detail->email)
		);
		
		$mail = Core::sendMail('forgotpassword', $vars, $subject, $user_detail->email, $user_detail->firstname.' '.$user_detail->lastname);

		if($mail){
			$password_send = true;
		} else {
			Core::displayError('There are some problem in sending email. Please try after some time.');
		}
	}
}

if(Core::isSubmit('reset_password') && $reset_mode)
{
	$email = Core::getValue('email');
	$reset = Core::getValue('reset');
	$new = Core::getValue('n_password');
	$c_pswd = Core::getValue('c_password');
	
	$user = new User();
	
	if(empty($new)){
		Core::displayError('Password cannot be empty.');
	} elseif(empty($c_pswd)){
		Core::displayError('Also re-type your password.');
	} elseif($new != $c_pswd){
		Core::displayError('Both password must be same entered.');
	} elseif(empty($reset)){
		Core::displayError('Invalid password reset');
	} elseif(empty($email)){
		Core::displayError('Email cannot be empty.');
	} elseif(!Core::isEmail($email)){
		Core::displayError('Invalid Email.');
	} elseif(!$user->emailExist($email)){
		Core::displayError('Email not exists.');
	} else {
		
		$user_detail = $user->getUserByEmail($email);
		
		if($user_detail->password!=$reset){
			Core::displayError('Invalid password reset');
		} else {
			$update = $user->changePassword($user_detail->user_id, $new);
			if($update){
				$password_reset = true;
			} else {
				Core::displayError('Password not reset successfully. Please try again.');
			}
		}
	}
}


?>