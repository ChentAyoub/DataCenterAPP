<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $resource->name }} - Details</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>

    <nav class="detail-nav">
        <a href="/" class="link-back">⬅ Back to Search</a>
    </nav>

    @if(session('success'))
        <div class="alert alert-success">
            ✅ {{ session('success') }}
        </div>
    @endif

    <div class="detail-container">
        
        <div class="left-column">
            
            <div class="hero-image">
                @if($resource->image)
                    <img src="{{ asset('storage/' . $resource->image) }}" alt="{{ $resource->name }}" class="resource-img">
                @else
                    <span class="resource-emoji">
                        @if($resource->category->name == 'Server') 🖥️
                        @elseif($resource->category->name == 'Router') 📡
                        @else 📦 @endif
                    </span>
                @endif
            </div>

            <h1 class="resource-title">{{ $resource->name }}</h1>
            <p class="resource-category">{{ $resource->category->name }}</p>

            <hr class="divider">

            <div class="resource-description">
                <h3>About this resource</h3>
                <p>
                    {{ $resource->description ?? 'No detailed description available for this resource.' }}
                </p>
            </div>

            <div class="resource-specs">
                <h3>Technical Specifications</h3>
                
                <div class="spec-grid">
                    <div class="spec-item">
                        <strong>Configuration</strong><br>
                        {{ $resource->specifications }}
                    </div>
                    
                    <div class="spec-item">
                        <strong>State</strong><br>
                        @if($resource->state == 'available') 
                            <span class="status-available">● Fully Operational</span>
                        @else 
                            <span class="status-maintenance">● Under Maintenance</span>
                        @endif
                    </div>
                    
                    <div class="spec-item">
                        <strong>Asset ID</strong><br>
                        #RES-{{ $resource->id }}00
                    </div>
                </div>
            </div>

        </div>

        <div class="right-column">
            <div class="reservation-card">
                
                <span class="card-header">Reserve Now</span>

                @auth
                    <form action="{{ route('reservation.store') }}" method="POST" class="reservation-form">
                        @csrf
                        <input type="hidden" name="resource_id" value="{{ $resource->id }}">

                        <div class="form-group">
                            <label>Start Date</label>
                            <input type="datetime-local" name="start_time" 
                                   value="{{ request('start_time') }}" 
                                   required class="form-control">
                        </div>

                        <div class="form-group">
                            <label>End Date</label>
                            <input type="datetime-local" name="end_time" 
                                   value="{{ request('end_time') }}" 
                                   required class="form-control">
                        </div>

                        @if($resource->state == 'available')
                            <button type="submit" class="btn-primary btn-full">
                                Confirm Reservation
                            </button>
                        @else
                            <button type="button" disabled class="btn-disabled btn-full">
                                ⛔ Currently Unavailable
                            </button>
                            <p class="maintenance-warning">
                                This resource is flagged for maintenance.
                            </p>
                        @endif
                    </form>

                @else
                    <div class="guest-message">
                        <p>Log in to check availability and book this resource.</p>
                        <a href="{{ route('login') }}">
                            <button class="btn-primary btn-full">
                                Login to Book
                            </button>
                        </a>
                    </div>
                @endauth

            </div>
        </div>

    </div>

</body>
</html>