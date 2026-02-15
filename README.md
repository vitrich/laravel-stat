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
