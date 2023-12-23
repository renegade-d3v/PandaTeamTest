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

if [ -f .env ]; then
  export $(grep -v '^#' .env | xargs)
fi

# Запрашиваем у пользователя название базы данных
read -p "Введіть назву для БД (Назва за замовчуванням: $DB_NAME): " user_input_db_name
DB_NAME=${user_input_db_name:-$DB_NAME}

read -p "Введіть ім'я користувача (Ім'я за замовчуванням: $DB_USER): " user_input_db_username
DB_USER=${user_input_db_username:-$DB_USER}

read -p "Введіть пароль для БД (Пароль за замовчуванням відсутній $DB_PASSWORD): " user_input_db_pass
DB_PASSWORD=${user_input_db_pass:-$DB_PASSWORD}

if [ -z "$DB_NAME" ] || [ -z "$DB_USER" ] || [ -z "$DB_PASSWORD" ]; then
  echo "Не вдалося отримати данні для створення БД"
  exit 1
fi

echo "DB_NAME=$DB_NAME" >> .env
echo "DB_USER=$DB_USER" >> .env
echo "DB_PASSWORD=$DB_PASSWORD" >> .env

docker exec -it app-db mysql -uroot -proot -e "USE $DB_NAME" 2>/dev/null

if [ $? -eq 0 ]; then
  echo "База данных $DB_NAME уже существует."
else
  docker exec -it app-db mysql -uroot -proot -e "CREATE DATABASE $DB_NAME CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
  docker exec -it app-db mysql -uroot -proot -e "CREATE USER '$DB_USER'@'%' IDENTIFIED BY '$DB_PASSWORD';"
  docker exec -it app-db mysql -uroot -proot -e "GRANT ALL ON $DB_NAME.* TO '$DB_USER'@'%';"
  docker exec -it app-db mysql -uroot -proot -e "FLUSH PRIVILEGES;"
fi

command="php artisan migrate"
docker exec -it app bash -c "sudo -u devuser /bin/bash -c \"$command\""

npm run dev
