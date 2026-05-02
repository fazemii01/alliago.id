<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $title ?? 'Alliago.id | Visa Assistance' }}</title>
    <link rel="icon" type="image/jpeg" href="/favicon.jpeg">
    <link rel="apple-touch-icon" href="/images/alliago-logo.jpeg">
    
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.ts'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
    @endif

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
</head>
<body>
    {{ $slot }}
    
    @stack('scripts')
</body>
</html>
