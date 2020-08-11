<?php
//Основной файл

echo 'v.07.06.2019-1';
header('Content-Type: text/html; charset= utf-8');
include('query.php');
$query = new Query();

//https://xdrivetelegramsupport.herokuapp.com/index.php

//158010101 - Саша Иванов
//293854654 - Саша Жаров
$admin_array = array(158010101, 293854654); //для теста

//Меня запросов
//create_complaint - Создать жалобу
//application_status - Статус заявки


function sendMessage($chat_id, $message, $param)
{
    $t = '%E2%80%8B%E2%80%8B%E2%80%8B%E2%80%8B%E2%80%8B%E2%80%8B%E2%80%8B%E2%80%8B%E2%80%8B%E2%80%8B%E2%80%8B';
    $param = str_replace('[]','[' . $t . ']', $param);
    $send = file_get_contents($GLOBALS['api'] . '/sendMessage?chat_id=' . $chat_id . '&text=' . urlencode($message) .
        $param);

    var_dump($send);
}


$access_token = '762331141:AAGztjW4kC40IHXY8yY3SrRjeVDtVeM0V0U';
$api = 'https://api.telegram.org/bot' . $access_token;

/*
$output         = json_decode(file_get_contents('php://input'), TRUE);
$chat_id        = $output['message']['chat']['id'];
$contact        = isset($output['message']['contact']['phone_number']) ? $output['message']['contact']['phone_number'] : '';
$first_name     = $output['message']['chat']['first_name'];
$message        = $output['message']['text'];*/
$message_t      = '';
$param          = isset($_REQUEST['param']) ? $_REQUEST['param'] : '';
$parse_mode     = isset($_REQUEST['parse_mode']) ? $_REQUEST['parse_mode'] : '';
$error_text     = ' Request_error: ' . $message_t;
$chat_id = 210365779;
$file_chat_temp = 'https://xdrive.faberlic.com/files/telegram_reg/_' . $chat_id . '.txt';
$file_chat      = 'https://xdrive.faberlic.com/files/telegram_reg/' . $chat_id . '.txt';
$file_request   = 'https://xdrive.faberlic.com/files/telegram_reg/request_' . $chat_id . '.txt';
$file_sms       = 'https://xdrive.faberlic.com/files/telegram_reg/sms_' . $chat_id . '.txt';

/*
 * Отправка личного сообщения
 */

if($_REQUEST['send_message'] == 1) {
    if(!empty($parse_mode)) {
        //Обработка параметра
        $param .= '&parse_mode=' . $parse_mode;
    }
    $chat_id = isset($_REQUEST['chat_id']) ? $_REQUEST['chat_id'] : '';
    $message = isset($_REQUEST['message']) ? $_REQUEST['message'] : '';
    if(!empty($chat_id) && !empty($message)) {
        sendMessage($chat_id, $message, $param);
    }
    exit();
}
$array = array(
    'action'    => 'sms',
    'chat_id'   => $chat_id,
    'phone'     => '79687614155'
);
$q = $query->xDriveQuery($array);

var_dump($q);
if($q == 0) {
    $message_t .= 'Я не могу предоставить Вам доступ.' .$error_text . ' [' . $q . ']';
} else {
    $message_t .= 'Для завершения регистрации, пришлите код из SMS, отправленный на номер: +' . $q;
}

$array = array(
    'action'    => 'loginCreate',
    'chat_id'   => $chat_id
);
$q = $query->xDriveQuery($array);

echo '<pre>';
var_dump($q);

$message_t .= ' - ' . file_get_contents($file_chat) .  '  - ' . file_get_contents($file_chat_temp) .  '  - ';

if(!empty(file_exists($file_chat))) {
    $message_t .= '-file_create';
} else {
    $message_t .= '-file_not_create';
}
var_dump($message_t);
echo '</pre>';
sendMessage($chat_id, $message_t, $param);