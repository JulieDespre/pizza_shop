<?php

namespace pizzashop\shop\domain\entities\commande;

use Illuminate\database\Eloquent\Model;
use Ramsey\Uuid\UuidInterface;

/**
 * @property string $commande_id
 * @property int $quantite
 * @property int $taille
 * @property int $numero
 * @property UuidInterface $id
 */
class Item extends Model{
    //propriétés connexion à la base de données
    protected $connection = 'commande';
    protected $table = 'item';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = ['id', 'numero', 'libelle', 'taille', 'libelle_taille', 'tarif','quantite', 'commande_id'];

}