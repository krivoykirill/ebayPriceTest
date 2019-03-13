<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>eBay Price Analyzer</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">
         <!-- Compiled and minified CSS -->
         <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
        

        <!-- Fonts -->
        <link rel="dns-prefetch" href="//fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <link href="{{ asset('css/styles.css') }}" rel="stylesheet">

        
    </head>
    <body>
        <img src="/img/BG.png" id="bg" alt="wallpaper">
        <!--
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ route('login') }}">Login</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}">Register</a>
                        @endif
                    @endauth
                </div>
            @endif

            <div class="content">
                <div class="title m-b-md">
                    Laravel
                </div>

                <div class="links">
                    <a href="https://laravel.com/docs">Docs</a>
                    <a href="https://laracasts.com">Laracasts</a>
                    <a href="https://laravel-news.com">News</a>
                    <a href="https://blog.laravel.com">Blog</a>
                    <a href="https://nova.laravel.com">Nova</a>
                    <a href="https://forge.laravel.com">Forge</a>
                    <a href="https://github.com/laravel/laravel">GitHub</a>
                </div>
            </div>
        </div>-->
        <wrapper class="container d-flex flex-column">
            <nav class="navbar navbar-expand-md navbar-dark">
                <div class="container">
                    <a class="navbar-brand" href="{{ url('/') }}">
                        <img src="img/logo_on_dark.png" alt="logo"/>
                    </a>
                </div>
            </nav>
            <section class="mt-5" id="mainSection">
                <h1 class="display-4 text-center text-white">Log in or sign up to continue</h1>
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-body bg-light">
                            <div class="row justify-content-center">
                                <div id="leftAuthSection" class="col-md-6 text-center">
                                        <p>Log in</p>
                                        <form action="{{route('login')}}" method="post">
                                            @csrf
                                            <div class="form-group">
                                                <input class="form-control w-100 border-radius-30 text-center py-1 no-outline" name="email" type="text" placeholder="email" />
                                                
                                                
                                                @if ($errors->has('email'))
                                                    @if (strlen($errors->first('email'))==43)
                                                        <div class="invalid-feedback d-block" role="alert">
                                                            {{ $errors->first('email') }}
                                                        </div>
                                                    @endif
                                                @endif


                                            </div>
                                            
                                            <div class="form-group">
                                                <input class="w-100 border-radius-30 text-center py-1 no-outline" type="password" name="password" placeholder="password" />
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                                <label class="form-check-label" for="remember">
                                                    {{ __('Remember me on this computer') }}
                                                </label>
                                            </div>
                                            
                                            <button type="submit" class="btn btn-outline-success cta-inverted w-100 p-2">Start analyzing</button>
                                      
                                        </form>
                                </div>
                                <div class="col-md-6 text-center">
                                        <p>Sign up</p>
                                        <form action="{{ route('register') }}" method="post">
                                            @csrf
                                            <div class="form-group">
                                                <input class="w-100 border-radius-30 text-center py-1 no-outline" name="name" type="text" placeholder="name" required />
                                                @if ($errors->has('name'))
                                                    
                                                    <div class="invalid-feedback d-block" role="alert">
                                                        {{ $errors->first('name') }}
                                                    </div>
                                                    
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                <input class="w-100 border-radius-30 text-center py-1 no-outline" name="email" type="text" placeholder="email" required/>
                                                @if ($errors->has('email'))
                                                    @if (strlen($errors->first('email'))!=43)
                                                        <div class="invalid-feedback d-block" role="alert">
                                                            {{ $errors->first('email') }}
                                                        </div>
                                                    @endif
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                <input class="w-100 border-radius-30 text-center py-1 no-outline" name="password" type="password" placeholder="password" required/>
                                                @if ($errors->has('password'))
                                                    <div class="invalid-feedback d-block" role="alert">
                                                        {{ $errors->first('password') }}
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                <input class="w-100 border-radius-30 text-center py-1 no-outline" name="password_confirmation" type="password" placeholder="confirm password" required/>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input type="checkbox" class="form-check-input p-1" id="agreement" required>
                                                <label class="form-check-label" for="agreement">I agree to the <strong class="ebay-color">Terms & Conditions</strong></label>
                                            </div>
                                            <button type="submit" class="btn btn-outline-success cta-inverted w-100 p-2">Start analyzing</button>
                                        </form>
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

        </wrapper>
        <footer class="container-fluid w-100 text-light py-4 mt-3 text-center">
            <img src="img/logo_on_dark.png" alt="logo"/>
            <br/>
            <br/>
            <p><strong class="ebay-color">eBayOnSteroids &copy;</strong>  2019 No Rights Reserved</p>
        </footer>
        
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

    </body>
</html>
