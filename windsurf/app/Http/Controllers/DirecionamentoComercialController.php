<?php

namespace App\Http\Controllers;

use App\Models\DirecionamentoComercial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DirecionamentoComercialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = DirecionamentoComercial::query();

        // Filtro por descrição
        if ($request->filled('descricao')) {
            $query->where('descricao', 'like', '%' . $request->descricao . '%');
        }

        // Filtro por status
        if ($request->filled('ativo')) {
            $query->where('ativo', $request->ativo);
        }

        // Incluir excluídos
        if ($request->filled('incluir_excluidos')) {
            $query->withTrashed();
        }

        $direcionamentos = $query->orderBy('descricao')->paginate(10);

        return view('direcionamentos-comerciais.index', compact('direcionamentos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('direcionamentos-comerciais.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'descricao' => 'required|string|max:100|unique:direcionamentos_comerciais,descricao',
            'ativo' => 'boolean'
        ], [
            'descricao.required' => 'A descrição é obrigatória.',
            'descricao.max' => 'A descrição não pode ter mais de 100 caracteres.',
            'descricao.unique' => 'Já existe um direcionamento comercial com esta descrição.'
        ]);

        if ($validator->fails()) {
            return redirect()->route('direcionamentos-comerciais.create')
                ->withErrors($validator)
                ->withInput();
        }

        DirecionamentoComercial::create([
            'descricao' => $request->descricao,
            'ativo' => $request->has('ativo')
        ]);

        return redirect()->route('direcionamentos-comerciais.index')
            ->with('success', 'Direcionamento comercial criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $direcionamento = DirecionamentoComercial::withTrashed()->findOrFail($id);
        return view('direcionamentos-comerciais.show', compact('direcionamento'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $direcionamento = DirecionamentoComercial::findOrFail($id);
        return view('direcionamentos-comerciais.edit', compact('direcionamento'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $direcionamento = DirecionamentoComercial::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'descricao' => 'required|string|max:100|unique:direcionamentos_comerciais,descricao,' . $id,
            'ativo' => 'boolean'
        ], [
            'descricao.required' => 'A descrição é obrigatória.',
            'descricao.max' => 'A descrição não pode ter mais de 100 caracteres.',
            'descricao.unique' => 'Já existe um direcionamento comercial com esta descrição.'
        ]);

        if ($validator->fails()) {
            return redirect()->route('direcionamentos-comerciais.edit', $direcionamento)
                ->withErrors($validator)
                ->withInput();
        }

        $direcionamento->update([
            'descricao' => $request->descricao,
            'ativo' => $request->has('ativo')
        ]);

        return redirect()->route('direcionamentos-comerciais.index')
            ->with('success', 'Direcionamento comercial atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $direcionamento = DirecionamentoComercial::findOrFail($id);

        // Verificar se há produtos vinculados
        if ($direcionamento->produtos()->count() > 0) {
            return redirect()->route('direcionamentos-comerciais.index')
                ->with('error', 'Não é possível excluir este direcionamento comercial pois existem produtos vinculados.');
        }

        $direcionamento->delete();

        return redirect()->route('direcionamentos-comerciais.index')
            ->with('success', 'Direcionamento comercial excluído com sucesso!');
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore(string $id)
    {
        $direcionamento = DirecionamentoComercial::withTrashed()->findOrFail($id);
        $direcionamento->restore();

        return redirect()->route('direcionamentos-comerciais.index')
            ->with('success', 'Direcionamento comercial restaurado com sucesso!');
    }
}
