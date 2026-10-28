<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Inventario TI') · TechStock</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root {
            --ts-navy: #0f2a4a;
            --ts-navy-2: #16375f;
            --ts-primary: #0d6efd;
            --ts-success: #198754;
            --ts-danger: #dc3545;
            --ts-warning: #ffc107;
            --ts-bg: #f4f7fb;
            --ts-sidebar-w: 250px;
        }
        body { background: var(--ts-bg); color: #1f2d3d; font-family: "Segoe UI", system-ui, -apple-system, Arial, sans-serif; }

        /* Sidebar */
        .sidebar {
            position: fixed; inset: 0 auto 0 0; width: var(--ts-sidebar-w);
            background: linear-gradient(180deg, var(--ts-navy) 0%, var(--ts-navy-2) 100%);
            color: #cfd9e6; display: flex; flex-direction: column; z-index: 1040;
            transition: transform .25s ease;
        }
        .sidebar .brand { padding: 1.25rem 1.25rem 1rem; color: #fff; font-weight: 700; font-size: 1.25rem; display: flex; align-items: center; gap: .6rem; text-decoration: none; }
        .sidebar .brand .logo { width: 38px; height: 38px; border-radius: 10px; background: var(--ts-primary); display: grid; place-items: center; font-size: 1.25rem; }
        .sidebar .nav-link { color: #cfd9e6; padding: .7rem 1.25rem; display: flex; align-items: center; gap: .75rem; border-left: 3px solid transparent; font-weight: 500; }
        .sidebar .nav-link:hover { background: rgba(255,255,255,.07); color: #fff; }
        .sidebar .nav-link.active { background: rgba(13,110,253,.25); color: #fff; border-left-color: #6ea8fe; }
        .sidebar .nav-link i { font-size: 1.1rem; }
        .sidebar .sidebar-footer { margin-top: auto; padding: 1rem 1.25rem; font-size: .8rem; color: #8fa3bd; border-top: 1px solid rgba(255,255,255,.08); }
        .sidebar-backdrop { display: none; }

        /* Contenido */
        .main { margin-left: var(--ts-sidebar-w); min-height: 100vh; }
        .topbar { background: #fff; border-bottom: 1px solid #e3e9f1; padding: .75rem 1.5rem; display: flex; align-items: center; gap: 1rem; position: sticky; top: 0; z-index: 1030; }
        .topbar .page-title { font-weight: 600; margin: 0; font-size: 1.05rem; }
        .content { padding: 1.5rem; }

        /* Tarjetas */
        .card { border: 0; border-radius: 14px; box-shadow: 0 2px 10px rgba(15,42,74,.06); }
        .card-header { background: #fff; border-bottom: 1px solid #edf1f6; border-radius: 14px 14px 0 0 !important; font-weight: 600; }
        .stat-card { padding: 1.1rem 1.25rem; display: flex; align-items: center; gap: 1rem; }
        .stat-card .icon { width: 52px; height: 52px; border-radius: 12px; display: grid; place-items: center; font-size: 1.6rem; }
        .stat-card .value { font-size: 1.8rem; font-weight: 700; line-height: 1; }
        .stat-card .label { color: #6b7c93; font-size: .85rem; }
        .stat-blue .icon { background: #e7f0ff; color: var(--ts-primary); }
        .stat-green .icon { background: #e3f5ea; color: var(--ts-success); }
        .stat-purple .icon { background: #efe8fb; color: #6f42c1; }
        .stat-red .icon { background: #fdeaec; color: var(--ts-danger); }

        /* Tablas */
        .table > :not(caption) > * > * { padding: .8rem .9rem; vertical-align: middle; }
        .table thead th { background: #f1f5fa; color: #44566c; font-size: .78rem; text-transform: uppercase; letter-spacing: .04em; border-bottom: 0; white-space: nowrap; }
        .table-hover tbody tr:hover { background: #f8fbff; }
        .row-low { background: #fff8e6; }
        .sku { font-family: ui-monospace, SFMono-Regular, Consolas, monospace; font-size: .85rem; background: #eef2f7; padding: .15rem .45rem; border-radius: 6px; }
        .btn-icon { width: 34px; height: 34px; padding: 0; display: inline-grid; place-items: center; border-radius: 8px; }
        .form-label { font-weight: 600; font-size: .88rem; }
        .form-control, .form-select { border-radius: 9px; }
        .btn { border-radius: 9px; }
        .sort-link { color: inherit; text-decoration: none; }
        .sort-link:hover { color: var(--ts-primary); }

        /* Responsive */
        @media (max-width: 991.98px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .sidebar-backdrop.show { display: block; position: fixed; inset: 0; background: rgba(0,0,0,.45); z-index: 1035; }
            .main { margin-left: 0; }
            .content { padding: 1rem; }
        }
    </style>
</head>
<body>
@php
    $nav = [
        ['dashboard', 'Inicio', 'bi-house-door', 'dashboard'],
        ['products.index', 'Productos', 'bi-box-seam', 'products.*'],
        ['categories.index', 'Categorías', 'bi-tags', 'categories.*'],
        ['suppliers.index', 'Proveedores', 'bi-truck', 'suppliers.*'],
        ['stock-movements.index', 'Movimientos', 'bi-arrow-left-right', 'stock-movements.*'],
    ];
@endphp

<aside class="sidebar" id="sidebar">
    <a href="{{ route('dashboard') }}" class="brand">
        <span class="logo"><i class="bi bi-pc-display"></i></span> TechStock
    </a>
    <nav class="nav flex-column mt-2">
        @foreach ($nav as [$route, $label, $icon, $pattern])
            <a href="{{ route($route) }}" class="nav-link {{ request()->routeIs($pattern) ? 'active' : '' }}">
                <i class="bi {{ $icon }}"></i> {{ $label }}
            </a>
        @endforeach
    </nav>
    <div class="sidebar-footer">
        Inventario de accesorios informáticos<br>
        SENATI · Marco de Desarrollo para Proyectos
    </div>
</aside>
<div class="sidebar-backdrop" id="backdrop"></div>

<div class="main">
    <header class="topbar">
        <button class="btn btn-light d-lg-none" type="button" id="menuToggle" aria-label="Abrir menú">
            <i class="bi bi-list fs-5"></i>
        </button>
        <h1 class="page-title">@yield('title', 'Inventario TI')</h1>
        <div class="ms-auto text-muted small d-none d-md-block">
            <i class="bi bi-calendar3"></i> {{ now()->translatedFormat('d \d\e F, Y') }}
        </div>
    </header>

    <main class="content">
        {{-- Mensajes de sesión (RF10) --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                <div><strong>¡Éxito!</strong> {{ session('success') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert">
                <i class="bi bi-x-circle-fill me-2"></i>
                <div><strong>Error:</strong> {{ session('error') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        @endif
        @if (session('warning'))
            <div class="alert alert-warning alert-dismissible fade show d-flex align-items-center" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <div><strong>Advertencia:</strong> {{ session('warning') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        @endif

        @yield('content')
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const sidebar = document.getElementById('sidebar');
    const backdrop = document.getElementById('backdrop');
    const toggle = document.getElementById('menuToggle');
    function setMenu(open) {
        sidebar.classList.toggle('open', open);
        backdrop.classList.toggle('show', open);
    }
    toggle?.addEventListener('click', () => setMenu(!sidebar.classList.contains('open')));
    backdrop?.addEventListener('click', () => setMenu(false));
</script>
@stack('scripts')
</body>
</html>
