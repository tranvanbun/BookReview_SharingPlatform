@php
    use Illuminate\Support\Facades\Auth;
    $favoriteCount = Auth::check() ? Auth::user()->favorites()->count() : 0;
    $watchLaterCount = Auth::check() ? Auth::user()->watchLaterBooks()->count() : 0;
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Home</title>

    <!-- Bootstrap + FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

    <style>
        .logo {
            height: 40px;
            border-radius: 8px;
        }

        .navbar-brand {
            font-weight: bold;
            font-size: 1.7rem;
            display: flex;
            align-items: center;
        }

        .navbar-brand img {
            margin-right: 10px;
        }

        .nav-link {
            font-weight: 500;
            color: white !important;
            margin: 0 8px;
        }

        .user-actions a {
            text-decoration: none;
            position: relative;
            margin-left: 15px;
        }

        .user-actions i {
            font-size: 1.3rem;
            color: white;
        }

        .user-actions .badge {
            position: absolute;
            top: -6px;
            right: -10px;
            background-color: red;
            font-size: 0.7rem;
            padding: 3px 6px;
        }

        .user-link {
            display: flex;
            align-items: center;
            color: white;
            margin-left: 20px;
        }

        .user-link i {
            font-size: 1.5rem;
        }

        .user-link span {
            margin-left: 5px;
        }

        .navbar-nav .nav-item {
            margin: 0 5px;
        }

        .navbar {
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        footer {
            background-color: #f8f9fa;
            padding: 40px 0;
        }

        footer .footer-title {
            font-weight: bold;
            margin-bottom: 15px;
        }

        footer a {
            color: #343a40;
            text-decoration: none;
        }

        footer a:hover {
            text-decoration: underline;
        }

        footer p {
            color: #6c757d;
        }
    </style>

    @stack('styles')
</head>

<body>
    <!-- Header -->
    <header class="bg-primary">
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary py-3">
            <div class="container d-flex justify-content-between align-items-center">
                <!-- Logo -->
                <a class="navbar-brand text-white" href="{{ route('books.home') }}">
                    <img src="{{ asset('img/book-review.jpg') }}" alt="Logo" class="logo">
                    BookReview
                </a>

                <!-- Toggle -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <!-- Nav links -->
                <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item"><a class="nav-link" href="{{ route('books.home') }}">HOME</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">ABOUT</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('contactMe') }}">CONTACT</a></li>
                    </ul>
                </div>

                <!-- User actions -->
                <div class="d-flex align-items-center user-actions">
                    <a href="#">
                        <i class="fas fa-box-open"></i>
                        @if ($watchLaterCount > 0)
                            <span class="badge rounded-pill">{{ $watchLaterCount }}</span>
                        @endif
                    </a>

                    <a href="#">
                        <i class="fas fa-heart"></i>
                        @if ($favoriteCount > 0)
                            <span class="badge rounded-pill">{{ $favoriteCount }}</span>
                        @endif
                    </a>

                    @php
                        $unreadCount = auth()->check() ? auth()->user()->unreadNotifications->count() : 0;
                    @endphp
                    <a href="{{ route('notifications') }}" class="position-relative me-3">
                        <i class="fas fa-bell fa-lg text-white"></i>
                        @if ($unreadCount > 0)
                            <span
                                class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                {{ $unreadCount }}
                            </span>
                        @endif
                    </a>


                    @if (Auth::check())
                        <div class="dropdown">
                            <a href="#" class="dropdown-toggle user-link" id="userDropdown" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user-circle"></i>
                                <span>{{ Auth::user()->name }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="{{ route('profile') }}">Profile</a></li>
                                <li><a class="dropdown-item" href="{{ route('books.index') }}">My Posts</a></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">Logout</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="user-link">
                            <i class="fas fa-user-circle"></i>
                            <span>Login</span>
                        </a>
                    @endif
                </div>
            </div>
        </nav>

        <!-- Search bar -->
        <div class="bg-light py-3">
            <div class="container">
                <form action="#" method="GET" class="d-flex">
                    <input type="text" name="query" class="form-control me-2"
                        placeholder="Search for books, authors, or topics..." required>
                    <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
                </form>
            </div>
        </div>
    </header>

    <!-- Main content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5 class="footer-title">BookReview</h5>
                    <p>Helping you discover and review the best books. Built with Laravel.</p>
                </div>
                <div class="col-md-4">
                    <h5 class="footer-title">Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('books.home') }}">Home</a></li>
                        <li><a href="#">About</a></li>
                        <li><a href="{{ route('contactMe') }}">Contact</a></li>
                        <li><a href="{{ route('books.index') }}">My Posts</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5 class="footer-title">Follow Us</h5>
                    <a href="#"><i class="fab fa-facebook fa-lg me-3"></i></a>
                    <a href="#"><i class="fab fa-twitter fa-lg me-3"></i></a>
                    <a href="#"><i class="fab fa-instagram fa-lg me-3"></i></a>
                </div>
            </div>
            <div class="text-center mt-4">
                <p>&copy; {{ date('Y') }} BookReview. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>

</html>
