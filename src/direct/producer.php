<?php

require_once __DIR__ . '/../connection.php';

use PhpAmqpLib\Message\AMQPMessage;

list(, $message) = $argv;
$channel = $connection->channel();

$channel->queue_declare('tuts-01', false, false, false, false);

$msg = new AMQPMessage($message);
$channel->basic_publish($msg, '', 'tuts-01');

echo "[x] Sent '{$message}'\n";

$channel->close();
$connection->close();