<?php

namespace pizzashop\shop\domain\service\exception;

use Exception;
use Throwable;

class ServiceCommandeInvalidDataException extends Exception
{
    public function __construct(string $message = "Les données de la commande sont invalides", int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
