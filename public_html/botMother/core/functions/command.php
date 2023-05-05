<?php

class command
{

    function __construct()
    {
    }

    static function on($command , $input_text)
    {
        if ($input_text == $command) {
            return true;
        } else {
            return false;
        }
    }
}
