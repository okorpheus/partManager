<?php
require_once('includes.php');

// If nobody is logged in, go to the login page
if (!is_object($currentUser)) {
	redirect('login.php');
	die;
}

// Process multiple edit form if submitted
if(isset($_POST['editMultiple']) AND isset($_POST['multipleParts'])) {
	if (is_array($_POST['multipleParts'])) {
		$editParts = $_POST['multipleParts'];
		$newTitle = htmlspecialchars($_POST['updateMultiSongTitle']);
		$newPartName = htmlspecialchars($_POST['updateMultiPartName']);
		editMultipleParts($editParts, $newTitle, $newPartName);
	}
}

// Build filter options from submission
$whileArray = array();
if(isset($_POST['titleLimit']) AND $_POST['titleLimit'] != 'all') {
	$whileArray['songTitle'] = $_POST['titleLimit'];
}

if(isset($_POST['partLimit']) AND $_POST['partLimit'] != 'all') {
	$whileArray['partName'] = $_POST['partLimit'];
}

// Set page title
View::setTitle("PartManager");

// Setup option strings
$titleOptions = PartFactory::getExistingTitles(TRUE);
$partNameOptions = PartFactory::getExistingPartNames(TRUE);


// Begin Page
// Set page title
View::setTitle("PartManager");
partFilterForm();
partTableHeader();

// Table Content
$parts = PartFactory::getAllParts($whileArray);
foreach ($parts as $part) View::addContent($part->getFullRow());

multiEditForm();
partTableFooter();
View::sendPage();
exit();




/**
 * 
 * @param array $parts parts to be edited
 * @param string $newTitle
 * @param string $newPartName
 */
function editMultipleParts($parts,$newTitle,$newPartName) {
	$editParts = array();
	
	foreach ($parts as $partID=>$junk) {
		$editParts[] = PartFactory::getByID($partID);
	}
	foreach ($editParts as $editPart) {
		if (strlen($newTitle)>0) {
			$editPart->setSongTitle($newTitle);
		}
		if ($newPartName != "noChange") {
			$editPart->setPartName($newPartName);
		}
		$editPart->writeToDB();
	}
	$numberEdited = count($editParts);
	UserMessageQueue::addMessage('success', "Updated $numberEdited parts");
}

function partFilterForm() {
	$titleOptions = PartFactory::getExistingTitles(TRUE);
	$partNameOptions = PartFactory::getExistingPartNames(TRUE);
	$content = <<< FILTERTABLE
	<form class='form-inline' method='post' action=''>
		<div class='form-group'>
			<label class='sr-only' for='titleLimit'>Title</label>
			<select name='titleLimit' id='titleLimit'>
				<option value='all'>-- SHOW ALL TITLES --</option>
				$titleOptions
			</select>
		</div>
		<div class='form-group'>
			<label class='sr-only' for='partLimit'>Part</label>
			<select name='partLimit' id='partLimit'>
				<option value='all'>-- SHOW ALL PARTS --</option>
				$partNameOptions
			</select>
		</div>
		<button type="submit" class="btn btn-primary">Filter Parts</button>
	</form>
FILTERTABLE;
	View::addContent($content);
}

function partTableHeader() {
	$content = <<< PARTTABLEHEAD
	<form method='post' action='' class='form-inline'>
	<table class='table table-hover'>
		<thead>
			<tr>
				<th></th>
				<th>ID</th>
				<th></th>
				<th></th>
				<th>Title</th>
				<th>Part</th>
				<th>Filename</th>
				<th>Date Added</th>
			</tr>
		</thead>
		<tbody>
PARTTABLEHEAD;
	View::addContent($content);
}

function multiEditForm() {
	global $currentUser;
	$partNameOptions = PartFactory::getExistingPartNames(TRUE);
	$content = <<< MULTIEDITFORM
	<tr class='info'>
		<td colspan='4'></td>
		<td>
MULTIEDITFORM;
	if ($currentUser->isAdmin()) {
		$content .= <<< MULTIEDITFORM
			<div class='form-group'>
				<label class="sr-only" for="updateMultiSongTitle">Update Song Title</label>
				<input type='text' name='updateMultiSongTitle' id='updateMultiSongTitle' placeholder='New Song Title'>
			</div>
MULTIEDITFORM;
	}
	$content .= "</td><td>";
	if ($currentUser->isAdmin()) {
		$content .= <<< MULTIEDITFORM
			<div class='form-group'>
				<label class="sr-only" for="updateMultiPartName">Update Part Name</label>
				<select name='updateMultiPartName' id='updateMultiPartName'>
					<option value='noChange'>No Change to Part Name</option>
					$partNameOptions
				</select>
			</div>
MULTIEDITFORM;
	}
	$content .= "</td><td colspan='2'><input type='hidden' name='editMultiple' value='TRUE'>";		
	if ($currentUser->isAdmin()) {
		$content .= "<button type='submit' class='btn btn-primary'>Update Selected</button>";
	}		
	$content .= "</td></tr>";
	View::addContent($content);
}

function partTableFooter() {
	$content = <<< PARTTABLEFOOT
	</tbody>
</table>
</form>
PARTTABLEFOOT;
	View::addContent($content);
}

