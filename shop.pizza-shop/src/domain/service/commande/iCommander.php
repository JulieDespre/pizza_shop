<?php

namespace pizzashop\shop\domain\service\commande;

use pizzashop\shop\domain\dto\commande\CommandeDTO;
use pizzashop\shop\domain\service\exception\ServiceCommandeInvalideException;
use pizzashop\shop\domain\service\exception\ServiceCommandeInvalidItemException;
use pizzashop\shop\domain\service\exception\ServiceCommandeInvalidTransitionException;
use pizzashop\shop\domain\service\exception\ServiceCatalogueNotFoundException;
use pizzashop\shop\domain\service\exception\ServiceException;
use pizzashop\shop\domain\service\exception\ServiceProduitNotFoundException;

interface iCommander
{
    /**
     * Crée une nouvelle commande.
     *
     * @param CommandeDTO $commandeDTO
     * @return CommandeDTO La commande créée.
     * @throws ServiceCommandeInvalidItemException Si un ou plusieurs items de la commande sont invalides.
     * @throws ServiceCommandeInvalidTransitionException Si la transition de la commande est invalide.
     * @throws ServiceCommandeInvalideException Si la commande est invalide.
     * @throws ServiceProduitNotFoundException Si un produit de la commande n'est pas trouvé.
     */
    function creerCommande(CommandeDTO $commandeDTO) : CommandeDTO;

    /**
     * Modifie une commande spécifique en utilisant son identifiant et de nouvelles données.
     *
     * @param string $commandeId
     * @return CommandeDTO La commande modifiée.
     * @throws ServiceCatalogueNotFoundException Si la commande n'est pas trouvée.
     * @throws ServiceException Si une erreur survient lors de la modification de la commande.
     */
    function validerCommande(string $commandeId) : CommandeDTO;

    /**
     * Accède à une commande spécifique en utilisant son identifiant.
     *
     * @param string $commandeId
     * @return CommandeDTO La commande correspondante.
     * @throws ServiceCatalogueNotFoundException Si la commande n'est pas trouvée.
     */
    function accederCommande(string $commandeId) : CommandeDTO;
}