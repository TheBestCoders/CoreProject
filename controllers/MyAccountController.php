<?php

global $user_id;

$success = Core::getValue('success', '');
$edit = false;
$editpwd = false;

if(Core::getValue('edit')){
	$edit = true;
	
	if(Core::isSubmit('edit_user')){
		$data = array(
					'firstname' => Core::getValue('firstname'),
					'lastname' => Core::getValue('lastname'),
					'email' => Core::getValue('email'),
					'modified_date' => $now
				);
		$user = new User($user_id);
		if($user->updateInfo($data))
			Core::redirect($_SERVER['PHP_SELF'].'?success=1');
		else
			Core::displayError('Data not updated. Please try again.');
	}
}

if(Core::getValue('chgpsw')){
	$edit = true;
	$editpwd = true;
	
	if(Core::isSubmit('edit_password')){
		$user = new User($user_id);
		if($user->updatePassword(Core::getValue('c_password'), Core::getValue('n_password')))
			Core::redirect($_SERVER['PHP_SELF'].'?success=2');
		else
			Core::displayError('Current Password is wrong.');
	}
}

$user = new User($user_id);

?>