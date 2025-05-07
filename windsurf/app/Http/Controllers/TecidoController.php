<?php

namespace App\Http\Controllers;

use App\Models\Tecido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TecidoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Tecido::query();

        // Filtros
        if ($request->filled('descricao')) {
            $query->where('descricao', 'like', '%' . $request->descricao . '%');
        }

        if ($request->filled('data_cadastro')) {
            $query->whereDate('data_cadastro', $request->data_cadastro);
        }

        if ($request->filled('ativo')) {
            $query->where('ativo', $request->ativo);
        }

        // Ordenação
        $orderBy = $request->input('order_by', 'descricao');
        $orderDirection = $request->input('order_direction', 'asc');
        $query->orderBy($orderBy, $orderDirection);

        $tecidos = $query->paginate(10);

        return view('tecidos.index', compact('tecidos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tecidos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'descricao' => 'required|string|max:255',
            'ativo' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->route('tecidos.create')
                ->withErrors($validator)
                ->withInput();
        }

        Tecido::create([
            'descricao' => $request->descricao,
            'referencia' => $request->referencia,
            'data_cadastro' => now(),
            'ativo' => $request->has('ativo'),
        ]);

        return redirect()->route('tecidos.index')
            ->with('success', 'Tecido criado com sucesso.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $tecido = Tecido::withTrashed()->findOrFail($id);
        return view('tecidos.show', compact('tecido'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $tecido = Tecido::withTrashed()->findOrFail($id);
        return view('tecidos.edit', compact('tecido'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'descricao' => 'required|string|max:255',
            'ativo' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->route('tecidos.edit', $id)
                ->withErrors($validator)
                ->withInput();
        }

        $tecido = Tecido::withTrashed()->findOrFail($id);
        $tecido->update([
            'descricao' => $request->descricao,
            'referencia' => $request->referencia,
            'ativo' => $request->has('ativo'),
        ]);

        return redirect()->route('tecidos.index')
            ->with('success', 'Tecido atualizado com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $tecido = Tecido::withTrashed()->findOrFail($id);
        $tecido->delete(); // Soft delete

        return redirect()->route('tecidos.index')
            ->with('success', 'Tecido removido com sucesso.');
    }
}
