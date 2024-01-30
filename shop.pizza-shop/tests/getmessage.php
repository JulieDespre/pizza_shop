<?php
require_once __DIR__ . '/../vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

echo "---------------------------------";
echo "Début du script";

// Paramètres de connexion à RabbitMQ

$connection = new AMQPStreamConnection('rabbitmq', 5672, 'admin', '@dm1#!');
$channel = $connection->channel();

$queue_name="nouvelles_commandes";
$routingKey = 'nouvelle';

$callback = function ($msg) {
    echo ' [x] ', $msg->getBody(), "\n";
};

$channel->basic_consume($queue_name, '', false, true, false, false, $callback);

try {
    $channel->consume();
} catch (\Throwable $exception) {
    echo $exception->getMessage();
}

// Ferme la connexion
$channel->close();
$connection->close();