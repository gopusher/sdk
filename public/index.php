<?php

include __DIR__ . '/../vendor/autoload.php';

$body = file_get_contents('php://input');
if (! empty($body)) {
    //file_put_contents('/tmp/chat-rpc.log', $body . PHP_EOL, FILE_APPEND);

    header('Content-Type: application/json; charset=utf-8');

    class Handler implements \Gopusher\Sdk\Notification\Handler {
        public function checkToken($connId, $token, $nodeId)
        {
            file_put_contents('/tmp/chat-rpc.log', 'checkToken ' .$connId. $token. $nodeId . PHP_EOL, FILE_APPEND);
        }

        public function online($connId, $nodeId)
        {
            file_put_contents('/tmp/chat-rpc.log', 'online ' .$connId. $nodeId . PHP_EOL, FILE_APPEND);
        }

        public function offline($connId, $nodeId)
        {
            file_put_contents('/tmp/chat-rpc.log', 'offline ' .$connId. $nodeId . PHP_EOL, FILE_APPEND);
        }
    }

    $notification = new \Gopusher\Sdk\Notification\Notification();
    $notification->registerHandler(new Handler());
    echo $notification->handleAndResponse($_SERVER['HTTP_USER_AGENT'], $body);
    exit;
}

include __DIR__ . '/chat.html';
