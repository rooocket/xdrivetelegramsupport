<?php
ini_set('display_errors',1);
error_reporting(E_ALL);
include("Tl.php");

$telegramAPI = new Tl();
$updates = $telegramAPI->getUpdates();

print_r($updates);