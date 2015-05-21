<?php
require_once('includes.php');

$currentUser = NULL;
session_destroy();
redirect('index.php');