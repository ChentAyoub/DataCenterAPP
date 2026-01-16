<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager Dashboard</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>
<body>

<div class="app-container">

    <aside class="sidebar">
        <div class="sidebar-header">
            <h2 class="brand">DataCenter</h2>
            <span class="role-badge">MANAGER</span>
        </div>

        <nav class="sidebar-menu">
            <ul>
                <li>
                    <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="fa-solid fa-chart-line"></i> Dashboard
                    </a>
                </li>

                <li>
                    <a href="{{ route('resources.manage') }}" class="{{ request()->routeIs('resources.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-server"></i> Inventory
                    </a>
                </li>

                <li>
                    <a href="#">
                        <i class="fa-solid fa-file-contract"></i> Reports
                    </a>
                </li>
            </ul>
        </nav>

        <div class="sidebar-footer">

                <a href="/" class="logout-link" style="text-decoration: none;">
                    <i class="fa-solid fa-arrow-left"></i> Back to Website
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
        @yield('content')
    </main>

</div>

</body>
</html>