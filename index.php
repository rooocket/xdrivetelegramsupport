<?php

function sendMessage($chat_id, $message)
{
    file_get_contents($GLOBALS['api'] . '/sendMessage?chat_id=' . $chat_id . '&text=' . urlencode($message));
}

$access_token = '762331141:AAGztjW4kC40IHXY8yY3SrRjeVDtVeM0V0U';
$api = 'https://api.telegram.org/bot' . $access_token;


$output         = json_decode(file_get_contents('php://input'), TRUE);
$chat_id        = $output['message']['chat']['id'];
$contact        = isset($output['message']['contact']['phone_number']) ? $output['message']['contact']['phone_number'] : '';
$first_name     = $output['message']['chat']['first_name'];
$message        = $output['message']['text'];
$message_t      = '';

$number = preg_replace('![^0-9]+!', '', $message);
if(!empty($contact)) {
    $message_t = 'На Ваш номер телефона +' . $contact . ' отправлено SMS-сообщение с кодом доступа. Такой же номер телефона у вас должен быть указан в проекте xDrive/';
}

elseif(strlen($number) == 4) {
    $message_t = 'Началась проверка введенного кода доступа';
}

else {

    $keyboard = array(
        "keyboard" => array(
            array(
                array(
                    "text" => "contact",
                    "request_contact" => true // Данный запрос необязательный telegram button для запроса номера телефона

                )
            )
        ),
        "one_time_keyboard" => true, // можно заменить на FALSE,клавиатура скроется после нажатия кнопки автоматически при True
        "resize_keyboard" => true // можно заменить на FALSE, клавиатура будет использовать компактный размер автоматически при True
    );

    $message_t = 'Привет, ' . $first_name . '. Меня зовут xDriveSupportBot. Чтобы мной воспользоваться, необходимо подтвердить свой номер телефона. Такой же номер телефона должен быть указан в вашем личном кабинете xDrive. Нажмите на кнопку отправить номер телефона под клавиатурой.&reply_markup=' . json_encode($keyboard);
}

sendMessage($chat_id, $message_t);
