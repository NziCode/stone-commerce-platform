{{-- "Request a quote" button of a stone. It opens the inquiry pop-up (front.partials.inquiry-modal), where the visitor asks on
     WhatsApp, calls, or leaves a number to be called back. The href is the fallback for a browser without JavaScript.
     Sold stones cannot be asked about, so they get no button.
     Needs $product; optional $class (the button's style) and $block (full width). --}}
@if($product->status !== 'sold')
    @php
        $inquiryLocale = app()->getLocale();
        $inquiryName   = $product->getTranslation('name', $inquiryLocale);
        $inquiryWaCode = $product->sku ? __('messages.pd_wa_code', ['sku' => $product->sku]) : '';
        $inquiryWaUrl  = \App\Support\WhatsApp::siteUrl(trim(preg_replace('/\s+/u', ' ', __('messages.pd_wa_message', [
            'name' => $inquiryName,
            'code' => $inquiryWaCode,
            'url'  => route('products.show', $product->getTranslation('slug', $inquiryLocale)),
        ]))));
    @endphp
    <a href="{{ route('contact') }}?product={{ $product->sku }}"
       class="{{ $class ?? 'mt-btn mt-btn-primary' }}"
       @if(!empty($block)) style="width:100%;justify-content:center" @endif
       data-inquiry
       data-inquiry-url="{{ route('products.inquiry', $product) }}"
       data-inquiry-name="{{ $inquiryName }}"
       data-inquiry-code="{{ $product->sku }}"
       data-inquiry-wa="{{ $inquiryWaUrl }}">
        {{ __('messages.inquiry') }}
    </a>
@endif
