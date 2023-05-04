<?php

class bot extends collector {

    public ?object $sdk = null;

    protected ?array $data;

    protected ?array $input_message;
    public ?string $qtid = null;
    public $input_text;

    function __construct($_token)
    {
        $this->sdk = new Telegram\Bot\Api($_token);

        $this->data = json_decode(file_get_contents("php://input"), TRUE);


        if (
            !isset($this->data) ||
            $this->data === false // ||
        ) {
            $this->state = "php://input does not found.";
        } else {
            $this->qtid = $this->data["message"]["chat"]["id"];
            $this->input_message = $this->data["message"];
            $this->input_text = $this->input_message['text'];
    
    
        }


    }
}