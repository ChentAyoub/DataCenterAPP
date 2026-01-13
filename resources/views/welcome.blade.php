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
                <details style="display:inline-block;">
                    <summary style="cursor: pointer;">🔔 Notifications</summary>
                    <ul style="position: absolute; background: white; border: 1px solid black; padding: 10px; list-style: none;">
                        <li>✅ Reservation confirmed: Dell Server</li>
                        <li>⚠️ Maintenance alert: Network Switch</li>
                    </ul>
                </details>

                | <b>Hello, {{ Auth::user()->name }}</b>
                | <a href="{{ route('dashboard') }}">My Dashboard</a>
                | 
                <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                    @csrf 
                    <button type="submit" style="cursor: pointer;">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}">Login</a> | 
                <a href="{{ route('register') }}">Sign Up</a>
            @endauth
        </nav>
    </header>

    <hr>

    <section>
        <fieldset style="border-radius: 10px; padding: 15px; border: 1px solid #ccc;">
            <legend style="padding: 0 10px; font-weight: bold; color: #ff385c;">🔍 Find Resources</legend>
            
            <form action="/" method="GET" style="display: flex; align-items: flex-end; gap: 15px; flex-wrap: wrap;">
                
                <div style="flex: 1;">
                    <label style="display: block; font-size: 0.8rem; font-weight: bold;">Keyword (Name, CPU, RAM)</label>
                    <input type="text" name="search" placeholder="e.g. Dell, 64GB, Cisco..." 
                           value="{{ request('search') }}"
                           style="width: 100%; padding: 8px;">
                </div>
                
                <div>
                    <label style="display: block; font-size: 0.8rem; font-weight: bold;">From</label>
                    <input type="datetime-local" name="start_date" 
                           value="{{ request('start_date') }}"
                           style="padding: 8px;">
                </div>
                
                <div>
                    <label style="display: block; font-size: 0.8rem; font-weight: bold;">To</label>
                    <input type="datetime-local" name="end_date" 
                           value="{{ request('end_date') }}"
                           style="padding: 8px;">
                </div>

                <button type="submit">
                    Search
                </button>
            </form>
        </fieldset>
    </section>

    <hr>

    <main>
        <h2>Available Resources</h2>

        @if($resources->isEmpty())
            <p>No resources found matching your search.</p>
        @else
            <div style="display: flex; flex-wrap: wrap; gap: 20px;">
                
                @foreach($resources as $resource)
                <div style="border: 1px solid #ccc; padding: 15px; width: 300px; border-radius: 8px;">
                    
                    <div style="background: #eee; height: 150px; display: flex; align-items: center; justify-content: center; margin-bottom: 10px;">
                        [🖼️ Image of {{ $resource->name }}]
                    </div>

                    <h3 style="margin: 5px 0;">{{ $resource->name }}</h3>
                    <p style="margin: 5px 0; color: #555;">{{ $resource->category->name }}</p>
                    
                    <p style="font-size: 0.9rem; color: #777;">
                        ⚙️ {{ $resource->specifications ?? 'Standard Specs' }}
                    </p>

                    <p>
                        @if($resource->state == 'available') 
                            <span style="color: green; font-weight: bold;">● Available</span>
                        @else 
                            <span style="color: red; font-weight: bold;">● Maintenance</span>
                        @endif
                    </p>

                    @auth
                        <form action="{{ route('reservation.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="resource_id" value="{{ $resource->id }}">
                            
                            <input type="hidden" name="start_time" value="{{ request('start_date') }}">
                            <input type="hidden" name="end_time" value="{{ request('end_date') }}">

                            @if(request('start_date') && request('end_date'))
                                <button type="submit" style="width: 100%; padding: 10px; background: #ff385c; color: white; border: none; cursor: pointer;">
                                    Reserve Now
                                </button>
                            @else
                                <button type="button" onclick="alert('Please select a Start and End date in the top search bar first!')" style="width: 100%; padding: 10px; background: #ddd; border: none; cursor: pointer;">
                                    Select Dates Above
                                </button>
                            @endif
                        </form>
                    @else
                        <a href="{{ route('login') }}" style="text-decoration: none;">
                            <button style="width: 100%; padding: 10px; cursor: pointer;">Login to Book</button>
                        </a>
                    @endauth

                </div>
                @endforeach

            </div>
        @endif
    </main>

</body>
</html>