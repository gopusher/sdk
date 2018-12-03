<?php

include __DIR__ . '/../vendor/autoload.php';



$body = file_get_contents('php://input');
if (! empty($body)) {
    file_put_contents('/tmp/chat-rpc.log', $body . PHP_EOL, FILE_APPEND);

    header('Content-Type: application/json; charset=utf-8');
    $gopusher = new \Gopusher\Sdk\Gopusher();
    echo $gopusher->notification([new \Gopusher\Sdk\Notification\StatusHandler()])
        ->handleAndResponse($_SERVER['HTTP_USER_AGENT'], $body);
    exit;
}

include __DIR__ . '/chat.html';
