<?php 
 
// Update the path below to your autoload.php, 
// see https://getcomposer.org/doc/01-basic-usage.md 
require_once 'twilio/vendor/autoload.php'; 
 
use Twilio\Rest\Client; 
 
$sid    = "ACba9bef90b6a40080a511ca078e7a0227"; 
$token  = "faf1141db25137cd2f7959e07c32ebc2"; 
$twilio = new Client($sid, $token); 
 
$message = $twilio->messages ->create("+21622997398",array("body" => "plz work")); 
 
print($message->sid);
/*
require_once 'messagebird/vendor/autoload.php';
$messagebird = new MessageBird\Client('DrHEfGJJJmK3FyjiB3SJNeNa9');
$message = new MessageBird\Objects\Message;
$message->originator = '+21651650798';
$message->recipients = [ '+21622997398' ];
$message->body = 'Hi! This is your first message';
$response = $messagebird->messages->create($message);
var_dump($response);/*

