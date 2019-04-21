<?php

$chat_id = 293854654;
$text = $_POST['text'];

file_get_contents('https://api.telegram.org/bot762331141:AAGztjW4kC40IHXY8yY3SrRjeVDtVeM0V0U/sendMessage?chat_id=' . $chat_id . '&text=' . urlencode($text) );