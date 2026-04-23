@props(['label', 'storeKey', 'active' => false])

<div>
    <button @click="$store.sidebar.open
                ? $store.sidebar.toggleGrupo('{{ $storeKey }}')
                : (() => {
                    $store.sidebar.open = true;
                    localStorage.setItem('sidebar_open', 'true');
                    $nextTick(() => {
                        $store.sidebar.grupos['{{ $storeKey }}'] = true;
                        localStorage.setItem('sidebar_grupos', JSON.stringify($store.sidebar.grupos));
                    });
                  })()"
            class="flex items-center gap-3 px-3 py-2.5 rounded-lg w-full text-left transition-colors duration-150 group relative
                   {{ $active ? 'text-white bg-gray-700' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">

        @isset($icon)
            <span class="flex-shrink-0 w-5 h-5 flex items-center justify-center">
                {{ $icon }}
            </span>
        @endisset

        <span x-cloak x-show="$store.sidebar.open"
              class="flex-1 flex items-center justify-between text-sm font-medium whitespace-nowrap overflow-hidden">
            <span>{{ $label }}</span>
            <svg :class="$store.sidebar.grupos['{{ $storeKey }}'] ? 'rotate-90' : ''"
                 class="w-3.5 h-3.5 flex-shrink-0 transition-transform duration-200"
                 fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
            </svg>
        </span>

        <span x-cloak x-show="!$store.sidebar.open"
              class="absolute left-full ml-3 px-2.5 py-1.5 bg-gray-800 text-white text-xs font-medium rounded-md
                     whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity duration-150
                     pointer-events-none z-[100] shadow-lg border border-gray-700">
            {{ $label }}
        </span>
    </button>

    <div x-cloak
         x-show="$store.sidebar.open && $store.sidebar.grupos['{{ $storeKey }}']"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-1"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-1"
         class="mt-0.5 ml-4 pl-3 border-l border-gray-700 space-y-0.5 pb-1">
        {{ $slot }}
    </div>
</div>
