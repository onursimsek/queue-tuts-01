<?php

/**
 * php consumer.php "*.rabbit"
 */

require_once __DIR__ . '/../connection.php';

$exchange = 'tuts-topic-exchange';

$channel->exchange_declare($exchange, 'topic', false, false, false);

list($queueName, ,) = $channel->queue_declare('', false, false, true, false);

$bindingKeys = array_slice($argv, 1);
if (empty($bindingKeys)) {
    file_put_contents('php://stderr', "Usage: $argv[0] [binding_key]\n");
    exit(1);
}

foreach ($bindingKeys as $bindingKey) {
    $channel->queue_bind($queueName, $exchange, $bindingKey);
}

echo " [*] Waiting for logs. To exit press CTRL+C\n";
$channel->basic_consume($queueName, '', false, true, false, false, function ($msg) {
    echo '[x] ', $msg->delivery_info['routing_key'], ':', $msg->body, "\n";
});

while (count($channel->callbacks)) {
    $channel->wait();
}

$channel->close();
$connection->close();