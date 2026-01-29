<!-- Modal para exibir imagem -->
<div id="imageModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-75" style="display: none;">
    <div class="relative max-w-4xl max-h-screen p-2">
        <button type="button" onclick="closeImageModal()" class="absolute top-2 right-2 bg-white rounded-full p-1 shadow-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-800" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
        <img id="modalImage" src="" alt="Anexo da Movimentação" class="max-w-full max-h-[90vh] rounded-lg shadow-lg object-contain bg-white p-1">
    </div>
</div>
