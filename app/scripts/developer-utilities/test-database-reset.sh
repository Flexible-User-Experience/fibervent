#!/bin/bash

echo "Reseting database test script."
echo "Started at `date +"%T %d/%m/%Y"`"

php app/console cache:clear --env=test
php app/console doctrine:database:drop --force --env=test
php app/console doctrine:database:create --env=test
php app/console doctrine:schema:update --force --env=test
php app/console hautelook_alice:doctrine:fixtures:load -n --env=test

echo "Finished at `date +"%T %d/%m/%Y"`"
echo "EOF."
