@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card d-flex">
                <div class="card-header row bg-light shadow p-3 bg-white rounded">
                     <p class="col-md-2 my-auto text-dark font-weight-bold p-2">Home Page</p>
                     <div class="ml-auto d-flex row pr-2">
                            <p class="my-auto text-dark font-weight-bold mx-1 my-auto pt-1">Items selected: <span id="counter">0</span></p>
                            <a class="btn btn-success ebay-btn mx-1 mt-1" href="{{url('add')}}">Add Query</a>
                            <button id="refreshBtn" class="btn btn-secondary mx-1 mt-1">Update selected</button> 
                            <button id="deleteBtn" class="btn btn-secondary mx-1 mt-1">Delete selected</button> 
                            
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
                            <a 
                            @if (($query->checked==true)&&($query->buying_type=='Auction'))
                                class="d-flex card btn card-btn ebay-bg-blue rounded m-2 border-custom" href="{{url('/view/'.$query->id)}}"
                            @elseif (($query->checked==true)&&($query->buying_type=='FixedPrice'))
                                class="d-flex card btn card-btn ebay-bg-dblue rounded m-2 border-custom" href="{{url('/view/'.$query->id)}}"
                            @elseif (($query->checked==true)&&($query->buying_type=='All'))
                                class="d-flex card btn card-btn ebay-bg-red rounded m-2 border-custom" href="{{url('/view/'.$query->id)}}"
                            @elseif($query->checked==false)
                                class="d-flex card btn card-btn ebay-bg-gray rounded m-2 border-custom btn-disabled text-disabled pointer-disabled" href="javascript:void(0)"
                            @endif
                                style="width: 13.5rem;" alt="query link">
                                <div class="custom-control custom-checkbox p-0 pl-4 text-left">
                                    <input type="checkbox" class="custom-control-input check" onclick="checkCheckboxes()" data-id="{{$query->id}}" id="customCheck{{$query->id}}" name="example1"/>
                                    <label for="customCheck{{$query->id}}" style="color:rgba(0,0,0,0)!important;" class="font-weight-bold ebay-color-blue custom-control-label">item</label>
                                    
                                </div>
                                <div class="card">
                                    <div class="card-body py-2" >
                                        @if ($query->buying_type=='Auction')
                                            <p class="{{'font-weight-bold'}} h5">Auction</p>
                                        @elseif ($query->buying_type=='FixedPrice')
                                            <p class="{{'font-weight-bold'}} h5">Buy It Now</p>
                                        @else ($query->buying_type=='Both')
                                            <p class="{{'font-weight-bold'}} h5">All Types</p>
                                        @endif
                                        <img class="rounded inline-block img-fluid mb-2 rounded img-thumbnail shadow-sm" src="{{$query->thumbnail}}" alt="thumbnail" style="max-height:80px;"/>
                                    
                                        
                                        <p class="card-text">{{$query->keywords}}</p>

                                        
                                        
                                        <br/>
                                        
                                    </div>
                                    
                                </div>
                                
                                @if ($query->checked==true)
                                    <p class="h6 font-weight-thin secondary-text" style="position:absolute;bottom:0;color:rgba(255,255,255,0.75);">Updated: <span class="font-weight-bold text-white last-updated" id="counter_{{$query->id}}">{{$query->last_check}}</span></p>
                                @else
                                    <div class="d-flex justify-content-center">
                                        <p class="h6 m-1">Preparing</p>
                                        <div class="spinner-border" role="status">
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                    </div>
                                    
                                    
                                    
                                    
                                @endif
                            </a>
                        @empty
                            <div class="alert alert-info" role="alert">
                                You have no saved queries yet. <a href="{{url('/add')}}" class="alert-link"> Add a query</a> or press the green button above to continue.
                              </div>                     
                        @endforelse
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
<script>
    var csrf = '{{csrf_token()}}';
    var idArr = [];
    var i;
    var counter = document.getElementById("counter");
    var btn = document.getElementById("deleteBtn");
    var ticks = document.getElementsByClassName("check");
    var dates = document.getElementsByClassName("last-updated");
    var formData = new FormData();
    var updBtn=document.getElementById("refreshBtn");
    function checkCheckboxes(){
        var countVal=0;
        idArr=[];
        for(i=0;i<ticks.length;i++){
            
            if(ticks[i].checked){
                console.log(ticks[i]);
                idArr.push(ticks[i].dataset.id);
                countVal++;
            }
        }
        
        counter.innerHTML=countVal;
    }
    for (let date of dates) {
        date.innerHTML=moment(date.innerHTML).fromNow();
        
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
            
            window.location.reload();
        });
    });

    updBtn.addEventListener('click',()=>{
        idArr.forEach(function (id){
            fetch("{{url('/refresh')}}"+"/"+id);
        });

        window.location.reload();
        
    });


</script>
@endsection
