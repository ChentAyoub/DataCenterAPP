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
                | <a href="{{ route('dashboard') }}">My Dashboard</a>
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
            <legend>🔍 Find Resources</legend>
            
            <form action="/" method="GET">
                
                <div>
                    <label>Keyword (Name, CPU, RAM)</label>
                    <input type="text" name="search" placeholder="e.g. Dell, 64GB, Cisco..." value="{{ request('search') }}">
                </div>
                
                <div>
                    <label>From</label>
                    <input type="datetime-local" name="start_date" value="{{ request('start_date') }}">
                </div>
                
                <div>
                    <label>To</label>
                    <input type="datetime-local" name="end_date" value="{{ request('end_date') }}">
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
            <div>
                @foreach($resources as $resource)
                <div>
                    <div>
                        [🖼️ Image of {{ $resource->name }}]
                    </div>

                    <h3>{{ $resource->name }}</h3>
                    <p>{{ $resource->category->name }}</p>
                    
                    <p>
                        ⚙️ {{ $resource->specifications ?? 'Standard Specs' }}
                    </p>

                    <p>
                        @if($resource->state == 'available') 
                            <span>● Available</span>
                        @else 
                            <span>● Maintenance</span>
                        @endif
                    </p>

                    @auth
                        <form action="{{ route('reservation.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="resource_id" value="{{ $resource->id }}">
                            
                            <input type="hidden" name="start_time" value="{{ request('start_date') }}">
                            <input type="hidden" name="end_time" value="{{ request('end_date') }}">

                            @if(request('start_date') && request('end_date'))
                                <button type="submit">
                                    Reserve Now
                                </button>
                            @else
                                <button type="button">
                                    Select Dates Above
                                </button>
                            @endif
                        </form>
                    @else
                        <a href="{{ route('login') }}">
                            <button>Login to Book</button>
                        </a>
                    @endauth

                </div>
                <br> @endforeach

            </div>
        @endif
    </main>

</body>
</html>