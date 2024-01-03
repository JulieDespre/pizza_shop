<?php

namespace pizzashop\shop\domain\dto\item;

class ItemDTO {
    public string $id;
    public string $libelle;
    public string $libelle_taille;
    public float $tarif;
    public int $numero;
    public int $taille;
    public int $quantite;
    
    public function __construct(string $numero, string $taille, int $quantite){
        $this->numero = $numero;
        $this->taille = $taille;
        $this->quantite = $quantite;
    }
}