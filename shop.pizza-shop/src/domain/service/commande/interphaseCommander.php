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
    
    /**
     * Modifie une commande spécifique en utilisant son identifiant et de nouvelles données.
     *
     * @param string $commandeId L'identifiant de la commande à modifier.
     * @param CommandeDTO $nouvellesDonnees Les nouvelles données de la commande.
     * @return CommandeDTO La commande modifiée.
     * @throws ServiceCommandeNotFoundException Si la commande n'est pas trouvée.
     * @throws ServiceException Si une erreur survient lors de la modification de la commande.
     */
    public function modifierCommande(string $commandeId, CommandeDTO $nouvellesDonnees): CommandeDTO;
}
