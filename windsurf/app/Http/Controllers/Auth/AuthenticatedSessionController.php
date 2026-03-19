<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        // Busca marcas ativas e que tenham logo
        $todasMarcas = \App\Models\Marca::where('ativo', true)->whereNotNull('logo_path')->get();

        // Seleciona apenas uma marca aleatória
        if ($todasMarcas->count() > 0) {
            $marca = $todasMarcas->random(1)->first();
        } else {
            $marca = null;
        }

        return view('auth.login', [
            'marca' => $marca
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Registrar a atividade de login
        activity('access')
            ->causedBy(Auth::user())
            ->withProperties([
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'subject_type' => 'login'
            ])
            ->event('login')
            ->log('Access system');

        // Se o usuário só tem permissão de logística, redireciona direto para logística
        $user = Auth::user();
        if (!$user->isAdmin() && $user->hasPermission('logistica') && !$user->hasPermission('produtos')) {
            return redirect()->intended(route('logistica-coleta.index', absolute: false));
        }

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $user = Auth::user();

        if ($user) {
            // Registrar a atividade de logout antes de deslogar o usuário
            activity('access')
                ->causedBy($user)
                ->withProperties([
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'subject_type' => 'login'
                ])
                ->event('logout')
                ->log('Access system');
        }

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
