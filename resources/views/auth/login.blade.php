<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Data Center</title>
</head>
<body>

    <header>
        <nav>
            <a href="/">Back to Home</a>
        </nav>
    </header>

    <hr>

    <main>
        <h2>Login</h2>
        
        <form action="{{ route('login') }}" method="POST">
            @csrf <div>
                <label for="email">Email Address:</label><br>
                <input type="email" id="email" name="email" required autofocus>
                @error('email') 
                    <div style="color:red;">{{ $message }}</div> 
                @enderror
            </div>
            <br>

            <div>
                <label for="password">Password:</label><br>
                <input type="password" id="password" name="password" required>
            </div>
            <br>

            <button type="submit">Login</button>
        </form>

        <p>Don't have an account? <a href="{{ route('register') }}">Sign up here</a>.</p>
    </main>

</body>
</html>