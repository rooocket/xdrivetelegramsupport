<?php


class Tl
{

    private $token = '762331141:AAGztjW4kC40IHXY8yY3SrRjeVDtVeM0V0U';

    public function query($method, $params = []) {
        $url        = 'https://api.telegram.org/bot';
        $url        .= $this->token . '/';
        $url        .= $method . '/';
        $context    = stream_context_create([
            'http' => [
                'method' => 'GET',
                'content' => http_build_query($params)
            ]
        ]);
        $result =  file_get_contents($url, false, $context);
        return json_encode($result);
    }

    public function getUpdates() {
        $response = $this->query('getUpdates');
        return $response;
    }

    public function getMessage() {

    }

}