<?php

use pizzashop\shop\app\actions\AccederCommandeAction;
use pizzashop\shop\app\actions\ConnectionAction;
use pizzashop\shop\app\actions\CreerCommandeAction;
use pizzashop\shop\app\actions\ValiderCommandeAction;
use Psr\Container\ContainerInterface;

// Définition des actions avec leur nom de clé associé
$actions = [
    'commande' => function (ContainerInterface $c) {
        return new AccederCommandeAction($c);
    },
    // 'commande.validate' => function (ContainerInterface $c) {
    //     return new ValiderCommandeAction($c);
    // },
    // 'commande.create' => function (ContainerInterface $c) {
    //     return new CreerCommandeAction($c);
    // },
    // 'commande.auth' => function () {
    //     return new ConnectionAction();
    // }
];

// Retourne le tableau associatif des actions configurées
return $actions;
