<?php
require_once('includes.php');
$content = '<form method="post" action="">';
$content .= '<textarea name="inputText"></textarea>';
$content .= '<input type="submit" name="submit" value="submit">';
$content .= '</form>';
View::addContent($content);

/**
$string = <<< EOF
matt,password-matt,Matt Young,matt@mattyoung.us,0
alicia,password-alicia,Alicia Young,alicia@mattyoung.us,0
roy,password-roy,Roy Young,roy@mattyoung.us,0
lela,password-lela,Lela Young,lela@mattyoung.us,0
EOF;
**/

$string = $_POST['inputText'];
$array = preg_split ('/$\R?^/m', $string);
$rowOn = 0;
foreach ($array as $line) {
	$array[$rowOn] = explode(',',$line);
	$rowOn ++;
}

View::addContent(showArray($array));
View::sendPage();