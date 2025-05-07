<?php

namespace App\Http\Controllers;

use App\Models\Estilista;
use App\Models\Marca;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EstilistaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Estilista::with('marca');

        // Filtros
        if ($request->filled('nome_estilista')) {
            $query->where('nome_estilista', 'like', '%' . $request->nome_estilista . '%');
        }
        
        if ($request->filled('marca')) {
            $query->whereHas('marca', function($q) use ($request) {
                $q->where('nome_marca', 'like', '%' . $request->marca . '%');
            });
        }

        if ($request->filled('data_cadastro')) {
            $query->whereDate('data_cadastro', $request->data_cadastro);
        }

        if ($request->filled('ativo')) {
            $query->where('ativo', $request->ativo);
        }

        // Ordenação
        $orderBy = $request->input('order_by', 'nome_estilista');
        $orderDirection = $request->input('order_direction', 'asc');
        $query->orderBy($orderBy, $orderDirection);

        $estilistas = $query->paginate(10);

        return view('estilistas.index', compact('estilistas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $marcas = Marca::orderBy('nome_marca')->get();
        return view('estilistas.create', compact('marcas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nome_estilista' => 'required|string|max:255',
            'marca_id' => 'required|exists:marcas,id',
            'suporte_marca' => 'nullable|string|max:255',
            'ativo' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->route('estilistas.create')
                ->withErrors($validator)
                ->withInput();
        }

        Estilista::create([
            'nome_estilista' => $request->nome_estilista,
            'marca_id' => $request->marca_id,
            'suporte_marca' => $request->suporte_marca,
            'ativo' => $request->has('ativo'),
        ]);

        return redirect()->route('estilistas.index')
            ->with('success', 'Estilista criado com sucesso.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $estilista = Estilista::withTrashed()->with('marca')->findOrFail($id);
        return view('estilistas.show', compact('estilista'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $estilista = Estilista::withTrashed()->findOrFail($id);
        $marcas = Marca::orderBy('nome_marca')->get();
        return view('estilistas.edit', compact('estilista', 'marcas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'nome_estilista' => 'required|string|max:255',
            'marca_id' => 'required|exists:marcas,id',
            'suporte_marca' => 'nullable|string|max:255',
            'ativo' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->route('estilistas.edit', $id)
                ->withErrors($validator)
                ->withInput();
        }

        $estilista = Estilista::withTrashed()->findOrFail($id);
        $estilista->update([
            'nome_estilista' => $request->nome_estilista,
            'marca_id' => $request->marca_id,
            'suporte_marca' => $request->suporte_marca,
            'ativo' => $request->has('ativo'),
        ]);

        return redirect()->route('estilistas.index')
            ->with('success', 'Estilista atualizado com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $estilista = Estilista::withTrashed()->findOrFail($id);
        $estilista->delete(); // Soft delete

        return redirect()->route('estilistas.index')
            ->with('success', 'Estilista removido com sucesso.');
    }
}
