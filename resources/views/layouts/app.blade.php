<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Clínica Vet') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <style>
        .sidebar {
            width: 260px;
            min-height: 100vh;
        }
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.75);
            border-radius: 0.375rem;
            margin-bottom: 0.2rem;
        }
        .sidebar .nav-link:hover {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.1);
        }
        .sidebar .nav-link.active {
            color: #fff;
            background-color: #0d6efd;
        }
        .main-wrapper {
            flex: 1;
            min-width: 0;
        }
    </style>
</head>
<body class="bg-light">

    <div class="d-flex">
        @if(auth()->check())
        <!-- Sidebar Esquerda -->
        <aside class="sidebar bg-dark text-white p-3 d-flex flex-column sticky-top" style="height: 100vh;">
            <a href="{{ route('dashboard') }}" class="d-flex align-items-center mb-3 text-white text-decoration-none px-2">
                <i class="bi bi-hospital fs-4 me-2 text-primary"></i>
                <span class="fs-5 fw-bold">Clínica Vet</span>
            </a>
            
            <hr class="border-secondary my-2">

            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2 me-2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('consultations.index') }}" class="nav-link {{ request()->routeIs('consultations.*') ? 'active' : '' }}">
                        <i class="bi bi-calendar-event me-2"></i> Consultas
                    </a>
                </li>
                
                @if(in_array(auth()->user()->role, ['admin', 'veterinario']))
                    <li class="nav-item">
                        <a href="{{ route('vaccinations.index') }}" class="nav-link {{ request()->routeIs('vaccinations.*') ? 'active' : '' }}">
                            <i class="bi bi-shield-plus me-2"></i> Vacinações
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('prescriptions.index') }}" class="nav-link {{ request()->routeIs('prescriptions.*') ? 'active' : '' }}">
                            <i class="bi bi-journal-medical me-2"></i> Receitas Médicas
                        </a>
                    </li>
                @endif

                <li class="nav-item">
                    <a href="{{ route('exams.index') }}" class="nav-link {{ request()->routeIs('exams.*') ? 'active' : '' }}">
                        <i class="bi bi-file-earmark-medical me-2"></i> Exames
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('tutors.index') }}" class="nav-link {{ request()->routeIs('tutors.*') ? 'active' : '' }}">
                        <i class="bi bi-people me-2"></i> Tutores
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('animals.index') }}" class="nav-link {{ request()->routeIs('animals.*') ? 'active' : '' }}">
                        <i class="bi bi-heart-pulse me-2"></i> Animais
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('species.index') }}" class="nav-link {{ request()->routeIs('species.*') ? 'active' : '' }}">
                        <i class="bi bi-tags me-2"></i> Espécies
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('races.index') }}" class="nav-link {{ request()->routeIs('races.*') ? 'active' : '' }}">
                        <i class="bi bi-bookmark-star me-2"></i> Raças
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('reports.index') }}" class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                        <i class="bi bi-file-earmark-pdf"></i> Relatórios
                    </a>
                </li>

                @if(auth()->user()->role === 'admin')
                <li class="nav-item mt-2">
                    <span class="text-uppercase fs-7 text-muted px-2 fw-semibold" style="font-size: 0.75rem;">Administração</span>
                </li>
                <li class="nav-item">
                    <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                        <i class="bi bi-person-gear me-2"></i> Usuários
                    </a>
                </li>
                @endif
            </ul>
        </aside>
        @endif

        <!-- Conteúdo Principal + Topbar -->
        <div class="main-wrapper d-flex flex-column min-vh-100">
            
            <!-- Header / Topbar Superior (Exibido apenas se autenticado) -->
            @auth
            <header class="navbar navbar-expand bg-white border-bottom px-4 py-2 shadow-sm sticky-top">
                <div class="container-fluid p-0 justify-content-end">
                    <div class="dropdown">
                        <button class="btn btn-light dropdown-toggle d-flex align-items-center gap-2 border-0" 
                                type="button" 
                                id="userDropdown" 
                                data-bs-toggle="dropdown" 
                                data-bs-display="static"
                                aria-expanded="false">
                            <i class="bi bi-person-circle fs-5 text-secondary"></i>
                            <span>{{ auth()->user()->name }}</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0" aria-labelledby="userDropdown">
                            <li>
                                <span class="dropdown-item-text text-muted">
                                    Perfil: <strong class="text-dark">{{ ucfirst(auth()->user()->role) }}</strong>
                                </span>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger d-flex align-items-center gap-2">
                                        <i class="bi bi-box-arrow-right"></i> Sair
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </header>
            @endauth

            <!-- Área de Exibição das Views (Padding apenas quando logado) -->
            <main class="flex-grow-1 {{ auth()->check() ? 'p-4' : 'p-0' }}">
                <div class="{{ auth()->check() ? 'container-fluid' : '' }}">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const deleteForms = document.querySelectorAll('.form-delete');

            deleteForms.forEach(form => {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();

                    const resource = form.dataset.resource || 'este registro';
                    Swal.fire({
                        title: 'Tem certeza?',
                        text: `Deseja excluir ${resource} permanentemente?`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#dc3545',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Sim, excluir!',
                        cancelButtonText: 'Cancelar',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });

            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Sucesso!',
                    text: '{{ session('success') }}',
                    timer: 3000,
                    showConfirmButton: false
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Ops...',
                    text: '{{ session('error') }}',
                });
            @endif
        });
    </script>
</body>
</html>