<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Reservations</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <link rel="stylesheet" href="{{ asset('css/mylist.css') }}">
</head>
<body>

    <header class="main-header">
        <nav class="navbar">
             <a href="/welcome" class="pro-logo"><img src="{{ asset('images/logo1NBG.png') }}" alt="DataCenter Logo" class="logo-image" style="height:100px; vertical-align:middle;"></a>
            <div class="user-menu">
                <a href="/" class="nav-link">⬅ Back</a>
                <form action="{{ route('logout') }}" method="POST" class="logout-form">
                    @csrf 
                    <button class="btn-logout">Logout</button>
                </form>
            </div>
        </nav>
    </header>

    <div class="main-content">
        
        <div class="page-header">
            <h2 class="page-title">My Reservations</h2>

            <form action="{{ route('reservations.my_list') }}" method="GET" class="mini-search">
                
                <select name="status" class="search-select" onchange="this.form.submit()">
                    <option value="all">All Statuses</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>⏳ Pending</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>✅ Approved</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>❌ Rejected</option>
                </select>

                <input type="text" name="search" placeholder="Search resource..." value="{{ request('search') }}" 
                       style="padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 8px; outline: none;">
                
                <button type="submit" style="background: #385c40; color: white; border: none; padding: 8px 12px; border-radius: 8px; cursor: pointer;">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>

                @if(request('search') || (request('status') && request('status') !== 'all'))
                    <a href="{{ route('reservations.my_list') }}" class="clear-search">✖</a>
                @endif
            </form>
        </div>

        @if(session('success'))
            <div class="alert alert-success" style="background:#dcfce7; color:#166534; padding:15px; border-radius:8px; margin-bottom:20px; border:1px solid #bbf7d0;">
                {{ session('success') }}
            </div>
        @endif

        <h3 class="section-title"><i class="fa-solid fa-clock"></i> Active & Upcoming</h3>
        
        @php
            $active = $reservations->filter(function($res) {
                return in_array($res->status, ['pending', 'approved']);
            });
            $history = $reservations->filter(function($res) {
                return in_array($res->status, ['rejected', 'cancelled']);
            });
        @endphp

        @if($active->isEmpty())
            <div style="text-align:center; padding: 40px; background:white; border-radius:12px; border:1px dashed #cbd5e1; color:#64748b;">
                <p>No active reservations.</p>
            </div>
        @else
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Resource</th>
                            <th>Duration</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($active as $res)
                        <tr>
                            <td><strong>{{ $res->resource->name }}</strong></td>
                            <td>
                                <div style="font-size:13px;">
                                    <div><i class="fa-solid fa-play" style="color:#22c55e;"></i> {{ \Carbon\Carbon::parse($res->start_time)->format('M d, H:i') }}</div>
                                    <div style="margin-top:4px;"><i class="fa-solid fa-stop" style="color:#ef4444;"></i> {{ \Carbon\Carbon::parse($res->end_time)->format('M d, H:i') }}</div>
                                </div>
                            </td>
                            <td>
                                @if($res->status == 'pending')
                                    <span class="badge badge-warning">Pending</span>
                                @else
                                    <span class="badge badge-success">Approved</span>
                                @endif
                            </td>
                            <td>
                                @if($res->status == 'pending')
                                    <form action="{{ route('reservation.destroy', $res->id) }}" method="POST" onsubmit="return confirm('Cancel this request?');">
                                        @csrf @method('DELETE')
                                        <button class="btn-danger-sm">Cancel</button>
                                    </form>
                                @else
                                    <button class="btn-disabled-sm">Locked</button>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif


        <h3 class="section-title" style="color:#64748b; margin-top: 50px;"><i class="fa-solid fa-history"></i> History & Past</h3>
        
        @if($history->isEmpty())
             <div style="text-align:center; padding: 30px; background: #f8fafc; border-radius:12px; border:1px dashed #e2e8f0; color:#94a3b8;">
                <p>No history found.</p>
            </div>
        @else
            <div class="table-container" style="opacity: 0.8;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Resource</th>
                            <th>Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($history as $res)
                        <tr>
                            <td><span style="font-weight:600; color:#64748b;">{{ $res->resource->name }}</span></td>
                            <td>{{ \Carbon\Carbon::parse($res->start_time)->format('M d, Y') }}</td>
                            <td>
                                @if($res->status == 'rejected')
                                    <span class="badge badge-danger">Rejected</span>
                                @else
                                    <span class="badge badge-gray">{{ $res->status }}</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

    </div>
    <footer class="pro-footer">
        <div class="container footer-grid">
            <div class="footer-col brand-col">
                <a href="/" class="brand-logo" style="color:white;">
                    <img src="{{ asset('images/logoBLK.png') }}" alt="DigitalCenter Logo" class="logo-image">
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
                <a href="{{route('usage-rules')}}">Usage Rules</a>
                <a href="{{route('privacy-policy')}}">Privacy Policy</a>
            </div>
            
            <div class="footer-col">
                <h5>Contact Us</h5>
                <a href="#">IT Support</a>
            </div>
        </div>
        <div class="footer-bottom">
            &copy; 2026 DigitalCenter. All rights reserved.
        </div>
    </footer>

</body>
</html>