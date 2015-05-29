<?php
require_once('includes.php');
if (!$currentUser->isAdmin()) {
	UserMessageQueue::addMessage('danger', 'Viewing logs requires admin privileges');
	redirect('index.php');
}

$content = "<!-- Begin Log Entry Table -->";
$content .= "<table class='table table-hover'>\r\n";
$content .= "<thead>\r\n";
$content .= "<th>Timestamp</th>\r\n";
$content .= "<th>User</th>\r\n";
$content .= "<th>Part Affected</th>\r\n";
$content .= "<th>User Affected</th>\r\n";
$content .= "<th>Entry</th>\r\n";
$content .= "</thead>\r\n";
View::addContent($content);
$entries = logger::getAllEntries();
foreach ($entries as $entry) {
	View::addContent($entry->tableRow());
}
View::addContent("</table>\r\n");
View::addContent('<!-- End Log Entry Table -->');
View::sendPage();