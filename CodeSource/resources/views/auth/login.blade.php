<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'VibeShot') }} - Connexion</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
            overflow: hidden;
            height: 100vh;
            width: 100vw;
        }
        
        .login-container {
            display: flex;
            width: 100%;
            height: 100vh;
        }
        
        .login-form {
            flex: 1;
            padding: 3rem 2rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background-color: white;
        }
        
        .login-logo {
            margin-bottom: 1.5rem;
            text-align: center;
        }
        
        .login-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            text-align: center;
        }
        
        .login-subtitle {
            font-size: 0.875rem;
            color: #6b7280;
            margin-bottom: 2rem;
            text-align: center;
        }
        
        .form-container {
            width: 100%;
            max-width: 320px;
        }
        
        .form-group {
            width: 100%;
            margin-bottom: 1rem;
        }
        
        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid #e5e7eb;
            border-radius: 4px;
            font-size: 0.875rem;
            transition: all 0.2s;
        }
        
        .form-control:focus {
            outline: none;
            border-color: #3490dc;
            box-shadow: 0 0 0 2px rgba(52, 144, 220, 0.25);
        }
        
        .form-error {
            color: #ef4444;
            font-size: 0.75rem;
            margin-top: 0.25rem;
        }
        
        .form-link {
            font-size: 0.75rem;
            color: #6b7280;
            text-align: right;
            display: block;
            margin-top: 0.5rem;
            text-decoration: none;
        }
        
        .form-link:hover {
            color: #3490dc;
        }
        
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.75rem 1.5rem;
            border-radius: 4px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            border: none;
            outline: none;
            width: 100%;
        }
        
        .btn-primary {
            background-color: #000;
            color: white;
        }
        
        .btn-primary:hover {
            background-color: #333;
        }
        
        .login-image {
            flex: 1;
            background-size: cover;
            background-position: center;
            border-radius: 40px 0 0 40px;
            position: relative;
            display: flex;
        }
        
        .vibeshot-logo {
            position: absolute;
            bottom: 10px;
            right: 10px;
            color: white;
            font-size: 0.8rem;
            opacity: 0.8;
        }
        
        .login-footer {
            position: absolute;
            bottom: 24px;
            font-size: 0.875rem;
            color: #6b7280;
        }
        
        .login-footer a {
            color: #3490dc;
            text-decoration: none;
            font-weight: 500;
        }
        
        .login-footer a:hover {
            text-decoration: underline;
        }
        
        @media (max-width: 768px) {
            .login-container {
                flex-direction: column;
            }
            
            .login-image {
                display: none;
            }
            
            .login-form {
                width: 100%;
                height: 100vh;
            }
        }
        
        .password-field {
            position: relative;
        }
        
        .password-icon {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            width: 18px;
            height: 18px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-form">
            <div class="form-container">
                <div class="login-logo">
                    <img src="{{ asset('images/V.png') }}" alt="">
                </div>
                
                <h1 class="login-title">Welcome back</h1>
                <p class="login-subtitle">Please enter your details</p>
                
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    
                    <div class="form-group">
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                            name="email" value="{{ old('email') }}" required autocomplete="email" autofocus 
                            placeholder="Enter your email">
                        
                        @error('email')
                            <span class="form-error" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <div class="password-field">
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" 
                                name="password" required autocomplete="current-password" placeholder="Enter password">
                            
                            <svg class="password-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                        </div>
                        
                        @error('password')
                            <span class="form-error" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        
                        <a href="#" class="form-link">forgot password ?</a>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">
                        Continue
                    </button>
                </form>
             
            </div>
            <div class="login-footer">
                Don't have an account? <a href="{{ route('register') }}">Sign up</a>
            </div>
        </div>
        
        <div class="login-image" style="background-image: url('{{ asset('images/login-bg.jpg') }}')">
            <div class="vibeshot-logo">Vibeshot</div>
        </div>
    </div>

    <script>
        // Script pour afficher/masquer le mot de passe
        document.addEventListener('DOMContentLoaded', function() {
            const passwordIcon = document.querySelector('.password-icon');
            const passwordInput = document.getElementById('password');
            
            passwordIcon.addEventListener('click', function() {
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    this.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />';
                } else {
                    passwordInput.type = 'password';
                    this.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />';
                }
            });
        });
    </script>
</body>
</html>