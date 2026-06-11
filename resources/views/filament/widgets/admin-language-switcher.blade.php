<x-filament-widgets::widget>
    <x-filament::section>
        <div class="flex items-center justify-between gap-3">

            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">
                Panel Language
            </span>

            <div class="relative" x-data="{ open: false }">

                {{-- دکمه اصلی --}}
                <button
                    @click="open = !open"
                    @click.outside="open = false"
                    type="button"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium
                           bg-primary-500 text-white shadow transition-all hover:bg-primary-600">
                    @php $current = $languages->firstWhere('code', $currentLocale); @endphp
                    <span class="text-base leading-none">{{ $current?->flag }}</span>
                    <span>{{ $current?->native_name }}</span>
                    <x-heroicon-m-chevron-down class="w-4 h-4 transition-transform" ::class="{ 'rotate-180': open }"/>
                </button>

                {{-- Dropdown --}}
                <div
                    x-show="open"
                    x-transition
                    class="absolute z-50 mt-2 w-48 rounded-xl shadow-lg
                           bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700
                           overflow-hidden"
                    style="right:0">

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
                                <span class="text-xs text-gray-400 ml-auto uppercase">{{ $lang->code }}</span>
                                @if($currentLocale === $lang->code)
                                    <x-heroicon-m-check class="w-4 h-4 text-primary-500 shrink-0"/>
                                @endif
                            </button>
                        </form>
                    @endforeach

                </div>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
