<?php

namespace App\Http\Responses;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;

class RegisterResponse implements RegisterResponseContract
{
    public function toResponse($request): RedirectResponse
    {
        if (Auth::check()) {
            Auth::guard()->logout();
        }

        if ($request instanceof Request) {
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        return redirect()
            ->route('login')
            ->with('status', 'Tu cuenta fue creada correctamente. Ahora puedes iniciar sesion con tus credenciales.');
    }
}
