<?php

namespace pizzashop\shop\domain\exception;

class ErreurEtatCommandeException extends \Exception {

    public function __construct(string $id) {
        parent::__construct("La commande $id a déjà dépassé l'état de validation",400);
    }
}