<?php
require_once __DIR__ . '/../vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;

$connection=null;
try {
    $connection = new AMQPStreamConnection('rabbitmq', 5672, 'admin', '@dm1#!');
} catch (Exception $e) {
    var_dump($e->getMessage());
}
$channel = $connection->channel();

$queue_name="nouvelles_commandes";
$routingKey = 'nouvelle';

$callback = function ($msg) {
    echo ' [x] ', $msg->getBody(), "\n";
};

$channel->basic_consume($queue_name, '', false, true, false, false, $callback);

try {
    $channel->consume();
} catch (Throwable $exception) {
    echo $exception->getMessage();
}

$channel->close();
try {
    $connection->close();
} catch (Exception $e) {
    var_dump($e->getMessage());
}