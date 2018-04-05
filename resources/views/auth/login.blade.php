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

        <title>Native-Optimize</title>

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
            <div class="container">
                <div class="row">
                    <div class="col-md-12">

                        <div id="login-container">

                            <div class="login-title text-center">
                                <img src="{{ asset('pago/img/logo.png') }}" alt="logo" class="img-responsive">
                            </div>

                            <div class="block">
                                <form action="{{ route('login') }}" method="post" id="form-login" name="form-login" class="form-horizontal">
                                    {{ csrf_field() }}
                                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                        <div class="col-xs-12">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="gi gi-envelope"></i></span>
                                                <input id="email" name="email" type="text" class="form-control input-lg"  value="{{ old('email') }}" placeholder="Email">
                                                @if ($errors->has('email'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('email') }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                        <div class="col-xs-12">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="gi gi-asterisk"></i></span>
                                                <input id="password" type="password" class="form-control input-lg" name="password" placeholder="Senha" required>
                                                @if ($errors->has('password'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('password') }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group form-actions"> 
                                        <div class="col-xs-12 text-center">
                                            <button type="submit" class="btn btn-md btn-primary">Logar</button>
                                            <div class="checkbox">
                                                <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Lembrar-me
                                            </div>
                                            <a class="btn btn-link" href="{{ route('register') }}">
                                                Inscreva-se
                                            </a>
                                            <a class="btn btn-link" href="{{ route('password.request') }}">
                                                Esqueceu sua senha?
                                            </a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <a href="#" id="to-top"><i class="fa fa-angle-double-up"></i></a>

        <script src="js/vendor/jquery.min.js"></script>
        <script src="js/vendor/bootstrap.min.js"></script>
        <script src="js/plugins.js"></script>
        <script src="js/main.js"></script>
        <script src="js/pages/tablesDatatables.js"></script>
        <script>$(function () { TablesDatatables.init();});</script>
    </body>
</html>
