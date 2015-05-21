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

$myForm = new Form(4,"My Form");
//$myForm->setWidth(4);
//$myForm->setName("My Form");
$myForm->addHidden('hiddenField', 'secretValue');
$myForm->addStaticText('static', 'Static Text' , 'This can\'t be changed');
$myForm->addTextInput('firstName', 'First Name', '');
$myForm->addTextInput('LastName', 'Last Name', 'Young');
$myForm->addTextInput('password', 'Password', '', 'password');
$myForm->addSelect('livedCities', 'Cities Lived In', $livedCitiesOptions, 'vian');
$myForm->addCheckbox('CheckboxLabel', 'This is a checkbox', TRUE);
$myForm->addButton('Submit Form');
View::addContent($myForm->returnForm());
View::sendPage();

