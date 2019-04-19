<?php


class Tl
{

    private $url     = 'https://api.telegram.org/bot762331141:AAGztjW4kC40IHXY8yY3SrRjeVDtVeM0V0U';

    public function getUpdates() {
        $response = json_decode(file_get_contents($this->url . '/getUpdates'));
        return $response->result;
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