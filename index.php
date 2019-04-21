<?php
include('query.php');
$query = new Query();



//158010101 - Саша Иванов
//293854654 - Саша Жаров
$admin_array = array(293854654);

file_get_contents('https://api.telegram.org/bot762331141:AAGztjW4kC40IHXY8yY3SrRjeVDtVeM0V0U/deleteWebhook');
echo 'deletted';