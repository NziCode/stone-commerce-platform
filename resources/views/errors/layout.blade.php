<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ in_array(app()->getLocale(), ['fa','ar']) ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') — {{ \App\Models\Setting::get('site_name', config('app.name')) }}</title>
    <meta name="robots" content="noindex,nofollow">
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@400;600;700;800&family=Inter:wght@400;600;700&display=swap">

    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    @if(in_array(app()->getLocale(), ['fa','ar']))
        <link rel="stylesheet" href="{{ asset('assets/css/rtl.css') }}">
    @else
        <link rel="stylesheet" href="{{ asset('assets/css/ltr.css') }}">
    @endif
    <link rel="stylesheet" href="{{ asset('assets/css/theme-modern.css') }}">

    <style>
        :root{
            --brand:#ff5a1f; --brand-2:#ff8a3d; --ink:#0b2147; --ink-2:#123a7a;
            --stone-50:#f6f3ee; --stone-100:#efe9e0; --stone-500:#8a8071;
        }
        *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
        body{
            min-height:100vh; display:flex; flex-direction:column;
            font-family:'Vazirmatn','Inter',sans-serif;
            background: radial-gradient(1000px 500px at 5% -5%, rgba(255,90,31,.08), transparent 60%),
                        radial-gradient(800px 400px at 105% 10%, rgba(18,58,122,.07), transparent 60%),
                        var(--stone-50);
        }
        .err-wrap{
            flex:1; display:flex; flex-direction:column; align-items:center;
            justify-content:center; padding:2rem 1.5rem; text-align:center;
        }
        .err-code{
            font-size:clamp(5rem,16vw,9rem); font-weight:800; line-height:1;
            background:linear-gradient(135deg, var(--ink) 30%, var(--ink-2) 70%);
            -webkit-background-clip:text; -webkit-text-fill-color:transparent;
            background-clip:text; position:relative; display:inline-block;
            margin-bottom:.3rem;
        }
        .err-code::after{
            content:''; position:absolute; inset-inline-start:50%; transform:translateX(-50%);
            bottom:-8px; width:60px; height:4px; border-radius:4px;
            background:linear-gradient(90deg,var(--brand),var(--brand-2));
        }
        .err-title{
            font-size:clamp(1.3rem,3vw,1.85rem); font-weight:800; color:var(--ink);
            margin:1.5rem 0 .7rem;
        }
        .err-desc{
            font-size:1rem; color:var(--stone-500); max-width:52ch;
            line-height:1.85; margin:0 auto 2rem;
        }
        .err-actions{ display:flex; gap:.8rem; flex-wrap:wrap; justify-content:center; }
        .err-btn{
            display:inline-flex; align-items:center; gap:.5rem;
            font-weight:700; font-size:.92rem; padding:.85rem 1.6rem;
            border-radius:999px; text-decoration:none; transition:.2s;
            border:1px solid transparent;
        }
        .err-btn-primary{
            background:linear-gradient(135deg,var(--brand),var(--brand-2));
            color:#fff; box-shadow:0 12px 24px -10px rgba(255,90,31,.5);
        }
        .err-btn-primary:hover{ transform:translateY(-2px); color:#fff; }
        .err-btn-outline{
            background:#fff; color:var(--ink); border-color:rgba(11,33,71,.12);
            box-shadow:0 2px 8px rgba(0,0,0,.06);
        }
        .err-btn-outline:hover{ border-color:var(--ink); transform:translateY(-2px); }
        .err-icon{
            width:clamp(80px,16vw,120px); height:clamp(80px,16vw,120px);
            border-radius:50%; display:flex; align-items:center; justify-content:center;
            margin:0 auto 1.4rem;
        }
        .err-icon svg{ width:50%; height:50%; }
        .err-footer{
            padding:1.4rem; border-top:1px solid var(--stone-100);
            text-align:center; font-size:.78rem; color:var(--stone-500);
        }
        .err-footer a{ color:var(--stone-500); text-decoration:none; }
        .err-footer a:hover{ color:var(--brand); }
        @media(max-width:480px){
            .err-btn{ font-size:.84rem; padding:.75rem 1.2rem; }
            .err-actions{ gap:.6rem; }
        }
    </style>
</head>
<body>
    <div class="err-wrap">
        @yield('content')
    </div>
    <footer class="err-footer">
        <a href="{{ url('/') }}">{{ \App\Models\Setting::get('site_name', config('app.name')) }}</a>
        &nbsp;·&nbsp;
        <a href="{{ route('contact') }}">{{ __('messages.contact') }}</a>
    </footer>
</body>
</html>
