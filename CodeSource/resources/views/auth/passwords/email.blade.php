<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Password Reset</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
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
        <div class="flex-1 p-12 flex flex-col items-center justify-center bg-white">
            <div class="w-full max-w-sm">
                <div class="mb-6 text-center">
                    <img src="{{ asset('images/V.png') }}" alt="Logo" class="mx-auto w-10 h-auto">
                </div>
                
                <h1 class="text-2xl font-bold mb-2 text-center">Reset Password</h1>
                <p class="text-sm text-gray-500 mb-8 text-center">Enter your email address to receive a reset link</p>
                
                @if (session('status'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
                        <span class="block sm:inline">{{ session('status') }}</span>
                    </div>
                @endif
                
                <form method="POST" action="{{ route('password.email') }}">
                    @csrf
                    
                    <div class="mb-6">
                        <input id="email" type="email" 
                            class="w-full px-4 py-3 border border-gray-200 rounded-md text-sm focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/25 @error('email') border-red-500 @enderror" 
                            name="email" value="{{ old('email') }}" required autocomplete="email" autofocus 
                            placeholder="Email address">
                        
                        @error('email')
                            <span class="text-red-500 text-xs mt-1 block" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    
                    <button type="submit" class="w-full bg-black hover:bg-gray-800 text-white font-medium py-3 px-6 rounded-md transition duration-200 flex items-center justify-center">
                        Send Reset Link
                    </button>
                </form>
            </div>
            
            <div class="absolute bottom-6 text-sm text-gray-500">
                <a href="{{ route('login') }}" class="text-blue-500 font-medium hover:underline">
                    <span class="inline-flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Back to Login
                    </span>
                </a>
            </div>
        </div>
        
      
        <div class="flex-1 bg-cover bg-center relative rounded-l-3xl hidden md:flex" 
             style="background-image: url('{{ asset('images/login-bg.jpg') }}')">
            <div class="absolute bottom-2.5 right-2.5 text-white text-sm opacity-80">Vibeshot</div>
        </div>
    </div>
</body>
</html>