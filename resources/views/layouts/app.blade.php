<!doctype html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'لوحة التحكم') - {{ config('app.name') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body>
@php
    $navItems = [
        ['label' => 'لوحة التحكم', 'icon' => 'fa-chart-line', 'route' => 'dashboard', 'active' => request()->routeIs('dashboard')],
        ['label' => 'تصنيفات الميزانية', 'icon' => 'fa-tags', 'route' => 'budget-categories.index', 'active' => request()->routeIs('budget-categories.*')],
        ['label' => 'عمليات الميزانية', 'icon' => 'fa-wallet', 'route' => 'budget-operations.index', 'active' => request()->routeIs('budget-operations.*')],
        ['label' => 'تصنيفات المخزون', 'icon' => 'fa-boxes-stacked', 'route' => 'inventory-categories.index', 'active' => request()->routeIs('inventory-categories.*')],
        ['label' => 'عمليات المخزون', 'icon' => 'fa-warehouse', 'route' => 'inventory-operations.index', 'active' => request()->routeIs('inventory-operations.*')],
        ['label' => 'الحالات الإنسانية', 'icon' => 'fa-users', 'route' => 'humanitarian-cases.index', 'active' => request()->routeIs('humanitarian-cases.*') || request()->routeIs('humanitarian-case-files.*')],
        ['label' => 'المناطق', 'icon' => 'fa-map-location-dot', 'route' => 'districts.index', 'active' => request()->routeIs('districts.*')],
        ['label' => 'تصنيفات الحملات', 'icon' => 'fa-tags', 'route' => 'campaign-categories.index', 'active' => request()->routeIs('campaign-categories.*')],
        ['label' => 'الحملات', 'icon' => 'fa-bullhorn', 'route' => 'campaigns.index', 'active' => request()->routeIs('campaigns.*')],
        ['label' => 'الدلائل', 'icon' => 'fa-address-book', 'route' => 'case-referrers.index', 'active' => request()->routeIs('case-referrers.*')],
    ];
@endphp

<div class="app-shell">
    <aside class="app-sidebar">
        <a class="brand" href="{{ route('dashboard') }}">
            <span class="brand-icon"><i class="fa-solid fa-hand-holding-heart"></i></span>
            <span>
                <strong>{{ config('app.name') }}</strong>
                <small>إدارة الجمعيات الخيرية</small>
            </span>
        </a>

        <nav class="sidebar-nav">
            @foreach($navItems as $item)
                <a class="{{ $item['active'] ? 'active' : '' }}" href="{{ $item['route'] ? route($item['route']) : '#' }}">
                    <i class="fa-solid {{ $item['icon'] }}"></i>
                    <span>{{ $item['label'] }}</span>
                </a>
            @endforeach
        </nav>
    </aside>

    <div class="app-main">
        <nav class="topbar navbar navbar-expand">
            <div class="container-fluid px-0">
                <button class="btn icon-btn d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar" aria-label="فتح القائمة">
                    <i class="fa-solid fa-bars"></i>
                </button>

                <div>
                    <div class="page-kicker">مرحبًا، {{ auth()->user()->name ?? 'ضيف' }}</div>
                    <h1 class="page-title">@yield('page_title', 'لوحة التحكم')</h1>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <button class="btn icon-btn" type="button" data-bs-toggle="tooltip" title="الإشعارات">
                        <i class="fa-regular fa-bell"></i>
                    </button>
                    <button class="btn btn-outline-danger btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#logoutModal">
                        <i class="fa-solid fa-arrow-right-from-bracket ms-1"></i>
                        خروج
                    </button>
                </div>
            </div>
        </nav>

        <main class="content">
            @isset($breadcrumbs)
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">الرئيسية</a></li>
                        @foreach($breadcrumbs as $label => $url)
                            <li class="breadcrumb-item {{ $loop->last ? 'active' : '' }}" @if($loop->last) aria-current="page" @endif>
                                @if($loop->last)
                                    {{ $label }}
                                @else
                                    <a href="{{ $url }}">{{ $label }}</a>
                                @endif
                            </li>
                        @endforeach
                    </ol>
                </nav>
            @endisset

            @include('partials.flash')

            @yield('content')
        </main>
    </div>
</div>

<div class="offcanvas offcanvas-end mobile-menu" tabindex="-1" id="mobileSidebar" aria-labelledby="mobileSidebarLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="mobileSidebarLabel">{{ config('app.name') }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="إغلاق"></button>
    </div>
    <div class="offcanvas-body">
        <nav class="sidebar-nav">
            @foreach($navItems as $item)
                <a class="{{ $item['active'] ? 'active' : '' }}" href="{{ $item['route'] ? route($item['route']) : '#' }}">
                    <i class="fa-solid {{ $item['icon'] }}"></i>
                    <span>{{ $item['label'] }}</span>
                </a>
            @endforeach
        </nav>
    </div>
</div>

<div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="logoutModalLabel">تأكيد تسجيل الخروج</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
            </div>
            <div class="modal-body">هل تريد إنهاء الجلسة الحالية؟</div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">إلغاء</button>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-danger">تسجيل الخروج</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.js"></script>
<script>
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach((element) => new bootstrap.Tooltip(element));

    document.querySelectorAll('.datatable').forEach((table) => {
        new DataTable(table, {
            language: {
                url: 'https://cdn.datatables.net/plug-ins/2.1.8/i18n/ar.json'
            },
            pageLength: 5,
            lengthMenu: [5, 10, 25],
            responsive: true
        });
    });
</script>
@stack('scripts')
</body>
</html>
