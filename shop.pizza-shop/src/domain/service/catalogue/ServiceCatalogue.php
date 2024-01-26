<?php

namespace pizzashop\shop\domain\service\catalogue;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use pizzashop\shop\domain\dto\catalogue\ProduitDTO;
use pizzashop\shop\domain\entities\catalogue\Produit;
use pizzashop\shop\domain\exception\ProduitNonTrouveeException;

/**
 * Service de gestion du catalogue
 */
class ServiceCatalogue implements iInfoCatalogue, iInfoProduit {

    function __construct() {}

    /**
     * Retourne un produit du catalogue en fonction de son numéro et de la taille
     * @param $numero
     * @param $taille
     * @return ProduitDTO
     * @throws ProduitNonTrouveeException
     */
    function getProduit(int $numero, int $taille) : ProduitDTO {
        try {
            $produit = Produit::where('numero', '=', $numero)->firstOrFail();
        }catch (ModelNotFoundException $e) {
            throw new ProduitNonTrouveeException($numero);
        }
        return $produit->toDTO();
    }

    /**
     * Retourne un produit du catalogue en fonction de son numéro
     * @param $numero
     * @return array
     * @throws ProduitNonTrouveeException
     */
    function getProduitByNum(int $numero) : array {
        //get un produit et le retourne en tant que tableau, affiche son prix en fonction de chaque taille
        try {
            $produit = Produit::where('numero', '=', $numero)->firstOrFail();
        }catch (ModelNotFoundException $e) {
            throw new ProduitNonTrouveeException($numero);
        }
        $produitDTO = array();
        foreach ($produit->tailles()->get() as $taille) {
            $produitDTO[] = $produit->toDTO($taille->id);
        }
        return $produitDTO;
    }

    /**
     * Retourne tous les produits du catalogue
     * @return array
     */
    function getAllProduits() : array
    {
        $produits = Produit::all();
        $produitsDTO = array();
        foreach ($produits as $produit) {
            $produitsDTO[] = $produit->toDTO($produit->tailles()->get()->first()->id);
        }
        return $produitsDTO;
    }

    /**
     * Retourne tous les produits du catalogue en fonction de la catégorie
     * @param $categorie_id int id de la catégorie
     * @return array tableau de ProduitDTO
     */
    function getProduitByCategorie($categorie_id) : array
    {
        $produits = Produit::where('categorie_id', '=', $categorie_id)->get();
        $produitsDTO = array();
        foreach ($produits as $produit) {
            $produitsDTO[] = $produit->toDTO($produit->tailles()->get()->first()->id);
        }
        return $produitsDTO;
    }

}