<?php

namespace App\Http\Controllers;

use App\Models\Conferencia;
use Illuminate\Http\RedirectResponse;

class SpeakerAccessController extends Controller
{
    public function __invoke(string $token): RedirectResponse
    {
        Conferencia::query()
            ->where('speaker_access_token', $token)
            ->firstOrFail();

        return redirect()->route('speaker.wizard', ['token' => $token]);
    }
}
