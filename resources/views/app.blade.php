<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="author" content="Vitaly Kasymov">
        <meta name="description" content="">

        <title>{{ config('app.name') }}</title>

        <link href="{{ mix('/css/app.css') }}" rel="stylesheet">
        <link rel="icon" href="{{ asset('/img/vcdi.png') }}" type="image/png" sizes="16x16">

        <script>
            window.Laravel = {!! json_encode([
                'siteName' => config('app.name'),
                'siteUrl' => config('app.url'),
                'apiUrl' => config('app.url') . '/api'
            ]) !!};
        </script>
    </head>
    <body>
        <div id="app"></div>

        <script src="{{ mix('/js/app.js') }}"></script>
    </body>
</html>
