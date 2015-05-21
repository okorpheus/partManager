<?php
require_once('includes.php');

if (!$currentUser->isAdmin()) {
	UserMessageQueue::addMessage('danger', 'Maintenance functions require admin privileges');
	redirect('index.php');
}
switch ($_GET['a']) {
	case 'rescanDirectory':
		$addedCount = PartFactory::addMissingDbEntries();
		break;
		
	case 'cleanDatabase':
		$removedCount = PartFactory::removeMissingFilesFromDB();
		break;
		
	case 'all':
		$addedCount = PartFactory::addMissingDbEntries();
		$removedCount = PartFactory::removeMissingFilesFromDB();
}

redirect('index.php');