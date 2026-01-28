<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catalogue - DataCenter</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script src="{{ asset('js/catalogue.js') }}" defer></script>
</head>
<body>

    <nav class="pro-navbar">
        <a href="/" class="pro-logo"><img src="{{ asset('images/logo1NBG.png') }}" alt="DataCenter Logo" class="logo-image" style="height:100px; vertical-align:middle;"></a>
        <div class="pro-menu">
            <a href="/" class="nav-btn"><i class="fa-solid fa-house"></i> Home</a>
            @auth
                <a href="#" class="nav-btn warning"><i class="fa-solid fa-triangle-exclamation"></i> Reclamations</a>
                @if(Auth::user()->role === 'admin')
                    <a href="{{ route('dashboard') }}" class="nav-btn primary"><i class="fa-solid fa-wrench"></i> Admin Panel</a>
                @elseif(Auth::user()->role === 'manager')
                    <a href="{{ route('dashboard') }}" class="nav-btn primary"><i class="fa-solid fa-bolt"></i> Manager Dash</a>
               @else
                <div class="notify-wrapper">
                   <div class="notify-icon-container" onclick="toggleNotifications()">
                       <i class="fa-solid fa-bell"></i>
                       @if(isset($unreadCount) && $unreadCount > 0)
                           <span class="notify-badge" id="notify-badge-id">{{ $unreadCount }}</span>
                       @endif
                   </div>

                   <div class="notify-dropdown" id="notificationDropdown">
                       <div class="notify-header">
                           <h4>Notifications</h4>
                           <span class="mark-read" id="mark-read-btn" onclick="markAllAsRead()">Mark all read</span>
                       </div>

                       <div class="notify-list" id="notify-list-container">
                           @if(isset($notifications) && $notifications->count() > 0)
                               @foreach($notifications as $notify)
                                   <div class="notify-item {{ $notify->is_read ? '' : 'unread' }}" id="notify-item-{{ $notify->id }}">
                                       <div class="n-icon {{ $notify->type == 'success' ? 'success' : 'warning' }}">
                                           @if($notify->type == 'success')
                                               <i class="fa-solid fa-check"></i>
                                           @else
                                               <i class="fa-solid fa-info"></i>
                                           @endif
                                       </div>
                                       <div class="n-content">
                                           <span class="n-msg">{{ $notify->message }}</span>
                                           <span class="n-time">{{ $notify->created_at->diffForHumans() }}</span>
                                       </div>
                                   </div>
                               @endforeach
                           @else
                               <div class="empty-state">No notifications yet.</div>
                           @endif
                       </div>
                   </div>
                </div>

                 <a href="{{ route('dashboard') }}" class="nav-btn primary">
                     <i class="fa-solid fa-calendar-check"></i> My Reservations
                 </a>
            @endif
                <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                    @csrf 
                    <button class="nav-btn logout-btn"><i class="fa-solid fa-right-from-bracket"></i> Log Out</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="nav-btn">Login</a>
                <a href="{{ route('register') }}" class="nav-btn primary">Sign Up</a>
            @endauth
        </div>
    </nav>

    <div class="app-container">
        
        <aside class="sidebar">
            <form action="/" method="GET" id="filterForm">
                <div class="filter-group">
                    <h3>Select Date</h3>
                    <div class="calendar-widget">
                        <div class="cal-inputs">
                            <div class="cal-input-group">
                                <span class="cal-label">From</span>
                                <div id="displayStart" class="cal-display" onclick="setPickMode('start')">--/--</div>
                            </div>
                            <div class="cal-input-group">
                                <span class="cal-label">To</span>
                                <div id="displayEnd" class="cal-display" onclick="setPickMode('end')">--/--</div>
                            </div>
                        </div>
                        <input type="hidden" name="start_date" id="realStart" value="{{ request('start_date') }}">
                        <input type="hidden" name="end_date" id="realEnd" value="{{ request('end_date') }}">
                        <div class="cal-header">
                            <div class="cal-nav" onclick="changeMonth(-1)">❮</div>
                            <div class="cal-month" id="calMonthYear">January 2026</div>
                            <div class="cal-nav" onclick="changeMonth(1)">❯</div>
                            <button type="button" class="btn-today" onclick="goToToday()">Today</button>
                        </div>
                        <div class="cal-grid">
                            <div class="cal-day-name">Mo</div><div class="cal-day-name">Tu</div><div class="cal-day-name">We</div>
                            <div class="cal-day-name">Th</div><div class="cal-day-name">Fr</div><div class="cal-day-name">Sa</div>
                            <div class="cal-day-name">Su</div>
                        </div>
                        <div class="cal-grid" id="calDays"></div>
                        <button type="submit" class="btn-apply-filters">Apply Dates</button>
                    </div>
                </div>
                <br><br>
                <div class="filter-group">
                    <h3>Categories</h3>
                    <div class="cat-list">
                        <label class="cat-item">
                            <input type="radio" name="category_id" value="" {{ !request('category_id') ? 'checked' : '' }} onchange="this.form.submit()">
                            All Resources
                        </label>
                        @foreach($categories as $cat)
                        <label class="cat-item">
                            <input type="radio" name="category_id" value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'checked' : '' }} onchange="this.form.submit()">
                            {{ $cat->name }}
                        </label>
                        @endforeach
                    </div>
                </div>
            </form>
        </aside>

        <main class="content-area">
            <div class="top-bar">
                <div class="back-link" onclick="window.history.back()">
                    <i class="fa-solid fa-arrow-left"></i> &nbsp;Back
                </div>
                <input type="text" form="filterForm" name="search" class="top-search" placeholder="Search resources..." value="{{ request('search') }}">
            </div>

            @if($resources->isEmpty())
                <div style="text-align:center; padding:50px; color:#999;">
                    <h3>No resources found</h3>
                    <p>Try adjusting your filters.</p>
                </div>
            @else
                <div class="card-grid">
                    @foreach($resources as $resource)
                    <div class="pro-card">
                        <div class="card-img">
                            @if($resource->image)
                                <img src="{{ asset('storage/' . $resource->image) }}" alt="{{ $resource->name }}">
                            @else
                                <i class="fa-solid fa-server"></i>
                            @endif
                        </div>
                        <span class="status-badge {{ $resource->state == 'available' ? 'green' : 'orange' }}">
                            {{ $resource->state == 'available' ? 'Available' : 'Maintenance' }}
                        </span>
                        <div class="card-body">
                            <h4 class="card-title">{{ $resource->name }}</h4>
                            <p class="card-specs">{{ Str::limit($resource->specifications ?? 'High Perf.', 40) }}</p>
                            
                            <div class="card-actions">
                                @auth
                                    @if(Auth::user()->role === 'admin')
                                        <div style="display:flex; gap:10px;">
                                            <a href="{{ route('resources.edit', $resource->id) }}" style="flex:1;">
                                                <button class="card-btn" style="background:#fff; border:1px solid #ddd; color:#333;">Edit</button>
                                            </a>
                                            <form action="{{ route('resource.destroy', $resource->id) }}" method="POST" onsubmit="return confirm('Delete?');" style="flex:1;">
                                                @csrf @method('DELETE')
                                                <button class="card-btn" style="background:#fee2e2; color:#b91c1c;">Delete</button>
                                            </form>
                                        </div>

                                    @elseif(Auth::user()->role === 'manager')
                                        <div style="display:flex; gap:10px;">
                                            <a href="{{ route('resources.edit', $resource->id) }}" style="flex:1;">
                                                <button class="card-btn" style="background:#fff; border:1px solid #ddd; color:#333;">Edit</button>
                                            </a>
                                            <form action="{{ route('resource.toggle', $resource->id) }}" method="POST" style="flex:1;">
                                                @csrf @method('PATCH')
                                                <button class="card-btn" style="background:#fff7ed; color:#c2410c;">
                                                    {{ $resource->state == 'available' ? 'Flag' : 'Fix' }}
                                                </button>
                                            </form>
                                        </div>

                                    @else
                                        <a href="{{ route('resources.show', ['id' => $resource->id]) }}" class="card-btn">
                                            {{ $resource->state == 'available' ? 'Reserve Now' : 'View Details' }}
                                        </a>
                                    @endif
                                @else
                                    <a href="{{ route('login') }}" class="card-btn">Login to Reserve</a>
                                @endauth
                            </div>
                            </div>
                    </div>
                    @endforeach
                </div>

                <div class="pagination-wrapper">
                    @if($resources->hasPages())
                        {{ $resources->appends(request()->query())->links('pagination.default') }}
                    @else
                        <ul class="pagination" role="navigation">
                            <li class="page-item disabled"><span class="page-link">&lsaquo;</span></li>
                            <li class="page-item active"><span class="page-link">1</span></li>
                            <li class="page-item disabled"><span class="page-link">&rsaquo;</span></li>
                        </ul>
                    @endif
                </div>
            @endif
        </main>
    </div>

    <footer class="pro-footer">
        <div class="footer-grid">
            <div class="footer-col brand-col">
                <a href="/" class="brand-logo" style="color:white;">
                    <img src="{{ asset('images/logoBLK.png') }}" alt="DigitalCenter" style="height:100px; vertical-align:middle;">
                </a>
                <p>Platform for allocation and tracking of Data Center IT resources.</p>
            </div>
            <div class="footer-col">
                <h5>Navigation</h5>
                <a href="{{ url('/catalogue') }}">Catalogue</a>
                <a href="#">My Reservations</a>
            </div>
            <div class="footer-col">
                <h5>Legal</h5>
                <a href="#">Usage Rules</a>
                <a href="#">Privacy Policy</a>
            </div>
            <div class="footer-col">
                <h5>Contact Us</h5>
                <a href="#">IT Support</a>
            </div>
        </div>
        <div class="footer-bottom">&copy; 2026 DigitalCenter. All rights reserved.</div>
 
    </footer>




<script>

    function toggleNotifications() {
        const dropdown = document.getElementById('notificationDropdown');
        const icon = document.querySelector('.notify-icon-container');
        
        if (dropdown.style.display === 'block') {
            dropdown.style.display = 'none';
            icon.classList.remove('active');
        } else {
            dropdown.style.display = 'block';
            icon.classList.add('active');
        }
    }

    function markAllAsRead() {
        console.log("1. Starting Mark Read process..."); // Debug
        fetch("{{ route('notifications.markRead') }}", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "Content-Type": "application/json"
            }
        })
        .then(response => response.json())
        .then(data => {
            console.log("2. Server Response:", data); // Debug

            if (data.success) {
                console.log("3. Updating UI..."); // Debug
                const badge = document.getElementById('notify-badge-id');
                if (badge) {
                    console.log("   - Found badge. Removing it."); // Debug
                    badge.remove();
                } else {
                    console.log("   - WARNING: Could not find badge element with ID 'notify-badge-id'");
                }
                const listContainer = document.getElementById('notify-list-container');
                if(listContainer) {
                    const unreadItems = listContainer.querySelectorAll('.unread');
                    console.log("   - Found " + unreadItems.length + " unread items.");

                    unreadItems.forEach(item => {
                        item.classList.remove('unread');
                        item.style.backgroundColor = '#ffffff';
                    });
                }
            }
        })
        .catch(error => console.error('Error:', error));
    }
    document.addEventListener('click', function(event) {
        const wrapper = document.querySelector('.notify-wrapper');
        const dropdown = document.getElementById('notificationDropdown');
        
        if (wrapper && dropdown && !wrapper.contains(event.target)) {
            dropdown.style.display = 'none';
            const icon = document.querySelector('.notify-icon-container');
            if(icon) icon.classList.remove('active');
        }
    });
</script>

</body>
</html>