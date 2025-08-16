<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">

<meta property="og:image" content="{{ theme_asset('brand/social-card.jpg') }}"/>
<meta property="og:title" content="{{ config('app.name') }}"/>
<meta property="og:description"
      content="Yulan Mu - Ancient. Awaken. Alive. An age-old saga, reborn in your hands."/>
<meta property="og:url" content="{{ url()->current() }}"/>
<meta property="og:type" content="website"/>

<title>{{ config('app.name', 'Home') }}</title>

<!-- Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400..600&display=swap" rel="stylesheet">

<!-- Favicon -->
<link rel="apple-touch-icon" sizes="180x180" href="{{ theme_favicon('light', 'apple-touch-icon.png') }}">
<link rel="icon" type="image/png" sizes="32x32" href="{{ theme_favicon('light', 'favicon-32x32.png') }}" id="favicon-32">
<link rel="icon" type="image/png" sizes="16x16" href="{{ theme_favicon('light', 'favicon-16x16.png') }}" id="favicon-16">
<link rel="icon" href="{{ theme_favicon('light', 'favicon.ico') }}" id="favicon-ico">
<link rel="manifest" href="{{ theme_favicon('light', 'site.webmanifest') }}">

<!-- Google tag (gtag.js) -->
<!--
<script async src="https://www.googletagmanager.com/gtag/js?id=G-9FQ4QV8M1J"></script>
<script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
        dataLayer.push(arguments);
    }

    gtag('js', new Date());

    gtag('config', 'G-9FQ4QV8M1J');
</script>
-->

<!-- Twitch SDK -->
<script src="https://embed.twitch.tv/embed/v1.js"></script>

<!-- Scripts -->
@vite(['resources/css/app.css', 'resources/js/app.js'])
@themeAssets
@fluxStyles
