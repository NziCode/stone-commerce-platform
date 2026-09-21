{{-- The "Request a quote" pop-up, shared by every inquiry button of the storefront (cards and product page).
     Three ways to get the price: ask on WhatsApp (the message already names the stone), call, or leave a number
     to be called back - that last one is saved for the sales team and announced to the owner. --}}
@php $inquiryPhone = display_phone(\App\Models\Setting::get('site_phone')); @endphp

<div class="modal fade" id="inquiryModal" tabindex="-1" aria-hidden="true" aria-labelledby="inquiryModalTitle">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:var(--radius);border:none">
            <form id="inquiryForm" method="POST" novalidate
                  data-sending="{{ __('messages.inquiry_sending') }}"
                  data-invalid="{{ __('messages.inquiry_phone_invalid') }}"
                  data-error="{{ __('messages.inquiry_error') }}">
                @csrf
                <div class="modal-header" style="border-bottom:1px solid var(--stone-100)">
                    <div>
                        <h5 class="modal-title" id="inquiryModalTitle" style="font-weight:800;color:var(--ink);font-size:1.05rem">{{ __('messages.inquiry') }}</h5>
                        <div class="mt-inq-stone" data-inq-stone></div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('messages.close') }}"></button>
                </div>

                {{-- what the visitor sees after the request was sent --}}
                <div class="modal-body mt-inq-done" data-inq-done hidden>
                    <svg viewBox="0 0 24 24" width="44" height="44" fill="none" stroke="#1f9d55" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="m8 12.5 2.8 2.8L16 9.5"/></svg>
                    <p data-inq-done-text></p>
                    <button type="button" class="mt-btn mt-btn-primary" data-bs-dismiss="modal">{{ __('messages.close') }}</button>
                </div>

                <div data-inq-fields>
                    <div class="modal-body" style="padding:1.2rem 1.4rem">
                        <p class="mt-inq-intro">{{ __('messages.inquiry_intro') }}</p>

                        <div class="mt-inq-quick">
                            <a href="#" target="_blank" rel="noopener noreferrer" class="mt-btn mt-inq-wa" data-inq-wa hidden>
                                <svg viewBox="0 0 24 24" fill="currentColor" width="18" height="18" aria-hidden="true"><path d="M17.47 14.38c-.28-.14-1.64-.81-1.9-.9-.25-.1-.44-.14-.62.14-.18.27-.71.9-.87 1.08-.16.18-.32.2-.6.07-.27-.14-1.16-.43-2.2-1.36-.82-.73-1.36-1.62-1.53-1.9-.16-.27-.02-.42.12-.56.13-.13.27-.32.41-.49.14-.16.18-.27.27-.46.09-.18.05-.34-.02-.48-.07-.14-.62-1.5-.86-2.05-.22-.54-.45-.46-.62-.47-.16 0-.34-.01-.53-.01-.18 0-.48.07-.73.34-.25.27-.96.94-.96 2.29 0 1.35.98 2.66 1.12 2.84.14.18 1.93 2.95 4.68 4.13.65.28 1.16.45 1.56.58.66.21 1.25.18 1.72.11.52-.08 1.64-.67 1.87-1.32.23-.65.23-1.2.16-1.32-.07-.12-.25-.18-.53-.32z"/><path d="M12 2C6.48 2 2 6.48 2 12c0 1.85.5 3.58 1.37 5.07L2 22l5.07-1.33A9.96 9.96 0 0 0 12 22c5.52 0 10-4.48 10-10S17.52 2 12 2zm0 18.2a8.2 8.2 0 0 1-4.18-1.14l-.3-.18-3.01.79.8-2.93-.2-.31A8.2 8.2 0 1 1 12 20.2z" fill-rule="evenodd"/></svg>
                                {{ __('messages.whatsapp') }}
                            </a>
                            @if($inquiryPhone)
                                <a href="tel:{{ $inquiryPhone }}" class="mt-btn mt-btn-outline">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.8 19.8 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.8 19.8 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.91.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                                    <span dir="ltr">{{ $inquiryPhone }}</span>
                                </a>
                            @endif
                        </div>

                        <div class="mt-inq-or"><span>{{ __('messages.inquiry_or_leave') }}</span></div>

                        <label class="mt-inq-label" for="inquiryName">{{ __('messages.full_name') }}</label>
                        <input type="text" id="inquiryName" name="name" maxlength="150" autocomplete="name" class="mt-inq-input"
                               value="{{ auth()->user()->name ?? '' }}">

                        <label class="mt-inq-label" for="inquiryPhone">{{ __('messages.phone_number') }} <span style="color:#e0473a">*</span></label>
                        <div style="display:flex;gap:.5rem">
                            <select name="phone_country" class="mt-inq-input" style="flex:0 0 110px;padding-inline:.5rem;font-size:.85rem" aria-label="{{ __('messages.phone_number') }}">
                                <option value="+98" selected>🇮🇷 +98</option>
                                <option value="+971">🇦🇪 +971</option>
                                <option value="+90">🇹🇷 +90</option>
                                <option value="+966">🇸🇦 +966</option>
                                <option value="+974">🇶🇦 +974</option>
                                <option value="+968">🇴🇲 +968</option>
                                <option value="+965">🇰🇼 +965</option>
                                <option value="+973">🇧🇭 +973</option>
                                <option value="+964">🇮🇶 +964</option>
                                <option value="+92">🇵🇰 +92</option>
                                <option value="+91">🇮🇳 +91</option>
                                <option value="+86">🇨🇳 +86</option>
                                <option value="+39">🇮🇹 +39</option>
                                <option value="+49">🇩🇪 +49</option>
                                <option value="+44">🇬🇧 +44</option>
                                <option value="+1">🇺🇸 +1</option>
                                <option value="other">{{ __('messages.other_country') }}</option>
                            </select>
                            <input type="text" id="inquiryPhone" name="phone" inputmode="tel" autocomplete="tel-national" placeholder="912 345 6789"
                                   class="mt-inq-input" style="flex:1;direction:ltr;text-align:start" dir="ltr">
                        </div>

                        <span class="mt-inq-label">{{ __('messages.preferred_contact_method') }}</span>
                        <div style="display:flex;gap:1.3rem;margin-bottom:.8rem">
                            <label style="display:flex;align-items:center;gap:.4rem;font-size:.85rem;cursor:pointer">
                                <input type="radio" name="contact_method" value="call" checked> {{ __('messages.phone_call') }}
                            </label>
                            <label style="display:flex;align-items:center;gap:.4rem;font-size:.85rem;cursor:pointer">
                                <input type="radio" name="contact_method" value="whatsapp"> {{ __('messages.whatsapp') }}
                            </label>
                        </div>

                        <label class="mt-inq-label" for="inquiryNote">{{ __('messages.note_optional') }}</label>
                        <textarea id="inquiryNote" name="note" rows="2" maxlength="1000" class="mt-inq-input"></textarea>

                        <div class="mt-inq-error" data-inq-error role="alert" hidden></div>
                    </div>
                    <div class="modal-footer" style="border-top:1px solid var(--stone-100)">
                        <button type="button" class="mt-btn mt-btn-outline" data-bs-dismiss="modal">{{ __('messages.cancel') }}</button>
                        <button type="submit" class="mt-btn mt-btn-primary" data-inq-submit>{{ __('messages.inquiry_submit') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
(function () {
    var modalEl = document.getElementById('inquiryModal');
    // without Bootstrap's modal the buttons stay ordinary links to the contact page
    if (!modalEl || !window.bootstrap || !bootstrap.Modal) return;

    var modal   = bootstrap.Modal.getOrCreateInstance(modalEl);
    var form    = document.getElementById('inquiryForm');
    var fields  = modalEl.querySelector('[data-inq-fields]');
    var done    = modalEl.querySelector('[data-inq-done]');
    var doneTxt = modalEl.querySelector('[data-inq-done-text]');
    var stone   = modalEl.querySelector('[data-inq-stone]');
    var wa      = modalEl.querySelector('[data-inq-wa]');
    var errorEl = modalEl.querySelector('[data-inq-error]');
    var submit  = modalEl.querySelector('[data-inq-submit]');
    var idle    = submit.textContent.trim();

    function showError(text) { errorEl.textContent = text; errorEl.hidden = !text; }

    function reset() {
        fields.hidden = false;
        done.hidden = true;
        showError('');
        submit.disabled = false;
        submit.textContent = idle;
    }

    document.addEventListener('click', function (e) {
        var btn = e.target.closest('[data-inquiry]');
        if (!btn) return;

        e.preventDefault();
        form.action = btn.getAttribute('data-inquiry-url');
        // the stone's name often carries its code already ("… کد ۱۰۰۶"): add the code only when it is not in the name
        var name = btn.getAttribute('data-inquiry-name') || '';
        var code = btn.getAttribute('data-inquiry-code') || '';
        stone.textContent = code && name.indexOf(code) === -1 ? name + ' — ' + code : name;

        var waUrl = btn.getAttribute('data-inquiry-wa');
        wa.hidden = !waUrl;
        if (waUrl) wa.href = waUrl;

        reset();
        modal.show();
    });

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        showError('');

        var digits = (form.elements.phone.value || '').replace(/\D+/g, '');
        if (digits.length < 5) {
            form.elements.phone.focus();
            showError(form.getAttribute('data-invalid'));
            return;
        }

        submit.disabled = true;
        submit.textContent = form.getAttribute('data-sending');

        fetch(form.action, {
            method: 'POST',
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            body: new FormData(form),
            credentials: 'same-origin'
        }).then(function (res) {
            return res.json().catch(function () { return {}; }).then(function (data) { return { status: res.status, data: data }; });
        }).then(function (r) {
            if (r.status === 200 && r.data.ok) {
                doneTxt.textContent = r.data.message || '';
                fields.hidden = true;
                done.hidden = false;
                return;
            }

            var invalidPhone = r.status === 422 && r.data && r.data.errors && r.data.errors.phone;
            showError(invalidPhone ? form.getAttribute('data-invalid') : ((r.data && r.data.message) || form.getAttribute('data-error')));
            submit.disabled = false;
            submit.textContent = idle;
        }).catch(function () {
            showError(form.getAttribute('data-error'));
            submit.disabled = false;
            submit.textContent = idle;
        });
    });
})();
</script>
@endpush
