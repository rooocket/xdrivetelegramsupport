<?php
ini_set('display_errors',1);
error_reporting(E_ALL);
include("Tl.php");

$telegramAPI = new Tl();

//while(true) {
//
//    sleep(10);
//
//
//}


//Получаем сообщения
$updates = $telegramAPI->getUpdates();

echo '<pre>';
var_dump($updates);
echo '</pre>';
//Проходим по сообщения
foreach($updates as $update) {
    $text = 'Ты мне написал: ' . $update->message->chat->text;
    $telegramAPI->sendMessage($update->message->chat->id, $text);
}



//$telegramAPI->sendMessage(293854654, 'Hello');
//$result = fopen("https://api.telegram.org/bot762331141:AAGztjW4kC40IHXY8yY3SrRjeVDtVeM0V0U/sendMessage?chat_id=293854654&parse_mode=html&text='hello'","r");