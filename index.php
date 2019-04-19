<?php
ini_set('display_errors',1);
error_reporting(E_ALL);
include("Tl.php");

$telegramAPI = new Tl();

//Получаем сообщения
$updates = $telegramAPI->getUpdates();

echo '<pre>';
var_dump($updates);
echo '</pre>';
/*
//Проходим по сообщения
foreach($updates as $update) {
    $telegramAPI->sendMessage($update->message->chat->id, 'Hello');
}*/