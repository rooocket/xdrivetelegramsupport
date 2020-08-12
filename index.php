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
define('API','https://api.telegram.org/bot' . $access_token);

//Меня запросов
//create_complaint - Создать жалобу
//application_status - Статус заявки


function sendMessage($chat_id, $message, $param)
{
    $t = '%E2%80%8B%E2%80%8B%E2%80%8B%E2%80%8B%E2%80%8B%E2%80%8B%E2%80%8B%E2%80%8B%E2%80%8B%E2%80%8B%E2%80%8B';
    $param = str_replace('[]','[' . $t . ']', $param);
    $send = file_get_contents(API . '/sendMessage?chat_id=' . $chat_id . '&text=' . urlencode($message) .
        $param);

    var_dump($send, $GLOBALS['api']);
}
sendMessage(293854654, 'hello', '');

$access_token = '762331141:AAGztjW4kC40IHXY8yY3SrRjeVDtVeM0V0U';
$api = 'https://api.telegram.org/bot' . $access_token;

exit();
$output         = json_decode(file_get_contents('php://input'), TRUE);
$chat_id        = $output['message']['chat']['id'];
$contact        = isset($output['message']['contact']['phone_number']) ? $output['message']['contact']['phone_number'] : '';
$first_name     = $output['message']['chat']['first_name'];
$message        = $output['message']['text'];
$message_t      = '';
$param          = isset($_REQUEST['param']) ? $_REQUEST['param'] : '';
$parse_mode     = isset($_REQUEST['parse_mode']) ? $_REQUEST['parse_mode'] : '';
$error_text     = ' Request_error: ' . $message_t;

if(!empty($contact)) {
    $message_t = $contact;
} else {
    $keyboard = array(
        "keyboard" => array(
            array(
                array(
                    "text" => "Отправить номер телефона",
                    "request_contact" => true // Данный запрос необязательный telegram button для запроса номера телефона

                )
            )
        ),
        "one_time_keyboard" => false, // можно заменить на FALSE,клавиатура скроется после нажатия кнопки автоматически при True
        "resize_keyboard" => false // можно заменить на FALSE, клавиатура будет использовать компактный размер автоматически при True
    );

    $message_t = "Привет, " . $first_name . ".\n\nМеня зовут xDriveSupportBot. Чтобы мной воспользоваться, необходимо подтвердить свой номер телефона. Такой же номер телефона должен быть указан в вашем личном кабинете xDrive.\n\nНажмите на кнопку «Отправить номер телефона» под клавиатурой.";
    $param = '&reply_markup=' . json_encode($keyboard);
}
sendMessage($chat_id, $message_t, $param);