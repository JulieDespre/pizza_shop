README - pizza-shop.net
Présentation

nrv.net est un site permettant de réserver des billets pour différents événements, notamment des spectacles. Ce dépôt contient des instructions pour installer le projet localement et configurer les composants requis.
Installation

    Clonez le dépôt sur votre machine locale :

git clone https://github.com/JulieDespre/pizza_shop.git
cd pizza.shop.components

    Utilisez Docker Compose pour démarrer les composants nécessaires. Exécutez la commande suivante à la racine du projet :

lancer Docker : docker compose up -d

    Installez les dépendances du projet en utilisant Composer. Exécutez les commandes suivantes dans les répertoires respectifs :

cd ..
composer install

Cette commande démarrera les services requis en mode détaché.

    Installez la base de données en utilisant les détails de connexion suivants :

Pour la BDD catalogue utiliser le lien adminer et entrer le paramètres suivant:

## pour la base de donnée commande

- System : MySQL
- Server : pizza-shop.commande.db
- Username : root
- Password : r00tpizz
- Database : pizza_shop

Ensuite cliquer sur l'onglet importer afin d'importer les 2 fichiers .sql -> pizza.shop/shop.pizza-shop/sql/

- pizza_shop.commande.schema.sql
- pizza_shop.commande.data.sql

### Pour la base de donnée catalogue

cliquer sur SQL pour revenir à l'acceuil
Pour la BDD catalogue utiliser le lien adminer et entrer le paramètres suivant:

- System : PostgreSQL
- Server : pizza-shop.catalogue.db
- Username : pizza_cat
- Password : pizza_cat
- Database : pizza_catalog

importer les 2 fichiers .sql -> pizza.shop/shop.pizza-shop/sql/

- pizza_shop.auth.schema.sql
- pizza_shop.auth.data.sql

## Auteur :

Julie Despré

## lien GIT :

https://github.com/JulieDespre/pizza_shop.git
