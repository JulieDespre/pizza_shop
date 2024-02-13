<?php

namespace pizzashop\shop\domain\dto\commande;

class CommandeDTO {
    public string $id;
    public string $date_commande;
    public string $type_livraison;
    public string $etat;
    public float $montant_total;
    public string $mail_client;
    public string $delai;
    public array $items = [];

    /**
     * CommandeDTO constructor.
     * @param string $mail_client
     * @param string $type_livraison
     * @param array $items
     */
    public function __construct(string $mail_client, string $type_livraison, array $items){
        $this->mail_client = $mail_client;
        $this->type_livraison = $type_livraison;
        foreach ($items as $item){
            $this->items[] = $item->toDTO();       
        }
    }

    /**
     * Ajoute un item à la commande.
     * @param Item $item
     * @return void
     */
    public function addItem(Item $item): void
    {
        $this->items[] = $item->toDTO();
    }
}