<?php
ini_set('display_errors',1);
error_reporting(E_ALL);

include('query.php');
$query = new Query();



//158010101 - Саша Иванов
//293854654 - Саша Жаров
$admin_array = array(293854654);

$updatest = file_get_contents('https://api.telegram.org/bot762331141:AAGztjW4kC40IHXY8yY3SrRjeVDtVeM0V0U/getUpdates');
$output = json_decode($updatest);

$chat_id        = $output['message']['chat']['id'];
$contact        = isset($output['message']['contact']['phone_number']) ? $output['message']['contact']['phone_number'] : '';
$first_name     = $output['message']['chat']['first_name'];
$message        = $output['message']['text'];
$message_t      = '';
$param          = '';

