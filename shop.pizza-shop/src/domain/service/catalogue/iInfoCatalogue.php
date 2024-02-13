<?php

namespace pizzashop\shop\domain\service\catalogue;

use pizzashop\shop\domain\dto\catalogue\ProduitDTO;
use pizzashop\shop\domain\service\exception\ServiceCatalogueNotFoundException;

interface iInfoCatalogue{
    /**
     * Récupère un produit spécifique en utilisant son identifiant.
     *
     * @param int $numero L'identifiant du produit à récupérer.
     * @return ProduitDTO Le produit correspondant.
     * @throws ServiceCatalogueNotFoundException Si le produit n'est pas trouvé.
     */
    public function getProduit(int $numero, int $taille): ProduitDTO;

}