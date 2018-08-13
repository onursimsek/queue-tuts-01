<?php

/**
 * Çoklu alıcıların bir kuyruktan sırasıyla iş alması
 * işi aldım diye işaretlemesi
 *
 * php consumer.php
 */

require_once __DIR__ . '/../connection.php';

$queue = 'tuts-work-queue';

$channel->queue_declare($queue, false, true, false, false);
echo " [*] Waiting for messages. To exit press CTRL+C\n";

$channel->basic_qos(null, 2, null);
$channel->basic_consume($queue, '', false, false, false, false, function ($msg) {
    echo ' [x] Received ', $msg->body, "\n";
    sleep(substr_count($msg->body, '.'));
    echo " [x] Done\n";
    $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
});

while (count($channel->callbacks)) {
    $channel->wait();
}

$channel->close();
$connection->close();