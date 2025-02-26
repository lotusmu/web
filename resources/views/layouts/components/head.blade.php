<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">

<meta property="og:image" content="{{ asset('images/social-card.jpg') }}"/>
<meta property="og:title" content="{{ config('app.name') }}"/>
<meta property="og:description"
      content="Lotus Mu - Reimagined. Refined. Revolutionar. MU Online server with soul. Thoughtfully crafted, not copy-pasted. Join this fantasy MMORPG experience today."/>
<meta property="og:url" content="{{ url()->current() }}"/>
<meta property="og:type" content="website"/>

<title>{{ config('app.name', 'Home') }}</title>

<!-- Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400..600&display=swap" rel="stylesheet">

<!-- Favicon -->
<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
<link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
<link rel="manifest" href="{{ asset('site.webmanifest') }}">

<!-- Scripts -->
@vite(['resources/css/app.css', 'resources/js/app.js'])
@fluxStyles
