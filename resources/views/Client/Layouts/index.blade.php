<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="zxx">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Eventiz - Event Conference </title>
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('client/images/favicon.png') }}">
    <!-- Bootstrap core CSS -->
    <link href="{{ asset('client/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
    <!-- Custom CSS -->
    <link href="{{ asset('client/css/style.css') }}" rel="stylesheet" type="text/css">
    <!-- Plugin CSS -->
    <link href="{{ asset('client/css/plugin.css') }}" rel="stylesheet" type="text/css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('client/fonts/line-icons.css') }}" type="text/css">
    <script src="{{ asset('vendor/kustomer/js/kustomer.js') }}" defer></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Styles personnalisés pour les notifications -->
    <style>
        /* Style for notification links */
        .notification-link {
            color: #007bff;
            text-decoration: none;
            margin-left: 10px;
        }
        /* Hover effect for notifications */
        .notification-item:hover {
            background-color: #e0e0e0;
            transition: background-color 0.3s ease-in-out;
        }
    </style>

    <!-- Script de gestion d'erreurs de validation -->
    <script>
        $(document).ready(function() {
            var validationErrors = $('#validation-errors').html();
            if (validationErrors.trim() !== '') {
                $('#exampleModal').modal('show');
            }
        });
    </script>

    <!-- Script pour afficher le modal de succès si la session 'Demandeur' est présente -->
    <script>
        $(document).ready(function() {
            @if(session('Demandeur'))
                $('#successModal').modal('show');
            @endif
        });
    </script>
</head>
<body>
    <!-- Preloader -->
    <div id="preloader">
        <div id="status"></div>
    </div>
    <!-- Preloader Ends -->

    <!-- Header / Navigation -->
    <header class="main_header_area">
        <div class="header_menu" id="header_menu">
            <nav class="navbar navbar-default">
                <div class="container">
                    <div class="navbar-flex d-flex align-items-center justify-content-between w-100 pb-2 pt-2">
                        <!-- Logo / Brand -->
                        <div class="navbar-header">
                            <a class="navbar-brand" href="{{ route('home') }}">
                                <img src="{{ asset('client/images/logo-white.png') }}" alt="Logo White">
                                <img src="{{ asset('client/images/logo.png') }}" alt="Logo">
                            </a>
                        </div>
                        <!-- Menu de navigation -->
                        <div class="navbar-collapse1 align-items-center" id="bs-example-navbar-collapse-1">
                            <ul class="nav navbar-nav" id="responsive-menu">
                                <li class="dropdown submenu active">
                                    <a href="{{ route('home') }}">Home</a>
                                </li>
                                <li>
                                    <a href="{{ url('about') }}">À PROPOS</a>
                                </li>
                                <li class="submenu dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                        Categories <i class="fas fa-caret-down ms-1" aria-hidden="true"></i>
                                    </a>
                                    <ul class="dropdown-menu">
                                        @foreach($categories as $category)
                                            <li>
                                                <a href="{{ route('ShowEventByCategory', $category->id) }}">{{ $category->Nom }}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                                @guest
                                    {{-- Optionnel : afficher des liens spécifiques aux invités --}}
                                @else
                                    <li>
                                        <a href="{{ url('historique') }}">Historique</a>
                                    </li>
                                @endguest
                                <li>
                                    <a href="{{ url('contact') }}">Contactez</a>
                                </li>

                                @guest
                                    @if (Route::has('login'))
                                        <li>
                                            <a href="{{ route('login') }}">{{ __('Se connecter') }}</a>
                                        </li>
                                    @endif
                                    @if (Route::has('register'))
                                        <li>
                                            <a href="{{ route('register') }}">{{ __('Register') }}</a>
                                        </li>
                                    @endif
                                @else
                                    <!-- Menu pour l'utilisateur authentifié -->
                                    <li class="submenu dropdown">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                            {{ Auth::user()->name }} <i class="fas fa-caret-down ms-1" aria-hidden="true"></i>
                                        </a>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a href="{{ route('profileclient') }}">Mon Compte</a>
                                            </li>
                                            <li>
                                                <a href="{{ route('logout') }}"
                                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                                    Se déconnecter
                                                </a>
                                            </li>
                                        </ul>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    </li>
                                    @if (Auth::user()->role == 'demandeur')
                                        <li>
                                            <a href=""
                                               style="color: #f2f2f2;"
                                               data-toggle="dropdown"
                                               role="button"
                                               aria-haspopup="true"
                                               aria-expanded="false"
                                               data-bs-toggle="modal"
                                               data-bs-target="#exampleModal">
                                                Ajouter un evenement
                                            </a>
                                        </li>
                                        <li class="submenu dropdown">
                                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" id="notifications-button" aria-haspopup="true" aria-expanded="false">
                                                notifications <span id="notification-count" data-count="0">0</span>
                                                <i class="fas fa-caret-down ms-1" aria-hidden="true"></i>
                                            </a>
                                            <ul class="dropdown-menu" id="notifications-list">
                                                <li id="notifications">
                                                    <!-- Notifications générées dynamiquement -->
                                                </li>
                                            </ul>
                                        </li>
                                    @endif
                                @endguest
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>
        </div>
    </header>
    <!-- End Header -->

    <!-- Contenu principal -->
    @yield('content')

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <!-- Widget À PROPOS -->
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="footer-widget">
                        <h3 class="widget-title">À PROPOS</h3>
                        <p>Nous organisons des événements exceptionnels pour créer des expériences inoubliables.</p>
                    </div>
                </div>
                <!-- Widget CONTACTEZ-NOUS -->
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="footer-widget">
                        <h3 class="widget-title">CONTACTEZ-NOUS</h3>
                        <ul class="listnone">
                            <li><i class="fas fa-map-marker-alt"></i> Adresse, Ville, Pays</li>
                            <li><i class="fas fa-phone"></i> +221 77 299 57 16</li>
                            <li><i class="fas fa-envelope"></i> info@example.com</li>
                        </ul>
                    </div>
                </div>
                <!-- Widget LIENS RAPIDES -->
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="footer-widget">
                        <h3 class="widget-title">LIENS RAPIDES</h3>
                        <ul class="listnone">
                            <li><a href="{{ route('home') }}">Accueil</a></li>
                            <li><a href="{{ url('about') }}">À Propos</a></li>
                            <li><a href="{{ url('contact') }}">Contact</a></li>
                        </ul>
                    </div>
                </div>
                <!-- Widget RÉSEAUX SOCIAUX -->
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="footer-widget">
                        <h3 class="widget-title">RÉSEAUX SOCIAUX</h3>
                        <ul class="social-icons list-inline">
                            <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                            <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                            <li><a href="#"><i class="fab fa-instagram"></i></a></li>
                            <li><a href="#"><i class="fab fa-linkedin-in"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- End Footer -->

    <!-- Scripts -->
    <script src="{{ asset('client/js/jquery-3.5.1.min.js') }}"></script>
    <script src="{{ asset('client/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('client/js/plugin.js') }}"></script>
    <script src="{{ asset('client/js/main.js') }}"></script>
    <script src="{{ asset('client/js/custom-nav.js') }}"></script>
</body>
</html>
