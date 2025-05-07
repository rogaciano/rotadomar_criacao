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
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
