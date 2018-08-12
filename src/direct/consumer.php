<?php
require_once __DIR__ . '/../connection.php';

$channel = $connection->channel();

$channel->queue_declare('tuts-01', false, false, false, false);

echo " [*] Waiting for messages. To exit press CTRL+C\n";

$callback = function ($msg) {
    echo ' [x] Received ', $msg->body, "\n";
};

$channel->basic_consume('tuts-01', '', false, true, false, false, $callback);
while (count($channel->callbacks)) {
    $channel->wait();
}

$channel->close();
$connection->close();