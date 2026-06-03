@props([
    'title' => 'Alliago.id | Visa Assistance',
    'description' => 'Alliago.id — layanan visa dan tiket pesawat terpercaya untuk traveler, keluarga, pelajar, dan perjalanan bisnis internasional.',
    'canonical' => null,
    'ogType' => 'website',
    'ogImage' => null,
])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Alliago.id | Visa Assistance' }}</title>
    <meta name="description" content="{{ $description ?? 'Alliago.id — layanan visa dan tiket pesawat terpercaya untuk traveler, keluarga, pelajar, dan perjalanan bisnis internasional.' }}" />
    <meta name="keywords" content="alliago, alliago.id, visa assistance, jasa visa indonesia, japan visa, korea visa, australia visa, schengen visa" />
    <meta name="robots" content="index, follow" />
    <link rel="canonical" href="{{ $canonical ?: url()->current() }}" />

    <meta property="og:type" content="{{ $ogType ?? 'website' }}" />
    <meta property="og:title" content="{{ $title ?? 'Alliago.id | Visa Assistance' }}" />
    <meta property="og:description" content="{{ $description ?? 'Alliago.id — layanan visa dan tiket pesawat terpercaya untuk traveler, keluarga, pelajar, dan perjalanan bisnis internasional.' }}" />
    <meta property="og:url" content="{{ $canonical ?: url()->current() }}" />
    <meta property="og:image" content="{{ $ogImage ?: asset('images/alliago-logo.jpeg') }}" />
    <meta property="og:site_name" content="Alliago.id" />

    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="{{ $title ?? 'Alliago.id | Visa Assistance' }}" />
    <meta name="twitter:description" content="{{ $description ?? 'Alliago.id — layanan visa dan tiket pesawat terpercaya untuk traveler, keluarga, pelajar, dan perjalanan bisnis internasional.' }}" />
    <meta name="twitter:image" content="{{ $ogImage ?: asset('images/alliago-logo.jpeg') }}" />

    <link rel="icon" type="image/jpeg" href="/favicon.jpeg">
    <link rel="apple-touch-icon" href="/images/alliago-logo.jpeg">

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.ts'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
    @endif

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    @stack('meta')
</head>
<body class="pb-16 md:pb-0">
    {{ $slot }}

    <x-home.bottom-nav />

    @stack('scripts')
</body>
</html>
