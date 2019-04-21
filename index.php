<?php
include('query.php');
$query = new Query();

//158010101 - Саша Иванов
//293854654 - Саша Жаров
$admin_array = array(293854654);

//Меня запросов
//create_complaint - Создать жалобу
//application_status - Статус заявки


function sendMessage($chat_id, $message, $param)
{
    file_get_contents($GLOBALS['api'] . '/sendMessage?chat_id=' . $chat_id . '&text=' . urlencode($message) . $param);
}


$access_token = '762331141:AAGztjW4kC40IHXY8yY3SrRjeVDtVeM0V0U';
$api = 'https://api.telegram.org/bot' . $access_token;


$output         = json_decode(file_get_contents('php://input'), TRUE);
$chat_id        = $output['message']['chat']['id'];
$contact        = isset($output['message']['contact']['phone_number']) ? $output['message']['contact']['phone_number'] : '';
$first_name     = $output['message']['chat']['first_name'];
$message        = $output['message']['text'];
$message_t      = '';
$param          = '';

/*
 * Проверяем была регистрация или нет
 */

//Регистрация состоялась и в файле записан номер телефона
if(!empty(file_get_contents('https://xdrive.faberlic.com/files/telegram_reg/' . $chat_id . '.txt'))) {
    /******************************************************************************/
    if(!empty(file_get_contents('https://xdrive.faberlic.com/files/telegram_reg/request_' . $chat_id . '.txt'))) {
        $type = file_get_contents('https://xdrive.faberlic.com/files/telegram_reg/request_' . $chat_id . '.txt');
        if($type == 'create_complaint') {
            preg_match_all("/([0-9]*)(.*)/",$message,$m_arr);
            if(!empty($num_arr[1]) && !empty($num_arr[2])) {
                $array = array(
                    'action'    => 'add',
                    'number'    => $num_arr[1],
                    'text'      => $num_arr[2],
                    'chat_id'   => $chat_id
                );
                $q = $query->xDriveQuery($array);

                $message = 'Вашей заявке присвоен номер ' . $q . '. Мы отправим вам ответ в ближайшее время.';
            } else {
                $message = 'Не правильно введен запрос. Пример, 71******* слишком молодая девушка';
            }
        }
        elseif($type == 'application_status') {

        }
        else {
            $message = 'Ошибка выполнения запроса. Напишите администратору @br0dobro и мы вам поможем!';
        }
    }
    /******************************************************************************/
    elseif($message == 'Создать жалобу') {
        $message = $first_name . ", Введите 9-тизначный регистрационный номер заявки и текст сообщения, по которой у вас жалоба.\n\nПример, 71******* слишком молодая девушка";
        $array = array(
            'action'    => 'request',
            'type'      => 'create_complaint',
            'chat_id'   => $chat_id
        );
        $q = $query->xDriveQuery($array);
    }
    /******************************************************************************/
    elseif ($message == 'Статус заявки') {

    }
    /******************************************************************************/
    else {
        $keyboard = array(
            "keyboard" => array(
                array(
                    array(
                        "text" => "Создать жалобу"
                    ),
                    array(
                        "text" => "Статус заявки"
                    )
                )
            ),
            "one_time_keyboard" => false, // можно заменить на FALSE,клавиатура скроется после нажатия кнопки автоматически при True
            "resize_keyboard" => false // можно заменить на FALSE, клавиатура будет использовать компактный размер автоматически при True
        );

        $message_t = "Привет, " . $first_name . ".\n\nЕсли хотите пожаловаться на заявку - Нажмите на кнопку «Создать жалобу», если хотите проверить статус выполнения заявки - «Статус заявки» под клавиатурой." . $message;
        $param = '&reply_markup=' . json_encode($keyboard);
    }


} else {

    $number = preg_replace('![^0-9]+!', '', $message);
    /******************************************************************************/
    if(!empty($contact)) {

        $array = array(
            'action'    => 'sms',
            'chat_id'   => $chat_id,
            'phone'     => $contact
        );
        $q = $query->xDriveQuery($array);

        if($q == 0) {
            $message_t = 'Я не могу предоставить Вам доступ. Напишите администратору @br0dobro и мы вам поможем!';
        } else {
            $message_t = 'На Ваш номер телефона ' . $contact . ' отправлено SMS-сообщение с кодом доступа.';
        }

    }
    /******************************************************************************/
    elseif(strlen($number) == 4) {
        $code = file_get_contents('https://xdrive.faberlic.com/files/telegram_reg/sms_' . $chat_id . '.txt');

        if($code == $message) {
            $message_t = 'Ваш аккаунт активирован';
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

sendMessage($chat_id, $message_t, $param);