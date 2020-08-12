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
$access_token = '762331141:AAGztjW4kC40IHXY8yY3SrRjeVDtVeM0V0U';
define('API','https://api.telegram.org/bot' . $access_token);

//Меня запросов
//create_complaint - Создать жалобу
//application_status - Статус заявки


function sendMessage($chat_id, $message, $param)
{
    $t = '%E2%80%8B%E2%80%8B%E2%80%8B%E2%80%8B%E2%80%8B%E2%80%8B%E2%80%8B%E2%80%8B%E2%80%8B%E2%80%8B%E2%80%8B';
    $param = str_replace('[]','[' . $t . ']', $param);
    $send = file_get_contents(API . '/sendMessage?chat_id=' . $chat_id . '&text=' . urlencode($message) . $param);
}

$output         = json_decode(file_get_contents('php://input'), TRUE);
$chat_id        = $output['message']['chat']['id'];
$contact        = isset($output['message']['contact']['phone_number']) ? $output['message']['contact']['phone_number'] : '';
$first_name     = $output['message']['chat']['first_name'];
$message        = $output['message']['text'];
$message_t      = '';
$param          = isset($_REQUEST['param']) ? $_REQUEST['param'] : '';
$parse_mode     = isset($_REQUEST['parse_mode']) ? $_REQUEST['parse_mode'] : '';
$error_text     = ' Request_error: ' . $message_t;

//Проверяем, есьб ли аккаунт в телеграме
$user_info      = $query->xDriveQuery(array('action'=>'check_user','chat_id'=>158010101));

$message_t = $user_info;

sendMessage($chat_id, $message_t, $param);