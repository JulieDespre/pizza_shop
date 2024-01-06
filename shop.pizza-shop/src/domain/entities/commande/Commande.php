<?php

namespace pizzashop\shop\domain\entities\commande;
use pizzashop\shop\domain\entities\commande\CommandeDTO;

class Commande extends \Illuminate\Database\Eloquent\Model
{
    const ETAT_CREE = 1;
    const ETAT_VALIDE = 2;
    const ETAT_PAYEE = 3;
    const ETAT_LIVREE = 4;

    const LIVRAISON_SUR_PLACE = 1;
    const LIVRAISON_DOMICILE = 2;
    const LIVRAISON_A_EMPORTER = 3;

    protected $connection = 'commande';
    protected $table = 'commande';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = ['id', 'date_commande', 'type_livraison', 'etat', 'montant_total', 'mail_client', 'delai'];

    public function items()
    {
        return $this->hasMany(Item::class, 'id');
    }

    public function toDTO(): CommandeDTO{
    // Création d'un objet CommandeDTO avec quelques attributs de la commande actuelle
    $commandeDTO = new CommandeDTO(
        //sont les attributs de la commandeDTO losqu'elle est créée
        $this->mail_client,
        $this->type_livraison,
        $this->items->toArray() 
    );

    // Assignation d'autres attributs de la commande à l'objet CommandeDTO
    $commandeDTO->id = $this->id_commande;
    $commandeDTO->date_commande = $this->date_commande;
    $commandeDTO->montant_total = $this->montant_total;
    $commandeDTO->etat = $this->etat;
    $commandeDTO->delai = $this->delai ?? 0;

    //initialise un tableau vide d'items dans commandeDTO
    $commandeDTO->items = [];

    // Convertir chaque item associé à cette commande en un objet DTO et les ajouter à la liste d'items du DTO et remplis le tableau
    foreach ($this->items() as $item) {
        $commandeDTO->items[] = $item->toDTO();
    }

    return $commandeDTO;
    }
}