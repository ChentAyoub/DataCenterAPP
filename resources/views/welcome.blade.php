<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DataCenter Home</title>
    
</head>
<body>

    <header class="main-header">
        <nav class="navbar">
            <h1 class="logo">DataCenter.io</h1>
            
            <div class="user-menu">
                @auth
                    <details class="notifications-dropdown">
                        <summary>🔔 Notifications</summary>
                        <ul>
                            <li>✅ Reservation confirmed: Dell Server</li>
                            <li>⚠️ Maintenance alert: Network Switch</li>
                        </ul>
                    </details>

                    <span class="user-greeting">Hello, {{ Auth::user()->name }}</span>

                    @if(Auth::user()->role === 'admin')
                        <a href="{{ route('dashboard') }}" class="nav-link admin-link">🔧 Admin Panel</a>
                    @elseif(Auth::user()->role === 'manager')
                        <a href="{{ route('dashboard') }}" class="nav-link manager-link">⚡ Manager Dashboard</a>
                    @else
                        <a href="{{ route('dashboard') }}" class="nav-link student-link">📅 My Reservations</a>
                    @endif
                    
                    <form action="{{ route('logout') }}" method="POST" class="logout-form">
                        @csrf 
                        <button class="btn-logout">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="nav-link">Login</a> | 
                    <a href="{{ route('register') }}" class="nav-link">Sign Up</a>
                @endauth
            </div>
        </nav>
    </header>

    <hr class="divider">

    @if(session('success'))
        <div class="alert alert-success">
            <strong>✅ Success: {{ session('success') }}</strong>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-error">
            <strong>⚠️ Errors:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <hr class="divider">

    <section class="search-section">
        <fieldset>
            <legend>🔍 Find Resources</legend>
            
            <form action="/" method="GET" class="search-form">
                
                <div class="form-group">
                    <label>Category</label>
                    <select name="category_id" class="form-control">
                        <option value="">-- All Categories --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Keyword</label>
                    <input type="text" name="search" class="form-control" placeholder="e.g. Dell..." value="{{ request('search') }}">
                </div>
                
                <div class="form-group">
                    <label>From</label>
                    <input type="datetime-local" name="start_date" class="form-control" value="{{ request('start_date') }}">
                </div>
                
                <div class="form-group">
                    <label>To</label>
                    <input type="datetime-local" name="end_date" class="form-control" value="{{ request('end_date') }}">
                </div>

                <button type="submit" class="btn-primary search-btn">Search</button>
            </form>
        </fieldset>
    </section>

    <hr class="divider">

    <main class="main-content">
        
        @if(request('category_id') || request('search'))
            
            <div class="results-container">
                <div class="section-header">
                    <h2>
                        @if(request('category_id'))
                            {{ $categories->find(request('category_id'))->name }} Resources
                        @else
                            Search Results
                        @endif
                    </h2>
                    <a href="/" class="link-clear">(Clear Filters)</a>
                </div>

                @if($resources->isEmpty())
                    <p class="no-results">No resources found.</p>
                @else
                    <div class="grid-layout">
                        @foreach($resources as $resource)
                            <div class="resource-card">
                                
                                <div class="card-icon">
                                    @if($resource->image)
                                        <img src="{{ asset('storage/' . $resource->image) }}" alt="{{ $resource->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                    @else
                                        <span style="font-size: 50px;">
                                            @if($resource->category->name == 'Server') 🖥️
                                            @elseif($resource->category->name == 'Router') 📡
                                            @elseif($resource->category->name == 'Switch') 🔌
                                            @else 📦 @endif
                                        </span>
                                    @endif
                                </div>

                                <h3 class="card-title">{{ $resource->name }}</h3>
                                <p class="card-specs">{{ $resource->specifications ?? 'Standard Specs' }}</p>

                                <p class="card-status">
                                    @if($resource->state == 'available') 
                                        <span class="status-available">● Available</span>
                                    @else 
                                        <span class="status-maintenance">● Maintenance</span>
                                    @endif
                                </p>

                                <div class="card-actions">
                                    @auth
                                        @if(Auth::user()->role === 'admin')
                                            <a href="">
                                                <button class="btn-secondary">Edit</button>
                                            </a>
                                            <form action="{{ route('resource.destroy', $resource->id) }}" method="POST" onsubmit="return confirm('Delete?');">
                                                @csrf @method('DELETE')
                                                <button class="btn-danger">Delete</button>
                                            </form>
                                        @elseif(Auth::user()->role === 'manager')
                                            <a href="">
                                                <button class="btn-secondary">Edit</button>
                                            </a>
                                            <form action="{{ route('resource.toggle', $resource->id) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <button class="btn-warning">{{ $resource->state == 'available' ? '⚠️ Flag' : '✅ Fix' }}</button>
                                            </form>
                                        @else
                                            <a href="{{ route('resource.show', [
                                                'id' => $resource->id, 
                                                'start_time' => request('start_date'), 
                                                'end_time' => request('end_date')
                                            ]) }}" style="text-decoration: none; width: 100%;">
                                                <button class="btn-primary" style="width: 100%;">View & Reserve</button>
                                            </a>
                                        @endif
                                    @else
                                        <a href="{{ route('login') }}"><button class="btn-primary">Login to Book</button></a>
                                    @endauth
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>


        @else
            
            <h2 class="page-title">Available Resources</h2>

            @if($resources->isEmpty())
                <p class="no-results">No resources found.</p>
            @else
                
                @foreach($resources->groupBy('category.name') as $categoryName => $items)
                
                <section class="category-section">
                    
                    <div class="section-header">
                        <h3>{{ $categoryName }}</h3>
                        <a href="/?category_id={{ $items->first()->category_id }}" class="link-see-all">
                            See all >
                        </a>
                    </div>

                    <div class="carousel-wrapper">
                        
                        <button class="carousel-btn btn-left" onclick="scrollCarousel('track-{{ $loop->index }}', -300)">❮</button>

                        <div id="track-{{ $loop->index }}" class="carousel-track">
                            
                            @foreach($items as $resource)
                            <div class="resource-card">
                                
                                <div class="card-icon">
                                    @if($categoryName == 'Server') 🖥️
                                    @elseif($categoryName == 'Router') 📡
                                    @elseif($categoryName == 'Switch') 🔌
                                    @else 📦 @endif
                                </div>

                                <h3 class="card-title">{{ $resource->name }}</h3>
                                <p class="card-specs">{{ $resource->specifications ?? 'Standard Specs' }}</p>

                                <p class="card-status">
                                    @if($resource->state == 'available') 
                                        <span class="status-available">● Available</span>
                                    @else 
                                        <span class="status-maintenance">● Maintenance</span>
                                    @endif
                                </p>

                                <div class="card-actions">
                                    @auth
                                        @if(Auth::user()->role === 'admin')
                                            <a href=""><button class="btn-secondary">Edit</button></a>
                                        @elseif(Auth::user()->role === 'manager')
                                            <a href="">
                                                <button class="btn-secondary">Edit</button>
                                            </a>
                                        @else
                                            <a href="{{ route('resource.show', [
                                                'id' => $resource->id, 
                                                'start_time' => request('start_date'), 
                                                'end_time' => request('end_date')
                                            ]) }}" style="text-decoration: none; width: 100%;">
                                                <button class="btn-primary" style="width: 100%;">View & Reserve</button>
                                            </a>
                                        @endif
                                    @else
                                            <a href="{{ route('login') }}">
                                                <button class="btn-primary">Login to Book</button>
                                            </a>
                                    @endauth
                                </div>

                            </div>
                            @endforeach

                        </div>

                        <button class="carousel-btn btn-right" onclick="scrollCarousel('track-{{ $loop->index }}', 300)">❯</button>
                    
                    </div>
                </section>
                @endforeach
            @endif

        @endif
    </main>

    <script>
        function scrollCarousel(trackId, offset) {
            const track = document.getElementById(trackId);
            track.scrollBy({ left: offset, behavior: 'smooth' });
        }
    </script> 
</body>
</html>