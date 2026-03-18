<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-3">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">Detalhes da Sugestão</h2>
            <a href="{{ route('sugestoes.index') }}" class="inline-flex items-center px-3 py-2 rounded-md bg-gray-200 text-gray-700 text-xs font-semibold uppercase hover:bg-gray-300">Voltar</a>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50 dark:bg-slate-950 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-4">
            <div class="bg-white dark:bg-slate-800 shadow-sm rounded-lg p-6">
                @php
                    $statusClasses = [
                        'nao_lida' => 'bg-red-100 text-red-700',
                        'lida' => 'bg-blue-100 text-blue-700',
                        'em_analise' => 'bg-amber-200 text-amber-900',
                        'aceito' => 'bg-green-100 text-green-700',
                        'negado' => 'bg-gray-200 text-gray-700',
                    ];
                @endphp

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <p class="text-xs uppercase font-semibold text-gray-500 dark:text-gray-400">Usuário</p>
                        <p class="text-sm text-gray-900 dark:text-white">{{ $sugestao->usuario->name ?? 'Usuário' }}</p>
                    </div>
                    <div>
                        <p class="text-xs uppercase font-semibold text-gray-500 dark:text-gray-400">Data/Hora</p>
                        <p class="text-sm text-gray-900 dark:text-white">{{ $sugestao->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-xs uppercase font-semibold text-gray-500 dark:text-gray-400">Localização</p>
                        <p class="text-sm text-gray-900 dark:text-white">{{ $sugestao->localizacao->nome_localizacao ?? 'Sem localização' }}</p>
                    </div>
                    <div>
                        <p class="text-xs uppercase font-semibold text-gray-500 dark:text-gray-400">Status</p>
                        <span class="inline-flex px-2 py-1 rounded-full text-xs font-semibold {{ $statusClasses[$sugestao->status] ?? 'bg-gray-100 text-gray-700' }}">
                            {{ $sugestao->status_label }}
                        </span>
                    </div>
                </div>

                <div class="mb-4">
                    <p class="text-xs uppercase font-semibold text-gray-500 dark:text-gray-400">Assunto</p>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $sugestao->assunto }}</h3>
                </div>

                <div>
                    <p class="text-xs uppercase font-semibold text-gray-500 dark:text-gray-400 mb-1">Texto</p>
                    <div class="bg-slate-50 dark:bg-slate-900 rounded-md p-4 text-sm text-gray-700 dark:text-gray-200 whitespace-pre-wrap">{{ $sugestao->texto }}</div>
                </div>

                @if($sugestao->lidoPor)
                    <div class="mt-4 text-xs text-gray-500 dark:text-gray-400">
                        Última atualização de leitura/status por <strong>{{ $sugestao->lidoPor->name }}</strong>
                        @if($sugestao->lido_em)
                            em {{ $sugestao->lido_em->format('d/m/Y H:i') }}
                        @endif
                    </div>
                @endif
            </div>

            <div class="bg-white dark:bg-slate-800 shadow-sm rounded-lg p-6">
                <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-200 uppercase mb-3">Atualizar Status</h4>
                <form action="{{ route('sugestoes.update-status', $sugestao) }}" method="POST" class="flex flex-col md:flex-row gap-3 md:items-end">
                    @csrf
                    @method('PUT')

                    <div class="w-full md:w-72">
                        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Novo status</label>
                        <select name="status" id="status" class="mt-1 w-full rounded-md border-gray-300 dark:bg-slate-700 dark:border-slate-600 dark:text-white">
                            @foreach($statusValidos as $status)
                                <option value="{{ $status }}" {{ $sugestao->status === $status ? 'selected' : '' }}>{{ \Illuminate\Support\Str::of($status)->replace('_', ' ')->title() }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="px-4 py-2 rounded-md bg-indigo-600 text-white text-sm font-semibold hover:bg-indigo-700">Salvar Status</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
