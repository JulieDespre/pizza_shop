<?php

namespace pizzashop\shop\domain\service\exception;

use Exception;

class ServiceException extends Exception
{
    public function __construct(string $message = 'Une erreur est survenue lors de la modification de la commande.')
    {
        parent::__construct($message);
    }
}
