<?php
ini_set('display_errors',1);
error_reporting(E_ALL);
include("Tl.php");

function sendMessage($chat_id, $message, $first_name)
{
    /*
        if($message == 'hi' || $message == 'привет') {
            $message = $first_name . ', я приветствую тебя. Для перехода в меню введи /start или /menu';
        }
        elseif($message == '999-999'){
             $url = 'https://xdrive.faberlic.com/api/api_telegram.php';
            $context = stream_context_create([
                'http' => [
                    'method' => 'POST',
                    'content' => http_build_query([

                        'key' 			=> '54670a1ad18ce5842eea01499c12ed5a',	//ключ
                        'action' 		=> 'CheckRegistration',	                //метод
                        'login' 		=> $message,							//телефон
                        'chat_id' 		=> $chat_id,							//телефон
                    ])
                ]
            ]);

            file_get_contents($url, false, $context);
        }

        else {
            $message = $first_name . ', Ты написал: ' . $message;
        }
    */

    $message = $first_name . ', требуется регистрация в боте';
    $keyboard = array(
        "keyboard" => array(array(array(
            "text" => "/button"

        ),
            array(
                "text" => "contact",
                "request_contact" => true // Данный запрос необязательный telegram button для запроса номера телефона

            ),
            array(
                "text" => "location",
                "request_location" => true // Данный запрос необязательный telegram button для запроса локации пользователя

            )

        )),
        "one_time_keyboard" => true, // можно заменить на FALSE,клавиатура скроется после нажатия кнопки автоматически при True
        "resize_keyboard" => true // можно заменить на FALSE, клавиатура будет использовать компактный размер автоматически при True
    );


    file_get_contents($GLOBALS['api'] . '/sendMessage?chat_id=' . $chat_id . '&text=' . urlencode($message) . '&reply_markup');
}

$access_token = '762331141:AAGztjW4kC40IHXY8yY3SrRjeVDtVeM0V0U';
$api = 'https://api.telegram.org/bot' . $access_token;


$output = json_decode(file_get_contents('php://input'), TRUE);
$chat_id = $output['message']['chat']['id'];
$first_name = $output['message']['chat']['first_name'];
$message = $output['message']['text'];

//$preload_text = $first_name . ', я получила ваше сообщение!' . $message;
sendMessage($chat_id, $message, $first_name);
