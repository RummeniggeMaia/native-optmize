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

                                <div class="panel-body">
                                    @if (session('status'))
                                    <div class="alert alert-success">
                                        {{ session('status') }}
                                    </div>
                                    @endif

                                    <form class="form-horizontal" method="POST" action="{{ route('password.email') }}">
                                        {{ csrf_field() }}

                                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                            <div class="col-xs-12">
                                                <div class="input-group">
                                                    <span class="input-group-addon"><i class="gi gi-envelope"></i></span>
                                                    <input id="email" name="email" type="text" class="form-control input-lg"  value="{{ old('email') }}" placeholder="Email">
                                                </div>
                                                @if ($errors->has('email'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('email') }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group form-actions text-center">
                                            <button type="submit" class="btn btn-md btn-default">ENVIAR LINK PARA RESETAR SENHA</button>
                                        </div> 
                                        <div class="form-group form-actions text-center">
                                            <a class="btn btn-md btn-default" href="{{ route('login') }}">
                                                VOLTAR
                                            </a>
                                        </div> 
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <a href="#" id="to-top"><i class="fa fa-angle-double-up"></i></a>
    </body>
</html>
