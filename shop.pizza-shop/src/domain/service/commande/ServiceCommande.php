<?php

namespace pizzashop\shop\domain\service\commande;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use pizzashop\shop\domain\dto\commande\CommandeDTO;
use pizzashop\shop\domain\entities\commande\Commande;
use pizzashop\shop\domain\entities\commande\Item;
use pizzashop\shop\domain\service\catalogue\iInfoCatalogue;
use pizzashop\shop\domain\service\exception\ServiceCommandeInvalidDataException;
use pizzashop\shop\domain\service\exception\ServiceCommandeInvalidTransitionException;
use pizzashop\shop\domain\service\exception\ServiceCatalogueNotFoundException;
use pizzashop\shop\domain\service\exception\ServiceCommandeNotFoundException;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;
use Respect\Validation\Exceptions\ValidationException;
use Respect\Validation\Validator as validate;

/**
 * Service de gestion des commandes.
 */
class ServiceCommande implements iCommander {

    private iInfoCatalogue $serviceCatalogue;
    private LoggerInterface $logger;

    function __construct(iInfoCatalogue $serviceCatalogue, LoggerInterface $logger){
        $this->serviceCatalogue = $serviceCatalogue;
        $this->logger = $logger;
    }

    /**
     * Accède à une commande spécifique en utilisant son identifiant.
     *
     * @param string $commandeId L'identifiant de la commande à accéder.
     * @return CommandeDTO La commande correspondante.
     * @throws ServiceCatalogueNotFoundException Si la commande n'est pas trouvée.
     */
    public function accederCommande(string $commandeId): CommandeDTO {
        try {
            $commande = Commande::findOrFail($commandeId);
        } catch (ModelNotFoundException) {
            throw new ServiceCatalogueNotFoundException($commandeId);
        }
        return $commande->toDTO();
    }

    /**
     * Modifie une commande spécifique en utilisant son identifiant et de nouvelles données.
     *
     * @param string $commandeId L'identifiant de la commande à modifier.
     * @return CommandeDTO La commande modifiée.
     * @throws ServiceCommandeNotFoundException Si la commande n'est pas trouvée.
     * @throws ServiceCommandeInvalidTransitionException Si la transition de la commande est invalide.
     */
    public function validerCommande(string $commandeId): CommandeDTO {
        try {
            $commande = Commande::findOrFail($commandeId);
        } catch (ModelNotFoundException) {
            throw new ServiceCommandeNotFoundException($commandeId);
        }

        if ($commande->etape >= Commande::ETAT_VALIDE) {
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
     * @throws ServiceCommandeInvalidDataException Si les données de la commande sont invalides.
     */
    public function creerCommande(CommandeDTO $commandeDTO): CommandeDTO {
        // Valide les données de la commande
        $this->validerDonneesDeCommande($commandeDTO);

        // Créer les données de la commande
        $commandeDTO->id = Uuid::uuid4(); // Génère un ID unique
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

        // Créer les items d'une commande
        foreach ($commandeDTO->items as $itemDTO) {
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

    /**
     * Valide les données de la commande.
     *
     * @param CommandeDTO $commandeDTO Les données de la commande à valider.
     * @throws ServiceCommandeInvalidDataException Si les données de la commande sont invalides.
     */
    private function validerDonneesDeCommande(CommandeDTO $commandeDTO): void
    {
        try {
            validate::attribute('mail_client', validate::email())
                ->attribute('type_livraison', validate::in([Commande::LIVRAISON_SUR_PLACE,
                    Commande::LIVRAISON_A_EMPORTER, Commande::LIVRAISON_A_DOMICILE]))
                ->attribute('items', validate::arrayType()->notEmpty()
                    ->each(
                        validate::attribute('numero', validate::intVal()->positive())
                            ->attribute('taille', validate::in([1, 2]))
                            ->attribute('quantite', validate::intVal()->positive())
                    )
                )
                ->assert($commandeDTO);
        } catch (ValidationException) {
            throw new ServiceCommandeInvalidDataException("Les données de la commande sont invalides");
        }
    }

    public function getServiceCatalogue():iInfoCatalogue {
        return $this->serviceCatalogue;
    }
}
