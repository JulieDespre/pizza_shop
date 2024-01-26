<?php

namespace pizzashop\shop\domain\exception;

class ProduitNonTrouveeException extends \Exception {

    public function __construct(string $id) {
        parent::__construct("Le produit $id n'a pas été trouvé",404);
    }
}