<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - DataCenter</title>
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">  
</head>
<body class="auth-body">

    <header class="auth-header">
        <nav class="auth-nav">
            <a href="/" class="link-back">⬅ Back to Home</a>
        </nav>
    </header>

    <main class="auth-container">
        
        <div class="auth-card">
            
            <div class="left-panel">
                <h2 class="auth-title">Log In</h2>
                
                <form action="{{ route('login') }}" method="POST" class="auth-form">
                    @csrf 
                    
                    <div class="form-group">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" id="email" name="email" class="input-field" required autofocus placeholder="student@university.com" value="{{ old('email') }}">
                        @error('email') 
                            <div class="error-message">{{ $message }}</div> 
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="passowrd" class="form-label">Password</label>
                        <div class="password-wrapper">
                            <input type="password" id="password" name="password" class="input-field" required placeholder="••••••••">
                            <i class="fa-solid fa-eye toggle-password" onclick="togglePassword('password', this)"></i>
                        </div>
                        @error('password')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-actions">
                        <label class="remember-me">
                            <input type="checkbox" name="remember"> 
                            Remember me
                        </label>
                        
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="forgot-password">Forgot Password?</a>
                        @endif
                    </div>

                    <button type="submit" class="btn-primary btn-full">Login</button>
                    
                    <div class="divider">
                        <span>Or sign in with</span>
                    </div>

                    <a href="#" class="btn-social">
                        <i class="fa-brands fa-google icon-google"></i>
                        Continue with Google
                    </a>
                    
                    <a href="#" class="btn-social" onclick="alert('Disabled for demo.'); return false;">
                        <i class="fa-brands fa-apple icon-apple"></i>
                        Continue with Apple
                    </a>

                    <div class="auth-footer">
                        <p>Don't have an account? <a href="{{ route('register') }}" class="link-primary">Sign up here</a>.</p>
                    </div>

                </form>
            </div>

            <div class="right-panel">
                <div class="image-panel">
                     <div class="carousel-slide active" style="background-image: url('{{ asset('images/log1.png') }}'); opacity: 1;">
                     </div>
                </div>
            </div>

        </div>
    </main>

    <script src="{{ asset('js/auth.js') }}"></script>
</body>
</html>