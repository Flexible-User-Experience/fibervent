#!/bin/bash

if [ -z "$1" ]
  then
    php app/console doctrine:database:drop --force
    php app/console doctrine:database:create
    php app/console doctrine:schema:update --force
    # php app/console doctrine:migrations:diff
    # php app/console doctrine:migrations:mig
    php app/console hautelook_alice:doctrine:fixtures:load -n
  else
    if [ "$1" = "dev" -o "$1" = "test" -o "$1" = "prod" -o "$1" = "remote" ]
      then
        if [ "$1" = "remote" ]
          then
            # cap symfony:doctrine:database:drop
            # cap symfony:doctrine:database:create
            # cap symfony:doctrine:schema:update
            # cap symfony:doctrine:load_fixtures
            echo "Be careful, this is a real production environment"
          else
            php app/console doctrine:database:drop --force --env="$1"
            php app/console doctrine:database:create --env="$1"
            php app/console doctrine:schema:update --force --env="$1"
            php app/console hautelook_alice:doctrine:fixtures:load -n --env="$1"
        fi
      else
        echo "Argument error! Available argument options: 'remote', 'dev', 'test' or 'prod'"
    fi
fi
