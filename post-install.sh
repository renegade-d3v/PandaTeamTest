#!/bin/bash

set -e

sudo -k

cp .env.example .env

docker compose build --no-cache

if ! command node -v &> /dev/null; then
    sudo add-apt-repository ppa:chris-lea/node.js
    sudo apt-get update
    sudo apt-get install nodejs
fi

docker compose up -d

DB_USERNAME="panda_db"
DB_USERNAME="panda_app"
DB_PASSWORD=""

docker exec -it app-db mysql -uroot -proot -e "USE $DB_DATABASE" 2>/dev/null

if [ $? -eq 0 ]; then
  echo "База данных $DB_DATABASE уже существует."
else
  docker exec -it app-db mysql -uroot -proot -e "CREATE DATABASE $DB_DATABASE CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
  docker exec -it app-db mysql -uroot -proot -e "CREATE USER '$DB_USERNAME'@'%' IDENTIFIED BY '$DB_PASSWORD';"
  docker exec -it app-db mysql -uroot -proot -e "GRANT ALL ON $DB_DATABASE.* TO '$DB_USERNAME'@'%';"
  docker exec -it app-db mysql -uroot -proot -e "FLUSH PRIVILEGES;"
fi

command="php artisan migrate"
docker exec -it app bash -c "sudo -u devuser /bin/bash -c \"$command\""

npm run dev
