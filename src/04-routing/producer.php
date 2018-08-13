<?php

/**
 * Routing key ile mesaj gönderir
 * for i in "info" "error" "warning"; do php 04-routing/producer.php $i "hello $i"; done
 */

require_once __DIR__ . '/../connection.php';

use PhpAmqpLib\Message\AMQPMessage;

list(, $severity, $message) = $argv;
$exchange = 'tuts-routing-exchange';

$channel->exchange_declare($exchange, 'direct', false, false, false);

$msg = new AMQPMessage($message);
$channel->basic_publish($msg, $exchange, $severity);

echo '[x] Sent ' . $severity . ':' . $message . PHP_EOL;

$channel->close();
$connection->close();