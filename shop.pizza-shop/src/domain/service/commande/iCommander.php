<?php

namespace pizzashop\shop\domain\service\commande;

use pizzashop\shop\domain\dto\commande\CommandeDTO;

interface iCommander
{
    /**
     * Crée une nouvelle commande.
     *
     * @param CommandeDTO $c Les données de la commande à créer.
     * @return CommandeDTO La commande créée.
     * @throws ServiceCommandeInvalidItemException Si un ou plusieurs items de la commande sont invalides.
     * @throws ServiceCommandeInvalidTransitionException Si la transition de la commande est invalide.
     * @throws ServiceCommandeInvialideException Si la commande est invalide.
     * @throws ServiceProduitNotFoundException Si un produit de la commande n'est pas trouvé.
     */
    function creerCommande(CommandeDTO $c) : CommandeDTO;

    /**
     * Modifie une commande spécifique en utilisant son identifiant et de nouvelles données.
     *
     * @param string $id L'identifiant de la commande à modifier.
     * @param CommandeDTO $nouvellesDonnees Les nouvelles données de la commande.
     * @return CommandeDTO La commande modifiée.
     * @throws ServiceCommandeNotFoundException Si la commande n'est pas trouvée.
     * @throws ServiceException Si une erreur survient lors de la modification de la commande.
     */
    function validerCommande(string $id) : CommandeDTO;

    /**
     * Accède à une commande spécifique en utilisant son identifiant.
     *
     * @param string $id L'identifiant de la commande à accéder.
     * @return CommandeDTO La commande correspondante.
     * @throws ServiceCommandeNotFoundException Si la commande n'est pas trouvée.
     */
    function accederCommande(string $id) : CommandeDTO;
}