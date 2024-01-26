<?php
require_once __DIR__ . '/../vendor/autoload.php';
use Illuminate\Database\Capsule\Manager as DB;
use pizzashop\shop\domain\dto\commande\CommandeDTO;
use pizzashop\shop\domain\dto\commande\ItemDTO;
use pizzashop\shop\domain\entities\catalogue\Categorie;
use pizzashop\shop\domain\entities\catalogue\Taille;
use pizzashop\shop\domain\entities\catalogue\Produit;
use Faker\Factory;
use pizzashop\shop\domain\entities\commande\Commande;

$dbcom = __DIR__ . '/../config/commande.db.ini';
$dbcat = __DIR__ . '/../config/catalog.db.ini';
$db = new DB();
$db->addConnection(parse_ini_file($dbcom), 'commande');
$db->addConnection(parse_ini_file($dbcat), 'catalog');
$db->setAsGlobal();
$db->bootEloquent();
$loger = new \Monolog\Logger('test');
$loger->pushHandler(new \Monolog\Handler\StreamHandler(__DIR__ . '/../logs/test.log', \Monolog\Logger::DEBUG));

$faker = Factory::create('fr_FR');

$commandeDTO = new CommandeDTO('mattheo@gmail.com', 'A Domicile', [1, 2]);
$commandeDTO->id = $faker->uuid;
$commandeDTO->date = $faker->dateTime;
$commandeDTO->etat = Commande::ETAT_PREPARATION;
$commandeDTO->mail_client = $faker->email;
$commandeDTO->type_livraison = Commande::LIVRAISON_A_EMPORTER;
$commandeDTO->delai = 0;
$commandeDTO->montant_total = 0;
$commandeDTO->items = [];

$produits = Produit::all();
$tailles = Taille::all();
$categories = Categorie::all();

$infoproduit = new \pizzashop\shop\domain\service\catalogue\ServiceCatalogue();
$service_commande = new \pizzashop\shop\domain\service\commande\ServiceCommande($loger, $infoproduit);
$res = $service_commande->creerCommande($commandeDTO);
return json_encode($res, JSON_PRETTY_PRINT);