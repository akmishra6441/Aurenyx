<?php
require_once 'core/config.php';

$_SESSION = array();

session_destroy();

header('location: login.php');
exit();