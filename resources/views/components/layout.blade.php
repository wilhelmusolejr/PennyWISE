<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <!-- JQuery -->
    <script defer src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <!-- Font Awesome -->
    <script defer src="https://kit.fontawesome.com/6b2bcc8033.js" crossorigin="anonymous"></script>

    <!-- custom -->
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <script defer src="{{ asset('js/dashboard.js') }}"></script>
</head>

<body>

    <x-loader-logo></x-loader-logo>

    {{-- NAVIGATOR --}}
    <nav class="px-sm-5 d-flex">
        <div class="container-fluid d-flex align-items-center justify-content-between">
            <div>
                <a href="/dashboard" class="fs-1 logo text-decoration-none">Penny<span>WISE</span></a>
            </div>
            <div class="d-flex align-items-center gap-4">
                <i class="fa-regular fa-bell"></i>
                <div class="user dropdown d-flex align-items-center gap-2" data-bs-toggle="dropdown" role="button">
                    <img src="{{ $user->profile_picture ? asset('storage/' . $user->profile_picture) : asset('images/dummy.jpg') }}" alt="Profile Picture" class="rounded-circle">
                    <p class="d-sm-block d-none">{{ $user->first_name." ".$user->last_name }}</p>
                    <i class="fa-solid fa-caret-down"></i>

                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item d-none" href="#">Profile</a></li>
                        <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#settingModal">Settings</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a href="{{ route('logout') }}" class="dropdown-item"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                               Logout
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    {{ $slot }}

</body>

</html>
