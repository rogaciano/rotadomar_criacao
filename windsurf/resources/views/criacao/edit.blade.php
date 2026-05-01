<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center gap-3">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">Editar Criação</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $produto->referencia }} - {{ $produto->descricao }}</p>
            </div>
            <a href="{{ route('criacao.index') }}" class="btn-ghost-secondary">Voltar</a>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50 dark:bg-slate-950">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))
                <div class="bg-green-100 dark:bg-green-900/30 border-l-4 border-green-500 dark:border-green-400 text-green-700 dark:text-green-300 p-4">{{ session('success') }}</div>
            @endif

            <div class="glass dark:glass-dark overflow-hidden rounded-2xl border-none ring-1 ring-black/5">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if ($errors->any())
                        <div class="bg-red-100 dark:bg-red-900/30 border-l-4 border-red-500 dark:border-red-400 text-red-700 dark:text-red-300 p-4 mb-4">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('criacao.update', $produto) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        @include('criacao._form')
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
