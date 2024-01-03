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
    public array $items;
   
    /**
     * CommandeDTO constructor.
     * @param string $id
     * @param string $mail_client
     * @param string $type_livraison
     * @param array $items
     */
    public function __construct(string $id, string $mail_client, string $type_livraison, array $items){
        $this->id = $id;
        $this->mail_client = $mail_client;
        $this->type_livraison = $type_livraison;
        foreach ($items as $item){
            $this->items[] = new ItemDTO($item['numero'], $item['taille'], $item['quantite']);       
        }
    }
}