<?php

namespace pizzashop\shop\domain\exception;

class TokenInvalidException extends \Exception {
    public function __construct() {
        parent::__construct("Le token n'est pas valide",401);
    }
}