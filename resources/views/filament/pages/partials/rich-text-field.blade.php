{{-- Minimal Trix editor bound to a single Livewire state path (e.g. "site_tagline.fa").
     Uses the same trix-editor element / Alpine component Filament's own RichEditor form
     field uses, hand-wired here since this page isn't built on Filament's Form Builder. --}}
@php
    $trixId = 'trix-' . str($statePath)->slug();
@endphp

<div
    ax-load
    ax-load-src="{{ \Filament\Support\Facades\FilamentAsset::getAlpineComponentSrc('rich-editor', 'filament/forms') }}"
    x-data="richEditorFormComponent({ state: $wire.entangle('{{ $statePath }}', false) })"
    x-ignore
    x-on:trix-change="
        let value = $event.target.value
        $nextTick(() => { if (! $refs.trix) return; state = value })
    "
    style="border:1px solid #d1d5db;border-radius:.5rem;overflow:hidden"
>
    <input id="trix-value-{{ $trixId }}" x-ref="trixValue" type="hidden" />

    <trix-toolbar id="trix-toolbar-{{ $trixId }}" style="border-bottom:1px solid #f3f4f6;padding:.35rem .5rem;background:#fafafa">
        <div style="display:flex;gap:.25rem;overflow-x:auto">
            <x-filament-forms::rich-editor.toolbar.group data-trix-button-group="text-tools">
                <x-filament-forms::rich-editor.toolbar.button data-trix-attribute="bold" data-trix-key="b" title="{{ __('filament-forms::components.rich_editor.toolbar_buttons.bold') }}" tabindex="-1">
                    <x-filament::icon icon="heroicon-m-bold" class="h-4 w-4" />
                </x-filament-forms::rich-editor.toolbar.button>
                <x-filament-forms::rich-editor.toolbar.button data-trix-attribute="italic" data-trix-key="i" title="{{ __('filament-forms::components.rich_editor.toolbar_buttons.italic') }}" tabindex="-1">
                    <x-filament::icon icon="heroicon-m-italic" class="h-4 w-4" />
                </x-filament-forms::rich-editor.toolbar.button>
                <x-filament-forms::rich-editor.toolbar.button data-trix-attribute="underline" data-trix-key="u" title="{{ __('filament-forms::components.rich_editor.toolbar_buttons.underline') }}" tabindex="-1">
                    <x-filament::icon icon="heroicon-m-underline" class="h-4 w-4" />
                </x-filament-forms::rich-editor.toolbar.button>
                <x-filament-forms::rich-editor.toolbar.button data-trix-attribute="href" data-trix-action="link" data-trix-key="k" title="{{ __('filament-forms::components.rich_editor.toolbar_buttons.link') }}" tabindex="-1">
                    <x-filament::icon icon="heroicon-m-link" class="h-4 w-4" />
                </x-filament-forms::rich-editor.toolbar.button>
            </x-filament-forms::rich-editor.toolbar.group>
        </div>

        <div x-cloak data-trix-dialogs class="trix-dialogs">
            <div data-trix-dialog="href" data-trix-dialog-attribute="href" class="trix-dialog trix-dialog--link">
                <div class="trix-dialog__link-fields">
                    <input aria-label="{{ __('filament-forms::components.rich_editor.dialogs.link.label') }}" data-trix-input disabled name="href" placeholder="{{ __('filament-forms::components.rich_editor.dialogs.link.placeholder') }}" required type="text" inputmode="url" class="trix-input trix-input--dialog" />
                    <div class="trix-button-group">
                        <input data-trix-method="setAttribute" type="button" value="{{ __('filament-forms::components.rich_editor.dialogs.link.actions.link') }}" class="trix-button trix-button--dialog" />
                        <input data-trix-method="removeAttribute" type="button" value="{{ __('filament-forms::components.rich_editor.dialogs.link.actions.unlink') }}" class="trix-button trix-button--dialog" />
                    </div>
                </div>
            </div>
        </div>
    </trix-toolbar>

    <trix-editor
        id="{{ $trixId }}"
        input="trix-value-{{ $trixId }}"
        toolbar="trix-toolbar-{{ $trixId }}"
        x-ref="trix"
        wire:ignore
        style="min-height:90px;padding:.5rem .75rem;font-size:.85rem"
    ></trix-editor>
</div>
