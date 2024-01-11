# Projet Blog sous Symfony 6

![Logo de l'application](www/public/assets/images/logo_blogger.png)

Réalisation d'un projet de blog sous Symfony 6 dans le cadre de cours.

## Prérequis
Assurez-vous d'avoir les logiciels suivants installés :
- Docker Engine : [Télécharger Docker](https://docs.docker.com/get-docker/)
- Composer : [Instaler Composer](https://getcomposer.org/download/)

## Lancement du conteneur Docker

- Clone this repository on your local computer
- configure .env as needed
- Run the `docker-compose up -d`.


Your LAMP stack is now ready!! You can access it via `http://localhost`.

PhpMyAdmin is now accessible via `http://localhost:8080`.

## Configuration de Symfony

- rendez vous dans le répertoire contenant le projet Symfony `cd www`.
- installez les dépendances via `composer install`.
- puis `npm install`.

## Base de données
- Si la base de données n'existe pas, vous pouvez la créer avec la commande `symfony console database:create`.
- Créez un fichier de migration pour importer le shéma de la base avec `symfony console make:migration`.
- Importation du shéma de la base `symfony console doctrine:migrations:migrate`.

## Compilation des assets
Vous pouvez compiler les assetes avec la commande `npm run prod`.

## Lancement de l'application
Vous pouvez aceder à l'application sur `http://localhost`.

# utilisation de l'application
L'aplication est séparée en deux parties : 
- la partie publique
- l'espace d'administration

Vous pouvez créer un utilisateur via le bouton créer un compte.

Pour pouvoir acceder à la partie administrateur, modifiez la propriété 'roles' dans la base de données vers `["ROLE_ADMIN"]`.
