<?php

/**
 * php 06-rpc/consumer.php
 */

use PhpAmqpLib\Message\AMQPMessage;

require_once __DIR__ . '/../connection.php';

$channel->queue_declare('rpc_queue', false, false, false, false);
function fib($n)
{
    if ($n == 0) {
        return 0;
    }
    if ($n == 1) {
        return 1;
    }
    return fib($n - 1) + fib($n - 2);
}

echo " [x] Awaiting RPC requests\n";
$channel->basic_qos(null, 1, null);
$channel->basic_consume('rpc_queue', '', false, false, false, false, function ($req) {
    $n = intval($req->body);
    echo ' [.] fib(', $n, ")\n";
    $msg = new AMQPMessage((string)fib($n), ['correlation_id' => $req->get('correlation_id')]);

    $req->delivery_info['channel']->basic_publish($msg, '', $req->get('reply_to'));
    $req->delivery_info['channel']->basic_ack($req->delivery_info['delivery_tag']);
});

while (count($channel->callbacks)) {
    $channel->wait();
}

$channel->close();
$connection->close();