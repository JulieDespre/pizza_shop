<?php

// Import des classes nécessaires
use DI\ContainerBuilder;
use Illuminate\Database\Capsule\Manager;
use Slim\Factory\AppFactory;

// Inclusion des fichiers de configuration
$settings = require_once __DIR__ . '/settings.php';
$dependencies = require_once __DIR__ . '/services_dependencies.php';
$actions = require_once __DIR__ . '/actions_dependencies.php';

// Création d'un nouveau builder de conteneur d'injection de dépendances
$builder = new ContainerBuilder();

// Ajout des définitions de configuration du constructeur de contener d'injection de dépendances
$builder->addDefinitions($settings);
$builder->addDefinitions($dependencies);
$builder->addDefinitions($actions);

// Création du conteneur d'injection de dépendances
$container = $builder->build();

// Création d'une application Slim à partir du conteneur
$app = AppFactory::createFromContainer($container);

// Configuration de la connexion à la base de données avec Eloquent
$capsule = new Manager();
$capsule->addConnection(parse_ini_file(__DIR__ . '/commande.db.ini'), 'commande');
$capsule->addConnection(parse_ini_file(__DIR__ . '/catalog.db.ini'), 'catalog');
$capsule->setAsGlobal();
$capsule->bootEloquent();

// Retourne l'application configurée
return $app;