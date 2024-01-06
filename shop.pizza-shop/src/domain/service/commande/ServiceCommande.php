<?php
//use Exception;
use pizzashop\shop\domain\dto\commande\CommandeDTO;
use pizzashop\shop\domain\entities\commande\Commande;
use pizzashop\shop\domain\entities\commande\Item;
use pizzashop\shop\domain\service\catalogue\interfaceCatalogue;
use pizzashop\shop\domain\service\catalogue\ServiceCatalogueNotFoundException;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator as validate;

/**
 * Service de gestion des commandes.
 */
class ServiceCommande implements interfaceCommander {

    private interfaceInfoCatalogue $serviceCatalogue;
    private LoggerInterface $logger;

    function __construct(interfaceInfoCatalogue $serviceCatalogue, LoggerInterface $logger){
        $this->serviceCatalogue = $serviceCatalogue;
        $this->logger = $logger;
    }
    /**
     * Accède à une commande spécifique en utilisant son identifiant.
     *
     * @param string $commandeId L'identifiant de la commande à accéder.
     * @return CommandeDTO La commande correspondante.
     * @throws ServiceCommandeNotFoundException Si la commande n'est pas trouvée.
     */
    public function accederCommande(string $commandeId): CommandeDTO {
        try {
            $commande = Commande::where('id', $commandeId)->firstOrFail($commandeId);
        }catch (ModelNotFoundException $e){
                throw new ServiceCommandeNotFoundException("La commande $commandeId n'existe pas");
            }
            return $commande->toDTO();
        }
    

    /**
     * Modifie une commande spécifique en utilisant son identifiant et de nouvelles données.
     *
     * @param string $commandeId L'identifiant de la commande à modifier.
     * @param CommandeDTO $nouvellesDonnees Les nouvelles données de la commande.
     * @return CommandeDTO La commande modifiée.
     * @throws ServiceCommandeNotFoundException Si la commande n'est pas trouvée.
     * @throws ServiceException Si une erreur survient lors de la modification de la commande.
     */
    public function validerCommande(string $commandeId): CommandeDTO{
        try {
            $commande = Commande::findOrFail($commandeId);
            $commande->valider();
            $commande->save();
            return $commande->toDTO();
        }catch (ModelNotFoundException $e){
                throw new ServiceCommandeNotFoundException("La commande $commandeId n'existe pas");
            }
           
    }

    /**
     * Crée une nouvelle commande.
     *
     * @param CommandeDTO $commandeDTO Les données de la commande à créer.
     * @return CommandeDTO La commande créée.
     * @throws ServiceException Si une erreur survient lors de la création de la commande.
     */
    public function creerCommande (CommandeDTO $commandeDTO): CommandeDTO{
        //valide les données de la commande
        this->validerDonneesDeCommande($commandeDTO);
        
        //créer une commande 
        $commande->id = Uuid::uuid4();//génère unique ID

        $commande = Commande::create([
            'id' => $uuid4->toString(),
            'date_commande' => date('Y-m-d H:i:s'),
            'type_livraison' => $commandeDTO->type_livraison,
            'etat' => Commande::ETAT_CREE,
            'mail_client' => $commandeDTO->mail_client,
            'delai' => 0
        ]);

        //creer les items d'une commande
        //$commandeDTO->items = [];
        foreach ($commandeDTO->items as $itemDTO){
            $item = new Item();
            $item->id = Uuid::uuid4();
            $item->numero = $itemDTO->numero;
            $item->taille = $itemDTO->taille;
            $item->quantite = $itemDTO->quantite;
            $item->commande_id = $commande->id;
            $item->save();
            $itemDTO->id = $item->id;
            $commandeDTO->items[] = $itemDTO;
        }
        return $commandeDTO;
    }
 
    private function validerDonneesDeCommande(CommandeDTO $commandeDTO){
        try {

            validate::attribute('mail_client', validate::email())
                ->attribute('type_livraison', validate::in([Commande::LIVRAISON_SUR_PLACE, Commande::LIVRAISON_A_EMPORTER, Commande::LIVRAISON_DOMICILE]))
                ->attribute('items', validate::array()->notEmpty()
                    ->each(validate::attribute('numero', validate::intVal()->positive())
                    ->attribute('taille', validate::in([1,2]))
                    ->attribute('quantite', validate::intVal()->positive())

                    ))
        
                ->assert($commandeDTO);
            }catch (NestedValidationException $e){
            throw new ServiceCommandeInvalidDataException("Les données de la commande sont invalides");
        }
    }
}