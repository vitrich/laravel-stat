<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TeacherMiddleware
{
    /**
     * Проверка, является ли пользователь преподавателем
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user()) {
            return redirect()->route('login')
                ->with('error', 'Пожалуйста, войдите в систему.');
        }

        // Проверяем, есть ли у пользователя профиль преподавателя
        if (!$request->user()->teacher) {
            return redirect()->route('home')
                ->with('error', 'У вас нет доступа к этому разделу. Только для преподавателей.');
        }

        return $next($request);
    }
}
