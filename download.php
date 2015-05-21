<?php
require_once('includes.php');

// Non-users don't get to download
if (!is_object($currentUser)) {
	UserMessageQueue::addMessage('danger', 'Only users can download parts');
	redirect('login.php');
	die;
}

if (!isset($_GET['dlid'])) {
	UserMessageQueue::addMessage('danger', 'No part specified');
	redirect('index.php');
	die;
}

$part = PartFactory::getByID($_GET['dlid']);
if (!$part) {
	UserMessageQueue::addMessage('danger', 'Invalid part id');
	redirect('index.php');
	exit();
}
$part->sendFile();