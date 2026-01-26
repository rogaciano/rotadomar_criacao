<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
                {{ __('Detalhes da Situação') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('situacoes.edit', $situacao->id) }}" class="btn-ghost-warning">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Editar
                </a>
                <a href="{{ route('situacoes.index') }}" class="btn-ghost-secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Voltar
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-50 dark:bg-slate-950">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="glass dark:glass-dark overflow-hidden rounded-2xl border-none ring-1 ring-black/5 p-6">

                <div class="mb-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Informações da Situação</h3>

                    <div class="bg-gray-50 dark:bg-slate-800 p-4 rounded-lg">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Descrição</p>
                                <p class="mt-1 text-lg text-gray-900 dark:text-white">{{ $situacao->descricao }}</p>
                            </div>

                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</p>
                                <p class="mt-1">
                                    <span class="px-2 py-1 rounded-full text-xs {{ $situacao->ativo ? 'bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300' : 'bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-300' }}">
                                        {{ $situacao->ativo ? 'Ativo' : 'Inativo' }}
                                    </span>
                                </p>
                            </div>

                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Prazo</p>
                                <p class="mt-1 text-lg text-gray-900 dark:text-white">
                                    @if($situacao->prazo)
                                        {{ $situacao->prazo }} {{ $situacao->prazo == 1 ? 'dia' : 'dias' }}
                                        <span class="text-xs text-gray-500 dark:text-gray-400">(dias úteis)</span>
                                    @else
                                        <span class="text-gray-400">Não definido</span>
                                    @endif
                                </p>
                            </div>

                            <div class="md:col-span-2">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Observações</p>
                                <p class="mt-1 text-gray-900 dark:text-white whitespace-pre-line">{{ $situacao->observacoes ?? 'Nenhuma observação cadastrada.' }}</p>
                            </div>

                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Data de Criação</p>
                                <p class="mt-1 text-gray-900 dark:text-white">{{ $situacao->created_at->format('d/m/Y H:i:s') }}</p>
                            </div>

                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Última Atualização</p>
                                <p class="mt-1 text-gray-900 dark:text-white">{{ $situacao->updated_at->format('d/m/Y H:i:s') }}</p>
                            </div>

                            @if($situacao->trashed())
                            <div>
                                <p class="text-sm font-medium text-red-600">Excluído em</p>
                                <p class="mt-1 text-red-900">{{ $situacao->deleted_at->format('d/m/Y H:i:s') }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
