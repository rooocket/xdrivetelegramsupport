<?php


class Tl
{

    private $url     = 'https://api.telegram.org/bot762331141:AAGztjW4kC40IHXY8yY3SrRjeVDtVeM0V0U';

    public function getUpdates() {
        $response = $this->url . '/getUpdates';
        return json_decode($response);
    }

    public function sendMessage($chat_id, $text) {
        //send
//        $response = $this->query('sendMessage',
//            [
//                'text' => $text,
//                'chat_id' => $chat_id,
//                'parse_mode' => 'html'
//            ]
//        );
//        return $response;
    }

}