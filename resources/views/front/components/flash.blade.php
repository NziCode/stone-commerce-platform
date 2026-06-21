@if(session('success'))
    <div data-auto-hide class="mt-toast is-success">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
        <span>{{ session('success') }}</span>
    </div>
@endif

@if(session('error'))
    <div data-auto-hide class="mt-toast is-error">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
        <span>{{ session('error') }}</span>
    </div>
@endif

@if(session('info'))
    <div data-auto-hide class="mt-toast is-info">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <span>{{ session('info') }}</span>
    </div>
@endif

@if($errors->any())
    <div data-auto-hide class="mt-toast is-error">
        <ul>
            @foreach($errors->all() as $error)
                <li>
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ $error }}
                </li>
            @endforeach
        </ul>
    </div>
@endif

<script>
    document.querySelectorAll('[data-auto-hide]').forEach(function (el) {
        setTimeout(function () {
            el.style.transition = 'opacity .4s ease, transform .4s ease';
            el.style.opacity = '0';
            el.style.transform = 'translateX(-50%) translateY(-10px)';
            setTimeout(function () { el.remove(); }, 400);
        }, 4000);
    });
</script>
