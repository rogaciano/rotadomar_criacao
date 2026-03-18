<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
            Nova Sugestão
        </h2>
    </x-slot>

    <div class="py-8 bg-slate-50 dark:bg-slate-950 min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-slate-800 shadow-sm rounded-lg p-6">
                <form action="{{ route('sugestoes.store') }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label for="assunto" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Assunto</label>
                        <input type="text" name="assunto" id="assunto" value="{{ old('assunto') }}" required maxlength="255"
                               class="mt-1 block w-full rounded-md border-gray-300 dark:bg-slate-700 dark:border-slate-600 dark:text-white" />
                        @error('assunto')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="texto" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Texto da Sugestão</label>
                        <textarea name="texto" id="texto" rows="8" required maxlength="5000"
                                  class="mt-1 block w-full rounded-md border-gray-300 dark:bg-slate-700 dark:border-slate-600 dark:text-white">{{ old('texto') }}</textarea>
                        @error('texto')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('sugestoes.index') }}" class="px-4 py-2 rounded-md bg-gray-200 text-gray-700 text-sm font-semibold hover:bg-gray-300">Cancelar</a>
                        <button type="submit" class="px-4 py-2 rounded-md bg-indigo-600 text-white text-sm font-semibold hover:bg-indigo-700">Enviar Sugestão</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
