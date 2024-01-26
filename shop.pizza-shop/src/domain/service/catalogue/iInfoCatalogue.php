<?php

namespace pizzashop\shop\domain\service\catalogue;

use pizzashop\shop\domain\dto\catalogue\ProduitDTO;
use pizzashop\shop\domain\exception\ProduitNonTrouveeException;

interface iInfoCatalogue{
    /**
     * Récupère un produit spécifique en utilisant son identifiant.
     *
     * @param int $numero L'identifiant du produit à récupérer.
     * @param int $taille La taille du produit à récupérer.
     * @return ProduitDTO Le produit correspondant.
     * @throws ProduitNonTrouveeException Si le produit n'est pas trouvé.
     */
    public function getProduit(int $num, int $taille): ProduitDTO;
    public function getProduitByCategorie($categorie): array;
    public function getAllProduits(): array;

    public function getProduitByNum(int $numero): array;


}