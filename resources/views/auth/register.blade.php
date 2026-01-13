<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up - Data Center</title>
</head>
<body>

    <header>
        <nav>
            <a href="/">Back to Home</a>
        </nav>
    </header>

    <hr>

    <main>
        <h2>Create an Account</h2>
        
        <form action="{{ route('register') }}" method="POST">
            @csrf <div>
                <label for="name">Full Name:</label><br>
                <input type="text" id="name" name="name" required value="{{ old('name') }}">
                @error('name') 
                    <div style="color:red;">{{ $message }}</div> 
                @enderror
            </div>
            <br>

            <div>
                <label for="email">Email Address:</label><br>
                <input type="email" id="email" name="email" required value="{{ old('email') }}">
                @error('email') 
                    <div style="color:red;">{{ $message }}</div> 
                @enderror
            </div>
            <br>

            <div>
                <label for="password">Password:</label><br>
                <input type="password" id="password" name="password" required>
                @error('password') 
                    <div style="color:red;">{{ $message }}</div> 
                @enderror
            </div>
            <br>

            <div>
                <label for="password_confirmation">Confirm Password:</label><br>
                <input type="password" id="password_confirmation" name="password_confirmation" required>
            </div>
            <br>

            <button type="submit">Register</button>
        </form>

        <p>Already have an account? <a href="{{ route('login') }}">Login here</a>.</p>
    </main>

</body>
</html>