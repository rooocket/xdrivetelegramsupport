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
    return $send;
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
$user_info      = $query->xDriveQuery(array('action'=>'check_user','chat_id'=>$chat_id));

if($user_info) {
    $message_t .= $user_info;
} else {
    //Формируем регистрацию
    $number = preg_replace('![^0-9]+!', '', $message); //Осталяем только цифры
    /******************************************************************************/
    if(!empty($contact)) {

        $array = array(
            'action'    => 'sms',
            'chat_id'   => $chat_id,
            'phone'     => $contact
        );
        $q = $query->xDriveQuery($array);

        if($q == 0) {
            $message_t = 'Я не могу предоставить Вам доступ.' .$error_text . ' [' . $q . ']';
        } else {
            $message_t = 'Для завершения регистрации, пришлите код из SMS, отправленный на номер: +' . $q;
        }

    }
    /******************************************************************************/
    elseif(strlen($number) == 4) {
        $array = array(
            'action'    => 'request',
            'chat_id'   => $chat_id
        );
        $code = $query->xDriveQuery($array);

        if($code == $message) {
            $message_t = 'Ваш аккаунт активирован. Отправьте /menu для получения инструкции.';
            $array = array(
                'action'    => 'loginCreate',
                'chat_id'   => $chat_id
            );
            $q = $query->xDriveQuery($array);
        } else {
            $message_t = 'Вы ввели неверный пароль для подтверждения.';
        }
    }
    /******************************************************************************/
    elseif($message == '/chat_id') {
        $message_t = 'Ваш Chat_id: ' . $chat_id;
    }
    /******************************************************************************/
    else {

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
}
if($contact) {
    $message_t .= ' Contact: ' . $contact;
}
sendMessage($chat_id, $message_t, $param);