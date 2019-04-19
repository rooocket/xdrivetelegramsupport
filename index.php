<?php
ini_set('display_errors',1);
error_reporting(E_ALL);
include("Tl.php");

$telegramAPI = new Tl();

//Получаем сообщения
$updates = $telegramAPI->getUpdates();

//Проходим по сообщения
foreach($updates as $update) {
    $telegramAPI->sendMessage($update->message->chat->id, 'This time ' . date('H.i.s', time()));
}

//$telegramAPI->sendMessage(293854654, 'Hello');
//$result = fopen("https://api.telegram.org/bot762331141:AAGztjW4kC40IHXY8yY3SrRjeVDtVeM0V0U/sendMessage?chat_id=293854654&parse_mode=html&text='hello'","r");