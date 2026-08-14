<?php

namespace App\Http\Controllers\Login;

use App\Http\Controllers\Controller;
use App\Models\Tipoperfil;
use App\Services\ProfileDirectoryLookupService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RegistrarUsarioController extends Controller
{
    public function lookupProfile(Request $request, ProfileDirectoryLookupService $lookupService): JsonResponse
    {
        $validated = $request->validate([
            'tipoperfil_id' => ['required', 'exists:tipoperfils,id'],
            'lookup_identifier' => ['required', 'string', 'max:100'],
        ]);

        $profile = Tipoperfil::query()
            ->with(['apiIntegrations' => fn ($query) => $query->where('is_active', true)->latest('id')])
            ->findOrFail((int) $validated['tipoperfil_id']);

        if ($profile->apiIntegrations->isEmpty()) {
            return response()->json([
                'success' => false,
                'manual' => true,
                'message' => 'Este tipo de perfil no tiene una API activa configurada. Puedes completar los datos manualmente.',
                'data' => [],
            ]);
        }

        $result = $lookupService->lookup($profile, trim($validated['lookup_identifier']));

        return response()->json([
            'success' => (bool) ($result['success'] ?? false),
            'manual' => false,
            'message' => $result['message'] ?? '',
            'data' => $result['data'] ?? [],
        ], ($result['success'] ?? false) ? 200 : 422);
    }
}
