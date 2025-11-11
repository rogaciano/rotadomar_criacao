<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Notificações') }}
                </h2>
                @if($podeVerTodas ?? false)
                    <p class="text-sm text-gray-600 mt-1">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                            Visualização Global - Todas as Localizações
                        </span>
                    </p>
                @endif
            </div>
            <button id="marcar-todas-visualizadas" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Marcar Todas como Lidas
            </button>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if($notificacoes->count() > 0)
                        <div class="space-y-4">
                            @foreach($notificacoes as $notificacao)
                                <div class="border rounded-lg p-4 {{ $notificacao->isVisualizada() ? 'bg-gray-50' : 'bg-blue-50 border-blue-200' }}">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center space-x-2">
                                                <h3 class="text-lg font-semibold {{ $notificacao->isVisualizada() ? 'text-gray-700' : 'text-blue-800' }}">
                                                    {{ $notificacao->titulo }}
                                                </h3>
                                                @if($notificacao->tipo === 'nova_movimentacao')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        Nova
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                        Concluída
                                                    </span>
                                                @endif
                                                @if(!$notificacao->isVisualizada())
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                        Não lida
                                                    </span>
                                                @endif
                                            </div>
                                            <p class="text-gray-600 mt-1">{{ $notificacao->mensagem }}</p>
                                            <div class="flex items-center space-x-4 mt-2 text-sm text-gray-500">
                                                <span>{{ $notificacao->created_at->diffForHumans() }}</span>
                                                <span>{{ $notificacao->localizacao->nome_localizacao }}</span>
                                                @if($notificacao->isVisualizada())
                                                    <span>Visualizada por {{ $notificacao->visualizadaPor->name }} em {{ $notificacao->visualizada_em->format('d/m/Y H:i') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <a href="{{ route('notificacoes.visualizar', $notificacao->id) }}" 
                                               class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                Ver Movimentação
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Paginação -->
                        <div class="mt-6">
                            {{ $notificacoes->links() }}
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="text-gray-500 text-lg">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5-5-5h5v-12h5v12z" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">Nenhuma notificação</h3>
                                <p class="mt-1 text-sm text-gray-500">Você não possui notificações no momento.</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.getElementById('marcar-todas-visualizadas').addEventListener('click', function() {
            if (confirm('Deseja marcar todas as notificações como visualizadas?')) {
                fetch('{{ route("api.notificacoes.marcar-todas-visualizadas") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Erro: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    alert('Erro ao marcar notificações como visualizadas');
                });
            }
        });
    </script>
    @endpush
</x-app-layout>
