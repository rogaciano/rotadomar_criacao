@if($produto->anexos && $produto->anexos->count() > 0)
<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
    @foreach($produto->anexos as $anexo)
    <div class="bg-white p-3 rounded-md border border-gray-200 flex items-center justify-between">
        <div class="flex items-center">
            @php
            $icone = 'M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z';
            $corIcone = 'text-blue-500';

            if (in_array($anexo->tipo_arquivo, ['jpg', 'jpeg', 'png'])) {
            $icone = 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z';
            $corIcone = 'text-green-500';
            }
            @endphp

            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 {{ $corIcone }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icone }}" />
            </svg>
            <span class="text-sm text-gray-700">{{ $anexo->descricao }}</span>
        </div>
        <div class="flex items-center">
            <a href="{{ route('produtos.anexos.show', $anexo->id) }}" target="_blank" class="text-blue-600 hover:text-blue-800 text-sm mr-3">
                Visualizar
            </a>
            <button type="button" onclick="deleteAttachment({{ $anexo->id }})" class="text-red-600 hover:text-red-800 text-sm">
                Excluir
            </button>
        </div>
    </div>
    @endforeach
</div>
@else
<div class="text-center py-4 text-gray-500 italic mb-4">
    Nenhum anexo adicionado. Salve o produto e adicione anexos na tela de visualização.
</div>
@endif
