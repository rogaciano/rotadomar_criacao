<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-3">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
                {{ isset($somenteMinhas) && $somenteMinhas ? 'Minhas Sugestões' : 'Sugestões' }}
            </h2>
            <a href="{{ route('sugestoes.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                Nova Sugestão
            </a>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50 dark:bg-slate-950 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
            <div class="bg-white dark:bg-slate-800 shadow-sm rounded-lg p-4">
                <form method="GET" action="{{ request()->url() }}" class="grid grid-cols-1 md:grid-cols-4 gap-3 md:items-end">
                    <div class="w-full md:w-64">
                        <label for="status" class="block text-xs font-semibold uppercase text-gray-500 dark:text-gray-400 mb-1">Status</label>
                        <select name="status" id="status" class="w-full rounded-md border-gray-300 dark:bg-slate-700 dark:border-slate-600 dark:text-white">
                            <option value="">Todos</option>
                            @foreach($statusValidos as $status)
                                <option value="{{ $status }}" {{ ($statusSelecionado ?? '') === $status ? 'selected' : '' }}>{{ \Illuminate\Support\Str::of($status)->replace('_', ' ')->title() }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="assunto" class="block text-xs font-semibold uppercase text-gray-500 dark:text-gray-400 mb-1">Assunto</label>
                        <input type="text"
                               name="assunto"
                               id="assunto"
                               value="{{ $assuntoSelecionado ?? '' }}"
                               placeholder="Buscar no assunto"
                               class="w-full rounded-md border-gray-300 dark:bg-slate-700 dark:border-slate-600 dark:text-white" />
                    </div>
                    <div>
                        <label for="usuario" class="block text-xs font-semibold uppercase text-gray-500 dark:text-gray-400 mb-1">Usuário</label>
                        <input type="text"
                               name="usuario"
                               id="usuario"
                               value="{{ $usuarioSelecionado ?? '' }}"
                               placeholder="Nome do usuário"
                               class="w-full rounded-md border-gray-300 dark:bg-slate-700 dark:border-slate-600 dark:text-white" />
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="px-4 py-2 rounded-md bg-indigo-600 text-white text-sm font-semibold hover:bg-indigo-700">Filtrar</button>
                        <a href="{{ request()->url() }}" class="px-4 py-2 rounded-md bg-gray-200 text-gray-700 text-sm font-semibold hover:bg-gray-300">Limpar</a>
                    </div>
                </form>
            </div>

            @if(isset($contadores))
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3">
                <a href="{{ request()->url() }}" class="bg-white dark:bg-slate-800 shadow-sm rounded-lg p-4 hover:shadow-md transition-shadow {{ empty($statusSelecionado) ? 'ring-2 ring-indigo-500' : '' }}">
                    <p class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Total</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $contadores['total'] }}</p>
                </a>
                <a href="{{ request()->fullUrlWithQuery(['status' => 'nao_lida']) }}" class="bg-white dark:bg-slate-800 shadow-sm rounded-lg p-4 hover:shadow-md transition-shadow {{ ($statusSelecionado ?? '') === 'nao_lida' ? 'ring-2 ring-red-500' : '' }}">
                    <p class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Não Lidas</p>
                    <p class="text-2xl font-bold text-red-600 mt-1">{{ $contadores['nao_lida'] }}</p>
                </a>
                <a href="{{ request()->fullUrlWithQuery(['status' => 'em_analise']) }}" class="bg-white dark:bg-slate-800 shadow-sm rounded-lg p-4 hover:shadow-md transition-shadow {{ ($statusSelecionado ?? '') === 'em_analise' ? 'ring-2 ring-amber-500' : '' }}">
                    <p class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Em Análise</p>
                    <p class="text-2xl font-bold text-amber-600 mt-1">{{ $contadores['em_analise'] }}</p>
                </a>
                <a href="{{ request()->fullUrlWithQuery(['status' => 'aceito']) }}" class="bg-white dark:bg-slate-800 shadow-sm rounded-lg p-4 hover:shadow-md transition-shadow {{ ($statusSelecionado ?? '') === 'aceito' ? 'ring-2 ring-green-500' : '' }}">
                    <p class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Aceitas</p>
                    <p class="text-2xl font-bold text-green-600 mt-1">{{ $contadores['aceito'] }}</p>
                </a>
                <a href="{{ request()->fullUrlWithQuery(['status' => 'negado']) }}" class="bg-white dark:bg-slate-800 shadow-sm rounded-lg p-4 hover:shadow-md transition-shadow {{ ($statusSelecionado ?? '') === 'negado' ? 'ring-2 ring-gray-400' : '' }}">
                    <p class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Negadas</p>
                    <p class="text-2xl font-bold text-gray-500 mt-1">{{ $contadores['negado'] }}</p>
                </a>
            </div>
            @endif

            <div class="bg-white dark:bg-slate-800 shadow-sm rounded-lg overflow-hidden">
                @if($sugestoes->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-slate-700">
                            <thead class="bg-gray-50 dark:bg-slate-700">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase">Data/Hora</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase">Usuário</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase">Localização</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase">Assunto</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase">Status</th>
                                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                                @foreach($sugestoes as $sugestao)
                                    @php
                                        $statusClasses = [
                                            'nao_lida' => 'bg-red-100 text-red-700',
                                            'lida' => 'bg-blue-100 text-blue-700',
                                            'em_analise' => 'bg-amber-200 text-amber-900',
                                            'aceito' => 'bg-green-100 text-green-700',
                                            'negado' => 'bg-gray-200 text-gray-700',
                                        ];
                                    @endphp
                                    <tr>
                                        <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-200">{{ $sugestao->created_at->format('d/m/Y H:i') }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-200">{{ $sugestao->usuario->name ?? 'Usuário' }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-200">{{ $sugestao->localizacao->nome_localizacao ?? 'Sem localização' }}</td>
                                        <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-white">{{ $sugestao->assunto }}</td>
                                        <td class="px-4 py-3">
                                            <span class="inline-flex px-2 py-1 rounded-full text-xs font-semibold {{ $statusClasses[$sugestao->status] ?? 'bg-gray-100 text-gray-700' }}">
                                                {{ $sugestao->status_label }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-right">
                                            <a href="{{ route('sugestoes.show', $sugestao) }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-semibold">Abrir</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="p-4 border-t border-gray-100 dark:border-slate-700">
                        {{ $sugestoes->links() }}
                    </div>
                @else
                    <div class="p-8 text-center text-gray-500 dark:text-gray-400">
                        Nenhuma sugestão encontrada.
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
