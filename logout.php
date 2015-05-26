<?php
require_once('includes.php');
Logger::makeEntry('Logout');
$currentUser = NULL;
session_destroy();
redirect('index.php');