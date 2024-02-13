<?php

namespace pizzashop\shop\domain\entities\commande;

use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Relations\HasMany;
use pizzashop\shop\domain\dto\commande\CommandeDTO;
use Ramsey\Collection\Collection;
use Ramsey\Uuid\Uuid;

/**
 * @property Collection $items
 * @property float $montant_total
 * @property string $mail_client
 * @property int $type_livraison
 * @property string $id
 * @property DateTime $date_commande
 * @property int $etape
 * @method static find(string $commandeId)
 */
class Commande extends Model
{
    const ETAT_CREE = 1;
    const ETAT_VALIDE = 2;
    const ETAT_PAYEE = 3;
    const ETAT_LIVREE = 4;

    const LIVRAISON_SUR_PLACE = 1;
    const LIVRAISON_A_DOMICILE = 2;
    const LIVRAISON_A_EMPORTER = 3;

    protected $connection = 'commande';
    protected $table = 'commande';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'date_commande',
        'type_livraison',
        'etape',
        'montant_total',
        'mail_client',
        'delai'
    ];

    public function items(): HasMany
    {
        return $this->hasMany(Item::class, 'commande_id');
    }

    public static function create(array $attributes): Commande
    {
        // Génération d'un nouvel ID si non fourni
        if (!isset($attributes['id'])) {
            $attributes['id'] = Uuid::uuid4();
        }

        // Création de la commande
        $commande = new self($attributes);
        $commande->save();

        return $commande;
    }

    public function calculerMontantTotal(): void
    {
        $montant = 0;
        foreach ($this->items as $item) {
            $montant += $item->tarif * $item->quantite;
        }
        $this->montant_total = $montant;
        $this->save();
    }

    public function toDTO(): CommandeDTO
    {
        // Création d'un objet CommandeDTO avec quelques attributs de la commande actuelle
        $commandeDTO = new CommandeDTO(
            $this->mail_client,
            $this->type_livraison,
            $this->items->toArray()
        );

        // Assignation d'autres attributs de la commande à l'objet CommandeDTO
        $commandeDTO->id = $this->id;
        $commandeDTO->date_commande = $this->date_commande;
        $commandeDTO->montant_total = $this->montant_total;
        $commandeDTO->etat = $this->etape;
        $commandeDTO->delai = $this->delai ?? 0;

        // Initialise un tableau vide d'items dans commandeDTO
        $commandeDTO->items = [];

        // Convertir chaque item associé à cette commande en un objet DTO et les ajouter à la liste d'items du DTO et remplis le tableau
        foreach ($this->items as $item) {
            $commandeDTO->items[] = $item->toDTO();
        }

        return $commandeDTO;
    }

    /**
     * Trouver une commande par son identifiant ou lever une exception si non trouvée.
     *
     * @param string $commandeId L'identifiant de la commande à trouver.
     * @return Commande La commande trouvée.
     * @throws ModelNotFoundException Si la commande n'est pas trouvée.
     */
    public static function findOrFail(string $commandeId): self
    {
        $commande = self::find($commandeId);

        if (!$commande) {
            throw new ModelNotFoundException("La commande avec l'identifiant $commandeId n'a pas été trouvée.");
        }

        return $commande;
    }
}
