# 🚀 Инструкция по развертыванию Laravel Stat

Подробное руководство по установке и настройке приложения на сервере.

---

## 📦 Системные требования

### Минимальные требования:

- **PHP**: >= 8.1
- **Composer**: >= 2.0
- **MySQL**: >= 8.0 (или MariaDB >= 10.5)
- **Node.js**: >= 18.x (для Bootstrap и frontend assets)
- **NPM**: >= 9.x
- **Apache** или **Nginx**

### Рекомендуемые PHP расширения:

```bash
sudo apt install php8.1-cli php8.1-fpm php8.1-mysql php8.1-mbstring \
    php8.1-xml php8.1-curl php8.1-zip php8.1-gd php8.1-bcmath
```

---

## 🛠️ Установка на Ubuntu/Debian

### Шаг 1: Обновление системы

```bash
sudo apt update && sudo apt upgrade -y
```

### Шаг 2: Установка PHP 8.1

```bash
# Добавляем репозиторий
 sudo add-apt-repository ppa:ondrej/php -y
sudo apt update

# Устанавливаем PHP и расширения
sudo apt install php8.1 php8.1-fpm php8.1-mysql php8.1-mbstring \
    php8.1-xml php8.1-curl php8.1-zip php8.1-gd php8.1-bcmath -y

# Проверяем версию
php -v
```

### Шаг 3: Установка Composer

```bash
cd ~
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
sudo chmod +x /usr/local/bin/composer

# Проверяем
composer --version
```

### Шаг 4: Установка MySQL

```bash
sudo apt install mysql-server -y
sudo mysql_secure_installation

# Создаем базу данных
sudo mysql
```

В MySQL консоли:

```sql
CREATE DATABASE laravel_stat CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'laravel_user'@'localhost' IDENTIFIED BY 'ваш_пароль';
GRANT ALL PRIVILEGES ON laravel_stat.* TO 'laravel_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### Шаг 5: Установка Node.js и NPM

```bash
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt install nodejs -y

# Проверяем
node -v
npm -v
```

### Шаг 6: Установка Nginx (или Apache)

#### Для Nginx:

```bash
sudo apt install nginx -y
sudo systemctl start nginx
sudo systemctl enable nginx
```

#### Для Apache:

```bash
sudo apt install apache2 libapache2-mod-php8.1 -y
sudo a2enmod rewrite
sudo systemctl start apache2
sudo systemctl enable apache2
```

---

## 💻 Клонирование и настройка проекта

### Шаг 1: Клонируем репозиторий

```bash
cd /var/www
sudo git clone https://github.com/vitrich/laravel-stat.git
sudo chown -R $USER:$USER laravel-stat
cd laravel-stat
```

### Шаг 2: Установка Laravel

Так как в репозитории еще нет полной структуры Laravel, сначала создадим проект:

```bash
cd /var/www
composer create-project laravel/laravel laravel-stat-temp

# Копируем файлы из репозитория
cp -r laravel-stat/app/* laravel-stat-temp/app/
cp -r laravel-stat/database/* laravel-stat-temp/database/

# Переименовываем
rm -rf laravel-stat
mv laravel-stat-temp laravel-stat
cd laravel-stat
```

### Шаг 3: Настройка .env файла

```bash
cp .env.example .env
nano .env
```

Измените следующие параметры:

```env
APP_NAME="Laravel Stat"
APP_ENV=production
APP_DEBUG=false
APP_URL=http://your-domain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_stat
DB_USERNAME=laravel_user
DB_PASSWORD=ваш_пароль

SESSION_DRIVER=database
QUEUE_CONNECTION=database
```

### Шаг 4: Установка зависимостей

```bash
# PHP зависимости
composer install --optimize-autoloader --no-dev

# Frontend зависимости (добавьте Bootstrap)
npm install
npm install bootstrap @popperjs/core
npm run build
```

### Шаг 5: Генерация ключа приложения

```bash
php artisan key:generate
```

### Шаг 6: Запуск миграций

```bash
php artisan migrate
```

### Шаг 7: Настройка прав доступа

```bash
sudo chown -R www-data:www-data /var/www/laravel-stat
sudo chmod -R 775 /var/www/laravel-stat/storage
sudo chmod -R 775 /var/www/laravel-stat/bootstrap/cache
```

---

## 🌐 Настройка Nginx

### Создайте конфигурационный файл:

```bash
sudo nano /etc/nginx/sites-available/laravel-stat
```

Добавьте следующую конфигурацию:

```nginx
server {
    listen 80;
    server_name your-domain.com www.your-domain.com;
    root /var/www/laravel-stat/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

### Активируйте конфигурацию:

```bash
sudo ln -s /etc/nginx/sites-available/laravel-stat /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl restart nginx
```

---

## 🌐 Настройка Apache

### Создайте .htaccess в public/:

```bash
nano /var/www/laravel-stat/public/.htaccess
```

Добавьте:

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

### Создайте VirtualHost:

```bash
sudo nano /etc/apache2/sites-available/laravel-stat.conf
```

```apache
<VirtualHost *:80>
    ServerName your-domain.com
    ServerAlias www.your-domain.com
    DocumentRoot /var/www/laravel-stat/public

    <Directory /var/www/laravel-stat/public>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/laravel-stat-error.log
    CustomLog ${APACHE_LOG_DIR}/laravel-stat-access.log combined
</VirtualHost>
```

### Активируйте:

```bash
sudo a2ensite laravel-stat.conf
sudo systemctl reload apache2
```

---

## 🔒 Настройка SSL (опционально, но рекомендуется)

```bash
sudo apt install certbot python3-certbot-nginx -y
sudo certbot --nginx -d your-domain.com -d www.your-domain.com
```

Для Apache:

```bash
sudo apt install certbot python3-certbot-apache -y
sudo certbot --apache -d your-domain.com -d www.your-domain.com
```

---

## 👥 Создание первого пользователя-преподавателя

Создайте seeder или используйте tinker:

```bash
php artisan tinker
```

В tinker:

```php
$user = new App\Models\User();
$user->name = 'Имя Преподавателя';
$user->email = 'teacher@example.com';
$user->password = Hash::make('password');
$user->save();

$teacher = new App\Models\Teacher();
$teacher->user_id = $user->id;
$teacher->full_name = 'Иванов Иван Иванович';
$teacher->email = 'teacher@example.com';
$teacher->save();
```

---

## 🔄 Обновление приложения

```bash
cd /var/www/laravel-stat

# Переводим в maintenance режим
php artisan down

# Получаем последние изменения
git pull origin main

# Обновляем зависимости
composer install --optimize-autoloader --no-dev
npm install
npm run build

# Запускаем миграции
php artisan migrate --force

# Очищаем кэш
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Кэшируем для производительности
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Возвращаемся в обычный режим
php artisan up
```

---

## 🛡️ Настройка резервного копирования

Создайте скрипт резервного копирования:

```bash
sudo nano /usr/local/bin/backup-laravel-stat.sh
```

```bash
#!/bin/bash

BACKUP_DIR="/var/backups/laravel-stat"
DATE=$(date +%Y%m%d_%H%M%S)
DB_NAME="laravel_stat"
DB_USER="laravel_user"
DB_PASS="ваш_пароль"

mkdir -p $BACKUP_DIR

# Резервное копирование БД
mysqldump -u$DB_USER -p$DB_PASS $DB_NAME | gzip > $BACKUP_DIR/db_$DATE.sql.gz

# Резервное копирование файлов
tar -czf $BACKUP_DIR/files_$DATE.tar.gz /var/www/laravel-stat/storage

# Удаление старых резервных копий (старше 30 дней)
find $BACKUP_DIR -type f -mtime +30 -delete
```

Сделайте скрипт исполняемым:

```bash
sudo chmod +x /usr/local/bin/backup-laravel-stat.sh
```

Добавьте в cron (ежедневно в 3:00):

```bash
sudo crontab -e
```

Добавьте строку:

```
0 3 * * * /usr/local/bin/backup-laravel-stat.sh
```

---

## ⚙️ Полезные команды

```bash
# Просмотр логов
tail -f storage/logs/laravel.log

# Очистка всех кэшей
php artisan optimize:clear

# Перезапуск очередей
php artisan queue:restart

# Просмотр маршрутов
php artisan route:list

# Проверка конфигурации
php artisan config:show
```

---

## 🐞 Решение проблем

### Ошибка 500: Internal Server Error

```bash
# Проверьте логи
tail -50 storage/logs/laravel.log

# Проверьте права доступа
sudo chown -R www-data:www-data /var/www/laravel-stat
sudo chmod -R 775 /var/www/laravel-stat/storage
```

### Ошибки БД

```bash
# Проверьте соединение с MySQL
php artisan tinker
>>> DB::connection()->getPdo();

# Перезапуск MySQL
sudo systemctl restart mysql
```

### CSS/JS не загружаются

```bash
# Пересоберите assets
npm run build

# Проверьте права на public/
sudo chmod -R 755 /var/www/laravel-stat/public
```

---

## 📚 Дополнительные ресурсы

- [Laravel Документация](https://laravel.com/docs)
- [Bootstrap Документация](https://getbootstrap.com/docs)
- [MySQL Документация](https://dev.mysql.com/doc/)

---

## 👨‍💻 Поддержка

При вопросах и проблемах:

- Создайте [Issue](https://github.com/vitrich/laravel-stat/issues) на GitHub
- Проверьте логи: `storage/logs/laravel.log`
- Просмотрите логи веб-сервера
