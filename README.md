# Todo List

TodoList est une application Symfony en suivant le principe du TDD et la librairie PHPunit.
Ce projet a été créé dans le cadre du projet 8 d'OpenClassrooms de la formation développeur d'application PHP/Symfony.

## Requis

- Docker
- Composer

## Installation

1- télécharger le repository.

2- allez dans le dossier api-bilemo avec votre invite de commande est créer l'image avec docker et monter cette image.

```sh
cd path/to/p8-openclassrooms
docker build -t p8 .
docker-composer up
```
3- Installez les dépendances est le fichier .env avec composer install.
```sh
composer install
```
4- Dans le fichier .env définissez la base de données.
```sh
DATABASE_URL="mysql://root:@mysql/p8"
```
5- Créer la base de données.
```sh
php bin/console doctrine:database:create
```
6- Importer les entités dans la base de données.
```sh
php bin/console doctrine:schema:create
```
7- Installez les fixtures.
```sh
php bin/console doctrine:fixture:load
```
8- Créer la base de données de test.
```sh
php bin/console doctrine:database:create --env=test
```

9- Importer le schéma de la base de données de test.
```sh
php bin/console doctrine:schema:create --env=dev
```
