@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">📚 Уроки по математике</h1>
    
    @if($lessons->count() > 0)
        <div class="row">
            @foreach($lessons as $lesson)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card lesson-card h-100">
                        <div class="card-body">
                            <h5 class="card-title">{{ $lesson->title }}</h5>
                            <p class="card-text">{!! Str::limit(strip_tags($lesson->theory_content), 100) !!}</p>
                            <p class="text-muted small">
                                📅 {{ \Carbon\Carbon::parse($lesson->date)->format('d.m.Y') }}
                            </p>
                            <p class="small">
                                ⏱️ {{ $lesson->duration_minutes }} мин
                                @if($lesson->test_duration_minutes > 0)
                                    | 📝 {{ $lesson->test_duration_minutes }} мин тест
                                @endif
                            </p>
                            <a href="#" class="btn btn-primary">
                                Открыть урок
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="alert alert-info">
            <h4>📭 Уроков пока нет</h4>
            <p>Уроки будут добавлены преподавателем.</p>
            <hr>
            <p class="mb-0"><strong>Для преподавателей:</strong> Создайте первый урок через tinker или админ-панель.</p>
        </div>
    @endif
</div>
@endsection
