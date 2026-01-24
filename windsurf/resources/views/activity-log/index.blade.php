<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
            {{ __('Log de Atividades') }}
        </h2>
    </x-slot>
<div class="py-12 bg-slate-50 dark:bg-slate-950">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="glass dark:glass-dark overflow-hidden rounded-2xl border-none ring-1 ring-black/5">
            <div class="p-6">
                <!-- Filtros -->
                <form method="GET" action="{{ route('activity-log.index') }}" class="mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label for="log_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nome do Log</label>
                            <select name="log_name" id="log_name" class="mt-1 block w-full rounded-md border-gray-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="">Todos</option>
                                @foreach($logNames as $name)
                                    <option value="{{ $name }}" {{ request('log_name') == $name ? 'selected' : '' }}>
                                        {{ $name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="event" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Evento</label>
                            <select name="event" id="event" class="mt-1 block w-full rounded-md border-gray-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="">Todos</option>
                                @foreach($events as $event)
                                    <option value="{{ $event }}" {{ request('event') == $event ? 'selected' : '' }}>
                                        {{ $event }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="subject_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tipo de Objeto</label>
                            <select name="subject_type" id="subject_type" class="mt-1 block w-full rounded-md border-gray-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="">Todos</option>
                                @foreach($subjectTypes as $type)
                                    <option value="{{ $type['value'] }}" {{ request('subject_type') == $type['value'] ? 'selected' : '' }}>
                                        {{ $type['label'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="causer_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tipo de Usuário</label>
                            <select name="causer_type" id="causer_type" class="mt-1 block w-full rounded-md border-gray-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="">Todos</option>
                                @foreach($causerTypes as $type)
                                    <option value="{{ $type['value'] }}" {{ request('causer_type') == $type['value'] ? 'selected' : '' }}>
                                        {{ $type['label'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                        <div>
                            <label for="date_from" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Data Inicial</label>
                            <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
                        </div>
                        <div>
                            <label for="date_to" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Data Final</label>
                            <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
                        </div>
                        <div class="flex items-end gap-2">
                            <x-button type="submit">Filtrar</x-button>
                            <a href="{{ route('activity-log.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-300 focus:outline-none focus:border-gray-300 focus:ring focus:ring-gray-200 transition">Limpar</a>
                        </div>
                    </div>
                </form>

                <!-- Tabela de Logs -->
                <div class="overflow-x-auto">
                    <table class="table-base">
                    <thead class="table-header">
                        <tr>
                            <th class="table-header-cell">ID</th>
                            <th class="table-header-cell">Descrição</th>
                            <th class="table-header-cell">Tipo</th>
                            <th class="table-header-cell">Evento</th>
                            <th class="table-header-cell">Usuário</th>
                            <th class="table-header-cell">Data</th>
                            <th class="table-header-cell text-right">Ações</th>
                        </tr>
                    </thead>
                        <tbody class="table-body">
                        @forelse($activities as $activity)
                            <tr class="table-row">
                                <td class="table-cell table-cell-secondary">{{ $activity->id }}</td>
                                <td class="table-cell table-cell-secondary">{{ $activity->description }}</td>
                                    <td class="table-cell table-cell-secondary">
                                    @if($activity->subject_type)
                                        {{ class_basename($activity->subject_type) }}
                                        @if($activity->subject_id)
                                            #{{ $activity->subject_id }}
                                        @endif
                                    @else
                                        -
                                    @endif
                                </td>
                                    <td class="table-cell">
                                    @php($event = $activity->event)
                                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $event === 'created' ? 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-200' : ($event === 'updated' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-200' : ($event === 'deleted' ? 'bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-200' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300')) }}">
                                        {{ $activity->event }}
                                    </span>
                                </td>
                                    <td class="table-cell table-cell-secondary">
                                    @if($activity->causer)
                                        {{ $activity->causer->name ?? class_basename($activity->causer_type) . ' #' . $activity->causer_id }}
                                    @else
                                        Sistema
                                    @endif
                                </td>
                                <td class="table-cell table-cell-secondary">{{ $activity->created_at->format('d/m/Y H:i:s') }}</td>
                                        <td class="table-cell text-right flex items-center justify-end space-x-2">
                                            <a href="{{ route('activity-log.show', $activity->id) }}" class="btn-action-view">Detalhes</a>
                                        </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="table-cell table-empty">Nenhum registro encontrado</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Paginação -->
                <div class="mt-6">
                    {{ $activities->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
</x-app-layout>
