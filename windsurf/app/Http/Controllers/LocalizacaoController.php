<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LocalizacaoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = \App\Models\Localizacao::query();

        // Filtros
        if ($request->filled('nome_localizacao')) {
            $query->where('nome_localizacao', 'like', '%' . $request->nome_localizacao . '%');
        }

        if ($request->filled('prazo')) {
            $query->where('prazo', $request->prazo);
        }

        if ($request->filled('ativo')) {
            $query->where('ativo', $request->ativo);
        }
        
        // Incluir excluídos se solicitado
        if ($request->filled('incluir_excluidos')) {
            $query->withTrashed();
        }

        $localizacoes = $query->orderBy('nome_localizacao')->paginate(10);
        
        return view('localizacoes.index', compact('localizacoes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('localizacoes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome_localizacao' => 'required|string|max:255',
            'prazo' => 'nullable|integer|min:0',
            'ativo' => 'sometimes|boolean',
        ]);
        
        // Definir ativo como false se não estiver presente no request
        if (!isset($validated['ativo'])) {
            $validated['ativo'] = false;
        }
        
        $localizacao = \App\Models\Localizacao::create($validated);
        
        return redirect()->route('localizacoes.index')
            ->with('success', 'Localização criada com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $localizacao = \App\Models\Localizacao::withTrashed()->findOrFail($id);
        return view('localizacoes.show', compact('localizacao'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $localizacao = \App\Models\Localizacao::findOrFail($id);
        return view('localizacoes.edit', compact('localizacao'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $localizacao = \App\Models\Localizacao::findOrFail($id);
        
        $validated = $request->validate([
            'nome_localizacao' => 'required|string|max:255',
            'prazo' => 'nullable|integer|min:0',
            'ativo' => 'sometimes|boolean',
        ]);
        
        // Definir ativo como false se não estiver presente no request
        if (!isset($validated['ativo'])) {
            $validated['ativo'] = false;
        }
        
        $localizacao->update($validated);
        
        return redirect()->route('localizacoes.index')
            ->with('success', 'Localização atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
