<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

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

        // Filtro de data_cadastro removido

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
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->route('marcas.create')
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->all();
        
        // Upload da imagem de logo, se fornecida
        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $logoName = time() . '_' . $logo->getClientOriginalName();
            $logoPath = $logo->storeAs('logos', $logoName, 'public');
            $data['logo_path'] = $logoPath;
        }
        
        Marca::create($data);

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
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->route('marcas.edit', $marca)
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->all();
        
        // Upload da nova imagem de logo, se fornecida
        if ($request->hasFile('logo')) {
            // Remover logo anterior, se existir
            if ($marca->logo_path) {
                Storage::disk('public')->delete($marca->logo_path);
            }
            
            $logo = $request->file('logo');
            $logoName = time() . '_' . $logo->getClientOriginalName();
            $logoPath = $logo->storeAs('logos', $logoName, 'public');
            $data['logo_path'] = $logoPath;
        }
        
        $marca->update($data);

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
