<?php

/**
 * Mesaj alıcıların hepsine ulaştırılır
 * php consumer.php
 */

require_once __DIR__ . '/../connection.php';

$exchange = 'tuts-pub-sub-exchange';

$channel->exchange_declare($exchange, 'fanout', false, false, false);

list($queue_name, ,) = $channel->queue_declare('', false, false, true, false);

$channel->queue_bind($queue_name, $exchange);

echo " [*] Waiting for logs. To exit press CTRL+C\n";
$channel->basic_consume($queue_name, '', false, true, false, false, function ($msg) {
    echo '[x] ', $msg->body, "\n";
});

while (count($channel->callbacks)) {
    $channel->wait();
}

$channel->close();
$connection->close();