<?php
require_once __DIR__ . '/../connection.php';

list(, $queue) = $argv;
$queue = 'tuts-01';
$channel = $connection->channel();

$channel->queue_declare($queue, false, false, false, false);

echo " [*] Waiting for messages. To exit press CTRL+C\n";
$channel->basic_consume($queue, '', false, true, false, false, function ($msg) {
    echo '[x] Received ' . $msg->body . PHP_EOL;
});

while (count($channel->callbacks)) {
    $channel->wait();
}

$channel->close();
$connection->close();