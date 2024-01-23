<?php

namespace pizzashop\shop\app\actions;

use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;
use Slim\Routing\RouteContext;
use Symfony\Component\Console\Exception\CommandNotFoundException;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;


/**
 * permet l'Action d'accès à une commande.
 */

class AccederCommandeAction
{

    private ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Accède à une commande spécifique en utilisant son identifiant.
     *
        * @param ServerRequestInterface $request La requête.
        * @param ResponseInterface $response La réponse.
     * @param array $args Les arguments.
     * @return Response La réponse.
     * @throws HttpBadRequestException Si l'identifiant de la commande est manquant.
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $id_commande = $args['id_commande'] ?? null;
        //si l'id de la commande est null, on renvoie une erreur
        if (is_null($id_commande)) {
            throw new HttpBadRequestException($request, 'Missing id_command');
        }
        //on récupère la commande
        $routeParser = RouteContext::fromRequest($request)->getRouteParser();
        try {
            $commande = $this->container->get('commande_service')->accederCommande($args['id_commande']);

            $commande_data = [  
                            'type' => 'ressource',
                            'commande' => $commande,
                            'links' => [
                                'self' => ['href' => $routeParser->urlFor('commande', ['id_commande' => $commande->id])],
                                'valider' => ['href' => $routeParser->urlFor('valider_commande', ['id_commande' => $commande->id])],
                                ]
                        ];
            $code = 200;
            } catch (CommandNotFoundException $e) {
                // si la commande n'est pas trouvée, on lève une exception
                $data = [
                    "message" => "404 Not Found",
                    "exception" => [[
                        "type" => "Slim\\Exception\\HttpNotFoundException",
                        "message" => $e->getMessage(),
                        "code" => $e->getCode(),
                        "file" => $e->getFile(),
                        "line" => $e->getLine(),
                    ]]
                ];
                $code = 404;
            }

            //on renvoie la réponse
            $response->getBody()->write(json_encode($commande_data));
            return $response->withHeader('Content-Type', 'application/json')->withStatus($code);
    }
}