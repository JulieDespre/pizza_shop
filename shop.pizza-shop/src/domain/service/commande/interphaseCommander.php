<?php 
namespace pizzashop\shop\domaine\service\commande;

use pizzashop\shop\domaine8\dto\commande\CommandeDTO;

interface interphasecommander{
    /**
     * Accède à une commande spécifique en utilisant son identifiant.
     *
     * @param string $commandeId L'identifiant de la commande à accéder.
     * @return CommandeDTO La commande correspondante.
     * @throws ServiceCommandeNotFoundException Si la commande n'est pas trouvée.
     */
    public function accederCommande(string $commandeId): CommandeDTO;
    
    /**
     * Valide une commande spécifique en utilisant son identifiant.
     *
     * @param string $commandeId L'identifiant de la commande à valider.
     * @return CommandeDTO La commande validée.
     * @throws ServiceCommandeNotFoundException Si la commande n'est pas trouvée.
     */
    public function validerCommande(string $commandeId): CommandeDTO;
    
   
}
