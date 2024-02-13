<?php

namespace pizzashop\shop\domain\entities\catalogue;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Taille extends Model
{

    const NORMALE = 1;
    const GRANDE = 2;
	
    protected $connection = 'catalog';
    protected $table = 'taille';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [ 'libelle'];

    public function produits(): BelongsToMany
    {
        return $this->belongsToMany(Produit::class, 'tarif', 'taille_id', 'produit_id');
    }

}