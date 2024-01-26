<?php
require_once __DIR__ . '/../vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

// Paramètres de connexion à RabbitMQ
$connection = new AMQPStreamConnection('rabbitmq', 5672, 'admin', '@dm1#!');
$channel = $connection->channel();

// Nom de la queue
$queueName = 'nouvelles_commandes';

// Déclare la queue si elle n'existe pas
$channel->queue_declare($queueName, false, true, false, false);

// Génère une commande aléatoire
$commandData = [
    'command' => 'votre_commande',
    'parametre' => 'votre_parametre'
];

// Convertit la commande en JSON
$jsonCommand = json_encode($commandData);

// Crée un message AMQP
$message = new AMQPMessage($jsonCommand);

// Publie le message dans la queue
$channel->basic_publish($message, '', $queueName);

// Affiche la commande dans la console
echo "Commande publiée : $jsonCommand\n";

// Ferme la connexion
$channel->close();
$connection->close();