
<div class="flex flex-wrap gap-3 mb-6">
    @if(auth()->user() && auth()->user()->canCreate('tecidos'))
    <a href="{{ route('tecidos.create') }}" class="btn-ghost-primary">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
        </svg>
        Adicionar Tecido
    </a>
    @endif
    @if(auth()->user() && auth()->user()->canUpdate('tecidos'))
    <a href="{{ route('tecidos.atualizar-todos-estoques') }}" class="btn-ghost-success">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
        </svg>
        Atualizar Estoques
    </a>
    @endif
    @if(auth()->user() && auth()->user()->canUpdate('tecidos'))
    <a href="{{ route('tecidos.importar-estoque-form') }}" class="btn-ghost-purple">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
        </svg>
        Importar Estoque
    </a>
    @endif
</div>
