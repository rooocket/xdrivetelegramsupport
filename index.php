<?php
ini_set('display_errors',1);
error_reporting(E_ALL);
include("Tl.php");

$telegramAPI = new Tl();
$updates = $telegramAPI->getUpdates();

echo date('H.i:s', time()) . '<br>';


var_dump($updates);