<?php
use Exception;
use pizzashop\shop\domain\dto\commande\CommandeDTO;
use pizzashop\shop\domain\entities\commande\Commande;
use pizzashop\shop\domain\entities\commande\Item;
use pizzashop\shop\domain\service\catalogue\iInfoCatalogue;
use pizzashop\shop\domain\service\catalogue\ServiceCatalogueNotFoundException;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator as validate;

/**
 * Service de gestion des commandes.
 */
class ServiceCommande implements interphasecommander {

    private interphaceInfoCatalogue $serviceCatalogue;
    private LoggerInterface $logger;

    function __construct(interphaceInfoCatalogue $serviceCatalogue, LoggerInterface $logger){
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
            $commande = Commande::findOrFail($commandeId);
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
}