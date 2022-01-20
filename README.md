# Todo List

TodoList est une apllication Symfony en suivant le principe du TDD et la librairie PHPunit.
Ce projets a été crée dans le cadre du projets 8 d'Openclassrooms de la formation développeur d'application PHP/Symfony.

## Requis

- Docker
- Composer

## Installation

1- télécharger le repository.

2- allez dans le dossier api-bilemo avec votre invite de commande est builder l'image avec docker et monter cette image.

```sh
cd path/to/p8-openclassrooms
docker build -t p8 .
docker-composer up
```
3- Installez les dépendance est le fichier .env avec composer install.
```sh
composer install
```
4- Dans le fichier .env définissez la base de donnée.
```sh
DATABASE_URL="mysql://root:@mysql/p8"
```
5- Créer la base de données.
```sh
php bin/console doctrine:database:create
```
6- Importer les entité dans la base de donnée.
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

9- Importer le schema de la base de donnée de test.
```sh
php bin/console doctrine:schema:create --env=dev
```
