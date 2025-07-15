@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Detalhes do Grupo: {{ $group->display_name }}</span>
                        <div>
                            <a href="{{ route('groups.edit', $group) }}" class="btn btn-sm btn-primary">Editar</a>
                            <a href="{{ route('groups.index') }}" class="btn btn-sm btn-secondary">Voltar</a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="mb-4">
                        <h5>Informações Gerais</h5>
                        <table class="table">
                            <tr>
                                <th style="width: 200px;">Nome:</th>
                                <td>{{ $group->name }}</td>
                            </tr>
                            <tr>
                                <th>Nome de Exibição:</th>
                                <td>{{ $group->display_name }}</td>
                            </tr>
                            <tr>
                                <th>Descrição:</th>
                                <td>{{ $group->description ?: 'Nenhuma descrição' }}</td>
                            </tr>
                            <tr>
                                <th>Status:</th>
                                <td>
                                    @if ($group->is_active)
                                        <span class="badge bg-success">Ativo</span>
                                    @else
                                        <span class="badge bg-danger">Inativo</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div class="mb-4">
                        <h5>Permissões ({{ $group->permissions->count() }})</h5>
                        @if($group->permissions->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Nome</th>
                                            <th>Slug</th>
                                            <th>Módulo</th>
                                            <th>Descrição</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($group->permissions as $permission)
                                            <tr>
                                                <td>{{ $permission->name }}</td>
                                                <td><code>{{ $permission->slug }}</code></td>
                                                <td>{{ $permission->module }}</td>
                                                <td>{{ $permission->description }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info">
                                Este grupo não possui permissões atribuídas.
                            </div>
                        @endif
                    </div>

                    <div class="mb-4">
                        <h5>Usuários neste Grupo ({{ $group->users->count() }})</h5>
                        @if($group->users->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Nome</th>
                                            <th>Email</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($group->users as $user)
                                            <tr>
                                                <td>{{ $user->name }}</td>
                                                <td>{{ $user->email }}</td>
                                                <td>
                                                    <a href="{{ route('user-groups.edit', $user) }}" class="btn btn-sm btn-primary">Gerenciar Grupos</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info">
                                Este grupo não possui usuários atribuídos.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
