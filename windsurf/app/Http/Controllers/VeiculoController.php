<?php

namespace App\Http\Controllers;

use App\Models\Veiculo;
use App\Http\Requests\StoreVeiculoRequest;
use App\Http\Requests\UpdateVeiculoRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VeiculoController extends Controller
{
    public function index(Request $request): View
    {
        $query = Veiculo::query()->orderBy('placa');

        if ($request->filled('busca')) {
            $busca = $request->get('busca');
            $query->where(function ($q) use ($busca) {
                $q->where('placa', 'like', "%{$busca}%")
                  ->orWhere('descricao', 'like', "%{$busca}%");
            });
        }

        if ($request->filled('ativo')) {
            $query->where('ativo', $request->get('ativo') === '1');
        }

        $veiculos = $query->paginate(15)->appends($request->query());

        return view('veiculos.index', compact('veiculos'));
    }

    public function create(): View
    {
        return view('veiculos.create');
    }

    public function store(StoreVeiculoRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $validated['ativo'] = $request->has('ativo');

        Veiculo::create($validated);

        return redirect()->route('veiculos.index')->with('success', 'Veículo cadastrado com sucesso!');
    }

    public function edit(Veiculo $veiculo): View
    {
        return view('veiculos.edit', compact('veiculo'));
    }

    public function update(UpdateVeiculoRequest $request, Veiculo $veiculo): RedirectResponse
    {
        $validated = $request->validated();

        $validated['ativo'] = $request->has('ativo');

        $veiculo->update($validated);

        return redirect()->route('veiculos.index')->with('success', 'Veículo atualizado com sucesso!');
    }

    public function destroy(Veiculo $veiculo): RedirectResponse
    {
        if ($veiculo->coletasLogisticas()->ativas()->exists()) {
            return redirect()->route('veiculos.index')->with('error', 'Não é possível excluir veículo com coletas ativas.');
        }

        $veiculo->delete();

        return redirect()->route('veiculos.index')->with('success', 'Veículo excluído com sucesso!');
    }
}
