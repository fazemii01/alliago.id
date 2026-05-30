<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $title ?? 'Alliago.id | Visa Assistance' }}</title>
    <link rel="icon" type="image/jpeg" href="/favicon.jpeg">
    <link rel="apple-touch-icon" href="/images/alliago-logo.jpeg">

    {{-- SEO Meta Tags --}}
    <meta name="description" content="Alliago - Platform visa assistance terpercaya untuk traveler, keluarga, pelajar, dan perjalanan bisnis. Japan Visa, Korea Visa, Australia Visa, Schengen Visa.">
    <meta name="keywords" content="alliago, alliago.id, visa assistance, jasa visa indonesia, japan visa, korea visa, australia visa, schengen visa">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url()->current() }}">

    {{-- Open Graph --}}
    <meta property="og:title" content="{{ $title ?? 'Alliago.id | Visa Assistance' }}">
    <meta property="og:description" content="Platform visa assistance terpercaya di Indonesia untuk semua jenis visa.">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Alliago.id">
    <meta property="og:image" content="{{ asset('images/alliago-logo.jpeg') }}">

    {{-- Structured Data --}}
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Organization",
        "name": "Alliago",
        "url": "https://alliago.id",
        "logo": "https://alliago.id/images/alliago-logo.jpeg",
        "description": "Platform visa assistance terpercaya di Indonesia"
    }
    </script>

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.ts'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
    @endif
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
</head>