<?php

$chat_id = $_REQUEST['chat_id'];
$text = $_REQUEST['text'];

file_get_contents('https://api.telegram.org/bot762331141:AAGztjW4kC40IHXY8yY3SrRjeVDtVeM0V0U/sendMessage?chat_id=' . $chat_id . '&text=' . urlencode($text) );

echo $chat_id . ' / ' . $text;

///