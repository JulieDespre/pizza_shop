<?php

namespace pizzashop\shop\domain\exception;

use Exception;
use Respect\Validation\Exceptions\NotEmptyException;
use Respect\Validation\Exceptions\NegativeException;
use Respect\Validation\Exceptions\BetweenException;
use Respect\Validation\Exceptions\NumericValException;
use Respect\Validation\Exceptions\ValidationException;
use Respect\Validation\Exceptions\EmailException;

class ValidationCommandeException extends Exception
{
    public function __construct($message = "", $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function handleNotEmptyException(NotEmptyException $e)
    {
        $this->message = "La validation a échoué : " . $e->getMessage();
    }

    public function handleNegativeException(NegativeException $e)
    {
        $this->message = "La validation a échoué : " . $e->getMessage();
    }

    public function handleBetweenException(BetweenException $e)
    {
        $this->message = "La validation a échoué : " . $e->getMessage();
    }

    public function handleNumericValException(NumericValException $e)
    {
        $this->message = "La validation a échoué : " . $e->getMessage();
    }

    public function handleEmailException(EmailException $e)
    {
        $this->message = "La validation a échoué : " . $e->getMessage();
    }

    public function handleValidationException(ValidationException $e)
    {
        $this->message = "La validation a échoué : " . $e->getMessage();
    }

    public function handleException(Exception $e)
    {
        $this->message = "La validation a échoué : " . $e->getMessage();
    }

    public function handleDefault()
    {
        $this->message = "La validation a échoué";
    }
}

