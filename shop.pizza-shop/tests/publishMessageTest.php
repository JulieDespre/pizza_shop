<?php
require_once __DIR__ . '/../vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$connection=null;
try {
    $connection = new AMQPStreamConnection('rabbitmq', 5672, 'admin', '@dm1#!');
} catch (Exception $e) {
    var_dump($e->getMessage());
}
$channel = $connection->channel();

$queueName = 'nouvelles_commandes';
$faker = Faker\Factory::create();

// Génère une commande aléatoire
$commandData = [
    'delai' => $faker->numberBetween(0, 7),
    'id' => $faker->uuid,
    'date_commande' => $faker->dateTimeThisMonth()->format('Y-m-d H:i:s'),
    'type_livraison' => $faker->numberBetween(1, 5),
    'etape' => $faker->numberBetween(1, 10),
    'montant_total' => $faker->randomFloat(2, 10, 1000),
    'mail_client' => $faker->email,
];

$jsonCommand = json_encode($commandData);
$message = new AMQPMessage($jsonCommand);
$channel->basic_publish($message, '', $queueName);

echo "Commande publiée : $jsonCommand\n";

$channel->close();
try {
    $connection->close();
} catch (Exception $e) {
    var_dump($e->getMessage());
}