<?php
ini_set('display_errors',1);
error_reporting(E_ALL);
include("Tl.php");




/*
$telegramAPI = new Tl();

while(true) {

    sleep(10);


//Получаем сообщения
    $updates = $telegramAPI->getUpdates();

    echo '<pre>';
    var_dump($updates);
    echo '</pre>';
//Проходим по сообщения
    foreach($updates as $update) {
        $text = 'Ты мне написал: ' . $update->message->text;
        $telegramAPI->sendMessage($update->message->chat->id, $text);
    }


}

*/

function sendMessage($chat_id, $message)
{
    file_get_contents($GLOBALS['api'] . '/sendMessage?chat_id=' . $chat_id . '&text=' . urlencode($message));
}

$access_token = '762331141:AAGztjW4kC40IHXY8yY3SrRjeVDtVeM0V0U';
$api = 'https://api.telegram.org/bot' . $access_token;


$output = json_decode(file_get_contents('php://input'), TRUE);
$chat_id = $output['message']['chat']['id'];
$first_name = $output['message']['chat']['first_name'];
$message = $output['message']['text'];

$preload_text = $first_name . ', я получила ваше сообщение!';
sendMessage($chat_id, $preload_text);
/*
$res = file_get_contents('https://api.telegram.org/bot762331141:AAGztjW4kC40IHXY8yY3SrRjeVDtVeM0V0U/setwebhook?url=https://xdrivetelegramsupport.herokuapp.com/index.php');

echo '<pre>';
var_dump($res);
echo '</pre>';
*/
//$telegramAPI->sendMessage(293854654, 'Hello');
//$result = fopen("https://api.telegram.org/bot762331141:AAGztjW4kC40IHXY8yY3SrRjeVDtVeM0V0U/sendMessage?chat_id=293854654&parse_mode=html&text='hello'","r");