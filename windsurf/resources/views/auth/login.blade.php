<x-guest-layout>
    <!-- Título de boas-vindas -->
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-purple-600 to-blue-500 bg-clip-text text-transparent">
            Bem-vindo!
        </h1>
        <p class="text-gray-600 mt-2">Faça login para continuar</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />
    
    <!-- Mensagem de erro -->
    @if(session('error'))
        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-r-lg animate-pulse">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                {{ session('error') }}
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <!-- Logos das marcas -->
        <div class="bg-gradient-to-br from-purple-50 to-blue-50 rounded-xl shadow-sm overflow-hidden border border-purple-100">
            <div class="p-6">
                <div class="flex justify-center items-center">
                    @if($marca)
                        <div class="text-center transform transition-all duration-300 hover:scale-105">
                            @if($marca->logo_path)
                                <img src="{{ asset('storage/' . $marca->logo_path) }}" alt="{{ $marca->nome_marca }}" class="h-20 w-auto object-contain mx-auto filter drop-shadow-lg">
                            @else
                                <div class="h-20 w-28 flex items-center justify-center bg-white rounded-xl shadow-sm">
                                    <span class="text-base font-bold bg-gradient-to-r from-purple-600 to-blue-500 bg-clip-text text-transparent">{{ $marca->nome_marca }}</span>
                                </div>
                            @endif
                            <p class="mt-3 text-sm font-semibold text-gray-700">{{ $marca->nome_marca }}</p>
                        </div>
                    @else
                        <div class="text-center py-6">
                            <svg class="w-12 h-12 mx-auto text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                            <p class="text-sm text-gray-500">Nenhuma marca disponível</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Login (Email ou Nome) -->
        <div class="space-y-2">
            <x-input-label for="login" :value="__('Email ou Nome')" class="text-gray-700 font-semibold" />
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <input id="login" 
                       class="block w-full pl-10 pr-3 py-3 border-gray-300 focus:border-purple-500 focus:ring-purple-500 rounded-lg shadow-sm transition duration-150 ease-in-out" 
                       type="text" 
                       name="login" 
                       value="{{ old('login') }}" 
                       required 
                       autofocus 
                       autocomplete="username"
                       placeholder="Digite seu email ou nome" />
            </div>
            <x-input-error :messages="$errors->get('login')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="space-y-2">
            <x-input-label for="password" :value="__('Senha')" class="text-gray-700 font-semibold" />
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <input id="password" 
                       class="block w-full pl-10 pr-3 py-3 border-gray-300 focus:border-purple-500 focus:ring-purple-500 rounded-lg shadow-sm transition duration-150 ease-in-out"
                       type="password"
                       name="password"
                       required 
                       autocomplete="current-password"
                       placeholder="Digite sua senha" />
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                <input id="remember_me" 
                       type="checkbox" 
                       class="rounded border-gray-300 text-purple-600 shadow-sm focus:ring-purple-500 transition duration-150 ease-in-out" 
                       name="remember">
                <span class="ms-2 text-sm text-gray-600 group-hover:text-gray-900 transition duration-150">{{ __('Lembrar-me') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-purple-600 hover:text-purple-800 font-medium transition duration-150 ease-in-out" 
                   href="{{ route('password.request') }}">
                    {{ __('Esqueceu sua senha?') }}
                </a>
            @endif
        </div>

        <!-- Botão de Login -->
        <div class="pt-2">
            <button type="submit" 
                    class="w-full flex justify-center items-center px-4 py-3 bg-gradient-to-r from-purple-600 to-blue-500 text-white font-semibold rounded-lg shadow-lg hover:from-purple-700 hover:to-blue-600 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transform transition-all duration-150 hover:scale-105 active:scale-95">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                </svg>
                {{ __('Entrar') }}
            </button>
        </div>
    </form>
</x-guest-layout>
