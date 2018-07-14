<!DOCTYPE html>
<html class="no-js" lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="author" content="pixelcave">
        <meta name="robots" content="noindex, nofollow">
        <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=0">
        <link rel="shortcut icon" href="{{ asset('pago/img/favicon.png') }}">
        <link rel="stylesheet" href="{{ asset('pago/css/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ asset('pago/css/plugins.css') }}">
        <link rel="stylesheet" href="{{ asset('pago/css/main.css') }}">
        <script src="{{ asset('pago/js/vendor/modernizr.min.js') }}"></script>
        <link rel="stylesheet" href="https://cdn.linearicons.com/free/1.0.0/icon-font.min.css">
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title> @yield('title') | Ads4XXX</title>

        <!-- Scripts -->
        <script src="{{ asset('pago/js/vendor/jquery.min.js') }}"></script>
        <script src="{{ asset('pago/js/vendor/bootstrap.min.js') }}"></script>
        <script src="{{ asset('pago/js/plugins.js') }}"></script>
        <script src="{{ asset('pago/js/main.js') }}"></script>
        <style>
            #datatable td {
                text-align: center
            }
        </style>
    </head>
    <body>
        <div id="page-wrapper">
            <div id="page-container" class="sidebar-partial sidebar-visible-lg sidebar-no-animations">
                <div id="sidebar">
                    <div id="sidebar-scroll">
                        <div class="sidebar-content">
                            <a href="{{ route('home') }}" class="sidebar-brand">
                                <img src="{{ asset('pago/img/logo-p.png') }}" alt="logo">
                            </a>
                            @auth
                            <ul class="sidebar-nav">
                                <li>
                                    <a href="{{ route('home') }}">&nbsp;<i class="lnr lnr-screen sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">Dashboard</span></a>
                                </li>
                                @If (Auth::user()->hasAnyRole(['admin', 'adver']))
                                    @If (Auth::user()->hasRole('admin'))
                                        <li class="active">
                                            <a href="#" class="sidebar-nav-menu active"><i class="lnr lnr-chevron-right sidebar-nav-indicator sidebar-nav-mini-hide"></i><i class="lnr lnr-lock sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">Usuários</span></a>
                                            <ul>
                                                <li>
                                                    <a href="{{ route('users.create') }}">Adicionar Usuário</a>
                                                </li>
                                                <li>
                                                    <a href="{{ route('users') }}">Listar Usuários</a>
                                                </li>
                                            </ul>
                                        </li>
                                    @endif
                                    <li>
                                        <a href="#" class="sidebar-nav-menu"><i class="lnr lnr-chevron-right sidebar-nav-indicator sidebar-nav-mini-hide"></i><i class="fa fa-bullhorn sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">Anúncios</span></a>
                                        <ul>
                                            <li>
                                                <a href="{{ route('creatives.create') }}">Adicionar Anúncios</a>
                                            </li>
                                            <li>
                                                <a href="{{ route('creatives') }}">Listar Anúncios</a>
                                            </li>
                                        </ul>
                                    </li>
                                    <li>
                                        <a href="#" class="sidebar-nav-menu"><i class="lnr lnr-chevron-right sidebar-nav-indicator sidebar-nav-mini-hide"></i><i class="lnr lnr-bullhorn sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">Campanhas</span></a>
                                        <ul>
                                            <li>
                                                <a href="{{ route('campaingns.create') }}">Adicionar Campanha</a>
                                            </li>
                                            <li>
                                                <a href="{{ route('campaingns') }}">Listar Campanhas</a>
                                            </li>
                                            @If (Auth::user()->hasRole('admin'))
                                                <li>
                                                    <a href="{{ route('campaingns.inatives') }}">Listar Campanhas Inativas</a>
                                                </li>
                                            @endif
                                        </ul>
                                    </li>
                                    @If (Auth::user()->hasRole('admin'))
                                        <li>
                                            <a href="#" class="sidebar-nav-menu"><i class="lnr lnr-chevron-right sidebar-nav-indicator sidebar-nav-mini-hide"></i><i class="lnr lnr-list sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">Categorias</span></a>
                                            <ul>
                                                <li>
                                                    <a href="{{ route('categories.create') }}">Adicionar Categoria</a>
                                                </li>
                                                <li>
                                                    <a href="{{ route('categories') }}">Listar Categorias</a>
                                                </li>
                                            </ul>
                                        </li>
                                    @endif
                                @else
                                <li>
                                    <a href="#" class="sidebar-nav-menu"><i class="lnr lnr-chevron-right sidebar-nav-indicator sidebar-nav-mini-hide"></i><i class="lnr lnr-power-switch sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">Widgets</span></a>
                                    <ul>
                                        <li>
                                            <a href="{{ route('widgets.create') }}">Adicionar Widget</a>
                                        </li>
                                        <li>
                                            <a href="{{ route('widgets') }}">Listar Widgets</a>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <a href="#" class="sidebar-nav-menu"><i class="lnr lnr-chevron-right sidebar-nav-indicator sidebar-nav-mini-hide"></i><i class="fa fa-money sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">Pagamentos</span></a>
                                    <ul>
                                        <li>
                                            <a href="{{ route('payments.create') }}">Solicitar pagamento</a>
                                        </li>
                                        <li>
                                            <a href="{{ route('payments') }}">Listar Pagamentos</a>
                                        </li>
                                    </ul>
                                </li>
                                @endif
                            </ul>
                            @endauth
                        </div>
                    </div>
                </div>
                <div id="main-container">
                    <header class="navbar navbar-default">
                        <ul class="nav navbar-nav-custom">
                            <li>
                                <a href="javascript:void(0)" onclick="App.sidebar('toggle-sidebar');this.blur();">
                                    <i class="fa fa-bars fa-fw side-togle"></i>
                                </a>
                            </li>
                        </ul>
                        <ul class="nav navbar-nav-custom pull-right">
                            @auth
                            <li class="dropdown">
                                <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">
                                    <img src="{{ asset('pago/img/avatar.png') }}" style="border:1px solid #eaeaea;" alt="avatar"> <i class="fa fa-angle-down"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-custom dropdown-menu-right">
                                    <li>
                                        <a href="{{ route('auth.account') }}">
                                            <i class="fa fa-user pull-right"></i>  &nbsp; Minha Conta
                                        </a>
                                        <a href="{{ route('auth.paymentData') }}">
                                            <i class="fa fa-credit-card pull-right"></i>  &nbsp; Dados Bancários
                                        </a>
                                        <a href="{{ route('logout') }}"
                                           onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                            <i class="gi gi-exit pull-right"></i>  &nbsp; Sair
                                        </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>

                                    </li>
                                </ul>
                            </li>
                        </ul>
                        @endauth
                    </header>
                    <div id="page-content">
                        @include('comum/flash-message')
                        @yield('content')
                    </div>
                    <footer class="clearfix">
                        <div class="pull-right">
                            ads4xxx.com
                        </div>
                    </footer>
                </div>
            </div>
        </div>
    </body>
</html>
