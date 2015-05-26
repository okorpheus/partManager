<?php
require_once('includes.php');


function showEditForm($editUserID) {
	if (isset($_GET['delete'])) {
		$userToDelete = UserFactory::findByID($editUserID);
		$userToDelete->delete();
		UserMessageQueue::addMessage('success', "User $userToDelete->getUsername() deleted");
		redirect('users.php');
	}
	include('userForm.view.php');
	$content = userForm($editUserID);
	View::setTitle('Edit User');
	View::addContent($content);
	View::sendPage();
}