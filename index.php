<?php

include("Tl.php");

$telegramAPI = new Tl();
$updates = $telegramAPI->getUpdates();

print_r($updates);