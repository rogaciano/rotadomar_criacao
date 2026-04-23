@props(['href', 'active' => false])

<a href="{{ $href }}"
   class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors duration-150 group relative w-full
          {{ $active
              ? 'bg-indigo-600 text-white'
              : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">

    @isset($icon)
        <span class="flex-shrink-0 w-5 h-5 flex items-center justify-center">
            {{ $icon }}
        </span>
    @endisset

    <span x-cloak x-show="$store.sidebar.open"
          x-transition:enter="transition-opacity duration-150 delay-75"
          x-transition:enter-start="opacity-0"
          x-transition:enter-end="opacity-100"
          x-transition:leave="transition-opacity duration-75"
          x-transition:leave-start="opacity-100"
          x-transition:leave-end="opacity-0"
          class="text-sm font-medium whitespace-nowrap overflow-hidden flex-1">
        {{ $slot }}
    </span>

    <span x-cloak x-show="!$store.sidebar.open"
          class="absolute left-full ml-3 px-2.5 py-1.5 bg-gray-800 text-white text-xs font-medium rounded-md
                 whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity duration-150
                 pointer-events-none z-[100] shadow-lg border border-gray-700">
        {{ $slot }}
    </span>
</a>
