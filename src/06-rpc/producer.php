<?php

/**
 * php 06-rpc/producer.php
 */

require_once __DIR__ . '/../connection.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class FibonacciRpcClient
{
    private $connection;
    private $channel;
    private $callbackQueue;
    private $response;
    private $correlationId;

    public function __construct()
    {
        $this->connection = new AMQPStreamConnection('queue-rabbit', 5672, 'guest', 'guest');
        $this->channel = $this->connection->channel();
        list($this->callbackQueue, ,) = $this->channel->queue_declare("", false, false, true, false);
        $this->channel->basic_consume($this->callbackQueue, '', false, false, false, false, [
            $this,
            'onResponse'
        ]);
    }

    public function onResponse($rep)
    {
        if ($rep->get('correlation_id') == $this->correlationId) {
            $this->response = $rep->body;
        }
    }

    public function call($n)
    {
        $this->response = null;
        $this->correlationId = uniqid();
        $msg = new AMQPMessage((string)$n, [
            'correlation_id' => $this->correlationId,
            'reply_to' => $this->callbackQueue
        ]);

        $this->channel->basic_publish($msg, '', 'rpc_queue');
        while (!$this->response) {
            $this->channel->wait();
        }

        return intval($this->response);
    }
}

$fibonacciRpc = new FibonacciRpcClient();
$response = $fibonacciRpc->call(30);
echo ' [.] Got ', $response, "\n";