<?php

namespace pizzashop\shop\domain\dto\catalogue;

class ProduitDTO extends \pizzashop\shop\domain\dto\DTO
{


    public int $id;
    public int $numero_produit;
    public string $libelle_produit;
    public string $description_produit;
    public string $libelle_categorie;
    public string $libelle_taille;
    public float $tarif;

    /**
     * @param int $id
     * @param int $numero_produit
     * @param string $libelle_produit
     * @param string $libelle_categorie
     * @param string $libelle_taille
     * @param float $tarif
     */
    public function __construct(int $id, int $numero_produit, string $libelle_produit,string $description_produit, string $libelle_categorie, string $libelle_taille, float $tarif)
    {
        $this->id = $id;
        $this->numero_produit = $numero_produit;
        $this->libelle_produit = $libelle_produit;
        $this->description_produit = $description_produit;
        $this->libelle_categorie = $libelle_categorie;
        $this->libelle_taille = $libelle_taille;
        $this->tarif = $tarif;
    }


}