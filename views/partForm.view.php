<?php 
function partForm($id = null) {
	if ($id) {
		$part = PartFactory::getByID($id);
	}
	$songTitle = $part->getSongTitle();
	$partName = $part->getPartName();
	$fileName = $part->getFileName();
	$fileID = $part->getID();
	$content = <<< EOF
	<div class='row'>
	<div class='col-sm-4 hidden-xs'></div>
	<div class='col-sm-4' col-xs-12'>
	<form method='post' action=''>
		<input type='hidden' name='id' value='$id'>
		<div class='form-group'>
			<label for='fileID'>ID</label>
			<p class='form-control'>$fileID</p>
		</div>
		<div class='form-group'>
			<label for='songTitle'>Song Title</label>
			<input type='text' name='songTitle' id='songTitle' class='form-control' placeholder='Song Title' value='$songTitle'>
		</div>
		<div class='form-group'>
			<label for='partName'>Part Name</label>
			<select name='partName' class='form-control'>
				<option value=''>None</option>
EOF;
	$content .= PartFactory::partOptions($partName);
	$content .= <<< EOF
			</select>
		</div>
		<div class='form-group'>
			<label for='fileName'>Filename</label>
			<p class='form-control'>$fileName</p>
		</div>
		<button type='submit' class='btn btn-primary'>Submit</button>
	</form>
	</div>
	<div class='col-sm-4 hidden-xs'></div>
	</div>
EOF;
	View::addContent($content);
}
?>