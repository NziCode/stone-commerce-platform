<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.2rem">
    <x-filament::button size="sm" color="gray" icon="heroicon-o-language" type="button"
        wire:click="mountAction('translateGroup', { group: '{{ $group }}' })">
        {{ __('admin.translate_automatically') }}
    </x-filament::button>
    <x-filament::button type="submit" icon="heroicon-o-check-circle">
        {{ __('admin.save_settings') }}
    </x-filament::button>
</div>
