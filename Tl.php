<?php


class Tl
{

    private $token = '762331141:AAGztjW4kC40IHXY8yY3SrRjeVDtVeM0V0U'; //url telegram

    public function query($method, $params = []) {
        $url        = 'https://api.telegram.org/bot';
        $url        .= $this->token . '/';
        $url        .= $method ;
        if(!empty($params)) {
            $k = 0;
            foreach($params as $key=>$value) {
                $url .= ($k == 0 ? '/?' : '&') . $key . '=' . $value;
                $k++;
            }
        }

        $result = fopen($url,"r");
        return $result;
    }

    public function getUpdates() {
        $response = $this->query('getUpdates');
        return json_decode($response);
    }

    public function sendMessage($chat_id, $text) {
        //send
        $response = $this->query('sendMessage',
            [
                'text' => $text,
                'chat_id' => $chat_id,
                'parse_mode' => 'html'
            ]
        );
        return $response;
    }

}