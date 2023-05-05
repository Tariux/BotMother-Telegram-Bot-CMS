<?php

class soundcloudDL extends bot
{

    function __construct($token)
    {


        parent::__construct($token);


        if (!isset($this->qtid)) {
            echo 'you cant do it in browser.';
            exit;
        }

        $this->init();
    }

    public function init()
    {

        $client_id = "RV0HWX0CL63E6Saup2ina7vQ4t6h9M1M";

        $sc = new SoundCloudPHP;

        $url_api = 'https://api-v2.soundcloud.com/resolve?url=' . $this->input_text . '&client_id=' . $client_id;


        $json = file_get_contents($url_api);

        $obj = json_decode($json);


        if (empty($obj)) {

            preg_match_all('#\bhttps?://soundcloud[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#', $sc->rquest($this->input_text), $match);
            $detected_url = $match[0][0];
            $this->input_text = $detected_url;
            $this->init();
        }


        if (empty($obj->permalink)) {

            return false;
        }

        $tmp_file = TMP_PATH . $obj->user->permalink . DIRECTORY_SEPARATOR;



        $artwork =  $tmp_file . $obj->permalink . '.jpg';

        if (file_exists($artwork)) {
            $this->sdk->sendPhoto([
                'chat_id' => $this->qtid,
                'photo' => $artwork,
                'caption' => $obj->title
            ]);
        } else {

            mkdir(TMP_PATH . $obj->user->permalink, 0755, true);
            $large_artwork = str_replace("-large", "-t500x500", $obj->artwork_url);
            file_put_contents($artwork, file_get_contents($large_artwork)); // save artwork to host

            $this->sdk->sendPhoto([
                'chat_id' => $this->qtid,
                'photo' => $artwork,
                'caption' => $obj->title
            ]);
        }












        $music_path = $tmp_file . $obj->title . '.mp3';

        if (file_exists($music_path)) {
            $this->sdk->sendAudio([
                'chat_id' => $this->qtid,
                'audio' => $music_path,
            ]);
        } else {


            $data_music = $sc->getMusicInfo($this->input_text);

            file_put_contents($music_path, file_get_contents($sc->getMusic($data_music['media']['transcodings'][1]['url']))); // save music to host


            $this->sdk->sendAudio([
                'chat_id' => $this->qtid,
                'audio' => $music_path,
            ]);
        }
    }
}



class SoundCloudPHP
{

    const API_INFO = 'https://api-v2.soundcloud.com';
    public $client_id;

    public function _getHome()
    {

        return $this->rquest('https://soundcloud.com/');
    }

    public function _getScripts()
    {

        $home = $this->_getHome();

        preg_match_all('/<script[^>]+src="([^"]+)/m', $home, $matchs);

        return $matchs[1];
    }

    public function searchClientId($script)
    {

        $data = $this->rquest($script);
        preg_match_all('/client_id\s*:\s*"([0-9a-zA-Z]{32})"/m', $data, $match);

        return $match[1][0] ?? null;
    }

    public function getMusicInfo($url)
    {
        foreach ($this->_getScripts() as $script) {

            $result = $this->searchClientId($script);

            if (is_null($result)) {
                continue;
            }

            $this->client_id = $result;
        }

        $info = json_decode($this->rquest(self::API_INFO . "/resolve?url={$url}&client_id={$this->client_id}"), true);

        if (isset($info['media'])) {
            for ($i = 0; $i < count($info['media']['transcodings']); $i++) {

                $url = $info['media']['transcodings'][$i]['url'];
                $info['media']['transcodings'][$i]['url'] = "{$url}?client_id={$this->client_id}";
            }
        }

        return $info;
    }

    public function getMusic($url)
    {

        $data = @json_decode($this->rquest($url), true);
        return $data['url'] ?? null;
    }

    public function rquest($url, $method = 'GET', $data = null, $header = [])
    {

        $connect = curl_init();

        curl_setopt($connect, CURLOPT_URL, $url);
        curl_setopt($connect, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($connect, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($connect, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($connect, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36');
        curl_setopt($connect, CURLOPT_HTTPHEADER, $header);

        if ($method == 'POST') {

            curl_setopt($connect, CURLOPT_POST, true);
            curl_setopt($connect, CURLOPT_POSTFIELDS, $query);
        }

        $request = curl_exec($connect);

        if ($request === false) {
            throw new Exception(curl_error($connect));
        }

        return $request;
    }
}


$sc = new soundcloudDL("6043862679:AAGQ9RhrU_nBrsp67l9X4wmc6fq-_ODZP-U");
