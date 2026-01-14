<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DataCenter Home</title>
</head>
<body>

    <header>
        <nav>
            <h1>DataCenter.io</h1>
            
            @auth
                <details>
                    <summary>🔔 Notifications</summary>
                    <ul>
                        <li>✅ Reservation confirmed: Dell Server</li>
                        <li>⚠️ Maintenance alert: Network Switch</li>
                    </ul>
                </details>

                | <b>Hello, {{ Auth::user()->name }}</b>

                @if(Auth::user()->role === 'admin')
                    | <a href="{{ route('dashboard') }}">🔧 Admin Panel</a>
                @elseif(Auth::user()->role === 'manager')
                    | <a href="{{ route('dashboard') }}">⚡ Manager Dashboard</a>
                @else
                    | <a href="{{ route('dashboard') }}">📅 My Reservations</a>
                @endif
                
                | 
                <form action="{{ route('logout') }}" method="POST">
                    @csrf 
                    <button>Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}">Login</a> | 
                <a href="{{ route('register') }}">Sign Up</a>
            @endauth
        </nav>
    </header>

    <hr>

    @if(session('success'))
        <div>
            <strong>✅ Success: {{ session('success') }}</strong>
        </div>
    @endif

    @if($errors->any())
        <div>
            <strong>⚠️ Errors:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <hr>

    <section>
        <fieldset>
            <legend> Find Resources</legend>
            
            <form action="/" method="GET">
                
                <div style="margin-bottom: 10px;">
                    <label>Category</label>
                    <select name="category_id" style="padding: 5px;">
                        <option value="">-- All Categories --</option>
                        
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" 
                                {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label>Keyword (Name, CPU, RAM)</label>
                    <input type="text" name="search" placeholder="e.g. Dell..." value="{{ request('search') }}">
                </div>
                
                <div>
                    <label>From</label>
                    <input type="datetime-local" name="start_date" value="{{ request('start_date') }}">
                </div>
                
                <div>
                    <label>To</label>
                    <input type="datetime-local" name="end_date" value="{{ request('end_date') }}">
                </div>

                <button type="submit" style="margin-top: 10px;">Search</button>
            </form>
        </fieldset>
    </section>

    <hr>

    <main>
        
        @if(request('category_id') || request('search'))
            
            <div>
                <h2>
                    @if(request('category_id'))
                        {{ $categories->find(request('category_id'))->name }} Resources
                    @else
                        Search Results
                    @endif
                    <a href="/">(Clear Filters)</a>
                </h2>

                @if($resources->isEmpty())
                    <p>No resources found.</p>
                @else
                    <div>
                        
                        @foreach($resources as $resource)
                            <div>
                                
                                <div>
                                    @if($resource->category->name == 'Server') 🖥️
                                    @elseif($resource->category->name == 'Router') 📡
                                    @elseif($resource->category->name == 'Switch') 🔌
                                    @else 📦 @endif
                                </div>

                                <h3>{{ $resource->name }}</h3>
                                <p>{{ $resource->specifications ?? 'Standard Specs' }}</p>

                                <p>
                                    @if($resource->state == 'available') 
                                        <span>● Available</span>
                                    @else 
                                        <span>● Maintenance</span>
                                    @endif
                                </p>

                                @auth
                                    @if(Auth::user()->role === 'admin')
                                        <div>
                                            <a href="{{ route('resource.edit', $resource->id) }}">
                                                <button>Edit</button>
                                        </a>
                                            <form action="{{ route('resource.destroy', $resource->id) }}" method="POST" onsubmit="return confirm('Delete?');">
                                                @csrf @method('DELETE')
                                                <button>Delete</button>
                                            </form>
                                        </div>
                                    @elseif(Auth::user()->role === 'manager')
                                        <div>
                                            <a href="{{ route('resource.edit', $resource->id) }}">
                                                <button>Edit</button>
                                            </a>
                                            <form action="{{ route('resource.toggle', $resource->id) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <button>{{ $resource->state == 'available' ? '⚠️ Flag' : '✅ Fix' }}</button>
                                            </form>
                                        </div>
                                    @else
                                        <form action="{{ route('reservation.store') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="resource_id" value="{{ $resource->id }}">
                                            <input type="hidden" name="start_time" value="{{ request('start_date') }}">
                                            <input type="hidden" name="end_time" value="{{ request('end_date') }}">
                                            
                                            @if($resource->state == 'maintenance')
                                                <button disabled>⛔ Maintenance</button>
                                            @elseif(request('start_date'))
                                                <button>Reserve</button>
                                            @else
                                                <button type="button" onclick="alert('Select dates!')">Select Dates</button>
                                            @endif
                                        </form>
                                    @endif
                                @else
                                    <a href="{{ route('login') }}"><button style="width: 100%;">Login to Book</button></a>
                                @endauth
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>


        @else
            
            <h2>Available Resources</h2>

            @if($resources->isEmpty())
                <p>No resources found.</p>
            @else
                
                @foreach($resources->groupBy('category.name') as $categoryName => $items)
                
                <div>
                    
                    <div>
                        <h3>{{ $categoryName }}</h3>
                        <a href="/?category_id={{ $items->first()->category_id }}">
                            See all >
                        </a>
                    </div>

                    <div>
                        
                        <button onclick="scrollCarousel('track-{{ $loop->index }}', -300)">❮</button>

                        <div id="track-{{ $loop->index }}">
                            
                            @foreach($items as $resource)
                            <div>
                                
                                <div>
                                    @if($categoryName == 'Server') 🖥️
                                    @elseif($categoryName == 'Router') 📡
                                    @elseif($categoryName == 'Switch') 🔌
                                    @else 📦 @endif
                                </div>

                                <h3>{{ $resource->name }}</h3>
                                <p>{{ $resource->specifications ?? 'Standard Specs' }}</p>

                                <p>
                                    @if($resource->state == 'available') 
                                        <span style="color: green;">● Available</span>
                                    @else 
                                        <span style="color: red;">● Maintenance</span>
                                    @endif
                                </p>

                                @auth
                                    @if(Auth::user()->role === 'admin')
                                        <a href="{{ route('resource.edit', $resource->id) }}"><button>Edit</button></a>
                                    @elseif(Auth::user()->role === 'manager')
                                        <a href="{{ route('resource.edit', $resource->id) }}"><button>Edit</button></a>
                                    @else
                                        <button onclick="alert('Please select dates or view details')">View Details</button>
                                    @endif
                                @endauth

                            </div>
                            @endforeach

                        </div>

                        <button onclick="scrollCarousel('track-{{ $loop->index }}', 300)">❯</button>
                    
                    </div>
                </div>
                @endforeach
            @endif

        @endif
    </main>
<script>
        function scrollCarousel(trackId, offset) {
            const track = document.getElementById(trackId);
            track.scrollBy({ left: offset, behavior: 'smooth' });
        }
    </script> <!-- Carousel logic to add in the homepage.js -->
</body>
</html>