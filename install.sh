#!/bin/bash

cd app && composer install

#enable mysql
sed -i "/^# DATABASE_URL=\"mysql:\/\/app:!ChangeMe\!\@127.0.0.1:3306\/app?serverVersion=8&charset=utf8mb4\"/ cDATABASE_URL=\"mysql:\/\/root:secret\@0.0.0.0:4306\/xm-test-task?serverVersion=8&charset=utf8mb4\"" ./app/.env
#disable postgres
sed -i "/DATABASE_URL=\"postgresql:\/\/app:!ChangeMe\!\@127.0.0.1:5432\/app?serverVersion=14&charset=utf8\"/ c#/DATABASE_URL=\"postgresql:\/\/app:!ChangeMe\!\@127.0.0.1:5432\/app?serverVersion=14&charset=utf8" ./app/.env

php bin/console doctrine:database:create