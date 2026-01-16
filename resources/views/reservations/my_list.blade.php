<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Reservations</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>

    <header class="main-header">
        <nav class="navbar">
            <h1 class="logo">DataCenter.io</h1>
            <div class="user-menu">
                <a href="/" class="nav-link">⬅ Back to Home</a>
                
                <form action="{{ route('logout') }}" method="POST" class="logout-form">
                    @csrf 
                    <button class="btn-logout">Logout</button>
                </form>
            </div>
        </nav>
    </header>

    <div class="main-content" style="margin-top: 40px;">
        
        <div class="page-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 10px;">
            <h2 class="page-title" style="margin: 0;">📅 My Reservations</h2>

            <form action="{{ route('reservations.my_list') }}" method="GET" class="mini-search">
                
                <select name="status" class="search-select" onchange="this.form.submit()">
                    <option value="all">All Statuses</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>⏳ Pending</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>✅ Approved</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>❌ Rejected</option>
                </select>

                <input type="text" name="search" placeholder="Search resource..." value="{{ request('search') }}">
                <button type="submit">🔍</button>

                @if(request('search') || (request('status') && request('status') !== 'all'))
                    <a href="{{ route('reservations.my_list') }}" class="clear-search" title="Clear Filters">✖</a>
                @endif
            </form>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if($reservations->isEmpty())
            
            <div class="empty-state">
                @if(request('search') || request('status'))
                    <p>🚫 No reservations found matching your filters.</p>
                    <a href="{{ route('reservations.my_list') }}">
                        <button class="btn-secondary">Clear All Filters</button>
                    </a>
                @else
                    <p>You haven't booked anything yet.</p>
                    <a href="/">
                        <button class="btn-primary">Browse Resources</button>
                    </a>
                @endif
            </div>

        @else
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Resource</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reservations as $res)
                        <tr>
                            <td>
                                <strong>{{ $res->resource->name }}</strong><br>
                                <small class="text-muted">{{ $res->resource->category->name ?? 'Device' }}</small>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($res->start_time)->format('M d, H:i') }}</td>
                            <td>{{ \Carbon\Carbon::parse($res->end_time)->format('M d, H:i') }}</td>
                            <td>
                                @if($res->status == 'pending')
                                    <span class="badge badge-warning">⏳ Pending</span>
                                @elseif($res->status == 'approved')
                                    <span class="badge badge-success">✅ Approved</span>
                                @elseif($res->status == 'rejected')
                                    <span class="badge badge-danger">❌ Rejected</span>
                                @else
                                    <span class="badge badge-secondary">{{ $res->status }}</span>
                                @endif
                            </td>
                            <td>
                                @if($res->status == 'pending')
                                    <form action="{{ route('reservation.destroy', $res->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this?');">
                                        @csrf @method('DELETE')
                                        <button class="btn-danger-sm">Cancel</button>
                                    </form>
                                @else
                                    <button class="btn-disabled-sm" disabled>Locked</button>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

</body>
</html>