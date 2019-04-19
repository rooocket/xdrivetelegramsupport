<?php


class Tl
{

    private $url     = 'https://api.telegram.org/bot762331141:AAGztjW4kC40IHXY8yY3SrRjeVDtVeM0V0U';
    protected $updateId;

    public function getUpdates() {


        $response = json_decode(file_get_contents($this->url . '/getUpdates?offset=' . $this->updateId));
/*
        if(!empty($response->result)) {
            $this->updateId = $response->result[count($response->result) - 1]->update_id;
        }
*/
        return $response->result;
    }

    public function sendMessage($chat_id, $text) {
        $response = fopen($this->url . "/sendMessage?chat_id=" . $chat_id ."&parse_mode=html&text='".$text . "''","r");
        return $response;
    }

}