<?php
declare(strict_types=1);

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

return function( \Slim\App $app):void {

    $app->get('/', function (Request $request, Response $response, array $args) {
        $response->getBody()->write("Hello world!");
        return $response;
    });

    // $app->post('/commandes[/]', \pizzashop\shop\app\actions\CreerCommandeAction::class)
    //     ->setName('creer_commande');

    // $app->get('/commandes/{id_commande}[/]', \pizzashop\shop\app\actions\AccederCommandeAction::class)
    //     ->setName('commande');
    
    // $app->patch('/commandes/{id_commande}[/]', \pizzashop\shop\app\actions\ValiderCommandeAction::class)
    //     ->setName('valider_commande');
};