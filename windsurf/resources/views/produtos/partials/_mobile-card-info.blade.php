{{-- Informações do Card Mobile --}}

{{-- Marca e Grupo --}}
<div class="grid grid-cols-2 gap-3">
    <div>
        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Marca</p>
        @if($produto->marca && $produto->marca->logo_path)
            <img src="{{ asset('storage/' . $produto->marca->logo_path) }}" 
                 alt="{{ $produto->marca->nome_marca }}" 
                 class="h-5 w-auto object-contain">
        @else
            <p class="text-sm font-medium text-gray-900 dark:text-white">
                {{ $produto->marca->nome_marca ?? 'N/A' }}
            </p>
        @endif
    </div>
    <div>
        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Grupo</p>
        <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
            {{ $produto->grupoProduto->descricao ?? 'N/A' }}
        </p>
    </div>
</div>

{{-- Datas --}}
<div class="grid grid-cols-2 gap-3">
    <div>
        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Prev. Produção</p>
        <p class="text-sm font-semibold text-indigo-600 dark:text-indigo-400">
            {{ $produto->data_prevista_producao_mes_ano ?? 'N/A' }}
        </p>
    </div>
    <div>
        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Facção</p>
        @if($produto->primeira_data_prevista_faccao)
            <p class="text-sm font-semibold text-indigo-600 dark:text-indigo-400">
                {{ $produto->primeira_data_prevista_faccao->format('d/m/Y') }}
            </p>
        @else
            <p class="text-xs italic text-gray-400 dark:text-gray-500">Sem data</p>
        @endif
    </div>
</div>

{{-- Badges --}}
<div class="flex flex-wrap gap-2">
    {{-- Status --}}
    <span class="px-2.5 py-1 text-xs font-semibold rounded-full {{ $produto->status && $produto->status->descricao == 'Ativo' ? 'bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-200' : 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200' }}">
        {{ $produto->status ? $produto->status->descricao : 'N/A' }}
    </span>
    
    {{-- Localização --}}
    @if($produto->localizacao_atual)
        <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-200">
            📍 {{ $produto->localizacao_atual->nome_localizacao }}
        </span>
    @endif

    {{-- Situação --}}
    @if($produto->situacao_atual)
        <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-purple-100 dark:bg-purple-900/50 text-purple-800 dark:text-purple-200">
            {{ $produto->situacao_atual->descricao }}
        </span>
    @endif

    {{-- Direcionamento --}}
    @if($produto->direcionamentoComercial)
        <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-amber-100 dark:bg-amber-900/50 text-amber-800 dark:text-amber-200">
            {{ $produto->direcionamentoComercial->descricao }}
        </span>
    @endif
</div>
