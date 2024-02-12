<?php
require_once __DIR__ . '/../vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;

// Paramètres de connexion à RabbitMQ
$connection = new AMQPStreamConnection('rabbitmq', 5672, 'admin', '@dm1#!');
$channel = $connection->channel();

// Nom de la queue
$queueName = 'nouvelles_commandes';

// Récupère un message de la queue en mode "get"
$message = $channel->basic_get($queueName);

if ($message !== null) {
    // Décoder le contenu du message (JSON)
    $jsonCommand = $message->getBody();
    $commandData = json_decode($jsonCommand, true);

    // Affiche le contenu du message dans la console
    echo "Message reçu : ", $jsonCommand, "\n";
    echo "Contenu décodé : ", print_r($commandData, true), "\n";

    // Acquitte la réception du message
    $channel->basic_ack($message->getDeliveryTag());
} else {
    echo "Aucun message disponible dans la file d'attente.\n";
}

// Ferme la connexion
$channel->close();
$connection->close();