<?php

namespace pizzashop\shop\domain\service\commande;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use pizzashop\shop\domain\dto\commande\CommandeDTO;
use pizzashop\shop\domain\entities\commande\Commande;
use pizzashop\shop\domain\entities\commande\Item;
use pizzashop\shop\domain\exception\CommandeNonTrouveeException;
use pizzashop\shop\domain\exception\ValidationCommandeException;
use pizzashop\shop\domain\service\catalogue\ServiceCatalogue;
use Psr\Log\LoggerInterface;

/**
 * Service de gestion des commandes.
 */
class ServiceCommande implements iCommander {
    private LoggerInterface $logger;
    private ServiceCatalogue $serviceCatalogue;

    public function __construct(LoggerInterface $logger, ServiceCatalogue $serviceCatalogue){
        $this->serviceCatalogue = $serviceCatalogue;
        $this->logger = $logger;
    }
    /**
     * Accède à une commande spécifique en utilisant son identifiant.
     *
     * @param string $CommandeID L'identifiant de la commande à accéder.
     * @return CommandeDTO La commande correspondante.
     * @throws CommandeNonTrouveeException Si la commande n'est pas trouvée.
     */
    public function accederCommande(string $CommandeID): CommandeDTO {
        try {
            $commande = Commande::findOrFail($CommandeID);
        }catch (ModelNotFoundException $e){
                throw new CommandeNonTrouveeException("La commande $CommandeID n'existe pas");
            }
            return $commande->toDTO();
        }
    

    /**
     * Modifie une commande spécifique en utilisant son identifiant et de nouvelles données.
     *
     * @param string $CommandeID L'identifiant de la commande à modifier.
     * @param CommandeDTO $nouvellesDonnees Les nouvelles données de la commande.
     * @return CommandeDTO La commande modifiée.
     * @throws CommandeNonTrouveeException Si la commande n'est pas trouvée.
     * @throws ValidationCommandeException Si une erreur survient lors de la modification de la commande.
     */
    public function validerCommande(string $CommandeID): CommandeDTO{
     try {
            $commande = Commande::findOrFail($CommandeID);

            if ($commande->etat >= Commande::ETAT_VALIDE) {
                throw new ValidationCommandeException("La commande $CommandeID est déjà validée");
            }

            if ($commande->type_livraison == Commande::LIVRAISON_A_DOMICILE) {
                $commande->delai = 60;
            } else {
                $commande->delai = 30;
            }

            if ($commande->type_livraison == Commande::LIVRAISON_A_EMPORTER) {
                $commande->etat = Commande::ETAT_PREPARATION;
            } else {
                $commande->etat = Commande::ETAT_EN_COURS_DE_LIVRAISON;
            }

            $commande->update(['etat' => Commande::ETAT_VALIDE]);

            $this->logger->info("Commande $CommandeID validée");

        } catch (ModelNotFoundException $e) {
            throw new CommandeNonTrouveeException("La commande $CommandeID n'existe pas");
        } catch (ValidationCommandeException $e) {
            throw $e;
        }

        return $commande->toDTO();
    }
    
    /**
     * Crée une nouvelle commande.
     *
     * @param CommandeDTO $commandeDTO Les données de la commande à créer.
     * @return CommandeDTO La commande créée.
     * @throws ValidationCommandeException Si une erreur survient lors de la création de la commande.
     */
    public function creerCommande(CommandeDTO $commandeDTO): CommandeDTO {
        $create = new Commande();
        
        // Assigner les valeurs des attributs de la commande
        $create->date_commande = date('Y-m-d H:i:s');
        $create->type_livraison = 'A DOMICILE';
        $create->etat = Commande::ETAT_CREE;
        $create->montant_total = 0;
        $create->mail_client = 'bernard@gmail.com';
        $create->delai = 10;
        
        // Enregistrer la commande dans la base de données
        $create->save();

        // Assigner l'identifiant de la commande
        $commandeDTO->id = $create->id;

        // Assigner les valeurs des attributs des items de la commande
        foreach ($commandeDTO->items as $itemDTO) {
            $item = new Item();
            $item->quantite = $itemDTO->quantite;
            $item->tarif = $itemDTO->tarif;
            $item->taille_id = $itemDTO->taille_id;
            $item->produit_id = $itemDTO->produit_id;
            $item->commande_id = $commandeDTO->id;
            $item->save();
        }

        $this->calculerMontantTotal($commandeDTO->id);


        return $commandeDTO;

    }
}