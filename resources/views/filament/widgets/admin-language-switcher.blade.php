<div class="relative" x-data="{ open: false }" style="margin-inline-end: 0.5rem;">

    <button
        @click="open = !open"
        @click.outside="open = false"
        type="button"
        class="inline-flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium
               bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300
               hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors">
        @php $current = $languages->firstWhere('code', $currentLocale); @endphp
        <span class="text-base leading-none">{{ $current?->flag }}</span>
        <span class="hidden sm:inline">{{ $current?->native_name }}</span>
        <x-heroicon-m-chevron-down class="w-4 h-4 transition-transform" ::class="{'rotate-180':open}"/>
    </button>

    <div
        x-show="open"
        x-transition
        x-cloak
        class="absolute z-50 mt-2 w-48 rounded-xl shadow-lg
               bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700
               overflow-hidden"
        style="inset-inline-end: 0;">

        @foreach($languages as $lang)
            <form method="POST" action="{{ route('admin.set-locale') }}">
                @csrf
                <input type="hidden" name="locale" value="{{ $lang->code }}">
                <button
                    type="submit"
                    class="w-full flex items-center gap-3 px-4 py-2.5 text-sm transition-colors
                           hover:bg-gray-50 dark:hover:bg-gray-800
                           {{ $currentLocale === $lang->code
                               ? 'text-primary-500 font-semibold bg-primary-50 dark:bg-primary-900/20'
                               : 'text-gray-700 dark:text-gray-300' }}">
                    <span class="text-base">{{ $lang->flag }}</span>
                    <span>{{ $lang->native_name }}</span>
                    <span class="text-xs text-gray-400 ms-auto uppercase">{{ $lang->code }}</span>
                    @if($currentLocale === $lang->code)
                        <x-heroicon-m-check class="w-4 h-4 text-primary-500 shrink-0"/>
                    @endif
                </button>
            </form>
        @endforeach

    </div>
</div>
