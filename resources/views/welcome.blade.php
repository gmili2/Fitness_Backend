<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Poppins', sans-serif;
            background: #f8f9fa;
            color: #333;
        }
        .hero {
            min-height: 100vh;
            background: linear-gradient(135deg, rgba(220, 53, 69, 0.9) 0%, rgba(200, 35, 51, 0.9) 100%), url('https://images.unsplash.com/photo-1534438327276-14e5300c3a48?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1950&q=80') center/cover;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
            padding: 2rem;
        }
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            padding: 1rem 2rem;
            background: transparent;
            transition: 0.3s;
            z-index: 1000;
        }
        .navbar.scrolled {
            background: #dc3545;
            box-shadow: 0 2px 4px rgba(0,0,0,.1);
        }
        .nav-links {
            list-style: none;
            display: flex;
            gap: 2rem;
            justify-content: flex-end;
        }
        .nav-link {
            color: white;
            text-decoration: none;
            font-weight: 500;
            transition: 0.3s;
            padding: 0.5rem 1rem;
            border-radius: 5px;
        }
        .nav-link:hover {
            background: rgba(255,255,255,0.1);
        }
        .hero-content {
            max-width: 800px;
        }
        .hero-title {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }
        .hero-subtitle {
            font-size: 1.2rem;
            margin-bottom: 2rem;
            opacity: 0.9;
        }
        .cta-button {
            display: inline-block;
            padding: 1rem 2rem;
            background: white;
            color: #dc3545;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 600;
            transition: 0.3s;
        }
        .cta-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 8px rgba(0,0,0,.2);
        }
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }
            .nav-links {
                display: none;
            }
        }
    </style>
</head>
<body class="antialiased">
    <nav class="navbar">
        <div class="nav-links">
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" class="nav-link">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="nav-link">Se connecter</a>
                    <a href="{{ route('admin.login') }}" class="nav-link">Admin</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="nav-link">S'inscrire</a>
                    @endif
                @endauth
            @endif
        </div>
    </nav>

    <section class="hero">
        <div class="hero-content">
            <h1 class="hero-title">Bienvenue sur votre Application Fitness</h1>
            <p class="hero-subtitle">Rejoignez-nous pour transformer votre vie et atteindre vos objectifs de remise en forme</p>
            @if (Route::has('register'))
                <a href="{{ route('register') }}" class="cta-button">Commencer maintenant</a>
            @endif
        </div>
    </section>

    <script>
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });
    </script>
</body>
</html>