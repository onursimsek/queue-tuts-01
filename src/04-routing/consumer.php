<?php

/**
 * İstenilen tüm -ya da tek- routing key'lerden mesaj alabilir
 * php consumer.php info
 */

require_once __DIR__ . '/../connection.php';

$exchange = 'tuts-routing-exchange';

$channel->exchange_declare($exchange, 'direct', false, false, false);

list($queue_name, ,) = $channel->queue_declare("", false, false, true, false);

$severities = array_slice($argv, 1);
if (empty($severities)) {
    file_put_contents('php://stderr', "Usage: $argv[0] [info] [warning] [error]\n");
    exit(1);
}

foreach ($severities as $severity) {
    $channel->queue_bind($queue_name, $exchange, $severity);
}

echo "[*] Waiting for logs. To exit press CTRL+C\n";
$channel->basic_consume($queue_name, '', false, true, false, false, function ($msg) {
    echo '[x] ', $msg->delivery_info['routing_key'], ':', $msg->body, "\n";
});

while (count($channel->callbacks)) {
    $channel->wait();
}

$channel->close();
$connection->close();