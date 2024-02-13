<?php
namespace pizzashop\shop\domain\service\exception;

use Exception;

class ServiceCatalogueNotFoundException extends Exception
{
    public function __construct()
    {
        parent::__construct();
    }
}
