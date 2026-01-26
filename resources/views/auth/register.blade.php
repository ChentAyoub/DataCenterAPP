<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Data Center</title>
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="auth-body">

    <main class="auth-container">
        
        <div class="auth-card" style="flex-direction: row-reverse;">
         <div class="left-panel" style="padding: 0 60px;">
            <h2 class="auth-title">Join Us</h2>
            
            <form action="{{ route('register') }}" method="POST" class="auth-form">
                @csrf 
                
                <div class="form-group">
                    <label for="name" class="form-label">Full Name</label>
                    <input type="text" id="name" name="name" class="form-control" required value="{{ old('name') }}" placeholder="Full Name">
                    @error('name') 
                        <div class="error-message">{{ $message }}</div> 
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" id="email" name="email" class="form-control" required value="{{ old('email') }}" placeholder="example@datacenter.com">
                    @error('email') 
                        <div class="error-message">{{ $message }}</div> 
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Password</label>
                    <div class="password-wrapper">
                        <input type="password" id="password" name="password" class="input-field" placeholder="••••••••" required>
                        <i class="fa-solid fa-eye toggle-password" onclick="togglePassword('password', this)"></i>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Confirm Password</label>
                    <div class="password-wrapper">
                        <input type="password" id="password_confirmation" name="password_confirmation" class="input-field" placeholder="••••••••" required>
                        <i class="fa-solid fa-eye toggle-password" onclick="togglePassword('password_confirmation', this)"></i>
                    </div>
                </div>
                <div class="form-check">
                    <input type="checkbox" id="terms" name="terms" required>
                    <label for="terms">
                        I agree to the <a href="#">Privacy Policy</a> and <a href="#">Terms of Service</a>.
                    </label>
                </div>
                <button type="submit" class="btn-primary btn-full">Register</button>
            

            <div class="divider">
                    <span>Or sign up with</span>
             </div>

            <a href="#" class="btn-social">
                <i class="fa-brands fa-google" style="color: black; font-size: 16px;"></i>
                Continue with Google
            </a>
            <a href="#" class="btn-social">
                <i class="fa-brands fa-github" style="color: black; font-size: 18px;"></i>
                Continue with github
            </a>
            <a href="#" class="btn-social" onclick="alert('demo only')">
                <i class="fa-brands fa-apple" style="color: black; font-size: 18px;"></i>
                Continue with Apple
            </a>

            <div class="auth-footer">
                <p>Already have an account? <a href="{{ route('login') }}" class="link-primary">Login here</a>.</p>
            </div>
            </form>
         </div>

         <div class="right-panel">
             <div class="image-panel">
                <a href="/" class="link-back">⬅ Back to Home</a>
                 <div class="carousel-slide active" style="background-image: url('{{ asset('images/rege1.png') }}');">
                 </div>
                 <div class="carousel-slide" style="background-image: url('{{ asset('images/rege2.png') }}');">
                 </div>
                 <div class="carousel-slide" style="background-image: url('{{ asset('images/rege3.png') }}');">
                 </div>
                 <div class="carousel-indicators">
                     <span class="dot active"></span>
                     <span class="dot"></span>
                     <span class="dot"></span>
                 </div>
             </div>
         </div>
        </div>
    </main>
<script src="{{ asset('js/auth.js') }}"></script>
</body>
</html>