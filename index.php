<?php
//Основной файл

echo 'v.27.12.2020';
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


/*
 * Отправка личного сообщения
 */

if($_REQUEST['send_message'] == 1) {

    $param          = isset($_REQUEST['param']) ? $_REQUEST['param'] : '';
    $parse_mode     = isset($_REQUEST['parse_mode']) ? $_REQUEST['parse_mode'] : '';

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



$output         = json_decode(file_get_contents('php://input'), TRUE);
$chat_id        = $output['message']['chat']['id'];
$contact        = isset($output['message']['contact']['phone_number']) ? $output['message']['contact']['phone_number'] : '';
$first_name     = $output['message']['chat']['first_name'];
$message        = trim($output['message']['text']);
$message_t      = '';
$param          = isset($_REQUEST['param']) ? $_REQUEST['param'] : '';
$parse_mode     = isset($_REQUEST['parse_mode']) ? $_REQUEST['parse_mode'] : '';
$error_text     = ' Request_error: ' . $message_t;

//Проверяем, есьб ли аккаунт в телеграме
$user_info      = $query->xDriveQuery(array('action'=>'check_user','chat_id'=>$chat_id));
$user_info_row = json_decode($user_info);

if(!empty($user_info_row) && $user_info_row->active == 1) {

    if(empty($user_info_row->request)) {

        $keyboard = array(
            "keyboard" => array(
                array(
                    array(
                        "text" => "Создать жалобу"
                    ),
                    array(
                        "text" => "Статус заявки"
                    ),
                    array(
                        "text" => "Статистика за день"
                    )
                )
            ),
            "one_time_keyboard" => false, // можно заменить на FALSE,клавиатура скроется после нажатия кнопки автоматически при True
            "resize_keyboard" => false // можно заменить на FALSE, клавиатура будет использовать компактный размер автоматически при True
        );
        $param = '&reply_markup=' . json_encode($keyboard);

        /******************************************************************************/
        if($message == 'Создать жалобу') {
            $message_t = $first_name . ", Введите 9-тизначный регистрационный номер заявки и текст сообщения, по которой у вас жалоба.\n\nПример, 71******* слишком молодая девушка";
            $array = array(
                'action' => 'request_update',
                'type' => 'create_complaint',
                'chat_id' => $chat_id
            );
            $q = $query->xDriveQuery($array);
        }
        /******************************************************************************/
        elseif ($message == 'Статус заявки') {
            $message_t = $first_name . ", введите числовой номер заявки или регистрационный номер консультанта. ";
            $array = array(
                'action' => 'request_update',
                'type' => 'application_status',
                'chat_id' => $chat_id
            );
            $q = $query->xDriveQuery($array);
        }
        /******************************************************************************/
        elseif ($message == 'Статистика за день') {
            $array = array(
                'action' => 'stat',
                'chat_id' => $chat_id
            );
            $q = $query->xDriveQuery($array);
            $message_t = $q;
        }
        /******************************************************************************/
        elseif($message == '/chat_id') {
            $message_t = 'Ваш Chat_id: ' . $chat_id;
        }
        /******************************************************************************/
        else {

            $message_t = "Привет, " . $first_name . ".\n\nЕсли хотите пожаловаться на заявку - Нажмите на кнопку «Создать жалобу», если хотите проверить статус выполнения заявки - «Статус заявки» под клавиатурой.";
        }
        /******************************************************************************/
    } else {


        $keyboard = array(
            "keyboard" => array(
                array(
                    array(
                        "text" => "Создать жалобу"
                    ),
                    array(
                        "text" => "Статус заявки"
                    ),
                    array(
                        "text" => "Статистика за день"
                    )
                )
            ),
            "one_time_keyboard" => false, // можно заменить на FALSE,клавиатура скроется после нажатия кнопки автоматически при True
            "resize_keyboard" => false // можно заменить на FALSE, клавиатура будет использовать компактный размер автоматически при True
        );

        $param = '&reply_markup=' . json_encode($keyboard);

        //Если выполняется сложный запрос с вводом
        $type = $user_info_row->request;
        if($type == 'create_complaint') {
            preg_match("/([0-9]*) (.*)/",$message,$m_arr);
            if(!empty($m_arr[1]) && !empty($m_arr[2])) {

                if(strlen($m_arr[1]) == 9) {
                    $array = array(
                        'action'    => 'add',
                        'number'    => $m_arr[1],
                        'text'      => trim($m_arr[2]),
                        'chat_id'   => $chat_id
                    );
                    $q = $query->xDriveQuery($array);


                    if($q == 0) {
                        $message_t = 'Ошибка добавления заявки. - ' . $message;
                    } elseif($q == 2) {
                        $message_t = 'В xDrive нет консультанта с регистрационным номером ' . $m_arr[1];
                    } else {
                        $message_t = 'Вашей заявке присвоен №' . $q . '. Мы отправим вам ответ в ближайшее время.';
                    }
                } else {
                    $message_t = "Ошибка!\n\nНеправильно введен регистрационный номер консультанта.\n\nПример, 71******* слишком молодая девушка";
                }


            } else {
                $message_t = "Не правильно введен запрос.\n\nПример, 71******* слишком молодая девушка";
            }
        }
        elseif($type == 'application_status') {
            $id = preg_replace('![^0-9]+!', '', $message);
            $array = array(
                'action'    => 'find',
                'id'        => $id,
                'chat_id'   => $chat_id
            );
            $q = $query->xDriveQuery($array);

            if($q == 0) {
                $message_t = 'Заявка не найдена.' . $error_text;
            } else {
                $message_t = $q;
            }

            $message_t = $q;
        }
        else {
            $message_t = 'Ошибка выполнения запроса. Тип запроса не найден. ' . $error_text;
        }
    }
} else {
    //Регистрация
    $my_request = $user_info_row->request;
    if(empty($my_request)) {
        //Первый запрос, просим ввести пару логин пароль
        $message_t = "Привет, " . $first_name . ".\n\nМеня зовут xDriveSupportBot. Чтобы мной воспользоваться, необходимо пройти регистрацию, чтобы привязать свой личный кабинет xdrive.faberlic.com к аккаунту Telegram.\n\nЗайдите свой профиль в личном кабинете https://xdrive.faberlic.com/?act=profile, скопируйте значение поля xCode и отправьте его мне.";
        $array = array(
            'action'    => 'reg_xDrive',
            'chat_id'   => $chat_id
        );
        $q = $query->xDriveQuery($array);
    } elseif ($my_request == '') {
        //Вводи логина и пароля

        $array = array(
            'action'    => 'reg_xDrive',
            'chat_id'   => $chat_id,
            'login_password' => $message
        );
        $q = $query->xDriveQuery($array);
        if($q == 1) {
            //Успешная регистрация
            $message_t = 'Вы успешно зарегистрированы в xDriveSupportBot';
            $keyboard = array(
                "keyboard" => array(
                    array(
                        array(
                            "text" => "Создать жалобу"
                        ),
                        array(
                            "text" => "Статус заявки"
                        ),
                        array(
                            "text" => "Статистика за день"
                        )
                    )
                ),
                "one_time_keyboard" => false, // можно заменить на FALSE,клавиатура скроется после нажатия кнопки автоматически при True
                "resize_keyboard" => false // можно заменить на FALSE, клавиатура будет использовать компактный размер автоматически при True
            );
            $param = '&reply_markup=' . json_encode($keyboard);

        } elseif ($q == 1003) {
            $message_t = 'Неверное значение поля xCode [' . $q . ']';
        } else {
            $message_t = 'Пришлите еще раз значение поля xCode.';
        }
    }
}










sendMessage($chat_id, $message_t, $param);
/////////////////////////////////////
exit();
if($user_info) {



    if($user_info_row->active == 0) {
        //аккаунт не активирован
        $number = preg_replace('![^0-9]+!', '', $message); //Осталяем только цифры
        if(strlen($number) == 4) {
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

                $message_t = 'Вы ввели неверный пароль для подтверждения.';
                $param = '&reply_markup=' . json_encode($keyboard);
            }
        } elseif(!empty($contact))  {
            $array = array(
                'action'    => 'sms',
                'chat_id'   => $chat_id,
                'phone'     => $contact
            );
            $q = $query->xDriveQuery($array);

            if($q == 0) {
                $message_t = 'Я не могу предоставить Вам доступ.' .$error_text . ' [' . $q . ']';
            } else {
                $phone = preg_replace('![^0-9]+!', '', $contact);
                $message_t = 'Для завершения регистрации, пришлите код из SMS, отправленный на номер: +' . $phone;
            }
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
    } else {



    }


    /******************************************************************************/

} else {
    //Формируем регистрацию
    $my_request = '';


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

sendMessage($chat_id, $message_t, $param);