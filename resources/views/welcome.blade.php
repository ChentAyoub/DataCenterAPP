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

                <button type="submit">Search</button>
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
                    <p><b>Category:</b> {{ $resource->category->name }}</p>
                    <p>
                        ⚙️ {{ $resource->specifications ?? 'Standard Specs' }}
                    </p>

                    <p>
                        @if($resource->state == 'available') 
                            <span style="color: green;">● Available</span>
                        @else 
                            <span style="color: red;">● Maintenance (Broken)</span>
                        @endif
                    </p>

                    @auth
                        
                        @if(Auth::user()->role === 'admin')

                            <a href=""> <!-- href for the edit page in the dashboard TODO -->
                                <button type="submit">
                                     Edit 
                                </button>
                            </a>
                            <form action="{{ route('resource.destroy', $resource->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit">
                                    🗑️ Delete Resource
                                </button>
                            </form>


                        @elseif(Auth::user()->role === 'manager')
                            <div>
        
                                 <a href="">
                                     <button type="button">
                                          Edit
                                     </button>
                                 </a>

                                <form action="{{ route('resource.toggle', $resource->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')

                                    @if($resource->state == 'available')
                                        <button type="submit">
                                             Set Maintenance
                                        </button>
                                    @else
                                        <button type="submit">
                                             Set Available
                                        </button>
                                    @endif
                                </form>
                            </div>

                        @else
                            <form action="{{ route('reservation.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="resource_id" value="{{ $resource->id }}">
                                <input type="hidden" name="start_time" value="{{ request('start_date') }}">
                                <input type="hidden" name="end_time" value="{{ request('end_date') }}">

                                @if($resource->state == 'maintenance')
                                    <button type="button" disabled>
                                         Under Maintenance
                                    </button>
                                @elseif(request('start_date') && request('end_date'))
                                    <button type="submit">
                                        Reserve Now
                                    </button>
                                @else
                                    <button type="button">
                                        Select Dates Above
                                    </button>
                                @endif
                            </form>
                        @endif

                    @else
                        <a href="{{ route('login') }}">
                            <button>Login to Book</button>
                        </a>
                    @endauth

                </div>
                @endforeach

            </div>
        @endif
    </main>

</body>
</html>