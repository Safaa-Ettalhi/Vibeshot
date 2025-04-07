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

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'sans': ['Inter', 'sans-serif'],
                    },
                }
            }
        }
    </script>
</head>
<body class="font-sans m-0 p-0 overflow-hidden h-screen w-screen">
    <div class="flex w-full h-screen">
        <!-- Formulaire de connexion -->
        <div class="flex-1 p-12 flex flex-col items-center justify-center bg-white">
            <div class="w-full max-w-sm">
                <div class="mb-6 text-center">
                    <img src="{{ asset('images/V.png') }}" alt="Logo" class="mx-auto">
                </div>
                
                <h1 class="text-2xl font-bold mb-2 text-center">Welcome back</h1>
                <p class="text-sm text-gray-500 mb-8 text-center">Please enter your details</p>
                
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    
                    <div class="mb-4">
                        <input id="email" type="email" 
                            class="w-full px-4 py-3 border border-gray-200 rounded-md text-sm focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/25 @error('email') border-red-500 @enderror" 
                            name="email" value="{{ old('email') }}" required autocomplete="email" autofocus 
                            placeholder="Enter your email">
                        
                        @error('email')
                            <span class="text-red-500 text-xs mt-1 block" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <div class="relative">
                            <input id="password" type="password" 
                                class="w-full px-4 py-3 border border-gray-200 rounded-md text-sm focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/25 @error('password') border-red-500 @enderror" 
                                name="password" required autocomplete="current-password" placeholder="Enter password">
                            
                            <svg class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 w-5 h-5 cursor-pointer" 
                                 id="password-toggle"
                                 xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                        </div>
                        
                        @error('password')
                            <span class="text-red-500 text-xs mt-1 block" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        
                        <a href="#" class="text-xs text-gray-500 block text-right mt-2 hover:text-blue-500">
                            forgot password?
                        </a>
                    </div>
                    
                    <button type="submit" class="w-full bg-black hover:bg-gray-800 text-white font-medium py-3 px-6 rounded-md transition duration-200 flex items-center justify-center">
                        Continue
                    </button>
                </form>
            </div>
            
            <div class="absolute bottom-6 text-sm text-gray-500">
                Don't have an account? <a href="{{ route('register') }}" class="text-blue-500 font-medium hover:underline">Sign up</a>
            </div>
        </div>
        
        <!-- Image d'arrière-plan -->
        <div class="flex-1 bg-cover bg-center relative rounded-l-3xl hidden md:flex" style="background-image: url('{{ asset('images/login-bg.jpg') }}')">
            <div class="absolute bottom-2.5 right-2.5 text-white text-sm opacity-80">Vibeshot</div>
        </div>
    </div>

    <script>
        // Script pour afficher/masquer le mot de passe
        document.addEventListener('DOMContentLoaded', function() {
            const passwordToggle = document.getElementById('password-toggle');
            const passwordInput = document.getElementById('password');
            
            passwordToggle.addEventListener('click', function() {
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