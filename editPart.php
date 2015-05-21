<?php
require_once('includes.php');
if (!$currentUser->isAdmin()) {
	UserMessageQueue::addMessage('danger', 'Maintenance functions require admin privileges');
	redirect('index.php');
}
if (isset($_POST['id'])) {
	$id = htmlspecialchars($_POST['id']);
	$songTitle = htmlspecialchars($_POST['songTitle']);
	$partName = htmlspecialchars($_POST['partName']);
	processEditForm($id, $songTitle, $partName);
	redirect('index.php');
	exit();
}
elseif (isset($_GET['id'])) showEditForm($_GET['id']);
else redirect('index.php');




function showEditForm($editPartID) {
	$editPart = PartFactory::getByID($editPartID);
	if(!$editPart) {
		UserMessageQueue::addMessage('danger', 'No such part id');
		redirect('index.php');
		exit();
	}
	include_once('partForm.view.php');
	partForm($editPartID);
	View::setTitle("Edit Part");
	View::sendPage();
	die();
}

/**
 * 
 * @param int $id Part ID to be edited
 * @param string $songTitle New songTitle for the part
 * @param string $partName New partName for the part
 */
function processEditForm($id,$songTitle,$partName) {
	$editPart = PartFactory::getByID($id);
	if(!$editPart) {
		UserMessageQueue::addMessage('danger', 'No such part id');
		redirect('index.php');
		exit();
	}
	$editPart->setSongTitle($songTitle);
	$editPart->setPartName($partName);
	$editPart->writeToDB();
	UserMessageQueue::addMessage('success', 'Part Updated Successfully');
}