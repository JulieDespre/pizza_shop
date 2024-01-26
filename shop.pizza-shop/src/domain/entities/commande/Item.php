<?php

namespace pizzashop\shop\domain\entities\commande;

use Illuminate\database\Eloquent\Model;
use pizzashop\shop\domain\dto\commande\ItemDTO;
use pizzashop\shop\domain\entities\Commande;

class Item extends Model{
    //propriétés connexion à la base de données
    protected $connection = 'commande';
    protected $table = 'item';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = ['id', 'numero', 'libelle', 'taille', 'libelle_taille', 'tarif','quantite', 'commande_id'];

    public function getCommande(): BelongsTo { //retourne objet de type BelongsTo, lien vers la commande
        return $this->belongsTo(Commande::class, 'commande_id');
    }

}