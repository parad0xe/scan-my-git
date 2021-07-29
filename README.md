# scan-my-git

Scan My Git est une application open-source permettant d'analyser des repositories Github

## Installation

### Télécharger le repository

```bash
git clone https://github.com/parad0xe/scan-my-git.git
cd scan-my-git
```

### Modifier les variables d'environements

```bash
cp .env .env.local
```

Dans le `.env.local`:

* Modifier le `APP_SECRET`
* Modifier le `DATABASE_URL` => mysql://root:root@database:3306/scan_my_git
* Décommenter `MERCURE_URL`
* Décommenter `MERCURE_PUBLIC_URL`
* Décommenter `MERCURE_JWT_SECRET`
* Modifier le `MERCURE_JWT_SECRET` => super-secret

### Initialisation de Docker

Build docker:

```bash
docker-compose build
```

Run Docker:

```bash
docker-compose up -d
```

### Installation des dépendances

#### Composer

```bash
docker-compose exec php-fpm composer install
```

#### NPM

```npm
docker-compose exec php-fpm npm install
```

```npm
docker-compose exec php-fpm npm run build
```

### Flush des Migrations

```bash
docker-compose exec php-fpm php bin/console doctrine:migrations:migrate
```

### Flush des Fixtures

```bash
docker-compose exec php-fpm php bin/console doctrine:fixtures:load
# type `yes`
```

## Contribution

Pour contribuer au développement de l'application, veuillez suivre les instructions suivantes:

Déplacer vous sur la branche `develop`:

```bash
git checkout develop
```

Créer une nouvelle pour votre fonctionnalité:

```bash
git checkout -b feature/<nom-de-votre-fonctionnalité>
```

Démarrer le watch des assets:

```bash
docker-compose exec php-fpm npm run watch
```

> Pour que les fonctionnalitées soient ajouté à la branche principale, créer une nouvelle pull request 
qui sera validé ultérieurement.

## Crédits

@parad0xe(https://github.com/parad0xe) \
@Yann-IT(https://github.com/Yann-IT) \
@IssaDia(https://github.com/IssaDia) \
@aelii(https://github.com/aelii)
