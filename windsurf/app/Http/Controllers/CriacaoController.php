<?php

namespace App\Http\Controllers;

use App\Http\Requests\CriacaoRequest;
use App\Models\DirecionamentoComercial;
use App\Models\Estilista;
use App\Models\EtapaProducao;
use App\Models\GrupoProduto;
use App\Models\Localizacao;
use App\Models\Marca;
use App\Models\Produto;
use App\Models\ProdutoAnexo;
use App\Models\Status;
use Database\Seeders\StatusSeeder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CriacaoController extends Controller
{
    public function index(Request $request): View
    {
        $query = Produto::with(['marca', 'estilista', 'grupoProduto', 'status', 'direcionamentoComercial', 'etapaProducao'])
            ->emCriacao();

        if ($request->filled('estilista_id')) {
            $query->where('estilista_id', $request->integer('estilista_id'));
        }

        if ($request->filled('marca_id')) {
            $query->where('marca_id', $request->integer('marca_id'));
        }

        if ($request->filled('direcionamento_comercial_id')) {
            $query->where('direcionamento_comercial_id', $request->integer('direcionamento_comercial_id'));
        }

        if ($request->filled('status_id')) {
            $query->where('status_id', $request->integer('status_id'));
        }

        if ($request->filled('referencia')) {
            $query->where('referencia', 'like', '%' . trim((string) $request->referencia) . '%');
        }

        if ($request->filled('data_entrada_de')) {
            $query->whereDate('data_entrada_processo', '>=', $request->date('data_entrada_de'));
        }

        if ($request->filled('data_entrada_ate')) {
            $query->whereDate('data_entrada_processo', '<=', $request->date('data_entrada_ate'));
        }

        $produtos = $query->orderByDesc('data_entrada_processo')->orderByDesc('id')->paginate(15)->withQueryString();

        return view('criacao.index', $this->buildFormData([
            'produtos' => $produtos,
            'modo' => 'index',
        ]));
    }

    public function create(): View
    {
        $statusDisponivel = Status::where('descricao', 'DISPONIVEL')->first();

        return view('criacao.create', $this->buildFormData([
            'produto' => new Produto([
                'referencia' => $this->generateUniqueReferenciaAlfa(),
                'data_cadastro' => now()->format('Y-m-d'),
                'data_entrada_processo' => now()->format('Y-m-d'),
                'status_id' => $statusDisponivel?->id,
                'quantidade' => 0,
                'preco_atacado' => 0,
                'preco_varejo' => 0,
            ]),
        ]));
    }

    public function store(CriacaoRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $statusDisponivel = Status::where('descricao', 'DISPONIVEL')->first();

        $data['referencia'] = $this->generateUniqueReferenciaAlfa();
        $data['status_id'] = $data['status_id'] ?? $statusDisponivel?->id;
        $data['preco_atacado'] = $data['preco_atacado'] ?? 0;
        $data['preco_varejo'] = $data['preco_varejo'] ?? 0;
        unset(
            $data['foto_principal_criacao'],
            $data['imagens_criacao'],
            $data['remover_foto_principal_criacao'],
            $data['remover_anexos_criacao']
        );

        $produto = Produto::create($data);
        $this->syncMedia($request, $produto);

        return redirect()->route('criacao.show', $produto)->with('success', 'Produto em criação cadastrado com sucesso.');
    }

    public function edit(Produto $produto): View
    {
        $produto->load(['marca', 'estilista', 'grupoProduto', 'status', 'direcionamentoComercial', 'etapaProducao', 'responsavelCriacao', 'faccaoLocalizacao', 'anexos']);

        return view('criacao.edit', $this->buildFormData([
            'produto' => $produto,
        ]));
    }

    public function show(Produto $produto): View
    {
        $produto->load(['marca', 'estilista', 'grupoProduto', 'status', 'direcionamentoComercial', 'anexos']);

        return view('criacao.show', [
            'produto' => $produto,
            'anexosCriacao' => $produto->anexos->where('contexto', ProdutoAnexo::CONTEXTO_CRIACAO),
        ]);
    }

    public function update(CriacaoRequest $request, Produto $produto): RedirectResponse
    {
        $data = $request->validated();

        if (!$request->user()->can('editObsDesigner', $produto)) {
            unset($data['obs_designer']);
        }

        unset(
            $data['foto_principal_criacao'],
            $data['imagens_criacao'],
            $data['remover_foto_principal_criacao'],
            $data['remover_anexos_criacao']
        );

        $produto->update($data);
        $this->syncMedia($request, $produto);

        return redirect()->route('criacao.edit', $produto)->with('success', 'Produto em criação atualizado com sucesso.');
    }

    public function bel(Request $request): View
    {
        $query = Produto::with(['marca', 'estilista', 'grupoProduto', 'status', 'direcionamentoComercial', 'etapaProducao'])
            ->emCriacao();

        if ($request->filled('estilista_id')) {
            $query->where('estilista_id', $request->integer('estilista_id'));
        }

        if ($request->filled('marca_id')) {
            $query->where('marca_id', $request->integer('marca_id'));
        }

        if ($request->filled('direcionamento_comercial_id')) {
            $query->where('direcionamento_comercial_id', $request->integer('direcionamento_comercial_id'));
        }

        if ($request->filled('status_id')) {
            $query->where('status_id', $request->integer('status_id'));
        }

        if ($request->filled('referencia')) {
            $query->where('referencia', 'like', '%' . trim((string) $request->referencia) . '%');
        }

        if ($request->filled('data_entrada_de')) {
            $query->whereDate('data_entrada_processo', '>=', $request->date('data_entrada_de'));
        }

        if ($request->filled('data_entrada_ate')) {
            $query->whereDate('data_entrada_processo', '<=', $request->date('data_entrada_ate'));
        }

        $produtos = $query->orderByDesc('data_entrada_processo')->orderByDesc('id')->paginate(15)->withQueryString();

        return view('criacao.bel', $this->buildFormData([
            'produtos' => $produtos,
            'modo' => 'bel',
        ]));
    }

    public function kanban(Request $request): View
    {
        $produtos = Produto::with(['marca', 'estilista', 'grupoProduto', 'status', 'direcionamentoComercial', 'etapaProducao'])
            ->emCriacao()
            ->orderBy('data_entrada_processo')
            ->get()
            ->groupBy(fn (Produto $produto) => $produto->etapaProducao?->nome ?? 'Sem etapa');

        return view('criacao.kanban', $this->buildFormData([
            'produtosPorEtapa' => $produtos,
        ]));
    }

    public function moverEtapa(Request $request, Produto $produto): RedirectResponse
    {
        abort_unless($request->user()->canAction('update', 'criacao'), 403);

        $validated = $request->validate([
            'etapa_producao_id' => ['required', 'exists:etapas_producao,id'],
        ]);

        $status = Status::where('descricao', 'AGUARDANDO DESENVOLVIMENTO')->first();

        $produto->update([
            'etapa_producao_id' => $validated['etapa_producao_id'],
            'status_id' => $status?->id ?? $produto->status_id,
        ]);

        activity('criacao')
            ->performedOn($produto)
            ->causedBy($request->user())
            ->withProperties([
                'etapa_producao_id' => $validated['etapa_producao_id'],
                'status_id' => $status?->id,
            ])
            ->log('etapa_criacao_definida');

        return redirect()->route('criacao.index')->with('success', 'Etapa definida com sucesso. Produto removido da listagem de criação.');
    }

    private function buildFormData(array $extra = []): array
    {
        return array_merge([
            'marcas' => Marca::where('ativo', true)->orderBy('nome_marca')->get(),
            'estilistas' => Estilista::where('ativo', true)->orderBy('nome_estilista')->get(),
            'grupos' => GrupoProduto::where('ativo', true)->orderBy('descricao')->get(),
            'localizacoes' => Localizacao::where('ativo', true)
                ->where('capacidade', '>', 0)
                ->orderBy('nome_localizacao')
                ->get(),
            'statuses' => Status::where('ativo', true)
                ->whereIn('descricao', StatusSeeder::CRIACAO_STATUSES)
                ->orderByRaw("FIELD(descricao, 'AGUARDANDO ENVIO', 'EM CRIAÇÃO', 'DISPONIVEL', 'AGUARDANDO DESENVOLVIMENTO')")
                ->orderBy('descricao')
                ->get(),
            'direcionamentosComerciais' => DirecionamentoComercial::where('ativo', true)->orderBy('descricao')->get(),
            'etapasProducao' => EtapaProducao::where('ativo', true)->orderBy('ordem')->orderBy('nome')->get(),
            'statusAguardandoDesenvolvimento' => Status::where('descricao', 'AGUARDANDO DESENVOLVIMENTO')->first(),
        ], $extra);
    }

    private function syncMedia(CriacaoRequest $request, Produto $produto): void
    {
        $this->syncPrimaryImage(
            $request,
            $produto,
            'foto_principal_criacao',
            'criacao/foto-principal',
            'remover_foto_principal_criacao'
        );

        $this->removeAttachments($request->input('remover_anexos_criacao', []), $produto, ProdutoAnexo::CONTEXTO_CRIACAO);

        $this->storeAttachments($request, $produto, 'imagens_criacao', ProdutoAnexo::CONTEXTO_CRIACAO);
    }

    private function syncPrimaryImage(CriacaoRequest $request, Produto $produto, string $field, string $directory, string $removeFlag): void
    {
        $updates = [];

        if ($request->hasFile($field)) {
            if ($produto->{$field}) {
                Storage::disk('public')->delete($produto->{$field});
            }

            $file = $request->file($field);
            $name = time() . '_' . $field . '_' . preg_replace('/[^A-Za-z0-9\-\.]/', '_', $file->getClientOriginalName());
            $updates[$field] = $file->storeAs('produtos/' . $directory, $name, 'public');
        } elseif ($request->boolean($removeFlag) && $produto->{$field}) {
            Storage::disk('public')->delete($produto->{$field});
            $updates[$field] = null;
        }

        if (!empty($updates)) {
            $produto->update($updates);
        }
    }

    private function storeAttachments(CriacaoRequest $request, Produto $produto, string $field, string $contexto): void
    {
        if (!$request->hasFile($field)) {
            return;
        }

        foreach ($request->file($field) as $file) {
            if (!$file) {
                continue;
            }

            $name = time() . '_' . $contexto . '_' . preg_replace('/[^A-Za-z0-9\-\.]/', '_', $file->getClientOriginalName());
            $path = $file->storeAs('anexos/produtos/' . $produto->id . '/' . $contexto, $name, 'public');

            $produto->anexos()->create([
                'descricao' => strtoupper($contexto) . ' - ' . $file->getClientOriginalName(),
                'arquivo_path' => $path,
                'tipo_arquivo' => strtolower((string) $file->getClientOriginalExtension()),
                'contexto' => $contexto,
            ]);
        }
    }

    private function removeAttachments(array $ids, Produto $produto, string $contexto): void
    {
        if (empty($ids)) {
            return;
        }

        $anexos = $produto->anexos()
            ->where('contexto', $contexto)
            ->whereIn('id', array_filter($ids))
            ->get();

        foreach ($anexos as $anexo) {
            if ($anexo->arquivo_path) {
                Storage::disk('public')->delete($anexo->arquivo_path);
            }

            $anexo->delete();
        }
    }

    private function generateUniqueReferenciaAlfa(): string
    {
        $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

        do {
            $referencia = '';

            for ($i = 0; $i < 6; $i++) {
                $referencia .= $alphabet[random_int(0, 25)];
            }
        } while (Produto::where('referencia', $referencia)->exists());

        return $referencia;
    }
}
