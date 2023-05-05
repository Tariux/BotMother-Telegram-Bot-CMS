<?php
// hi im J
define("BASE" , __DIR__ . DIRECTORY_SEPARATOR);
define("TMP_PATH" , __DIR__ . DIRECTORY_SEPARATOR . "store/tmp" . DIRECTORY_SEPARATOR);

class collector
{

    public ?array $folders =  [
        "core/functions/",
    ];
    public ?string $state;

    function __construct($bot_name = "")
    {
        $this->buildApp();
        $this->collectRequest();
    }
    public function collectRequest()
    {
        if (!isset($_GET['b']) || empty($_GET['b'])) {
            $this->state = "GET request does not found.";
        } else {
            $b = $_GET['b'];
            $this->state = "GET request found.";    
        }


        if (file_exists(BASE . "public/webhook/$b.bot.php")) {
            $request_file = BASE . "public/webhook/$b.bot.php";

            include_once($request_file);

            $this->state = "GET request and file found.";
        } else {
            $this->state = "GET request found but noting to call.";

        }

        echo $this->state;

        MainFunctions::hi();
    }







    public function buildApp()
    {

        require_once BASE . "public/vendor/autoload.php";
        require_once BASE . "core/bot/bot.php";

        foreach ($this->folders as $folder) {
            if (!file_exists(BASE . $folder)) {
                mkdir(BASE . $folder);
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
        foreach ($this->folders as $folder) {
            $this->_include_php(BASE . $folder);
        }
    }
}

new collector;
