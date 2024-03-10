<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="icon" href="{{ config('app.url') }}/favicon.png" type="image/png">

    <link rel="stylesheet"
        href="{{ app()->environment('production') ? secure_asset('css/app.css') : asset('css/app.css') }}">

    <script src="{{ app()->environment('production') ? secure_asset('js/app.js') : asset('js/app.js') }}" defer></script>
</head>

<body>

    <body class="font-sans antialiased bg-dark text-light">
        <div class="container">
            <div class="card bg-dark text-light mt-5">
                <div class="card-head mb-5 text-center fw-bold h1 text-secondary">
                    @if (isset($code))
                        <span>{{ $code }}</span>
                    @endif
                </div>
                <div class="card-body text-center mt-5">
                    <div class="mb-2">
                        @if (isset($message))
                            <span>{{ $message }}</span>
                        @endif
                    </div>
                    <div class="mt-5">
                        @if (isset($code) && $code == 401)
                            <form action="/login" method="GET">
                                <button type="submit" class="btn btn-primary">
                                    Ir al login
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </body>

</html>
