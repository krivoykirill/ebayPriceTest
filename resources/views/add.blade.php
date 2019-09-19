@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="bg-light p-3 rounded">
                <h2 class="h2">Add new query</h2>
                <p class="secondary-text">Please enter information about your query. <br/>Specify keywords, buying type, condition and category of your eBay query. Visiting <a href="http://ebay.co.uk">eBay UK</a> is recommended to find more information about the desired product.<br/>
                Good query description has a great impact on further analysis.</p>
                <hr>
                <div class="">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <form action="{{url('/add/new')}}" method="post">
                        <div class="form-group" >
                            <label for="keywords">Enter keywords: </label>
                            <input type="text" class="form-control" id="keywords" name="keywords" placeholder="for instance: iPhone 7 Plus 32GB Unlocked" required>
                        </div>
                        <div class="form-group">
                            <label for="buyingType">Buying type: </label>
                            <select name="buying_type" class="form-control" id="buyingType" required>
                                <option value="Auction">Auction</option>
                                <option value="FixedPrice">Buy It Now</option>
                                <option value="All">Both Types</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="condition">Condition: </label>
                            <p class="secondary-text text-secondary mb-1">You can select multiple conditions</p>
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
                        <hr>
                        <p class="secondary-text text-secondary font-weight-bold">For better results, additional filters required.</p>
                        <button class="btn ebay-btn text-dark" type="button" data-toggle="collapse" data-target="#additionalInfo" aria-expanded="false" aria-controls="additionalInfo">
                            Apply more filters
                        </button>
                        <br/>
                        
                        <div class="collapse pt-2" id="additionalInfo">
                                <hr>
                            <div class="form-group row">
                                <label class="px-3" for="categoryId">Category ID <strong class="text-danger">(strongly recommended to specify this field)</strong>:</label>
                                <p class="px-3 secondary-text text-secondary">If you don't know Category ID for your product, press the button below to find more information about it</p>
                                <div class="col-md-8">
                                    <input name ="categoryId" type="text" class="form-control" id="categoryId" rows="1" placeholder="for instance: 9355">
                                </div>
                                <div class="col-md-4">
                                    <button type="button" class="btn ebay-btn" data-toggle="modal" data-target="#exampleModalCenter">
                                        Find Category ID
                                    </button>
                                </div>
                                
                            </div>
                            <hr>
                            <div class="form-group">
                                <label for="productId">Product ID (leave blank if unknown):</label>
                                <input name ="productId" type="text" class="form-control" id="productId" rows="1" placeholder="225203615">
                            </div>
                        </div>
                        <hr>
                        
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

<!-- Modal -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle">Find Category ID</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <p class="h6">Category name can be found when searching for a product on <a href="http://ebay.co.uk" alt="link to ebay">eBay</a>.</p>
                <img class="h-50"src="{{asset('img/findCategory.PNG')}}" alt="find category instruction">
                <p class="h6 m-2">Simply add full category name of your query into the box below to find it's ID</p>
                <input class="input-group-text" type="search" id="categoryFinder" onkeyup="findCatByText()">
                <div class="list-group list-group-categories" id="categoryList">
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-primary" data-dismiss="modal">Save changes</button>
            </div>
          </div>
        </div>
      </div>
      <script>    
            var categories = {!! json_encode($categories, JSON_HEX_TAG) !!};
            function findCatByText(text){
                var list = document.getElementById("categoryList");
                list.innerHTML='';
                var x = document.getElementById("categoryFinder").value;
                for (var i=0;i<categories.length;i++){
                    if (categories[i].category_name.toLowerCase().includes(x.toLowerCase())){
                        category={'id':categories[i].category_id,'name':categories[i].category_name};
                        var btn=document.createElement('BUTTON');
                        var text=document.createTextNode(categories[i].category_name);
                        btn.className="list-group-item list-group-item-action";
                        btn.dataset.id=categories[i].category_id;
                        btn.dataset.dismiss='modal';
                        btn.addEventListener('click',function(){
                            id=this.dataset.id;
                            document.getElementById("categoryId").value=id;
                        })
                        btn.appendChild(text);
                        list.appendChild(btn);

                    }
                }
                
            }
        </script>
@endsection