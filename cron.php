<?php

if (php_sapi_name() !== "cli") {
    http_response_code(404);
    exit;
}

require_once "mu.php";

for (;;) {

    foreach (new DirectoryIterator(PATH_ACCOUNT) as $acc) {
        $call = new Call();

        try {

            if($acc->isDot()){
                continue;
            }
            $username = $acc->getFilename();
            if (substr($username, 0, 1) === ".") {
                continue;
            }
            $password = file_get_contents(PATH_ACCOUNT . $username);

            echo "Getting {$username}..." . PHP_EOL;

            $call->login($username, $password);

            $info = $call->info();

            foreach ($info->CharacterList as $char) {
                file_put_contents(PATH_CHARS . $char->Name, json_encode($char, JSON_PRETTY_PRINT));
                file_put_contents(PATH_COINS . $char->Name, json_encode([
                    "WCoin" => $info->WCoin,
                    "HCoin" => $info->WCoinWaiting,
                    "GPoint" => $info->GoblinPoint
                ], JSON_PRETTY_PRINT));
            
            }
        } catch (Exception $e) {}

        unset($call);

        sleep(2);
    }

    echo "Done!" . PHP_EOL;
    sleep(10);
}