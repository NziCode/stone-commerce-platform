@php
    $activeLangs = \App\Services\LanguageService::getActive();
    $defaultCode = \App\Services\LanguageService::getDefault()?->code ?? ($activeLangs->first()->code ?? 'fa');
@endphp

<div x-data="{ loc: '{{ $defaultCode }}' }">
    <div style="display:flex;flex-wrap:wrap;gap:.35rem;margin-bottom:1rem">
        @foreach($activeLangs as $lang)
            <button type="button" @click="loc = '{{ $lang->code }}'"
                :style="'display:inline-flex;align-items:center;padding:.4rem .8rem;border-radius:7px;border:none;cursor:pointer;font-size:.76rem;font-weight:600;font-family:inherit;transition:all .15s;' + (loc === '{{ $lang->code }}' ? 'background:#ff5a1f;color:#fff' : 'color:#6b7280;background:#f3f4f6')">
                {{ $lang->native_name }}
            </button>
        @endforeach
    </div>

    <div style="display:grid;gap:1.1rem">
        @foreach($fields as $field)
            <div>
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:.2rem">
                    <label style="{{ $labelStyle }}margin-bottom:0">{{ $field['label'] }}</label>
                    <button type="button"
                        wire:click="mountAction('translateField', { field: '{{ $field['key'] }}', isHtml: false })"
                        style="display:inline-flex;align-items:center;gap:.3rem;padding:.25rem .55rem;border-radius:6px;border:none;cursor:pointer;background:transparent;color:#ff5a1f;font-size:.72rem;font-weight:600;font-family:inherit">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="13" height="13"><path d="m5 8 6 6M4 14l6-6 2-3M2 5h12M7 2h1m10 20-4-9-4 9m1.5-3.5h5"/></svg>
                        {{ __('admin.translate_automatically') }}
                    </button>
                </div>

                @if($field['help'] ?? null)
                    <p style="font-size:.72rem;color:#9ca3af;margin:0 0 .4rem">{{ $field['help'] }}</p>
                @endif

                @foreach($activeLangs as $lang)
                    <div x-show="loc === '{{ $lang->code }}'" x-cloak>
                        @if(($field['type'] ?? 'text') === 'textarea')
                            <textarea wire:model.defer="{{ $field['key'] }}.{{ $lang->code }}" rows="{{ $field['rows'] ?? 3 }}" dir="{{ $lang->direction }}" class="{{ $inputClass }}"></textarea>
                        @else
                            <input type="text" wire:model.defer="{{ $field['key'] }}.{{ $lang->code }}" dir="{{ $lang->direction }}" class="{{ $inputClass }}">
                        @endif
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>
</div>
