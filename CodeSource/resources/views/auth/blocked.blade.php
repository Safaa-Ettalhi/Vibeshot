@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-[#0a0a0a]">
    <div class="max-w-md w-full bg-[#111111]/90 rounded-xl border border-gray-800/50 shadow-lg backdrop-blur-sm overflow-hidden p-8">
        <div class="text-center mb-6">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-red-500/20 mb-4">
                <i data-feather="lock" class="w-8 h-8 text-red-500"></i>
            </div>
            <h1 class="text-2xl font-bold text-white mb-2">Compte bloqué</h1>
            <p class="text-gray-400">Votre compte a été bloqué par un administrateur.</p>
        </div>
        
        <div class="bg-red-500/10 border border-red-500/20 rounded-lg p-4 mb-6">
            <div class="flex items-start">
                <i data-feather="alert-circle" class="w-5 h-5 text-red-500 mt-0.5 mr-3 flex-shrink-0"></i>
                <div class="text-sm text-red-200">
                    Si vous pensez qu'il s'agit d'une erreur ou si vous souhaitez contester cette décision, veuillez contacter l'équipe d'assistance.
                </div>
            </div>
        </div>
        
        <div class="space-y-4">
            <div class="bg-[#1a1a1a] rounded-lg p-4">
                <h3 class="text-sm font-medium text-gray-300 mb-2">Raisons possibles du blocage:</h3>
                <ul class="text-sm text-gray-400 space-y-1 list-disc list-inside">
                    <li>Violation des conditions d'utilisation</li>
                    <li>Comportement inapproprié envers d'autres utilisateurs</li>
                    <li>Publication de contenu interdit</li>
                    <li>Activités suspectes détectées sur votre compte</li>
                </ul>
            </div>
            
            <div class="bg-[#1a1a1a] rounded-lg p-4">
                <h3 class="text-sm font-medium text-gray-300 mb-2">Contact:</h3>
                <p class="text-sm text-gray-400">
                    Pour toute question ou demande de déblocage, veuillez nous contacter à 
                    <a href="mailto:support@vibeshot.com" class="text-blue-400 hover:text-blue-300">support@vibeshot.com</a>
                </p>
            </div>
        </div>
        
        <div class="mt-8 text-center">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-[#222222] hover:bg-[#333333] text-gray-300 hover:text-white rounded-lg transition-colors">
                    <i data-feather="log-out" class="w-4 h-4 mr-2"></i>
                    Retour à l'accueil
                </button>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof feather !== 'undefined') {
        feather.replace({
            'stroke-width': 1.5
        });
    }
});
</script>
@endsection