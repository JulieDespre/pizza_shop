<?php

namespace pizzashop\shop\domain\service\exception;

use Exception;

class ServiceCommandeInvalidTransitionException extends Exception
{
    public function __construct(string $message = 'La transition de la commande est invalide.')
    {
        parent::__construct($message);
    }
}
