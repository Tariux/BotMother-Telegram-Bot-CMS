<?php


/*
$bot = new bot($token);


$bot->sdk->sendMessage(['chat_id' => '6180523987' , 'text' => $bot->input_text]); // Admin user id



if ($bot->onCommand("/start")) {
    $bot->sdk->sendMessage(['chat_id' => '6180523987' , 'text' => 'Start called. yes 11']); // Admin user id
} 


if ($bot->onCommand("/start")) {
    $bot->sdk->sendMessage(['chat_id' => '6180523987' , 'text' => 'Start called. yes 22']); // Admin user id
} 



$command->on('test' , '/start');

function test()
{
    $token = "6194057432:AAFRXwfvv1wuzKdKFOgWhmq89yvXZxFmKjM";

    $bot = new bot($token);

    $bot->sdk->sendMessage(['chat_id' => '6180523987' , 'text' => 'Start called. yes 33']); // Admin user id

}

*/



class startBot extends bot
{

    function __construct($token)
    {
        parent::__construct($token);

        $this->hi();

    }
    public function hi()
    {
        if (command::on('/start' , $this->input_text)) {
            $this->sdk->sendMessage(['chat_id' => '6180523987' , 'text' => 'class ran.']); // Admin user id

        }

    }
    
}

$token = "6194057432:AAFRXwfvv1wuzKdKFOgWhmq89yvXZxFmKjM";
new startBot($token);




 
