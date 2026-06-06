<div class="breadcrumb-area breadcrumb-height"
     data-bg-image="{{ asset('assets/images/breadcrumb/bg/1.jpg') }}">
    <div class="container">
        <div class="breadcrumb-content">
            @isset($subtitle)
                <span class="breadcrumb-sub-title">{{ $subtitle }}</span>
            @endisset
            <h1 class="breadcrumb-title mb-1">{{ $title }}</h1>
            @isset($desc)
                <p class="breadcrumb-desc font-size-20">{{ $desc }}</p>
            @endisset
        </div>
    </div>
</div>
