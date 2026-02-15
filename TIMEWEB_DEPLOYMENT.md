# 🚀 Инструкция по развертыванию на Timeweb

**Логин хостинга:** co53144  
**Дата:** 15 февраля 2026 года

Подробная инструкция по развертыванию Laravel Stat на хостинге Timeweb.

---

## 📋 Предварительная информация

### Особенности хостинга Timeweb:

- **PHP версия:** Можно выбрать 8.1 или выше в панели управления
- **SSH доступ:** Доступен для всех тарифов
- **Composer:** Установлен по умолчанию
- **MySQL:** Доступен через панель управления
- **Веб-сервер:** Apache с mod_rewrite
- **Корневая директория:** `/home/c/co53144/`
- **Публичная директория:** `/home/c/co53144/domen.ru/public_html/`

---

## 🔐 Шаг 1: Подключение по SSH

### Получение данных для SSH:

1. Войдите в панель управления Timeweb: https://timeweb.com/ru/my/
2. Перейдите в раздел **SSH-доступ**
3. Скопируйте данные:
   - **Хост:** например, `ssh.timeweb.ru` или `vh123.timeweb.ru`
   - **Порт:** обычно `22`
   - **Логин:** `co53144`
   - **Пароль:** ваш пароль от хостинга

### Подключение:

```bash
ssh co53144@ssh.timeweb.ru
# Или
ssh co53144@vh123.timeweb.ru

# Введите пароль когда попросит
```

---

## 📁 Шаг 2: Подготовка директорий

```bash
# Переходим в домашнюю директорию
cd /home/c/co53144/

# Создаем директорию для Laravel приложения (вне public_html)
mkdir -p laravel-stat
cd laravel-stat
```

---

## 📦 Шаг 3: Установка Laravel

### Проверка PHP и Composer:

```bash
# Проверяем версию PHP
php -v

# Если версия ниже 8.1, измените в панели Timeweb:
# Сайты → Ваш домен → PHP → Версия PHP → 8.1 или 8.2

# Проверяем Composer
composer --version
```

### Установка Laravel:

```bash
# Создаем новый Laravel проект
composer create-project laravel/laravel .

# Это может занять 3-5 минут
```

---

## 🔄 Шаг 4: Клонирование репозитория

```bash
# Клонируем в временную папку
cd /home/c/co53144/
git clone https://github.com/vitrich/laravel-stat.git laravel-stat-temp

# Копируем файлы в Laravel проект
cp -r laravel-stat-temp/app/* laravel-stat/app/
cp -r laravel-stat-temp/database/* laravel-stat/database/
cp -r laravel-stat-temp/routes/* laravel-stat/routes/
cp -r laravel-stat-temp/resources/* laravel-stat/resources/
cp laravel-stat-temp/.env.example laravel-stat/.env.example
cp laravel-stat-temp/package.json laravel-stat/
cp laravel-stat-temp/vite.config.js laravel-stat/

# Удаляем временную папку
rm -rf laravel-stat-temp

cd laravel-stat
```

---

## 🗄️ Шаг 5: Настройка базы данных

### Создание БД через панель Timeweb:

1. Войдите в панель управления Timeweb
2. Перейдите в раздел **Базы данных MySQL**
3. Нажмите **Создать базу данных**
4. Введите имя: `co53144_laravel_stat`
5. Создайте пользователя:
   - Имя пользователя: `co53144_laravel`
   - Пароль: сгенерируйте надежный пароль
6. Предоставьте все права пользователю на созданную БД
7. Сохраните данные:
   - **Хост:** обычно `localhost` или `127.0.0.1`
   - **База:** `co53144_laravel_stat`
   - **Пользователь:** `co53144_laravel`
   - **Пароль:** ваш пароль

---

## ⚙️ Шаг 6: Настройка .env файла

```bash
cd /home/c/co53144/laravel-stat

# Копируем пример
cp .env.example .env

# Редактируем файл
nano .env
```

### Измените следующие параметры:

```env
APP_NAME="Laravel Stat"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://ваш-домен.ru

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=co53144_laravel_stat
DB_USERNAME=co53144_laravel
DB_PASSWORD=ваш_пароль_от_БД

SESSION_DRIVER=database
QUEUE_CONNECTION=database
CACHE_STORE=database
```

**Сохраните файл:** `Ctrl + O`, затем `Enter`, затем `Ctrl + X`

---

## 🔑 Шаг 7: Генерация ключа и миграции

```bash
# Генерируем ключ приложения
php artisan key:generate

# Устанавливаем зависимости
composer install --optimize-autoloader --no-dev

# Запускаем миграции
php artisan migrate --force

# Заполняем тестовыми данными
php artisan db:seed --force

# Кэшируем конфигурацию для производительности
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 🌐 Шаг 8: Настройка публичной директории

### Вариант 1: Символическая ссылка (рекомендуется)

```bash
# Удаляем старую public_html (если есть)
cd /home/c/co53144/
mv domen.ru/public_html domen.ru/public_html_backup

# Создаем символическую ссылку
ln -s /home/c/co53144/laravel-stat/public /home/c/co53144/domen.ru/public_html
```

### Вариант 2: Копирование (если симлинк не работает)

```bash
cd /home/c/co53144/
rm -rf domen.ru/public_html/*
cp -r laravel-stat/public/* domen.ru/public_html/
```

---

## 📝 Шаг 9: Настройка .htaccess

### Создайте .htaccess в корне домена:

```bash
nano /home/c/co53144/domen.ru/.htaccess
```

### Добавьте следующий код:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public_html/$1 [L]
</IfModule>
```

### Проверьте .htaccess в public_html:

```bash
nano /home/c/co53144/domen.ru/public_html/.htaccess
```

### Должно быть:

```apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

---

## 🔒 Шаг 10: Настройка прав доступа

```bash
cd /home/c/co53144/laravel-stat

# Устанавливаем правильные права
chmod -R 755 storage
chmod -R 755 bootstrap/cache

# Для директории public (если не симлинк)
chmod -R 755 public
```

---

## 🎨 Шаг 11: Сборка frontend assets

### Установка Node.js (если не установлен):

```bash
# Проверяем Node.js
node -v

# Если не установлен, используйте панель Timeweb:
# SSH-доступ → Node.js → Установить
```

### Сборка assets:

```bash
cd /home/c/co53144/laravel-stat

# Устанавливаем зависимости
npm install

# Собираем для production
npm run build
```

**Примечание:** Если на хостинге нет Node.js, соберите локально:

```bash
# На локальной машине
git clone https://github.com/vitrich/laravel-stat.git
cd laravel-stat
npm install
npm run build

# Затем загрузите папку public/build на хостинг через FTP/SFTP
```

---

## 🔐 Шаг 12: Настройка SSL сертификата

1. Войдите в панель управления Timeweb
2. Перейдите в **Сайты** → Ваш домен
3. Раздел **SSL-сертификат**
4. Нажмите **Получить бесплатный Let's Encrypt**
5. Дождитесь установки (1-2 минуты)
6. Включите **Принудительное перенаправление на HTTPS**

---

## ✅ Шаг 13: Проверка работы

Откройте в браузере:

```
https://ваш-домен.ru
```

### Тестовые учетные записи:

- **Преподаватель:** `teacher@example.com` / `password`
- **Ученик:** `student@example.com` / `password`

---

## 🔄 Обновление приложения

### Создайте скрипт обновления:

```bash
nano /home/c/co53144/update-laravel-stat.sh
```

### Добавьте код:

```bash
#!/bin/bash

cd /home/c/co53144/laravel-stat

# Режим обслуживания
php artisan down

# Получаем последние изменения
git pull origin main

# Обновляем зависимости
composer install --optimize-autoloader --no-dev
npm install
npm run build

# Миграции
php artisan migrate --force

# Очищаем и кэшируем
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

php artisan config:cache
php artisan route:cache
php artisan view:cache

# Копируем в public (если не используется симлинк)
# cp -r public/* /home/c/co53144/domen.ru/public_html/

# Возвращаем в нормальный режим
php artisan up

echo "Обновление завершено!"
```

### Сделайте скрипт исполняемым:

```bash
chmod +x /home/c/co53144/update-laravel-stat.sh
```

### Запуск обновления:

```bash
/home/c/co53144/update-laravel-stat.sh
```

---

## 🛠️ Настройка Cron для резервного копирования

### Создайте скрипт бэкапа:

```bash
nano /home/c/co53144/backup-laravel-stat.sh
```

### Код скрипта:

```bash
#!/bin/bash

BACKUP_DIR="/home/c/co53144/backups"
DATE=$(date +%Y%m%d_%H%M%S)
DB_NAME="co53144_laravel_stat"
DB_USER="co53144_laravel"
DB_PASS="ваш_пароль_от_БД"

mkdir -p $BACKUP_DIR

# Резервное копирование БД
mysqldump -u$DB_USER -p$DB_PASS $DB_NAME | gzip > $BACKUP_DIR/db_$DATE.sql.gz

# Резервное копирование файлов
tar -czf $BACKUP_DIR/files_$DATE.tar.gz /home/c/co53144/laravel-stat/storage

# Удаление старых бэкапов (старше 30 дней)
find $BACKUP_DIR -type f -mtime +30 -delete

echo "Бэкап создан: $DATE"
```

### Сделайте исполняемым:

```bash
chmod +x /home/c/co53144/backup-laravel-stat.sh
```

### Добавьте в Cron через панель Timeweb:

1. Перейдите в **Планировщик задач (Cron)**
2. Добавьте новую задачу:
   - **Команда:** `/home/c/co53144/backup-laravel-stat.sh`
   - **Период:** Ежедневно в 03:00
   - **Минута:** 0
   - **Час:** 3
   - **День месяца:** *
   - **Месяц:** *
   - **День недели:** *

---

## 🐛 Решение проблем

### 1. Ошибка 500 - Internal Server Error

```bash
# Проверьте логи Laravel
tail -50 /home/c/co53144/laravel-stat/storage/logs/laravel.log

# Проверьте права доступа
chmod -R 755 /home/c/co53144/laravel-stat/storage
chmod -R 755 /home/c/co53144/laravel-stat/bootstrap/cache

# Очистите кэш
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

### 2. Ошибка подключения к БД

```bash
# Проверьте параметры в .env
cat /home/c/co53144/laravel-stat/.env | grep DB_

# Проверьте подключение к MySQL
mysql -u co53144_laravel -p co53144_laravel_stat
```

### 3. CSS/JS не загружаются

```bash
# Пересоберите assets
cd /home/c/co53144/laravel-stat
npm run build

# Если используете копирование (не симлинк)
cp -r public/build /home/c/co53144/domen.ru/public_html/
```

### 4. Символическая ссылка не работает

```bash
# Используйте метод копирования
cd /home/c/co53144/
rm -rf domen.ru/public_html/*
cp -r laravel-stat/public/* domen.ru/public_html/
cp laravel-stat/public/.htaccess domen.ru/public_html/

# После каждого обновления нужно будет копировать заново
```

### 5. Проблемы с правами доступа

```bash
# Сбросьте права
cd /home/c/co53144/laravel-stat
find . -type f -exec chmod 644 {} \;
find . -type d -exec chmod 755 {} \;
chmod -R 755 storage
chmod -R 755 bootstrap/cache
```

---

## 📞 Поддержка Timeweb

Если возникнут проблемы с хостингом:

- **Панель управления:** https://timeweb.com/ru/my/
- **База знаний:** https://timeweb.com/ru/help/
- **Техподдержка:** support@timeweb.ru
- **Телефон:** 8 (800) 700-06-08

---

## ✅ Чеклист после развертывания

- [ ] Приложение открывается по домену
- [ ] SSL сертификат установлен и работает
- [ ] Вход с тестовыми учетными записями работает
- [ ] Можно создать урок (для преподавателя)
- [ ] Можно выполнить задание (для ученика)
- [ ] Статистика отображается
- [ ] Настроено резервное копирование
- [ ] Создан скрипт обновления
- [ ] Проверены права доступа к файлам
- [ ] Логи Laravel пишутся корректно

---

## 🎓 Полезные команды для работы на Timeweb

```bash
# Просмотр логов
tail -f /home/c/co53144/laravel-stat/storage/logs/laravel.log

# Очистка кэша
php artisan cache:clear && php artisan config:clear && php artisan view:clear

# Просмотр маршрутов
php artisan route:list

# Проверка подключения к БД
php artisan tinker
>>> DB::connection()->getPdo();

# Просмотр размера директорий
du -sh /home/c/co53144/laravel-stat/*

# Просмотр свободного места
df -h
```

---

**Успешного развертывания! 🚀**
