<x-guest-layout>
    <div class="page">
        <div class="page-single">
            <div class="container">
                <div class="row">
                    <div class="col mx-auto">
                        <div class="row justify-content-center">
                            <div class="col-xl-7 col-lg-12">
                                <div class="row p-0 m-0">
                                    <!-- Left Side: Welcome Text -->
                                    <div class="col-lg-6 p-0">
                                        <div class="text-justified text-white p-5 register-1 overflow-hidden">
                                            <div class="custom-content">
                                                <div class="mb-5 br-7">
                                                    <img src="{{ asset('assets/images/brand/logo1.png') }}" class="header-brand-img desktop-lgo" alt="Azea logo">
                                                </div>
                                                <div class="ms-5">
                                                    <div class="fs-18 mb-6 font-weight-bold text-white">Welcome Back To Azea!</div>
                                                    <div class="mb-6 text-white-50">
                                                        Lorem ipsum, dolor sit amet consectetur adipisicing elit. Exercitationem et esse in velit deleniti facilis quo!
                                                    </div>
                                                    <h6 class="text-white-50">Don't Have an Account?</h6>
                                                    <a href="{{ route('register') }}" class="btn btn-white text-primary text-transparent font-weight-bold">Create Here</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Right Side: Login Form -->
                                    <div class="col-md-8 col-lg-6 p-0 mx-auto">
                                        <div class="bg-white text-dark br-7 br-tl-0 br-bl-0">
                                            <div class="card-body">
                                                <div class="text-center mb-3">
                                                    <h1 class="mb-2">Log In</h1>
                                                    <p>Hello There!</p>
                                                </div>

                                                <!-- Session Status -->
                                                <x-auth-session-status class="mb-4" :status="session('status')" />

                                                <!-- Validation Errors -->
                                                <x-auth-validation-errors class="mb-4" :errors="$errors" />

                                                <!-- Login Form -->
                                                <form method="POST" action="{{ route('login') }}" class="mt-5">
                                                    @csrf

                                                    <!-- Email Input -->
                                                    <div class="input-group mb-4">
                                                        <div class="input-group-text">
                                                            <i class="fe fe-user"></i>
                                                        </div>
                                                        <x-input id="email" class="form-control" type="text" name="email" :value="old('email')" required autofocus placeholder="Email Address" />
                                                    </div>

                                                    <!-- Password Input -->
                                                    <div class="input-group mb-4">
                                                        <div class="input-group" id="Password-toggle">
                                                            <a href="#" class="input-group-text">
                                                                <i class="fe fe-eye" aria-hidden="true"></i>
                                                            </a>
                                                            <x-input id="password" class="form-control" type="password" name="password" required autocomplete="current-password" placeholder="Password" />
                                                        </div>
                                                    </div>

                                                    <!-- Remember Me Checkbox -->
                                                    <div class="form-group">
                                                        <label class="custom-control custom-checkbox">
                                                            <input id="remember_me" type="checkbox" class="custom-control-input" name="remember">
                                                            <span class="custom-control-label">Remember Me</span>
                                                        </label>
                                                    </div>

                                                    <!-- Submit Button -->
                                                    <div class="form-group text-center mb-3">
                                                        <x-button class="btn btn-primary btn-lg w-100 br-7">
                                                            {{ __('Log in') }}
                                                        </x-button>
                                                    </div>

                                                    <!-- Forgot Password -->
                                                    @if (Route::has('password.request'))
                                                    <div class="form-group fs-13 text-center">
                                                        <a href="{{ route('password.request') }}">Forget Password?</a>
                                                    </div>
                                                    @endif
                                                </form>

                                                <!-- Back Home -->
                                                <div class="form-group fs-14 text-center font-weight-bold">
                                                    <a href="{{ url('/') }}">Click Here To Back Home</a>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <!-- End of Right Side -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
