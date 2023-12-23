## Розгортання проекту
1. Клонувати проект
```bash
git clone https://github.com/renegade-d3v/PandaTeamTest
```
2. Зайти в папку проекту та скопіювати файл енвайрменту
```bash
cd PandaTeamTest
```
```bash
cp .env.example .env
```
3. Заповнити данні для відправки пошти
```bash
MAIL_MAILER=
MAIL_HOST=
MAIL_PORT=
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=
MAIL_FROM_ADDRESS=
```
4. Зібрати та підняти проект
```bash
docker compose up -d
```
5. Зайти в контейнер з БД та створити користувача
```bash
docker exec -it app-db mysql -uroot -proot
```
```mysql
SHOW DATABASES;
USE panda_db;
CREATE USER 'panda_app'@'%' IDENTIFIED BY '';
GRANT ALL PRIVILEGES ON panda_db.* TO 'panda_app'@'%';
FLUSH PRIVILEGES;
```
6. Встановити залежності composer
```bash
./composer install
```
7. Запустити міграції
```bash
./php-artisan migrate
```
8. Встановити залежності nodejs та запустити збірку для розробки
```bash
npm install
npm run dev
```
9. Відкрити у браузері
http://localhost:8000
