<?php
require_once('includes.php');
if (!$currentUser->isAdmin()) {
	UserMessageQueue::addMessage('danger', 'Maintenance functions require admin privileges');
	redirect('index.php');
}

if (isset($_POST['editUserID'])) processEditForm();
elseif (isset($_GET['id'])) showEditForm($_GET['id']);
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
	if (strlen($_POST['newPassword'])) {
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