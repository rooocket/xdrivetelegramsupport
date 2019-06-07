<?php
//Запросы

class Query
{

    function xDriveQuery($array) {
        $url = 'https://xdrive.faberlic.com/api/api_telegram.php';
        $array['key'] = '54670a1ad18ce5842eea01499c12ed5a';
        $context = stream_context_create([
            'http' => [
                'method' => 'POST',
                'content' => http_build_query($array)
            ]
        ]);

        $result =  file_get_contents($url, false, $context);
        return $result;
    }

}