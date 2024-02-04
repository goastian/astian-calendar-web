<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name') }}</title>
    <link rel="icon" href="favicon.svg" type="image/svg+xml">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet"
        href="{{ app()->environment('production') ? secure_asset('css/app.css') : asset('css/app.css') }}">
    <script src="{{ app()->environment('production') ? secure_asset('js/app.js') : asset('js/app.js') }}" defer></script>

    <style>
        .calendar-mark .col {
            flex: 0 0 auto;
            margin: 1% auto;
            width: 97%;

            @media (min-width:900px) {
                width: 70%;
            }
        }

        .calendar-mark .list-group {
            width: 49%;
            display: inline-block;
            margin: 1% auto;
        }

        .calendar-mark .form-control {
            width: 100%;

            @media (min-width:900px) {
                width: 30%;
            }
        }

        .calendar-mark .form {
            align-content: flex-start
        }

        .calendar-mark .users ul li {
            list-style: none;
        }

        .calendar-mark .users span:nth-child(1) {}

        .calendar-mark .users span:nth-child(2) {
            float: inline-end;
        }

        .calendar-mark .users span:nth-child(3) {
            float: inline-end;
        }
    </style>
</head>

<body>
    <div class="calendar-mark">
        <div class="row">
            <div class="col">
                <p class="h1 text-uppercase border-bottom">{{ $calendar->title }}</p>
                <p class="border-bottom text-uppercase h6"> <strong>{{ __('Hora del evento') }} :</strong>
                    {{ $calendar->meeting }}</p>
                <p class="border-bottom text-uppercase h6"> <strong>{{ __('Fue creado') }} :</strong>
                    {{ $calendar->created_at }}</p>
                <p class="border-bottom text-uppercase h6"> <strong>{{ __('Ultima actualizacion') }} :</strong>
                    {{ $calendar->created_at }}</p>
                <p class="border-bottom text-uppercase h6"> <strong>{{ __('Estado') }} :</strong>
                    {{ $calendar->deleted_at ? 'Evento Cancelado' : 'Evento Activo' }}</p>

                <div class="card {{ $calendar->body ?: 'hide' }}">
                    <div class="card-body">
                        {!! $calendar->body !!}
                    </div>
                </div>


                <p class="border-bottom">
                    <a target="_blank" href="{{ $calendar->resource }}">
                        {{ __('Ir al recurso') }} <i class="bi bi-arrow-up-right-circle h5"></i>
                    </a>
                </p>

                <form class="form" action="{{ route('calendar.assistance', ['calendar' => $calendar->id]) }}"
                    method="POST" autocomplete="off">
                    @csrf
                    <select class="form-control" name="respuesta" id="respuesta">
                        <option selected>{{ __('Estarás presente en el evento?') }}</option>
                        <option value="si">{{ __('Si') }}</option>
                        <option value="tal vez">{{ __('Tal vez') }}</option>
                        <option value="no">{{ __('No') }}</option>
                    </select>
                    @foreach ($errors->get('respuesta') as $value)
                        <p class="error">{{ $value }}</p>
                    @endforeach

                    <input type="password" id="codigo" name="codigo" class="form-control my-2"
                        placeholder="Introduce tu codigo aquí">
                    @foreach ($errors->get('codigo') as $value)
                        <p class="error">{{ $value }}</p>
                    @endforeach
                    <input type="hidden" id="token" name="token" value="{{ $token }}">
                    <input type="hidden" id="email" name="email" value="{{ $email }}">
                    <button type="submit" class="btn btn-success my-2">{{ __('Responder') }}</button>
                </form>
            </div>

            <div class="col users">
                <p class="h3 text-center text-uppercase border-bottom">{{ __('Usuarios invitados al evento') }}</p>
                @foreach ($users as $key => $value)
                    <ul>
                        <li class="border-bottom {{ $value->email == $email ? 'text-primary' : '' }}">
                            <span>
                                {{ $value->email }}
                            </span>
                            <span class="{{ $value->status ? 'hide' : 'error' }}">
                                {{ $value->status ? '' : 'No ha respondido' }}
                            </span>
                            <span
                                class="{{ $value->status ? '' : 'hide' }} 
                                {{ $value->status == 'si' ? 'text-success' : '' }}  
                                {{ $value->status == 'no' ? 'text-danger' : '' }} 
                                {{ $value->status == 'tal vez' ? 'text-warning' : '' }}">
                                <i class="bi bi-circle-fill"></i>
                            </span>
                        </li>
                    </ul>
                @endforeach
            </div>
        </div>
    </div>
</body>

</html>
