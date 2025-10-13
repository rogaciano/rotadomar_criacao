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

        // Filtro de ativo - se não estiver preenchido, assume como ativos (1)
        if ($request->has('ativo')) {
            if ($request->ativo === 'todos') {
                // Não aplica filtro para mostrar todos os registros
            } else if ($request->ativo !== '') {
                $query->where('ativo', $request->ativo);
            }
        } else {
            // Se o parâmetro não estiver no request (primeira visita), mostra apenas ativos
            $query->where('ativo', 1);
        }
        
        // Incluir excluídos se solicitado
        if ($request->filled('incluir_excluidos')) {
            $query->withTrashed();
        }

        $localizacoes = $query->orderBy('nome_localizacao')->paginate(10)->withQueryString();
        
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
            'capacidade' => 'nullable|integer|min:0',
            'ativo' => 'sometimes|boolean',
        ]);
        
        // Definir ativo como false se não estiver presente no request
        if (!isset($validated['ativo'])) {
            $validated['ativo'] = false;
        }
        
        try {
            $localizacao = \App\Models\Localizacao::create($validated);
            
            return redirect()->route('localizacoes.index')
                ->with('success', 'Localização criada com sucesso!');
        } catch (\Illuminate\Database\QueryException $e) {
            // Verificar se é erro de duplicidade (código 23000 - Integrity constraint violation)
            if ($e->getCode() === '23000') {
                // Verificar se é especificamente um erro de nome_localizacao único
                if (str_contains($e->getMessage(), 'localizacoes_nome_localizacao_unique')) {
                    return redirect()->back()
                        ->withInput()
                        ->with('error', 'Já existe uma localização com este nome. Por favor, escolha outro nome.');
                }
            }
            
            // Para outros erros de banco de dados
            return redirect()->back()
                ->withInput()
                ->with('error', 'Ocorreu um erro ao criar a localização: ' . $e->getMessage());
        }
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
            'capacidade' => 'nullable|integer|min:0',
            'ativo' => 'sometimes|boolean',
        ]);
        
        // Definir ativo como false se não estiver presente no request
        if (!isset($validated['ativo'])) {
            $validated['ativo'] = false;
        }
        
        try {
            $localizacao->update($validated);
            
            // Redirecionar para a mesma página que estava antes
            $page = $request->input('current_page') ? ['page' => $request->input('current_page')] : [];
            
            return redirect()->route('localizacoes.index', $page)
                ->with('success', 'Localização atualizada com sucesso!');
        } catch (\Illuminate\Database\QueryException $e) {
            // Verificar se é erro de duplicidade (código 23000 - Integrity constraint violation)
            if ($e->getCode() === '23000') {
                // Verificar se é especificamente um erro de nome_localizacao único
                if (str_contains($e->getMessage(), 'localizacoes_nome_localizacao_unique')) {
                    return redirect()->back()
                        ->withInput()
                        ->with('error', 'Já existe uma localização com este nome. Por favor, escolha outro nome.');
                }
            }
            
            // Para outros erros de banco de dados
            return redirect()->back()
                ->withInput()
                ->with('error', 'Ocorreu um erro ao atualizar a localização: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $localizacao = \App\Models\Localizacao::findOrFail($id);
        
        // Verifica se existem movimentações associadas a esta localização
        $movimentacoesCount = $localizacao->movimentacoes()->count();
        
        if ($movimentacoesCount > 0) {
            return redirect()->back()
                ->with('error', "Não é possível excluir esta localização pois existem $movimentacoesCount movimentação(ões) associadas a ela.")
                ->with('error_type', 'has_relations');
        }
        
        try {
            $localizacao->delete();
            return redirect()->route('localizacoes.index')
                ->with('success', 'Localização excluída com sucesso!');
        } catch (\Exception $e) {
            // Captura exceções do banco de dados e outras
            return redirect()->back()
                ->with('error', 'Ocorreu um erro ao tentar excluir a localização: ' . $e->getMessage())
                ->with('error_type', 'database_error');
        }
    }
}
