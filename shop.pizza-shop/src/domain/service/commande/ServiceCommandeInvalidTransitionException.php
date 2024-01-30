<?php

namespace pizzashop\shop\domain\service\commande;

class ServiceCommandeInvalidTransitionException
{

    /**
     * @param string $commandeId
     */
    public function __construct(string $commandeId)
    {
    }
}