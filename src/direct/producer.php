<?php

use PhpAmqpLib\Message\AMQPMessage;

require_once __DIR__ . '/../connection.php';

$channel = $connection->channel();

$channel->queue_declare('tuts-01', false, false, false, false);

$msg = new AMQPMessage('Hello World!');
$channel->basic_publish($msg, '', 'tuts-01');

echo " [x] Sent 'Hello World!'\n";

$channel->close();
$connection->close();