<?php
require_once('includes.php');

if (isset($_POST['hiddenField'])) {
	showArray($_POST);
	exit();
}

$livedCitiesOptions['chelsea'] = "Chelsea, Oklahoma";
$livedCitiesOptions['shawnee'] = "Shawnee, Oklahoma";
$livedCitiesOptions['vian'] = "Vian, Oklahoma";
$livedCitiesOptions['vinita'] = "Vinita, Oklahoma";

$myForm = new Form(4,"My Form", TRUE);
//$myForm->setWidth(4);
//$myForm->setName("My Form");


$myForm->addButton('Submit Form');
View::addContent($myForm->returnForm());
View::sendPage();

