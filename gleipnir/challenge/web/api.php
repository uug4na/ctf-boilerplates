<?php

include 'db_connect.php';
include 'functions.php';

header("Content-type: application/json");

if(isset($_GET['reset'])){
	if( reset_cards_and_balance() ){
		$response = '{"success": "true"}';
		die($response);
	}
}

if(isset($_GET['vouchers'])){
	$vouchers = getUnusedVouchers();
	$response = '{ "success": "true", "message": "' . $vouchers . '" }';
	die($response);
}

if(isset($_GET['balance'])){
	$balance = getBalance();
	$response = '{ "success": "true", "message": "' . $balance . '" }';
	die($response);
}

if(isset($_GET['buyGleipnir'])){
	$balance = getBalance();
	$required = getDefaultBalance() + 401;
	$difference = $required - $balance;
	$success = 'false';
	$message = 'You do not have enough balance! Need ' . $difference .' more to buy the Gleipnir.';
	if( $balance > $required ){
		$success = 'true';
		$message = 'Success, your flag is: HZU18{TESTFLAG}';
	}
	$response = '{ "success": "'.$success.'", "message": "' . $message . '" }';
	die($response);
}

if( empty($_GET['card']) ){
	$response = '{"success": "false", "message":"Please Enter Voucher code" }';
	die($response);
}

$card = $_GET['card'];

$balanceToBeAdded = getAmountFromVoucher($card);

if($balanceToBeAdded!='0'){
	$response = '{ "success": "true", "message":"';
	$response .= 'Balance to be added: '.$balanceToBeAdded.'<br>';
	$currentBalance = getBalance();
	$response .= 'Previous Balance: '.$currentBalance.'<br>';
	$newBalance = $currentBalance + $balanceToBeAdded;
	if(setBalance($newBalance)){
		setCardIsUsed($card);
		$response .= 'New balance is: '.getBalance();
		$response .= '" }';
		die($response);
	}
} else {
	$response = '{"success": "false", "message":"Card doesnt exist or has been used. Your Current Balance is: '.getBalance().'" }';
    die($response);
}

?>