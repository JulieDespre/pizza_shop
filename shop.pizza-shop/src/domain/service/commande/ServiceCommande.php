<?php

namespace pizzashop\shop\domain\service\commande;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use pizzashop\shop\domain\dto\commande\CommandeDTO;
use pizzashop\shop\domain\entities\commande\Commande;
use pizzashop\shop\domain\entities\commande\Item;
use pizzashop\shop\domain\service\exception\ServiceCommandeInvalidItemException;
use pizzashop\shop\domain\service\exception\ServiceCommandeInvalidTransitionException;
use pizzashop\shop\domain\service\exception\ServiceCommandeInvialideException;
use pizzashop\shop\domain\service\exception\ServiceCommandeNotFoundException;
use pizzashop\shop\domain\service\exception\ServiceProduitNotFoundException;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator as validate;

/**
 * Service de gestion des commandes.
 */
class ServiceCommande implements iCommander {

    private \pizzashop\shop\domain\service\catalogue\iInfoCatalogue $serviceCatalogue;
    private LoggerInterface $logger;

    /*function __construct(\pizzashop\shop\domain\service\catalogue\iInfoCatalogue $serviceCatalogue){
        $this->serviceCatalogue = $serviceCatalogue;
    }*/

    function __construct(\pizzashop\shop\domain\service\catalogue\iInfoCatalogue $serviceCatalogue, LoggerInterface $logger){
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
            $commande = Commande::where('id', $commandeId)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            throw new ServiceCommandeNotFoundException($commandeId);
        }
        if ($commande->etat >= Commande::ETAT_VALIDE) {
            throw new ServiceCommandeInvalidTransitionException($commandeId);
        }
        $commande->update(['etat' => Commande::ETAT_VALIDE]);
        $this->logger->info("Commande $commandeId validée");
        
        
        return $commande->toDTO();
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
        
        //créer donnée de la commande 
        $commandeDTO->id = Uuid::uuid4();//génère unique ID
        $commandeDTO->date_commande = date('Y-m-d H:i:s');
        $commandeDTO->etat = Commande::ETAT_CREE;
        $commandeDTO->delai = 0;


        $commande = Commande::create([
            'id' => $commandeDTO->id,
            'date_commande' => $commandeDTO->date_commande,
            'type_livraison' => $commandeDTO->type_livraison,
            'etat' => $commandeDTO->etat,
            'mail_client' => $commandeDTO->mail_client,
            'delai' => $commandeDTO->delai,
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
        $commande->save();
        $this->logger->info("Nouvelle commande créée avec l'ID: " . $commandeDTO->id);
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