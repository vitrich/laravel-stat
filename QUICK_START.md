# 🚀 Быстрый старт Laravel Stat

Скращенная инструкция для быстрого запуска на локальной машине.

## Предварительные требования

Убедитесь, что у вас установлено:

- PHP >= 8.1
- Composer >= 2.0
- MySQL >= 8.0
- Node.js >= 18.x
- NPM >= 9.x

## Шаг 1: Клонирование и установка Laravel

```bash
# Клонируем репозиторий
git clone https://github.com/vitrich/laravel-stat.git
cd laravel-stat

# Создаем базовый Laravel проект
cd ..
composer create-project laravel/laravel laravel-stat-full

# Копируем файлы из репозитория
cp -r laravel-stat/app/* laravel-stat-full/app/
cp -r laravel-stat/database/* laravel-stat-full/database/
cp -r laravel-stat/routes/* laravel-stat-full/routes/
cp -r laravel-stat/resources/* laravel-stat-full/resources/
cp laravel-stat/.env.example laravel-stat-full/.env.example
cp laravel-stat/package.json laravel-stat-full/
cp laravel-stat/vite.config.js laravel-stat-full/

# Удаляем старую папку и переименовываем
rm -rf laravel-stat
mv laravel-stat-full laravel-stat
cd laravel-stat
```

## Шаг 2: Установка зависимостей

```bash
# PHP зависимости
composer install

# NPM зависимости
npm install
```

## Шаг 3: Настройка окружения

```bash
# Копируем .env файл
cp .env.example .env

# Генерируем ключ приложения
php artisan key:generate
```

## Шаг 4: Настройка базы данных

### Создайте базу данных:

```bash
mysql -u root -p
```

В MySQL консоли:

```sql
CREATE DATABASE laravel_stat CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;
```

### Отредактируйте .env:

```bash
nano .env
```

Измените строки:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_stat
DB_USERNAME=root
DB_PASSWORD=ваш_пароль
```

## Шаг 5: Запуск миграций и сидеров

```bash
# Запускаем миграции
php artisan migrate

# Заполняем тестовыми данными
php artisan db:seed
```

🎉 **Тестовые учетные записи:**
- **Преподаватель**: `teacher@example.com` / `password`
- **Ученик**: `student@example.com` / `password`

## Шаг 6: Сборка frontend assets

```bash
# Для разработки
npm run dev

# ИЛИ для production
npm run build
```

## Шаг 7: Запуск сервера

В новом терминале:

```bash
php artisan serve
```

🎉 **Готово!** Откройте браузер по адресу: [http://localhost:8000](http://localhost:8000)

---

## 🐞 Решение проблем

### Ошибка: "Class 'App\Models\Teacher' not found"

Убедитесь, что все файлы из `app/Models/` скопированы:

```bash
composer dump-autoload
```

### Ошибка: "SQLSTATE[HY000] [1045] Access denied"

Проверьте настройки MySQL в `.env` файле.

### Ошибка: "Vite manifest not found"

Запустите сборку assets:

```bash
npm run build
```

### CSS не загружается

Убедитесь, что `npm run dev` или `npm run build` выполнено успешно.

---

## 📚 Дополнительные ресурсы

- [README.md](README.md) - Полное описание проекта
- [DEPLOYMENT.md](DEPLOYMENT.md) - Подробная инструкция по развертыванию на сервере
- [Laravel Документация](https://laravel.com/docs)
