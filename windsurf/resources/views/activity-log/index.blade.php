<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Log de Atividades') }}
        </h2>
    </x-slot>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>{{ __('Log de Atividades') }}</span>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Filtros -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Filtros</h5>
                        </div>
                        <div class="card-body">
                            <form method="GET" action="{{ route('activity-log.index') }}">
                                <div class="row">
                                    <div class="col-md-3 mb-3">
                                        <label for="log_name">Nome do Log</label>
                                        <select name="log_name" id="log_name" class="form-control">
                                            <option value="">Todos</option>
                                            @foreach($logNames as $name)
                                                <option value="{{ $name }}" {{ request('log_name') == $name ? 'selected' : '' }}>
                                                    {{ $name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <div class="col-md-3 mb-3">
                                        <label for="event">Evento</label>
                                        <select name="event" id="event" class="form-control">
                                            <option value="">Todos</option>
                                            @foreach($events as $event)
                                                <option value="{{ $event }}" {{ request('event') == $event ? 'selected' : '' }}>
                                                    {{ $event }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <div class="col-md-3 mb-3">
                                        <label for="subject_type">Tipo de Objeto</label>
                                        <select name="subject_type" id="subject_type" class="form-control">
                                            <option value="">Todos</option>
                                            @foreach($subjectTypes as $type)
                                                <option value="{{ $type['value'] }}" {{ request('subject_type') == $type['value'] ? 'selected' : '' }}>
                                                    {{ $type['label'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <div class="col-md-3 mb-3">
                                        <label for="causer_type">Tipo de Usuário</label>
                                        <select name="causer_type" id="causer_type" class="form-control">
                                            <option value="">Todos</option>
                                            @foreach($causerTypes as $type)
                                                <option value="{{ $type['value'] }}" {{ request('causer_type') == $type['value'] ? 'selected' : '' }}>
                                                    {{ $type['label'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-3 mb-3">
                                        <label for="date_from">Data Inicial</label>
                                        <input type="date" name="date_from" id="date_from" class="form-control" value="{{ request('date_from') }}">
                                    </div>
                                    
                                    <div class="col-md-3 mb-3">
                                        <label for="date_to">Data Final</label>
                                        <input type="date" name="date_to" id="date_to" class="form-control" value="{{ request('date_to') }}">
                                    </div>
                                    
                                    <div class="col-md-6 mb-3 d-flex align-items-end">
                                        <button type="submit" class="btn btn-primary mr-2">Filtrar</button>
                                        <a href="{{ route('activity-log.index') }}" class="btn btn-secondary">Limpar Filtros</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Tabela de Logs -->
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Descrição</th>
                                    <th>Tipo</th>
                                    <th>Evento</th>
                                    <th>Usuário</th>
                                    <th>Data</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($activities as $activity)
                                    <tr>
                                        <td>{{ $activity->id }}</td>
                                        <td>{{ $activity->description }}</td>
                                        <td>
                                            @if($activity->subject_type)
                                                {{ class_basename($activity->subject_type) }}
                                                @if($activity->subject_id)
                                                    #{{ $activity->subject_id }}
                                                @endif
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge {{ $activity->event == 'created' ? 'bg-success' : ($activity->event == 'updated' ? 'bg-primary' : ($activity->event == 'deleted' ? 'bg-danger' : 'bg-secondary')) }}">
                                                {{ $activity->event }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($activity->causer)
                                                {{ $activity->causer->name ?? class_basename($activity->causer_type) . ' #' . $activity->causer_id }}
                                            @else
                                                Sistema
                                            @endif
                                        </td>
                                        <td>{{ $activity->created_at->format('d/m/Y H:i:s') }}</td>
                                        <td>
                                            <a href="{{ route('activity-log.show', $activity->id) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i> Detalhes
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Nenhum registro encontrado</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Paginação -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $activities->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</x-app-layout>
