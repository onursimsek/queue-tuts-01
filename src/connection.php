<?php
require_once __DIR__ . '/../vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;

$connection = new AMQPStreamConnection('queue-rabbit', 5672, 'guest', 'guest');
$channel = $connection->channel();
