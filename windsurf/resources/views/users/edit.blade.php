<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
                {{ __('Editar Usuário') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('user-permissions.edit', $user->id) }}" class="btn-ghost-purple">
                    Permissões do Usuário
                </a>
                <a href="{{ route('user-access-schedules.edit', $user->id) }}" class="btn-ghost-success">
                    Horário de Acesso
                </a>
                <a href="{{ route('users.index') }}" class="btn-ghost-secondary">
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
                    <form method="POST" action="{{ route('users.update', $user->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- Nome -->
                            <div>
                                <x-label for="name" value="{{ __('Nome') }}" />
                                <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $user->name)" required autofocus />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            <!-- Email -->
                            <div>
                                <x-label for="email" value="{{ __('Email') }}" />
                                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $user->email)" required />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>

                            <!-- Localização -->
                            <div>
                                <x-label for="localizacao_id" value="{{ __('Localização') }}" />
                                <select id="localizacao_id" name="localizacao_id" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="">Selecione uma localização</option>
                                    @foreach($localizacoes as $localizacao)
                                        <option value="{{ $localizacao->id }}" {{ old('localizacao_id', $user->localizacao_id) == $localizacao->id ? 'selected' : '' }}>
                                            {{ $localizacao->nome_localizacao }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('localizacao_id')" class="mt-2" />
                            </div>

                            <!-- Tipo de Usuário -->
                            <div>
                                <x-label for="is_admin" value="{{ __('Tipo de Usuário') }}" />
                                <div class="mt-2">
                                    <label class="inline-flex items-center">
                                        <input type="hidden" name="is_admin" value="0">
                                        <input type="checkbox" name="is_admin" id="is_admin" value="1" class="rounded border-gray-300 dark:border-slate-600 dark:bg-slate-800 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" {{ old('is_admin', $user->is_admin) ? 'checked' : '' }}>
                                        <span class="ml-2 text-sm text-gray-600">Administrador</span>
                                    </label>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">O campo is_admin deve ser verdadeiro ou falso.</p>
                                <x-input-error :messages="$errors->get('is_admin')" class="mt-2" />
                            </div>

                            <!-- Usuário de Facção -->
                            <div>
                                <x-label for="is_faccao" value="{{ __('Acesso Restrito (Facção)') }}" />
                                <div class="mt-2">
                                    <label class="inline-flex items-center">
                                        <input type="hidden" name="is_faccao" value="0">
                                        <input type="checkbox" name="is_faccao" id="is_faccao" value="1" class="rounded border-gray-300 dark:border-slate-600 dark:bg-slate-800 text-orange-600 shadow-sm focus:border-orange-300 focus:ring focus:ring-orange-200 focus:ring-opacity-50" {{ old('is_faccao', $user->is_faccao) ? 'checked' : '' }}>
                                        <span class="ml-2 text-sm text-gray-600">Usuário de Facção</span>
                                    </label>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Marque se este usuário pertence a uma facção externa. Ele só verá os dados da sua localização no Planejamento.</p>
                                <x-input-error :messages="$errors->get('is_faccao')" class="mt-2" />
                            </div>

                            <!-- Localizações de Visualização -->
                            <div class="md:col-span-2">
                                <x-label for="visualizacoes" value="{{ __('Localizações de Visualização (além da principal)') }}" />
                                <select id="visualizacoes" name="visualizacoes[]" multiple class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 h-32">
                                    @foreach($localizacoes as $localizacao)
                                        @if($localizacao->id != $user->localizacao_id)
                                            <option value="{{ $localizacao->id }}" {{ in_array($localizacao->id, old('visualizacoes', $visualizacoesIds ?? [])) ? 'selected' : '' }}>
                                                {{ $localizacao->nome_localizacao }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                                <p class="text-xs text-gray-500 mt-1">Segure Ctrl (ou Cmd no Mac) para selecionar múltiplas localizações. O usuário poderá visualizar essas localizações mas não poderá editá-las.</p>
                                <x-input-error :messages="$errors->get('visualizacoes')" class="mt-2" />
                            </div>

                            <!-- Senha -->
                            <div>
                                <x-label for="password" value="{{ __('Nova Senha (deixe em branco para manter a atual)') }}" />
                                <x-input id="password" class="block mt-1 w-full" type="password" name="password" autocomplete="new-password" />
                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            </div>

                            <!-- Confirmar Senha -->
                            <div>
                                <x-label for="password_confirmation" value="{{ __('Confirmar Nova Senha') }}" />
                                <x-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" autocomplete="new-password" />
                                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <button type="submit" class="btn-ghost-primary">
                                Atualizar Usuário
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
