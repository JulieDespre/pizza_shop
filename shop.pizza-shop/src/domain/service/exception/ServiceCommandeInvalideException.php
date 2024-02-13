<?php

namespace pizzashop\shop\domain\service\exception;

use Exception;

class ServiceCommandeInvalideException extends Exception
{
    public function __construct(string $message = 'La commande est invalide.')
    {
        parent::__construct($message);
    }
}
