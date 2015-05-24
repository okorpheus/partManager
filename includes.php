<?php

// Autoload Classes
define('CLASS_DIR', 'classes');
define('VIEW_DIR', 'views');
set_include_path(get_include_path().PATH_SEPARATOR.CLASS_DIR);
set_include_path(get_include_path().PATH_SEPARATOR.VIEW_DIR);
spl_autoload_extensions('.class.php');
spl_autoload_register();


// Load Configuration
$config = parse_ini_file('partManager.ini');
foreach ($config as $key=>$value) {
	$constName = "PM_" . strtoupper($key);
	define($constName, $value);
}

// Create database connection
Database::connect();

// Initialize Session
ob_start();

$currentUser = NULL;
session_start();

if (isset($_SESSION['currentUserID'])) {
	$checkCurrent = UserFactory::findByID($_SESSION['currentUserID']);
	if (!is_null($checkCurrent)) {
		$currentUser = $checkCurrent;
	}
}
else {
	ob_clean();
	include('login.php');
	exit();
}

// Miscellaneous Useful Functions
function redirect($url) {
	header('Location: '.$url);
	die();
}

function showArray($array) {
	echo "<pre>";
	print_r($array);
	echo "</pre>";
}

/**
 * Code block for form input text field
 * @param string $fieldName
 * @param string $fieldLabel
 * @param string $defaultValue
 * @return string
 */
function createTextInput($fieldName,$fieldLabel,$defaultValue) {
	$content = <<< FORMBLOCK
	<div class='form-group'>
		<label for='$fieldName'>$fieldLabel</label>
		<input type='text' name='$fieldName' id='$fieldName' class='form-control' placeholder='$fieldLabel' value='$defaultValue'>
	</div>
FORMBLOCK;
	return $content;
}