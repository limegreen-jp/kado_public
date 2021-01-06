<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{ asset('/css/reset.css') }}">
    <link rel="stylesheet" href="{{ asset('/js/lib/tablesorter/theme.default.min.css') }}">
    @yield('link_css')
    <link rel="stylesheet" href="{{ asset('/css/app.css') }}">

    <script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>
    <script src="{{ asset('/js/lib/tablesorter/jquery.tablesorter.min.js') }}"></script>
    @yield('script_src')
    <script src="{{ asset('/js/script.js') }}"></script>
</head>
<body>
    <x-header />
    <main class="l-main">
        <div class="main-inner">
            @yield('content')
        </div>
        <x-side-nav />
    </main>
    @yield('script')
    @yield('style')
    <footer class="l-footer"></footer>
</body>
</html>