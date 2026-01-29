<div class="flex flex-wrap justify-between items-center mb-6 gap-4">
    <div class="flex flex-wrap gap-3">
        @if(auth()->user() && auth()->user()->canCreate('movimentacoes'))
        <a href="{{ route('movimentacoes.create') }}" class="btn-ghost-primary">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
            </svg>
            Nova Movimentação
        </a>
        @endif
    </div>

    <div class="flex flex-wrap gap-2">
        @if(auth()->user() && auth()->user()->canRead('movimentacoes'))
            <a href="{{ route('movimentacoes.lista.pdf', array_merge(request()->query(), ['status_dias' => request('status_dias')])) }}" target="_blank" class="btn-ghost-rose">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                </svg>
                Exportar PDF
            </a>
        @endif
    </div>
</div>
