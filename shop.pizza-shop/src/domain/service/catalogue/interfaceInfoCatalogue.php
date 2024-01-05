<?php

namespace pizzashop\shop\domain\service\catalogue;

use pizzashop\shop\domain\dto\catalogue\ProduitDTO;

interface interfaceInfoCatalogue
{
    /**
     * Récupère un produit spécifique en utilisant son identifiant.
     *
     * @param int $numero L'identifiant du produit à récupérer.
     * @param int $taille La taille du produit à récupérer.
     * @return ProduitDTO Le produit correspondant.
     * @throws ServiceCatalogueNotFoundException Si le produit n'est pas trouvé.
     */
    function getProduit(int $numero, int $taille): ProduitDTO;
}