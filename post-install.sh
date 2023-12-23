#!/bin/bash

set -e

sudo -k

cp .env.example .env

docker compose build --no-cache

if ! command -v node &> /dev/null; then
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
    sudo apt-get install -y gcc g++ make nodejs
fi

docker compose up -d

DB_DATABASE="panda_db"
echo "Введіть назву для БД (Назва за замовчуванням: $DB_DATABASE): "
read user_input
DB_DATABASE=${user_input:-$DB_DATABASE}

DB_USERNAME="panda_app"
echo "Введіть ім'я користувача (Ім'я за замовчуванням: $DB_USERNAME): "
read user_input
DB_USERNAME=${user_input:-$DB_USERNAME}

DB_PASSWORD=""
echo "Введіть пароль для БД (Пароль за замовчуванням відсутній): "
read -r user_input
DB_PASSWORD=${user_input:-$DB_PASSWORD}

if [ -z "$DB_DATABASE" ] || [ -z "$DB_USERNAME" ] || [ -z "$DB_PASSWORD" ]; then
  echo "Не вдалося отримати данні для створення БД"
  exit 1
fi

echo "DB_DATABASE=$DB_DATABASE" >> .env
echo "DB_USERNAME=$DB_USERNAME" >> .env
echo "DB_PASSWORD=$DB_PASSWORD" >> .env

docker exec -it app-db mysql -uroot -proot -e "USE $DB_NAME" 2>/dev/null

if [ $? -eq 0 ]; then
  echo "База данных $DB_NAME уже существует."
else
  docker exec -it app-db mysql -uroot -proot -e "CREATE DATABASE $DB_DATABASE CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
  docker exec -it app-db mysql -uroot -proot -e "CREATE USER '$DB_USERNAME'@'%' IDENTIFIED BY '$DB_PASSWORD';"
  docker exec -it app-db mysql -uroot -proot -e "GRANT ALL ON $DB_DATABASE.* TO '$DB_USERNAME'@'%';"
  docker exec -it app-db mysql -uroot -proot -e "FLUSH PRIVILEGES;"
fi

command="php artisan migrate"
docker exec -it app bash -c "sudo -u devuser /bin/bash -c \"$command\""

npm run dev
