<?php

define("BASE_PATH", __DIR__ . DIRECTORY_SEPARATOR);

class collector
{

    public ?array $folders =  [
        "publ/",
        "core/helper/",
        "bots/",
        "bots/webhook/",



    ];
    public ?string $state;

    function __construct($bot_name = "")
    {
        $this->buildApp();
    }
    public function collectRequest()
    {
        if (!isset($_GET['b']) || empty($_GET['b'])) {
            $this->state = "GET request does not found.";
        } else {
            $b = $_GET['b'];
            $this->state = "GET request found.";    
        }


        if (file_exists(BASE_PATH . "public/webhook/$b.bot.php")) {
            $request_file = BASE_PATH . "public/webhook/$b.bot.php";

            include_once($request_file);

            $this->state = "GET request and file found.";
        } else {
            $this->state = "GET request found but noting to call.";

        }
    }







    public static function buildApp()
    {
        foreach (self::$folders as $folder) {
            if (!file_exists(BASE_PATH . $folder)) {
                mkdir(BASE_PATH . $folder);
            }
        }
        $this->_load_robot();
    }
    public function _include_php($folder)
    {
        foreach (glob("{$folder}/*.php") as $filename) {
            require_once $filename;
        }
    }
    public function _load_robot()
    {


        foreach (self::$folders as $folder) {
            self::_include_php(BASE_PATH . $folder);
        }
    }
}
