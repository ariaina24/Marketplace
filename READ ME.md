
# Avant de lancer le projet

Si vous utiliser MySql
> symfony console doctrine:database:create

Puis
> symfony console make:migration

Apres
> php bin/console doctrine:migrations:migrate

Run
> composer install

Pour installer stripe
> composer require stripe/stripe-php

Pour l'API airtel
> composer require guzzlehttp/guzzle

Enfin
> symfony server:start
