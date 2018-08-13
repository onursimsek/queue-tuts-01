<?php

/**
 * Kuyruk kalıcı modda açılmıştır ve alıcının işi aldım diye işaretlemesi beklenir
 *
 * for i in {0..50}; do php 02-work-queue/producer.php "hello $i"; done
 */

use PhpAmqpLib\Message\AMQPMessage;

require __DIR__ . '/../connection.php';

list(, $message) = $argv;
$queue = 'tuts-work-queue';

$channel->queue_declare($queue, false, true, false, false);

$msg = new AMQPMessage($message, [
    'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT
]);
$channel->basic_publish($msg, '', $queue);

echo '[x] Sent ' . $message . PHP_EOL;

$channel->close();
$connection->close();