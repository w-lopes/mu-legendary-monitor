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

        sleep(1);
    }

    foreach (new DirectoryIterator(PATH_CHARS) as $char) {
        if($char->isDot()){
            continue;
        }
        $file = $char->getFilename();
        if (substr($file, 0, 1) === ".") {
            continue;
        }
        $diff = time() - $char->getMTime();
        if ($diff > 120) {
            echo "Removing '{$file}'" . PHP_EOL;
            @unlink($char->getPathname());
        }
    }

    echo "Done!" . PHP_EOL;
    sleep(10);
}