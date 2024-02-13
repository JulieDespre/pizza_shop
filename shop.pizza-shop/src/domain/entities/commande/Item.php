<?php

namespace pizzashop\shop\domain\entities\commande;

use Illuminate\database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use pizzashop\shop\domain\entities\commande\Commande;

class Item extends Model{
    //propriétés connexion à la base de données
    protected $connection = 'commande';
    protected $table = 'item';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = ['id', 'numero', 'libelle', 'taille', 'libelle_taille', 'tarif','quantite', 'commande_id'];

    public function getCommande(): BelongsTo {
        return $this->belongsTo(Commande::class, 'commande_id');
    }
}