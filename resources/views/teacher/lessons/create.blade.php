<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Создать контрольную работу</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">Математика</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="{{ route('teacher.lessons.index') }}">Уроки</a>
                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-link nav-link">Выход</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow">
                    <div class="card-header bg-success text-white">
                        <h4 class="mb-0">➕ Создать новую контрольную работу</h4>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('teacher.lessons.store') }}" method="POST">
                            @csrf

                            {{-- Название --}}
                            <div class="mb-3">
                                <label for="title" class="form-label fw-bold">Название <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('title') is-invalid @enderror" 
                                       id="title" 
                                       name="title" 
                                       value="{{ old('title') }}" 
                                       placeholder="Например: Контрольная работа № 1"
                                       required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Дата --}}
                            <div class="mb-3">
                                <label for="date" class="form-label fw-bold">Дата <span class="text-danger">*</span></label>
                                <input type="date" 
                                       class="form-control @error('date') is-invalid @enderror" 
                                       id="date" 
                                       name="date" 
                                       value="{{ old('date', date('Y-m-d')) }}"
                                       required>
                                @error('date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Номер урока --}}
                            <div class="mb-3">
                                <label for="lesson_number" class="form-label fw-bold">
                                    Номер урока (для генератора заданий)
                                </label>
                                <input type="number" 
                                       class="form-control @error('lesson_number') is-invalid @enderror" 
                                       id="lesson_number" 
                                       name="lesson_number" 
                                       value="{{ old('lesson_number') }}"
                                       min="1"
                                       placeholder="Например: 5">
                                <small class="form-text text-muted">
                                    Укажите номер, если существует генератор заданий (Lesson{N}Generator)
                                </small>
                                @error('lesson_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Описание --}}
                            <div class="mb-3">
                                <label for="description" class="form-label fw-bold">Описание</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" 
                                          name="description" 
                                          rows="3"
                                          placeholder="Краткое описание урока...">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Теория --}}
                            <div class="mb-3">
                                <label for="theory" class="form-label fw-bold">Теоретический материал</label>
                                <textarea class="form-control @error('theory') is-invalid @enderror" 
                                          id="theory" 
                                          name="theory" 
                                          rows="6"
                                          placeholder="Теоретический материал к уроку...">{{ old('theory') }}</textarea>
                                @error('theory')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Статус --}}
                            <div class="mb-4">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           id="is_active" 
                                           name="is_active" 
                                           {{ old('is_active', true) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold" for="is_active">
                                        ✅ Активная (ученики смогут видеть и решать)
                                    </label>
                                </div>
                            </div>

                            {{-- Кнопки --}}
                            <div class="d-flex gap-3">
                                <button type="submit" class="btn btn-success btn-lg px-5">
                                    ✅ Создать
                                </button>
                                <a href="{{ route('teacher.lessons.index') }}" class="btn btn-secondary btn-lg px-5">
                                    ❌ Отмена
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
