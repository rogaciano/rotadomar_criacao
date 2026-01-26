@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Gerenciar Grupos</span>
                        <a href="{{ route('groups.create') }}" class="btn-ghost-primary">Novo Grupo</a>
                    </div>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('groups.index') }}" method="GET" class="mb-4">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Buscar por nome ou descrição" value="{{ request('search') }}">
                            <div class="input-group-append">
                                <button class="btn-ghost-primary" type="submit">Buscar</button>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Nome</th>
                                    <th>Nome de Exibição</th>
                                    <th>Descrição</th>
                                    <th>Status</th>
                                    <th>Permissões</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($groups as $group)
                                    <tr>
                                        <td>{{ $group->name }}</td>
                                        <td>{{ $group->display_name }}</td>
                                        <td>{{ $group->description }}</td>
                                        <td>
                                            @if ($group->is_active)
                                                <span class="badge bg-success">Ativo</span>
                                            @else
                                                <span class="badge bg-danger">Inativo</span>
                                            @endif
                                        </td>
                                        <td>{{ $group->permissions->count() }}</td>
                                        <td>
                                            <div class="flex items-center space-x-2">
                                                <a href="{{ route('groups.show', $group) }}" class="btn-action-view">Ver</a>
                                                <a href="{{ route('groups.edit', $group) }}" class="btn-action-edit">Editar</a>
                                                <form action="{{ route('groups.destroy', $group) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn-action-delete" onclick="return confirm('Tem certeza que deseja excluir este grupo?')">Excluir</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Nenhum grupo encontrado.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-4">
                        {{ $groups->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
