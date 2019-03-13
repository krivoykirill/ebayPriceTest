@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card d-flex">
                <div class="card-header row bg-light shadow p-3 bg-white rounded">
                     <p class="col-md-2 my-auto text-dark font-weight-bold p-2">Home Page</p>
                     <div class="ml-auto d-flex">
                            <a class="btn btn-success ebay-btn mx-1" href="{{url('add')}}">Add Query</a>
                            <button id="deleteBtn" class="btn btn-primary ebay-btn mx-1">Delete selected: <span id="counter">0</span></button> 
                     </div>
                     
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <div class="row justify-content-center">
                        @forelse ($queries as $query)
                            <div class="card mr-2 mb-2 text-center bg-white shadow border-radius-30" style="width: 15rem;">
                                <div class="card-body bg-white border-radius-30">
                                    <h5 class="card-title">
                                    <input type="checkbox" onclick="checkCheckboxes()" name="item" data-id="{{$query->id}}"/>
                                        @if ($query->buying_type=='Auction')
                                            <label for="item_{{$query->id}}" class="{{'font-weight-bold'}} ebay-color-blue">{{'Auction'}}</label>
                                        @elseif ($query->buying_type=='FixedPrice')
                                            <label for="item_{{$query->id}}" class="{{'font-weight-bold'}} ebay-color-red">{{'Buy It Now'}}</label>
                                        @else ($query->buying_type=='Both')
                                            <label for="item_{{$query->id}}" class="{{'font-weight-bold'}} ebay-color-dblue">{{'All Types'}}</label>
                                        @endif
                                    </h5>
                                    <p class="card-text">{{$query->keywords}}</p>
                                    <img class="rounded inline-block img-fluid mb-2" src="{{$query->thumbnail}}" alt="thumbnail" style="max-height:65px;"/>
                                    <br/>
                                    
                                    @if ($query->checked==true)
                                        <a href="{{url('view/'.$query->id)}}" class="{{'btn btn-lg btn-success'}} ebay-btn">Open Dashboard</a>
                                    @else 
                                        <a href="{{url('view/'.$query->id)}}" class="{{'btn btn-lg btn-danger disabled'}} font-weight-bold border-radius-30">
                                            
                                            Preparing
                                        </a> 
                                        
                                    @endif
                                        
                                </div>
                            </div>
                        @empty
                            <div class="card mr-2 d-flex align-content-center flex-wrap mb-2 text-center bg-white p-5" style="width: 15rem;">
                            
                                
                                <p>No queries yet. Please add a query to proceed</p>
                        
                    
                            </div>                       
                        @endforelse
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var csrf = '{{csrf_token()}}';
    var idArr = [];
    var i;
    var counter = document.getElementById("counter");
    var btn = document.getElementById("deleteBtn");
    var ticks = document.getElementsByName("item");
    var formData = new FormData();

    function checkCheckboxes(){
        var countVal=0;
        idArr=[];
        for(i=0;i<ticks.length;i++){
            if(ticks[i].checked){
                idArr.push(ticks[i].dataset.id);
                countVal++;
            }
        }
        counter.innerHTML=countVal;
    }
    
    btn.addEventListener('click',()=>{
        fetch("{{url('/delete')}}", {
            method:'post',
            credentials:"same-origin",
            headers: {
                'Accept':'application/json',
                'Content-Type':'application/json',
                "X-Requested-With": "XMLHttpRequest",
                "X-CSRF-Token":csrf
            },
            body:JSON.stringify({
                ids:idArr
            })
        })
        .then((response)=>{
            console.log(response);
            window.location.reload();
        });
    });


</script>
@endsection
