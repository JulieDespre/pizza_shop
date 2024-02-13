<?php
 namespace pizzashop\shop\domain\service\exception;

 use Exception;

 class ServiceCommandeNotFoundException extends Exception
 {
     public function __construct(string $UUID)
     {
         parent::__construct("La commande $UUID n'existe pas");
     }
 }