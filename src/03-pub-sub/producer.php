<?php

/**
 * php producer.php "info: hello"
 */

use PhpAmqpLib\Message\AMQPMessage;

require_once __DIR__ . '/../connection.php';

list(, $message) = $argv;
$exchange = 'tuts-pub-sub-exchange';

$channel->exchange_declare($exchange, 'fanout', false, false, false);

$msg = new AMQPMessage($message);
$channel->basic_publish($msg, $exchange);

echo '[x] Sent ' . $message . PHP_EOL;

$channel->close();
$connection->close();