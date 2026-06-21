@php $crumbs = $crumbs ?? null; @endphp
<div class="mt-pagehead">
    <div class="mt-container">
        <div class="mt-pagehead-inner">
            @isset($subtitle)
                <span class="mt-eyebrow" style="color:var(--brand-2)">{{ $subtitle }}</span>
            @endisset
            <h1 class="mt-display">{{ $title }}</h1>
            @isset($desc)
                <p>{{ $desc }}</p>
            @endisset
            <ul class="mt-crumbs">
                <li><a href="{{ route('home') }}">{{ __('messages.home') }}</a></li>
                @if($crumbs)
                    @foreach($crumbs as $crumb)
                        <li>
                            @if(!empty($crumb['url']))
                                <a href="{{ $crumb['url'] }}">{{ $crumb['label'] }}</a>
                            @else
                                <span class="is-active">{{ $crumb['label'] }}</span>
                            @endif
                        </li>
                    @endforeach
                @else
                    <li><span class="is-active">{{ $title }}</span></li>
                @endif
            </ul>
        </div>
    </div>
</div>
