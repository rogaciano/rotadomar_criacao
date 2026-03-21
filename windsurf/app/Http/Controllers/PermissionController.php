<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Http\Requests\StorePermissionRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Permission::query();

        // Apply search filter if provided
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('display_name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $permissions = $query->orderBy('name')->paginate(10);

        return view('permissions.index', compact('permissions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('permissions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePermissionRequest $request)
    {
        $validated = $request->validated();

        Permission::create($validated);

        return redirect()->route('permissions.index')
            ->with('success', 'Permissão criada com sucesso.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Permission $permission)
    {
        return view('permissions.show', compact('permission'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Permission $permission)
    {
        return view('permissions.edit', compact('permission'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StorePermissionRequest $request, Permission $permission)
    {
        $validated = $request->validated();

        $permission->update($validated);

        return redirect()->route('permissions.index')
            ->with('success', 'Permissão atualizada com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Permission $permission)
    {
        $permission->delete();

        return redirect()->route('permissions.index')
            ->with('success', 'Permissão excluída com sucesso.');
    }

    /**
     * Restore a soft-deleted permission.
     */
    public function restore($id)
    {
        $permission = Permission::withTrashed()->findOrFail($id);
        $permission->restore();

        return redirect()->route('permissions.index')
            ->with('success', 'Permissão restaurada com sucesso.');
    }

    /**
     * Permanently delete a permission.
     */
    public function forceDelete($id)
    {
        $permission = Permission::withTrashed()->findOrFail($id);
        $permission->forceDelete();

        return redirect()->route('permissions.index')
            ->with('success', 'Permissão excluída permanentemente.');
    }
}
