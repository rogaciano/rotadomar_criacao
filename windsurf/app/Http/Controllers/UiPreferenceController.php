<?php

namespace App\Http\Controllers;

use App\Models\UserFilter;
use Illuminate\Http\Request;

class UiPreferenceController extends Controller
{
    public function setFiltersVisibility(Request $request)
    {
        $user = auth()->user();
        if (!$user) {
            abort(401);
        }

        $validated = $request->validate([
            'page_type' => ['required', 'string', 'in:produtos,movimentacoes'],
            'filters_visible' => ['required', 'boolean'],
        ]);

        UserFilter::saveFilters($user->id, $validated['page_type'] . '_ui', [
            'filters_visible' => (bool) $validated['filters_visible'],
        ]);

        return response()->json(['success' => true]);
    }
}
