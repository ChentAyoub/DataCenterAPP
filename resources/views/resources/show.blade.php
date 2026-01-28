<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reserve {{ $resource->name }}</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-light">

    <div class="bg-header">
        <nav class="container nav-split">
            <a href="/" class="back-link"><i class="fa-solid fa-arrow-left"></i> Back to Catalogue</a>
            <span class="header-logo">DataCenter.io</span>
        </nav>
        
        <div class="container header-content">
            <div class="badge-pill">{{ $resource->category->name }}</div>
            <h1>{{ $resource->name }}</h1>
            <p class="asset-id">Asset ID: #RES-{{ $resource->id }}00 • <span class="opacity-70">Managed by Dept of Computing</span></p>
        </div>
    </div>

    <div class="container main-content">
        <div class="glass-card">
            
            <div class="card-grid">
                <div class="card-left">
                    <div class="resource-visual">
                        @if($resource->image)
                            <img src="{{ asset('storage/' . $resource->image) }}" alt="Res">
                        @else
                            <i class="fa-solid fa-server"></i>
                        @endif
                        <div class="status-overlay {{ $resource->state == 'available' ? 'st-green' : 'st-red' }}">
                            {{ $resource->state == 'available' ? '● Online' : '● Maintenance' }}
                        </div>
                    </div>

                    <div class="specs-box">
                        <h3>System Specifications</h3>
                        <div class="spec-row">
                            <div class="spec-icon"><i class="fa-solid fa-microchip"></i></div>
                            <div class="spec-text">
                                <label>Configuration</label>
                                <strong>{{ $resource->specifications }}</strong>
                            </div>
                        </div>
                        <div class="spec-row">
                            <div class="spec-icon"><i class="fa-solid fa-location-dot"></i></div>
                            <div class="spec-text">
                                <label>Location</label>
                                <strong>Server Room B, Rack 4</strong>
                            </div>
                        </div>
                        <div class="spec-row">
                            <div class="spec-icon"><i class="fa-solid fa-file-lines"></i></div>
                            <div class="spec-text">
                                <label>Description</label>
                                <p>{{ $resource->description }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-right">
                    <div class="booking-header">
                        <h2>Reservation</h2>
                        <p>Select your time slot below.</p>
                    </div>

                    @auth
                        <form action="{{ route('reservation.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="resource_id" value="{{ $resource->id }}">

                            <div class="input-group">
                                <label>Start Time</label>
                                <div class="input-wrapper">
                                    <i class="fa-regular fa-calendar"></i>
                                    <input type="datetime-local" name="start_time" required>
                                </div>
                            </div>

                            <div class="input-group">
                                <label>End Time</label>
                                <div class="input-wrapper">
                                    <i class="fa-solid fa-clock"></i>
                                    <input type="datetime-local" name="end_time" required>
                                </div>
                            </div>

                            <div class="total-bar">
                                <span>Session Limit</span>
                                <strong>4 Hours Max</strong>
                            </div>

                            @if($resource->state == 'available')
                                <button type="submit" class="btn-confirm">
                                    Confirm Reservation <i class="fa-solid fa-arrow-right"></i>
                                </button>
                            @else
                                <button disabled class="btn-disabled">Currently Unavailable</button>
                            @endif
                        </form>
                    @else
                        <div class="login-wall">
                            <i class="fa-solid fa-lock"></i>
                            <p>Please log in to book resources.</p>
                            <a href="{{ route('login') }}" class="btn-confirm">Login Now</a>
                        </div>
                    @endauth
                </div>
            </div>

        </div>
    </div>

</body>
</html>