<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MarcaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Marca::query();

        // Filtros
        if ($request->filled('marca')) {
            $query->where('nome_marca', 'like', '%' . $request->marca . '%');
        }

        if ($request->filled('data_cadastro')) {
            $query->whereDate('data_cadastro', $request->data_cadastro);
        }

        if ($request->filled('ativo')) {
            $query->where('ativo', $request->ativo);
        }

        $marcas = $query->orderBy('nome_marca')->paginate(10);
        
        return view('marcas.index', compact('marcas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('marcas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nome_marca' => 'required|string|max:255|unique:marcas,nome_marca',
            'ativo' => 'boolean',
            'data_cadastro' => 'required|date',
        ]);

        if ($validator->fails()) {
            return redirect()->route('marcas.create')
                ->withErrors($validator)
                ->withInput();
        }

        Marca::create($request->all());

        return redirect()->route('marcas.index')
            ->with('success', 'Marca criada com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Marca $marca)
    {
        return view('marcas.show', compact('marca'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Marca $marca)
    {
        return view('marcas.edit', compact('marca'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Marca $marca)
    {
        $validator = Validator::make($request->all(), [
            'nome_marca' => 'required|string|max:255|unique:marcas,nome_marca,' . $marca->id,
            'ativo' => 'boolean',
            'data_cadastro' => 'required|date',
        ]);

        if ($validator->fails()) {
            return redirect()->route('marcas.edit', $marca)
                ->withErrors($validator)
                ->withInput();
        }

        $marca->update($request->all());

        return redirect()->route('marcas.index')
            ->with('success', 'Marca atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Marca $marca)
    {
        $marca->delete();

        return redirect()->route('marcas.index')
            ->with('success', 'Marca excluída com sucesso!');
    }
}
