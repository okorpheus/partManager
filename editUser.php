<?php
require_once('includes.php');


if (isset($_POST['editUserID'])) {
	if (!$currentUser->isAdmin() AND $_POST['editUserID'] != $currentUser->getID()) {
		UserMessageQueue::addMessage('danger', 'You are not allowed to edit other users');
		redirect('index.php');
		exit();
	}
	processEditForm();
}

elseif (isset($_GET['id'])) {
	
	if (!$currentUser->isAdmin() AND $_GET['id'] != $currentUser->getID()) {
		UserMessageQueue::addMessage('danger', 'You are not allowed to edit other users');
		redirect('index.php');
		exit();
	}
	showEditForm($_GET['id']);
}

else {
	UserMessageQueue::addMessage('danger', 'No user specified');
	redirect('users.php');
	exit();
}

function processEditForm() {
	showArray($_POST);
	$editUser = UserFactory::findByID($_POST['editUserID']);
	$editUser->setUsername($_POST['newUsername']);
	$editUser->setRealName($_POST['newRealName']);
	$editUser->setEmail($_POST['newEmail']);
	if(isset($_POST['newIsAdmin'])) $editUser->grantAdmin();
	else $editUser->revokeAdmin();
	if (strlen($_POST['newPassword']) > 0) {
		$editUser->changePassword($_POST['newPassword'], $_POST['newPasswordConfirm']);
	}
	$editUser->writeToDB();
	redirect('users.php');
}

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