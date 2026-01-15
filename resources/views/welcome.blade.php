<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Jalal Vendor</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom Styles -->
    <style>
        body {
            font-family: 'Nunito', sans-serif;
        }

        .hero {
            background-color: rgb(102, 77, 201, .85);
            background-size: cover;
            color: white;
            padding: 100px 0;
            position: relative;
        }

        .hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .hero-content {
            position: relative;
            z-index: 1;
        }

        .feature-icon {
            font-size: 2rem;
            color: #0d6efd;
        }

        .footer {
            background-color: #f8f9fa;
            padding: 20px 0;
        }

        .footer a {
            text-decoration: none;
            color: #6c757d;
        }

        .footer a:hover {
            color: #0d6efd;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
        <div class="container">
            <a class="navbar-brand" href={{ asset('assets/images/brand/logo.png') }}>
                <!-- You can replace the SVG with your logo -->
                                <img src="{{ asset('assets/images/brand/logo.png') }}" class="header-brand-img desktop-lgo" alt="Azea logo">

            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href={{ url('/') }}>Home</a>
                    </li>
                    <!-- Add more nav links here if needed -->
                    @if (Route::has('login'))
                        @auth
                            <li class="nav-item">
                                <a class="nav-link" href="{{ url('/dashboard') }}">Dashboard</a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">Login</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">Register</a>
                                </li>
                            @endif
                        @endauth
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero text-center d-flex align-items-center">
        <div class="hero-overlay"></div>
        <div class="container hero-content">
            <div class="mb-5 br-7">
                <img src="{{ asset('assets/images/brand/logo1.png') }}" class="header-brand-img desktop-lgo" alt="Azea logo">
            </div>
            <a href="{{ route('login') }}" class="btn btn-primary btn-lg me-2">Login</a>
            <a href="{{ route('register') }}" class="btn btn-secondary btn-lg">Register</a>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5">
        <div class="container">
            <div class="row text-center">
                <div class="col-md-4 mb-4">
                    {{-- <div class="mb-3">
                        <svg class="feature-icon" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M8 0a8 8 0 108 8A8.009 8.009 0 008 0zm1 12.93V14H7v-1.07a6.003 6.003 0 014 0z"/>
                        </svg>
                    </div>
                    <h3>Responsive Design</h3>
                    <p>Our templates are fully responsive, ensuring your site looks great on all devices.</p> --}}
                </div>
                <div class="col-md-4 mb-4">
                    {{-- <div class="mb-3">
                        <svg class="feature-icon" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M5 0a5 5 0 00-5 5v6a1 1 0 001 1h10a1 1 0 001-1V5a5 5 0 00-5-5H5zm0 1h6a4 4 0 014 4v6H1V5a4 4 0 014-4zM4 5a4 4 0 018 0v6H4V5z"/>
                        </svg>
                    </div>
                    <h3>Secure Authentication</h3>
                    <p>Implement secure login and registration features effortlessly with Laravel's built-in authentication.</p> --}}
                </div>
                <div class="col-md-4 mb-4">
                    {{-- <div class="mb-3">
                        <svg class="feature-icon" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M8 1a7 7 0 100 14A7 7 0 008 1zM4 8a4 4 0 118 0 4 4 0 01-8 0z"/>
                        </svg>
                    </div>
                    <h3>Easy Customization</h3>
                    <p>Customize your application easily with Laravel's flexible architecture and Bootstrap's versatile components.</p> --}}
                </div>
            </div>
        </div>
    </section>

    <!-- Optional Main Content -->
    <section class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-4">Why Choose azea?</h2>

            <!-- Add more content as needed -->
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer mt-auto py-3 bg-light">
        <div class="container text-center">
            <span>&copy; 2024 Laravel Bootstrap Page. All rights reserved.</span>
            <div class="mt-2">
                <a href="https://laravel.com/docs" class="me-3">Documentation</a>
                <a href="https://laracasts.com" class="me-3">Laracasts</a>
                <a href="https://laravel-news.com/" class="me-3">Laravel News</a>
                <a href="https://github.com/sponsors/taylorotwell">Sponsor</a>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS and dependencies (Popper.js) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
