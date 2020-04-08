<?php

define("PATH_ACCOUNT", dirname(__FILE__) . DIRECTORY_SEPARATOR . "accounts" . DIRECTORY_SEPARATOR);
define("PATH_CHARS",   dirname(__FILE__) . DIRECTORY_SEPARATOR . "chars" . DIRECTORY_SEPARATOR);
define("PATH_COINS",   dirname(__FILE__) . DIRECTORY_SEPARATOR . "coins" . DIRECTORY_SEPARATOR);

$post = json_decode(file_get_contents('php://input'), true);

if ($post) {
    $post["method"] = $post["method"] ?: "";
    $post["params"] = $post["params"] ?: [];
    $call           = new Call();

    if (!in_array($post["method"], $call::$callable)) {
        $call->response("Method not found!", false);
    }

    try {
        echo $call->response(call_user_func_array([$call, $post["method"]], $post["params"]));
    } catch (Exception $e) {
        echo $call->response($e->getMessage(), false);
    }

    exit;
}

class Call
{
    const URL_REFERER  = "https://mulegendary.net/";
    const URL_LOGIN    = "https://mulegendary.net/Account/Login";
    const URL_INFO     = "https://mulegendary.net/PanelUser/PanelGetInfos";
    
    protected $cookie;
    protected $ch;
    protected $username;
    protected $password;

    static public $callable = [
        "login",
        "levels",
        "coins"
    ];
    
    public function __construct()
    {
        $this->cookie = md5(uniqid(rand(), true));
        $this->ch     = curl_init();
    }

    public function __destruct()
    {
        curl_close($this->ch);
        @unlink($this->cookie);
    }

    public function login($username, $password)
    {
        $login = $this->_call(Call::URL_LOGIN, [
            "username" => $username,
            "password" => $password
        ]);

        if (!$login || !isset($login->type) || $login->type !== "success") {
            throw new Exception("Invalid username/password!");
        }

        if (file_put_contents(PATH_ACCOUNT . $username, $password)) {
            $this->username = $username;
            $this->password = $password;
            return $this->response(true);
        }

        throw new Exception("Account was not saved! Try again.");
    }

    public function levels()
    {
        $ret = [];
        foreach (new DirectoryIterator(PATH_CHARS) as $char) {
            if($char->isDot()){
                continue;
            }
            $char = $char->getFilename();
            if (substr($char, 0, 1) === ".") {
                continue;
            }

            $info        = json_decode(file_get_contents(PATH_CHARS . $char));
            $info->coins = json_decode(file_get_contents(PATH_COINS . $char));

            $ret[$char] = $info;
        }
        ksort($ret);
        return $ret;
    }

    public function info()
    {
        return $this->_call(Call::URL_INFO);
    }

    public function response($data, $success=true)
    {
        if ($success) {
            $ret = [
                "data" => $data,
                "error" => null
            ];
        } else {
            $ret = [
                "data" => null,
                "error" => $data
            ];
        }
        if (php_sapi_name() !== "cli") {
            header('Content-Type: application/json');
        }
        return json_encode($ret);
    }

    private function _call($url, $data=[])
    {
        curl_setopt($this->ch, CURLOPT_HEADER,         false);
        curl_setopt($this->ch, CURLOPT_NOBODY,         false);
        curl_setopt($this->ch, CURLOPT_URL,            $url);
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($this->ch, CURLOPT_COOKIEJAR,      $this->cookie);
        curl_setopt($this->ch, CURLOPT_COOKIE,         "cookiename=0");
        curl_setopt($this->ch, CURLOPT_USERAGENT,      "Mozilla/5.0 (Windows; U; Windows NT 5.0; en-US; rv:1.7.12) Gecko/20050915 Firefox/1.0.7");
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->ch, CURLOPT_REFERER,        Call::URL_REFERER);
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION, 0);
        curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST,  "POST");
        curl_setopt($this->ch, CURLOPT_POST,           1);
        curl_setopt($this->ch, CURLOPT_POSTFIELDS,     http_build_query($data));
        curl_exec($this->ch);

        return json_decode(curl_exec($this->ch));
    }

}