# Horizon Auto

Application Symfony de gestion de location de véhicules : véhicules, catégories, clients, locations et options.

## Prérequis

- PHP 8.2 ou supérieur
- Composer
- PostgreSQL 16
- Symfony CLI (facultatif, pour lancer le serveur de développement)

## Installation

```bash
composer install
```

Copiez `.env.example` vers `.env` (sous PowerShell : `Copy-Item .env.example .env`), puis adaptez `DATABASE_URL` et les paramètres PostgreSQL à votre environnement.

```bash
php bin/console doctrine:migrations:migrate
```

## Lancer l'application

Avec Symfony CLI :

```bash
symfony server:start
```

L'application est alors accessible à l'adresse indiquée par Symfony CLI, généralement <http://127.0.0.1:8000>.
