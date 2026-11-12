<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Connecting Skills to Opportunities') | Connect Me</title>
    <link rel="icon" href="{{ asset('assets/img/logo.png') }}">
    @include('partials.head')
</head>
<body>
@include('partials.nav')
<main>
    @if(session('success') || session('error') || $errors->any())
        <div class="container pt-3">@include('partials.flash')</div>
    @endif
    @yield('content')
</main>
@include('partials.footer')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@include('partials.theme-script')
</body>
</html>
