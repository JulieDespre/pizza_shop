<?php 
namespace pizzashop\shop\domaine\service\commande\interfaceCommander;

use pizzashop\shop\domaine\dto\commande\CommandeDTO;

interface interfaceCommander{
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
