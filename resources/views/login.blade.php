@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Вход в систему</div>
                <div class="card-body">
                    <p class="alert alert-info">
                        Для полноценной аутентификации установите Laravel Breeze:<br>
                        <code>composer require laravel/breeze --dev</code><br>
                        <code>php artisan breeze:install</code>
                    </p>
                    <h5>Тестовые аккаунты:</h5>
                    <ul>
                        <li>Преподаватель: teacher@example.com / password</li>
                        <li>Ученик: student@example.com / password</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
