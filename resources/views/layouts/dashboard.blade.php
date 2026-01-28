<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>

    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

@php
    $authUser = auth()->user();
    $pendingUsers = collect();
    $pendingReservations = collect();

    if ($authUser) {
        if ($authUser->role === 'admin') {
            $pendingUsers = \App\Models\User::where('role','!=','admin')
                ->where('is_approved', false)
                ->orderBy('created_at','asc')
                ->take(6)
                ->get();

            $pendingReservations = \App\Models\Reservation::where('status','pending')
                ->with(['user','resource'])
                ->orderBy('created_at','asc')
                ->take(6)
                ->get();
        }

        if ($authUser->role === 'manager') {
            $pendingReservations = \App\Models\Reservation::where('status','pending')
                ->with(['user','resource'])
                ->orderBy('created_at','asc')
                ->take(6)
                ->get();
        }
    }

    $notifCount = $pendingUsers->count() + $pendingReservations->count();
@endphp

<div class="app-container">

    <aside class="sidebar">
        <div class="sidebar-header">
            <a class="brand" href="{{ route('welcome') }}" aria-label="Back to home">
                <img src="{{ asset('images/logo1NBG.png') }}" alt="DataCenter" class="brand-logo" />
                <span>DataCenter</span>
            </a>
            <span class="role-badge">{{ strtoupper($authUser->role ?? 'GUEST') }}</span>
        </div>

        <nav class="sidebar-menu">
            <ul>
                <li>
                    <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="fa-solid fa-chart-line"></i> Dashboard
                    </a>
                </li>

                @if(($authUser->role ?? '') === 'admin')
                    <li>
                        <a href="{{ route('dashboard') }}#users">
                            <i class="fa-solid fa-users"></i> Users
                        </a>
                    </li>
                @endif

                @if(($authUser->role ?? '') === 'admin')
                    <li>
                        <a href="{{ route('admin.reservations') }}" class="{{ request()->routeIs('admin.reservations') ? 'active' : '' }}">
                            <i class="fa-solid fa-calendar-check"></i> Reservations
                        </a>
                    </li>
                @else
                    <li>
                        <a href="{{ route('reservations.my_list') }}" class="{{ request()->routeIs('reservations.*') ? 'active' : '' }}">
                            <i class="fa-solid fa-calendar-check"></i> Reservations
                        </a>
                    </li>
                @endif
            </ul>
        </nav>

        <div class="sidebar-footer">
            <a href="{{ route('catalogue') }}" class="logout-link" style="text-decoration:none;">
                <i class="fa-solid fa-arrow-left"></i> Back to Catalogue
            </a>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="logout-link">
                    <i class="fa-solid fa-power-off"></i> Logout
                </button>
            </form>
        </div>
    </aside>

    <main class="main-content">

        <header class="headerbar">
            <div class="headerbar-left">
                <span class="headerbar-page">{{ ucfirst($authUser->role ?? 'guest') }} Panel</span>
            </div>

            <div class="headerbar-right">
                <button class="icon-btn" type="button" id="themeToggle" aria-label="Toggle theme" title="Dark/Light">
                    <i class="fa-solid fa-moon"></i>
                </button>

                @if(in_array(($authUser->role ?? ''), ['admin','manager']))
                    <button class="icon-btn" type="button" id="notifToggle" aria-label="Notifications" title="Notifications">
                        <i class="fa-regular fa-bell"></i>
                        @if($notifCount > 0)
                            <span class="notif-count">{{ $notifCount }}</span>
                        @endif
                    </button>
                @endif
            </div>
        </header>

        @if(in_array(($authUser->role ?? ''), ['admin','manager']))
            <div class="notif-drawer" id="notifDrawer" aria-hidden="true">
                <div class="notif-drawer-head">
                    <div>
                        <div class="notif-title">Notifications</div>
                        <div class="notif-sub">Approvals & new requests</div>
                    </div>
                    <button class="icon-btn icon-btn--small" type="button" id="notifClose" aria-label="Close">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                @if(($authUser->role ?? '') === 'admin')
                    <div class="notif-section">
                        <div class="notif-section-title">Account approvals</div>

                        @forelse($pendingUsers as $u)
                            <div class="notif-item">
                                <div class="notif-item-main">
                                    <div class="notif-item-title">{{ $u->name }}</div>
                                    <div class="notif-item-meta">{{ $u->email }} · {{ strtoupper($u->role) }}</div>
                                </div>
                                <div class="notif-actions">
                                    <form method="POST" action="{{ route('users.approve', $u->id) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button class="btn accent btn-small" type="submit">Approve</button>
                                    </form>
                                    <form method="POST" action="{{ route('users.reject', $u->id) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button class="btn ghost btn-small" type="submit">Reject</button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="notif-empty">No pending account requests.</div>
                        @endforelse
                    </div>
                @endif

                <div class="notif-section">
                    <div class="notif-section-title">Reservation requests</div>

                    @forelse($pendingReservations as $r)
                        <div class="notif-item">
                            <div class="notif-item-main">
                                <div class="notif-item-title">{{ optional($r->user)->name }} → {{ optional($r->resource)->name }}</div>
                                <div class="notif-item-meta">{{ $r->start_time }} → {{ $r->end_time }}</div>
                            </div>
                            <div class="notif-actions">
                                <form method="POST" action="{{ route('reservation.approve', $r->id) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button class="btn accent btn-small" type="submit">Approve</button>
                                </form>
                                <form method="POST" action="{{ route('reservation.reject', $r->id) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button class="btn ghost btn-small" type="submit">Reject</button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="notif-empty">No pending reservations.</div>
                    @endforelse
                </div>
            </div>
        @endif

        @if(session('success'))
            <div class="flash">
                <strong>Success</strong>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="flash error">
                <strong>Error</strong>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @yield('content')
    </main>
</div>

<script src="{{ asset('js/dashboard-ui.js') }}"></script>
@yield('scripts')
</body>
</html>
