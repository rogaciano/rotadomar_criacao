<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
                {{ __('Detalhes do Status') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('status.edit', $status) }}" class="btn-ghost-warning">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                    </svg>
                    Editar
                </a>
                <a href="{{ route('status.index') }}" class="btn-ghost-secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                    </svg>
                    Voltar
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-50 dark:bg-slate-950">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="glass dark:glass-dark overflow-hidden rounded-2xl border-none ring-1 ring-black/5">
                <div class="p-6">
                    <!-- Informações do Status -->
                    <div class="bg-gray-50 dark:bg-slate-800 overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Informações Básicas</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <span class="block text-sm font-medium text-gray-500 dark:text-gray-400">ID</span>
                                <span class="block mt-1 text-sm text-gray-900 dark:text-white">{{ $status->id }}</span>
                            </div>

                            <div>
                                <span class="block text-sm font-medium text-gray-500 dark:text-gray-400">Descrição</span>
                                <span class="block mt-1 text-sm text-gray-900 dark:text-white">{{ $status->descricao }}</span>
                            </div>

                            <div>
                                <span class="block text-sm font-medium text-gray-500 dark:text-gray-400">Observações</span>
                                <span class="block mt-1 text-sm text-gray-900 dark:text-white">{{ $status->observacoes }}</span>
                            </div>

                            <div>
                                <span class="block text-sm font-medium text-gray-500 dark:text-gray-400">Status</span>
                                <span class="block mt-1">
                                    @if ($status->ativo)
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300">
                                            Ativo
                                        </span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300">
                                            Inativo
                                        </span>
                                    @endif
                                </span>
                            </div>

                            <div>
                                <span class="block text-sm font-medium text-gray-500 dark:text-gray-400">Calcular Necessidade</span>
                                <span class="block mt-1">
                                    @if ($status->calc_necessidade)
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            Sim
                                        </span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                            Não
                                        </span>
                                    @endif
                                </span>
                            </div>

                            <div>
                                <span class="block text-sm font-medium text-gray-500 dark:text-gray-400">Criado em</span>
                                <span class="block mt-1 text-sm text-gray-900 dark:text-white">{{ $status->created_at ? $status->created_at->format('d/m/Y H:i') : 'N/A' }}</span>
                            </div>

                            <div>
                                <span class="block text-sm font-medium text-gray-500 dark:text-gray-400">Última atualização</span>
                                <span class="block mt-1 text-sm text-gray-900 dark:text-white">{{ $status->updated_at ? $status->updated_at->format('d/m/Y H:i') : 'N/A' }}</span>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>
</x-app-layout>
