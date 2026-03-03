<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Управление уроками</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">Математика</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="{{ route('home') }}">Главная</a>
                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-link nav-link">Выход</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>📚 Управление уроками</h1>
            <a href="{{ route('teacher.lessons.create') }}" class="btn btn-success btn-lg">
                ➕ Создать контрольную работу
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>✅</strong> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card shadow">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-primary">
                            <tr>
                                <th class="py-3 px-4">Название</th>
                                <th class="py-3">Дата</th>
                                <th class="py-3">Номер урока</th>
                                <th class="py-3">Статус</th>
                                <th class="py-3 px-4">Действия</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($lessons as $lesson)
                                <tr>
                                    <td class="px-4 fw-bold">{{ $lesson->title }}</td>
                                    <td>{{ \Carbon\Carbon::parse($lesson->date)->format('d.m.Y') }}</td>
                                    <td>
                                        @if($lesson->lesson_number)
                                            <span class="badge bg-info">№ {{ $lesson->lesson_number }}</span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($lesson->is_active)
                                            <span class="badge bg-success">✔️ Активен</span>
                                        @else
                                            <span class="badge bg-secondary">❌ Неактивен</span>
                                        @endif
                                    </td>
                                    <td class="px-4">
                                        <a href="{{ route('teacher.lessons.edit', $lesson->id) }}" class="btn btn-sm btn-warning">
                                            ✏️ Редактировать
                                        </a>
                                        <form action="{{ route('teacher.lessons.destroy', $lesson->id) }}" method="POST" class="d-inline" 
                                              onsubmit="return confirm('Вы уверены, что хотите удалить этот урок?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                🗑️ Удалить
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted fs-5">
                                        📄 Уроков пока нет. Создайте первую контрольную работу!
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="mt-4">
            {{ $lessons->links('pagination::bootstrap-5') }}
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
