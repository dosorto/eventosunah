<?php

namespace App\Http\Controllers;

use App\Models\EventoStaff;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class StaffAccessController extends Controller
{
    public function __invoke(string $token): RedirectResponse
    {
        $staffMember = EventoStaff::query()
            ->with(['persona.user', 'evento'])
            ->where('staff_access_token', $token)
            ->firstOrFail();

        $linkedUserId = $staffMember->persona?->IdUsuario;

        if (! $linkedUserId) {
            return redirect()->route('staff.wizard', ['token' => $token]);
        }

        if (! Auth::check()) {
            session()->put('url.intended', route('staff.wizard', ['token' => $token]));

            return redirect()->route('login');
        }

        abort_if((int) Auth::id() !== (int) $linkedUserId, 403, 'Este enlace pertenece a otro miembro del staff.');

        return redirect()->route('staff.wizard', ['token' => $token]);
    }
}
