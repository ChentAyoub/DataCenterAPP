<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Data Center</title>
</head>
<body class="auth-body">

    <header class="auth-header">
        <nav class="auth-nav">
            <a href="/" class="link-back">⬅ Back to Home</a>
        </nav>
    </header>

    <main class="auth-container">
        
        <div class="auth-card">
            <h2 class="auth-title">Create an Account</h2>
            
            <form action="{{ route('register') }}" method="POST" class="auth-form">
                @csrf 
                
                <div class="form-group">
                    <label for="name" class="form-label">Full Name</label>
                    <input type="text" id="name" name="name" class="form-control" required value="{{ old('name') }}" placeholder="John Doe">
                    @error('name') 
                        <div class="error-message">{{ $message }}</div> 
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" id="email" name="email" class="form-control" required value="{{ old('email') }}" placeholder="student@university.com">
                    @error('email') 
                        <div class="error-message">{{ $message }}</div> 
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" id="password" name="password" class="form-control" required placeholder="••••••••">
                    @error('password') 
                        <div class="error-message">{{ $message }}</div> 
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required placeholder="••••••••">
                </div>

                <button type="submit" class="btn-primary btn-full">Register</button>
            </form>

            <div class="divider">
                <div class="divider">
                    <span>Or sign up with</span>
                </div>
                    <a href="#" class="btn-social">
                        <img src="{{ asset('images/google-logo.png') }}" alt="Google Logo" class="google-logo">
                        Continue with Google
                    </a>

                    <a href="#" class="btn-social">
                        <img src="{{ asset('images/apple-logo.png') }}" alt="Apple Logo" class="apple-logo">
                        Continue with Apple
                    </a>
                </div>

            <div class="auth-footer">
                <p>Already have an account? <a href="{{ route('login') }}" class="link-primary">Login here</a>.</p>
            </div>
        </div>
    </main>

</body>
</html>