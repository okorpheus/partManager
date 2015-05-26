<?php
require_once('includes.php');

if (!isset($_POST['bulkUsers'])) showBulkAddForm();
else processBulkAddForm($_POST['bulkUsers']);

function showBulkAddForm() {
	$bulkAddForm = new Form(4,'bulkAddUsers',FALSE);
	$label = "Bulk Users<br>";
	$label .= "List each new user on one row. Use the format: <br>";
	$label .= "username,password,Real Name,Email";
	$bulkAddForm->addTextArea('bulkUsers', $label);
	$bulkAddForm->addButton('Add Users');
	View::setTitle('Bulk Add Users');
	$content = $bulkAddForm->returnForm();
	View::addContent($content);
	View::sendPage();
}

function processBulkAddForm($newUserText) {
	$rows = preg_split('/$\R?^/m', $newUserText);
	$rowon = 0;
	$newUsers = array();
	foreach ($rows as $row) {
		$thisRow = explode(',', $row);
		if(UserFactory::checkUsernameExists($thisRow[0])) {
			UserMessageQueue::addMessage('info', "Username $thisRow[0] is already in use, line skipped");
			continue;
		}
		$newUsers[] = addUser($thisRow[0],$thisRow[1],$thisRow[2],$thisRow[3]);

	}
	View::addContent(showArray($newUsers) . '<hr>');
	redirect('users.php');
}

function addUser($userName,$password,$realName,$email) {
	$newUser = UserFactory::findByID('new');
	$newUser->setUsername($userName);
	$newUser->setRealName($realName);
	$newUser->setEmail($email);
	$newUser->revokeAdmin();
	$newUser->changePassword($password, $password);
	$newUser->writeToDB();
}