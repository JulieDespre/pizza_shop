<?php

namespace pizzashop\shop\domain\service\exception;

use Exception;

class ServiceCommandeInvalidItemException extends Exception
{
    public function __construct(string $message = 'Un ou plusieurs items de la commande sont invalides.')
    {
        parent::__construct($message);
    }
}
