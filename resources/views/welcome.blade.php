<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>DataCenter Home</title>
</head>
<body>

    <header>
        <nav>
            <h1>DataCenter App</h1>
            <ul>
                @auth
                    <li>Welcome, {{ Auth::user()->name }}</li>
                    <li><a href="/dashboard">Go to Dashboard</a></li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit">Logout</button>
                        </form>
                    </li>
                @else
                    <li><a href="{{ route('login') }}">Login</a></li>
                    <li><a href="{{ route('register') }}">Sign Up</a></li>
                @endauth
            </ul>
        </nav>
    </header>

    <hr> <main>
        <h2>Available Resources</h2>
        <p>Browse our Data Center equipment below.</p>

        <table border="1" cellpadding="10" cellspacing="0">
            <thead>
                <tr>
                    <th>Type</th>
                    <th>Name</th>
                    <th>State</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($resources as $resource)
                <tr>
                    <td>{{ $resource->category->name }}</td>
                    
                    <td><strong>{{ $resource->name }}</strong></td>
                    
                    <td>{{ $resource->state }}</td>
                    
                    <td>
                        @auth
                            <a href="#">Reserve Now</a>
                        @else
                            <a href="{{ route('login') }}">Login to Book</a>
                        @endauth
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </main>

    <hr>
    
    <footer>
        <p>&copy; 2026 DataCenter App</p>
    </footer>

</body>
</html>