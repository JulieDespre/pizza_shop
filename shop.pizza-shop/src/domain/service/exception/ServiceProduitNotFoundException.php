<?php

namespace pizzashop\shop\domain\service\exception;

use Exception;

class ServiceProduitNotFoundException extends Exception
{
    public function __construct(string $message = 'Le produit de la commande n\'a pas été trouvé.')
    {
        parent::__construct($message);
    }
}
