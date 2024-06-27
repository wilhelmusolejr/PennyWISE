<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Loading</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
    <script defer src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script defer src="{{ asset('js/index.js') }}"></script>

    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</head>
<body>

    <div class="home">
        <a href="{{ route('home_dashboard') }}" class="fs-1 logo text-decoration-none">Penny<span>WISE</span></a>
    </div>

    <x-loader-logo></x-loader-logo>

    {{-- LOGIN ELEMENT HERE --}}
    <button type="button" class="btn btn-primary btn-login d-none" data-bs-toggle="modal" data-bs-target="#loginModal">
        Open Login Modal
    </button>

    <!-- Login Modal -->
    <div class="modal fade d-none" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="loginModalLabel">Login</h5>
                    <button type="button" class="btn-close d-none" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('login') }}" id="login_form">
                        @csrf

                        <x-loader></x-loader>

                        <div class="form-body">
                            <x-input-parent class="col-md-12">
                                <x-label for="login_email">Email address</x-label>
                                <x-input type="email" id="login_email" name="login_email" aria-describedby="login_email" :required="true" />
                                <x-input-error id="login_email"></x-input-error>
                            </x-input-parent>

                            <x-input-parent class="col-md-12">
                                <x-label for="login_password">Password</x-label>
                                <x-input type="password" id="login_password" name="login_password" aria-describedby="login_password" :required="true" />
                                <x-input-error id="login_password"></x-input-error>
                            </x-input-parent>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 mt-4" form="login_form" >Login</button>
                    </form>

                </div>

                <div class="model-footer">
                    <div class="d-flex justify-content-between">
                        <a href="#" class="btn btn-link text-dark">Forgot Password?</a>
                        <button type="button" class="btn btn-link text-dark btn-signup" data-bs-toggle="modal" data-bs-target="#signupModal" data-bs-dismiss="modal">Sign Up</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sign Up Modal -->
    <div class="modal fade d-none" id="signupModal" tabindex="-1" aria-labelledby="signupModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="signupModalLabel">Sign Up</h5>
                    <button type="button" class="btn-close d-none" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <form method="POST" action="{{ route("register") }}" id="register_form" enctype="multipart/form-data">
                        @csrf

                        <x-loader></x-loader>

                        <div class="form-body">
                            <div class="d-flex flex-column gap-2">

                                <x-input-parent class="col-md-12">
                                    <x-label for="profile_picture">Profile Picture</x-label>
                                    <x-input type="file" id="profile_picture" name="profile_picture" aria-describedby="profile_picture" :required="false" />
                                    <x-input-error id="profile_picture"></x-input-error>
                                </x-input-parent>

                                <div class="row g-2">
                                    <x-input-parent class="col-md-6">
                                        <x-label for="first_name">First Name</x-label>
                                        <x-input type="text" id="first_name" name="first_name" aria-describedby="first_name" :required="true" />
                                        <x-input-error id="first_name"></x-input-error>
                                    </x-input-parent>

                                    <x-input-parent class="col-md-6">
                                        <x-label for="last_name">Last Name</x-label>
                                        <x-input type="text" id="last_name" name="last_name" aria-describedby="last_name" :required="true" />
                                        <x-input-error id="last_name"></x-input-error>
                                    </x-input-parent>
                                </div>

                                <div class="row g-2">
                                    <x-input-parent class="col-md-6">
                                        <x-label for="birthdate">Birthdate</x-label>
                                        <x-input type="date" id="birthdate" name="birthdate" aria-describedby="birthdate" :required="true" />
                                        <x-input-error id="birthdate"></x-input-error>
                                    </x-input-parent>

                                    <x-input-parent class="col-md-6">
                                        <x-label for="signupEmail">Email address</x-label>
                                        <x-input type="email" id="signupEmail" name="signupEmail" aria-describedby="signupEmail" :required="true" />
                                        <x-input-error id="signupEmail"></x-input-error>
                                    </x-input-parent>
                                </div>

                                <div class="row g-2">
                                    <x-input-parent class="col-md-6">
                                        <x-label for="password">Password</x-label>
                                        <x-input type="password" id="password" name="password" aria-describedby="password" :required="true" />
                                        <x-input-error id="password"></x-input-error>
                                    </x-input-parent>

                                    <x-input-parent class="col-md-6">
                                        <x-label for="password_confirmation">Confirm Password</x-label>
                                        <x-input type="password" id="password_confirmation" name="password_confirmation" aria-describedby="password_confirmation" :required="true" />
                                        <x-input-error id="password_confirmation"></x-input-error>
                                    </x-input-parent>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                        <div class="d-flex justify-content-center align-items-center flex-grow-1">
                            <button type="button" class="btn btn-link text-dark" data-bs-toggle="modal" data-bs-target="#loginModal" data-bs-dismiss="modal">Login</button>
                        </div>
                        <button type="submit" class="btn btn-primary flex-grow-1" form="register_form">Sign Up</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
