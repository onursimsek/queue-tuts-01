<?php

/**
 * php producer.php red.rabbit Hello
 *
 * for i in "blue" "pink" "black"; do php 05-topic/producer.php $i.rabbit "hello $i rabbit"; done
 */

require_once __DIR__ . '/../connection.php';

use PhpAmqpLib\Message\AMQPMessage;

$exchange = 'tuts-topic-exchange';

$channel->exchange_declare($exchange, 'topic', false, false, false);

$routingKey = isset($argv[1]) && !empty($argv[1]) ? $argv[1] : 'anonymous.info';
$data = implode(' ', array_slice($argv, 2));
if (empty($data)) {
    $data = "Hello World!";
}

$msg = new AMQPMessage($data);
$channel->basic_publish($msg, $exchange, $routingKey);

echo '[x] Sent ', $routingKey, ':', $data, "\n";

$channel->close();
$connection->close();