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

        <title>Resetar Senha | Ads4XXX</title>

        <!-- Scripts -->
        <script src="{{ asset('pago/js/vendor/jquery.min.js') }}"></script>
        <script src="{{ asset('pago/js/vendor/bootstrap.min.js') }}"></script>
        <script src="{{ asset('pago/js/plugins.js') }}"></script>
        <script src="{{ asset('pago/js/main.js') }}"></script>
    </head>
    <body style="background-color: #5A732D;">
        <div id="page-wrapper">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">

                        <div id="login-container">

                            <div class="login-title text-center">
                                <img src="{{ asset('pago/img/logo.png') }}" alt="logo" class="img-responsive">
                            </div>

                            <div class="block">
                                <form class="form-horizontal" method="POST" action="{{ route('password.request') }}">
                                    {{ csrf_field() }}

                                    <input type="hidden" name="token" value="{{ $token }}">

                                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                        <label for="email" class="col-md-4 control-label">E-mail</label>

                                        <div class="col-md-6">
                                            <input id="email" type="email" class="form-control" name="email" value="{{ $email or old('email') }}" required autofocus>

                                            @if ($errors->has('email'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('email') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                        <label for="password" class="col-md-4 control-label">Senha</label>

                                        <div class="col-md-6">
                                            <input id="password" type="password" class="form-control" name="password" required>

                                            @if ($errors->has('password'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('password') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                                        <label for="password-confirm" class="col-md-4 control-label">Confirmar Senha</label>
                                        <div class="col-md-6">
                                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>

                                            @if ($errors->has('password_confirmation'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('password_confirmation') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-md-6 col-md-offset-4">
                                            <button type="submit" class="btn btn-primary">
                                                Resetar Senha
                                            </button>
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
    </body>
</html>
