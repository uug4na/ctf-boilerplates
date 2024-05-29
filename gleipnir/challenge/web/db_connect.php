<?php

include_once 'config.php';

$con = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
$GLOBALS['con'] = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

if($con === false){
	die("ERROR: Could not connect. " . $mysqli->connect_error);
}
?>