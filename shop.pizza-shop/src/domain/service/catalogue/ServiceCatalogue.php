<?php

namespace pizzashop\shop\domain\service\catalogue;

use pizzashop\shop\domain\dto\catalogue\ProduitDTO;
use pizzashop\shop\domain\entities\catalogue\Produit;
use pizzashop\shop\domain\service\exception\ServiceCatalogueNotFoundException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ServiceCatalogue implements interfaceCatalogue
{
    //private $catalogue;

    public function __construct()
    {
        //$this->catalogue = new Catalogue();
    }

    /**
     * Récupère un produit spécifique en utilisant son identifiant.
     *
     * @param int $numero L'identifiant du produit à récupérer.
     * @param int $taille La taille du produit à récupérer.
     * @return ProduitDTO Le produit correspondant.
     * @throws ServiceCatalogueNotFoundException Si le produit n'est pas trouvé.
     */
    function getProduit(int $numero): ProduitDTO
    {
        try{
        $produit = Produit::where('numero', $numero) -> firstOfFail($numero);
    }catch (ModelNotFoundException $e){
        throw new ServiceCatalogueNotFoundException("Le produit $numero n'existe pas");
    }
    return $produit->toDTO();
    }

}