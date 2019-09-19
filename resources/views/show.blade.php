<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/graphs.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.js"></script>
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
</head>
<body onload='init()'>
    <div class="bg-light"id="bg" alt="wallpaper"></div>
    <nav id="index" class="navbar navbar-expand-md navbar-light bg-white shadow mb-4 text-dark navbar-default sticky-top">
        <div class="container">
            <a class="navbar-brand border-right pr-3" href="{{ url('/') }}">
                <img src="{{asset('img/logo_on_light.png')}}" alt="logo"/>
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav mr-auto my-auto ">
                    <li><a class="pr-2 ebay-color h3" href="#index">Dashboard</a></li>
                    <li class="my-auto pt-1"><a class=" h5 text-dark" href="{{url('/home')}}">Home</a></li>
                </ul>

                <!-- Right Side Of Navbar -->
                <ul id="accOptions" class="navbar-nav ml-auto rounded shadow-sm">
                    <!-- Authentication Links -->
                    @guest
                        <li class="nav-item">
                            <a class="nav-link btn btn-outline-success m-1 ebay-btn" href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>
                        @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link btn btn-success m-1 ebay-btn" href="{{ route('register') }}">{{ __('Register') }}</a>
                            </li>
                        @endif
                    @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle text-dark" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                Welcome, <strong class="ebay-color">{{ Auth::user()->name }}!</strong><span class="caret"></span>
                            </a>

                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>
    <wrapper class="container-fluid d-flex flex-column p-0" id="app">
        <main>
            <section class="container" id="dashboard">
                @if (!empty($demo))
                <div class="alert alert-secondary alert-dismissible fade show shadow-sm border" role="alert">
                    <strong>This is a demonstration of User Dashboard. Please <a class="ebay-color font-weight-bold" href="{{ route('signup') }}">{{ __('Log in') }}</a> to start adding your own products.</strong>
                        <br/>User specifies a product by keywords, buying type, condition and product category ID.
                        <br/>The website  adds a record to the database with all data specified and executes a 
                        <a class="ebay-color font-weight-bold" href="https://github.com/krivoykirill/ebayPriceTest/blob/master/app/Http/Controllers/Py/statsGenerator.py" alt="link to python script on GitHub">Python script</a> 
                        that connects to eBay API (<strong>ebaysdk-python</strong>), generates multiple datasets and stores them in the database as a JSON object to process that data in the browser (<strong>JavaScript 'data' object</strong>, can be seen using DevTools).
                        <br/>Along with that, it creates multiple Linear and Polynomial models and makes predictions on the Medians dataset ( <strong>Pandas</strong> and <strong>Scikit-learn</strong> libraries).
                        <br/>All graphs were created using <strong>Chart.js</strong>; Date manipulations were done using <strong>Moment.js</strong>
                        <br/><strong>GitHub repository is available <a class="ebay-color font-weight-bold" href="https://github.com/krivoykirill/ebayPriceTest" alt="Project's github repository">here</a></strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @endif
                <div class="row">
                    <div class="p-0 col-md-10 pl-0 mb-2 pr-0 pr-md-2 ">
                        <div class="content p-3 shadow-sm">
                            <p class="h4">Product information</p>
                            <div class="w-100 px-3 m-0">
                                    
                                <div class="col-md-12 ml-auto" type="text" >
                                    <div class="w-100 border-radius-30 pl-2 pt-1 pr-2 m-0">
                                        <ul class="list-inline m-0">
                                            @if($query->buying_type=='Auction')
                                            <li class="list-inline-item shadow-sm search-element search-element-blue px-3 py-2 m-0">Auction</li>
                                            @elseif($query->buying_type=='FixedPrice')
                                            <li class="list-inline-item search-element shadow-sm search-element-red px-3 py-2">Buy It Now</li>
                                            @elseif($query->buying_type=='All')
                                            <li class="list-inline-item search-element shadow-sm search-element-blue px-3 py-2">Auction</li>
                                            <li class="list-inline-item search-element shadow-sm search-element-red px-3 py-2">Buy It Now</li>
                                            @endif
                                            <li class="list-inline-item search-element px-3 py-2 search-element-dark">{{$query->keywords}}</li>
                                            @foreach ($query->condition as $cond)
                                                @if ($cond=='3000')
                                                <li class="list-inline-item search-element px-3 py-2">Used</li>
                                                @elseif($cond=='1000')
                                                <li class="list-inline-item search-element px-3 py-2">New</li>
                                                @elseif($cond=='1500')
                                                <li class="list-inline-item search-element px-3 py-2">New(other)</li>
                                                @elseif($cond=='4000')
                                                <li class="list-inline-item search-element px-3 py-2">Used Very Good</li>
                                                @elseif($cond=='5000')
                                                <li class="list-inline-item search-element px-3 py-2">Used Good</li>
                                                @elseif($cond=='6000')
                                                <li class="list-inline-item search-element px-3 py-2">Used Acceptable</li>
                                                @elseif($cond=='2000')
                                                <li class="list-inline-item search-element px-3 py-2">Manufacturer Refurbished</li>
                                                @elseif($cond=='2500')
                                                <li class="list-inline-item search-element px-3 py-2">Seller Refurbished</li>
                                                @elseif($cond=='7000')
                                                <li class="list-inline-item search-element px-3 py-2">Faulty</li>
                                                @endif
                                            @endforeach
                                                    
                                        </ul>
                                    </div>
                                    
                                    <hr>
                                    
                                    <div class="mt-2 ml-2 d-flex row">
                                            <p class="my-2 ml-2">Last scanned: <strong class="ebay-color-dblue">{{$query->last_check}}</strong></p>
                                        <div class="ml-auto pl-3">
                                            
                                            <div class="btn-group" role="group">
                                                    <button id="btnGroupDrop1" type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                      Options
                                                    </button>
                                                    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                                        <a class="dropdown-item" href="{{url('/refresh/'.$query->id)}}">Update query</a>
                                                        <a class="dropdown-item" href="{{url('/add')}}">Add another query</a>
                                                        <a class="dropdown-item" href="#">Edit query</a>
                                                        <a class="dropdown-item" href="#">Delete query</a>
                                                    </div>
                                                  </div>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 p-0 mb-2 d-flex">
                        <img id="thumbnail" class="img-thumbnail shadow mx-auto ml-md-auto mr-md-0" src="{{$query->thumbnail}}" alt="thumbnail"/>
                    </div>
                </div>
                    
                <div class="row mb-2 ">
                    <div class="col-md-2 col-xs-5 col-sm-5 p-0 pr-sm-2 pb-2 pb-md-0">
                        <div class="content p-1 shadow-sm">
                            <!-- sumTotal -->
                            <p id="itemsRetrieved" class="ebay-number text-center m-0 ebay-color-gray">0</p>
                            <p class="h5 text-center ebay-color-gray">items retrieved</p>
                            <p class="h5 text-secondary secondary-text text-center">from eBay's response</p>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-7 col-xs-7 p-0 pb-2 pb-md-0 pr-md-2">
                        <div class="content shadow-sm p-1">
                            <p id="totalSoldGBP" class="ebay-number text-center m-0 ebay-color-gray">£ 0</p>
                            <p class="h5 text-center ebay-color-gray">spent on this product</p>
                            <p class="h5 text-secondary secondary-text text-center">since <strong id="totalSoldSince">2019-01-13 12:56:22</strong></p>
                        </div>
                    </div>
                    <div class="col-md-3 col-xs-6 col-sm-6 p-0 pb-2 pr-sm-2 pb-md-0">
                        <div class="content shadow-sm p-1">
                            <p id="currentPrice" class="ebay-number text-center m-0 ebay-color-dblue font-weight-bold">£ 0</p>
                            <p class="h5 text-center ebay-color-dblue font-weight-bold">calculated average price</p>
                            <p class="h5 text-secondary secondary-text text-center">the product is trending at</p>
                        </div>
                    </div>
                    <div class="col-md-3 col-xs-6 col-sm-6 p-0 pb-2 pb-md-0">
                        <div class="content shadow-sm p-1">
                            <p id="predictedPrice" class="ebay-number text-center m-0 ebay-color-blue">£ 0</p>
                            <p class="h5 text-center ebay-color-blue font-weight-bold">price prediction</p>
                            <p class="h5 text-secondary secondary-text text-center">for <strong id="predictionWidget">30 days</strong></p>
                        </div>
                    </div>

                </div>   
                <div class="row">
                    <div class="col-md-12 col p-0">
                        <div class="content p-3">
                            <div class="row pl-3 d-flex">
                                <h2 class="h2">Charts</h2>
                                <p class="h5 mt-3 ml-3 text-secondary secondary-text">  all data is taken from eBay-GB and prices are shown in Pounds (GBP) </p>
                                <div class="right ml-auto mr-3">
                                    <div id="periodSwitcher" class="btn-group">
                                        <button type="button" data-period="Daily" class="btn period-switcher-btn chosen">Daily</button>
                                        <button type="button" data-period="Weekly" class="btn period-switcher-btn">Weekly</button>
                                        <button type="button" data-period="Monthly" class="btn period-switcher-btn">Monthly</button>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-12">
                                    <h3 class="h3">Medians</h3>
                                    <p class="h5">This chart shows prices people most often bought the product for. It is useful for noticing price changes of the product.</p>
                                    <canvas id="medians"></canvas>
                                    <hr>
                                </div>
                                
                                <div class="col-md-10">
                                    <h3 class="h3">Total sold</h3>
                                    <p class="h5">This chart is showing a total price of the products sold within the set timeframe. Demand for the item can be figured out from this chart.</p>
                                    <canvas id="sums"></canvas>
                                </div>
                            </div>
                            
                            <br/>
                            <br/>
                            

                            <br/>
                            <br/>
                            
                        </div>
                    </div>
                </div>
                <!--  <h1>Vendor Performance</h1>
                            <canvas class="pie" id="vendToPleb"></canvas>
                            <canvas id="topVend"></canvas>  -->
            </section>
        </main>

    
    <footer class="container-fluid w-100 text-light py-4 mt-3 text-center">
        <img src="{{asset('img/logo_on_dark.png')}}" alt="logo"/>
        <br/>
        <br/>
        <p><strong class="ebay-color">eBayOnSteroids &copy;</strong>  2019 No Rights Reserved</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <script>    
        var data = {!! json_encode($data->toArray(), JSON_HEX_TAG) !!};
        
    </script>
</body>
</html>
