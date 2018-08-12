<?php

/**
 * php producer.php "Hello world!"
 */

require_once __DIR__ . '/../connection.php';

use PhpAmqpLib\Message\AMQPMessage;

list(, $message) = $argv;
$queue = 'tuts-direct';

$channel->queue_declare($queue, false, false, false, false);

$msg = new AMQPMessage($message);
$channel->basic_publish($msg, '', $queue);

echo "[x] Sent '{$message}'\n";

$channel->close();
$connection->close();