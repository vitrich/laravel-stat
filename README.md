<<<<<<< HEAD
<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
=======
# Laravel Stat 📊

[![Laravel](https://img.shields.io/badge/Laravel-11.x-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.1+-blue.svg)](https://php.net)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-purple.svg)](https://getbootstrap.com)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

Платформа для обучения математике на Laravel с автоматической генерацией заданий и отслеживанием прогресса учеников.

## ✨ Особенности

- ✅ **Индивидуальные задания** - каждый ученик получает уникальный набор задач
- 🎯 **Автоматическая проверка** - мгновенное оценивание по 7-бальной шкале
- 📊 **Статистика и аналитика** - полное отслеживание прогресса учеников
- 👥 **Управление группами** - история перемещений между группами
- 📖 **Теоретический материал** - HTML-уроки с мультимедиа
- 🎨 **Bootstrap UI** - современный адаптивный интерфейс

## 💻 Технологии

- **Backend**: Laravel 11.x, PHP 8.1+
- **Frontend**: Bootstrap 5.3, Vanilla JavaScript
- **Database**: MySQL 8.0+
- **Authentication**: Laravel Breeze

## 🛠️ Типы заданий

### Урок 1: Смешанные и неправильные дроби

- Классификация дробей (правильные/неправильные)
- Преобразование смешанных дробей в неправильные
- Преобразование неправильных дробей в смешанные

### Урок 2: Сравнение и сокращение дробей

- Сокращение дробей
- Сравнение дробей с одинаковыми знаменателями
- Сравнение дробей с разными знаменателями
- Задачи повышенной сложности (2 балла)

## 🚀 Быстрый старт

### Требования

- PHP >= 8.1
- Composer >= 2.0
- MySQL >= 8.0
- Node.js >= 18.x
- NPM >= 9.x

### Установка

```bash
# Клонирование репозитория
git clone https://github.com/vitrich/laravel-stat.git
cd laravel-stat

# Установка Laravel (если еще не установлен)
cd ..
composer create-project laravel/laravel laravel-stat-temp
cp -r laravel-stat/app/* laravel-stat-temp/app/
cp -r laravel-stat/database/* laravel-stat-temp/database/
rm -rf laravel-stat
mv laravel-stat-temp laravel-stat
cd laravel-stat

# Установка зависимостей
composer install
npm install
npm install bootstrap @popperjs/core

# Копирование .env файла
cp .env.example .env

# Генерация ключа приложения
php artisan key:generate

# Настройка базы данных в .env
# Затем запускаем миграции
php artisan migrate

# Сборка frontend assets
npm run build

# Запуск сервера разработки
php artisan serve
```

## 📚 Подробная инструкция

Для полной инструкции по развертыванию на сервере смотрите [DEPLOYMENT.md](DEPLOYMENT.md).

## 📊 Архитектура

### Модели

- **Teacher** - преподаватель
- **Group** - группа (1, 2, 2.1, 2.2, 3)
- **Student** - ученик
- **GroupHistory** - история переходов между группами
- **Lesson** - урок с теорией
- **LessonTask** - индивидуальное задание ученика
- **Assignment** - задание (устаревшая модель)
- **Submission** - сданная работа (устаревшая модель)

### Контроллеры

- **HomeController** - главная страница
- **LessonController** - управление уроками и заданиями
- **StudentController** - управление учениками (только для преподавателей)
- **StatisticsController** - статистика и аналитика

## 🧑‍🏫 Роли пользователей

### Ученик (Student)

- Просмотр доступных уроков
- Выполнение индивидуальных заданий
- Просмотр своих результатов
- Просмотр теоретического материала

### Преподаватель (Teacher)

- Создание и редактирование уроков
- Просмотр таблицы результатов всех учеников
- Статистика и аналитика
- Управление группами

## 🎯 Оценивание

Система использует 7-бальную шкалу:

| Процент выполнения | Оценка |
| ---------------- | ------ |
| ≥ 95%           | 7      |
| 85% - 94%        | 6      |
| 75% - 84%        | 5      |
| 65% - 74%        | 4      |
| 50% - 64%        | 3      |
| 35% - 49%        | 2      |
| < 35%            | 1      |

## 🔗 Связанные проекты

- [Django Stat](https://github.com/vitrich/stat) - оригинальная версия на Django

## 📝 Лицензия

MIT License

## 👨‍💻 Автор

Создано [vitrich](https://github.com/vitrich)

## 🚀 Дорожная карта

- [ ] Добавить Laravel Breeze для аутентификации
- [ ] Создать Bootstrap шаблоны (views)
- [ ] Добавить middleware для проверки ролей
- [ ] Реализовать регистрацию учеников
- [ ] Добавить seeders для тестовых данных
- [ ] Интерактивный график статистики (Chart.js)
- [ ] API для мобильного приложения
- [ ] Импорт учеников из CSV
- [ ] Экспорт результатов в Excel/PDF
- [ ] Уведомления по email
>>>>>>> a351b8ff5bfaa518e9cb441ecdbb953d0f9e538b
