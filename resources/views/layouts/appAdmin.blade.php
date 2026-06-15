<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $pageTitle ?? 'Tableau de bord' }} — mygympro</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">

    <style>
        :root {
            --primary:       #e53935;
            --primary-dark:  #c62828;
            --sidebar-bg:    #12151e;
            --sidebar-item:  #1c2030;
            --sidebar-hover: #252b3b;
            --sidebar-w:     260px;
            --header-h:      64px;
            --bg:            #f0f2f7;
            --card-radius:   12px;
            --shadow:        0 2px 12px rgba(0,0,0,.07);
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            margin: 0;
            color: #1a1d2e;
        }

        /* ── Sidebar ── */
        #sidebar {
            position: fixed;
            top: 0; left: 0;
            width: var(--sidebar-w);
            height: 100vh;
            background: var(--sidebar-bg);
            display: flex;
            flex-direction: column;
            z-index: 1000;
            transition: transform .25s ease;
            overflow-y: auto;
        }

        #sidebar.hidden { transform: translateX(calc(-1 * var(--sidebar-w))); }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 22px 24px;
            border-bottom: 1px solid rgba(255,255,255,.06);
        }
        .sidebar-brand .brand-icon {
            width: 36px; height: 36px;
            background: var(--primary);
            border-radius: 9px;
            display: flex; align-items: center; justify-content: center;
            color: white; font-size: 1.1rem; font-weight: 700;
            flex-shrink: 0;
        }
        .sidebar-brand span {
            color: white;
            font-weight: 700;
            font-size: 1.05rem;
            letter-spacing: -.2px;
        }
        .sidebar-brand small {
            display: block;
            color: rgba(255,255,255,.4);
            font-size: .7rem;
            font-weight: 400;
        }

        .sidebar-section {
            padding: 20px 16px 6px;
            font-size: .65rem;
            font-weight: 600;
            color: rgba(255,255,255,.25);
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .sidebar-nav { padding: 0 12px; }

        .sidebar-nav .nav-link {
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 11px 14px;
            color: rgba(255,255,255,.55);
            border-radius: 8px;
            font-size: .875rem;
            font-weight: 500;
            transition: all .15s ease;
            margin-bottom: 2px;
            text-decoration: none;
        }
        .sidebar-nav .nav-link i {
            font-size: 1.15rem;
            flex-shrink: 0;
        }
        .sidebar-nav .nav-link:hover {
            color: white;
            background: var(--sidebar-hover);
        }
        .sidebar-nav .nav-link.active {
            color: white;
            background: rgba(229,57,53,.15);
            border-left: 3px solid var(--primary);
            padding-left: 11px;
        }
        .sidebar-nav .nav-link.active i { color: var(--primary); }

        .sidebar-footer {
            margin-top: auto;
            padding: 16px 12px;
            border-top: 1px solid rgba(255,255,255,.06);
        }
        .sidebar-footer .btn-logout {
            display: flex;
            align-items: center;
            gap: 10px;
            width: 100%;
            padding: 10px 14px;
            background: rgba(229,57,53,.12);
            border: none;
            border-radius: 8px;
            color: #e57373;
            font-size: .875rem;
            font-weight: 500;
            cursor: pointer;
            transition: background .15s;
            text-decoration: none;
        }
        .sidebar-footer .btn-logout:hover {
            background: rgba(229,57,53,.22);
            color: #ef5350;
        }

        /* ── Top header ── */
        #header {
            position: fixed;
            top: 0;
            left: var(--sidebar-w);
            right: 0;
            height: var(--header-h);
            background: white;
            border-bottom: 1px solid #eaecf0;
            display: flex;
            align-items: center;
            padding: 0 24px;
            gap: 16px;
            z-index: 999;
            transition: left .25s ease;
        }
        #header.full { left: 0; }

        .header-toggle {
            background: none;
            border: none;
            width: 36px; height: 36px;
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer;
            color: #667085;
            font-size: 1.3rem;
            transition: background .15s;
        }
        .header-toggle:hover { background: var(--bg); }

        .header-title {
            font-size: 1rem;
            font-weight: 600;
            color: #1a1d2e;
            flex: 1;
        }

        .header-user {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .header-user .avatar {
            width: 36px; height: 36px;
            background: var(--primary);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            color: white;
            font-size: .85rem;
            font-weight: 600;
        }
        .header-user .user-name {
            font-size: .875rem;
            font-weight: 500;
            color: #1a1d2e;
        }
        .header-user .user-role {
            font-size: .72rem;
            color: #667085;
        }

        /* ── Main content ── */
        #main-content {
            margin-left: var(--sidebar-w);
            margin-top: var(--header-h);
            padding: 28px;
            min-height: calc(100vh - var(--header-h));
            transition: margin-left .25s ease;
        }
        #main-content.full { margin-left: 0; }

        /* ── Cards ── */
        .card {
            border: none;
            border-radius: var(--card-radius);
            box-shadow: var(--shadow);
        }
        .card-header {
            background: white;
            border-bottom: 1px solid #f2f4f7;
            padding: 18px 24px;
            border-radius: var(--card-radius) var(--card-radius) 0 0 !important;
        }
        .card-body { padding: 24px; }

        /* ── Page header ── */
        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
        }
        .page-header h4 {
            font-size: 1.25rem;
            font-weight: 700;
            color: #1a1d2e;
            margin: 0;
        }
        .page-header .breadcrumb {
            margin: 0;
            font-size: .8rem;
        }
        .page-header .breadcrumb-item.active { color: #667085; }

        /* ── Buttons ── */
        .btn-primary-custom {
            background: var(--primary);
            border: none;
            color: white;
            border-radius: 8px;
            padding: 9px 18px;
            font-size: .875rem;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            transition: background .15s, transform .1s;
            text-decoration: none;
        }
        .btn-primary-custom:hover {
            background: var(--primary-dark);
            color: white;
            transform: translateY(-1px);
        }
        .btn-icon {
            width: 32px; height: 32px;
            border: none;
            border-radius: 7px;
            display: inline-flex;
            align-items: center; justify-content: center;
            font-size: .95rem;
            cursor: pointer;
            transition: all .15s;
            text-decoration: none;
        }
        .btn-icon-view  { background: #eff8ff; color: #2196f3; }
        .btn-icon-edit  { background: #fffbeb; color: #f59e0b; }
        .btn-icon-del   { background: #fff1f2; color: #e53935; }
        .btn-icon-view:hover  { background: #2196f3; color: white; }
        .btn-icon-edit:hover  { background: #f59e0b; color: white; }
        .btn-icon-del:hover   { background: #e53935; color: white; }

        /* ── Table ── */
        .table { font-size: .875rem; }
        .table th {
            font-size: .75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .5px;
            color: #667085;
            border-bottom: 1px solid #f2f4f7 !important;
            padding: 12px 16px;
            white-space: nowrap;
        }
        .table td {
            padding: 14px 16px;
            vertical-align: middle;
            border-bottom: 1px solid #f9fafb;
            color: #344054;
        }
        .table tbody tr:hover { background: #fafbfc; }
        .table tbody tr:last-child td { border-bottom: none; }

        /* ── Badges ── */
        .badge-active   { background: #ecfdf3; color: #027a48; padding: 4px 10px; border-radius: 20px; font-size: .75rem; font-weight: 500; }
        .badge-inactive { background: #fef3f2; color: #b42318; padding: 4px 10px; border-radius: 20px; font-size: .75rem; font-weight: 500; }

        /* ── Avatar ── */
        .user-avatar {
            width: 38px; height: 38px;
            border-radius: 50%;
            object-fit: cover;
        }
        .user-avatar-placeholder {
            width: 38px; height: 38px;
            border-radius: 50%;
            background: #fef3f2;
            display: flex; align-items: center; justify-content: center;
            color: var(--primary);
            font-size: 1.1rem;
        }

        /* ── Form inputs ── */
        .form-label {
            font-size: .8rem;
            font-weight: 600;
            color: #344054;
            margin-bottom: 6px;
        }
        .form-control, .form-select {
            border: 1.5px solid #d0d5dd;
            border-radius: 8px;
            font-size: .875rem;
            padding: 10px 14px;
            color: #101828;
            transition: border-color .15s, box-shadow .15s;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(229,57,53,.12);
            outline: none;
        }
        .form-control.is-invalid { border-color: #fda29b; }
        .input-group .input-icon {
            background: #f9fafb;
            border: 1.5px solid #d0d5dd;
            border-right: none;
            color: #667085;
            padding: 0 13px;
            display: flex; align-items: center;
            border-radius: 8px 0 0 8px;
        }
        .input-group .form-control { border-radius: 0 8px 8px 0; }

        /* ── Search bar ── */
        .search-bar {
            position: relative;
        }
        .search-bar i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #9aa3b3;
            font-size: 1.1rem;
            pointer-events: none;
        }
        .search-bar input {
            padding-left: 36px !important;
        }

        /* ── DataTables overrides ── */
        div.dataTables_wrapper div.dataTables_length label,
        div.dataTables_wrapper div.dataTables_filter label {
            font-size: .82rem;
            color: #667085;
        }
        div.dataTables_wrapper div.dataTables_filter input {
            border: 1.5px solid #d0d5dd;
            border-radius: 8px;
            padding: 7px 12px;
            font-size: .82rem;
        }
        div.dataTables_wrapper div.dataTables_length select {
            border: 1.5px solid #d0d5dd;
            border-radius: 8px;
            padding: 5px 8px;
            font-size: .82rem;
        }
        div.dataTables_wrapper div.dataTables_info { font-size: .8rem; color: #667085; }
        div.dataTables_wrapper div.dataTables_paginate .paginate_button {
            border-radius: 7px !important;
            font-size: .82rem;
        }
        div.dataTables_wrapper div.dataTables_paginate .paginate_button.current,
        div.dataTables_wrapper div.dataTables_paginate .paginate_button.current:hover {
            background: var(--primary) !important;
            border-color: var(--primary) !important;
            color: white !important;
        }
        div.dataTables_wrapper div.dataTables_paginate .paginate_button:hover {
            background: var(--bg) !important;
            border-color: #d0d5dd !important;
            color: #1a1d2e !important;
        }

        /* ── Laravel pagination ── */
        .pagination .page-link {
            border: 1.5px solid #d0d5dd;
            border-radius: 8px !important;
            margin: 0 2px;
            color: #344054;
            font-size: .82rem;
            padding: 6px 12px;
        }
        .pagination .page-item.active .page-link {
            background: var(--primary);
            border-color: var(--primary);
        }
        .pagination .page-link:hover { background: var(--bg); }

        /* ── Alert / Flash ── */
        .alert {
            border: none;
            border-radius: 10px;
            font-size: .875rem;
            padding: 14px 18px;
        }
        .alert-success { background: #ecfdf3; color: #027a48; }
        .alert-danger  { background: #fef3f2; color: #b42318; }

        /* ── Overlay (mobile) ── */
        #sidebar-overlay {
            display: none;
            position: fixed; inset: 0;
            background: rgba(0,0,0,.4);
            z-index: 999;
        }
        #sidebar-overlay.show { display: block; }

        /* ── Responsive ── */
        @media (max-width: 768px) {
            #sidebar { transform: translateX(calc(-1 * var(--sidebar-w))); }
            #sidebar.show { transform: translateX(0); }
            #header { left: 0; }
            #main-content { margin-left: 0; }
        }
    </style>
    @stack('styles')
</head>
<body>

@auth('user_admin')
<div id="sidebar-overlay"></div>

{{-- ── Sidebar ── --}}
<nav id="sidebar">
    <div class="sidebar-brand">
        <div class="brand-icon">G</div>
        <div>
            <span>mygympro</span>
            <small>Administration</small>
        </div>
    </div>

    <p class="sidebar-section">Navigation</p>
    <ul class="sidebar-nav nav flex-column">
        <li class="nav-item">
            <a href="{{ route('admin.dashboard') }}"
               class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class='bx bxs-dashboard'></i> Tableau de bord
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.users') }}"
               class="nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                <i class='bx bx-building-house'></i> Salles
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.users.create') }}"
               class="nav-link {{ request()->routeIs('admin.users.create') ? 'active' : '' }}">
                <i class='bx bx-plus-circle'></i> Ajouter une salle
            </a>
        </li>
    </ul>

    <div class="sidebar-footer">
        <form action="{{ route('admin.logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn-logout">
                <i class='bx bx-log-out'></i> Déconnexion
            </button>
        </form>
    </div>
</nav>

{{-- ── Header ── --}}
<header id="header">
    <button class="header-toggle" id="sidebar-toggle">
        <i class='bx bx-menu'></i>
    </button>
    <div class="header-title">@yield('page-title', 'Tableau de bord')</div>
    <div class="header-user">
        <div class="avatar">
            {{ strtoupper(substr(Auth::guard('user_admin')->user()->name, 0, 1)) }}
        </div>
        <div>
            <div class="user-name">{{ Auth::guard('user_admin')->user()->name }}</div>
            <div class="user-role">Administrateur</div>
        </div>
    </div>
</header>

{{-- ── Content ── --}}
<main id="main-content">
    @if(session('success'))
        <div class="alert alert-success d-flex align-items-center gap-2 mb-4" role="alert">
            <i class='bx bx-check-circle fs-5'></i>
            {{ session('success') }}
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger d-flex align-items-start gap-2 mb-4" role="alert">
            <i class='bx bx-error-circle fs-5 mt-1'></i>
            <ul class="mb-0 ps-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @yield('content')
</main>

@else
<main class="py-4">@yield('content')</main>
@endauth

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>

<script>
    const sidebar        = document.getElementById('sidebar');
    const header         = document.getElementById('header');
    const mainContent    = document.getElementById('main-content');
    const toggleBtn      = document.getElementById('sidebar-toggle');
    const overlay        = document.getElementById('sidebar-overlay');
    const isMobile       = () => window.innerWidth < 769;

    function openSidebar() {
        if (isMobile()) {
            sidebar?.classList.add('show');
            overlay?.classList.add('show');
        } else {
            sidebar?.classList.remove('hidden');
            header?.classList.remove('full');
            mainContent?.classList.remove('full');
        }
    }
    function closeSidebar() {
        if (isMobile()) {
            sidebar?.classList.remove('show');
            overlay?.classList.remove('show');
        } else {
            sidebar?.classList.add('hidden');
            header?.classList.add('full');
            mainContent?.classList.add('full');
        }
    }

    toggleBtn?.addEventListener('click', () => {
        const isOpen = isMobile() ? sidebar?.classList.contains('show') : !sidebar?.classList.contains('hidden');
        isOpen ? closeSidebar() : openSidebar();
    });
    overlay?.addEventListener('click', closeSidebar);
</script>
@stack('scripts')
</body>
</html>
