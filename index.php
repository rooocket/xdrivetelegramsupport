<?php
ini_set('display_errors',1);
error_reporting(E_ALL);
include("Tl.php");

function sendMessage($chat_id, $message, $first_name)
{
    $keyboard = '';
    //Проверяем, была ли регистрация?
    if(file_exists('https://xdrive.faberlic.com/files/telegram_reg/' . $chat_id . '.txt')) {

    } else {
        //Просим прислать номер телефона
        $keyboard = '';
        $mes = preg_replace('![^0-9]+!', '', $message);
        $mes = strlen($mes);
        if($mes > 10) {
            $message = 'Вы ввели номер телефона: ' . $message . ' (' . strlen($mes) . ')';
        } elseif($mes == 4){
            $message = 'Вы код подтверждения: ' . $message . ' (' . strlen($mes) . ')';
        } else {
            $message = $first_name . ', требуется регистрация, нажмите на кнопку Отправить сообщение';
            $keyboard = array(
                "keyboard" => array(array(
                    array(
                        "text" => "Отправить номер телефона",
                        "request_contact" => true

                    )
                )),
                "one_time_keyboard" => true,
                "resize_keyboard" => true
            );

        }

        file_get_contents($GLOBALS['api'] . '/sendMessage?chat_id=' . $chat_id . '&text=' . urlencode($message) . (!empty($keyboard) ? '&reply_markup=' . json_encode($keyboard) : ''));
    }


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

}

$access_token = '762331141:AAGztjW4kC40IHXY8yY3SrRjeVDtVeM0V0U';
$api = 'https://api.telegram.org/bot' . $access_token;


$output = json_decode(file_get_contents('php://input'), TRUE);
$chat_id = $output['message']['chat']['id'];
$first_name = $output['message']['chat']['first_name'];
$message = $output['message']['text'];

//$preload_text = $first_name . ', я получила ваше сообщение!' . $message;
sendMessage($chat_id, $message, $first_name);
