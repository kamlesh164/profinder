<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'ProFinder - India\'s #1 Platform Connecting Customers with Trusted Contractors' }}</title>
    <link rel="icon" type="image/svg+xml" href="/favicon.ico">
    
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    @foreach($Css ?? [] as $css)
        <link rel="stylesheet" href="{{ asset($css) }}">
    @endforeach
</head>
<body>
    @php
    $setting = App\Models\Setting::first();
    $logo = $setting?->logo && file_exists(public_path($setting->logo))
            ? asset($setting->logo)
            : asset('images/logo-final.png');
    @endphp
    @include('layouts.front_header')

    @if(!empty($T_Body))
        @include($T_Body)
    @endif

    @include('layouts.front_footer')

    @foreach($Js ?? [] as $js)
        <script src="{{ asset($js) }}"></script>
    @endforeach
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 700,
            once: true,
            delay: 100,
        });
    </script>
    </body>
</html>