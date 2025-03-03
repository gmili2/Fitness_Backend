<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - Admin</title>
    
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f6f9;
        }
        #sidebar {
            background: #343a40;
            color: white;
            min-height: 100vh;
            transition: all 0.3s;
            box-shadow: 0 0 15px rgba(0,0,0,.1);
        }
        #sidebar.closed {
            margin-left: -250px;
        }
        #sidebar .nav-link {
            color: rgba(255,255,255,.8);
            padding: 15px 25px;
            font-size: 1rem;
            border-left: 3px solid transparent;
        }
        #sidebar .nav-link:hover {
            color: white;
            background: rgba(255,255,255,.1);
            border-left-color: #dc3545;
        }
        #main-content {
            transition: all 0.3s;
            padding: 20px;
        }
        #main-content.full {
            margin-left: 0;
        }
        .navbar {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            box-shadow: 0 2px 4px rgba(0,0,0,.1);
        }
        .navbar-brand, .nav-link {
            color: white !important;
        }
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,.1);
            margin-bottom: 20px;
        }
        .btn-dark {
            background: #343a40;
            border: none;
            padding: 8px 16px;
            border-radius: 5px;
        }
        .btn-dark:hover {
            background: #23272b;
        }
        .text-danger {
            color: #dc3545 !important;
        }
    </style>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/admin') }}">
                    {{ config('app.name', 'Laravel') }} - Administration
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto">
                        @guest('user_admin')
                            @if (Route::has('admin.login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('admin.login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif
                            @if (Route::has('admin.register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('admin.register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                    <i class='bx bxs-user-circle fs-5 me-1'></i>{{ Auth::guard('user_admin')->user()->name }}
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a class="dropdown-item" href="{{ route('admin.logout') }}" 
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class='bx bx-log-out me-2'></i>{{ __('Logout') }}
                                    </a>
                                    <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        @auth('user_admin')
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-3 col-lg-2 px-0" id="sidebar">
                    <div class="p-3">
                        <h4 class="text-center text-white mb-4">Menu Administrateur</h4>
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <a href="{{ route('admin.users') }}" class="nav-link">
                                    <i class='bx bx-user me-2'></i>Liste des utilisateurs
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.users.create') }}" class="nav-link">
                                    <i class='bx bx-user-plus me-2'></i>Ajouter un utilisateur
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="col-md-9 col-lg-10" id="main-content">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h1 class="text-danger h3 mb-0">Tableau de Bord Administrateur</h1>
                        <button class="btn btn-dark d-md-none" id="toggle-sidebar">
                            <i class='bx bx-menu'></i>
                        </button>
                    </div>
                    @yield('content')
                </div>
            </div>
        </div>
        @else
            <main class="py-4">
                @yield('content')
            </main>
        @endauth
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content');
            const toggleButton = document.getElementById('toggle-sidebar');

            if (toggleButton) {
                toggleButton.addEventListener('click', function() {
                    sidebar.classList.toggle('closed');
                    mainContent.classList.toggle('full');
                });
            }
        });
    </script>
</body>
</html>