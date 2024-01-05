<?php

namespace pizzashop\shop\domain\entities\commande;
use pizzashop\shop\domain\entities\commande\CommandeDTO;

class Commande extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'commande';
    protected $primaryKey = 'id_commande';
    public $timestamps = false;
    protected $fillable = ['id_commande', 'date_commande', 'type_livraison', 'etat', 'montant_total', 'mail_client', 'delai'];

    public function items()
    {
        return $this->hasMany(Item::class, 'id_commande');
    }

    public function toDTO(): CommandeDTO{
    // Création d'un objet CommandeDTO avec quelques attributs de la commande actuelle
    $commandeDTO = new CommandeDTO(
        $this->id,
        $this->mail_client,
        $this->type_livraison,
        $this->items->toArray() 
    );

    // Assignation d'autres attributs de la commande à l'objet CommandeDTO
    $commandeDTO->date_commande = $this->date_commande;
    $commandeDTO->montant_total = $this->montant_total;
    $commandeDTO->etat = $this->etat;
    $commandeDTO->delai = $this->delai;


    $commandeDTO->items = [];

    // Convertir chaque item associé à cette commande en un objet DTO et les ajouter à la liste d'items du DTO
    foreach ($this->items() as $item) {
        $commandeDTO->items[] = $item->toDTO();
    }

    return $commandeDTO;
    }
}