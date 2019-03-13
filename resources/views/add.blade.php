@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Add new query</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <p style="color:red!important; font-size:1.1rem;">DISCLAIMER <br/>
                The website is still under development. Considering the fact the response is limited to 10k of items due to EBAY API policy, please make more specific requests (The query response must contain less than 100000 records within last 3 months).
            <br/>Also, you need to specify ebay category ID, here is some of them:  </p>
                    <ul class="text-danger">
                        <li>9355 - Mobile Phones</li>
                        <li>111422 - Apple Laptops</li>
                    </ul>
                    <form action="{{url('/add/new')}}" method="post">
                        <div class="form-group" >
                            <label for="keywords">Enter keywords: </label>
                            <input type="text" class="form-control" id="keywords" name="keywords" placeholder="iPhone 7 Plus 32GB Unlocked" required>
                        </div>
                        <div class="form-group">
                            <label for="buyingType">Buying type: </label>
                            <select name="buying_type" class="form-control" id="buyingType" required>
                                <option value="Auction">Auction</option>
                                <option value="FixedPrice">Buy It Now</option>
                                <option value="All">Both</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="condition">Condition: </label>
                            <select name="condition[]" multiple class="form-control" id="condition" required>
                                <option value="1000">New</option>
                                <option value="1500">New(Other)</option>
                                <option value="3000">Used</option>
                                <option value="4000">Used, Very Good</option>
                                <option value="5000">Used, Good</option>
                                <option value="6000">Used, Acceptable</option>
                                <option value="2000">Manufacturer Refurbished</option>
                                <option value="2500">Seller Refurbished</option>
                                <option value="7000">Faulty</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="categoryId">Category ID:</label>
                            <input name ="categoryId" type="text" class="form-control" id="categoryId" rows="1" placeholder="9355" required>
                        </div>
                        <div class="form-group">
                            <label for="productId">Product ID (leave blank if unknown):</label>
                            <input name ="productId" type="text" class="form-control" id="productId" rows="1" placeholder="225203615">
                        </div>
                        <!--
                        <div class="form-group">
                            <label for="exampleFormControlTextarea1"></label>
                            <textarea class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
                        </div>
                        -->
                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                        <button type="submit" class="btn btn-primary">Add new query</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection