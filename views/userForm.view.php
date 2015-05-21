<?php
function userForm($id = NULL) {
	$oldID = 'new';
	$oldEmail = NULL;
	$oldUsername = NULL;
	$oldRealName = NULL;
	$oldIsAdmin = NULL;
	if($id) {
		$editUser = UserFactory::findByID($id);
		if (!$editUser) {
			UserMessageQueue::addMessage('danger', 'No such user id');
			redirect('index.php');
			exit();
		}
		$oldID = $editUser->getID();
		$oldUsername = $editUser->getUsername();
		$oldEmail = $editUser->getEmail();
		$oldRealName = $editUser->getRealName();
		$oldIsAdmin = $editUser->getIsAdmin();
	}
	$userForm = new Form(4,'Edit User Form');
	$userForm->addHidden('editUserID', $oldID);
	$userForm->addTextInput('newUsername', 'Username', $oldUsername);
	$userForm->addTextInput('newRealName', 'Real Name', $oldRealName);
	$userForm->addTextInput('newPassword', 'Password (ignore to leave unchanged)', '', 'password');
	$userForm->addTextInput('newPasswordConfirm', 'Confirm Password', '', 'password');
	$userForm->addTextInput('newEmail', 'Email', $oldEmail, 'email');
	if ($oldIsAdmin == 1) $checked = TRUE;
	else $checked = FALSE;
	$userForm->addCheckbox('newIsAdmin', 'Admin', $checked);
	$userForm->addButton('Submit');
	$return = $userForm->returnForm();
	return $return;
}