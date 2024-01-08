<?php

namespace pizzashop\shop\app\actions;

use pizzashop\shop\domain\service\commande\iCommander;
use pizzashop\shop\domain\service\commande\ServiceCommande;
use pizzashop\shop\domain\service\commande\ServiceCommandeNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpBadRequestException;
use Slim\Routing\RouteContext;
use Symfony\Component\Console\Exception\CommandNotFoundException;


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
            $commande = $this->contener->get('commande_service')->accederCommande($args['id_commande']);

            $commande_data =[  
                            'type' => 'ressource',
                            'commande' => $commande,
                            'links' => [
                                'self' => ['href' => $routeParser->urlFor('commande', ['id_commande' => $commande->id])],
                                'valider' => ['href' => $routeParser->urlFor('valider_commande', ['id_commande' => $commande->id])],
                                ]
                        ];
                        $code = 200;
                    } catch (ServiceCommandeNotFoundException $e) {
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
            
                    // retour de la réponse
                    return JSONRenderer::render($rs, $code, $data)
                        ->withHeader('Access-Control-Allow-Origin', '*')
                        ->withHeader('Access-Control-Allow-Methods', 'GET' )
                        ->withHeader('Access-Control-Allow-Credentials', 'true')
                        ->withHeader('Content-Type', 'application/json');
                }
}