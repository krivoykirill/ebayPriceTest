@extends('layouts.app')

@section('content')
    <section class="container" id="dashboard">
        <div class="row mb-2">
            <div class="col-md-12 col">
                <div class="content p-3">
                    <p class="h6">title: <p class="display-4">{{$query->keywords}}</p></p>
                </div>
                
            </div>
        </div>
            
        <div class="row mb-2">
            <div class="col-md-5 col">
                <div class="content p-3">
                    <p>Last scan at: <strong>{{$query->last_check}}</strong></p>
                    <p id="sumTotal"></p>
                </div>
            </div>
            <div class="col-md-7 col">
                <div class="content p-3">
                    <p>Switch your query: </p><br/>
                    <div id="carouselExampleIndicators" class="carousel slide carouselHeight" data-ride="carousel">
                        <ol class="carousel-indicators">
                            <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                            <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                            <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
                        </ol>
                        <div class="carousel-inner carouselContent">
                            <div class="m-2 carousel-item active">
                                <img class="d-flex align-items-center shadow mb-5 bg-white rounded" src="http://thumbs2.ebaystatic.com/m/mBngmHmK1mq1NAAzc91dU-Q/140.jpg" alt="First slide">
                                <div class="carousel-caption d-none d-md-block">
                                    <h5>Caption Large</h5>
                                    <p>Caption Small</p>
                                </div>
                            </div>
                            <div class="m-2 carousel-item">
                                <img class="d-flex align-items-center shadow mb-5 bg-white rounded" src="http://thumbs1.ebaystatic.com/m/mKUDu9plYNfFAJtqVS6NXbg/140.jpg" alt="Second slide">
                                <div class="carousel-caption d-none d-md-block">
                                    <h5>Caption Large</h5>
                                    <p>Caption Small</p>
                                </div>
                            </div>
                            <div class="m-2 carousel-item">
                                <img class="d-flex align-items-center shadow mb-5 bg-white rounded" src="http://thumbs3.ebaystatic.com/m/myHD1OqjjtjUo4b1-W07RXw/140.jpg" alt="Third slide">
                                <div class="carousel-caption d-none d-md-block">
                                    <h5>Caption Large</h5>
                                    <p>Caption Small</p>
                                </div>
                            </div>
                        </div>
                        <a class="carousel-control-prev darkColour" href="#carouselExampleIndicators" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next darkColour" href="#carouselExampleIndicators" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>

                </div>
                    
            </div>
        </div>   
        <div class="row">
            <div class="col-md-12 col">
                <div class="content p-3">




                    <h1>Medianes</h1>
                    <canvas id="medianeWeekly"></canvas>
                    <canvas id="medianeDaily"></canvas>
                    <canvas id="medianeMonthly"></canvas>
                    <br/>
                    <br/>
                    <h1>Total sold in GBP</h1>
                    <canvas id="sumDaily"></canvas>
                    <canvas id="sumWeekly"></canvas>
                    <canvas id="sumMonthly"></canvas>
                    <br/>
                    <br/>
                    <h1>Vendor Performance</h1>
                    <canvas class="pie" id="vendToPleb"></canvas>
                    <canvas id="topVend"></canvas>





                </div>
            </div>
        </div>
    </section>
    <script>    
        var data = {!! json_encode($data->toArray(), JSON_HEX_TAG) !!};
        
    </script>
@endsection