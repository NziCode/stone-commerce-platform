@php
    $activeLangs = \App\Services\LanguageService::getActive();
    $defaultCode = \App\Services\LanguageService::getDefault()?->code ?? ($activeLangs->first()->code ?? 'fa');
@endphp

<div x-data="{ loc: '{{ $defaultCode }}' }">
    <div style="display:flex;flex-wrap:wrap;gap:.35rem;margin-bottom:.9rem">
        @foreach($activeLangs as $lang)
            <button type="button" @click="loc = '{{ $lang->code }}'"
                :style="'display:inline-flex;align-items:center;padding:.4rem .8rem;border-radius:7px;border:none;cursor:pointer;font-size:.76rem;font-weight:600;font-family:inherit;transition:all .15s;' + (loc === '{{ $lang->code }}' ? 'background:#ff5a1f;color:#fff' : 'color:#6b7280;background:#f3f4f6')">
                {{ $lang->native_name }}
            </button>
        @endforeach
    </div>

    @foreach($activeLangs as $lang)
        <div x-show="loc === '{{ $lang->code }}'" x-cloak style="display:grid;gap:.9rem">
            @foreach($fields as $field)
                <div>
                    <label style="{{ $labelStyle }}">{{ $field['label'] }}</label>
                    @if(($field['type'] ?? 'text') === 'textarea')
                        <textarea wire:model.defer="{{ $field['key'] }}.{{ $lang->code }}" rows="{{ $field['rows'] ?? 3 }}" class="{{ $inputClass }}"></textarea>
                    @else
                        <input type="text" wire:model.defer="{{ $field['key'] }}.{{ $lang->code }}" class="{{ $inputClass }}">
                    @endif
                </div>
            @endforeach
        </div>
    @endforeach
</div>
