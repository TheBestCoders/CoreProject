<?php

class AuthController extends CoreController
{
	public $auth = false;
	public $page_title = "User Authentication";
	
	public function index()
	{
		//print_this($this->session);

		// logout
		if($this->page_name=='logout'){
			unset($this->session->user);
			$this->session->user = array('user_logged_in' => false);
			Core::redirect('login.php');
		}
		
		// auto-redirect if already logged in
		if(isset($this->session->user->user_id)){
			Core::redirect('my_account.php');
		}
		
		// registration
		if(Core::isSubmit('user_registration')){
			//$captch_error = 0;
		//	if (!empty($_REQUEST['captcha'])) {
		//		if (empty($_SESSION['captcha']) || trim(strtolower($_REQUEST['captcha'])) != $_SESSION['captcha']) {
		//			$error .= 'Invalid captcha.';
		//			$captch_error = 1;
		//		}
		//	}
			$post_vars = array(
				'firstname' => Core::getValue('firstname'),
				'lastname' => Core::getValue('lastname'),
				'email' => Core::getValue('email'),
				'password' => Core::getValue('password'),
				'city' => Core::getValue('city'),
				'country' => Core::getValue('country')
			);
			
			$user = new User();
			$user->firstname = $post_vars['firstname'];
			$user->lastname = $post_vars['lastname'];
			$user->email = $post_vars['email'];
			$user->password = $post_vars['password'];
			$user->city = $post_vars['city'];
			$user->country = $post_vars['country'];
			
			if($user->addNewUser() && $user->error==''){
				$subject = 'Thanks for registration with '._SITE_NAME_;
				
				$vars = array(
					'user_name' => $user->firstname,
					'email' => $user->email,
					'password' => $user->password
				);
				
				$mail = Core::sendMail('register', $vars, $subject, $user->email, $user->firstname.' '.$user->lastname);
				
				$user_data = array(
					'user_logged_in' => true,
					'user_id' => $user->user_id,
					'firstname' => $user->firstname,
					'lastname' => $user->lastname,
					'fullname' => $user->firstname.' '.$user->lastname,
					'email' => $user->email,
					'last_login' => $user->last_login,
				);
				$this->session->user = $user_data;
				
				if(Core::getValue('back', '') != ''){
					Core::redirect(Core::getValue('back'));
				} else {
					Core::redirect($this->link->getBackLink(Core::getValue('back')));
				}
				
			} else {
				Core::displayError($user->error);
			}
		}
		
		
		// login
		if(Core::isSubmit('user_login')){
			$user = new User();
			if($user->loginUser(Core::getValue('email'), Core::getValue('password'))){
				$user_data = array(
					'user_logged_in' => true,
					'user_id' => $user->user_id,
					'firstname' => $user->firstname,
					'lastname' => $user->lastname,
					'fullname' => $user->firstname.' '.$user->lastname,
					'email' => $user->email,
					'last_login' => $user->last_login,
				);
				//print_this($user_data);
				$this->session->user = $user_data;
				
				
				if(Core::getValue('back', '') != ''){
					Core::redirect($this->link->getBackLink(Core::getValue('back')));
				} else {
					Core::redirect('my_account.php');
				}
			} else {
				Core::displayError($user->error);
			}
		}
	}
	
	public function setMedia()
	{
		$this->addJS('validation.js');
	}
}

?>